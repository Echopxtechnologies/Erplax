<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; }
.btn-sm { padding: 6px 12px; font-size: 13px; }
.btn-danger { background: #ef4444; color: #fff; }
.btn-danger:hover { background: #dc2626; }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: space-between; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }
.card-body.p-0 { padding: 0; }

/* Stats */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; }
.stat-card .label { font-size: 13px; color: #6b7280; margin-bottom: 4px; }
.stat-card .value { font-size: 28px; font-weight: 700; color: #1f2937; }
.stat-card.draft .value { color: #6b7280; }
.stat-card.inspecting .value { color: #f59e0b; }
.stat-card.approved .value { color: #10b981; }

/* Filters */
.filters { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 16px; }
.form-control { padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 10px center; background-repeat: no-repeat; background-size: 16px; padding-right: 36px; min-width: 150px; }

/* Table */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #374151; border-bottom: 2px solid #e5e7eb; }
.data-table td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
.data-table tr:hover { background: #f9fafb; }
.data-table tr:last-child td { border-bottom: none; }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-draft { background: #f3f4f6; color: #374151; }
.badge-inspecting { background: #fef3c7; color: #92400e; }
.badge-approved { background: #d1fae5; color: #065f46; }
.badge-rejected { background: #fee2e2; color: #991b1b; }
.badge-cancelled { background: #e5e7eb; color: #6b7280; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }

.checkbox { width: 18px; height: 18px; cursor: pointer; }
.actions { display: flex; gap: 8px; }

/* Empty */
.empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
.empty-state h4 { margin: 16px 0 8px; color: #374151; }

/* Pagination */
.pagination { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #e5e7eb; }
.pagination-info { color: #6b7280; font-size: 14px; }
.pagination-links { display: flex; gap: 4px; }
.pagination-links button { padding: 8px 12px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer; }
.pagination-links button:hover { background: #f3f4f6; }
.pagination-links button.active { background: #6366f1; color: #fff; border-color: #6366f1; }
.pagination-links button:disabled { opacity: 0.5; cursor: not-allowed; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
.alert-danger { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }

@media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="page-header">
    <h1>📦 Goods Receipt Notes</h1>
    <a href="{{ route('admin.purchase.grn.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Create GRN
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total GRN</div>
        <div class="value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card draft">
        <div class="label">Draft</div>
        <div class="value">{{ $stats['draft'] }}</div>
    </div>
    <div class="stat-card inspecting">
        <div class="label">Inspecting</div>
        <div class="value">{{ $stats['inspecting'] }}</div>
    </div>
    <div class="stat-card approved">
        <div class="label">Approved</div>
        <div class="value">{{ $stats['approved'] }}</div>
    </div>
</div>

<!-- Filters & Table -->
<div class="card">
    <div class="card-header">
        <h5>GRN List</h5>
        <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">Delete Selected</button>
    </div>
    <div class="card-body">
        <div class="filters">
            <input type="text" class="form-control" id="searchInput" placeholder="Search GRN, PO, Vendor..." style="width: 250px;">
            <select class="form-control" id="statusFilter">
                <option value="">All Status</option>
                <option value="DRAFT">Draft</option>
                <option value="INSPECTING">Inspecting</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
                <option value="CANCELLED">Cancelled</option>
            </select>
            <input type="date" class="form-control" id="fromDate" placeholder="From Date">
            <input type="date" class="form-control" id="toDate" placeholder="To Date">
            <button type="button" class="btn btn-outline btn-sm" onclick="resetFilters()">Reset</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" class="checkbox" id="selectAll"></th>
                        <th>GRN Number</th>
                        <th>Date</th>
                        <th>PO Number</th>
                        <th>Vendor</th>
                        <th>Warehouse</th>
                        <th>Accepted Qty</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr><td colspan="10" class="empty-state">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <div class="pagination-info" id="paginationInfo">Showing 0 entries</div>
            <div class="pagination-links" id="paginationLinks"></div>
        </div>
    </div>
</div>

<script>
let currentPage = 1, perPage = 25, selectedIds = [];

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    
    document.getElementById('searchInput').addEventListener('input', debounce(loadData, 300));
    document.getElementById('statusFilter').addEventListener('change', loadData);
    document.getElementById('fromDate').addEventListener('change', loadData);
    document.getElementById('toDate').addEventListener('change', loadData);
    
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = this.checked;
            updateSelection(cb.value, this.checked);
        });
        toggleBulkBtn();
    });
    
    document.getElementById('bulkDeleteBtn').addEventListener('click', bulkDelete);
});

function loadData(page = 1) {
    currentPage = page;
    const params = new URLSearchParams({
        page: currentPage,
        per_page: perPage,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        from_date: document.getElementById('fromDate').value,
        to_date: document.getElementById('toDate').value,
    });
    
    fetch(`{{ route('admin.purchase.grn.data') }}?${params}`)
        .then(r => r.json())
        .then(data => renderTable(data))
        .catch(err => {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="10" class="empty-state"><h4>Error</h4><p>${err.message}</p></td></tr>`;
        });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    
    if (!data.data || data.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="empty-state"><h4>No GRN Found</h4><p>Create a new GRN from a confirmed Purchase Order</p></td></tr>';
        document.getElementById('paginationInfo').textContent = 'Showing 0 entries';
        document.getElementById('paginationLinks').innerHTML = '';
        return;
    }
    
    tbody.innerHTML = data.data.map(row => `
        <tr>
            <td><input type="checkbox" class="checkbox row-checkbox" value="${row.id}" onchange="updateSelection(${row.id}, this.checked); toggleBulkBtn();" ${selectedIds.includes(row.id) ? 'checked' : ''}></td>
            <td><a href="{{ url('admin/purchase/grn') }}/${row.id}" style="color: #4f46e5; font-weight: 500;">${row.grn_number}</a></td>
            <td>${row.grn_date}</td>
            <td><a href="{{ url('admin/purchase/orders') }}/${row.po_id}" style="color: #6b7280;">${row.po_number}</a></td>
            <td>${row.vendor_name}</td>
            <td>${row.warehouse_name}</td>
            <td>${row.accepted_qty}</td>
            <td>${row.stock_updated ? '<span class="badge badge-success">Updated</span>' : '<span class="badge badge-warning">Pending</span>'}</td>
            <td><span class="badge badge-${row.status.toLowerCase()}">${row.status}</span></td>
            <td class="actions">
                <a href="{{ url('admin/purchase/grn') }}/${row.id}" class="btn btn-outline btn-sm">View</a>
            </td>
        </tr>
    `).join('');
    
    // Pagination
    const start = (data.current_page - 1) * perPage + 1;
    const end = Math.min(data.current_page * perPage, data.total);
    document.getElementById('paginationInfo').textContent = `Showing ${start}-${end} of ${data.total}`;
    
    let links = '';
    const totalPages = Math.ceil(data.total / perPage);
    links += `<button ${currentPage === 1 ? 'disabled' : ''} onclick="loadData(${currentPage - 1})">Prev</button>`;
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        links += `<button class="${i === currentPage ? 'active' : ''}" onclick="loadData(${i})">${i}</button>`;
    }
    links += `<button ${currentPage === totalPages ? 'disabled' : ''} onclick="loadData(${currentPage + 1})">Next</button>`;
    document.getElementById('paginationLinks').innerHTML = links;
}

function updateSelection(id, checked) {
    id = parseInt(id);
    if (checked && !selectedIds.includes(id)) selectedIds.push(id);
    else selectedIds = selectedIds.filter(i => i !== id);
}

function toggleBulkBtn() {
    document.getElementById('bulkDeleteBtn').style.display = selectedIds.length > 0 ? 'inline-flex' : 'none';
}

function bulkDelete() {
    if (!confirm(`Delete ${selectedIds.length} selected GRN(s)?`)) return;
    
    fetch('{{ route("admin.purchase.grn.bulk-delete") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            selectedIds = [];
            toggleBulkBtn();
            loadData();
        } else {
            alert(data.message || 'Error deleting');
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value = '';
    loadData();
}

function debounce(fn, delay) {
    let timer;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}
</script>
