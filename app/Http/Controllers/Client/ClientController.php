<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    
    protected ?User $client = null;
    protected int $perPage = 10;
    protected array $viewData = [];
    protected string $layout = 'components.layouts.guest';

    /*
    |--------------------------------------------------------------------------
    | Constructor - Authentication happens here
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        // Initialize client after middleware
        $this->middleware(function ($request, $next) {
            
            // Check if admin is logged in (redirect to admin dashboard)
            if (Auth::guard('admin')->check()) {
                return $this->handleAdminUser($request);
            }

            // Check if client is authenticated (web guard)
            if (!Auth::guard('web')->check()) {
                return $this->handleUnauthenticated($request);
            }

            $user = Auth::guard('web')->user();

            // Check if client account is active
            if (!$user->isActive()) {
                return $this->handleInactiveClient($request, $user);
            }

            // Initialize client user
            $this->client = $user;
            $this->shareViewData();

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
                'redirect' => route('client.login')
            ], 401);
        }

        return redirect()->route('client.login')
            ->with('error', 'Please login to continue.');
    }

    /**
     * Handle admin user trying to access client area
     */
    protected function handleAdminUser($request)
    {
        Log::info('[Client Area - Admin Redirect]', [
            'admin_id' => Auth::guard('admin')->id(),
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Please use the admin panel.',
                'redirect' => route('admin.dashboard')
            ], 403);
        }

        return redirect()->route('admin.dashboard')
            ->with('info', 'You are logged in as admin. Use admin panel or logout first.');
    }

    /**
     * Handle inactive client account
     */
    protected function handleInactiveClient($request, $user)
    {
        Log::warning('[Client Inactive Account Access]', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        // Logout from web guard only
        Auth::guard('web')->logout();
        
        // Regenerate session (don't invalidate - preserves admin session)
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact support.',
            ], 403);
        }

        return redirect()->route('client.login')
            ->with('error', 'Your account is not active. Please contact support.');
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
            'client' => $this->client,
            'clientName' => $this->client?->name,
            'clientEmail' => $this->client?->email,
            'isVerified' => $this->client?->hasVerifiedEmail() ?? false,
        ];

        view()->share($this->viewData);
    }

    /*
    |--------------------------------------------------------------------------
    | Client Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get current authenticated client
     */
    protected function client(): ?User
    {
        return $this->client ?? Auth::guard('web')->user();
    }

    /**
     * Get client ID
     */
    protected function clientId(): ?int
    {
        return $this->client()?->id;
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return Auth::guard('web')->check();
    }

    /**
     * Check if client email is verified
     */
    protected function isEmailVerified(): bool
    {
        return $this->client()?->hasVerifiedEmail() ?? false;
    }

    /**
     * Require email verification
     */
    protected function requireVerification()
    {
        if (!$this->isEmailVerified()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email address.',
                    'redirect' => route('client.verification.notice')
                ], 403);
            }

            return redirect()->route('client.verification.notice')
                ->with('warning', 'Please verify your email address to continue.');
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Response Helpers (keeping all existing methods...)
    |--------------------------------------------------------------------------
    */

    protected function successResponse(string $message, $data = null, int $code = 200)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) $response['data'] = $data;
        return response()->json($response, $code);
    }

    protected function errorResponse(string $message, $errors = null, int $code = 400)
    {
        $response = ['success' => false, 'message' => $message];
        if ($errors !== null) $response['errors'] = $errors;
        return response()->json($response, $code);
    }

    protected function redirectWithSuccess(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    protected function redirectWithError(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('error', $message);
    }

    protected function backWithSuccess(string $message)
    {
        return back()->with('success', $message);
    }

    protected function backWithError(string $message)
    {
        return back()->with('error', $message);
    }

    protected function backWithErrors(array $errors)
    {
        return back()->withErrors($errors)->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | View Helpers
    |--------------------------------------------------------------------------
    */

    protected function view(string $view, array $data = [])
    {
        return view($view, array_merge($this->viewData, $data))
            ->layout($this->layout);
    }

    protected function clientView(string $view, array $data = [])
    {
        return $this->view('client.' . $view, $data);
    }

    protected function with(string $key, $value): self
    {
        $this->viewData[$key] = $value;
        return $this;
    }

    protected function withData(array $data): self
    {
        $this->viewData = array_merge($this->viewData, $data);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Helpers (keeping all existing...)
    |--------------------------------------------------------------------------
    */

    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        return $request->validate($rules, $messages);
    }

    protected function uploadFile($file, string $directory = 'client-uploads', string $disk = 'public', ?string $filename = null): ?string
    {
        if (!$file || !$file->isValid()) return null;
        $clientPath = $directory . '/' . $this->clientId();
        $filename = $filename ?? Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($clientPath, $filename, $disk);
    }

    protected function deleteFile(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    protected function cache(string $key, int $minutes, callable $callback)
    {
        $scopedKey = 'client_' . $this->clientId() . '_' . $key;
        return Cache::remember($scopedKey, now()->addMinutes($minutes), $callback);
    }

    protected function logAction(string $action, array $data = []): void
    {
        Log::info('[Client Action] ' . $action, array_merge([
            'client_id' => $this->clientId(),
            'client_email' => $this->client()?->email,
            'ip' => request()->ip(),
        ], $data));
    }

    protected function logError(string $message, \Throwable $e): void
    {
        Log::error('[Client Error] ' . $message, [
            'client_id' => $this->clientId(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    protected function formatMoney(float $amount, string $currency = '₹'): string
    {
        return $currency . number_format($amount, 2);
    }

    protected function belongsToClient($model, string $foreignKey = 'user_id'): bool
    {
        if (!$model) return false;
        return $model->{$foreignKey} === $this->clientId();
    }

    protected function abortIfNotOwner($model, string $foreignKey = 'user_id', string $message = 'Access denied.'): void
    {
        if (!$this->belongsToClient($model, $foreignKey)) {
            abort(403, $message);
        }
    }

    protected function scopeToClient($query, string $foreignKey = 'user_id')
    {
        return $query->where($foreignKey, $this->clientId());
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard & Core Methods
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        return view('client.dashboard', [
            'user' => $this->client,
        ]);
    }

    public function profile()
    {
        return view('client.profile', [
            'user' => $this->client,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $this->client->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $this->logAction('Updated profile');

        return $this->backWithSuccess('Profile updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        if ($this->client->avatar) {
            $this->deleteFile($this->client->avatar);
        }

        $path = $this->uploadFile($request->file('avatar'), 'avatars');
        $this->client->update(['avatar' => $path]);
        $this->logAction('Updated avatar');

        return $this->backWithSuccess('Avatar updated successfully.');
    }

    public function logout(Request $request)
    {
        $this->logAction('Client logged out');

        Auth::guard('web')->logout();
        $request->session()->regenerate();
        
        return redirect()->route('client.login')->with('success', 'Logged out successfully.');
    }

    public static function menu(): ?array
    {
        return null;
    }


/**
     * Render menu HTML for sidebar
     * 
     * Usage in module's client-menu.blade.php:
     * {!! \App\Http\Controllers\Client\ClientController::renderMenu([
     *     'title' => 'My Orders',
     *     'icon' => 'cart',
     *     'route' => 'client.orders.*',
     *     'children' => [
     *         ['title' => 'All Orders', 'route' => 'client.orders.index', 'icon' => 'list'],
     *     ]
     * ]) !!}
     */
    public static function renderMenu(array $menu): string
    {
        $icons = [
            'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
            'plus' => 'M12 4v16m8-8H4',
            'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 4 0 014 0z',
            'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'receipt' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
            'support' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
            'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            'download' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
            'eye' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            'chat' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'file-text' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'key' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
            'logout' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
            'check' => 'M5 13l4 4L19 7',
            'x' => 'M6 18L18 6M6 6l12 12',
            'search' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'filter' => 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z',
            'edit' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'trash' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        ];

        $routePrefix = $menu['route'] ?? '';
        $isActive = $routePrefix && request()->routeIs($routePrefix);
        $hasChildren = !empty($menu['children']);
        $iconPath = $icons[$menu['icon'] ?? 'folder'] ?? $icons['folder'];

        $html = '';

        if ($hasChildren) {
            $activeClass = $isActive ? 'active open' : '';
            $html .= '<div class="nav-item ' . $activeClass . '" onclick="toggleSubmenu(this)" style="cursor: pointer;">';
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