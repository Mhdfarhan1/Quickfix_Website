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

    public function searchTeknisi(Request $request)
    {
        $search     = $request->query('search');
        $kategori   = $request->query('kategori');
        $lokasi     = $request->query('lokasi');
        $sort       = $request->query('sort', 'rating');
        $page       = (int)$request->query('page', 1);
        $limit      = (int)$request->query('limit', 5);

        $offset = ($page - 1) * $limit;

        $query = DB::table('teknisi')
            ->join('user', 'user.id_user', '=', 'teknisi.id_user')
            ->leftJoin('alamat', 'alamat.id_user', '=', 'user.id_user')
            ->join('keahlian_teknisi', 'keahlian_teknisi.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('keahlian', 'keahlian.id_keahlian', '=', 'keahlian_teknisi.id_keahlian')
            ->leftJoin('gambar_layanan', function ($join) {
                $join->on('gambar_layanan.id_teknisi', '=', 'teknisi.id_teknisi')
                    ->on('gambar_layanan.id_keahlian', '=', 'keahlian.id_keahlian');
            })
            ->leftJoin('pemesanan', function ($join) {
                $join->on('pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
                    ->on('pemesanan.id_teknisi', '=', 'keahlian_teknisi.id_teknisi');
            });

        // 🔎 Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user.nama', 'LIKE', "%$search%")
                ->orWhere('keahlian.nama_keahlian', 'LIKE', "%$search%");
            });
        }

        if ($kategori && $kategori != 'Semua') {
            $query->where('keahlian.nama_keahlian', 'LIKE', "%$kategori%");
        }

        if ($lokasi) {
            $query->where('alamat.alamat_lengkap', 'LIKE', "%$lokasi%");
        }

        // 🔢 Kolom yang diambil
        $query->select(
            'teknisi.id_teknisi',
            'user.nama',
            'alamat.alamat_lengkap',
            'keahlian.id_keahlian',
            'keahlian.nama_keahlian',
            DB::raw('COALESCE(teknisi.rating_avg, 0) AS rating'),
            DB::raw('COALESCE(MIN(pemesanan.harga), 0) AS harga_min'),
            DB::raw('COALESCE(MAX(pemesanan.harga), 0) AS harga_max'),
            DB::raw('COALESCE(gambar_layanan.url_gambar, "/uploads/default_layanan.jpg") AS gambar')
        )
        ->distinct()
        ->groupBy(
            'teknisi.id_teknisi',
            'user.nama',
            'alamat.alamat_lengkap',
            'keahlian.id_keahlian',
            'keahlian.nama_keahlian',
            'gambar_layanan.url_gambar',
            'teknisi.rating_avg'
        );

        // 🔽 Sorting aman tanpa error
        switch ($sort) {
            case 'rating':
                $query->orderBy(DB::raw('COALESCE(teknisi.rating_avg, 0)'), 'DESC');
                break;
            case 'harga_min':
                $query->orderBy(DB::raw('MIN(pemesanan.harga)'), 'ASC');
                break;
            case 'harga_max':
                $query->orderBy(DB::raw('MAX(pemesanan.harga)'), 'DESC');
                break;
            default:
                $query->orderBy('user.nama', 'ASC'); // fallback
        }

        // 📊 Pagination manual
        $count = $query->distinct('teknisi.id_teknisi')->count('teknisi.id_teknisi');
        $data = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'current_page' => $page,
            'total_data'   => $count,
            'has_more'     => ($page * $limit < $count),
            'data'         => $data
        ]);
    }

    public function getLayananDetail(Request $request)
    {
        $idTeknisi = $request->query('id_teknisi');
        $idKeahlian = $request->query('id_keahlian');

        if (!$idTeknisi || !$idKeahlian) {
            return response()->json(['message' => 'Parameter tidak lengkap'], 400);
        }

        // detail teknisi + user
        $teknisi = DB::table('teknisi')
            ->join('user', 'user.id_user', '=', 'teknisi.id_user')
            ->where('teknisi.id_teknisi', $idTeknisi)
            ->select(
                'teknisi.id_teknisi',
                'user.id_user',
                'user.nama',
                'user.foto_profile',
                'user.email',
                'user.no_hp',
                DB::raw('COALESCE(teknisi.rating_avg, 0) as rating')
            )
            ->first();

        // gambar layanan
        $gambar = DB::table('gambar_layanan')
            ->where('id_teknisi', $idTeknisi)
            ->where('id_keahlian', $idKeahlian)
            ->pluck('url_gambar')
            ->toArray();

        if (empty($gambar)) {
            $gambar[] = "/uploads/default_layanan.jpg";
        }

        // harga min max
        $harga = DB::table('keahlian_teknisi')
            ->where('id_teknisi', $idTeknisi)
            ->where('id_keahlian', $idKeahlian)
            ->select(
                'harga_min',
                'harga_max'
            )
            ->first();


        // ulasan
        $ulasan = DB::table('ulasan')
            ->join('user', 'user.id_user', '=', 'ulasan.id_pelanggan')
            ->where('ulasan.id_teknisi', $idTeknisi)
            ->select(
                'user.nama',
                'user.foto_profile',
                'ulasan.komentar',
                'ulasan.rating'
            )
            ->get();


        // jumlah pesanan
        $totalPemesanan = DB::table('pemesanan')
            ->where('id_teknisi', $idTeknisi)
            ->count();

        // alamat default
        $alamat = DB::table('alamat')
            ->where('id_user', $teknisi->id_user)
            ->where('is_default', 1)
            ->value('alamat_lengkap') ?? '-';

        return response()->json([
            "nama" => $teknisi->nama,
            "rating" => $teknisi->rating,
            "harga_min" => $harga->harga_min ?? 0,
            "harga_max" => $harga->harga_max ?? 0,
            "gambar" => $gambar,
            "ulasan" => $ulasan,
            "garansi" => 5,
            "lokasi" => $alamat ?? "Tidak terdapat alamat default",
            "total_pesanan" => $totalPemesanan
        ]);
    }





}
