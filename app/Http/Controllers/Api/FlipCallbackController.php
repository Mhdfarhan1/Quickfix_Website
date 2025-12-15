<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlipCallbackController extends Controller
{
    /**
     * Endpoint untuk menerima webhook/callback dari Flip
     * - Verifikasi token header (config('services.flip.callback_token'))
     * - Log payload (untuk debugging)
     * - Teruskan ke service yang meng-handle update (payout update)
     */
    public function handle(Request $request, PayoutService $payoutService)
    {
        // Log minimal (jangan log token plaintext)
        \Log::info('Flip webhook received (raw)', [
            'headers' => $request->headers->all(),
            'body_keys' => array_keys($request->all()),
        ]);

        // 1. Ambil token dari header atau body
        $headerToken = trim((string) ($request->header('x-callback-token')
            ?? $request->header('callback-token')
            ?? $request->header('x-flip-callback-token')
            ?? ''
        ));

        $bodyToken = trim((string) ($request->input('token') ?? ''));
        $receivedToken = $headerToken !== '' ? $headerToken : $bodyToken;

        // DEBUG: cek token yang dikirim Flip (dengan masking)
        $tokenMask = function ($t) {
            if (!$t) return '(empty)';
            $len = strlen($t);
            if ($len <= 6) return str_repeat('*', $len);
            return substr($t, 0, 3) . '...' . substr($t, -3);
        };

        \Log::info('flip-token-received', [
            'raw_token' => $tokenMask($receivedToken),
            'length' => strlen($receivedToken),
            'is_bcrypt' => (strlen($receivedToken) > 3 && in_array(substr($receivedToken, 0, 4), ['$2y$', '$2a$', '$2b$'])),
        ]);

        // 2. Ambil expected token dari config (bisa kosong jika dihapus dari .env)
        $expected = trim((string) config('services.flip.callback_token', ''));

        // 3. Jika expected kosong -> kebijakan: skip di non-production jika diizinkan, blok di production
        if ($expected === '') {
            // gunakan env flag untuk kontrol eksplisit di dev: FLIP_SKIP_VERIFICATION=true
            $skipAllowed = !app()->environment('production') && env('FLIP_SKIP_VERIFICATION', false);

            if ($skipAllowed) {
                \Log::warning('Flip webhook verification skipped: no expected token configured (non-production).');
                $valid = true;
            } else {
                // production harus punya token; kalau tidak, tolak untuk keamanan
                \Log::error('Flip webhook received but no callback token configured. Rejecting request.');
                return response()->json(['status' => 'unauthorized', 'message' => 'callback token missing'], 401);
            }
        } else {
            // 4. Verifikasi token fleksibel (expected might be bcrypt or plaintext)
            $valid = false;

            $looks_like_bcrypt = function ($s) {
                return is_string($s) && strlen($s) > 3 && in_array(substr($s, 0, 4), ['$2y$', '$2a$', '$2b$']);
            };

            $expIsHash = $looks_like_bcrypt($expected);
            $recIsHash = $looks_like_bcrypt($receivedToken);

            // Debug kecil setelah diketahui expected
            \Log::debug('flip-verification-debug', [
                'received_token_present' => ($receivedToken !== ''),
                'received_token_sample' => substr($receivedToken, 0, min(4, strlen($receivedToken))),
                'expected_present' => true,
                'expected_prefix' => substr($expected, 0, 4),
                'expIsHash' => $expIsHash,
                'recIsHash' => $recIsHash,
            ]);

            if ($expIsHash && !$recIsHash) {
                // expected = bcrypt hash, received = plaintext -> verify using password_verify
                $valid = password_verify($receivedToken, $expected);
            } elseif ($expIsHash && $recIsHash) {
                // both hash-like -> only valid if identical (provider must send same hash)
                $valid = hash_equals($expected, $receivedToken);
            } else {
                // expected is plaintext (or not bcrypt) -> compare plaintexts
                $valid = hash_equals($expected, $receivedToken);
            }
        }

        if (empty($valid) || $valid === false) {
            \Log::warning('Flip callback invalid or missing token', [
                'received_token_present' => ($receivedToken !== ''),
                'header_present' => ($headerToken !== ''),
            ]);
            return response()->json(['status' => 'unauthorized'], 401);
        }

        // 5. Parse payload data
        $dataField = $request->input('data');
        if (is_string($dataField)) {
            $payload = json_decode($dataField, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $payload = $request->all();
            }
        } else {
            $payload = $request->all();
        }

        // 6. Process callback
        try {
            $payoutService->handleCallback($payload);
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            \Log::error('Flip callback processing failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }




}
