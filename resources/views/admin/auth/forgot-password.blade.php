<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Forgot Password - {{ config('app.name', 'ERPLax') }}</title>
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
            --success-bg: rgba(16, 185, 129, 0.12);
            --success-text: #059669;
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
                --success-bg: rgba(16, 185, 129, 0.12);
                --success-text: #34d399;
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

        .bg-orb { position: fixed; border-radius: 50%; filter: blur(80px); z-index: 0; pointer-events: none; }
        .orb-1 { width: 500px; height: 500px; background: var(--orb1); top: -150px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: var(--orb2); bottom: -100px; left: -100px; }
        .orb-3 { width: 300px; height: 300px; background: var(--orb3); top: 50%; left: 50%; transform: translate(-50%, -50%); }

        .login-card {
            width: 100%;
            max-width: 420px;
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

        .form-header { text-align: center; margin-bottom: 1.75rem; }
        .form-title { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.375rem; }
        .form-subtitle { font-size: 0.875rem; color: var(--text-muted); line-height: 1.4; }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            border: 1px solid transparent;
        }
        .alert svg { width: 1.125rem; height: 1.125rem; flex-shrink: 0; margin-top: 2px; }
        .alert p { font-size: 0.875rem; }

        .alert-error { background: var(--error-bg); border-color: rgba(239,68,68,0.2); }
        .alert-error svg { color: var(--error); }
        .alert-error p { color: var(--error-text); }

        .alert-success { background: var(--success-bg); border-color: rgba(16,185,129,0.25); }
        .alert-success svg { color: var(--success); }
        .alert-success p { color: var(--success-text); }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.125rem;
            height: 1.125rem;
            color: var(--text-muted);
            pointer-events: none;
            transition: color 0.2s;
        }
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

        .error-text { font-size: 0.8125rem; color: var(--error); margin-top: 0.5rem; }

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
        .submit-btn .spinner {
            position: absolute;
            width: 1.25rem;
            height: 1.25rem;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            opacity: 0;
            animation: spin 0.7s linear infinite;
        }
        .submit-btn.loading .btn-text, .submit-btn.loading svg { opacity: 0; }
        .submit-btn.loading .spinner { opacity: 1; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .helper-links {
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .helper-links a {
            font-size: 0.875rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
        .helper-links a:hover { color: var(--accent-dark); }

        .form-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .security-badge { display: flex; align-items: center; gap: 0.375rem; }
        .security-badge svg { width: 1rem; height: 1rem; color: var(--success); }
        .security-badge span { font-size: 0.8125rem; color: var(--text-muted); }

        .footer-link {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }
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
            <div class="logo-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <span class="logo-text">ERP<span>Lax</span></span>
        </div>

        <div class="form-header">
            <h1 class="form-title">Admin Forgot Password</h1>
            <p class="form-subtitle">
                Enter your registered email. We will send a one-time verification code (OTP) to reset your password.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.forgot-password.send') }}" id="forgotForm">

            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
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
                </div>
                @error('email') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span class="spinner"></span>
                <span class="submit-btn-content">
                    <span class="btn-text">Send OTP</span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </button>

            <div class="helper-links">
                <a href="{{ route('admin.login') }}">Back to Admin Login</a>
                <a href="{{ url('/') }}">Back to Website</a>
            </div>
        </form>

        <div class="form-footer">
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>SSL Secured</span>
            </div>
            <a href="{{ url('/') }}" class="footer-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Home
            </a>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function () {
            document.getElementById('submitBtn').classList.add('loading');
        });
    </script>
</body>
</html>
