<x-layouts.app>
    <style>
        .cron-page { max-width: 1200px; margin: 0 auto; }
        .cron-header { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; }
        .cron-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .cron-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
        .header-actions { display: flex; gap: 10px; }
        
        .cron-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
        .cron-card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); display: flex; align-items: center; justify-content: space-between; }
        .cron-card-header h2 { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .cron-card-header svg { width: 20px; height: 20px; color: var(--primary); }
        .cron-card-body { padding: 20px; }
        
        /* Command Box */
        .cron-command-box { background: #1e293b; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .cron-command-box:last-child { margin-bottom: 0; }
        .cron-command-label { font-size: 12px; color: #94a3b8; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .cron-command { font-family: 'Monaco', 'Menlo', 'Consolas', monospace; font-size: 13px; color: #22d3ee; word-break: break-all; line-height: 1.6; }
        .cron-command-desc { font-size: 12px; color: #64748b; margin-top: 8px; }
        .cron-command-copy { display: flex; align-items: center; gap: 10px; margin-top: 12px; }
        
        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-success { background: #22c55e; color: #fff; }
        .btn-success:hover { background: #16a34a; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: var(--card-border); color: var(--text-primary); }
        .btn-secondary:hover { background: var(--text-muted); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-sm svg { width: 14px; height: 14px; }
        
        /* Status Grid */
        .status-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .status-item { background: var(--body-bg); border-radius: 8px; padding: 16px; text-align: center; }
        .status-label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; text-transform: uppercase; }
        .status-value { font-size: 20px; font-weight: 700; color: var(--text-primary); }
        .status-value.success { color: #22c55e; }
        .status-value.warning { color: #f59e0b; }
        
        /* Table */
        .cron-table { width: 100%; border-collapse: collapse; }
        .cron-table th, .cron-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--card-border); }
        .cron-table th { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; background: var(--body-bg); }
        .cron-table td { font-size: 14px; color: var(--text-primary); }
        .cron-table tr:hover { background: var(--body-bg); }
        .cron-name { font-weight: 600; }
        .cron-method { font-size: 12px; color: var(--text-muted); font-family: monospace; }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; font-size: 11px; font-weight: 600; border-radius: 20px; }
        .badge-success { background: rgba(34,197,94,0.1); color: #22c55e; }
        .badge-danger { background: rgba(239,68,68,0.1); color: #ef4444; }
        .badge-warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .badge-info { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .badge-gray { background: rgba(107,114,128,0.1); color: #6b7280; }
        
        /* Toggle Switch */
        .toggle-switch { position: relative; width: 44px; height: 24px; display: inline-block; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: var(--card-border); border-radius: 24px; transition: 0.3s; }
        .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; }
        .toggle-switch input:checked + .toggle-slider { background: #22c55e; }
        .toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }
        
        /* Tabs */
        .tabs { display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 2px solid var(--card-border); overflow-x: auto; }
        .tab { padding: 12px 24px; font-size: 14px; font-weight: 500; color: var(--text-secondary); cursor: pointer; border: none; background: none; border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; display: flex; align-items: center; gap: 8px; }
        .tab:hover { color: var(--text-primary); background: var(--body-bg); }
        .tab.active { color: var(--primary); border-bottom-color: var(--primary); }
        .tab svg { width: 18px; height: 18px; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Alert */
        .alert { border-radius: 8px; padding: 12px 16px; font-size: 14px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 10px; }
        .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px; }
        .alert-info { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); color: var(--primary); }
        .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #22c55e; }
        
        /* Actions */
        .action-btns { display: flex; gap: 6px; }
        
        /* Empty State */
        .empty-state { padding: 60px 20px; text-align: center; color: var(--text-muted); }
        .empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5; }
        .empty-state p { margin: 0 0 16px; }
        
        /* Log entry */
        .log-time { font-size: 12px; color: var(--text-muted); }
        .log-message { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        @media (max-width: 768px) {
            .cron-header { flex-direction: column; align-items: flex-start; }
            .header-actions { width: 100%; }
            .header-actions .btn { flex: 1; justify-content: center; }
            .status-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    <div class="cron-page">
        <!-- Header -->
        <div class="cron-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Cron Job
            </h1>
            <div class="header-actions">
                <a href="{{ route('admin.cronjob.create') }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Cron Job
                </a>
                <form action="{{ route('admin.cronjob.run-all') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Run Cron Manually
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button type="button" class="tab active" data-tab="command">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Command
            </button>
            <button type="button" class="tab" data-tab="tasks">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Cron Jobs ({{ $cronJobs->count() }})
            </button>
            <button type="button" class="tab" data-tab="logs">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Recent Logs
            </button>
        </div>

        <!-- Command Tab -->
        <div class="tab-content active" id="tab-command">
            <div class="cron-card">
                <div class="cron-card-header">
                    <h2>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Cron Command
                    </h2>
                </div>
                <div class="cron-card-body">
                    <div class="alert alert-info">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong>Setup Instructions:</strong> Add ONE of these commands to your server's crontab.
                            The Laravel Scheduler is recommended if you have SSH access.
                        </div>
                    </div>

                    @foreach($commands as $key => $cmd)
                        <div class="cron-command-box">
                            <div class="cron-command-label">
                                {{ $cmd['label'] }}
                                @if($key === 'laravel')
                                    <span class="badge badge-success">Recommended</span>
                                @endif
                            </div>
                            <div class="cron-command" id="cmd-{{ $key }}">{{ $cmd['command'] }}</div>
                            <div class="cron-command-desc">{{ $cmd['description'] }}</div>
                            <div class="cron-command-copy">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="copyCommand('cmd-{{ $key }}')">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div class="cron-card">
                <div class="cron-card-header">
                    <h2>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Status
                    </h2>
                </div>
                <div class="cron-card-body">
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="status-label">Last Run</div>
                            <div class="status-value {{ $status['last_run'] ? 'success' : 'warning' }}">
                                {{ $status['last_run'] ? \Carbon\Carbon::parse($status['last_run'])->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Last Duration</div>
                            <div class="status-value">{{ $status['last_duration'] ? $status['last_duration'] . 'ms' : '-' }}</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Total Jobs</div>
                            <div class="status-value">{{ $status['total_jobs'] }}</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Active Jobs</div>
                            <div class="status-value success">{{ $status['active_jobs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Tab -->
        <div class="tab-content" id="tab-tasks">
            <div class="cron-card">
                <div class="cron-card-header">
                    <h2>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Registered Cron Jobs
                    </h2>
                    <a href="{{ route('admin.cronjob.create') }}" class="btn btn-primary btn-sm">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New
                    </a>
                </div>
                <div class="cron-card-body" style="padding: 0;">
                    @if($cronJobs->count() > 0)
                        <table class="cron-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Schedule</th>
                                    <th>Last Run</th>
                                    <th>Status</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cronJobs as $cron)
                                    <tr>
                                        <td>
                                            <div class="cron-name">{{ $cron->name }}</div>
                                            <div class="cron-method">{{ $cron->method }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $cron->schedule)) }}</span>
                                        </td>
                                        <td>
                                            @if($cron->last_run)
                                                <div>{{ $cron->last_run->diffForHumans() }}</div>
                                                @if($cron->last_status === 'success')
                                                    <span class="badge badge-success">Success</span>
                                                @elseif($cron->last_status === 'failed')
                                                    <span class="badge badge-danger">Failed</span>
                                                @endif
                                            @else
                                                <span style="color: var(--text-muted);">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cron->last_duration)
                                                {{ $cron->last_duration_formatted }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.cronjob.toggle', $cron) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <label class="toggle-switch">
                                                    <input type="checkbox" {{ $cron->status ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                <form action="{{ route('admin.cronjob.run-single', $cron) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Run Now">
                                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('admin.cronjob.show', $cron) }}" class="btn btn-secondary btn-sm" title="View Logs">
                                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.cronjob.edit', $cron) }}" class="btn btn-secondary btn-sm" title="Edit">
                                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.cronjob.destroy', $cron) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this cron job?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>No cron jobs registered yet.</p>
                            <a href="{{ route('admin.cronjob.create') }}" class="btn btn-primary">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add First Cron Job
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Cron Classes -->
            @if(!empty($availableCrons))
                <div class="cron-card">
                    <div class="cron-card-header">
                        <h2>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Available Cron Classes
                        </h2>
                    </div>
                    <div class="cron-card-body">
                        <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 14px;">
                            These classes are available in <code>app/Crons/</code> directory and can be used when creating cron jobs.
                        </p>
                        <table class="cron-table">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Available Methods</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableCrons as $class => $methods)
                                    <tr>
                                        <td><code>{{ $class }}</code></td>
                                        <td>
                                            @foreach($methods as $method)
                                                <span class="badge badge-gray" style="margin: 2px;">{{ $class }}/{{ $method }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Logs Tab -->
        <div class="tab-content" id="tab-logs">
            <div class="cron-card">
                <div class="cron-card-header">
                    <h2>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Recent Execution Logs
                    </h2>
                    <form action="{{ route('admin.cronjob.clear-logs') }}" method="POST" onsubmit="return confirm('Clear logs older than 7 days?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clear Old Logs
                        </button>
                    </form>
                </div>
                <div class="cron-card-body" style="padding: 0;">
                    @if($recentLogs->count() > 0)
                        <table class="cron-table">
                            <thead>
                                <tr>
                                    <th>Cron Job</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Duration</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->cronJob->name ?? 'Unknown' }}</td>
                                        <td>
                                            @if($log->status === 'success')
                                                <span class="badge badge-success">Success</span>
                                            @elseif($log->status === 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-warning">Running</span>
                                            @endif
                                        </td>
                                        <td class="log-message" title="{{ $log->message }}">
                                            {{ Str::limit($log->message, 50) }}
                                        </td>
                                        <td>{{ $log->execution_time_formatted }}</td>
                                        <td class="log-time">{{ $log->created_at->format('M d, H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No logs yet. Run the cron to see execution logs.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });

        // Copy command
        function copyCommand(id) {
            const text = document.getElementById(id).textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px"><path d="M5 13l4 4L19 7"></path></svg> Copied!';
                btn.style.background = '#22c55e';
                btn.style.color = '#fff';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
    </script>
</x-layouts.app>