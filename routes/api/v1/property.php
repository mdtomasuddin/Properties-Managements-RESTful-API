<?php

use App\Http\Controllers\Api\PaymentRequest\PaymentRequestController;
use App\Http\Controllers\Api\PropertyResident\PropertyResidentController;
use App\Http\Controllers\Api\Property\Announcement\AnnouncementController;
use App\Http\Controllers\Api\Property\Document\DocumentController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\ResidentController;
use App\Http\Controllers\Api\V1\Plan\PlanController;
use App\Http\Controllers\Api\V1\Property\HomePage\HomePageController;
use App\Http\Controllers\Api\V1\Property\PaymentReminder\PaymentReminderController;
use App\Http\Controllers\Api\V1\Property\SecurityContact\SecurityContactController;
use Illuminate\Support\Facades\Route;

Route::apiResource('property', PropertyController::class);                  //CRUD
Route::apiResource('residents', ResidentController::class);                 //CRUD
Route::apiResource('property-resident', PropertyResidentController::class); //property owner first added =>(property|resident|FinancialAccount)
Route::apiResource('documents', DocumentController::class);
Route::apiResource('announcement', AnnouncementController::class);      //CRUD
Route::apiResource('payment-request', PaymentRequestController::class); //C

//get residents by property
Route::get('property-by-resident/{id}', [PropertyController::class, 'getResidentsByProperty']);
Route::apiResource('plans', PlanController::class);                       // plans get.
Route::apiResource('security-contact', SecurityContactController::class); //security contact CRUD
Route::apiResource('payment-reminder', PaymentReminderController::class); //payment reminder

//home page
Route::apiResource('property-homepage', HomePageController::class); //home-page
