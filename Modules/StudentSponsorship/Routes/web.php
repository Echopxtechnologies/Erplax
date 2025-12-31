<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentSponsorship\Http\Controllers\SchoolStudentController;
use Modules\StudentSponsorship\Http\Controllers\UniversityStudentController;
use Modules\StudentSponsorship\Http\Controllers\SponsorController;
use Modules\StudentSponsorship\Http\Controllers\SponsorTransactionController;
use Modules\StudentSponsorship\Http\Controllers\SponsorPaymentController;
use Modules\StudentSponsorship\Http\Controllers\DashboardController;
use App\Http\Middleware\EnsureIsAdmin;

Route::prefix('admin/studentsponsorship')
    ->middleware([EnsureIsAdmin::class])
    ->name('admin.studentsponsorship.')
    ->group(function () {
        
        // =====================================================================
        // DASHBOARD
        // =====================================================================
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');
        
        // =====================================================================
        // SPONSORS
        // =====================================================================
        Route::prefix('sponsors')->name('sponsors.')->group(function () {
            Route::get('/', [SponsorController::class, 'index'])->name('index');
            
            // DataTable routes
            Route::match(['get', 'post'], '/data', [SponsorController::class, 'handleData'])->name('data');
            
            // Bulk actions
            Route::post('/bulk-action', [SponsorController::class, 'handleBulkAction'])->name('bulk-action');
            
            // STATIC routes MUST come BEFORE {id} routes
            Route::get('/create', [SponsorController::class, 'create'])->name('create');
            Route::post('/add-bank', [SponsorController::class, 'addBank'])->name('add-bank');
            
            // Store (POST to root)
            Route::post('/', [SponsorController::class, 'store'])->name('store');
            
            // Portal Access routes (before {id})
            Route::post('/{id}/enable-portal', [SponsorController::class, 'enablePortalAccess'])->name('enable-portal')->where('id', '[0-9]+');
            Route::post('/{id}/disable-portal', [SponsorController::class, 'disablePortalAccess'])->name('disable-portal')->where('id', '[0-9]+');
            Route::post('/{id}/reset-password', [SponsorController::class, 'resetPortalPassword'])->name('reset-password')->where('id', '[0-9]+');
            
            // Dynamic {id} routes
            Route::get('/{id}', [SponsorController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::get('/{id}/edit', [SponsorController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
            Route::put('/{id}', [SponsorController::class, 'update'])->name('update')->where('id', '[0-9]+');
            Route::delete('/{id}', [SponsorController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        });

        // =====================================================================
        // SCHOOL STUDENTS
        // =====================================================================
        Route::prefix('school-students')->name('school-students.')->group(function () {
            Route::get('/', [SchoolStudentController::class, 'index'])->name('index');
            Route::get('/completed', [SchoolStudentController::class, 'completed'])->name('completed');
            
            // DataTable v2.0 routes - separate for inprogress and completed
            Route::match(['get', 'post'], '/data', [SchoolStudentController::class, 'handleData'])->name('data');
            Route::match(['get', 'post'], '/completed/data', [SchoolStudentController::class, 'handleCompletedData'])->name('completed.data');
            Route::post('/bulk-action', [SchoolStudentController::class, 'handleBulkAction'])->name('bulk-action');
            
            // Legacy bulk delete (backward compatibility)
            Route::post('/bulk-delete', [SchoolStudentController::class, 'bulkDelete'])->name('bulk-delete');
            
            // Rollback ALL completed students at once
            Route::post('/rollback-all', [SchoolStudentController::class, 'rollbackAll'])->name('rollback-all');
            
            // STATIC routes MUST come BEFORE {hash} routes
            Route::get('/create', [SchoolStudentController::class, 'create'])->name('create');
            Route::post('/add-school', [SchoolStudentController::class, 'addSchool'])->name('add-school');
            Route::post('/add-bank', [SchoolStudentController::class, 'addBank'])->name('add-bank');
            Route::post('/validate-age-grade', [SchoolStudentController::class, 'validateAgeGradeAjax'])->name('validate-age-grade');
            
            // Store (POST to root)
            Route::post('/', [SchoolStudentController::class, 'store'])->name('store');
            
            // Dynamic {hash} routes - accepts alphanumeric hashed IDs
            Route::get('/{hash}', [SchoolStudentController::class, 'show'])->name('show')->where('hash', '[a-zA-Z0-9]+');
            Route::get('/{hash}/edit', [SchoolStudentController::class, 'edit'])->name('edit')->where('hash', '[a-zA-Z0-9]+');
            Route::put('/{hash}', [SchoolStudentController::class, 'update'])->name('update')->where('hash', '[a-zA-Z0-9]+');
            Route::delete('/{hash}', [SchoolStudentController::class, 'destroy'])->name('destroy')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/remove-photo', [SchoolStudentController::class, 'removeProfilePhoto'])->name('remove-photo')->where('hash', '[a-zA-Z0-9]+');
            
            // Report Cards
            Route::post('/{hash}/report-cards', [SchoolStudentController::class, 'uploadReportCard'])->name('upload-report-card')->where('hash', '[a-zA-Z0-9]+');
            Route::get('/{hash}/report-cards/{reportCardId}/view', [SchoolStudentController::class, 'viewReportCard'])->name('view-report-card')->where(['hash' => '[a-zA-Z0-9]+', 'reportCardId' => '[0-9]+']);
            Route::get('/{hash}/report-cards/{reportCardId}/download', [SchoolStudentController::class, 'downloadReportCard'])->name('download-report-card')->where(['hash' => '[a-zA-Z0-9]+', 'reportCardId' => '[0-9]+']);
            Route::delete('/{hash}/report-cards/{reportCardId}', [SchoolStudentController::class, 'deleteReportCard'])->name('delete-report-card')->where(['hash' => '[a-zA-Z0-9]+', 'reportCardId' => '[0-9]+']);
            Route::post('/{hash}/report-cards/{reportCardId}/approve', [SchoolStudentController::class, 'approveReportCard'])->name('approve-report-card')->where(['hash' => '[a-zA-Z0-9]+', 'reportCardId' => '[0-9]+']);
            Route::post('/{hash}/report-cards/{reportCardId}/reject', [SchoolStudentController::class, 'rejectReportCard'])->name('reject-report-card')->where(['hash' => '[a-zA-Z0-9]+', 'reportCardId' => '[0-9]+']);
            
            // Portal Access
            Route::post('/check-email', [SchoolStudentController::class, 'checkEmailAvailability'])->name('check-email');
            Route::get('/{hash}/portal-status', [SchoolStudentController::class, 'getPortalStatus'])->name('portal-status')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/create-portal', [SchoolStudentController::class, 'createPortalAccount'])->name('create-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/deactivate-portal', [SchoolStudentController::class, 'deactivatePortalAccount'])->name('deactivate-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/activate-portal', [SchoolStudentController::class, 'activatePortalAccount'])->name('activate-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/reset-portal-password', [SchoolStudentController::class, 'resetPortalPassword'])->name('reset-portal-password')->where('hash', '[a-zA-Z0-9]+');
            
            // Rollback (promote to next year)
            Route::post('/{hash}/rollback', [SchoolStudentController::class, 'studentRollback'])->name('rollback')->where('hash', '[a-zA-Z0-9]+');
        });

        // =====================================================================
        // UNIVERSITY STUDENTS
        // =====================================================================
        Route::prefix('university-students')->name('university-students.')->group(function () {
            Route::get('/', [UniversityStudentController::class, 'index'])->name('index');
            Route::get('/completed', [UniversityStudentController::class, 'completed'])->name('completed');
            
            // DataTable - separate for inprogress and completed
            Route::match(['get', 'post'], '/data', [UniversityStudentController::class, 'handleData'])->name('data');
            Route::match(['get', 'post'], '/completed/data', [UniversityStudentController::class, 'handleCompletedData'])->name('completed.data');
            
            // Bulk actions
            Route::post('/bulk-action', [UniversityStudentController::class, 'handleBulkAction'])->name('bulk-action');
            Route::post('/bulk-delete', [UniversityStudentController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-status', [UniversityStudentController::class, 'bulkStatus'])->name('bulk-status');
            
            // Rollback ALL completed students at once
            Route::post('/rollback-all', [UniversityStudentController::class, 'rollbackAll'])->name('rollback-all');
            
            // Export/Import - STATIC routes before {hash}
            Route::get('/export', [UniversityStudentController::class, 'export'])->name('export');
            Route::post('/import', [UniversityStudentController::class, 'import'])->name('import');
            Route::get('/template', [UniversityStudentController::class, 'downloadTemplate'])->name('template');
            
            // Report Card upload (static)
            Route::post('/upload-report-card', [UniversityStudentController::class, 'uploadReportCard'])->name('upload-report-card');
            Route::get('/report-card/{id}/view', [UniversityStudentController::class, 'viewReportCard'])->name('report-card.view')->where('id', '[0-9]+');
            Route::get('/report-card/{id}/download', [UniversityStudentController::class, 'downloadReportCard'])->name('report-card.download')->where('id', '[0-9]+');
            Route::delete('/report-card/{id}', [UniversityStudentController::class, 'deleteReportCard'])->name('report-card.delete')->where('id', '[0-9]+');
            Route::post('/report-card/{id}/approve', [UniversityStudentController::class, 'approveReportCard'])->name('report-card.approve')->where('id', '[0-9]+');
            Route::post('/report-card/{id}/reject', [UniversityStudentController::class, 'rejectReportCard'])->name('report-card.reject')->where('id', '[0-9]+');
            
            // Add new entities (AJAX)
            Route::post('/add-university', [UniversityStudentController::class, 'addUniversity'])->name('add-university');
            Route::post('/add-program', [UniversityStudentController::class, 'addProgram'])->name('add-program');
            Route::post('/add-bank', [UniversityStudentController::class, 'addBank'])->name('add-bank');
            
            // Create
            Route::get('/create', [UniversityStudentController::class, 'create'])->name('create');
            Route::post('/', [UniversityStudentController::class, 'store'])->name('store');
            
            // Dynamic {hash} routes
            Route::get('/{hash}', [UniversityStudentController::class, 'show'])->name('show')->where('hash', '[a-zA-Z0-9]+');
            Route::get('/{hash}/edit', [UniversityStudentController::class, 'edit'])->name('edit')->where('hash', '[a-zA-Z0-9]+');
            Route::put('/{hash}', [UniversityStudentController::class, 'update'])->name('update')->where('hash', '[a-zA-Z0-9]+');
            Route::delete('/{hash}', [UniversityStudentController::class, 'destroy'])->name('destroy')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/remove-photo', [UniversityStudentController::class, 'removePhoto'])->name('remove-photo')->where('hash', '[a-zA-Z0-9]+');
            Route::get('/{hash}/report-cards', [UniversityStudentController::class, 'getReportCards'])->name('report-cards')->where('hash', '[a-zA-Z0-9]+');
            
            // Portal Access
            Route::post('/check-email', [UniversityStudentController::class, 'checkEmailAvailability'])->name('check-email');
            Route::get('/{hash}/portal-status', [UniversityStudentController::class, 'getPortalStatus'])->name('portal-status')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/create-portal', [UniversityStudentController::class, 'createPortalAccount'])->name('create-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/deactivate-portal', [UniversityStudentController::class, 'deactivatePortalAccount'])->name('deactivate-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/activate-portal', [UniversityStudentController::class, 'activatePortalAccount'])->name('activate-portal')->where('hash', '[a-zA-Z0-9]+');
            Route::post('/{hash}/reset-portal-password', [UniversityStudentController::class, 'resetPortalPassword'])->name('reset-portal-password')->where('hash', '[a-zA-Z0-9]+');
            
            // Rollback (promote to next year)
            Route::post('/{hash}/rollback', [UniversityStudentController::class, 'studentRollback'])->name('rollback')->where('hash', '[a-zA-Z0-9]+');
        });
        
        // =====================================================================
        // MASTER DATA
        // =====================================================================
        Route::prefix('master-data')->name('master-data.')->group(function () {
            Route::get('/', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'index'])->name('index');
            
            // Schools
            Route::post('/schools', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'storeSchool'])->name('schools.store');
            Route::put('/schools/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'updateSchool'])->name('schools.update');
            Route::delete('/schools/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'deleteSchool'])->name('schools.delete');
            
            // Universities
            Route::post('/universities', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'storeUniversity'])->name('universities.store');
            Route::put('/universities/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'updateUniversity'])->name('universities.update');
            Route::delete('/universities/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'deleteUniversity'])->name('universities.delete');
            
            // Programs
            Route::post('/programs', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'storeProgram'])->name('programs.store');
            Route::put('/programs/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'updateProgram'])->name('programs.update');
            Route::delete('/programs/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'deleteProgram'])->name('programs.delete');
            
            // Banks
            Route::post('/banks', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'storeBank'])->name('banks.store');
            Route::put('/banks/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'updateBank'])->name('banks.update');
            Route::delete('/banks/{id}', [\Modules\StudentSponsorship\Http\Controllers\MasterDataController::class, 'deleteBank'])->name('banks.delete');
        });
        
        // =====================================================================
        // TRANSACTIONS
        // =====================================================================
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [SponsorTransactionController::class, 'index'])->name('index');
            
            // DataTable
            Route::match(['get', 'post'], '/data', [SponsorTransactionController::class, 'handleData'])->name('data');
            
            // Bulk actions
            Route::post('/bulk-action', [SponsorTransactionController::class, 'handleBulkAction'])->name('bulk-action');
            
            // Create
            Route::get('/create', [SponsorTransactionController::class, 'create'])->name('create');
            Route::post('/', [SponsorTransactionController::class, 'store'])->name('store');
            
            // Dynamic {id} routes
            Route::get('/{id}', [SponsorTransactionController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::get('/{id}/edit', [SponsorTransactionController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
            Route::put('/{id}', [SponsorTransactionController::class, 'update'])->name('update')->where('id', '[0-9]+');
            Route::delete('/{id}', [SponsorTransactionController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
            
            // Status actions
            Route::post('/{id}/mark-cancelled', [SponsorTransactionController::class, 'markCancelled'])->name('mark-cancelled')->where('id', '[0-9]+');
            
            // Email
            Route::post('/{id}/send-email', [SponsorTransactionController::class, 'sendEmail'])->name('send-email')->where('id', '[0-9]+');
        });

        // =====================================================================
        // PAYMENTS (for transactions)
        // =====================================================================
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [SponsorPaymentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/data', [SponsorPaymentController::class, 'handleData'])->name('data');
            Route::post('/', [SponsorPaymentController::class, 'store'])->name('store');
            Route::delete('/{id}', [SponsorPaymentController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
            
            // Receipt preview, download, and send email
            Route::get('/{id}/receipt', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'previewPayment'])->name('receipt')->where('id', '[0-9]+');
            Route::get('/{id}/receipt/download', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'downloadPayment'])->name('receipt.download')->where('id', '[0-9]+');
            Route::post('/{id}/send-receipt', [SponsorPaymentController::class, 'sendReceipt'])->name('send-receipt')->where('id', '[0-9]+');
        });
        
        // =====================================================================
        // RECEIPT TEMPLATES
        // =====================================================================
        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('/', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'index'])->name('index');
            Route::post('/', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'store'])->name('store');
            Route::get('/{currency}/edit', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'edit'])->name('edit');
            Route::put('/{currency}', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'update'])->name('update');
            Route::delete('/{currency}', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'destroy'])->name('destroy');
            Route::get('/{currency}/preview', [\Modules\StudentSponsorship\Http\Controllers\PaymentReceiptController::class, 'preview'])->name('preview');
        });
        
    });
