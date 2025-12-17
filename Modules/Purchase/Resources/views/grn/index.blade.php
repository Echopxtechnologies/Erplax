@include('purchase::partials.styles')

<div style="padding: 20px;">
    <div class="page-header">
        <h1>Goods Receipt Notes</h1>
        <a href="{{ route('admin.purchase.grn.create') }}" class="btn-add">+ Create GRN</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">📦</div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total GRN</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft">📝</div>
            <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inspecting">🔍</div>
            <div><div class="stat-value">{{ $stats['inspecting'] }}</div><div class="stat-label">Inspecting</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">✅</div>
            <div><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-label">Approved</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">GRN List</div>
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
