<?php

use App\Http\Controllers\Web\V1\Setting\MailController;
use Illuminate\Support\Facades\Route;

Route::prefix('settings/mail')->name('v1.setting.mail.')->controller(MailController::class)
->group(function () {
    Route::get('/', 'show')->name('show');
    Route::post('/', 'store')->name('store');
});
