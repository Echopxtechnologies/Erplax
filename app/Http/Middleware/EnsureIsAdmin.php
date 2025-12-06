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
     * 
     * Ensures the authenticated user is an admin.
     * If user is logged in but not admin, redirect to client dashboard.
     * If not logged in, redirect to admin login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in - redirect to admin login
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Check if user is admin
        if ($this->isAdminUser($user)) {
            return $next($request);
        }

        // User is logged in but NOT admin - they logged in as client
        // Redirect to client dashboard (single session enforcement)
        return redirect()->route('client.dashboard')
            ->with('info', 'You are logged in as a client. Please use the admin portal to access admin features.');
    }

    /**
     * Check if user has admin access
     */
    protected function isAdminUser($user): bool
    {
        // Check is_admin flag
        if ($user->is_admin) {
            return true;
        }

        // Check if user has any role via Spatie
        if (method_exists($user, 'roles') && $user->roles->count() > 0) {
            return true;
        }

        return false;
    }
}