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
    protected string $layout = 'components.layouts.guest-wrap';

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
    | AUTOMATIC LAYOUT WRAPPING - The Magic!
    |--------------------------------------------------------------------------
    */

    /**
     * Execute an action on the controller.
     * This intercepts ALL controller method responses and wraps Views with layout
     */
    public function callAction($method, $parameters)
    {
        $response = parent::callAction($method, $parameters);

        // If response is a View, wrap it with layout automatically
        if ($response instanceof \Illuminate\View\View) {
            $content = $response->render();
            return view($this->layout, ['slot' => $content]);
        }

        // For JSON, redirects, etc. - return as-is
        return $response;
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
    | Response Helpers
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

    /**
     * Render view (layout handled automatically by callAction)
     */
    protected function view(string $view, array $data = [])
    {
        return view($view, array_merge($this->viewData, $data));
    }

    /**
     * Render client view with client. prefix
     */
    protected function clientView(string $view, array $data = [])
    {
        return $this->view('client.' . $view, $data);
    }

    /**
     * Render module view (layout handled automatically by callAction)
     * Just use: return view('modulename::client.index', $data);
     * Or use this helper for consistency
     */
    protected function moduleView(string $view, array $data = [])
    {
        return view($view, array_merge($this->viewData, $data));
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
    | Utility Helpers
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
        $client = $this->client();
        
        $stats = [
            'total_invoices' => 0,
            'unpaid' => 0,
            'paid' => 0,
            'amount_due' => 0,
        ];
        
        $invoices = collect();
        $customer = null;
        
        // Find customer by email (matching logged-in user's email)
        if (class_exists(\App\Models\Customer::class) && $client->email) {
            $customer = \App\Models\Customer::where('email', $client->email)->first();
            
            if ($customer && class_exists(\App\Models\Invoice::class)) {
                try {
                    $baseQuery = \App\Models\Invoice::where('customer_id', $customer->id);
                    
                    $stats = [
                        'total_invoices' => (clone $baseQuery)->count(),
                        'unpaid' => (clone $baseQuery)->where('payment_status', 'unpaid')->count(),
                        'paid' => (clone $baseQuery)->where('payment_status', 'paid')->count(),
                        'amount_due' => (clone $baseQuery)->sum('amount_due'),
                    ];
                    
                    $invoices = \App\Models\Invoice::where('customer_id', $customer->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                } catch (\Exception $e) {
                    // Handle error silently
                }
            }
        }
        
        return view('client.dashboard', compact('stats', 'invoices', 'customer'));
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

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */

    public static function menu(): ?array
    {
        return null;
    }

    /**
     * Render menu HTML for client navbar
     * 
     * Usage in module's client-navbar.blade.php:
     * {!! \App\Http\Controllers\Client\ClientController::renderMenu([
     *     'title' => 'My Orders',
     *     'icon' => 'cart',
     *     'route' => 'client.orders.index',
     * ]) !!}
     * 
     * Or use helper: {!! client_menu([...]) !!}
     */
    public static function renderMenu(array $menu): string
    {
        $icons = [
            'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'clipboard-check' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'clipboard-list' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
            'plus' => 'M12 4v16m8-8H4',
            'file' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'file-text' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'shopping-cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
            'cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
            'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            'package' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'cube' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'mail' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            'download' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
            'eye' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            'heart' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
            'star' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'tag' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'ticket' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
            'support' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
            'receipt' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
            'chat' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'key' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
            'logout' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
            'check' => 'M5 13l4 4L19 7',
            'x' => 'M6 18L18 6M6 6l12 12',
            'search' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'filter' => 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z',
            'edit' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'trash' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        ];

        $title = $menu['title'] ?? 'Menu';
        $icon = $menu['icon'] ?? 'folder';
        $route = $menu['route'] ?? '#';
        $children = $menu['children'] ?? [];
        $hasChildren = !empty($children);

        $iconPath = $icons[$icon] ?? $icons['folder'];
        $isActive = $route !== '#' && request()->routeIs($route);

        $html = '';

        if ($hasChildren) {
            // Dropdown menu for navbar
            $activeClass = $isActive ? 'active open' : '';
            $html .= '<div class="nav-dropdown ' . $activeClass . '">';
            $html .= '<button class="nav-dropdown-toggle ' . ($isActive ? 'active' : '') . '" onclick="toggleNavDropdown(this.parentElement, event)">';
            $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
            $html .= '<span>' . e($title) . '</span>';
            $html .= '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
            $html .= '</button>';
            $html .= '<div class="nav-dropdown-menu">';
            
            foreach ($children as $child) {
                $childRoute = $child['route'] ?? '#';
                $childIcon = $child['icon'] ?? 'document';
                $childIconPath = $icons[$childIcon] ?? $icons['document'];
                $childActive = $childRoute !== '#' && request()->routeIs($childRoute);
                $childHref = $childRoute !== '#' && \Route::has($childRoute) ? route($childRoute) : '#';
                
                $html .= '<a href="' . $childHref . '" class="nav-dropdown-item ' . ($childActive ? 'active' : '') . '">';
                $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $childIconPath . '"></path></svg>';
                $html .= '<span>' . e($child['title'] ?? 'Item') . '</span>';
                $html .= '</a>';
            }
            
            $html .= '</div>';
            $html .= '</div>';
        } else {
            // Simple nav link
            $href = $route !== '#' && \Route::has(str_replace('*', 'index', $route)) 
                ? route(str_replace('*', 'index', $route)) 
                : ($route !== '#' && \Route::has($route) ? route($route) : '#');
            
            $html .= '<a href="' . $href . '" class="nav-link ' . ($isActive ? 'active' : '') . '">';
            $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
            $html .= '<span>' . e($title) . '</span>';
            $html .= '</a>';
        }

        return $html;
    }

    /**
     * Render mobile menu HTML
     * 
     * Usage: {!! \App\Http\Controllers\Client\ClientController::renderMobileMenu([...]) !!}
     * Or helper: {!! client_mobile_menu([...]) !!}
     */
    public static function renderMobileMenu(array $menu): string
    {
        $icons = [
            'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
            'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
            'shopping-cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
            'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ];

        $title = $menu['title'] ?? 'Menu';
        $icon = $menu['icon'] ?? 'folder';
        $route = $menu['route'] ?? '#';

        $iconPath = $icons[$icon] ?? $icons['folder'];
        $isActive = $route !== '#' && request()->routeIs($route);
        $href = $route !== '#' && \Route::has($route) ? route($route) : '#';

        $html = '<a href="' . $href . '" class="mobile-nav-link ' . ($isActive ? 'active' : '') . '">';
        $html .= '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="' . $iconPath . '"></path></svg>';
        $html .= '<span>' . e($title) . '</span>';
        $html .= '</a>';

        return $html;
    }
}