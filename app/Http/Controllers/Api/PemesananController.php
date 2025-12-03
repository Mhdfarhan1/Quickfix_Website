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

                // pelanggan
                'pelanggan.nama as nama_pelanggan',

                // teknisi
                'u_teknisi.nama as nama_teknisi',
                'u_teknisi.foto_profile as foto_teknisi',

                // keahlian
                'keahlian.nama_keahlian',

                // booking
                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.harga',
                'pemesanan.keluhan',

                // alamat dari tabel alamat
                'alamat.alamat_lengkap',
                'alamat.kota',
                'alamat.provinsi',

                // status
                'pemesanan.status_pekerjaan as status'
            )
            ->join('user as pelanggan', 'pemesanan.id_pelanggan', '=', 'pelanggan.id_user')

            // join teknisi + user teknisi
            ->leftJoin('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->leftJoin('user as u_teknisi', 'teknisi.id_user', '=', 'u_teknisi.id_user')

            // join keahlian
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')

            // 🔥 join tabel alamat (alamat user pelanggan)
            ->leftJoin('alamat', function ($q) {
                $q->on('pelanggan.id_user', '=', 'alamat.id_user')
                ->where('alamat.is_default', 1);
            })


            ->where('pemesanan.id_pelanggan', $idUser)
            ->orderBy('pemesanan.id_pemesanan', 'desc')
            ->limit(50)
            ->get();

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

    public function getKeranjang(Request $request)
    {
        $id_pelanggan = $request->query('id_pelanggan');

        if (!$id_pelanggan) {
            return response()->json(['status' => false, 'message' => 'id_pelanggan wajib diisi'], 400);
        }

        $data = DB::table('pemesanan')
            ->join('teknisi', 'pemesanan.id_teknisi', '=', 'teknisi.id_teknisi')
            ->join('user as teknisi_user', 'teknisi.id_user', '=', 'teknisi_user.id_user')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->where('pemesanan.id_pelanggan', $id_pelanggan)
            ->where('pemesanan.status_pekerjaan', 'keranjang')
            ->select(
                'pemesanan.id_pemesanan',
                'keahlian.nama_keahlian',
                'pemesanan.harga',
                'pemesanan.keluhan',
                'teknisi_user.nama as nama_teknisi',
                'teknisi.foto_teknisi'
            )
            ->get();

        return response()->json([
            'status' => true,
            'count' => $data->count(),
            'data' => $data,
        ]);
    }

    
}
