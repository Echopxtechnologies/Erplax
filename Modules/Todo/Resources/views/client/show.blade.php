{{-- Todo Module - Client Task Detail --}}
{{-- No layout wrapper needed - ClientController::callAction() handles it automatically --}}

<div class="page-header">
    <div>
        <a href="{{ route('client.todo.index') }}" class="back-link">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tasks
        </a>
        <h1 class="page-title mt-2">{{ $todo->title }}</h1>
    </div>
    <div class="page-actions">
        <span class="priority-badge priority-{{ $todo->priority }}">{{ ucfirst($todo->priority) }} Priority</span>
        <span class="status-badge status-{{ $todo->status }}">{{ ucfirst(str_replace('_', ' ', $todo->status)) }}</span>
        @if($todo->is_overdue)
            <span class="badge badge-danger">Overdue</span>
        @endif
    </div>
</div>

<div class="task-detail-grid">
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Task Details</h3>
        </div>
        <div class="card-body">
            @if($todo->description)
                <div class="task-description">
                    <h4>Description</h4>
                    <div class="description-content">
                        {!! nl2br(e($todo->description)) !!}
                    </div>
                </div>
            @else
                <p class="text-muted">No description provided.</p>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="task-sidebar">
        <!-- Details Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Details</h3>
            </div>
            <div class="card-body">
                <div class="detail-list">
                    <div class="detail-item">
                        <span class="detail-label">Due Date</span>
                        <span class="detail-value {{ $todo->is_overdue ? 'text-danger' : '' }}">
                            {{ $todo->due_date ? $todo->due_date->format('M d, Y') : 'Not set' }}
                            @if($todo->is_overdue)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;vertical-align:middle;">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @endif
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Created</span>
                        <span class="detail-value">{{ $todo->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    
                    @if($todo->completed_at)
                    <div class="detail-item">
                        <span class="detail-label">Completed</span>
                        <span class="detail-value text-success">{{ $todo->completed_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-item">
                        <span class="detail-label">Created By</span>
                        <span class="detail-value">{{ $todo->user->name ?? 'Unknown' }}</span>
                    </div>
                    
                    @if($todo->assignee)
                    <div class="detail-item">
                        <span class="detail-label">Assigned To</span>
                        <span class="detail-value">{{ $todo->assignee->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    @if($todo->status !== 'in_progress')
                        <button class="btn btn-primary w-100 mb-2" onclick="updateStatus('in_progress')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Start Working
                        </button>
                    @endif
                    
                    @if($todo->status !== 'completed')
                        <button class="btn btn-success w-100 mb-2" onclick="updateStatus('completed')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark Complete
                        </button>
                    @endif
                    
                    @if($todo->status === 'completed')
                        <button class="btn btn-warning w-100" onclick="updateStatus('pending')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reopen Task
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: var(--font-sm);
    color: var(--text-muted);
    text-decoration: none;
}
.back-link:hover { color: var(--primary); }
.back-link svg { width: 16px; height: 16px; }

.task-detail-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: var(--space-xl);
}

@media (max-width: 1024px) {
    .task-detail-grid {
        grid-template-columns: 1fr;
    }
}

.priority-badge {
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    font-size: var(--font-sm);
    font-weight: 600;
}
.priority-low { background: var(--success-light); color: var(--success); }
.priority-medium { background: var(--warning-light); color: var(--warning); }
.priority-high { background: var(--danger-light); color: var(--danger); }

.status-badge {
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    font-size: var(--font-sm);
    font-weight: 600;
}
.status-pending { background: var(--warning-light); color: var(--warning); }
.status-in_progress { background: var(--info-light); color: var(--info); }
.status-completed { background: var(--success-light); color: var(--success); }

.task-description h4 {
    font-size: var(--font-sm);
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: var(--space-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.description-content {
    font-size: var(--font-base);
    line-height: 1.7;
    color: var(--text-primary);
}

.detail-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: var(--space-md);
    border-bottom: 1px solid var(--card-border);
}
.detail-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.detail-label {
    font-size: var(--font-sm);
    color: var(--text-muted);
}

.detail-value {
    font-size: var(--font-sm);
    font-weight: 500;
    color: var(--text-primary);
}

.quick-actions .btn {
    justify-content: center;
}
.quick-actions .btn svg {
    width: 16px;
    height: 16px;
}
</style>

<script>
function updateStatus(status) {
    fetch('{{ route('client.todo.toggle-status', $todo->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Toast.success('Task status updated!');
            setTimeout(() => location.reload(), 500);
        } else {
            Toast.error(data.message || 'Failed to update status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        Toast.error('An error occurred');
    });
}
</script>
