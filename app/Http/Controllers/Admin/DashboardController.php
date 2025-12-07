<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ================== DATA CARD LAMA ==================
        $jumlahTeknisi  = DB::table('teknisi')->count();
        $jumlahPengguna = DB::table('user')->where('role', 'pelanggan')->count();

        $adminFeePercent = 5;
        $totalHarga = Pemesanan::where('status_pekerjaan', 'selesai')->sum('harga');
        $totalPendapatanAdmin = ($totalHarga * $adminFeePercent) / 100;
        $jumlahPemesananSelesai = Pemesanan::where('status_pekerjaan', 'selesai')->count();

        // ===== FILTER TAHUN (chart akan tampil 1 tahun penuh) =====
        $selectedYear = (int) $request->input('year', now()->year);

        $labels = [];
        $teknisiPerMonth = [];
        $penggunaPerMonth = [];

        // LOOP 12 BULAN (JAN–DES)
        foreach (range(1, 12) as $m) {
            $start = Carbon::create($selectedYear, $m, 1)->startOfMonth();
            $end   = Carbon::create($selectedYear, $m, 1)->endOfMonth();

            // label bulan (pakai bahasa Indonesia)
            $labels[] = $start->locale('id')->translatedFormat('F');

            // jumlah teknisi yang daftar di bulan tsb
            $teknisiPerMonth[] = DB::table('teknisi')
                ->whereBetween('created_at', [$start, $end])
                ->count();

            // jumlah pengguna (pelanggan) yang daftar di bulan tsb
            $penggunaPerMonth[] = DB::table('user')
                ->where('role', 'pelanggan')
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        return view('admin.dashboard', compact(
            'jumlahTeknisi',
            'jumlahPengguna',
            'totalPendapatanAdmin',
            'jumlahPemesananSelesai',
            'labels',
            'teknisiPerMonth',
            'penggunaPerMonth',
            'selectedYear'
        ));
    }
}
