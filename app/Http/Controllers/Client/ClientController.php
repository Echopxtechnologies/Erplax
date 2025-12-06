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
    protected string $layout = 'components.layouts.client';

    /*
    |--------------------------------------------------------------------------
    | Constructor - Authentication happens here
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        // Apply auth middleware
        $this->middleware(['auth']);
        
        // Initialize client and check access after auth middleware
        $this->middleware(function ($request, $next) {
            
            // Check if user is authenticated
            if (!Auth::check()) {
                return $this->handleUnauthenticated($request);
            }

            $user = Auth::user();

            // Check if user is admin (admins should use admin panel)
            if ($this->isAdminUser($user)) {
                return $this->handleAdminUser($request, $user);
            }

            // Check if client account is active
            if (!$this->isClientActive($user)) {
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
    protected function handleAdminUser($request, $user)
    {
        Log::info('[Client Area - Admin Redirect]', [
            'user_id' => $user->id,
            'email' => $user->email,
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
            ->with('info', 'Redirected to admin panel.');
    }

    /**
     * Handle inactive client account
     */
    protected function handleInactiveClient($request, $user)
    {
        Log::warning('[Client Inactive Account Access]', [
            'user_id' => $user->id,
            'email' => $user->email,
            'status' => $user->status ?? 'unknown',
            'ip' => $request->ip(),
        ]);

        // Logout the user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact support.',
            ], 403);
        }

        return redirect()->route('client.login')
            ->with('error', 'Your account is not active. Please contact support.');
    }

    /**
     * Check if user is an admin user
     */
    protected function isAdminUser($user): bool
    {
        if (!$user) {
            return false;
        }

        // Check is_admin flag
        if ($user->is_admin) {
            return true;
        }

        // Check if user has roles (Spatie) - admin users have roles
        if (method_exists($user, 'roles') && $user->roles->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if client account is active
     */
    protected function isClientActive($user): bool
    {
        if (!$user) {
            return false;
        }

        // If status field exists, check it
        if (isset($user->status)) {
            return $user->status === 'active';
        }

        // If is_active field exists
        if (isset($user->is_active)) {
            return (bool) $user->is_active;
        }

        // Default to active if no status field
        return true;
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
        return $this->client ?? Auth::user();
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
        return Auth::check();
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
     * Render client view with layout
     */
    protected function view(string $view, array $data = [])
    {
        return view($view, array_merge($this->viewData, $data))
            ->layout($this->layout);
    }

    /**
     * Render client view (alias)
     */
    protected function clientView(string $view, array $data = [])
    {
        return $this->view('client.' . $view, $data);
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
        string $directory = 'client-uploads',
        string $disk = 'public',
        ?string $filename = null
    ): ?string {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Include client ID in path for organization
        $clientPath = $directory . '/' . $this->clientId();
        $filename = $filename ?? Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($clientPath, $filename, $disk);
    }

    /**
     * Upload image with validation
     */
    protected function uploadImage(
        $image,
        string $directory = 'client-images',
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
     * Get from cache or execute callback (scoped to client)
     */
    protected function cache(string $key, int $minutes, callable $callback)
    {
        $scopedKey = 'client_' . $this->clientId() . '_' . $key;
        return Cache::remember($scopedKey, now()->addMinutes($minutes), $callback);
    }

    /**
     * Forget cache key (scoped to client)
     */
    protected function forgetCache(string $key): bool
    {
        $scopedKey = 'client_' . $this->clientId() . '_' . $key;
        return Cache::forget($scopedKey);
    }

    /*
    |--------------------------------------------------------------------------
    | Logging Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Log client action
     */
    protected function logAction(string $action, array $data = []): void
    {
        Log::info('[Client Action] ' . $action, array_merge([
            'client_id' => $this->clientId(),
            'client_email' => $this->client()?->email,
            'ip' => request()->ip(),
        ], $data));
    }

    /**
     * Log error
     */
    protected function logError(string $message, \Throwable $e): void
    {
        Log::error('[Client Error] ' . $message, [
            'client_id' => $this->clientId(),
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
    | Ownership Helpers - Ensure client can only access their own data
    |--------------------------------------------------------------------------
    */

    /**
     * Check if resource belongs to current client
     */
    protected function belongsToClient($model, string $foreignKey = 'user_id'): bool
    {
        if (!$model) {
            return false;
        }

        return $model->{$foreignKey} === $this->clientId();
    }

    /**
     * Abort if resource doesn't belong to client
     */
    protected function abortIfNotOwner($model, string $foreignKey = 'user_id', string $message = 'Access denied.'): void
    {
        if (!$this->belongsToClient($model, $foreignKey)) {
            if (request()->expectsJson()) {
                abort(response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403));
            }
            abort(403, $message);
        }
    }

    /**
     * Scope query to current client
     */
    protected function scopeToClient($query, string $foreignKey = 'user_id')
    {
        return $query->where($foreignKey, $this->clientId());
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard & Core Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Client Dashboard
     */
    public function index(Request $request)
    {
        return view('client.dashboard', [
            'user' => $this->client,
        ]);
    }

    /**
     * Client Profile
     */
    public function profile()
    {
        return view('client.profile', [
            'user' => $this->client,
        ]);
    }

    /**
     * Update Profile
     */
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

    /**
     * Update Avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        // Delete old avatar
        if ($this->client->avatar) {
            $this->deleteFile($this->client->avatar);
        }

        // Upload new avatar
        $path = $this->uploadImage($request->file('avatar'), 'avatars');

        $this->client->update(['avatar' => $path]);

        $this->logAction('Updated avatar');

        return $this->backWithSuccess('Avatar updated successfully.');
    }

    /**
     * Client Logout
     */
    public function logout(Request $request)
    {
        $this->logAction('Client logged out');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('client.login')->with('success', 'Logged out successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration - Override in child classes for client modules
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get menu configuration
     */
    public static function menu(): ?array
    {
        return null;
    }
}