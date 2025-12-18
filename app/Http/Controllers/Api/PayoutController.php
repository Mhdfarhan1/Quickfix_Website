<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Services\FlipService;


class PayoutController extends Controller
{
    public function release(FlipService $flip)
    {
        $payouts = Payout::where('status', 'pending')
            ->whereHas('order', function($q){
                $q->where('status_pekerjaan', 'selesai_confirmed')
                ->where('payout_eligible_at', '<=', Carbon::now());
            })
            ->get();

        foreach ($payouts as $p) {
        // atomic guard untuk menghindari double-send
        $updated = Payout::where('id', $p->id)->where('status', 'pending')->update(['status' => 'processing']);
        if (!$updated) {
            Log::warning("Payout {$p->id} skip: already processed by another worker.");
            continue;
        }

        $rawRemark = "Pembayaran teknisi {$p->id}";
        $remark = preg_replace('/[^\p{L}\s]+/u', '', $rawRemark); // hanya huruf + spasi
        $remark = trim($remark);


        // build payload sesuai Flip v2 disbursement
        $payload = [
            'account_number' => $p->account_number,
            'bank_code'      => $p->bank_code,
            'amount'         => (int) $p->amount,
            'remark'         => $remark,
            'reference_id'   => $p->reference_id,
        ];

        // idempotency: gunakan kolom idempotency_key jika ada, fallback ke reference_id
        $idempotency = $p->idempotency_key ?? $p->reference_id ?? null;

        try {
            // panggil FlipService, passing idempotency agar Flip tidak double-send pada retry
            $response = $flip->createPayout(array_filter($payload), $idempotency);

            Log::info("Flip create response for payout {$p->id}", (array) $response);

            // normalisasi flip id dan status karena struktur response bisa berbeda
            $flipId = $response['id'] ?? ($response['data']['id'] ?? ($response['flip_id'] ?? null));
            $status = $response['status'] ?? ($response['data']['status'] ?? 'sent');

            $p->update([
                'status'       => $status,
                'flip_id'      => $flipId,
                'raw_response' => json_encode($response),
            ]);

            Log::info("Payout {$p->id} updated: status={$status}, flip_id={$flipId}");
        } catch (\Throwable $e) {
            // decode message jika JSON (Flip biasanya return JSON body sebagai message)
            $errMsg = $e->getMessage();
            $decoded = @json_decode($errMsg, true);
            $errors = $decoded['errors'] ?? null;

            // deteksi error bank_code
            $bankCodeError = false;
            if (is_array($errors)) {
                foreach ($errors as $err) {
                    if (isset($err['attribute']) && $err['attribute'] === 'bank_code') {
                        $bankCodeError = true;
                        break;
                    }
                }
            } else {
                // fallback: cek substring
                if (stripos($errMsg, 'Invalid bank code') !== false) $bankCodeError = true;
            }

            if ($bankCodeError) {
                Log::warning("Payout {$p->id} failed due to invalid bank_code, attempting fallback mapping", ['bank_code' => $p->bank_code]);

                // fallback mapping — tambahkan bank lain yang relevan
                $bankMap = [
                    // numeric -> short
                    '014' => 'bca', '14' => 'bca',
                    '002' => 'bri', '2' => 'bri',
                    '009' => 'bni', '9' => 'bni',
                    '008' => 'mandiri', 
                    // name -> short
                    'BCA' => 'bca', 'bri' => 'bri', 'BNI' => 'bni', 'MANDIRI' => 'mandiri',
                ];

                $raw = strtoupper(trim((string)$p->bank_code));
                $fallback = $bankMap[$raw] ?? ($bankMap[ltrim($raw, '0')] ?? null);

                if ($fallback && $fallback !== $p->bank_code) {
                    // coba kirim ulang dengan bank_code fallback
                    $payloadRetry = $payload;
                    $payloadRetry['bank_code'] = $fallback;

                    try {
                        Log::info("Retrying payout {$p->id} with fallback bank_code", ['new_bank_code' => $fallback]);
                        $response2 = $flip->createPayout(array_filter($payloadRetry), $idempotency);

                        Log::info("Flip create response (retry) for payout {$p->id}", (array) $response2);

                        $flipId2 = $response2['id'] ?? ($response2['data']['id'] ?? ($response2['flip_id'] ?? null));
                        $status2 = $response2['status'] ?? ($response2['data']['status'] ?? 'sent');

                        $p->update([
                            'status' => $status2,
                            'flip_id' => $flipId2,
                            'raw_response' => json_encode($response2),
                            'bank_code' => $fallback, // update stored bank_code agar konsisten
                        ]);

                        Log::info("Payout {$p->id} updated after retry: status={$status2}, flip_id={$flipId2}");
                        continue; // lanjut ke payout berikutnya
                    } catch (\Throwable $e2) {
                        Log::error("Retry with fallback bank_code also failed for payout {$p->id}: " . $e2->getMessage(), ['payload' => $payloadRetry]);
                        // rollback to pending for next cron
                        $p->update([
                            'status' => 'pending',
                            'raw_response' => json_encode(['error' => $e2->getMessage()]),
                        ]);
                        continue;
                    }
                }
            }

            // jika bukan error bank_code, atau fallback tidak tersedia, simpan error dan rollback ke pending
            Log::error("Flip create payout failed for payout {$p->id}: " . $errMsg, ['payload' => $payload, 'idempotency' => $idempotency]);
            $p->update([
                'status' => 'pending',
                'raw_response' => json_encode(['error' => $errMsg]),
            ]);
            continue;
        }

    }


        return response()->json(['status' => true]);
    }


    public function auto($id)
    {
        try {
            $order = Pemesanan::findOrFail($id);

            if ($order->status_pekerjaan !== 'selesai_confirmed') {
                return response()->json([
                    'status' => false,
                    'message' => 'Order belum dikonfirmasi selesai oleh pelanggan'
                ], 400);
            }

            // Cek status pembayaran untuk memastikan settlement selesai
            if ($order->status_pembayaran !== 'settlement') {
                return response()->json([
                    'status' => false,
                    'message' => 'Pembayaran belum settlement'
                ], 400);
            }

            // Proses payout
            $payout = PayoutService::createAndDispatch($order);

            return response()->json([
                'status' => true,
                'message' => 'Payout otomatis dikirim',
                'data' => $payout
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
