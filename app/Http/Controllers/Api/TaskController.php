<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use App\Models\BuktiPekerjaan;

class TaskController extends Controller
{
    public function getTasksByTeknisi(Request $request)
    {
        $idUser = $request->user()->id_user;

        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $idUser)
            ->value('id_teknisi');

        $tasks = DB::table('pemesanan')
            ->join('user', 'pemesanan.id_pelanggan', '=', 'user.id_user')
            ->leftJoin('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->leftJoin('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->leftJoin('lokasi_teknisi', 'pemesanan.id_teknisi', '=', 'lokasi_teknisi.id_teknisi')

            ->where('pemesanan.id_teknisi', $idTeknisi)
            ->whereIn('pemesanan.status_pekerjaan', [
                'dijadwalkan',
                'menuju_lokasi',
                'sedang_bekerja',
                'selesai'
            ])

            ->select(
                'pemesanan.id_pemesanan as id',
                'pemesanan.id_teknisi',
                'pemesanan.id_pelanggan',
                'pemesanan.id_keahlian',
                'pemesanan.id_alamat',
                'user.no_hp as no_hp_pelanggan',


                'pemesanan.kode_pemesanan',
                'user.nama as nama_pelanggan',
                'pemesanan.keluhan',
                'pemesanan.status_pekerjaan',
                'pemesanan.harga',

                'keahlian.nama_keahlian',
                'alamat.alamat_lengkap',
                'alamat.latitude',
                'alamat.longitude',

                'lokasi_teknisi.latitude as latitude_teknisi',
                'lokasi_teknisi.longitude as longitude_teknisi',

                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.created_at'
            )
            ->orderBy('pemesanan.jam_booking', 'asc')
            ->get();

        
        return response()->json([
            'status' => true,
            'debug_sample' => $tasks->first(), 
            'data' => $tasks
        ]);
    }



    public function getRiwayatTeknisi(Request $request)
    {
        $idUser = $request->user()->id_user;

        // Cari id_teknisi berdasarkan user yang sedang login
        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $idUser)
            ->value('id_teknisi');

        // Ambil semua pesanan yang statusnya selesai atau batal
        $riwayat = DB::table('pemesanan')
            ->join('user', 'pemesanan.id_pelanggan', '=', 'user.id_user')
            ->leftJoin('alamat', 'pemesanan.id_alamat', '=', 'alamat.id_alamat')
            ->leftJoin('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')

            // JOIN chat berdasarkan id_pelanggan + id_teknisi
            ->leftJoin('chats', function($join) use ($idTeknisi) {
                $join->on('chats.id_user', '=', 'pemesanan.id_pelanggan')
                    ->where('chats.id_teknisi', '=', $idTeknisi);
            })

            ->where('pemesanan.id_teknisi', $idTeknisi)
            ->whereIn('pemesanan.status_pekerjaan', ['selesai', 'batal'])

            ->select(
                'pemesanan.id_pemesanan as id',
                'pemesanan.id_teknisi',
                'pemesanan.kode_pemesanan',
                'user.id_user',
                'user.nama as nama_pelanggan',
                'keahlian.nama_keahlian',
                'pemesanan.keluhan',
                'user.no_hp as no_hp_pelanggan',

                'pemesanan.status_pekerjaan',
                'pemesanan.harga',

                'alamat.alamat_lengkap',
                'alamat.latitude',
                'alamat.longitude',

                'pemesanan.tanggal_booking',
                'pemesanan.jam_booking',
                'pemesanan.created_at',

                // Tambahkan field ini
                'chats.id_chat'
            )
            ->orderBy('pemesanan.created_at', 'DESC')
            ->get();


        return response()->json([
            'status' => true,
            'total' => $riwayat->count(),
            'data' => $riwayat
        ]);
    }

}
