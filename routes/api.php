<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeknisiController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BuktiController;

// ===============================
// API STATUS
// ===============================
Route::get('/', function () {
    return response()->json(['message' => 'API Quickfix aktif 🚀']);
});

// ===============================
// AUTH
// ===============================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// ===============================
// TEKNISI
// ===============================
Route::get('/get_teknisi', [TeknisiController::class, 'getTeknisi']);
Route::get('/get_teknisi_list', [TeknisiController::class, 'getListTeknisi']);
Route::get('/teknisi', [TeknisiController::class, 'getTeknisi']);
Route::get('/teknisi/layanan', [TeknisiController::class, 'getLayananTeknisi']);

// ===============================
// PEMESANAN (ORDER)
// ===============================
Route::get('/get_pemesanan', [PemesananController::class, 'getPemesanan']);
Route::middleware('auth:sanctum')->get('/get_pemesanan_by_user', [PemesananController::class, 'getPemesananByUser']);
Route::post('/add_pemesanan', [PemesananController::class, 'addPemesanan']);

// ===============================
// PROFILE
// ===============================
Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show']);

// PROFILE & GAMBAR
Route::post('/upload_foto', [ProfileController::class, 'uploadFoto']);
Route::post('/upload_gambar_layanan', [ProfileController::class, 'uploadGambarLayanan']);
Route::delete('/hapus_gambar_layanan/{id}', [ProfileController::class, 'deleteGambarLayanan']);

// GAMBAR TEKNISI
Route::get('/gambar_layanan/{id_teknisi}', [ProfileController::class, 'getGambarLayananByTeknisi']);
Route::get('/teknisi/{id_teknisi}/gambar', [ProfileController::class, 'getGambarLayananByTeknisi']);

Route::get('/bukti_pekerjaan/{id_teknisi}', [ProfileController::class, 'getBuktiPekerjaanByTeknisi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/upload-foto', [ProfileController::class, 'uploadFoto']);
});
// ===============================
// 🔹 AKSES GAMBAR DARI STORAGE DENGAN CORS SUPPORT
// ===============================
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!File::exists($filePath)) {
        abort(404);
    }

    $file = File::get($filePath);
    $type = File::mimeType($filePath);

    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Access-Control-Allow-Origin', '*')  // Tambahkan ini
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept');
})->where('path', '.*');


// ===============================
// 🔹 Bukti Pekerjaan
// ===============================

Route::get('/bukti', [BuktiController::class, 'index']);
Route::get('/bukti/{id_teknisi}', [BuktiController::class, 'getByTeknisi']);
Route::post('/bukti', [BuktiController::class, 'store']);
Route::delete('/bukti/{id}', [BuktiController::class, 'destroy']);