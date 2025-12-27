<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-container { padding: 20px; max-width: 800px; margin: 0 auto; }
    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #8b5cf6; }
    
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 12px 12px 0 0; }
    .form-card-title { font-size: 16px; font-weight: 600; color: #5b21b6; margin: 0; }
    .form-card-body { padding: 24px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--card-border); }
    .form-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
    .form-section-title { font-size: 13px; font-weight: 600; color: #8b5cf6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 20px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-help { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 8px !important; border: 1px solid var(--card-border) !important; min-height: 44px !important; background: var(--card-bg) !important; }
    .ts-wrapper.focus .ts-control { border-color: #8b5cf6 !important; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15) !important; }
    .ts-dropdown { border-radius: 8px !important; border: 1px solid var(--card-border) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important; background: var(--card-bg) !important; }
    .ts-dropdown .option { padding: 10px 14px !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #ede9fe !important; color: #5b21b6 !important; }

    .info-panel { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--card-border); }
    .info-icon { width: 44px; height: 44px; border-radius: 8px; background: #8b5cf6; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .info-name { font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .info-sku { font-size: 12px; color: #8b5cf6; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 10px; }
    .info-item { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 10px; }
    .info-item-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .info-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #ede9fe; color: #5b21b6; }

    .lot-box { background: #faf5ff; border: 1px solid #d8b4fe; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 13px; font-weight: 600; color: #7c3aed; margin-bottom: 12px; }

    .variation-box { background: #f5f3ff; border: 1px solid #c4b5fd; border-radius: 10px; padding: 16px; margin-top: 16px; display: none; }
    .variation-box .form-group { margin: 0; }

    .stock-preview { display: none; align-items: center; gap: 20px; background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    .stock-preview.show { display: flex; flex-wrap: wrap; }
    .stock-preview-item { flex: 1; min-width: 100px; text-align: center; }
    .stock-preview-label { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
    .stock-preview-value { font-size: 28px; font-weight: 800; }
    .stock-preview-value.current { color: #6b7280; }
    .stock-preview-value.new { color: #8b5cf6; }
    .stock-preview-arrow { font-size: 24px; color: var(--text-muted); }

    .adjustment-types { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
    .adj-type { display: flex; flex-direction: column; align-items: center; padding: 16px; border: 2px solid var(--card-border); border-radius: 10px; cursor: pointer; transition: all 0.2s; background: var(--card-bg); }
    .adj-type:hover { border-color: #c4b5fd; }
    .adj-type.active { border-color: #8b5cf6; background: #faf5ff; }
    .adj-type input { display: none; }
    .adj-type-icon { font-size: 24px; margin-bottom: 8px; }
    .adj-type-label { font-weight: 600; font-size: 14px; color: var(--text-primary); }
    .adj-type-desc { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

    .form-actions { display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--card-border); margin-top: 24px; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
    .btn-secondary { background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--card-border); }
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('inventory.stock.movements') }}" class="back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
        <h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Stock Adjustment</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    @include('inventory::partials.barcode-scanner', ['color' => 'purple'])

    <div class="form-card">
        <div class="form-card-header"><h3 class="form-card-title">📊 Adjust Stock Levels</h3></div>
        <div class="form-card-body">
            <form action="{{ route('inventory.stock.adjustments.store') }}" method="POST" id="mainForm">
                @csrf

                <div class="form-section">
                    <div class="form-section-title">📦 Product Selection</div>
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                data-batch="{{ $product->is_batch_managed ? '1' : '0' }}" 
                                data-unit="{{ $product->unit->short_name ?? 'PCS' }}" 
                                data-name="{{ $product->name }}" 
                                data-sku="{{ $product->sku }}"
                                data-has-variants="{{ $product->has_variants ? '1' : '0' }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="variation-box" id="variationBox">
                        <div class="form-group">
                            <label class="form-label">Variation <span class="required">*</span></label>
                            <select name="variation_id" id="variation_id"><option value="">Select variation...</option></select>
                        </div>
                    </div>
                    
                    <div class="info-panel" id="infoPanel">
                        <div class="info-header">
                            <div class="info-icon" id="pIcon">P</div>
                            <div>
                                <div class="info-name" id="pName">-</div>
                                <div class="info-sku" id="pSku">-</div>
                                <div id="lotBadge"></div>
                            </div>
                        </div>
                        <div class="info-grid">
                            <div class="info-item"><div class="info-item-label">Base Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">📍 Location</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Warehouse <span class="required">*</span></label>
                            <select name="warehouse_id" id="warehouse_id" required>
                                <option value="">Select warehouse...</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $wh->is_default ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rack</label>
                            <select name="rack_id" id="rack_id"><option value="">Select rack...</option></select>
                        </div>
                    </div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Select Lot</div>
                    <div class="form-group" style="margin:0;">
                        <select name="lot_id" id="lot_id"><option value="">Select lot...</option></select>
                    </div>
                </div>

                <div class="stock-preview" id="stockPreview">
                    <div class="stock-preview-item">
                        <div class="stock-preview-label">Current Stock</div>
                        <div class="stock-preview-value current"><span id="currentStock">0</span> <small id="stockUnit">PCS</small></div>
                    </div>
                    <div class="stock-preview-arrow">→</div>
                    <div class="stock-preview-item">
                        <div class="stock-preview-label">New Stock</div>
                        <div class="stock-preview-value new"><span id="newStock">0</span> <small id="newStockUnit">PCS</small></div>
                    </div>
                </div>

                <div class="adjustment-types">
                    <label class="adj-type active" onclick="setAdjType('set')">
                        <input type="radio" name="adjustment_type" value="set" checked>
                        <div class="adj-type-icon">📊</div>
                        <div class="adj-type-label">Set To</div>
                        <div class="adj-type-desc">Set exact quantity</div>
                    </label>
                    <label class="adj-type" onclick="setAdjType('add')">
                        <input type="radio" name="adjustment_type" value="add">
                        <div class="adj-type-icon">➕</div>
                        <div class="adj-type-label">Add</div>
                        <div class="adj-type-desc">Increase stock</div>
                    </label>
                    <label class="adj-type" onclick="setAdjType('subtract')">
                        <input type="radio" name="adjustment_type" value="subtract">
                        <div class="adj-type-icon">➖</div>
                        <div class="adj-type-label">Subtract</div>
                        <div class="adj-type-desc">Decrease stock</div>
                    </label>
                </div>

                <div class="form-section">
                    <div class="form-section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" id="qtyLabel">New Quantity <span class="required">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit</label>
                            <input type="text" id="unit_display" class="form-control" readonly style="background:#f3f4f6;">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Reason <span class="required">*</span></label>
                        <select name="reason" id="reason" required>
                            <option value="Physical Count">Physical Count</option>
                            <option value="Damaged Goods">Damaged Goods</option>
                            <option value="Expired Stock">Expired Stock</option>
                            <option value="Found Stock">Found Stock</option>
                            <option value="Lost Stock">Lost Stock</option>
                            <option value="System Correction">System Correction</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Additional details about this adjustment..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">📊 Apply Adjustment</button>
                    <a href="{{ route('inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var lots = [], currentStockVal = 0, adjType = 'set', baseUnit = 'PCS', selectedLot = null, variations = [];
var selProduct, selWh, selRack, selReason, selLot, selVariation;

document.addEventListener('DOMContentLoaded', function() {
    selProduct = new TomSelect('#product_id', {plugins: ['dropdown_input'], create: false, onChange: onProduct});
    selWh = new TomSelect('#warehouse_id', {plugins: ['dropdown_input'], create: false, onChange: onWarehouse});
    selRack = new TomSelect('#rack_id', {plugins: ['dropdown_input'], create: false, onChange: function() { checkStock(); }});
    selReason = new TomSelect('#reason', {plugins: ['dropdown_input'], create: false});
    selLot = new TomSelect('#lot_id', {plugins: ['dropdown_input'], create: false, onChange: onLotChange});
    selVariation = new TomSelect('#variation_id', {plugins: ['dropdown_input'], create: false, onChange: onVariation});
    
    var w = document.getElementById('warehouse_id').value;
    if (w) loadRacks(w);
});

function onProduct(v) {
    var o = document.querySelector('#product_id option[value="'+v+'"]');
    if (!o || !v) {
        document.getElementById('infoPanel').classList.remove('show');
        document.getElementById('lotBox').classList.remove('show');
        document.getElementById('stockPreview').classList.remove('show');
        document.getElementById('variationBox').style.display = 'none';
        return;
    }
    
    baseUnit = o.dataset.unit || 'PCS';
    document.getElementById('unit_display').value = baseUnit;
    
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent = o.dataset.name.substring(0, 2).toUpperCase();
    document.getElementById('pName').textContent = o.dataset.name;
    document.getElementById('pSku').textContent = 'SKU: ' + o.dataset.sku;
    document.getElementById('pUnit').textContent = baseUnit;
    document.getElementById('lotBadge').innerHTML = '';
    
    // Handle variations
    if (o.dataset.hasVariants === '1') {
        document.getElementById('variationBox').style.display = 'block';
        document.getElementById('variation_id').required = true;
        loadVariations(v);
    } else {
        document.getElementById('variationBox').style.display = 'none';
        document.getElementById('variation_id').required = false;
        selVariation.clear(); selVariation.clearOptions();
    }
    
    // Handle batch/lot managed products
    if (o.dataset.batch === '1') {
        document.getElementById('lotBox').classList.add('show');
        loadLots(v);
    } else {
        document.getElementById('lotBox').classList.remove('show');
        selLot.clear(); selLot.clearOptions();
    }
    
    checkStock();
}

function loadVariations(productId) {
    var wh = document.getElementById('warehouse_id').value;
    fetch('{{ url("admin/inventory/stock/product-variations") }}?product_id=' + productId + (wh ? '&warehouse_id=' + wh : ''))
        .then(r => r.json())
        .then(d => {
            variations = d.variations || d || [];
            selVariation.clear(); selVariation.clearOptions();
            selVariation.addOption({value: '', text: 'Select variation...'});
            variations.forEach(v => {
                var label = v.variation_name || v.sku;
                if (v.current_stock !== undefined) label += ' (Stock: ' + v.current_stock + ')';
                selVariation.addOption({value: String(v.id), text: label});
            });
        });
}

function onVariation(v) {
    if (!v) return;
    checkStock();
}

function loadLots(productId) {
    var wh = document.getElementById('warehouse_id').value;
    var rack = document.getElementById('rack_id').value;
    var url = '{{ url("admin/inventory/stock/product-lots") }}?product_id=' + productId + '&with_stock=1';
    if (wh) url += '&warehouse_id=' + wh;
    if (rack) url += '&rack_id=' + rack;
    
    fetch(url).then(r => r.json()).then(d => {
        lots = Array.isArray(d) ? d : (d.lots || d.data || []);
        selLot.clear(); selLot.clearOptions();
        selLot.addOption({value: '', text: 'Select lot...'});
        
        lots.forEach(l => {
            var label = l.lot_no;
            if (l.batch_no) label += ' / ' + l.batch_no;
            if (l.expiry_date) label += ' • Exp: ' + l.expiry_date;
            var stockQty = l.stock_display || l.stock || l.qty || '0';
            label += ' • ' + stockQty + ' PCS';
            selLot.addOption({value: l.id, text: label});
        });
        
        // Auto-select first lot
        if (lots.length > 0) {
            selLot.setValue(lots[0].id);
            onLotChange(lots[0].id);
        }
    });
}

function onLotChange(v) {
    if (!v || v === '') {
        selectedLot = null;
        document.getElementById('lotBadge').innerHTML = '';
        checkStock();
        return;
    }
    
    selectedLot = lots.find(l => l.id == v);
    if (selectedLot) {
        document.getElementById('lotBadge').innerHTML = '<span class="info-badge">' + selectedLot.lot_no + '</span>';
        checkStock();
    }
}

function checkStock() {
    var p = document.getElementById('product_id').value;
    var w = document.getElementById('warehouse_id').value;
    var r = document.getElementById('rack_id').value;
    var l = document.getElementById('lot_id').value;
    var v = document.getElementById('variation_id').value;
    
    if (!p || !w) {
        document.getElementById('stockPreview').classList.remove('show');
        return;
    }
    
    var url = '{{ route("inventory.stock.check") }}?product_id=' + p + '&warehouse_id=' + w;
    if (r) url += '&rack_id=' + r;
    if (l) url += '&lot_id=' + l;
    if (v) url += '&variation_id=' + v;
    
    fetch(url).then(r => r.json()).then(d => {
        currentStockVal = parseFloat(d.base_stock || d.quantity) || 0;
        document.getElementById('currentStock').textContent = currentStockVal;
        document.getElementById('stockUnit').textContent = d.base_unit || 'PCS';
        document.getElementById('newStockUnit').textContent = d.base_unit || 'PCS';
        document.getElementById('stockPreview').classList.add('show');
        updateNewStock();
    });
}

function setAdjType(type) {
    adjType = type;
    document.querySelectorAll('.adj-type').forEach(t => {
        t.classList.remove('active');
        if (t.querySelector('input').value === type) {
            t.classList.add('active');
            t.querySelector('input').checked = true;
        }
    });
    
    var l = document.getElementById('qtyLabel');
    if (type === 'set') l.innerHTML = 'New Quantity <span class="required">*</span>';
    else if (type === 'add') l.innerHTML = 'Quantity to Add <span class="required">*</span>';
    else l.innerHTML = 'Quantity to Subtract <span class="required">*</span>';
    
    updateNewStock();
}

function updateNewStock() {
    var qty = parseFloat(document.getElementById('qty').value) || 0;
    var newVal = 0;
    
    if (adjType === 'set') newVal = qty;
    else if (adjType === 'add') newVal = currentStockVal + qty;
    else newVal = currentStockVal - qty;
    
    document.getElementById('newStock').textContent = Math.max(0, newVal).toFixed(2);
}

function onWarehouse() {
    loadRacks(document.getElementById('warehouse_id').value);
    // Reload lots for new warehouse
    var p = document.getElementById('product_id').value;
    var o = document.querySelector('#product_id option[value="'+p+'"]');
    if (p && o && o.dataset.batch === '1') {
        loadLots(p);
    }
    checkStock();
}

function loadRacks(wid) {
    selRack.clear(); selRack.clearOptions();
    selRack.addOption({value: '', text: 'Select rack...'});
    if (!wid) return;
    fetch('{{ url("admin/inventory/racks/by-warehouse") }}/' + wid)
        .then(r => r.json())
        .then(d => {
            d.forEach(r => {
                selRack.addOption({value: r.id, text: r.code + ' - ' + r.name});
            });
        });
}

document.getElementById('qty').addEventListener('input', updateNewStock);
</script>
