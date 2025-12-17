@include('purchase::partials.styles')

<div style="padding: 20px;">
    <div class="page-header">
        <h1>Vendor Bills</h1>
        <a href="{{ route('admin.purchase.bills.create') }}" class="btn-add">+ New Vendor Bill</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">📋</div>
            <div><div class="stat-value">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Bills</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft">📝</div>
            <div><div class="stat-value">{{ $stats['draft'] ?? 0 }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending">⏳</div>
            <div><div class="stat-value">{{ $stats['pending'] ?? 0 }}</div><div class="stat-label">Pending</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">✅</div>
            <div><div class="stat-value">{{ $stats['approved'] ?? 0 }}</div><div class="stat-label">Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">💰</div>
            <div><div class="stat-value">₹{{ number_format($stats['total_amount'] ?? 0, 0) }}</div><div class="stat-label">Total Amount</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blocked">⚠️</div>
            <div><div class="stat-value">₹{{ number_format($stats['balance_due'] ?? 0, 0) }}</div><div class="stat-label">Balance Due</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Vendor Bill List
            </div>
            <div class="filter-group">
                <select class="filter-select" data-dt-filter="status">
                    <option value="">All Status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                </select>
                <select class="filter-select" data-dt-filter="payment_status">
                    <option value="">All Payment Status</option>
                    <option value="UNPAID">Unpaid</option>
                    <option value="PARTIALLY_PAID">Partially Paid</option>
                    <option value="PAID">Paid</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.bills.data') }}"
                   data-delete-route="{{ route('admin.purchase.bills.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="bill_number">Bill #</th>
                        <th data-col="vendor_name">Vendor</th>
                        <th class="dt-sort" data-col="bill_date">Bill Date</th>
                        <th data-col="due_date">Due Date</th>
                        <th data-col="grand_total">Amount</th>
                        <th data-col="paid_amount">Paid</th>
                        <th data-col="balance_due">Balance</th>
                        <th class="dt-sort" data-col="status" data-render="badge">Status</th>
                        <th data-col="payment_status" data-render="payment_badge">Payment</th>
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
// Custom render for payment status
window.dtRenders = window.dtRenders || {};
window.dtRenders['payment_badge'] = function(value, row) {
    let badge = '';
    switch(value) {
        case 'PAID':
            badge = '<span class="dt-badge dt-badge-success">PAID</span>';
            break;
        case 'PARTIALLY_PAID':
            badge = '<span class="dt-badge dt-badge-warning">PARTIAL</span>';
            break;
        case 'UNPAID':
            badge = '<span class="dt-badge dt-badge-danger">UNPAID</span>';
            break;
        default:
            badge = '<span class="dt-badge">' + value + '</span>';
    }
    if (row.is_overdue) {
        badge += ' <span class="dt-badge dt-badge-danger">⚠️</span>';
    }
    return badge;
};
</script>
