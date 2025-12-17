<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Traits\DataTable;
use App\Models\Option;
use App\Models\Admin\Tax;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    use DataTable;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    
    protected ?Admin $admin;
    protected int $perPage = 10;
    protected array $viewData = [];
    protected string $layout = 'components.layouts.app';

    /**
     * Roles that can access admin panel
     */
    protected array $allowedRoles = [
        'super-admin',
        'admin',
        'manager',
        'staff',
    ];

    /*
    |--------------------------------------------------------------------------
    | Constructor - Authentication happens here
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = auth()->guard('admin')->user(); // This now returns Admin model

                    // ✅ Logout if admin is inactive
        if ($this->admin && !$this->admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated.');
        }
            
            // If you have any other code that expects User, update it too
            return $next($request);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Handlers
    |--------------------------------------------------------------------------
    */

    /**
     * Handle unauthenticated access
     */
    protected function handleUnauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login.',
                'redirect' => route('admin.login')
            ], 401);
        }

        return redirect()->route('admin.login')
            ->with('error', 'Please login to continue.');
    }

    /**
     * Handle unauthorized access (logged in but not admin)
     */
    protected function handleUnauthorized($request, $user)
    {
        // Log unauthorized attempt
        Log::warning('[Admin Unauthorized Access]', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.',
            ], 403);
        }

        // Logout and redirect to admin login
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('error', 'You do not have permission to access the admin panel.');
    }

    /**
     * Check if user can access admin panel
     */
    protected function canAccessAdminPanel($admin): bool
    {
        if (!$admin) {
            return false;
        }
        // ✅ Add this check - Block inactive admins
        if (!$admin->is_active) {
            return false;
        }

        // Check is_admin flag
        if ($admin->is_admin) {
            return true;
        }

        // Check if admin has any role
        if (method_exists($admin, 'hasAnyRole')) {
            return $admin->hasAnyRole($this->allowedRoles);
        }

        // Check if admin has ANY role at all
        if (method_exists($admin, 'roles') && $admin->roles->count() > 0) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Shared Data
    |--------------------------------------------------------------------------
    */

    /**
     * Share common data with all views
     */
    protected function shareViewData(): void
    {
        $this->viewData = [
            'admin' => $this->admin,
            'adminName' => $this->admin?->name,
            'adminEmail' => $this->admin?->email,
            'adminRole' => $this->getAdminRoleName(),
            'adminRoles' => $this->getUserRoles(),
            'adminPermissions' => $this->getUserPermissions(),
        ];

        view()->share($this->viewData);
    }

    /**
     * Get admin's primary role name
     */
    protected function getAdminRoleName(): ?string
    {
        if (!$this->admin) {
            return null;
        }

        if (method_exists($this->admin, 'getRoleNames')) {
            $roles = $this->admin->getRoleNames();
            return $roles->first() ?? ($this->admin->is_admin ? 'admin' : 'user');
        }

        return $this->admin->is_admin ? 'admin' : 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get current authenticated admin
     */
    protected function admin(): ?Admin // Change return type
    {
        return $this->admin ?? Auth::guard('admin')->user(); // Use admin guard
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return Auth::guard('admin')->check(); // Use admin guard
    }
    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        return $this->canAccessAdminPanel($this->admin());
    }

    /**
     * Check if current user is super admin
     */
    protected function isSuperAdmin(): bool
    {
        $user = $this->admin();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['super-admin', 'super_admin']);
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Role & Permission Helpers (Spatie)
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user has specific role
     */
    protected function hasRole(string $role): bool
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'hasRole')) {
            return false;
        }

        return $user->hasRole($role);
    }

    /**
     * Check if user has any of the given roles
     */
    protected function hasAnyRole(array $roles): bool
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole($roles);
    }

    /**
     * Check if user has all given roles
     */
    protected function hasAllRoles(array $roles): bool
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'hasAllRoles')) {
            return false;
        }

        return $user->hasAllRoles($roles);
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission(string $permission): bool
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'hasPermissionTo')) {
            return false;
        }

        return $user->hasPermissionTo($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    protected function hasAnyPermission(array $permissions): bool
    {
        $user = $this->admin();
        
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasAnyPermission')) {
            return $user->hasAnyPermission($permissions);
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has direct permission
     */
    protected function hasDirectPermission(string $permission): bool
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'hasDirectPermission')) {
            return false;
        }

        return $user->hasDirectPermission($permission);
    }

    /**
     * Get all user roles
     */
    protected function getUserRoles(): array
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'getRoleNames')) {
            return [];
        }

        return $user->getRoleNames()->toArray();
    }

    /**
     * Get all user permissions
     */
    protected function getUserPermissions(): array
    {
        $user = $this->admin();
        
        if (!$user || !method_exists($user, 'getAllPermissions')) {
            return [];
        }

        return $user->getAllPermissions()->pluck('name')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Authorization Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Authorize with specific permission - throws exception if unauthorized
     */
    protected function authorizePermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            $this->abortUnauthorized('You do not have permission to perform this action.');
        }
    }

    /**
     * Authorize with specific role - throws exception if unauthorized
     */
    protected function authorizeRole(string $role): void
    {
        if (!$this->hasRole($role)) {
            $this->abortUnauthorized('You do not have the required role to perform this action.');
        }
    }

    /**
     * Authorize with any of the given roles
     */
    protected function authorizeAnyRole(array $roles): void
    {
        if (!$this->hasAnyRole($roles)) {
            $this->abortUnauthorized('You do not have the required role to perform this action.');
        }
    }

    /**
     * Authorize with any of the given permissions
     */
    protected function authorizeAnyPermission(array $permissions): void
    {
        if (!$this->hasAnyPermission($permissions)) {
            $this->abortUnauthorized('You do not have permission to perform this action.');
        }
    }

    /**
     * Abort with unauthorized response
     */
    protected function abortUnauthorized(string $message = 'Access denied.'): void
    {
        if (request()->expectsJson()) {
            abort(response()->json([
                'success' => false,
                'message' => $message,
            ], 403));
        }

        abort(403, $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Response Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Return success JSON response
     */
    protected function successResponse(string $message, $data = null, int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return error JSON response
     */
    protected function errorResponse(string $message, $errors = null, int $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Redirect with success message
     */
    protected function redirectWithSuccess(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    /**
     * Redirect with error message
     */
    protected function redirectWithError(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('error', $message);
    }

    /**
     * Redirect back with success
     */
    protected function backWithSuccess(string $message)
    {
        return back()->with('success', $message);
    }

    /**
     * Redirect back with error
     */
    protected function backWithError(string $message)
    {
        return back()->with('error', $message);
    }

    /**
     * Redirect back with validation errors
     */
    protected function backWithErrors(array $errors)
    {
        return back()->withErrors($errors)->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | View Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Render admin view with layout
     */
    protected function view(string $view, array $data = [])
    {
        return view($view, array_merge($this->viewData, $data))
            ->layout($this->layout);
    }

    /**
     * Render admin view (alias)
     */
    protected function adminView(string $view, array $data = [])
    {
        return $this->view('admin.' . $view, $data);
    }

    /**
     * Render module view with automatic layout
     */
    protected function moduleView(string $view, array $data = [])
    {
        $content = view($view, array_merge($this->viewData, $data))->render();
        return view('components.layouts.app-wrap', ['slot' => $content]);
    }

    /**
     * Add data to view
     */
    protected function with(string $key, $value): self
    {
        $this->viewData[$key] = $value;
        return $this;
    }

    /**
     * Add multiple data to view
     */
    protected function withData(array $data): self
    {
        $this->viewData = array_merge($this->viewData, $data);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Validate request with custom messages
     */
    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        return $request->validate($rules, $messages);
    }

    /**
     * Manual validation
     */
    protected function validateData(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $validator->validated();
    }

    /*
    |--------------------------------------------------------------------------
    | File Upload Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Upload file to storage
     */
    protected function uploadFile(
        $file,
        string $directory = 'uploads',
        string $disk = 'public',
        ?string $filename = null
    ): ?string {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = $filename ?? Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, $disk);
    }

    /**
     * Upload image with validation
     */
    protected function uploadImage(
        $image,
        string $directory = 'images',
        string $disk = 'public',
        ?string $filename = null
    ): ?string {
        if (!$image || !$image->isValid()) {
            return null;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($image->getMimeType(), $allowedMimes)) {
            return null;
        }

        return $this->uploadFile($image, $directory, $disk, $filename);
    }

    /**
     * Delete file from storage
     */
    protected function deleteFile(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    /**
     * Get file URL
     */
    protected function fileUrl(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }

    /*
    |--------------------------------------------------------------------------
    | Database Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Execute within transaction
     */
    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * Begin transaction
     */
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Commit transaction
     */
    protected function commit(): void
    {
        DB::commit();
    }

    /**
     * Rollback transaction
     */
    protected function rollback(): void
    {
        DB::rollBack();
    }

    /*
    |--------------------------------------------------------------------------
    | Cache Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get from cache or execute callback
     */
    protected function cache(string $key, int $minutes, callable $callback)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    /**
     * Forget cache key
     */
    protected function forgetCache(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Clear cache by tag
     */
    protected function clearCacheTag(string $tag): void
    {
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tag)->flush();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Logging Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Log admin action
     */
    protected function logAction(string $action, array $data = []): void
    {
        Log::info('[Admin Action] ' . $action, array_merge([
            'admin_id' => $this->admin()?->id,
            'admin_email' => $this->admin()?->email,
            'admin_roles' => $this->getUserRoles(),
            'ip' => request()->ip(),
        ], $data));
    }

    /**
     * Log error
     */
    protected function logError(string $message, \Throwable $e): void
    {
        Log::error('[Admin Error] ' . $message, [
            'admin_id' => $this->admin()?->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get pagination limit from request
     */
    protected function getPerPage(Request $request, int $default = null): int
    {
        $perPage = $request->input('per_page', $default ?? $this->perPage);
        return min((int) $perPage, 100);
    }

    /**
     * Set default pagination
     */
    protected function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Generate unique slug
     */
    protected function generateSlug(string $text, string $model, string $column = 'slug'): string
    {
        $slug = Str::slug($text);
        $originalSlug = $slug;
        $count = 1;

        while ($model::where($column, $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Format money
     */
    protected function formatMoney(float $amount, string $currency = '₹'): string
    {
        return $currency . number_format($amount, 2);
    }

    /**
     * Get client IP
     */
    protected function clientIp(): string
    {
        return request()->ip();
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return request()->ajax() || request()->wantsJson();
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Message Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Flash success message
     */
    protected function flashSuccess(string $message): void
    {
        session()->flash('success', $message);
    }

    /**
     * Flash error message
     */
    protected function flashError(string $message): void
    {
        session()->flash('error', $message);
    }

    /**
     * Flash warning message
     */
    protected function flashWarning(string $message): void
    {
        session()->flash('warning', $message);
    }

    /**
     * Flash info message
     */
    protected function flashInfo(string $message): void
    {
        session()->flash('info', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard & Settings Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // $this->admin is now an Admin model instance
        return view('admin.dashboard', [
            'admin' => $this->admin
        ]);
    }
    /**
     * General Settings Page
     */
public function settingsGeneral()
{
    // Query DB directly - no cache issues
    $options = \App\Models\Option::whereIn('key', [
        'company_name', 'company_email', 'company_phone', 'company_address',
        'company_website', 'company_gst', 'company_logo', 'company_favicon',
        'site_timezone', 'date_format', 'time_format', 'currency_symbol',
        'currency_code', 'pagination_limit', 'company_city', 'company_state',
        'company_country_code', 'company_zip', 'company_pan', 'company_cin',
    ])->pluck('value', 'key')->toArray();

    return view('admin.settings.general', [
        'company_name' => $options['company_name'] ?? '',
        'company_email' => $options['company_email'] ?? '',
        'company_phone' => $options['company_phone'] ?? '',
        'company_address' => $options['company_address'] ?? '',
        'company_website' => $options['company_website'] ?? '',
        'company_gst' => $options['company_gst'] ?? '',
        'company_logo' => $options['company_logo'] ?? '',
        'company_favicon' => $options['company_favicon'] ?? '',
        'company_city' => $options['company_city'] ?? '',
        'company_state' => $options['company_state'] ?? '',
        'company_country_code' => $options['company_country_code'] ?? '',
        'company_zip' => $options['company_zip'] ?? '',
        'company_pan' => $options['company_pan'] ?? '',
        'company_cin' => $options['company_cin'] ?? '',
        'site_timezone' => $options['site_timezone'] ?? 'Asia/Kolkata',
        'date_format' => $options['date_format'] ?? 'd/m/Y',
        'time_format' => $options['time_format'] ?? 'h:i A',
        'currency_symbol' => $options['currency_symbol'] ?? '₹',
        'currency_code' => $options['currency_code'] ?? 'INR',
        'pagination_limit' => $options['pagination_limit'] ?? 10,
    ]);
}

    /**
     * Save General Settings
     */
    public function saveSettingsGeneral(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'company_website' => 'nullable|url|max:255',
            'company_gst' => 'nullable|string|max:50',
            'company_logo' => 'nullable|image|max:2048',
            'company_favicon' => 'nullable|image|max:1024',
            'site_timezone' => 'required|string',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:10',
            'pagination_limit' => 'required|integer|min:5|max:100',
            'company_city' => 'nullable|string|max:100',
            'company_state' => 'nullable|string|max:100',
            'company_country_code' => 'nullable|string|max:5',
            'company_zip' => 'nullable|string|max:20',
            'company_pan' => 'nullable|string|max:20',
            'company_cin' => 'nullable|string|max:50',
        ]);

        // Company settings
        Option::set('company_name', $request->company_name, ['group' => 'company']);
        Option::set('company_email', $request->company_email, ['group' => 'company']);
        Option::set('company_phone', $request->company_phone, ['group' => 'company']);
        Option::set('company_address', $request->company_address, ['group' => 'company']);
        Option::set('company_website', $request->company_website, ['group' => 'company']);
        Option::set('company_gst', $request->company_gst, ['group' => 'company']);
        Option::set('company_city', $request->company_city, ['group' => 'company']);
        Option::set('company_state', $request->company_state, ['group' => 'company']);
        Option::set('company_country_code', $request->company_country_code, ['group' => 'company']);
        Option::set('company_zip', $request->company_zip, ['group' => 'company']);
        Option::set('company_pan', $request->company_pan, ['group' => 'company']);
        Option::set('company_cin', $request->company_cin, ['group' => 'company']);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            Option::setFile('company_logo', $request->file('company_logo'), ['group' => 'company']);
        }

        // Handle favicon upload
        if ($request->hasFile('company_favicon')) {
            Option::setFile('company_favicon', $request->file('company_favicon'), ['group' => 'company']);
        }

        // General settings
        Option::set('site_timezone', $request->site_timezone, ['group' => 'general']);
        Option::set('date_format', $request->date_format, ['group' => 'general']);
        Option::set('time_format', $request->time_format, ['group' => 'general']);
        Option::set('currency_symbol', $request->currency_symbol, ['group' => 'general']);
        Option::set('currency_code', $request->currency_code, ['group' => 'general']);
        Option::set('pagination_limit', $request->pagination_limit, ['group' => 'general']);

        Option::clearCache();

        $this->logAction('Updated general settings');

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }

    // ===================================================================================
// SYSTEM/SERVER INFORMATION - ADD THIS NEW METHOD
// ===================================================================================

/**
 * System/Server Information Page
 */
public function systemInfoIndex()
{
    // Get MySQL version and settings
    $mysqlVersion = 'N/A';
    $maxConnections = 'N/A';
    $maxPacketSize = 'N/A';
    $sqlMode = 'N/A';
    
    try {
        $mysqlVersion = DB::select('SELECT VERSION() as version')[0]->version ?? 'N/A';
        
        $maxConnResult = DB::select("SHOW VARIABLES LIKE 'max_connections'");
        $maxConnections = $maxConnResult[0]->Value ?? 'N/A';
        
        $maxPacketResult = DB::select("SHOW VARIABLES LIKE 'max_allowed_packet'");
        $maxPacketSize = $maxPacketResult[0]->Value ?? 'N/A';
        if (is_numeric($maxPacketSize)) {
            $maxPacketSize = $this->formatBytes((int)$maxPacketSize);
        }
        
        $sqlModeResult = DB::select("SHOW VARIABLES LIKE 'sql_mode'");
        $sqlMode = $sqlModeResult[0]->Value ?? 'N/A';
    } catch (\Exception $e) {}

    // Session count
    $sessionCount = 0;
    try {
        if (config('session.driver') === 'database') {
            $sessionCount = DB::table(config('session.table', 'sessions'))->count();
        }
    } catch (\Exception $e) {}

    $systemInfo = [
        'os' => PHP_OS,
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'webserver' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
        'webserver_user' => get_current_user(),
        'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'N/A',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? base_path(),
        'base_url' => config('app.url'),
        'environment' => config('app.env'),
        'debug_mode' => config('app.debug'),
        'timezone' => config('app.timezone'),
        'installation_path' => base_path(),
        'temp_dir' => sys_get_temp_dir(),
        
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_input_vars' => ini_get('max_input_vars'),
        'allow_url_fopen' => ini_get('allow_url_fopen'),
        
        'db_driver' => config('database.default'),
        'mysql_version' => $mysqlVersion,
        'db_name' => config('database.connections.mysql.database'),
        'db_host' => config('database.connections.mysql.host'),
        'max_connections' => $maxConnections,
        'max_packet_size' => $maxPacketSize,
        'sql_mode' => $sqlMode,
        
        'cache_driver' => config('cache.default'),
        'session_driver' => config('session.driver'),
        'session_count' => $sessionCount,
        'queue_driver' => config('queue.default'),
        'mail_driver' => config('mail.default'),
        'filesystem_driver' => config('filesystems.default'),
        
        'csrf_enabled' => 'Yes',
        'cloudflare' => isset($_SERVER['HTTP_CF_RAY']) ? 'Yes' : 'No',
    ];

    $requiredExtensions = ['curl', 'openssl', 'mbstring', 'iconv', 'gd', 'zip', 
        'pdo', 'pdo_mysql', 'json', 'xml', 'fileinfo', 'bcmath', 'tokenizer', 'ctype', 'dom', 'session', 'imap'];
    
    $phpExtensions = [];
    foreach ($requiredExtensions as $ext) {
        $phpExtensions[$ext] = [
            'loaded' => extension_loaded($ext),
            'version' => extension_loaded($ext) ? (phpversion($ext) ?: null) : null,
        ];
    }

    $diskTotal = @disk_total_space(base_path()) ?: 0;
    $diskFree = @disk_free_space(base_path()) ?: 0;
    $diskUsed = $diskTotal - $diskFree;
    $diskPercentage = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 2) : 0;
    
    $diskSpace = [
        'total' => $this->formatBytes($diskTotal),
        'free' => $this->formatBytes($diskFree),
        'used' => $this->formatBytes($diskUsed),
        'percentage' => $diskPercentage,
    ];

    $modules = [];
    try {
        $modules = \App\Models\Module::orderBy('name')->get();
    } catch (\Exception $e) {}

    return view('admin.settings.system-info.index', compact('systemInfo', 'phpExtensions', 'diskSpace', 'modules'));
}

/**
 * Clear Sessions
 */
public function clearSessions()
{
    try {
        if (config('session.driver') === 'database') {
            $table = config('session.table', 'sessions');
            $currentSessionId = session()->getId();
            DB::table($table)->where('id', '!=', $currentSessionId)->delete();
            $this->logAction('Cleared session table');
            return redirect()->back()->with('success', 'Sessions cleared! Other users will need to login again.');
        }
        return redirect()->back()->with('error', 'Session clearing only available for database session driver.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
    }
}

/**
 * Format bytes to human readable
 */
protected function formatBytes($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

    /**
     * Email Settings Page
     */
public function settingsEmail()
{
    $data = [
        'mail_mailer' => Option::get('mail_mailer', 'smtp'),
        'mail_host' => Option::get('mail_host', ''),
        'mail_port' => Option::get('mail_port', 587),
        'mail_username' => Option::get('mail_username', ''),
        'mail_password' => Option::get('mail_password', ''),
        'mail_encryption' => Option::get('mail_encryption', 'tls'),
        'mail_from_address' => Option::get('mail_from_address', ''),
        'mail_from_name' => Option::get('mail_from_name', ''),
        // Email templates
        'mail_test_subject' => Option::get('mail_test_subject', 'Test Email - {company_name}'),
        'mail_test_body' => Option::get('mail_test_body', $this->getDefaultTestEmailBody()),
        'mail_footer' => Option::get('mail_footer', $this->getDefaultEmailFooter()),
    ];

    return view('admin.settings.email', $data);
}

/**
 * Get default test email body
 */
protected function getDefaultTestEmailBody(): string
{
    return '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #333;">Test Email</h2>
    <p>Hello,</p>
    <p>This is a test email from <strong>{company_name}</strong>.</p>
    <p>If you received this email, your mail settings are configured correctly!</p>
    <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="color: #666; font-size: 12px;">Sent at: {date_time}</p>
</div>';
}

/**
 * Get default email footer
 */
protected function getDefaultEmailFooter(): string
{
    return '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 12px;">
    <p>{company_name}</p>
    <p>{company_address}</p>
    <p>© {year} All rights reserved.</p>
</div>';
}

   /**
 * Save Email Settings
 */
public function saveSettingsEmail(Request $request)
{
    $request->validate([
        'mail_mailer' => 'required|string',
        'mail_host' => 'required|string|max:255',
        'mail_port' => 'required|integer',
        'mail_username' => 'nullable|string|max:255',
        'mail_password' => 'nullable|string|max:255',
        'mail_encryption' => 'nullable|string|in:tls,ssl,null',
        'mail_from_address' => 'required|email|max:255',
        'mail_from_name' => 'required|string|max:255',
        'mail_test_subject' => 'nullable|string|max:255',
        'mail_test_body' => 'nullable|string',
        'mail_footer' => 'nullable|string',
    ]);

    // SMTP settings
    Option::set('mail_mailer', $request->mail_mailer, ['group' => 'mail']);
    Option::set('mail_host', $request->mail_host, ['group' => 'mail']);
    Option::set('mail_port', $request->mail_port, ['group' => 'mail']);
    Option::set('mail_username', $request->mail_username, ['group' => 'mail']);
    
    // Only update password if provided
    if ($request->filled('mail_password')) {
        Option::set('mail_password', $request->mail_password, ['group' => 'mail']);
    }
    
    Option::set('mail_encryption', $request->mail_encryption, ['group' => 'mail']);
    Option::set('mail_from_address', $request->mail_from_address, ['group' => 'mail']);
    Option::set('mail_from_name', $request->mail_from_name, ['group' => 'mail']);

    // Email templates
    Option::set('mail_test_subject', $request->mail_test_subject, ['group' => 'mail']);
    Option::set('mail_test_body', $request->mail_test_body, ['group' => 'mail']);
    Option::set('mail_footer', $request->mail_footer, ['group' => 'mail']);

    Option::clearCache();

    $this->logAction('Updated email settings');

    return redirect()->back()->with('success', 'Email settings saved successfully!');
}

    /**
     * Send Test Email
     */
    /**
 * Send Test Email
 */
public function sendTestEmail(Request $request)
{
    $request->validate([
        'test_email' => 'required|email',
    ]);

    $result = send_test_mail($request->test_email);

    $this->logAction('Sent test email', ['to' => $request->test_email, 'success' => $result['success']]);

    if ($result['success']) {
        return redirect()->back()->with('success', $result['message']);
    }

    return redirect()->back()->with('error', $result['message']);
}

    /**
     * Admin Logout
     */
    public function logout(Request $request)
    {
        $this->logAction('Admin logged out');

        Auth::guard('admin')->logout(); // Use admin guard
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
    /*
    |--------------------------------------------------------------------------
    | Tax Management Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Tax Management Index
     */
    public function taxIndex()
    {
        $stats = [
            'total' => Tax::count(),
            'active' => Tax::where('is_active', true)->count(),
            'inactive' => Tax::where('is_active', false)->count(),
        ];
        
        return view('admin.settings.taxes.index', compact('stats'));
    }

    /**
     * Tax Data (DataTable)
     */
    public function taxData(Request $request)
    {
        // Handle Store (POST with form data, not file)
        if ($request->isMethod('post') && !$request->hasFile('file') && $request->has('name')) {
            return $this->taxStore($request);
        }

        // Build query
        $query = Tax::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('rate', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        // Paginate
        $perPage = $request->get('per_page', 25);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'rate' => $item->rate,
                'rate_display' => number_format($item->rate, 2) . '%',
                'is_active' => $item->is_active,
                'status' => $item->is_active ? 'Active' : 'Inactive',
                'created_at' => $item->created_at->format('d M Y'),
                '_edit_url' => route('admin.settings.taxes.update', $item->id),
                '_delete_url' => route('admin.settings.taxes.destroy', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Store Tax
     */
    public function taxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:taxes,name',
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';

        $tax = Tax::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tax created successfully!',
                'data' => $tax,
            ]);
        }

        return redirect()->route('admin.settings.taxes.index')->with('success', 'Tax created successfully!');
    }

    /**
     * Update Tax
     */
    public function taxUpdate(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:taxes,name,' . $id,
            'rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';

        $tax->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tax updated successfully!',
                'data' => $tax,
            ]);
        }

        return redirect()->route('admin.settings.taxes.index')->with('success', 'Tax updated successfully!');
    }

    /**
     * Delete Tax
     */
    public function taxDestroy($id)
    {
        $tax = Tax::findOrFail($id);

        // Check if tax is in use
        $inUseCount = \App\Models\Inventory\Product::where('tax_1_id', $id)
            ->orWhere('tax_2_id', $id)
            ->count();

        if ($inUseCount > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete tax. It is assigned to {$inUseCount} product(s).",
                ], 422);
            }
            return back()->with('error', "Cannot delete tax. It is assigned to {$inUseCount} product(s).");
        }

        $tax->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tax deleted successfully!',
            ]);
        }

        return redirect()->route('admin.settings.taxes.index')->with('success', 'Tax deleted successfully!');
    }



    // ===================================================================================
// COUNTRIES
// ===================================================================================

/**
 * Countries Index Page
 */
public function countriesIndex()
{
    $stats = [
        'total' => \App\Models\Country::count(),
        'un_members' => \App\Models\Country::where('un_member', 'yes')->count(),
        'non_members' => \App\Models\Country::where('un_member', '!=', 'yes')->orWhereNull('un_member')->count(),
    ];
    
    return view('admin.settings.countries.index', compact('stats'));
}

/**
 * Countries Data (DataTable)
 */
public function countriesData(Request $request)
{
    // Handle Store (POST with form data)
    if ($request->isMethod('post') && $request->has('iso2')) {
        return $this->countryStore($request);
    }

    // Build query
    $query = \App\Models\Country::query();

    // Search
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('short_name', 'like', "%{$search}%")
              ->orWhere('long_name', 'like', "%{$search}%")
              ->orWhere('iso2', 'like', "%{$search}%")
              ->orWhere('iso3', 'like', "%{$search}%")
              ->orWhere('calling_code', 'like', "%{$search}%");
        });
    }

    // Filter by UN member
    if ($request->filled('un_member')) {
        $query->where('un_member', $request->un_member);
    }

    // Sorting - USE country_id NOT id
    $sortField = 'country_id';
    $sortDir = $request->get('dir', 'desc');
    $query->orderBy($sortField, $sortDir);

    // Paginate
    $perPage = $request->get('per_page', 25);
    $data = $query->paginate($perPage);

    $items = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->country_id,
            'iso2' => $item->iso2,
            'iso3' => $item->iso3,
            'short_name' => $item->short_name,
            'long_name' => $item->long_name,
            'numcode' => $item->numcode,
            'calling_code' => $item->calling_code,
            'cctld' => $item->cctld,
            'un_member' => $item->un_member,
            '_edit_url' => route('admin.settings.countries.update', $item->country_id),
            '_delete_url' => route('admin.settings.countries.destroy', $item->country_id),
        ];
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}
/**
 * Store Country
 */
