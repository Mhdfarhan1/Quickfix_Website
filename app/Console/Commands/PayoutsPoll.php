<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayoutService;

class PayoutsPoll extends Command
{
    protected $signature = 'payouts:poll';
    protected $description = 'Poll Flip for pending payouts';

    public function handle(PayoutService $svc)
    {
        $svc->processEligiblePayouts();
        $this->info('Polling done');
    }
}
    