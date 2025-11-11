<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Api\Auth\OTPRequest;
use App\Http\Requests\Api\Auth\OTPVerificationRequest;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use App\Services\Api\V1\Auth\PasswordResetService;
use Exception;
use Illuminate\Http\JsonResponse;

class PasswordResetController
{
    private PasswordResetService $passwordResetService;
    private Helper $helper;

    public function __construct(PasswordResetService $passwordResetService, Helper $helper)
    {
        $this->passwordResetService = $passwordResetService;
        $this->helper               = $helper;
    }

    /**
     * Send OTP code to the user's phone for password reset.
     */
    public function sendOtpToPhone(OTPRequest $request): JsonResponse
    {
        try {
            $phone = $request->input('phone');

            $response = $this->passwordResetService->sendOtpToPhone($phone);

            return $this->helper->jsonResponse(true, $response['message'], 200, ['otp' => $response['otp']]);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, $e->getMessage(), 400);
        }
    }



    /**
     * Verify the provided OTP code.
     */
    public function verifyOTP(OTPVerificationRequest $request): JsonResponse
    {
        try {
            $phone    = $request->header('phone') ?: $request->input('phone');
            $otp      = $request->input('otp');
            $response = $this->passwordResetService->verifyOtp([
                'phone' => $phone,
                'otp'   => $otp,
            ]);

            return $this->helper->jsonResponse(true, $response['message'], 200);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, $e->getMessage(), 400);
        }
    }




    /**
     * Reset the user's password.
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        try {
            $phone = $request->header('phone') ?: $request->input('phone');
            $password = $request->input('password');

            $response = $this->passwordResetService->resetPassword([
                'phone'    => $phone,
                'password' => $password,
            ]);

            return $this->helper->jsonResponse(true, $response['message'], 200);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, $e->getMessage(), 400);
        }
    }
}
