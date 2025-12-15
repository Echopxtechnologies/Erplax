<?php

namespace Modules\Product\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        $this->map();
    }

    public function map(): void
    {
        Route::middleware('web')->group(module_path('Product', '/Routes/web.php'));
    }
}
