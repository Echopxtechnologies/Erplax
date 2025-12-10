<x-layouts.app>
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .back-btn:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .back-btn svg {
        width: 20px;
        height: 20px;
    }
    
    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-header h1 svg {
        width: 28px;
        height: 28px;
        color: #8b5cf6;
    }

    /* Stats */
    .stats-row {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 16px 20px;
        min-width: 140px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Filters */
    .filters-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .search-box {
        flex: 1;
        min-width: 200px;
        max-width: 300px;
        position: relative;
    }
    
    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        box-sizing: border-box;
    }
    
    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--text-muted);
    }
    
    .filter-select {
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 180px;
    }

    /* Table Card */
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
    }
    
    th {
        background: var(--body-bg);
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    td {
        font-size: 14px;
        color: var(--text-primary);
    }
    
    tr:last-child td {
        border-bottom: none;
    }
    
    tr:hover td {
        background: var(--body-bg);
    }

    .rack-code {
        font-weight: 600;
        color: #8b5cf6;
    }
    
    .rack-location {
        font-size: 12px;
        color: var(--text-muted);
    }
    
    .warehouse-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #ede9fe;
        color: #6d28d9;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .warehouse-badge svg {
        width: 14px;
        height: 14px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 18px;
        height: 18px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .btn-sm {
        padding: 6px 10px;
        font-size: 13px;
    }
    
    .btn-sm svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-ghost {
        background: transparent;
        color: var(--text-muted);
        padding: 6px;
    }
    
    .btn-ghost:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .btn-danger {
        background: transparent;
        color: #dc2626;
        padding: 6px;
    }
    
    .btn-danger:hover {
        background: #fee2e2;
    }

    /* Pagination */
    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border-top: 1px solid var(--card-border);
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .table-info {
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .pagination {
        display: flex;
        gap: 4px;
    }
    
    .pagination button {
        padding: 8px 12px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
    }
    
    .pagination button:hover:not(:disabled) {
        background: var(--body-bg);
    }
    
    .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination button.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* Alert */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 18px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .empty-state p {
        margin-bottom: 20px;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <a href="{{ route('admin.inventory.dashboard') }}" class="back-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Racks & Locations
            </h1>
        </div>
        <a href="{{ route('admin.inventory.racks.create') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Rack
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Racks</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color: #059669;">{{ $stats['active'] }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-bar">
        <div class="search-box">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Search racks..." onkeyup="debounceSearch()">
        </div>
        <select id="warehouseFilter" class="filter-select" onchange="loadData()">
            <option value="">All Warehouses</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Warehouse</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <div class="table-info" id="tableInfo">Showing 0 of 0 racks</div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let searchTimeout;

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage = 1;
        loadData();
    }, 300);
}

function loadData() {
    let search = document.getElementById('searchInput').value;
    let warehouseId = document.getElementById('warehouseFilter').value;
    
    let url = '{{ route("admin.inventory.racks.data") }}?page=' + currentPage;
    if (search) url += '&search=' + encodeURIComponent(search);
    if (warehouseId) url += '&warehouse_id=' + warehouseId;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            renderTable(data);
            renderPagination(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align: center; color: #dc2626;">Error loading data</td></tr>';
        });
}

function renderTable(data) {
    let tbody = document.getElementById('tableBody');
    
    if (data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3>No Racks Found</h3>
                        <p>Create your first rack to organize warehouse storage.</p>
                        <a href="{{ route('admin.inventory.racks.create') }}" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Rack
                        </a>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    data.data.forEach(item => {
        let location = [];
        if (item.zone && item.zone !== '-') location.push(item.zone);
        if (item.aisle && item.aisle !== '-') location.push('Aisle ' + item.aisle);
        if (item.level && item.level !== '-') location.push('Level ' + item.level);
        let locationStr = location.length > 0 ? location.join(' › ') : '-';
        
        html += `
            <tr>
                <td><span class="rack-code">${item.code}</span></td>
                <td>${item.name}</td>
                <td>
                    <span class="warehouse-badge">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                        ${item.warehouse_name}
                    </span>
                </td>
                <td><span class="rack-location">${locationStr}</span></td>
                <td><span class="status-badge ${item.is_active ? 'active' : 'inactive'}">${item.status}</span></td>
                <td>
                    <div class="actions-cell">
                        <a href="{{ url('admin/inventory/racks') }}/${item.id}/edit" class="btn btn-ghost btn-sm" title="Edit">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteRack(${item.id})" title="Delete">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    
    let start = (data.current_page - 1) * 25 + 1;
    let end = Math.min(data.current_page * 25, data.total);
    document.getElementById('tableInfo').textContent = `Showing ${start}-${end} of ${data.total} racks`;
}

function renderPagination(data) {
    let pagination = document.getElementById('pagination');
    let html = '';
    
    html += `<button onclick="goToPage(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''}>Prev</button>`;
    
    for (let i = 1; i <= data.last_page; i++) {
        if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
            html += `<button onclick="goToPage(${i})" class="${i === data.current_page ? 'active' : ''}">${i}</button>`;
        } else if (i === data.current_page - 3 || i === data.current_page + 3) {
            html += `<button disabled>...</button>`;
        }
    }
    
    html += `<button onclick="goToPage(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''}>Next</button>`;
    
    pagination.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    loadData();
}

function deleteRack(id) {
    if (!confirm('Are you sure you want to delete this rack?')) return;
    
    fetch('{{ url("admin/inventory/racks") }}/' + id, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            loadData();
        } else {
            alert(result.message || 'Error deleting rack');
        }
    })
    .catch(error => alert('Error: ' + error));
}

// Load data on page load
document.addEventListener('DOMContentLoaded', loadData);
</script>
</x-layouts.app>