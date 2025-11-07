<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()->role !== $role) {
            // Catat insiden ke log khusus
            Log::channel('incident')->warning('Percobaan akses ilegal', [
                'user_id' => $request->user()->id ?? null,
                'ip' => $request->ip(),
                'route' => $request->path(),
                'required_role' => $role,
                'actual_role' => $request->user()->role,
                'time' => now()->toDateTimeString(),
            ]);

            return response()->json(['message' => 'Akses ditolak untuk role ini.'], 403);
        }

        return $next($request);
    }
}
