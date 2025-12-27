<?php

namespace Modules\Test1\Providers;

use Illuminate\Support\ServiceProvider;

class Test1ServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Test1';
    protected string $moduleNameLower = 'test1';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
    }

    public function register(): void
    {
        //
    }
}
