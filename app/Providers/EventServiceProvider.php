<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\HandleFailedLogin::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
