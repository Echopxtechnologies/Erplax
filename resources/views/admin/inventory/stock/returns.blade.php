<x-layouts.app>
<style>
    .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .back-btn:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .back-btn svg {
        width: 20px;
        height: 20px;
    }
    
    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-header h1 svg {
        width: 28px;
        height: 28px;
        color: #0891b2;
    }

    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        max-width: 700px;
    }
    
    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        background: linear-gradient(135deg, #cffafe, #a5f3fc);
        border-radius: 12px 12px 0 0;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #155e75;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-card-title svg {
        width: 20px;
        height: 20px;
    }
    
    .form-card-body {
        padding: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .form-label .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
    }
    
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .form-help {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .stock-info {
        background: #ecfeff;
        border: 1px solid #a5f3fc;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: none;
    }
    
    .stock-info.show {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .stock-info-item {
        flex: 1;
    }
    
    .stock-info-label {
        font-size: 12px;
        color: #155e75;
        margin-bottom: 4px;
    }
    
    .stock-info-value {
        font-size: 20px;
        font-weight: 700;
        color: #0891b2;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid var(--card-border);
        margin-top: 24px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 18px;
        height: 18px;
    }
    
    .btn-cyan {
        background: linear-gradient(135deg, #0891b2, #0e7490);
        color: #fff;
    }
    
    .btn-cyan:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.inventory.dashboard') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Stock Returns
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Return Goods to Stock
            </h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.inventory.stock.returns.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Product <span class="required">*</span></label>
                    <select name="product_id" id="product_id" class="form-control" required onchange="onProductChange()">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                data-batch="{{ $product->is_batch_managed ? '1' : '0' }}"
                                data-unit="{{ $product->unit_id }}"
                                {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warehouse <span class="required">*</span></label>
                        <select name="warehouse_id" id="warehouse_id" class="form-control" required onchange="onWarehouseChange()">
                            <option value="">-- Select Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $warehouse->is_default ? 'selected' : '' }}>
                                    {{ $warehouse->name }} {{ $warehouse->is_default ? '(Default)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rack / Location</label>
                        <select name="rack_id" id="rack_id" class="form-control" onchange="checkStock()">
                            <option value="">-- Select Rack (Optional) --</option>
                        </select>
                        <div class="form-help">Return to specific rack location</div>
                    </div>
                </div>

                <div class="form-group" id="lotGroup" style="display: none;">
                    <label class="form-label">Lot / Batch</label>
                    <select name="lot_id" id="lot_id" class="form-control" onchange="checkStock()">
                        <option value="">-- Select Lot (Optional) --</option>
                    </select>
                    <div class="form-help">Return to specific lot if batch managed</div>
                </div>

                <div class="stock-info" id="stockInfo">
                    <div class="stock-info-item">
                        <div class="stock-info-label">Current Stock</div>
                        <div class="stock-info-value"><span id="currentStock">0</span> <small id="stockUnit">PCS</small></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Return Quantity <span class="required">*</span></label>
                        <input type="number" name="qty" class="form-control" step="any" min="0.001" placeholder="Enter quantity" value="{{ old('qty') }}" required>
                        @error('qty')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit <span class="required">*</span></label>
                        <select name="unit_id" id="unit_id" class="form-control" required>
                            <option value="">-- Select Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Return Reason <span class="required">*</span></label>
                    <select name="return_reason" class="form-control" required>
                        <option value="">-- Select Reason --</option>
                        <option value="Customer Return" {{ old('return_reason') == 'Customer Return' ? 'selected' : '' }}>Customer Return</option>
                        <option value="Damaged Goods" {{ old('return_reason') == 'Damaged Goods' ? 'selected' : '' }}>Damaged Goods</option>
                        <option value="Wrong Delivery" {{ old('return_reason') == 'Wrong Delivery' ? 'selected' : '' }}>Wrong Delivery</option>
                        <option value="Quality Issue" {{ old('return_reason') == 'Quality Issue' ? 'selected' : '' }}>Quality Issue</option>
                        <option value="Excess Stock" {{ old('return_reason') == 'Excess Stock' ? 'selected' : '' }}>Excess Stock</option>
                        <option value="Other" {{ old('return_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('return_reason')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reason" class="form-control" placeholder="e.g., Return Order #789, Invoice #456" value="{{ old('reason') }}">
                    <div class="form-help">Reference number or additional context</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Additional notes about the return...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-cyan">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Process Return
                    </button>
                    <a href="{{ route('admin.inventory.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function onProductChange() {
    let productId = document.getElementById('product_id').value;
    let selectedOption = document.getElementById('product_id').selectedOptions[0];
    let isBatchManaged = selectedOption && selectedOption.dataset.batch === '1';
    let productUnitId = selectedOption ? selectedOption.dataset.unit : '';
    
    // Set default unit based on product
    if (productUnitId) {
        document.getElementById('unit_id').value = productUnitId;
    }
    
    if (isBatchManaged && productId) {
        document.getElementById('lotGroup').style.display = 'block';
        loadLots(productId);
    } else {
        document.getElementById('lotGroup').style.display = 'none';
        let lotSelect = document.getElementById('lot_id');
        lotSelect.innerHTML = '<option value="">-- Select Lot (Optional) --</option>';
    }
    
    checkStock();
}

function onWarehouseChange() {
    let warehouseId = document.getElementById('warehouse_id').value;
    loadRacks(warehouseId);
    checkStock();
}

function loadRacks(warehouseId) {
    let select = document.getElementById('rack_id');
    select.innerHTML = '<option value="">-- Select Rack (Optional) --</option>';
    
    if (!warehouseId) return;
    
    fetch('{{ url("admin/inventory/racks/by-warehouse") }}/' + warehouseId)
        .then(response => response.json())
        .then(racks => {
            if (racks && racks.length > 0) {
                racks.forEach(function(rack) {
                    let option = document.createElement('option');
                    option.value = rack.id;
                    option.textContent = rack.code + ' - ' + rack.name + (rack.zone ? ' (' + rack.zone + ')' : '');
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading racks:', error));
}

function checkStock() {
    let productId = document.getElementById('product_id').value;
    let warehouseId = document.getElementById('warehouse_id').value;
    let rackId = document.getElementById('rack_id').value;
    let lotId = document.getElementById('lot_id').value;
    
    if (productId && warehouseId) {
        let url = '{{ route("admin.inventory.stock.check") }}?product_id=' + productId + '&warehouse_id=' + warehouseId;
        if (rackId) url += '&rack_id=' + rackId;
        if (lotId) url += '&lot_id=' + lotId;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById('currentStock').textContent = data.quantity || 0;
                document.getElementById('stockUnit').textContent = data.unit || 'PCS';
                document.getElementById('stockInfo').classList.add('show');
            })
            .catch(error => console.error('Error checking stock:', error));
    } else {
        document.getElementById('stockInfo').classList.remove('show');
    }
}

function loadLots(productId) {
    let select = document.getElementById('lot_id');
    select.innerHTML = '<option value="">Loading lots...</option>';
    
    fetch('{{ url("admin/inventory/lots/by-product") }}/' + productId)
        .then(response => response.json())
        .then(lots => {
            select.innerHTML = '<option value="">-- Select Lot (Optional) --</option>';
            if (lots && lots.length > 0) {
                lots.forEach(function(lot) {
                    let option = document.createElement('option');
                    option.value = lot.id;
                    let text = lot.lot_no;
                    if (lot.expiry_date) text += ' (Exp: ' + lot.expiry_date + ')';
                    option.textContent = text;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading lots:', error);
            select.innerHTML = '<option value="">-- Select Lot (Optional) --</option>';
        });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    let warehouseId = document.getElementById('warehouse_id').value;
    if (warehouseId) {
        loadRacks(warehouseId);
    }
    
    let productId = document.getElementById('product_id').value;
    if (productId) {
        onProductChange();
    }
});
</script>
</x-layouts.app>