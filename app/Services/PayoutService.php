<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    protected $flip;

    public function __construct(FlipService $flip)
    {
        $this->flip = $flip;
    }

    // membuat payout baru
    public function create(Pemesanan $order): Payout
    {
        // Pastikan order memenuhi syarat (caller harus mengecek status_pembayaran & status_pekerjaan)
        // Cegah duplikat payout
        $existing = Payout::where('id_pemesanan', $order->id_pemesanan)->first();
        if ($existing) {
            return $existing;
        }

        // Ambil data verifikasi teknisi
        $verif = DB::table('verifikasi_teknisi')
            ->where('id_teknisi', $order->id_teknisi)
            ->first();

        if (!$verif || empty($verif->rekening) || empty($verif->bank)) {
            throw new \Exception("Rekening/Bank teknisi belum terverifikasi, payout dibatalkan!");
        }

        $bankCode      = trim($verif->bank);
        $accountNumber = trim($verif->rekening);
        $accountName   = trim($verif->nama ?? ($verif->account_name ?? ''));

        if (!$accountNumber || !$bankCode) {
            throw new \Exception("Informasi bank teknisi tidak lengkap.");
        }

        // Hitung amount (pastikan rounding/int sesuai gateway)
        $amount = (float) ($order->total_bayar ?? $order->harga ?? 0);
        $payoutAmount = (int) round($amount * 0.95); // cast ke integer jika gateway butuh integer (sesuaikan)

        // Gunakan transaction agar konsisten
        DB::beginTransaction();
        try {
            $payout = Payout::create([
                'id_pemesanan' => $order->id_pemesanan,
                'id_teknisi'   => $order->id_teknisi,
                'status'       => 'pending',
                'reference_id' => Str::uuid()->toString(),
                'bank_code'    => $bankCode,
                'account_no'   => $accountNumber,
                'account_name' => $accountName,
                'amount'       => $payoutAmount,
            ]);

            // coba panggil Flip (sinkron). Jika ingin asinkron, dispatch job dan commit.
            try {
                $response = $this->flip->createPayout([
                    'reference_id'   => $payout->reference_id,
                    'bank_code'      => $bankCode,
                    'account_number' => $accountNumber,
                    'amount'         => $payoutAmount,
                    'recipient_name' => $accountName,
                ]);

                // jika response valid, update
                $payout->update([
                    'flip_id'      => $response['id'] ?? null,
                    'raw_response' => json_encode($response),
                    'status'       => $response['status'] ?? $payout->status,
                ]);
            } catch (Throwable $e) {
                // jangan rollback pembuatan payout — biarkan tetap pending agar dapat dicoba saat scheduler
                Log::error("Flip createPayout failed for payout {$payout->id}: " . $e->getMessage());
                $payout->update([
                    'raw_response' => json_encode(['error' => $e->getMessage()]),
                    'status' => 'pending',
                ]);
            }

            DB::commit();
            return $payout;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed creating payout for order {$order->id_pemesanan}: " . $e->getMessage());
            throw $e;
        }
    }



    // scheduler akan mengecek payout yang belum selesai
    public function processEligiblePayouts()
    {
        $pending = Payout::whereIn('status', ['pending', 'processing'])->get();

        foreach ($pending as $p) {
            try {
                $res = $this->flip->getPayout($p->flip_id);

                $p->update([
                    'status' => $res['status'] ?? $p->status,
                    'raw_response' => json_encode($res)
                ]);
            } catch (\Exception $e) {
                Log::error("Error updating payout: " . $e->getMessage());
            }
        }
    }

    // callback dari Flip (opsional)
    public function handleCallback($data)
    {
        $reference = $data['reference_id'] ?? $data['reference'] ?? null;
        $flipId    = $data['id'] ?? $data['flip_id'] ?? null;
        $status    = $data['status'] ?? null;

        $payout = null;
        if ($reference) {
            $payout = Payout::where('reference_id', $reference)->first();
        }
        if (!$payout && $flipId) {
            $payout = Payout::where('flip_id', $flipId)->first();
        }

        if (!$payout) {
            \Log::warning('Callback payout not found', $data);
            return;
        }

        $payout->update([
            'status' => $status ?? $payout->status,
            'raw_response' => json_encode($data),
            'flip_id' => $flipId ?? $payout->flip_id,
        ]);

        // opsional: notify teknisi ketika success
        if (strtolower($status) === 'success' || strtolower($status) === 'done' || strtolower($status) === 'settlement') {
            // Notify::technicianPaymentReceived($payout->id_teknisi) // buat method notify
        }
    }


}
