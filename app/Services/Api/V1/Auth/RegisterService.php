<?php
namespace App\Services\Api\V1\Auth;

use App\Helpers\Helper;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterService
{

    /**
     * Handle the registration process (NO TOKEN HERE)
     * Only number
     * No token  create .
     */
    public function register(array $data): array
    {
        $existingUser = User::where('phone', $data['phone'])->first();

        if ($existingUser) {
            // If user exists but not verified, update OTP
            if (! $existingUser->phone_verified_at) {
                $otp          = "1234"; // rand(1000, 9999) for production
                $otpExpiresAt = Carbon::now()->addMinutes(60);

                $existingUser->update([
                    'otp'            => $otp,
                    'otp_expires_at' => $otpExpiresAt,
                ]);

                // Send OTP to phone number
                // Log::info("OTP for {$data['phone']}: {$otp}");
                return [
                    'user'   => $existingUser,
                    'is_new' => false,
                ];
            }
            throw new Exception('This phone number is already registered and verified.');
        }

        try {
            $otp          = "1234"; // rand(1000, 9999) for production
            $otpExpiresAt = Carbon::now()->addMinutes(60);

            $user = User::create([
                'phone'          => $data['phone'],
                // 'role' => $data['role'] ?? 'user',
                'otp'            => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);

            // Send OTP to phone number
            Log::info("OTP for {$data['phone']}: {$otp}");

            return [
                'user'   => $user,
                'is_new' => true,
            ];
        } catch (Exception $e) {
            throw new Exception('User registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Verify OTP (TOKEN CREATED HERE)
     * otp_expires_at check time
     * phone_verified_at  Carbon::now()
     */
    public function verifyOtp(array $data): array
    {
        $user = User::where('phone', $data['phone'])->first();
        if (! $user) {
            throw new Exception('User not found.');
        }
        //check
        if ($user->otp !== $data['otp']) {
            throw new Exception('Invalid OTP.');
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) { //otp_expires_at time check
            throw new Exception('OTP has expired.');
        }

        // Mark phone as verified
        $user->update([
            'phone_verified_at'    => Carbon::now(),
            'otp'                  => null,
            'otp_expires_at'       => null,
            'is_otp_verified'      => true,
            'terms_and_conditions' => true,
        ]);

        return [
            'user' => $user,
        ];
    }

    /**
     * Resend OTP
     * new otp send
     * Phone number is already verified.
     */
    public function resendOtp(array $data): array
    {
        $user = User::where('phone', $data['phone'])->first();
        if (! $user) {
            throw new Exception('User not found.');
        }

        if ($user->phone_verified_at) {
            throw new Exception('Phone number is already verified.');
        }

        $otp          = "1234"; // rand(1000, 9999) for production
        $otpExpiresAt = Carbon::now()->addMinutes(60);

        $user->update([
            'otp'             => $otp,
            'otp_expires_at'  => $otpExpiresAt,
            'is_otp_verified' => false,
        ]);

        // Send OTP to phone number
        Log::info("Resend OTP for {$data['phone']}: {$otp}");

        return [
            'user' => $user,
        ];
    }

    /**
     * storePersonalInfoService
     * PersonalInfo & password set
     * Handle avatar upload ||  cover photo upload
     * JWT Token Create.
     */
    public function storePersonalInfoService(array $data): array
    {
        $user = User::where('phone', $data['phone'])->firstOrFail(); // Find user by phone

        // Handle avatar upload ||  cover photo upload
        if (! empty($data['avatar'])) {
            $avatarName     = time() . '_' . $data['avatar']->getClientOriginalName();
            $data['avatar'] = Helper::fileUpload($data['avatar'], 'profile_picture', $avatarName);
        }
        if (! empty($data['cover_photo'])) {
            $coverName           = time() . '_' . $data['cover_photo']->getClientOriginalName();
            $data['cover_photo'] = Helper::fileUpload($data['cover_photo'], 'cover_photo', $coverName);
        }

        // Update user personal info & password
        $user->update([
            'name'        => $data['name'] ?? $user->name,
            'email'       => $data['email'] ?? $user->email,
            'address'     => $data['address'] ?? $user->address,
            'password'    => bcrypt($data['password']),
            'avatar'      => $data['avatar'] ?? $user->avatar,
            'cover_photo' => $data['cover_photo'] ?? $user->cover_photo,
        ]);

        try {
            $token = JWTAuth::attempt(['phone' => $data['phone'], 'password' => $data['password']]);
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
