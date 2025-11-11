<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/auth')->name('api.auth.')->group(function () {
    Route::middleware('guest:api')->group(function () {
        Route::post('/register', [RegisterController::class, 'register']);
        Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
        Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
        Route::post('/register/personal-info', [RegisterController::class, 'RegisterPersonalInfo']); // register & password set.
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Authenticated routes - Accessible only by authenticated users
    Route::middleware('auth:api')->group(function () {
        Route::controller(LogoutController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });
    });

    Route::controller(PasswordResetController::class)->group(function () {
        Route::post('/forget-password', 'sendOtpToPhone');
        Route::post('/verify-otp', 'verifyOTP');
        Route::post('/reset-password', 'resetPassword');
        Route::post('/update-password', 'changePassword')->middleware('auth:api');
    });
});
