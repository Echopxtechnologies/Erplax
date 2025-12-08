<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\Http\Controllers\StudentController;
use App\Http\Middleware\EnsureIsAdmin; // ← USE THIS!

Route::prefix('admin/student')
    ->middleware([EnsureIsAdmin::class]) // ← NOT 'auth'!
    ->name('admin.student.')
    ->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/data', [StudentController::class, 'dataTable'])->name('data');
        Route::post('/bulk-delete', [StudentController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('/', [StudentController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
    });
