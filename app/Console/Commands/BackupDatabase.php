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
            $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i') ."-". config('app.env'). ".sql";
            $file_path = storage_path() . "/app/db_backups/" . $filename;
            $defaultConnection = config('database.default');
            $connectionDetails = config("database.connections.$defaultConnection");

            if (!empty($connectionDetails)) {
                // Extract individual details with fallback to environment variables
                $databaseName = isset($connectionDetails['database']) ? $connectionDetails['database'] : env('DB_DATABASE');
                $username     = isset($connectionDetails['username']) ? $connectionDetails['username'] : env('DB_USERNAME');
                $password     = isset($connectionDetails['password']) ? $connectionDetails['password'] : env('DB_PASSWORD');
                $host         = isset($connectionDetails['host']) ? $connectionDetails['host'] : env('DB_HOST');
            } else {
                // Fallback to environment variables if no connection details found
                $databaseName = env('DB_DATABASE');
                $username     = env('DB_USERNAME');
                $password     = env('DB_PASSWORD');
                $host         = env('DB_HOST');
            }

            $command = 'mysqldump --user=' . $username .' --password="' . $password . '" --host=' . $host . ' ' . $databaseName . ' > ' .$file_path;
            // $command = 'mysqldump --user=' . env('DB_USERNAME') .' --password="' . env('DB_PASSWORD') . '" --host=' . env('DB_HOST') . ' ' . env('DB_DATABASE') . ' > ' .$file_path;

            $response = NULL;
            $output  = NULL;
            $path = storage_path('app/db_backups');
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
