<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Import Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthOtpController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\TeknisiController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BuktiController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\KeahlianController;
use App\Http\Controllers\Api\AlamatController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UlasanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\PasswordResetOtpController;
use App\Http\Controllers\Api\TeknisiPesananController;
use App\Http\Controllers\Api\VerifikasiTeknisiController;

/*
|--------------------------------------------------------------------------
| API STATUS
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => response()->json(['message' => 'API Quickfix aktif']));
Route::get('/test', fn() => response()->json(['status' => 'API hidup']));
Route::get('/test-db', fn() => DB::table('pemesanan')->limit(5)->get());

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register-request', [AuthOtpController::class, 'registerRequest']);
    Route::post('/verify-otp', [AuthOtpController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthOtpController::class, 'resendOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

/*
|--------------------------------------------------------------------------
| TEKNISI INFO
|--------------------------------------------------------------------------
*/

Route::get('/get_teknisi', [TeknisiController::class, 'getTeknisi']);
Route::get('/get_teknisi_list', [TeknisiController::class, 'getListTeknisi']);
Route::get('/teknisi/layanan', [TeknisiController::class, 'getLayananTeknisi']);
Route::get('/search-teknisi', [TeknisiController::class, 'searchTeknisi']);
Route::get('/layanan-detail', [TeknisiController::class, 'getLayananDetail']);

/*
|--------------------------------------------------------------------------
| PEMESANAN
|--------------------------------------------------------------------------
*/

Route::get('/get_pemesanan', [PemesananController::class, 'getPemesanan']);
Route::middleware('auth:sanctum')->get('/get_pemesanan_by_user', [PemesananController::class, 'getPemesananByUser']);
Route::middleware('auth:sanctum')->get('/pemesanan/{id}', [PemesananController::class, 'getPemesananById']);
Route::middleware('auth:sanctum')->post('/cancel_pemesanan', [PemesananController::class, 'cancelPemesanan']);
Route::post('/add_pemesanan', [PemesananController::class, 'addPemesanan']);

/*
|--------------------------------------------------------------------------
| KERANJANG
|--------------------------------------------------------------------------
*/

Route::get('/keranjang', [KeranjangController::class, 'getKeranjang']);
Route::post('/keranjang/add', [KeranjangController::class, 'addKeranjang']);
Route::delete('/keranjang/{id}', [KeranjangController::class, 'deleteKeranjang']);
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout']);

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/upload-foto', [ProfileController::class, 'uploadFoto']);
    Route::post('/upload_gambar_layanan', [ProfileController::class, 'uploadGambarLayanan']);
    Route::delete('/hapus_gambar_layanan/{id}', [ProfileController::class, 'deleteGambarLayanan']);
});

// public
Route::get('/gambar_layanan/{id_teknisi}', [ProfileController::class, 'getGambarLayananByTeknisi']);
Route::get('/teknisi/{id_teknisi}/gambar', [ProfileController::class, 'getGambarLayananByTeknisi']);
Route::get('/bukti_pekerjaan/{id_teknisi}', [ProfileController::class, 'getBuktiPekerjaanByTeknisi']);

/*
|--------------------------------------------------------------------------
| BUKTI PEKERJAAN
|--------------------------------------------------------------------------
*/

Route::get('/bukti', [BuktiController::class, 'index']);
Route::get('/bukti/{id_teknisi}', [BuktiController::class, 'getByTeknisi']);
Route::delete('/bukti/{id}', [BuktiController::class, 'destroy']);
Route::get('/bukti/recent', [BuktiController::class, 'getRecent']);

/*
|--------------------------------------------------------------------------
| TUGAS TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:teknisi'])->get('/tugas-teknisi', [TaskController::class, 'getTasksByTeknisi']);
Route::get('/teknisi/{id_teknisi}/tugas', [TaskController::class, 'getTasksByTeknisi']);

/*
|--------------------------------------------------------------------------
| ALAMAT
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'index']);
    Route::post('/alamat', [AlamatController::class, 'store']);
    Route::put('/alamat/{id}', [AlamatController::class, 'update']);
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy']);
    Route::post('/alamat/{id}/default', [AlamatController::class, 'setDefault']);
});

/*
|--------------------------------------------------------------------------
| PEMBAYARAN
|--------------------------------------------------------------------------
*/

