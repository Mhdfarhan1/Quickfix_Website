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

class PemesananController extends Controller
{
    /**
     * ✅ Ambil data pemesanan (filter by id_teknisi / id_pelanggan)
     * Masih bisa dipakai secara manual via URL.
     */
    public function getPemesanan(Request $request)
    {
        $start = microtime(true);

        $id_teknisi = $request->query('id_teknisi');
        $id_pelanggan = $request->query('id_pelanggan');

        $query = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('user as teknisi_user', 'teknisi.id_user', '=', 'teknisi_user.id_user')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->leftJoin('bukti_pekerjaan', 'pemesanan.id_pemesanan', '=', 'bukti_pekerjaan.id_pemesanan')
            ->select(
                'pemesanan.*',
                'pelanggan.nama as nama_pelanggan',
                'keahlian.nama_keahlian',
                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.provinsi',
                'bukti_pekerjaan.foto_bukti',
                'teknisi_user.nama as nama_teknisi'
            );

        if ($id_teknisi) $query->where('pemesanan.id_teknisi', $id_teknisi);
        if ($id_pelanggan) $query->where('pemesanan.id_pelanggan', $id_pelanggan);

        $data = $query->limit(1)->get();
            $duration = round(microtime(true) - $start, 3);
            \Log::info("⏱ Query getPemesanan limit(1) dalam {$duration} detik");
            return response()->json(['count' => $data->count(), 'duration' => $duration]);

    }


    /**
     * 🧠 Versi otomatis: ambil data berdasarkan token login user
     * Endpoint: GET /api/get_pemesanan_by_user
     */
    public function getPemesananByUser(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid atau belum login.',
            ], 401);
        }

        $query = DB::table('pemesanan')
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')
            ->join('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'pemesanan.id_pemesanan',
                'pemesanan.kode_pemesanan',
                'pelanggan.nama as nama_pelanggan',
                'keahlian.nama_keahlian',
                'pemesanan.tanggal_booking',
                'pemesanan.keluhan',
                'pemesanan.status',
                'pemesanan.harga'
            );

        // Cek role user untuk menentukan filter
        if ($user->role === 'teknisi') {
            $query->where('pemesanan.id_teknisi', DB::table('teknisi')->where('id_user', $user->id_user)->value('id_teknisi'));
        } elseif ($user->role === 'pelanggan') {
            $query->where('pemesanan.id_pelanggan', $user->id_user);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak diizinkan mengakses data pemesanan.',
            ], 403);
        }

        $data = $query->orderByDesc('pemesanan.id_pemesanan')->limit(50)->get();
        

        return response()->json([
            'status' => true,
            'count' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * 🆕 Tambah data pemesanan baru
     * Endpoint: POST /api/add_pemesanan
     */
    public function addPemesanan(Request $request)
    {
        \Log::info('MIDTRANS SERVER KEY:', [config('midtrans.server_key')]);

        try {
            $request->validate([
                'id_pelanggan' => 'required|exists:user,id_user',
                'id_teknisi' => 'required|exists:teknisi,id_teknisi',
                'id_keahlian' => 'required|exists:keahlian,id_keahlian',
                'tanggal_booking' => 'required|date',
                'keluhan' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
            ]);

            $alamatDefault = DB::table('alamat')
                ->where('id_user', $request->id_pelanggan)
                ->where('is_default', true)
                ->first();

            if (!$alamatDefault) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pelanggan belum memiliki alamat default.'
                ], 400);
            }

            $prefix = 'QFX-' . date('Y') . '-';
            $latest = Pemesanan::latest('id_pemesanan')->first();
            $nextNumber = $latest ? ((int) substr($latest->kode_pemesanan, -4)) + 1 : 1;
            $kode = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Simpan data awal
            $pemesanan = Pemesanan::create([
                'kode_pemesanan' => $kode,
                'id_pelanggan' => $request->id_pelanggan,
                'id_teknisi' => $request->id_teknisi,
                'id_keahlian' => $request->id_keahlian,
                'id_alamat' => $alamatDefault->id_alamat,
                'tanggal_booking' => $request->tanggal_booking,
                'keluhan' => $request->keluhan,
                'status' => 'menunggu',
                'harga' => $request->harga,
                'payment_status' => 'pending',
            ]);

            // 🔹 Integrasi Midtrans
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
                    'payment_status' => 'pending'
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }

}
