@include('purchase::partials.styles')

<div style="padding: 20px;">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Goods Receipt Notes
        </h1>
        <a href="{{ route('admin.purchase.grn.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Create GRN
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
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total GRN</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inspecting"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></div>
            <div><div class="stat-value">{{ $stats['inspecting'] }}</div><div class="stat-label">Inspecting</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-label">Approved</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                GRN List
            </div>
            <div class="filter-group">
                <select class="filter-select" data-dt-filter="status">
                    <option value="">All Status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="INSPECTING">Inspecting</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                    <option value="CANCELLED">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.grn.data') }}"
                   data-delete-route="{{ route('admin.purchase.grn.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="grn_number">GRN Number</th>
                        <th class="dt-sort" data-col="grn_date">Date</th>
                        <th data-col="po_number">PO Number</th>
                        <th data-col="vendor_name">Vendor</th>
                        <th data-col="warehouse_name">Warehouse</th>
                        <th data-col="accepted_qty">Accepted Qty</th>
                        <th data-col="stock_updated" data-render="stock_badge">Stock</th>
                        <th class="dt-sort" data-col="status" data-render="badge">Status</th>
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
// Custom render for stock status
window.dtRenders = window.dtRenders || {};
window.dtRenders['stock_badge'] = function(value, row) {
    if (value === true || value === 'Updated' || value === 1 || value === '1') {
        return '<span class="dt-badge dt-badge-success">Updated</span>';
    }
    return '<span class="dt-badge dt-badge-warning">Pending</span>';
};
</script>
