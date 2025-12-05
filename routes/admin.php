<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\Settings\Permission;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;

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

    // contacts managenetn routes enga 
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/dummy1', [AdminController::class, 'dummy1'])->name('dummy1');
        Route::get('/dummy2', [AdminController::class, 'dummy2'])->name('dummy2');
        Route::get('/dummy3', [AdminController::class, 'dummy3'])->name('dummy3');
    });

    // Settings Routes - Controller handles auth checks
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [AdminController::class, 'general'])->name('general');
        Route::get('/email', [AdminController::class, 'email'])->name('email');
        
        // Permission - Livewire Component (uses middleware for auth)
        Route::get('/permission', Permission::class)->middleware([AdminMiddleware::class])->name('permission');

            // Permissions
        Route::get('/permissions/list', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');  

            // Roles & Permissions
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Role Permissions Management
        Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::get('/role-permissions/{roleId}/edit', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
        Route::post('/role-permissions/{roleId}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
        Route::post('/role-permissions/{roleId}/menus', [RolePermissionController::class, 'syncMenuAccess'])->name('role-permissions.menus');

    // Users CRUD
        Route::get('/users/list', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
                
    });

    
    



    // Admin Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});