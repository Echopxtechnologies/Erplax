<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/test2')->name('admin.test2.')->middleware(['web', 'auth:admin'])->group(function () {
    Route::get('/', function () {
        return view('test2::index');
    })->name('index');
});
