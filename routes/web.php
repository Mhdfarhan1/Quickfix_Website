<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\PendapatanController;
use App\Http\Controllers\Admin\AdminProfileController; // ⬅️ TAMBAHAN

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

    // ====== ROUTE PROFIL ADMIN ======
    Route::get('profile', [AdminProfileController::class, 'show'])
        ->name('admin.profile.show');

    Route::get('profile/edit', [AdminProfileController::class, 'edit'])
        ->name('admin.profile.edit');

    // Update profil (nama, email, foto)
    Route::match(['post', 'put'], 'profile', [AdminProfileController::class, 'update'])
        ->name('admin.profile.update');

    // Update password
    Route::match(['post', 'put'], 'profile/password', [AdminProfileController::class, 'updatePassword'])
        ->name('admin.profile.password.update');
    // =================================

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

    // ==========================
    // ROUTE BANNER PROMOSI
    // ==========================
    Route::get('banner', [BannerController::class, 'index'])
        ->name('admin.banner.index');

    Route::post('banner', [BannerController::class, 'store'])
        ->name('admin.banner.store');

    // Update banner (gunakan method PUT)
    Route::put('banner/{banner}', [BannerController::class, 'update'])
        ->name('admin.banner.update');

    // Hapus banner
    Route::delete('banner/{banner}', [BannerController::class, 'destroy'])
        ->name('admin.banner.destroy');

    // Aktif / Nonaktifkan banner
    Route::patch('banner/{banner}/toggle', [BannerController::class, 'toggle'])
        ->name('admin.banner.toggle');

    // Route Pemesanan Selesai
    Route::get('pemesanan/selesai', [PemesananController::class, 'selesai'])
        ->name('admin.pemesanan.selesai');

    // Route Pendapatan Admin
    Route::get('pendapatan', [PendapatanController::class, 'index'])
        ->name('admin.pendapatan.index');

    // Route Komplain
    Route::get('complaints', [
        ComplaintController::class,
        'index'
    ])->name('admin.complaints.index');
    Route::get('complaints/{complaint}', [
        ComplaintController::class,
        'show'
    ])->name('admin.complaints.show');
    Route::put('complaints/{complaint}', [
        ComplaintController::class,
        'update'
    ])->name('admin.complaints.update');
});
