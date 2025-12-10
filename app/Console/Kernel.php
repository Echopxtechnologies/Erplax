<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * These commands will run when you use:
     * * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run our ERP cron system every 5 minutes
        // This checks all registered cron jobs and runs them if due
        $schedule->command('erp:run-crons')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/cron.log'));

        // ==============================================
        // You can also add Laravel-native scheduled tasks here:
        // ==============================================
        
        // Example: Clean old logs daily
        // $schedule->call(function () {
        //     \App\Models\CronLog::where('created_at', '<', now()->subDays(30))->delete();
        // })->daily()->name('clean_old_cron_logs');

        // Example: Backup database daily
        // $schedule->command('backup:run')->dailyAt('02:00');

        // Example: Send weekly reports
        // $schedule->command('reports:weekly')->weeklyOn(1, '08:00');

        // Example: Queue worker restart hourly
        // $schedule->command('queue:restart')->hourly();
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