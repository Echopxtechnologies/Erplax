<?php

namespace Modules\Test2\Providers;

use Illuminate\Support\ServiceProvider;

class Test2ServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Test2';
    protected string $moduleNameLower = 'test2';

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
