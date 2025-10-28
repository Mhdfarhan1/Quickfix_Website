<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuktiPekerjaan;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    /**
     * Menampilkan semua bukti pekerjaan.
     */
    public function index()
    {
        $bukti = BuktiPekerjaan::with(['teknisi', 'keahlian', 'pemesanan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->url = asset('storage/uploads/bukti/' . $item->foto_bukti);
                return $item;
            });

        return response()->json($bukti);
    }

    /**
     * Menampilkan bukti pekerjaan berdasarkan id_teknisi.
     */
    public function getByTeknisi($id_teknisi)
    {
        $bukti = BuktiPekerjaan::where('id_teknisi', $id_teknisi)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->url = asset('storage/uploads/bukti/' . $item->foto_bukti);
                return $item;
            });

        return response()->json($bukti);
    }

    /**
     * Menyimpan bukti pekerjaan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'deskripsi' => 'nullable|string',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan file ke storage/uploads/bukti
        $path = $request->file('foto_bukti')->store('uploads/bukti', 'public');
        $namaFile = basename($path);

        $bukti = BuktiPekerjaan::create([
            'id_pemesanan' => $request->id_pemesanan,
            'id_teknisi' => $request->id_teknisi,
            'id_keahlian' => $request->id_keahlian,
            'deskripsi' => $request->deskripsi,
            'foto_bukti' => $namaFile,
        ]);

        $bukti->url = asset('storage/' . $path);

        return response()->json([
            'message' => 'Bukti pekerjaan berhasil ditambahkan.',
            'data' => $bukti
        ]);
    }

    /**
     * Menghapus bukti pekerjaan.
     */
    public function destroy($id)
    {
        $bukti = BuktiPekerjaan::findOrFail($id);

        // Hapus file dari storage
        if (Storage::disk('public')->exists('uploads/bukti/' . $bukti->foto_bukti)) {
            Storage::disk('public')->delete('uploads/bukti/' . $bukti->foto_bukti);
        }

        $bukti->delete();

        return response()->json(['message' => 'Bukti pekerjaan berhasil dihapus.']);
    }
}
