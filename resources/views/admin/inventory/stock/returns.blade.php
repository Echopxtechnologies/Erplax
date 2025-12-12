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
    .stock-card-header { padding: 18px 24px; background: linear-gradient(135deg, #cffafe, #a5f3fc); border-bottom: 1px solid #67e8f9; }
    .stock-card-header h3 { margin: 0; font-size: 16px; font-weight: 600; color: #155e75; }
    .stock-card-body { padding: 28px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #e2e8f0; }
    .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .section-title { font-size: 13px; font-weight: 700; color: #0891b2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .req { color: #ef4444; }
    .form-control { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; background: #fff; box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8,145,178,0.12); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-hint { font-size: 12px; color: #64748b; margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 10px !important; border: 1px solid #e2e8f0 !important; min-height: 46px !important; }
    .ts-wrapper.focus .ts-control { border-color: #0891b2 !important; box-shadow: 0 0 0 3px rgba(8,145,178,0.12) !important; }
    .ts-dropdown { border-radius: 10px !important; box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important; margin-top: 4px !important; }
    .ts-dropdown .option { padding: 10px 14px !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #ecfeff !important; }
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid #e2e8f0 !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; }

    .info-panel { background: linear-gradient(135deg, #ecfeff, #cffafe); border: 1px solid #67e8f9; border-radius: 12px; padding: 20px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #67e8f9; }
    .info-icon { width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #0891b2, #0e7490); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
    .info-name { font-size: 17px; font-weight: 700; color: #155e75; }
    .info-sku { font-size: 12px; color: #0891b2; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; }
    .info-item { background: #fff; border-radius: 8px; padding: 10px 12px; }
    .info-item-label { font-size: 10px; color: #0891b2; text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 14px; font-weight: 700; color: #155e75; }

    .stock-display { background: #ecfeff; border: 1px solid #67e8f9; border-radius: 10px; padding: 14px 18px; margin-bottom: 18px; display: none; }
    .stock-display.show { display: flex; align-items: center; gap: 20px; }
    .stock-label { font-size: 12px; color: #155e75; }
    .stock-value { font-size: 22px; font-weight: 700; color: #0891b2; }
    
    .unit-hint { background: #cffafe; border: 1px solid #67e8f9; border-radius: 8px; padding: 10px 14px; margin-top: 8px; font-size: 13px; color: #155e75; display: none; }
    .unit-hint.show { display: block; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 18px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 14px; font-weight: 600; color: #92400e; margin-bottom: 14px; }

    .form-actions { display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid #e2e8f0; margin-top: 20px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #0891b2, #0e7490); color: #fff; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-secondary { background: #f1f5f9; color: #334155; }
    
    .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }
</style>

<div class="stock-page">
    <div class="stock-header">
        <a href="{{ route('admin.inventory.stock.movements') }}" class="back-link">←</a>
        <span class="icon">↩️</span>
        <h1>Stock Returns</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    <div class="stock-card">
        <div class="stock-card-header">
            <h3>↩️ Process Stock Return</h3>
        </div>
        <div class="stock-card-body">
            <form action="{{ route('admin.inventory.stock.returns.store') }}" method="POST" id="mainForm">
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
                            <div class="info-item"><div class="info-item-label">Current Stock</div><div class="info-item-value" id="pStock">0</div></div>
                            <div class="info-item"><div class="info-item-label">Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">📍 Return To Location</div>
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
                    <div class="stock-display" id="stockDisplay">
                        <div><div class="stock-label">Current Stock</div><div class="stock-value" id="availStock">0 PCS</div></div>
                    </div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Lot Selection (Optional)</div>
                    <div class="form-group" style="margin:0;">
                        <select name="lot_id" id="lot_id"><option value="">Select lot or leave empty...</option></select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Quantity <span class="req">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" required>
                            <div class="form-hint">Amount being returned</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit <span class="req">*</span></label>
                            <select name="unit_id" id="unit_id" required><option value="">Select unit...</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                            <div class="unit-hint" id="unitHint"></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Return Reason <span class="req">*</span></label>
                        <select name="reason" id="reason" required>
                            <option value="">Select reason...</option>
                            <option value="Customer Return">Customer Return</option>
                            <option value="Damaged on Delivery">Damaged on Delivery</option>
                            <option value="Wrong Item">Wrong Item Shipped</option>
                            <option value="Quality Issue">Quality Issue</option>
                            <option value="Excess Stock">Excess Stock Return</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Additional details..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">↩️ Process Return</button>
                    <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var pData={}, lots=[];
var selProduct, selWh, selRack, selUnit, selLot, selReason;

document.addEventListener('DOMContentLoaded', function() {
    selProduct = new TomSelect('#product_id', {plugins:['dropdown_input'], create:false, onChange:onProduct});
    selWh = new TomSelect('#warehouse_id', {plugins:['dropdown_input'], create:false, onChange:onWarehouse});
    selRack = new TomSelect('#rack_id', {plugins:['dropdown_input'], create:false, onChange:checkStock});
    selUnit = new TomSelect('#unit_id', {plugins:['dropdown_input'], create:false, onChange:updateUnit});
    selLot = new TomSelect('#lot_id', {plugins:['dropdown_input'], create:false});
    selReason = new TomSelect('#reason', {plugins:['dropdown_input'], create:false});
    
    var w = document.getElementById('warehouse_id').value;
    if(w) loadRacks(w);
});

function onProduct(v) {
    var o = document.querySelector('#product_id option[value="'+v+'"]');
    if(!o||!v) { document.getElementById('infoPanel').classList.remove('show'); document.getElementById('lotBox').classList.remove('show'); return; }
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent = o.dataset.name.substring(0,2).toUpperCase();
    document.getElementById('pName').textContent = o.dataset.name;
    document.getElementById('pSku').textContent = 'SKU: ' + o.dataset.sku;
    document.getElementById('pUnit').textContent = o.dataset.unit;
    if(o.dataset.batch==='1') document.getElementById('lotBox').classList.add('show');
    else document.getElementById('lotBox').classList.remove('show');
    loadUnits(v); checkStock();
}

function loadUnits(pid) {
    fetch('{{ url("admin/inventory/stock/product-units") }}?product_id='+pid).then(r=>r.json()).then(d=>{
        pData=d; selUnit.clear(); selUnit.clearOptions();
        (d.units||[]).forEach(u=>{ selUnit.addOption({value:u.id, text:u.name+(u.is_base?' (Base)':u.conversion_factor!=1?' (='+u.conversion_factor+' '+d.base_unit_name+')':''), conv:u.conversion_factor, isBase:u.is_base}); });
        selUnit.setValue(d.base_unit_id);
    });
}

function checkStock() {
    var p=document.getElementById('product_id').value, w=document.getElementById('warehouse_id').value, r=document.getElementById('rack_id').value;
    if(!p||!w) { document.getElementById('stockDisplay').classList.remove('show'); return; }
    var url='{{ route("admin.inventory.stock.check") }}?product_id='+p+'&warehouse_id='+w;
    if(r) url+='&rack_id='+r;
    fetch(url).then(r=>r.json()).then(d=>{
        document.getElementById('availStock').textContent = (d.base_stock||d.quantity||0) + ' ' + (d.base_unit||'PCS');
        document.getElementById('pStock').textContent = (d.base_stock||d.quantity||0) + ' ' + (d.base_unit||'PCS');
        document.getElementById('stockDisplay').classList.add('show');
        var o = document.querySelector('#product_id option[value="'+p+'"]');
        if(d.is_batch_managed||(o&&o.dataset.batch==='1')) loadLots(p,w,r);
    });
}

function loadLots(p,w,r) {
    var url='{{ url("admin/inventory/stock/product-lots") }}?product_id='+p;
    if(w) url+='&warehouse_id='+w; if(r) url+='&rack_id='+r;
    fetch(url).then(r=>r.json()).then(d=>{
        lots=d.lots||[];
        selLot.clear(); selLot.clearOptions();
        selLot.addOption({value:'', text:'Select lot or leave empty...'});
        lots.forEach(l=>{ selLot.addOption({value:l.id, text:l.lot_no+(l.expiry_display?' (Exp: '+l.expiry_display+')':'')}); });
    });
}

function updateUnit() {
    var uid=document.getElementById('unit_id').value, qty=parseFloat(document.getElementById('qty').value)||0, h=document.getElementById('unitHint');
    if(uid&&pData.units) {
        var u=pData.units.find(x=>x.id==uid);
        if(u&&pData.base_unit_name&&!u.is_base&&parseFloat(u.conversion_factor)!=1) {
            var c=parseFloat(u.conversion_factor)||1;
            if(qty>0) h.innerHTML='<b>'+qty+'</b> × '+c+' = <b>'+(qty*c).toFixed(2)+' '+pData.base_unit_name+'</b> will be added';
            else h.innerHTML='1 unit = '+c+' '+pData.base_unit_name;
            h.classList.add('show'); return;
        }
    }
    h.classList.remove('show');
}

function onWarehouse() { loadRacks(document.getElementById('warehouse_id').value); checkStock(); }
function loadRacks(wid) {
    selRack.clear(); selRack.clearOptions(); selRack.addOption({value:'',text:'Select rack...'});
    if(!wid) return;
    fetch('{{ url("admin/inventory/racks/by-warehouse") }}/'+wid).then(r=>r.json()).then(d=>{ d.forEach(r=>{ selRack.addOption({value:r.id,text:r.code+' - '+r.name}); }); });
}

document.getElementById('qty').addEventListener('input', updateUnit);
</script>
</x-layouts.app>