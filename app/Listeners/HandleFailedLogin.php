<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HandleFailedLogin
{
    public function handle(Failed $event): void
    {
        try {
            $request = request();
            $ip = $request->ip() ?? 'cli';
            $email = strtolower($request->input('email') ?? ($event->credentials['email'] ?? null));

            // Config
            $threshold = 7;           // attempts per round
            $baseMinutes = 5;         // initial lock duration in minutes
            $roundsKeySuffix = ':rounds'; // key suffix to track how many times user has been locked

            // Keys
            $keyIpCount = "login_failed_count:ip:{$ip}";
            $keyEmailCount = $email ? "login_failed_count:email:{$email}" : null;

            $keyIpRounds = "login_lock_rounds:ip:{$ip}";
            $keyEmailRounds = $email ? "login_lock_rounds:email:{$email}" : null;

            $keyIpLockUntil = "login_lock_until:ip:{$ip}";
            $keyEmailLockUntil = $email ? "login_lock_until:email:{$email}" : null;

            // Increment counters (persist via cache driver - file/database/redis)
            $ipCount = (int) Cache::get($keyIpCount, 0) + 1;
            Cache::put($keyIpCount, $ipCount, now()->addMinutes(60)); // keep counts for 60 mins

            $emailCount = 0;
            if ($keyEmailCount) {
                $emailCount = (int) Cache::get($keyEmailCount, 0) + 1;
                Cache::put($keyEmailCount, $emailCount, now()->addMinutes(60));
            }

            // Check whether currently locked (skip creating duplicate incident if already locked)
            $ipLockedUntil = Cache::get($keyIpLockUntil);
            $emailLockedUntil = $keyEmailLockUntil ? Cache::get($keyEmailLockUntil) : null;

            if (($ipLockedUntil && Carbon::parse($ipLockedUntil)->isFuture()) ||
                ($emailLockedUntil && Carbon::parse($emailLockedUntil)->isFuture())) {
                // already locked — don't escalate again
                return;
            }

            // If threshold reached for either dimension, create incident and lock
            if ($ipCount >= $threshold || ($keyEmailCount && $emailCount >= $threshold)) {
                // Determine rounds (how many previous locks happened)
                $ipRounds = (int) Cache::get($keyIpRounds, 0);
                $emailRounds = (int) ($keyEmailRounds ? Cache::get($keyEmailRounds, 0) : 0);

                // choose the larger rounds among ip/email to compute lock time
                $rounds = max($ipRounds, $emailRounds);
                $rounds = $rounds + 1; // this is the next round number

                // exponential backoff: baseMinutes * (2 ** (rounds-1))
                $lockMinutes = $baseMinutes * (2 ** ($rounds - 1));

                $lockUntil = Carbon::now()->addMinutes($lockMinutes);

                // Incident data
                $incident = [
                    'type' => 'bruteforce_login',
                    'description' => "Excessive failed login attempts (threshold {$threshold}) from IP {$ip}" . ($email ? " for {$email}" : ''),
                    'ip' => $ip,
                    'user_id' => null,
                    'metadata' => json_encode([
                        'ip_count' => $ipCount,
                        'email_count' => $emailCount,
                        'rounds' => $rounds,
                        'lock_minutes' => $lockMinutes,
                    ]),
                    'status' => 'open',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::connection('audit')->table('incidents')->insert($incident);

                // Block IP in DB table
                DB::connection('audit')->table('blocked_ips')->updateOrInsert(
                    ['ip' => $ip],
                    [
                        'blocked_until' => $lockUntil,
                        'reason' => "Brute-force login detected; locked for {$lockMinutes} minutes",
                        'updated_at' => now(),
                    ]
                );

                // Set cache lock until values and increment rounds
                Cache::put($keyIpLockUntil, $lockUntil->toDateTimeString(), $lockMinutes * 60 + 60);
                Cache::put($keyIpRounds, $rounds, now()->addDays(30)); // keep rounds for 30 days

                if ($keyEmailCount) {
                    Cache::put($keyEmailLockUntil, $lockUntil->toDateTimeString(), $lockMinutes * 60 + 60);
                    Cache::put($keyEmailRounds, $rounds, now()->addDays(30));
                }

                // Reset counters so we start fresh after lock expires
                Cache::forget($keyIpCount);
                if ($keyEmailCount) Cache::forget($keyEmailCount);

                Log::warning("Brute-force detected. IP blocked: {$ip} until {$lockUntil}. Rounds: {$rounds}");

                // Optionally set is_blocked flag on user if exist
                if (!empty($event->user)) {
                    $userId = $event->user->id ?? $event->user->id_user ?? null;
                    if ($userId) {
                        DB::table('user')->where('id_user', $userId)->update(['is_blocked' => true]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('HandleFailedLogin error: ' . $e->getMessage());
        }
    }
}
