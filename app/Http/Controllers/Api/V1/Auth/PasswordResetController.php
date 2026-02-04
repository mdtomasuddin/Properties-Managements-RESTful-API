<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Api\Auth\OTPRequest;
use App\Http\Requests\Api\Auth\OTPVerificationRequest;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use App\Services\Api\V1\Auth\PasswordResetService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class PasswordResetController
{
    private PasswordResetService $passwordResetService;
    private Helper $helper;

    /**
     * Construct a new instance of the PasswordResetController.
     * @param PasswordResetService $passwordResetService The service to use for password reset functionality.
     * @param Helper $helper The helper to use for general functionality.
     */
    public function __construct(PasswordResetService $passwordResetService, Helper $helper)
    {
        $this->passwordResetService = $passwordResetService;
        $this->helper               = $helper;
    }

    /**
     * Send an OTP code to the user's phone number.
     * @param OTPRequest $request
     * @return JsonResponse
     * @throws Exception
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
     * Resend a new OTP code to the user's phone number.
     * Phone number is already verified.
     * @param OTPRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function otpResend(OTPRequest $request): JsonResponse
    {
        try {
            $phone = $request->input('phone');

            $response = $this->passwordResetService->otpResend($phone);

            return $this->helper->jsonResponse(true, $response['message'], 200, ['otp' => $response['otp']]);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, $e->getMessage(), 400);
        }
    }



    /**
     * Verify the provided OTP code.
     * @param OTPVerificationRequest $request
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
     * Resets the user's password.
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws Exception
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

    /**
     * Change the user's password
     * old_password & confirmed
     * changePassword login user
     */
    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'password'     => 'required|string|min:6|confirmed',
        ]);
        // dd($validatedData);
        try {
            $user = auth('api')->user();
            if (! Hash::check($validatedData['old_password'], $user->password)) {
                return $this->helper->jsonResponse(false, 'Current password is incorrect', 422);
            }

            // Update the password
            $user->update([
                'password' => bcrypt($validatedData['password']),
            ]);
            return $this->helper->jsonResponse(true, 'Password updated successfully', 200);
        } catch (Exception $e) {
            return $this->helper->jsonResponse(false, 'something went wrong', 403);
        }
    }
}