public function countryStore(Request $request)
{
    $validated = $request->validate([
        'iso2' => 'required|string|max:2|unique:countries,iso2',
        'iso3' => 'nullable|string|max:3',
        'short_name' => 'required|string|max:80',
        'long_name' => 'nullable|string|max:80',
        'numcode' => 'nullable|string|max:6',
        'calling_code' => 'nullable|string|max:8',
        'cctld' => 'nullable|string|max:5',
        'un_member' => 'nullable|string|in:yes,no,some,former',
    ]);

    $country = \App\Models\Country::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Country created successfully!',
            'data' => $country,
        ]);
    }

    return redirect()->route('admin.settings.countries.index')->with('success', 'Country created successfully!');
}

/**
 * Update Country
 */
public function countryUpdate(Request $request, $id)
{
    $country = \App\Models\Country::findOrFail($id);

    $validated = $request->validate([
        'iso2' => 'required|string|max:2|unique:countries,iso2,' . $id . ',country_id',
        'iso3' => 'nullable|string|max:3',
        'short_name' => 'required|string|max:80',
        'long_name' => 'nullable|string|max:80',
        'numcode' => 'nullable|string|max:6',
        'calling_code' => 'nullable|string|max:8',
        'cctld' => 'nullable|string|max:5',
        'un_member' => 'nullable|string|in:yes,no,some,former',
    ]);

    $country->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Country updated successfully!',
            'data' => $country,
        ]);
    }

    return redirect()->route('admin.settings.countries.index')->with('success', 'Country updated successfully!');
}

