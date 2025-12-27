<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-container { padding: 20px; max-width: 800px; margin: 0 auto; }
    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #ea580c; }
    
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: linear-gradient(135deg, #ffedd5, #fed7aa); border-radius: 12px 12px 0 0; }
    .form-card-title { font-size: 16px; font-weight: 600; color: #9a3412; margin: 0; }
    .form-card-body { padding: 24px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--card-border); }
    .form-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
    .form-section-title { font-size: 13px; font-weight: 600; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 20px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-help { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 8px !important; border: 1px solid var(--card-border) !important; min-height: 44px !important; background: var(--card-bg) !important; }
    .ts-wrapper.focus .ts-control { border-color: #ea580c !important; box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15) !important; }
    .ts-dropdown { border-radius: 8px !important; border: 1px solid var(--card-border) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important; background: var(--card-bg) !important; }
    .ts-dropdown .option { padding: 10px 14px !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #ffedd5 !important; color: #9a3412 !important; }

    .info-panel { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--card-border); }
    .info-icon { width: 44px; height: 44px; border-radius: 8px; background: #ea580c; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .info-name { font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .info-sku { font-size: 12px; color: #ea580c; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 10px; }
    .info-item { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 10px; }
    .info-item-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .info-item-value.price { color: #ea580c; }
    .info-item-sub { font-size: 10px; color: var(--text-muted); margin-top: 2px; }
    .info-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #d1fae5; color: #065f46; }

    .stock-display { background: linear-gradient(135deg, #fef3c7, #fed7aa); border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-bottom: 16px; display: none; }
    .stock-display.show { display: block; }
    .stock-label { font-size: 12px; color: #92400e; }
    .stock-value { font-size: 24px; font-weight: 800; color: #ea580c; }
    
    .unit-hint { background: #ffedd5; border: 1px solid #fed7aa; border-radius: 6px; padding: 10px 12px; margin-top: 8px; font-size: 12px; color: #9a3412; display: none; }
    .unit-hint.show { display: block; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 13px; font-weight: 600; color: #92400e; margin-bottom: 12px; }

    .variation-box { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-top: 16px; display: none; }
    .variation-box .form-group { margin: 0; }

    .form-actions { display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--card-border); margin-top: 24px; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #ea580c, #c2410c); color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3); }
    .btn-secondary { background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--card-border); }
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('inventory.stock.movements') }}" class="back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
        <h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Deliver Stock</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    @include('inventory::partials.barcode-scanner', ['color' => 'red'])

    <div class="form-card">
        <div class="form-card-header"><h3 class="form-card-title">📤 Stock Out - Deliver Goods</h3></div>
        <div class="form-card-body">
            <form action="{{ route('inventory.stock.deliver.store') }}" method="POST" id="mainForm">
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
                                data-sale="{{ $product->sale_price ?? 0 }}"
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
                            <div class="info-item"><div class="info-item-label">Available Stock</div><div class="info-item-value" id="pStock">0</div></div>
                            <div class="info-item"><div class="info-item-label">Sale Price</div><div class="info-item-value price" id="pSale">₹0</div><div class="info-item-sub" id="pSaleSource">Product default</div></div>
                            <div class="info-item"><div class="info-item-label">Base Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                            <div class="info-item" id="expItem" style="display:none;"><div class="info-item-label">Expiry</div><div class="info-item-value" id="pExp">-</div><div class="info-item-sub" id="pExpDays"></div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">📍 Source Location</div>
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
                    <div class="stock-display" id="stockDisplay">
                        <div class="stock-label">Available Stock</div>
                        <div class="stock-value" id="availStock">0</div>
                    </div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Select Lot (First Expiry First Out)</div>
                    <div class="form-group" style="margin:0;">
                        <select name="lot_id" id="lot_id"><option value="">Select lot...</option></select>
                        <div class="form-help">Lots are sorted by expiry date - oldest first</div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Quantity <span class="required">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" required>
                            <div class="form-help" id="qtyHint">Max: 0</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit <span class="required">*</span></label>
                            <select name="unit_id" id="unit_id" required>
                                <option value="">Select unit...</option>
                                @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <div class="unit-hint" id="unitHint"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference Type</label>
                        <select name="reference_type" id="ref_type" class="form-control">
                            <option value="SALE">Sale</option>
                            <option value="ADJUSTMENT">Adjustment</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" value="Stock delivered">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">📤 Deliver Stock</button>
                    <a href="{{ route('inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var pData = {}, lots = [], selectedLot = null, baseStock = 0, productDefaults = {sale: 0}, variations = [];
var selProduct, selWh, selRack, selUnit, selLot, selVariation;

document.addEventListener('DOMContentLoaded', function() {
    selProduct = new TomSelect('#product_id', {plugins: ['dropdown_input'], create: false, onChange: onProduct});
    selWh = new TomSelect('#warehouse_id', {plugins: ['dropdown_input'], create: false, onChange: onWarehouse});
    selRack = new TomSelect('#rack_id', {plugins: ['dropdown_input'], create: false, onChange: function() { checkStock(); }});
    selUnit = new TomSelect('#unit_id', {plugins: ['dropdown_input'], create: false, onChange: updateUnit});
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
        document.getElementById('variationBox').style.display = 'none';
        document.getElementById('stockDisplay').classList.remove('show');
        return;
    }
    
    productDefaults.sale = parseFloat(o.dataset.sale) || 0;
    
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent = o.dataset.name.substring(0, 2).toUpperCase();
    document.getElementById('pName').textContent = o.dataset.name;
    document.getElementById('pSku').textContent = 'SKU: ' + o.dataset.sku;
    document.getElementById('pUnit').textContent = o.dataset.unit || 'PCS';
    document.getElementById('pSale').textContent = '₹' + productDefaults.sale.toFixed(2);
    document.getElementById('pSaleSource').textContent = 'Product default';
    document.getElementById('lotBadge').innerHTML = '';
    document.getElementById('expItem').style.display = 'none';
    
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
    
    loadUnits(v);
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
    var variation = variations.find(x => String(x.id) === String(v));
    if (variation && variation.sale_price) {
        document.getElementById('pSale').textContent = '₹' + parseFloat(variation.sale_price).toFixed(2);
        document.getElementById('pSaleSource').textContent = '✓ From variation';
        document.getElementById('pSku').textContent = 'SKU: ' + variation.sku;
    }
    checkStock();
}

function loadUnits(pid) {
    fetch('{{ url("admin/inventory/stock/product-units") }}?product_id=' + pid)
        .then(r => r.json())
        .then(d => {
            pData = d;
            selUnit.clear(); selUnit.clearOptions();
            (d.units || []).forEach(u => {
                var label = u.name + (u.is_base ? ' (Base)' : u.conversion_factor != 1 ? ' (=' + u.conversion_factor + ' ' + d.base_unit_name + ')' : '');
                selUnit.addOption({value: u.id, text: label});
            });
            selUnit.setValue(d.base_unit_id);
        });
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
            if (l.sale_price) label += ' • ₹' + parseFloat(l.sale_price).toFixed(2);
            var stockQty = l.stock_display || l.stock || l.qty || '0';
            label += ' • ' + stockQty + ' PCS';
            selLot.addOption({value: l.id, text: label});
        });
        
        // Auto-select first lot (FEFO)
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
        document.getElementById('expItem').style.display = 'none';
        document.getElementById('pSale').textContent = '₹' + productDefaults.sale.toFixed(2);
        document.getElementById('pSaleSource').textContent = 'Product default';
        checkStock();
        return;
    }
    
    selectedLot = lots.find(l => l.id == v);
    if (selectedLot) {
        document.getElementById('lotBadge').innerHTML = '<span class="info-badge">' + selectedLot.lot_no + '</span>';
        
        // Show expiry info
        if (selectedLot.expiry_date) {
            document.getElementById('expItem').style.display = 'block';
            document.getElementById('pExp').textContent = selectedLot.expiry_date;
            var days = Math.ceil((new Date(selectedLot.expiry_date) - new Date()) / (1000*60*60*24));
            document.getElementById('pExpDays').textContent = days > 0 ? days + ' days left' : 'EXPIRED';
        } else {
            document.getElementById('expItem').style.display = 'none';
        }
        
        // Update price from lot
        if (selectedLot.sale_price !== null && selectedLot.sale_price !== '' && selectedLot.sale_price !== undefined) {
            document.getElementById('pSale').textContent = '₹' + parseFloat(selectedLot.sale_price).toFixed(2);
            document.getElementById('pSaleSource').textContent = '✓ From lot';
        } else {
            document.getElementById('pSale').textContent = '₹' + productDefaults.sale.toFixed(2);
            document.getElementById('pSaleSource').textContent = 'Product default';
        }
        
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
        document.getElementById('stockDisplay').classList.remove('show');
        return;
    }
    
    var url = '{{ route("inventory.stock.check") }}?product_id=' + p + '&warehouse_id=' + w;
    if (r) url += '&rack_id=' + r;
    if (l) url += '&lot_id=' + l;
    if (v) url += '&variation_id=' + v;
    
    fetch(url).then(r => r.json()).then(d => {
        baseStock = parseFloat(d.base_stock || d.quantity) || 0;
        var unitName = d.base_unit || 'PCS';
        document.getElementById('availStock').textContent = baseStock + ' ' + unitName;
        document.getElementById('pStock').textContent = baseStock + ' ' + unitName;
        document.getElementById('stockDisplay').classList.add('show');
        updateUnit();
    });
}

function updateUnit() {
    var uid = document.getElementById('unit_id').value;
    var qty = parseFloat(document.getElementById('qty').value) || 0;
    var h = document.getElementById('unitHint');
    
    if (uid && pData.units) {
        var u = pData.units.find(x => x.id == uid);
        if (u && pData.base_unit_name) {
            var c = parseFloat(u.conversion_factor) || 1;
            document.getElementById('qtyHint').textContent = 'Max: ' + (baseStock / c).toFixed(2);
            if (!u.is_base && c != 1) {
                h.innerHTML = qty > 0 
                    ? '<b>' + qty + '</b> × ' + c + ' = <b>' + (qty * c).toFixed(2) + ' ' + pData.base_unit_name + '</b> will be deducted'
                    : '1 unit = ' + c + ' ' + pData.base_unit_name;
                h.classList.add('show');
                return;
            }
        }
    }
    document.getElementById('qtyHint').textContent = 'Max: ' + baseStock;
    h.classList.remove('show');
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

document.getElementById('qty').addEventListener('input', updateUnit);

document.getElementById('mainForm').addEventListener('submit', function(e) {
    var qty = parseFloat(document.getElementById('qty').value) || 0;
    var c = 1;
    if (pData.units) {
        var u = pData.units.find(x => x.id == document.getElementById('unit_id').value);
        if (u) c = parseFloat(u.conversion_factor) || 1;
    }
    if (qty * c > baseStock) {
        e.preventDefault();
        alert('Insufficient stock!\n\nRequired: ' + (qty * c).toFixed(2) + '\nAvailable: ' + baseStock);
    }
});
</script>
