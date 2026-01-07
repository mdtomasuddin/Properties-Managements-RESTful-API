<?php

use App\Http\Controllers\Api\Resident\Maintenance\MaintenanceController;
use App\Http\Controllers\Api\V1\Resident\Neighbor\NeighborController;
use App\Http\Controllers\Api\V1\Resident\TotalDue\TotalDueController;
use Illuminate\Support\Facades\Route;

Route::apiResource('maintenance', MaintenanceController::class); //only create & list
Route::apiResource('neighbor', NeighborController::class);
Route::apiResource('total-due', TotalDueController::class);
