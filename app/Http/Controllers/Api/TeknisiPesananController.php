<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use App\Services\Notify;


class TeknisiPesananController extends Controller
{
    public function pesananBaru(Request $request)
    {
        $teknisiUserId = DB::table('teknisi')
            ->where('id_user', $request->user()->id_user)
            ->value('id_teknisi');

            if (!$teknisiUserId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teknisi tidak ditemukan pada user ini',
                    'id_user' => $request->user()->id_user
                ]);
            }


        $data = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->leftJoin('user as teknisi_user', 'teknisi.id_user', '=', 'teknisi_user.id_user')
            ->where('pemesanan.id_teknisi', $teknisiUserId)
            ->where('pemesanan.status_pekerjaan', 'menunggu_diterima')
            ->select(
                'pemesanan.id_pemesanan',
                'pemesanan.kode_pemesanan',
                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.keluhan',
                'pemesanan.harga',
                'pemesanan.status_pekerjaan',

                // pelanggan
                'pelanggan.nama as nama_pelanggan',
                'pelanggan.no_hp as no_hp',

                // alamat
                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.provinsi',

                // keahlian
                'keahlian.nama_keahlian',

                // teknisi
                'teknisi_user.nama as nama_teknisi',

            )
            ->orderBy('pemesanan.id_pemesanan', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function dijadwalkan(Request $request)
    {
        $authId = auth()->id();

        \Log::info("DIJADWALKAN - AUTH USER:", [
            'auth_id' => $authId
        ]);

        // Ambil id_teknisi berdasar user login
        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $authId)
            ->value('id_teknisi');

        \Log::info("DIJADWALKAN - ID TEKNISI:", [
            'id_teknisi' => $idTeknisi
        ]);

        if (!$idTeknisi) {
            \Log::warning("TEKNISI TIDAK DITEMUKAN UNTUK USER:", [
                'auth_id' => $authId
            ]);

            return response()->json([
                'status' => true,
                'data' => []
            ]);
        }

        // Ambil data pesanan
        $data = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('foto_keluhan as fk', 'fk.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->where('pemesanan.id_teknisi', $idTeknisi)
            ->where('pemesanan.status_pekerjaan', 'dijadwalkan')
            ->select(
                'pemesanan.*',
                'pelanggan.nama as nama_pelanggan',
                'pelanggan.no_hp',
                'alamat.alamat_lengkap',
                'alamat.kota',
                'keahlian.nama_keahlian',
                DB::raw("GROUP_CONCAT(fk.foto_keluhan SEPARATOR '|') as foto_keluhan")
            )
            ->groupBy('pemesanan.id_pemesanan')
            ->orderBy('pemesanan.id_pemesanan', 'DESC')
            ->get();



        \Log::info("DIJADWALKAN - JUMLAH DATA:", [
            'count' => $data->count()
        ]);

        $data->transform(function ($item) {
            $item->foto_keluhan = $item->foto_keluhan 
                ? explode('|', $item->foto_keluhan) 
                : [];
            return $item;
        });


        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function pesananBerjalan(Request $request)
    {
        $authId = $request->user()->id_user;

        // Ambil id teknisi berdasarkan user login
        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $authId)
            ->value('id_teknisi');

        if (!$idTeknisi) {
            return response()->json([
                'status' => true,
                'data' => []
            ]);
        }

        $data = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->join('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')

            // JOIN lokasi teknisi
            ->leftJoin('lokasi_teknisi', 'lokasi_teknisi.id_teknisi', '=', 'teknisi.id_teknisi')

            ->where('pemesanan.id_teknisi', $idTeknisi)
            ->whereIn('pemesanan.status_pekerjaan', [
                'menuju_lokasi',
                'sedang_bekerja'
            ])
            ->select(
                'pemesanan.*',

                // pelanggan
                'pelanggan.nama as nama_pelanggan',
                'pelanggan.no_hp as no_hp',

                // alamat pelanggan
                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.latitude',
                'alamat.longitude',

                // lokasi teknisi
                'lokasi_teknisi.latitude as latitude_teknisi',
                'lokasi_teknisi.longitude as longitude_teknisi',

                // keahlian
                'keahlian.nama_keahlian'
            )
            ->orderBy('pemesanan.updated_at', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }



    public function terimaPekerjaan(Request $request, $id)
    {
        try {
            $user = $request->user();
            \Log::info("TERIMA PEKERJAAN - User Login:", [
                'auth_id' => $user->id_user ?? null
            ]);

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Token tidak valid'], 401);
            }

            // Ambil id_teknisi berdasarkan user
            $id_teknisi = DB::table('teknisi')->where('id_user', $user->id_user)->value('id_teknisi');

            \Log::info("TEKNISI IDENTIFIKASI:", [
                'id_user' => $user->id_user,
                'id_teknisi' => $id_teknisi
            ]);

            if (!$id_teknisi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Akun ini bukan teknisi'
                ], 403);
            }

            // Update status
            $updated = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->where('id_teknisi', $id_teknisi)
                ->where('status_pekerjaan', 'menunggu_diterima')
                ->update([
                    'status_pekerjaan' => 'dijadwalkan',
                    'updated_at' => now()
                ]);

            \Log::info("HASIL UPDATE:", [
                'id_pemesanan' => $id,
                'updated' => $updated
            ]);

            if ($updated) {
                $pemesanan = Pemesanan::find($id);
                $pelangganId = DB::table('pemesanan')
                        ->where('id_pemesanan', $id)
                        ->value('id_pelanggan');
                
                $pemesanan = Pemesanan::find($id);
                Notify::statusChanged($pemesanan->id_pelanggan, 'dijadwalkan');

                \Log::info("DATA SETELAH UPDATE:", [
                    'data' => $pemesanan
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Pekerjaan berhasil diterima dan dijadwalkan',
                    'data' => $pemesanan
                ]);
            }

            // Jika gagal update → cek penyebab
            $row = DB::table('pemesanan')->where('id_pemesanan', $id)->first();

            \Log::warning("UPDATE GAGAL, CEK DETAIL:", [
                'id_teknisi_di_pesanan' => $row->id_teknisi ?? null,
                'status_pekerjaan' => $row->status_pekerjaan ?? null
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Pekerjaan tidak dapat diproses'
            ]);

        } catch (\Exception $e) {
            \Log::error('ERROR TERIMA PEKERJAAN: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function mulaiKerja(Request $request, $id)
    {
        try {

            \Log::info("=== MULAI KERJA - REQUEST MASUK ===", [
                'id_pemesanan' => $id,
                'auth_user' => $request->user()->id_user ?? null
            ]);

            $user = $request->user();

            if (!$user) {
                \Log::warning("Token tidak valid atau user tidak dikenali");
                return response()->json([
                    'status' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            // 🔵 Ambil ID teknisi berdasarkan user
            $id_teknisi = DB::table('teknisi')
                ->where('id_user', $user->id_user)
                ->value('id_teknisi');

            \Log::info("ID TEKNISI TERKAIT USER:", [
                'id_user' => $user->id_user,
                'id_teknisi' => $id_teknisi
            ]);

            if (!$id_teknisi) {
                \Log::error("User ini bukan teknisi! Tidak punya id_teknisi");
                return response()->json([
                    'status' => false,
                    'message' => 'Akun ini bukan teknisi'
                ], 403);
            }

            // 🔵 Cek dulu status terkini pesanan
            $cekStatus = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->select('id_teknisi', 'status_pekerjaan')
                ->first();

            \Log::info("STATUS SEBELUM UPDATE:", [
                'id_pemesanan' => $id,
                'id_teknisi_di_db' => $cekStatus->id_teknisi ?? null,
                'status_pekerjaan' => $cekStatus->status_pekerjaan ?? null
            ]);

            // 🔵 Update status: dijadwalkan → menuju_lokasi
            $updated = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->where('id_teknisi', $id_teknisi)
                ->where('status_pekerjaan', 'dijadwalkan')
                ->update([
                    'status_pekerjaan' => 'menuju_lokasi',
                    'updated_at' => now()
                ]);

            \Log::info("HASIL UPDATE:", [
                'berhasil_update' => $updated
            ]);

            if (!$updated) {

                $pelangganId = DB::table('pemesanan')
                        ->where('id_pemesanan', $id)
                        ->value('id_pelanggan');
                

                Notify::statusChanged($pelangganId, 'menuju_lokasi');


                \Log::warning("GAGAL UPDATE STATUS 'dijadwalkan' → 'menuju_lokasi'", [
                    'id_pemesanan' => $id,
                    'id_teknisi' => $id_teknisi,
                    'status_sebelumnya' => $cekStatus->status_pekerjaan ?? 'UNKNOWN'
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Gagal memulai pekerjaan, cek status sebelumnya'
                ], 400);
            }

            // 🔵 Ambil kembali data lengkap pesanan setelah update
            $data = DB::table('pemesanan')
                ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
                ->join('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
                ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
                ->where('pemesanan.id_pemesanan', $id)
                ->select(
                    'pemesanan.*',
                    'pelanggan.nama as nama_pelanggan',
                    'pelanggan.no_hp as no_hp',
                    'alamat.alamat_lengkap',
                    'alamat.kota',
                    'keahlian.nama_keahlian'
                )
                ->first();

            \Log::info("DATA SETELAH UPDATE:", [
                'data' => $data
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Teknisi menuju lokasi',
                'data' => $data
            ]);

        } catch (\Exception $e) {

            \Log::error("ERROR MULAI KERJA:", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function sampaiLokasi(Request $request, $id)
    {
        try {
            \Log::info("=== SAMPAI Lokasi - REQUEST ===", [
                'id_pemesanan' => $id,
                'user_login' => $request->user()->id_user ?? null
            ]);

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            // Ambil id teknisi
            $id_teknisi = DB::table('teknisi')
                ->where('id_user', $user->id_user)
                ->value('id_teknisi');

            if (!$id_teknisi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Akun ini bukan teknisi'
                ], 403);
            }

            // Cek status sekarang
            $cek = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->where('id_teknisi', $id_teknisi)
                ->first();

            if (!$cek) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ], 404);
            }

            if ($cek->status_pekerjaan !== 'menuju_lokasi') {
                return response()->json([
                    'status' => false,
                    'message' => 'Status harus menuju_lokasi untuk bisa sampai lokasi',
                    'status_sekarang' => $cek->status_pekerjaan
                ], 400);
            }

            // Update status
            DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->where('id_teknisi', $id_teknisi)
                ->update([
                    'status_pekerjaan' => 'sedang_bekerja',
                    'updated_at' => now()
                ]);

            \Log::info("✅ STATUS DIUBAH KE SEDANG_BEKERJA", [
                'id_pemesanan' => $id
            ]);

            $pelangganId = $cek->id_pelanggan;
            Notify::statusChanged($pelangganId, 'sedang_bekerja');

            return response()->json([
                'status' => true,
                'message' => 'Teknisi sudah sampai lokasi dan mulai bekerja'
            ]);

        } catch (\Exception $e) {
            \Log::error("ERROR SAMPAI LOKASI:", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function sedangBekerja(Request $request)
    {
        $authId = $request->user()->id_user;

        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $authId)
            ->value('id_teknisi');

        if (!$idTeknisi) {
            return response()->json([
                'status' => true,
                'data' => []
            ]);
        }

        $data = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->where('pemesanan.id_teknisi', $idTeknisi)
            ->where('pemesanan.status_pekerjaan', 'sedang_bekerja')
            ->select(
                'pemesanan.kode_pemesanan',
                'pelanggan.nama as nama_pelanggan',
                'pemesanan.id_keahlian',
                'pemesanan.id_alamat',
                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.keluhan',
                'pemesanan.harga',
                'pemesanan.status_pekerjaan'
            )
            ->orderBy('pemesanan.updated_at', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }



    public function selesaikanPekerjaan(Request $request, $id)
    {
        $user = $request->user();

        Log::info("[SELESAIKAN] incoming request by user_id={$user->id_user} for order_id={$id}", $request->all());


        // cari id_teknisi dari user
        $id_teknisi = DB::table('teknisi')
            ->where('id_user', $user->id_user)
            ->value('id_teknisi');

        // validasi teknisi ditemukan
        if (!$id_teknisi) {
            Log::warning("[SELESAIKAN] teknisi tidak ditemukan untuk user {$user->id_user}");
            return response()->json(['status' => false, 'message' => 'Akun teknisi tidak ditemukan'], 404);
        }

        // Cek apakah ada bukti pekerjaan minimal 1 foto
        $adaBukti = DB::table('bukti_pekerjaan')
            ->where('id_pemesanan', $id)
            ->where('id_teknisi', $id_teknisi)
            ->exists();

        if (!$adaBukti) {
            Log::info("[SELESAIKAN] order {$id} oleh teknisi {$id_teknisi} gagal - belum ada bukti");
            return response()->json([
                'status' => false,
                'message' => 'Upload foto bukti dulu sebelum menyelesaikan pekerjaan'
            ], 400);
        }

        // Ambil order via model supaya relasi & observer bekerja
        $order = Pemesanan::where('id_pemesanan', $id)
            ->where('id_teknisi', $id_teknisi)
            ->first();

        if (!$order) {
            Log::warning("[SELESAIKAN] order {$id} tidak ditemukan atau bukan milik teknisi {$id_teknisi}");
            return response()->json(['status' => false, 'message' => 'Order tidak ditemukan'], 404);
        }

        // Update status menjadi pending verifikasi pelanggan (ESCROW flow)
        $order->status_pekerjaan = 'selesai_pending_verifikasi';
        $order->updated_at = now();
        $order->save();

        Log::info("[SELESAIKAN] teknisi {$id_teknisi} menandai selesai untuk order {$id} (kode: {$order->kode_pemesanan})");

        // Notifikasi ke pelanggan: pakai mapping statusChanged
        Notify::statusChanged($order->id_pelanggan, 'selesai_pending_verifikasi');

        // Optional: notify teknisi bahwa permintaan selesai terkirim
        $techUserId = DB::table('teknisi')->where('id_teknisi', $id_teknisi)->value('id_user');
        if ($techUserId) {
            Notify::send($techUserId, 'Pengajuan Selesai Terkirim', "Pengajuan selesai untuk order {$order->kode_pemesanan} telah dikirim ke pelanggan untuk verifikasi.");
        }

        return response()->json([
            'status' => true,
            'message' => 'Pekerjaan telah diajukan selesai dan menunggu konfirmasi pelanggan'
        ]);
    }


}
