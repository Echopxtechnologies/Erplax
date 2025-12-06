<x-layouts.app>
    <style>
        /* Todo Module Styles */
        .todo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .todo-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .todo-header h1 svg {
            width: 28px;
            height: 28px;
            color: var(--primary);
        }
        
        .btn-add-todo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.2s;
        }
        
        .btn-add-todo:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            color: #fff;
        }
        
        .btn-add-todo svg {
            width: 18px;
            height: 18px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
        
        .stat-icon.total { background: var(--primary-light); color: var(--primary); }
        .stat-icon.pending { background: var(--warning-light); color: var(--warning); }
        .stat-icon.progress { background: #e0f2fe; color: #0284c7; }
        .stat-icon.completed { background: var(--success-light); color: var(--success); }
        .stat-icon.overdue { background: var(--danger-light); color: var(--danger); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }
        
        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        
        /* Table Card */
        .table-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .table-card-title svg {
            width: 20px;
            height: 20px;
            color: var(--text-muted);
        }
        
        .table-card-body {
            padding: 0;
        }
        
        /* Admin indicator */
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .admin-badge svg {
            width: 14px;
            height: 14px;
        }
        
        /* Priority badges in table */
        .priority-high { color: var(--danger); font-weight: 600; }
        .priority-medium { color: var(--warning); font-weight: 600; }
        .priority-low { color: var(--success); font-weight: 600; }
        
    </style>

    <div style="padding: 20px;">
        <!-- Header -->
        <div class="todo-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                My Tasks
                @if($isAdmin)
                    <span class="admin-badge">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Admin View - All Users
                    </span>
                @endif
            </h1>
            <a href="{{ route('admin.todo.create') }}" class="btn-add-todo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Task
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pending">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon progress">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['in_progress'] }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon completed">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['completed'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['overdue'] }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Task List
                </div>
            </div>
            <div class="table-card-body">
                <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                       data-route="{{ route('admin.todo.data') }}">
                    <thead>
                        <tr>
                            <th class="dt-sort" data-col="id">ID</th>
                            <th class="dt-sort" data-col="title">Title</th>
                            @if($isAdmin)
                                <th class="dt-sort" data-col="user_name">User</th>
                            @endif
                            <th class="dt-sort" data-col="priority" data-render="priority">Priority</th>
                            <th class="dt-sort" data-col="status" data-render="badge">Status</th>
                            <th class="dt-sort" data-col="due_date" data-render="date">Due Date</th>
                            <th data-render="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @include('core::datatable')
    
    <script>
        // Add custom priority render
        document.addEventListener('DOMContentLoaded', function() {
            // Priority colors are handled via badge classes
        });
    </script>
</x-layouts.app>
