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

    public function adminProcessRefund(Request $request, $kode)
    {
        $order = Pemesanan::where('kode_pemesanan', $kode)->firstOrFail();
        if (!$order->midtrans_transaction_id) {
            return response()->json(['status' => false, 'message' => 'Transaksi Midtrans tidak tersedia.']);
        }

        // jika payout sudah dilepas ke teknisi -> tolak otomatis (atau minta solusi manual)
        if ($order->payout_released_at) {
            return response()->json(['status' => false, 'message' => 'Dana sudah dicairkan ke teknisi. Proses refund harus ditangani manual.']);
        }

        // panggil Midtrans Refund (contoh via HTTP POST)
        $serverKey = config('midtrans.server_key');
        $transactionId = $order->midtrans_transaction_id;

        $payload = [
        'refund_key' => 'refund-'.time(),
        'amount' => (float) ($request->input('amount') ?? $order->gross_amount),
        'reason' => $request->input('reason') ?? 'Refund by marketplace'
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->post("https://api.midtrans.com/v2/{$transactionId}/refund", $payload);

        if ($response->successful()) {
            // update DB
            $order->payment_status = 'failed'; // atau 'refund' kalau mau tambahkan enum
            $order->save();

            // update dispute record if exists
            if ($order->dispute_id) {
                $d = Dispute::find($order->dispute_id);
                if ($d) {
                    $d->status = 'customer_refunded';
                    $d->save();
                }
            }

            return response()->json(['status' => true, 'message' => 'Refund processed', 'data' => $response->json()]);
        } else {
            return response()->json(['status' => false, 'message' => 'Refund failed', 'detail' => $response->body()], 500);
        }
    }

}
