<?php

use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;

Route::get('/', fn() => view('welcome'));

Route::middleware('auth')->group(function () {
    // Admin
    Route::middleware('isAdmin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/', fn() => view('admin.employees-qr-code'))
                ->name('admin.employees-qr-code');
        });
    });

    // HRD
    Route::middleware('isHrd')->group(function () {
        Route::prefix('hrd')->group(function () {
            Route::get('/', fn() => view('hrd.dashboard'))
                ->name('hrd.dashboard');

            Route::get('/absent', fn() => view('hrd.daily-absent'))
                ->name('hrd.daily-absent');

            Route::get('/salaries', fn() => view('hrd.salaries'))
                ->name('hrd.salaries');

            // Division
            Route::prefix('divisions')->group(function () {
                Route::get('/', fn() => view('hrd.division.index'))
                    ->name('hrd.division.index');

                Route::get('/{division}/detail', fn(Division $division) => view('hrd.division.detail', compact('division')))
                    ->name('hrd.division.detail');
            });

            // Employees
            Route::prefix('employees')->group(function () {
                Route::get('/', fn() => view('hrd.employee.index'))
                    ->name('hrd.employee.index');

                Route::get('/create', fn() => view('hrd.employee.create'))
                    ->name('hrd.employee.create');

                Route::get('/{user}/edit', fn(User $user) => view('hrd.employee.edit', compact('user')))
                    ->name('hrd.employee.edit');
            });

            // Attendances
            Route::prefix('attendances')->group(function () {
                Route::get('/', fn() => view('hrd.attendance.index'))
                    ->name('hrd.attendance.index');
            });
        });
    });

    // Employee
    Route::middleware('isEmployee')->group(function () {
        Route::prefix('employee')->group(function () {
            Route::get('/', fn() => view('user.dashboard'))
                ->name('user.dashboard');

            // Qr Absent
            Route::get('/absent', fn() => view('user.daily-absent'))
                ->name('user.daily-absent');

            Route::prefix('absents')->group(function () {
                Route::get('absen-datang', [AttendanceController::class, 'absenDatang'])
                    ->name('user.absen-datang');

                Route::get('absen-pulang', [AttendanceController::class, 'absenPulang'])
                    ->name('user.absen-pulang');
            });

            // Salaries
            Route::get('/salaries', fn() => view('user.salaries'))
                ->name('user.salaries');

            // Attendances
            Route::get('/attendances', fn() => view('user.attendance.index'))
                ->name('user.attendance.index');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
