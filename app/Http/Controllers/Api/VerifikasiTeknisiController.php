<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerifikasiTeknisi;
use Illuminate\Support\Facades\Log;

class VerifikasiTeknisiController extends Controller
{
    /**
     * ============================================
     * 1. SIMPAN VERIFIKASI TEKNISI
     * ============================================
     */
    public function store(Request $request)
    {
        Log::info("== MULAI VERIFIKASI TEKNISI ==");

        // 🔐 User login (Sanctum)
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        /**
         * ============================
         * VALIDASI (HARUS SAMA DENGAN FLUTTER)
         * ============================
         */
        $request->validate([
            'provinsi'           => 'required|string',
            'kabupaten'          => 'required|string',
            'kecamatan'          => 'required|string',

            'nik'                => 'required|string|digits:16',
            'rekening'           => 'required|string',
            'bank'               => 'required|string',
            'bank_code'          => 'required|string',
            'nama_akun_rekening' => 'required|string',

            // ✅ TAMBAHAN
            'skck_expired'       => 'required|date|after_or_equal:today',

            'ktp'                => 'required|file|mimes:jpg,jpeg,png',
            'skck'               => 'required|file|mimes:jpg,jpeg,png',
        ]);

        Log::info("DATA YANG MASUK", $request->all());

        /**
         * ============================
         * CEK TEKNISI
         * ============================
         */
        $teknisi = $user->teknisi;
        if (!$teknisi) {
            return response()->json([
                'success' => false,
                'message' => 'User belum terdaftar sebagai teknisi'
            ], 403);
        }

        /**
         * ============================
         * SIMPAN DATA
         * ============================
         */
        $ver = new VerifikasiTeknisi();
        $ver->id_teknisi = $teknisi->id_teknisi;

        // Identitas
        $ver->nik   = $request->nik;
        $ver->nama  = $request->nama_akun_rekening;

        // Wilayah
        $ver->provinsi  = $request->provinsi;
        $ver->kabupaten = $request->kabupaten;
        $ver->kecamatan = $request->kecamatan;

        // Rekening
        $ver->rekening              = $request->rekening;
        $ver->bank                  = $request->bank;
        $ver->bank_code             = $request->bank_code;
        $ver->account_name_verified = $request->nama_akun_rekening;

        // ✅ TANGGAL BERLAKU SKCK
        $ver->skck_expired = $request->skck_expired;

        /**
         * ============================
         * UPLOAD FILE
         * ============================
         */
        if ($request->hasFile('ktp')) {
            $ver->foto_ktp = $request->file('ktp')->store('verifikasi', 'public');
            Log::info("UPLOAD KTP OK", ['path' => $ver->foto_ktp]);
        }

        if ($request->hasFile('skck')) {
            $ver->foto_skck = $request->file('skck')->store('verifikasi', 'public');
            Log::info("UPLOAD SKCK OK", ['path' => $ver->foto_skck]);
        }

        // Status default
        $ver->status = 'pending';
        $ver->save();

        Log::info("VERIFIKASI TERSIMPAN", ['id' => $ver->id]);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi teknisi berhasil disimpan.',
            'data'    => $ver,
        ], 201);
    }

    /**
     * ============================================
     * 2. STATUS VERIFIKASI TEKNISI
     * ============================================
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $teknisi = $user->teknisi;

        $ver = VerifikasiTeknisi::where('id_teknisi', $teknisi->id_teknisi)->first();

        if (!$ver) {
            return response()->json([
                'status' => 'belum_verifikasi',
            ]);
        }

        return response()->json([
            'status' => $ver->status,
            'data'   => $ver,
        ]);
    }
}
