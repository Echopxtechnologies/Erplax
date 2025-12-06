<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AdminSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Set admin-specific session cookie name
        Config::set('session.cookie', 'erp_admin_session');
        
        // Optional: Different session path
        Config::set('session.path', '/admin');
        
        return $next($request);
    }
}