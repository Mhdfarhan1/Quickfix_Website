<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use App\Services\Notify;
use Carbon\Carbon;

class AdminPayoutController extends Controller
{
    public function simulateSuccess($id)
    {
        $payout = Payout::find($id);

        if (!$payout) {
            return back()->with('error', 'Payout tidak ditemukan');
        }

        if ($payout->status === 'success') {
            return back()->with('info', 'Payout sudah success sebelumnya.');
        }

        DB::transaction(function () use ($payout) {

            $payout->status = 'success';
            $payout->processed_at = Carbon::now();
            $payout->midtrans_payout_id = 'SIM-' . now()->timestamp;
            $payout->save();

            $order = Pemesanan::find($payout->id_pemesanan);
            if ($order) {
                $order->payout_released_at = Carbon::now();
                $order->save();
            }

            Notify::send($payout->id_teknisi, 'Payout Success (Simulasi)', 'Dana berhasil dicairkan (simulasi).');
        });

        return back()->with('success', 'Simulasi payout success berhasil dijalankan');
    }
}
