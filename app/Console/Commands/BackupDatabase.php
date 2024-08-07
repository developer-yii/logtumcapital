<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database and store it in the storage folder';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('DB backup started');
        $filename = 'backup-' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/db_backups/' . $filename);

        // Ensure the db_backups directory exists
        Storage::makeDirectory('db_backups');

        // Command to backup the database
        $command = "mysqldump --user=" . env('DB_USERNAME') .
                   " --password=" . env('DB_PASSWORD') .
                   " --host=" . env('DB_HOST') .
                   " " . env('DB_DATABASE') . " > " . $path;

        // Execute the command
        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            Log::info('Database backup successfully.');
        } else {
            Log::info('Database backup failed.');
        }
        Log::info('DB backup ended');
    }
}
