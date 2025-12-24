<?php

namespace Modules\Pos\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PosServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Pos';

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), 'pos');
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        // Mobile scanner route - no admin auth required, just session code
        Route::middleware(['web'])
            ->prefix('pos')
            ->group(function () {
                $c = \Modules\Pos\Http\Controllers\Admin\PosController::class;
                Route::get('/scanner/{code}', [$c, 'mobileScanner'])->name('pos.scanner');
                Route::post('/scanner/send', [$c, 'remoteScan'])->name('pos.scanner.send');
            });

        Route::middleware(['web', \App\Http\Middleware\EnsureIsAdmin::class])
            ->prefix('admin/pos')
            ->name('admin.pos.')
            ->group(function () {
                $c = \Modules\Pos\Http\Controllers\Admin\PosController::class;
                
                Route::get('/', [$c, 'billing'])->name('billing');
                Route::get('/search', [$c, 'searchProducts'])->name('search');
                Route::post('/scan', [$c, 'scanBarcode'])->name('scan');
                Route::post('/complete', [$c, 'completeSale'])->name('complete');
                Route::post('/hold', [$c, 'holdBill'])->name('hold');
                Route::get('/held', [$c, 'getHeldBills'])->name('held');
                Route::get('/held/{id}', [$c, 'recallBill'])->name('held.recall');
                Route::delete('/held/{id}', [$c, 'deleteHeldBill'])->name('held.delete');
                Route::get('/receipt/{id}', [$c, 'receipt'])->name('receipt');
                Route::get('/invoice/{id}', [$c, 'invoicePdf'])->name('invoice');
                
                Route::get('/sales', [$c, 'sales'])->name('sales');
                Route::get('/sales/data', [$c, 'salesData'])->name('sales.data');
                Route::get('/sales/{id}', [$c, 'showSale'])->name('sales.show');
                Route::post('/sales/{id}/void', [$c, 'voidSale'])->name('sales.void');
                
                Route::get('/sessions', [$c, 'sessions'])->name('sessions');
                Route::get('/sessions/data', [$c, 'sessionsData'])->name('sessions.data');
                Route::post('/sessions/open', [$c, 'openSession'])->name('sessions.open');
                Route::post('/sessions/close', [$c, 'closeSession'])->name('sessions.close');
                
                Route::get('/settings', [$c, 'settings'])->name('settings');
                Route::post('/settings', [$c, 'saveSettings'])->name('settings.save');
                Route::post('/settings/assign', [$c, 'assignWarehouse'])->name('settings.assign');
                
                // Customer management
                Route::get('/customers/search', [$c, 'searchCustomers'])->name('customers.search');
                Route::post('/customers/create', [$c, 'createCustomer'])->name('customers.create');
                
                // Products by category
                Route::get('/products/category', [$c, 'getProductsByCategory'])->name('products.category');
                
                // Remote scanner polling
                Route::get('/poll-scans', [$c, 'pollScans'])->name('poll');
            });
    }

    public function register(): void {}
}
