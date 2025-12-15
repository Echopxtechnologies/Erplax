<x-layouts.app>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-container { padding: 20px; max-width: 800px; margin: 0 auto; }
    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-muted); text-decoration: none; transition: all 0.2s; flex-shrink: 0; }
    .back-btn:hover { background: var(--body-bg); color: var(--text-primary); }
    .back-btn svg { width: 20px; height: 20px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #059669; }
    
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius: 12px 12px 0 0; }
    .form-card-title { font-size: 16px; font-weight: 600; color: #065f46; margin: 0; display: flex; align-items: center; gap: 8px; }
    .form-card-body { padding: 24px; }
    
    .form-section { margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid var(--card-border); }
    .form-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
    .form-section-title { font-size: 13px; font-weight: 600; color: #059669; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 20px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15); }
    .form-control::placeholder { color: var(--text-muted); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-help { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

    .ts-wrapper { width: 100%; }
    .ts-control { padding: 10px 14px !important; border-radius: 8px !important; border: 1px solid var(--card-border) !important; min-height: 44px !important; background: var(--card-bg) !important; color: var(--text-primary) !important; }
    .ts-wrapper.focus .ts-control { border-color: #059669 !important; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important; }
    .ts-dropdown { border-radius: 8px !important; border: 1px solid var(--card-border) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important; margin-top: 4px !important; background: var(--card-bg) !important; }
    .ts-dropdown .option { padding: 10px 14px !important; color: var(--text-primary) !important; }
    .ts-dropdown .option:hover, .ts-dropdown .option.active { background: #d1fae5 !important; color: #065f46 !important; }
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid var(--card-border) !important; background: var(--card-bg) !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px !important; border: 1px solid var(--card-border) !important; border-radius: 6px !important; background: var(--card-bg) !important; color: var(--text-primary) !important; }

    .info-panel { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .info-panel.show { display: block; }
    .info-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--card-border); }
    .info-icon { width: 44px; height: 44px; border-radius: 8px; background: #059669; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .info-name { font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .info-sku { font-size: 12px; color: #059669; margin-top: 2px; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 10px; }
    .info-item { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 10px; }
    .info-item-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 3px; }
    .info-item-value { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .info-item-value.price { color: #059669; }
    .info-item-sub { font-size: 10px; color: var(--text-muted); margin-top: 2px; }
    .info-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 6px; }
    .info-badge.ok { background: #d1fae5; color: #065f46; }
    .info-badge.warn { background: #fef3c7; color: #92400e; }
    .info-badge.bad { background: #fee2e2; color: #991b1b; }

    .stock-display { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: none; }
    .stock-display.show { display: flex; align-items: center; gap: 16px; }
    .stock-label { font-size: 12px; color: var(--text-muted); }
    .stock-value { font-size: 20px; font-weight: 700; color: #059669; }
    
    .unit-hint { background: #d1fae5; border: 1px solid #a7f3d0; border-radius: 6px; padding: 10px 12px; margin-top: 8px; font-size: 12px; color: #065f46; display: none; }
    .unit-hint.show { display: block; }

    .lot-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 20px; display: none; }
    .lot-box.show { display: block; }
    .lot-box-title { font-size: 13px; font-weight: 600; color: #92400e; margin-bottom: 12px; }
    .lot-toggle { display: flex; gap: 10px; margin-bottom: 14px; }
    .lot-toggle-btn { flex: 1; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; background: #fff; cursor: pointer; text-align: center; font-weight: 600; font-size: 12px; color: #374151; }
    .lot-toggle-btn:hover { border-color: #fbbf24; }
    .lot-toggle-btn.active { border-color: #f59e0b; background: #fef3c7; color: #92400e; }
    .new-lot-fields { display: none; }
    .new-lot-fields.show { display: block; }
    .existing-lot-field { display: none; }
    .existing-lot-field.show { display: block; }

    .form-actions { display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--card-border); margin-top: 24px; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
    .btn-primary { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); }
    .btn-secondary { background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--card-border); }
    .btn-secondary:hover { background: var(--card-border); }
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="page-container">
    <div class="page-header">
        <a href="{{ route('inventory.stock.movements') }}" class="back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
        <h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Receive Stock</h1>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

    <div class="form-card">
        <div class="form-card-header"><h3 class="form-card-title">📥 Stock In - Receive Goods</h3></div>
        <div class="form-card-body">
            <form action="{{ route('inventory.stock.receive.store') }}" method="POST" id="mainForm">
                @csrf
                <div class="form-section">
                    <div class="form-section-title">📦 Product Selection</div>
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-batch="{{ $product->is_batch_managed ? '1' : '0' }}" data-unit="{{ $product->unit->short_name ?? 'PCS' }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}" data-purchase="{{ $product->purchase_price ?? 0 }}" data-sale="{{ $product->sale_price ?? 0 }}" data-mrp="{{ $product->mrp ?? 0 }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="info-panel" id="infoPanel">
                        <div class="info-header">
                            <div class="info-icon" id="pIcon">P</div>
                            <div><div class="info-name" id="pName">-</div><div class="info-sku" id="pSku">-</div><div id="lotBadge"></div></div>
                        </div>
                        <div class="info-grid">
                            <div class="info-item"><div class="info-item-label">Base Unit</div><div class="info-item-value" id="pUnit">PCS</div></div>
                            <div class="info-item"><div class="info-item-label">Purchase Price</div><div class="info-item-value price" id="pPurchase">₹0</div><div class="info-item-sub" id="pPurchaseSource">Product default</div></div>
                            <div class="info-item"><div class="info-item-label">Sale Price</div><div class="info-item-value price" id="pSale">₹0</div><div class="info-item-sub" id="pSaleSource">Product default</div></div>
                            <div class="info-item"><div class="info-item-label">MRP</div><div class="info-item-value price" id="pMrp">₹0</div></div>
                            <div class="info-item" id="mfgItem" style="display:none;"><div class="info-item-label">Mfg Date</div><div class="info-item-value" id="pMfg">-</div></div>
                            <div class="info-item" id="expItem" style="display:none;"><div class="info-item-label">Expiry</div><div class="info-item-value" id="pExp">-</div><div class="info-item-sub" id="pExpDays"></div></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">📍 Destination Location</div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Warehouse <span class="required">*</span></label><select name="warehouse_id" id="warehouse_id" required><option value="">Select warehouse...</option>@foreach($warehouses as $wh)<option value="{{ $wh->id }}" {{ $wh->is_default ? 'selected' : '' }}>{{ $wh->name }}</option>@endforeach</select></div>
                        <div class="form-group"><label class="form-label">Rack</label><select name="rack_id" id="rack_id"><option value="">Select rack...</option></select></div>
                    </div>
                    <div class="stock-display" id="stockDisplay"><div><div class="stock-label">Current Stock</div><div class="stock-value" id="currentStock">0 PCS</div></div></div>
                </div>

                <div class="lot-box" id="lotBox">
                    <div class="lot-box-title">📦 Lot / Batch Information</div>
                    <div class="lot-toggle">
                        <div class="lot-toggle-btn active" id="toggleNew" onclick="setLotMode('new')">➕ Create New Lot</div>
                        <div class="lot-toggle-btn" id="toggleExisting" onclick="setLotMode('existing')">📋 Select Existing</div>
                    </div>
                    <div class="new-lot-fields show" id="newLotFields">
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Lot Number <span class="required">*</span></label><input type="text" name="lot_no" id="lot_no" class="form-control" placeholder="e.g., LOT-2024-001"></div>
                            <div class="form-group"><label class="form-label">Batch Number</label><input type="text" name="batch_no" class="form-control" placeholder="Optional"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Mfg Date</label><input type="date" name="manufacturing_date" class="form-control"></div>
                            <div class="form-group"><label class="form-label">Expiry Date</label><input type="date" name="expiry_date" class="form-control"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Lot Purchase Price</label><input type="number" name="lot_purchase_price" class="form-control" step="0.01" min="0" placeholder="Leave empty for product default"></div>
                            <div class="form-group"><label class="form-label">Lot Sale Price</label><input type="number" name="lot_sale_price" class="form-control" step="0.01" min="0" placeholder="Leave empty for product default"></div>
                        </div>
                    </div>
                    <div class="existing-lot-field" id="existingLotField">
                        <div class="form-group" style="margin:0;"><label class="form-label">Select Existing Lot</label><select name="lot_id" id="lot_id"><option value="">Select lot...</option></select></div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">🔢 Quantity</div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Quantity <span class="required">*</span></label><input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" required></div>
                        <div class="form-group"><label class="form-label">Unit <span class="required">*</span></label><select name="unit_id" id="unit_id" required><option value="">Select unit...</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select><div class="unit-hint" id="unitHint"></div></div>
                    </div>
                    <div class="form-group"><label class="form-label">Reference Type</label><select name="reference_type" id="ref_type"><option value="PURCHASE">Purchase</option><option value="OPENING">Opening Stock</option></select></div>
                </div>

                <div class="form-section">
                    <div class="form-group"><label class="form-label">Reason</label><input type="text" name="reason" class="form-control" value="Stock received"></div>
                    <div class="form-group"><label class="form-label">Notes</label><textarea name="notes" class="form-control"></textarea></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">📥 Receive Stock</button>
                    <a href="{{ route('inventory.stock.movements') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
var pData={},lots=[],selectedLot=null,lotMode='new',productDefaults={purchase:0,sale:0,mrp:0};
var selProduct,selWh,selRack,selUnit,selLot;

document.addEventListener('DOMContentLoaded',function(){
    selProduct=new TomSelect('#product_id',{plugins:['dropdown_input'],create:false,onChange:onProduct});
    selWh=new TomSelect('#warehouse_id',{plugins:['dropdown_input'],create:false,onChange:onWarehouse});
    selRack=new TomSelect('#rack_id',{plugins:['dropdown_input'],create:false,onChange:checkStock});
    selUnit=new TomSelect('#unit_id',{plugins:['dropdown_input'],create:false,onChange:updateUnit});
    selLot=new TomSelect('#lot_id',{plugins:['dropdown_input'],create:false,onChange:onLotChange});
    new TomSelect('#ref_type',{create:false});
    var w=document.getElementById('warehouse_id').value;if(w)loadRacks(w);
});

function onProduct(v){
    var o=document.querySelector('#product_id option[value="'+v+'"]');
    if(!o||!v){document.getElementById('infoPanel').classList.remove('show');document.getElementById('lotBox').classList.remove('show');return;}
    productDefaults={purchase:parseFloat(o.dataset.purchase)||0,sale:parseFloat(o.dataset.sale)||0,mrp:parseFloat(o.dataset.mrp)||0};
    document.getElementById('infoPanel').classList.add('show');
    document.getElementById('pIcon').textContent=o.dataset.name.substring(0,2).toUpperCase();
    document.getElementById('pName').textContent=o.dataset.name;
    document.getElementById('pSku').textContent='SKU: '+o.dataset.sku;
    document.getElementById('pUnit').textContent=o.dataset.unit;
    resetToProductPrices();
    if(o.dataset.batch==='1'){document.getElementById('lotBox').classList.add('show');loadLots(v);}else{document.getElementById('lotBox').classList.remove('show');}
    loadUnits(v);checkStock();
}

function resetToProductPrices(){
    document.getElementById('pPurchase').textContent='₹'+productDefaults.purchase.toFixed(2);
    document.getElementById('pSale').textContent='₹'+productDefaults.sale.toFixed(2);
    document.getElementById('pMrp').textContent='₹'+productDefaults.mrp.toFixed(2);
    document.getElementById('pPurchaseSource').textContent='Product default';
    document.getElementById('pSaleSource').textContent='Product default';
    document.getElementById('mfgItem').style.display='none';
    document.getElementById('expItem').style.display='none';
    document.getElementById('lotBadge').innerHTML='';
}

function showLotPrices(lot){
    var pp=(lot.purchase_price!==null&&lot.purchase_price!=='')?parseFloat(lot.purchase_price):productDefaults.purchase;
    var sp=(lot.sale_price!==null&&lot.sale_price!=='')?parseFloat(lot.sale_price):productDefaults.sale;
    document.getElementById('pPurchase').textContent='₹'+pp.toFixed(2);
    document.getElementById('pSale').textContent='₹'+sp.toFixed(2);
    document.getElementById('pPurchaseSource').textContent=(lot.purchase_price!==null&&lot.purchase_price!=='')?'✓ From lot':'Product default';
    document.getElementById('pSaleSource').textContent=(lot.sale_price!==null&&lot.sale_price!=='')?'✓ From lot':'Product default';
    if(lot.manufacturing_date){document.getElementById('mfgItem').style.display='block';document.getElementById('pMfg').textContent=lot.manufacturing_date;}else{document.getElementById('mfgItem').style.display='none';}
    if(lot.expiry_date){document.getElementById('expItem').style.display='block';document.getElementById('pExp').textContent=lot.expiry_date;var today=new Date(),exp=new Date(lot.expiry_date),diff=Math.ceil((exp-today)/(1000*60*60*24));document.getElementById('pExpDays').textContent=diff>0?diff+' days left':'EXPIRED';}else{document.getElementById('expItem').style.display='none';}
    var bc=(lot.status==='EXPIRED')?'bad':'ok';if(lot.expiry_date){var d=Math.ceil((new Date(lot.expiry_date)-new Date())/(1000*60*60*24));if(d<=0)bc='bad';else if(d<=30)bc='warn';}
    document.getElementById('lotBadge').innerHTML='<span class="info-badge '+bc+'">'+lot.lot_no+'</span>';
}

function loadUnits(pid){fetch('{{ url("admin/inventory/stock/product-units") }}?product_id='+pid).then(r=>r.json()).then(d=>{pData=d;selUnit.clear();selUnit.clearOptions();(d.units||[]).forEach(u=>{selUnit.addOption({value:u.id,text:u.name+(u.is_base?' (Base)':u.conversion_factor!=1?' (='+u.conversion_factor+' '+d.base_unit_name+')':'')});});selUnit.setValue(d.base_unit_id);});}

function loadLots(productId){fetch('{{ url("admin/inventory/stock/product-lots") }}?product_id='+productId).then(r=>r.json()).then(d=>{lots=Array.isArray(d)?d:(d.lots||d.data||[]);selLot.clear();selLot.clearOptions();selLot.addOption({value:'',text:'Select existing lot...'});lots.forEach(l=>{var label=l.lot_no+(l.batch_no?' / '+l.batch_no:'')+(l.expiry_date?' (Exp: '+l.expiry_date+')':'');if(l.purchase_price)label+=' - ₹'+parseFloat(l.purchase_price).toFixed(2);selLot.addOption({value:l.id,text:label});});});}

function onLotChange(v){if(!v||v===''){selectedLot=null;resetToProductPrices();return;}selectedLot=lots.find(l=>l.id==v);if(selectedLot)showLotPrices(selectedLot);}

function setLotMode(mode){lotMode=mode;document.getElementById('toggleNew').classList.toggle('active',mode==='new');document.getElementById('toggleExisting').classList.toggle('active',mode==='existing');document.getElementById('newLotFields').classList.toggle('show',mode==='new');document.getElementById('existingLotField').classList.toggle('show',mode==='existing');if(mode==='new'){selLot.clear();selectedLot=null;resetToProductPrices();document.getElementById('lot_no').required=true;}else{document.getElementById('lot_no').required=false;document.getElementById('lot_no').value='';}}

function checkStock(){var p=document.getElementById('product_id').value,w=document.getElementById('warehouse_id').value,r=document.getElementById('rack_id').value;if(!p||!w){document.getElementById('stockDisplay').classList.remove('show');return;}fetch('{{ route("inventory.stock.check") }}?product_id='+p+'&warehouse_id='+w+(r?'&rack_id='+r:'')).then(r=>r.json()).then(d=>{document.getElementById('currentStock').textContent=(d.base_stock||d.quantity||0)+' '+(d.base_unit||'PCS');document.getElementById('stockDisplay').classList.add('show');});}

function updateUnit(){var uid=document.getElementById('unit_id').value,qty=parseFloat(document.getElementById('qty').value)||0,h=document.getElementById('unitHint');if(uid&&pData.units){var u=pData.units.find(x=>x.id==uid);if(u&&pData.base_unit_name&&!u.is_base&&parseFloat(u.conversion_factor)!=1){var c=parseFloat(u.conversion_factor)||1;h.innerHTML=qty>0?'<b>'+qty+'</b> × '+c+' = <b>'+(qty*c).toFixed(2)+' '+pData.base_unit_name+'</b> will be added':'1 unit = '+c+' '+pData.base_unit_name;h.classList.add('show');return;}}h.classList.remove('show');}

function onWarehouse(){loadRacks(document.getElementById('warehouse_id').value);checkStock();}
function loadRacks(wid){selRack.clear();selRack.clearOptions();selRack.addOption({value:'',text:'Select rack...'});if(!wid)return;fetch('{{ url("admin/inventory/racks/by-warehouse") }}/'+wid).then(r=>r.json()).then(d=>{d.forEach(r=>{selRack.addOption({value:r.id,text:r.code+' - '+r.name});});});}
document.getElementById('qty').addEventListener('input',updateUnit);
</script>
</x-layouts.app>