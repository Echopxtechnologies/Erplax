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
}