@include('purchase::partials.styles')


<div class="page-header">
    <h1>Edit {{ $po->po_number }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.purchase.orders.show', $po->id) }}" class="btn btn-outline">View</a>
        <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Back</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.orders.update', $po->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header"><h5>Order Details</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">PO Number</label>
                    <input type="text" class="form-control" value="{{ $po->po_number }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="text" class="form-control" value="{{ $po->po_date->format('d M Y') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Delivery</label>
                    <input type="date" name="expected_date" class="form-control" value="{{ $po->expected_date?->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Vendor <span class="required">*</span></label>
                    <select name="vendor_id" class="form-control" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ $po->vendor_id == $v->id ? 'selected' : '' }}>{{ $v->vendor_code }} - {{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Payment Terms</label>
                    <select name="payment_terms" class="form-control">
                        @foreach(['Immediate', 'Net 15', 'Net 30', 'Net 45', 'Net 60'] as $term)
                        <option value="{{ $term }}" {{ $po->payment_terms == $term ? 'selected' : '' }}>{{ $term }}</option>
                        @endforeach
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
                    <textarea name="shipping_address" class="form-control" rows="2">{{ old('shipping_address', $po->shipping_address) }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city', $po->shipping_city) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="shipping_state" class="form-control" value="{{ old('shipping_state', $po->shipping_state) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="shipping_pincode" class="form-control" value="{{ old('shipping_pincode', $po->shipping_pincode) }}">
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
                            <th style="min-width:200px">Product <span class="required">*</span></th>
                            <th style="width:70px">Unit</th>
                            <th style="width:80px">Qty <span class="required">*</span></th>
                            <th style="width:100px">Rate <span class="required">*</span></th>
                            <th style="width:100px">Tax</th>
                            <th style="width:70px">Disc%</th>
                            <th style="width:100px" class="text-end">Total</th>
                            <th style="width:40px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @foreach($po->items as $i => $item)
                        @php $itemProduct = $products->firstWhere('id', $item->product_id); @endphp
                        <tr class="item-row" data-idx="{{ $i }}">
                            <td class="text-center row-num">{{ $i + 1 }}</td>
                            <td>
                                <select name="items[{{ $i }}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">
                                    <option value="">Select Product</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-unit="{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}" data-unit-id="{{ $p->unit_id }}" data-rate="{{ $p->sale_price ?? $p->mrp ?? 0 }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->sku ? $p->sku.' - ' : '' }}{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="items[{{ $i }}][unit_id]" class="unit-id-input" value="{{ $item->unit_id }}">
                            </td>
                            <td><div class="unit-display unit-text">{{ $itemProduct->unit->short_name ?? '-' }}</div></td>
                            <td><input type="number" name="items[{{ $i }}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" value="{{ $item->qty }}" required onchange="calcRow(this)"></td>
                            <td><input type="number" name="items[{{ $i }}][rate]" class="form-control form-control-sm rate-input" step="0.01" min="0" value="{{ $item->rate }}" required onchange="calcRow(this)"></td>
                            <td>
                                <select name="items[{{ $i }}][tax_percent]" class="form-control form-control-sm tax-input" onchange="calcRow(this)">
                                    <option value="0">No Tax</option>
                                    @foreach($taxes as $tax)
                                    <option value="{{ $tax->rate }}" {{ $item->tax_percent == $tax->rate ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[{{ $i }}][discount_percent]" class="form-control form-control-sm disc-input" step="0.01" min="0" max="100" value="{{ $item->discount_percent }}" onchange="calcRow(this)"></td>
                            <td class="text-end"><span class="row-total">{{ number_format($item->total, 2) }}</span></td>
                            <td class="text-center"><button type="button" class="btn-danger-outline" onclick="removeRow(this)" {{ $po->items->count() <= 1 ? 'disabled' : '' }}>×</button></td>
                        </tr>
                        @endforeach
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
                    <input type="number" name="shipping_charge" id="shippingCharge" class="form-control" step="0.01" min="0" value="{{ $po->shipping_charge }}" onchange="calcGrandTotal()">
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Amount</label>
                    <input type="number" name="discount_amount" id="discountAmount" class="form-control" step="0.01" min="0" value="{{ $po->discount_amount }}" onchange="calcGrandTotal()">
                </div>
                <div class="form-group col-2">
                    <table class="summary-table">
                        <tr><td>Subtotal</td><td id="subtotal">₹{{ number_format($po->subtotal, 2) }}</td></tr>
                        <tr><td>Tax</td><td id="taxTotal">₹{{ number_format($po->tax_total, 2) }}</td></tr>
                        <tr class="grand-total"><td>Grand Total</td><td id="grandTotal">₹{{ number_format($po->grand_total, 2) }}</td></tr>
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
                    <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions', $po->terms_conditions) }}</textarea>
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $po->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-danger" onclick="if(confirm('Delete this PO?')) document.getElementById('deleteForm').submit();">Delete</button>
        <div class="form-actions-right">
            <a href="{{ route('admin.purchase.orders.show', $po->id) }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

<form id="deleteForm" action="{{ route('admin.purchase.orders.destroy', $po->id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<script>
let idx = {{ $po->items->count() }};
const productsData = [
    @foreach($products as $p)
    {
        id: {{ $p->id }},
        sku: "{{ $p->sku ?? '' }}",
        name: "{{ addslashes($p->name) }}",
        unit: "{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}",
        unit_id: {{ $p->unit_id ?? 'null' }},
        rate: {{ $p->sale_price ?? $p->mrp ?? 0 }}
    },
    @endforeach
];
const taxes = @json($taxes);

function onProductChange(select) {
    const row = select.closest('tr');
    const opt = select.options[select.selectedIndex];
    const unitText = row.querySelector('.unit-text');
    const unitInput = row.querySelector('.unit-id-input');
    const rateInput = row.querySelector('.rate-input');
    
    if (opt.value) {
        unitText.textContent = opt.dataset.unit || '-';
        unitInput.value = opt.dataset.unitId || '';
        if (!rateInput.value) rateInput.value = parseFloat(opt.dataset.rate || 0).toFixed(2);
    } else {
        unitText.textContent = '-';
        unitInput.value = '';
    }
    calcRow(select);
}

function addItemRow() {
    let opts = '<option value="">Select Product</option>';
    productsData.forEach(p => {
        opts += `<option value="${p.id}" data-unit="${p.unit}" data-unit-id="${p.unit_id}" data-rate="${p.rate}">${p.sku ? p.sku+' - ' : ''}${p.name}</option>`;
    });
    let taxOpts = '<option value="0">No Tax</option>';
    taxes.forEach(t => taxOpts += `<option value="${t.rate}">${t.name} (${t.rate}%)</option>`);
    
    const row = `<tr class="item-row" data-idx="${idx}">
        <td class="text-center row-num">${document.querySelectorAll('.item-row').length + 1}</td>
        <td>
            <select name="items[${idx}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">${opts}</select>
            <input type="hidden" name="items[${idx}][unit_id]" class="unit-id-input">
        </td>
        <td><div class="unit-display unit-text">-</div></td>
        <td><input type="number" name="items[${idx}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" required onchange="calcRow(this)"></td>
        <td><input type="number" name="items[${idx}][rate]" class="form-control form-control-sm rate-input" step="0.01" min="0" required onchange="calcRow(this)"></td>
        <td><select name="items[${idx}][tax_percent]" class="form-control form-control-sm tax-input" onchange="calcRow(this)">${taxOpts}</select></td>
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
    const tax = parseFloat(row.querySelector('.tax-input').value) || 0;
    const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
    
    const subtotal = qty * rate;
    const discAmount = subtotal * disc / 100;
    const afterDisc = subtotal - discAmount;
    const taxAmount = afterDisc * tax / 100;
    const total = afterDisc + taxAmount;
    
    row.querySelector('.row-total').textContent = total.toFixed(2);
    calcGrandTotal();
}

function calcGrandTotal() {
    let subtotal = 0, taxTotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
        const tax = parseFloat(row.querySelector('.tax-input').value) || 0;
        const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
        
        const rowSubtotal = qty * rate;
        const discAmount = rowSubtotal * disc / 100;
        const afterDisc = rowSubtotal - discAmount;
        const taxAmount = afterDisc * tax / 100;
        
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
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = row.querySelector('.qty-input');
        if (qty && qty.value) calcRow(qty);
    });
    updateRemoveButtons();
});
</script>
