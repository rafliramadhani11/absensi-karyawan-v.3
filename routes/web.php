<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))
        ->name('dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', fn() => view('user.index'))
            ->name('user.index');
        Route::get('/create', fn() => view('user.create'))
            ->name('user.create');
        Route::get('/{user}/edit', fn(User $user) => view('user.edit', compact('user')))
            ->name('user.edit');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
