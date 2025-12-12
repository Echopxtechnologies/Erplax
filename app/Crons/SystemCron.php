<?php
// app/Crons/SystemCron.php

namespace App\Crons;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemCron
{
    /**
     * Clean old log files
     */
    public function cleanLogs(): string
    {
        $logPath = storage_path('logs');
        $deletedCount = 0;
        $freedSpace = 0;

        // Get all log files
        $files = File::glob($logPath . '/*.log');

        foreach ($files as $file) {
            // Check if file is older than 7 days
            if (filemtime($file) < strtotime('-7 days')) {
                $freedSpace += filesize($file);
                File::delete($file);
                $deletedCount++;
            }
        }

        // Also truncate main laravel.log if it's too large (> 50MB)
        $mainLog = $logPath . '/laravel.log';
        if (File::exists($mainLog) && filesize($mainLog) > 50 * 1024 * 1024) {
            File::put($mainLog, ''); // Clear the file
            $deletedCount++;
        }

        $freedMB = round($freedSpace / 1024 / 1024, 2);
        
        Log::info("[Cron] Cleaned {$deletedCount} log files, freed {$freedMB}MB");
        
        return "Cleaned {$deletedCount} log files, freed {$freedMB}MB";
    }

    /**
     * Clean old cron logs from database
     */
    public function cleanCronLogs(): string
    {
        $deleted = \App\Models\CronLog::where('created_at', '<', now()->subDays(30))->delete();
        
        return "Deleted {$deleted} old cron log entries";
    }

    /**
     * Clear all caches
     */
    public function clearCache(): string
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        
        return "Cache cleared successfully";
    }
}