<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateFreshAll extends Command
{
    protected $signature = 'migrate:fresh-all';
    protected $description = 'Fresh migrate MAIN and AUDIT databases';

    public function handle()
    {
        $this->freshMainDatabase();
        $this->freshAuditDatabase();

        $this->info("\n🎉 ALL DATABASES HAVE BEEN RESET SUCCESSFULLY!");
        return Command::SUCCESS;
    }

    // =====================================================
    // 1️⃣ FRESH MIGRATE UNTUK DATABASE UTAMA
    // =====================================================
    private function freshMainDatabase()
    {
        $this->info("🔥 Dropping tables in MAIN database...\n");

        Artisan::call('migrate:fresh', [
            '--seed' => true, // jalankan seeder
        ]);

        $this->line(Artisan::output());
    }

    // =====================================================
    // 2️⃣ FRESH DATABASE AUDIT (DROP SEMUA TABEL)
    // =====================================================
    private function freshAuditDatabase()
    {
        $this->info("\n🔥 Dropping tables in AUDIT database...\n");

        // Matikan foreign key constraints
        DB::connection('audit')->statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Ambil semua tabel audit
        $tables = DB::connection('audit')
            ->select("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'");

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            DB::connection('audit')->statement("DROP TABLE `$tableName`;");
        }

        // Aktifkan kembali foreign keys
        DB::connection('audit')->statement('SET FOREIGN_KEY_CHECKS = 1;');

        $this->info("✅ AUDIT database cleaned.");

        // Jalankan migrasi audit
        $this->runAuditMigrations();
    }

    // =====================================================
    // 3️⃣ MIGRASI KHUSUS AUDIT
    // =====================================================
    private function runAuditMigrations()
    {
        $this->info("\n📌 Running AUDIT migrations...\n");

        Artisan::call('migrate', [
            '--database' => 'audit',
            '--path' => 'database/migrations/audit', // folder khusus audit
            '--force' => true,
        ]);

        $this->line(Artisan::output());
    }
}
