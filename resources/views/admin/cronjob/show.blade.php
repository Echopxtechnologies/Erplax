<x-layouts.app>
    <style>
        .show-page { max-width: 1000px; margin: 0 auto; }
        .show-header { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; }
        .show-header-content h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px; display: flex; align-items: center; gap: 10px; }
        .show-header-content h1 svg { width: 28px; height: 28px; color: var(--primary); }
        .show-header-content p { color: var(--text-muted); margin: 0; font-family: monospace; font-size: 14px; }
        .header-actions { display: flex; gap: 10px; }
        
        .show-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
        .show-card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); display: flex; align-items: center; justify-content: space-between; }
        .show-card-header h2 { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .show-card-header svg { width: 20px; height: 20px; color: var(--primary); }
        .show-card-body { padding: 20px; }
        
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .info-item { }
        .info-label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; text-transform: uppercase; font-weight: 600; }
        .info-value { font-size: 16px; font-weight: 600; color: var(--text-primary); }
        .info-value.success { color: #22c55e; }
        .info-value.danger { color: #ef4444; }
        .info-value.muted { color: var(--text-muted); font-weight: normal; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-success { background: #22c55e; color: #fff; }
        .btn-success:hover { background: #16a34a; }
        .btn-secondary { background: var(--card-border); color: var(--text-primary); }
        .btn-secondary:hover { background: var(--text-muted); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-sm svg { width: 14px; height: 14px; }
        
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; font-size: 11px; font-weight: 600; border-radius: 20px; }
        .badge-success { background: rgba(34,197,94,0.1); color: #22c55e; }
        .badge-danger { background: rgba(239,68,68,0.1); color: #ef4444; }
        .badge-warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .badge-info { background: rgba(59,130,246,0.1); color: #3b82f6; }
        
        .logs-table { width: 100%; border-collapse: collapse; }
        .logs-table th, .logs-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--card-border); }
        .logs-table th { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; background: var(--body-bg); }
        .logs-table td { font-size: 14px; color: var(--text-primary); }
        .logs-table tr:hover { background: var(--body-bg); }
        
        .log-message { max-width: 400px; }
        .log-message-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .log-message-full { display: none; font-size: 13px; color: var(--text-secondary); margin-top: 8px; padding: 10px; background: var(--body-bg); border-radius: 6px; white-space: pre-wrap; word-break: break-word; }
        .log-message.expanded .log-message-full { display: block; }
        .log-message.expanded .log-message-text { white-space: normal; }
        
        .log-time { font-size: 13px; color: var(--text-muted); }
        
        .empty-state { padding: 60px 20px; text-align: center; color: var(--text-muted); }
        .empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5; }
        
        .pagination-wrapper { padding: 16px 20px; border-top: 1px solid var(--card-border); }
    </style>

    <div class="show-page">
        <!-- Header -->
        <div class="show-header">
            <div class="show-header-content">
                <h1>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $cronjob->name }}
                </h1>
                <p>{{ $cronjob->method }}</p>
            </div>
            <div class="header-actions">
                <form action="{{ route('admin.cronjob.run-single', $cronjob) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Run Now
                    </button>
                </form>
                <a href="{{ route('admin.cronjob.edit', $cronjob) }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.cronjob.index') }}" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Details Card -->
        <div class="show-card">
            <div class="show-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Details
                </h2>
            </div>
            <div class="show-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Schedule</div>
                        <div class="info-value">
                            <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $cronjob->schedule)) }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($cronjob->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Run</div>
                        <div class="info-value {{ $cronjob->last_run ? '' : 'muted' }}">
                            {{ $cronjob->last_run ? $cronjob->last_run->format('M d, Y H:i:s') : 'Never' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Status</div>
                        <div class="info-value">
                            @if($cronjob->last_status === 'success')
                                <span class="badge badge-success">Success</span>
                            @elseif($cronjob->last_status === 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @else
                                <span class="info-value muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Duration</div>
                        <div class="info-value {{ $cronjob->last_duration ? '' : 'muted' }}">
                            {{ $cronjob->last_duration_formatted }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Created</div>
                        <div class="info-value muted">
                            {{ $cronjob->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                @if($cronjob->description)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--card-border);">
                        <div class="info-label">Description</div>
                        <p style="margin: 8px 0 0; color: var(--text-secondary);">{{ $cronjob->description }}</p>
                    </div>
                @endif

                @if($cronjob->last_message)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--card-border);">
                        <div class="info-label">Last Message</div>
                        <p style="margin: 8px 0 0; color: var(--text-secondary); font-family: monospace; font-size: 13px;">{{ $cronjob->last_message }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Logs Card -->
        <div class="show-card">
            <div class="show-card-header">
                <h2>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Execution Logs
                </h2>
            </div>
            <div class="show-card-body" style="padding: 0;">
                @if($logs->count() > 0)
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Duration</th>
                                <th>Started</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        @if($log->status === 'success')
                                            <span class="badge badge-success">Success</span>
                                        @elseif($log->status === 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-warning">Running</span>
                                        @endif
                                    </td>
                                    <td class="log-message" onclick="this.classList.toggle('expanded')">
                                        <div class="log-message-text" title="{{ $log->message }}">
                                            {{ Str::limit($log->message, 60) }}
                                        </div>
                                        @if(strlen($log->message) > 60)
                                            <div class="log-message-full">{{ $log->message }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $log->execution_time_formatted }}</td>
                                    <td class="log-time">
                                        {{ $log->started_at ? $log->started_at->format('M d, H:i:s') : '-' }}
                                    </td>
                                    <td class="log-time">
                                        {{ $log->completed_at ? $log->completed_at->format('M d, H:i:s') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($logs->hasPages())
                        <div class="pagination-wrapper">
                            {{ $logs->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No execution logs yet.</p>
                        <form action="{{ route('admin.cronjob.run-single', $cronjob) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                Run Now
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>