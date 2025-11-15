<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

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
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});