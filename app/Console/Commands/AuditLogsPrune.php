<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditLogsPrune extends Command
{
    protected $signature = 'audit:prune {days=90}';
    protected $description = 'Prune audit logs older than X days (default 90)';

    public function handle()
    {
        $days = (int) $this->argument('days');
        $threshold = Carbon::now()->subDays($days)->toDateTimeString();

        $count = DB::table('audit_logs')->where('created_at', '<', $threshold)->delete();

        $this->info("Deleted {$count} audit log(s) older than {$days} days.");
        return 0;
    }
}
