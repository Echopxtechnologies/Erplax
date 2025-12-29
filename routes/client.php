<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Auth\ClientLoginController;
use App\Http\Controllers\Client\Auth\ClientRegisterController;
use App\Http\Controllers\Client\Auth\ClientForgotPasswordController;
use App\Http\Controllers\Client\Auth\ClientResetPasswordController;
use App\Http\Controllers\Client\Auth\ClientEmailVerificationController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Middleware\EnsureIsClient;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

// Redirect /client to login
Route::get('/client', fn() => redirect()->route('client.login'));

Route::prefix('client')->name('client.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest Routes (Login, Register, Password Reset)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:web')->group(function () {  // ← Added :web
        
        // Login
        Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [ClientLoginController::class, 'login'])->name('login.submit');

        // Register
        Route::get('/register', [ClientRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [ClientRegisterController::class, 'register'])->name('register.submit');

        // Forgot Password
        Route::get('/forgot-password', [ClientForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [ClientForgotPasswordController::class, 'sendResetLink'])->name('password.email');

        // Reset Password
        Route::get('/reset-password/{token}', [ClientResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ClientResetPasswordController::class, 'reset'])->name('password.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Client Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware([EnsureIsClient::class])->group(function () {

        // Email Verification
        Route::get('/verify-email', [ClientEmailVerificationController::class, 'showVerificationNotice'])->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', [ClientEmailVerificationController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('/email/verification-notification', [ClientEmailVerificationController::class, 'sendVerificationEmail'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        // Dashboard
        Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');

        // Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ClientController::class, 'profile'])->name('show');
            Route::put('/', [ClientController::class, 'updateProfile'])->name('update');
            Route::put('/avatar', [ClientController::class, 'updateAvatar'])->name('avatar.update');
        });

          // =============================================
        // NOTIFICATION ROUTES
        // =============================================
        Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

        Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ClientController::class, 'profile'])->name('show');
        Route::put('/', [ClientController::class, 'updateProfile'])->name('update');
        Route::put('/avatar', [ClientController::class, 'updateAvatar'])->name('avatar.update');
        Route::put('/password', [ClientController::class, 'updatePassword'])->name('password.update');  // ADD THIS
    });
        // Logout
        Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout');
    });
});