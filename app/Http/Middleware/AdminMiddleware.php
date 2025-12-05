<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
        protected array $allowedRoles = [
        'super-admin',
        'admin',
        'manager',
        'staff',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Check if user can access admin panel
        if (!$this->canAccessAdminPanel($user)) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
        /**
     * Check if user can access admin panel
     */
    protected function canAccessAdminPanel($user): bool
    {
        // Check is_admin flag
        if ($user->is_admin) {
            return true;
        }

        // Check if user has any allowed role
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($this->allowedRoles);
        }

        // Or check if user has ANY role
        if (method_exists($user, 'roles')) {
            return $user->roles->count() > 0;
        }

        return false;
    }
}