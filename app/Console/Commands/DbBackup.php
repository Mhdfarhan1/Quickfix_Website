<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class DbBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database MySQL ke storage/app/backups/db dengan rotasi & log';

    public function handle()
    {
        $db     = config('database.connections.mysql.database');
        $user   = config('database.connections.mysql.username');
        $pass   = config('database.connections.mysql.password');
        $host   = config('database.connections.mysql.host');
        $dump   = env('MYSQLDUMP_PATH', 'C:\\laragon\\bin\\mysql\\mysql-8.4.5-winx64\\bin\\mysqldump.exe');

        $folder     = 'backups/db';
        $storageDir = storage_path("app/{$folder}");
        $timestamp  = now()->format('Ymd_His');
        $filename   = "{$db}_{$timestamp}.sql.gz";
        $filePath   = "{$storageDir}/{$filename}";

        // pastikan folder ada
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $this->info("🚀 Memulai proses backup database...");
        Log::info("[DB BACKUP] Mulai: {$filename}");

        // Jalankan mysqldump
        $process = new Process([
            $dump,
            '--protocol=tcp',
            '--column-statistics=0',
            "-h{$host}",
            "-u{$user}",
            "-p{$pass}",
            $db,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $error = $process->getErrorOutput();
            $this->error("❌ Backup gagal: {$error}");
            Log::error("[DB BACKUP] Gagal: {$error}");
            return Command::FAILURE;
        }

        $sqlOutput = $process->getOutput();

        if (trim($sqlOutput) === '') {
            $this->error("⚠️ Dump kosong — kemungkinan password salah atau hak akses MySQL terbatas.");
            Log::warning("[DB BACKUP] Dump kosong untuk {$db}");
            return Command::FAILURE;
        }

        // Kompres & tulis langsung ke file
        file_put_contents($filePath, gzencode($sqlOutput));

        if (file_exists($filePath)) {
            $this->info("✅ Backup berhasil disimpan di: {$filePath}");
            Log::info("[DB BACKUP] Sukses disimpan di: {$filePath}");
        } else {
            $this->error("⚠️ File tidak ditemukan setelah proses — cek izin folder storage/app/backups.");
            Log::error("[DB BACKUP] File tidak ditemukan setelah proses: {$filePath}");
            return Command::FAILURE;
        }

        // Salin juga ke folder publik agar mudah diakses
        $publicDir = public_path('backups/db');
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0777, true);
        }

        $publicPath = "{$publicDir}/{$filename}";
        copy($filePath, $publicPath);

        $this->info("📁 File publik disalin ke: {$publicPath}");
        Log::info("[DB BACKUP] File publik: {$publicPath}");

        // Rotasi backup lama (>7 hari)
        $this->cleanupOldBackups($storageDir, 7);

        $this->info("🧹 Backup lama (>7 hari) telah dibersihkan.");
        Log::info("[DB BACKUP] Rotasi backup selesai.");

        return Command::SUCCESS;
    }

    protected function cleanupOldBackups(string $folder, int $days = 7)
    {
        $files = glob($folder . '/*.gz');
        $threshold = Carbon::now()->subDays($days);

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            if ($fileTime->lt($threshold)) {
                unlink($file);
                Log::info("[DB BACKUP] Dihapus: {$file}");
            }
        }
    }
}
