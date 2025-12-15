<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    // Tambah Ulasan
    public function create(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'nullable|max:500'
        ]);

        $pemesanan = Pemesanan::find($request->id_pemesanan);

        if (!$pemesanan || $pemesanan->status_pekerjaan != "selesai") {
            return response()->json(["status" => false, "message" => "Tidak bisa membuat ulasan"], 400);
        }


        // Cek pemilik
        if ($pemesanan->id_pelanggan != $request->user()->id_user) {
            return response()->json(["status" => false, "message" => "Anda tidak berhak membuat ulasan untuk pesanan ini"], 403);
        }

        // Cek sudah pernah ulasan
        if (Ulasan::where('id_pemesanan', $request->id_pemesanan)->exists()) {
            return response()->json(["status" => false, "message" => "Ulasan sudah dibuat"], 400);
        }

        $ulasan = Ulasan::create([
            'id_pemesanan' => $request->id_pemesanan,
            'id_pelanggan' => $pemesanan->id_pelanggan,
            'id_teknisi' => $pemesanan->id_teknisi,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        // Update rating teknisi
        $rating_avg = Ulasan::where('id_teknisi', $pemesanan->id_teknisi)->avg('rating');
        DB::table('teknisi')
            ->where('id_teknisi', $pemesanan->id_teknisi)
            ->update([
                'rating_avg' => number_format($rating_avg, 1)
            ]);


        // Optional: notifikasi ke teknisi
        // Notify::send($pemesanan->id_teknisi, "Ulasan Baru", "Pelanggan memberikan ulasan baru");

        return response()->json(["status" => true, "message" => "Ulasan berhasil diberikan", "data" => $ulasan]);
    }

    // Get ulasan teknisi
    public function getUlasanTeknisi($id_teknisi)
    {
        $ulasan = Ulasan::where('id_teknisi', $id_teknisi)
            ->with('pelanggan:id_user,nama,foto_profile')
            ->orderBy('created_at', 'desc')
            ->get();

        $ulasan->transform(function ($item) {
            $item->pelanggan->foto_url = $item->pelanggan->foto_profile
                ? url('storage/foto/foto_teknisi/' . $item->pelanggan->foto_profile)
                : null;
            return $item;
        });

        $rating_avg = Ulasan::where('id_teknisi', $id_teknisi)->avg('rating');

        return response()->json([
            "status" => true,
            "rating_avg" => number_format($rating_avg, 1),
            "ulasan" => $ulasan
        ]);
    }


    public function cekUlasanPemesanan($id_pemesanan)
    {
        $pemesanan = Pemesanan::find($id_pemesanan);

        if (!$pemesanan) {
            return response()->json([
                "status" => false,
                "message" => "Pemesanan tidak ditemukan"
            ], 404);
        }

        // hanya pemilik pesanan yang boleh mengecek
        if ($pemesanan->id_pelanggan != auth()->user()->id_user) {
            return response()->json([
                "status" => false,
                "message" => "Akses ditolak"
            ], 403);
        }

        $ulasan = Ulasan::where('id_pemesanan', $id_pemesanan)->first();

        return response()->json([
            "status" => true,
            "sudah_review" => $ulasan ? true : false,
            "ulasan" => $ulasan
        ]);
    }

}
