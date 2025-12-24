<style>
        .settings-page { max-width: 1000px; margin: 0 auto; }
        .settings-header { margin-bottom: 24px; }
        .settings-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .settings-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
        .settings-header p { font-size: 14px; color: var(--text-muted); margin: 8px 0 0 38px; }
        
        .settings-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
        .settings-card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); display: flex; align-items: center; justify-content: space-between; }
        .settings-card-header h2 { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .settings-card-header h2 svg { width: 20px; height: 20px; color: var(--primary); }
        .settings-card-body { padding: 20px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 24px; height: 24px; }
        .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-icon.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
        .stat-icon.purple { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
        .stat-icon.orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
        .stat-content p { margin: 0; }
        .stat-content .label { font-size: 13px; color: var(--text-muted); }
        .stat-content .value { font-size: 18px; font-weight: 700; color: var(--text-primary); }
        
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); background: var(--body-bg); border-bottom: 1px solid var(--card-border); }
        .info-table td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid var(--card-border); }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table tr:nth-child(even) { background: var(--body-bg); }
        .info-table .label { font-weight: 600; color: var(--text-primary); width: 35%; }
        .info-table .value { color: var(--text-secondary); }
        .info-table .mono { font-family: monospace; font-size: 12px; }
        
        .badge { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 20px; }
        .badge-success { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn svg { width: 16px; height: 16px; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { opacity: 0.9; }
        
        .progress-bar { width: 100%; height: 12px; background: var(--card-border); border-radius: 6px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 6px; transition: width 0.3s; }
        .progress-fill.blue { background: #3b82f6; }
        .progress-fill.yellow { background: #f59e0b; }
        .progress-fill.red { background: #ef4444; }
        
        .session-info { padding: 16px; background: var(--body-bg); border-radius: 8px; }
        .session-info p { margin: 0; font-size: 13px; color: var(--text-muted); }
    </style>

    <div class="settings-page">
        <div class="settings-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>
                System / Server Information
            </h1>
            <p>View system and server configuration details</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="label">PHP Version</p>
                    <p class="value">{{ $systemInfo['php_version'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="label">MySQL Version</p>
                    <p class="value">{{ $systemInfo['mysql_version'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="label">Web Server</p>
                    <p class="value" title="{{ $systemInfo['webserver'] }}">{{ Str::limit($systemInfo['webserver'], 20) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="label">Laravel Version</p>
                    <p class="value">{{ $systemInfo['laravel_version'] }}</p>
                </div>
            </div>
        </div>

        <!-- Server Information -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                    </svg>
                    Server Information
                </h2>
            </div>
            <table class="info-table">
                <tr><td class="label">OS</td><td class="value">{{ $systemInfo['os'] }}</td></tr>
                <tr><td class="label">Web Server</td><td class="value">{{ $systemInfo['webserver'] }}</td></tr>
                <tr><td class="label">Webserver User</td><td class="value">{{ $systemInfo['webserver_user'] }}</td></tr>
                <tr><td class="label">Server Protocol</td><td class="value">{{ $systemInfo['server_protocol'] }}</td></tr>
                <tr><td class="label">Installation Path</td><td class="value mono">{{ $systemInfo['installation_path'] }}</td></tr>
                <tr><td class="label">Temp DIR</td><td class="value mono">{{ $systemInfo['temp_dir'] }}</td></tr>
                <tr><td class="label">Base URL</td><td class="value">{{ $systemInfo['base_url'] }}</td></tr>
                <tr>
                    <td class="label">Environment</td>
                    <td class="value">
                        <span class="badge {{ $systemInfo['environment'] === 'production' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($systemInfo['environment']) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Debug Mode</td>
                    <td class="value">
                        <span class="badge {{ $systemInfo['debug_mode'] ? 'badge-danger' : 'badge-success' }}">
                            {{ $systemInfo['debug_mode'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </td>
                </tr>
                <tr><td class="label">Timezone</td><td class="value">{{ $systemInfo['timezone'] }}</td></tr>
                <tr><td class="label">CSRF Enabled</td><td class="value">{{ $systemInfo['csrf_enabled'] }}</td></tr>
                <tr><td class="label">Cloudflare</td><td class="value">{{ $systemInfo['cloudflare'] }}</td></tr>
            </table>
        </div>

        <!-- Session Table -->
        @if($systemInfo['session_driver'] === 'database')
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Session Table
                </h2>
                <form action="{{ route('admin.settings.system-info.clear-sessions') }}" method="POST" 
                      onsubmit="return confirm('Are you sure? All other users will be logged out and need to login again.')">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear Sessions ({{ number_format($systemInfo['session_count']) }} rows)
                    </button>
                </form>
            </div>
            <div class="settings-card-body">
                <div class="session-info">
                    <p>If you clear the sessions table, you and all other users may be logged out and will need to login again.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- PHP Configuration -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    PHP Configuration
                </h2>
            </div>
            <table class="info-table">
                <tr><td class="label">PHP Version</td><td class="value">{{ $systemInfo['php_version'] }}</td></tr>
                <tr><td class="label">memory_limit</td><td class="value">{{ $systemInfo['memory_limit'] }}</td></tr>
                <tr><td class="label">max_execution_time</td><td class="value">{{ $systemInfo['max_execution_time'] }} seconds</td></tr>
                <tr><td class="label">upload_max_filesize</td><td class="value">{{ $systemInfo['upload_max_filesize'] }}</td></tr>
                <tr><td class="label">post_max_size</td><td class="value">{{ $systemInfo['post_max_size'] }}</td></tr>
                <tr><td class="label">max_input_vars</td><td class="value">{{ $systemInfo['max_input_vars'] }}</td></tr>
                <tr>
                    <td class="label">allow_url_fopen</td>
                    <td class="value">
                        <span class="badge {{ $systemInfo['allow_url_fopen'] ? 'badge-success' : 'badge-danger' }}">
                            {{ $systemInfo['allow_url_fopen'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- PHP Extensions -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                    PHP Extensions
                </h2>
            </div>
            <table class="info-table">
                <thead>
                    <tr><th>Extension</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($phpExtensions as $ext => $status)
                    <tr>
                        <td class="label">{{ $ext }}</td>
                        <td class="value">
                            @if($status['loaded'])
                                <span class="badge badge-success">Enabled {{ $status['version'] ? '(v' . $status['version'] . ')' : '' }}</span>
                            @else
                                <span class="badge badge-danger">Not Installed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Database Information -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                    Database Information
                </h2>
            </div>
            <table class="info-table">
                <tr><td class="label">Database Driver</td><td class="value">{{ $systemInfo['db_driver'] }}</td></tr>
                <tr><td class="label">MySQL Version</td><td class="value">{{ $systemInfo['mysql_version'] }}</td></tr>
                <tr><td class="label">Database Name</td><td class="value">{{ $systemInfo['db_name'] }}</td></tr>
                <tr><td class="label">Database Host</td><td class="value">{{ $systemInfo['db_host'] }}</td></tr>
                <tr><td class="label">Max Connections</td><td class="value">{{ $systemInfo['max_connections'] }}</td></tr>
                <tr><td class="label">Max Packet Size</td><td class="value">{{ $systemInfo['max_packet_size'] }}</td></tr>
                <tr><td class="label">sql_mode</td><td class="value mono">{{ $systemInfo['sql_mode'] }}</td></tr>
            </table>
        </div>

        <!-- Laravel Information -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                    Laravel Information
                </h2>
            </div>
            <table class="info-table">
                <tr><td class="label">Laravel Version</td><td class="value">{{ $systemInfo['laravel_version'] }}</td></tr>
                <tr><td class="label">Cache Driver</td><td class="value">{{ $systemInfo['cache_driver'] }}</td></tr>
                <tr><td class="label">Session Driver</td><td class="value">{{ $systemInfo['session_driver'] }}</td></tr>
                <tr><td class="label">Queue Driver</td><td class="value">{{ $systemInfo['queue_driver'] }}</td></tr>
                <tr><td class="label">Mail Driver</td><td class="value">{{ $systemInfo['mail_driver'] }}</td></tr>
                <tr><td class="label">Filesystem Driver</td><td class="value">{{ $systemInfo['filesystem_driver'] }}</td></tr>
            </table>
        </div>

        <!-- Disk Space -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                    Disk Space
                </h2>
            </div>
            <div class="settings-card-body">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                    <span style="color: var(--text-secondary);">Used: {{ $diskSpace['used'] }}</span>
                    <span style="color: var(--text-secondary);">Free: {{ $diskSpace['free'] }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill {{ $diskSpace['percentage'] > 90 ? 'red' : ($diskSpace['percentage'] > 70 ? 'yellow' : 'blue') }}" 
                         style="width: {{ min($diskSpace['percentage'], 100) }}%"></div>
                </div>
                <p style="margin-top: 8px; font-size: 13px; color: var(--text-muted);">
                    Total: {{ $diskSpace['total'] }} ({{ $diskSpace['percentage'] }}% used)
                </p>
            </div>
        </div>

        <!-- Modules -->
        @if(count($modules) > 0)
        <div class="settings-card">
            <div class="settings-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Installed Modules ({{ count($modules) }})
                </h2>
            </div>
            <table class="info-table">
                <thead>
                    <tr><th>Module</th><th>Version</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                    <tr>
                        <td class="label">{{ $module->name }}</td>
                        <td class="value">{{ $module->version ?? '1.0.0' }}</td>
                        <td class="value">
                            <span class="badge {{ $module->is_active ? 'badge-success' : 'badge-info' }}">
                                {{ $module->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
