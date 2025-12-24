<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateAdminSession
{
    /*
    |--------------------------------------------------------------------------
    | Admin Session Validation Middleware (FIXED)
    |--------------------------------------------------------------------------
    |
    | This middleware validates admin sessions on every request to prevent:
    | - Session hijacking (stolen cookie used on different device)
    | - Cookie replay attacks
    | - Idle session abuse
    |
    | CRITICAL FIX: Fingerprint is now generated FRESH on every request,
    | not read from session. This prevents attackers from copying the
    | fingerprint along with the session cookie.
    |
    */

    /**
     * Idle timeout in minutes
     */
    protected int $idleTimeout = 30;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if not authenticated via admin guard
        if (!Auth::guard('admin')->check()) {
            return $next($request);
        }

        $sessionId = session()->getId();
        
        // CRITICAL FIX: Generate fingerprint FRESH from current request
        // Do NOT trust session('admin_fingerprint') - it can be copied!
        $currentFingerprint = $this->generateFingerprint($request);

        // Get session record from database
        $dbSession = DB::table('admin_sessions')
            ->where('session_id', $sessionId)
            ->first();

        // ATTACK: Session ID not found in DB (reused/stolen/expired cookie)
        if (!$dbSession) {
            Log::warning('[Admin Session] Invalid session - not in database', [
                'session_id' => substr($sessionId, 0, 10) . '...',
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 50) . '...',
            ]);
            
            return $this->destroySession($request, 'Session not found. Please login again.');
        }

        //  ATTACK: Fingerprint mismatch (SESSION HIJACKING DETECTED!)
        // This happens when someone copies the cookie to a different browser/device
        if (!hash_equals($dbSession->fingerprint, $currentFingerprint)) {
            Log::warning('[Admin Session] HIJACKING DETECTED - Fingerprint mismatch', [
                'admin_id' => $dbSession->admin_id,
                'session_id' => substr($sessionId, 0, 10) . '...',
                'db_fingerprint' => substr($dbSession->fingerprint, 0, 16) . '...',
                'request_fingerprint' => substr($currentFingerprint, 0, 16) . '...',
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 100),
            ]);
            
            // Delete the compromised session from DB
            DB::table('admin_sessions')
                ->where('session_id', $sessionId)
                ->delete();
            
            return $this->destroySession($request, 'Session security violation. Please login again.');
        }

        //  SECURITY: Idle timeout exceeded
        $lastActivity = \Carbon\Carbon::parse($dbSession->last_activity);
        $idleMinutes = now()->diffInMinutes($lastActivity);
        
        if ($idleMinutes > $this->idleTimeout) {
            Log::info('[Admin Session] Idle timeout', [
                'admin_id' => $dbSession->admin_id,
                'last_activity' => $lastActivity->toDateTimeString(),
                'idle_minutes' => $idleMinutes,
            ]);
            
            // Delete expired session from DB
            DB::table('admin_sessions')
                ->where('session_id', $sessionId)
                ->delete();
            
            return $this->destroySession($request, 'Session expired due to inactivity. Please login again.', 440);
        }

        //  VALID: Update last activity timestamp
        DB::table('admin_sessions')
            ->where('session_id', $sessionId)
            ->update([
                'last_activity' => now(),
                'ip_address' => $request->ip(), // Track IP changes (informational only)
            ]);

        return $next($request);
    }

    /**
     * Generate device fingerprint from current request
     * 
     * This MUST be called fresh on every request - never trust stored values!
     * 
     * The fingerprint is based on:
     * - User-Agent (different per browser)
     * - APP_KEY (prevents external forgery)
     */
    protected function generateFingerprint(Request $request): string
    {
        return hash(
            'sha256',
            $request->userAgent() . '|' . config('app.key')
        );
    }

    /**
     * Destroy session and return appropriate response
     */
    protected function destroySession(Request $request, string $reason, int $code = 401): Response
    {
        // Logout from admin guard
        Auth::guard('admin')->logout();
        
        // Invalidate the session completely
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $reason,
                'redirect' => route('admin.login'),
            ], $code);
        }

        // Redirect to login for web requests
        return redirect()->route('admin.login')
            ->with('error', $reason);
    }
}