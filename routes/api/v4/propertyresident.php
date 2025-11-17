<?php

use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Emergency\EmergencyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('category', CategoryController::class); //'payment', 'emergency', 'maintenance', 'announcement', 'document'=section
Route::apiResource('emergency', EmergencyController::class); //Create & list
