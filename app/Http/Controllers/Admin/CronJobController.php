<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CronJob;
use App\Models\Admin\CronLog;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CronJobController extends Controller
{
    /**
     * Display cron jobs list with settings
     */
    public function index()
    {
        $cronJobs = CronJob::orderBy('sort_order')->get();
        $recentLogs = CronLog::with('cronJob')->latest()->limit(20)->get();
        
        $status = [
            'last_run' => Option::get('cron_last_run'),
            'last_duration' => Option::get('cron_last_duration'),
            'total_jobs' => CronJob::count(),
            'active_jobs' => CronJob::active()->count(),
        ];

        $commands = $this->getCronCommands();
        $availableCrons = $this->getAvailableCronClasses();

        return view('admin.cronjob.index', compact(
            'cronJobs', 
            'recentLogs', 
            'status', 
            'commands',
            'availableCrons'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $schedules = CronJob::scheduleOptions();
        $availableCrons = $this->getAvailableCronClasses();
        
        return view('admin.cronjob.create', compact('schedules', 'availableCrons'));
    }

    /**
     * Store a new cron job
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'schedule' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Validate method format (ClassName/methodName)
        if (!str_contains($request->method, '/')) {
            return back()->withErrors(['method' => 'Method must be in format: ClassName/methodName'])->withInput();
        }

        CronJob::create([
            'name' => $request->name,
            'method' => $request->method,
            'schedule' => $request->schedule,
            'description' => $request->description,
            'status' => $request->boolean('status', true),
            'sort_order' => CronJob::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.cronjob.index')->with('success', 'Cron job created successfully.');
    }

    /**
     * Show cron job details with logs
     */
    public function show(CronJob $cronjob)
    {
        $logs = $cronjob->logs()->latest()->paginate(50);
        
        return view('admin.cronjob.show', compact('cronjob', 'logs'));
    }

    /**
     * Show edit form
     */
    public function edit(CronJob $cronjob)
    {
        $schedules = CronJob::scheduleOptions();
        $availableCrons = $this->getAvailableCronClasses();
        
        return view('admin.cronjob.edit', compact('cronjob', 'schedules', 'availableCrons'));
    }

    /**
     * Update cron job
     */
    public function update(Request $request, CronJob $cronjob)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'schedule' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if (!str_contains($request->method, '/')) {
            return back()->withErrors(['method' => 'Method must be in format: ClassName/methodName'])->withInput();
        }

        $cronjob->update([
            'name' => $request->name,
            'method' => $request->method,
            'schedule' => $request->schedule,
            'description' => $request->description,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('admin.cronjob.index')->with('success', 'Cron job updated successfully.');
    }

    /**
     * Delete cron job
     */
    public function destroy(CronJob $cronjob)
    {
        $cronjob->delete();
        
        return redirect()->route('admin.cronjob.index')->with('success', 'Cron job deleted successfully.');
    }

    /**
     * Toggle cron job status
     */
    public function toggle(CronJob $cronjob)
    {
        $cronjob->update(['status' => !$cronjob->status]);
        
        $status = $cronjob->status ? 'enabled' : 'disabled';
        return back()->with('success', "Cron job '{$cronjob->name}' has been {$status}.");
    }

    /**
     * Run all cron jobs manually
     */
    public function runAll(Request $request)
    {
        try {
            Artisan::call('erp:run-crons', ['--force' => true]);
            $output = Artisan::output();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cron jobs executed successfully',
                    'output' => $output,
                ]);
            }
            
            return back()->with('success', 'All cron jobs executed successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            
            return back()->with('error', 'Failed to run cron jobs: ' . $e->getMessage());
        }
    }

    /**
     * Run a single cron job
     */
    public function runSingle(CronJob $cronjob)
    {
        try {
            Artisan::call('erp:run-crons', ['--task' => $cronjob->id]);
            
            return back()->with('success', "Cron job '{$cronjob->name}' executed successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    /**
     * View cron logs
     */
    public function logs(Request $request)
    {
        $logs = CronLog::with('cronJob')
            ->when($request->cron_job_id, fn($q) => $q->where('cron_job_id', $request->cron_job_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(50);

        $cronJobs = CronJob::pluck('name', 'id');

        return view('admin.cronjob.logs', compact('logs', 'cronJobs'));
    }

    /**
     * Clear old logs
     */
    public function clearLogs(Request $request)
    {
        $days = $request->get('days', 7);
        $deleted = CronLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return back()->with('success', "Cleared {$deleted} log entries older than {$days} days.");
    }

    /**
     * Web endpoint for cron (wget/curl)
     * This is called from: /cron/index
     */
    public function webRun(Request $request)
    {
        // Optional: Add security key check
        $expectedKey = config('app.cron_key');
        if ($expectedKey && $request->get('key') !== $expectedKey) {
            // Log warning but still run (or return 403 for strict mode)
        }

        $startTime = microtime(true);
        
        try {
            Artisan::call('erp:run-crons');
            $output = Artisan::output();
            
            $totalTime = round((microtime(true) - $startTime) * 1000);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'time' => $totalTime . 'ms',
                    'output' => $output,
                ]);
            }

            return response("Cron completed in {$totalTime}ms\n{$output}", 200)
                ->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }

            return response("Cron failed: {$e->getMessage()}", 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Get cron commands for different server setups
     */
    protected function getCronCommands(): array
    {
        $url = url('/cron/index');
        $basePath = base_path();

        return [
            'laravel' => [
                'label' => 'Laravel Scheduler (Recommended)',
                'command' => "* * * * * cd {$basePath} && php artisan schedule:run >> /dev/null 2>&1",
                'description' => 'Runs every minute, Laravel handles scheduling',
            ],
            'wget' => [
                'label' => 'Wget (Shared Hosting)',
                'command' => "*/5 * * * * wget -q -O- {$url} > /dev/null 2>&1",
                'description' => 'For hosts without SSH access',
            ],
            'curl' => [
                'label' => 'Curl (Alternative)',
                'command' => "*/5 * * * * curl -s {$url} > /dev/null 2>&1",
                'description' => 'Alternative to wget',
            ],
            'artisan' => [
                'label' => 'Artisan Command (Direct)',
                'command' => "*/5 * * * * cd {$basePath} && php artisan erp:run-crons >> /dev/null 2>&1",
                'description' => 'Direct artisan command',
            ],
        ];
    }

    /**
     * Scan app/Crons directory for available cron classes
     */
    protected function getAvailableCronClasses(): array
    {
        $cronsPath = app_path('Crons');
        $availableCrons = [];

        if (!File::isDirectory($cronsPath)) {
            return $availableCrons;
        }

        $files = File::files($cronsPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = $file->getFilenameWithoutExtension();
            $fullClass = "App\\Crons\\{$className}";

            if (!class_exists($fullClass)) {
                continue;
            }

            // Get public methods (excluding constructor and magic methods)
            $reflection = new \ReflectionClass($fullClass);
            $methods = [];

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class === $fullClass && !str_starts_with($method->name, '__')) {
                    $methods[] = $method->name;
                }
            }

            $availableCrons[$className] = $methods;
        }

        return $availableCrons;
    }
}