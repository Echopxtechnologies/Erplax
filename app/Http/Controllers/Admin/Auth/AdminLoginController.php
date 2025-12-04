<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check using Spatie's hasRole or is_admin flag
            if ($user->is_admin || $user->hasRole('admin') || $user->hasRole('super-admin')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('livewire.admin.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find the user first
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email.',
            ]);
        }

        // Check if user is admin using Spatie's hasRole or is_admin flag
        if (!$this->isAdminUser($user)) {
            throw ValidationException::withMessages([
                'email' => 'You do not have admin access.',
            ]);
        }

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Regenerate session
            $request->session()->regenerate();

            // Redirect to admin dashboard
            return redirect()->route('admin.dashboard');
        }

        // Wrong password
        throw ValidationException::withMessages([
            'password' => 'Incorrect password.',
        ]);
    }

    /**
     * Check if user has admin access
     */
    protected function isAdminUser(User $user): bool
    {
        // Check is_admin flag first
        if ($user->is_admin) {
            return true;
        }

        // Check using Spatie's hasRole method
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'super-admin', 'super_admin']);
        }

        // Check using Spatie's hasAnyRole method
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole(['admin', 'super-admin', 'super_admin']);
        }

        return false;
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}