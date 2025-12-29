<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentSponsorship\Http\Controllers\Client\StudentPortalController;

/*
|--------------------------------------------------------------------------
| Client Routes - StudentSponsorship Module
|--------------------------------------------------------------------------
|
| Student portal routes for viewing their own form/data.
| Prefix: /client/student-portal
| Middleware: web, client (handled by RouteServiceProvider)
|
*/

Route::prefix('student-portal')->name('student-portal.')->group(function () {
    Route::get('/my-form', [StudentPortalController::class, 'myProfile'])->name('my-profile');
    
    // Report Cards - Download and Upload only (no view, no delete for students)
    Route::get('/report-card/{id}/download', [StudentPortalController::class, 'downloadReportCard'])->name('report-card.download');
    Route::post('/report-card/upload', [StudentPortalController::class, 'uploadReportCard'])->name('report-card.upload');
});
