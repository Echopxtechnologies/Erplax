@include('purchase::partials.styles')


<div style="padding: 20px;">
    <div class="page-header">
        <h1>Purchase Requests</h1>
        <a href="{{ route('admin.purchase.requests.create') }}" class="btn-add">+ New Request</a>
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
            <div class="stat-icon pending">⏳</div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">✅</div>
            <div><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-label">Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">❌</div>
            <div><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-label">Rejected</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">Purchase Request List</div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.requests.data') }}"
                   data-delete-route="{{ route('admin.purchase.requests.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="pr_number">PR Number</th>
                        <th class="dt-sort" data-col="pr_date">Date</th>
                        <th data-col="department">Department</th>
                        <th data-col="items_count">Items</th>
                        <th class="dt-sort" data-col="priority" data-render="badge">Priority</th>
                        <th data-col="requester_name">Requested By</th>
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
