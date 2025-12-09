<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        // Persentase fee admin (bisa kamu ubah)
        $adminFeePercent = 5; // 10% dari harga

        // 🔹 Base query: hanya pesanan yang SUDAH SELESAI dikerjakan
        $baseQuery = Pemesanan::where('status_pekerjaan', 'selesai');

        

        $summaryQuery = clone $baseQuery;

        // Total harga semua pesanan selesai
        $totalHarga = (clone $summaryQuery)->sum('harga');

        // Pendapatan admin total (10% dari total harga)
        $totalPendapatanAdmin = ($totalHarga * $adminFeePercent) / 100;

        // Pendapatan admin bulan ini
        $hargaBulanIni = (clone $summaryQuery)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('harga');

        $pendapatanAdminBulanIni = ($hargaBulanIni * $adminFeePercent) / 100;

        // Pendapatan admin hari ini
        $hargaHariIni = (clone $summaryQuery)
            ->whereDate('updated_at', now()->toDateString())
            ->sum('harga');

        $pendapatanAdminHariIni = ($hargaHariIni * $adminFeePercent) / 100;

        // ==============================
        //   FILTER SEARCH UNTUK TABEL
        // ==============================

        $search = $request->input('search');

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('kode_pemesanan', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($qq) use ($search) {
                        $qq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('keahlian', function ($qq) use ($search) {
                        $qq->where('nama_keahlian', 'like', "%{$search}%");
                    });
            });
        }

        // ==============================
        //   TABEL DETAIL TRANSAKSI
        // ==============================

        $entries = $request->input('entries', 10);

        $pemesanan = $baseQuery
            ->with(['pelanggan', 'teknisi.user', 'keahlian']) // relasi sama seperti di PaymentController
            ->orderByDesc('updated_at')
            ->paginate($entries)
            ->appends($request->except('page'));

        // Hitung admin_fee & teknisi_fee per baris
        $pemesanan->getCollection()->transform(function ($item) use ($adminFeePercent) {
            $item->admin_fee = ($item->harga * $adminFeePercent) / 100;
            $item->teknisi_fee = $item->harga - $item->admin_fee;
            return $item;
        });

        return view('admin.pendapatan.index', compact(
            'adminFeePercent',
            'totalPendapatanAdmin',
            'pendapatanAdminBulanIni',
            'pendapatanAdminHariIni',
            'pemesanan',
            'entries',
            'search',
        ));
    }
}
