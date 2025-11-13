<?php

use App\Http\Controllers\Api\Property\PropertyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('property', PropertyController::class); //CRUD
