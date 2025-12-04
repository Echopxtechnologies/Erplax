<?php

use Illuminate\Support\Facades\Route;
use Modules\Todo\Http\Controllers\TodoController;

Route::prefix('admin/todo')
    ->middleware(['web', 'auth', 'admin'])
    ->name('admin.todo.')
    ->group(function () {
        Route::get('/', [TodoController::class, 'index'])->name('index');
    });
