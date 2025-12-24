
<style>
    .estimation-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .est-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .est-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .est-header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    
    .est-header-icon svg { width: 22px; height: 22px; }
    .est-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .est-header-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.2s;
    }
    .btn-add:hover { background: var(--primary-hover); color: #fff; }
    .btn-add svg { width: 16px; height: 16px; }
    
    .est-stats-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    
    .est-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 10px 16px;
        border-radius: 8px;
        min-width: 140px;
    }
    
    .est-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .est-stat-icon svg { width: 16px; height: 16px; }
    .est-stat-icon.total { background: #ede9fe; color: #7c3aed; }
    .est-stat-icon.draft { background: #f3f4f6; color: #6b7280; }
    .est-stat-icon.sent { background: #dbeafe; color: #2563eb; }
    .est-stat-icon.accepted { background: #ecfdf5; color: #059669; }
    .est-stat-icon.declined { background: #fef2f2; color: #dc2626; }
    .est-stat-icon.expired { background: #fef3c7; color: #d97706; }
    
    .est-stat-value { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1; }
    .est-stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }
    
    .est-filter-select {
        padding: 7px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 130px;
    }
    
    .est-table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    .est-table-wrap table { min-width: 900px; }
    .est-table-wrap .btn { padding: 4px 10px !important; font-size: 11px !important; border-radius: 4px !important; }
    .est-table-wrap td:last-child .btn { padding: 3px 8px !important; font-size: 10px !important; margin: 1px !important; }
    
    .est-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }
    .est-badge.draft { background: #f3f4f6; color: #6b7280; }
    .est-badge.sent { background: #dbeafe; color: #1d4ed8; }
    .est-badge.accepted { background: #ecfdf5; color: #059669; }
    .est-badge.declined { background: #fef2f2; color: #dc2626; }
    .est-badge.expired { background: #fef3c7; color: #92400e; }
    .est-badge.invoiced { background: #ede9fe; color: #7c3aed; }
    
    .estimation-number { font-weight: 600; color: #7c3aed; font-size: 13px; }
    .amount-display { font-weight: 600; font-size: 13px; color: var(--text-primary); }
    .est-table-wrap td { white-space: nowrap; }
</style>

<div class="estimation-container">
    <div class="est-header">
        <div class="est-header-left">
            <div class="est-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1>Estimations</h1>
                <div class="est-header-sub">Create and manage cost estimations</div>
            </div>
        </div>
        <a href="{{ route('admin.sales.estimations.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Estimation
        </a>
    </div>

    <div class="est-stats-bar">
        <div class="est-stat">
            <div class="est-stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="est-stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="est-stat-label">Total</div>
            </div>
        </div>
        <div class="est-stat">
            <div class="est-stat-icon draft">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <div class="est-stat-value">{{ $stats['draft'] ?? 0 }}</div>
                <div class="est-stat-label">Draft</div>
            </div>
        </div>
        <div class="est-stat">
            <div class="est-stat-icon sent">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
            <div>
                <div class="est-stat-value">{{ $stats['sent'] ?? 0 }}</div>
                <div class="est-stat-label">Sent</div>
            </div>
        </div>
        <div class="est-stat">
            <div class="est-stat-icon accepted">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <div class="est-stat-value">{{ $stats['accepted'] ?? 0 }}</div>
                <div class="est-stat-label">Accepted</div>
            </div>
        </div>
        @if(($stats['declined'] ?? 0) > 0)
        <div class="est-stat">
            <div class="est-stat-icon declined">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <div class="est-stat-value">{{ $stats['declined'] }}</div>
                <div class="est-stat-label">Declined</div>
            </div>
        </div>
        @endif
    </div>

    <div class="est-table-wrap">
        <div style="padding: 12px 16px; border-bottom: 1px solid var(--card-border);">
            <select class="est-filter-select" data-dt-filter="status">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="accepted">Accepted</option>
                <option value="declined">Declined</option>
                <option value="expired">Expired</option>
            </select>
        </div>
        <table class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
               id="estimationsTable"
               data-route="{{ route('admin.sales.estimations.data') }}">
            <thead>
                <tr>
                    <th class="dt-sort dt-clickable" data-col="id">ID</th>
                    <th class="dt-sort dt-clickable" data-col="estimation_number" data-render="estimation_number">Estimation #</th>
                    <th class="dt-sort" data-col="subject">Subject</th>
                    <th class="dt-sort" data-col="customer_name">Customer</th>
                    <th class="dt-sort" data-col="date">Date</th>
                    <th class="dt-sort" data-col="valid_until">Valid Until</th>
                    <th class="dt-sort" data-col="total" data-render="total">Total</th>
                    <th data-col="status" data-render="status">Status</th>
                    <th data-render="actions" style="min-width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.estimation_number = function(data, row) {
    return '<span class="estimation-number">' + row.estimation_number + '</span>';
};

window.dtRenders.total = function(data, row) {
    return '<span class="amount-display">' + row.total + '</span>';
};

window.dtRenders.status = function(data, row) {
    var status = row.status || 'draft';
    var label = status.charAt(0).toUpperCase() + status.slice(1);
    return '<span class="est-badge ' + status + '">' + label + '</span>';
};

window.dtRenders.actions = function(data, row) {
    var html = '<a href="/admin/sales/estimations/' + row.id + '" class="btn btn-sm btn-info">View</a> ' +
               '<a href="/admin/sales/estimations/' + row.id + '/edit" class="btn btn-sm btn-warning">Edit</a> ';
    if (row.status === 'accepted') {
        html += '<a href="/admin/sales/invoices/from-estimation/' + row.id + '" class="btn btn-sm btn-success">Invoice</a> ';
    }
    html += '<button type="button" class="btn btn-sm btn-danger" onclick="deleteEstimation(' + row.id + ')">Delete</button>';
    return html;
};

function deleteEstimation(id) {
    if (!confirm('Are you sure you want to delete this estimation?')) return;
    fetch('/admin/sales/estimations/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) { if (window.dtReload) window.dtReload(); else location.reload(); }
        alert(data.message || 'Deleted successfully');
    })
    .catch(function() { alert('Error deleting'); });
}
</script>

@include('components.datatable')
