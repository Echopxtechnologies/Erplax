<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - {{ config('app.name', 'ERPLax') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#0f172a; --card:#1e293b; --input:#0f172a;
            --border:#334155; --hover:#475569; --text:#f1f5f9; --muted:#94a3b8;
            --accent:#3b82f6; --accent2:#2563eb; --error:#f87171;
            --errorBg: rgba(248,113,113,.12);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'Inter',sans-serif; min-height:100vh; background:var(--bg);
            display:flex; align-items:center; justify-content:center; padding:24px;
        }
        .card{
            width:100%; max-width:420px; background:var(--card); border:1px solid var(--border);
            border-radius:18px; padding:40px; box-shadow:0 25px 50px -12px rgba(0,0,0,.55);
        }
        h1{color:var(--text); font-size:24px; margin-bottom:8px}
        p{color:var(--muted); font-size:14px; line-height:1.5; margin-bottom:22px}

        .alert{
            background:var(--errorBg); border:1px solid rgba(248,113,113,.25);
            color:var(--error); padding:12px 14px; border-radius:12px; margin-bottom:16px;
            font-size:13px;
        }

        label{display:block; color:var(--text); font-size:13px; margin-bottom:8px}
        input{
            width:100%; padding:14px 14px; border-radius:12px; outline:none;
            border:1.5px solid var(--border); background:var(--input); color:var(--text);
            font-size:18px; letter-spacing:6px; text-align:center;
        }
        input:focus{border-color:var(--accent); box-shadow:0 0 0 4px rgba(59,130,246,.18)}
        .btn{
            width:100%; margin-top:18px; padding:14px 16px; border:none; cursor:pointer;
            border-radius:12px; color:#fff; font-weight:600; font-size:14px;
            background:linear-gradient(135deg,var(--accent),var(--accent2));
        }
        .links{
            display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;
            margin-top:16px;
        }
        .links a{color:var(--muted); text-decoration:none; font-size:13px}
        .links a:hover{color:#fff}
    </style>
</head>
<body>
    <div class="card">
        <h1>Verify OTP</h1>
        <p>Please enter the 6-digit code sent to your email. This code expires in 5 minutes.</p>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        @if (session('success'))
            <div class="alert" style="background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.25); color:#34d399;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.verify-otp.check') }}">
            @csrf

            <label for="otp">OTP</label>
            <input
                type="text"
                name="otp"
                id="otp"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                autocomplete="one-time-code"
                required
                autofocus
            >

            <button type="submit" class="btn">Verify & Continue</button>
        </form>

        <div class="links">
            <a href="{{ route('admin.forgot-password.form') }}">Change Email</a>
            <a href="{{ route('admin.login') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
