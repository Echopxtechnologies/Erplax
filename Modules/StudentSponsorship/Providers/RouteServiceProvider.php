<?php

namespace Modules\StudentSponsorship\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\StudentSponsorship\Http\Controllers';

    public function boot(): void
    {
        parent::boot();
        $this->map(); // Register routes
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapClientRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('StudentSponsorship', '/Routes/web.php'));
    }

    protected function mapClientRoutes(): void
    {
        $clientRoutesPath = module_path('StudentSponsorship', '/Routes/client.php');
        
        if (file_exists($clientRoutesPath)) {
            Route::middleware(['web', 'client'])
                ->prefix('client')
                ->name('client.')
                ->group($clientRoutesPath);
        }
    }
}
