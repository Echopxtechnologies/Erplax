@include('purchase::partials.styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="page-header">
    <h1>Edit: {{ $pr->pr_number }}</h1>
    <a href="{{ route('admin.purchase.requests.show', $pr->id) }}" class="btn btn-outline">Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.requests.update', $pr->id) }}" method="POST" id="prForm">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-header"><h5>Request Details</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">PR Number</label>
                    <input type="text" class="form-control" value="{{ $pr->pr_number }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" value="{{ $pr->pr_date->format('Y-m-d') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Required By</label>
                    <input type="date" name="required_date" class="form-control" value="{{ $pr->required_date?->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Priority <span class="required">*</span></label>
                    <select name="priority" class="form-control" required>
                        @foreach(['LOW', 'NORMAL', 'HIGH', 'URGENT'] as $p)
                        <option value="{{ $p }}" {{ $pr->priority == $p ? 'selected' : '' }}>{{ ucfirst(strtolower($p)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ $pr->department }}">
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Purpose</label>
                    <input type="text" name="purpose" class="form-control" value="{{ $pr->purpose }}">
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
                        <th style="min-width:300px">Product <span class="required">*</span></th>
                        <th style="width:80px">Unit</th>
                        <th style="width:100px">Qty <span class="required">*</span></th>
                        <th style="width:120px">Est. Price</th>
                        <th style="width:120px">Total</th>
                        <th style="width:50px"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong id="grandTotal">₹0.00</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Notes</h5></div>
        <div class="card-body">
            <textarea name="notes" class="form-control" rows="2">{{ $pr->notes }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.purchase.requests.show', $pr->id) }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

@php
$existingItemsData = [];
foreach ($pr->items as $i) {
    $existingItemsData[] = [
        'product_id' => $i->product_id,
        'variation_id' => $i->variation_id,
        'product_name' => $i->product->name ?? '',
        'product_sku' => $i->product->sku ?? '',
        'variation_name' => $i->variation->variation_name ?? ($i->variation->sku ?? ''),
        'unit_id' => $i->unit_id,
        'unit_name' => $i->unit->short_name ?? ($i->unit->name ?? '-'),
        'qty' => $i->qty,
        'estimated_price' => $i->estimated_price ?? 0,
    ];
}
@endphp

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let rowIndex = 0;
const existingItems = @json($existingItemsData);

function addRow(item = null) {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.id = `row-${rowIndex}`;
    
    let displayText = item ? (item.product_sku ? item.product_sku + ' - ' : '') + item.product_name : '';
    if (item && item.variation_name) displayText += ` » ${item.variation_name}`;
    
    row.innerHTML = `
        <td class="text-center">${tbody.children.length + 1}</td>
        <td>
            <select name="items[${rowIndex}][product_id]" class="product-select" data-row="${rowIndex}" style="width:100%">
                ${item ? `<option value="${item.product_id}" selected>${displayText}</option>` : '<option value="">Search product...</option>'}
            </select>
            <input type="hidden" name="items[${rowIndex}][variation_id]" class="variation-id" value="${item?.variation_id || ''}">
            <input type="hidden" name="items[${rowIndex}][unit_id]" class="unit-id" value="${item?.unit_id || ''}">
        </td>
        <td class="text-center"><span class="unit-name">${item?.unit_name || '-'}</span></td>
        <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty-input" value="${item?.qty || 1}" min="0.001" step="0.001" onchange="calcRow(${rowIndex})"></td>
        <td><input type="number" name="items[${rowIndex}][estimated_price]" class="form-control price-input" value="${item?.estimated_price || 0}" min="0" step="0.01" onchange="calcRow(${rowIndex})"></td>
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
    const row = document.getElementById(`row-${idx}`);
    if (row) row.remove();
    updateRowNumbers();
    calcGrandTotal();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#itemsBody tr');
    rows.forEach((row, i) => row.cells[0].textContent = i + 1);
}

function initSelect2(idx) {
    $(`#row-${idx} .product-select`).select2({
        placeholder: 'Search product...',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.purchase.requests.search-products") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        },
        templateResult: formatProduct,
        templateSelection: formatProductSelection
    }).on('select2:select', function(e) {
        const data = e.params.data;
        const row = $(this).closest('tr');
        row.find('.variation-id').val(data.variation_id || '');
        row.find('.unit-id').val(data.unit_id || '');
        row.find('.unit-name').text(data.unit_name || '-');
        row.find('.price-input').val(data.price || 0);
        calcRow(idx);
    }).on('select2:clear', function() {
        const row = $(this).closest('tr');
        row.find('.variation-id').val('');
        row.find('.unit-id').val('');
        row.find('.unit-name').text('-');
        row.find('.price-input').val(0);
        calcRow(idx);
    });
}

function formatProduct(item) {
    if (!item.id) return item.text;
    let html = `<div style="display:flex;align-items:center;gap:10px;">`;
    if (item.image) {
        html += `<img src="${item.image}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">`;
    }
    html += `<div><strong>${item.text}</strong>`;
    if (item.variation_name) {
        html += `<span style="background:#8b5cf6;color:#fff;padding:2px 6px;border-radius:3px;font-size:10px;margin-left:6px;">${item.variation_name}</span>`;
    }
    html += `<br><small style="color:#888;">SKU: ${item.sku || '-'} | ₹${parseFloat(item.price || 0).toFixed(2)}</small></div></div>`;
    return $(html);
}

function formatProductSelection(item) {
    if (!item.id) return item.text;
    let text = item.text;
    if (item.variation_name) text += ` » ${item.variation_name}`;
    return text;
}

function calcRow(idx) {
    const row = document.getElementById(`row-${idx}`);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = qty * price;
    row.querySelector('.row-total').textContent = `₹${total.toFixed(2)}`;
    calcGrandTotal();
}

function calcGrandTotal() {
    let total = 0;
    document.querySelectorAll('.row-total').forEach(el => {
        total += parseFloat(el.textContent.replace('₹', '').replace(',', '')) || 0;
    });
    document.getElementById('grandTotal').textContent = `₹${total.toFixed(2)}`;
}

// Load existing items
if (existingItems.length) {
    existingItems.forEach(item => addRow(item));
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
</style>
