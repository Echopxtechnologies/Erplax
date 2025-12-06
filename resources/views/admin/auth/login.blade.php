<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - {{ config('app.name', 'ERPLax') }}</title>
    
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
        
        /* Split Layout */
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Left Panel - Branding */
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
        
        /* Animated Background Elements */
        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(14, 165, 233, 0.08) 0%, transparent 50%);
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
        
        /* Floating Shapes */
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
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            top: -50px;
            left: -50px;
            animation-delay: 0s;
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
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
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
        
        /* Logo */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 48px;
        }
        
        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
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
            letter-spacing: -0.5px;
        }
        
        .logo-text span {
            color: #3b82f6;
        }
        
        /* Headline */
        .brand-headline {
            font-size: 42px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }
        
        .brand-headline span {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
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
        
        /* Features */
        .features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        
        .feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .feature-icon svg {
            width: 20px;
            height: 20px;
            color: #3b82f6;
        }
        
        .feature-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 4px;
        }
        
        .feature-content p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        
        /* Stats */
        .stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .stat-item {
            text-align: left;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }
        
        .stat-number span {
            color: #3b82f6;
        }
        
        .stat-label {
            font-size: 13px;
            color: #64748b;
        }
        
        /* Right Panel - Form */
        .right-panel {
            width: 520px;
            background: #1e293b;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
        }
        
        .form-container {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }
        
        /* Form Header */
        .form-header {
            text-align: center;
            margin-bottom: 36px;
        }
        
        .form-header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .form-header-icon svg {
            width: 28px;
            height: 28px;
            color: #3b82f6;
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
        
        /* Alert */
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
            margin-top: 1px;
        }
        
        .alert-error p {
            font-size: 13px;
            color: #fca5a5;
            line-height: 1.5;
        }
        
        /* Form */
        .form-group {
            margin-bottom: 22px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #e2e8f0;
            margin-bottom: 8px;
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        
        .input-wrapper:focus-within .input-icon {
            color: #3b82f6;
        }
        
        /* Password Toggle */
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
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #94a3b8;
        }
        
        .password-toggle svg {
            width: 18px;
            height: 18px;
        }
        
        /* Error */
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
        
        /* Form Options */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
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
            transition: all 0.2s ease;
        }
        
        .checkbox-input:checked {
            background: #3b82f6;
            border-color: #3b82f6;
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
        
        .checkbox-label {
            font-size: 13px;
            color: #94a3b8;
            user-select: none;
        }
        
        .forgot-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .forgot-link:hover {
            color: #60a5fa;
        }
        
        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
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
        
        /* Loading */
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
        
        /* Security */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding: 12px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 8px;
        }
        
        .security-note svg {
            width: 16px;
            height: 16px;
            color: #22c55e;
        }
        
        .security-note span {
            font-size: 12px;
            color: #22c55e;
            font-weight: 500;
        }
        
        /* Footer */
        .form-footer {
            text-align: center;
            margin-top: 32px;
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
            color: #3b82f6;
        }
        
        .footer-link svg {
            width: 16px;
            height: 16px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .left-panel {
                display: none;
            }
            
            .right-panel {
                width: 100%;
                padding: 40px 24px;
            }
        }
        
        @media (max-width: 480px) {
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .form-header-icon {
                width: 56px;
                height: 56px;
            }
            
            .form-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Panel - Branding -->
        <div class="left-panel">
            <div class="bg-pattern"></div>
            <div class="bg-grid"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            
            <div class="left-content">
                <!-- Logo -->
                <div class="brand-logo">
                    <div class="logo-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="logo-text">ERP<span>Lax</span></span>
                </div>
                
                <!-- Headline -->
                <h1 class="brand-headline">
                    Streamline Your<br>
                    <span>Business Operations</span>
                </h1>
                
                <p class="brand-description">
                    Powerful enterprise resource planning solution designed to help you manage inventory, sales, purchases, accounting, and more - all in one unified platform.
                </p>
                
                <!-- Features -->
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Real-time Analytics</h4>
                            <p>Get instant insights into your business performance with powerful dashboards.</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Enterprise Security</h4>
                            <p>Bank-grade encryption and role-based access control keeps your data safe.</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4>Modular Design</h4>
                            <p>Enable only the modules you need. Scale as your business grows.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">500<span>+</span></div>
                        <div class="stat-label">Active Companies</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">99.9<span>%</span></div>
                        <div class="stat-label">Uptime SLA</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24<span>/7</span></div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Login Form -->
        <div class="right-panel">
            <div class="form-container">
                <!-- Form Header -->
                <div class="form-header">
                    <div class="form-header-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h2 class="form-title">Admin Login</h2>
                    <p class="form-subtitle">Sign in to access admin dashboard</p>
                </div>
                
                <!-- Error Alert -->
                @if($errors->any() && $errors->has('email') && str_contains($errors->first('email'), 'credentials'))
                    <div class="alert alert-error">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p>Invalid credentials. Please check your email and password.</p>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                    @csrf
                        <!-- DEBUG: Remove after testing -->
    <p style="color: #22c55e; font-size: 11px; margin-bottom: 10px;">
        Session ID: {{ substr(session()->getId(), 0, 8) }}... | 
        Token: {{ substr(csrf_token(), 0, 8) }}...
    </p>
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-input" 
                                placeholder="admin@example.com"
                                value="{{ old('email') }}"
                                required 
                                autofocus
                                autocomplete="email"
                            >
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @error('email')
                            @if(!str_contains($message, 'credentials'))
                                <div class="error-message">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @endif
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-input" 
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg id="eyeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
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
                    
                    <!-- Remember & Forgot -->
                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" class="checkbox-input" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkbox-label">Remember me</span>
                        </label>
                        @if(Route::has('admin.password.request'))
                            <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>
                    
                    <!-- Submit -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span class="spinner"></span>
                        <span class="submit-btn-content">
                            <span class="btn-text">Sign In</span>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </button>
                </form>
                
                <!-- Security Note -->
                <div class="security-note">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>256-bit SSL Encrypted</span>
                </div>
                
                <!-- Footer -->
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }
        
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').classList.add('loading');
        });
    </script>
</body>
</html>