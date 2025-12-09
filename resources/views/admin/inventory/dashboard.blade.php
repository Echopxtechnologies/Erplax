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
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-header h1 svg {
        width: 32px;
        height: 32px;
        color: var(--primary);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .stat-icon svg {
        width: 28px;
        height: 28px;
    }
    
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-icon.orange { background: #ffedd5; color: #ea580c; }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .stat-label {
        font-size: 14px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .quick-actions {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title svg {
        width: 22px;
        height: 22px;
        color: var(--text-muted);
    }
    
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
    }
    
    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        color: #fff;
        transition: all 0.2s;
        text-align: center;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        color: #fff;
    }
    
    .quick-action-btn svg {
        width: 28px;
        height: 28px;
    }
    
    .quick-action-btn.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .quick-action-btn.blue:hover { box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); }
    
    .quick-action-btn.green { background: linear-gradient(135deg, #10b981, #059669); }
    .quick-action-btn.green:hover { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4); }
    
    .quick-action-btn.orange { background: linear-gradient(135deg, #f97316, #ea580c); }
    .quick-action-btn.orange:hover { box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4); }
    
    .quick-action-btn.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .quick-action-btn.purple:hover { box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4); }
    
    .quick-action-btn.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .quick-action-btn.cyan:hover { box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4); }
    
    .quick-action-btn.indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .quick-action-btn.indigo:hover { box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4); }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    @media (max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .table-card-header {
        padding: 20px 24px;
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
        gap: 10px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .table-card-body {
        padding: 0;
    }
    
    .simple-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .simple-table th,
    .simple-table td {
        padding: 14px 24px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .simple-table th {
        background: var(--body-bg);
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    .simple-table tbody tr:hover {
        background: var(--body-bg);
    }
    
    .simple-table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #dbeafe; color: #1e40af; }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 56px;
        height: 56px;
        margin-bottom: 16px;
        opacity: 0.4;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    .view-all-link {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    .view-all-link:hover {
        text-decoration: underline;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Inventory Dashboard
        </h1>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalProducts'] }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalCategories'] }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalBrands'] }}</div>
                <div class="stat-label">Brands</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalWarehouses'] }}</div>
                <div class="stat-label">Warehouses</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Quick Actions
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('admin.inventory.products.create') }}" class="quick-action-btn blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
            <a href="{{ route('admin.inventory.stock.receive') }}" class="quick-action-btn green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                Receive Stock
            </a>
            <a href="{{ route('admin.inventory.stock.deliver') }}" class="quick-action-btn orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                Deliver Stock
            </a>
            <a href="{{ route('admin.inventory.stock.transfer') }}" class="quick-action-btn purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transfer Stock
            </a>
            <a href="{{ route('admin.inventory.stock.adjustments') }}" class="quick-action-btn cyan">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Adjust Stock
            </a>
            <a href="{{ route('admin.inventory.reports.stock-summary') }}" class="quick-action-btn indigo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                View Reports
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Low Stock Alerts -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Low Stock Alerts
                </div>
                <a href="{{ route('admin.inventory.products.index') }}" class="view-all-link">View All</a>
            </div>
            <div class="table-card-body">
                @if($lowStockProducts->count() > 0)
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current</th>
                                <th>Min Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $product->sku }}</div>
                                </td>
                                <td><span class="badge badge-danger">{{ $product->current_stock }}</span></td>
                                <td>{{ $product->min_stock_level }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>All products are well stocked!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Movements -->
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Recent Movements
                </div>
                <a href="{{ route('admin.inventory.reports.movement-history') }}" class="view-all-link">View All</a>
            </div>
            <div class="table-card-body">
                @if($recentMovements->count() > 0)
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Warehouse</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                            @php
                                $badgeClass = match($movement->movement_type) {
                                    'IN' => 'badge-success',
                                    'OUT' => 'badge-danger',
                                    'RETURN' => 'badge-info',
                                    'ADJUSTMENT' => 'badge-warning',
                                    default => 'badge-info'
                                };
                            @endphp
                            <tr>
                                <td>{{ $movement->product->name ?? '-' }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ $movement->movement_type }}</span></td>
                                <td>{{ number_format($movement->qty, 2) }}</td>
                                <td>{{ $movement->warehouse->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No recent stock movements</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>