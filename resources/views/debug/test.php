<?php
/**
 * Session Security Debug Script
 * 
 * Run this from your project root:
 * php debug_session_security.php
 * 
 * Or access via browser (temporarily place in public folder)
 */

// Load Laravel
require __DIR__ . '../../vendor/autoload.php';
$app = require_once __DIR__ . '../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n==========================================\n";
echo "SESSION SECURITY DEBUG\n";
echo "==========================================\n\n";

// 1. Check Session Driver
$sessionDriver = config('session.driver');
echo "1. Session Driver: {$sessionDriver}\n";
if ($sessionDriver !== 'database') {
    echo "   ❌ ERROR: Must be 'database', not '{$sessionDriver}'\n";
    echo "   FIX: Update .env → SESSION_DRIVER=database\n";
} else {
    echo "   ✅ OK\n";
}

// 2. Check sessions table exists
echo "\n2. Laravel Sessions Table: ";
try {
    $sessionTable = config('session.table', 'sessions');
    $exists = \Illuminate\Support\Facades\Schema::hasTable($sessionTable);
    if ($exists) {
        echo "✅ '{$sessionTable}' exists\n";
    } else {
        echo "❌ '{$sessionTable}' NOT FOUND\n";
        echo "   FIX: Run 'php artisan session:table && php artisan migrate'\n";
    }
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

// 3. Check admin_sessions table exists
echo "\n3. Admin Sessions Table: ";
try {
    $exists = \Illuminate\Support\Facades\Schema::hasTable('admin_sessions');
    if ($exists) {
        echo "✅ 'admin_sessions' exists\n";
        
        // Show columns
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('admin_sessions');
        echo "   Columns: " . implode(', ', $columns) . "\n";
        
        // Show record count
        $count = \Illuminate\Support\Facades\DB::table('admin_sessions')->count();
        echo "   Records: {$count}\n";
    } else {
        echo "❌ 'admin_sessions' NOT FOUND\n";
        echo "   FIX: Run the migration for admin_sessions table\n";
    }
} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

// 4. Check Middleware exists
echo "\n4. Middleware Files:\n";
$middlewarePath = app_path('Http/Middleware');
$middlewares = [
    'ValidateAdminSession.php',
    'EnsureIsAdmin.php',
];
foreach ($middlewares as $file) {
    $fullPath = $middlewarePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "   ✅ {$file} exists\n";
    } else {
        echo "   ❌ {$file} NOT FOUND\n";
    }
}

// 5. Check AdminLoginController
echo "\n5. AdminLoginController:\n";
$controllerPath = app_path('Http/Controllers/Admin/Auth/AdminLoginController.php');
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Check for createSecureSession method
    if (strpos($content, 'createSecureSession') !== false) {
        echo "   ✅ createSecureSession method exists\n";
    } else {
        echo "   ❌ createSecureSession method NOT FOUND\n";
        echo "   FIX: Update AdminLoginController with the new version\n";
    }
    
    // Check for fingerprint generation
    if (strpos($content, 'generateFingerprint') !== false) {
        echo "   ✅ generateFingerprint method exists\n";
    } else {
        echo "   ❌ generateFingerprint method NOT FOUND\n";
    }
    
    // Check if inserting to admin_sessions
    if (strpos($content, 'admin_sessions') !== false) {
        echo "   ✅ Inserting to admin_sessions table\n";
    } else {
        echo "   ❌ NOT inserting to admin_sessions table\n";
    }
} else {
    echo "   ❌ AdminLoginController.php NOT FOUND\n";
}

// 6. Check Cookie Settings
echo "\n6. Cookie Settings:\n";
echo "   secure: " . (config('session.secure') ? '✅ true' : '⚠️ false') . "\n";
echo "   http_only: " . (config('session.http_only') ? '✅ true' : '⚠️ false') . "\n";
echo "   same_site: " . config('session.same_site') . "\n";
echo "   domain: " . (config('session.domain') ?: '(not set)') . "\n";

// 7. Check Routes
echo "\n7. Route Middleware Check:\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $dashboardRoute = $routes->getByName('admin.dashboard');
    
    if ($dashboardRoute) {
        $middleware = $dashboardRoute->middleware();
        echo "   admin.dashboard middleware: " . implode(', ', $middleware) . "\n";
        
        if (in_array('App\Http\Middleware\ValidateAdminSession', $middleware) || 
            in_array('admin.session', $middleware)) {
            echo "   ✅ ValidateAdminSession is applied\n";
        } else {
            echo "   ❌ ValidateAdminSession NOT applied to routes\n";
            echo "   FIX: Add ValidateAdminSession::class to route middleware\n";
        }
    } else {
        echo "   ⚠️ admin.dashboard route not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error checking routes: {$e->getMessage()}\n";
}

// 8. Test fingerprint generation
echo "\n8. Fingerprint Generation Test:\n";
$testUA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
$fingerprint = hash('sha256', $testUA . '|' . config('app.key'));
echo "   Test UA: {$testUA}\n";
echo "   Generated: " . substr($fingerprint, 0, 32) . "...\n";
echo "   Length: " . strlen($fingerprint) . " chars\n";

// 9. Summary
echo "\n==========================================\n";
echo "CHECKLIST\n";
echo "==========================================\n";
echo "[ ] .env: SESSION_DRIVER=database\n";
echo "[ ] Migration: php artisan session:table && php artisan migrate\n";
echo "[ ] Migration: admin_sessions table created\n";
echo "[ ] File: app/Http/Middleware/ValidateAdminSession.php\n";
echo "[ ] File: app/Http/Middleware/EnsureIsAdmin.php (updated)\n";
echo "[ ] File: app/Http/Controllers/Admin/Auth/AdminLoginController.php (updated)\n";
echo "[ ] Route: ValidateAdminSession applied to admin routes\n";
echo "[ ] Clear: php artisan config:clear && php artisan route:clear\n";
echo "[ ] Clear: TRUNCATE sessions; TRUNCATE admin_sessions;\n";
echo "\n";