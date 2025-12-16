@include('purchase::partials.styles')


<div class="page-header">
    <h1>Edit {{ $pr->pr_number }}</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.purchase.requests.show', $pr->id) }}" class="btn btn-outline">View</a>
        <a href="{{ route('admin.purchase.requests.index') }}" class="btn btn-outline">Back</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.requests.update', $pr->id) }}" method="POST">
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
                    <input type="text" class="form-control" value="{{ $pr->pr_date->format('d M Y') }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Required By</label>
                    <input type="date" name="required_date" class="form-control" value="{{ $pr->required_date?->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Priority <span class="required">*</span></label>
                    <select name="priority" class="form-control" required>
                        <option value="LOW" {{ $pr->priority == 'LOW' ? 'selected' : '' }}>Low</option>
                        <option value="NORMAL" {{ $pr->priority == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                        <option value="HIGH" {{ $pr->priority == 'HIGH' ? 'selected' : '' }}>High</option>
                        <option value="URGENT" {{ $pr->priority == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $pr->department) }}">
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Purpose</label>
                    <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $pr->purpose) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items</h5>
            <button type="button" class="btn btn-success btn-sm" onclick="addItemRow()">+ Add Item</button>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="min-width: 250px;">Product <span class="required">*</span></th>
                        <th style="width: 80px;">Unit</th>
                        <th style="width: 100px;">Qty <span class="required">*</span></th>
                        <th style="width: 120px;">Est. Price</th>
                        <th style="width: 120px;">Total</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    @foreach($pr->items as $i => $item)
                    @php
                        $itemProduct = $products->firstWhere('id', $item->product_id);
                        $unitName = $itemProduct ? ($itemProduct->unit->short_name ?? $itemProduct->unit->name ?? '-') : '-';
                    @endphp
                    <tr class="item-row" data-idx="{{ $i }}">
                        <td class="text-center row-num">{{ $i + 1 }}</td>
                        <td>
                            <select name="items[{{ $i }}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">
                                <option value="">Select Product</option>
                                @foreach($products as $p)
                                <option value="{{ $p->id }}" data-unit="{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}" data-unit-id="{{ $p->unit_id }}" data-price="{{ $p->sale_price ?? $p->mrp ?? 0 }}" {{ $item->product_id == $p->id ? 'selected' : '' }}>{{ $p->sku ? $p->sku.' - ' : '' }}{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="items[{{ $i }}][unit_id]" class="unit-id-input" value="{{ $item->unit_id }}">
                        </td>
                        <td><div class="unit-display unit-text">{{ $unitName }}</div></td>
                        <td><input type="number" name="items[{{ $i }}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" value="{{ $item->qty }}" required onchange="calcRow(this)"></td>
                        <td><input type="number" name="items[{{ $i }}][estimated_price]" class="form-control form-control-sm price-input" step="0.01" min="0" value="{{ $item->estimated_price }}" onchange="calcRow(this)"></td>
                        <td class="text-end"><span class="row-total">{{ number_format($item->estimated_total, 2) }}</span></td>
                        <td class="text-center"><button type="button" class="btn btn-danger-outline" onclick="removeRow(this)" {{ $pr->items->count() <= 1 ? 'disabled' : '' }}>×</button></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">Estimated Total:</td>
                        <td class="text-end"><span id="grandTotal">₹{{ number_format($pr->total_estimated, 2) }}</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Notes</h5></div>
        <div class="card-body">
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $pr->notes) }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-danger" onclick="if(confirm('Delete this PR?')) document.getElementById('deleteForm').submit();">Delete</button>
        <div class="form-actions-right">
            <a href="{{ route('admin.purchase.requests.show', $pr->id) }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

<form id="deleteForm" action="{{ route('admin.purchase.requests.destroy', $pr->id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<script>
let idx = {{ $pr->items->count() }};
const productsData = [
    @foreach($products as $p)
    {
        id: {{ $p->id }},
        sku: "{{ $p->sku ?? '' }}",
        name: "{{ addslashes($p->name) }}",
        unit: "{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}",
        unit_id: {{ $p->unit_id ?? 'null' }},
        price: {{ $p->sale_price ?? $p->mrp ?? 0 }}
    },
    @endforeach
];

function onProductChange(select) {
    const row = select.closest('tr');
    const opt = select.options[select.selectedIndex];
    const unitText = row.querySelector('.unit-text');
    const unitInput = row.querySelector('.unit-id-input');
    const priceInput = row.querySelector('.price-input');
    
    if (opt.value) {
        unitText.textContent = opt.dataset.unit || '-';
        unitInput.value = opt.dataset.unitId || '';
        if (!priceInput.value) priceInput.value = parseFloat(opt.dataset.price || 0).toFixed(2);
    } else {
        unitText.textContent = '-';
        unitInput.value = '';
    }
    calcRow(select);
}

function addItemRow() {
    let opts = '<option value="">Select Product</option>';
    productsData.forEach(p => {
        opts += `<option value="${p.id}" data-unit="${p.unit}" data-unit-id="${p.unit_id}" data-price="${p.price}">${p.sku ? p.sku+' - ' : ''}${p.name}</option>`;
    });
    
    const row = `<tr class="item-row" data-idx="${idx}">
        <td class="text-center row-num">${document.querySelectorAll('.item-row').length + 1}</td>
        <td>
            <select name="items[${idx}][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">${opts}</select>
            <input type="hidden" name="items[${idx}][unit_id]" class="unit-id-input">
        </td>
        <td><div class="unit-display unit-text">-</div></td>
        <td><input type="number" name="items[${idx}][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" required onchange="calcRow(this)"></td>
        <td><input type="number" name="items[${idx}][estimated_price]" class="form-control form-control-sm price-input" step="0.01" min="0" onchange="calcRow(this)"></td>
        <td class="text-end"><span class="row-total">0.00</span></td>
        <td class="text-center"><button type="button" class="btn btn-danger-outline" onclick="removeRow(this)">×</button></td>
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
        calcTotal();
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
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    row.querySelector('.row-total').textContent = (qty * price).toFixed(2);
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.row-total').forEach(el => total += parseFloat(el.textContent) || 0);
    document.getElementById('grandTotal').textContent = '₹' + total.toFixed(2);
}
</script>
