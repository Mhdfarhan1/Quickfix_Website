<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuditRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Generate request_id untuk trace
        $requestId = (string) Str::uuid();

        // Start timer
        $start = microtime(true);

        // Proceed request
        $response = $next($request);

        try {
            // Latency in ms
            $latencyMs = (int) round((microtime(true) - $start) * 1000);

            // Prepare payload but exclude sensitive fields
            $payload = $request->except([
                'password', 'password_confirmation', 'current_password', 'remember_token'
            ]);

            // request size (approx)
            $requestSize = mb_strlen(json_encode($payload), '8bit') + strlen($request->getContent());

            // response size (approx) — guard in case stream
            $responseContent = method_exists($response, 'getContent') ? $response->getContent() : null;
            $responseSize = is_string($responseContent) ? mb_strlen($responseContent, '8bit') : null;

            // user agent & device simple parse
            $userAgent = $request->header('User-Agent');
            $device = $request->header('X-Device') ?? $this->detectDevice($userAgent);

            DB::table('audit_logs')->insert([
                'request_id' => $requestId,
                'user_id' => optional($request->user())->id,
                'ip_address' => $request->ip(),
                'route' => $request->path(),
                'method' => $request->method(),
                'user_agent' => substr($userAgent ?? '', 0, 512),
                'device' => $device,
                'status' => $response->status(),
                'latency_ms' => $latencyMs,
                'request_size_bytes' => $requestSize,
                'response_size_bytes' => $responseSize,
                'payload' => json_encode($payload),
                'headers' => json_encode($this->filterHeaders($request->headers->all())),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // avoid breaking response—log silently
            \Log::error('AuditRequest error: '.$e->getMessage());
        }

        // Attach request id to response headers for tracing (optional)
        if (method_exists($response, 'header')) {
            $response->header('X-Request-ID', $requestId);
        }

        return $response;
    }

    private function detectDevice(?string $ua): string
    {
        if (!$ua) return 'unknown';
        $ua = strtolower($ua);
        if (str_contains($ua, 'android')) return 'android';
        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ios')) return 'ios';
        if (str_contains($ua, 'windows')) return 'desktop';
        if (str_contains($ua, 'macintosh') || str_contains($ua, 'mac os')) return 'desktop';
        return 'other';
    }

    private function filterHeaders(array $headers): array
    {
        // Convert to simpler key => value and remove sensitive headers
        $filtered = [];
        foreach ($headers as $k => $v) {
            $kLower = strtolower($k);
            if (in_array($kLower, ['authorization', 'cookie', 'set-cookie'])) continue;
            $filtered[$kLower] = is_array($v) ? implode('; ', $v) : $v;
        }
        return $filtered;
    }
}
