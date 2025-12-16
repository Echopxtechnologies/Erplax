<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name', 'ERPLax') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #eef2ff;
            --bg-card: #ffffff;
            --bg-input: #f8fafc;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --text-placeholder: #94a3b8;
            --accent: #3b82f6;
            --accent-dark: #2563eb;
            --accent-light: rgba(59, 130, 246, 0.1);
            --success: #10b981;
            --error: #ef4444;
            --error-bg: rgba(239, 68, 68, 0.1);
            --error-text: #dc2626;
            --orb1: rgba(99, 102, 241, 0.15);
            --orb2: rgba(59, 130, 246, 0.12);
            --orb3: rgba(14, 165, 233, 0.1);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #0f172a;
                --bg-card: #1e293b;
                --bg-input: #0f172a;
                --border: #334155;
                --border-hover: #475569;
                --text-primary: #f1f5f9;
                --text-secondary: #cbd5e1;
                --text-muted: #94a3b8;
                --text-placeholder: #64748b;
                --accent: #3b82f6;
                --accent-dark: #60a5fa;
                --accent-light: rgba(59, 130, 246, 0.2);
                --error: #f87171;
                --error-bg: rgba(239, 68, 68, 0.15);
                --error-text: #fca5a5;
                --orb1: rgba(99, 102, 241, 0.08);
                --orb2: rgba(59, 130, 246, 0.06);
                --orb3: rgba(14, 165, 233, 0.05);
            }
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; background: var(--orb1); top: -150px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: var(--orb2); bottom: -100px; left: -100px; }
        .orb-3 { width: 300px; height: 300px; background: var(--orb3); top: 50%; left: 50%; transform: translate(-50%, -50%); }
        
        .card {
            width: 100%;
            max-width: 400px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08), 0 0 0 1px rgba(255,255,255,0.05) inset;
            position: relative;
            z-index: 1;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 2.75rem;
            height: 2.75rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
        }
        .logo-icon svg { width: 1.375rem; height: 1.375rem; color: #fff; }
        .logo-text { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; }
        .logo-text span { color: var(--accent); }
        
        .form-header { text-align: center; margin-bottom: 2rem; }
        .form-title { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.375rem; }
        .form-subtitle { font-size: 0.875rem; color: var(--text-muted); }
        .form-subtitle strong { color: var(--text-secondary); }
        
        .alert { padding: 0.875rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.625rem; }
        .alert svg { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }
        .alert p { font-size: 0.875rem; }
        .alert-error { background: var(--error-bg); border: 1px solid rgba(239,68,68,0.2); }
        .alert-error svg { color: var(--error); }
        .alert-error p { color: var(--error-text); }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); }
        .alert-success svg { color: var(--success); }
        .alert-success p { color: #10b981; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.125rem; height: 1.125rem; color: var(--text-muted); pointer-events: none; transition: color 0.2s; }
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--text-primary);
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            outline: none;
            transition: all 0.2s;
        }
        .form-input::placeholder { color: var(--text-placeholder); }
        .form-input:hover { border-color: var(--border-hover); }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-light); }
        .input-wrapper:focus-within .input-icon { color: var(--accent); }
        .form-input.readonly { background: var(--bg-input); color: var(--text-muted); cursor: not-allowed; opacity: 0.7; }
        
        .password-toggle { position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.25rem; display: flex; transition: color 0.2s; }
        .password-toggle:hover { color: var(--text-secondary); }
        .password-toggle svg { width: 1.125rem; height: 1.125rem; }
        .error-text { font-size: 0.8125rem; color: var(--error); margin-top: 0.5rem; }
        
        .password-strength { margin-top: 0.625rem; }
        .strength-bar { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; margin-bottom: 0.375rem; }
        .strength-fill { height: 100%; border-radius: 2px; transition: all 0.3s ease; width: 0%; }
        .strength-fill.weak { width: 33%; background: var(--error); }
        .strength-fill.medium { width: 66%; background: #f59e0b; }
        .strength-fill.strong { width: 100%; background: var(--success); }
        .strength-text { font-size: 0.75rem; color: var(--text-muted); }
        
        .submit-btn {
            width: 100%;
            padding: 0.9375rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: inherit;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
            transition: all 0.25s;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
            margin-top: 0.5rem;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.45); }
        .submit-btn:active { transform: translateY(0); }
        .submit-btn-content { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .submit-btn svg { width: 1.125rem; height: 1.125rem; transition: transform 0.2s; }
        .submit-btn:hover svg { transform: translateX(3px); }
        .submit-btn.loading { pointer-events: none; }
        .submit-btn .spinner { position: absolute; width: 1.25rem; height: 1.25rem; border: 2.5px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; opacity: 0; animation: spin 0.7s linear infinite; }
        .submit-btn.loading .btn-text, .submit-btn.loading svg { opacity: 0; }
        .submit-btn.loading .spinner { opacity: 1; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .form-footer { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .security-badge { display: flex; align-items: center; gap: 0.375rem; }
        .security-badge svg { width: 1rem; height: 1rem; color: var(--success); }
        .security-badge span { font-size: 0.8125rem; color: var(--text-muted); }
        .footer-link { display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .footer-link:hover { color: var(--accent); }
        .footer-link svg { width: 1rem; height: 1rem; }
        
        .help-section { margin-top: 1.5rem; text-align: center; }
        .help-text { font-size: 0.8125rem; color: var(--text-muted); }
        .help-text a { color: var(--accent); text-decoration: none; }
        .help-text a:hover { text-decoration: underline; }
        
        @media (max-width: 440px) { body { padding: 1rem; } .card { padding: 1.75rem; border-radius: 16px; } }
    </style>
</head>
<body>
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>
    
    <div class="card">
        <div class="logo">
            <div class="logo-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span class="logo-text">ERP<span>Lax</span></span>
        </div>
        
        <div class="form-header">
            <h1 class="form-title">Reset Password</h1>
            @php
                $resolvedEmail = old('email', $email ?? session('password_reset_email'));
            @endphp
            <p class="form-subtitle">
                @if(!empty($resolvedEmail))
                    Create a new password for <strong>{{ $resolvedEmail }}</strong>
                @else
                    Set a strong password to secure your account
                @endif
            </p>
        </div>
        
        @if(session('status'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>{{ session('status') }}</p>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('admin.reset-password.update') }}" id="resetForm">
            @csrf
            
            @isset($token)
                <input type="hidden" name="token" value="{{ $token }}">
            @endisset
            
            @if(!empty($resolvedEmail))
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input type="email" name="email" id="email" class="form-input readonly" value="{{ $resolvedEmail }}" readonly autocomplete="username">
                    </div>
                    @error('email')<p class="error-text">{{ $message }}</p>@enderror
                </div>
            @else
                <input type="hidden" name="email" value="{{ old('email') }}">
            @endif
            
            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required autocomplete="new-password" onkeyup="checkPasswordStrength()">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg id="eyeIcon1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeOffIcon1" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-text" id="strengthText">Enter a password</span>
                </div>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" required autocomplete="new-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg id="eyeIcon2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeOffIcon2" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">
                <span class="spinner"></span>
                <span class="submit-btn-content">
                    <span class="btn-text">Reset Password</span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </button>
        </form>
        
        <div class="help-section">
            <p class="help-text">
                Didn't receive the OTP? <a href="{{ route('admin.forgot-password.form') }}">Request a new one</a>
            </p>
        </div>
        
        <div class="form-footer">
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>SSL Secured</span>
            </div>
            <a href="{{ route('admin.login') }}" class="footer-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId === 'password' ? 'eyeIcon1' : 'eyeIcon2');
            const eyeOffIcon = document.getElementById(fieldId === 'password' ? 'eyeOffIcon1' : 'eyeOffIcon2');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
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
            
            if (!password.length) {
                text.textContent = 'Enter a password';
                return;
            }
            
            if (strength <= 1) {
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