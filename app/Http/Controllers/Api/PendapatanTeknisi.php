<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendapatanTeknisi extends Controller
{
    public function pendapatan(Request $request)
    {
        $user = $request->user();

        $idTeknisi = DB::table('teknisi')
            ->where('id_user', $user->id_user)
            ->value('id_teknisi');

        if (!$idTeknisi) {
            return response()->json(['message' => 'Teknisi tidak ditemukan'], 404);
        }

        $mode = $request->query('mode', 'bulanan');
        $now = Carbon::now();

        /* ================= GRAFIK ================= */
        if ($mode === 'tahunan') {
            $grafik = DB::table('payouts')
                ->selectRaw('MONTH(created_at) as bulan, SUM(amount) as total')
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'success')
                ->whereYear('created_at', $now->year)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
        } else {
            $grafik = DB::table('payouts')
                ->selectRaw('WEEK(created_at, 1) as minggu, SUM(amount) as total')
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'success')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->groupBy('minggu')
                ->orderBy('minggu')
                ->limit(4)
                ->get();
        }

        /* ================= RIWAYAT ================= */
        $riwayat = DB::table('payouts')
            ->join('pemesanan', 'payouts.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->join('keahlian', 'pemesanan.id_keahlian', '=', 'keahlian.id_keahlian')
            ->select(
                'keahlian.nama_keahlian',
                'payouts.amount',
                'payouts.created_at'
            )
            ->where('payouts.id_teknisi', $idTeknisi)
            ->where('payouts.status', 'success')
            ->orderByDesc('payouts.created_at')
            ->paginate(5);

        return response()->json([
            'grafik' => $grafik,
            'riwayat' => $riwayat
        ]);
    }
}