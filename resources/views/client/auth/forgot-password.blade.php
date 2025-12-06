<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - {{ config('app.name', 'ERPLax') }}</title>
    
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
            line-height: 1.6;
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-success svg {
            width: 20px;
            height: 20px;
            color: #22c55e;
            flex-shrink: 0;
        }
        
        .alert-success p {
            font-size: 13px;
            color: #86efac;
            line-height: 1.5;
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
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 24px;
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
        }
        
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
        
        .back-link:hover {
            color: #10b981;
        }
        
        .back-link svg {
            width: 18px;
            height: 18px;
        }
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
                    <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="card-title">Forgot Password?</h1>
            <p class="card-subtitle">No worries! Enter your email and we'll send you a link to reset your password.</p>
        </div>
        
        @if(session('status'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>{{ session('status') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('client.password.email') }}" id="forgotForm">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-input" 
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
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
            
            <button type="submit" class="submit-btn" id="submitBtn">
                <span class="spinner"></span>
                <span class="submit-btn-content">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="btn-text">Send Reset Link</span>
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
        document.getElementById('forgotForm').addEventListener('submit', function() {
            document.getElementById('submitBtn').classList.add('loading');
        });
    </script>
</body>
</html>