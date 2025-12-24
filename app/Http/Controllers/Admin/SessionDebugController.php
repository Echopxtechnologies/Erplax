<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class SessionDebugController extends Controller
{
    public function index()
    {
        $results = [];
        
        // 1. Session Driver
        $sessionDriver = config('session.driver');
        $results['session_driver'] = [
            'label' => 'Session Driver',
            'value' => $sessionDriver,
            'status' => $sessionDriver === 'database' ? 'ok' : 'error',
            'message' => $sessionDriver === 'database' 
                ? 'Correctly set to database' 
                : "Must be 'database', not '{$sessionDriver}'. Update .env → SESSION_DRIVER=database"
        ];
        
        // 2. Sessions table
        $sessionTable = config('session.table', 'sessions');
        $sessionsExists = Schema::hasTable($sessionTable);
        $results['sessions_table'] = [
            'label' => 'Laravel Sessions Table',
            'value' => $sessionTable,
            'status' => $sessionsExists ? 'ok' : 'error',
            'message' => $sessionsExists 
                ? "Table '{$sessionTable}' exists" 
                : "Table '{$sessionTable}' NOT FOUND. Run: php artisan session:table && php artisan migrate"
        ];
        
        // 3. Admin sessions table
        $adminSessionsExists = Schema::hasTable('admin_sessions');
        $adminSessionsCount = 0;
        $adminSessionsColumns = [];
        if ($adminSessionsExists) {
            $adminSessionsCount = DB::table('admin_sessions')->count();
            $adminSessionsColumns = Schema::getColumnListing('admin_sessions');
        }
        $results['admin_sessions_table'] = [
            'label' => 'Admin Sessions Table',
            'value' => 'admin_sessions',
            'status' => $adminSessionsExists ? 'ok' : 'error',
            'message' => $adminSessionsExists 
                ? "Table exists with {$adminSessionsCount} records. Columns: " . implode(', ', $adminSessionsColumns)
                : "Table 'admin_sessions' NOT FOUND. Run the migration."
        ];
        
        // 4. Middleware files
        $middlewarePath = app_path('Http/Middleware');
        $validateSessionExists = file_exists($middlewarePath . '/ValidateAdminSession.php');
        $ensureAdminExists = file_exists($middlewarePath . '/EnsureIsAdmin.php');
        $results['middleware_files'] = [
            'label' => 'Middleware Files',
            'value' => '',
            'status' => ($validateSessionExists && $ensureAdminExists) ? 'ok' : 'error',
            'message' => implode(' | ', [
                'ValidateAdminSession.php: ' . ($validateSessionExists ? '✅' : '❌'),
                'EnsureIsAdmin.php: ' . ($ensureAdminExists ? '✅' : '❌')
            ])
        ];
        
        // 5. AdminLoginController check
        $controllerPath = app_path('Http/Controllers/Admin/Auth/AdminLoginController.php');
        $controllerChecks = [];
        if (file_exists($controllerPath)) {
            $content = file_get_contents($controllerPath);
            $controllerChecks['file'] = true;
            $controllerChecks['createSecureSession'] = strpos($content, 'createSecureSession') !== false;
            $controllerChecks['generateFingerprint'] = strpos($content, 'generateFingerprint') !== false;
            $controllerChecks['admin_sessions'] = strpos($content, 'admin_sessions') !== false;
        } else {
            $controllerChecks['file'] = false;
        }
        
        $allControllerOk = $controllerChecks['file'] && 
                          ($controllerChecks['createSecureSession'] ?? false) && 
                          ($controllerChecks['generateFingerprint'] ?? false) &&
                          ($controllerChecks['admin_sessions'] ?? false);
        
        $results['login_controller'] = [
            'label' => 'AdminLoginController',
            'value' => '',
            'status' => $allControllerOk ? 'ok' : 'error',
            'message' => $controllerChecks['file'] 
                ? implode(' | ', [
                    'createSecureSession: ' . (($controllerChecks['createSecureSession'] ?? false) ? '✅' : '❌'),
                    'generateFingerprint: ' . (($controllerChecks['generateFingerprint'] ?? false) ? '✅' : '❌'),
                    'admin_sessions insert: ' . (($controllerChecks['admin_sessions'] ?? false) ? '✅' : '❌'),
                ])
                : 'AdminLoginController.php NOT FOUND'
        ];
        
        // 6. Cookie settings
        $results['cookie_settings'] = [
            'label' => 'Cookie Settings',
            'value' => '',
            'status' => config('session.secure') && config('session.http_only') ? 'ok' : 'warning',
            'message' => implode(' | ', [
                'secure: ' . (config('session.secure') ? '✅ true' : '⚠️ false'),
                'http_only: ' . (config('session.http_only') ? '✅ true' : '⚠️ false'),
                'same_site: ' . config('session.same_site'),
                'domain: ' . (config('session.domain') ?: '(not set)'),
            ])
        ];
        
        // 7. Route middleware check
        $routes = Route::getRoutes();
        $dashboardRoute = $routes->getByName('admin.dashboard');
        $middlewareApplied = false;
        $routeMiddleware = [];
        
        if ($dashboardRoute) {
            $routeMiddleware = $dashboardRoute->middleware();
            $middlewareApplied = in_array('App\Http\Middleware\ValidateAdminSession', $routeMiddleware) || 
                                in_array(\App\Http\Middleware\ValidateAdminSession::class, $routeMiddleware);
        }
        
        $results['route_middleware'] = [
            'label' => 'Route Middleware',
            'value' => '',
            'status' => $middlewareApplied ? 'ok' : 'error',
            'message' => $dashboardRoute 
                ? ($middlewareApplied 
                    ? '✅ ValidateAdminSession is applied. Middleware: ' . implode(', ', array_map(fn($m) => is_string($m) ? class_basename($m) : $m, $routeMiddleware))
                    : '❌ ValidateAdminSession NOT applied. Current: ' . implode(', ', array_map(fn($m) => is_string($m) ? class_basename($m) : $m, $routeMiddleware)))
                : 'admin.dashboard route not found'
        ];
        
        // 8. Current session info - THIS IS THE KEY TEST!
        $currentSessionId = session()->getId();
        
        // Generate FRESH fingerprint from current request (this is what the middleware does)
        $freshFingerprint = $this->generateFingerprint(request());
        
        // Get stored fingerprint from session (this gets COPIED with the cookie!)
        $storedFingerprint = session('admin_fingerprint');
        
        $dbSession = null;
        $dbFingerprint = null;
        
        if ($adminSessionsExists && $currentSessionId) {
            $dbSession = DB::table('admin_sessions')
                ->where('session_id', $currentSessionId)
                ->first();
            if ($dbSession) {
                $dbFingerprint = $dbSession->fingerprint;
            }
        }
        
        // Check if fingerprints match
        $fingerprintMatch = $dbFingerprint && hash_equals($dbFingerprint, $freshFingerprint);
        
        $results['current_session'] = [
            'label' => 'Current Session',
            'value' => '',
            'status' => ($dbSession && $fingerprintMatch) ? 'ok' : 'error',
            'message' => implode(' | ', [
                'Session ID: ' . substr($currentSessionId, 0, 10) . '...',
                'Found in DB: ' . ($dbSession ? '✅' : '❌'),
            ])
        ];
        
        // 9. FINGERPRINT COMPARISON - Critical for hijacking detection!
        $results['fingerprint_check'] = [
            'label' => '🔐 Fingerprint Check',
            'value' => '',
            'status' => $fingerprintMatch ? 'ok' : 'error',
            'message' => $fingerprintMatch 
                ? '✅ VALID - Fresh fingerprint matches DB record'
                : '❌ MISMATCH - This session would be BLOCKED (possible hijacking!)'
        ];
        
        // 10. Detailed fingerprint info
        $results['fingerprint_details'] = [
            'label' => 'Fingerprint Details',
            'value' => '',
            'status' => 'info',
            'message' => implode(' | ', [
                'Fresh (from UA): ' . ($freshFingerprint ? substr($freshFingerprint, 0, 12) . '...' : 'N/A'),
                'In DB: ' . ($dbFingerprint ? substr($dbFingerprint, 0, 12) . '...' : 'N/A'),
                'In Session: ' . ($storedFingerprint ? substr($storedFingerprint, 0, 12) . '...' : 'N/A'),
            ])
        ];
        
        // 11. User Agent
        $results['user_agent'] = [
            'label' => 'Your User-Agent',
            'value' => '',
            'status' => 'info',
            'message' => request()->userAgent()
        ];
        
        // All active admin sessions
        $activeSessions = [];
        if ($adminSessionsExists) {
            $activeSessions = DB::table('admin_sessions')
                ->join('admins', 'admin_sessions.admin_id', '=', 'admins.id')
                ->select(
                    'admin_sessions.*',
                    'admins.name as admin_name',
                    'admins.email as admin_email'
                )
                ->orderBy('last_activity', 'desc')
                ->limit(10)
                ->get();
        }
        
        return view('debug.session-security', [
            'results' => $results,
            'activeSessions' => $activeSessions,
            'currentSessionId' => $currentSessionId,
            'freshFingerprint' => $freshFingerprint,
        ]);
    }
    
    /**
     * Generate fingerprint (same logic as middleware)
     */
    protected function generateFingerprint($request): string
    {
        return hash(
            'sha256',
            $request->userAgent() . '|' . config('app.key')
        );
    }
}