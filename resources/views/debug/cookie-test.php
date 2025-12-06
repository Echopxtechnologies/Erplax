<?php
// Simple cookie test
setcookie(
    'test_cookie_php', 
    'php_cookie_value_' . time(),
    [
        'expires' => time() + 3600,
        'path' => '/',
        'domain' => '.erpbangalore.in',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]
);

setcookie(
    'test_cookie_simple',
    'simple_value',
    time() + 3600,
    '/',
    '.erpbangalore.in',
    true,
    true
);
?>
<!DOCTYPE html>
<html>
<head><title>Cookie Test</title></head>
<body>
    <h1>Cookie Test Page</h1>
    <h3>PHP Cookies Set:</h3>
    <pre><?php print_r(headers_list()); ?></pre>
    
    <h3>JavaScript Cookie Test:</h3>
    <script>
        document.cookie = "test_cookie_js=js_value; path=/; domain=.erpbangalore.in; secure";
        console.log("Cookies:", document.cookie);
        document.write("Cookies from JS: " + document.cookie);
    </script>
    
    <p><a href="/debug/csrf">Back to Debug</a></p>
</body>
</html>