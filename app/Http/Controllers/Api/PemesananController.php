<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Pemesanan;
use Illuminate\Validation\ValidationException;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use App\Services\Notify;


class PemesananController extends Controller
{
    public function getPemesanan(Request $request)
    {
        $start = microtime(true);

        $id_teknisi = $request->query('id_teknisi');
        $id_pelanggan = $request->query('id_pelanggan');
        $limit = $request->query('limit', 10);

        $query = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('user as teknisi_user', 'teknisi.id_user', '=', 'teknisi_user.id_user')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->leftJoin('bukti_pekerjaan', 'pemesanan.id_pemesanan', '=', 'bukti_pekerjaan.id_pemesanan')
            ->select(
                'pemesanan.id_pemesanan',
                'pemesanan.kode_pemesanan',
                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.keluhan',
                'pemesanan.harga',
                'pemesanan.status_pekerjaan as status',

                'pelanggan.nama as nama_pelanggan',
                'keahlian.nama_keahlian',

                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.provinsi',

                'bukti_pekerjaan.foto_bukti',

                'pemesanan.payment_status',
                'pemesanan.payment_type',

                'teknisi_user.nama as nama_teknisi',
                'teknisi_user.foto_profile as foto_teknisi' // ✅ AMBIL DARI USER
            );


        if ($id_teknisi) $query->where('pemesanan.id_teknisi', $id_teknisi);
        if ($id_pelanggan) $query->where('pemesanan.id_pelanggan', $id_pelanggan);

        $data = $query->orderByDesc('pemesanan.id_pemesanan')->limit($limit)->get();

        $data->transform(function ($item) {

            $item->foto_teknisi_url = $item->foto_teknisi
                ? url('storage/foto/foto_teknisi/' . $item->foto_teknisi)
                : url('storage/default.png');

            $item->foto_bukti_url = $item->foto_bukti
                ? url('storage/foto/bukti/' . $item->foto_bukti)
                : null;

            return $item;
        });


        $duration = round(microtime(true) - $start, 3);

        return response()->json([
            'status' => true,
            'count' => $data->count(),
            'duration' => $duration,
            'data' => $data,
        ]);
    }

    public function getPemesananByUser(Request $request)
    {
        $idUser = $request->user()->id_user;

        $data = DB::table('pemesanan')
            ->select(
                'pemesanan.id_pemesanan',
                'pemesanan.kode_pemesanan',
                'pemesanan.id_teknisi',

                'pelanggan.nama as nama_pelanggan',

                'u_teknisi.nama as nama_teknisi',
                'u_teknisi.foto_profile as foto_teknisi',

                'keahlian.nama_keahlian',

                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.harga',
                'pemesanan.keluhan',

                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.provinsi',

                'bukti_pekerjaan.foto_bukti',

                'pemesanan.status_pekerjaan as status'
            )
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->leftJoin('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->leftJoin('user as u_teknisi', 'teknisi.id_user', '=', 'u_teknisi.id_user')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('alamat', function ($q) {
                $q->on('pelanggan.id_user', '=', 'alamat.id_user')
                    ->where('alamat.is_default', 1);
            })
            ->leftJoin('bukti_pekerjaan', 'pemesanan.id_pemesanan', '=', 'bukti_pekerjaan.id_pemesanan')
            ->where('pemesanan.id_pelanggan', $idUser)
            ->orderBy('pemesanan.id_pemesanan', 'desc')
            ->limit(50)
            ->get();

        foreach ($data as $item) {
            $item->foto_teknisi_url = $item->foto_teknisi
                ? url('storage/foto/foto_teknisi/' . $item->foto_teknisi)
                : url('storage/default.png');

            $item->foto_bukti_url = $item->foto_bukti
                ? url('storage/foto/bukti/' . $item->foto_bukti)
                : "";
        }

