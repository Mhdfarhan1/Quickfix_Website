<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ← tambahkan ini
use Illuminate\Http\Request;
use App\Models\BuktiPekerjaan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 


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
                $item->url = url('storage/foto/bukti/' . $item->foto_bukti);
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
                        ? url('storage/foto/bukti/' . $item->foto_bukti)
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
                $item->url = url('storage/foto/bukti/' . $item->foto_bukti);
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

    public function getByPemesanan($id_pemesanan)
    {
        $bukti = BuktiPekerjaan::where('id_pemesanan', $id_pemesanan)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                $item->url = url('storage/foto/bukti/' . $item->foto_bukti);
                return $item;
            });

        return response()->json([
            'status' => true,
            'total' => $bukti->count(),
            'data' => $bukti
        ]);
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'foto'     => 'required|array',
            'foto.*'   => 'image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $user = $request->user();

        $id_teknisi = DB::table('teknisi')
            ->where('id_user', $user->id_user)
            ->value('id_teknisi');

        $pemesanan = DB::table('pemesanan')
            ->where('id_pemesanan', $id)
            ->where('id_teknisi', $id_teknisi)
            ->where('status_pekerjaan', 'sedang_bekerja')
            ->first();

        if (!$pemesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Pemesanan tidak valid'
            ], 403);
        }

        $files = $request->file('foto');
        $data = [];

        foreach ($files as $file) {

            $namaFile = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // simpan ke: storage/app/public/bukti
            $file->storeAs('foto/bukti', $namaFile, 'public');

            // simpan ke DB: hanya nama file
            DB::table('bukti_pekerjaan')->insert([
                'id_pemesanan' => $id,
                'id_teknisi'   => $id_teknisi,
                'id_keahlian'  => $pemesanan->id_keahlian,
                'deskripsi'    => null,
                'foto_bukti'   => $namaFile, // ✅ hanya nama file
                'created_at'   => now(),
                'updated_at'   => now()
            ]);

            $data[] = [
                'nama_file' => $namaFile,
                'url' => asset('storage/foto/bukti/' . $namaFile)

            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Foto bukti berhasil diupload',
            'total_upload' => count($data),
            'data' => $data
        ], 201);
    }


    /**
     * Menghapus bukti pekerjaan.
     */
    public function destroy($id)
    {
        $bukti = BuktiPekerjaan::findOrFail($id);

        // Hapus file dari storage
        if (Storage::disk('public')->exists('foto/bukti/' . $bukti->foto_bukti)) {
            Storage::disk('public')->delete('foto/bukti/' . $bukti->foto_bukti);
        }


        $bukti->delete();

        return response()->json(['message' => 'bukti pekerjaan berhasil dihapus.']);
    }
}
