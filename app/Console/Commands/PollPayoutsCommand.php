<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PollPendingPayouts;
use Illuminate\Support\Facades\Log;

class PollPayoutsCommand extends Command
{
    protected $signature = 'payouts:poll';
    protected $description = 'Poll pending payouts from provider and update statuses';

    public function handle()
    {
        $this->info("Dispatching PollPendingPayouts job...");
        PollPendingPayouts::dispatch();
        $this->info("Dispatched.");
        Log::info("[CMD] payouts:poll dispatched job PollPendingPayouts");
        return 0;
    }
}
