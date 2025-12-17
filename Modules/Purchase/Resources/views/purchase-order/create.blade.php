@include('purchase::partials.styles')

<style>
/* Tax Display - Read Only from Product */
.tax-display { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 4px; 
    min-height: 36px; 
    padding: 6px 8px; 
    border: 1px solid #374151; 
    border-radius: 6px; 
    background: #1f2937; 
    align-items: center;
}
.tax-badge { 
    display: inline-flex; 
    align-items: center; 
    padding: 4px 10px; 
    background: #10b981; 
    color: #fff; 
    border-radius: 4px; 
    font-size: 11px; 
    font-weight: 600;
    white-space: nowrap;
}
.tax-placeholder { color: #6b7280; font-size: 12px; }
</style>

<div class="page-header">
    <h1>{{ $pr ? 'Create PO from PR: '.$pr->pr_number : 'New Purchase Order' }}</h1>
    <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

@if($pr)
<div class="alert alert-info">Creating PO from Purchase Request: <strong>{{ $pr->pr_number }}</strong></div>
@endif

<form action="{{ route('admin.purchase.orders.store') }}" method="POST">
    @csrf
    @if($pr)<input type="hidden" name="purchase_request_id" value="{{ $pr->id }}">@endif

    <div class="card">
        <div class="card-header"><h5>Order Details</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">PO Number</label>
                    <input type="text" class="form-control" value="{{ $poNumber }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Date <span class="required">*</span></label>
                    <input type="date" name="po_date" id="poDate" class="form-control" value="{{ old('po_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Delivery</label>
                    <input type="date" name="expected_date" id="expectedDate" class="form-control" value="{{ old('expected_date') }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Vendor <span class="required">*</span></label>
                    <select name="vendor_id" class="form-control" required id="vendorSelect">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->vendor_code }} - {{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Payment Terms</label>
                    <select name="payment_terms" class="form-control">
                        <option value="Immediate">Immediate</option>
                        <option value="Net 15">Net 15</option>
                        <option value="Net 30" selected>Net 30</option>
                        <option value="Net 45">Net 45</option>
                        <option value="Net 60">Net 60</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Shipping Address</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-full">
                    <label class="form-label">Address</label>
                    <textarea name="shipping_address" class="form-control" rows="2">{{ old('shipping_address', $companyAddress['address'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city', $companyAddress['city'] ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="shipping_state" class="form-control" value="{{ old('shipping_state', $companyAddress['state'] ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="shipping_pincode" class="form-control" value="{{ old('shipping_pincode', $companyAddress['pincode'] ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items</h5>
            <button type="button" class="btn btn-success btn-sm" onclick="addItemRow()">+ Add Item</button>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th style="min-width:180px">Product <span class="required">*</span></th>
                            <th style="width:60px">Unit</th>
                            <th style="width:70px">Qty <span class="required">*</span></th>
                            <th style="width:90px">Rate <span class="required">*</span></th>
                            <th style="min-width:140px">Taxes (Auto)</th>
                            <th style="width:60px">Disc%</th>
                            <th style="width:90px" class="text-end">Total</th>
                            <th style="width:40px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @if($pr && $pr->items->count())
                            @foreach($pr->items as $i => $item)
                            @php $itemProduct = $products->firstWhere('id', $item->product_id); @endphp
                            <tr class="item-row" data-idx="{{ $i }}">
                                <td class="text-center row-num">{{ $i + 1 }}</td>
                                <td>
                                    <input type="hidden" name="items[{{ $i }}][pr_item_id]" value="{{ $item->id }}">
                                    <select name="items[{{ $i }}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">
                                        <option value="">Select Product</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}" 
                                            data-unit="{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}" 
                                            data-unit-id="{{ $p->unit_id }}" 
                                            data-rate="{{ $p->purchase_price ?? $p->sale_price ?? 0 }}"
                                            data-tax1-id="{{ $p->tax_1_id }}"
                                            data-tax2-id="{{ $p->tax_2_id }}"
                                            {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->sku ? $p->sku.' - ' : '' }}{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[{{ $i }}][unit_id]" class="unit-id-input" value="{{ $item->unit_id ?? $itemProduct->unit_id ?? '' }}">
                                    <input type="hidden" name="items[{{ $i }}][tax_1_id]" class="tax1-input" value="{{ $itemProduct->tax_1_id ?? '' }}">
                                    <input type="hidden" name="items[{{ $i }}][tax_2_id]" class="tax2-input" value="{{ $itemProduct->tax_2_id ?? '' }}">
                                </td>
                                <td><div class="unit-display unit-text">{{ $itemProduct->unit->short_name ?? '-' }}</div></td>
                                <td><input type="number" name="items[{{ $i }}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" value="{{ $item->qty }}" required onchange="calcRow(this)"></td>
                                <td><input type="number" name="items[{{ $i }}][rate]" class="form-control form-control-sm rate-input" step="0.01" min="0" value="{{ $item->estimated_price ?? $itemProduct->purchase_price ?? 0 }}" required onchange="calcRow(this)"></td>
                                <td>
                                    <div class="tax-display">
                                        <span class="tax-badges"></span>
                                    </div>
                                </td>
                                <td><input type="number" name="items[{{ $i }}][discount_percent]" class="form-control form-control-sm disc-input" step="0.01" min="0" max="100" value="0" onchange="calcRow(this)"></td>
                                <td class="text-end"><span class="row-total">0.00</span></td>
                                <td class="text-center"><button type="button" class="btn-danger-outline" onclick="removeRow(this)">×</button></td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="item-row" data-idx="0">
                                <td class="text-center row-num">1</td>
                                <td>
                                    <select name="items[0][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">
                                        <option value="">Select Product</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}" 
                                            data-unit="{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}" 
                                            data-unit-id="{{ $p->unit_id }}" 
                                            data-rate="{{ $p->purchase_price ?? $p->sale_price ?? 0 }}"
                                            data-tax1-id="{{ $p->tax_1_id }}"
                                            data-tax2-id="{{ $p->tax_2_id }}">{{ $p->sku ? $p->sku.' - ' : '' }}{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[0][unit_id]" class="unit-id-input">
                                    <input type="hidden" name="items[0][tax_1_id]" class="tax1-input">
                                    <input type="hidden" name="items[0][tax_2_id]" class="tax2-input">
                                </td>
                                <td><div class="unit-display unit-text">-</div></td>
                                <td><input type="number" name="items[0][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" required onchange="calcRow(this)"></td>
                                <td><input type="number" name="items[0][rate]" class="form-control form-control-sm rate-input" step="0.01" min="0" required onchange="calcRow(this)"></td>
                                <td>
                                    <div class="tax-display">
                                        <span class="tax-badges"></span>
                                    </div>
                                </td>
                                <td><input type="number" name="items[0][discount_percent]" class="form-control form-control-sm disc-input" step="0.01" min="0" max="100" value="0" onchange="calcRow(this)"></td>
                                <td class="text-end"><span class="row-total">0.00</span></td>
                                <td class="text-center"><button type="button" class="btn-danger-outline" onclick="removeRow(this)" disabled>×</button></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Summary</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Shipping Charge</label>
                    <input type="number" name="shipping_charge" id="shippingCharge" class="form-control" step="0.01" min="0" value="0" onchange="calcGrandTotal()">
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Amount</label>
                    <input type="number" name="discount_amount" id="discountAmount" class="form-control" step="0.01" min="0" value="0" onchange="calcGrandTotal()">
                </div>
                <div class="form-group col-2">
                    <table class="summary-table">
                        <tr><td>Subtotal</td><td id="subtotal">₹0.00</td></tr>
                        <tr><td>Tax</td><td id="taxTotal">₹0.00</td></tr>
                        <tr class="grand-total"><td>Grand Total</td><td id="grandTotal">₹0.00</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Terms & Notes</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-2">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions', $defaultTerms) }}</textarea>
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save as Draft</button>
        <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

<script>
let idx = {{ $pr ? $pr->items->count() : 1 }};

// Products data with tax info
const productsData = [
    @foreach($products as $p)
    { 
        id: {{ $p->id }}, 
        sku: "{{ $p->sku ?? '' }}", 
        name: "{{ addslashes($p->name) }}", 
        unit: "{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}", 
        unit_id: {{ $p->unit_id ?? 'null' }}, 
        rate: {{ $p->purchase_price ?? $p->sale_price ?? 0 }},
        tax_1_id: {{ $p->tax_1_id ?? 'null' }},
        tax_2_id: {{ $p->tax_2_id ?? 'null' }}
    },
    @endforeach
];

// Taxes lookup
const taxesMap = {
    @foreach($taxes as $id => $t)
    {{ $id }}: { id: {{ $t['id'] }}, name: "{{ addslashes($t['name']) }}", rate: {{ $t['rate'] }} },
    @endforeach
};

function onProductChange(select) {
    const row = select.closest('tr');
    const opt = select.options[select.selectedIndex];
    const unitText = row.querySelector('.unit-text');
    const unitInput = row.querySelector('.unit-id-input');
    const rateInput = row.querySelector('.rate-input');
    const tax1Input = row.querySelector('.tax1-input');
    const tax2Input = row.querySelector('.tax2-input');
    const taxBadges = row.querySelector('.tax-badges');
    
    if (opt.value) {
        unitText.textContent = opt.dataset.unit || '-';
        unitInput.value = opt.dataset.unitId || '';
        if (!rateInput.value || rateInput.value == '0') {
            rateInput.value = parseFloat(opt.dataset.rate || 0).toFixed(2);
        }
        
        // Set tax IDs from product
        const tax1Id = opt.dataset.tax1Id || '';
        const tax2Id = opt.dataset.tax2Id || '';
        tax1Input.value = tax1Id;
        tax2Input.value = tax2Id;
        
        // Display tax badges
        let badges = '';
        if (tax1Id && taxesMap[tax1Id]) {
            badges += `<span class="tax-badge">${taxesMap[tax1Id].name}</span>`;
        }
        if (tax2Id && taxesMap[tax2Id]) {
            badges += `<span class="tax-badge">${taxesMap[tax2Id].name}</span>`;
        }
        taxBadges.innerHTML = badges || '<span class="tax-placeholder">No tax</span>';
    } else {
        unitText.textContent = '-';
        unitInput.value = '';
        tax1Input.value = '';
        tax2Input.value = '';
        taxBadges.innerHTML = '<span class="tax-placeholder">Select product</span>';
    }
    calcRow(select);
}

function getRowTaxRate(row) {
    const tax1Id = row.querySelector('.tax1-input').value;
    const tax2Id = row.querySelector('.tax2-input').value;
    let totalRate = 0;
    if (tax1Id && taxesMap[tax1Id]) totalRate += taxesMap[tax1Id].rate;
    if (tax2Id && taxesMap[tax2Id]) totalRate += taxesMap[tax2Id].rate;
    return totalRate;
}

function addItemRow() {
    let opts = '<option value="">Select Product</option>';
    productsData.forEach(p => {
        opts += `<option value="${p.id}" data-unit="${p.unit}" data-unit-id="${p.unit_id}" data-rate="${p.rate}" data-tax1-id="${p.tax_1_id || ''}" data-tax2-id="${p.tax_2_id || ''}">${p.sku ? p.sku+' - ' : ''}${p.name}</option>`;
    });
    
    const row = `<tr class="item-row" data-idx="${idx}">
        <td class="text-center row-num">${document.querySelectorAll('.item-row').length + 1}</td>
        <td>
            <select name="items[${idx}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">${opts}</select>
            <input type="hidden" name="items[${idx}][unit_id]" class="unit-id-input">
            <input type="hidden" name="items[${idx}][tax_1_id]" class="tax1-input">
            <input type="hidden" name="items[${idx}][tax_2_id]" class="tax2-input">
        </td>
        <td><div class="unit-display unit-text">-</div></td>
        <td><input type="number" name="items[${idx}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" required onchange="calcRow(this)"></td>
        <td><input type="number" name="items[${idx}][rate]" class="form-control form-control-sm rate-input" step="0.01" min="0" required onchange="calcRow(this)"></td>
        <td>
            <div class="tax-display">
                <span class="tax-badges"><span class="tax-placeholder">Select product</span></span>
            </div>
        </td>
        <td><input type="number" name="items[${idx}][discount_percent]" class="form-control form-control-sm disc-input" step="0.01" min="0" max="100" value="0" onchange="calcRow(this)"></td>
        <td class="text-end"><span class="row-total">0.00</span></td>
        <td class="text-center"><button type="button" class="btn-danger-outline" onclick="removeRow(this)">×</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    idx++;
    updateRowNumbers();
    updateRemoveButtons();
}

function removeRow(btn) {
    if (document.querySelectorAll('.item-row').length > 1) {
        btn.closest('tr').remove();
        updateRowNumbers();
        updateRemoveButtons();
        calcGrandTotal();
    }
}

function updateRowNumbers() {
    document.querySelectorAll('.item-row').forEach((row, i) => row.querySelector('.row-num').textContent = i + 1);
}

function updateRemoveButtons() {
    const btns = document.querySelectorAll('.item-row button');
    btns.forEach(b => b.disabled = btns.length <= 1);
}

function calcRow(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
    const taxRate = getRowTaxRate(row);
    const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
    
    const subtotal = qty * rate;
    const discAmount = subtotal * disc / 100;
    const afterDisc = subtotal - discAmount;
    const taxAmount = afterDisc * taxRate / 100;
    const total = afterDisc + taxAmount;
    
    row.querySelector('.row-total').textContent = total.toFixed(2);
    calcGrandTotal();
}

function calcGrandTotal() {
    let subtotal = 0, taxTotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
        const taxRate = getRowTaxRate(row);
        const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
        
        const rowSubtotal = qty * rate;
        const discAmount = rowSubtotal * disc / 100;
        const afterDisc = rowSubtotal - discAmount;
        const taxAmount = afterDisc * taxRate / 100;
        
        subtotal += afterDisc;
        taxTotal += taxAmount;
    });
    
    const shipping = parseFloat(document.getElementById('shippingCharge').value) || 0;
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const grandTotal = subtotal + taxTotal + shipping - discount;
    
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('taxTotal').textContent = '₹' + taxTotal.toFixed(2);
    document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    // Initialize existing rows
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        if (select && select.value) {
            onProductChange(select);
        }
    });
    updateRemoveButtons();
});
</script>
