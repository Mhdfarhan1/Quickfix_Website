<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeknisiController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BuktiController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AlamatController;
use App\Http\Controllers\Api\PaymentController;

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
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'role:teknisi'])->group(function () {
    Route::get('/tugas-teknisi', [TaskController::class, 'getTasksByTeknisi']);
});


// ===============================
// TEKNISI
// ===============================
Route::get('/get_teknisi', [TeknisiController::class, 'getTeknisi']);
Route::get('/get_teknisi_list', [TeknisiController::class, 'getListTeknisi']);
Route::get('/teknisi/layanan', [TeknisiController::class, 'getLayananTeknisi']);
Route::get('/search-teknisi', [TeknisiController::class, 'searchTeknisi']);
Route::get('/layanan-detail', [TeknisiController::class, 'getLayananDetail']);



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
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload_foto', [ProfileController::class, 'uploadFoto']);
    Route::post('/upload_gambar_layanan', [ProfileController::class, 'uploadGambarLayanan']);
    Route::delete('/hapus_gambar_layanan/{id}', [ProfileController::class, 'deleteGambarLayanan']);
});

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

Route::get('/teknisi/{id_teknisi}/tugas', [TaskController::class, 'getTasksByTeknisi']);

Route::get('/bukti/recent', [BuktiController::class, 'getRecent']);


// ===============================
// 🔹 BackUp adn Restore
// ===============================
Route::get('/backup', function() {
    Artisan::call('backup:run');
    return response()->json(['message' => 'Backup berhasil dijalankan']);
});

Route::post('/restore', function (Request $request) {
    $backupFile = storage_path('app/backup/latest.zip');
    if (!file_exists($backupFile)) {
        return response()->json(['message' => 'File backup tidak ditemukan'], 404);
    }

    // Contoh pseudo-restore
    // Jalankan perintah artisan atau unzip manual
    Artisan::call('backup:restore', ['--file' => $backupFile]);

    return response()->json(['message' => 'Restore berhasil dijalankan']);
});


Route::post('/selesai', [TaskController::class, 'selesaikanTugas']);

// ===============================
// 🔹 Alamat
// ===============================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'index']);
    Route::post('/alamat', [AlamatController::class, 'store']);
    Route::put('/alamat/{id}', [AlamatController::class, 'update']);
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy']);
    Route::post('/alamat/{id}/default', [AlamatController::class, 'setDefault']);
});

// ===============================
// 🔹 pembayaran
// ===============================
Route::post('/payment/create', [PaymentController::class, 'createPayment']);
Route::post('/payment/callback', [PaymentController::class, 'callback']);
Route::post('midtrans/pay/{id}', [PaymentController::class, 'pay']);

Route::get('/tes', function () {
    return response()->json(['status' => 'OK']);
});

Route::get('/test-db', function () {
    return DB::table('pemesanan')->limit(5)->get();
});
