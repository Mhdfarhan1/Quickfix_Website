<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Console\Scheduling\Schedule; // 🔹 WAJIB

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS middleware global
        $middleware->append(HandleCors::class);

        // Alias middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        ]);

        $middleware->append(\App\Http\Middleware\AuditRequest::class);
    })

    // 🔹🔹🔹 TAMBAHKAN BAGIAN INI 🔹🔹🔹
    ->withSchedule(function (Schedule $schedule) {
        // Menjalankan auto payout setiap 1 jam
        $schedule->command('payouts:release')->hourly();

        // Jika ingin test:
        // $schedule->command('payouts:release')->everyMinute();
    })
    // 🔹🔹🔹 END SCHEDULER BLOCK 🔹🔹🔹

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
