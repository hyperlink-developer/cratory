<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'app:backup-db';
    protected $description = 'Creates a timestamped SQLite backup of the MySQL database and cleans up backups older than 7 days.';

    public function handle()
    {
        $this->info("Starting MySQL to SQLite database backup...");
        
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $tempSqlitePath = $backupDir . '/temp_backup.sqlite';
        
        // Ensure clean temp file
        if (File::exists($tempSqlitePath)) {
            File::delete($tempSqlitePath);
        }
        File::put($tempSqlitePath, '');

        $this->info("1. Running migrations to build SQLite schema...");
        Artisan::call('migrate', [
            '--database' => 'sqlite_backup',
            '--force' => true
        ]);

        $this->info("2. Copying data from MySQL to SQLite...");
        
        // Disable foreign keys during insertion
        DB::connection('sqlite_backup')->statement('PRAGMA foreign_keys = OFF;');

        // Get all tables from mysql
        $tables = DB::connection('mysql')->select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableKey = "Tables_in_{$dbName}";
        
        foreach ($tables as $tableRow) {
            // Depending on MySQL version, the key might vary, but usually it's array or object
            // Let's safely extract table name
            $tableRowArray = (array) $tableRow;
            $tableName = array_values($tableRowArray)[0];
            
            if ($tableName === 'migrations') continue;
            
            $this->info("   -> Exporting {$tableName}...");
            
            // Get columns to find a safe order column
            $columns = DB::connection('mysql')->getSchemaBuilder()->getColumnListing($tableName);
            $orderCol = in_array('id', $columns) ? 'id' : (count($columns) > 0 ? $columns[0] : null);
            
            if ($orderCol) {
                // Chunk and copy
                DB::connection('mysql')->table($tableName)->orderBy($orderCol)->chunk(500, function ($records) use ($tableName) {
                    $data = array_map(function ($record) {
                        return (array) $record;
                    }, $records->toArray());
                    
                    DB::connection('sqlite_backup')->table($tableName)->insert($data);
                });
            } else {
                // Table has no columns? Skip.
                $this->info("      (Skipped - No columns found)");
            }
        }
        
        DB::connection('sqlite_backup')->statement('PRAGMA foreign_keys = ON;');

        $this->info("3. Archiving backup...");
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFilename = "cratory_db_{$timestamp}.sqlite";
        $backupPath = $backupDir . '/' . $backupFilename;

        File::move($tempSqlitePath, $backupPath);
        
        // Zip the backup
        $zipPath = $backupPath . '.zip';
        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile($backupPath, $backupFilename);
                $zip->close();
                File::delete($backupPath);
                $this->info("Database backed up and zipped: {$backupFilename}.zip");
            } else {
                $this->warn("Failed to zip the backup. Keeping the raw sqlite file.");
            }
        } else {
            $this->info("Database backed up: {$backupFilename}");
        }

        // Clean up old backups
        $this->info("4. Cleaning up backups older than 7 days...");
        $files = File::files($backupDir);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime());
            if ($lastModified->lt(now()->subDays(7))) {
                File::delete($file->getPathname());
                $deletedCount++;
            }
        }

        $this->info("Backup process completed. Cleaned up {$deletedCount} old backup(s).");
        return 0;
    }
}
