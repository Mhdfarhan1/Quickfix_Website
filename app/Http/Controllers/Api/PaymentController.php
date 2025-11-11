<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Snap;

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
}
