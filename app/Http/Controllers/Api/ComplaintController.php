<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User belum login.',
            ], 401);
        }

        $complaints = Complaint::where('user_id', $user->id_user ?? $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $complaints->map(function ($complaint) {
            return [
                'id'              => $complaint->id,
                'kategori'        => $complaint->kategori,
                'jenis_masalah'   => $complaint->jenis_masalah,
                'deskripsi'       => $complaint->deskripsi,
                'status'          => $complaint->status,
                'balasan_admin'   => $complaint->balasan_admin,

                // ⭐ DATA TAMBAHAN UNTUK RIWAYAT / DETAIL
                'nomor_pesanan'      => $complaint->nomor_pesanan,
                'metode_pembayaran'  => $complaint->metode_pembayaran,
                'nominal_id'         => $complaint->nominal_id,
                'nomor_tujuan'       => $complaint->nomor_tujuan,   // ⬅️ BARU
                'nama_tujuan'        => $complaint->nama_tujuan,    // ⬅️ BARU

                'tanggal_lapor'   => $complaint->created_at->format('d M Y H:i'),
                'lampiran_url'    => $complaint->lampiran
                    ? asset('storage/' . $complaint->lampiran)
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data riwayat komplain berhasil diambil.',
            'data'    => $data,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User belum login.',
            ], 401);
        }

        // ✅ VALIDASI INPUT
        $request->validate([
            'kategori'           => 'required|in:pesanan,pembayaran,aplikasi,akun',
            'jenis_masalah'      => 'required|string|max:255',
            'deskripsi'          => 'required|string|min:10',
            'lampiran'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'nomor_pesanan'      => 'nullable|string|max:255',
            'metode_pembayaran'  => 'nullable|string|max:255',
            'nominal_id'         => 'nullable|string|max:255',

            // ⭐ FIELD BARU
            'nomor_tujuan'       => 'nullable|string|max:255',
            'nama_tujuan'        => 'nullable|string|max:255',
        ]);

        // ✅ VALIDASI KHUSUS PER KATEGORI
        if ($request->kategori === 'pesanan' && ! $request->nomor_pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor pesanan wajib diisi untuk masalah pesanan.',
            ], 422);
        }

        if ($request->kategori === 'pembayaran' && ! $request->metode_pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran wajib diisi untuk masalah pembayaran.',
            ], 422);
        }

        // (kalau suatu saat mau wajibkan nominal_id / nomor_tujuan untuk pembayaran,
        // bisa tambahkan cek lagi di sini)

        // ✅ SIMPAN LAMPIRAN (JIKA ADA)
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('complaints', 'public');
        }

        // ✅ SIMPAN KE DATABASE
        $complaint = Complaint::create([
            'user_id'           => $user->id_user ?? $user->id,
            'kategori'          => $request->kategori,
            'jenis_masalah'     => $request->jenis_masalah,
            'deskripsi'         => $request->deskripsi,

            'nomor_pesanan'     => $request->nomor_pesanan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'nominal_id'        => $request->nominal_id,

            'nomor_tujuan'      => $request->nomor_tujuan,  // ⬅️ BARU
            'nama_tujuan'       => $request->nama_tujuan,   // ⬅️ BARU

            'lampiran'          => $lampiranPath,
            'status'            => 'baru',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim.',
            'data'    => [
                'id'              => $complaint->id,
                'kategori'        => $complaint->kategori,
                'status'          => $complaint->status,
                'balasan_admin'   => $complaint->balasan_admin,
                'lampiran_url'    => $lampiranPath
                    ? asset('storage/' . $lampiranPath)
                    : null,
            ]
        ]);
    }
}
