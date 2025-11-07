<?php

use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/auth')->name('api.auth.')->group(function () {
    Route::middleware('guest:api')->group(function () {
        Route::post('/register', [RegisterController::class, 'register']);
        Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
        Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
        Route::post('/register/personal-info', [RegisterController::class, 'RegisterPersonalInfo']);  // register & password set.
    });

    // Authenticated routes - Accessible only by authenticated users
    Route::middleware('auth:api')->group(function () {});
});
