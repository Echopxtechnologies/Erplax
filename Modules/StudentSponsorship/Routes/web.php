<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentSponsorship\Http\Controllers\SchoolStudentController;
use App\Http\Middleware\EnsureIsAdmin;

Route::prefix('admin/studentsponsorship')
    ->middleware([EnsureIsAdmin::class])
    ->name('admin.studentsponsorship.')
    ->group(function () {
        
        // School Students
        Route::prefix('school-students')->name('school-students.')->group(function () {
            Route::get('/', [SchoolStudentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/data', [SchoolStudentController::class, 'dataTable'])->name('data');
            Route::post('/bulk-delete', [SchoolStudentController::class, 'bulkDelete'])->name('bulk-delete');
            
            Route::get('/create', [SchoolStudentController::class, 'create'])->name('create');
            Route::post('/', [SchoolStudentController::class, 'store'])->name('store');
            Route::get('/{id}', [SchoolStudentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SchoolStudentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SchoolStudentController::class, 'update'])->name('update');
            Route::delete('/{id}', [SchoolStudentController::class, 'destroy'])->name('destroy');
            
            // AJAX endpoints
            Route::post('/add-school', [SchoolStudentController::class, 'addSchool'])->name('add-school');
            Route::post('/validate-age-grade', [SchoolStudentController::class, 'validateAgeGradeAjax'])->name('validate-age-grade');
            
            // Report Cards
            Route::post('/{id}/report-cards', [SchoolStudentController::class, 'uploadReportCard'])->name('upload-report-card');
            Route::delete('/{id}/report-cards/{mediaId}', [SchoolStudentController::class, 'deleteReportCard'])->name('delete-report-card');
        });
        
    });
