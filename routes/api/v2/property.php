<?php

use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\ResidentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('property', PropertyController::class); //CRUD
Route::apiResource('resident', ResidentController::class); //C
