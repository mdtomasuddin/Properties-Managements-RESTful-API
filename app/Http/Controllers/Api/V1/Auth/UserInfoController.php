<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserInfoController
{
    /**
     * Get user information.
     */
    public function userInformation()
    {
        // dd(auth('api')->user()); // Remove this for production
        return Helper::jsonResponse(true, 'User details fetched successfully', 200, auth('api')->user());
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => 'nullable|string|max:200',
            'address'                 => 'nullable|string|max:500',
            'invitation_code'         => 'nullable|string|max:500',
            'avatar'                  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'cover_photo'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'nid_file'                => 'nullable|array',
            'nid_file.*'              => 'image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'organization'            => 'nullable|string|max:255',
            'designation'             => 'nullable|string|max:255',
            'national_id'             => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return Helper::jsonResponse(false, $message, 422);
        }

        try {
            $user          = auth('api')->user();
            $validatedData = $validator->validated();

            // Handle avatar image update
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    $parsedUrl    = parse_url($user->avatar, PHP_URL_PATH);
                    $oldImagePath = ltrim($parsedUrl, '/');
                    Helper::fileDelete($oldImagePath);
                }
                $uploadedAvatar          = Helper::fileUpload($request->file('avatar'), 'profile_picture', $request->file('avatar'));
                $validatedData['avatar'] = $uploadedAvatar;
            }

            // Handle cover photo update
            if ($request->hasFile('cover_photo')) {
                if ($user->cover_photo) {
                    $parsedUrl    = parse_url($user->cover_photo, PHP_URL_PATH);
                    $oldImagePath = ltrim($parsedUrl, '/');
                    Helper::fileDelete($oldImagePath);
                }
                $uploadedCover                = Helper::fileUpload($request->file('cover_photo'), 'cover_photo', $request->file('cover_photo'));
                $validatedData['cover_photo'] = $uploadedCover;
            }

            // Handle multiple NID files
            if ($request->hasFile('nid_file')) {
                if ($user->nid_file && is_array($user->nid_file)) {
                    foreach ($user->nid_file as $oldFile) {
                        $oldImagePath = ltrim(parse_url($oldFile, PHP_URL_PATH), '/');
                        Helper::fileDelete($oldImagePath);
                    }
                }
                $uploadedNidFiles = [];
                foreach ($request->file('nid_file') as $file) {
                    $uploadedNidFiles[] = Helper::fileUpload($file, 'nid_file');
                }
                $validatedData['nid_file'] = $uploadedNidFiles;
            }

            $user->update($validatedData);
            return Helper::jsonResponse(true, 'Profile updated successfully', 200, $user);
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Something went wrong', 500, $e->getMessage());
        }
    }
}
