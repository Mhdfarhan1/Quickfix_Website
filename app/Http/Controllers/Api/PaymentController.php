<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $order = Pemesanan::find($request->id_pemesanan);
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Pemesanan tidak ditemukan'], 404);
        }
        dd(config('midtrans.serverKey'));

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.is_production'); // harus false
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id_pemesanan . '-' . uniqid(),
                'gross_amount' => (int) $order->harga > 0 ? $order->harga : 50000,
            ],
            'enabled_payments' => ['gopay', 'qris'],
            'customer_details' => [
                'first_name' => $order->nama_pelanggan ?? 'User',
            ],
        ];


        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'status' => true,
            'snap_token' => $snapToken,
            'payment_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken",
        ]);
        
    }

    // Webhook / Callback Midtrans
    public function callback(Request $request)
    {
        \Log::info($request->all());

        $hash = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            Config::$serverKey
        );

        if ($hash !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order_code = explode('_', $request->order_id)[0];
        $order = Pemesanan::where('id_pemesanan', $order_code)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $order->update(['status' => 'dibayar']);
                break;

            case 'pending':
                $order->update(['status' => 'menunggu_pembayaran']);
                break;

            case 'expire':
                $order->update(['status' => 'gagal']);
                break;

            case 'cancel':
            case 'deny':
                $order->update(['status' => 'batal']);
                break;
        }

        return response()->json(['message' => 'OK']);
    }

    public function boot()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function pay($id)
    {
        \Log::info('midtrans.serverKey at runtime: ' . config('midtrans.serverKey'));

        Config::$serverKey = config('midtrans.serverKey');
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        $order = Pemesanan::findOrFail($id);

        // Set gross_amount
        $order->gross_amount = $order->harga;
        $order->save();

        // Generate Snap
        $params = [
            'transaction_details' => [
                'order_id' => $order->kode_pemesanan,
                'gross_amount' => (int)$order->harga,
            ],
            'customer_details' => [
                'first_name' => $order->pelanggan->nama,
                'email' => $order->pelanggan->email,
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $order->snap_token = $snapToken;
        $order->payment_status = 'pending';
        $order->save();

        return response()->json([
            "snap_token" => $snapToken,
        ]);
    }

}
