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
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
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
                            <span>{{ $product->unit }}</span>
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
                        <div class="info-item">
                            <label>Min Stock</label>
                            <span>{{ $product->min_stock_level }}</span>
                        </div>
                        <div class="info-item">
                            <label>Max Stock</label>
                            <span>{{ $product->max_stock_level }}</span>
                        </div>
                        <div class="info-item">
                            <label>Batch Managed</label>
                            <span>{{ $product->is_batch_managed ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock by Warehouse -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock by Warehouse</h3>
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
                                    <td>{{ $stock->warehouse->name ?? '-' }}</td>
                                    <td><strong>{{ number_format($stock->total_qty, 2) }}</strong> {{ $product->unit }}</td>
                                    <td>₹{{ number_format($stock->total_qty * $product->purchase_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <p>No stock data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Movements</h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if(isset($movements) && count($movements) > 0)
                        <table class="simple-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Warehouse</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @php
                                            $typeClass = match($movement->movement_type) {
                                                'IN' => 'badge-success',
                                                'RETURN' => 'badge-info',
                                                'OUT' => 'badge-danger',
                                                default => 'badge-warning'
                                            };
                                        @endphp
                                        <span class="badge {{ $typeClass }}">{{ $movement->movement_type }}</span>
                                    </td>
                                    <td><strong>{{ $movement->qty }}</strong></td>
                                    <td>{{ $movement->warehouse->name ?? '-' }}</td>
                                    <td>{{ $movement->reason ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
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
                    @endphp
                    <div class="stock-value">
                        {{ number_format($totalStock, 0) }}
                        <span class="stock-unit">{{ $product->unit }}</span>
                    </div>
                    <div class="stock-label">Total Stock</div>
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

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.inventory.stock.receive') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        📦 Receive Stock
                    </a>
                    <a href="{{ route('admin.inventory.stock.deliver') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        🚚 Deliver Stock
                    </a>
                    <a href="{{ route('admin.inventory.stock.adjustments') }}?product_id={{ $product->id }}" class="btn btn-secondary">
                        ⚖️ Adjust Stock
                    </a>
                    <a href="{{ route('admin.inventory.products.edit', $product->id) }}" class="btn btn-secondary">
                        ✏️ Edit Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>