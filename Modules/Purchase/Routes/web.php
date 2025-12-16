<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\VendorController;
use Modules\Purchase\Http\Controllers\PurchaseRequestController;
use Modules\Purchase\Http\Controllers\PurchaseOrderController;
use Modules\Purchase\Http\Controllers\SettingsController;
use Modules\Purchase\Http\Controllers\GoodsReceiptNoteController;

Route::prefix('admin/purchase')->middleware(['web', 'auth:admin'])->group(function () {
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('admin.purchase.settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('admin.purchase.settings.update');
    
    // Vendors
    Route::get('vendors/data', [VendorController::class, 'dataTable'])->name('admin.purchase.vendors.data');
    Route::post('vendors/bulk-delete', [VendorController::class, 'bulkDelete'])->name('admin.purchase.vendors.bulk-delete');
    Route::resource('vendors', VendorController::class)->names([
        'index' => 'admin.purchase.vendors.index',
        'create' => 'admin.purchase.vendors.create',
        'store' => 'admin.purchase.vendors.store',
        'show' => 'admin.purchase.vendors.show',
        'edit' => 'admin.purchase.vendors.edit',
        'update' => 'admin.purchase.vendors.update',
        'destroy' => 'admin.purchase.vendors.destroy',
    ]);

    // Purchase Requests
    Route::get('requests/data', [PurchaseRequestController::class, 'dataTable'])->name('admin.purchase.requests.data');
    Route::post('requests/bulk-delete', [PurchaseRequestController::class, 'bulkDelete'])->name('admin.purchase.requests.bulk-delete');
    Route::post('requests/{id}/submit', [PurchaseRequestController::class, 'submit'])->name('admin.purchase.requests.submit');
    Route::post('requests/{id}/approve', [PurchaseRequestController::class, 'approve'])->name('admin.purchase.requests.approve');
    Route::post('requests/{id}/reject', [PurchaseRequestController::class, 'reject'])->name('admin.purchase.requests.reject');
    Route::post('requests/{id}/cancel', [PurchaseRequestController::class, 'cancel'])->name('admin.purchase.requests.cancel');
    Route::resource('requests', PurchaseRequestController::class)->names([
        'index' => 'admin.purchase.requests.index',
        'create' => 'admin.purchase.requests.create',
        'store' => 'admin.purchase.requests.store',
        'show' => 'admin.purchase.requests.show',
        'edit' => 'admin.purchase.requests.edit',
        'update' => 'admin.purchase.requests.update',
        'destroy' => 'admin.purchase.requests.destroy',
    ]);

    // Purchase Orders
    Route::get('orders/data', [PurchaseOrderController::class, 'dataTable'])->name('admin.purchase.orders.data');
    Route::post('orders/bulk-delete', [PurchaseOrderController::class, 'bulkDelete'])->name('admin.purchase.orders.bulk-delete');
    Route::post('orders/{id}/send', [PurchaseOrderController::class, 'send'])->name('admin.purchase.orders.send');
    Route::post('orders/{id}/confirm', [PurchaseOrderController::class, 'confirm'])->name('admin.purchase.orders.confirm');
    Route::post('orders/{id}/cancel', [PurchaseOrderController::class, 'cancel'])->name('admin.purchase.orders.cancel');
    Route::get('orders/{id}/pdf', [PurchaseOrderController::class, 'pdf'])->name('admin.purchase.orders.pdf');
    Route::post('orders/{id}/duplicate', [PurchaseOrderController::class, 'duplicate'])->name('admin.purchase.orders.duplicate');
    Route::resource('orders', PurchaseOrderController::class)->names([
        'index' => 'admin.purchase.orders.index',
        'create' => 'admin.purchase.orders.create',
        'store' => 'admin.purchase.orders.store',
        'show' => 'admin.purchase.orders.show',
        'edit' => 'admin.purchase.orders.edit',
        'update' => 'admin.purchase.orders.update',
        'destroy' => 'admin.purchase.orders.destroy',
    ]);

    // Goods Receipt Notes (GRN)
    Route::get('grn/data', [GoodsReceiptNoteController::class, 'dataTable'])->name('admin.purchase.grn.data');
    Route::post('grn/bulk-delete', [GoodsReceiptNoteController::class, 'bulkDelete'])->name('admin.purchase.grn.bulk-delete');
    Route::get('grn/po-items/{poId}', [GoodsReceiptNoteController::class, 'getPOItems'])->name('admin.purchase.grn.po-items');
    Route::get('grn/racks/{warehouseId}', [GoodsReceiptNoteController::class, 'getRacks'])->name('admin.purchase.grn.racks');
    Route::post('grn/{id}/submit', [GoodsReceiptNoteController::class, 'submit'])->name('admin.purchase.grn.submit');
    Route::post('grn/{id}/approve', [GoodsReceiptNoteController::class, 'approve'])->name('admin.purchase.grn.approve');
    Route::post('grn/{id}/reject', [GoodsReceiptNoteController::class, 'reject'])->name('admin.purchase.grn.reject');
    Route::post('grn/{id}/cancel', [GoodsReceiptNoteController::class, 'cancel'])->name('admin.purchase.grn.cancel');
    Route::resource('grn', GoodsReceiptNoteController::class)->names([
        'index' => 'admin.purchase.grn.index',
        'create' => 'admin.purchase.grn.create',
        'store' => 'admin.purchase.grn.store',
        'show' => 'admin.purchase.grn.show',
        'edit' => 'admin.purchase.grn.edit',
        'update' => 'admin.purchase.grn.update',
        'destroy' => 'admin.purchase.grn.destroy',
    ]);
});
