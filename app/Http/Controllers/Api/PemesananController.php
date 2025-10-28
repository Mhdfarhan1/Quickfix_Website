<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Pemesanan;
use Illuminate\Validation\ValidationException;

class PemesananController extends Controller
{
    /**
     * ✅ Ambil data pemesanan (filter by id_teknisi / id_pelanggan)
     * Masih bisa dipakai secara manual via URL.
     */
    public function getPemesanan(Request $request)
    {
        $id_teknisi = $request->query('id_teknisi');
        $id_pelanggan = $request->query('id_pelanggan');

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

        if ($id_teknisi) {
            $query->where('pemesanan.id_teknisi', $id_teknisi);
        }

        if ($id_pelanggan) {
            $query->where('pemesanan.id_pelanggan', $id_pelanggan);
        }

        $data = $query->orderByDesc('pemesanan.id_pemesanan')->get();

        return response()->json([
            'status' => true,
            'count' => $data->count(),
            'data' => $data,
        ]);
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

        $data = $query->orderByDesc('pemesanan.id_pemesanan')->get();

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
        try {
            $request->validate([
                'id_pelanggan' => 'required|exists:user,id_user',
                'id_teknisi' => 'required|exists:teknisi,id_teknisi',
                'id_keahlian' => 'required|exists:keahlian,id_keahlian',
                'tanggal_booking' => 'required|date',
                'keluhan' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
            ]);

            // Generate kode pemesanan unik
            $prefix = 'QFX-' . date('Y') . '-';
            $latest = Pemesanan::latest('id_pemesanan')->first();
            $nextNumber = $latest ? ((int) substr($latest->kode_pemesanan, -4)) + 1 : 1;
            $kode = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $pemesanan = Pemesanan::create([
                'kode_pemesanan' => $kode,
                'id_pelanggan' => $request->id_pelanggan,
                'id_teknisi' => $request->id_teknisi,
                'id_keahlian' => $request->id_keahlian,
                'tanggal_booking' => $request->tanggal_booking,
                'keluhan' => $request->keluhan,
                'status' => 'menunggu',
                'harga' => $request->harga,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pemesanan berhasil dibuat!',
                'data' => $pemesanan
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
