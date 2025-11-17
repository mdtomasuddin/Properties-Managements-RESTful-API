<?php

use App\Http\Controllers\Api\Resident\Maintenance\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::apiResource('maintenance', MaintenanceController::class);  //only create & list
