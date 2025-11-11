<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\RegisterResidentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/auth')->name('api.auth.')->group(function () {
    Route::middleware('guest:api')->group(function () {
        Route::post('/register', [RegisterController::class, 'register']);
        Route::post('/verify-number', [RegisterController::class, 'verifyOtpToPhone']);
        Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
        Route::post('/register/property-info', [RegisterController::class, 'RegisterPersonalInfo']);         // register & password set property.
        Route::post('/register/resident-info', [RegisterResidentController::class, 'RegisterResidentInfo']); // register & password set resident.
        Route::post('/login', [LoginController::class, 'login']);
    });

    Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:api'); //logout
    Route::controller(PasswordResetController::class)->group(function () {
        Route::post('/forget-password', 'sendOtpToPhone');
        Route::post('/verify-otp', 'verifyOTP');
        Route::post('/reset-password', 'resetPassword');
        Route::post('/update-password', 'changePassword')->middleware('auth:api');
    });
});
