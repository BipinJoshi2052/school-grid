<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database to the storage folder';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dbName = env('DB_DATABASE'); // Get DB name from .env
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');
        $date = Carbon::today()->format('Y-m-d');
        $backupPath = storage_path("app/backup/{$dbName}-{$date}.sql");

        // Ensure the backup directory exists
        $backupDir = storage_path('app/backup');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $daysToKeep = 7;
        $oldBackups = File::glob(storage_path('app/backup/seatplan-*.sql'));
        foreach ($oldBackups as $file) {
            if (Carbon::createFromTimestamp(filemtime($file))->lt(Carbon::today()->subDays($daysToKeep))) {
                File::delete($file);
                $this->info("Deleted old backup: {$file}");
            }
        }

        // Command to backup MySQL database
        $command = "mysqldump --user={$dbUser} --password='{$dbPassword}' --host={$dbHost} {$dbName} > {$backupPath}";

        // Execute the command
        $output = null;
        $result = null;
        exec($command . ' 2>&1', $output, $result);

        // Inside the handle() method, after the exec command:
        if ($result === 0) {
            Log::info("Database backup created successfully: {$backupPath}");
            $this->info("Database backup created successfully: {$backupPath}");
        } else {
            Log::error("Backup failed: " . implode("\n", $output));
            $this->error("Backup failed: " . implode("\n", $output));
        }
    }
}