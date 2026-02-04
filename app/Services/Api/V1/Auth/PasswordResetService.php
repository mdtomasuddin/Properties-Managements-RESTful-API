<?php
namespace App\Services\Api\V1\Auth;

use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{

    /**
     * Sends a random OTP code to the user's phone number.
     * @param string $phone The user's phone number.
     * @return array An array containing the OTP code and a success message.
     * @throws Exception
     */
    public function sendOtpToPhone(string $phone): array
    {
        try {
            $user = User::where('phone', $phone)->first();
            if (! $user) {
                throw new Exception('No account associated with this phone number.');
            }

            $otp = "1234";
            // $otp = rand(1000, 9999);
            // You can later integrate SMS sending logic here.

            //OTP MODEL
            $otpRecord = OTP::updateOrCreate(
                ['phone' => $phone],
                [
                    'user_id' => $user->id,
                    'otp'     => $otp,
                    'status'  => true,
                ]
            );
            return [
                'message' => 'OTP Code Sent Successfully',
                'otp'     => $otpRecord->otp,
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
