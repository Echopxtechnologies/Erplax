<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via admin guard
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // Check if admin can access admin panel
        if (!$this->isAdminUser($admin)) {
            Auth::guard('admin')->logout();
            $request->session()->regenerate();
            
            return redirect()->route('admin.login')
                ->with('error', 'You do not have admin access.');
        }

        // Share admin with all views
        View::share('admin', $admin);
        
        // Also set as default auth user for compatibility with modules
        Auth::setUser($admin);

        return $next($request);
    }

    protected function isAdminUser($admin): bool
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
}