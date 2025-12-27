<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/test1')->name('admin.test1.')->middleware(['web', 'auth:admin'])->group(function () {
    Route::get('/', function () {
        return view('test1::index');
    })->name('index');
});