/**
 * Delete Country
 */
public function countryDestroy($id)
{
    $country = \App\Models\Country::findOrFail($id);
    $country->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Country deleted successfully!',
        ]);
    }

    return redirect()->route('admin.settings.countries.index')->with('success', 'Country deleted successfully!');
}


// ===================================================================================
// CURRENCIES
// ===================================================================================

/**
 * Currencies Index Page
 */
public function currenciesIndex()
{
    $stats = [
        'total' => \App\Models\Currency::count(),
        'active' => \App\Models\Currency::where('is_active', true)->count(),
        'inactive' => \App\Models\Currency::where('is_active', false)->count(),
    ];
    
    return view('admin.settings.currencies.index', compact('stats'));
}

/**
 * Currencies Data (DataTable)
 */
public function currenciesData(Request $request)
{
    // Handle Store (POST with form data)
    if ($request->isMethod('post') && $request->has('code')) {
        return $this->currencyStore($request);
    }

    // Build query
    $query = \App\Models\Currency::query();

    // Search
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('symbol', 'like', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    // Sorting
    $sortField = $request->get('sort', 'code');
    $sortDir = $request->get('dir', 'asc');
    $query->orderBy($sortField, $sortDir);

    // Paginate
    $perPage = $request->get('per_page', 25);
    $data = $query->paginate($perPage);

    $items = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'symbol' => $item->symbol,
            'exchange_rate' => $item->exchange_rate,
            'exchange_rate_display' => number_format($item->exchange_rate, 4),
            'decimal_places' => $item->decimal_places,
            'symbol_position' => $item->symbol_position,
            'is_default' => $item->is_default,
            'is_active' => $item->is_active,
            'created_at' => $item->created_at ? $item->created_at->format('d M Y') : '-',
            '_edit_url' => route('admin.settings.currencies.update', $item->id),
            '_delete_url' => route('admin.settings.currencies.destroy', $item->id),
        ];
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}

