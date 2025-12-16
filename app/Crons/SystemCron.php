<?php

namespace App\Crons;

use App\Models\PasswordResetOtp;
use App\Models\CronLog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemCron
{
    /**
     * Define schedules for each method
     * 
     * Available schedules:
     * - everyMinute, everyFiveMinutes, everyTenMinutes, everyFifteenMinutes, everyThirtyMinutes
     * - hourly, hourlyAt:15 (at 15 minutes past)
     * - daily, dailyAt:01:00 (at 1 AM)
     * - weekly, weeklyOn:0:02:00 (Sunday at 2 AM, 0=Sunday, 1=Monday...)
     * - monthly, monthlyOn:1:00:00 (1st of month at midnight)
     */
    public static array $schedules = [
        'cleanOldOtps'   => 'weekly',
        'cleanCronLogs'  => 'dailyAt:01:00',
        'cleanLogs'      => 'weeklyOn:0:02:00',
        // Add new crons here - they will auto-register!
        // 'yourNewMethod' => 'daily',
    ];

        public function cleanOldOtps(): int
    {
        $now = now();
        $cutoff = $now->subHours(24);

        $query = PasswordResetOtp::where(function ($q) use ($now, $cutoff) {
            $q->where('expires_at', '<', $now)
            ->orWhere('is_used', true)
            ->orWhere('created_at', '<', $cutoff);
        });

        $deletedCount = $query->count();

        if ($deletedCount > 0) {
            $query->delete();
            Log::info("[Cron] Deleted {$deletedCount} old password reset OTP records");
        }

        return $deletedCount;
    }

    /**
     * Clean old cron logs from database (keep 30 days)
     */
    public function cleanCronLogs(): string
    {
        $deleted = CronLog::where('created_at', '<', now()->subDays(30))->delete();
        
        if ($deleted > 0) {
            Log::channel('daily')->info("[SystemCron] Cleaned {$deleted} old cron logs");
        }

        return "Deleted {$deleted} old cron log entries";
    }

    /**
     * Clean old log files (keep 7 days)
     */
    public function cleanLogs(): string
    {
        $logPath = storage_path('logs');
        $deletedCount = 0;
        $freedSpace = 0;

        $files = File::glob($logPath . '/*.log');

        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-7 days')) {
                $freedSpace += filesize($file);
                File::delete($file);
                $deletedCount++;
            }
        }

        // Truncate main laravel.log if > 50MB
        $mainLog = $logPath . '/laravel.log';
        if (File::exists($mainLog) && filesize($mainLog) > 50 * 1024 * 1024) {
            File::put($mainLog, '');
            $deletedCount++;
        }

        $freedMB = round($freedSpace / 1024 / 1024, 2);
        
        if ($deletedCount > 0) {
            Log::channel('daily')->info("[SystemCron] Cleaned {$deletedCount} log files, freed {$freedMB}MB");
        }

        return "Cleaned {$deletedCount} log files, freed {$freedMB}MB";
    }

    /**
     * Clear all caches
     */
    public function clearCache(): string
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        
        Log::channel('daily')->info("[SystemCron] Cache cleared");

        return "Cache cleared successfully";
    }

    // =============================================
    // ADD YOUR NEW METHODS BELOW
    // Don't forget to add schedule in $schedules array above!
    // =============================================

    // Example:
    // public function sendWeeklyReport(): string
    // {
    //     // Your logic here
    //     return "Report sent";
    // }
}
/**

**Done!** ✅ Kernel picks it up automatically.

## Schedule Options Reference

| Schedule | Example | Description |
|----------|---------|-------------|
| `everyMinute` | `'everyMinute'` | Every minute |
| `everyFiveMinutes` | `'everyFiveMinutes'` | Every 5 minutes |
| `hourly` | `'hourly'` | Every hour |
| `hourlyAt` | `'hourlyAt:30'` | At 30 minutes past every hour |
| `daily` | `'daily'` | Every day at midnight |
| `dailyAt` | `'dailyAt:09:00'` | Every day at 9 AM |
| `twiceDaily` | `'twiceDaily:1:13'` | At 1 AM and 1 PM |
| `weekly` | `'weekly'` | Every Sunday at midnight |
| `weeklyOn` | `'weeklyOn:1:09:00'` | Monday at 9 AM (0=Sun, 1=Mon) |
| `monthly` | `'monthly'` | 1st of month at midnight |
| `monthlyOn` | `'monthlyOn:15:10:00'` | 15th of month at 10 AM |

## Summary

┌────────────────────────────────────────────────────────────┐
│  Adding a new core cron:                                   │
│                                                            │
│  1. Add method in app/Crons/SystemCron.php                 │
│                                                            │
│  2. Add schedule in $schedules array:                      │
│     'methodName' => 'daily'                                │
│                                                            │
│  3. Done! Kernel auto-registers it ✅                      │
└────────────────────────────────────────────────────────────┘

 */