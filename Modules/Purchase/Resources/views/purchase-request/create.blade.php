<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-success { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; }
.btn-sm { padding: 6px 12px; font-size: 13px; }
.btn-danger-outline { background: #fff; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 8px; }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }

.form-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; }
.form-group.col-2 { grid-column: span 2; }
.form-group.col-full { grid-column: 1 / -1; }

.form-label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-label .required { color: #ef4444; }
.form-control { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; width: 100%; box-sizing: border-box; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-control:read-only { background: #f9fafb; color: #6b7280; }
.form-control-sm { padding: 8px 12px; font-size: 13px; }
select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 10px center; background-repeat: no-repeat; background-size: 16px; padding-right: 36px; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert ul { margin: 0; padding-left: 20px; }

.items-table { width: 100%; border-collapse: collapse; }
.items-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 2px solid #e5e7eb; }
.items-table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
.items-table tfoot td { background: #f9fafb; font-weight: 600; }
.items-table .text-center { text-align: center; }
.items-table .text-end { text-align: right; }

.unit-display { padding: 8px 12px; background: #f3f4f6; border-radius: 6px; font-size: 13px; color: #374151; min-height: 36px; display: flex; align-items: center; }

#grandTotal { font-size: 18px; color: #4f46e5; }
.form-actions { display: flex; gap: 12px; margin-top: 24px; }

@media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .form-group.col-2 { grid-column: span 1; } }
</style>

<div class="page-header">
    <h1>New Purchase Request</h1>
    <a href="{{ route('admin.purchase.requests.index') }}" class="btn btn-outline">Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.requests.store') }}" method="POST" id="prForm">
    @csrf

    <div class="card">
        <div class="card-header"><h5>Request Details</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">PR Number</label>
                    <input type="text" class="form-control" value="{{ $prNumber }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Date <span class="required">*</span></label>
                    <input type="date" name="pr_date" class="form-control" value="{{ old('pr_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Required By</label>
                    <input type="date" name="required_date" class="form-control" value="{{ old('required_date') }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Priority <span class="required">*</span></label>
                    <select name="priority" class="form-control" required>
                        <option value="LOW">Low</option>
                        <option value="NORMAL" selected>Normal</option>
                        <option value="HIGH">High</option>
                        <option value="URGENT">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="e.g., Production, IT">
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Purpose</label>
                    <input type="text" name="purpose" class="form-control" value="{{ old('purpose') }}" placeholder="Brief description">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Items <span class="required">*</span></h5>
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
                    <tr class="item-row" data-idx="0">
                        <td class="text-center row-num">1</td>
                        <td>
                            <select name="items[0][product_id]" class="form-control form-control-sm product-select" required onchange="onProductChange(this)">
                                <option value="">Select Product</option>
                                @foreach($products as $p)
                                <option value="{{ $p->id }}" data-unit="{{ $p->unit->short_name ?? $p->unit->name ?? '-' }}" data-unit-id="{{ $p->unit_id }}" data-price="{{ $p->sale_price ?? $p->mrp ?? 0 }}">{{ $p->sku ? $p->sku.' - ' : '' }}{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="items[0][unit_id]" class="unit-id-input">
                        </td>
                        <td><div class="unit-display unit-text">-</div></td>
                        <td><input type="number" name="items[0][qty]" class="form-control form-control-sm qty-input" step="0.001" min="0.001" required onchange="calcRow(this)"></td>
                        <td><input type="number" name="items[0][estimated_price]" class="form-control form-control-sm price-input" step="0.01" min="0" onchange="calcRow(this)"></td>
                        <td class="text-end"><span class="row-total">0.00</span></td>
                        <td class="text-center"><button type="button" class="btn btn-danger-outline" onclick="removeRow(this)" disabled>×</button></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">Estimated Total:</td>
                        <td class="text-end"><span id="grandTotal">₹0.00</span></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Notes</h5></div>
        <div class="card-body">
            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save as Draft</button>
        <a href="{{ route('admin.purchase.requests.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

<script>
let idx = 1;
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
        priceInput.value = parseFloat(opt.dataset.price || 0).toFixed(2);
    } else {
        unitText.textContent = '-';
        unitInput.value = '';
        priceInput.value = '';
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
}

function removeRow(btn) {
    if (document.querySelectorAll('.item-row').length > 1) {
        btn.closest('tr').remove();
        updateRowNumbers();
        calcTotal();
    }
}

function updateRowNumbers() {
    document.querySelectorAll('.item-row').forEach((row, i) => {
        row.querySelector('.row-num').textContent = i + 1;
    });
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
