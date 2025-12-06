<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Admin; // Change from User to Admin

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        // Check if already logged in as admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            
            if ($this->canAccessAdminPanel($admin)) {
                return redirect()->route('admin.dashboard');
            } else {
                // Invalid admin - logout
                Auth::guard('admin')->logout();
                session()->invalidate();
            }
        }
        
        // If logged in as regular user (web guard), redirect them away
        if (Auth::guard('web')->check()) {
            return redirect()->route('client.dashboard')
                ->with('info', 'You are logged in as a client. Please logout first to access admin panel.');
        }

        return view('admin.auth.login');
    }

    /**
     * Check if admin can access admin panel
     */
    protected function canAccessAdminPanel($admin): bool
    {
        if (!$admin) {
            return false;
        }

        // Check is_admin flag
        if ($admin->is_admin) {
            return true;
        }

        // Check if admin has any role
        if (method_exists($admin, 'roles')) {
            return $admin->roles->count() > 0;
        }

        return false;
    }

    /**
     * Handle admin login request - WITH SEPARATE SESSION SUPPORT
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check rate limiting
        $this->ensureIsNotRateLimited($request);

        // Find the admin using Admin model
        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('No account found with this email.'),
            ]);
        }

        // Check if admin has admin access
        if (!$this->canAccessAdminPanel($admin)) {
            throw ValidationException::withMessages([
                'email' => __('You do not have admin access.'),
            ]);
        }

        // IMPORTANT: Use admin guard for authentication
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            
            RateLimiter::clear($this->throttleKey($request));
            
            // Regenerate session for security - Use separate admin session
            $request->session()->regenerate();
            
            // Store admin-specific data
            $request->session()->put('user_type', 'admin');
            $request->session()->put('admin_id', $admin->id);
            
            // Clear any web guard session if exists (prevent conflict)
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('Invalid credentials.'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip());
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Only logout admin guard
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}