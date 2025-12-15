<x-layouts.app>
<style>
    .inventory-container {
        padding: 20px;
        max-width: 1600px;
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
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .inv-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .inv-header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 12px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        color: #fff;
    }
    
    .btn-stock:hover { transform: translateY(-1px); color: #fff; }
    .btn-stock svg { width: 14px; height: 14px; }
    
    .btn-receive { background: linear-gradient(135deg, #059669, #047857); }
    .btn-receive:hover { box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); }
    .btn-deliver { background: linear-gradient(135deg, #ea580c, #c2410c); }
    .btn-deliver:hover { box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3); }
    .btn-transfer { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    .btn-transfer:hover { box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3); }
    .btn-return { background: linear-gradient(135deg, #0891b2, #0e7490); }
    .btn-return:hover { box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3); }
    .btn-adjust { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .btn-adjust:hover { box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }
    
    /* Stats Bar */
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
        min-width: 120px;
    }
    
    .inv-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    
    .inv-stat-icon.in { background: #ecfdf5; }
    .inv-stat-icon.out { background: #fef2f2; }
    .inv-stat-icon.transfer { background: #f3e8ff; }
    .inv-stat-icon.return { background: #ecfeff; }
    .inv-stat-icon.adjustment { background: #fffbeb; }
    
    .inv-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .inv-stat.in .inv-stat-value { color: #059669; }
    .inv-stat.out .inv-stat-value { color: #dc2626; }
    .inv-stat.transfer .inv-stat-value { color: #7c3aed; }
    .inv-stat.return .inv-stat-value { color: #0891b2; }
    .inv-stat.adjustment .inv-stat-value { color: #f59e0b; }
    
    .inv-stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 2px;
    }
    
    /* Table Container */
    .inv-table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    
    .inv-table-wrap table {
        min-width: 1100px;
    }
    
    /* Filters */
    .inv-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .inv-filter-select,
    .inv-filter-input {
        padding: 7px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 130px;
        cursor: pointer;
    }
    
    .inv-filter-select:focus,
    .inv-filter-input:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .product-thumb {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 11px;
        flex-shrink: 0;
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
    
    /* Badges */
    .mv-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .mv-badge.in { background: #ecfdf5; color: #059669; }
    .mv-badge.out { background: #fef2f2; color: #dc2626; }
    .mv-badge.transfer { background: #f3e8ff; color: #7c3aed; }
    .mv-badge.return { background: #ecfeff; color: #0891b2; }
    .mv-badge.adjustment { background: #fffbeb; color: #d97706; }
    
    /* Quantity */
    .qty-display { font-weight: 600; font-size: 13px; }
    .qty-display.positive { color: #059669; }
    .qty-display.negative { color: #dc2626; }
    .qty-display .unit { font-weight: 400; color: var(--text-muted); font-size: 11px; }
    
    /* Location */
    .location-display { font-size: 13px; color: var(--text-primary); }
    .location-display small { display: block; font-size: 11px; color: var(--text-muted); margin-top: 1px; }
    
    /* Date */
    .date-display { font-size: 13px; color: var(--text-primary); }
    .date-display small { display: block; font-size: 11px; color: var(--text-muted); }
    
    /* View Button */
    .btn-view {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        background: #eef2ff;
        color: #4f46e5;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-view:hover { background: #4f46e5; color: #fff; }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show { display: flex; }
    
    .modal-box {
        background: var(--card-bg);
        border-radius: 12px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        background: var(--body-bg);
        color: var(--text-muted);
        cursor: pointer;
        font-size: 16px;
    }
    .modal-close:hover { background: #fee2e2; color: #dc2626; }
    
    .modal-body { padding: 20px; }
    
    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    @media (max-width: 500px) { .detail-grid { grid-template-columns: 1fr; } }
    
    .detail-item {
        background: var(--body-bg);
        border-radius: 8px;
        padding: 12px;
    }
    .detail-item.full { grid-column: 1 / -1; }
    
    .detail-label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    /* Transfer Box */
    .transfer-box {
        background: var(--body-bg);
        border-radius: 8px;
        padding: 12px;
        margin-top: 8px;
    }
    
    .transfer-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }
    .transfer-row:first-child { border-bottom: 1px dashed var(--card-border); }
    
    .transfer-tag {
        font-size: 9px;
        font-weight: 700;
        padding: 3px 6px;
        border-radius: 3px;
        text-transform: uppercase;
    }
    .transfer-tag.from { background: #fee2e2; color: #991b1b; }
    .transfer-tag.to { background: #d1fae5; color: #065f46; }
    
    .transfer-info { flex: 1; }
    .transfer-warehouse { font-weight: 600; font-size: 13px; color: var(--text-primary); }
    .transfer-rack { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
</style>

<div class="inventory-container">
    <!-- Header -->
    <div class="inv-header">
        <div class="inv-header-left">
            <div class="inv-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
            </div>
            <div>
                <h1>Stock Movements</h1>
                <div class="inv-header-sub">Track all inventory transactions</div>
            </div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('inventory.stock.receive') }}" class="btn-stock btn-receive">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                Receive
            </a>
            <a href="{{ route('inventory.stock.deliver') }}" class="btn-stock btn-deliver">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                Deliver
            </a>
            <a href="{{ route('inventory.stock.transfer') }}" class="btn-stock btn-transfer">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Transfer
            </a>
            <a href="{{ route('inventory.stock.returns') }}" class="btn-stock btn-return">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Return
            </a>
            <a href="{{ route('inventory.stock.adjustments') }}" class="btn-stock btn-adjust">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Adjust
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="inv-stats-bar">
        <div class="inv-stat in">
            <div class="inv-stat-icon in">📥</div>
            <div>
                <div class="inv-stat-value">{{ $stats['in'] ?? 0 }}</div>
                <div class="inv-stat-label">Received</div>
            </div>
        </div>
        <div class="inv-stat out">
            <div class="inv-stat-icon out">📤</div>
            <div>
                <div class="inv-stat-value">{{ $stats['out'] ?? 0 }}</div>
                <div class="inv-stat-label">Delivered</div>
            </div>
        </div>
        <div class="inv-stat transfer">
            <div class="inv-stat-icon transfer">🔄</div>
            <div>
                <div class="inv-stat-value">{{ $stats['transfer'] ?? 0 }}</div>
                <div class="inv-stat-label">Transfers</div>
            </div>
        </div>
        <div class="inv-stat return">
            <div class="inv-stat-icon return">↩️</div>
            <div>
                <div class="inv-stat-value">{{ $stats['return'] ?? 0 }}</div>
                <div class="inv-stat-label">Returns</div>
            </div>
        </div>
        <div class="inv-stat adjustment">
            <div class="inv-stat-icon adjustment">⚖️</div>
            <div>
                <div class="inv-stat-value">{{ $stats['adjustment'] ?? 0 }}</div>
                <div class="inv-stat-label">Adjustments</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="inv-table-wrap">
        <div class="inv-filters">
            <select class="inv-filter-select" data-dt-filter="product_id">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <select class="inv-filter-select" data-dt-filter="warehouse_id">
                <option value="">All Warehouses</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            <select class="inv-filter-select" data-dt-filter="movement_type">
                <option value="">All Types</option>
                <option value="IN">📥 Receive</option>
                <option value="OUT">📤 Deliver</option>
                <option value="TRANSFER">🔄 Transfer</option>
                <option value="RETURN">↩️ Return</option>
                <option value="ADJUSTMENT">⚖️ Adjustment</option>
            </select>
            <input type="date" class="inv-filter-input" data-dt-filter="from_date" title="From Date">
            <input type="date" class="inv-filter-input" data-dt-filter="to_date" title="To Date">
        </div>
        
        <table class="dt-table dt-search dt-export dt-perpage" 
               id="movementsTable"
               data-route="{{ route('inventory.stock.movements.data') }}">
            <thead>
                <tr>
                    <th data-col="reference_no">Reference</th>
                    <th class="dt-sort" data-col="created_at" data-render="dateTime">Date</th>
                    <th data-col="movement_type" data-render="movementType">Type</th>
                    <th data-col="product_name" data-render="product">Product</th>
                    <th data-col="qty_signed" data-render="quantity">Qty</th>
                    <th data-col="location_display" data-render="location">Location</th>
                    <th data-col="reason">Reason</th>
                    <th data-render="actions">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- View Modal -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle">📋 Movement Details</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script>
// Store movement data for modal
var movementData = {};

window.dtRenders = window.dtRenders || {};

window.dtRenders.dateTime = function(value, row) {
    return `<div class="date-display">${row.created_at || '-'}<small>${row.created_time || ''}</small></div>`;
};

window.dtRenders.movementType = function(value, row) {
    const types = {
        'IN': { cls: 'in', icon: '📥', label: 'Received' },
        'OUT': { cls: 'out', icon: '📤', label: 'Delivered' },
        'TRANSFER': { cls: 'transfer', icon: '🔄', label: 'Transfer' },
        'RETURN': { cls: 'return', icon: '↩️', label: 'Return' },
        'ADJUSTMENT': { cls: 'adjustment', icon: '⚖️', label: 'Adjustment' }
    };
    const t = types[row.movement_type] || { cls: 'in', icon: '📦', label: row.movement_type };
    return `<span class="mv-badge ${t.cls}">${t.icon} ${t.label}</span>`;
};

window.dtRenders.product = function(value, row) {
    return `<div class="product-cell">
        <div class="product-thumb">${row.product_initials || 'PR'}</div>
        <div class="product-info">
            <div class="name">${row.product_name || '-'}</div>
            <div class="sku">${row.product_sku || ''}</div>
        </div>
    </div>`;
};

window.dtRenders.quantity = function(value, row) {
    const cls = row.is_positive ? 'positive' : 'negative';
    return `<span class="qty-display ${cls}">${row.qty_signed} <span class="unit">${row.unit || ''}</span></span>`;
};

window.dtRenders.location = function(value, row) {
    return `<div class="location-display">${row.location_display || '-'}</div>`;
};

window.dtRenders.actions = function(value, row) {
    movementData[row.id] = row;
    return `<button class="btn-view" onclick="viewMovement(${row.id})">👁 View</button>`;
};

function viewMovement(id) {
    const row = movementData[id];
    if (!row) return;
    
    const types = {
        'IN': { icon: '📥', label: 'Stock Received', color: '#059669' },
        'OUT': { icon: '📤', label: 'Stock Delivered', color: '#dc2626' },
        'TRANSFER': { icon: '🔄', label: 'Stock Transfer', color: '#7c3aed' },
        'RETURN': { icon: '↩️', label: 'Stock Return', color: '#0891b2' },
        'ADJUSTMENT': { icon: '⚖️', label: 'Stock Adjustment', color: '#f59e0b' }
    };
    const t = types[row.movement_type] || { icon: '📦', label: row.movement_type, color: '#6366f1' };
    
    document.getElementById('modalTitle').innerHTML = `${t.icon} ${t.label}`;
    
    let html = `<div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Reference No</div>
            <div class="detail-value">${row.reference_no || '-'}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Date & Time</div>
            <div class="detail-value">${row.created_at || '-'} ${row.created_time || ''}</div>
        </div>
        <div class="detail-item full">
            <div class="detail-label">Product</div>
            <div class="detail-value">${row.product_name || '-'} <span style="color:var(--text-muted);font-size:12px;">(${row.product_sku || ''})</span></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Quantity</div>
            <div class="detail-value" style="color:${t.color}">${row.qty_display || row.qty + ' ' + row.unit}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Stock Change</div>
            <div class="detail-value">${row.stock_before} → ${row.stock_after} ${row.base_unit}</div>
        </div>`;
    
    // Location details
    if (row.movement_type === 'TRANSFER') {
        html += `<div class="detail-item full">
            <div class="detail-label">Transfer Details</div>
            <div class="transfer-box">
                <div class="transfer-row">
                    <span class="transfer-tag from">FROM</span>
                    <div class="transfer-info">
                        <div class="transfer-warehouse">${row.warehouse_name || '-'}</div>
                        <div class="transfer-rack">${row.rack_code ? 'Rack: ' + row.rack_code : ''}</div>
                    </div>
                </div>
                <div class="transfer-row">
                    <span class="transfer-tag to">TO</span>
                    <div class="transfer-info">
                        <div class="transfer-warehouse">(See paired record)</div>
                    </div>
                </div>
            </div>
        </div>`;
    } else {
        html += `<div class="detail-item">
            <div class="detail-label">Warehouse</div>
            <div class="detail-value">${row.warehouse_name || '-'}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Rack</div>
            <div class="detail-value">${row.rack_code || 'Not specified'}</div>
        </div>`;
    }
    
    // Lot info
    if (row.lot_info) {
        html += `<div class="detail-item">
            <div class="detail-label">Lot / Batch</div>
            <div class="detail-value">${row.lot_info.lot_no || '-'}${row.lot_info.batch_no ? ' / ' + row.lot_info.batch_no : ''}</div>
        </div>`;
        if (row.lot_info.expiry_date) {
            html += `<div class="detail-item">
                <div class="detail-label">Expiry Date</div>
                <div class="detail-value">${row.lot_info.expiry_date}</div>
            </div>`;
        }
    }
    
    // Reason & Notes
    html += `<div class="detail-item full">
        <div class="detail-label">Reason</div>
        <div class="detail-value">${row.reason || '-'}</div>
    </div>`;
    
    if (row.notes) {
        html += `<div class="detail-item full">
            <div class="detail-label">Notes</div>
            <div class="detail-value" style="font-weight:400;font-size:13px;">${row.notes}</div>
        </div>`;
    }
    
    html += `<div class="detail-item full">
        <div class="detail-label">Created By</div>
        <div class="detail-value">${row.created_by || '-'}</div>
    </div></div>`;
    
    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('viewModal').classList.add('show');
}

function closeModal() {
    document.getElementById('viewModal').classList.remove('show');
}

document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

@include('core::datatable')
</x-layouts.app>