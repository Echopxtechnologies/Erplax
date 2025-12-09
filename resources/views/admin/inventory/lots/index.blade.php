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
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: #fff;
    }
    
    .btn-add svg {
        width: 18px;
        height: 18px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.red { background: #fee2e2; color: #dc2626; }
    
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
        flex-wrap: wrap;
        gap: 12px;
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
    
    .table-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .table-filters select {
        padding: 8px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 150px;
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
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-purple { background: #ede9fe; color: #6d28d9; }
    
    .expiry-soon {
        color: #ea580c;
        font-weight: 600;
    }
    
    .expiry-expired {
        color: #dc2626;
        font-weight: 600;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Lots / Batches
        </h1>
        <a href="{{ route('admin.inventory.lots.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Lot
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Lots</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['available'] }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['expired'] }}</div>
                <div class="stat-label">Expired</div>
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
                Lot List
            </div>
            <div class="table-filters">
                <select id="filterProduct" onchange="applyFilters()">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <select id="filterStatus" onchange="applyFilters()">
                    <option value="">All Status</option>
                    <option value="AVAILABLE">Available</option>
                    <option value="RESERVED">Reserved</option>
                    <option value="EXPIRED">Expired</option>
                    <option value="CONSUMED">Consumed</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage" 
                   id="lotsTable"
                   data-route="{{ route('admin.inventory.lots.data') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="id">ID</th>
                        <th class="dt-sort" data-col="lot_no">Lot No</th>
                        <th class="dt-sort" data-col="product_name">Product</th>
                        <th class="dt-sort" data-col="initial_qty">Initial Qty</th>
                        <th class="dt-sort" data-col="purchase_price">Purchase Price</th>
                        <th class="dt-sort" data-col="manufacturing_date">Mfg Date</th>
                        <th class="dt-sort" data-col="expiry_date" data-render="expiry">Expiry Date</th>
                        <th data-col="status" data-render="status">Status</th>
                        <th data-render="actions">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.status = function(data, row) {
    let statusClass = {
        'AVAILABLE': 'badge-success',
        'RESERVED': 'badge-info',
        'EXPIRED': 'badge-danger',
        'CONSUMED': 'badge-warning'
    };
    return '<span class="badge ' + (statusClass[row.status] || 'badge-info') + '">' + row.status + '</span>';
};

window.dtRenders.expiry = function(data, row) {
    if (!row.expiry_date || row.expiry_date === '-') {
        return '-';
    }
    
    let expiryDate = new Date(row.expiry_date);
    let today = new Date();
    let diffDays = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) {
        return '<span class="expiry-expired">' + row.expiry_date + ' (Expired)</span>';
    } else if (diffDays <= 30) {
        return '<span class="expiry-soon">' + row.expiry_date + ' (' + diffDays + ' days)</span>';
    }
    return row.expiry_date;
};

function applyFilters() {
    let product = document.getElementById('filterProduct').value;
    let status = document.getElementById('filterStatus').value;
    
    if (window.dtInstance && window.dtInstance['lotsTable']) {
        window.dtInstance['lotsTable'].reload({
            product_id: product,
            status: status
        });
    }
}
</script>

@include('core::datatable')
</x-layouts.app>