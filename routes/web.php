<?php

use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DivisionPdfController;
use App\Http\Controllers\PdfControllerAllEmployee;
use App\Http\Controllers\PdfHRDSalaryController;
use App\Http\Controllers\UserController;

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

            // Salaries
            Route::get('/salaries', fn() => view('hrd.salaries'))
                ->name('hrd.salaries');

            Route::get('/salaries/export-pdf/{start}/{end}', PdfHRDSalaryController::class)
                ->name('hrd.salaries.pdf');

            // Division
            Route::prefix('divisions')->group(function () {
                Route::get('/', fn() => view('hrd.division.index'))
                    ->name('hrd.division.index');

                Route::get('/{division}/detail', fn(Division $division) => view('hrd.division.detail', compact('division')))
                    ->name('hrd.division.detail');

                Route::get('{division}/kinerja/{start}/{end}', DivisionPdfController::class)
                    ->name('hrd.division.kinerja');
            });

            // Employees
            Route::prefix('employees')->group(function () {
                Route::get('/', fn() => view('hrd.employee.index'))
                    ->name('hrd.employee.index');

                Route::get('/kinerja/{start}/{end}', PdfControllerAllEmployee::class)
                    ->name('hrd.employee.kinerja-all-employee');

                Route::get('/create', fn() => view('hrd.employee.create'))
                    ->name('hrd.employee.create');

                Route::get('/{user}/edit', fn(User $user) => view('hrd.employee.edit', compact('user')))
                    ->name('hrd.employee.edit');

                Route::get('{user}/kinerja/{start}/{end}', [PdfController::class, 'kinerja'])
                    ->name('hrd.employee.kinerja');

                Route::get('{user}/kinerja-absensi/{start}/{end}', [PdfController::class, 'kinerjaAbsensi'])
                    ->name('hrd.employee.kinerja-karyawan');
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
            Route::get('/profile-pdf/{user}', [UserController::class, 'exportProfilePdf'])
                ->name('user.profile-pdf');

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

            // Route::get('/salaries/{start}/{end}', [UserController::class, 'exportSalaries'])
            //     ->name('user.salaries.export');

            // Attendances
            Route::get('/attendances', fn() => view('user.attendance.index'))
                ->name('user.attendance.index');

            Route::get('/attendances/kinerja/{start}/{end}', [UserController::class, 'exportAttendances'])
                ->name('user.kinerja.export');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
