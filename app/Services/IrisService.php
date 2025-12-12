<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class IrisService
{
    /**
     * Base URL for IRIS / payout API (set in .env)
     * Example sandbox: https://api.sandbox.midtrans.com/iris/v1
     */
    protected string $baseUrl;

    /**
     * API key or username for basic auth (set in .env)
     */
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.iris.base_url', env('MIDTRANS_IRIS_BASE_URL', '')), '/');
        $this->apiKey = config('services.iris.api_key', env('MIDTRANS_IRIS_KEY', ''));
    }

    /**
     * Ambil status payout dari provider.
     *
     * @param string $payoutId  ID yang kamu simpan saat create payout
     * @return array|null raw response array atau null saat gagal
     */
    public function getPayoutStatus(string $payoutId): ?array
    {
        try {
            // Contoh: GET {baseUrl}/payouts/{payoutId}
            $url = "{$this->baseUrl}/payouts/{$payoutId}";

            Log::info("[IRIS] GET payout status: {$url}");

            // gunakan Basic Auth: username = apiKey, password blank (sesuaikan provider)
            $response = Http::withBasicAuth($this->apiKey, '')
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                $body = $response->json();
                Log::info("[IRIS] response for payout {$payoutId}", ['response' => $body]);
                return is_array($body) ? $body : null;
            }

            Log::warning("[IRIS] failed to get payout {$payoutId}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
        } catch (Exception $e) {
            Log::error("[IRIS] exception while getPayoutStatus: " . $e->getMessage());
            return null;
        }
    }
}
