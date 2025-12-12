<x-layouts.app>
{{-- Tom Select CSS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-container { padding: 20px; width: 100%; box-sizing: border-box; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header-left { display: flex; align-items: center; gap: 16px; }
    .back-btn { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; transition: all 0.2s; }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #6366f1; }
    
    /* Action Buttons */
    .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-action { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-action svg { width: 16px; height: 16px; }
    .btn-action:hover { transform: translateY(-2px); }
    .btn-receive { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
    .btn-receive:hover { box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35); }
    .btn-deliver { background: linear-gradient(135deg, #ea580c, #c2410c); color: #fff; }
    .btn-deliver:hover { box-shadow: 0 4px 15px rgba(234, 88, 12, 0.35); }
    .btn-transfer { background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff; }
    .btn-transfer:hover { box-shadow: 0 4px 15px rgba(124, 58, 237, 0.35); }
    .btn-adjust { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .btn-adjust:hover { box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35); }
    
    /* Quick Stats */
    .quick-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .quick-stat { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; padding: 20px; text-align: center; transition: all 0.2s; }
    .quick-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .quick-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 20px; }
    .quick-stat-value { font-size: 32px; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .quick-stat-label { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 8px; font-weight: 600; }
    .quick-stat.in .quick-stat-icon { background: #d1fae5; color: #059669; }
    .quick-stat.in .quick-stat-value { color: #059669; }
    .quick-stat.out .quick-stat-icon { background: #fee2e2; color: #dc2626; }
    .quick-stat.out .quick-stat-value { color: #dc2626; }
    .quick-stat.transfer .quick-stat-icon { background: #ede9fe; color: #7c3aed; }
    .quick-stat.transfer .quick-stat-value { color: #7c3aed; }
    .quick-stat.return .quick-stat-icon { background: #cffafe; color: #0891b2; }
    .quick-stat.return .quick-stat-value { color: #0891b2; }
    .quick-stat.adjustment .quick-stat-icon { background: #fef3c7; color: #f59e0b; }
    .quick-stat.adjustment .quick-stat-value { color: #f59e0b; }
    
    /* Table Card */
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .table-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); }
    .table-card-title { font-size: 16px; font-weight: 700; color: #3730a3; display: flex; align-items: center; gap: 10px; }
    .table-card-title svg { width: 22px; height: 22px; }
    
    /* Filter Dropdowns */
    .table-filters { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
    .filter-group { position: relative; }
    .filter-group select, .filter-group input { padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; background: #fff; color: var(--text-primary); min-width: 150px; cursor: pointer; }
    .filter-group select:focus, .filter-group input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .filter-group input[type="date"] { min-width: 140px; }

    /* Tom Select for Filters */
    .table-filters .ts-wrapper { min-width: 160px; }
    .table-filters .ts-control { padding: 8px 12px !important; border-radius: 8px !important; min-height: 40px !important; font-size: 13px !important; }
    .table-filters .ts-wrapper.single .ts-control::after { right: 10px; }
    
    /* Badges */
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .badge-cyan { background: #cffafe; color: #155e75; }
    
    /* Quantity Styling */
    .qty-positive { color: #059669; font-weight: 700; }
    .qty-negative { color: #dc2626; font-weight: 700; }

    /* Product Cell */
    .product-cell { display: flex; align-items: flex-start; gap: 12px; }
    .product-icon { width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; flex-shrink: 0; }
    .product-details { display: flex; flex-direction: column; gap: 4px; }
    .product-name { font-weight: 600; color: var(--text-primary); font-size: 13px; }
    .product-sku { font-size: 11px; color: var(--text-muted); }
    .product-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
    .meta-badge { font-size: 9px; padding: 3px 7px; border-radius: 5px; font-weight: 700; }
    .meta-badge.lot { background: #fef3c7; color: #92400e; }
    .meta-badge.price { background: #d1fae5; color: #065f46; }
    .meta-badge.expiry { background: #fee2e2; color: #991b1b; }
    .meta-badge.expiry-soon { background: #ffedd5; color: #9a3412; }
    .meta-badge.mfg { background: #e0e7ff; color: #3730a3; }

    /* Location Cell */
    .location-cell { display: flex; flex-direction: column; gap: 4px; }
    .warehouse-name { font-weight: 600; color: var(--text-primary); font-size: 13px; }
    .rack-badge { font-size: 10px; padding: 4px 8px; background: #ede9fe; color: #5b21b6; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px; font-weight: 700; }
    
    /* Transfer Box */
    .transfer-box { background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 10px; padding: 12px; min-width: 200px; }
    .transfer-row { display: flex; align-items: flex-start; gap: 10px; }
    .transfer-label { font-size: 9px; font-weight: 800; padding: 3px 7px; border-radius: 4px; text-transform: uppercase; }
    .transfer-label.from { background: #fee2e2; color: #991b1b; }
    .transfer-label.to { background: #d1fae5; color: #065f46; }
    .transfer-details { flex: 1; }
    .transfer-warehouse { font-weight: 600; font-size: 12px; color: var(--text-primary); }
    .transfer-rack { font-size: 10px; color: #7c3aed; margin-top: 3px; font-weight: 600; }
    .transfer-arrow { display: flex; justify-content: center; padding: 6px 0; }
    .transfer-arrow svg { width: 16px; height: 16px; color: #a78bfa; }
    
    /* Date Cell */
    .date-cell { display: flex; flex-direction: column; gap: 2px; }
    .date-primary { font-weight: 600; color: var(--text-primary); font-size: 13px; }
    .date-secondary { font-size: 11px; color: var(--text-muted); }
    
    /* User Cell */
    .user-cell { display: flex; align-items: center; gap: 8px; }
    .user-avatar { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 11px; }
    .user-name { font-size: 13px; color: var(--text-primary); }
    
    /* Reason Text */
    .reason-text { font-size: 12px; color: var(--text-muted); max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    
    /* Quantity Cell */
    .qty-cell { display: flex; flex-direction: column; gap: 3px; }
    .qty-main { font-weight: 800; font-size: 14px; }
    .qty-base { font-size: 10px; color: var(--text-muted); font-weight: 500; }
</style>

<div class="page-container">
    <div class="page-header">
        <div class="page-header-left">
            <a href="{{ route('admin.inventory.dashboard') }}" class="back-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                Stock Movement History
            </h1>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.inventory.stock.receive') }}" class="btn-action btn-receive">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                Receive
            </a>
            <a href="{{ route('admin.inventory.stock.deliver') }}" class="btn-action btn-deliver">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                Deliver
            </a>
            <a href="{{ route('admin.inventory.stock.transfer') }}" class="btn-action btn-transfer">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Transfer
            </a>
            <a href="{{ route('admin.inventory.stock.adjustments') }}" class="btn-action btn-adjust">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Adjust
            </a>
        </div>
    </div>

    <div class="quick-stats">
        <div class="quick-stat in">
            <div class="quick-stat-icon">📥</div>
            <div class="quick-stat-value">{{ $stats['in'] ?? 0 }}</div>
            <div class="quick-stat-label">Received</div>
        </div>
        <div class="quick-stat out">
            <div class="quick-stat-icon">📤</div>
            <div class="quick-stat-value">{{ $stats['out'] ?? 0 }}</div>
            <div class="quick-stat-label">Delivered</div>
        </div>
        <div class="quick-stat transfer">
            <div class="quick-stat-icon">🔄</div>
            <div class="quick-stat-value">{{ $stats['transfer'] ?? 0 }}</div>
            <div class="quick-stat-label">Transfers</div>
        </div>
        <div class="quick-stat return">
            <div class="quick-stat-icon">↩️</div>
            <div class="quick-stat-value">{{ $stats['return'] ?? 0 }}</div>
            <div class="quick-stat-label">Returns</div>
        </div>
        <div class="quick-stat adjustment">
            <div class="quick-stat-icon">⚖️</div>
            <div class="quick-stat-value">{{ $stats['adjustment'] ?? 0 }}</div>
            <div class="quick-stat-label">Adjustments</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                Movement Records
            </div>
            <div class="table-filters">
                <div class="filter-group">
                    <select id="filter_product" data-dt-filter="product_id">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <select id="filter_warehouse" data-dt-filter="warehouse_id">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <select id="filter_type" data-dt-filter="movement_type">
                        <option value="">All Types</option>
                        <option value="IN">📥 Receive</option>
                        <option value="OUT">📤 Deliver</option>
                        <option value="TRANSFER">🔄 Transfer</option>
                        <option value="RETURN">↩️ Return</option>
                        <option value="ADJUSTMENT">⚖️ Adjustment</option>
                    </select>
                </div>
                <div class="filter-group">
                    <input type="date" data-dt-filter="from_date" title="From Date" placeholder="From">
                </div>
                <div class="filter-group">
                    <input type="date" data-dt-filter="to_date" title="To Date" placeholder="To">
                </div>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage" id="movementsTable" data-route="{{ route('admin.inventory.stock.movements.data') }}">
                <thead>
                    <tr>
                        <th data-col="reference_no">Reference</th>
                        <th class="dt-sort" data-col="created_at" data-render="date">Date</th>
                        <th data-col="movement_type" data-render="movementType">Type</th>
                        <th data-col="product_name" data-render="product">Product Info</th>
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

{{-- Tom Select JS --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
// Initialize Tom Select for filter dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if elements exist
    ['filter_product', 'filter_warehouse', 'filter_type'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            new TomSelect(el, {
                plugins: ['dropdown_input'],
                create: false,
                allowEmptyOption: true
            });
        }
    });
});

window.dtRenders = window.dtRenders || {};

window.dtRenders.date = function(data, row) {
    return `<div class="date-cell"><span class="date-primary">${row.created_at}</span><span class="date-secondary">${row.created_time || ''}</span></div>`;
};

window.dtRenders.movementType = function(data, row) {
    const types = {
        'IN': { class: 'badge-success', icon: '📥', label: 'Received' },
        'OUT': { class: 'badge-danger', icon: '📤', label: 'Delivered' },
        'TRANSFER': { class: 'badge-purple', icon: '🔄', label: 'Transfer' },
        'RETURN': { class: 'badge-cyan', icon: '↩️', label: 'Return' },
        'ADJUSTMENT': { class: 'badge-warning', icon: '⚖️', label: 'Adjustment' }
    };
    const c = types[row.movement_type] || { class: 'badge-info', icon: '📦', label: row.movement_type };
    return `<span class="badge ${c.class}">${c.icon} ${c.label}</span>`;
};

window.dtRenders.product = function(data, row) {
    let metaHtml = '';
    
    // Lot badge
    if (row.lot_no) {
        metaHtml += `<span class="meta-badge lot">LOT: ${row.lot_no}${row.batch_no ? '/' + row.batch_no : ''}</span>`;
    }
    
    // Price
    const price = row.lot_price || row.product_price || row.unit_price;
    if (price) {
        metaHtml += `<span class="meta-badge price">₹${parseFloat(price).toFixed(2)}</span>`;
    }
    
    // Manufacturing date
    if (row.manufacturing_date) {
        metaHtml += `<span class="meta-badge mfg">Mfg: ${row.manufacturing_date}</span>`;
    }
    
    // Expiry date
    if (row.expiry_date) {
        let expClass = 'expiry';
        if (row.expiry_status === 'expiring_soon') expClass = 'expiry-soon';
        metaHtml += `<span class="meta-badge ${expClass}">Exp: ${row.expiry_date}${row.days_to_expiry ? ' (' + row.days_to_expiry + 'd)' : ''}</span>`;
    }
    
    return `
        <div class="product-cell">
            <div class="product-icon">${row.product_initials || 'P'}</div>
            <div class="product-details">
                <span class="product-name">${row.product_name || '-'}</span>
                <span class="product-sku">${row.product_sku || ''}</span>
                ${metaHtml ? '<div class="product-meta">' + metaHtml + '</div>' : ''}
            </div>
        </div>
    `;
};

window.dtRenders.quantity = function(data, row) {
    const qtyClass = row.is_positive ? 'qty-positive' : 'qty-negative';
    const sign = row.is_positive ? '+' : '-';
    let html = `<div class="qty-cell"><span class="qty-main ${qtyClass}">${sign}${row.qty_display || row.qty} ${row.unit_name || ''}</span>`;
    if (row.base_qty && row.base_qty != row.qty) {
        html += `<span class="qty-base">= ${row.base_qty} ${row.base_unit_name || 'base'}</span>`;
    }
    html += `</div>`;
    return html;
};

window.dtRenders.location = function(data, row) {
    if (row.is_transfer && row.from_warehouse) {
        const fromRack = row.from_rack_code ? `<span class="transfer-rack">${row.from_rack_code}</span>` : '';
        const toRack = row.to_rack_code ? `<span class="transfer-rack">${row.to_rack_code}</span>` : '';
        return `
            <div class="transfer-box">
                <div class="transfer-row">
                    <span class="transfer-label from">FROM</span>
                    <div class="transfer-details">
                        <div class="transfer-warehouse">${row.from_warehouse}</div>
                        ${fromRack}
                    </div>
                </div>
                <div class="transfer-arrow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
                <div class="transfer-row">
                    <span class="transfer-label to">TO</span>
                    <div class="transfer-details">
                        <div class="transfer-warehouse">${row.to_warehouse}</div>
                        ${toRack}
                    </div>
                </div>
            </div>
        `;
    }
    const rackHtml = row.rack_code ? `<span class="rack-badge">${row.rack_code}</span>` : '';
    return `<div class="location-cell"><span class="warehouse-name">${row.warehouse_name || '-'}</span>${rackHtml}</div>`;
};

window.dtRenders.reason = function(data, row) {
    const r = row.reason || '-';
    return `<span class="reason-text" title="${r}">${r.length > 40 ? r.substring(0,40)+'...' : r}</span>`;
};

window.dtRenders.user = function(data, row) {
    return `<div class="user-cell"><div class="user-avatar">${row.created_by_initial || 'U'}</div><span class="user-name">${row.created_by || '-'}</span></div>`;
};
</script>

@include('core::datatable')
</x-layouts.app>