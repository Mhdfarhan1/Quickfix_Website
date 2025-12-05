<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    // daftar pemesanan selesai
    public function selesai(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);

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
                'teknisi_user.foto_profile as foto_teknisi'
            )
            ->where('pemesanan.status_pekerjaan', 'selesai'); // ✅ cuma yang selesai


        // Optional: search di admin
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pemesanan.kode_pemesanan', 'like', "%$search%")
                    ->orWhere('pelanggan.nama', 'like', "%$search%")
                    ->orWhere('teknisi_user.nama', 'like', "%$search%")
                    ->orWhere('keahlian.nama_keahlian', 'like', "%$search%");
            });
        }

        $pemesanan = $query
            ->orderByDesc('pemesanan.id_pemesanan')
            ->paginate($entries)
            ->appends($request->except('page'));

        return view('admin.pemesanan.selesai', compact('pemesanan', 'entries', 'search'));
    }
}
