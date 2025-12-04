<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Ambil semua banner aktif untuk mobile.
     */
    public function index()
    {
        // Hanya kirim banner yang aktif
        $banners = Banner::where('is_active', true)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'judul',
                'gambar',   // contoh: "banner_promosi/namafile.jpg"
                'link',
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Daftar banner aktif',
            'data'    => $banners,
        ]);
    }
}