/**
 * Store Currency
 */
public function currencyStore(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|string|max:3|unique:currencies,code',
        'name' => 'required|string|max:100',
        'symbol' => 'required|string|max:10',
        'exchange_rate' => 'required|numeric|min:0',
        'decimal_places' => 'nullable|integer|min:0|max:4',
        'symbol_position' => 'nullable|string|in:before,after',
        'thousand_separator' => 'nullable|string|max:1',
        'decimal_separator' => 'nullable|string|max:1',
        'is_default' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_default'] = $request->has('is_default') || $request->is_default == '1';

    // If setting as default, unset others
    if ($validated['is_default']) {
        \App\Models\Currency::where('is_default', true)->update(['is_default' => false]);
    }

    $currency = \App\Models\Currency::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Currency created successfully!',
            'data' => $currency,
        ]);
    }

    return redirect()->route('admin.settings.currencies.index')->with('success', 'Currency created successfully!');
}

/**
 * Update Currency
 */
public function currencyUpdate(Request $request, $id)
{
    $currency = \App\Models\Currency::findOrFail($id);

    $validated = $request->validate([
        'code' => 'required|string|max:3|unique:currencies,code,' . $id,
        'name' => 'required|string|max:100',
        'symbol' => 'required|string|max:10',
        'exchange_rate' => 'required|numeric|min:0',
        'decimal_places' => 'nullable|integer|min:0|max:4',
        'symbol_position' => 'nullable|string|in:before,after',
        'thousand_separator' => 'nullable|string|max:1',
        'decimal_separator' => 'nullable|string|max:1',
        'is_default' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_default'] = $request->has('is_default') || $request->is_default == '1';

    // If setting as default, unset others
    if ($validated['is_default'] && !$currency->is_default) {
        \App\Models\Currency::where('is_default', true)->update(['is_default' => false]);
    }

    $currency->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Currency updated successfully!',
            'data' => $currency,
        ]);
    }

    return redirect()->route('admin.settings.currencies.index')->with('success', 'Currency updated successfully!');
}

