<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/v1/auth')->name('api.auth.')->group(function () {

    // Authenticated routes - Accessible only by authenticated users
    Route::middleware('auth:api')->group(function () {});
});
