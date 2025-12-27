<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceController;
use App\Http\Middleware\EnsureIsAdmin;

Route::prefix('admin/service')
    ->middleware([EnsureIsAdmin::class])
    ->name('admin.service.')
    ->group(function () {
        // List & DataTable
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/data', [ServiceController::class, 'dataTable'])->name('data');
        
        // Bulk Operations
        Route::post('/bulk-delete', [ServiceController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Products API for materials dropdown
        Route::get('/products', [ServiceController::class, 'getProducts'])->name('products');
        
        // Create (before {id} routes)
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        
        // Service Records - MUST be before /{id} routes to avoid conflicts
        Route::get('/{serviceId}/records/{recordId}', [ServiceController::class, 'getRecord'])
            ->where(['serviceId' => '[0-9]+', 'recordId' => '[0-9]+'])
            ->name('records.get');
        Route::post('/{serviceId}/records', [ServiceController::class, 'storeRecord'])
            ->where('serviceId', '[0-9]+')
            ->name('records.store');
        Route::put('/{serviceId}/records/{recordId}', [ServiceController::class, 'updateRecord'])
            ->where(['serviceId' => '[0-9]+', 'recordId' => '[0-9]+'])
            ->name('records.update');
        Route::delete('/{serviceId}/records/{recordId}', [ServiceController::class, 'deleteRecord'])
            ->where(['serviceId' => '[0-9]+', 'recordId' => '[0-9]+'])
            ->name('records.delete');
        
        // Service Visits - MUST be before /{id} routes
        Route::post('/{serviceId}/visits', [ServiceController::class, 'storeVisit'])
            ->where('serviceId', '[0-9]+')
            ->name('visits.store');
        Route::put('/{serviceId}/visits/{visitId}', [ServiceController::class, 'updateVisit'])
            ->where(['serviceId' => '[0-9]+', 'visitId' => '[0-9]+'])
            ->name('visits.update');
        Route::delete('/{serviceId}/visits/{visitId}', [ServiceController::class, 'deleteVisit'])
            ->where(['serviceId' => '[0-9]+', 'visitId' => '[0-9]+'])
            ->name('visits.delete');
        
        // Refresh dates & Send reminder - before /{id}
        Route::post('/{id}/refresh-dates', [ServiceController::class, 'refreshDates'])
            ->where('id', '[0-9]+')
            ->name('refresh-dates');
        Route::post('/{id}/send-reminder', [ServiceController::class, 'sendReminder'])
            ->where('id', '[0-9]+')
            ->name('send-reminder');
        
        // Invoice actions
        Route::post('/invoice/{invoiceId}/mark-paid', [ServiceController::class, 'markInvoicePaid'])
            ->where('invoiceId', '[0-9]+')
            ->name('invoice.mark-paid');
        
        // Email notifications
        Route::post('/{id}/send-completed-email/{recordId}', [ServiceController::class, 'sendCompletedEmailManual'])
            ->where(['id' => '[0-9]+', 'recordId' => '[0-9]+'])
            ->name('send-completed-email');
        Route::post('/{id}/send-invoice-email/{recordId}', [ServiceController::class, 'sendInvoiceEmailManual'])
            ->where(['id' => '[0-9]+', 'recordId' => '[0-9]+'])
            ->name('send-invoice-email');
        
        // Single resource routes (LAST - these are catch-all)
        Route::get('/{id}', [ServiceController::class, 'show'])
            ->where('id', '[0-9]+')
            ->name('show');
        Route::get('/{id}/edit', [ServiceController::class, 'edit'])
            ->where('id', '[0-9]+')
            ->name('edit');
        Route::put('/{id}', [ServiceController::class, 'update'])
            ->where('id', '[0-9]+')
            ->name('update');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('destroy');
    });