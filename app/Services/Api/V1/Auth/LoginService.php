<?php

namespace App\Services\Api\V1\Auth;

use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{

    /**
     * Handle the login process.
     */
    public function login(array $data): array
    {
        $token = JWTAuth::attempt([
            'phone'    => $data['phone'],
            'password' => $data['password'],
        ]);

        if (! $token) {
            throw new Exception('Invalid credentials');
        }

        return [
            'user'  => Auth::user(),
            'token' => $token,
        ];
    }
}
