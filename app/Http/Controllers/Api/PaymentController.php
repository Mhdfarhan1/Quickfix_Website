<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\Notify;
use App\Models\Dispute;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Support\Str; 


class PaymentController extends Controller
{
    /**
     * 🔹 Buat Link Pembayaran (Snap)
     */
    public function createPayment(Request $request)
    {
        Log::info('=== [CREATE PAYMENT] ===', $request->all());

        $order = Pemesanan::where('kode_pemesanan', $request->kode_pemesanan)->first();
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Pemesanan tidak ditemukan'], 404);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->kode_pemesanan . '-' . time(),
                    'gross_amount' => (int) $order->harga,
                ],
                'enabled_payments' => ['qris', 'gopay'],
                'customer_details' => [
                    // ambil nama dari relasi pelanggan, bukan kolom langsung
                    'first_name' => optional($order->pelanggan)->nama ?? 'User',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken";

            // Simpan ke tabel
            $order->update([
                'gross_amount' => $order->harga,
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'status' => true,
                'snap_token' => $snapToken,
                'payment_url' => $paymentUrl,
                'kode_pemesanan' => $order->kode_pemesanan,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat SnapToken: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔹 Callback/Notification dari Midtrans
     */
    public function handleNotification(Request $request)
    {
        Log::info("=== [MIDTRANS NOTIFICATION RECEIVED] ===", $request->all());

        $notif = $request->all();
        $orderId = $notif['order_id'];
        $transaction = $notif['transaction_status'] ?? null;

        // Ambil kode pemesanan dari order_id (hapus timestamp di belakang)
        $kode = explode('-', $orderId);
        array_pop($kode);
        $kodePemesanan = implode('-', $kode);

        $order = Pemesanan::where('kode_pemesanan', $kodePemesanan)->first();
        if (!$order) {
            Log::warning("⚠️ Order $kodePemesanan tidak ditemukan");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Map payment_status sesuai enum tabel
        $paymentStatus = match ($transaction) {
            'settlement', 'capture' => 'settlement',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'failed',
            default => 'failed',
        };

        // Map status pekerjaan (kalau nanti ingin ubah otomatis)
        $statusPekerjaan = match ($paymentStatus) {
            'settlement' => 'menunggu_diterima',
            default => $order->status_pekerjaan,
        };

        // Simpan semua info dari notifikasi
        $order->update([
            'payment_status' => $paymentStatus,
            'payment_type' => $notif['payment_type'] ?? null,
            'midtrans_transaction_id' => $notif['transaction_id'] ?? null,
            'status_pekerjaan' => $statusPekerjaan,
        ]);

        if ($paymentStatus == 'settlement') {
            Notify::paymentSuccess($order->id_pelanggan);
        }


        Log::info("✅ Order {$kodePemesanan} diperbarui menjadi {$paymentStatus}");
        return response()->json(['message' => 'OK']);
    }

    /**
     * 🔹 Endpoint Flutter untuk cek status
     */
    public function checkStatus(Request $request)
    {
        $order = Pemesanan::where('kode_pemesanan', $request->kode_pemesanan)->first();

        if (!$order) {
            return response()->json(['status' => false, 'payment_status' => 'not_found']);
        }

        return response()->json([
            'status' => true,
            'kode_pemesanan' => $order->kode_pemesanan,
            'payment_status' => $order->payment_status,
        ]);
    }

    public function technicianComplete(Request $request, $kode)
    {
        Log::info("[TECHNICIAN COMPLETE] incoming for kode: {$kode}", $request->all());

        $order = Pemesanan::where('kode_pemesanan', $kode)->firstOrFail();

        // simpan bukti (bisa berupa array URL)
        $bukti = $request->input('bukti') ?? null;
        if ($bukti) {
            // pastikan menyimpan sebagai array (casts di model)
            $order->visible_bukti_teknisi = json_encode($bukti);
        }

        // set status ke pending verifikasi (escrow)
        $order->status_pekerjaan = 'selesai_pending_verifikasi';
        $order->save();

        Log::info("[TECHNICIAN COMPLETE] order {$order->kode_pemesanan} set to selesai_pending_verifikasi, bukti_count=" . ($bukti ? count((array)$bukti) : 0));

        // notifikasi ke customer (pakai statusChanged agar pesan konsisten)
        Notify::statusChanged($order->id_pelanggan, 'selesai_pending_verifikasi');

        return response()->json(['status' => true, 'message' => 'Status updated to selesai_pending_verifikasi']);
    }


    public function customerConfirm(Request $request, $kode)
    {
        $order = Pemesanan::where('kode_pemesanan', $kode)->firstOrFail();

        // ambil data verifikasi teknisi dari tabel verifikasi_teknisi
        $verif = DB::table('verifikasi_teknisi')
            ->where('id_teknisi', $order->id_teknisi)
            ->first();

        Log::info('VERIF DATA', (array) $verif);

        if (!$verif || empty($verif->rekening) || empty($verif->bank)) {
            Log::warning("Payout aborted: verifikasi teknisi tidak lengkap for order {$order->kode_pemesanan}");
            return response()->json([
                'status' => false,
                'message' => 'Rekening teknisi belum terverifikasi atau data bank belum lengkap.'
            ], 400);
        }

        // mapping/fallback nama kolom (sesuaikan jika DB-mu pakai nama lain)
        $bank_code      = $verif->bank_code ?? $verif->bank ?? null;

        // normalisasi => jika bank_code = "014" OK, tapi jika "BCA" ubah
        if (!is_numeric($bank_code)) {
            $bankMap = [
                'BCA' => '014',
                'BRI' => '002',
                'BNI' => '009',
                'MANDIRI' => '008',
            ];
            $bank_code = $bankMap[strtoupper($bank_code)] ?? null;
        }

        $account_number = $verif->rekening ?? $verif->account_no ?? null;
        $account_name   = $verif->nama ?? $verif->account_name ?? null;

        // hitung payout (adjust sesuai logika: gunakan total_bayar / harga)
        $gross = $order->total_bayar ?? $order->harga ?? 0;
        $payoutAmount = (int) round($gross * 0.95); // misal 95% ke teknisi

        $referenceId = Str::uuid()->toString();
        $idempotency = $referenceId; // deterministic: reuse this for retries

        $remark = "Pembayaran teknisi";

        $payload = [
            'id_pemesanan'    => $order->id_pemesanan,
            'id_teknisi'      => $order->id_teknisi,
            'status'          => 'pending',
            'reference_id'    => $referenceId,
            'idempotency_key' => $idempotency,
            'bank_code'       => $bank_code,
            'account_number'  => $account_number,
            'account_name'    => $account_name,
            'amount'          => $payoutAmount,
            'remark'        => $remark,
        ];

        Log::info('PAYOUT CREATE PAYLOAD', $payload);

        // create + update inside transaction
        try {
            DB::beginTransaction();

            // create payout (will include idempotency_key column)
            $payout = Payout::create($payload);

            // update order: set pelanggan sudah verifikasi and schedule payout_eligible_at
            $order->verifikasi_by_customer = 1;
            $order->verifikasi_at = now();
            $order->payout_eligible_at = Carbon::now()->addHours(72);
            $order->status_pekerjaan = 'selesai_confirmed';
            $order->save();

            DB::commit();

            // notify teknisi
            Notify::technicianWorkConfirmed($order->id_teknisi);

            return response()->json([
                'status' => true,
                'message' => 'Terima kasih, pekerjaan dikonfirmasi. Dana akan dilepas setelah jendela verifikasi.',
                'data' => $payout
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payout/create or order update failed: ' . $e->getMessage(), [
                'order_id' => $order->id_pemesanan,
                'payload' => $payload
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memproses konfirmasi. ' . $e->getMessage()
            ], 500);
        }
    }

    public function customerRequestRefund(Request $request, $kode)
    {
        $order = Pemesanan::where('kode_pemesanan', $kode)->firstOrFail();

        // buat record dispute
        $dispute = Dispute::create([
            'id_pemesanan' => $order->id_pemesanan,
            'tipe' => 'refund',
            'amount' => $request->input('amount') ?? $order->gross_amount,
            'status' => 'open',
            'notes' => $request->input('notes') ?? null,
        ]);

        $order->status_pekerjaan = 'in_dispute';
        $order->dispute_id = $dispute->id;
        $order->refund_requested_at = now();
        $order->save();

        // blokir payout (cron/job harus cek status dispute sebelum release)
        // Kirim ke channel admin via judul + pesan (gunakan id admin jika ada)
        Notify::send(null, "Permintaan Refund", "Refund requested for order {$order->kode_pemesanan}");

        // Kirim ke teknisi
        Notify::send($order->id_teknisi, "Permintaan Refund", "Ada permintaan refund. Silakan menanggapi atau hubungi admin.");

        return response()->json(['status' => true, 'message' => 'Permintaan refund diterima, tim kami akan menindaklanjuti.']);
    }

    /**
     * 🔹 Ambil data struk pembayaran
     */
    public function getStruk($kode)
    {
        $pemesanan = Pemesanan::with(['pelanggan', 'teknisi.user', 'alamat', 'keahlian'])
            ->where('kode_pemesanan', $kode)
            ->first();

        if (!$pemesanan) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }


        return response()->json([
            'status' => true,
            'data' => [
                'kode_pemesanan' => $pemesanan->kode_pemesanan,
                'nama_layanan' => $pemesanan->keahlian->nama_keahlian ?? '-',
                'alamat' => $pemesanan->alamat->alamat_lengkap ?? '-',
                'tanggal' => $pemesanan->tanggal_booking,
                'nama_teknisi' => $pemesanan->teknisi->user->nama ?? '-',
                'harga' => $pemesanan->harga,
                'status' => $pemesanan->status_pekerjaan,
            ],
        ]);
    }

    public function getOrder($kode)
    {
        \Log::info("[GET ORDER] request for kode: {$kode}");

        $order = Pemesanan::with(['pelanggan','teknisi.user','alamat','keahlian','buktiPekerjaan','payouts','dispute'])
            ->where('kode_pemesanan', $kode)
            ->first();

        if (!$order) {
            \Log::warning("[GET ORDER] order not found: {$kode}");
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }

        \Log::info("[GET ORDER] returning order", ['kode' => $kode, 'status' => $order->status_pekerjaan]);

        return response()->json([
            'status' => true,
            'data' => $order->toArray()
        ]);
    }

}
