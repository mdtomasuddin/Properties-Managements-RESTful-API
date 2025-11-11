<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Api\Auth\RegisterPersonalInfoRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResendOtpRequest;
use App\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Services\Api\V1\Auth\RegisterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController
{

    private RegisterService $registerService;

    /**
     * call Register Service 3 function ->
     * register ->call
     * verifyOtp ->call
     * resendOtp->call
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * Handle user registration (NO TOKEN)
     * Only Phone number Create User.
     * Call RegisterService &&  RegisterRequest
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data   = $request->validated();
            $result = $this->registerService->register($data);

            return response()->json([
                'status'  => true,
                'message' => $result['is_new'] ? 'Registration successful. OTP sent to your phone.' : 'OTP resent to your phone.',
                'code'    => 201,
                'data'    => [
                    'phone'          => $result['user']->phone,
                    'otp_expires_in' => '60 minutes',
                ],
            ], 201);
        } catch (Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Verify OTP (TOKEN CREATED HERE)
     * Create JWT token ONLY after successful OTP verification
     * status  200
     */
    public function verifyOtpToPhone(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $data   = $request->validated();
            $result = $this->registerService->verifyOtpToPhone($data);

            $token = JWTAuth::fromUser($result['user']); // Create JWT token ONLY after successful OTP verification

            return response()->json([
                'status'     => true,
                'message'    => 'Phone number verified successfully.',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => [
                    'id'                => $result['user']->id,
                    'phone'             => $result['user']->phone,
                    'role'              => $result['user']->role,
                    'phone_verified_at' => $result['user']->phone_verified_at,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * user ResendOtpRequest call
     * RegisterService call  resend function .
     * service call
     */
    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        try {
            $data   = $request->validated();
            $result = $this->registerService->resendOtp($data);

            return response()->json([
                'status'  => true,
                'message' => 'OTP resent successfully.',
                'code'    => 200,
                'data'    => [
                    'phone'          => $result['user']->phone,
                    'otp_expires_in' => '60 minutes',
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Resend OTP error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store RegisterPersonalInfo
     * RegisterPersonalInfoRequest .
     * storePersonalInfoService service call
     */
    public function RegisterPersonalInfo(RegisterPersonalInfoRequest $request): JsonResponse
    {
        try {
            $data   = $request->validated();
            $result = $this->registerService->storePersonalInfoService($data);

            return response()->json([
                'status'     => true,
                'message'    => 'Personal info & registered saved  successfully.',
                'code'       => 200,
                'role'       => $result['user']->role,
                'token_type' => 'bearer',
                'token'      => $result['token'], // JWT token
                'data'       => [
                    'id'          => $result['user']->id,
                    'name'        => $result['user']->name,
                    'email'       => $result['user']->email,
                    'phone'       => $result['user']->phone,
                    'address'     => $result['user']->address,
                    'avatar'      => $result['user']->avatar,
                    'cover_photo' => $result['user']->cover_photo,
                ],
            ], 200);
        } catch (Exception $e) {
            // Log::error('Register personal info error: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to save personal info.', 400, [
                $e->getMessage(),
            ]);
        }
    }
}
