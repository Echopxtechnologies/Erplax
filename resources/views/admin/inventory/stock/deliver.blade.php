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
    .stock-card-header { padding: 18px 24px; background: linear-gradient(135deg, #ffedd5, #fed7aa); border-bottom: 1px solid #fdba74; }
    .stock-card-header h3 { margin: 0; font-size: 16px; font-weight: 600; color: #9a3412; }
    .stock-card-body { padding: 28px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #e2e8f0; }
    .form-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .section-title { font-size: 13px; font-weight: 700; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .req { color: #ef4444; }
    .form-control { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; background: #fff; box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.12); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-hint { font-size: 12px; color: #64748b; margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 10px !important; border: 1px solid #e2e8f0 !important; min-height: 46px !important; }
    .ts-wrapper.focus .ts-control { border-color: #ea580c !important; box-shadow: 0 0 0 3px rgba(234,88,12,0.12) !important; }
    .ts-dropdown { border-radius: 10px !important; box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important; margin-top: 4px !important; }
    .ts-dropdown .option { padding: 10px 14px !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #fff7ed !important; }
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid #e2e8f0 !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; }

    .info-panel { background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 1px solid #fdba74; border-radius: 12px; padding: 20px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #fdba74; }
    .info-icon { width: 50px; height: 50px; border-radius: 10px; background: linear-gradient(135deg, #ea580c, #c2410c); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
    .info-name { font-size: 17px; font-weight: 700; color: #9a3412; }
    .info-sku { font-size: 12px; color: #c2410c; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; }
    .info-item { background: #fff; border-radius: 8px; padding: 10px 12px; }
    .info-item-label { font-size: 10px; color: #c2410c; text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 14px; font-weight: 700; color: #9a3412; }
    .info-item-value.price { color: #ea580c; }
    .info-badge { display: inline-block; padding: 5px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; margin-top: 8px; }
    .info-badge.ok { background: #d1fae5; color: #065f46; }
    .info-badge.warn { background: #fef3c7; color: #92400e; }
    .info-badge.bad { background: #fee2e2; color: #991b1b; }

    .stock-display { background: #fff7ed; border: 1px solid #fdba74; border-radius: 10px; padding: 14px 18px; margin-bottom: 18px; display: none; }
    .stock-display.show { display: flex; align-items: center; gap: 20px; }
    .stock-label { font-size: 12px; color: #9a3412; }
    .stock-value { font-size: 22px; font-weight: 700; color: #ea580c; }
    .stock-value.low { color: #dc2626; }
    
    .unit-hint { background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; margin-top: 8px; font-size: 13px; color: #92400e; display: none; }
    .unit-hint.show { display: block; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 18px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 14px; font-weight: 600; color: #92400e; margin-bottom: 14px; }
    .lot-list { display: flex; flex-direction: column; gap: 8px; max-height: 200px; overflow-y: auto; }
    .lot-item { display: flex; align-items: center; gap: 12px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; background: #fff; cursor: pointer; }
    .lot-item:hover { border-color: #fbbf24; }
    .lot-item.selected { border-color: #f59e0b; background: #fef3c7; }
    .lot-item.expired { border-color: #fca5a5; background: #fef2f2; }
    .lot-item input { display: none; }
    .lot-info { flex: 1; }
    .lot-name { font-weight: 600; font-size: 13px; }
    .lot-meta { font-size: 11px; color: #64748b; margin-top: 3px; }
    .lot-qty { font-weight: 700; color: #059669; }
    .lot-exp { font-size: 10px; padding: 3px 8px; border-radius: 10px; font-weight: 600; }
    .lot-exp.ok { background: #d1fae5; color: #065f46; }
    .lot-exp.warn { background: #fed7aa; color: #9a3412; }
    .lot-exp.bad { background: #fecaca; color: #991b1b; }

    .form-actions { display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid #e2e8f0; margin-top: 20px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: linear-gradient(135deg, #ea580c, #c2410c); color: #fff; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-secondary { background: #f1f5f9; color: #334155; }
    
    .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }
</style>

<div class="stock-page">
    <div class="stock-header">
        <a href="{{ route('admin.inventory.stock.movements') }}" class="back-link">←</a>
        <span class="icon">📤</span>
        <h1>Deliver Stock</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    <div class="stock-card">
        <div class="stock-card-header">
            <h3>📤 Stock Out - Deliver Goods</h3>
        </div>
        <div class="stock-card-body">
            <form action="{{ route('admin.inventory.stock.deliver.store') }}" method="POST" id="mainForm">
                @csrf
                <input type="hidden" name="lot_id" id="lot_id" value="">

                <div class="form-section">
                    <div class="section-title">📦 Product Selection</div>
                    <div class="form-group">
                        <label class="form-label">Product <span class="req">*</span></label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-batch="{{ $product->is_batch_managed ? '1' : '0' }}" data-unit="{{ $product->unit->short_name ?? 'PCS' }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-price="{{ $product->sale_price ?? 0 }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
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
                            <div class="info-item"><div class="info-item-label">Stock</div><div class="info-item-value" id="pStock">0</div></div>
                            <div class="info-item"><div class="info-item-label">Price</div><div class="info-item-value price" id="pPrice">₹0</div></div>
                            <div class="info-item"><div class="info-item-label">Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">📍 Source Location</div>
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
                        <div><div class="stock-label">Available</div><div class="stock-value" id="availStock">0 PCS</div></div>
                    </div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Select Lot (FEFO)</div>
                    <div class="lot-list" id="lotList"><div style="color:#64748b;padding:12px;">Loading...</div></div>
                </div>

                <div class="form-section">
                    <div class="section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Quantity <span class="req">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" required>
                            <div class="form-hint" id="qtyHint">Max: 0</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unit <span class="req">*</span></label>
                            <select name="unit_id" id="unit_id" required><option value="">Select unit...</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                            <div class="unit-hint" id="unitHint"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference Type</label>
                        <select name="reference_type" id="ref_type"><option value="SALE">Sale</option><option value="ADJUSTMENT">Adjustment</option></select>
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
                    <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var pData={}, lots=[], selLot=null, baseStock=0;
var selProduct, selWh, selRack, selUnit;

document.addEventListener('DOMContentLoaded', function() {
    selProduct = new TomSelect('#product_id', {plugins:['dropdown_input'], create:false, onChange:onProduct});
    selWh = new TomSelect('#warehouse_id', {plugins:['dropdown_input'], create:false, onChange:onWarehouse});
    selRack = new TomSelect('#rack_id', {plugins:['dropdown_input'], create:false, onChange:checkStock});
    selUnit = new TomSelect('#unit_id', {plugins:['dropdown_input'], create:false, onChange:updateUnit});
    new TomSelect('#ref_type', {create:false});
    
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
    document.getElementById('pPrice').textContent = '₹' + parseFloat(o.dataset.price||0).toFixed(2);
    document.getElementById('lotBadge').innerHTML = '';
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
    var p=document.getElementById('product_id').value, w=document.getElementById('warehouse_id').value, r=document.getElementById('rack_id').value, l=document.getElementById('lot_id').value;
    if(!p||!w) { document.getElementById('stockDisplay').classList.remove('show'); return; }
    var url='{{ route("admin.inventory.stock.check") }}?product_id='+p+'&warehouse_id='+w;
    if(r) url+='&rack_id='+r; if(l) url+='&lot_id='+l;
    fetch(url).then(r=>r.json()).then(d=>{
        baseStock = parseFloat(d.base_stock||d.quantity)||0;
        document.getElementById('availStock').textContent = baseStock + ' ' + (d.base_unit||'PCS');
        document.getElementById('pStock').textContent = baseStock + ' ' + (d.base_unit||'PCS');
        document.getElementById('stockDisplay').classList.add('show');
        var o = document.querySelector('#product_id option[value="'+p+'"]');
        if(d.is_batch_managed||(o&&o.dataset.batch==='1')) loadLots(p,w,r);
        updateUnit();
    });
}

function loadLots(p,w,r) {
    var url='{{ url("admin/inventory/stock/product-lots") }}?product_id='+p;
    if(w) url+='&warehouse_id='+w; if(r) url+='&rack_id='+r;
    fetch(url).then(r=>r.json()).then(d=>{ lots=d.lots||[]; renderLots(); });
}

function renderLots() {
    var c=document.getElementById('lotList');
    if(!lots.length) { c.innerHTML='<div style="color:#64748b;padding:12px;">No lots</div>'; return; }
    var h='';
    lots.forEach((lot,i)=>{
        var ec=lot.expiry_status==='expired'?'bad':lot.expiry_status==='expiring_soon'?'warn':'ok';
        var ic=lot.expiry_status==='expired'?'expired':'';
        h+='<label class="lot-item '+ic+'" onclick="pickLot('+lot.id+')"><input type="radio" name="ls" value="'+lot.id+'" '+(i===0?'checked':'')+'><div class="lot-info"><div class="lot-name">'+lot.lot_no+'</div><div class="lot-meta"><span class="lot-exp '+ec+'">'+(lot.expiry_display?'Exp: '+lot.expiry_display:'No expiry')+'</span></div></div><div class="lot-qty">'+(lot.stock_display||lot.stock)+'</div></label>';
    });
    c.innerHTML=h;
    if(lots.length) pickLot(lots[0].id);
}

function pickLot(id) {
    document.getElementById('lot_id').value=id;
    selLot=lots.find(l=>l.id==id);
    document.querySelectorAll('.lot-item').forEach(i=>{ i.classList.remove('selected'); if(i.querySelector('input').value==id) i.classList.add('selected'); });
    if(selLot) {
        var bc=selLot.expiry_status==='expired'?'bad':selLot.expiry_status==='expiring_soon'?'warn':'ok';
        document.getElementById('lotBadge').innerHTML='<span class="info-badge '+bc+'">'+selLot.lot_no+'</span>';
    }
    checkStock();
}

function updateUnit() {
    var uid=document.getElementById('unit_id').value, qty=parseFloat(document.getElementById('qty').value)||0, h=document.getElementById('unitHint');
    if(uid&&pData.units) {
        var u=pData.units.find(x=>x.id==uid);
        if(u&&pData.base_unit_name) {
            var c=parseFloat(u.conversion_factor)||1;
            document.getElementById('qtyHint').textContent='Max: '+(baseStock/c).toFixed(2);
            if(!u.is_base&&c!=1) {
                if(qty>0) h.innerHTML='<b>'+qty+'</b> × '+c+' = <b>'+(qty*c).toFixed(2)+' '+pData.base_unit_name+'</b>';
                else h.innerHTML='1 unit = '+c+' '+pData.base_unit_name;
                h.classList.add('show'); return;
            }
        }
    }
    document.getElementById('qtyHint').textContent='Max: '+baseStock;
    h.classList.remove('show');
}

function onWarehouse() { loadRacks(document.getElementById('warehouse_id').value); checkStock(); }
function loadRacks(wid) {
    selRack.clear(); selRack.clearOptions(); selRack.addOption({value:'',text:'Select rack...'});
    if(!wid) return;
    fetch('{{ url("admin/inventory/racks/by-warehouse") }}/'+wid).then(r=>r.json()).then(d=>{ d.forEach(r=>{ selRack.addOption({value:r.id,text:r.code+' - '+r.name}); }); });
}

document.getElementById('qty').addEventListener('input', updateUnit);
document.getElementById('mainForm').addEventListener('submit', function(e) {
    var qty=parseFloat(document.getElementById('qty').value)||0, c=1;
    if(pData.units) { var u=pData.units.find(x=>x.id==document.getElementById('unit_id').value); if(u) c=parseFloat(u.conversion_factor)||1; }
    if(qty*c>baseStock) { e.preventDefault(); alert('Insufficient stock! Need: '+(qty*c).toFixed(2)+', Have: '+baseStock); }
});
</script>
</x-layouts.app>