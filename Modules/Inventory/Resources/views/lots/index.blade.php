

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header h1 {a
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
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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
        flex-shrink: 0;
    }
    
    .stat-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.red { background: #fee2e2; color: #dc2626; }
    .stat-icon.orange { background: #ffedd5; color: #ea580c; }
    
    .stat-content {
        flex: 1;
        min-width: 0;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
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

    /* Product Cell Styling */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .product-image {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--card-border);
        background: var(--body-bg);
        flex-shrink: 0;
    }
    
    .product-image-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        flex-shrink: 0;
    }
    
    .product-image-placeholder svg {
        width: 20px;
        height: 20px;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    
    .product-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-sku {
        font-size: 12px;
        color: var(--text-muted);
        font-family: monospace;
    }

    /* Lot number styling */
    .lot-number {
        font-family: monospace;
        font-weight: 600;
        color: var(--primary);
    }
    
    .batch-no {
        font-size: 11px;
        color: var(--text-muted);
        display: block;
        margin-top: 2px;
    }

    /* Expiry styling */
    .expiry-ok { color: var(--text-primary); }
    .expiry-medium { color: #3b82f6; }
    .expiry-soon { color: #ea580c; font-weight: 600; }
    .expiry-expired { color: #dc2626; font-weight: 600; }
    .expiry-none { color: var(--text-muted); font-style: italic; }
    
    .expiry-days {
        font-size: 11px;
        display: block;
        margin-top: 2px;
    }

    /* Stock styling */
    .stock-value {
        font-weight: 600;
    }
    
    .stock-unit {
        font-size: 11px;
        color: var(--text-muted);
        margin-left: 4px;
    }
    
    .stock-low { color: #dc2626; }
    .stock-medium { color: #ea580c; }
    .stock-ok { color: var(--text-primary); }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .table-filters {
            width: 100%;
        }
        
        .table-filters select {
            flex: 1;
            min-width: 120px;
        }
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
        <a href="{{ route('inventory.lots.create') }}" class="btn-add">
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
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['expiring_soon'] ?? 0 }}</div>
                <div class="stat-label">Expiring Soon</div>
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
                <select data-dt-filter="product_id" data-dt-table="lotsTable">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                    @endforeach
                </select>
                <select data-dt-filter="status" data-dt-table="lotsTable">
                    <option value="">All Status</option>
                    <option value="ACTIVE">Active</option>
                    <option value="RECALLED">Recalled</option>
                    <option value="EXPIRED">Expired</option>
                    <option value="CONSUMED">Consumed</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   id="lotsTable"
                   data-route="{{ route('inventory.lots.data') }}">
                <thead>
                    <tr>
                        <th data-col="id" class="dt-sort" style="width: 60px;">ID</th>
                        <th data-col="lot_no" class="dt-sort" data-render="lot">Lot No</th>
                        <th data-col="product_name" data-render="product">Product</th>
                        <th data-col="initial_qty" class="dt-sort" style="text-align: right;">Initial Qty</th>
                        <th data-col="current_stock" class="dt-sort" data-render="stock" style="text-align: right;">Current Stock</th>
                        <th data-col="expiry_date" class="dt-sort" data-render="expiry">Expiry</th>
                        <th data-col="status" data-render="badge">Status</th>
                        <th data-render="actions" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Custom Renderers for DataTable --}}
<script>
window.dtRenders = window.dtRenders || {};

// ─────────────────────────────────────────────────────────────────────────────
// LOT NUMBER RENDERER
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.lot = function(value, row) {
    var html = '<span class="lot-number">' + (row.lot_no || '-') + '</span>';
    if (row.batch_no && row.batch_no !== '-') {
        html += '<span class="batch-no">Batch: ' + row.batch_no + '</span>';
    }
    return html;
};

// ─────────────────────────────────────────────────────────────────────────────
// PRODUCT CELL RENDERER (with image and variation)
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.product = function(value, row) {
    var imageHtml = '';
    
    if (row.product_image) {
        imageHtml = '<img src="' + row.product_image + '" class="product-image" alt="' + (row.product_name || 'Product') + '" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\';">' +
                    '<div class="product-image-placeholder" style="display:none;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>';
    } else {
        imageHtml = '<div class="product-image-placeholder"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>';
    }
    
    var variationHtml = '';
    if (row.variation_name) {
        variationHtml = '<div class="product-variation" style="font-size: 11px; color: #8b5cf6; margin-top: 2px;">⬥ ' + row.variation_name + '</div>';
    }
    
    return '<div class="product-cell">' +
        imageHtml +
        '<div class="product-info">' +
            '<div class="product-name">' + (row.product_name || '-') + '</div>' +
            '<div class="product-sku">' + (row.product_sku || '') + '</div>' +
            variationHtml +
        '</div>' +
    '</div>';
};

// ─────────────────────────────────────────────────────────────────────────────
// STOCK RENDERER (with unit and color)
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.stock = function(value, row) {
    var stockVal = parseFloat(row.current_stock_raw || 0);
    var colorClass = 'stock-ok';
    
    if (stockVal <= 0) {
        colorClass = 'stock-low';
    } else if (stockVal < 10) {
        colorClass = 'stock-medium';
    }
    
    return '<span class="stock-value ' + colorClass + '">' + 
           (row.current_stock || '0.00') + 
           '<span class="stock-unit">' + (row.unit_name || 'PCS') + '</span></span>';
};

// ─────────────────────────────────────────────────────────────────────────────
// EXPIRY DATE RENDERER (with days and color)
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.expiry = function(value, row) {
    if (!row.expiry_date || row.expiry_date === '-') {
        return '<span class="expiry-none">No Expiry</span>';
    }
    
    var days = row.days_to_expiry;
    var colorClass = 'expiry-ok';
    var daysText = '';
    
    if (row.is_expired || days < 0) {
        colorClass = 'expiry-expired';
        daysText = '<span class="expiry-days">Expired ' + Math.abs(days) + ' days ago</span>';
    } else if (days <= 30) {
        colorClass = 'expiry-soon';
        daysText = '<span class="expiry-days">' + days + ' days left</span>';
    } else if (days <= 90) {
        colorClass = 'expiry-medium';
        daysText = '<span class="expiry-days">' + days + ' days</span>';
    }
    
    return '<span class="' + colorClass + '">' + row.expiry_date + daysText + '</span>';
};

// ─────────────────────────────────────────────────────────────────────────────
// STATUS BADGE RENDERER
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.badge = function(value, row) {
    var statusMap = {
        'ACTIVE': 'active',
        'RECALLED': 'warning',
        'EXPIRED': 'danger',
        'CONSUMED': 'secondary'
    };
    var badgeClass = statusMap[row.status] || 'secondary';
    return '<span class="dt-badge dt-badge-' + badgeClass + '">' + (row.status || '-') + '</span>';
};

// ─────────────────────────────────────────────────────────────────────────────
// ACTIONS RENDERER
// ─────────────────────────────────────────────────────────────────────────────
window.dtRenders.actions = function(value, row) {
    return '<div class="dt-actions">' +
        '<a href="' + row._edit_url + '" class="dt-btn dt-btn-edit" title="Edit">Edit</a>' +
        '<button type="button" class="dt-btn dt-btn-delete" data-id="' + row.id + '" data-url="' + row._delete_url + '" title="Delete">Delete</button>' +
    '</div>';
};
</script>

{{-- Include DataTable Component --}}
@include('core::datatable')
