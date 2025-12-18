<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payout;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\Notify;

class SimulatePayoutSuccess extends Command
{
    protected $signature = 'payouts:simulate-success {payout_id}';
    protected $description = 'Simulate a payout success for a given payout_id (for demo/sandbox)';

    public function handle()
    {
        $payoutId = $this->argument('payout_id');
        $payout = Payout::find($payoutId);

        if (!$payout) {
            $this->error("Payout not found: {$payoutId}");
            return 1;
        }

        if ($payout->status === 'success') {
            $this->info("Payout {$payoutId} already success.");
            return 0;
        }

        \DB::transaction(function () use ($payout) {
            // mark simulated provider id
            $payout->midtrans_payout_id = 'SIM-' . now()->format('YmdHis') . '-' . $payout->id;
            $payout->status = 'success';
            $payout->processed_at = Carbon::now();
            $payout->save();

            // update pemesanan payout_released_at
            $order = Pemesanan::where('id_pemesanan', $payout->id_pemesanan)->first();
            if ($order && !$order->payout_released_at) {
                $order->payout_released_at = Carbon::now();
                $order->status_pekerjaan = 'selesai'; // final state if you want
                $order->save();
            }

            // notify teknisi via your Notify service
            Notify::send($payout->id_teknisi, "Payout Terkirim (Simulasi)", "Dana Rp " . number_format($payout->amount,0,',','.') . " telah dikirim (simulasi).");
            Notify::sendToAdmin("Simulated payout success for payout_id={$payout->id}, order={$payout->id_pemesanan}");
            Log::info("[SIM-PAYOUT] payout {$payout->id} simulated as success");
        });

        $this->info("Payout {$payoutId} simulated as success.");
        return 0;
    }
}
