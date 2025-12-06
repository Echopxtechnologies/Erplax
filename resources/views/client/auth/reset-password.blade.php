<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name', 'ERPLax') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }
        
        .bg-shapes {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            top: -100px;
            left: -100px;
        }
        
        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            bottom: -50px;
            right: -50px;
        }
        
        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            background: #1e293b;
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .header-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.2));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        
        .header-icon svg {
            width: 32px;
            height: 32px;
            color: #10b981;
        }
        
        .card-title {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }
        
        .card-subtitle {
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
            transition: color 0.2s ease;
        }
        
        .form-input {
            width: 100%;
            padding: 13px 14px 13px 44px;
            font-size: 14px;
            font-family: inherit;
            color: #f1f5f9;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }
        
        .form-input::placeholder { color: #64748b; }
        .form-input:hover { border-color: #475569; }
        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        
        .form-input.readonly {
            background: #1e293b;
            color: #94a3b8;
            cursor: not-allowed;
        }
        
        .input-wrapper:focus-within .input-icon { color: #10b981; }
        
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
        
        .password-toggle:hover { color: #94a3b8; }
        .password-toggle svg { width: 18px; height: 18px; }
        
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
        
        .submit-btn {
            width: 100%;
            padding: 14px 24px;
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
            margin-top: 8px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
        }
        
        .submit-btn:active { transform: translateY(0); }
        
        .submit-btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .submit-btn svg { width: 18px; height: 18px; }
        .submit-btn.loading { pointer-events: none; }
        
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
        .submit-btn.loading svg { opacity: 0; }
        
        .submit-btn.loading .spinner { opacity: 1; }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
            font-size: 14px;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .back-link:hover { color: #10b981; }
        .back-link svg { width: 18px; height: 18px; }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="card-title">Reset Password</h1>
            <p class="card-subtitle">Create a strong password for your account</p>
        </div>
        
        @if($errors->any())
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('client.password.update') }}" id="resetForm">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-input readonly" 
                        value="{{ old('email', $email ?? '') }}"
                        required 
                        readonly
                    >
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-input" 
                        placeholder="••••••••"
                        required 
                        autofocus
                        onkeyup="checkPasswordStrength()"
                    >
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-input" 
                        placeholder="••••••••"
                        required
                    >
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">
                <span class="spinner"></span>
                <span class="submit-btn-content">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="btn-text">Reset Password</span>
                </span>
            </button>
        </form>
        
        <a href="{{ route('client.login') }}" class="back-link">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Sign In
        </a>
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
        
        document.getElementById('resetForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').classList.add('loading');
        });
    </script>
</body>
</html>