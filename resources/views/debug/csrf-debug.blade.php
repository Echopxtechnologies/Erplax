<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF & Session Debug - ERP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; }
        .success { color: #198754; }
        .warning { color: #ffc107; }
        .danger { color: #dc3545; }
        pre { background: #2d2d2d; color: #f8f9fa; padding: 15px; border-radius: 5px; }
        .console { background: #000; color: #0f0; padding: 15px; font-family: monospace; height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">🔧 CSRF & Session Debug Tool</h1>
        
        @if(session('message'))
            <div class="alert alert-info">{{ session('message') }}</div>
        @endif
        
        <!-- Actions -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🛠️ Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ url('/debug/csrf') }}" class="btn btn-primary">Refresh Debug Info</a>
                <a href="{{ url('/debug/clear-session') }}" class="btn btn-warning" onclick="return confirm('Clear all session data?')">Clear Session</a>
                <a href="{{ url('/debug/set-test-session') }}" class="btn btn-secondary">Set Test Session</a>
                <a href="{{ url('/debug/test-post') }}" class="btn btn-success">Test POST Request</a>
                <a href="{{ url('/login') }}" class="btn btn-info">Go to Login Page</a>
            </div>
        </div>

        <!-- Console Output -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">📟 Browser Console Output</h5>
            </div>
            <div class="card-body">
                <div class="console" id="consoleOutput">
                    <!-- Console output will appear here -->
                </div>
            </div>
        </div>

        <!-- CSRF Status -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">🛡️ CSRF Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>CSRF Token (Meta Tag):</strong> 
                            @if(csrf_token())
                                <span class="success">✓ Present</span>
                                <small class="d-block text-muted">{{ substr($data['csrf_token'], 0, 20) }}...</small>
                            @else
                                <span class="danger">✗ Missing</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>XSRF-TOKEN Cookie:</strong> 
                            @if($data['xsrf_token_cookie'])
                                <span class="success">✓ Present</span>
                            @else
                                <span class="danger">✗ Missing</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Status -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">💾 Session Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Session ID:</strong> {{ $data['session_id'] ?: 'None' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Session Status:</strong> 
                            <span class="{{ $data['session_status'] == 'Started' ? 'success' : 'danger' }}">
                                {{ $data['session_status'] }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Session Cookie:</strong> 
                            @if($data['laravel_session_cookie'])
                                <span class="success">✓ Present</span>
                            @else
                                <span class="danger">✗ Missing</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">⚙️ Configuration</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>APP_ENV:</strong> {{ $data['app_env'] }}</p>
                        <p><strong>APP_DEBUG:</strong> {{ $data['app_debug'] ? 'true' : 'false' }}</p>
                        <p><strong>APP_URL:</strong> {{ $data['app_url'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Session Driver:</strong> {{ $data['session_config']['driver'] }}</p>
                        <p><strong>Session Lifetime:</strong> {{ $data['session_config']['lifetime'] }} minutes</p>
                        <p><strong>Session Domain:</strong> {{ $data['session_config']['domain'] ?: 'Not set' }}</p>
                        <p><strong>Secure Cookies:</strong> {{ $data['session_config']['secure'] ? 'true (HTTPS)' : 'false (HTTP)' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Raw Data (Expandable) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#rawData">
                        📊 View Raw Data
                    </button>
                </h5>
            </div>
            <div class="collapse" id="rawData">
                <div class="card-body">
                    <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        </div>

        <!-- Test Form -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🧪 Test CSRF Form</h5>
            </div>
            <div class="card-body">
                <form id="testForm">
                    <div class="mb-3">
                        <label class="form-label">Test Field</label>
                        <input type="text" class="form-control" name="test_field" value="Test Value">
                    </div>
                    <button type="submit" class="btn btn-success">Submit Test (AJAX)</button>
                    <div id="formResult" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Console output function
        function logToConsole(message, type = 'info') {
            const colors = {
                info: '#0f0',
                success: '#0f0',
                warning: '#ff0',
                error: '#f00'
            };
            
            const timestamp = new Date().toLocaleTimeString();
            const consoleDiv = document.getElementById('consoleOutput');
            const messageDiv = document.createElement('div');
            messageDiv.innerHTML = `<span style="color: #aaa">[${timestamp}]</span> <span style="color: ${colors[type]}">${message}</span>`;
            consoleDiv.appendChild(messageDiv);
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
            
            // Also log to browser console
            if (type === 'info') console.info(message);
            if (type === 'success') console.log(message);
            if (type === 'warning') console.warn(message);
            if (type === 'error') console.error(message);
        }

        // Initialize
        $(document).ready(function() {
            logToConsole('🔍 CSRF Debug Page Loaded', 'info');
            logToConsole(`Session ID: {{ $data['session_id'] }}`, 'info');
            logToConsole(`CSRF Token present: {{ csrf_token() ? 'YES' : 'NO' }}`, 'info');
            
            // Check for common issues
            if (!'{{ $data["xsrf_token_cookie"] }}') {
                logToConsole('⚠️ XSRF-TOKEN cookie is missing!', 'warning');
            }
            
            if (!'{{ $data["laravel_session_cookie"] }}') {
                logToConsole('⚠️ Laravel session cookie is missing!', 'warning');
            }
            
            if ('{{ $data["session_config"]["secure"] }}' && window.location.protocol !== 'https:') {
                logToConsole('⚠️ Secure cookies enabled but not using HTTPS!', 'error');
            }
            
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Test form submission
            $('#testForm').on('submit', function(e) {
                e.preventDefault();
                
                logToConsole('📤 Testing POST request with CSRF...', 'info');
                
                $.post('/debug/test-post', $(this).serialize())
                    .done(function(response) {
                        logToConsole(`✅ POST successful! Session ID: ${response.session_id}`, 'success');
                        $('#formResult').html(`
                            <div class="alert alert-success">
                                <strong>Success!</strong> CSRF validation passed.<br>
                                Session ID: ${response.session_id}<br>
                                Received: ${JSON.stringify(response.received_data)}
                            </div>
                        `);
                    })
                    .fail(function(xhr) {
                        logToConsole(`❌ POST failed! Status: ${xhr.status}`, 'error');
                        $('#formResult').html(`
                            <div class="alert alert-danger">
                                <strong>Failed!</strong> Status: ${xhr.status}<br>
                                Response: ${xhr.responseText}
                            </div>
                        `);
                    });
            });
            
            // Log all cookies
            logToConsole('🍪 Cookies present:', 'info');
            document.cookie.split(';').forEach(cookie => {
                if (cookie.trim()) logToConsole(`  ${cookie.trim()}`, 'info');
            });
        });
    </script>
</body>
</html>