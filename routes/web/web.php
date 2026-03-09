<?php

use App\Http\Controllers\Web\Announcement\AnnouncementAdminController;
use App\Http\Controllers\Web\CMS\FaqController;
use App\Http\Controllers\Web\Emergency\EmergencyAdminController;
use App\Http\Controllers\Web\User\DashboardController;
use App\Http\Controllers\Web\User\Properties\PropertyOwnerController;
use App\Http\Controllers\Web\User\Resident\ResidentInformationController;
use App\Http\Controllers\Web\V1\Plan\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('dashboard');

require 'v1/auth.php';
require 'v1/user.php';
require 'v1/settings/mail.php';
//faq  FaqController
Route::resource('faq', FaqController::class)->parameters(['faq' => 'id']);
Route::get('faq-status/{id}', [FaqController::class, 'status'])->name('faq.status');

//subscription plan routes
Route::prefix('plan')->name('plan.')->group(function () {
    Route::get('/', [PlanController::class, 'index'])->name('index');
    Route::patch('/{plan}', [PlanController::class, 'update'])->name('update');
    Route::post('/{plan}/toggle-status', [PlanController::class, 'toggleStatus'])->name('toggle-status');
});

//properties 
Route::resource('property-owner', PropertyOwnerController::class);
Route::resource('resident-information', ResidentInformationController::class); //resident information
Route::resource('admin-emergency', EmergencyAdminController::class);
Route::resource('admin-announcement', AnnouncementAdminController::class);
