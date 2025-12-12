<x-layouts.app>
<style>
    .inventory-container { padding: 20px; max-width: 1400px; margin: 0 auto; }
    .inv-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--card-border); }
    .inv-header-left { display: flex; align-items: center; gap: 12px; }
    .inv-header-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
    .inv-header-icon svg { width: 22px; height: 22px; }
    .inv-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .inv-header-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    .btn-add { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 13px; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-add:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); color: #fff; }
    .btn-add svg { width: 16px; height: 16px; }
    .inv-stats-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .inv-stat { display: flex; align-items: center; gap: 10px; background: var(--card-bg); border: 1px solid var(--card-border); padding: 10px 16px; border-radius: 8px; min-width: 140px; }
    .inv-stat-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
    .inv-stat-icon svg { width: 16px; height: 16px; }
    .inv-stat-icon.total { background: #f3e8ff; color: #7c3aed; }
    .inv-stat-icon.active { background: #ecfdf5; color: #059669; }
    .inv-stat-icon.inactive { background: #fef2f2; color: #dc2626; }
    .inv-stat-value { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1; }
    .inv-stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }
    .inv-filters { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .inv-filter-select { padding: 7px 12px; border: 1px solid var(--card-border); border-radius: 6px; font-size: 12px; background: var(--card-bg); color: var(--text-primary); min-width: 150px; cursor: pointer; }
    .inv-filter-select:focus { outline: none; border-color: var(--primary); }
    .inv-table-wrap { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; overflow-x: auto; }
    .inv-table-wrap table { min-width: 900px; }
    .inv-table-wrap .btn, .inv-table-wrap .dt-actions .btn, .inv-table-wrap [class*="btn-"] { padding: 4px 10px !important; font-size: 11px !important; border-radius: 4px !important; }
    .inv-table-wrap td:last-child .btn, .inv-table-wrap td:last-child [class*="btn-"] { padding: 3px 8px !important; font-size: 10px !important; margin: 1px !important; }
    .rack-code { font-weight: 600; color: #8b5cf6; font-family: monospace; font-size: 13px; }
    .warehouse-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #ede9fe; color: #6d28d9; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .warehouse-badge svg { width: 14px; height: 14px; }
    .location-text { font-size: 12px; color: var(--text-muted); }
    .location-text .zone { color: var(--text-primary); font-weight: 500; }
    .capacity-text { font-size: 13px; }
    .capacity-text .value { font-weight: 500; color: var(--text-primary); }
    .capacity-text .unit { font-size: 11px; color: var(--text-muted); margin-left: 2px; }
    .inv-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; white-space: nowrap; }
    .inv-badge.active { background: #ecfdf5; color: #059669; }
    .inv-badge.inactive { background: #fef2f2; color: #dc2626; }
    .inv-badge svg { width: 12px; height: 12px; flex-shrink: 0; }
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="inventory-container">
    <!-- Header -->
    <div class="inv-header">
        <div class="inv-header-left">
            <div class="inv-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <h1>Racks & Locations</h1>
                <div class="inv-header-sub">Manage warehouse storage locations</div>
            </div>
        </div>
        <a href="{{ route('admin.inventory.racks.create') }}" class="btn-add">
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

    <!-- Stats Bar -->
    <div class="inv-stats-bar">
        <div class="inv-stat">
            <div class="inv-stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['total'] }}</div>
                <div class="inv-stat-label">Total</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon active">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['active'] }}</div>
                <div class="inv-stat-label">Active</div>
            </div>
        </div>
        @if(isset($stats['inactive']) && $stats['inactive'] > 0)
        <div class="inv-stat">
            <div class="inv-stat-icon inactive">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['inactive'] }}</div>
                <div class="inv-stat-label">Inactive</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Racks Table -->
    <div class="inv-table-wrap">
        <div class="inv-filters" style="padding: 12px 16px; border-bottom: 1px solid var(--card-border);">
            <select class="inv-filter-select" data-dt-filter="warehouse_id">
                <option value="">All Warehouses</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            <select class="inv-filter-select" data-dt-filter="is_active">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <table class="dt-table dt-search dt-perpage" 
               id="racksTable"
               data-route="{{ route('admin.inventory.racks.data') }}">
            <thead>
                <tr>
                    <th class="dt-sort" data-col="id">ID</th>
                    <th class="dt-sort" data-col="code" data-render="code">Code</th>
                    <th class="dt-sort" data-col="name">Name</th>
                    <th class="dt-sort" data-col="warehouse_name" data-render="warehouse">Warehouse</th>
                    <th data-col="full_location" data-render="location">Location</th>
                    <th data-col="max_capacity" data-render="capacity">Capacity</th>
                    <th data-col="max_weight" data-render="weight">Max Weight</th>
                    <th class="dt-sort" data-col="is_active" data-render="status">Status</th>
                    <th data-render="actions" style="min-width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.code = function(data, row) {
    return `<span class="rack-code">${data || row.code}</span>`;
};

window.dtRenders.warehouse = function(data, row) {
    let name = data || row.warehouse_name || '-';
    if (name === '-') return name;
    
    return `<span class="warehouse-badge">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
        </svg>
        ${name}
    </span>`;
};

window.dtRenders.location = function(data, row) {
    let parts = [];
    if (row.zone && row.zone !== '-') parts.push(`<span class="zone">${row.zone}</span>`);
    if (row.aisle && row.aisle !== '-') parts.push(`Aisle ${row.aisle}`);
    if (row.level && row.level !== '-') parts.push(`Level ${row.level}`);
    
    if (parts.length === 0) return '<span class="location-text">-</span>';
    
    return `<span class="location-text">${parts.join(' › ')}</span>`;
};

window.dtRenders.capacity = function(data, row) {
    // data is already formatted from controller: "1,000.00 pcs"
    if (!data || data === '-') {
        return '<span class="capacity-text">-</span>';
    }
    return `<span class="capacity-text"><span class="value">${data}</span></span>`;
};

window.dtRenders.weight = function(data, row) {
    // data is already formatted from controller: "100.00 kg"
    if (!data || data === '-') {
        return '<span class="capacity-text">-</span>';
    }
    return `<span class="capacity-text"><span class="value">${data}</span></span>`;
};

window.dtRenders.status = function(data, row) {
    if (data || row.is_active) {
        return '<span class="inv-badge active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Active</span>';
    }
    return '<span class="inv-badge inactive"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Inactive</span>';
};

window.dtRenders.actions = function(data, row) {
    return `
        <a href="{{ url('admin/inventory/racks') }}/${row.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteRack(${row.id})" title="Delete">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
};

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
            if (window.dtReload) {
                window.dtReload();
            } else {
                location.reload();
            }
        } else {
            alert(result.message || 'Error deleting rack');
        }
    })
    .catch(error => alert('Error: ' + error));
}
</script>

@include('components.datatable')
</x-layouts.app>