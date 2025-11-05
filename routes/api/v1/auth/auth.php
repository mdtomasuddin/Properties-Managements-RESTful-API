<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgerPasswordController;
use App\Http\Controllers\Api\V1\Auth\OTPController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/auth')->name('api.auth.')->group(function () {

    // Guest routes - Accessible by unauthenticated users only
    Route::middleware('guest:api')->group(function () {
        // Authentication-related routes
        Route::controller(AuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
        });

        // Password-related routes
        Route::controller(PasswordController::class)->group(function () {
            Route::post('/chage-password', 'changePassword');
        });

        // OTP-related routes
        Route::prefix('/forget-password')->name('forgetpassword.')->controller(OTPController::class)->group(function () {
            Route::post('/otp-send', 'otpSend');
            Route::post('/otp-match', 'otpMatch');
        });

        Route::prefix('/forget-password')->name('forgetpassword.')->controller(ForgerPasswordController::class)->group(function () {
            Route::post('/reset-password', 'resetPassword');
        });

        Route::prefix('/social')->name('social.')->controller(SocialLoginController::class)->group(
            function () {
                Route::post('/login', 'socialLogin');
            }
        );
    });



    // Authenticated routes - Accessible only by authenticated users
    Route::middleware('auth:api')->group(function () {
        // Authentication-related routes
        Route::controller(AuthController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
            Route::post('/refresh', 'refresh')->name('refresh.token');
        });
        // OTP-related routes
        Route::controller(OTPController::class)->group(function () {
            Route::post('/otp-send', 'otpSend')->name('otp.send');
            Route::post('/otp-match', 'otpMatch')->name('otp.match');
        });
    });
});
