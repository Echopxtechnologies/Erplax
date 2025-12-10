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
    
    .page-header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-header-left h1 svg {
        width: 32px;
        height: 32px;
        color: #6366f1;
    }
    
    .page-header-left p {
        margin: 4px 0 0 44px;
        color: var(--text-muted);
        font-size: 14px;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    
    .btn svg {
        width: 18px;
        height: 18px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: #fff;
    }
    
    .btn-secondary {
        background: var(--card-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--body-bg);
    }

    /* Main Stats Grid */
    .main-stats {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 1200px) {
        .main-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .main-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f97316, #fb923c); }
    .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #f87171); }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
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
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-icon.orange { background: #ffedd5; color: #ea580c; }
    .stat-icon.red { background: #fee2e2; color: #dc2626; }
    
    .stat-trend {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .stat-trend.up { background: #d1fae5; color: #059669; }
    .stat-trend.down { background: #fee2e2; color: #dc2626; }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 4px;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Summary Cards Row */
    .summary-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 992px) {
        .summary-row {
            grid-template-columns: 1fr;
        }
    }
    
    .summary-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
    }
    
    .summary-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .summary-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .summary-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .stock-value-display {
        text-align: center;
        padding: 20px 0;
    }
    
    .stock-value-amount {
        font-size: 36px;
        font-weight: 700;
        color: #059669;
        margin-bottom: 8px;
    }
    
    .stock-value-label {
        font-size: 14px;
        color: var(--text-muted);
    }
    
    .stock-value-breakdown {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--card-border);
    }
    
    .breakdown-item {
        text-align: center;
        padding: 12px;
        background: var(--body-bg);
        border-radius: 10px;
    }
    
    .breakdown-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .breakdown-label {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Today's Activity */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 768px) {
        .activity-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .activity-item {
        text-align: center;
        padding: 16px 12px;
        background: var(--body-bg);
        border-radius: 12px;
        transition: all 0.2s;
    }
    
    .activity-item:hover {
        transform: scale(1.02);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 18px;
    }
    
    .activity-icon.in { background: #d1fae5; }
    .activity-icon.out { background: #fee2e2; }
    .activity-icon.transfer { background: #ede9fe; }
    .activity-icon.adjust { background: #fef3c7; }
    
    .activity-count {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .activity-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* Warehouse Overview */
    .warehouse-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .warehouse-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: var(--body-bg);
        border-radius: 12px;
        transition: all 0.2s;
    }
    
    .warehouse-item:hover {
        background: #eef2ff;
    }
    
    .warehouse-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 16px;
    }
    
    .warehouse-info {
        flex: 1;
    }
    
    .warehouse-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
    }
    
    .warehouse-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .warehouse-stats {
        display: flex;
        gap: 16px;
        text-align: right;
    }
    
    .warehouse-stat-item {
        display: flex;
        flex-direction: column;
    }
    
    .warehouse-stat-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .warehouse-stat-label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    /* Quick Actions */
    .quick-actions {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
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
        font-size: 12px;
        color: #fff;
        transition: all 0.2s;
        text-align: center;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-3px);
        color: #fff;
    }
    
    .quick-action-btn svg {
        width: 26px;
        height: 26px;
    }
    
    .quick-action-btn.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .quick-action-btn.blue:hover { box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4); }
    
    .quick-action-btn.green { background: linear-gradient(135deg, #10b981, #059669); }
    .quick-action-btn.green:hover { box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); }
    
    .quick-action-btn.orange { background: linear-gradient(135deg, #f97316, #ea580c); }
    .quick-action-btn.orange:hover { box-shadow: 0 8px 24px rgba(249, 115, 22, 0.4); }
    
    .quick-action-btn.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .quick-action-btn.purple:hover { box-shadow: 0 8px 24px rgba(139, 92, 246, 0.4); }
    
    .quick-action-btn.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .quick-action-btn.cyan:hover { box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4); }
    
    .quick-action-btn.indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .quick-action-btn.indigo:hover { box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4); }
    
    .quick-action-btn.pink { background: linear-gradient(135deg, #ec4899, #db2777); }
    .quick-action-btn.pink:hover { box-shadow: 0 8px 24px rgba(236, 72, 153, 0.4); }
    
    .quick-action-btn.teal { background: linear-gradient(135deg, #14b8a6, #0d9488); }
    .quick-action-btn.teal:hover { box-shadow: 0 8px 24px rgba(20, 184, 166, 0.4); }

    /* Content Grid */
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
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }
    
    .table-card-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
        color: #6366f1;
    }
    
    .table-card-body {
        padding: 0;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .simple-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .simple-table th,
    .simple-table td {
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .simple-table th {
        background: var(--body-bg);
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    
    .simple-table tbody tr {
        transition: background 0.15s;
    }
    
    .simple-table tbody tr:hover {
        background: #f8fafc;
    }
    
    .simple-table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .badge-cyan { background: #cffafe; color: #155e75; }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .product-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .product-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }
    
    .product-sku {
        font-size: 11px;
        color: var(--text-muted);
    }

    .stock-bar {
        width: 100%;
        height: 6px;
        background: #fee2e2;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 6px;
    }
    
    .stock-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #ef4444, #f87171);
        border-radius: 3px;
        transition: width 0.3s;
    }

    .qty-badge {
        font-weight: 700;
        font-size: 13px;
    }
    
    .qty-badge.positive { color: #059669; }
    .qty-badge.negative { color: #dc2626; }

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
    
    .empty-state h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        color: var(--text-primary);
    }
    
    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    .view-all-link {
        font-size: 12px;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .view-all-link:hover {
        text-decoration: underline;
    }
    
    .view-all-link svg {
        width: 14px;
        height: 14px;
    }

    /* Location display for transfers */
    .transfer-location {
        font-size: 11px;
        line-height: 1.5;
    }
    
    .transfer-from {
        color: #dc2626;
    }
    
    .transfer-to {
        color: #059669;
    }
    
    .transfer-arrow {
        color: var(--text-muted);
        margin: 0 4px;
    }
</style>

@php
    // Calculate additional stats
    $totalStockValue = \App\Models\Inventory\StockLevel::join('products', 'stock_levels.product_id', '=', 'products.id')
        ->selectRaw('SUM(stock_levels.qty * products.purchase_price) as total_value')
        ->value('total_value') ?? 0;
    
    $totalStockQty = \App\Models\Inventory\StockLevel::sum('qty') ?? 0;
    
    $lowStockCount = $lowStockProducts->count();
    
    // Today's movements
    $todayMovements = \App\Models\Inventory\StockMovement::whereDate('created_at', today())->get();
    $todayIn = $todayMovements->where('movement_type', 'IN')->count();
    $todayOut = $todayMovements->where('movement_type', 'OUT')->count();
    $todayTransfer = $todayMovements->where('movement_type', 'TRANSFER')->count();
    $todayAdjust = $todayMovements->where('movement_type', 'ADJUSTMENT')->count();
    
    // Warehouses with stock count
    $warehousesWithStock = \App\Models\Inventory\Warehouse::where('is_active', true)
        ->withCount('racks')
        ->withSum('stockLevels', 'qty')
        ->orderBy('name')
        ->get();
    
    // Get transfer details for recent movements
    $transferRefNos = $recentMovements->where('movement_type', 'TRANSFER')
        ->pluck('reference_no')
        ->map(fn($ref) => str_replace('-IN', '', $ref))
        ->unique()
        ->toArray();
    
    $transfers = collect();
    if (!empty($transferRefNos)) {
        $transfers = \App\Models\Inventory\StockTransfer::with(['fromWarehouse', 'toWarehouse'])
            ->whereIn('transfer_no', $transferRefNos)
            ->get()
            ->keyBy('transfer_no');
    }
    
    // Greeting based on time
    $hour = now()->hour;
    $greeting = match(true) {
        $hour < 12 => 'Good Morning',
        $hour < 17 => 'Good Afternoon',
        default => 'Good Evening'
    };
@endphp

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                {{ $greeting }}!
            </h1>
            <p>Here's what's happening with your inventory today.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                View History
            </a>
            <a href="{{ route('admin.inventory.products.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>
    </div>

    <!-- Main Stats -->
    <div class="main-stats">
        <div class="stat-card blue">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalProducts']) }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalWarehouses']) }}</div>
            <div class="stat-label">Warehouses</div>
        </div>
        
        <div class="stat-card purple">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalRacks']) }}</div>
            <div class="stat-label">Storage Racks</div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalCategories']) }}</div>
            <div class="stat-label">Categories</div>
        </div>
        
        <div class="stat-card red">
            <div class="stat-header">
                <div class="stat-icon red">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                @if($lowStockCount > 0)
                    <span class="stat-trend down">⚠️ Alert</span>
                @endif
            </div>
            <div class="stat-value">{{ number_format($lowStockCount) }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="summary-row">
        <!-- Stock Value Card -->
        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Stock Value
                </div>
            </div>
            <div class="stock-value-display">
                <div class="stock-value-amount">₹{{ number_format($totalStockValue, 2) }}</div>
                <div class="stock-value-label">Total Inventory Value</div>
            </div>
            <div class="stock-value-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ number_format($totalStockQty, 0) }}</div>
                    <div class="breakdown-label">Total Units</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['totalBrands'] }}</div>
                    <div class="breakdown-label">Brands</div>
                </div>
            </div>
        </div>

        <!-- Today's Activity Card -->
        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Today's Activity
                </div>
                <span style="font-size: 11px; color: var(--text-muted);">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="activity-grid">
                <div class="activity-item">
                    <div class="activity-icon in">📥</div>
                    <div class="activity-count">{{ $todayIn }}</div>
                    <div class="activity-label">Received</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon out">📤</div>
                    <div class="activity-count">{{ $todayOut }}</div>
                    <div class="activity-label">Delivered</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon transfer">🔄</div>
                    <div class="activity-count">{{ $todayTransfer }}</div>
                    <div class="activity-label">Transfers</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon adjust">⚖️</div>
                    <div class="activity-count">{{ $todayAdjust }}</div>
                    <div class="activity-label">Adjustments</div>
                </div>
            </div>
        </div>

        <!-- Warehouse Overview Card -->
        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    Warehouses
                </div>
                <a href="{{ route('admin.inventory.warehouses.index') }}" class="view-all-link">
                    View All
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="warehouse-list">
                @forelse($warehousesWithStock->take(3) as $warehouse)
                    <div class="warehouse-item">
                        <div class="warehouse-icon">{{ strtoupper(substr($warehouse->name, 0, 2)) }}</div>
                        <div class="warehouse-info">
                            <div class="warehouse-name">{{ $warehouse->name }}</div>
                            <div class="warehouse-meta">{{ $warehouse->racks_count }} racks • {{ $warehouse->type }}</div>
                        </div>
                        <div class="warehouse-stats">
                            <div class="warehouse-stat-item">
                                <div class="warehouse-stat-value">{{ number_format($warehouse->stock_levels_sum_qty ?? 0, 0) }}</div>
                                <div class="warehouse-stat-label">Units</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="padding: 24px;">
                        <p>No warehouses yet</p>
                    </div>
                @endforelse
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
            <a href="{{ route('admin.inventory.stock.returns') }}" class="quick-action-btn cyan">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Process Return
            </a>
            <a href="{{ route('admin.inventory.stock.adjustments') }}" class="quick-action-btn teal">
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
            <a href="{{ route('admin.inventory.settings.index') }}" class="quick-action-btn pink">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
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
                <a href="{{ route('admin.inventory.products.index') }}" class="view-all-link">
                    View All
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="table-card-body">
                @if($lowStockProducts->count() > 0)
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current</th>
                                <th>Min Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            @php
                                $percentage = $product->min_stock_level > 0 
                                    ? min(100, ($product->current_stock / $product->min_stock_level) * 100) 
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-avatar">{{ strtoupper(substr($product->name, 0, 2)) }}</div>
                                        <div class="product-info">
                                            <span class="product-name">{{ $product->name }}</span>
                                            <span class="product-sku">{{ $product->sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-danger">{{ number_format($product->current_stock, 0) }}</span>
                                </td>
                                <td>{{ number_format($product->min_stock_level, 0) }}</td>
                                <td>
                                    <div class="stock-bar">
                                        <div class="stock-bar-fill" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h4>All Stocked Up!</h4>
                        <p>All products are above minimum stock levels.</p>
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
                <a href="{{ route('admin.inventory.stock.movements') }}" class="view-all-link">
                    View All
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="table-card-body">
                @if($recentMovements->count() > 0)
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                            @php
                                $badgeConfig = match($movement->movement_type) {
                                    'IN' => ['class' => 'badge-success', 'icon' => '📥', 'label' => 'IN'],
                                    'OUT' => ['class' => 'badge-danger', 'icon' => '📤', 'label' => 'OUT'],
                                    'TRANSFER' => ['class' => 'badge-purple', 'icon' => '🔄', 'label' => 'TRF'],
                                    'RETURN' => ['class' => 'badge-cyan', 'icon' => '↩️', 'label' => 'RTN'],
                                    'ADJUSTMENT' => ['class' => 'badge-warning', 'icon' => '⚖️', 'label' => 'ADJ'],
                                    default => ['class' => 'badge-info', 'icon' => '📦', 'label' => $movement->movement_type]
                                };
                                $isPositive = in_array($movement->movement_type, ['IN', 'RETURN']);
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <span class="product-name">{{ Str::limit($movement->product->name ?? '-', 20) }}</span>
                                        <span class="product-sku">{{ $movement->created_at->format('d M, H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $badgeConfig['class'] }}">
                                        {{ $badgeConfig['icon'] }} {{ $badgeConfig['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="qty-badge {{ $isPositive ? 'positive' : 'negative' }}">
                                        {{ $isPositive ? '+' : '-' }}{{ number_format($movement->qty, 0) }}
                                    </span>
                                </td>
                                <td>
                                    @if($movement->movement_type == 'TRANSFER')
                                        @php
                                            $transferNo = str_replace('-IN', '', $movement->reference_no);
                                            $transfer = $transfers[$transferNo] ?? null;
                                        @endphp
                                        @if($transfer)
                                            <div class="transfer-location">
                                                <span class="transfer-from">{{ Str::limit($transfer->fromWarehouse->name ?? '-', 10) }}</span>
                                                <span class="transfer-arrow">→</span>
                                                <span class="transfer-to">{{ Str::limit($transfer->toWarehouse->name ?? '-', 10) }}</span>
                                            </div>
                                        @else
                                            {{ $movement->warehouse->name ?? '-' }}
                                        @endif
                                    @else
                                        {{ Str::limit($movement->warehouse->name ?? '-', 15) }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h4>No Movements Yet</h4>
                        <p>Start by receiving some stock.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>