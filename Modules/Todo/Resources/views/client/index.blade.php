{{-- Todo Module - Client Task List --}}
{{-- No layout wrapper needed - ClientController::callAction() handles it automatically --}}

<div class="page-header">
    <div>
        <h1 class="page-title">My Tasks</h1>
        <p class="page-subtitle">View and manage your assigned tasks</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-total" onclick="filterByStatus('')" style="cursor: pointer;">
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Tasks</div>
        </div>
    </div>
    
    <div class="stat-card stat-pending" onclick="filterByStatus('pending')" style="cursor: pointer;">
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    
    <div class="stat-card stat-progress" onclick="filterByStatus('in_progress')" style="cursor: pointer;">
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">In Progress</div>
        </div>
    </div>
    
    <div class="stat-card stat-completed" onclick="filterByStatus('completed')" style="cursor: pointer;">
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['completed'] }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    
    @if($stats['overdue'] > 0)
    <div class="stat-card stat-overdue" onclick="filterByOverdue()" style="cursor: pointer;">
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['overdue'] }}</div>
            <div class="stat-label">Overdue</div>
        </div>
    </div>
    @endif
</div>

<!-- Task List Card -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Task List</h3>
        <div class="header-actions">
            <input type="text" id="searchInput" class="form-control" placeholder="Search tasks..." style="width: 200px;">
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filter-bar">
        <select id="statusFilter" class="form-control" style="width: 150px;">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
        
        <select id="priorityFilter" class="form-control" style="width: 150px;">
            <option value="">All Priority</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
        
        <button type="button" class="btn btn-light" onclick="resetFilters()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reset
        </button>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="taskTable">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTableBody">
                    <tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper" id="paginationWrapper"></div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: var(--space-lg);
    margin-bottom: var(--space-xl);
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    display: flex;
    align-items: center;
    gap: var(--space-md);
    transition: all 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon svg { width: 24px; height: 24px; }

.stat-total .stat-icon { background: var(--primary-light); color: var(--primary); }
.stat-pending .stat-icon { background: var(--warning-light); color: var(--warning); }
.stat-progress .stat-icon { background: var(--info-light); color: var(--info); }
.stat-completed .stat-icon { background: var(--success-light); color: var(--success); }
.stat-overdue .stat-icon { background: var(--danger-light); color: var(--danger); }

.stat-value { font-size: 24px; font-weight: 700; color: var(--text-primary); }
.stat-label { font-size: var(--font-sm); color: var(--text-muted); }

.filter-bar {
    display: flex;
    gap: var(--space-sm);
    padding: var(--space-md) var(--space-lg);
    border-bottom: 1px solid var(--card-border);
    flex-wrap: wrap;
}

.header-actions {
    display: flex;
    gap: var(--space-sm);
}

.priority-badge {
    padding: 3px 8px;
    border-radius: var(--radius-sm);
    font-size: var(--font-xs);
    font-weight: 600;
}
.priority-low { background: var(--success-light); color: var(--success); }
.priority-medium { background: var(--warning-light); color: var(--warning); }
.priority-high { background: var(--danger-light); color: var(--danger); }

.status-badge {
    padding: 3px 8px;
    border-radius: var(--radius-sm);
    font-size: var(--font-xs);
    font-weight: 600;
}
.status-pending { background: var(--warning-light); color: var(--warning); }
.status-in_progress { background: var(--info-light); color: var(--info); }
.status-completed { background: var(--success-light); color: var(--success); }

.overdue-warning {
    color: var(--danger);
    font-size: var(--font-xs);
    display: flex;
    align-items: center;
    gap: 4px;
}
.overdue-warning svg { width: 12px; height: 12px; }

.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--space-sm);
    padding-top: var(--space-lg);
}

.pagination-wrapper button {
    padding: 6px 12px;
    border: 1px solid var(--card-border);
    background: var(--card-bg);
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: var(--font-sm);
}
.pagination-wrapper button:hover:not(:disabled) { background: var(--body-bg); }
.pagination-wrapper button:disabled { opacity: 0.5; cursor: not-allowed; }
.pagination-wrapper .page-info { font-size: var(--font-sm); color: var(--text-muted); }

.btn-action {
    padding: 4px 8px;
    border: none;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    transition: all 0.15s;
}
.btn-action:hover { background: var(--primary); color: #fff; }
</style>

<script>
let currentPage = 1;
let currentFilters = { search: '', status: '', priority: '', overdue: '' };
let debounceTimer;

document.addEventListener('DOMContentLoaded', function() {
    loadTasks();
    
    // Search with debounce
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentFilters.search = this.value;
            currentPage = 1;
            loadTasks();
        }, 300);
    });
    
    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentFilters.status = this.value;
        currentPage = 1;
        loadTasks();
    });
    
    // Priority filter
    document.getElementById('priorityFilter').addEventListener('change', function() {
        currentFilters.priority = this.value;
        currentPage = 1;
        loadTasks();
    });
});

function loadTasks() {
    const params = new URLSearchParams({
        page: currentPage,
        per_page: 10,
        search: currentFilters.search,
        status: currentFilters.status,
        priority: currentFilters.priority,
        overdue: currentFilters.overdue
    });
    
    fetch(`{{ route('client.todo.data') }}?${params}`)
        .then(res => res.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(err => {
            console.error('Error loading tasks:', err);
            document.getElementById('taskTableBody').innerHTML = 
                '<tr><td colspan="5" class="text-center text-danger">Error loading tasks</td></tr>';
        });
}

function renderTable(items) {
    const tbody = document.getElementById('taskTableBody');
    
    if (!items.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No tasks found</td></tr>';
        return;
    }
    
    tbody.innerHTML = items.map(item => `
        <tr>
            <td>
                <a href="${item._show_url}" style="font-weight: 500;">${escapeHtml(item.title)}</a>
                ${item.is_overdue ? `
                    <div class="overdue-warning">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Overdue
                    </div>
                ` : ''}
            </td>
            <td><span class="priority-badge priority-${item.priority}">${capitalize(item.priority)}</span></td>
            <td>${item.due_date || '-'}</td>
            <td><span class="status-badge status-${item.status}">${formatStatus(item.status)}</span></td>
            <td>
                <a href="${item._show_url}" class="btn-action">View</a>
            </td>
        </tr>
    `).join('');
}

function renderPagination(data) {
    const wrapper = document.getElementById('paginationWrapper');
    
    if (data.last_page <= 1) {
        wrapper.innerHTML = '';
        return;
    }
    
    wrapper.innerHTML = `
        <button onclick="goToPage(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''}>Previous</button>
        <span class="page-info">Page ${data.current_page} of ${data.last_page}</span>
        <button onclick="goToPage(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''}>Next</button>
    `;
}

function goToPage(page) {
    currentPage = page;
    loadTasks();
}

function filterByStatus(status) {
    currentFilters.status = status;
    currentFilters.overdue = '';
    document.getElementById('statusFilter').value = status;
    currentPage = 1;
    loadTasks();
}

function filterByOverdue() {
    currentFilters.overdue = '1';
    currentFilters.status = '';
    document.getElementById('statusFilter').value = '';
    currentPage = 1;
    loadTasks();
}

function resetFilters() {
    currentFilters = { search: '', status: '', priority: '', overdue: '' };
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    currentPage = 1;
    loadTasks();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatStatus(status) {
    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
}
</script>