/**
 * Delete Currency
 */
public function currencyDestroy($id)
{
    $currency = \App\Models\Currency::findOrFail($id);

    if ($currency->is_default) {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default currency.',
            ], 422);
        }
        return back()->with('error', 'Cannot delete the default currency.');
    }

    $currency->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Currency deleted successfully!',
        ]);
    }

    return redirect()->route('admin.settings.currencies.index')->with('success', 'Currency deleted successfully!');
}


// ===================================================================================
// PAYMENT METHODS
// ===================================================================================

/**
 * Payment Methods Index Page
 */
public function paymentMethodsIndex()
{
    $stats = [
        'total' => \App\Models\PaymentMethod::count(),
        'active' => \App\Models\PaymentMethod::where('is_active', true)->count(),
        'inactive' => \App\Models\PaymentMethod::where('is_active', false)->count(),
    ];
    
    return view('admin.settings.payment-methods.index', compact('stats'));
}

/**
 * Payment Methods Data (DataTable)
 */
public function paymentMethodsData(Request $request)
{
    // Handle Store (POST with form data)
    if ($request->isMethod('post') && $request->has('name')) {
        return $this->paymentMethodStore($request);
    }

    // Build query
    $query = \App\Models\PaymentMethod::query();

    // Search
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    // Sorting
    $sortField = $request->get('sort', 'sort_order');
    $sortDir = $request->get('dir', 'asc');
    $query->orderBy($sortField, $sortDir);

    // Paginate
    $perPage = $request->get('per_page', 25);
    $data = $query->paginate($perPage);

    $items = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'sort_order' => $item->sort_order,
            'show_on_invoice' => $item->show_on_invoice,
            'is_default' => $item->is_default,
            'is_active' => $item->is_active,
            'created_at' => $item->created_at ? $item->created_at->format('d M Y') : '-',
            '_edit_url' => route('admin.settings.payment-methods.update', $item->id),
            '_delete_url' => route('admin.settings.payment-methods.destroy', $item->id),
        ];
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}

