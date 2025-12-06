<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Lockout;
use App\Models\User;

class ClientLoginController extends Controller
{
    /**
     * Show the client login form
     */
    public function showLoginForm()
    {
        // If already logged in, check user type
        if (Auth::check()) {
            $user = Auth::user();
            
            // If client user (not admin), redirect to client dashboard
            if (!$this->isAdminUser($user)) {
                return redirect()->route('client.dashboard');
            }
            
            // If admin user is logged in, logout and REDIRECT to get fresh CSRF token
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            
            // IMPORTANT: Redirect to same page to get fresh CSRF token
            return redirect()->route('client.login');
        }

        return view('client.auth.login');
    }

    /**
     * Check if user is admin
     */
    protected function isAdminUser($user): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if (method_exists($user, 'roles') && $user->roles->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Handle client login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check rate limiting
        $this->ensureIsNotRateLimited($request);

        // Find the user
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('No account found with this email.'),
            ]);
        }

        // Check if user is admin (should use admin login)
        if ($this->isAdminUser($user)) {
            throw ValidationException::withMessages([
                'email' => __('Please use the admin login portal.'),
            ]);
        }

        // Check if account is active
        if (isset($user->status) && $user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => __('Your account is not active. Please contact support.'),
            ]);
        }

        // If someone else is logged in, logout first
        if (Auth::check() && Auth::id() !== $user->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerate(); // Regenerate to get new session
        }

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            RateLimiter::clear($this->throttleKey($request));
            
            // Regenerate session for security
            $request->session()->regenerate();
            
            // Store user type in session for quick checks
            $request->session()->put('user_type', 'client');

            return redirect()->intended(route('client.dashboard'));
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'password' => __('The provided password is incorrect.'),
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

        event(new Lockout($request));

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
     * Handle client logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}