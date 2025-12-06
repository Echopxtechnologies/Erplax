<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test POST Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Test POST Request with CSRF</h4>
        </div>
        <div class="card-body">
            <p>This page tests if CSRF validation is working correctly.</p>
            
            <form method="POST" action="{{ url('/debug/test-post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Test Input</label>
                    <input type="text" class="form-control" name="test_input" value="Testing CSRF Protection">
                </div>
                <button type="submit" class="btn btn-primary">Submit Form (Normal POST)</button>
                <a href="{{ url('/debug/csrf') }}" class="btn btn-secondary">Back to Debug</a>
            </form>
            
            @if(session('message'))
                <div class="alert alert-info mt-3">{{ session('message') }}</div>
            @endif
        </div>
    </div>
</body>
</html>