/**
 * Store Payment Method
 */
public function paymentMethodStore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:payment_methods,name',
        'slug' => 'nullable|string|max:100|unique:payment_methods,slug',
        'description' => 'nullable|string|max:500',
        'sort_order' => 'nullable|integer|min:0',
        'show_on_invoice' => 'nullable|boolean',
        'is_default' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    // Generate slug if not provided
    if (empty($validated['slug'])) {
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
    }

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_default'] = $request->has('is_default') || $request->is_default == '1';
    $validated['show_on_invoice'] = $request->has('show_on_invoice') || $request->show_on_invoice == '1';

    // If setting as default, unset others
    if ($validated['is_default']) {
        \App\Models\PaymentMethod::where('is_default', true)->update(['is_default' => false]);
    }

    $paymentMethod = \App\Models\PaymentMethod::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Payment method created successfully!',
            'data' => $paymentMethod,
        ]);
    }

    return redirect()->route('admin.settings.payment-methods.index')->with('success', 'Payment method created successfully!');
}

/**
 * Update Payment Method
 */
public function paymentMethodUpdate(Request $request, $id)
{
    $paymentMethod = \App\Models\PaymentMethod::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:payment_methods,name,' . $id,
        'slug' => 'nullable|string|max:100|unique:payment_methods,slug,' . $id,
        'description' => 'nullable|string|max:500',
        'sort_order' => 'nullable|integer|min:0',
        'show_on_invoice' => 'nullable|boolean',
        'is_default' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_default'] = $request->has('is_default') || $request->is_default == '1';
    $validated['show_on_invoice'] = $request->has('show_on_invoice') || $request->show_on_invoice == '1';

    // If setting as default, unset others
    if ($validated['is_default'] && !$paymentMethod->is_default) {
        \App\Models\PaymentMethod::where('is_default', true)->update(['is_default' => false]);
    }

    $paymentMethod->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Payment method updated successfully!',
            'data' => $paymentMethod,
        ]);
    }

    return redirect()->route('admin.settings.payment-methods.index')->with('success', 'Payment method updated successfully!');
}

