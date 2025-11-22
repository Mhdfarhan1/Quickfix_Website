<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;
use Carbon\Carbon;

class TrackingController extends Controller
{
    /**
     * Simpan/update lokasi teknisi
     */
    public function storeLocation(Request $request)
    {
        $request->validate([
            'id_teknisi' => 'required|integer',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric'
        ]);

        $lokasi = Lokasi::updateOrCreate(
            ['id_teknisi' => $request->id_teknisi],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => Carbon::now()
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data' => $lokasi
        ]);
    }

    /**
     * Ambil lokasi terakhir teknisi
     */
    public function getLatestLocation($id_teknisi)
    {
        $lokasi = Lokasi::where('id_teknisi', $id_teknisi)->first();

        if (!$lokasi) {
            return response()->json([
                'status' => false,
                'message' => 'Lokasi teknisi belum tersedia.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $lokasi
        ]);
    }

    /**
     * Ambil lokasi teknisi berdasarkan id_pemesanan
     */
    public function getCustomerTracking($id_pemesanan)
    {
        $lokasi = Lokasi::where('id_pemesanan', $id_pemesanan)->first();

        if (!$lokasi) {
            return response()->json([
                'status' => false,
                'message' => 'Lokasi belum tersedia untuk pesanan ini.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $lokasi
        ]);
    }
}
