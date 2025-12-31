<?php

use App\Http\Controllers\Api\Resident\Maintenance\MaintenanceController;
use App\Http\Controllers\Api\V1\Resident\Neighbor\NeighborController;
use Illuminate\Support\Facades\Route;

Route::apiResource('maintenance', MaintenanceController::class); //only create & list
Route::apiResource('neighbor', NeighborController::class);
