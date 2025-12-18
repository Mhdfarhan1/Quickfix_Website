<?php

namespace App\Jobs;

use App\Models\Payout;
use App\Services\FlipService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayout implements ShouldQueue
{
    use Queueable;
    public $payout;

    public function __construct(Payout $payout)
    {
        $this->payout = $payout;
    }

    public function handle()
    {
        $p = $this->payout;

        $payload = [
            "account_number" => $p->account_number,
            "bank_code"      => $p->bank_code,
            "amount"         => $p->amount,
            "remark"         => "Payout teknisi order #{$p->order_id}",
            "reference_id"   => $p->reference_id
        ];

        $res = FlipService::disburse($payload);

        $p->raw_response = json_encode($res);

        if (isset($res['id'])) {
            $p->flip_id = $res['id'];
        }

        $p->status = $res['status'] ?? 'processing';
        $p->save();
    }
}
