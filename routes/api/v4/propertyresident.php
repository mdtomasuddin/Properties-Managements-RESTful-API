<?php

use App\Http\Controllers\Api\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('category', CategoryController::class);                  //'payment', 'emergency', 'maintenance', 'announcement', 'document'=section
