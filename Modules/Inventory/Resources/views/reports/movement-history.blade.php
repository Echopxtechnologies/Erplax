<x-layouts.app>
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
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
        color: var(--primary);
    }

    .filter-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .filter-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .filter-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .filter-group select,
    .filter-group input {
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
    }
    
    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: #fff;
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }

    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .table-card-body {
        padding: 0;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-in { background: #d1fae5; color: #065f46; }
    .badge-out { background: #fee2e2; color: #991b1b; }
    .badge-return { background: #dbeafe; color: #1e40af; }
    .badge-adjustment { background: #fef3c7; color: #92400e; }

    .qty-positive {
        color: #059669;
        font-weight: 600;
    }
    
    .qty-negative {
        color: #dc2626;
        font-weight: 600;
    }

    .btn-export {
        padding: 8px 14px;
        font-size: 13px;
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-export:hover {
        background: var(--card-border);
    }
    
    .btn-export svg {
        width: 16px;
        height: 16px;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            Movement History
        </h1>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">Filters</div>
        <div class="filter-grid">
            <div class="filter-group">
                <label>Product</label>
                <select id="filterProduct" onchange="applyFilters()">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Warehouse</label>
                <select id="filterWarehouse" onchange="applyFilters()">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Movement Type</label>
                <select id="filterType" onchange="applyFilters()">
                    <option value="">All Types</option>
                    <option value="IN">Stock In</option>
                    <option value="OUT">Stock Out</option>
                    <option value="RETURN">Return</option>
                    <option value="ADJUSTMENT">Adjustment</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date From</label>
                <input type="date" id="filterDateFrom" onchange="applyFilters()">
            </div>
            <div class="filter-group">
                <label>Date To</label>
                <input type="date" id="filterDateTo" onchange="applyFilters()">
            </div>
            <div class="filter-actions">
                <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Movement Log
            </div>
            <button class="btn-export" onclick="window.print()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage" 
                   id="movementsTable"
                   data-route="{{ route('inventory.reports.movement-history.data') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="id">ID</th>
                        <th class="dt-sort" data-col="date">Date</th>
                        <th class="dt-sort" data-col="product_name">Product</th>
                        <th class="dt-sort" data-col="warehouse_name">Warehouse</th>
                        <th data-col="lot_no">Lot</th>
                        <th data-col="movement_type" data-render="movement_type">Type</th>
                        <th class="dt-sort" data-col="qty" data-render="qty">Quantity</th>
                        <th data-col="reason">Reason</th>
                        <th data-col="created_by">By</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.movement_type = function(data, row) {
    let typeClass = {
        'IN': 'badge-in',
        'OUT': 'badge-out',
        'RETURN': 'badge-return',
        'ADJUSTMENT': 'badge-adjustment'
    };
    let typeLabel = {
        'IN': 'Stock In',
        'OUT': 'Stock Out',
        'RETURN': 'Return',
        'ADJUSTMENT': 'Adjustment'
    };
    return '<span class="badge ' + (typeClass[row.movement_type] || 'badge-in') + '">' + (typeLabel[row.movement_type] || row.movement_type) + '</span>';
};

window.dtRenders.qty = function(data, row) {
    let isPositive = ['IN', 'RETURN'].includes(row.movement_type) || (row.movement_type === 'ADJUSTMENT' && parseFloat(row.qty) >= 0);
    let prefix = isPositive ? '+' : '';
    let qtyClass = isPositive ? 'qty-positive' : 'qty-negative';
    
    if (row.movement_type === 'OUT') {
        return '<span class="qty-negative">-' + row.qty + ' ' + row.uom + '</span>';
    }
    
    return '<span class="' + qtyClass + '">' + prefix + row.qty + ' ' + row.uom + '</span>';
};

function applyFilters() {
    let filters = {
        product_id: document.getElementById('filterProduct').value,
        warehouse_id: document.getElementById('filterWarehouse').value,
        movement_type: document.getElementById('filterType').value,
        date_from: document.getElementById('filterDateFrom').value,
        date_to: document.getElementById('filterDateTo').value
    };
    
    if (window.dtInstance && window.dtInstance['movementsTable']) {
        window.dtInstance['movementsTable'].reload(filters);
    }
}

function resetFilters() {
    document.getElementById('filterProduct').value = '';
    document.getElementById('filterWarehouse').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterDateFrom').value = '';
    document.getElementById('filterDateTo').value = '';
    applyFilters();
}
</script>

@include('core::datatable')
</x-layouts.app>