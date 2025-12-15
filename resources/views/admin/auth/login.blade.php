<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - {{ config('app.name', 'ERPLax') }}</title>
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
        /* Decorative background orbs */
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
        
        .login-card {
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
        
        .alert { padding: 0.875rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.625rem; background: var(--error-bg); border: 1px solid rgba(239,68,68,0.2); }
        .alert svg { width: 1.125rem; height: 1.125rem; color: var(--error); flex-shrink: 0; }
        .alert p { font-size: 0.875rem; color: var(--error-text); }
        
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
        
        .password-toggle { position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.25rem; display: flex; transition: color 0.2s; }
        .password-toggle:hover { color: var(--text-secondary); }
        .password-toggle svg { width: 1.125rem; height: 1.125rem; }
        .error-text { font-size: 0.8125rem; color: var(--error); margin-top: 0.5rem; }
        
        .form-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; }
        .checkbox-wrapper { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
        .checkbox-input { width: 1.125rem; height: 1.125rem; border: 2px solid var(--border-hover); border-radius: 5px; background: transparent; cursor: pointer; appearance: none; position: relative; transition: all 0.2s; }
        .checkbox-input:checked { background: var(--accent); border-color: var(--accent); }
        .checkbox-input:checked::after { content: ''; position: absolute; left: 3px; top: 0px; width: 5px; height: 9px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .checkbox-label { font-size: 0.875rem; color: var(--text-muted); }
        .forgot-link { font-size: 0.875rem; color: var(--accent); text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .forgot-link:hover { color: var(--accent-dark); }
        
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
        
        @media (max-width: 440px) { body { padding: 1rem; } .login-card { padding: 1.75rem; border-radius: 16px; } }
    </style>
</head>
<body>
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>
    
    <div class="login-card">
        <div class="logo">
            <div class="logo-icon"><svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
            <span class="logo-text">ERP<span>Lax</span></span>
        </div>
        <div class="form-header">
            <h1 class="form-title">Admin Login</h1>
            <p class="form-subtitle">Sign in to access your dashboard</p>
        </div>
        @if($errors->any() && $errors->has('email') && str_contains($errors->first('email'), 'credentials'))
        <div class="alert"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><p>Invalid email or password</p></div>
        @endif
        <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <input type="email" name="email" id="email" class="form-input" placeholder="admin@example.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>
                @error('email')@if(!str_contains($message, 'credentials'))<p class="error-text">{{ $message }}</p>@endif @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eyeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg id="eyeOffIcon" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-options">
                <label class="checkbox-wrapper"><input type="checkbox" name="remember" class="checkbox-input" {{ old('remember') ? 'checked' : '' }}><span class="checkbox-label">Remember me</span></label>
                @if(Route::has('admin.password.request'))<a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot password?</a>@endif
            </div>
            <button type="submit" class="submit-btn" id="submitBtn"><span class="spinner"></span><span class="submit-btn-content"><span class="btn-text">Sign in</span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></span></button>
        </form>
        <div class="form-footer">
            <div class="security-badge"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg><span>SSL Secured</span></div>
            <a href="{{ url('/') }}" class="footer-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>Back to website</a>
        </div>
    </div>
    <script>
        function togglePassword(){const p=document.getElementById('password'),e1=document.getElementById('eyeIcon'),e2=document.getElementById('eyeOffIcon');if(p.type==='password'){p.type='text';e1.style.display='none';e2.style.display='block';}else{p.type='password';e1.style.display='block';e2.style.display='none';}}
        document.getElementById('loginForm').addEventListener('submit',function(){document.getElementById('submitBtn').classList.add('loading');});
    </script>
</body>
</html>