<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
