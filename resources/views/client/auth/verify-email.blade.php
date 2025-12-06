<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - {{ config('app.name', 'ERPLax') }}</title>
    
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
            max-width: 480px;
            background: #1e293b;
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        
        .email-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.2));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            position: relative;
        }
        
        .email-icon svg {
            width: 44px;
            height: 44px;
            color: #10b981;
        }
        
        .email-icon::after {
            content: '';
            position: absolute;
            inset: -8px;
            border: 2px dashed rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            to { transform: rotate(360deg); }
        }
        
        .card-title {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 16px;
        }
        
        .card-description {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        
        .card-description strong {
            color: #f1f5f9;
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-success svg {
            width: 20px;
            height: 20px;
            color: #22c55e;
        }
        
        .alert-success span {
            font-size: 14px;
            color: #86efac;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .primary-btn {
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
        
        .primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
        }
        
        .primary-btn:active {
            transform: translateY(0);
        }
        
        .primary-btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .primary-btn svg {
            width: 20px;
            height: 20px;
        }
        
        .primary-btn.loading { pointer-events: none; }
        
        .primary-btn .spinner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            opacity: 0;
            animation: spin 0.8s linear infinite;
        }
        
        .primary-btn.loading .btn-text,
        .primary-btn.loading svg { opacity: 0; }
        
        .primary-btn.loading .spinner { opacity: 1; }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .secondary-btn {
            width: 100%;
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            color: #94a3b8;
            background: transparent;
            border: 1.5px solid #334155;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .secondary-btn:hover {
            border-color: #475569;
            color: #f1f5f9;
        }
        
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
            color: #475569;
            font-size: 13px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #334155;
        }
        
        .help-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }
        
        .help-text a {
            color: #10b981;
            text-decoration: none;
        }
        
        .help-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="card">
        <div class="email-icon">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
            </svg>
        </div>
        
        <h1 class="card-title">Verify Your Email</h1>
        
        <p class="card-description">
            We've sent a verification link to <strong>{{ Auth::user()->email ?? 'your email address' }}</strong>. 
            Click the link in the email to verify your account and get started.
        </p>
        
        @if(session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>A new verification link has been sent!</span>
            </div>
        @endif
        
        <div class="btn-group">
            <form method="POST" action="{{ route('client.verification.send') }}" id="resendForm">
                @csrf
                <button type="submit" class="primary-btn" id="resendBtn">
                    <span class="spinner"></span>
                    <span class="primary-btn-content">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="btn-text">Resend Verification Email</span>
                    </span>
                </button>
            </form>
            
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit" class="secondary-btn">
                    Log Out
                </button>
            </form>
        </div>
        
        <div class="divider">Need help?</div>
        
        <p class="help-text">
            If you didn't receive the email, check your spam folder or 
            <a href="#">contact support</a> for assistance.
        </p>
    </div>
    
    <script>
        document.getElementById('resendForm').addEventListener('submit', function() {
            document.getElementById('resendBtn').classList.add('loading');
        });
    </script>
</body>
</html>