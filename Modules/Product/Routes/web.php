<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use App\Http\Middleware\EnsureIsAdmin;

Route::prefix('admin/product')
    ->middleware([EnsureIsAdmin::class])
    ->name('admin.product.')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/data', [ProductController::class, 'dataTable'])->name('data');
        Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
    });
