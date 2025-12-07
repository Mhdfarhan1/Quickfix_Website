<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teknisi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                DB::raw('(SELECT ROUND(AVG(ulasan.rating), 1) 
                        FROM ulasan 
                        WHERE ulasan.id_teknisi = teknisi.id_teknisi) as rating_avg'),
                'teknisi.status',
                DB::raw('GROUP_CONCAT(DISTINCT keahlian.nama_keahlian SEPARATOR ", ") as daftar_keahlian')
            )
            ->where('teknisi.id_teknisi', $id)
            ->groupBy('teknisi.id_teknisi')
            ->first();

        if (!$teknisi) {
            return response()->json(['message' => 'Teknisi tidak ditemukan'], 404);
        }

        if ($teknisi->foto_profile) {
            $teknisi->foto_profile = url('storage/foto/foto_teknisi/' . $teknisi->foto_profile);
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
                ->leftJoin('ulasan', function ($join) {
                    $join->on('ulasan.id_teknisi', '=', 'keahlian_teknisi.id_teknisi');
                })
                ->select(
                    'keahlian_teknisi.id_teknisi',
                    'keahlian.id_keahlian',
                    'keahlian.nama_keahlian',
                    'keahlian.deskripsi',
                    'kategori.nama_kategori',
                    DB::raw('
                        CASE
                            WHEN keahlian_teknisi.gambar_layanan IS NULL OR keahlian_teknisi.gambar_layanan = ""
                            THEN "/storage/default_layanan.jpg"
                            ELSE CONCAT("/gambar_layanan/", keahlian_teknisi.gambar_layanan)
                        END AS gambar
                    '),
                    DB::raw('ROUND(AVG(ulasan.rating), 1) as rating'),
                    DB::raw('COALESCE(keahlian_teknisi.harga, 0) AS harga')
                )
                ->where('keahlian_teknisi.id_teknisi', $id)
                ->groupBy(
                    'keahlian_teknisi.id_teknisi',
                    'keahlian.id_keahlian',
                    'keahlian.nama_keahlian',
                    'keahlian.deskripsi',
                    'kategori.nama_kategori',
                    'keahlian_teknisi.gambar_layanan',
                    'keahlian_teknisi.harga'   // ← WAJIB TAMBAH
                )
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
            DB::raw('COALESCE(keahlian_teknisi.harga, 0) AS harga'),
            DB::raw('
                CASE
                    WHEN keahlian_teknisi.gambar_layanan IS NULL OR keahlian_teknisi.gambar_layanan = ""
                    THEN "/storage/default_layanan.jpg"
                    ELSE CONCAT("/gambar_layanan/", keahlian_teknisi.gambar_layanan)
                END AS gambar
            ')
        )
        ->distinct()
        ->groupBy(
            'teknisi.id_teknisi',
            'user.nama',
            'alamat.alamat_lengkap',
            'keahlian.id_keahlian',
            'keahlian.nama_keahlian',
            'teknisi.rating_avg',
            'keahlian_teknisi.gambar_layanan',
            'keahlian_teknisi.harga'

        );

        // 🔽 Sorting aman
        switch ($sort) {
            case 'rating':
                $query->orderBy(DB::raw('COALESCE(teknisi.rating_avg, 0)'), 'DESC');
                break;
            case 'harga':
                $query->orderBy('harga', 'ASC');
                break;
            default:
                $query->orderBy('user.nama', 'ASC');
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
        
        $gambar = DB::table('keahlian_teknisi')
            ->where('id_teknisi', $idTeknisi)
            ->where('id_keahlian', $idKeahlian)
            ->pluck('gambar_layanan')
            ->map(function ($path) {

                if (empty($path)) {
                    return 'foto/gambar_layanan/default_layanan.jpg';
                }

                if (str_starts_with($path, 'foto/gambar_layanan')) {
                    return $path;
                }

                return 'foto/gambar_layanan/' . ltrim($path, '/');
            })
            ->toArray();

        if (empty($gambar)) {
            $gambar[] = 'gambar_layanan/default_layanan.jpg';
        }



        // harga min max
        $harga = DB::table('keahlian_teknisi')
            ->where('id_teknisi', $idTeknisi)
            ->where('id_keahlian', $idKeahlian)
            ->value('harga');



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
            'harga' => $harga,
            "gambar" => $gambar,
            "ulasan" => $ulasan,
            "garansi" => 5,
            "lokasi" => $alamat ?? "Tidak terdapat alamat default",
            "total_pesanan" => $totalPemesanan
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'teknisi') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak, hanya teknisi yang dapat mengubah profil.'
            ], 403);
        }


        // cari data teknisi berdasarkan id_user
        $teknisi = DB::table('teknisi')->where('id_user', $user->id_user)->first();
        if (!$teknisi) {
            return response()->json([
                'status' => false,
                'message' => 'Teknisi tidak ditemukan'
            ], 404);
        }

        // validasi hanya deskripsi
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string', // wajib diisi
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // update deskripsi saja
        DB::table('teknisi')->where('id_teknisi', $teknisi->id_teknisi)
            ->update([
                'deskripsi' => $request->deskripsi,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Profil diperbarui'
        ], 200);

    }



    // Upload gambar galeri teknisi
    public function uploadGaleri(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'teknisi') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak, hanya teknisi yang dapat upload galeri.'
            ], 403);
        }

        $teknisi = DB::table('teknisi')->where('id_user', $user->id_user)->first();
        if (!$teknisi) {
            return response()->json(['status' => false, 'message' => 'Teknisi tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $path = $file->store('public/foto/galeri_teknisi'); // akan simpan di storage/app/public/foto/galeri_teknisi
        $filename = basename($path);
        $relative = 'foto/galeri_teknisi/' . $filename; // path yang akan disimpan di DB

        $id = DB::table('galeri_teknisi')->insertGetId([
            'id_teknisi' => $teknisi->id_teknisi,
            'gambar_galeri' => $relative,   // ← ganti ini
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Foto galeri berhasil diupload',
            'data' => [
                'id_galeri' => $id,
                'gambar_galeri' => url('storage/' . $relative),
            ]
        ]);
    }

    // Delete galeri
    public function deleteGaleri($id)
    {
        $user = request()->user();

        if ($user->role !== 'teknisi') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak, hanya teknisi yang dapat menghapus galeri.'
            ], 403);
        }

        $teknisi = DB::table('teknisi')->where('id_user', $user->id_user)->first();

        if (!$teknisi) {
            return response()->json(['status' => false, 'message' => 'Teknisi tidak ditemukan'], 404);
        }

        $record = DB::table('galeri_teknisi')
            ->where('id_galeri', $id)
            ->where('id_teknisi', $teknisi->id_teknisi)
            ->first();

        if (!$record) {
            return response()->json(['status' => false, 'message' => 'Galeri tidak ditemukan'], 404);
        }

        // hapus file
        $diskPath = 'public/' . $record->gambar_galeri;
        if (Storage::exists($diskPath)) {
            Storage::delete($diskPath);
        }

        DB::table('galeri_teknisi')
            ->where('id_galeri', $id)
            ->delete();

        return response()->json(['status' => true, 'message' => 'Galeri berhasil dihapus']);
    }

    public function getGaleri($id_teknisi)
    {
        $galeri = DB::table('galeri_teknisi')
            ->where('id_teknisi', $id_teknisi)
            ->orderBy('id_galeri', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $galeri->map(function ($item) {
                return [
                    'id_galeri' => $item->id_galeri,
                    'gambar_galeri' => url('storage/' . $item->gambar_galeri),
                ];
            })
        ], 200);
    }





}
