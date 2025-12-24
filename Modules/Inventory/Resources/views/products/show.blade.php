

<style>
    .product-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .product-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--card-border);
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
        flex-shrink: 0;
    }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .back-btn svg { width: 20px; height: 20px; }
    
    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        flex-shrink: 0;
        overflow: hidden;
    }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    
    .product-title-section { flex: 1; }
    .product-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 6px 0;
    }
    .product-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .product-sku {
        font-family: monospace;
        font-size: 13px;
        color: var(--text-muted);
        background: var(--body-bg);
        padding: 4px 10px;
        border-radius: 4px;
    }
    
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .badge-blue { background: #dbeafe; color: #1e40af; }
    .badge-amber { background: #fef3c7; color: #92400e; }
    
    .header-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn svg { width: 16px; height: 16px; }
    .btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
    .btn-primary:hover { box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); color: #fff; }
    .btn-secondary { background: var(--card-bg); color: var(--text-primary); border: 1px solid var(--card-border); }
    .btn-secondary:hover { background: var(--body-bg); }
    .btn-green { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
    .btn-green:hover { box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); color: #fff; }

    /* Stats Row */
    .stats-row {
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
        text-align: center;
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 20px;
    }
    .stat-icon.stock { background: #ecfdf5; }
    .stat-icon.value { background: #eff6ff; }
    .stat-icon.purchase { background: #fef3c7; }
    .stat-icon.sale { background: #f3e8ff; }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    .stat-value.green { color: #059669; }
    .stat-value.blue { color: #2563eb; }
    .stat-value.amber { color: #d97706; }
    .stat-value.purple { color: #7c3aed; }
    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-sub {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
    }
    @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }

    /* Cards */
    .card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        margin-bottom: 20px;
    }
    .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-title .count {
        background: var(--body-bg);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
        color: var(--text-muted);
    }
    .card-body { padding: 20px; }
    .card-body.no-pad { padding: 0; }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 768px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
    .info-item label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 4px;
    }
    .info-item span {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
    }
    .info-item .price {
        font-size: 16px;
        font-weight: 700;
        color: #059669;
    }

    /* Flags */
    .flags-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--card-border);
    }
    .flag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    .flag svg { width: 12px; height: 12px; }
    .flag.on { background: #d1fae5; color: #065f46; }
    .flag.off { background: var(--body-bg); color: var(--text-muted); }

    /* Stock Table */
    .stock-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stock-table th, .stock-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    .stock-table th {
        background: var(--body-bg);
        font-weight: 500;
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stock-table tbody tr:hover { background: var(--body-bg); }
    .stock-table tbody tr:last-child td { border-bottom: none; }
    
    .location-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .location-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 14px;
    }
    .location-name { font-weight: 500; color: var(--text-primary); }
    .location-rack {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .rack-tag {
        display: inline-block;
        background: #ede9fe;
        color: #5b21b6;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        margin-right: 4px;
    }
    .lot-tag {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
    }
    
    .qty-cell {
        font-weight: 600;
        font-size: 14px;
    }
    .qty-cell .unit {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 11px;
        margin-left: 4px;
    }
    .qty-positive { color: #059669; }
    .qty-negative { color: #dc2626; }

    /* Movement Type Badges */
    .mv-type {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    .mv-type.in { background: #ecfdf5; color: #059669; }
    .mv-type.out { background: #fef2f2; color: #dc2626; }
    .mv-type.transfer { background: #f3e8ff; color: #7c3aed; }
    .mv-type.return { background: #ecfeff; color: #0891b2; }
    .mv-type.adjustment { background: #fffbeb; color: #d97706; }

    /* Units Grid */
    .units-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }
    .unit-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 14px;
    }
    .unit-card.base {
        border-color: #6366f1;
        background: #eef2ff;
    }
    .unit-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    .unit-short {
        font-size: 11px;
        color: var(--text-muted);
        font-family: monospace;
    }
    .unit-factor {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed var(--card-border);
        font-size: 12px;
        color: var(--text-muted);
    }
    .unit-factor strong { color: var(--text-primary); }
    .unit-prices {
        display: flex;
        gap: 12px;
        margin-top: 8px;
        font-size: 12px;
    }
    .unit-prices span { color: var(--text-muted); }
    .unit-prices strong { color: #059669; }

    /* Variations Section */
    .variations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .load-more-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        font-size: 12px;
        cursor: pointer;
    }
    .load-more-btn:hover { background: var(--body-bg); }
    
    .variations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }
    .var-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 14px;
    }
    .var-card.inactive { opacity: 0.5; }
    .var-sku { font-weight: 600; font-size: 13px; color: var(--text-primary); margin-bottom: 4px; }
    .var-name { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
    .var-attrs { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px; }
    .var-attr {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    .var-attr .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid var(--card-border);
    }
    .var-footer {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding-top: 8px;
        border-top: 1px dashed var(--card-border);
    }
    .var-price { font-weight: 600; color: #059669; }
    .var-stock { color: var(--text-muted); }
    .var-barcode { 
        font-family: 'Courier New', monospace; 
        font-size: 11px; 
        color: var(--text-muted); 
        margin: 6px 0;
        padding: 4px 8px;
        background: var(--body-bg);
        border-radius: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .var-barcode.no-barcode { 
        font-style: italic; 
        color: #9ca3af;
        background: transparent;
        padding: 4px 0;
    }

    /* Quick Actions Sidebar */
    .action-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 12px 14px;
        border-radius: 8px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    .action-btn:last-child { margin-bottom: 0; }
    .action-btn:hover { background: var(--card-border); color: var(--text-primary); }
    .action-btn .icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .action-btn .icon.green { background: #d1fae5; }
    .action-btn .icon.red { background: #fee2e2; }
    .action-btn .icon.purple { background: #ede9fe; }
    .action-btn .icon.amber { background: #fef3c7; }
    .action-btn .icon.blue { background: #dbeafe; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    .empty-state svg { width: 48px; height: 48px; opacity: 0.3; margin-bottom: 12px; }
    .empty-state p { margin: 0; font-size: 14px; }

    /* Pagination */
    .pagination-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-top: 1px solid var(--card-border);
        font-size: 12px;
        color: var(--text-muted);
    }
    .pagination-btns { display: flex; gap: 6px; }
    .page-btn {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        font-size: 12px;
        cursor: pointer;
    }
    .page-btn:hover { background: var(--body-bg); }
    .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Loading */
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
    .spinner {
        width: 24px;
        height: 24px;
        border: 3px solid var(--card-border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 12px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

@php
    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $totalStock = $product->stockLevels->sum('qty');
    $stockValue = $totalStock * $product->purchase_price;
    $saleValue = $totalStock * $product->sale_price;
    $unitName = $product->unit->short_name ?? 'PCS';
    
    // Stock status
    $stockStatus = 'ok';
    $stockMessage = 'In Stock';
    if ($totalStock <= 0) {
        $stockStatus = 'out';
        $stockMessage = 'Out of Stock';
    } elseif ($product->min_stock_level > 0 && $totalStock <= $product->min_stock_level) {
        $stockStatus = 'low';
        $stockMessage = 'Low Stock';
    } elseif ($product->max_stock_level > 0 && $totalStock >= $product->max_stock_level) {
        $stockStatus = 'over';
        $stockMessage = 'Overstocked';
    }
    
    // Group stock by warehouse with details
    $stockDetails = $product->stockLevels()
        ->with(['warehouse', 'rack', 'lot', 'unit', 'variation.attributeValues.attribute'])
        ->where('qty', '>', 0)
        ->orderBy('variation_id')
        ->orderBy('warehouse_id')
        ->get();
@endphp

<div class="product-container">
    <!-- Header -->
    <div class="product-header">
        <a href="{{ route('inventory.products.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        
        <div class="product-image">
            @if($primaryImage)
                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" alt="{{ $product->name }}">
            @else
                {{ strtoupper(substr($product->name, 0, 2)) }}
            @endif
        </div>
        
        <div class="product-title-section">
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="product-meta">
                <span class="product-sku">{{ $product->sku }}</span>
                @if($product->barcode)
                    <span class="product-sku">{{ $product->barcode }}</span>
                @endif
                <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($product->has_variants)
                    <span class="badge badge-purple">Has Variants</span>
                @endif
                @if($product->is_batch_managed)
                    <span class="badge badge-amber">Batch Managed</span>
                @endif
                <span class="badge {{ $stockStatus === 'low' || $stockStatus === 'out' ? 'badge-danger' : ($stockStatus === 'over' ? 'badge-amber' : 'badge-success') }}">
                    {{ $stockMessage }}
                </span>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('inventory.stock.receive') }}?product_id={{ $product->id }}" class="btn btn-green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Stock
            </a>
            <a href="{{ route('inventory.products.edit', $product->id) }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon stock">📦</div>
            <div class="stat-value green">{{ number_format($totalStock, 2) }}</div>
            <div class="stat-label">Total Stock</div>
            <div class="stat-sub">{{ $unitName }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon value">💰</div>
            <div class="stat-value blue">₹{{ number_format($stockValue, 0) }}</div>
            <div class="stat-label">Stock Value</div>
            <div class="stat-sub">@ ₹{{ number_format($product->purchase_price, 2) }}/{{ $unitName }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purchase">🏷️</div>
            <div class="stat-value amber">₹{{ number_format($product->purchase_price, 2) }}</div>
            <div class="stat-label">Purchase Price</div>
            <div class="stat-sub">Per {{ $unitName }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon sale">💵</div>
            <div class="stat-value purple">₹{{ number_format($product->sale_price, 2) }}</div>
            <div class="stat-label">Sale Price</div>
            <div class="stat-sub">{{ $product->mrp ? 'MRP: ₹' . number_format($product->mrp, 2) : '' }}</div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div>
            <!-- Product Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📋 Product Details</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Category</label>
                            <span>{{ $product->category->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Brand</label>
                            <span>{{ $product->brand->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Base Unit</label>
                            <span>{{ $product->unit->name ?? 'Pieces' }} ({{ $unitName }})</span>
                        </div>
                        <div class="info-item">
                            <label>HSN Code</label>
                            <span>{{ $product->hsn_code ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Min Stock Level</label>
                            <span>{{ $product->min_stock_level ?? 0 }} {{ $unitName }}</span>
                        </div>
                        <div class="info-item">
                            <label>Max Stock Level</label>
                            <span>{{ $product->max_stock_level ?? 0 }} {{ $unitName }}</span>
                        </div>
                        @if($product->tax1)
                        <div class="info-item">
                            <label>{{ $product->tax1->name }}</label>
                            <span>{{ $product->tax1->rate }}%</span>
                        </div>
                        @endif
                        @if($product->tax2)
                        <div class="info-item">
                            <label>{{ $product->tax2->name }}</label>
                            <span>{{ $product->tax2->rate }}%</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <label>Profit Margin</label>
                            <span>{{ $product->default_profit_rate ?? 0 }}%</span>
                        </div>
                    </div>
                    
                    <!-- Flags -->
                    <div class="flags-row">
                        <span class="flag {{ $product->can_be_sold ? 'on' : 'off' }}">
                            @if($product->can_be_sold)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Can Sell
                        </span>
                        <span class="flag {{ $product->can_be_purchased ? 'on' : 'off' }}">
                            @if($product->can_be_purchased)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Can Purchase
                        </span>
                        <span class="flag {{ $product->track_inventory ? 'on' : 'off' }}">
                            @if($product->track_inventory)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Track Stock
                        </span>
                        <span class="flag {{ $product->is_batch_managed ? 'on' : 'off' }}">
                            @if($product->is_batch_managed)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Batch Managed
                        </span>
                        <span class="flag {{ $product->has_variants ? 'on' : 'off' }}">
                            @if($product->has_variants)
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Has Variants
                        </span>
                    </div>
                    
                    @if($product->description)
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--card-border);">
                        <label style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Description</label>
                        <p style="margin: 0; color: var(--text-primary); line-height: 1.6; font-size: 14px;">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Available Units -->
            @if($product->productUnits->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📐 Available Units <span class="count">{{ $product->productUnits->count() + 1 }}</span></h3>
                </div>
                <div class="card-body">
                    <div class="units-grid">
                        <!-- Base Unit -->
                        <div class="unit-card base">
                            <div class="unit-name">{{ $product->unit->name ?? 'Pieces' }}</div>
                            <div class="unit-short">{{ $unitName }} (Base Unit)</div>
                            <div class="unit-badges" style="margin: 8px 0; display: flex; gap: 4px; flex-wrap: wrap;">
                                <span class="badge badge-blue" style="font-size:10px;padding:3px 8px;">📦 Stock Unit</span>
                            </div>
                            <div class="unit-factor">1 {{ $unitName }} = <strong>1</strong> {{ $unitName }}</div>
                            <div class="unit-prices">
                                <span>Buy: <strong>₹{{ number_format($product->purchase_price, 2) }}</strong></span>
                                <span>Sell: <strong>₹{{ number_format($product->sale_price, 2) }}</strong></span>
                            </div>
                        </div>
                        
                        <!-- Additional Units -->
                        @foreach($product->productUnits as $pu)
                        <div class="unit-card">
                            <div class="unit-name">{{ $pu->unit_name ?: $pu->unit->name }}</div>
                            <div class="unit-short">{{ $pu->unit->short_name ?? '' }}{{ $pu->barcode ? ' • ' . $pu->barcode : '' }}</div>
                            <div class="unit-badges" style="margin: 8px 0; display: flex; gap: 4px; flex-wrap: wrap;">
                                @if($pu->is_purchase_unit)
                                <span class="badge badge-amber" style="font-size:10px;padding:3px 8px;">🛒 Purchase</span>
                                @endif
                                @if($pu->is_sale_unit)
                                <span class="badge badge-success" style="font-size:10px;padding:3px 8px;">💰 Sale</span>
                                @endif
                            </div>
                            <div class="unit-factor">
                                1 {{ $pu->unit->short_name ?? '' }} = <strong>{{ number_format($pu->conversion_factor, 4) }}</strong> {{ $unitName }}
                            </div>
                            <div class="unit-prices">
                                <span>Buy: <strong>₹{{ number_format($pu->purchase_price ?? ($product->purchase_price * $pu->conversion_factor), 2) }}</strong></span>
                                <span>Sell: <strong>₹{{ number_format($pu->sale_price ?? ($product->sale_price * $pu->conversion_factor), 2) }}</strong></span>
                            </div>
                            @if($pu->conversion_factor > 1)
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--card-border); font-size: 11px; color: var(--text-muted);">
                                💡 Receive 2 {{ $pu->unit->short_name ?? '' }} = {{ number_format(2 * $pu->conversion_factor, 0) }} {{ $unitName }} added to stock
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    <div style="margin-top: 16px; padding: 12px; background: var(--body-bg); border-radius: 8px; font-size: 12px; color: var(--text-muted);">
                        <strong style="color: var(--text-primary);">ℹ️ How Units Work:</strong><br>
                        • Stock is always stored in <strong>{{ $unitName }}</strong> (base unit)<br>
                        • When receiving/delivering, you can select any unit - it auto-converts to {{ $unitName }}<br>
                        • Example: Receive 2 "Box of 5" → Stock increases by 10 {{ $unitName }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Stock by Location -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🏭 Stock by Location <span class="count">{{ $stockDetails->count() }} entries</span></h3>
                </div>
                <div class="card-body no-pad">
                    @if($stockDetails->count() > 0)
                    <table class="stock-table">
                        <thead>
                            <tr>
                                @if($product->has_variants)
                                <th>Variation</th>
                                @endif
                                <th>Location</th>
                                <th>Lot / Batch</th>
                                <th style="text-align: right;">Quantity</th>
                                <th style="text-align: right;">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockDetails as $stock)
                            <tr>
                                @if($product->has_variants)
                                <td>
                                    @if($stock->variation)
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                                            @foreach($stock->variation->attributeValues as $av)
                                                @if($av->attribute && $av->attribute->type === 'color')
                                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 12px; font-size: 11px;">
                                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $av->color_code ?? '#ccc' }}; border: 1px solid rgba(0,0,0,0.1);"></span>
                                                        {{ $av->value }}
                                                    </span>
                                                @else
                                                    <span style="padding: 3px 8px; background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 12px; font-size: 11px;">{{ $av->value }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">{{ $stock->variation->sku }}</div>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <div class="location-cell">
                                        <div class="location-icon">🏭</div>
                                        <div>
                                            <div class="location-name">{{ $stock->warehouse->name ?? '-' }}</div>
                                            @if($stock->rack)
                                            <div class="location-rack">
                                                <span class="rack-tag">{{ $stock->rack->code }}</span>
                                                {{ $stock->rack->name ?? '' }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($stock->lot)
                                        <span class="lot-tag">{{ $stock->lot->lot_no }}</span>
                                        @if($stock->lot->batch_no)
                                            <span style="font-size: 11px; color: var(--text-muted);">/ {{ $stock->lot->batch_no }}</span>
                                        @endif
                                        @if($stock->lot->expiry_date)
                                            <br><span style="font-size: 11px; color: {{ $stock->lot->expiry_date->isPast() ? '#dc2626' : 'var(--text-muted)' }};">
                                                Exp: {{ $stock->lot->expiry_date->format('d M Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <span class="qty-cell">{{ number_format($stock->qty, 2) }}<span class="unit">{{ $unitName }}</span></span>
                                </td>
                                <td style="text-align: right;">
                                    <span style="font-weight: 500; color: #059669;">₹{{ number_format($stock->qty * $product->purchase_price, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: var(--body-bg); font-weight: 600;">
                            <tr>
                                <td colspan="{{ $product->has_variants ? 3 : 2 }}">Total</td>
                                <td style="text-align: right;">{{ number_format($totalStock, 2) }} {{ $unitName }}</td>
                                <td style="text-align: right; color: #059669;">₹{{ number_format($stockValue, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p>No stock available</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Variations (Lazy Loaded) -->
            @if($product->has_variants)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🎨 Variations <span class="count" id="variationCount">Loading...</span></h3>
                    <button class="load-more-btn" id="loadVariationsBtn" onclick="loadVariations()">Load Variations</button>
                </div>
                <div class="card-body" id="variationsContainer">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>Click "Load Variations" to see all variants</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Movements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📊 Stock Movement History</h3>
                    <a href="{{ route('inventory.stock.movements') }}?product_id={{ $product->id }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                        View All
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="card-body no-pad">
                    @if($recentMovements->count() > 0)
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reference</th>
                                <th>Location</th>
                                <th style="text-align: right;">Qty</th>
                                <th style="text-align: right;">Stock</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $mv)
                            @php
                                $isPositive = in_array($mv->movement_type, ['IN', 'RETURN']);
                                $typeClass = match($mv->movement_type) {
                                    'IN' => 'in',
                                    'OUT' => 'out',
                                    'TRANSFER' => 'transfer',
                                    'RETURN' => 'return',
                                    default => 'adjustment'
                                };
                                $typeIcon = match($mv->movement_type) {
                                    'IN' => '📥',
                                    'OUT' => '📤',
                                    'TRANSFER' => '🔄',
                                    'RETURN' => '↩️',
                                    default => '⚖️'
                                };
                                
                                // Check if transaction unit differs from base unit
                                $txnUnitName = $mv->unit->short_name ?? $unitName;
                                $isDifferentUnit = $mv->qty != $mv->base_qty && $mv->base_qty;
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-size: 13px;">{{ $mv->created_at->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $mv->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="mv-type {{ $typeClass }}">{{ $typeIcon }} {{ $mv->movement_type }}</span>
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-size: 12px;">{{ $mv->reference_no ?? '-' }}</span>
                                </td>
                                <td>
                                    <div style="font-size: 13px;">{{ $mv->warehouse->name ?? '-' }}</div>
                                    @if($mv->rack)
                                    <div style="font-size: 11px;"><span class="rack-tag">{{ $mv->rack->code }}</span></div>
                                    @endif
                                    @if($mv->lot)
                                    <div style="font-size: 10px;"><span class="lot-tag">{{ $mv->lot->lot_no }}</span></div>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    {{-- Show transaction quantity in the unit used --}}
                                    <span class="qty-cell {{ $isPositive ? 'qty-positive' : 'qty-negative' }}">
                                        {{ $isPositive ? '+' : '-' }}{{ number_format($mv->qty, 2) }}
                                        <span class="unit">{{ $txnUnitName }}</span>
                                    </span>
                                    {{-- If different from base unit, show base qty too --}}
                                    @if($isDifferentUnit)
                                    <div style="font-size: 10px; color: var(--text-muted);">
                                        = {{ number_format($mv->base_qty, 2) }} {{ $unitName }}
                                    </div>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        {{ number_format($mv->stock_before, 2) }} → {{ number_format($mv->stock_after, 2) }}
                                        <span style="font-size: 9px;">{{ $unitName }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ Str::limit($mv->reason, 25) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <p>No movements recorded yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">⚡ Quick Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('inventory.stock.receive') }}?product_id={{ $product->id }}" class="action-btn">
                        <span class="icon green">📥</span>
                        <span>Receive Stock</span>
                    </a>
                    <a href="{{ route('inventory.stock.deliver') }}?product_id={{ $product->id }}" class="action-btn">
                        <span class="icon red">📤</span>
                        <span>Deliver Stock</span>
                    </a>
                    <a href="{{ route('inventory.stock.transfer') }}?product_id={{ $product->id }}" class="action-btn">
                        <span class="icon purple">🔄</span>
                        <span>Transfer Stock</span>
                    </a>
                    <a href="{{ route('inventory.stock.returns') }}?product_id={{ $product->id }}" class="action-btn">
                        <span class="icon blue">↩️</span>
                        <span>Process Return</span>
                    </a>
                    <a href="{{ route('inventory.stock.adjustments') }}?product_id={{ $product->id }}" class="action-btn">
                        <span class="icon amber">⚖️</span>
                        <span>Adjust Stock</span>
                    </a>
                </div>
            </div>

            <!-- Stock Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📈 Stock Summary</h3>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                        <span style="color: var(--text-muted); font-size: 13px;">Total Quantity</span>
                        <span style="font-weight: 600; font-size: 14px;">{{ number_format($totalStock, 2) }} {{ $unitName }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                        <span style="color: var(--text-muted); font-size: 13px;">Purchase Value</span>
                        <span style="font-weight: 600; font-size: 14px; color: #d97706;">₹{{ number_format($stockValue, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                        <span style="color: var(--text-muted); font-size: 13px;">Sale Value</span>
                        <span style="font-weight: 600; font-size: 14px; color: #059669;">₹{{ number_format($saleValue, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                        <span style="color: var(--text-muted); font-size: 13px;">Potential Profit</span>
                        <span style="font-weight: 600; font-size: 14px; color: #7c3aed;">₹{{ number_format($saleValue - $stockValue, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="color: var(--text-muted); font-size: 13px;">Locations</span>
                        <span style="font-weight: 600; font-size: 14px;">{{ $stockDetails->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Stock in Different Units -->
            @if($product->productUnits->count() > 0 && $totalStock > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📦 Stock in Units</h3>
                </div>
                <div class="card-body" style="padding: 16px;">
                    {{-- Base Unit --}}
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border); background: #eef2ff; margin: -16px -16px 0; padding: 12px 16px;">
                        <span style="font-weight: 500; font-size: 13px;">{{ $unitName }} <span style="color: var(--text-muted); font-size: 11px;">(base)</span></span>
                        <span style="font-weight: 700; font-size: 14px; color: #4f46e5;">{{ number_format($totalStock, 2) }}</span>
                    </div>
                    
                    {{-- Other Units --}}
                    @foreach($product->productUnits as $pu)
                    @php
                        $stockInUnit = $pu->conversion_factor > 0 ? $totalStock / $pu->conversion_factor : 0;
                        $fullUnits = floor($stockInUnit);
                        $remainder = $totalStock - ($fullUnits * $pu->conversion_factor);
                    @endphp
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                        <span style="color: var(--text-muted); font-size: 13px;">
                            {{ $pu->unit_name ?: $pu->unit->name }}
                            <span style="font-size: 10px;">({{ $pu->conversion_factor }}x)</span>
                        </span>
                        <span style="font-weight: 600; font-size: 14px;">
                            {{ number_format($stockInUnit, 2) }}
                            @if($remainder > 0 && $fullUnits > 0)
                            <span style="font-size: 10px; color: var(--text-muted);">
                                ({{ $fullUnits }} full + {{ number_format($remainder, 2) }} {{ $unitName }})
                            </span>
                            @endif
                        </span>
                    </div>
                    @endforeach
                    
                    <div style="margin-top: 12px; font-size: 11px; color: var(--text-muted); padding: 8px; background: var(--body-bg); border-radius: 6px;">
                        💡 Stock is stored in {{ $unitName }}. Above shows equivalent quantities in other units.
                    </div>
                </div>
            </div>
            @endif

            <!-- Reorder Info -->
            @if($product->min_stock_level > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🔔 Reorder Info</h3>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <span style="color: var(--text-muted); font-size: 13px;">Min Level</span>
                        <span style="font-weight: 500;">{{ $product->min_stock_level }} {{ $unitName }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <span style="color: var(--text-muted); font-size: 13px;">Max Level</span>
                        <span style="font-weight: 500;">{{ $product->max_stock_level ?? '-' }} {{ $unitName }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                        <span style="color: var(--text-muted); font-size: 13px;">Current</span>
                        <span style="font-weight: 600; color: {{ $totalStock <= $product->min_stock_level ? '#dc2626' : '#059669' }};">{{ number_format($totalStock, 2) }} {{ $unitName }}</span>
                    </div>
                    @if($totalStock <= $product->min_stock_level)
                    <div style="margin-top: 12px; padding: 12px; background: #fef2f2; border-radius: 8px; text-align: center;">
                        <span style="color: #dc2626; font-weight: 600; font-size: 13px;">⚠️ Reorder Required!</span>
                        <div style="font-size: 12px; color: #991b1b; margin-top: 4px;">
                            Order at least {{ number_format(max(0, $product->min_stock_level - $totalStock), 0) }} {{ $unitName }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Tags -->
            @if($product->tags->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🏷️ Tags</h3>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach($product->tags as $tag)
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; background: {{ $tag->color ?? '#e5e7eb' }}20; color: {{ $tag->color ?? '#6b7280' }}; border: 1px solid {{ $tag->color ?? '#e5e7eb' }};">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($product->has_variants)
<script>
let variationsLoaded = false;

function loadVariations() {
    if (variationsLoaded) return;
    
    const container = document.getElementById('variationsContainer');
    const countEl = document.getElementById('variationCount');
    const btn = document.getElementById('loadVariationsBtn');
    
    container.innerHTML = '<div class="loading"><div class="spinner"></div><p>Loading variations...</p></div>';
    btn.disabled = true;
    btn.textContent = 'Loading...';
    
    fetch('{{ route("inventory.products.variations", $product->id) }}')
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.variations.length) {
                container.innerHTML = '<div class="empty-state"><p>No variations found</p></div>';
                countEl.textContent = '0';
                return;
            }
            
            countEl.textContent = data.variations.length;
            btn.style.display = 'none';
            variationsLoaded = true;
            
            let html = '<div class="variations-grid">';
            data.variations.forEach(v => {
                let attrs = '';
                if (v.attributes && v.attributes.length) {
                    attrs = v.attributes.map(a => {
                        let dot = a.color_code ? `<span class="dot" style="background:${a.color_code}"></span>` : '';
                        return `<span class="var-attr">${dot}${a.value}</span>`;
                    }).join('');
                }
                
                let barcodeHtml = v.barcode 
                    ? `<div class="var-barcode" title="${v.barcode}">🔲 ${v.barcode}</div>` 
                    : `<div class="var-barcode no-barcode">No barcode</div>`;
                
                html += `
                <div class="var-card ${v.is_active ? '' : 'inactive'}">
                    <div class="var-sku">${v.sku}</div>
                    <div class="var-name">${v.variation_name || '-'}</div>
                    ${attrs ? `<div class="var-attrs">${attrs}</div>` : ''}
                    ${barcodeHtml}
                    <div class="var-footer">
                        <span class="var-price">₹${parseFloat(v.sale_price || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        <span class="var-stock">${v.stock_qty || 0} {{ $unitName }}</span>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        })
        .catch(err => {
            container.innerHTML = '<div class="empty-state"><p>Failed to load variations</p></div>';
            btn.disabled = false;
            btn.textContent = 'Retry';
        });
}

// Auto-load if less than 20 variations expected
@if($product->variations()->count() <= 20)
document.addEventListener('DOMContentLoaded', loadVariations);
@endif
</script>
@endif
