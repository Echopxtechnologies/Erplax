<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // Check if admin can access admin panel
        if (!$this->isAdminUser($admin)) {
            // Admin is logged in but not authorized
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            
            return redirect()->route('admin.login')
                ->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }

    /**
     * Check if user has admin access
     */
    protected function isAdminUser($admin): bool
    {
        // Check is_admin flag
        if ($admin->is_admin) {
            return true;
        }

        // Check if admin has any role via Spatie
        if (method_exists($admin, 'roles') && $admin->roles->count() > 0) {
            return true;
        }

        return false;
    }
}