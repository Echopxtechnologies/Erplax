<?php

use Illuminate\Support\Facades\Route;
use Modules\Todo\Http\Controllers\TodoController;
use App\Http\Middleware\EnsureIsAdmin;

Route::prefix('admin/todo')
    ->middleware([EnsureIsAdmin::class])
    ->name('admin.todo.')
    ->group(function () {
        // List & DataTable v2.0 (GET for list/export, POST for import)
        Route::get('/', [TodoController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/data', [TodoController::class, 'handleData'])->name('data');
        
        // Bulk Actions (DataTable v2.0)
        Route::post('/bulk-action', [TodoController::class, 'handleBulkAction'])->name('bulk-action');
        
        // Legacy bulk delete (backward compatibility)
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
