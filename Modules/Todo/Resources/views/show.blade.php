<style>
    .show-page {
        max-width: 700px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .show-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .btn-back {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-back:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .btn-back svg {
        width: 20px;
        height: 20px;
    }
    
    .show-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        flex: 1;
    }
    
    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--warning);
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .btn-edit:hover {
        background: #d97706;
        color: #fff;
    }
    
    .btn-edit svg {
        width: 18px;
        height: 18px;
    }
    
    .show-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .show-card-header {
        padding: 24px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .task-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 16px 0;
        line-height: 1.4;
    }
    
    .task-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .task-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .task-badge svg {
        width: 14px;
        height: 14px;
    }
    
    .badge-status-pending { background: var(--warning-light); color: var(--warning); }
    .badge-status-in_progress { background: #e0f2fe; color: #0284c7; }
    .badge-status-completed { background: var(--success-light); color: var(--success); }
    
    .badge-priority-low { background: var(--success-light); color: var(--success); }
    .badge-priority-medium { background: var(--warning-light); color: var(--warning); }
    .badge-priority-high { background: var(--danger-light); color: var(--danger); }
    
    .badge-overdue { background: var(--danger-light); color: var(--danger); }
    
    .show-card-body {
        padding: 24px;
    }
    
    .detail-section {
        margin-bottom: 24px;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
    }
    
    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .detail-value {
        font-size: 15px;
        color: var(--text-primary);
        line-height: 1.6;
    }
    
    .detail-value.empty {
        color: var(--text-muted);
        font-style: italic;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 24px;
    }
    
    /* Meta Footer */
    .show-card-footer {
        padding: 16px 24px;
        background: var(--body-bg);
        border-top: 1px solid var(--card-border);
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .meta-item svg {
        width: 16px;
        height: 16px;
    }
    
    /* Progress indicator for status */
    .progress-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--card-border);
    }
    
    .progress-step {
        flex: 1;
        height: 6px;
        background: var(--body-bg);
        border-radius: 3px;
        position: relative;
    }
    
    .progress-step.active {
        background: var(--primary);
    }
    
    .progress-step.completed {
        background: var(--success);
    }
    
    .progress-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
    }
    
    .progress-label {
        font-size: 11px;
        color: var(--text-muted);
    }
    
    .progress-label.active {
        color: var(--primary);
        font-weight: 600;
    }
</style>

<div class="show-page">
    <!-- Header -->
    <div class="show-header">
        <a href="{{ route('admin.todo.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1>Task Details</h1>
        <a href="{{ route('admin.todo.edit', $todo->id) }}" class="btn-edit">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
    </div>

    <!-- Show Card -->
    <div class="show-card">
        <div class="show-card-header">
            <h2 class="task-title">{{ $todo->title }}</h2>
            <div class="task-badges">
                <span class="task-badge badge-status-{{ $todo->status }}">
                    @if($todo->status == 'pending')
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @elseif($todo->status == 'in_progress')
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    @else
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                    {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                </span>
                
                <span class="task-badge badge-priority-{{ $todo->priority }}">
                    @if($todo->priority == 'high')
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    @elseif($todo->priority == 'medium')
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 12H4"></path>
                        </svg>
                    @else
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    @endif
                    {{ ucfirst($todo->priority) }} Priority
                </span>
                
                @if($todo->due_date && $todo->due_date->isPast() && $todo->status !== 'completed')
                    <span class="task-badge badge-overdue">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Overdue
                    </span>
                @endif
            </div>
            
            <!-- Progress Indicator -->
            <div class="progress-indicator">
                <div class="progress-step {{ in_array($todo->status, ['pending', 'in_progress', 'completed']) ? 'completed' : '' }}"></div>
                <div class="progress-step {{ in_array($todo->status, ['in_progress', 'completed']) ? 'completed' : '' }}"></div>
                <div class="progress-step {{ $todo->status == 'completed' ? 'completed' : '' }}"></div>
            </div>
            <div class="progress-labels">
                <span class="progress-label {{ $todo->status == 'pending' ? 'active' : '' }}">Pending</span>
                <span class="progress-label {{ $todo->status == 'in_progress' ? 'active' : '' }}">In Progress</span>
                <span class="progress-label {{ $todo->status == 'completed' ? 'active' : '' }}">Completed</span>
            </div>
        </div>
        
        <div class="show-card-body">
            <!-- Description -->
            <div class="detail-section">
                <div class="detail-label">Description</div>
                <div class="detail-value {{ empty($todo->description) ? 'empty' : '' }}">
                    {{ $todo->description ?: 'No description provided' }}
                </div>
            </div>
            
            <!-- Details Grid -->
            <div class="detail-grid">
                <div class="detail-section">
                    <div class="detail-label">Assigned To</div>
                    <div class="detail-value">{{ $todo->user->name ?? 'Unknown' }}</div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value {{ empty($todo->due_date) ? 'empty' : '' }}">
                        {{ $todo->due_date?->format('F d, Y') ?: 'No due date' }}
                    </div>
                </div>
                
                @if($todo->completed_at)
                <div class="detail-section">
                    <div class="detail-label">Completed On</div>
                    <div class="detail-value">{{ $todo->completed_at->format('F d, Y') }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="show-card-footer">
            <div class="meta-item">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Created: {{ $todo->created_at->format('M d, Y \a\t h:i A') }}
            </div>
            <div class="meta-item">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Updated: {{ $todo->updated_at->format('M d, Y \a\t h:i A') }}
            </div>
        </div>
    </div>
</div>
