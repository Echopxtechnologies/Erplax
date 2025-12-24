<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scanner Error</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#111;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.error-box{text-align:center;max-width:400px}
.icon{font-size:64px;margin-bottom:20px}
h1{font-size:24px;margin-bottom:12px}
p{color:#888;line-height:1.6}
</style>
</head>
<body>
<div class="error-box">
    <div class="icon">⚠️</div>
    <h1>{{ $message }}</h1>
    <p>Please check the session code or ask the cashier to provide a new QR code.</p>
</div>
</body>
</html>
