<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class FlipCallbackController extends Controller
{
    public function handle(Request $request, PayoutService $payoutService)
    {
        $data = $request->all();
        \Log::info('Flip webhook received', $data);

        // opsional: verify signature/header jika Flip mengirim signature
        // if (! $this->verifyFlipSignature($request)) { return response()->json(['ok'=>false], 403); }

        $payoutService->handleCallback($data);

        return response()->json(['status' => 'ok']);
    }

}
