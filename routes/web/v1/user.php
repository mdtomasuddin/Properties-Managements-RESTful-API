<?php

use App\Http\Controllers\Web\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin/user')->name('admin.user.')->middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{user:handle}', 'show')->name('show');
    Route::get('/status/{user:handle}', 'status')->name('status');
});
