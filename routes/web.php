<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\Admin\DashboardController;

// Halaman landing
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Halaman login admin
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

// Proses login admin
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

// Proses logout admin
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

// Group route yang butuh autentikasi admin
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Route Teknisi
    Route::get('teknisi', [TeknisiController::class, 'index'])
        ->name('admin.teknisi.index');
    Route::get('teknisi/verify/{id}', [TeknisiController::class, 'verify'])
        ->name('admin.teknisi.verify');
    Route::get('teknisi/delete/{id}', [TeknisiController::class, 'destroy'])
        ->name('admin.teknisi.destroy');

    // Route Pengguna
    Route::get('user', [UserController::class, 'index'])
        ->name('admin.user.index');
    Route::delete('user/delete/{id}', [UserController::class, 'destroy'])
        ->name('admin.user.destroy');
});
