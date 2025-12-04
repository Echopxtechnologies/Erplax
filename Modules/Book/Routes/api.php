<?php

use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookController;

Route::prefix('api/books')
    ->middleware(['api'])
    ->group(function () {
        // API routes can be added here if needed
    });
