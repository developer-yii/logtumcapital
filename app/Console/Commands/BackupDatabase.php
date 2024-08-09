<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use File;

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
        try
        {
            $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i') ."-". env('APP_ENV'). ".sql";
            $file_path = storage_path() . "/app/public/db_backups/" . $filename;
            $command = 'mysqldump --user=' . env('DB_USERNAME') .' --password="' . env('DB_PASSWORD') . '" --host=' . env('DB_HOST') . ' ' . env('DB_DATABASE') . ' > ' .$file_path;

            $response = NULL;
            $output  = NULL;
            $path = storage_path('app/public/db_backups');
            $check_directory_exists = (File::isDirectory($path))?true:false;
            if($check_directory_exists == false){
                File::makeDirectory($path, 0777, true, true);
            }

            exec($command, $output, $response);
            // response will be 0 if it ran successfully
            if($response == 0)
            {
                Log::info('Database backup successfully.');
            }
            else {
                throw new Exception('Database backup failed.');
            }
        } catch (Exception $e) {
            Log::info('Error: ' . $e->getMessage());
        }
        Log::info('DB backup ended');
    }
}
