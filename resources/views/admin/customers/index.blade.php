
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="margin:0 0 4px 0;font-size:24px;font-weight:700;color:var(--text-primary);">Customers</h1>
                <p style="margin:0;font-size:14px;color:var(--text-muted);">Manage your customers and contacts</p>
            </div>
            <a href="{{ route('admin.customers.create') }}" class="btn-modern btn-primary">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </a>
        </div>
    </x-slot>

    <style>
        /* Modern Button Styles */
        .btn-modern {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 20px;
            font-size:14px;
            font-weight:600;
            border-radius:10px;
            border:none;
            cursor:pointer;
            transition:all 0.2s ease;
            text-decoration:none;
            box-shadow:0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-modern:hover {
            transform:translateY(-2px);
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary { 
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white;
        }
        .btn-primary:hover { 
            background:linear-gradient(135deg,#5568d3 0%,#63408a 100%);
        }
        .btn-light { 
            background:white;
            color:#64748b;
            border:2px solid #e2e8f0;
        }
        .btn-light:hover { 
            background:#f8fafc;
            color:#475569;
            border-color:#cbd5e1;
        }
        .btn-sm { 
            padding:8px 16px;
            font-size:13px;
        }
        
        /* Compact Stats Grid */
        .stats-grid { 
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:16px;
        }
        .stat-card { 
            background:white;
            border:1px solid #e5e7eb;
            border-radius:10px;
            padding:12px 20px;
            display:flex;
            align-items:center;
            gap:12px;
            flex:1;
            min-width:180px;
            transition:all 0.2s ease;
            box-shadow:0 1px 2px rgba(0,0,0,0.04);
        }
        .stat-card:hover {
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        }
        .stat-icon {
            width:40px;
            height:40px;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }
        .stat-icon svg {
            width:20px;
            height:20px;
        }
        .stat-icon.purple { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; }
        .stat-icon.pink { background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%); color:white; }
        .stat-icon.blue { background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%); color:white; }
        .stat-icon.green { background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%); color:white; }
        .stat-content {
            display:flex;
            flex-direction:column;
        }
        .stat-value { 
            font-size:22px;
            font-weight:700;
            color:#1e293b;
            line-height:1.2;
        }
        .stat-label { 
            font-size:11px;
            color:#64748b;
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:0.3px;
        }
        
        /* Filter Card - More Compact */
        .filter-card { 
            background:white;
            border:1px solid #e5e7eb;
            border-radius:12px;
            padding:16px 20px;
            margin-bottom:16px;
            box-shadow:0 1px 2px rgba(0,0,0,0.04);
        }
        .filter-grid { 
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            align-items:end;
        }
        .filter-group {
            flex:1;
            min-width:160px;
        }
        .filter-label { 
            display:block;
            font-size:11px;
            font-weight:600;
            color:#64748b;
            margin-bottom:4px;
            text-transform:uppercase;
            letter-spacing:0.3px;
        }
        .filter-input { 
            width:100%;
            padding:8px 12px;
            font-size:13px;
            border:1px solid #e5e7eb;
            border-radius:8px;
            background:white;
            color:#1e293b;
            transition:all 0.2s;
            font-family:inherit;
        }
        .filter-input:hover {
            border-color:#cbd5e1;
        }
        .filter-input:focus { 
            outline:none;
            border-color:#667eea;
            box-shadow:0 0 0 3px rgba(102,126,234,0.1);
        }
        select.filter-input {
            cursor:pointer;
            appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 10px center;
            background-size:16px;
            padding-right:36px;
        }
        .filter-actions { 
            display:flex;
            gap:8px;
            align-items:end;
        }
        .filter-actions .btn-modern {
            padding:8px 14px;
            font-size:12px;
        }
        
        /* Badges */
        .dt-badge { 
            display:inline-flex;
            align-items:center;
            gap:5px;
            padding:4px 10px;
            border-radius:16px;
            font-size:11px;
            font-weight:600;
            letter-spacing:0.2px;
            text-transform:uppercase;
        }
        .dt-badge::before {
            content:'';
            width:5px;
            height:5px;
            border-radius:50%;
        }
        .dt-badge-info { 
            background:#dbeafe;
            color:#1e40af;
        }
        .dt-badge-info::before {
            background:#1e40af;
        }
        .dt-badge-primary { 
            background:#ede9fe;
            color:#6d28d9;
        }
        .dt-badge-primary::before {
            background:#6d28d9;
        }
        .dt-badge-success { 
            background:#dcfce7;
            color:#15803d;
        }
        .dt-badge-success::before {
            background:#15803d;
        }
        .dt-badge-secondary { 
            background:#f1f5f9;
            color:#64748b;
        }
        .dt-badge-secondary::before {
            background:#64748b;
        }
        
        /* Customer Name Display */
        .customer-name { 
            font-weight:600;
            color:#1e293b;
            font-size:14px;
        }
        .company-name { 
            font-size:12px;
            color:#64748b;
            margin-top:2px;
        }

        /* Modern Toggle Switch */
        .status-toggle { 
            position:relative;
            display:inline-block;
            width:44px;
            height:24px;
        }
        .status-toggle input { 
            opacity:0;
            width:0;
            height:0;
        }
        .toggle-slider {
            position:absolute;
            cursor:pointer;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background-color:#cbd5e1;
            border-radius:24px;
            transition:0.3s;
        }
        .toggle-slider:before {
            position:absolute;
            content:"";
            height:18px;
            width:18px;
            left:3px;
            bottom:3px;
            background-color:white;
            border-radius:50%;
            transition:0.3s;
            box-shadow:0 1px 3px rgba(0,0,0,0.2);
        }
        .status-toggle input:checked + .toggle-slider { 
            background:linear-gradient(135deg,#10b981 0%,#059669 100%);
        }
        .status-toggle input:checked + .toggle-slider:before { 
            transform:translateX(20px);
        }
        .toggle-inactive .toggle-slider { 
            background-color:#94a3b8;
        }
        .toggle-active .toggle-slider { 
            background:linear-gradient(135deg,#10b981 0%,#059669 100%);
        }
        
        /* DataTable Container */
        .dt-container {
            background:white;
            border:1px solid #e5e7eb;
            border-radius:12px;
            padding:20px;
            box-shadow:0 1px 2px rgba(0,0,0,0.04);
        }
        
        /* Responsive */
        @media(max-width:768px) {
            .stats-grid {
                flex-direction:column;
            }
            .stat-card {
                min-width:100%;
            }
            .filter-grid {
                flex-direction:column;
            }
            .filter-group {
                width:100%;
            }
        }
    </style>

    <!-- Compact Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_customers'] }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active_customers'] }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['individual_customers'] }}</div>
                <div class="stat-label">Individual</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pink">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['company_customers'] }}</div>
                <div class="stat-label">Company</div>
            </div>
        </div>
    </div>

    <!-- Compact Filters -->
    <div class="filter-card">
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Customer Type</label>
                <select id="filterCustomerType" class="filter-input">
                    <option value="">All Types</option>
                    @foreach($customerTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Customer Group</label>
                <select id="filterGroupName" class="filter-input">
                    <option value="">All Groups</option>
                    @foreach($customerGroups as $group)
                        <option value="{{ $group }}">{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select id="filterActive" class="filter-input">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="filter-actions">
                <button onclick="applyFilters()" class="btn-modern btn-primary btn-sm">Apply</button>
                <button onclick="clearFilters()" class="btn-modern btn-light btn-sm">Clear</button>
            </div>
        </div>
    </div>



{{-- 🎯 PASTE THE BUTTON CODE HERE --}}
    {{-- <div style="display:flex;justify-content:flex-end;gap:12px;margin-bottom:16px;">
        <a href="{{ route('admin.customer-groups.index') }}" class="btn-modern btn-light">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Customer Groups
        </a>
         --}}
         <div style="display:flex;justify-content:flex-end;gap:12px;margin-bottom:16px;">
        <a href="{{ route('admin.customers.create') }}" class="btn-modern btn-primary">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
             Create Customer
        </a>
    </div>



    




    <!-- DataTable -->
    <table class="dt-table dt-search dt-export dt-perpage dt-checkbox dt-import" 
           data-route="{{ route('admin.customers.data') }}"
           id="customersTable">
        <thead>
            <tr>
                <th class="dt-sort" data-col="id">ID</th>
                <th class="dt-sort" data-col="name" data-render="nameCompany">Name / Company</th>
                <th class="dt-sort" data-col="email">Email</th>
                <th data-col="phone">Phone</th>
                <th data-col="designation">Designation</th>
                <th data-col="group_name">Group</th>
                <th class="dt-sort" data-col="customer_type" data-render="badge">Type</th>
                <th data-col="active">Status</th>
                <th class="dt-sort" data-col="created_at">Created</th>
                <th data-render="actions">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    @include('components.datatable')

<script>
    window.dtRenderCallbacks = window.dtRenderCallbacks || {};

    window.dtRenderCallbacks.nameCompany = function(data, row) {
        if (row.customer_type === 'company' && row.company) {
            return `
                <div class="customer-name">${row.company}</div>
                <div class="company-name">${row.name || '-'}</div>
            `;
        } else {
            return `<div class="customer-name">${row.name || '-'}</div>`;
        }
    };

    window.dtRenderCallbacks.statusToggle = function(data, row) {
        const isActive = row.active == 1 || row.active === true;
        const toggleClass = isActive ? 'toggle-active' : 'toggle-inactive';
        const statusText = isActive ? 'Active' : 'Inactive';
        
        return `
            <div style="display:flex;align-items:center;gap:8px;">
                <label class="status-toggle ${toggleClass}">
                    <input type="checkbox" ${isActive ? 'checked' : ''} onchange="toggleStatus(${row.id}, this)">
                    <span class="toggle-slider"></span>
                </label>
                <span class="dt-badge ${isActive ? 'dt-badge-success' : 'dt-badge-secondary'}">${statusText}</span>
            </div>
        `;
    };

    function applyFilters() {
        const customerType = document.getElementById('filterCustomerType').value;
        const groupName = document.getElementById('filterGroupName').value;
        const active = document.getElementById('filterActive').value;
        
        const table = document.getElementById('customersTable');
        const route = "{{ route('admin.customers.data') }}";
        
        let url = route + '?';
        if (customerType) url += 'customer_type=' + customerType + '&';
        if (groupName) url += 'group_name=' + encodeURIComponent(groupName) + '&';
        if (active) url += 'active=' + active + '&';
        
        table.dataset.route = url.slice(0, -1);
        if (table.dtReload) table.dtReload();
    }
    
    function clearFilters() {
        document.getElementById('filterCustomerType').value = '';
        document.getElementById('filterGroupName').value = '';
        document.getElementById('filterActive').value = '';
        
        const table = document.getElementById('customersTable');
        table.dataset.route = "{{ route('admin.customers.data') }}";
        if (table.dtReload) table.dtReload();
    }

    function toggleStatus(customerId, checkbox) {
        if (!customerId) {
            console.error('❌ Invalid customer ID:', customerId);
            checkbox.checked = !checkbox.checked;
            alert('Invalid customer ID');
            return;
        }
        
        const newStatus = checkbox.checked ? 1 : 0;
        const csrf = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/admin/customers/' + customerId + '/toggle-status', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrf ? csrf.content : '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ active: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const toggle = checkbox.closest('.status-toggle');
                const badge = toggle.nextElementSibling;
                
                if (newStatus === 1) {
                    toggle.classList.remove('toggle-inactive');
                    toggle.classList.add('toggle-active');
                    badge.className = 'dt-badge dt-badge-success';
                    badge.textContent = 'Active';
                } else {
                    toggle.classList.remove('toggle-active');
                    toggle.classList.add('toggle-inactive');
                    badge.className = 'dt-badge dt-badge-secondary';
                    badge.textContent = 'Inactive';
                }
                
                if (typeof Toast !== 'undefined') {
                    Toast.success(data.message || 'Status updated successfully');
                }
            } else {
                checkbox.checked = !checkbox.checked;
                if (typeof Toast !== 'undefined') {
                    Toast.error(data.message || 'Failed to update status');
                } else {
                    alert(data.message || 'Failed to update status');
                }
            }
        })
        .catch(error => {
            console.error('❌ Toggle error:', error);
            checkbox.checked = !checkbox.checked;
            if (typeof Toast !== 'undefined') {
                Toast.error('Failed to update status');
            } else {
                alert('Failed to update status');
            }
        });
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //     document.addEventListener('click', function(e) {
    //         if (e.target.classList.contains('dt-btn-delete')) {
    //             e.preventDefault();
    //             const id = e.target.dataset.id;
                
    //             if (!confirm('Are you sure you want to delete this customer?')) {
    //                 return;
    //             }
                
    //             const csrf = document.querySelector('meta[name="csrf-token"]');
    //             fetch('/admin/customers/' + id, {
    //                 method: 'DELETE',
    //                 headers: {
    //                     'X-CSRF-TOKEN': csrf ? csrf.content : '',
    //                     'Accept': 'application/json'
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success !== false) {
    //                     const table = document.getElementById('customersTable');
    //                     if (table.dtReload) table.dtReload();
    //                     if (typeof Toast !== 'undefined') {
    //                         Toast.success('Customer deleted successfully');
    //                     }
    //                 } else {
    //                     if (typeof Toast !== 'undefined') {
    //                         Toast.error(data.message || 'Delete failed');
    //                     } else {
    //                         alert(data.message || 'Delete failed');
    //                     }
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error('Error:', error);
    //                 if (typeof Toast !== 'undefined') {
    //                     Toast.error('Delete failed');
    //                 } else {
    //                     alert('Delete failed');
    //                 }
    //             });
    //         }
    //     });
    // });
</script>
