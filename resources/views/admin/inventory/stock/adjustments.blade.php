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
        color: #f59e0b;
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
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 12px 12px 0 0;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #92400e;
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
        background: #fffbeb;
        border: 1px solid #fde68a;
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
        color: #92400e;
        margin-bottom: 4px;
    }
    
    .stock-info-value {
        font-size: 20px;
        font-weight: 700;
        color: #f59e0b;
    }
    
    .stock-info-arrow {
        font-size: 24px;
        color: var(--text-muted);
    }
    
    .stock-info-value.new {
        color: #059669;
    }

    .adjustment-type-group {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .adjustment-type-btn {
        flex: 1;
        padding: 16px;
        border: 2px solid var(--card-border);
        border-radius: 10px;
        background: var(--card-bg);
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }
    
    .adjustment-type-btn:hover {
        border-color: #f59e0b;
    }
    
    .adjustment-type-btn.active {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    
    .adjustment-type-btn input {
        display: none;
    }
    
    .adjustment-type-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }
    
    .adjustment-type-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .adjustment-type-desc {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
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
    
    .btn-amber {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    
    .btn-amber:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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
    
    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            Stock Adjustment
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="alert alert-warning">
        <strong>⚠️ Caution:</strong> Stock adjustments directly modify inventory levels. Use this for inventory counts, corrections, or write-offs. All adjustments are logged for audit purposes.
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Adjust Inventory Levels
            </h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.inventory.stock.adjustments.store') }}" method="POST">
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
                        <div class="form-help">Adjust stock at specific rack</div>
                    </div>
                </div>

                <div class="form-group" id="lotGroup" style="display: none;">
                    <label class="form-label">Lot / Batch</label>
                    <select name="lot_id" id="lot_id" class="form-control" onchange="checkStock()">
                        <option value="">-- Select Lot (Optional) --</option>
                    </select>
                    <div class="form-help">Adjust specific lot if batch managed</div>
                </div>

                <div class="stock-info" id="stockInfo">
                    <div class="stock-info-item">
                        <div class="stock-info-label">Current Stock</div>
                        <div class="stock-info-value"><span id="currentStock">0</span> <small id="stockUnit">PCS</small></div>
                    </div>
                    <div class="stock-info-arrow">→</div>
                    <div class="stock-info-item">
                        <div class="stock-info-label">New Stock</div>
                        <div class="stock-info-value new"><span id="newStock">0</span> <small id="newStockUnit">PCS</small></div>
                    </div>
                </div>

                <!-- Adjustment Type -->
                <div class="form-group">
                    <label class="form-label">Adjustment Type <span class="required">*</span></label>
                    <div class="adjustment-type-group">
                        <label class="adjustment-type-btn" onclick="selectAdjustmentType('set')">
                            <input type="radio" name="adjustment_type" value="set" {{ old('adjustment_type', 'set') == 'set' ? 'checked' : '' }}>
                            <div class="adjustment-type-icon">📊</div>
                            <div class="adjustment-type-label">Set Quantity</div>
                            <div class="adjustment-type-desc">Set exact stock level</div>
                        </label>
                        <label class="adjustment-type-btn" onclick="selectAdjustmentType('add')">
                            <input type="radio" name="adjustment_type" value="add" {{ old('adjustment_type') == 'add' ? 'checked' : '' }}>
                            <div class="adjustment-type-icon">➕</div>
                            <div class="adjustment-type-label">Add</div>
                            <div class="adjustment-type-desc">Increase stock</div>
                        </label>
                        <label class="adjustment-type-btn" onclick="selectAdjustmentType('subtract')">
                            <input type="radio" name="adjustment_type" value="subtract" {{ old('adjustment_type') == 'subtract' ? 'checked' : '' }}>
                            <div class="adjustment-type-icon">➖</div>
                            <div class="adjustment-type-label">Subtract</div>
                            <div class="adjustment-type-desc">Decrease stock</div>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" id="qtyLabel">New Quantity <span class="required">*</span></label>
                        <input type="number" name="qty" id="qty" class="form-control" step="any" min="0" placeholder="Enter quantity" value="{{ old('qty') }}" required oninput="updateNewStock()">
                        <div class="form-help" id="qtyHelp">Enter the new stock quantity</div>
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
                    <label class="form-label">Adjustment Reason <span class="required">*</span></label>
                    <select name="adjustment_reason" id="adjustment_reason" class="form-control" required>
                        <option value="">-- Select Reason --</option>
                        <option value="Physical Count" {{ old('adjustment_reason') == 'Physical Count' ? 'selected' : '' }}>Physical Count / Inventory Audit</option>
                        <option value="Damaged" {{ old('adjustment_reason') == 'Damaged' ? 'selected' : '' }}>Damaged Goods</option>
                        <option value="Expired" {{ old('adjustment_reason') == 'Expired' ? 'selected' : '' }}>Expired Products</option>
                        <option value="Lost" {{ old('adjustment_reason') == 'Lost' ? 'selected' : '' }}>Lost / Missing</option>
                        <option value="Theft" {{ old('adjustment_reason') == 'Theft' ? 'selected' : '' }}>Theft / Shrinkage</option>
                        <option value="Data Correction" {{ old('adjustment_reason') == 'Data Correction' ? 'selected' : '' }}>Data Entry Correction</option>
                        <option value="Opening Stock" {{ old('adjustment_reason') == 'Opening Stock' ? 'selected' : '' }}>Opening Stock Entry</option>
                        <option value="Other" {{ old('adjustment_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('adjustment_reason')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reason" class="form-control" placeholder="e.g., Stock Count Jan 2025, Write-off #123" value="{{ old('reason') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Detailed notes about this adjustment...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-amber">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Apply Adjustment
                    </button>
                    <a href="{{ route('admin.inventory.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentStockValue = 0;
let adjustmentType = 'set';

function selectAdjustmentType(type) {
    adjustmentType = type;
    
    // Update UI
    document.querySelectorAll('.adjustment-type-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.querySelector('input').value === type) {
            btn.classList.add('active');
            btn.querySelector('input').checked = true;
        }
    });
    
    // Update label
    let label = document.getElementById('qtyLabel');
    let help = document.getElementById('qtyHelp');
    
    if (type === 'set') {
        label.innerHTML = 'New Quantity <span class="required">*</span>';
        help.textContent = 'Enter the new stock quantity';
    } else if (type === 'add') {
        label.innerHTML = 'Quantity to Add <span class="required">*</span>';
        help.textContent = 'Enter quantity to add to current stock';
    } else {
        label.innerHTML = 'Quantity to Subtract <span class="required">*</span>';
        help.textContent = 'Enter quantity to subtract from current stock';
    }
    
    updateNewStock();
}

function updateNewStock() {
    let qty = parseFloat(document.getElementById('qty').value) || 0;
    let newStock = 0;
    
    if (adjustmentType === 'set') {
        newStock = qty;
    } else if (adjustmentType === 'add') {
        newStock = currentStockValue + qty;
    } else {
        newStock = currentStockValue - qty;
    }
    
    document.getElementById('newStock').textContent = Math.max(0, newStock).toFixed(2);
}

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
                currentStockValue = parseFloat(data.quantity) || 0;
                let unitName = data.unit || 'PCS';
                
                document.getElementById('currentStock').textContent = currentStockValue;
                document.getElementById('stockUnit').textContent = unitName;
                document.getElementById('newStockUnit').textContent = unitName;
                document.getElementById('stockInfo').classList.add('show');
                
                updateNewStock();
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
    // Set initial adjustment type
    let checkedType = document.querySelector('input[name="adjustment_type"]:checked');
    if (checkedType) {
        selectAdjustmentType(checkedType.value);
    } else {
        selectAdjustmentType('set');
    }
    
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