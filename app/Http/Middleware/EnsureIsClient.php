<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsClient
{
    /**
     * Handle an incoming request.
     * 
     * Ensures the authenticated user is a client (not admin).
     * If user is logged in but is admin, redirect to admin dashboard.
     * If not logged in, redirect to client login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in - redirect to client login
        if (!Auth::check()) {
            return redirect()->route('client.login');
        }

        $user = Auth::user();

        // Check if user is admin
        if ($this->isAdminUser($user)) {
            // User is logged in as admin - redirect to admin dashboard
            return redirect()->route('admin.dashboard')
                ->with('info', 'You are logged in as admin. Please use the client portal for client features.');
        }

        // User is client - allow access
        return $next($request);
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