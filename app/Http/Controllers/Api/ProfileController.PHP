<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /* --------------------------------------------------
     * 🔹 BAGIAN 1 — PROFIL USER
     * -------------------------------------------------- */

    // Ambil profil user
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // Upload / update foto profil
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:user,id_user',
            'foto_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ambil user
        $user = DB::table('user')->where('id_user', $request->id_user)->first();

        // Hapus foto lama (jika ada)
        if ($user && $user->foto_profile && Storage::exists(str_replace('storage/', 'public/', $user->foto_profile))) {
            Storage::delete(str_replace('storage/', 'public/', $user->foto_profile));
        }

        // Upload foto baru
        $path = $request->file('foto_profile')->store('foto_profile', 'public');

        DB::table('user')->where('id_user', $request->id_user)
            ->update(['foto_profile' => 'storage/' . $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupload',
            'path' => asset('storage/' . $path)
        ]);
    }

    public function update(Request $request)
    {
        // Pastikan user ter-auth
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Validasi input
        $request->validate([
            'nama'   => 'required|string|max:150',
            'email'  => 'required|email|max:150|unique:user,email,' . $user->id_user . ',id_user',
            'no_hp'  => 'nullable|string|max:30',
        ]);

        // Update
        DB::table('user')->where('id_user', $user->id_user)
            ->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'updated_at' => now(),
            ]);

        // Ambil data yang baru
        $updated = DB::table('user')->where('id_user', $user->id_user)->first();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $updated,
        ]);
    }



    /* --------------------------------------------------
     * 🔹 BAGIAN 2 — GAMBAR LAYANAN TEKNISI
     * -------------------------------------------------- */

    // Upload gambar layanan
    public function uploadGambarLayanan(Request $request)
    {
        $request->validate([
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'id_keahlian' => 'required|exists:keahlian,id_keahlian',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // simpan ke "public/gambar_layanan"
        $path = $request->file('gambar')->store('gambar_layanan', 'public');

        // simpan file path tanpа "storage/"
        $id = DB::table('gambar_layanan')->insertGetId([
            'id_teknisi' => $request->id_teknisi,
            'id_keahlian' => $request->id_keahlian,
            'nama_file' => $request->file('gambar')->getClientOriginalName(),
            'url' => asset('storage/' . $path),
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar layanan berhasil diupload',
            'id_gambar' => $id,
            'url' => asset('storage/' . $path), // untuk Flutter
        ]);
    }


    // Ambil semua gambar layanan milik teknisi
    public function getGambarLayananByTeknisi($id_teknisi)
    {
        $gambarList = DB::table('gambar_layanan')
            ->join('keahlian', 'gambar_layanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'gambar_layanan.id_gambar',
                'gambar_layanan.url_gambar',
                'keahlian.nama_keahlian',
                'keahlian.deskripsi'
            )
            ->where('gambar_layanan.id_teknisi', $id_teknisi)
            ->get();

        foreach ($gambarList as $gambar) {
            $gambar->url = asset('storage/' . $gambar->url_gambar);
        }

        return response()->json([
            'success' => true,
            'data' => $gambarList
        ]);
    }


    // Hapus gambar layanan
    public function deleteGambarLayanan($id)
    {
        $gambar = DB::table('gambar_layanan')->where('id_gambar', $id)->first();

        if (!$gambar) {
            return response()->json(['error' => 'Gambar tidak ditemukan'], 404);
        }

        // ubah ke path public
        $filePath = 'public/' . $gambar->url_gambar;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        DB::table('gambar_layanan')->where('id_gambar', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Gambar layanan berhasil dihapus']);
    }



    /* --------------------------------------------------
     * 🔹 BAGIAN 3 — BUKTI PEKERJAAN (opsional)
     * -------------------------------------------------- */

    // 🔹 Ambil semua bukti pekerjaan berdasarkan id_teknisi
        public function getBuktiPekerjaanByTeknisi($id_teknisi)
    {
        $buktiList = DB::table('bukti_pekerjaan')
            ->join('keahlian', 'bukti_pekerjaan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('pemesanan', 'bukti_pekerjaan.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->select(
                'bukti_pekerjaan.id_bukti',
                'bukti_pekerjaan.deskripsi',
                'bukti_pekerjaan.foto_bukti',
                'keahlian.nama_keahlian',
                'pemesanan.kode_pemesanan',
                'bukti_pekerjaan.created_at'
            )
            ->where('bukti_pekerjaan.id_teknisi', $id_teknisi)
            ->orderBy('bukti_pekerjaan.created_at', 'desc')
            ->get();

        foreach ($buktiList as $bukti) {
            // Pastikan foto_bukti sudah pakai format 'storage/...'
            $bukti->url = asset('storage/foto/bukti/' . $bukti->foto_bukti);
        }

        if ($buktiList->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada bukti pekerjaan untuk teknisi ini',
                'data' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $buktiList,
        ]);
    }


}
