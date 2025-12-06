<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        $data = [
            'company_name' => Option::get('company_name', ''),
            'company_email' => Option::get('company_email', ''),
            'company_phone' => Option::get('company_phone', ''),
            'company_address' => Option::get('company_address', ''),
            'company_website' => Option::get('company_website', ''),
            'company_gst' => Option::get('company_gst', ''),
            'company_logo' => Option::get('company_logo', ''),
            'company_favicon' => Option::get('company_favicon', ''),
            'site_timezone' => Option::get('site_timezone', 'Asia/Kolkata'),
            'date_format' => Option::get('date_format', 'd/m/Y'),
            'time_format' => Option::get('time_format', 'h:i A'),
            'currency_symbol' => Option::get('currency_symbol', '₹'),
            'currency_code' => Option::get('currency_code', 'INR'),
            'pagination_limit' => Option::get('pagination_limit', 10),
        ];

        return view('admin.settings.general', $data);
    }

    /**
     * Save General Settings
     */
    public function saveSettingsGeneral(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
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
        ]);

        // Company settings
        Option::set('company_name', $request->company_name, ['group' => 'company']);
        Option::set('company_email', $request->company_email, ['group' => 'company']);
        Option::set('company_phone', $request->company_phone, ['group' => 'company']);
        Option::set('company_address', $request->company_address, ['group' => 'company']);
        Option::set('company_website', $request->company_website, ['group' => 'company']);
        Option::set('company_gst', $request->company_gst, ['group' => 'company']);

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
        ];

        return view('admin.settings.email', $data);
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
        ]);

        Option::set('mail_mailer', $request->mail_mailer, ['group' => 'mail']);
        Option::set('mail_host', $request->mail_host, ['group' => 'mail']);
        Option::set('mail_port', $request->mail_port, ['group' => 'mail']);
        Option::set('mail_username', $request->mail_username, ['group' => 'mail']);
        Option::set('mail_password', $request->mail_password, ['group' => 'mail']);
        Option::set('mail_encryption', $request->mail_encryption, ['group' => 'mail']);
        Option::set('mail_from_address', $request->mail_from_address, ['group' => 'mail']);
        Option::set('mail_from_name', $request->mail_from_name, ['group' => 'mail']);

        Option::clearCache();

        $this->logAction('Updated email settings');

        return redirect()->back()->with('success', 'Email settings saved successfully!');
    }

    /**
     * Send Test Email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Config::set('mail.mailers.smtp.host', Option::get('mail_host'));
            Config::set('mail.mailers.smtp.port', Option::get('mail_port'));
            Config::set('mail.mailers.smtp.username', Option::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Option::get('mail_password'));
            Config::set('mail.mailers.smtp.encryption', Option::get('mail_encryption') === 'null' ? null : Option::get('mail_encryption'));
            Config::set('mail.from.address', Option::get('mail_from_address'));
            Config::set('mail.from.name', Option::get('mail_from_name'));

            Mail::raw('This is a test email. If you received this, your email settings are working!', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('Test Email - ' . Option::companyName());
            });

            $this->logAction('Sent test email', ['to' => $request->test_email]);

            return redirect()->back()->with('success', 'Test email sent to ' . $request->test_email);
        } catch (\Exception $e) {
            $this->logError('Test email failed', $e);
            return redirect()->back()->with('error', 'Failed to send: ' . $e->getMessage());
        }
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