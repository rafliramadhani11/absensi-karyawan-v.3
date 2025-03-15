<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;



Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))
        ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')->name('verification.send');

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');
});
