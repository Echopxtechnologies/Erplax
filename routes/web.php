<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

// User Dashboard - For regular users without admin panel access
Route::get('dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        
        // Check if user can access admin panel
        $canAccessAdmin = $user->is_admin || 
            (method_exists($user, 'roles') && $user->roles->count() > 0);
        
        if ($canAccessAdmin) {
            return redirect()->route('admin.dashboard');
        }
    }
    
    // Regular user dashboard
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

use App\Livewire\Admin\Customers\index as CustomersIndex;

Route::middleware(['auth'])   // plus your admin middleware
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('customers', CustomersIndex::class)->name('admin.customers.index');
    });
