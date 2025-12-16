@include('purchase::partials.styles')


<div style="padding: 20px;">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Vendors
        </h1>
        <a href="{{ route('admin.purchase.vendors.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Add Vendor
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Vendors</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Active</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inactive"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['inactive'] }}</div><div class="stat-label">Inactive</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blocked"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg></div>
            <div><div class="stat-value">{{ $stats['blocked'] }}</div><div class="stat-label">Blocked</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Vendor List
            </div>
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