Route::post('/payment/create', [PaymentController::class, 'createPayment']);
Route::post('/payment/notification', [PaymentController::class, 'handleNotification']);
Route::get('/payment/status', [PaymentController::class, 'checkStatus']);
Route::get('/get_struk/{kode}', [PaymentController::class, 'getStruk']);

/*
|--------------------------------------------------------------------------
| PASSWORD RESET
|--------------------------------------------------------------------------
*/

Route::prefix('password')->group(function () {
    Route::post('/forgot', [PasswordResetOtpController::class, 'sendResetOtp']);
    Route::post('/reset', [PasswordResetOtpController::class, 'verifyResetOtp']);
});

/*
|--------------------------------------------------------------------------
| TRACKING & LOKASI
|--------------------------------------------------------------------------
*/

Route::post('/update-lokasi-teknisi', [LokasiController::class, 'update']);
Route::get('/lokasi-teknisi/{id_teknisi}', [LokasiController::class, 'getLokasi']);

Route::post('/tracking/store', [TrackingController::class, 'storeLocation']);
Route::get('/tracking/latest/{id_teknisi}', [TrackingController::class, 'getLatestLocation']);
Route::get('/tracking/customer/{id_pemesanan}', [TrackingController::class, 'getCustomerTracking']);

/*
|--------------------------------------------------------------------------
| PEMESANAN TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pemesanan/{id}/terima', [TeknisiPesananController::class, 'terimaPekerjaan']);
    Route::post('/teknisi/pemesanan/{id}/mulai', [TeknisiPesananController::class, 'mulaiKerja']);
    Route::post('/pemesanan/{id}/upload-bukti', [BuktiController::class, 'uploadBukti']);
    Route::get('/pemesanan/{id}/bukti', [BuktiController::class, 'getByPemesanan']);
    Route::post('/pemesanan/{id}/selesaikan', [TeknisiPesananController::class, 'selesaikanPekerjaan']);
    Route::post('/pemesanan/{id}/sampai-lokasi', [TeknisiPesananController::class, 'sampaiLokasi']);
});

/*
|--------------------------------------------------------------------------
| VERIFIKASI TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->post('/verifikasi-teknisi', [VerifikasiTeknisiController::class, 'store']);
Route::middleware('auth:sanctum')->get('/teknisi/verifikasi/status', [VerifikasiTeknisiController::class, 'status']);

/*
|--------------------------------------------------------------------------
| UPLOAD & OCR KTP
|--------------------------------------------------------------------------
*/

Route::post('/scan-ktp', [VerifikasiTeknisiController::class, 'scanKTP']); // <- endpoint OCR KTP

/*
|--------------------------------------------------------------------------
| ULASAN
|--------------------------------------------------------------------------
*/

// public
Route::get('/teknisi/{id}/ulasan', [UlasanController::class, 'getUlasanTeknisi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ulasan', [UlasanController::class, 'create']);
    Route::get('/pemesanan/{id}/ulasan', [UlasanController::class, 'cekUlasanPemesanan']);
});

/*
|--------------------------------------------------------------------------
| GALERI TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teknisi/update_profile', [TeknisiController::class, 'updateProfile']);
    Route::post('/teknisi/upload_galeri', [TeknisiController::class, 'uploadGaleri']);
    Route::delete('/teknisi/galeri/{id}', [TeknisiController::class, 'deleteGaleri']);
});

Route::middleware('auth:sanctum')->get('/teknisi/{id}/galeri', [TeknisiController::class, 'getGaleri']);

/*
|--------------------------------------------------------------------------
| KEAHLIAN TEKNISI
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teknisi/keahlian', [App\Http\Controllers\Teknisi\KeahlianTeknisiController::class, 'store']);
    Route::get('/teknisi/keahlian', [App\Http\Controllers\Teknisi\KeahlianTeknisiController::class, 'getByTeknisi']);
    Route::get('/teknisi/{id_teknisi}/keahlian', [App\Http\Controllers\Teknisi\KeahlianTeknisiController::class, 'getByTeknisiId']);
    Route::put('/teknisi/keahlian/{id}', [App\Http\Controllers\Teknisi\KeahlianTeknisiController::class, 'update']);
    Route::delete('/teknisi/keahlian/{id}', [App\Http\Controllers\Teknisi\KeahlianTeknisiController::class, 'destroy']);
});

// public
Route::get('/keahlian', [KeahlianController::class, 'index']);
Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index']);
