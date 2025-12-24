<?php

namespace Modules\Website\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WebsiteServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Website';
    protected string $moduleNameLower = 'website';

    public function boot(): void
    {
        $this->registerViews();
        $this->registerConfig();
        $this->registerLivewireComponents();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
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

    protected function registerLivewireComponents(): void
    {
        // Register Livewire components
        if (class_exists(Livewire::class)) {
            Livewire::component('website::product-search', \Modules\Website\Http\Livewire\ProductSearch::class);
            Livewire::component('website::product-grid', \Modules\Website\Http\Livewire\ProductGrid::class);
            Livewire::component('website::cart', \Modules\Website\Http\Livewire\Cart::class);
            Livewire::component('website::wishlist', \Modules\Website\Http\Livewire\Wishlist::class);
            Livewire::component('website::add-to-cart-button', \Modules\Website\Http\Livewire\AddToCartButton::class);
            Livewire::component('website::wishlist-button', \Modules\Website\Http\Livewire\WishlistButton::class);
        }
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
