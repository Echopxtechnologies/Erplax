<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentSponsor\Http\Controllers\DashboardController;
use Modules\StudentSponsor\Http\Controllers\SchoolStudentController;
use Modules\StudentSponsor\Http\Controllers\UniversityStudentController;
use Modules\StudentSponsor\Http\Controllers\SponsorController;
use Modules\StudentSponsor\Http\Controllers\TransactionController;
use Modules\StudentSponsor\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Student Sponsor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin/studentsponsor')->name('admin.studentsponsor.')->middleware(['web', 'auth:admin'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    

Route::prefix('school')->name('school.')->group(function () {
    // Main CRUD
    Route::get('/', [SchoolStudentController::class, 'index'])->name('index');
    Route::get('/data', [SchoolStudentController::class, 'data'])->name('data');
    Route::get('/create', [SchoolStudentController::class, 'create'])->name('create');
    Route::post('/', [SchoolStudentController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [SchoolStudentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SchoolStudentController::class, 'update'])->name('update');
    Route::delete('/{id}', [SchoolStudentController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/photo', [SchoolStudentController::class, 'displayPhoto'])->name('photo');
    
    // AJAX Add endpoints
   // Add to your routes file inside the school group
Route::post('/add-school-name', [SchoolStudentController::class, 'addSchoolName'])->name('add-school-name');
Route::post('/add-bank', [SchoolStudentController::class, 'addBank'])->name('add-bank');
Route::post('/add-country', [SchoolStudentController::class, 'addCountry'])->name('add-country');
    // Report cards
    Route::get('/{id}/report-cards', [SchoolStudentController::class, 'getReportCards'])->name('report-cards');
    Route::post('/upload-report-card', [SchoolStudentController::class, 'uploadReportCard'])->name('upload-report-card');
    Route::get('/download-report-card/{id}', [SchoolStudentController::class, 'downloadReportCard'])->name('download-report-card');
    Route::delete('/report-card/{id}', [SchoolStudentController::class, 'deleteReportCard'])->name('delete-report-card');
});


    // University Students
    Route::prefix('university')->name('university.')->group(function () {
        Route::get('/', [UniversityStudentController::class, 'index'])->name('index');
        Route::get('/data', [UniversityStudentController::class, 'dataTable'])->name('data');
        Route::get('/create', [UniversityStudentController::class, 'create'])->name('create');
        Route::post('/', [UniversityStudentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UniversityStudentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UniversityStudentController::class, 'update'])->name('update');
        Route::delete('/{id}', [UniversityStudentController::class, 'destroy'])->name('destroy');
    });
    
    // Sponsors
    Route::prefix('sponsor')->name('sponsor.')->group(function () {
        Route::get('/', [SponsorController::class, 'index'])->name('index');
        Route::get('/data', [SponsorController::class, 'dataTable'])->name('data');
        Route::get('/create', [SponsorController::class, 'create'])->name('create');
        Route::post('/', [SponsorController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SponsorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SponsorController::class, 'update'])->name('update');
        Route::delete('/{id}', [SponsorController::class, 'destroy'])->name('destroy');
    });
    
    // Transactions
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/data', [TransactionController::class, 'dataTable'])->name('data');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
        
        // Email
        Route::get('/{id}/send-email', [TransactionController::class, 'sendDueEmail'])->name('sendEmail');
        
        // Payments (nested under transaction)
        Route::post('/{transactionId}/payment', [TransactionController::class, 'addPayment'])->name('payment.store');
        Route::put('/{transactionId}/payment/{paymentId}', [TransactionController::class, 'updatePayment'])->name('payment.update');
        Route::delete('/{transactionId}/payment/{paymentId}', [TransactionController::class, 'deletePayment'])->name('payment.destroy');
    });
    
    // Payments List (standalone view)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/data', [PaymentController::class, 'dataTable'])->name('data');
    });
    
    // AJAX Search endpoints (for Select2)
    Route::post('/search-sponsors', [TransactionController::class, 'searchSponsors'])->name('search.sponsors');
    Route::post('/search-school-students', [TransactionController::class, 'searchSchoolStudents'])->name('search.school');
    Route::post('/search-university-students', [TransactionController::class, 'searchUniversityStudents'])->name('search.university');
});
