<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

// User Dashboard - Redirects admin to admin dashboard
Route::get('dashboard', function () {
    // If logged in as admin, redirect to admin dashboard
    if (Auth::check() && Auth::user()->is_admin == 1) {
        return redirect()->route('admin.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';