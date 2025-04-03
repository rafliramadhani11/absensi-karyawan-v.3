<?php

use App\Models\User;
use App\Models\Division;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;

Route::get('/', fn() => view('welcome'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))
        ->name('dashboard');

    // Division
    Route::prefix('divisions')->group(function () {
        Route::get('/', fn() => view('division.index'))
            ->name('division.index');
        Route::get('/{division}/detail', fn(Division $division) => view('division.detail', compact('division')))
            ->name('division.detail');
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', fn() => view('user.index'))
            ->name('user.index');
        Route::get('/create', fn() => view('user.create'))
            ->name('user.create');
        Route::get('/{user}/edit', fn(User $user) => view('user.edit', compact('user')))
            ->name('user.edit');
    });

    // Attendances
    Route::prefix('attendances')->group(function () {
        Route::get('/', fn() => view('attendance.index'))
            ->name('attendance.index');
        Route::get('/create', fn() => view('attendance.create'))
            ->name('attendance.create');
        Route::get('/{attendance}/edit', fn(Attendance $attendance) => view('attendance.edit', compact('attendance')))
            ->name('attendance.edit');
    });

    // Qr Absent
    Route::prefix('absents')->group(function () {
        Route::get('/', fn() => view('absent.index'))
            ->name('absent.index');

        Route::get('absen-datang', [AttendanceController::class, 'absenDatang'])
            ->name('user.absen-datang');

        Route::get('absen-pulang', [AttendanceController::class, 'absenPulang'])
            ->name('user.absen-pulang');
    });

    // Employee qr code
    Route::prefix('employees-qr-code')->group(function () {
        Route::get('/', fn() => view('admin.employees-qr-code'))
            ->name('admin.employees-qr-code');
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
