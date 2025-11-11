<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class KeranjangController extends Controller
{
    // ✅ 1. Ambil semua item keranjang milik user
    public function getKeranjang(Request $request)
    {
        \Log::info('📦 getKeranjang dipanggil dengan id_pelanggan=' . $request->id_pelanggan);

        $idPelanggan = $request->id_pelanggan; // ✅ ambil dari query string

        if (!$idPelanggan) {
            return response()->json(['error' => 'id_pelanggan tidak ditemukan'], 400);
        }

        $keranjang = DB::table('keranjang')
            ->join('teknisi', 'keranjang.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('user as teknisi_user', 'teknisi.id_user', '=', 'teknisi_user.id_user')
            ->join('keahlian', 'keranjang.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'keranjang.id_keranjang',
                'teknisi_user.nama as nama_teknisi',
                'keahlian.nama_keahlian',
                'keranjang.harga',
                'keranjang.catatan',
                'teknisi_user.foto_profile as foto_teknisi'
            )
            ->where('keranjang.id_pelanggan', $idPelanggan)
            ->orderByDesc('keranjang.id_keranjang')
            ->get();

        return response()->json($keranjang);
    }

    // ✅ 2. Tambahkan item ke keranjang
    public function addKeranjang(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:user,id_user',
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'harga' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $item = Keranjang::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Layanan ditambahkan ke keranjang',
            'data' => $item,
        ], 201);
    }

    // ✅ 3. Hapus item dari keranjang
    public function deleteKeranjang($id)
    {
        $item = Keranjang::find($id);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        $item->delete();

        return response()->json(['status' => true, 'message' => 'Item dihapus dari keranjang']);
    }

    // ✅ 4. Checkout semua item di keranjang
    public function checkout(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:user,id_user',
        ]);

        $items = Keranjang::where('id_pelanggan', $request->id_pelanggan)->get();
        if ($items->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Keranjang kosong'], 400);
        }

        // Buat satu pemesanan per item (bisa kamu ubah jadi 1 order gabungan kalau mau)
        foreach ($items as $item) {
            Pemesanan::create([
                'id_pelanggan' => $item->id_pelanggan,
                'id_teknisi' => $item->id_teknisi,
                'id_keahlian' => $item->id_keahlian,
                'tanggal_booking' => now()->toDateString(),
                'jam_booking' => now()->format('H:i:s'),
                'harga' => $item->harga,
                'status_pekerjaan' => 'menunggu_diterima',
            ]);
        }

        // Kosongkan keranjang setelah checkout
        Keranjang::where('id_pelanggan', $request->id_pelanggan)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Checkout berhasil, pesanan dibuat'
        ]);
    }
}
