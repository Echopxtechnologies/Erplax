<x-layouts.app>
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-header h1 svg {
        width: 28px;
        height: 28px;
        color: #6366f1;
    }

    .filters-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        align-items: end;
    }
    
    .filter-group label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        box-sizing: border-box;
    }
    
    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    }
    
    .table-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #3730a3;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
    }
    
    .record-count {
        font-size: 13px;
        color: #6366f1;
        font-weight: 500;
        background: #fff;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }
    
    .data-table th,
    .data-table td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .data-table th {
        background: var(--body-bg);
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .data-table tbody tr {
        transition: background 0.15s;
    }
    
    .data-table tbody tr:hover {
        background: var(--body-bg);
    }
    
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .badge-cyan { background: #cffafe; color: #155e75; }

    .qty-positive {
        color: #059669;
        font-weight: 700;
        font-size: 14px;
    }
    
    .qty-negative {
        color: #dc2626;
        font-weight: 700;
        font-size: 14px;
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .product-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
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
    }
    
    .product-sku {
        font-size: 11px;
        color: var(--text-muted);
    }

    .location-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .warehouse-name {
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .rack-info {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
    }
    
    .rack-badge {
        font-size: 10px;
        padding: 2px 8px;
        background: #ede9fe;
        color: #5b21b6;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    
    .rack-name {
        font-size: 11px;
        color: var(--text-muted);
    }

    .transfer-location {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
    }
    
    .transfer-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .transfer-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        min-width: 35px;
    }
    
    .transfer-label.from { color: #dc2626; }
    .transfer-label.to { color: #059669; }
    
    .transfer-warehouse {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .transfer-rack-info {
        margin-left: 43px;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .transfer-divider {
        border-top: 1px dashed #e2e8f0;
        margin: 8px 0;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .date-primary {
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .date-secondary {
        font-size: 11px;
        color: var(--text-muted);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 11px;
    }
    
    .user-name {
        font-size: 12px;
        color: var(--text-muted);
    }

    .reason-cell {
        max-width: 200px;
    }
    
    .reason-text {
        font-size: 12px;
        color: var(--text-muted);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ref-badge {
        font-size: 10px;
        padding: 2px 6px;
        background: var(--body-bg);
        color: var(--text-muted);
        border-radius: 4px;
        font-family: monospace;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        opacity: 0.4;
    }
    
    .empty-state h3 {
        margin: 0 0 8px 0;
        font-size: 18px;
        color: var(--text-primary);
    }
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .pagination-info {
        font-size: 13px;
        color: var(--text-muted);
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
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .btn-secondary {
        background: var(--card-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--body-bg);
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    
    .quick-stat {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 16px;
        text-align: center;
    }
    
    .quick-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .quick-stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }
    
    .quick-stat.in .quick-stat-value { color: #059669; }
    .quick-stat.out .quick-stat-value { color: #dc2626; }
    .quick-stat.transfer .quick-stat-value { color: #8b5cf6; }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <a href="{{ route('admin.inventory.dashboard') }}" class="back-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Stock Movement History
            </h1>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.inventory.stock.receive') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Receive Stock
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    @php
        $totalIn = $movements->where('movement_type', 'IN')->count();
        $totalOut = $movements->where('movement_type', 'OUT')->count();
        $totalTransfer = $movements->where('movement_type', 'TRANSFER')->count();
        $totalReturn = $movements->where('movement_type', 'RETURN')->count();
        $totalAdjustment = $movements->where('movement_type', 'ADJUSTMENT')->count();
    @endphp
    <div class="quick-stats">
        <div class="quick-stat in">
            <div class="quick-stat-value">{{ $totalIn }}</div>
            <div class="quick-stat-label">Received</div>
        </div>
        <div class="quick-stat out">
            <div class="quick-stat-value">{{ $totalOut }}</div>
            <div class="quick-stat-label">Delivered</div>
        </div>
        <div class="quick-stat transfer">
            <div class="quick-stat-value">{{ $totalTransfer }}</div>
            <div class="quick-stat-label">Transfers</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-value">{{ $totalReturn }}</div>
            <div class="quick-stat-label">Returns</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-value">{{ $totalAdjustment }}</div>
            <div class="quick-stat-label">Adjustments</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.inventory.stock.movements') }}" id="filterForm">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Product</label>
                    <select name="product_id" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Products</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Warehouse</label>
                    <select name="warehouse_id" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses ?? [] as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Movement Type</label>
                    <select name="movement_type" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Types</option>
                        <option value="IN" {{ request('movement_type') == 'IN' ? 'selected' : '' }}>📥 Receive (IN)</option>
                        <option value="OUT" {{ request('movement_type') == 'OUT' ? 'selected' : '' }}>📤 Deliver (OUT)</option>
                        <option value="TRANSFER" {{ request('movement_type') == 'TRANSFER' ? 'selected' : '' }}>🔄 Transfer</option>
                        <option value="RETURN" {{ request('movement_type') == 'RETURN' ? 'selected' : '' }}>↩️ Return</option>
                        <option value="ADJUSTMENT" {{ request('movement_type') == 'ADJUSTMENT' ? 'selected' : '' }}>⚖️ Adjustment</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="document.getElementById('filterForm').submit()">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="document.getElementById('filterForm').submit()">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Movement Records
            </div>
            <span class="record-count">{{ $movements->total() ?? 0 }} records</span>
        </div>
        
        @if(isset($movements) && $movements->count() > 0)
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Reason</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>
                                <span class="ref-badge">{{ $movement->reference_no ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="date-primary">{{ $movement->created_at->format('d M Y') }}</span>
                                    <span class="date-secondary">{{ $movement->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeConfig = match($movement->movement_type) {
                                        'IN' => ['class' => 'badge-success', 'icon' => '📥', 'label' => 'Received'],
                                        'OUT' => ['class' => 'badge-danger', 'icon' => '📤', 'label' => 'Delivered'],
                                        'TRANSFER' => ['class' => 'badge-purple', 'icon' => '🔄', 'label' => 'Transfer'],
                                        'RETURN' => ['class' => 'badge-cyan', 'icon' => '↩️', 'label' => 'Return'],
                                        'ADJUSTMENT' => ['class' => 'badge-warning', 'icon' => '⚖️', 'label' => 'Adjustment'],
                                        default => ['class' => 'badge-info', 'icon' => '📦', 'label' => $movement->movement_type]
                                    };
                                @endphp
                                <span class="badge {{ $typeConfig['class'] }}">
                                    {{ $typeConfig['icon'] }} {{ $typeConfig['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="product-cell">
                                    <div class="product-icon">
                                        {{ strtoupper(substr($movement->product->name ?? 'P', 0, 2)) }}
                                    </div>
                                    <div class="product-info">
                                        <span class="product-name">{{ $movement->product->name ?? '-' }}</span>
                                        <span class="product-sku">{{ $movement->product->sku ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $isPositive = in_array($movement->movement_type, ['IN', 'RETURN']);
                                    $unitName = $movement->unit->short_name ?? $movement->product->unit->short_name ?? 'PCS';
                                @endphp
                                <span class="{{ $isPositive ? 'qty-positive' : 'qty-negative' }}">
                                    {{ $isPositive ? '+' : '-' }}{{ number_format($movement->qty, 2) }}
                                </span>
                                <span style="color: var(--text-muted); font-size: 11px; margin-left: 2px;">{{ $unitName }}</span>
                            </td>
                            <td>
                                @if($movement->movement_type == 'TRANSFER')
                                    @php
                                        $transferNo = str_replace('-IN', '', $movement->reference_no);
                                        $transfer = $transfers[$transferNo] ?? null;
                                    @endphp
                                    @if($transfer)
                                        <div class="transfer-location">
                                            <!-- FROM -->
                                            <div class="transfer-row">
                                                <span class="transfer-label from">FROM</span>
                                                <span class="transfer-warehouse">{{ $transfer->fromWarehouse->name ?? '-' }}</span>
                                            </div>
                                            @if($transfer->fromRack)
                                                <div class="transfer-rack-info">
                                                    <span class="rack-badge"> {{ $transfer->fromRack->code }}</span>
                                                    <span>|</span>
                                                    <span class="rack-name">{{ $transfer->fromRack->name }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="transfer-divider"></div>
                                            
                                            <!-- TO -->
                                            <div class="transfer-row">
                                                <span class="transfer-label to">TO</span>
                                                <span class="transfer-warehouse">{{ $transfer->toWarehouse->name ?? '-' }}</span>
                                            </div>
                                            @if($transfer->toRack)
                                                <div class="transfer-rack-info">
                                                    <span class="rack-badge"> {{ $transfer->toRack->code }}</span>
                                                    <span>|</span>
                                                    <span class="rack-name">{{ $transfer->toRack->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="location-cell">
                                            <span class="warehouse-name">{{ $movement->warehouse->name ?? '-' }}</span>
                                            @if($movement->rack)
                                                <div class="rack-info">
                                                    <span class="rack-badge"> {{ $movement->rack->code }}</span>
                                                    <span class="rack-name">{{ $movement->rack->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <div class="location-cell">
                                        <span class="warehouse-name">{{ $movement->warehouse->name ?? '-' }}</span>
                                        @if($movement->rack)
                                            <div class="rack-info">
                                                <span class="rack-badge"> {{ $movement->rack->code }}</span>
                                                <span class="rack-name">{{ $movement->rack->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="reason-cell">
                                <span class="reason-text" title="{{ $movement->reason }}">
                                    {{ Str::limit($movement->reason, 30) ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="user-cell">
                                    @if($movement->creator)
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($movement->creator->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="user-name">{{ $movement->creator->name }}</span>
                                    @else
                                        <div class="user-avatar" style="background: #9ca3af;">S</div>
                                        <span class="user-name">System</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($movements->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $movements->firstItem() }} to {{ $movements->lastItem() }} of {{ $movements->total() }} entries
                    </div>
                    {{ $movements->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                <h3>No Movements Found</h3>
                <p>There are no stock movements matching your filters.</p>
                <div style="margin-top: 20px;">
                    <a href="{{ route('admin.inventory.stock.receive') }}" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Receive First Stock
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
