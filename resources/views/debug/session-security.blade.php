<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Security Debug</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #1a1a2e; color: #eee; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #00d4ff; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #00d4ff; }
        h2 { color: #00d4ff; margin: 30px 0 15px; font-size: 1.2rem; }
        .card { background: #16213e; border-radius: 8px; padding: 20px; margin-bottom: 15px; }
        .result-row { display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #0f3460; }
        .result-row:last-child { border-bottom: none; }
        .result-label { width: 200px; font-weight: 600; color: #888; flex-shrink: 0; }
        .result-status { width: 30px; flex-shrink: 0; font-size: 1.2rem; }
        .result-message { flex: 1; word-break: break-word; }
        .status-ok { color: #00ff88; }
        .status-error { color: #ff4757; }
        .status-warning { color: #ffa502; }
        .status-info { color: #00d4ff; }
        
        .alert { padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-danger { background: #ff4757; color: #fff; }
        .alert-success { background: #00ff88; color: #000; }
        .alert h3 { margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #0f3460; }
        th { background: #0f3460; color: #00d4ff; }
        tr:hover { background: #1f3460; }
        .current-session { background: #0f3460 !important; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; }
        .badge-success { background: #00ff88; color: #000; }
        .badge-danger { background: #ff4757; color: #fff; }
        .checklist { background: #0f3460; padding: 15px 20px; border-radius: 8px; margin-top: 20px; }
        .checklist h3 { color: #ffa502; margin-bottom: 10px; }
        .checklist ul { list-style: none; }
        .checklist li { padding: 5px 0; }
        .checklist code { background: #16213e; padding: 2px 6px; border-radius: 4px; color: #00d4ff; }
        .mono { font-family: 'Monaco', 'Consolas', monospace; font-size: 0.85rem; }
        
        .fingerprint-box { background: #0f3460; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .fingerprint-box .label { color: #888; font-size: 0.85rem; }
        .fingerprint-box .value { font-family: monospace; color: #00d4ff; word-break: break-all; }
        .fingerprint-match { border-left: 4px solid #00ff88; }
        .fingerprint-mismatch { border-left: 4px solid #ff4757; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Session Security Debug</h1>
        
        @php
            $fingerprintCheck = $results['fingerprint_check'] ?? null;
            $isHijacked = $fingerprintCheck && $fingerprintCheck['status'] === 'error';
        @endphp
        
        @if($isHijacked)
        <div class="alert alert-danger">
            <h3>⚠️ SESSION HIJACKING DETECTED!</h3>
            <p>The fingerprint from your current browser does NOT match the fingerprint stored in the database for this session.</p>
            <p>This means this cookie was likely copied from another browser/device.</p>
            <p><strong>With the fixed middleware, this request would be BLOCKED and session destroyed.</strong></p>
        </div>
        @else
        <div class="alert alert-success">
            <h3>✅ Session is Valid</h3>
            <p>Your browser fingerprint matches the stored fingerprint. This is a legitimate session.</p>
        </div>
        @endif
        
        <div class="card">
            @foreach($results as $key => $result)
            <div class="result-row">
                <div class="result-label">{{ $result['label'] }}</div>
                <div class="result-status status-{{ $result['status'] }}">
                    @if($result['status'] === 'ok') ✅
                    @elseif($result['status'] === 'error') ❌
                    @elseif($result['status'] === 'warning') ⚠️
                    @else ℹ️
                    @endif
                </div>
                <div class="result-message">
                    @if($result['value'])
                        <strong>{{ $result['value'] }}</strong> - 
                    @endif
                    {{ $result['message'] }}
                </div>
            </div>
            @endforeach
        </div>
        
        <h2>📋 Active Admin Sessions (Last 10)</h2>
        <div class="card">
            @if(count($activeSessions) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Session ID</th>
                        <th>DB Fingerprint</th>
                        <th>Device</th>
                        <th>IP</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeSessions as $session)
                    @php
                        $isCurrent = $session->session_id === $currentSessionId;
                        $fingerprintMatches = $isCurrent && hash_equals($session->fingerprint, $freshFingerprint);
                    @endphp
                    <tr class="{{ $isCurrent ? 'current-session' : '' }}">
                        <td>
                            <strong>{{ $session->admin_name }}</strong><br>
                            <small class="mono">{{ $session->admin_email }}</small>
                        </td>
                        <td class="mono">{{ substr($session->session_id, 0, 12) }}...</td>
                        <td class="mono">{{ substr($session->fingerprint, 0, 12) }}...</td>
                        <td>{{ $session->device_name ?? 'Unknown' }}</td>
                        <td class="mono">{{ $session->ip_address }}</td>
                        <td>{{ \Carbon\Carbon::parse($session->last_activity)->diffForHumans() }}</td>
                        <td>
                            @if($isCurrent)
                                @if($fingerprintMatches)
                                    <span class="badge badge-success">✅ Valid</span>
                                @else
                                    <span class="badge badge-danger">❌ Hijacked!</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <div class="fingerprint-box {{ $isHijacked ? 'fingerprint-mismatch' : 'fingerprint-match' }}">
                    <div class="label">Your Fresh Fingerprint (generated now from your User-Agent):</div>
                    <div class="value">{{ $freshFingerprint }}</div>
                </div>
            </div>
            @else
            <p>No active sessions found in admin_sessions table.</p>
            @endif
        </div>
        
        <div class="checklist">
            <h3>📝 Implementation Checklist</h3>
            <ul>
                <li>☐ <code>.env</code>: SESSION_DRIVER=database</li>
                <li>☐ Run: <code>php artisan session:table && php artisan migrate</code></li>
                <li>☐ Run: <code>admin_sessions</code> migration</li>
                <li>☐ File: <code>app/Http/Middleware/ValidateAdminSession.php</code> <strong>(FIXED version!)</strong></li>
                <li>☐ File: <code>app/Http/Middleware/EnsureIsAdmin.php</code></li>
                <li>☐ File: <code>app/Http/Controllers/Admin/Auth/AdminLoginController.php</code></li>
                <li>☐ Route: Add <code>ValidateAdminSession::class</code> to middleware array</li>
                <li>☐ Clear caches: <code>php artisan config:clear && php artisan route:clear</code></li>
                <li>☐ Clear old sessions: <code>TRUNCATE sessions; TRUNCATE admin_sessions;</code></li>
                <li>☐ Re-login to create new secure session</li>
            </ul>
        </div>
        
        <p style="margin-top: 30px; color: #666; font-size: 0.85rem;">
            ⚠️ Delete this debug route after testing! <code>routes/admin.php</code> → remove <code>/debug</code> route
        </p>
    </div>
</body>
</html>