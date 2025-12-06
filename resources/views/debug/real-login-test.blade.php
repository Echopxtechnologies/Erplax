<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Real Login Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .form-group { margin: 15px 0; }
        label { display: inline-block; width: 120px; }
        input { padding: 8px; width: 300px; }
        .result { padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>🔐 Real Login Form Test</h1>
    <p><strong>Current Session ID:</strong> {{ session()->getId() }}</p>
    
    <h3>Form that matches your actual login:</h3>
    <form method="POST" action="{{ url('/debug/test-real-login') }}" id="loginForm">
        @csrf
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="admin@erpbangalore.in" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" value="password" required>
        </div>
        <div class="form-group">
            <button type="submit" style="padding: 10px 20px;">Login</button>
            <button type="button" onclick="submitAjax()" style="padding: 10px 20px;">Login via AJAX</button>
        </div>
    </form>
    
    <div id="result"></div>
    
    <hr>
    <h3>Debug Info:</h3>
    <pre id="debugInfo"></pre>
    
    <p>
        <a href="/login">Go to REAL Login Page</a> | 
        <a href="/debug/csrf">Back to CSRF Debug</a>
    </p>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Show debug info
        $('#debugInfo').text(
            'CSRF Token: ' + $('meta[name="csrf-token"]').attr('content') + '\n' +
            'All Cookies: ' + document.cookie + '\n' +
            'Session Cookie Present: ' + (document.cookie.includes('laravel_session') ? 'Yes' : 'No') + '\n' +
            'XSRF Cookie Present: ' + (document.cookie.includes('XSRF-TOKEN') ? 'Yes' : 'No')
        );
        
        // Normal form submission
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $('#result').html('<div class="result">Submitting...</div>');
            
            // Submit normally
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                return response.json().then(data => ({status: response.status, data}));
            })
            .then(({status, data}) => {
                if (status === 419) {
                    $('#result').html(`
                        <div class="error result">
                            <strong>❌ 419 Page Expired Error!</strong><br>
                            ${data.error}<br>
                            Received Token: ${data.received_token}<br>
                            Expected Token: ${data.expected_token}
                        </div>
                    `);
                } else if (data.success) {
                    $('#result').html(`
                        <div class="success result">
                            <strong>✅ Login Successful!</strong><br>
                            Session ID: ${data.session_id}<br>
                            Email: ${data.email}
                        </div>
                    `);
                }
            })
            .catch(error => {
                $('#result').html(`
                    <div class="error result">
                        <strong>❌ Error:</strong><br>
                        ${error}
                    </div>
                `);
            });
        });
        
        // AJAX submission
        function submitAjax() {
            $('#result').html('<div class="result">Submitting via AJAX...</div>');
            
            $.ajax({
                url: '/debug/test-real-login',
                method: 'POST',
                data: $('#loginForm').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .done(function(data) {
                $('#result').html(`
                    <div class="success result">
                        <strong>✅ AJAX Login Successful!</strong><br>
                        Session ID: ${data.session_id}<br>
                        Message: ${data.message}
                    </div>
                `);
            })
            .fail(function(xhr) {
                if (xhr.status === 419) {
                    const data = xhr.responseJSON;
                    $('#result').html(`
                        <div class="error result">
                            <strong>❌ 419 Page Expired (AJAX)!</strong><br>
                            ${data.error}<br>
                            Received Token: ${data.received_token}<br>
                            Expected Token: ${data.expected_token}
                        </div>
                    `);
                } else {
                    $('#result').html(`
                        <div class="error result">
                            <strong>❌ AJAX Error ${xhr.status}:</strong><br>
                            ${xhr.responseText}
                        </div>
                    `);
                }
            });
        }
    </script>
</body>
</html>