/**
 * Delete Payment Method
 */
public function paymentMethodDestroy($id)
{
    $paymentMethod = \App\Models\PaymentMethod::findOrFail($id);

    if ($paymentMethod->is_default) {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default payment method.',
            ], 422);
        }
        return back()->with('error', 'Cannot delete the default payment method.');
    }

    $paymentMethod->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Payment method deleted successfully!',
        ]);
    }

    return redirect()->route('admin.settings.payment-methods.index')->with('success', 'Payment method deleted successfully!');
}


// ===================================================================================
// TIMEZONES
// ===================================================================================

/**
 * Timezones Index Page
 */
public function timezonesIndex()
{
    $stats = [
        'total' => \App\Models\Timezone::count(),
        'active' => \App\Models\Timezone::where('is_active', true)->count(),
        'inactive' => \App\Models\Timezone::where('is_active', false)->count(),
    ];
    
    return view('admin.settings.timezones.index', compact('stats'));
}

/**
 * Timezones Data (DataTable)
 */
public function timezonesData(Request $request)
{
    // Handle Store (POST with form data)
    if ($request->isMethod('post') && $request->has('name')) {
        return $this->timezoneStore($request);
    }

    // Build query
    $query = \App\Models\Timezone::query();

    // Search
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('label', 'like', "%{$search}%")
              ->orWhere('country_code', 'like', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    // Sorting
    $sortField = $request->get('sort', 'utc_offset');
    $sortDir = $request->get('dir', 'asc');
    $query->orderBy($sortField, $sortDir);

    // Paginate
    $perPage = $request->get('per_page', 25);
    $data = $query->paginate($perPage);

    $items = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'label' => $item->label,
            'country_code' => $item->country_code,
            'utc_offset' => $item->utc_offset,
            'utc_offset_display' => $item->utc_offset >= 0 ? '+' . $item->utc_offset : $item->utc_offset,
            'sort_order' => $item->sort_order,
            'is_active' => $item->is_active,
            'created_at' => $item->created_at ? $item->created_at->format('d M Y') : '-',
            '_edit_url' => route('admin.settings.timezones.update', $item->id),
            '_delete_url' => route('admin.settings.timezones.destroy', $item->id),
        ];
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}

/**
 * Store Timezone
 */
public function timezoneStore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:timezones,name',
        'label' => 'nullable|string|max:150',
        'country_code' => 'nullable|string|max:2',
        'utc_offset' => 'required|numeric|between:-12,14',
        'sort_order' => 'nullable|integer|min:0',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';

    $timezone = \App\Models\Timezone::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Timezone created successfully!',
            'data' => $timezone,
        ]);
    }

    return redirect()->route('admin.settings.timezones.index')->with('success', 'Timezone created successfully!');
}

/**
 * Update Timezone
 */
public function timezoneUpdate(Request $request, $id)
{
    $timezone = \App\Models\Timezone::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:timezones,name,' . $id,
        'label' => 'nullable|string|max:150',
        'country_code' => 'nullable|string|max:2',
        'utc_offset' => 'required|numeric|between:-12,14',
        'sort_order' => 'nullable|integer|min:0',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';

    $timezone->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Timezone updated successfully!',
            'data' => $timezone,
        ]);
    }

    return redirect()->route('admin.settings.timezones.index')->with('success', 'Timezone updated successfully!');
}

/**
 * Delete Timezone
 */
public function timezoneDestroy($id)
{
    $timezone = \App\Models\Timezone::findOrFail($id);
    $timezone->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Timezone deleted successfully!',
        ]);
    }

    return redirect()->route('admin.settings.timezones.index')->with('success', 'Timezone deleted successfully!');
}

// ===================================================================================
// BANK DETAILS
// ===================================================================================

/**
 * Bank Details Index Page
 */
public function bankDetailsIndex()
{
    $stats = [
        'total' => \App\Models\Admin\BankDetail::count(),
        'active' => \App\Models\Admin\BankDetail::where('is_active', true)->count(),
        'vendors' => \App\Models\Admin\BankDetail::where('holder_type', 'vendor')->count(),
        'customers' => \App\Models\Admin\BankDetail::where('holder_type', 'customer')->count(),
    ];
    
    return view('admin.settings.bank-details.index', compact('stats'));
}

/**
 * Bank Details Data (DataTable)
 */
