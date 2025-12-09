<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Livewire\Admin\Settings\Permission;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RolePermissionController;

Route::get('/admin', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::prefix('admin')->name('admin.')->group(function () {

    // Login routes - NO middleware at all
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    
    // Protected Admin Routes - Only EnsureIsAdmin middleware
    Route::middleware([EnsureIsAdmin::class])->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Module Management Routes
        Route::prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            Route::post('/upload', [ModuleController::class, 'uploadZip'])->name('upload');
            Route::post('/{alias}/install', [ModuleController::class, 'install'])->name('install');
            Route::post('/{alias}/activate', [ModuleController::class, 'activate'])->name('activate');
            Route::post('/{alias}/deactivate', [ModuleController::class, 'deactivate'])->name('deactivate');
            Route::delete('/{alias}/uninstall', [ModuleController::class, 'uninstall'])->name('uninstall');
            Route::delete('/{alias}/delete', [ModuleController::class, 'delete'])->name('delete');
        });

        // All other routes...
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/dummy1', [AdminController::class, 'dummy1'])->name('dummy1');
            Route::get('/dummy2', [AdminController::class, 'dummy2'])->name('dummy2');
            Route::get('/dummy3', [AdminController::class, 'dummy3'])->name('dummy3');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', [AdminController::class, 'settingsGeneral'])->name('general');
            Route::post('/general', [AdminController::class, 'saveSettingsGeneral'])->name('general.save');
            Route::get('/email', [AdminController::class, 'settingsEmail'])->name('email');
            Route::post('/email', [AdminController::class, 'saveSettingsEmail'])->name('email.save');
            Route::post('/email/test', [AdminController::class, 'sendTestEmail'])->name('email.test');
            Route::get('/permission', Permission::class)->name('permission');

            Route::get('/permissions/list', [PermissionController::class, 'index'])->name('permissions.index');
            Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
            Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
            Route::post('/permissions/bulk', [PermissionController::class, 'storeBulk'])->name('permissions.store-bulk');
            Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
            Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
            Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

            Route::get('/roles/list', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

            Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
            Route::get('/role-permissions/{roleId}/edit', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
            Route::post('/role-permissions/{roleId}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
            Route::post('/role-permissions/{roleId}/menus', [RolePermissionController::class, 'syncMenuAccess'])->name('role-permissions.menus');

            Route::get('/users/list', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::post('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });


        //testing 

Route::prefix('debug')->name('debug.')->group(function () {
    
    // Test current logged-in admin
    Route::get('/user', function () {
        $admin = Auth::guard('admin')->user();
        dd([
            'admin' => $admin,
            'id' => $admin?->id,
            'name' => $admin?->name,
            'email' => $admin?->email,
            'roles' => $admin?->roles?->pluck('name'),
            'permissions' => $admin?->getAllPermissions()?->pluck('name'),
        ]);
    });
    Route::get('/mail-status', function () {
    dd(\App\Services\Admin\MailService::getStatus());
    });
    Route::get('/send_mail', function () {
    $result = dd(\App\Services\Admin\MailService::sendTest('googleteam2@echopx.com'));
    dd($result);
    });
    // Test all options/settings
    Route::get('/options', function () {
        $options = \App\Models\Option::all();
        dd([
            'all_options' => $options->toArray(),
            'by_group' => $options->groupBy('group'),
        ]);
    });

    // Test specific settings groups
    Route::get('/settings/general', function () {
        dd([
            'company_name' => \App\Models\Option::get('company_name', ''),
            'company_email' => \App\Models\Option::get('company_email', ''),
            'company_phone' => \App\Models\Option::get('company_phone', ''),
            'company_address' => \App\Models\Option::get('company_address', ''),
            'company_website' => \App\Models\Option::get('company_website', ''),
            'company_gst' => \App\Models\Option::get('company_gst', ''),
            'company_logo' => \App\Models\Option::get('company_logo', ''),
            'company_favicon' => \App\Models\Option::get('company_favicon', ''),
            'site_timezone' => \App\Models\Option::get('site_timezone', 'Asia/Kolkata'),
            'date_format' => \App\Models\Option::get('date_format', 'd/m/Y'),
            'time_format' => \App\Models\Option::get('time_format', 'h:i A'),
            'currency_symbol' => \App\Models\Option::get('currency_symbol', '₹'),
            'currency_code' => \App\Models\Option::get('currency_code', 'INR'),
            'pagination_limit' => \App\Models\Option::get('pagination_limit', 10),
        ]);
    });

    // Test email settings
    Route::get('/settings/email', function () {
        dd([
            'mail_mailer' => \App\Models\Option::get('mail_mailer', 'smtp'),
            'mail_host' => \App\Models\Option::get('mail_host', ''),
            'mail_port' => \App\Models\Option::get('mail_port', 587),
            'mail_username' => \App\Models\Option::get('mail_username', ''),
            'mail_password' => '***hidden***',
            'mail_encryption' => \App\Models\Option::get('mail_encryption', 'tls'),
            'mail_from_address' => \App\Models\Option::get('mail_from_address', ''),
            'mail_from_name' => \App\Models\Option::get('mail_from_name', ''),
        ]);
    });

    // Test roles
    Route::get('/roles', function () {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        dd([
            'roles' => $roles->map(fn($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'guard' => $r->guard_name,
                'permissions' => $r->permissions->pluck('name'),
            ]),
        ]);
    });

    // Test permissions
    Route::get('/permissions', function () {
        $permissions = \Spatie\Permission\Models\Permission::all();
        dd([
            'count' => $permissions->count(),
            'permissions' => $permissions->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'guard' => $p->guard_name,
            ]),
        ]);
    });

    // Test admins
    Route::get('/admins', function () {
        $admins = \App\Models\Admin::with('roles')->get();
        dd([
            'admins' => $admins->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->name,
                'email' => $a->email,
                'is_active' => $a->is_active,
                'roles' => $a->roles->pluck('name'),
            ]),
        ]);
    });

    // Test menus
    Route::get('/menus', function () {
        $menus = \App\Models\Menu::with('children')->whereNull('parent_id')->get();
        dd([
            'menus' => $menus->toArray(),
        ]);
    });

    // Test modules
    Route::get('/modules', function () {
        $modules = \App\Models\Module::all();
        dd([
            'modules' => $modules->toArray(),
        ]);
    });

    // Test database tables
    Route::get('/tables', function () {
        $tables = \DB::select('SHOW TABLES');
        $key = 'Tables_in_' . env('DB_DATABASE');
        dd([
            'tables' => collect($tables)->pluck($key),
        ]);
    });
});



        });
    });
// });

// Remove the duplicate route at the top: Route::get('/admin', ...)