        return response()->json($data);
    }




    public function addPemesanan(Request $request)
    {
        try {
            $request->validate([
                'id_pelanggan' => 'required|exists:user,id_user',
                'id_teknisi' => 'required|exists:teknisi,id_teknisi',
                'id_keahlian' => 'required|exists:keahlian,id_keahlian',
                'tanggal_booking' => 'required|date',
                'jam_booking' => 'required|date_format:H:i:s', // ✅ tambahkan ini
                'keluhan' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'id_alamat' => 'nullable|exists:alamat,id_alamat',
            ]);

            $idAlamat = $request->id_alamat ?: DB::table('alamat')
                ->where('id_user', $request->id_pelanggan)
                ->where('is_default', true)
                ->value('id_alamat');

            if (!$idAlamat) {
                return response()->json(['status' => false, 'message' => 'Alamat belum dipilih'], 400);
            }

            $fotoKeluhanPath = null;

            if ($request->hasFile('foto_keluhan')) {
                $fotoKeluhanPath = $request->file('foto_keluhan')
                    ->store('foto_keluhan', 'public');
            }


            $prefix = 'QFX-' . date('Y') . '-';
            $latest = Pemesanan::latest('id_pemesanan')->first();
            $nextNumber = $latest ? ((int) substr($latest->kode_pemesanan, -4)) + 1 : 1;
            $kode = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $pemesanan = Pemesanan::create([
                'kode_pemesanan' => $kode,
                'id_pelanggan' => $request->id_pelanggan,
                'id_teknisi' => $request->id_teknisi,
                'id_keahlian' => $request->id_keahlian,
                'id_alamat' => $idAlamat,
                'tanggal_booking' => $request->tanggal_booking,
                'jam_booking' => $request->jam_booking, // ✅ tambahkan ini
                'keluhan' => $request->keluhan,
                'status_pekerjaan' => 'menunggu_diterima',
                'harga' => $request->harga,
                'payment_status' => 'pending',
            ]);

            DB::table('foto_keluhan')->insert([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'foto_keluhan' => $fotoKeluhanPath,
                'created_at' => now()
            ]);


            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $kode,
                    'gross_amount' => (int) $request->harga,
                ],
                'customer_details' => [
                    'first_name' => DB::table('user')->where('id_user', $request->id_pelanggan)->value('nama'),
                    'email' => DB::table('user')->where('id_user', $request->id_pelanggan)->value('email'),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

            $pemesanan->update([
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pemesanan berhasil dibuat.',
                'data' => [
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'kode_pemesanan' => $kode,
                    'payment_url' => $paymentUrl,
                    'payment_status' => 'pending',
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function batalkanPemesanan(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Token tidak valid'], 401);
            }

            // Ambil pesanan
            $pemesanan = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->first();

            if (!$pemesanan) {
                return response()->json(['status' => false, 'message' => 'Pemesanan tidak ditemukan'], 404);
            }

            // Pastikan yang membatalkan adalah pemilik (pelanggan)
            if ($pemesanan->id_pelanggan != $user->id_user) {
                return response()->json(['status' => false, 'message' => 'Anda tidak memiliki izin untuk membatalkan pesanan ini'], 403);
            }

            // Cek status yang diizinkan untuk dibatalkan
            $boleh = in_array($pemesanan->status_pekerjaan, ['menunggu_diterima', 'dijadwalkan']);

            if (!$boleh) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pesanan tidak dapat dibatalkan pada status saat ini',
                    'status_sekarang' => $pemesanan->status_pekerjaan
                ], 400);
            }

            // Update status ke 'dibatalkan'
            $updated = DB::table('pemesanan')
                ->where('id_pemesanan', $id)
                ->where('id_pelanggan', $user->id_user)
                ->update([
                    'status_pekerjaan' => 'batal',
                    'updated_at' => now()
                ]);

            if (!$updated) {
                return response()->json(['status' => false, 'message' => 'Gagal membatalkan pesanan'], 500);
            }

            // Load model untuk notifikasi / pengembalian data
            $pemesananModel = Pemesanan::find($id);

            // Notifikasi: beri tahu teknisi jika ada teknisi terkait
            $idTeknisi = DB::table('pemesanan')->where('id_pemesanan', $id)->value('id_teknisi');
            if ($idTeknisi) {
                try {
                    Notify::statusChanged($idTeknisi, 'batal');
                } catch (\Exception $e) {
                    Log::warning('Gagal kirim notifikasi ke teknisi: ' . $e->getMessage());
                }
            }

            // Notifikasi ke pelanggan juga (opsional)
            try {
                Notify::statusChanged($user->id_user, 'batal');
            } catch (\Exception $e) {
                Log::warning('Gagal kirim notifikasi ke pelanggan: ' . $e->getMessage());
            }

            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil dibatalkan',
                'data' => $pemesananModel
            ]);

        } catch (\Exception $e) {
            \Log::error('ERROR BATALKAN PESANAN: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan server'], 500);
        }
    }

    public function pelangganKonfirmasi(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'pelanggan') {
            return response()->json(['status' => false, 'message' => 'Anda bukan pelanggan'], 403);
        }

        $p = Pemesanan::find($id);

        if (!$p) {
            return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($p->id_pelanggan != $user->id_user) {
            return response()->json(['status' => false, 'message' => 'Anda tidak memiliki izin'], 403);
        }

        if ($p->status_pekerjaan !== 'selesai_pending_verifikasi') {
            return response()->json(['status' => false, 'message' => 'Status tidak valid'], 400);
        }

        if (!in_array($p->payment_status, ['settlement', 'settled'])) {
            return response()->json(['status' => false, 'message' => 'Pembayaran belum settled'], 400);
        }

        try {
            // update verification
            $p->status_pekerjaan = 'selesai_confirmed';
            $p->verifikasi_by_customer = 1;
            $p->verifikasi_at = now();
            $p->payout_eligible_at = now();
            $p->save();

            // call payout service
            $payout = app(\App\Services\PayoutService::class)->create($p);

            return response()->json([
                'status' => true,
                'message' => 'Pekerjaan selesai. Payout akan diproses.',
                'payout_id' => $payout->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Payout error: '.$e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pelangganKomplain(Request $request, $id)
    {
        $user = $request->user();

        $p = Pemesanan::find($id);
        if (!$p) return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);

        if ($p->status_pekerjaan !== 'menunggu_konfirmasi_pelanggan') {
            return response()->json(['status' => false, 'message' => 'Tidak bisa komplain di status ini'], 400);
        }

        $p->status_pekerjaan = 'dispute';
        $p->save();

        return response()->json([
            'status' => true,
            'message' => 'Komplain diajukan. Menunggu keputusan admin.'
        ]);
    }

    public function getPemesananById($id)
    {
        $data = DB::table('pemesanan')
            ->where('id_pemesanan', $id)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Pemesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    
}
