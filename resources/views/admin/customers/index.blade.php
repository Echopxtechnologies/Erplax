<x-layouts.app>
    <style>
        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .page-header h1 { margin:0; color:var(--text-primary, #1f2937); }
        .btn-add { background:#3498DB; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; }
        .btn-add:hover { opacity:0.9; }
        .alert-success { background:#D4EDDA; border:1px solid #C3E6CB; color:#155724; padding:12px; border-radius:5px; margin-bottom:20px; }
        
        /* Badge styles */
        .badge-type { padding:4px 12px; border-radius:12px; font-size:12px; font-weight:500; display:inline-block; }
        .badge-company { background:#dbeafe; color:#1d4ed8; }
        .badge-individual { background:#dcfce7; color:#16a34a; }
        
        /* Dark mode */
        .dark .page-header h1, [data-theme="dark"] .page-header h1 { color:#f1f5f9; }
        .dark .alert-success, [data-theme="dark"] .alert-success { background:#14532d; border-color:#166534; color:#86efac; }
        .dark .badge-company, [data-theme="dark"] .badge-company { background:#1e3a5f; color:#93c5fd; }
        .dark .badge-individual, [data-theme="dark"] .badge-individual { background:#14532d; color:#86efac; }
    </style>

    <div style="padding: 20px;">
        <div class="page-header">
            <h1>Customer Management</h1>
            <a href="{{ route('admin.customers.create') }}" class="btn-add">+ Add Customer</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <table class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox"
               data-route="{{ route('admin.customers.data') }}">
            <thead>
                <tr>
                    <th data-col="sno">S.No</th>
                    <th class="dt-sort" data-col="name">Name</th>
                    <th class="dt-sort" data-col="email">Email</th>
                    <th class="dt-sort" data-col="phone">Phone</th>
                    {{-- <th class="dt-sort" data-col="customer_type">Customer Type</th> --}}
                    <th data-render="actions">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @include('core::datatable')
</x-layouts.app>