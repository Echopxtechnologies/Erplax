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
    // First check admin guard
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    // Then check web guard (client)
    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
        
        // If somehow a client user has admin flag, redirect to admin
        if ($user->is_admin || (method_exists($user, 'roles') && $user->roles->count() > 0)) {
            return redirect()->route('admin.dashboard');
        }
        
        // Regular client dashboard
        return view('dashboard');
    }
    
    // Not logged in at all - redirect to client login
    return redirect()->route('client.login');
})->name('dashboard');

// User Dashboard - For regular users without admin panel access
// Route::get('dashboard', function () {
//     if (Auth::check()) {
//         $user = Auth::user();
        
//         // Check if user can access admin panel
//         $canAccessAdmin = $user->is_admin || 
//             (method_exists($user, 'roles') && $user->roles->count() > 0);
        
//         if ($canAccessAdmin) {
//             return redirect()->route('admin.dashboard');
//         }
//     }
    
//     // Regular user dashboard
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/client.php';

// use App\Livewire\Admin\Customers\index as CustomersIndex;

// Route::middleware(['auth'])   // plus your admin middleware
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
//         Route::get('customers', CustomersIndex::class)->name('admin.customers.index');
//     });

// //notofication 
// Route::middleware('auth')->prefix('admin')->group(function () {
//     Route::delete('/notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy']);
//     Route::delete('/notifications/clear-all', [App\Http\Controllers\Admin\NotificationController::class, 'clearAll']);
// });
// // Debug Routes
// Route::middleware('web')->group(function () {
//     Route::get('/debug/csrf', [App\Http\Controllers\DebugController::class, 'csrfDebug']);
//     Route::get('/debug/test-post', [App\Http\Controllers\DebugController::class, 'testPost']);
//     Route::post('/debug/test-post', [App\Http\Controllers\DebugController::class, 'testPost']);
//     Route::get('/debug/clear-session', [App\Http\Controllers\DebugController::class, 'clearSession']);
//     Route::get('/debug/set-test-session', [App\Http\Controllers\DebugController::class, 'setTestSession']);

//     // Route::get('/debug/cookie-test', function(){
//     //     return view('debug.cookie-test');
//     // });
// });

// // In routes/web.php
// Route::get('/debug/test-real-login', function() {
//     return view('debug.real-login-test');
// });
// Route::get('/debug/real-login-test', function() {
//     return view('debug.real-login-test');
// });

// Route::post('/debug/test-real-login', function(\Illuminate\Http\Request $request) {
//     // Log everything about the request
//     \Log::info('Login Test Request:', [
//         'csrf_token_received' => $request->input('_token'),
//         'csrf_token_expected' => csrf_token(),
//         'tokens_match' => $request->input('_token') === csrf_token(),
//         'session_id' => session()->getId(),
//         'cookies_received' => $request->cookie(),
//         'headers' => $request->headers->all(),
//     ]);
    
//     if ($request->input('_token') !== csrf_token()) {
//         return response()->json([
//             'success' => false,
//             'error' => 'CSRF token mismatch',
//             'received_token' => $request->input('_token'),
//             'expected_token' => csrf_token(),
//             'session_id' => session()->getId(),
//         ], 419);
//     }
    
//     return response()->json([
//         'success' => true,
//         'message' => 'Login would succeed',
//         'session_id' => session()->getId(),
//         'email' => $request->input('email'),
//     ]);
// });
// Add at top of Book routes/web.php temporarily
Route::get('/admin/book-debug', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'admin_guard_check' => \Auth::guard('admin')->check(),
        'admin_user' => \Auth::guard('admin')->user(),
        'web_guard_check' => \Auth::guard('web')->check(),
        'session_data' => session()->all(),
    ]);
})->middleware('web');