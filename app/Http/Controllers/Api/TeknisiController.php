<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teknisi;
use Illuminate\Support\Facades\DB;

class TeknisiController extends Controller
{
    // Ambil satu teknisi berdasarkan id
    public function getTeknisi(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json(['error' => 'Parameter id tidak ditemukan'], 400);
        }

        $teknisi = DB::table('teknisi')
            ->join('user', 'teknisi.id_user', '=', 'user.id_user')
            ->leftJoin('keahlian_teknisi', 'teknisi.id_teknisi', '=', 'keahlian_teknisi.id_teknisi')
            ->leftJoin('keahlian', 'keahlian_teknisi.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'teknisi.id_teknisi',
                'user.nama',
                'user.email',
                'user.no_hp',
                'user.foto_profile',
                'teknisi.deskripsi',
                'teknisi.pengalaman',
                'teknisi.rating_avg',
                'teknisi.status',
                DB::raw('GROUP_CONCAT(DISTINCT keahlian.nama_keahlian SEPARATOR ", ") as daftar_keahlian')
            )
            ->where('teknisi.id_teknisi', $id)
            ->groupBy('teknisi.id_teknisi')
            ->first();

        if (!$teknisi) {
            return response()->json(['message' => 'Teknisi tidak ditemukan'], 404);
        }

        return response()->json($teknisi);
    }


    // Ambil semua teknisi
    public function getListTeknisi()
    {
        try {
            $list = \App\Models\Teknisi::with('user')->orderBy('rating_avg', 'DESC')->get();

            return response()->json([
                'status' => true,
                'data' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memuat data teknisi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLayananTeknisi(Request $request)
    {
        $id = $request->query('id_teknisi');

        if (!$id) {
            return response()->json(['error' => 'Parameter id_teknisi tidak ditemukan'], 400);
        }

        try {
            $layanan = DB::table('keahlian_teknisi')
                ->join('keahlian', 'keahlian_teknisi.id_keahlian', '=', 'keahlian.id_keahlian')
                ->leftJoin('kategori', 'keahlian.id_kategori', '=', 'kategori.id_kategori')
                ->leftJoin('gambar_layanan', function ($join) {
                    $join->on('gambar_layanan.id_keahlian', '=', 'keahlian.id_keahlian')
                        ->on('gambar_layanan.id_teknisi', '=', 'keahlian_teknisi.id_teknisi');
                })
                ->leftJoin('pemesanan', function ($join) {
                    $join->on('pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
                        ->on('pemesanan.id_teknisi', '=', 'keahlian_teknisi.id_teknisi');
                })
                ->leftJoin('ulasan', function ($join) {
                    $join->on('ulasan.id_teknisi', '=', 'keahlian_teknisi.id_teknisi');
                })
                ->select(
                    'keahlian_teknisi.id_teknisi',
                    'keahlian.id_keahlian',
                    'keahlian.nama_keahlian',
                    'keahlian.deskripsi',
                    'kategori.nama_kategori',
                    DB::raw('COALESCE(gambar_layanan.url_gambar, "/uploads/default_layanan.jpg") as gambar'),
                    DB::raw('ROUND(AVG(ulasan.rating), 1) as rating'),
                    DB::raw('MIN(pemesanan.harga) as harga_min'),
                    DB::raw('MAX(pemesanan.harga) as harga_max')
                )
                ->where('keahlian_teknisi.id_teknisi', $id)
                ->groupBy('keahlian.id_keahlian', 'keahlian_teknisi.id_teknisi', 'gambar_layanan.url_gambar', 'kategori.nama_kategori')
                ->get();

            return response()->json($layanan);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data layanan teknisi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



}
