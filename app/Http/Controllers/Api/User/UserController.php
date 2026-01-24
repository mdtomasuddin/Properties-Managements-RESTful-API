<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{

    /**
     * Delete user account
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount()
    {
        try {
            $user = Auth::user();
            $user->delete();
            return response()->json(['message' => 'Account deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete account'], 500);
        }
    }
}
