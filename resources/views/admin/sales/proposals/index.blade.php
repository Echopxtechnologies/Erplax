<x-layouts.app>
<style>
    .proposal-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .pro-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .pro-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .pro-header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    
    .pro-header-icon svg { width: 22px; height: 22px; }
    .pro-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .pro-header-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    
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
    
    .pro-stats-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    
    .pro-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 10px 16px;
        border-radius: 8px;
        min-width: 140px;
    }
    
    .pro-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pro-stat-icon svg { width: 16px; height: 16px; }
    .pro-stat-icon.total { background: #ffedd5; color: #ea580c; }
    .pro-stat-icon.draft { background: #f3f4f6; color: #6b7280; }
    .pro-stat-icon.sent { background: #dbeafe; color: #2563eb; }
    .pro-stat-icon.open { background: #ede9fe; color: #7c3aed; }
    .pro-stat-icon.accepted { background: #ecfdf5; color: #059669; }
    .pro-stat-icon.declined { background: #fef2f2; color: #dc2626; }
    
    .pro-stat-value { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1; }
    .pro-stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }
    
    .pro-filter-select {
        padding: 7px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 130px;
    }
    
    .pro-table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    .pro-table-wrap table { min-width: 900px; }
    .pro-table-wrap .btn { padding: 4px 10px !important; font-size: 11px !important; border-radius: 4px !important; }
    .pro-table-wrap td:last-child .btn { padding: 3px 8px !important; font-size: 10px !important; margin: 1px !important; }
    
    .pro-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }
    .pro-badge.draft { background: #f3f4f6; color: #6b7280; }
    .pro-badge.sent { background: #dbeafe; color: #1d4ed8; }
    .pro-badge.open { background: #ede9fe; color: #7c3aed; }
    .pro-badge.accepted { background: #ecfdf5; color: #059669; }
    .pro-badge.declined { background: #fef2f2; color: #dc2626; }
    .pro-badge.revised { background: #fef3c7; color: #92400e; }
    
    .proposal-number { font-weight: 600; color: #ea580c; font-size: 13px; }
    .amount-display { font-weight: 600; font-size: 13px; color: var(--text-primary); }
    .pro-table-wrap td { white-space: nowrap; }
</style>

<div class="proposal-container">
    <div class="pro-header">
        <div class="pro-header-left">
            <div class="pro-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1>Proposals</h1>
                <div class="pro-header-sub">Create and manage client proposals</div>
            </div>
        </div>
        <a href="{{ route('admin.sales.proposals.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Proposal
        </a>
    </div>

    <div class="pro-stats-bar">
        <div class="pro-stat">
            <div class="pro-stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="pro-stat-label">Total</div>
            </div>
        </div>
        <div class="pro-stat">
            <div class="pro-stat-icon draft">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['draft'] ?? 0 }}</div>
                <div class="pro-stat-label">Draft</div>
            </div>
        </div>
        <div class="pro-stat">
            <div class="pro-stat-icon sent">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['sent'] ?? 0 }}</div>
                <div class="pro-stat-label">Sent</div>
            </div>
        </div>
        <div class="pro-stat">
            <div class="pro-stat-icon open">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['open'] ?? 0 }}</div>
                <div class="pro-stat-label">Open</div>
            </div>
        </div>
        <div class="pro-stat">
            <div class="pro-stat-icon accepted">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['accepted'] ?? 0 }}</div>
                <div class="pro-stat-label">Accepted</div>
            </div>
        </div>
        @if(($stats['declined'] ?? 0) > 0)
        <div class="pro-stat">
            <div class="pro-stat-icon declined">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <div class="pro-stat-value">{{ $stats['declined'] }}</div>
                <div class="pro-stat-label">Declined</div>
            </div>
        </div>
        @endif
    </div>

    <div class="pro-table-wrap">
        <div style="padding: 12px 16px; border-bottom: 1px solid var(--card-border);">
            <select class="pro-filter-select" data-dt-filter="status">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="open">Open</option>
                <option value="accepted">Accepted</option>
                <option value="declined">Declined</option>
                <option value="revised">Revised</option>
            </select>
        </div>
        <table class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
               id="proposalsTable"
               data-route="{{ route('admin.sales.proposals.data') }}">
            <thead>
                <tr>
                    <th class="dt-sort dt-clickable" data-col="id">ID</th>
                    <th class="dt-sort dt-clickable" data-col="proposal_number" data-render="proposal_number">Proposal #</th>
                    <th class="dt-sort" data-col="subject">Subject</th>
                    <th class="dt-sort" data-col="customer_name">Customer</th>
                    <th class="dt-sort" data-col="date">Date</th>
                    <th class="dt-sort" data-col="open_till">Open Till</th>
                    <th class="dt-sort" data-col="total" data-render="total">Total</th>
                    <th data-col="status" data-render="status">Status</th>
                    <th data-col="assigned_to">Assigned</th>
                    <th data-render="actions" style="min-width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.proposal_number = function(data, row) {
    return '<span class="proposal-number">' + row.proposal_number + '</span>';
};

window.dtRenders.total = function(data, row) {
    return '<span class="amount-display">' + row.total + '</span>';
};

window.dtRenders.status = function(data, row) {
    var status = row.status || 'draft';
    var label = status.charAt(0).toUpperCase() + status.slice(1);
    return '<span class="pro-badge ' + status + '">' + label + '</span>';
};

window.dtRenders.actions = function(data, row) {
    var html = '<a href="/admin/sales/proposals/' + row.id + '" class="btn btn-sm btn-info">View</a> ' +
               '<a href="/admin/sales/proposals/' + row.id + '/edit" class="btn btn-sm btn-warning">Edit</a> ';
    if (row.status === 'accepted') {
        html += '<form action="/admin/sales/estimations/from-proposal/' + row.id + '" method="POST" style="display:inline;">' +
                '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '">' +
                '<button type="submit" class="btn btn-sm btn-success">Estimation</button></form> ';
    }
    html += '<button type="button" class="btn btn-sm btn-danger" onclick="deleteProposal(' + row.id + ')">Delete</button>';
    return html;
};

function deleteProposal(id) {
    if (!confirm('Are you sure you want to delete this proposal?')) return;
    fetch('/admin/sales/proposals/' + id, {
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
</x-layouts.app>