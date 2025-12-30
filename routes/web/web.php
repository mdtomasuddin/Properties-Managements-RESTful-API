<?php

use App\Http\Controllers\Web\CMS\FaqController;
use App\Http\Controllers\Web\V1\Plan\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
//check role  and admin login this system
Route::get('/dashboard', function () {
    return view('backend.layouts.dashboard.index');
})->middleware(['auth', 'admin', 'verified'])->name('dashboard');

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
