<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\RoleMiddleware; // 🔹 tambahkan ini


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS middleware global
        $middleware->append(HandleCors::class);

        // 🔹 Daftarkan middleware custom Role
        $middleware->alias([
            'role' => RoleMiddleware::class,
            
        ]);

        $middleware->append(\App\Http\Middleware\AuditRequest::class);

        // Kamu juga bisa daftarkan middleware lain di sini bila perlu
        // $middleware->append(\App\Http\Middleware\TrustProxies::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
