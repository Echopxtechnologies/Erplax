<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        // Already logged in as admin? Go to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Just show login form - don't do complex checks
        return view('admin.auth.login');
    }

    protected function canAccessAdminPanel($admin): bool
    {
        if (!$admin) {
            return false;
        }

        if ($admin->is_admin ?? false) {
            return true;
        }

        if (method_exists($admin, 'roles') && $admin->roles->count() > 0) {
            return true;
        }

        return false;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('No account found with this email.'),
            ]);
        }

        if (!$this->canAccessAdminPanel($admin)) {
            throw ValidationException::withMessages([
                'email' => __('You do not have admin access.'),
            ]);
        }

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();
            
            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('Invalid credentials.'),
        ]);
    }

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

    protected function throttleKey(Request $request): string
    {
        return 'admin-login:' . Str::lower($request->string('email')) . '|' . $request->ip();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerate();  // Don't invalidate, just regenerate
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}