<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DbRestore extends Command
{
    protected $signature = 'db:restore {filename}';
    protected $description = 'Restore database dari file backup .sql.gz';

    public function handle()
    {
        $filename = $this->argument('filename');
        $db     = config('database.connections.mysql.database');
        $user   = config('database.connections.mysql.username');
        $pass   = config('database.connections.mysql.password');
        $host   = config('database.connections.mysql.host');
        $mysql  = env('MYSQL_PATH', 'C:\\laragon\\bin\\mysql\\mysql-8.4.5-winx64\\bin\\mysql.exe');

        $path = storage_path("app/backups/db/{$filename}");

        if (!file_exists($path)) {
            $this->error("❌ File tidak ditemukan: {$path}");
            return Command::FAILURE;
        }

        $this->warn("⚠️ PERINGATAN: Database {$db} akan dioverwrite.");
        if (!$this->confirm("Lanjutkan restore?", false)) {
            $this->info("❌ Dibatalkan oleh pengguna.");
            return Command::SUCCESS;
        }

        $this->info("🔄 Mengembalikan database dari {$filename}...");

        // --- ekstrak isi file .gz ke file sementara ---
        $tempFile = storage_path("app/temp_restore.sql");
        $sqlContent = gzdecode(file_get_contents($path));
        file_put_contents($tempFile, $sqlContent);

        // --- jalankan mysql import via file ---
        $process = new Process([
            $mysql,
            "--protocol=tcp",
            "-h{$host}",
            "-u{$user}",
            "-p{$pass}",
            $db
        ]);

        // arahkan input dari file, bukan dari memory
        $process->setInput(file_get_contents($tempFile));
        $process->run();

        // hapus file sementara
        unlink($tempFile);

        if ($process->isSuccessful()) {
            $this->info("✅ Restore berhasil untuk database {$db}");
            Log::info("[DB RESTORE] Sukses: {$filename}");
        } else {
            $this->error("❌ Restore gagal:");
            $this->line($process->getErrorOutput());
            Log::error("[DB RESTORE] Gagal: " . $process->getErrorOutput());
        }

        return Command::SUCCESS;
    }
}
