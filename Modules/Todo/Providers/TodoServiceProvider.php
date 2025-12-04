<?php

namespace Modules\Todo\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TodoServiceProvider extends ServiceProvider
{
    protected $modulePath = __DIR__ . '/../';

    public function boot()
    {
        $this->loadRoutesFrom($this->modulePath . 'Routes/web.php');
        $this->loadViewsFrom($this->modulePath . 'Resources/views', 'todo');
        $this->loadMigrationsFrom($this->modulePath . 'Database/Migrations');
        
        // Register Livewire components
        Livewire::component('todo::todo-list', \Modules\Todo\Http\Livewire\TodoList::class);
        Livewire::component('todo::create-todo', \Modules\Todo\Http\Livewire\CreateTodo::class);
        Livewire::component('todo::edit-todo', \Modules\Todo\Http\Livewire\EditTodo::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            $this->modulePath . 'Config/config.php',
            'todo'
        );
    }
}
