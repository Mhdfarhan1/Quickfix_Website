<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\IncidentDetected;
use Carbon\Carbon;

class SecurityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Pantau semua query yang dijalankan Laravel
        DB::listen(function ($query) {
            try {
                $sql = strtolower($query->sql);
                $ip = request()->ip() ?? 'cli';
                $userId = optional(request()->user())->id ?? null;

                $patternDetected = false;
                $reason = '';

                // 🔎 Deteksi pola SQL Injection
                if (str_contains($sql, "or '1'='1'") || str_contains($sql, 'or 1=1') || str_contains($sql, 'union select')) {
                    $patternDetected = true;
                    $reason = 'SQL Injection attempt detected';
                }

                // 🚦 Deteksi query berlebihan dari IP sama (indikasi brute force)
                $key = "query_count:{$ip}";
                $count = Cache::increment($key);
                Cache::put($key, $count, 60); // reset tiap 60 detik

                if ($count > 200) {
                    $patternDetected = true;
                    $reason = "Excessive query activity from IP {$ip}";
                }

                // 🚨 Jika ada pola mencurigakan
                if ($patternDetected) {
                    $incidentData = [
                        'type' => 'suspicious_query',
                        'description' => $reason,
                        'ip' => $ip,
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Simpan ke tabel incidents
                    DB::table('incidents')->insert($incidentData);

                    // Blokir IP sementara
                    DB::table('blocked_ips')->updateOrInsert(
                        ['ip' => $ip],
                        [
                            'blocked_until' => Carbon::now()->addHour(),
                            'reason' => $reason,
                            'updated_at' => now(),
                        ]
                    );

                    // Kirim notifikasi email ke admin
                    try {
                        Mail::to(env('ADMIN_EMAIL'))->send(new IncidentDetected($incidentData));
                    } catch (\Throwable $e) {
                        Log::error('Gagal kirim email notifikasi incident: '.$e->getMessage());
                    }

                    Log::warning("Incident detected: {$reason} - IP: {$ip}");
                }
            } catch (\Throwable $e) {
                Log::error('Security listener error: '.$e->getMessage());
            }
        });
    }
}
