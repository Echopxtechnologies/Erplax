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

class ClientLoginController extends Controller  // ← Changed from ClientController
{
    /**
     * Show the client login form
     */
    public function showLoginForm()
    {
        // If already logged in as client, redirect to dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('client.dashboard');
        }

        // If admin is logged in, let them know
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'You are logged in as admin. Logout first to access client portal.');
        }

        return view('client.auth.login');
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

        // Check if account is active
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => __('Your account is not active. Please contact support.'),
            ]);
        }

        // Attempt to authenticate with web guard
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            
            RateLimiter::clear($this->throttleKey($request));
            
            // Regenerate session for security (don't invalidate - preserves admin session)
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
        Auth::guard('web')->logout();
        
        // Only regenerate, don't invalidate (preserves admin session if logged in)
        $request->session()->regenerate();
        $request->session()->forget('user_type');

        return redirect()->route('client.login')->with('success', 'Logged out successfully.');
    }
}