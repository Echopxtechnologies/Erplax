<?php

use Illuminate\Support\Facades\Route;
use Modules\Todo\Http\Controllers\TodoController;
use App\Http\Middleware\EnsureIsAdmin; // ← USE THIS!

Route::prefix('admin/todo')
    ->middleware([EnsureIsAdmin::class]) // ← NOT 'auth'!
    ->name('admin.todo.')
    ->group(function () {
        // List & DataTable
        Route::get('/', [TodoController::class, 'index'])->name('index');
        Route::get('/data', [TodoController::class, 'dataTable'])->name('data');
        
        // Bulk Operations
        Route::post('/bulk-delete', [TodoController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Check overdue tasks (for scheduler/cron)
        Route::post('/check-overdue', [TodoController::class, 'checkOverdueTasks'])->name('check-overdue');
        
        // CRUD
        Route::get('/create', [TodoController::class, 'create'])->name('create');
        Route::post('/', [TodoController::class, 'store'])->name('store');
        Route::get('/{id}', [TodoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TodoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TodoController::class, 'update'])->name('update');
        Route::delete('/{id}', [TodoController::class, 'destroy'])->name('destroy');
        
        // Quick status toggle (AJAX)
        Route::post('/{id}/toggle-status', [TodoController::class, 'toggleStatus'])->name('toggle-status');
    });
