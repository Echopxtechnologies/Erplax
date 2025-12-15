<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowAdminRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        if(\App\Models\Admin::count()>10){
            return redirect()->route('admin.login')->with('error','Registration is Disabled');
        }
        return $next($request);
    }

}