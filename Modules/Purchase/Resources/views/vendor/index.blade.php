@include('purchase::partials.styles')


<div style="padding: 20px;">
    <div class="page-header">
        <h1>Vendors</h1>
        <a href="{{ route('admin.purchase.vendors.create') }}" class="btn-add">+ Add Vendor</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">🏢</div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Vendors</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">✅</div>
            <div><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Active</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inactive">⏸️</div>
            <div><div class="stat-value">{{ $stats['inactive'] }}</div><div class="stat-label">Inactive</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blocked">🚫</div>
            <div><div class="stat-value">{{ $stats['blocked'] }}</div><div class="stat-label">Blocked</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">Vendor List</div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.vendors.data') }}"
                   data-delete-route="{{ route('admin.purchase.vendors.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="vendor_code">Code</th>
                        <th class="dt-sort dt-clickable" data-col="name">Name</th>
                        <th data-col="contact_person">Contact Person</th>
                        <th data-col="phone">Phone</th>
                        <th data-col="gst_number">GST Number</th>
                        <th data-col="billing_city">City</th>
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
