<?php

namespace Modules\Attendance\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AttendanceServiceProvider extends ServiceProvider
{
    protected string $modulePath = __DIR__ . '/..';

    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerPublishAssets();
    }

    public function register(): void
    {
        //
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom("{$this->modulePath}/Database/Migrations");
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->name('api.')
            ->group("{$this->modulePath}/Routes/api.php");

        Route::middleware('web')
            ->group("{$this->modulePath}/Routes/web.php");
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom("{$this->modulePath}/Resources/views", 'attendance');
    }

    protected function registerPublishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                "{$this->modulePath}/public" => public_path('modules/attendance'),
            ], 'attendance-assets');
        }
    }
}
