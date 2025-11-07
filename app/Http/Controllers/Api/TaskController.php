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
    public function selesaikanTugas(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'status' => 'required'
        ]);

        $pesanan = Pemesanan::find($request->id_pemesanan);
        $pesanan->status = $request->status;
        $pesanan->save();

        return response()->json([
            'message' => 'Status berhasil diubah!'
        ], 200);
    }


}
