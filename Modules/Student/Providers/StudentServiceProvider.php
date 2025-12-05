<?php

namespace Modules\Student\Providers;

use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    protected $modulePath = __DIR__ . '/../';

    public function boot()
    {
        $this->loadRoutesFrom($this->modulePath . 'Routes/web.php');
        $this->loadViewsFrom($this->modulePath . 'Resources/views', 'student');
        $this->loadMigrationsFrom($this->modulePath . 'Database/Migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            $this->modulePath . 'Config/config.php',
            'student'
        );
    }
}
