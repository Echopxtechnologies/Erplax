<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\AllowAdminRegistration;
use App\Livewire\Admin\Settings\Permission;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\CronJobController;
use App\Http\Controllers\Admin\Customers\Index as CustomersIndexController;
use App\Http\Controllers\Admin\Customers\Form as CustomersFormController;
use App\Http\Controllers\Admin\Customers\ContactsForm as ContactsFormController;
use App\Http\Controllers\Admin\Customers\CustomerGroupsForm as CustomerGroupsFormController;
use App\Http\Controllers\Admin\ClientUserController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordOtpController;

use App\Http\Controllers\Admin\Sales\SalesIndexController;
use App\Http\Controllers\Admin\Sales\ProposalsIndexController;
use App\Http\Controllers\Admin\Sales\ProposalsFormController;
use App\Http\Controllers\Admin\Sales\ProposalsController;
use App\Http\Controllers\Admin\Sales\EstimationsIndexController;
use App\Http\Controllers\Admin\Sales\EstimationsFormController;
use App\Http\Controllers\Admin\Sales\EstimationsController;
use App\Http\Controllers\Admin\Sales\InvoicesIndexController;
use App\Http\Controllers\Admin\Sales\InvoicesFormController;
use App\Http\Controllers\Admin\Sales\InvoicesController;
use App\Http\Controllers\Admin\Sales\PaymentsIndexController;


Route::get('/admin', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::prefix('admin')->name('admin.')->group(function () {
    // ✅ Auto-redirect: /admin → setup or login based on admin count
    Route::get('/', function () {
        if (\App\Models\Admin::count() === 0) {
            return redirect()->route('admin.setup');
        }
        return redirect()->route('admin.login');
    })->name('index');

    // ✅ Setup routes - only accessible when no admins exist
    Route::middleware('admin.registration.allowed')->group(function () {
        Route::get('/setup', [AdminRegisterController::class, 'showRegisterForm'])->name('setup');
        Route::post('/setup', [AdminRegisterController::class, 'register'])->name('setup.submit');
    });
    // Forgot Password (OTP)
    Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showEmailForm'])
        ->name('forgot-password.form');

    Route::post('/forgot-password', [ForgotPasswordOtpController::class, 'sendOtp'])
        ->middleware('throttle:5,10')->name('forgot-password.send');

    // Verify OTP
    Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showOtpForm'])
        ->name('verify-otp.form');

    Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])
        ->middleware('throttle:5,10')->name('verify-otp.check');

    // Reset Password (after OTP verified)
    Route::get('/reset-password', [ForgotPasswordOtpController::class, 'showResetForm'])
        ->name('reset-password.form');

    Route::post('/reset-password', [ForgotPasswordOtpController::class, 'resetPassword'])
        ->name('reset-password.update');


    // ✅ Login routes - always available
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

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', [AdminController::class, 'settingsGeneral'])->name('general');
            Route::post('/general', [AdminController::class, 'saveSettingsGeneral'])->name('general.save');
            Route::get('/email', [AdminController::class, 'settingsEmail'])->name('email');
            Route::post('/email', [AdminController::class, 'saveSettingsEmail'])->name('email.save');
            Route::post('/email/test', [AdminController::class, 'sendTestEmail'])->name('email.test');
            Route::get('/permission', Permission::class)->name('permission');
            Route::get('/system-info', [AdminController::class, 'systemInfoIndex'])->name('system-info');
            Route::post('/system-info/clear-sessions', [AdminController::class, 'clearSessions'])->name('system-info.clear-sessions');

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

            Route::prefix('client')->name('client.')->group(function () {
                Route::get('/', [ClientUserController::class, 'index'])->name('index');
                Route::get('/export', [App\Http\Controllers\Admin\ClientUserController::class, 'export'])->name('export');
                Route::get('/create', [ClientUserController::class, 'create'])->name('create');
                Route::post('/', [ClientUserController::class, 'store'])->name('store');
                Route::post('/bulk-delete', [ClientUserController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/{id}', [ClientUserController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [ClientUserController::class, 'edit'])->name('edit');
                Route::put('/{id}', [ClientUserController::class, 'update'])->name('update');
                Route::post('/{id}/toggle-status', [ClientUserController::class, 'toggleStatus'])->name('toggle-status');
                Route::delete('/{id}', [ClientUserController::class, 'destroy'])->name('destroy');
            });
             Route::prefix('taxes')->name('taxes.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'taxIndex'])->name('index');
            Route::match(['get', 'post'], '/data', [App\Http\Controllers\Admin\AdminController::class, 'taxData'])->name('data');
            Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'taxStore'])->name('store');
            Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'taxUpdate'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'taxDestroy'])->name('destroy');
        });
        
