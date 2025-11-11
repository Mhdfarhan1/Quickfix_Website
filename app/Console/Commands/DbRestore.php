<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use ZipArchive;

class DbRestore extends Command
{
    protected $signature = 'db:restore {filename}';
    protected $description = 'Restore database dari file backup .zip atau .sql.gz';

    public function handle()
    {
        $filename = $this->argument('filename');
        $db     = config('database.connections.mysql.database');
        $user   = config('database.connections.mysql.username');
        $pass   = config('database.connections.mysql.password');
        $host   = config('database.connections.mysql.host');

        $mysql  = env('MYSQL_CLIENT_EXE');

        $path = storage_path("app/backups/{$filename}");

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

        $tempFile = storage_path("app/temp_restore.sql");

        // ✅ ============= FILE .gz
        if (str_ends_with($filename, '.gz')) {
            $sqlContent = gzdecode(file_get_contents($path));

            if (!$sqlContent) {
                $this->error("❌ Gagal decode .gz, file rusak!");
                return Command::FAILURE;
            }

            file_put_contents($tempFile, $sqlContent);
        }

        // ✅ ============= FILE .zip
        elseif (str_ends_with($filename, '.zip')) {
            $zip = new ZipArchive;

            if ($zip->open($path) === TRUE) {
                $sqlContent = null;

                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $file = $zip->getNameIndex($i);

                    if (str_contains($file, 'db-dumps') && str_ends_with($file, '.sql')) {
                        $sqlContent = $zip->getFromName($file);
                        break;
                    }
                }

                $zip->close();

                if (!$sqlContent) {
                    $this->error("❌ Tidak ditemukan file .sql dalam ZIP!");
                    return Command::FAILURE;
                }

                file_put_contents($tempFile, $sqlContent);

            } else {
                $this->error("❌ Gagal membuka ZIP");
                return Command::FAILURE;
            }
        }

        else {
            $this->error("❌ Format tidak dikenali! Gunakan .gz atau .zip");
            return Command::FAILURE;
        }

        // ✅ ============= Restore pakai file redirect
        $cmd = "\"{$mysql}\" -h{$host} -u{$user} -p{$pass} {$db} < \"{$tempFile}\"";

        $process = Process::fromShellCommandline($cmd);
        $process->setTimeout(300);
        $process->run();

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
