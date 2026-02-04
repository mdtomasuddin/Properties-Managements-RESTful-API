<?php

use App\Http\Controllers\Api\Faq\FaqController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\V1\Firebase\FirebaseTokenController;
use App\Http\Controllers\Api\V1\Messaging\MessageController;
use App\Http\Controllers\Api\V1\Notification\NotificationController;
use App\Http\Controllers\Api\V1\SSLCommerz\SSLCommerzPaymentController;
use Illuminate\Support\Facades\Route;

/**
 * V1 API Routes:
 */
require 'v1/auth/auth.php';                          // All Auth routes
Route::get('/faq', [FaqController::class, 'index']); //faq

//only property API Endpoint
Route::middleware(['auth:api', 'property_check'])->group(function () {
    require 'v1/property.php';
});

//only resident API Endpoint
Route::middleware(['auth:api', 'resident_check'])->group(function () {
    require 'v1/resident.php';
});

//property && resident Both API Endpoint &&  *property_or_resident_check
Route::middleware(['auth:api'])->group(function () {
    require 'v1/propertyresident.php';

    //Message Section All API Endpoint .
    Route::controller(MessageController::class)->group(function () {
        Route::post('/conversation/{id}', 'ConversationCreate');
        Route::post('/send-message/{id}', 'sendMessage');
        Route::get('/chat/{id}', 'getChat');
        Route::get('/conversations', 'conversations');
    });
    Route::post('delete-account', [UserController::class, 'deleteAccount']); //delete account
});

// Firebase Token Module
Route::post("firebase/token/add", [FirebaseTokenController::class, "store"]);
Route::post("firebase/token/get", [FirebaseTokenController::class, "getToken"]);
Route::post("firebase/token/delete", [FirebaseTokenController::class, "deleteToken"]);

// SSLCommerz routes API (Postman)
Route::get('/payment/create', [SSLCommerzPaymentController::class, 'PaymentCreate'])->name('payment.create')->middleware('auth:api');
Route::post('/payment/success', [SSLCommerzPaymentController::class, 'PaymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [SSLCommerzPaymentController::class, 'PaymentFail'])->name('payment.fail');
Route::post('/payment/cancel', [SSLCommerzPaymentController::class, 'PaymentCancel'])->name('payment.cancel');
Route::post('/payment/ipn', [SSLCommerzPaymentController::class, 'PaymentIPN'])->name('payment.ipn');

// Notification Section API Endpoints
Route::group(['prefix' => 'notifications', 'middleware' => 'auth:api'], function () {
    Route::get('/', [NotificationController::class, 'getNotification']);
    Route::post('/read', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'deleteNotification']);
});
