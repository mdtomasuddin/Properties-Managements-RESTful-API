<?php

use App\Http\Controllers\Api\PropertyResident\PropertyResidentController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\ResidentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('property', PropertyController::class);                  //CRUD
Route::apiResource('residents', ResidentController::class);                 //CRUD
Route::apiResource('property-resident', PropertyResidentController::class); //property owner first added =>(property|resident|FinancialAccount)
