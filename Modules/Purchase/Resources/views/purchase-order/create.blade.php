@include('purchase::partials.styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="page-header">
    <h1>{{ $pr ? 'Create PO from PR: '.$pr->pr_number : 'New Purchase Order' }}</h1>
    <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul></div>
@endif

@if($pr)
<div class="alert alert-info">Creating PO from PR: <strong>{{ $pr->pr_number }}</strong></div>
@endif

<form action="{{ route('admin.purchase.orders.store') }}" method="POST" id="poForm">
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
                    <input type="date" name="po_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected Delivery</label>
                    <input type="date" name="expected_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Vendor <span class="required">*</span></label>
                    <select name="vendor_id" class="form-control" required>
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $v)
                        <option value="{{$v->id}}">{{$v->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items <span class="required">*</span></h5>
            <button type="button" class="btn btn-success btn-sm" onclick="addRow()">+ Add</button>
        </div>
        <div class="card-body" style="padding:0; overflow-x:auto;">
            <table class="items-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th style="min-width:280px">Product <span class="required">*</span></th>
                        <th style="width:60px">Unit</th>
                        <th style="width:80px">Qty <span class="required">*</span></th>
                        <th style="width:100px">Rate <span class="required">*</span></th>
                        <th style="width:70px">Disc%</th>
                        <th style="width:100px">Tax</th>
                        <th style="width:100px">Total</th>
                        <th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
            </table>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header"><h5>Shipping</h5></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-2">
                        <label class="form-label">Address</label>
                        <textarea name="shipping_address" class="form-control" rows="2">{{ $companyAddress['address'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">City</label><input type="text" name="shipping_city" class="form-control" value="{{ $companyAddress['city'] ?? '' }}"></div>
                    <div class="form-group"><label class="form-label">State</label><input type="text" name="shipping_state" class="form-control" value="{{ $companyAddress['state'] ?? '' }}"></div>
                    <div class="form-group"><label class="form-label">PIN</label><input type="text" name="shipping_pincode" class="form-control" value="{{ $companyAddress['pincode'] ?? '' }}"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5>Summary</h5></div>
            <div class="card-body">
                <div class="summary-row"><span>Subtotal</span><span id="subtotal">₹0.00</span></div>
                <div class="summary-row"><span>Tax</span><span id="taxTotal">₹0.00</span></div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <input type="number" name="shipping_charge" id="shippingCharge" class="form-control" style="width:100px" value="0" min="0" step="0.01" onchange="calcTotals()">
                </div>
                <div class="summary-row">
                    <span>Discount</span>
                    <input type="number" name="discount_amount" id="discountAmount" class="form-control" style="width:100px" value="0" min="0" step="0.01" onchange="calcTotals()">
                </div>
                <div class="summary-row total"><span>Grand Total</span><span id="grandTotal">₹0.00</span></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Terms & Notes</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Payment Terms</label><input type="text" name="payment_terms" class="form-control" value="Net 30"></div>
                <div class="form-group col-2"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="1"></textarea></div>
            </div>
            <div class="form-group"><label class="form-label">Terms & Conditions</label><textarea name="terms_conditions" class="form-control" rows="3">{{ $defaultTerms }}</textarea></div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save as Draft</button>
        <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

@php
$prItemsData = [];
if ($pr) {
    foreach ($pr->items as $i) {
        $prItemsData[] = [
            'product_id' => $i->product_id,
            'variation_id' => $i->variation_id,
            'product_name' => $i->product->name ?? '',
            'product_sku' => $i->product->sku ?? '',
            'variation_name' => $i->variation->variation_name ?? ($i->variation->sku ?? ''),
            'unit_id' => $i->unit_id ?? ($i->product->unit_id ?? null),
            'unit_name' => $i->unit->short_name ?? ($i->unit->name ?? ($i->product->unit->short_name ?? '-')),
            'qty' => $i->qty,
            'price' => $i->estimated_price ?? ($i->product->purchase_price ?? 0),
            'tax_1_id' => $i->product->tax_1_id ?? null,
            'tax_2_id' => $i->product->tax_2_id ?? null,
            'pr_item_id' => $i->id,
        ];
    }
}
@endphp

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let rowIndex = 0;
const taxes = @json($taxes ?? []);
const prItems = @json($prItemsData);

function addRow(item = null) {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.id = `row-${rowIndex}`;
    
    let displayText = item ? (item.product_sku ? item.product_sku + ' - ' : '') + item.product_name : '';
    if (item && item.variation_name) displayText += ` » ${item.variation_name}`;
    
    const tax1 = item?.tax_1_id && taxes[item.tax_1_id] ? taxes[item.tax_1_id] : null;
    const tax2 = item?.tax_2_id && taxes[item.tax_2_id] ? taxes[item.tax_2_id] : null;
    let taxHtml = '-';
    if (tax1 || tax2) {
        taxHtml = '';
        if (tax1) taxHtml += `<span class="tax-badge">${tax1.name}</span>`;
        if (tax2) taxHtml += `<span class="tax-badge">${tax2.name}</span>`;
    }
    
    row.innerHTML = `
        <td class="text-center">${tbody.children.length + 1}</td>
        <td>
            <select name="items[${rowIndex}][product_id]" class="product-select" data-row="${rowIndex}" style="width:100%">
                ${item ? `<option value="${item.product_id}" selected>${displayText}</option>` : '<option value="">Search product...</option>'}
            </select>
            <input type="hidden" name="items[${rowIndex}][variation_id]" class="variation-id" value="${item?.variation_id || ''}">
            <input type="hidden" name="items[${rowIndex}][unit_id]" class="unit-id" value="${item?.unit_id || ''}">
            <input type="hidden" name="items[${rowIndex}][tax_1_id]" class="tax1-id" value="${item?.tax_1_id || ''}">
            <input type="hidden" name="items[${rowIndex}][tax_2_id]" class="tax2-id" value="${item?.tax_2_id || ''}">
            ${item?.pr_item_id ? `<input type="hidden" name="items[${rowIndex}][pr_item_id]" value="${item.pr_item_id}">` : ''}
        </td>
        <td class="text-center"><span class="unit-name">${item?.unit_name || '-'}</span></td>
        <td><input type="number" name="items[${rowIndex}][qty]" class="form-control form-control-sm qty-input" value="${item?.qty || 1}" min="0.001" step="0.001" onchange="calcRow(${rowIndex})"></td>
        <td><input type="number" name="items[${rowIndex}][rate]" class="form-control form-control-sm rate-input" value="${item?.price || 0}" min="0" step="0.01" onchange="calcRow(${rowIndex})"></td>
        <td><input type="number" name="items[${rowIndex}][discount_percent]" class="form-control form-control-sm disc-input" value="0" min="0" max="100" step="0.01" onchange="calcRow(${rowIndex})"></td>
        <td class="tax-cell">${taxHtml}</td>
        <td class="text-end"><span class="row-total">₹0.00</span></td>
        <td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowIndex})">×</button></td>
    `;
    tbody.appendChild(row);
    initSelect2(rowIndex);
    calcRow(rowIndex);
    rowIndex++;
    updateRowNumbers();
}

function removeRow(idx) {
    document.getElementById(`row-${idx}`)?.remove();
    updateRowNumbers();
    calcTotals();
}

function updateRowNumbers() {
    document.querySelectorAll('#itemsBody tr').forEach((row, i) => row.cells[0].textContent = i + 1);
}

function initSelect2(idx) {
    $(`#row-${idx} .product-select`).select2({
        placeholder: 'Search product...',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.purchase.orders.search-products") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        },
        templateResult: formatProduct,
        templateSelection: formatProductSelection
    }).on('select2:select', function(e) {
        const d = e.params.data;
        const row = $(this).closest('tr');
        row.find('.variation-id').val(d.variation_id || '');
        row.find('.unit-id').val(d.unit_id || '');
        row.find('.tax1-id').val(d.tax_1_id || '');
        row.find('.tax2-id').val(d.tax_2_id || '');
        row.find('.unit-name').text(d.unit_name || '-');
        row.find('.rate-input').val(d.price || 0);
        
        let taxHtml = '-';
        const t1 = d.tax_1_id && taxes[d.tax_1_id] ? taxes[d.tax_1_id] : null;
        const t2 = d.tax_2_id && taxes[d.tax_2_id] ? taxes[d.tax_2_id] : null;
        if (t1 || t2) {
            taxHtml = '';
            if (t1) taxHtml += `<span class="tax-badge">${t1.name}</span>`;
            if (t2) taxHtml += `<span class="tax-badge">${t2.name}</span>`;
        }
        row.find('.tax-cell').html(taxHtml);
        calcRow(idx);
    });
}

function formatProduct(item) {
    if (!item.id) return item.text;
    let html = `<div style="display:flex;align-items:center;gap:10px;">`;
    if (item.image) html += `<img src="${item.image}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">`;
    html += `<div><strong>${item.text}</strong>`;
    if (item.variation_name) html += `<span style="background:#8b5cf6;color:#fff;padding:2px 6px;border-radius:3px;font-size:10px;margin-left:6px;">${item.variation_name}</span>`;
    html += `<br><small style="color:#888;">SKU: ${item.sku || '-'} | ₹${parseFloat(item.price || 0).toFixed(2)}</small></div></div>`;
    return $(html);
}

function formatProductSelection(item) {
    if (!item.id) return item.text;
    return item.text + (item.variation_name ? ` » ${item.variation_name}` : '');
}

function calcRow(idx) {
    const row = document.getElementById(`row-${idx}`);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
    const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
    const tax1Id = row.querySelector('.tax1-id').value;
    const tax2Id = row.querySelector('.tax2-id').value;
    
    let lineTotal = qty * rate;
    lineTotal -= lineTotal * (disc / 100);
    
    let taxRate = 0;
    if (tax1Id && taxes[tax1Id]) taxRate += parseFloat(taxes[tax1Id].rate) || 0;
    if (tax2Id && taxes[tax2Id]) taxRate += parseFloat(taxes[tax2Id].rate) || 0;
    
    const taxAmt = lineTotal * (taxRate / 100);
    const total = lineTotal + taxAmt;
    
    row.querySelector('.row-total').textContent = `₹${total.toFixed(2)}`;
    calcTotals();
}

function calcTotals() {
    let subtotal = 0, taxTotal = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
        const rate = parseFloat(row.querySelector('.rate-input')?.value) || 0;
        const disc = parseFloat(row.querySelector('.disc-input')?.value) || 0;
        const tax1Id = row.querySelector('.tax1-id')?.value;
        const tax2Id = row.querySelector('.tax2-id')?.value;
        
        let line = qty * rate;
        line -= line * (disc / 100);
        subtotal += line;
        
        let taxRate = 0;
        if (tax1Id && taxes[tax1Id]) taxRate += parseFloat(taxes[tax1Id].rate) || 0;
        if (tax2Id && taxes[tax2Id]) taxRate += parseFloat(taxes[tax2Id].rate) || 0;
        taxTotal += line * (taxRate / 100);
    });
    
    const shipping = parseFloat(document.getElementById('shippingCharge').value) || 0;
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const grand = subtotal + taxTotal + shipping - discount;
    
    document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('taxTotal').textContent = `₹${taxTotal.toFixed(2)}`;
    document.getElementById('grandTotal').textContent = `₹${grand.toFixed(2)}`;
}

// Init
if (prItems.length) {
    prItems.forEach(item => addRow(item));
} else {
    addRow();
}
</script>

<style>
.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single { height: 38px; border-color: #374151; background: #1f2937; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; color: #f3f4f6; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
.select2-dropdown { background: #1f2937; border-color: #374151; }
.select2-results__option { color: #f3f4f6; }
.select2-results__option--highlighted { background: #374151 !important; }
.select2-search__field { background: #111827 !important; border-color: #374151 !important; color: #f3f4f6 !important; }
.tax-badge { display: inline-block; background: #10b981; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin: 1px; }
.summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #374151; }
.summary-row.total { font-weight: 700; font-size: 16px; color: #10b981; border-bottom: none; padding-top: 12px; }
</style>
