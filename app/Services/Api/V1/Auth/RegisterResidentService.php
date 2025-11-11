<?php

namespace App\Services\Api\V1\Auth;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterResidentService
{
    /**
     * RegisterResidentInfo ||   JWT Token Create.
     * Handle avatar upload ||  cover photo upload
     * Store personal information, password, handle uploads, and create JWT token.
     */
    public function RegisterResidentInfo(array $data): array
    {
        $user = User::where('phone', $data['phone'])->firstOrFail();


        // Handle avatar upload
        if (! empty($data['avatar'])) {
            $avatarName     = time() . '_' . $data['avatar']->getClientOriginalName();
            $data['avatar'] = Helper::fileUpload($data['avatar'], 'profile_picture', $avatarName);
        }

        // Handle cover photo upload
        if (! empty($data['cover_photo'])) {
            $coverName           = time() . '_' . $data['cover_photo']->getClientOriginalName();
            $data['cover_photo'] = Helper::fileUpload($data['cover_photo'], 'cover_photo', $coverName);
        }

        // Handle NID files (array)
        if (! empty($data['nid_file']) && is_array($data['nid_file'])) {
            $uploadedNidFiles = [];
            foreach ($data['nid_file'] as $file) {
                $nidName            = time() . '_' . $file->getClientOriginalName();
                $uploadedNidFiles[] = Helper::fileUpload($file, 'nid_files', $nidName);
            }
            $data['nid_file'] = $uploadedNidFiles;
        }

        // Update user personal info & password
        $user->update([
            'name'        => $data['name'] ?? $user->name,
            'email'       => $data['email'] ?? $user->email,
            'address'     => $data['address'] ?? $user->address,
            'password'    => bcrypt($data['password']),
            'avatar'      => $data['avatar'] ?? $user->avatar,
            'cover_photo' => $data['cover_photo'] ?? $user->cover_photo,
            'nid_file'    => $data['nid_file'] ?? $user->nid_file,
        ]);

        // Create JWT token
        try {
            $token = JWTAuth::attempt([
                'phone'    => $data['phone'],
                'password' => $data['password'],
            ]);

            if (! $token) {
                throw new Exception('Authentication failed.');
            }
        } catch (JWTException $e) {
            throw new Exception('Could not create token: ' . $e->getMessage());
        }

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }
}
