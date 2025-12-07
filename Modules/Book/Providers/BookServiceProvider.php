<?php

namespace Modules\Book\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; //new


class BookServiceProvider extends ServiceProvider
{
    protected $modulePath = __DIR__ . '/../';

    public function boot()
    {
         Route::middleware('web')
            ->group(fn () => $this->loadRoutesFrom($this->modulePath . 'Routes/web.php'));
        
        $this->loadViewsFrom($this->modulePath . 'Resources/views', 'book');
        $this->loadMigrationsFrom($this->modulePath . 'Database/Migrations');
        // $this->loadRoutesFrom($this->modulePath . 'Routes/web.php');
        // $this->loadViewsFrom($this->modulePath . 'Resources/views', 'book');
        // $this->loadMigrationsFrom($this->modulePath . 'Database/Migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            $this->modulePath . 'Config/config.php',
            'book'
        );
    }
}
