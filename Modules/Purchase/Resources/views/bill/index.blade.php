@include('purchase::partials.styles')

<div style="padding: 20px;">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Vendor Bills
        </h1>
        <a href="{{ route('admin.purchase.bills.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            New Vendor Bill
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Bills</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['draft'] ?? 0 }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['pending'] ?? 0 }}</div><div class="stat-label">Pending</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['approved'] ?? 0 }}</div><div class="stat-label">Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">₹{{ number_format($stats['total_amount'] ?? 0, 0) }}</div><div class="stat-label">Total Amount</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blocked"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
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
            <table class="dt-table dt-search dt-export dt-perpage" 
                   data-route="{{ route('admin.purchase.bills.data') }}">
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