// Countries (MATCHING TAX PATTERN)
Route::prefix('countries')->name('countries.')->group(function () {
    Route::get('/', [AdminController::class, 'countriesIndex'])->name('index');
    Route::match(['get', 'post'], '/data', [AdminController::class, 'countriesData'])->name('data');
    Route::post('/', [AdminController::class, 'countryStore'])->name('store');
    Route::put('/{id}', [AdminController::class, 'countryUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'countryDestroy'])->name('destroy');
});

// Currencies (MATCHING TAX PATTERN)
Route::prefix('currencies')->name('currencies.')->group(function () {
    Route::get('/', [AdminController::class, 'currenciesIndex'])->name('index');
    Route::match(['get', 'post'], '/data', [AdminController::class, 'currenciesData'])->name('data');
    Route::post('/', [AdminController::class, 'currencyStore'])->name('store');
    Route::put('/{id}', [AdminController::class, 'currencyUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'currencyDestroy'])->name('destroy');
});

// Payment Methods (MATCHING TAX PATTERN)
Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [AdminController::class, 'paymentMethodsIndex'])->name('index');
    Route::match(['get', 'post'], '/data', [AdminController::class, 'paymentMethodsData'])->name('data');
    Route::post('/', [AdminController::class, 'paymentMethodStore'])->name('store');
    Route::put('/{id}', [AdminController::class, 'paymentMethodUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'paymentMethodDestroy'])->name('destroy');
});
// Bank Details (add after payment-methods routes)
Route::prefix('bank-details')->name('bank-details.')->group(function () {
    Route::get('/', [AdminController::class, 'bankDetailsIndex'])->name('index');
    Route::match(['get', 'post'], '/data', [AdminController::class, 'bankDetailsData'])->name('data');
    Route::post('/', [AdminController::class, 'bankDetailStore'])->name('store');
    Route::put('/{id}', [AdminController::class, 'bankDetailUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'bankDetailDestroy'])->name('destroy');
});
// Timezones (MATCHING TAX PATTERN)
Route::prefix('timezones')->name('timezones.')->group(function () {
    Route::get('/', [AdminController::class, 'timezonesIndex'])->name('index');
    Route::match(['get', 'post'], '/data', [AdminController::class, 'timezonesData'])->name('data');
    Route::post('/', [AdminController::class, 'timezoneStore'])->name('store');
    Route::put('/{id}', [AdminController::class, 'timezoneUpdate'])->name('update');
    Route::delete('/{id}', [AdminController::class, 'timezoneDestroy'])->name('destroy');
});

        });
        Route::prefix('cronjob')->name('cronjob.')->group(function () {
            Route::get('/', [CronJobController::class, 'index'])->name('index');
            Route::get('/create', [CronJobController::class, 'create'])->name('create');
            Route::post('/', [CronJobController::class, 'store'])->name('store');
            Route::get('/{cronjob}', [CronJobController::class, 'show'])->name('show');
            Route::get('/{cronjob}/edit', [CronJobController::class, 'edit'])->name('edit');
            Route::put('/{cronjob}', [CronJobController::class, 'update'])->name('update');
            Route::delete('/{cronjob}', [CronJobController::class, 'destroy'])->name('destroy');
            
            // Actions
            Route::patch('/{cronjob}/toggle', [CronJobController::class, 'toggle'])->name('toggle');
            Route::post('/run-all', [CronJobController::class, 'runAll'])->name('run-all');
            Route::post('/{cronjob}/run', [CronJobController::class, 'runSingle'])->name('run-single');
            
            // Logs
            Route::get('/logs/all', [CronJobController::class, 'logs'])->name('logs');
            Route::delete('/logs/clear', [CronJobController::class, 'clearLogs'])->name('clear-logs');
        });

  // ============================================
// CUSTOMERS ROUTES (Main)
// ============================================
Route::prefix('customers')->name('customers.')->group(function () {
    // List & DataTable
    Route::get('/', [CustomersIndexController::class, 'index'])->name('index');
    Route::get('/data', [CustomersIndexController::class, 'data'])->name('data');
    
    // AJAX Helpers (MUST BE BEFORE {customer} ROUTES)
    Route::get('/search-company', [CustomersFormController::class, 'searchCompany'])->name('searchCompany');
    Route::post('/add-group', [CustomersFormController::class, 'addGroup'])->name('addGroup');
    Route::post('/get-company-details', [CustomersFormController::class, 'getCompanyDetails'])->name('getCompanyDetails');
    
    // Bulk Actions
    Route::post('/bulk-delete', [CustomersIndexController::class, 'bulkDelete'])->name('bulk-delete');
    
    // Create Customer (Individual OR Company with first contact)
    Route::get('/create', [CustomersFormController::class, 'create'])->name('create');
    Route::post('/', [CustomersFormController::class, 'store'])->name('store');
    
    // Toggle Status (BEFORE dynamic {customer} routes)
    Route::patch('/{id}/toggle-status', [CustomersIndexController::class, 'toggleStatus'])->name('toggle-status');
    
    // View, Edit, Update, Delete Customer
    Route::get('/{customer}', [CustomersFormController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [CustomersFormController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CustomersFormController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CustomersFormController::class, 'destroy'])->name('destroy');
    
    // ✨ NEW: Add Contact to Company (nested routes)
    Route::prefix('{customerId}/contacts')->name('contacts.')->group(function () {
        Route::get('/create', [ContactsFormController::class, 'create'])->name('create');
        Route::post('/', [ContactsFormController::class, 'store'])->name('store');
    });
});

// ============================================
// CONTACTS ROUTES (Direct access for edit/delete)
// ============================================
Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::get('/{id}/edit', [ContactsFormController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ContactsFormController::class, 'update'])->name('update');
    Route::delete('/{id}', [ContactsFormController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [CustomersIndexController::class, 'bulkDelete'])->name('bulk-delete');
});

// ============================================
// CUSTOMER GROUPS ROUTES ⬅️ ADD THIS WHOLE SECTION
// ============================================
Route::prefix('customer-groups')->name('customer-groups.')->group(function () {
    Route::get('/', [CustomerGroupsFormController::class, 'index'])->name('index');
    Route::get('/create', [CustomerGroupsFormController::class, 'create'])->name('create');
    Route::post('/', [CustomerGroupsFormController::class, 'store'])->name('store');
    Route::get('/{id}', [CustomerGroupsFormController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [CustomerGroupsFormController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerGroupsFormController::class, 'update'])->name('update');
    Route::delete('/{id}', [CustomerGroupsFormController::class, 'destroy'])->name('destroy');
    Route::get('/all/list', [CustomerGroupsFormController::class, 'getAll'])->name('all');
});



Route::get('/sales', [SalesIndexController::class, 'index'])->name('sales.index');

/*
|--------------------------------------------------------------------------
| Proposals
|--------------------------------------------------------------------------
*/
Route::prefix('sales/proposals')->name('sales.proposals.')->group(function () {
    // List & DataTable
    Route::get('/',       [ProposalsIndexController::class, 'index'])->name('index');
    Route::get('/data',   [ProposalsIndexController::class, 'data'])->name('data');
    Route::get('/export', [ProposalsIndexController::class, 'export'])->name('export');

    // Create / Store
    Route::get('/create', [ProposalsFormController::class, 'create'])->name('create');
    Route::post('/',      [ProposalsFormController::class, 'store'])->name('store');

    // Bulk actions
    Route::post('/store-user',   [ProposalsController::class, 'storeUser'])->name('storeUser');
    Route::post('/bulk-delete',  [ProposalsController::class, 'bulkDelete'])->name('bulkDelete');

    // API endpoints (form helpers)
    Route::get('/products/search',     [ProposalsController::class, 'searchProducts'])->name('products.search');
    Route::get('/products/{product}',  [ProposalsController::class, 'getProduct'])->name('products.get');
    Route::get('/taxes',               [ProposalsController::class, 'getTaxes'])->name('taxes');
    Route::get('/customer/{customer}', [ProposalsController::class, 'getCustomerDetails'])->name('customer');

    // Single proposal (CRUD + print)
    Route::get('/{proposal}',        [ProposalsFormController::class, 'show'])->name('show');
    Route::get('/{proposal}/edit',   [ProposalsFormController::class, 'edit'])->name('edit');
    Route::put('/{proposal}',        [ProposalsFormController::class, 'update'])->name('update');
    Route::delete('/{proposal}',     [ProposalsFormController::class, 'destroy'])->name('destroy');
    Route::get('/{proposal}/print',  [ProposalsFormController::class, 'print'])->name('print');

    // Actions
    Route::post('/{proposal}/duplicate',       [ProposalsController::class, 'duplicate'])->name('duplicate');
    Route::post('/{proposal}/status',          [ProposalsController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/{proposal}/send',            [ProposalsController::class, 'send'])->name('send');
    Route::post('/{proposal}/convert-invoice', [ProposalsController::class, 'convertToInvoice'])->name('convertToInvoice');
});

/*
|--------------------------------------------------------------------------
| Estimations
|--------------------------------------------------------------------------
*/
Route::prefix('sales/estimations')->name('sales.estimations.')->group(function () {
    // List & DataTable
    Route::get('/',      [EstimationsIndexController::class, 'index'])->name('index');
    Route::get('/data',  [EstimationsIndexController::class, 'data'])->name('data');
    Route::post('/import',[EstimationsIndexController::class, 'import'])->name('import');

    // Create / Store
    Route::get('/create', [EstimationsFormController::class, 'create'])->name('create');
    Route::post('/',      [EstimationsFormController::class, 'store'])->name('store');

    // Bulk
    Route::post('/bulk-delete', [EstimationsFormController::class, 'bulkDestroy'])->name('bulkDestroy');

    // Helpers
    Route::get('/products/search',     [EstimationsFormController::class, 'searchProducts'])->name('searchProducts');
    Route::get('/customer/{customer}', [EstimationsFormController::class, 'getCustomer'])->name('customer');

    // Create from Proposal
    Route::post('/from-proposal/{proposal}', [EstimationsFormController::class, 'fromProposal'])->name('fromProposal');

    // Single estimation (CRUD)
    Route::get('/{estimation}',      [EstimationsFormController::class, 'show'])->name('show');
    Route::get('/{estimation}/edit', [EstimationsFormController::class, 'edit'])->name('edit');
    Route::put('/{estimation}',      [EstimationsFormController::class, 'update'])->name('update');
    Route::delete('/{estimation}',   [EstimationsFormController::class, 'destroy'])->name('destroy');
    // Actions
    Route::get('/{estimation}/duplicate', [EstimationsFormController::class, 'duplicate'])->name('duplicate');
Route::post('/{estimation}/status',   [EstimationsController::class, 'updateStatus'])->name('updateStatus');});
/*
|--------------------------------------------------------------------------
| Taxes (JSON)
|--------------------------------------------------------------------------
*/
Route::get('/sales/taxes', function () {
    return response()->json(
        \App\Models\Tax::where('active', 1)
            ->where('rate', '>', 0)  // Exclude "No Tax" from multi-select
            ->orderBy('name')
            ->get()
    );
})->name('sales.taxes');

/*
|--------------------------------------------------------------------------
| Invoices
|--------------------------------------------------------------------------
*/
Route::prefix('sales/invoices')->name('sales.invoices.')->group(function () {
Route::get('/search-products', [InvoicesFormController::class, 'searchProducts'])->name('searchProducts');



 Route::get('/taxes', [TaxesController::class, 'index'])->name('taxes.index');
    Route::post('/taxes', [TaxesController::class, 'store'])->name('taxes.store');
    Route::put('/taxes/{tax}', [TaxesController::class, 'update'])->name('taxes.update');
    Route::delete('/taxes/{tax}', [TaxesController::class, 'destroy'])->name('taxes.destroy');
    // Inside admin sales routes
    // List & DataTable
    Route::get('/',      [InvoicesIndexController::class, 'index'])->name('index');
    Route::get('/data',  [InvoicesIndexController::class, 'data'])->name('data');
    Route::post('/import',[InvoicesIndexController::class, 'import'])->name('import');

    // Create / Store
    Route::get('/create', [InvoicesFormController::class, 'create'])->name('create');
    Route::post('/',      [InvoicesFormController::class, 'store'])->name('store');

    // Bulk
    Route::post('/bulk-delete', [InvoicesController::class, 'bulkDestroy'])->name('bulkDestroy');

    // Helpers
    Route::get('/product/{product}',  [InvoicesController::class, 'getProduct'])->name('getProduct');
    Route::get('/customer/{customer}',[InvoicesFormController::class, 'getCustomer'])->name('customer');

    // Create from Estimation
    Route::post('/from-estimation/{estimation}', [InvoicesController::class, 'createFromEstimation'])->name('fromEstimation');

    // Single invoice (CRUD)
    Route::get('/{invoice}',      [InvoicesFormController::class, 'show'])->name('show');
    Route::get('/{invoice}/edit', [InvoicesFormController::class, 'edit'])->name('edit');
    Route::put('/{invoice}',      [InvoicesFormController::class, 'update'])->name('update');
    Route::delete('/{invoice}',   [InvoicesFormController::class, 'destroy'])->name('destroy');

    // Payments (pick one controller + one route name, avoid duplicates)
    Route::post('/{invoice}/payment', [InvoicesController::class, 'recordPayment'])->name('recordPayment');

    // Actions
    Route::get('/{invoice}/duplicate', [InvoicesController::class, 'duplicate'])->name('duplicate');
    Route::post('/{invoice}/status',   [InvoicesController::class, 'updateStatus'])->name('updateStatus');
    });

/*
|--------------------------------------------------------------------------
| Admin Payments
|--------------------------------------------------------------------------
*/
    Route::prefix('admin/sales/payments')->name('admin.sales.payments.')->group(function () {
        Route::get('/',          [PaymentsIndexController::class, 'index'])->name('index');
        Route::get('/data',      [PaymentsIndexController::class, 'data'])->name('data');
        Route::get('/{payment}', [PaymentsIndexController::class, 'show'])->name('show');
        Route::delete('/{payment}', [PaymentsIndexController::class, 'destroy'])->name('destroy');
        });

    });
});