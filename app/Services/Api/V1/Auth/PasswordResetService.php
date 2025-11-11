<?php

namespace App\Services\Api\V1\Auth;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    /**
     * Send an OTP to the provided Phone.
     */
    public function sendOtpToPhone(string $phone): array
    {
        try {
            $user = User::where('phone', $phone)->first();
            if (! $user) {
                throw new Exception('Invalid phone number');
            }

            $otp = "1234";

            // $otp = rand(1000, 9999);
            // You can later integrate SMS sending logic here.
            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            return [
                'message' => 'OTP Code Sent Successfully',
                'otp'     => $otp,
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify the provided OTP code.
     */
    public function verifyOtp(array $data): array
    {
        try {
            $user = User::where('phone', $data['phone'])
                ->where('otp', $data['otp'])
                ->where('otp_expires_at', '>=', Carbon::now())
                ->first();
            // dd($user);

            if (! $user) {
                throw new Exception('Invalid OTP Code');
            }

            $user->update([
                'otp_verified_at'      => Carbon::now(),
                'otp'                  => null,
                'otp_expires_at'       => null,
                'is_otp_verified'      => true,
                'terms_and_conditions' => true,
            ]);

            return ['message' => 'OTP Verified Successfully'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(array $data): array
    {
        try {
            $user = User::where('phone', $data['phone'])->first();
            // dd($user);

            if (! $user) {
                throw new Exception('Invalid phone number');
            }
            if (is_null($user->otp_verified_at)) {
                throw new Exception('OTP not verified');
            }

            $user->update([
                'password'        => Hash::make($data['password']),
                'otp_verified_at' => null,
                'otp_expires_at'  => null,
                'otp'             => null,

            ]);

            return ['message' => 'Password Reset Successfully'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
