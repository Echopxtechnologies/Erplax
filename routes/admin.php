<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\Settings\Permission;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with '/admin'
|
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest Routes (Login)
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Controller handles auth checks)
    |--------------------------------------------------------------------------
    */
    
    // Dashboard - Controller checks if user is admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Module Management Routes
    Route::prefix('modules')->name('modules.')->middleware([AdminMiddleware::class])->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::post('/upload', [ModuleController::class, 'uploadZip'])->name('upload');
        Route::post('/{alias}/install', [ModuleController::class, 'install'])->name('install');
        Route::post('/{alias}/activate', [ModuleController::class, 'activate'])->name('activate');
        Route::post('/{alias}/deactivate', [ModuleController::class, 'deactivate'])->name('deactivate');
        Route::delete('/{alias}/uninstall', [ModuleController::class, 'uninstall'])->name('uninstall');
        Route::delete('/{alias}/delete', [ModuleController::class, 'delete'])->name('delete');
    });

    // Settings Routes - Controller handles auth checks
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [AdminController::class, 'general'])->name('general');
        Route::get('/email', [AdminController::class, 'email'])->name('email');
        
        // Permission - Livewire Component (uses middleware for auth)
        Route::get('/permission', Permission::class)->middleware([AdminMiddleware::class])->name('permission');
    });

    // Admin Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});