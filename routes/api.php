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
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\TeknisiPesananController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\AuthOtpController;



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

Route::prefix('auth')->group(function () {
    Route::post('register-request', [AuthOtpController::class, 'registerRequest']);
    Route::post('verify-otp', [AuthOtpController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthOtpController::class, 'resendOtp']);
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
// Keranjang
// ===============================
Route::get('/keranjang', [KeranjangController::class, 'getKeranjang']);
Route::post('/keranjang/add', [KeranjangController::class, 'addKeranjang']);
Route::delete('/keranjang/{id}', [KeranjangController::class, 'deleteKeranjang']);
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout']);

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
// 🔹 Bukti Pekerjaan
// ===============================

Route::get('/bukti', [BuktiController::class, 'index']);
Route::get('/bukti/{id_teknisi}', [BuktiController::class, 'getByTeknisi']);
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
// 🔹 Buat link pembayaran (dipanggil saat user klik "Bayar Sekarang")
Route::post('/payment/create', [PaymentController::class, 'createPayment']);

// 🔹 Endpoint callback Midtrans (wajib sesuai di dashboard Midtrans)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification']);

// 🔹 Endpoint untuk Flutter polling status pembayaran
Route::get('/payment/status', [PaymentController::class, 'checkStatus']);

// 🔹 Endpoint untuk ambil struk pembayaran (setelah sukses)
Route::get('/get_struk/{kode}', [PaymentController::class, 'getStruk']);


// ===============================
// 🔹 Password reset
// ===============================
Route::prefix('password')->group(function () {
    Route::post('/forgot', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/reset', [PasswordResetController::class, 'resetPassword']);
});


Route::get('/tes', function () {
    return response()->json(['status' => 'OK']);
});

Route::get('/test-db', function () {
    return DB::table('pemesanan')->limit(5)->get();
});


// ===============================
// 🔹 tEKNISI
// ===============================
// Teknisi menerima pekerjaan
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pemesanan/{id}/terima', [App\Http\Controllers\Api\TeknisiPesananController::class, 'terimaPekerjaan']);
    Route::post('/teknisi/pemesanan/{id}/mulai', [TeknisiPesananController::class, 'mulaiKerja']);

});
Route::middleware('auth:sanctum')->prefix('teknisi')->group(function () {
    Route::get('/pesanan/baru', [TeknisiPesananController::class, 'pesananBaru']);
    Route::get('/pesanan/dijadwalkan', [TeknisiPesananController::class, 'dijadwalkan']);
    Route::get('/pesanan/berjalan', [TeknisiPesananController::class, 'pesananBerjalan']);
    Route::post('/pemesanan/{id}/sampai-lokasi', [TeknisiPesananController::class, 'sampaiLokasi']);
});

Route::post('/update-lokasi-teknisi', [LokasiController::class, 'update']);
Route::get('/lokasi-teknisi/{id_teknisi}', [LokasiController::class, 'getLokasi']);

Route::post('/tracking/store', [TrackingController::class, 'storeLocation']);
Route::get('/tracking/latest/{id_teknisi}', [TrackingController::class, 'getLatestLocation']);
Route::get('/tracking/customer/{id_pemesanan}', [TrackingController::class, 'getCustomerTracking']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pemesanan/{id}/upload-bukti', [BuktiController::class, 'uploadBukti']);
    Route::get('/pemesanan/{id}/bukti', [BuktiController::class, 'getByPemesanan']);
    Route::post('/pemesanan/{id}/selesaikan', [TeknisiPesananController::class, 'selesaikanPekerjaan']);
});

Route::get('/test', function(){
    return response()->json(["status" => "API hidup"]);
});

Route::middleware('auth:sanctum')->get('/teknisi/riwayat', [TaskController::class, 'getRiwayatTeknisi']);
