<x-layouts.app>
<style>
    .page-container {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
    }

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
        flex-shrink: 0;
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

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .quick-stat {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    
    .quick-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .quick-stat-label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }
    
    .quick-stat.in .quick-stat-value { color: #059669; }
    .quick-stat.out .quick-stat-value { color: #dc2626; }
    .quick-stat.transfer .quick-stat-value { color: #8b5cf6; }

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
    
    .table-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .table-filters select,
    .table-filters input {
        padding: 8px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 140px;
    }
    
    .table-card-body {
        padding: 0;
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

    .qty-positive { color: #059669; font-weight: 700; }
    .qty-negative { color: #dc2626; font-weight: 700; }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
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

    /* Normal Location */
    .location-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .warehouse-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }
    
    .rack-badge {
        font-size: 10px;
        padding: 3px 8px;
        background: #ede9fe;
        color: #5b21b6;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    
    .rack-badge svg {
        width: 10px;
        height: 10px;
    }

    /* Transfer Location Box */
    .transfer-box {
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
        border: 1px solid #e9d5ff;
        border-radius: 8px;
        padding: 10px 12px;
        min-width: 220px;
    }
    
    .transfer-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 6px 0;
    }
    
    .transfer-row:first-child {
        padding-top: 0;
    }
    
    .transfer-row:last-child {
        padding-bottom: 0;
    }
    
    .transfer-arrow {
        display: flex;
        justify-content: center;
        padding: 4px 0;
    }
    
    .transfer-arrow svg {
        width: 16px;
        height: 16px;
        color: #8b5cf6;
    }
    
    .transfer-label {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 3px 6px;
        border-radius: 4px;
        min-width: 38px;
        text-align: center;
    }
    
    .transfer-label.from {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .transfer-label.to {
        background: #d1fae5;
        color: #059669;
    }
    
    .transfer-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    
    .transfer-warehouse {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .transfer-warehouse-code {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .transfer-rack {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        padding: 2px 6px;
        background: #fff;
        border: 1px solid #e9d5ff;
        border-radius: 4px;
        color: #7c3aed;
        font-weight: 600;
    }
    
    .transfer-rack svg {
        width: 10px;
        height: 10px;
    }
    
    .transfer-no-rack {
        font-size: 10px;
        color: var(--text-muted);
        font-style: italic;
    }
    
    .same-warehouse-note {
        font-size: 9px;
        color: #8b5cf6;
        background: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        margin-top: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .same-warehouse-note svg {
        width: 12px;
        height: 12px;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .date-primary {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 13px;
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
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 10px;
    }
    
    .user-name {
        font-size: 12px;
        color: var(--text-muted);
    }

    .reason-text {
        font-size: 12px;
        color: var(--text-muted);
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        color: #fff;
    }
    
    .btn-add svg {
        width: 18px;
        height: 18px;
    }
</style>

<div class="page-container">
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
        <a href="{{ route('admin.inventory.stock.receive') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Receive Stock
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="quick-stat in">
            <div class="quick-stat-value">{{ $stats['in'] ?? 0 }}</div>
            <div class="quick-stat-label">Received</div>
        </div>
        <div class="quick-stat out">
            <div class="quick-stat-value">{{ $stats['out'] ?? 0 }}</div>
            <div class="quick-stat-label">Delivered</div>
        </div>
        <div class="quick-stat transfer">
            <div class="quick-stat-value">{{ $stats['transfer'] ?? 0 }}</div>
            <div class="quick-stat-label">Transfers</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-value">{{ $stats['return'] ?? 0 }}</div>
            <div class="quick-stat-label">Returns</div>
        </div>
        <div class="quick-stat">
            <div class="quick-stat-value">{{ $stats['adjustment'] ?? 0 }}</div>
            <div class="quick-stat-label">Adjustments</div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Movement Records
            </div>
            <div class="table-filters">
                <!-- FIXED: Using data-dt-filter instead of id and onchange -->
                <select data-dt-filter="product_id">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <select data-dt-filter="warehouse_id">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                <select data-dt-filter="movement_type">
                    <option value="">All Types</option>
                    <option value="IN">📥 Receive</option>
                    <option value="OUT">📤 Deliver</option>
                    <option value="TRANSFER">🔄 Transfer</option>
                    <option value="RETURN">↩️ Return</option>
                    <option value="ADJUSTMENT">⚖️ Adjustment</option>
                </select>
                <input type="date" data-dt-filter="from_date" title="From Date">
                <input type="date" data-dt-filter="to_date" title="To Date">
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage" 
                   id="movementsTable"
                   data-route="{{ route('admin.inventory.stock.movements.data') }}">
                <thead>
                    <tr>
                        <th data-col="reference_no">Reference</th>
                        <th class="dt-sort" data-col="created_at" data-render="date">Date</th>
                        <th data-col="movement_type" data-render="movementType">Type</th>
                        <th data-col="product_name" data-render="product">Product</th>
                        <th data-col="qty" data-render="quantity">Quantity</th>
                        <th data-col="location_display" data-render="location">Location</th>
                        <th data-col="reason" data-render="reason">Reason</th>
                        <th data-col="created_by" data-render="user">By</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Custom renderers for datatable
window.dtRenders = window.dtRenders || {};

window.dtRenders.date = function(data, row) {
    return `
        <div class="date-cell">
            <span class="date-primary">${row.created_at}</span>
            <span class="date-secondary">${row.created_time || ''}</span>
        </div>
    `;
};

window.dtRenders.movementType = function(data, row) {
    const types = {
        'IN': { class: 'badge-success', icon: '📥', label: 'Received' },
        'OUT': { class: 'badge-danger', icon: '📤', label: 'Delivered' },
        'TRANSFER': { class: 'badge-purple', icon: '🔄', label: 'Transfer' },
        'RETURN': { class: 'badge-cyan', icon: '↩️', label: 'Return' },
        'ADJUSTMENT': { class: 'badge-warning', icon: '⚖️', label: 'Adjustment' }
    };
    const config = types[row.movement_type] || { class: 'badge-info', icon: '📦', label: row.movement_type };
    return `<span class="badge ${config.class}">${config.icon} ${config.label}</span>`;
};

window.dtRenders.product = function(data, row) {
    return `
        <div class="product-cell">
            <div class="product-icon">${row.product_initials || 'P'}</div>
            <div class="product-info">
                <span class="product-name">${row.product_name || '-'}</span>
                <span class="product-sku">${row.product_sku || ''}</span>
            </div>
        </div>
    `;
};

window.dtRenders.quantity = function(data, row) {
    const qtyClass = row.is_positive ? 'qty-positive' : 'qty-negative';
    return `<span class="${qtyClass}">${row.qty_display || row.qty}</span> <span style="color: var(--text-muted); font-size: 11px;">${row.unit || ''}</span>`;
};

window.dtRenders.location = function(data, row) {
    // Transfer movement
    if (row.is_transfer && row.from_warehouse) {
        let fromRackHtml = row.from_rack_code 
            ? `<span class="transfer-rack">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                ${row.from_rack_code}${row.from_rack_name ? ' - ' + row.from_rack_name : ''}
               </span>`
            : `<span class="transfer-no-rack">No rack</span>`;
        
        let toRackHtml = row.to_rack_code 
            ? `<span class="transfer-rack">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                ${row.to_rack_code}${row.to_rack_name ? ' - ' + row.to_rack_name : ''}
               </span>`
            : `<span class="transfer-no-rack">No rack</span>`;
        
        let sameWarehouseNote = row.is_same_warehouse 
            ? `<div class="same-warehouse-note">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Rack transfer within same warehouse
               </div>`
            : '';
        
        return `
            <div class="transfer-box">
                <div class="transfer-row">
                    <span class="transfer-label from">FROM</span>
                    <div class="transfer-details">
                        <div class="transfer-warehouse">
                            ${row.from_warehouse}
                            ${row.from_warehouse_code ? '<span class="transfer-warehouse-code">(' + row.from_warehouse_code + ')</span>' : ''}
                        </div>
                        ${fromRackHtml}
                    </div>
                </div>
                <div class="transfer-arrow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <div class="transfer-row">
                    <span class="transfer-label to">TO</span>
                    <div class="transfer-details">
                        <div class="transfer-warehouse">
                            ${row.to_warehouse}
                            ${row.to_warehouse_code ? '<span class="transfer-warehouse-code">(' + row.to_warehouse_code + ')</span>' : ''}
                        </div>
                        ${toRackHtml}
                    </div>
                </div>
                ${sameWarehouseNote}
            </div>
        `;
    }
    
    // Normal movement
    let rackHtml = row.rack_code 
        ? `<span class="rack-badge">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            ${row.rack_code}${row.rack_name ? ' - ' + row.rack_name : ''}
           </span>` 
        : '';
    
    return `
        <div class="location-cell">
            <span class="warehouse-name">${row.warehouse_name || '-'}</span>
            ${rackHtml}
        </div>
    `;
};

window.dtRenders.reason = function(data, row) {
    const reason = row.reason || '-';
    const truncated = reason.length > 35 ? reason.substring(0, 35) + '...' : reason;
    return `<span class="reason-text" title="${reason}">${truncated}</span>`;
};

window.dtRenders.user = function(data, row) {
    return `
        <div class="user-cell">
            <div class="user-avatar">${row.created_by_initial || 'U'}</div>
            <span class="user-name">${row.created_by || '-'}</span>
        </div>
    `;
};
</script>

@include('core::datatable')
</x-layouts.app>