public function bankDetailsData(Request $request)
{
    if ($request->isMethod('post') && $request->has('account_holder_name')) {
        return $this->bankDetailStore($request);
    }

    $query = \App\Models\Admin\BankDetail::query();

    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('account_holder_name', 'like', "%{$search}%")
              ->orWhere('bank_name', 'like', "%{$search}%")
              ->orWhere('account_number', 'like', "%{$search}%")
              ->orWhere('ifsc_code', 'like', "%{$search}%");
        });
    }

    if ($request->filled('holder_type')) {
        $query->where('holder_type', $request->holder_type);
    }

    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    $sortField = $request->get('sort', 'id');
    $sortDir = $request->get('dir', 'desc');
    $query->orderBy($sortField, $sortDir);

    $perPage = $request->get('per_page', 25);
    $data = $query->paginate($perPage);

    $items = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->id,
            'holder_type' => ucfirst($item->holder_type),
            'holder_id' => $item->holder_id,
            'account_holder_name' => $item->account_holder_name,
            'bank_name' => $item->bank_name,
            'account_number' => $item->account_number,
            'ifsc_code' => $item->ifsc_code,
            'branch_name' => $item->branch_name,
            'upi_id' => $item->upi_id,
            'account_type' => $item->account_type,
            'is_primary' => $item->is_primary,
            'is_active' => $item->is_active,
            'created_at' => $item->created_at ? $item->created_at->format('d M Y') : '-',
            '_edit_url' => route('admin.settings.bank-details.update', $item->id),
            '_delete_url' => route('admin.settings.bank-details.destroy', $item->id),
        ];
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}

/**
 * Store Bank Detail
 */
public function bankDetailStore(Request $request)
{
    $validated = $request->validate([
        'holder_type' => 'required|string|in:vendor,customer,employee,company',
        'holder_id' => 'required|integer',
        'account_holder_name' => 'required|string|max:191',
        'bank_name' => 'required|string|max:191',
        'account_number' => 'required|string|max:50',
        'ifsc_code' => 'nullable|string|max:20',
        'branch_name' => 'nullable|string|max:191',
        'upi_id' => 'nullable|string|max:100',
        'account_type' => 'nullable|string|in:SAVINGS,CURRENT,OTHER',
        'is_primary' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_primary'] = $request->has('is_primary') || $request->is_primary == '1';

    // If setting as primary, unset others for same holder
    if ($validated['is_primary']) {
        \App\Models\Admin\BankDetail::where('holder_type', $validated['holder_type'])
            ->where('holder_id', $validated['holder_id'])
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
    }

    $bank = \App\Models\Admin\BankDetail::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Bank detail created successfully!',
            'data' => $bank,
        ]);
    }

    return redirect()->route('admin.settings.bank-details.index')->with('success', 'Bank detail created successfully!');
}

/**
 * Update Bank Detail
 */
public function bankDetailUpdate(Request $request, $id)
{
    $bank = \App\Models\Admin\BankDetail::findOrFail($id);

    $validated = $request->validate([
        'holder_type' => 'required|string|in:vendor,customer,employee,company',
        'holder_id' => 'required|integer',
        'account_holder_name' => 'required|string|max:191',
        'bank_name' => 'required|string|max:191',
        'account_number' => 'required|string|max:50',
        'ifsc_code' => 'nullable|string|max:20',
        'branch_name' => 'nullable|string|max:191',
        'upi_id' => 'nullable|string|max:100',
        'account_type' => 'nullable|string|in:SAVINGS,CURRENT,OTHER',
        'is_primary' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
    ]);

    $validated['is_active'] = $request->has('is_active') || $request->is_active == '1';
    $validated['is_primary'] = $request->has('is_primary') || $request->is_primary == '1';

    // If setting as primary, unset others for same holder
    if ($validated['is_primary'] && !$bank->is_primary) {
        \App\Models\Admin\BankDetail::where('holder_type', $validated['holder_type'])
            ->where('holder_id', $validated['holder_id'])
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
    }

    $bank->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Bank detail updated successfully!',
            'data' => $bank,
        ]);
    }

    return redirect()->route('admin.settings.bank-details.index')->with('success', 'Bank detail updated successfully!');
}

/**
 * Delete Bank Detail
 */
public function bankDetailDestroy($id)
{
    $bank = \App\Models\Admin\BankDetail::findOrFail($id);
    $bank->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Bank detail deleted successfully!',
        ]);
    }

    return redirect()->route('admin.settings.bank-details.index')->with('success', 'Bank detail deleted successfully!');
}
    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get menu configuration - Override in child classes
     */
    public static function menu(): ?array
    {
        return null;
    }

    /**
     * Render menu HTML
     */
    public static function renderMenu(array $menu): string
    {
        $icons = [
            'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
            'plus' => 'M12 4v16m8-8H4',
            'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'mail' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'cube' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'tag' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        ];

        $routePrefix = $menu['route'] ?? '';
        $isActive = $routePrefix && request()->routeIs($routePrefix);
        $hasChildren = !empty($menu['children']);
        $iconPath = $icons[$menu['icon'] ?? 'folder'] ?? $icons['folder'];

        $html = '';

        if ($hasChildren) {
            $activeClass = $isActive ? 'active open' : '';
            $html .= '<div class="nav-item ' . $activeClass . '" onclick="this.classList.toggle(\'open\'); this.nextElementSibling.classList.toggle(\'open\');" style="cursor: pointer;">';
            $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
            $html .= '<span>' . e($menu['title']) . '</span>';
            $html .= '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
            $html .= '</div>';

            $submenuClass = $isActive ? 'open' : '';
            $html .= '<div class="nav-submenu ' . $submenuClass . '">';
            foreach ($menu['children'] as $child) {
                $childRoute = $child['route'] ?? '#';
                $childActive = request()->routeIs($childRoute) ? 'active' : '';
                $childIcon = $icons[$child['icon'] ?? 'document'] ?? $icons['document'];
                $childHref = \Route::has($childRoute) ? route($childRoute) : '#';

                $html .= '<a href="' . $childHref . '" class="nav-item ' . $childActive . '">';
                $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $childIcon . '"></path></svg>';
                $html .= '<span>' . e($child['title']) . '</span>';
                $html .= '</a>';
            }
            $html .= '</div>';
        } else {
            $activeClass = $isActive ? 'active' : '';
            $href = \Route::has($routePrefix) ? route($routePrefix) : '#';
            $html .= '<a href="' . $href . '" class="nav-item ' . $activeClass . '">';
            $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
            $html .= '<span>' . e($menu['title']) . '</span>';
            $html .= '</a>';
        }

        return $html;
    }



}