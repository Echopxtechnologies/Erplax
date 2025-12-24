<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /*
    |--------------------------------------------------------------------------
    | Admin Access Middleware
    |--------------------------------------------------------------------------
    |
    | This middleware ensures the user is authenticated via admin guard
    | and has admin access rights. Works in conjunction with 
    | ValidateAdminSession middleware for complete security.
    |
    | Middleware order in Kernel.php should be:
    | 1. ValidateAdminSession (validates session integrity)
    | 2. EnsureIsAdmin (validates admin access rights)
    |
    */

    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via admin guard
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'redirect' => route('admin.login'),
                ], 401);
            }
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // Check if admin account is active
        if (!$admin->is_active) {
            return $this->denyAccess($request, 'Your account has been deactivated.');
        }

        // Check if admin can access admin panel
        if (!$this->isAdminUser($admin)) {
            return $this->denyAccess($request, 'You do not have admin access.');
        }

        // Share admin with all views
        View::share('admin', $admin);
        
        // Set as default auth user for compatibility with modules
        Auth::setUser($admin);

        return $next($request);
    }

    /**
     * Check if user has admin privileges
     */
    protected function isAdminUser($admin): bool
    {
        if (!$admin) {
            return false;
        }

        // Check is_admin flag
        if ($admin->is_admin ?? false) {
            return true;
        }

        // Check if has any role (Spatie Permission)
        if (method_exists($admin, 'roles') && $admin->roles->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Deny access and redirect to login
     */
    protected function denyAccess(Request $request, string $message): Response
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'redirect' => route('admin.login'),
            ], 403);
        }

        return redirect()->route('admin.login')
            ->with('error', $message);
    }
}