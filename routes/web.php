<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

Route::get('dashboard', function () {
    // Check admin guard first
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    // Check client guard (web)
    if (Auth::guard('web')->check()) {
        return redirect()->route('client.dashboard');
    }
    
    // Not logged in - redirect to client login
    return redirect()->route('client.login');
})->name('dashboard');

require __DIR__.'/admin.php';
require __DIR__.'/client.php';