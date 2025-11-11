<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\LoginResource;
use App\Services\Api\V1\Auth\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController
{
    private LoginService $loginService;
    private Helper $helper;

    public function __construct(LoginService $loginService, Helper $helper)
    {
        $this->loginService = $loginService;
        $this->helper       = $helper;
    }

    /**
     * Handle user login.
     * loginService call
     * LoginResource call
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $result      = $this->loginService->login($credentials);

            $user  = $result['user'];
            $token = $result['token'];

            if (! empty($user->otp_expires_at)) {
                return $this->helper->jsonResponse(false, 'A verification  has been sent. Please check your inbox.', 403);
            }

            return response()->json([
                'status'     => true,
                'message'    => 'User logged in successfully.',
                'code'       => 200,
                'role'       => $user->role,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => new LoginResource($user), //user info update
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            $status = $e->getMessage() === 'Unauthorized' ? 401 : 500;
            return $this->helper->jsonResponse(
                false,
                $status === 401 ? 'Unauthorized' : 'An error occurred during login.',
                $status,
                ['error' => $e->getMessage()],
                $status
            );
        }
    }
}
