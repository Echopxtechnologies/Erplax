<?php

namespace Modules\Service\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Service\Console\Commands\SendServiceReminders;

class ServiceServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Service';
    protected string $moduleNameLower = 'service';

    /**
     * Console commands provided by the module
     */
    protected $commands = [
        SendServiceReminders::class,
    ];

    public function boot(): void
    {
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        
        // Register commands
        $this->commands($this->commands);
        
        // Schedule the service reminder command
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Run service reminders daily at 9:00 AM
            $schedule->command('service:send-reminders')
                ->dailyAt('09:00')
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/service-reminders.log'));
        });
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    public function provides(): array
    {
        return [];
    }
}