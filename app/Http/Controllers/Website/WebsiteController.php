<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class WebsiteController extends Controller
{
    // ==================== AUTH: LOGIN ====================
    
    /**
     * Show login form
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('ecommerce.account');
        }
        
        // Store intended URL for redirect after login
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        
        return view('website::ecommerce.auth.login');
    }
    
    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
        
        // Rate limiting
        $key = Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => __('Too many attempts. Try again in :seconds seconds.', ['seconds' => $seconds]),
            ]);
        }
        
        // Find user
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            RateLimiter::hit($key);
            throw ValidationException::withMessages([
                'email' => __('No account found with this email.'),
            ]);
        }
        
        // Check if active (if method exists)
        if (method_exists($user, 'isActive') && !$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => __('Your account is not active. Please contact support.'),
            ]);
        }
        
        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            $request->session()->put('user_type', 'customer');
            
            // Redirect to intended or account
            $intended = session('url.intended');
            if ($intended && !str_contains($intended, 'login') && !str_contains($intended, 'register')) {
                session()->forget('url.intended');
                return redirect($intended)->with('success', 'Welcome back!');
            }
            
            return redirect()->route('ecommerce.account')->with('success', 'Welcome back!');
        }
        
        RateLimiter::hit($key);
        throw ValidationException::withMessages([
            'password' => __('The provided password is incorrect.'),
        ]);
    }
    
    // ==================== AUTH: REGISTER ====================
    
    /**
     * Show registration form
     */
    public function showRegisterForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('ecommerce.account');
        }
        
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        
        return view('website::ecommerce.auth.register');
    }
    
    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);
        
        event(new Registered($user));
        
        Auth::login($user);
        $request->session()->put('user_type', 'customer');
        
        // Redirect to intended or account
        $intended = session('url.intended');
        if ($intended && !str_contains($intended, 'login') && !str_contains($intended, 'register')) {
            session()->forget('url.intended');
            return redirect($intended)->with('success', 'Account created successfully!');
        }
        
        return redirect()->route('ecommerce.account')->with('success', 'Account created successfully!');
    }
    
    // ==================== AUTH: LOGOUT ====================
    
    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('ecommerce.home')->with('success', 'Logged out successfully.');
    }
    
    // ==================== AUTH: FORGOT PASSWORD ====================
    
    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('website::ecommerce.auth.forgot-password');
    }
    
    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        
        $status = Password::sendResetLink($request->only('email'));
        
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
    
    // ==================== AUTH: RESET PASSWORD ====================
    
    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('website::ecommerce.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }
    
    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                
                event(new PasswordReset($user));
            }
        );
        
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('ecommerce.login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
    
    // ==================== CUSTOMER ACCOUNT ====================
    
    /**
     * Customer account dashboard
     */
    public function account()
    {
        $user = Auth::user();
        return view('website::ecommerce.account.dashboard', compact('user'));
    }
    
    /**
     * Customer orders
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        return view('website::ecommerce.account.orders', compact('user', 'orders'));
    }
    
    /**
     * Customer profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('website::ecommerce.account.profile', compact('user'));
    }
    
    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
        
        $user->update($request->only('name', 'email', 'phone'));
        
        return back()->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Password changed successfully!');
    }
    
    /**
     * Customer addresses
     */
    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses ?? collect();
        return view('website::ecommerce.account.addresses', compact('user', 'addresses'));
    }
    
    /**
     * Customer wishlist
     */
    public function wishlist()
    {
        $user = Auth::user();
        $wishlist = $user->wishlist ?? collect();
        return view('website::ecommerce.account.wishlist', compact('user', 'wishlist'));
    }
}