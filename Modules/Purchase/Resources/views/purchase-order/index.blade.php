@include('purchase::partials.styles')


<div style="padding: 20px;">
    <div class="page-header">
        <h1>Purchase Orders</h1>
        <a href="{{ route('admin.purchase.orders.create') }}" class="btn-add">+ New Order</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">📋</div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft">📝</div>
            <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon sent">📤</div>
            <div><div class="stat-value">{{ $stats['sent'] }}</div><div class="stat-label">Sent</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon confirmed">✅</div>
            <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">Confirmed</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon received">📦</div>
            <div><div class="stat-value">{{ $stats['received'] }}</div><div class="stat-label">Received</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">Purchase Order List</div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.orders.data') }}"
                   data-delete-route="{{ route('admin.purchase.orders.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="po_number">PO Number</th>
                        <th class="dt-sort" data-col="po_date">Date</th>
                        <th data-col="vendor_name">Vendor</th>
                        <th data-col="pr_number">From PR</th>
                        <th data-col="items_count">Items</th>
                        <th data-col="grand_total">Amount</th>
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
