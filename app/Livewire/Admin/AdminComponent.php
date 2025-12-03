<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Base Admin Component
 * 
 * Extend this class for all admin Livewire components.
 * Provides authentication, authorization, file uploads, 
 * flash messages, and many utility methods.
 */
abstract class AdminComponent extends Component
{
    use WithPagination, WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Default pagination limit
     */
    protected int $perPage = 10;

    /**
     * Layout to use
     */
    protected string $layout = 'components.layouts.app';

    /**
     * Pagination theme
     */
    protected string $paginationTheme = 'tailwind';

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    /**
     * Boot - Runs on every request
     */
    public function boot()
    {
        // Check admin authorization on every request
        $this->checkAdminAuth();
    }

    /**
     * Mount - Runs once when component is initialized
     */
    public function mount()
    {
        $this->checkAdminAuth();
        $this->init();
    }

    /**
     * Override this in child components for custom initialization
     */
    protected function init(): void
    {
        // Child components can override this
    }

    /**
     * Check admin authentication
     */
    protected function checkAdminAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
        }

        if (!$this->isAdmin()) {
            $this->redirectToLogin('Access denied. Admin only.');
        }
    }

    /**
     * Redirect to login
     */
    protected function redirectToLogin(string $message = null): void
    {
        if ($message) {
            session()->flash('error', $message);
        }
        $this->redirect(route('admin.login'));
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication & Authorization
    |--------------------------------------------------------------------------
    */

    /**
     * Get current authenticated admin
     */
    protected function admin(): ?User
    {
        return Auth::user();
    }

    /**
     * Get admin ID
     */
    protected function adminId(): ?int
    {
        return Auth::id();
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }
        return $this->admin()?->role === 'admin' || $this->isSuperAdmin();
    }

    /**
     * Check if current user is super admin
     */
    protected function isSuperAdmin(): bool
    {
        return $this->admin()?->role === 'super_admin';
    }

    /**
     * Check if user has specific role
     */
    protected function hasRole(string $role): bool
    {
        return $this->admin()?->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    protected function hasAnyRole(array $roles): bool
    {
        return in_array($this->admin()?->role, $roles);
    }

    /**
     * Check if user has permission (Spatie Permission)
     */
    protected function hasPermission(string $permission): bool
    {
        if (!$this->admin()) {
            return false;
        }

        if (method_exists($this->admin(), 'hasPermissionTo')) {
            return $this->admin()->hasPermissionTo($permission);
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    protected function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all given permissions
     */
    protected function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check permission - shows error if not authorized
     * (Renamed from authorize() to avoid conflict with Livewire's authorize method)
     */
    protected function checkPermission(string $permission): bool
    {
        if (!$this->hasPermission($permission)) {
            $this->toast('error', 'You do not have permission to perform this action.');
            return false;
        }
        return true;
    }

    /**
     * Alias for checkPermission
     */
    protected function can(string $permission): bool
    {
        return $this->checkPermission($permission);
    }

    /**
     * Abort if no permission
     */
    protected function abortIfNoPermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Messages & Toasts
    |--------------------------------------------------------------------------
    */

    /**
     * Flash success message
     */
    protected function success(string $message): void
    {
        session()->flash('success', $message);
    }

    /**
     * Flash error message
     */
    protected function error(string $message): void
    {
        session()->flash('error', $message);
    }

    /**
     * Flash warning message
     */
    protected function warning(string $message): void
    {
        session()->flash('warning', $message);
    }

    /**
     * Flash info message
     */
    protected function info(string $message): void
    {
        session()->flash('info', $message);
    }

    /**
     * Dispatch toast notification (for Alpine.js toast)
     */
    protected function toast(string $type, string $message, string $title = null): void
    {
        $this->dispatch('toast', [
            'type' => $type,
            'message' => $message,
            'title' => $title ?? ucfirst($type),
        ]);
    }

    /**
     * Toast success
     */
    protected function toastSuccess(string $message, string $title = 'Success'): void
    {
        $this->toast('success', $message, $title);
    }

    /**
     * Toast error
     */
    protected function toastError(string $message, string $title = 'Error'): void
    {
        $this->toast('error', $message, $title);
    }

    /**
     * Toast warning
     */
    protected function toastWarning(string $message, string $title = 'Warning'): void
    {
        $this->toast('warning', $message, $title);
    }

    /**
     * Toast info
     */
    protected function toastInfo(string $message, string $title = 'Info'): void
    {
        $this->toast('info', $message, $title);
    }

    /**
     * Dispatch browser event
     */
    protected function dispatchEvent(string $event, array $data = []): void
    {
        $this->dispatch($event, $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Modal Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Open modal
     */
    protected function openModal(string $modal = 'showModal'): void
    {
        $this->{$modal} = true;
    }

    /**
     * Close modal
     */
    protected function closeModal(string $modal = 'showModal'): void
    {
        $this->{$modal} = false;
    }

    /**
     * Toggle modal
     */
    protected function toggleModal(string $modal = 'showModal'): void
    {
        $this->{$modal} = !$this->{$modal};
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
        if (!$file) {
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
        if (!$image) {
            return null;
        }

        // Validate image type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($image->getMimeType(), $allowedMimes)) {
            $this->addError('image', 'Invalid image type.');
            return null;
        }

        return $this->uploadFile($image, $directory, $disk, $filename);
    }

    /**
     * Upload multiple files
     */
    protected function uploadMultiple(
        array $files,
        string $directory = 'uploads',
        string $disk = 'public'
    ): array {
        $paths = [];
        foreach ($files as $file) {
            $path = $this->uploadFile($file, $directory, $disk);
            if ($path) {
                $paths[] = $path;
            }
        }
        return $paths;
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
     * Delete multiple files
     */
    protected function deleteFiles(array $paths, string $disk = 'public'): void
    {
        foreach ($paths as $path) {
            $this->deleteFile($path, $disk);
        }
    }

    /**
     * Get file URL
     */
    protected function fileUrl(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }

    /**
     * Check if file exists
     */
    protected function fileExists(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Validate single field
     */
    protected function validateField(string $field): void
    {
        $this->validateOnly($field);
    }

    /**
     * Add custom validation error
     */
    protected function addValidationError(string $field, string $message): void
    {
        $this->addError($field, $message);
    }

    /**
     * Clear specific validation error
     */
    protected function clearError(string $field): void
    {
        $this->resetErrorBag($field);
    }

    /**
     * Clear all validation errors
     */
    protected function clearErrors(): void
    {
        $this->resetErrorBag();
    }

    /**
     * Check if has validation errors
     */
    protected function hasErrors(): bool
    {
        return $this->getErrorBag()->isNotEmpty();
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
     * Put in cache
     */
    protected function putCache(string $key, $value, int $minutes = 60): void
    {
        Cache::put($key, $value, now()->addMinutes($minutes));
    }

    /**
     * Get from cache
     */
    protected function getCache(string $key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * Forget cache key
     */
    protected function forgetCache(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Clear multiple cache keys
     */
    protected function clearCache(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
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
            'admin_id' => $this->adminId(),
            'admin_email' => $this->admin()?->email,
            'ip' => request()->ip(),
            'component' => static::class,
        ], $data));
    }

    /**
     * Log error
     */
    protected function logError(string $message, \Throwable $e = null): void
    {
        Log::error('[Admin Error] ' . $message, [
            'admin_id' => $this->adminId(),
            'component' => static::class,
            'error' => $e?->getMessage(),
            'trace' => $e?->getTraceAsString(),
        ]);
    }

    /**
     * Log warning
     */
    protected function logWarning(string $message, array $data = []): void
    {
        Log::warning('[Admin Warning] ' . $message, array_merge([
            'admin_id' => $this->adminId(),
            'component' => static::class,
        ], $data));
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Generate unique slug
     */
    protected function generateSlug(string $text, string $model, string $column = 'slug', ?int $exceptId = null): string
    {
        $slug = Str::slug($text);
        $originalSlug = $slug;
        $count = 1;

        $query = $model::where($column, $slug);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = $model::where($column, $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
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
     * Format date
     */
    protected function formatDate($date, string $format = 'M d, Y'): string
    {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Format datetime
     */
    protected function formatDateTime($date, string $format = 'M d, Y h:i A'): string
    {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Get client IP
     */
    protected function clientIp(): string
    {
        return request()->ip();
    }

    /**
     * Generate random string
     */
    protected function randomString(int $length = 16): string
    {
        return Str::random($length);
    }

    /**
     * Generate UUID
     */
    protected function uuid(): string
    {
        return Str::uuid()->toString();
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Set pagination limit
     */
    protected function setPerPage(int $perPage): void
    {
        $this->perPage = min($perPage, 100);
    }

    /**
     * Get pagination limit
     */
    protected function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Reset pagination to first page
     */
    protected function resetPagination(): void
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | Confirmation Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Request confirmation before action
     */
    protected function confirm(string $message, string $action, $params = null): void
    {
        $this->dispatch('confirm', [
            'message' => $message,
            'action' => $action,
            'params' => $params,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search & Filter Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Apply search to query
     */
    protected function applySearch($query, string $search, array $columns)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * Apply date range filter
     */
    protected function applyDateRange($query, ?string $from, ?string $to, string $column = 'created_at')
    {
        if ($from) {
            $query->whereDate($column, '>=', $from);
        }
        if ($to) {
            $query->whereDate($column, '<=', $to);
        }
        return $query;
    }

    /**
     * Apply sorting
     */
    protected function applySorting($query, string $sortBy, string $sortDirection = 'asc')
    {
        return $query->orderBy($sortBy, $sortDirection);
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Reset form fields
     */
    protected function resetForm(array $fields = []): void
    {
        if (empty($fields)) {
            $this->reset();
        } else {
            $this->reset($fields);
        }
        $this->clearErrors();
    }

    /**
     * Reset specific properties
     */
    protected function resetProperties(array $properties): void
    {
        $this->reset($properties);
    }

    /*
    |--------------------------------------------------------------------------
    | Render Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Render view with layout
     */
    protected function renderView(string $view, array $data = [])
    {
        return view($view, $data)->layout($this->layout);
    }

    /**
     * Render admin view
     */
    protected function adminView(string $view, array $data = [])
    {
        return $this->renderView('admin.' . $view, $data);
    }

    /**
 * Define sidebar menu for this module
 * Override in child components to define menu
 * 
 * @return array|null
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
        // Parent with submenu
        $activeClass = $isActive ? 'active open' : '';
        $html .= '<div class="nav-item ' . $activeClass . '" onclick="this.classList.toggle(\'open\'); this.nextElementSibling.classList.toggle(\'open\');" style="cursor: pointer;">';
        $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
        $html .= '<span>' . e($menu['title']) . '</span>';
        $html .= '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
        $html .= '</div>';

        // Submenu
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
        // Single item
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