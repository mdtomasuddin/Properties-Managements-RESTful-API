<?php

namespace App\Http\Controllers\Web\V1\User;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileInfoController
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('backend.layouts.user.profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id  ADMIN personal information show page.
     * Password update and admin profile update
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name'     => 'nullable|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $user->id,
                'phone'    => 'nullable|string|max:20',
                'address'  => 'nullable|string|max:500',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();

            // handle avatar
            if ($request->hasFile('avatar')) {
                if (! empty($user->avatar)) {
                    $path = ltrim(parse_url($user->avatar, PHP_URL_PATH), '/');
                    Helper::fileDelete($path);
                }
                $file           = $request->file('avatar');
                $imageName      = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $data['avatar'] = Helper::fileUpload($file, 'Avatar', $imageName);
            }

            // handle password
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            //response
            return redirect()->back()->with('t-success', 'Profile Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
