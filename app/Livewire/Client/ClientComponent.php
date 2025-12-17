<?php

namespace App\Livewire\Client;

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

abstract class ClientComponent extends Component
{
    use WithPagination, WithFileUploads;

    protected int $perPage = 10;
    protected string $paginationTheme = 'tailwind';

    public function boot()
    {
        $this->checkClientAuth();
    }

    public function mount()
    {
        $this->checkClientAuth();
        $this->init();
    }

    protected function init(): void {}

    protected function checkClientAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect(route('client.login'));
            return;
        }

        $client = $this->client();
        if ($client && !$client->isActive()) {
            Auth::guard('web')->logout();
            session()->regenerate();
            session()->flash('error', 'Your account is not active.');
            $this->redirect(route('client.login'));
        }
    }

    protected function client(): ?User
    {
        return Auth::guard('web')->user();
    }

    protected function clientId(): ?int
    {
        return Auth::guard('web')->id();
    }

    protected function isAuthenticated(): bool
    {
        return Auth::guard('web')->check();
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Messages & Toasts
    |--------------------------------------------------------------------------
    */

    protected function success(string $message): void
    {
        session()->flash('success', $message);
    }

    protected function error(string $message): void
    {
        session()->flash('error', $message);
    }

    protected function warning(string $message): void
    {
        session()->flash('warning', $message);
    }

    protected function info(string $message): void
    {
        session()->flash('info', $message);
    }

    protected function toast(string $type, string $message, string $title = null): void
    {
        $this->dispatch('toast', [
            'type' => $type,
            'message' => $message,
            'title' => $title ?? ucfirst($type),
        ]);
    }

    protected function toastSuccess(string $message, string $title = 'Success'): void
    {
        $this->toast('success', $message, $title);
    }

    protected function toastError(string $message, string $title = 'Error'): void
    {
        $this->toast('error', $message, $title);
    }

    /*
    |--------------------------------------------------------------------------
    | Modal Helpers
    |--------------------------------------------------------------------------
    */

    protected function openModal(string $modal = 'showModal'): void
    {
        $this->{$modal} = true;
    }

    protected function closeModal(string $modal = 'showModal'): void
    {
        $this->{$modal} = false;
    }

    /*
    |--------------------------------------------------------------------------
    | File Upload Helpers
    |--------------------------------------------------------------------------
    */

    protected function uploadFile($file, string $directory = 'client-uploads', string $disk = 'public', ?string $filename = null): ?string
    {
        if (!$file) return null;
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

    /*
    |--------------------------------------------------------------------------
    | Database & Cache Helpers
    |--------------------------------------------------------------------------
    */

    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }

    protected function cache(string $key, int $minutes, callable $callback)
    {
        $scopedKey = 'client_' . $this->clientId() . '_' . $key;
        return Cache::remember($scopedKey, now()->addMinutes($minutes), $callback);
    }

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

    protected function logAction(string $action, array $data = []): void
    {
        Log::info('[Client Action] ' . $action, array_merge([
            'client_id' => $this->clientId(),
            'client_email' => $this->client()?->email,
            'ip' => request()->ip(),
            'component' => static::class,
        ], $data));
    }

    protected function logError(string $message, \Throwable $e = null): void
    {
        Log::error('[Client Error] ' . $message, [
            'client_id' => $this->clientId(),
            'component' => static::class,
            'error' => $e?->getMessage(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership Helpers
    |--------------------------------------------------------------------------
    */

    protected function belongsToClient($model, string $foreignKey = 'user_id'): bool
    {
        if (!$model) return false;
        return $model->{$foreignKey} === $this->clientId();
    }

    protected function abortIfNotOwner($model, string $foreignKey = 'user_id'): void
    {
        if (!$this->belongsToClient($model, $foreignKey)) {
            abort(403, 'Access denied.');
        }
    }

    protected function scopeToClient($query, string $foreignKey = 'user_id')
    {
        return $query->where($foreignKey, $this->clientId());
    }

    /*
    |--------------------------------------------------------------------------
    | Reset & Pagination Helpers
    |--------------------------------------------------------------------------
    */

    protected function resetForm(array $fields = []): void
    {
        if (empty($fields)) {
            $this->reset();
        } else {
            $this->reset($fields);
        }
        $this->resetErrorBag();
    }

    protected function setPerPage(int $perPage): void
    {
        $this->perPage = min($perPage, 100);
    }

    /*
    |--------------------------------------------------------------------------
    | View Helpers
    |--------------------------------------------------------------------------
    */

    protected function clientView(string $view, array $data = [])
    {
        return view($view, $data)->layout('layouts.guest');
    }

    /*
    |--------------------------------------------------------------------------
    | Menu - Uses ClientController's renderMenu
    |--------------------------------------------------------------------------
    */

    public static function menu(): ?array
    {
        return null;
    }

    public static function renderMenu(array $menu): string
    {
        return \App\Http\Controllers\Client\ClientController::renderMenu($menu);
    }
}