<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:var(--text-primary);">Customers</h1>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">➕ Add Customer</a>
        </div>
    </x-slot>

    <style>
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-bottom:20px; }
        .stat-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:var(--radius-lg); padding:16px; }
        .stat-value { font-size:28px; font-weight:700; color:var(--text-primary); }
        .stat-label { font-size:var(--font-sm); color:var(--text-muted); margin-top:4px; }
        
        .dt-container { background:var(--card-bg); border:1px solid var(--card-border); border-radius:var(--radius-lg); padding:20px; }
        .dt-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px; }
        .dt-search { flex:1; min-width:200px; max-width:400px; }
        .dt-search input { width:100%; padding:8px 12px; border:1px solid var(--input-border); border-radius:var(--radius-md); }
        .dt-filters { display:flex; gap:8px; flex-wrap:wrap; }
        .dt-filters select { padding:8px 12px; border:1px solid var(--input-border); border-radius:var(--radius-md); }
        
        .dt-table { width:100%; border-collapse:collapse; }
        .dt-table th { background:var(--body-bg); padding:12px; text-align:left; font-weight:600; border-bottom:2px solid var(--card-border); }
        .dt-table td { padding:12px; border-bottom:1px solid var(--card-border); }
        .dt-table tr:hover { background:var(--body-bg); }
        
        .badge { display:inline-block; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600; }
        .badge-individual { background:#dbeafe; color:#1e40af; }
        .badge-company { background:#dcfce7; color:#15803d; }
        .badge-active { background:#dcfce7; color:#15803d; }
        .badge-inactive { background:#f1f5f9; color:#475569; }
        
        .dt-actions { display:flex; gap:4px; }
        .dt-pagination { display:flex; justify-content:space-between; align-items:center; margin-top:16px; padding-top:16px; border-top:1px solid var(--card-border); }
    </style>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
            <div class="stat-label">Total Customers</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['active_customers'] }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['individual_customers'] }}</div>
            <div class="stat-label">Individuals</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['company_customers'] }}</div>
            <div class="stat-label">Companies</div>
        </div>
    </div>

    <!-- DataTable -->
    <div class="dt-container">
        <div class="dt-header">
            <div class="dt-search">
                <input type="text" id="search" placeholder="🔍 Search customers...">
            </div>
            <div class="dt-filters">
                <select id="filter-type">
                    <option value="">All Types</option>
                    @foreach($customerTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select id="filter-group">
                    <option value="">All Groups</option>
                    @foreach($customerGroups as $group)
                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                <select id="filter-status">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <table class="dt-table" id="customersTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated via AJAX -->
            </tbody>
        </table>

        <div class="dt-pagination">
            <div id="pagination-info"></div>
            <div id="pagination-links"></div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let perPage = 10;

        function loadCustomers() {
            const search = document.getElementById('search').value;
            const filterType = document.getElementById('filter-type').value;
            const filterGroup = document.getElementById('filter-group').value;
            const filterStatus = document.getElementById('filter-status').value;

            fetch(`{{ route('admin.customers.data') }}?page=${currentPage}&per_page=${perPage}&search=${search}&customer_type=${filterType}&group_name=${filterGroup}&active=${filterStatus}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('#customersTable tbody');
                    tbody.innerHTML = '';

                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;color:var(--text-muted);">No customers found</td></tr>';
                        return;
                    }

                    data.data.forEach(customer => {
                        const row = `
                            <tr>
                                <td>${customer.sno}</td>
                                <td>
                                    <strong>${customer.full_name}</strong>
                                    ${customer.company ? '<br><small style="color:var(--text-muted);">' + customer.company + '</small>' : ''}
                                    ${customer.contact_count ? '<br><small style="color:var(--text-muted);">(' + customer.contact_count + ' contacts)</small>' : ''}
                                </td>
                                <td>${customer.email}</td>
                                <td>${customer.phone}</td>
                                <td>${customer.type_badge}</td>
                                <td>${customer.group_name}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" ${customer.active ? 'checked' : ''} onchange="toggleStatus(${customer.id}, this.checked)">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="dt-actions">
                                        <a href="${customer._show_url}" class="btn btn-sm btn-primary">View</a>
                                        <a href="${customer._edit_url}" class="btn btn-sm btn-light">Edit</a>
                                        <button onclick="deleteCustomer(${customer.id})" class="btn btn-sm btn-danger">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });

                    // Update pagination
                    document.getElementById('pagination-info').textContent = `Showing ${data.data.length} of ${data.total} customers`;
                    
                    const totalPages = data.last_page;
                    let paginationHTML = '';
                    for (let i = 1; i <= totalPages; i++) {
                        paginationHTML += `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-light'}" onclick="changePage(${i})">${i}</button> `;
                    }
                    document.getElementById('pagination-links').innerHTML = paginationHTML;
                });
        }

        function changePage(page) {
            currentPage = page;
            loadCustomers();
        }

        function toggleStatus(id, active) {
            fetch(`{{ url('admin/customers') }}/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ active: active ? 1 : 0 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully');
                } else {
                    alert('Failed to update status');
                    loadCustomers();
                }
            });
        }

        function deleteCustomer(id) {
            if (!confirm('Are you sure you want to delete this customer?')) return;

            fetch(`{{ url('admin/customers') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || 'Customer deleted');
                loadCustomers();
            });
        }

        // Event listeners
        document.getElementById('search').addEventListener('input', () => {
            currentPage = 1;
            loadCustomers();
        });

        document.getElementById('filter-type').addEventListener('change', () => {
            currentPage = 1;
            loadCustomers();
        });

        document.getElementById('filter-group').addEventListener('change', () => {
            currentPage = 1;
            loadCustomers();
        });

        document.getElementById('filter-status').addEventListener('change', () => {
            currentPage = 1;
            loadCustomers();
        });

        // Initial load
        loadCustomers();
    </script>

    <style>
        .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #22c55e; }
        input:checked + .slider:before { transform: translateX(26px); }
    </style>
</x-layouts.app>