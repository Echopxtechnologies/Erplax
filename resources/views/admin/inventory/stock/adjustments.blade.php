<x-layouts.app>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .stock-page { padding: 24px; max-width: 900px; }
    .stock-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .stock-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .stock-header .icon { font-size: 28px; }
    .back-link { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; font-size: 18px; }
    .back-link:hover { background: #f1f5f9; }
    
    .stock-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; overflow: hidden; }
    .stock-card-header { padding: 18px 24px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-bottom: 1px solid #fcd34d; }
    .stock-card-header h3 { margin: 0; font-size: 16px; font-weight: 600; color: #92400e; }
    .stock-card-body { padding: 28px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #e2e8f0; }
    .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .section-title { font-size: 13px; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .req { color: #ef4444; }
    .form-control { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; background: #fff; box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,0.12); }
    .form-control[readonly] { background: #f8fafc; }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-hint { font-size: 12px; color: #64748b; margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 10px !important; border: 1px solid #e2e8f0 !important; min-height: 46px !important; }
    .ts-wrapper.focus .ts-control { border-color: #d97706 !important; box-shadow: 0 0 0 3px rgba(217,119,6,0.12) !important; }
    .ts-dropdown { border-radius: 10px !important; box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important; margin-top: 4px !important; }
    .ts-dropdown .option { padding: 10px 14px !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #fffbeb !important; }
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid #e2e8f0 !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; }

    .info-panel { background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fcd34d; border-radius: 12px; padding: 20px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #fcd34d; }
    .info-icon { width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
    .info-name { font-size: 17px; font-weight: 700; color: #92400e; }
    .info-sku { font-size: 12px; color: #d97706; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; }
    .info-item { background: #fff; border-radius: 8px; padding: 10px 12px; }
    .info-item-label { font-size: 10px; color: #d97706; text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 14px; font-weight: 700; color: #92400e; }

    .stock-preview { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 20px; margin-bottom: 20px; display: none; }
    .stock-preview.show { display: flex; align-items: center; justify-content: center; gap: 30px; }
    .stock-preview-item { text-align: center; }
    .stock-preview-label { font-size: 12px; color: #92400e; margin-bottom: 6px; }
    .stock-preview-value { font-size: 28px; font-weight: 800; }
    .stock-preview-value.current { color: #d97706; }
    .stock-preview-value.new { color: #059669; }
    .stock-preview-arrow { font-size: 28px; color: #d97706; }

    .adjustment-types { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
    .adj-type { padding: 18px 14px; border: 2px solid #e5e7eb; border-radius: 12px; background: #fff; cursor: pointer; text-align: center; transition: all 0.2s; }
    .adj-type:hover { border-color: #fbbf24; background: #fffbeb; }
    .adj-type.active { border-color: #f59e0b; background: #fef3c7; }
    .adj-type input { display: none; }
    .adj-type-icon { font-size: 28px; margin-bottom: 8px; }
    .adj-type-label { font-size: 14px; font-weight: 700; color: var(--text-primary); }
    .adj-type-desc { font-size: 11px; color: #64748b; margin-top: 4px; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 18px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 14px; font-weight: 600; color: #92400e; margin-bottom: 14px; }

    .form-actions { display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid #e2e8f0; margin-top: 20px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-secondary { background: #f1f5f9; color: #334155; }
    
    .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }
</style>

<div class="stock-page">
    <div class="stock-header">
        <a href="{{ route('admin.inventory.stock.movements') }}" class="back-link">←</a>
        <span class="icon">⚖️</span>
        <h1>Stock Adjustment</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    <div class="stock-card">
        <div class="stock-card-header">
            <h3>⚖️ Adjust Stock Quantity</h3>
        </div>
        <div class="stock-card-body">
            <form action="{{ route('admin.inventory.stock.adjustments.store') }}" method="POST" id="mainForm">
                @csrf

                <div class="form-section">
                    <div class="section-title">📦 Product Selection</div>
                    <div class="form-group">
                        <label class="form-label">Product <span class="req">*</span></label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-batch="{{ $product->is_batch_managed ? '1' : '0' }}" data-unit="{{ $product->unit->short_name ?? 'PCS' }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="info-panel" id="infoPanel">
                        <div class="info-header">
                            <div class="info-icon" id="pIcon">P</div>
                            <div>
                                <div class="info-name" id="pName">-</div>
                                <div class="info-sku" id="pSku">-</div>
                            </div>
                        </div>
                        <div class="info-grid">
                            <div class="info-item"><div class="info-item-label">Base Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">📍 Location</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Warehouse <span class="req">*</span></label>
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
                    <div class="lot-box-title">📦 Lot Selection</div>
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
                    <div class="section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" id="qtyLabel">New Quantity <span class="req">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0" required>
                            <div class="form-hint">In base unit</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit</label>
                            <input type="text" id="unit_display" class="form-control" readonly>
                            <div class="form-hint">Adjustments in base unit</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Reason <span class="req">*</span></label>
                        <select name="reason" id="reason" required>
                            <option value="">Select reason...</option>
                            <option value="Physical Count">📋 Physical Count</option>
                            <option value="Damaged">💔 Damaged Goods</option>
                            <option value="Expired">⏰ Expired Products</option>
                            <option value="Lost">❓ Lost / Missing</option>
                            <option value="Theft">🚨 Theft / Shrinkage</option>
                            <option value="Data Correction">🔧 Data Correction</option>
                            <option value="Opening Stock">📦 Opening Stock</option>
                            <option value="Other">📝 Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Additional details..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">⚖️ Apply Adjustment</button>
                    <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var lots=[], currentStockVal=0, adjType='set', baseUnit='PCS';
var selProduct, selWh, selRack, selLot, selReason;

document.addEventListener('DOMContentLoaded', function() {
    selProduct = new TomSelect('#product_id', {plugins:['dropdown_input'], create:false, onChange:onProduct});
    selWh = new TomSelect('#warehouse_id', {plugins:['dropdown_input'], create:false, onChange:onWarehouse});
    selRack = new TomSelect('#rack_id', {plugins:['dropdown_input'], create:false, onChange:checkStock});
    selLot = new TomSelect('#lot_id', {plugins:['dropdown_input'], create:false, onChange:onLotChange});
    selReason = new TomSelect('#reason', {plugins:['dropdown_input'], create:false});
    
    var w = document.getElementById('warehouse_id').value;
    if(w) loadRacks(w);
});

function onProduct(v) {
    var o = document.querySelector('#product_id option[value="'+v+'"]');
    if(!o||!v) { document.getElementById('infoPanel').classList.remove('show'); document.getElementById('lotBox').classList.remove('show'); document.getElementById('stockPreview').classList.remove('show'); return; }
    baseUnit = o.dataset.unit || 'PCS';
    document.getElementById('unit_display').value = baseUnit;
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent = o.dataset.name.substring(0,2).toUpperCase();
    document.getElementById('pName').textContent = o.dataset.name;
    document.getElementById('pSku').textContent = 'SKU: ' + o.dataset.sku;
    document.getElementById('pUnit').textContent = baseUnit;
    if(o.dataset.batch==='1') document.getElementById('lotBox').classList.add('show');
    else document.getElementById('lotBox').classList.remove('show');
    checkStock();
}

function checkStock() {
    var p=document.getElementById('product_id').value, w=document.getElementById('warehouse_id').value, r=document.getElementById('rack_id').value, l=document.getElementById('lot_id').value;
    if(!p||!w) { document.getElementById('stockPreview').classList.remove('show'); return; }
    var url='{{ route("admin.inventory.stock.check") }}?product_id='+p+'&warehouse_id='+w;
    if(r) url+='&rack_id='+r; if(l) url+='&lot_id='+l;
    fetch(url).then(r=>r.json()).then(d=>{
        currentStockVal = parseFloat(d.base_stock||d.quantity)||0;
        document.getElementById('currentStock').textContent = currentStockVal;
        document.getElementById('stockUnit').textContent = d.base_unit||'PCS';
        document.getElementById('newStockUnit').textContent = d.base_unit||'PCS';
        document.getElementById('stockPreview').classList.add('show');
        var o = document.querySelector('#product_id option[value="'+p+'"]');
        if(d.is_batch_managed||(o&&o.dataset.batch==='1')) loadLots(p,w,r);
        updateNewStock();
    });
}

function loadLots(p,w,r) {
    var url='{{ url("admin/inventory/stock/product-lots") }}?product_id='+p;
    if(w) url+='&warehouse_id='+w; if(r) url+='&rack_id='+r;
    fetch(url).then(r=>r.json()).then(d=>{
        lots=d.lots||[];
        selLot.clear(); selLot.clearOptions();
        selLot.addOption({value:'', text:'Select lot...'});
        lots.forEach(l=>{ selLot.addOption({value:l.id, text:l.lot_no+(l.expiry_display?' (Exp: '+l.expiry_display+')':'')}); });
    });
}

function onLotChange(v) { checkStock(); }

function setAdjType(type) {
    adjType = type;
    document.querySelectorAll('.adj-type').forEach(t=>{ t.classList.remove('active'); if(t.querySelector('input').value===type) { t.classList.add('active'); t.querySelector('input').checked=true; } });
    var l = document.getElementById('qtyLabel');
    if(type==='set') l.innerHTML='New Quantity <span class="req">*</span>';
    else if(type==='add') l.innerHTML='Quantity to Add <span class="req">*</span>';
    else l.innerHTML='Quantity to Subtract <span class="req">*</span>';
    updateNewStock();
}

function updateNewStock() {
    var qty = parseFloat(document.getElementById('qty').value)||0;
    var newVal = 0;
    if(adjType==='set') newVal = qty;
    else if(adjType==='add') newVal = currentStockVal + qty;
    else newVal = currentStockVal - qty;
    document.getElementById('newStock').textContent = Math.max(0, newVal).toFixed(2);
}

function onWarehouse() { loadRacks(document.getElementById('warehouse_id').value); checkStock(); }
function loadRacks(wid) {
    selRack.clear(); selRack.clearOptions(); selRack.addOption({value:'',text:'Select rack...'});
    if(!wid) return;
    fetch('{{ url("admin/inventory/racks/by-warehouse") }}/'+wid).then(r=>r.json()).then(d=>{ d.forEach(r=>{ selRack.addOption({value:r.id,text:r.code+' - '+r.name}); }); });
}

document.getElementById('qty').addEventListener('input', updateNewStock);
</script>
</x-layouts.app>