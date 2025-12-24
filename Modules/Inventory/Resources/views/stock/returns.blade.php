

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-container { padding: 20px; max-width: 800px; margin: 0 auto; }
    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .back-btn svg { width: 20px; height: 20px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #0891b2; }
    
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: linear-gradient(135deg, #cffafe, #a5f3fc); border-radius: 12px 12px 0 0; }
    .form-card-title { font-size: 16px; font-weight: 600; color: #0e7490; margin: 0; }
    .form-card-body { padding: 24px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--card-border); }
    .form-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
    .form-section-title { font-size: 13px; font-weight: 600; color: #0891b2; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 20px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.15); }
    .form-control::placeholder { color: var(--text-muted); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-help { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 8px !important; border: 1px solid var(--card-border) !important; min-height: 44px !important; background: var(--card-bg) !important; color: var(--text-primary) !important; }
    .ts-wrapper.focus .ts-control { border-color: #0891b2 !important; box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.15) !important; }
    .ts-dropdown { border-radius: 8px !important; border: 1px solid var(--card-border) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important; background: var(--card-bg) !important; }
    .ts-dropdown .option { padding: 10px 14px !important; color: var(--text-primary) !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #cffafe !important; color: #0e7490 !important; }
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid var(--card-border) !important; background: var(--card-bg) !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px !important; border: 1px solid var(--card-border) !important; border-radius: 6px !important; background: var(--card-bg) !important; color: var(--text-primary) !important; }

    .info-panel { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--card-border); }
    .info-icon { width: 44px; height: 44px; border-radius: 8px; background: #0891b2; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .info-name { font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .info-sku { font-size: 12px; color: #0891b2; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 10px; }
    .info-item { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 10px; }
    .info-item-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .info-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 6px; background: #cffafe; color: #0e7490; }

    .stock-display { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: none; }
    .stock-display.show { display: flex; align-items: center; gap: 16px; }
    .stock-label { font-size: 12px; color: var(--text-muted); }
    .stock-value { font-size: 20px; font-weight: 700; color: #0891b2; }
    
    .unit-hint { background: #cffafe; border: 1px solid #67e8f9; border-radius: 6px; padding: 10px 12px; margin-top: 8px; font-size: 12px; color: #0e7490; display: none; }
    .unit-hint.show { display: block; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 13px; font-weight: 600; color: #92400e; margin-bottom: 12px; }

    .variation-box { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 16px; }
    .variation-box .form-group { margin: 0; }

    .form-actions { display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--card-border); margin-top: 24px; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
    .btn-primary { background: linear-gradient(135deg, #0891b2, #0e7490); color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3); }
    .btn-secondary { background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--card-border); }
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('inventory.stock.movements') }}" class="back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
        <h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg> Stock Returns</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    {{-- Barcode Scanner --}}
    @include('inventory::partials.barcode-scanner', ['color' => 'orange'])

    <div class="form-card">
        <div class="form-card-header"><h3 class="form-card-title">↩️ Process Stock Return</h3></div>
        <div class="form-card-body">
            <form action="{{ route('inventory.stock.returns.store') }}" method="POST" id="mainForm">
                @csrf

                <div class="form-section">
                    <div class="form-section-title">📦 Product Selection</div>
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-batch="{{ $product->is_batch_managed ? '1' : '0' }}" data-unit="{{ $product->unit->short_name ?? 'PCS' }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-has-variants="{{ $product->has_variants ? '1' : '0' }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Variation Selector -->
                    <div class="variation-box" id="variationBox" style="display:none;">
                        <div class="form-group">
                            <label class="form-label">Variation <span class="required">*</span></label>
                            <select name="variation_id" id="variation_id">
                                <option value="">Select variation...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="info-panel" id="infoPanel">
                        <div class="info-header">
                            <div class="info-icon" id="pIcon">P</div>
                            <div><div class="info-name" id="pName">-</div><div class="info-sku" id="pSku">-</div><div id="lotBadge"></div></div>
                        </div>
                        <div class="info-grid">
                            <div class="info-item"><div class="info-item-label">Current Stock</div><div class="info-item-value" id="pStock">0</div></div>
                            <div class="info-item"><div class="info-item-label">Base Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">📍 Return To Location</div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Warehouse <span class="required">*</span></label><select name="warehouse_id" id="warehouse_id" required><option value="">Select warehouse...</option>@foreach($warehouses as $wh)<option value="{{ $wh->id }}" {{ $wh->is_default ? 'selected' : '' }}>{{ $wh->name }}</option>@endforeach</select></div>
                        <div class="form-group"><label class="form-label">Rack</label><select name="rack_id" id="rack_id"><option value="">Select rack...</option></select></div>
                    </div>
                    <div class="stock-display" id="stockDisplay"><div><div class="stock-label">Current Stock</div><div class="stock-value" id="availStock">0 PCS</div></div></div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Lot Selection (Optional)</div>
                    <div class="form-group" style="margin:0;"><select name="lot_id" id="lot_id"><option value="">Select lot or leave empty...</option></select></div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Quantity <span class="required">*</span></label><input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" required><div class="form-help">Amount being returned</div></div>
                        <div class="form-group"><label class="form-label">Unit <span class="required">*</span></label><select name="unit_id" id="unit_id" required><option value="">Select unit...</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select><div class="unit-hint" id="unitHint"></div></div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Return Reason <span class="required">*</span></label>
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
                    <div class="form-group"><label class="form-label">Notes</label><textarea name="notes" class="form-control" placeholder="Additional details..."></textarea></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">↩️ Process Return</button>
                    <a href="{{ route('inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var pData={},lots=[],variations=[];
var selProduct,selWh,selRack,selUnit,selLot,selReason,selVariation;

document.addEventListener('DOMContentLoaded',function(){
    selProduct=new TomSelect('#product_id',{plugins:['dropdown_input'],create:false,onChange:onProduct});
    selWh=new TomSelect('#warehouse_id',{plugins:['dropdown_input'],create:false,onChange:onWarehouse});
    selRack=new TomSelect('#rack_id',{plugins:['dropdown_input'],create:false,onChange:checkStock});
    selUnit=new TomSelect('#unit_id',{plugins:['dropdown_input'],create:false,onChange:updateUnit});
    selLot=new TomSelect('#lot_id',{plugins:['dropdown_input'],create:false,onChange:onLotChange});
    selReason=new TomSelect('#reason',{plugins:['dropdown_input'],create:false});
    selVariation=new TomSelect('#variation_id',{plugins:['dropdown_input'],create:false,onChange:onVariation});
    var w=document.getElementById('warehouse_id').value;if(w)loadRacks(w);
});

function onProduct(v){
    var o=document.querySelector('#product_id option[value="'+v+'"]');
    if(!o||!v){document.getElementById('infoPanel').classList.remove('show');document.getElementById('lotBox').classList.remove('show');document.getElementById('variationBox').style.display='none';return;}
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent=o.dataset.name.substring(0,2).toUpperCase();
    document.getElementById('pName').textContent=o.dataset.name;
    document.getElementById('pSku').textContent='SKU: '+o.dataset.sku;
    document.getElementById('pUnit').textContent=o.dataset.unit;
    document.getElementById('lotBadge').innerHTML='';
    
    // Handle variations
    if(o.dataset.hasVariants==='1'){
        document.getElementById('variationBox').style.display='block';
        document.getElementById('variation_id').required=true;
        loadVariations(v);
    } else {
        document.getElementById('variationBox').style.display='none';
        document.getElementById('variation_id').required=false;
        selVariation.clear();selVariation.clearOptions();
    }
    
    if(o.dataset.batch==='1'){document.getElementById('lotBox').classList.add('show');loadLots(v);}else document.getElementById('lotBox').classList.remove('show');
    loadUnits(v);checkStock();
}

function loadVariations(productId){
    var wh=document.getElementById('warehouse_id').value;
    fetch('{{ url("admin/inventory/stock/product-variations") }}?product_id='+productId+(wh?'&warehouse_id='+wh:''))
        .then(r=>r.json()).then(d=>{
            variations=d.variations||d||[];
            selVariation.clear();selVariation.clearOptions();
            selVariation.addOption({value:'',text:'Select variation...'});
            variations.forEach(v=>{
                var label=v.variation_name||v.sku;
                if(v.current_stock!==undefined)label+=' (Stock: '+v.current_stock+')';
                selVariation.addOption({value:String(v.id),text:label});
            });
        });
}

function onVariation(v){
    if(!v)return;
    var variation=variations.find(x=>String(x.id)==String(v));
    if(variation){
        document.getElementById('pSku').textContent='SKU: '+variation.sku;
    }
    checkStock();
}

function loadUnits(pid){fetch('{{ url("admin/inventory/stock/product-units") }}?product_id='+pid).then(r=>r.json()).then(d=>{pData=d;selUnit.clear();selUnit.clearOptions();(d.units||[]).forEach(u=>{selUnit.addOption({value:u.id,text:u.name+(u.is_base?' (Base)':u.conversion_factor!=1?' (='+u.conversion_factor+' '+d.base_unit_name+')':'')});});selUnit.setValue(d.base_unit_id);});}

function checkStock(){
    var p=document.getElementById('product_id').value,w=document.getElementById('warehouse_id').value,r=document.getElementById('rack_id').value,v=document.getElementById('variation_id').value;
    if(!p||!w){document.getElementById('stockDisplay').classList.remove('show');return;}
    var url='{{ route("inventory.stock.check") }}?product_id='+p+'&warehouse_id='+w;if(r)url+='&rack_id='+r;if(v)url+='&variation_id='+v;
    fetch(url).then(r=>r.json()).then(d=>{
        document.getElementById('availStock').textContent=(d.base_stock||d.quantity||0)+' '+(d.base_unit||'PCS');
        document.getElementById('pStock').textContent=(d.base_stock||d.quantity||0)+' '+(d.base_unit||'PCS');
        document.getElementById('stockDisplay').classList.add('show');
    });
}

function loadLots(productId){fetch('{{ url("admin/inventory/stock/product-lots") }}?product_id='+productId).then(r=>r.json()).then(d=>{lots=Array.isArray(d)?d:(d.lots||d.data||[]);selLot.clear();selLot.clearOptions();selLot.addOption({value:'',text:'Select lot or leave empty...'});lots.forEach(l=>{selLot.addOption({value:l.id,text:l.lot_no+(l.expiry_date?' (Exp: '+l.expiry_date+')':'')});});});}

function onLotChange(v){var lot=lots.find(l=>l.id==v);if(lot)document.getElementById('lotBadge').innerHTML='<span class="info-badge">'+lot.lot_no+'</span>';else document.getElementById('lotBadge').innerHTML='';}

function updateUnit(){
    var uid=document.getElementById('unit_id').value,qty=parseFloat(document.getElementById('qty').value)||0,h=document.getElementById('unitHint');
    if(uid&&pData.units){var u=pData.units.find(x=>x.id==uid);if(u&&pData.base_unit_name&&!u.is_base&&parseFloat(u.conversion_factor)!=1){var c=parseFloat(u.conversion_factor)||1;h.innerHTML=qty>0?'<b>'+qty+'</b> × '+c+' = <b>'+(qty*c).toFixed(2)+' '+pData.base_unit_name+'</b> will be added':'1 unit = '+c+' '+pData.base_unit_name;h.classList.add('show');return;}}
    h.classList.remove('show');
}

function onWarehouse(){loadRacks(document.getElementById('warehouse_id').value);checkStock();}
function loadRacks(wid){selRack.clear();selRack.clearOptions();selRack.addOption({value:'',text:'Select rack...'});if(!wid)return;fetch('{{ url("admin/inventory/racks/by-warehouse") }}/'+wid).then(r=>r.json()).then(d=>{d.forEach(r=>{selRack.addOption({value:r.id,text:r.code+' - '+r.name});});});}
document.getElementById('qty').addEventListener('input',updateUnit);
</script>
