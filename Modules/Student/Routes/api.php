<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\Http\Controllers\StudentController;

Route::prefix('api/students')
    ->middleware(['api'])
    ->group(function () {
        // API routes can be added here if needed
    });
