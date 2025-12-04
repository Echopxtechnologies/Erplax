<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Check using BOTH methods (is_admin flag OR has admin role)
        $isAdmin = $user->is_admin === 1 || 
                   (method_exists($user, 'hasRole') && $user->hasRole('admin'));

        if (!$isAdmin) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin only.');
        }

        return $next($request);
    }
}