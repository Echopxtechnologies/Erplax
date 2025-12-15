<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use App\Http\Middleware\EnsureIsAdmin;

Route::middleware(['web', 'auth:admin', EnsureIsAdmin::class])
    ->prefix('admin/inventory')
    ->name('inventory.')
    ->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/', [InventoryController::class, 'dashboard'])->name('dashboard');

    // ==================== PRODUCTS ====================
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/tags/search', [InventoryController::class, 'tagsSearch'])->name('tags.search');
        Route::get('/attributes', [InventoryController::class, 'getAttributes'])->name('attributes.index');
        
        Route::get('/', [InventoryController::class, 'productsIndex'])->name('index');
        Route::match(['get', 'post'], '/data', [InventoryController::class, 'productsData'])->name('data');
        Route::get('/create', [InventoryController::class, 'productsCreate'])->name('create');
        Route::post('/', [InventoryController::class, 'productsStore'])->name('store');
        Route::get('/{id}', [InventoryController::class, 'productsShow'])->name('show');
        Route::get('/{id}/edit', [InventoryController::class, 'productsEdit'])->name('edit');
        Route::put('/{id}', [InventoryController::class, 'productsUpdate'])->name('update');
        Route::post('/{id}/deactivate', [InventoryController::class, 'productsDeactivate'])->name('deactivate');
        Route::delete('/{id}', [InventoryController::class, 'productsDestroy'])->name('destroy');
        
        Route::get('/{id}/units', [InventoryController::class, 'productsGetUnits'])->name('units');
        Route::get('/{id}/variations', [InventoryController::class, 'getVariations'])->name('variations');
        Route::post('/{id}/generate-variations', [InventoryController::class, 'generateVariations'])->name('generate-variations');
    });

    // ==================== VARIATIONS ====================
    Route::prefix('variations')->name('variations.')->group(function () {
        Route::put('/{id}', [InventoryController::class, 'updateVariation'])->name('update');
        Route::delete('/{id}', [InventoryController::class, 'deleteVariation'])->name('destroy');
    });

    // ==================== WAREHOUSES ====================
    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [InventoryController::class, 'warehousesIndex'])->name('index');
        Route::get('/data', [InventoryController::class, 'warehousesData'])->name('data');
        Route::get('/create', [InventoryController::class, 'warehousesCreate'])->name('create');
        Route::post('/', [InventoryController::class, 'warehousesStore'])->name('store');
        Route::get('/{id}/edit', [InventoryController::class, 'warehousesEdit'])->name('edit');
        Route::put('/{id}', [InventoryController::class, 'warehousesUpdate'])->name('update');
        Route::post('/{id}/set-default', [InventoryController::class, 'warehousesSetDefault'])->name('set-default');
        Route::post('/{id}/deactivate', [InventoryController::class, 'warehousesDeactivate'])->name('deactivate');
        Route::delete('/{id}', [InventoryController::class, 'warehousesDestroy'])->name('destroy');
    });

    // ==================== RACKS ====================
    Route::prefix('racks')->name('racks.')->group(function () {
        Route::get('/', [InventoryController::class, 'racksIndex'])->name('index');
        Route::get('/data', [InventoryController::class, 'racksData'])->name('data');
        Route::get('/create', [InventoryController::class, 'racksCreate'])->name('create');
        Route::post('/', [InventoryController::class, 'racksStore'])->name('store');
        Route::get('/{id}/edit', [InventoryController::class, 'racksEdit'])->name('edit');
        Route::put('/{id}', [InventoryController::class, 'racksUpdate'])->name('update');
        Route::post('/{id}/deactivate', [InventoryController::class, 'racksDeactivate'])->name('deactivate');
        Route::delete('/{id}', [InventoryController::class, 'racksDestroy'])->name('destroy');
        Route::get('/by-warehouse/{warehouseId}', [InventoryController::class, 'racksByWarehouse'])->name('by-warehouse');
    });

    // ==================== LOTS ====================
    Route::prefix('lots')->name('lots.')->group(function () {
        Route::get('/', [InventoryController::class, 'lotsIndex'])->name('index');
        Route::match(['get', 'post'], '/data', [InventoryController::class, 'lotsData'])->name('data');
        
        Route::get('/check', [InventoryController::class, 'lotsCheck'])->name('check');
        Route::get('/generate-lot-no', [InventoryController::class, 'lotsGenerateLotNo'])->name('generate-lot-no');
        Route::get('/expiring-soon', [InventoryController::class, 'lotsExpiringSoon'])->name('expiring-soon');
        Route::post('/update-statuses', [InventoryController::class, 'lotsUpdateStatuses'])->name('update-statuses');
        Route::get('/by-product/{productId}', [InventoryController::class, 'lotsByProduct'])->name('by-product');
        Route::get('/with-stock/{productId}', [InventoryController::class, 'lotsWithStockByProduct'])->name('with-stock');
        Route::get('/product-info/{productId}', [InventoryController::class, 'lotsGetProductInfo'])->name('product-info');
        
        Route::get('/create', [InventoryController::class, 'lotsCreate'])->name('create');
        Route::post('/', [InventoryController::class, 'lotsStore'])->name('store');
        Route::get('/{id}', [InventoryController::class, 'lotsShow'])->name('show');
        Route::get('/{id}/edit', [InventoryController::class, 'lotsEdit'])->name('edit');
        Route::put('/{id}', [InventoryController::class, 'lotsUpdate'])->name('update');
        Route::post('/{id}/deactivate', [InventoryController::class, 'lotsDeactivate'])->name('deactivate');
        Route::post('/{id}/mark-expired', [InventoryController::class, 'lotsMarkExpired'])->name('mark-expired');
        Route::post('/{id}/mark-recalled', [InventoryController::class, 'lotsMarkRecalled'])->name('mark-recalled');
        Route::delete('/{id}', [InventoryController::class, 'lotsDestroy'])->name('destroy');
    });

    // ==================== STOCK ====================
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/movements', [InventoryController::class, 'stockMovements'])->name('movements');
        Route::match(['get', 'post'], '/movements/data', [InventoryController::class, 'stockMovementsData'])->name('movements.data');
        
        Route::get('/receive', [InventoryController::class, 'stockReceive'])->name('receive');
        Route::post('/receive', [InventoryController::class, 'stockReceiveStore'])->name('receive.store');
        
        Route::get('/deliver', [InventoryController::class, 'stockDeliver'])->name('deliver');
        Route::post('/deliver', [InventoryController::class, 'stockDeliverStore'])->name('deliver.store');
        
        Route::get('/returns', [InventoryController::class, 'stockReturns'])->name('returns');
        Route::post('/returns', [InventoryController::class, 'stockReturnsStore'])->name('returns.store');
        
        Route::get('/adjustments', [InventoryController::class, 'stockAdjustments'])->name('adjustments');
        Route::post('/adjustments', [InventoryController::class, 'stockAdjustmentsStore'])->name('adjustments.store');
        
        Route::get('/transfer', [InventoryController::class, 'stockTransfer'])->name('transfer');
        Route::post('/transfer', [InventoryController::class, 'stockTransferStore'])->name('transfer.store');
        
        Route::get('/check', [InventoryController::class, 'stockCheck'])->name('check');
        Route::get('/product-units', [InventoryController::class, 'stockProductUnits'])->name('product-units');
        Route::get('/product-lots', [InventoryController::class, 'stockProductLots'])->name('product-lots');
    });

    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock-summary', [InventoryController::class, 'reportStockSummary'])->name('stock-summary');
        Route::get('/lot-summary', [InventoryController::class, 'reportLotSummary'])->name('lot-summary');
        Route::get('/movement-history', [InventoryController::class, 'reportMovementHistory'])->name('movement-history');
        Route::get('/movement-history/data', [InventoryController::class, 'reportMovementHistoryData'])->name('movement-history.data');
    });

    // ==================== SETTINGS ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [InventoryController::class, 'settingsIndex'])->name('index');
        
        // Categories
        Route::get('/categories/data', [InventoryController::class, 'categoriesData'])->name('categories.data');
        Route::post('/categories', [InventoryController::class, 'categoriesStore'])->name('categories.store');
        Route::put('/categories/{id}', [InventoryController::class, 'categoriesUpdate'])->name('categories.update');
        Route::post('/categories/{id}/deactivate', [InventoryController::class, 'categoriesDeactivate'])->name('categories.deactivate');
        Route::delete('/categories/{id}', [InventoryController::class, 'categoriesDestroy'])->name('categories.destroy');
        
        // Brands
        Route::get('/brands/data', [InventoryController::class, 'brandsData'])->name('brands.data');
        Route::post('/brands', [InventoryController::class, 'brandsStore'])->name('brands.store');
        Route::put('/brands/{id}', [InventoryController::class, 'brandsUpdate'])->name('brands.update');
        Route::post('/brands/{id}/deactivate', [InventoryController::class, 'brandsDeactivate'])->name('brands.deactivate');
        Route::delete('/brands/{id}', [InventoryController::class, 'brandsDestroy'])->name('brands.destroy');
        
        // Units
        Route::get('/units/data', [InventoryController::class, 'unitsData'])->name('units.data');
        Route::post('/units', [InventoryController::class, 'unitsStore'])->name('units.store');
        Route::put('/units/{id}', [InventoryController::class, 'unitsUpdate'])->name('units.update');
        Route::delete('/units/{id}', [InventoryController::class, 'unitsDestroy'])->name('units.destroy');
    });
});