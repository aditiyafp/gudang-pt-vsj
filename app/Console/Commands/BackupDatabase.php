<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Otomatis membuat backup database PT Gudang Kebersihan VSJ';

    public function handle()
    {
        $this->info('Memulai proses backup database PT Gudang Kebersihan VSJ...');

        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $dbConnection = config('database.default');

        if ($dbConnection === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');

            if ($dbPath === ':memory:') {
                $this->info("Database in-memory detected. Backup skipped for test.");
                return 0;
            }

            if (!File::exists($dbPath)) {
                $this->error("Database SQLite tidak ditemukan di: {$dbPath}");
                return 1;
            }

            $backupFileName = "backup_vsj_{$timestamp}.sqlite";
            $targetPath = $backupDir . '/' . $backupFileName;

            if (File::copy($dbPath, $targetPath)) {
                $this->info("Backup database berhasil dibuat: {$backupFileName}");
                Log::info("Backup database otomatis berhasil: {$backupFileName}");
            } else {
                $this->error("Gagal menyalin file database SQLite.");
                return 1;
            }
        } else {
            // Mysql Fallback
            $host = config('database.connections.mysql.host');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            $backupFileName = "backup_vsj_{$timestamp}.sql";
            $targetPath = $backupDir . '/' . $backupFileName;

            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($targetPath)
            );

            $returnVar = null;
            $output = null;
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $this->info("Backup MySQL berhasil dibuat: {$backupFileName}");
                Log::info("Backup database MySQL otomatis berhasil: {$backupFileName}");
            } else {
                $this->error("Gagal melakukan backup MySQL.");
                return 1;
            }
        }

        // Clean up backups older than 30 days
        $files = File::files($backupDir);
        $now = Carbon::now();
        foreach ($files as $file) {
            if ($now->diffInDays(Carbon::createFromTimestamp($file->getMTime())) >= 30) {
                File::delete($file->getRealPath());
                $this->info("Backup lama dihapus: " . $file->getFilename());
            }
        }

        $this->info('Proses backup selesai!');
        return 0;
    }
}
