<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Alamat;

class TaskController extends Controller
{
    public function getTasksByTeknisi($id_teknisi)
    {
        $tasks = Pemesanan::where('id_teknisi', $id_teknisi)
            ->join('user', 'pemesanan.id_pelanggan', '=', 'user.id_user')
            ->leftJoin('alamat', 'user.id_user', '=', 'alamat.id_user')
            ->select(
                'pemesanan.id_pemesanan as id',
                'user.nama as nama_pelanggan',
                'pemesanan.keluhan as deskripsi',
                'pemesanan.status as status_tugas',
                'pemesanan.harga',
                'alamat.alamat_lengkap',
                'alamat.latitude',
                'alamat.longitude'
            )
            ->orderBy('pemesanan.updated_at', 'desc')
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Belum ada tugas untuk teknisi ini.'
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $tasks
        ], 200);
    }
    public function selesaikanPekerjaan($id_pemesanan)
    {
        $jumlahBukti = \App\Models\BuktiPekerjaan::where('id_pemesanan', $id_pemesanan)->count();

        if ($jumlahBukti < 1) {
            return response()->json([
                'status' => false,
                'message' => 'Upload minimal 1 foto bukti terlebih dahulu'
            ], 400);
        }

        $pesanan = \App\Models\Pemesanan::find($id_pemesanan);

        if ($pesanan->status_pekerjaan === 'selesai') {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan sudah selesai dan dikunci'
            ], 403);
        }

        $pesanan->update([
            'status_pekerjaan' => 'selesai'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pekerjaan berhasil diselesaikan'
        ]);
    }


}
