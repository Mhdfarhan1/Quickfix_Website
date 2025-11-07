<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ← tambahkan ini
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
        $bukti = BuktiPekerjaan::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->url = url('storage/bukti/' . $item->foto_bukti);
                return $item;
            });

        return response()->json([
            'status' => true,
            'data' => $bukti
        ]);
    }

    public function getRecent()
    {
        $bukti = BuktiPekerjaan::with('teknisi')
            ->orderBy('id_bukti', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_teknisi' => $item->teknisi->nama ?? 'Tidak diketahui',
                    'foto_teknisi' => $item->teknisi->foto_teknisi 
                        ? url('storage/foto_teknisi/' . $item->teknisi->foto_teknisi)
                        : null,
                    'foto_bukti' => $item->foto_bukti 
                        ? url('storage/bukti/' . $item->foto_bukti)
                        : null,
                    'deskripsi' => $item->deskripsi ?? '',
                ];
            });

        return response()->json([
            'status' => true,
            'count' => $bukti->count(),
            'data' => $bukti,
        ]);
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
                // URL lengkap ke folder public/storage/bukti/
                $item->url = url('storage/bukti/' . $item->foto_bukti);
                return $item;
            });

        return response()->json([
            'status' => true,
            'data' => $bukti
        ]);
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

        $file = $request->file('foto_bukti');
        $namaFile = $file->hashName(); // nama file unik (hash)
        $file->storeAs('bukti', $namaFile, 'public'); // simpan di storage/app/public/bukti

        $bukti = BuktiPekerjaan::create([
            'id_pemesanan' => $request->id_pemesanan,
            'id_teknisi' => $request->id_teknisi,
            'id_keahlian' => $request->id_keahlian,
            'deskripsi' => $request->deskripsi,
            'foto_bukti' => $namaFile,
        ]);

        $bukti->url = asset('storage/bukti/' . $namaFile);

        return response()->json([
            'message' => 'bukti pekerjaan berhasil ditambahkan.',
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
        if (Storage::disk('public')->exists('bukti/' . $bukti->foto_bukti)) {
            Storage::disk('public')->delete('bukti/' . $bukti->foto_bukti);
        }


        $bukti->delete();

        return response()->json(['message' => 'bukti pekerjaan berhasil dihapus.']);
    }
}
