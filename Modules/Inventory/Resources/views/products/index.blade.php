

<style>
    .inventory-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Compact Header */
    .inv-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .inv-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .inv-header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    
    .inv-header-icon svg {
        width: 22px;
        height: 22px;
    }
    
    .inv-header h1 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .inv-header-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .btn-add-product {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .btn-add-product:hover {
        background: var(--primary-hover);
        color: #fff;
    }
    
    .btn-add-product svg {
        width: 16px;
        height: 16px;
    }
    
    /* Compact Stats Bar */
    .inv-stats-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .inv-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 10px 16px;
        border-radius: 8px;
        min-width: 140px;
    }
    
    .inv-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .inv-stat-icon svg {
        width: 16px;
        height: 16px;
    }
    
    .inv-stat-icon.total { background: #eff6ff; color: #2563eb; }
    .inv-stat-icon.active { background: #ecfdf5; color: #059669; }
    .inv-stat-icon.inactive { background: #fef2f2; color: #dc2626; }
    .inv-stat-icon.lowstock { background: #fffbeb; color: #d97706; }
    
    .inv-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .inv-stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 2px;
    }
    
    /* Filters */
    .inv-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .inv-filter-select {
        padding: 7px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 130px;
        cursor: pointer;
    }
    
    .inv-filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }
    

    /* Table Container */
    .inv-table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    
    /* Force table to not truncate */
    .inv-table-wrap table {
        min-width: 1000px;
    }
    
    /* Smaller action buttons */
    .inv-table-wrap .btn,
    .inv-table-wrap .dt-actions .btn,
    .inv-table-wrap [class*="btn-"] {
        padding: 4px 10px !important;
        font-size: 11px !important;
        border-radius: 4px !important;
    }
    
    /* Even smaller for action column */
    .inv-table-wrap td:last-child .btn,
    .inv-table-wrap td:last-child [class*="btn-"] {
        padding: 3px 8px !important;
        font-size: 10px !important;
        margin: 1px !important;
    }
    
    /* Product Cell Styling */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .product-thumb {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        object-fit: cover;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
    }
    
    .product-thumb-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }
    
    .product-thumb-placeholder svg {
        width: 16px;
        height: 16px;
    }
    
    .product-info .name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 13px;
    }
    
    .product-info .sku {
        font-size: 11px;
        color: var(--text-muted);
        font-family: monospace;
    }
    
    /* Price Styling */
    .price-display {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }
    
    .price-display.purchase {
        color: var(--text-muted);
    }
    
    /* Stock Styling */
    .stock-display {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .stock-display.ok { color: #059669; }
    .stock-display.low { color: #dc2626; }
    
    .stock-display .unit {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 11px;
    }
    
    /* Badge Styling */
    .inv-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .inv-badge.active { background: #ecfdf5; color: #059669; }
    .inv-badge.inactive { background: #fef2f2; color: #dc2626; }
    .inv-badge.variant { background: #f3e8ff; color: #7c3aed; }
    .inv-badge.simple { background: var(--body-bg); color: var(--text-muted); }
    
    .inv-badge svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }
    
    /* Prevent table cell content from wrapping badly */
    .inv-table-wrap td {
        white-space: nowrap;
    }
    
    .inv-table-wrap td:nth-child(2) {
        white-space: normal;
        min-width: 180px;
    }
    
    /* Category/Brand tags */
    .cat-tag {
        font-size: 12px;
        color: var(--text-secondary);
    }
    
    .brand-tag {
        font-size: 12px;
        color: var(--text-muted);
    }
</style>

<div class="inventory-container">
    <!-- Header -->
    <div class="inv-header">
        <div class="inv-header-left">
            <div class="inv-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h1>Product Inventory</h1>
                <div class="inv-header-sub">Manage your product catalog and stock levels</div>
            </div>
        </div>
        <a href="{{ route('inventory.products.create') }}" class="btn-add-product">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    <!-- Compact Stats Bar -->
    <div class="inv-stats-bar">
        <div class="inv-stat">
            <div class="inv-stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
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
        @if(isset($stats['low_stock']) && $stats['low_stock'] > 0)
        <div class="inv-stat">
            <div class="inv-stat-icon lowstock">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['low_stock'] }}</div>
                <div class="inv-stat-label">Low Stock</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Products Table -->
    <div class="inv-table-wrap">
        <div class="inv-filters" style="padding: 12px 16px; border-bottom: 1px solid var(--card-border);">
            <select class="inv-filter-select" data-dt-filter="category_id">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select class="inv-filter-select" data-dt-filter="brand_id">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
            <select class="inv-filter-select" data-dt-filter="has_variants">
                <option value="">All Types</option>
                <option value="1">With Variants</option>
                <option value="0">Simple</option>
            </select>
            <select class="inv-filter-select" data-dt-filter="is_active">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <table class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
               id="productsTable"
               data-route="{{ route('inventory.products.data') }}">
            <thead>
                <tr>
                    {{-- ✅ Added dt-clickable to ID and Product columns --}}
                    <th class="dt-sort dt-clickable" data-col="id">ID</th>
                    <th class="dt-clickable" data-col="name" data-render="product" style="min-width: 200px;">Product</th>
                    <th class="dt-sort" data-col="category_name">Category</th>
                    <th class="dt-sort" data-col="brand_name">Brand</th>
                    <th class="dt-sort" data-col="purchase_price">Purchase</th>
                    <th class="dt-sort" data-col="sale_price">Sale</th>
                    <th class="dt-sort" data-col="current_stock" data-render="stock">Stock</th>
                    <th data-col="has_variants" data-render="variants">Type</th>
                    <th data-col="status" data-render="status">Status</th>
                    <th data-render="actions" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.product = function(data, row) {
    let imgHtml = '';
    if (row.image) {
        imgHtml = `<img src="${row.image}" class="product-thumb" alt="${row.name}">`;
    } else {
        imgHtml = `<div class="product-thumb-placeholder">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>`;
    }
    
    return `<div class="product-cell">
        ${imgHtml}
        <div class="product-info">
            <div class="name">${row.name}</div>
            <div class="sku">${row.sku || '-'}</div>
        </div>
    </div>`;
};

window.dtRenders.status = function(data, row) {
    if (row.is_active) {
        return '<span class="inv-badge active">Active</span>';
    }
    return '<span class="inv-badge inactive">Inactive</span>';
};

window.dtRenders.stock = function(data, row) {
    let cls = row.is_low_stock ? 'low' : 'ok';
    let icon = row.is_low_stock ? '<svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>' : '';
    return `<span class="stock-display ${cls}">${icon}${row.current_stock} <span class="unit">${row.unit || ''}</span></span>`;
};

window.dtRenders.variants = function(data, row) {
    if (row.has_variants) {
        return `<span class="inv-badge variant">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
            </svg>
            ${row.variant_count || ''} Variants
        </span>`;
    }
    return '<span class="inv-badge simple">Simple</span>';
};
</script>

@include('core::datatable')
