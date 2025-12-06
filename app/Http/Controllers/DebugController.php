<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class DebugController extends Controller
{
    public function csrfDebug()
    {
        $data = [
            // CSRF Information
            'csrf_token' => csrf_token(),
            'csrf_token_from_session' => session()->token(),
            
            // Session Information
            'session_id' => session()->getId(),
            'session_name' => session()->getName(),
            'session_status' => session()->isStarted() ? 'Started' : 'Not Started',
            'session_data' => session()->all(),
            
            // Cookie Information
            'laravel_session_cookie' => request()->cookie(session()->getName()),
            'xsrf_token_cookie' => request()->cookie('XSRF-TOKEN'),
            
            // Request Headers
            'headers' => [
                'user_agent' => request()->header('User-Agent'),
                'accept' => request()->header('Accept'),
                'referer' => request()->header('Referer'),
                'origin' => request()->header('Origin'),
            ],
            
            // App Configuration
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            
            // Session Configuration
            'session_config' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
                'expire_on_close' => config('session.expire_on_close'),
                'encrypt' => config('session.encrypt'),
                'cookie' => config('session.cookie'),
                'path' => config('session.path'),
                'domain' => config('session.domain'),
                'secure' => config('session.secure'),
                'http_only' => config('session.http_only'),
                'same_site' => config('session.same_site'),
            ],
            
            // Time Information
            'server_time' => now()->format('Y-m-d H:i:s'),
            'session_expiry_time' => now()->addMinutes(config('session.lifetime'))->format('Y-m-d H:i:s'),
        ];
        
        return view('debug.csrf-debug', compact('data'));
    }
    
    public function testPost(Request $request)
    {
        // This endpoint tests if CSRF works
        if ($request->isMethod('post')) {
            return response()->json([
                'success' => true,
                'message' => 'CSRF validation passed!',
                'received_data' => $request->all(),
                'session_id' => session()->getId(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        
        return view('debug.test-post');
    }
    
    public function clearSession()
    {
        session()->flush();
        return redirect('/debug/csrf')->with('message', 'Session cleared!');
    }
    
    public function setTestSession()
    {
        session(['test_key' => 'test_value_' . time()]);
        return redirect('/debug/csrf')->with('message', 'Test session set!');
    }
}