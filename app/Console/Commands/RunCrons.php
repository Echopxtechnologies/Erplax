<?php

namespace App\Console\Commands;

use App\Models\Admin\CronJob;
use App\Models\Admin\CronLog;
use App\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunCrons extends Command
{
    protected $signature = 'erp:run-crons 
                            {--task= : Run a specific cron by ID or name}
                            {--force : Force run even if not scheduled}';
    
    protected $description = 'Run all ERP cron jobs';

    protected array $results = [];

    public function handle()
    {
        $startTime = microtime(true);

        // Run specific task if provided
        if ($taskId = $this->option('task')) {
            return $this->runSingleTask($taskId);
        }

        $this->info('Starting ERP Cron Jobs...');
        $this->newLine();

        $crons = CronJob::active()->orderBy('sort_order')->get();

        if ($crons->isEmpty()) {
            $this->warn('No active cron jobs found.');
            return 0;
        }

        $force = $this->option('force');

        foreach ($crons as $cron) {
            if ($force || $cron->shouldRun()) {
                $this->runCron($cron);
            } else {
                $this->line("  <fg=gray>⏭ Skipping:</> {$cron->name} (not scheduled yet)");
            }
        }

        // Update last run time in options
        $totalTime = round((microtime(true) - $startTime) * 1000);
        Option::set('cron_last_run', now()->toDateTimeString(), ['group' => 'cron']);
        Option::set('cron_last_duration', $totalTime, ['group' => 'cron']);

        // Clean old logs (keep last 30 days)
        $deleted = CronLog::where('created_at', '<', now()->subDays(30))->delete();
        if ($deleted > 0) {
            $this->line("  <fg=gray>🗑 Cleaned {$deleted} old log entries</>");
        }

        $this->newLine();
        $this->info("✓ Cron run completed in {$totalTime}ms");

        // Show summary table
        if (!empty($this->results)) {
            $this->newLine();
            $this->table(
                ['Task', 'Status', 'Duration', 'Message'],
                collect($this->results)->map(fn($r) => [
                    $r['name'],
                    $r['status'] === 'success' ? '<fg=green>✓ Success</>' : '<fg=red>✗ Failed</>',
                    $r['duration'],
                    \Str::limit($r['message'], 40),
                ])->toArray()
            );
        }

        return 0;
    }

    /**
     * Run a single cron job
     */
    protected function runCron(CronJob $cron): array
    {
        $this->line("  <fg=cyan>▶ Running:</> {$cron->name}");
        
        $startTime = microtime(true);

        // Create log entry
        $log = CronLog::create([
            'cron_job_id' => $cron->id,
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Update cron status
        $cron->update(['last_status' => 'running']);

        try {
            $fullClass = $cron->getFullClass();
            $method = $cron->getMethodName();

            // Check if class exists
            if (!class_exists($fullClass)) {
                throw new \Exception("Class {$fullClass} not found");
            }

            $instance = app($fullClass);

            // Check if method exists
            if (!method_exists($instance, $method)) {
                throw new \Exception("Method {$method} not found in {$fullClass}");
            }

            // Run the cron
            $result = $instance->$method();
            $message = is_string($result) ? $result : 'Task completed successfully';

            $executionTime = round((microtime(true) - $startTime) * 1000);

            // Update log
            $log->update([
                'status' => 'success',
                'message' => $message,
                'execution_time' => $executionTime,
                'completed_at' => now(),
            ]);

            // Update cron
            $cron->update([
                'last_run' => now(),
                'last_duration' => $executionTime,
                'last_status' => 'success',
                'last_message' => $message,
            ]);

            $this->line("    <fg=green>✓ Completed</> in {$executionTime}ms");

            $this->results[] = [
                'name' => $cron->name,
                'status' => 'success',
                'duration' => $executionTime . 'ms',
                'message' => $message,
            ];

            return ['success' => true, 'message' => $message];

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);
            $errorMessage = $e->getMessage();

            // Update log
            $log->update([
                'status' => 'failed',
                'message' => $errorMessage,
                'execution_time' => $executionTime,
                'completed_at' => now(),
            ]);

            // Update cron
            $cron->update([
                'last_run' => now(),
                'last_duration' => $executionTime,
                'last_status' => 'failed',
                'last_message' => $errorMessage,
            ]);

            // Log error
            Log::error("[Cron] {$cron->name} failed: {$errorMessage}", [
                'cron_id' => $cron->id,
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error("    ✗ Failed: {$errorMessage}");

            $this->results[] = [
                'name' => $cron->name,
                'status' => 'failed',
                'duration' => $executionTime . 'ms',
                'message' => $errorMessage,
            ];

            return ['success' => false, 'message' => $errorMessage];
        }
    }

    /**
     * Run a single task by ID or name
     */
    protected function runSingleTask($identifier): int
    {
        $cron = is_numeric($identifier)
            ? CronJob::find($identifier)
            : CronJob::where('name', $identifier)->first();

        if (!$cron) {
            $this->error("Cron job not found: {$identifier}");
            return 1;
        }

        $this->info("Running cron: {$cron->name}");
        $result = $this->runCron($cron);

        return $result['success'] ? 0 : 1;
    }
}