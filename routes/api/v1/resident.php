<?php

use App\Http\Controllers\Api\Resident\Maintenance\MaintenanceController;
use App\Http\Controllers\Api\V1\Resident\MyProperty\MyPropertyController;
use App\Http\Controllers\Api\V1\Resident\Neighbor\NeighborController;
use App\Http\Controllers\Api\V1\Resident\Payment\PaymentController;
use App\Http\Controllers\Api\V1\Resident\Rule\RulesRegulationController;
use App\Http\Controllers\Api\V1\Resident\Support\SupportController;
use App\Http\Controllers\Api\V1\Resident\TotalDue\TotalDueController;
use Illuminate\Support\Facades\Route;

Route::apiResource('maintenance', MaintenanceController::class); //only create & list
Route::apiResource('neighbor', NeighborController::class);
Route::apiResource('total-due', TotalDueController::class);
Route::get('/due-invoice/{id}', [TotalDueController::class, 'DueInvoice']); //due invoice
Route::apiResource('payments', PaymentController::class);                   //payment all due & payment
Route::apiResource('my-property', MyPropertyController::class);
Route::apiResource('rule-regulations', RulesRegulationController::class);
Route::apiResource('support', SupportController::class);
