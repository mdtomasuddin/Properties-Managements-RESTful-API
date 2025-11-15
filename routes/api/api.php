<?php

use App\Http\Controllers\Api\Faq\FaqController;
use Illuminate\Support\Facades\Route;

/**
 * V1 API Routes:
 */
require 'v1/auth/auth.php';                          // All Auth routes
Route::get('/faq', [FaqController::class, 'index']); //faq

Route::middleware(['auth:api'])->group(function () {
    require 'v2/property.php';
});
