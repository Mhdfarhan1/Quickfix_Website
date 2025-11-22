<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LokasiTeknisi;

class LokasiController extends Controller
{
    public function update(Request $request)
    {
        \Log::info("API update lokasi dipanggil", $request->all());

        // Validasi input
        $request->validate([
            'id_teknisi' => 'required|integer',
            'latitude'   => 'required',
            'longitude'  => 'required'
        ]);

        // Simpan lokasi ke DB
        $lokasi = LokasiTeknisi::updateOrCreate(
            ['id_teknisi' => $request->id_teknisi],   // cek teknisi
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Lokasi teknisi diperbarui',
            'data' => $lokasi
        ]);
    }

    public function getLokasi($id_teknisi)
    {
        $lokasi = LokasiTeknisi::where('id_teknisi', $id_teknisi)->first();

        if (!$lokasi) {
            return response()->json([
                'status' => false,
                'message' => 'Lokasi teknisi tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $lokasi
        ]);
    }
}
