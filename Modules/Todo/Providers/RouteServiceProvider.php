<?php

namespace Modules\Todo\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        $this->map(); // CRITICAL: Must call map() to register routes!
    }

    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapClientRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path('Todo', '/Routes/web.php'));
    }

    protected function mapClientRoutes(): void
    {
        $clientRoutesPath = module_path('Todo', '/Routes/client.php');
        
        if (file_exists($clientRoutesPath)) {
            Route::middleware(['web', 'client'])
                ->prefix('client')
                ->name('client.')
                ->group($clientRoutesPath);
        }
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(module_path('Todo', '/Routes/api.php'));
    }
}
