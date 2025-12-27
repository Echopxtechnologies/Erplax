<style>
    /* Service Module Styles */
    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .service-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .service-header h1 svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }
    
    .btn-add-service {
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
    
    .btn-add-service:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: #fff;
    }
    
    .btn-add-service svg {
        width: 18px;
        height: 18px;
    }
    
    /* Stats Cards - Compact */
    .stats-grid {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
        cursor: pointer;
        flex: 1;
        min-width: 140px;
    }
    
    .stat-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .stat-card.active {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon svg {
        width: 18px;
        height: 18px;
    }
    
    .stat-icon.total { background: var(--primary-light); color: var(--primary); }
    .stat-icon.active { background: var(--success-light); color: var(--success); }
    .stat-icon.pending { background: var(--warning-light); color: var(--warning); }
    .stat-icon.completed { background: #e0f2fe; color: #0284c7; }
    .stat-icon.overdue { background: var(--danger-light); color: var(--danger); }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .stat-label {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
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
        flex-wrap: wrap;
        gap: 12px;
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
    
    /* Filter Section */
    .filter-section {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--input-border);
        border-radius: var(--radius-md);
        font-size: 13px;
        background: var(--input-bg);
        color: var(--input-text);
        cursor: pointer;
        min-width: 140px;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    /* Status badges */
    .status-active { color: var(--success); font-weight: 600; }
    .status-inactive { color: var(--text-muted); font-weight: 600; }
    
    /* Service status badges */
    .service-status-draft { color: var(--text-muted); }
    .service-status-pending { color: var(--warning); }
    .service-status-completed { color: var(--success); }
    .service-status-overdue { color: var(--danger); }
    .service-status-canceled { color: var(--text-secondary); }
    
    /* Overdue row highlight */
    .dt-table tbody tr.overdue-row {
        background: rgba(229, 62, 62, 0.05);
    }
    
    .dt-table tbody tr.overdue-row:hover {
        background: rgba(229, 62, 62, 0.1);
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="service-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Services
        </h1>
        <a href="{{ route('admin.service.create') }}" class="btn-add-service">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Service Contract
        </a>
    </div>

    <!-- Stats Cards (Clickable Filters) -->
    <div class="stats-grid">
        <div class="stat-card" data-filter="all" onclick="filterByServiceStatus('')">
            <div class="stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Services</div>
            </div>
        </div>
        
        <div class="stat-card" data-filter="active" onclick="filterByStatus('active')">
            <div class="stat-icon active">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        
        <div class="stat-card" data-filter="pending" onclick="filterByServiceStatus('pending')">
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
        
        <div class="stat-card" data-filter="completed" onclick="filterByServiceStatus('completed')">
            <div class="stat-icon completed">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        
        <div class="stat-card" data-filter="overdue" onclick="filterByOverdue()">
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
                Service List
            </div>
            
            <!-- Filters -->
            <div class="filter-section">
                <select class="filter-select" id="statusFilter" data-dt-filter="status" data-dt-table="serviceTable">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                
                <select class="filter-select" id="serviceStatusFilter" data-dt-filter="service_status" data-dt-table="serviceTable">
                    <option value="">All Service Status</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="overdue">Overdue</option>
                    <option value="canceled">Canceled</option>
                </select>
                
                <select class="filter-select" id="frequencyFilter" data-dt-filter="service_frequency" data-dt-table="serviceTable">
                    <option value="">All Frequency</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="half_yearly">Half Yearly</option>
                    <option value="yearly">Yearly</option>
                    <option value="custom">Custom</option>
                </select>
                
                @if($clients->count() > 0)
                <select class="filter-select" id="clientFilter" data-dt-filter="client_id" data-dt-table="serviceTable">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company ?? $client->name }}</option>
                    @endforeach
                </select>
                @endif
            </div>
        </div>
        <div class="table-card-body">
            <!-- DataTable with all features -->
            <table id="serviceTable" 
                   class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.service.data') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="id">ID</th>
                        <th data-col="client_name" class="dt-sort dt-clickable" >Client</th>
                        <th class="dt-sort dt-clickable" data-col="machine_name">Machine Name</th>
                        <th data-col="equipment_no">Equipment No</th>
                        <th data-col="model_no">Model No</th>
                        <th class="dt-sort" data-col="service_frequency" data-render="frequency">Frequency</th>
                        <th class="dt-sort" data-col="next_service_date" data-render="next_date">Next Service</th>
                        <th class="dt-sort" data-col="status" data-render="status">Status</th>
                        <th class="dt-sort" data-col="service_status" data-render="service_status">Service Status</th>
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
    // Custom renders
    window.dtRenders = window.dtRenders || {};
    
    // Frequency render
    window.dtRenders.frequency = function(value, row) {
        var labels = {
            'monthly': 'Monthly',
            'quarterly': 'Quarterly',
            'half_yearly': 'Half Yearly',
            'yearly': 'Yearly',
            'custom': 'Custom'
        };
        return '<span style="text-transform:capitalize;">' + (labels[value] || value || '-') + '</span>';
    };
    
    // Status render
    window.dtRenders.status = function(value, row) {
        var colors = {
            'active': '#16a34a',
            'inactive': '#6b7280'
        };
        var color = colors[value] || '#6b7280';
        return '<span style="color:' + color + ';font-weight:600;text-transform:capitalize;">' + (value || '-') + '</span>';
    };
    
    // Service status render
    window.dtRenders.service_status = function(value, row) {
        var colors = {
            'draft': '#6b7280',
            'pending': '#d97706',
            'completed': '#16a34a',
            'overdue': '#dc2626',
            'canceled': '#9ca3af'
        };
        var color = colors[value] || '#6b7280';
        var label = value ? value.replace('_', ' ') : '-';
        return '<span style="color:' + color + ';font-weight:600;text-transform:capitalize;">' + label + '</span>';
    };
    
    // Next service date render with overdue highlight
    window.dtRenders.next_date = function(value, row) {
        if (!value) return '-';
        
        var date = new Date(value);
        var formatted = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        
        if (row.is_overdue) {
            return '<span style="color:#dc2626;font-weight:600;">' + formatted + ' ⚠️</span>';
        }
        return formatted;
    };
    
    // Filter by status
    function filterByStatus(status) {
        document.getElementById('statusFilter').value = status;
        document.getElementById('serviceStatusFilter').value = '';
        
        var event = new Event('change', { bubbles: true });
        document.getElementById('statusFilter').dispatchEvent(event);
        
        updateActiveCard(status || 'all');
    }
    
    // Filter by service status
    function filterByServiceStatus(status) {
        document.getElementById('serviceStatusFilter').value = status;
        document.getElementById('statusFilter').value = '';
        
        var event = new Event('change', { bubbles: true });
        document.getElementById('serviceStatusFilter').dispatchEvent(event);
        
        updateActiveCard(status || 'all');
    }
    
    // Filter by overdue
    function filterByOverdue() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('serviceStatusFilter').value = '';
        
        var table = document.getElementById('serviceTable');
        if (table && table.dtSetFilter) {
            table.dtSetFilter('overdue', '1');
        }
        
        updateActiveCard('overdue');
    }
    
    // Update active card
    function updateActiveCard(filter) {
        document.querySelectorAll('.stat-card').forEach(function(card) {
            card.classList.remove('active');
            if (card.dataset.filter === filter) {
                card.classList.add('active');
            }
        });
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        var allCard = document.querySelector('.stat-card[data-filter="all"]');
        if (allCard) {
            allCard.classList.add('active');
        }
    });
</script>
