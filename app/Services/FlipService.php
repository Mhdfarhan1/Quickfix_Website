<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class FlipService
{
    protected $key;
    protected $base;

    public function __construct()
    {
        $this->key  = config('services.flip.secret');
        $this->base = rtrim(config('services.flip.base_url') ?? 'https://bigflip.id/big_sandbox_api/v2', '/');

        // hanya log presence, jangan log key actual
        Log::info('FlipService loaded. Key present: ' . (!empty($this->key) ? 'YES' : 'NO'));
    }

    /**
     * Create disbursement on Flip v2
     *
     * expected payload: account_number, bank_code, amount, remark, (reference_id optional)
     *
     * @param array $payload
     * @return array
     * @throws \Exception
     */
    public function createPayout(array $payload, ?string $idempotency = null): array
    {
        if (empty($this->key)) {
            throw new \Exception('Flip API key not configured.');
        }

        $url = rtrim(config('services.flip.base_url'), '/') . '/disbursement';

        // ISO8601 timestamp
        $timestamp = now()->toIso8601String();

        // idempotency-key
        if (!$idempotency) {
            $idempotency = $payload['reference_id'] ?? Str::uuid()->toString();
        }

        Log::info('Flip REQUEST', [
            'url'   => $url,
            'idempotency' => $idempotency,
            'timestamp'   => $timestamp,
            'payload'     => $payload,
        ]);

        // ⛔ WAJIB: gunakan form-urlencoded
        $res = Http::asForm()
            ->withBasicAuth($this->key, '') // API key sebagai username
            ->withHeaders([
                'idempotency-key' => $idempotency,
                'x-timestamp'     => $timestamp,
            ])
            ->post($url, $payload);

        Log::info('Flip RESPONSE', [
            'status' => $res->status(),
            'body' => $res->body(),
        ]);

        if ($res->successful()) {
            return $res->json();
        }

        throw new \Exception("Flip create disbursement failed: " . $res->body());
    }




    /**
     * Get disbursement by flip id (v2)
     */
    public function getPayout(string $flipId): array
    {
        if (empty($this->key)) {
            throw new Exception('Flip API key not configured.');
        }

        // try endpoint /api/v2/disbursement/{id}
        $url = rtrim($this->base, '/');
        if (!str_contains($url, '/api/')) {
            $url .= '/api/v2/disbursement/' . $flipId;
        } else {
            $url = rtrim($url, '/') . '/disbursement/' . $flipId;
        }

        $res = Http::withBasicAuth($this->key, '')->get($url);

        if ($res->successful()) {
            return $res->json();
        }

        throw new Exception('Flip get disbursement failed: ' . $res->body());
    }
}
