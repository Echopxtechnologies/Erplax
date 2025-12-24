<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller (Session Security Enabled)
    |--------------------------------------------------------------------------
    |
    | This controller handles admin authentication with ERP-grade session
    | security including fingerprint binding, multi-device support, and
    | session hijacking prevention.
    |
    */

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle login request with secure session creation
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $admin = Admin::where('email', $credentials['email'])->first();

        // Check if admin exists
        if (!$admin) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('No account found with this email.'),
            ]);
        }

        // Check if admin is active
        if (!$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => __('Your account is inactive. Please contact administrator.'),
            ]);
        }

        // Check admin access
        if (!$this->canAccessAdminPanel($admin)) {
            throw ValidationException::withMessages([
                'email' => __('You do not have admin access.'),
            ]);
        }

        // Attempt authentication
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey($request));
            
            // ✅ SECURE SESSION CREATION
            $this->createSecureSession($request, $admin);
            
            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('Invalid credentials.'),
        ]);
    }

    /**
     * Create secure session with fingerprint binding
     */
    protected function createSecureSession(Request $request, Admin $admin): void
    {
        // Regenerate session ID (prevents session fixation)
        $request->session()->regenerate();
        
        // Generate device-bound fingerprint
        $fingerprint = $this->generateFingerprint($request);
        
        // Store fingerprint in session
        session(['admin_fingerprint' => $fingerprint]);
        
        // Store session record in database
        DB::table('admin_sessions')->insert([
            'admin_id'      => $admin->id,
            'session_id'    => session()->getId(),
            'fingerprint'   => $fingerprint,
            'device_name'   => $this->getDeviceName($request),
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
            'last_activity' => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Generate secure device fingerprint
     * 
     * Uses User-Agent + APP_KEY to create device-bound hash
     * - Same browser = same fingerprint
     * - Different browser = different fingerprint
     * - Cannot be forged without APP_KEY
     */
    protected function generateFingerprint(Request $request): string
    {
        return hash(
            'sha256',
            $request->userAgent() . '|' . config('app.key')
        );
    }

    /**
     * Extract device name from request headers
     */
    protected function getDeviceName(Request $request): ?string
    {
        // Try to get platform from Client Hints
        $platform = $request->header('Sec-CH-UA-Platform');
        if ($platform) {
            return trim($platform, '"');
        }

        // Fallback: Parse User-Agent
        $ua = $request->userAgent() ?? '';
        
        if (str_contains($ua, 'Windows')) return 'Windows';
        if (str_contains($ua, 'Mac')) return 'macOS';
        if (str_contains($ua, 'Linux')) return 'Linux';
        if (str_contains($ua, 'Android')) return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';
        
        return 'Unknown';
    }

    /**
     * Handle logout with proper session cleanup
     */
    public function logout(Request $request)
    {
        $sessionId = session()->getId();
        
        // Remove session record from database
        DB::table('admin_sessions')
            ->where('session_id', $sessionId)
            ->delete();
        
        // Logout from admin guard
        Auth::guard('admin')->logout();
        
        // Invalidate session (destroy all session data)
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }

    /**
     * Check if admin can access admin panel
     */
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

    /**
     * Rate limiting check
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
     * Generate throttle key
     */
    protected function throttleKey(Request $request): string
    {
        return 'admin-login:' . Str::lower($request->string('email')) . '|' . $request->ip();
    }
}