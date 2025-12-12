<?php
// app/Http/Controllers/MidtransWebhookController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;

class MidtransWebhookController extends Controller
{
    public function handle(Request $req)
    {
        // verify signature_key
        $serverKey = config('services.midtrans.server_key');
        $order_id = $req->order_id ?? null;
        $status = $req->transaction_status ?? null;
        $signature_key = $req->signature_key ?? null;

        // reconstruct expected signature (Midtrans: order_id + status_code + gross_amount + serverKey)
        $data = $req->order_id . $req->status_code . $req->gross_amount . $serverKey;
        $expected = hash('sha512', $data);

        if (!hash_equals($expected, $signature_key)) {
            return response()->json(['message' => 'invalid signature'], 403);
        }

        // update order
        $order = Order::where('order_id', $order_id)->first();
        if (!$order) return response()->json(['message' => 'order not found'], 404);

        if ($status === 'settlement' || $status === 'capture') {
            $order->payment_status = 'settled';
            $order->save();
        } else {
            $order->payment_status = $status;
            $order->save();
        }

        return response()->json(['message' => 'ok']);
    }
}
