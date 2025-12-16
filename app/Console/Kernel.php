<?php

namespace App\Console;

use App\Crons\SystemCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // =============================================
        // CORE SYSTEM CRONS (Auto-registered from SystemCron)
        // =============================================
        
        $this->registerSystemCrons($schedule);

        // =============================================
        // CUSTOM CRONS (From admin panel database)
        // =============================================
        
        $schedule->command('erp:run-crons')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/cron.log'));
    }

    /**
     * Auto-register crons from SystemCron class
     */
    protected function registerSystemCrons(Schedule $schedule): void
    {
        // Check if SystemCron class exists
        if (!class_exists(SystemCron::class)) {
            return;
        }

        // Check if schedules are defined
        if (!isset(SystemCron::$schedules) || empty(SystemCron::$schedules)) {
            return;
        }

        $systemCron = new SystemCron();

        foreach (SystemCron::$schedules as $method => $scheduleConfig) {
            // Check if method exists
            if (!method_exists($systemCron, $method)) {
                \Log::warning("[Kernel] SystemCron method not found: {$method}");
                continue;
            }

            // Create the scheduled task
            $task = $schedule->call(function () use ($systemCron, $method) {
                try {
                    $systemCron->$method();
                } catch (\Exception $e) {
                    \Log::channel('daily')->error("[SystemCron] {$method} failed: " . $e->getMessage());
                }
            })->name("system_cron_{$method}");

            // Apply the schedule
            $this->applySchedule($task, $scheduleConfig);
        }
    }

    /**
     * Apply schedule configuration to a task
     */
    protected function applySchedule($task, string $scheduleConfig): void
    {
        // Parse schedule config (e.g., "dailyAt:01:00" or "weekly")
        $parts = explode(':', $scheduleConfig, 2);
        $method = $parts[0];
        $params = isset($parts[1]) ? explode(':', $parts[1]) : [];

        match ($method) {
            // Every X minutes
            'everyMinute' => $task->everyMinute(),
            'everyTwoMinutes' => $task->everyTwoMinutes(),
            'everyFiveMinutes' => $task->everyFiveMinutes(),
            'everyTenMinutes' => $task->everyTenMinutes(),
            'everyFifteenMinutes' => $task->everyFifteenMinutes(),
            'everyThirtyMinutes' => $task->everyThirtyMinutes(),
            
            // Hourly
            'hourly' => $task->hourly(),
            'hourlyAt' => $task->hourlyAt((int)($params[0] ?? 0)),
            
            // Daily
            'daily' => $task->daily(),
            'dailyAt' => $task->dailyAt($params[0] ?? '00:00'),
            'twiceDaily' => $task->twiceDaily((int)($params[0] ?? 1), (int)($params[1] ?? 13)),
            
            // Weekly
            'weekly' => $task->weekly(),
            'weeklyOn' => $task->weeklyOn(
                (int)($params[0] ?? 0),           // Day (0=Sunday)
                ($params[1] ?? '00') . ':' . ($params[2] ?? '00')  // Time
            ),
            
            // Monthly
            'monthly' => $task->monthly(),
            'monthlyOn' => $task->monthlyOn(
                (int)($params[0] ?? 1),           // Day of month
                ($params[1] ?? '00') . ':' . ($params[2] ?? '00')  // Time
            ),
            
            // Quarterly & Yearly
            'quarterly' => $task->quarterly(),
            'yearly' => $task->yearly(),
            
            // Default to daily
            default => $task->daily(),
        };
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}