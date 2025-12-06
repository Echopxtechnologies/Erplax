<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new #[Layout('components.layouts.empty')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    @livewireStyles
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e1e5eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .error-message {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 6px;
        }

        .session-status {
            background: #c6f6d5;
            color: #276749;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .remember-row label {
            color: #555;
            font-size: 14px;
            cursor: pointer;
        }

        .actions-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .forgot-link {
            color: #667eea;
            font-size: 14px;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please sign in to your account</p>
        </div>

        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    wire:model="form.email" 
                    id="email" 
                    type="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required 
                    autofocus 
                    autocomplete="username"
                >
                @error('form.email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    wire:model="form.password" 
                    id="password" 
                    type="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required 
                    autocomplete="current-password"
                >
                @error('form.password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    name="remember"
                >
                <label for="remember">Remember me</label>
            </div>

            <div class="actions-row">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}" wire:navigate>
                        Forgot password?
                    </a>
                @endif

                <button type="submit" class="btn-login" wire:loading.attr="disabled">
                    <span wire:loading.remove>Log In</span>
                    <span wire:loading>Please wait...</span>
                </button>
            </div>
        </form>
    </div>
    @livewireScripts
</body>
</html>