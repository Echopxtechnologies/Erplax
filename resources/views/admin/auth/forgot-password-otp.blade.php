<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7fb; padding:20px;">
    <div style="max-width:520px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding:22px;">
        <h2 style="margin:0 0 10px; color:#111827;">Password Reset Verification Code</h2>
        <p style="margin:0 0 14px; color:#374151; font-size:14px; line-height:1.5;">
            Use the OTP below to reset your password. This OTP expires in <b>5 minutes</b>.
        </p>

        <div style="text-align:center; padding:14px 0; margin:14px 0; background:#f3f4f6; border-radius:10px;">
            <div style="font-size:28px; letter-spacing:6px; font-weight:700; color:#111827;">{{ $otp }}</div>
        </div>

        <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.5;">
            If you did not request this, please ignore this email. Do not share this code with anyone.
        </p>
    </div>
</body>
</html>
