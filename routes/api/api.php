<?php

use App\Http\Controllers\Api\Faq\FaqController;
use App\Http\Controllers\Api\V1\Firebase\FirebaseTokenController;
use Illuminate\Support\Facades\Route;

/**
 * V1 API Routes:
 */
require 'v1/auth/auth.php';                          // All Auth routes
Route::get('/faq', [FaqController::class, 'index']); //faq

//only property API Endpoint
Route::middleware(['auth:api', 'property_check'])->group(function () {
    require 'v1/property.php';
});

//only resident API Endpoint
Route::middleware(['auth:api', 'resident_check'])->group(function () {
    require 'v1/resident.php';
});

//property && resident Both API Endpoint &&  *property_or_resident_check
Route::middleware(['auth:api', 'property_or_resident_check'])->group(function () {
    require 'v1/propertyresident.php';
});

// Firebase Token Module
Route::post("firebase/token/add", [FirebaseTokenController::class, "store"]);
Route::post("firebase/token/get", [FirebaseTokenController::class, "getToken"]);
Route::post("firebase/token/delete", [FirebaseTokenController::class, "deleteToken"]);
