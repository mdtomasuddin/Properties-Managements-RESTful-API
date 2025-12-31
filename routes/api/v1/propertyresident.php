<?php

use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Emergency\EmergencyController;
use App\Http\Controllers\Api\V1\Property\SecurityContact\SecurityContactController;
use Illuminate\Support\Facades\Route;

Route::apiResource('category', CategoryController::class);                //'payment', 'emergency', 'maintenance', 'announcement', 'document'=section
Route::apiResource('emergency', EmergencyController::class);              //Create & list
Route::apiResource('security-contact', SecurityContactController::class); //security contact CRUD
