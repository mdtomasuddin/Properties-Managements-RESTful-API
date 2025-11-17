<?php

use App\Http\Controllers\Api\Faq\FaqController;
use Illuminate\Support\Facades\Route;

/**
 * V1 API Routes:
 */
require 'v1/auth/auth.php';                          // All Auth routes
Route::get('/faq', [FaqController::class, 'index']); //faq

//only property API Endpoint
Route::middleware(['auth:api', 'property_check'])->group(function () {
    require 'v2/property.php';
});

//only resident API Endpoint
Route::middleware(['auth:api', 'resident_check'])->group(function () {
    require 'v3/resident.php';
});

//property && resident Both API Endpoint &&  *property_or_resident_check
Route::middleware(['auth:api', 'property_or_resident_check'])->group(function () {
    require 'v4/propertyresident.php';
});
