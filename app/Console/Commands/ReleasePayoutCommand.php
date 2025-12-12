<?php

namespace App\Console\Commands;
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlipService;
use App\Http\Controllers\Api\PayoutController;

class ReleasePayoutCommand extends Command
{
    protected $signature = 'payout:release';
    protected $description = 'Release pending payouts and send to Flip API';

    public function handle()
    {
        $this->info("Menjalankan payout release...");

        // Panggil controller kamu
        $controller = new PayoutController();
        $controller->release(app(FlipService::class));

        $this->info("Payout release selesai.");

        return Command::SUCCESS;
    }
}
