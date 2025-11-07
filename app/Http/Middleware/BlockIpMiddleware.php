<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlockIpMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        // Cek apakah IP diblokir
        $blocked = DB::table('blocked_ips')->where('ip', $ip)->first();
        if ($blocked && (!$blocked->blocked_until || Carbon::parse($blocked->blocked_until)->isFuture())) {
            return response()->json([
                'message' => 'Access blocked due to suspicious activity.',
            ], 403);
        }

        // Jika user login dan akunnya diblokir
        if ($request->user() && $request->user()->is_blocked) {
            return response()->json([
                'message' => 'Your account is temporarily blocked. Please contact admin.',
            ], 403);
        }

        return $next($request);
    }
}
