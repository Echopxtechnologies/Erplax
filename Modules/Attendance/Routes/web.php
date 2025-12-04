<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Livewire\AttendanceIndex;
use Modules\Attendance\Livewire\AttendanceForm;

Route::prefix('admin/attendance')
    ->middleware(['web', 'auth', 'admin'])
    ->name('admin.attendance.')
    ->group(function () {
        Route::get('/', AttendanceIndex::class)->name('index');
        Route::get('/create', AttendanceForm::class)->name('create');
        Route::get('/{id}/edit', AttendanceForm::class)->name('edit');
    });
