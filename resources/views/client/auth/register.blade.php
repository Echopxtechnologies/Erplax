<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'ERPLax') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }
        
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Left Panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        
        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
            z-index: 0;
        }
        
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
            animation: float 15s ease-in-out infinite;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            top: -50px;
            left: -50px;
        }
        
        .shape-2 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            bottom: 10%;
            right: -30px;
            animation-delay: -5s;
        }
        
        .shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #10b981, #14b8a6);
            bottom: 30%;
            left: 20%;
            animation-delay: -10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(15px, -15px) rotate(5deg); }
            50% { transform: translate(-10px, 10px) rotate(-5deg); }
            75% { transform: translate(-15px, -10px) rotate(3deg); }
        }
        
        .left-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
        }
        
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 48px;
        }
        
        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3);
        }
        
        .logo-icon svg {
            width: 28px;
            height: 28px;
            color: #fff;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
        }
        
        .logo-text span {
            color: #10b981;
        }
        
        .brand-headline {
            font-size: 42px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .brand-headline span {
            background: linear-gradient(135deg, #10b981, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-description {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 48px;
        }
        
        .benefits {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .benefit-icon {
            width: 24px;
            height: 24px;
            background: rgba(16, 185, 129, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .benefit-icon svg {
            width: 14px;
            height: 14px;
            color: #10b981;
        }
        
        .benefit-item span {
            font-size: 14px;
            color: #94a3b8;
        }
        
        /* Right Panel */
        .right-panel {
            width: 560px;
            background: #1e293b;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 60px;
            position: relative;
            overflow-y: auto;
        }
        
        .form-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .form-header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.2));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .form-header-icon svg {
            width: 28px;
            height: 28px;
            color: #10b981;
        }
        
        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }
        
        .form-subtitle {
            font-size: 14px;
            color: #94a3b8;
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .alert-error svg {
            width: 20px;
            height: 20px;
            color: #ef4444;
            flex-shrink: 0;
        }
        
        .alert-error p {
            font-size: 13px;
            color: #fca5a5;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #e2e8f0;
            margin-bottom: 8px;
        }
        
        .form-label .optional {
            color: #64748b;
            font-weight: 400;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #64748b;
            pointer-events: none;
            transition: color 0.2s ease;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #f1f5f9;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }
        
        .form-input::placeholder {
            color: #64748b;
        }
        
        .form-input:hover {
            border-color: #475569;
        }
        
        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        
        .input-wrapper:focus-within .input-icon {
            color: #10b981;
        }
        
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #94a3b8;
        }
        
        .password-toggle svg {
            width: 18px;
            height: 18px;
        }
        
        .error-message {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 12px;
            color: #f87171;
        }
        
        .error-message svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }
        
        /* Password Strength */
        .password-strength {
            margin-top: 10px;
        }
        
        .strength-bar {
            height: 4px;
            background: #334155;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        
        .strength-fill {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }
        
        .strength-fill.weak { width: 33%; background: #ef4444; }
        .strength-fill.medium { width: 66%; background: #f59e0b; }
        .strength-fill.strong { width: 100%; background: #10b981; }
        
        .strength-text {
            font-size: 11px;
            color: #64748b;
        }
        
        /* Terms Checkbox */
        .terms-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .checkbox-input {
            width: 18px;
            height: 18px;
            border: 2px solid #475569;
            border-radius: 5px;
            background: #0f172a;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            flex-shrink: 0;
            margin-top: 2px;
            transition: all 0.2s ease;
        }
        
        .checkbox-input:checked {
            background: #10b981;
            border-color: #10b981;
        }
        
        .checkbox-input:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .terms-label {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
        }
        
        .terms-label a {
            color: #10b981;
            text-decoration: none;
        }
        
        .terms-label a:hover {
            text-decoration: underline;
        }
        
        .submit-btn {
            width: 100%;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            color: #fff;
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .submit-btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .submit-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }
        
        .submit-btn:hover svg {
            transform: translateX(4px);
        }
        
        .submit-btn.loading {
            pointer-events: none;
        }
        
        .submit-btn .spinner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            opacity: 0;
            animation: spin 0.8s linear infinite;
        }
        
        .submit-btn.loading .btn-text,
        .submit-btn.loading svg {
            opacity: 0;
        }
        
        .submit-btn.loading .spinner {
            opacity: 1;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #94a3b8;
        }
        
        .login-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #34d399;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 24px;
        }
        
        .footer-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .footer-link:hover {
            color: #10b981;
        }
        
        .footer-link svg {
            width: 16px;
            height: 16px;
        }
        
        @media (max-width: 1024px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 24px; }
        }
        
        @media (max-width: 480px) {
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="bg-pattern"></div>
            <div class="bg-grid"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            
            <div class="left-content">
                <div class="brand-logo">
                    <div class="logo-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="logo-text">ERP<span>Lax</span></span>
                </div>
                
                <h1 class="brand-headline">
                    Join Thousands of<br>
                    <span>Growing Businesses</span>
                </h1>
                
                <p class="brand-description">
                    Create your account today and unlock powerful tools to manage your business efficiently. Get started in minutes.
                </p>
                
                <div class="benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span>Free 14-day trial, no credit card required</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span>Access to all features during trial</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span>Cancel anytime, hassle-free</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span>Dedicated onboarding support</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel -->
        <div class="right-panel">
            <div class="form-container">
                <div class="form-header">
                    <div class="form-header-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h2 class="form-title">Create Account</h2>
                    <p class="form-subtitle">Start your journey with us today</p>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-error">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p>Please fix the errors below and try again.</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('client.register.submit') }}" id="registerForm">
                    @csrf
                    
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-wrapper">
                                <input type="text" name="name" id="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            @error('name')
                                <div class="error-message">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone <span class="optional">(Optional)</span></label>
                            <div class="input-wrapper">
                                <input type="tel" name="phone" id="phone" class="form-input" placeholder="+91 98765 43210" value="{{ old('phone') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="form-input" placeholder="you@company.com" value="{{ old('email') }}" required>
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Company -->
                    <div class="form-group">
                        <label for="company" class="form-label">Company Name <span class="optional">(Optional)</span></label>
                        <div class="input-wrapper">
                            <input type="text" name="company" id="company" class="form-input" placeholder="Your Company Ltd." value="{{ old('company') }}">
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required onkeyup="checkPasswordStrength()">
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <svg class="eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <span class="strength-text" id="strengthText">Enter a password</span>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" required>
                                <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <svg class="eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms -->
                    <div class="terms-wrapper">
                        <input type="checkbox" name="terms" id="terms" class="checkbox-input" required {{ old('terms') ? 'checked' : '' }}>
                        <label for="terms" class="terms-label">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    @error('terms')
                        <div class="error-message" style="margin-top: -16px; margin-bottom: 16px;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    
                    <!-- Submit -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span class="spinner"></span>
                        <span class="submit-btn-content">
                            <span class="btn-text">Create Account</span>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </button>
                </form>
                
                <p class="login-link">
                    Already have an account? <a href="{{ route('client.login') }}">Sign in</a>
                </p>
                
                <div class="form-footer">
                    <a href="{{ url('/') }}" class="footer-link">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Website
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            fill.className = 'strength-fill';
            if (password.length === 0) {
                text.textContent = 'Enter a password';
            } else if (strength <= 1) {
                fill.classList.add('weak');
                text.textContent = 'Weak password';
            } else if (strength <= 2) {
                fill.classList.add('medium');
                text.textContent = 'Medium strength';
            } else {
                fill.classList.add('strong');
                text.textContent = 'Strong password';
            }
        }
        
        document.getElementById('registerForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').classList.add('loading');
        });
    </script>
</body>
</html>