<x-layouts.app>
<style>
    .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
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
        flex: 1;
    }
    
    .badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

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
    }
    
    .card-body {
        padding: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 20px;
    }
    
    .info-item label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }
    
    .info-item span {
        font-size: 15px;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .info-item .price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }

    .stock-display {
        text-align: center;
        padding: 30px 20px;
    }
    
    .stock-value {
        font-size: 48px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .stock-unit {
        font-size: 18px;
        color: var(--text-muted);
        margin-left: 4px;
    }
    
    .stock-label {
        font-size: 14px;
        color: var(--text-muted);
        margin-top: 8px;
    }
    
    .stock-status {
        margin-top: 12px;
        padding: 8px 16px;
        border-radius: 20px;
        display: inline-block;
        font-size: 13px;
        font-weight: 600;
    }
    
    .stock-status.low { background: #fee2e2; color: #991b1b; }
    .stock-status.ok { background: #d1fae5; color: #065f46; }
    .stock-status.high { background: #dbeafe; color: #1e40af; }

    .simple-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .simple-table th,
    .simple-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .simple-table th {
        background: var(--body-bg);
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .simple-table tbody tr:hover {
        background: var(--body-bg);
    }
    
    .simple-table tbody tr:last-child td {
        border-bottom: none;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .btn:last-child {
        margin-bottom: 0;
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
    }
    
    .rack-badge {
        font-size: 11px;
        padding: 2px 8px;
        background: #ede9fe;
        color: #5b21b6;
        border-radius: 4px;
        margin-left: 6px;
    }
    
    .warehouse-info {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .warehouse-icon {
        width: 16px;
        height: 16px;
        color: var(--text-muted);
    }

    /* Variations */
    .variations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }
    
    .variation-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 14px;
    }
    
    .variation-card.inactive {
        opacity: 0.5;
    }
    
    .variation-sku {
        font-weight: 600;
        font-size: 13px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .variation-name {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }
    
    .variation-attrs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    
    .variation-attr {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    
    .variation-attr .color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid var(--card-border);
    }
    
    .variation-meta {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
    }
    
    .variation-price {
        font-weight: 600;
        color: #059669;
    }
    
    .variation-stock {
        color: var(--text-muted);
    }

    .flags-display {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--card-border);
    }
    
    .flag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .flag-badge.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .flag-badge.inactive {
        background: var(--body-bg);
        color: var(--text-muted);
    }
    
    .flag-badge svg {
        width: 14px;
        height: 14px;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.inventory.products.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>{{ $product->name }}</h1>
        <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
            {{ $product->is_active ? 'Active' : 'Inactive' }}
        </span>
        @if($product->has_variants)
        <span class="badge badge-purple">Has Variants</span>
        @endif
    </div>

    <div class="content-grid">
        <!-- Main Content -->
        <div>
            <!-- Product Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Details</h3>
                    <a href="{{ route('admin.inventory.products.edit', $product->id) }}" class="btn btn-secondary" style="width: auto; margin: 0; padding: 8px 16px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>SKU</label>
                            <span>{{ $product->sku }}</span>
                        </div>
                        <div class="info-item">
                            <label>Barcode</label>
                            <span>{{ $product->barcode ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Category</label>
                            <span>{{ $product->category->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Brand</label>
                            <span>{{ $product->brand->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Unit</label>
                            <span>{{ $product->unit->name ?? 'PCS' }} ({{ $product->unit->short_name ?? 'PCS' }})</span>
                        </div>
                        <div class="info-item">
                            <label>HSN Code</label>
                            <span>{{ $product->hsn_code ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Purchase Price</label>
                            <span class="price">₹{{ number_format($product->purchase_price, 2) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Sale Price</label>
                            <span class="price">₹{{ number_format($product->sale_price, 2) }}</span>
                        </div>
                        @if($product->mrp)
                        <div class="info-item">
                            <label>MRP</label>
                            <span class="price">₹{{ number_format($product->mrp, 2) }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <label>Min Stock</label>
                            <span>{{ $product->min_stock_level }} {{ $product->unit->short_name ?? 'PCS' }}</span>
                        </div>
                        <div class="info-item">
                            <label>Max Stock</label>
                            <span>{{ $product->max_stock_level }} {{ $product->unit->short_name ?? 'PCS' }}</span>
                        </div>
                        @if($product->tax_1_name)
                        <div class="info-item">
                            <label>{{ $product->tax_1_name }}</label>
                            <span>{{ $product->tax_1_rate }}%</span>
                        </div>
                        @endif
                        @if($product->tax_2_name)
                        <div class="info-item">
                            <label>{{ $product->tax_2_name }}</label>
                            <span>{{ $product->tax_2_rate }}%</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Flags -->
                    <div class="flags-display">
                        <span class="flag-badge {{ $product->can_be_sold ? 'active' : 'inactive' }}">
                            @if($product->can_be_sold)
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Can be Sold
                        </span>
                        <span class="flag-badge {{ $product->can_be_purchased ? 'active' : 'inactive' }}">
                            @if($product->can_be_purchased)
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Can be Purchased
                        </span>
                        <span class="flag-badge {{ $product->track_inventory ? 'active' : 'inactive' }}">
                            @if($product->track_inventory)
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Track Inventory
                        </span>
                        <span class="flag-badge {{ $product->is_batch_managed ? 'active' : 'inactive' }}">
                            @if($product->is_batch_managed)
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            Batch Managed
                        </span>
                    </div>
                    
                    @if($product->description)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--card-border);">
                        <label style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Description</label>
                        <p style="margin: 0; color: var(--text-primary); line-height: 1.6;">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Variations -->
            @if($product->has_variants && $product->variations->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Variations ({{ $product->variations->count() }})</h3>
                </div>
                <div class="card-body">
                    <div class="variations-grid">
                        @foreach($product->variations as $variation)
                        <div class="variation-card {{ $variation->is_active ? '' : 'inactive' }}">
                            <div class="variation-sku">{{ $variation->sku }}</div>
                            <div class="variation-name">{{ $variation->display_name }}</div>
                            <div class="variation-attrs">
                                @foreach($variation->attributeValues as $attrValue)
                                <span class="variation-attr">
                                    @if($attrValue->color_code)
                                    <span class="color-dot" style="background: {{ $attrValue->color_code }};"></span>
                                    @endif
                                    {{ $attrValue->value }}
                                </span>
                                @endforeach
                            </div>
                            <div class="variation-meta">
                                <span class="variation-price">₹{{ number_format($variation->effective_sale_price, 2) }}</span>
                                <span class="variation-stock">{{ $variation->current_stock }} {{ $product->unit->short_name ?? 'PCS' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Stock by Warehouse -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock by Location</h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if(isset($stockByWarehouse) && count($stockByWarehouse) > 0)
                        <table class="simple-table">
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Quantity</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockByWarehouse as $stock)
                                <tr>
                                    <td>
                                        <div class="warehouse-info">
                                            <svg class="warehouse-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                            </svg>
                                            {{ $stock->warehouse->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td><strong>{{ number_format($stock->total_qty, 2) }}</strong> {{ $product->unit->short_name ?? 'PCS' }}</td>
                                    <td>₹{{ number_format($stock->total_qty * $product->purchase_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p>No stock data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Movements</h3>
                    <a href="{{ route('admin.inventory.reports.movement-history') }}?product_id={{ $product->id }}" style="font-size: 13px; color: var(--primary); text-decoration: none;">View All</a>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if(isset($recentMovements) && count($recentMovements) > 0)
                        <table class="simple-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Location</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @php
                                            $typeClass = match($movement->movement_type) {
                                                'IN' => 'badge-success',
                                                'RETURN' => 'badge-info',
                                                'OUT' => 'badge-danger',
                                                'TRANSFER' => 'badge-purple',
                                                default => 'badge-warning'
                                            };
                                        @endphp
                                        <span class="badge {{ $typeClass }}">{{ $movement->movement_type }}</span>
                                    </td>
                                    <td>
                                        <strong style="color: {{ in_array($movement->movement_type, ['IN', 'RETURN']) ? '#059669' : '#dc2626' }}">
                                            {{ in_array($movement->movement_type, ['IN', 'RETURN']) ? '+' : '-' }}{{ number_format($movement->qty, 2) }}
                                        </strong>
                                        @if($movement->variation)
                                        <br><small style="color: var(--text-muted);">{{ $movement->variation->display_name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $movement->warehouse->name ?? '-' }}
                                        @if($movement->rack)
                                            <span class="rack-badge">{{ $movement->rack->code }}</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($movement->reason, 30) ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            <p>No movements yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Total Stock Card -->
            <div class="card">
                <div class="card-body stock-display">
                    @php
                        $totalStock = isset($stockByWarehouse) ? $stockByWarehouse->sum('total_qty') : 0;
                        $unitName = $product->unit->short_name ?? 'PCS';
                        
                        if ($totalStock <= $product->min_stock_level) {
                            $stockStatus = 'low';
                            $stockMessage = 'Low Stock!';
                        } elseif ($product->max_stock_level > 0 && $totalStock >= $product->max_stock_level) {
                            $stockStatus = 'high';
                            $stockMessage = 'Overstocked';
                        } else {
                            $stockStatus = 'ok';
                            $stockMessage = 'In Stock';
                        }
                    @endphp
                    <div class="stock-value">
                        {{ number_format($totalStock, 0) }}
                        <span class="stock-unit">{{ $unitName }}</span>
                    </div>
                    <div class="stock-label">Total Stock</div>
                    <div class="stock-status {{ $stockStatus }}">{{ $stockMessage }}</div>
                    
                    <div style="margin-top: 20px;">
                        <a href="{{ route('admin.inventory.stock.receive') }}?product_id={{ $product->id }}" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Stock
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stock Value -->
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 24px;">
                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Stock Value</div>
                    <div style="font-size: 28px; font-weight: 700; color: #059669;">
                        ₹{{ number_format($totalStock * $product->purchase_price, 2) }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">@ ₹{{ number_format($product->purchase_price, 2) }} per {{ $unitName }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.inventory.stock.receive') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #10b981;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Receive Stock
                    </a>
                    <a href="{{ route('admin.inventory.stock.deliver') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #f59e0b;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        Deliver Stock
                    </a>
                    <a href="{{ route('admin.inventory.stock.transfer') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #8b5cf6;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Transfer Stock
                    </a>
                    <a href="{{ route('admin.inventory.stock.adjustments') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #06b6d4;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Adjust Stock
                    </a>
                    <a href="{{ route('admin.inventory.products.edit', $product->id) }}" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #3b82f6;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>