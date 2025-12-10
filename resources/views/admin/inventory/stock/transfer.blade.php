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
        color: #7c3aed;
    }

    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        max-width: 800px;
    }
    
    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        border-radius: 12px 12px 0 0;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #5b21b6;
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

    .form-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .form-section-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-section-title svg {
        width: 16px;
        height: 16px;
    }
    
    .form-section-title.from {
        color: #dc2626;
    }
    
    .form-section-title.to {
        color: #059669;
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
        background: #fef3c7;
        border: 1px solid #fcd34d;
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
        color: #d97706;
    }
    
    .stock-info-value.low {
        color: #dc2626;
    }

    .transfer-arrow {
        display: flex;
        justify-content: center;
        padding: 16px 0;
    }
    
    .transfer-arrow svg {
        width: 32px;
        height: 32px;
        color: #7c3aed;
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
    
    .btn-purple {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: #fff;
    }
    
    .btn-purple:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }
    
    .btn-purple:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
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
        border: 1px solid #fde68a;
    }
    
    .location-warning {
        display: none;
        margin-top: 16px;
    }
    
    .location-warning.show {
        display: flex;
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Stock Transfer
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transfer Stock Between Locations
            </h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.inventory.stock.transfer.store') }}" method="POST" id="transferForm">
                @csrf

                <!-- Product Selection -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" class="form-control" required onchange="onProductChange()">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-batch="{{ $product->is_batch_managed ? '1' : '0' }}"
                                    data-unit="{{ $product->unit_id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" id="lotGroup" style="display: none;">
                        <label class="form-label">Lot / Batch</label>
                        <select name="lot_id" id="lot_id" class="form-control" onchange="checkStock()">
                            <option value="">-- Select Lot (Optional) --</option>
                        </select>
                        <div class="form-help">Transfer specific lot/batch</div>
                    </div>
                </div>

                <!-- Source Location -->
                <div class="form-section">
                    <div class="form-section-title from">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        From (Source)
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Source Warehouse <span class="required">*</span></label>
                            <select name="from_warehouse_id" id="from_warehouse_id" class="form-control" required onchange="onFromWarehouseChange()">
                                <option value="">-- Select Source --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Source Rack</label>
                            <select name="from_rack_id" id="from_rack_id" class="form-control" onchange="checkStock(); validateLocation();">
                                <option value="">-- Select Rack (Optional) --</option>
                            </select>
                            <div class="form-help">Pick from specific rack</div>
                        </div>
                    </div>

                    <div class="stock-info" id="stockInfo">
                        <div class="stock-info-item">
                            <div class="stock-info-label">Available at Source</div>
                            <div class="stock-info-value" id="currentStockWrapper">
                                <span id="currentStock">0</span> <small id="stockUnit">PCS</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Arrow -->
                <div class="transfer-arrow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>

                <!-- Destination Location -->
                <div class="form-section">
                    <div class="form-section-title to">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        To (Destination)
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Destination Warehouse <span class="required">*</span></label>
                            <select name="to_warehouse_id" id="to_warehouse_id" class="form-control" required onchange="onToWarehouseChange()">
                                <option value="">-- Select Destination --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">Can be same warehouse for rack-to-rack transfer</div>
                            @error('to_warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Destination Rack</label>
                            <select name="to_rack_id" id="to_rack_id" class="form-control" onchange="validateLocation()">
                                <option value="">-- Select Rack (Optional) --</option>
                            </select>
                            <div class="form-help">Place in specific rack</div>
                        </div>
                    </div>
                    
                    <!-- Location Validation Warning -->
                    <div class="alert alert-warning location-warning" id="locationWarning">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Source and destination are the same. Choose a different warehouse or rack.</span>
                    </div>
                </div>

                <!-- Quantity & Details -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Quantity <span class="required">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0.001" placeholder="Enter quantity" value="{{ old('qty') }}" required>
                            <div class="form-help" id="qtyHelp">Enter quantity to transfer</div>
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
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g., Restock retail store, Rack reorganization" value="{{ old('reason', 'Stock Transfer') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-purple" id="submitBtn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Transfer Stock
                    </button>
                    <a href="{{ route('admin.inventory.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let availableStock = 0;

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

function onFromWarehouseChange() {
    let warehouseId = document.getElementById('from_warehouse_id').value;
    loadRacks(warehouseId, 'from_rack_id');
    checkStock();
    validateLocation();
}

function onToWarehouseChange() {
    let warehouseId = document.getElementById('to_warehouse_id').value;
    loadRacks(warehouseId, 'to_rack_id');
    validateLocation();
}

function loadRacks(warehouseId, selectId) {
    let select = document.getElementById(selectId);
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
            validateLocation();
        })
        .catch(error => console.error('Error loading racks:', error));
}

function validateLocation() {
    let fromWarehouse = document.getElementById('from_warehouse_id').value;
    let toWarehouse = document.getElementById('to_warehouse_id').value;
    let fromRack = document.getElementById('from_rack_id').value;
    let toRack = document.getElementById('to_rack_id').value;
    
    let warningEl = document.getElementById('locationWarning');
    let submitBtn = document.getElementById('submitBtn');
    
    // Check if source and destination are exactly the same
    let isSameLocation = (fromWarehouse && toWarehouse && fromWarehouse === toWarehouse && fromRack === toRack);
    
    if (isSameLocation) {
        warningEl.classList.add('show');
        submitBtn.disabled = true;
    } else {
        warningEl.classList.remove('show');
        submitBtn.disabled = false;
    }
}

function checkStock() {
    let productId = document.getElementById('product_id').value;
    let warehouseId = document.getElementById('from_warehouse_id').value;
    let rackId = document.getElementById('from_rack_id').value;
    let lotId = document.getElementById('lot_id').value;
    
    if (productId && warehouseId) {
        let url = '{{ route("admin.inventory.stock.check") }}?product_id=' + productId + '&warehouse_id=' + warehouseId;
        if (rackId) url += '&rack_id=' + rackId;
        if (lotId) url += '&lot_id=' + lotId;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                availableStock = parseFloat(data.quantity) || 0;
                let stockEl = document.getElementById('currentStock');
                let wrapperEl = document.getElementById('currentStockWrapper');
                stockEl.textContent = availableStock;
                document.getElementById('stockUnit').textContent = data.unit || 'PCS';
                
                if (availableStock <= 0) {
                    wrapperEl.classList.add('low');
                } else {
                    wrapperEl.classList.remove('low');
                }
                
                document.getElementById('stockInfo').classList.add('show');
                document.getElementById('qtyHelp').textContent = 'Max available: ' + availableStock + ' ' + (data.unit || 'PCS');
            })
            .catch(error => console.error('Error checking stock:', error));
    } else {
        document.getElementById('stockInfo').classList.remove('show');
        document.getElementById('qtyHelp').textContent = 'Enter quantity to transfer';
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

// Form validation before submit
document.getElementById('transferForm').addEventListener('submit', function(e) {
    let fromWarehouse = document.getElementById('from_warehouse_id').value;
    let toWarehouse = document.getElementById('to_warehouse_id').value;
    let fromRack = document.getElementById('from_rack_id').value;
    let toRack = document.getElementById('to_rack_id').value;
    
    // Check if source and destination are exactly the same
    if (fromWarehouse === toWarehouse && fromRack === toRack) {
        e.preventDefault();
        alert('Source and destination must be different. Choose a different warehouse or rack.');
        return false;
    }
    
    // Check available stock
    let qty = parseFloat(document.getElementById('qty').value) || 0;
    if (qty > availableStock) {
        e.preventDefault();
        alert('Transfer quantity (' + qty + ') exceeds available stock (' + availableStock + ')');
        return false;
    }
    
    return true;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    let fromWarehouseId = document.getElementById('from_warehouse_id').value;
    if (fromWarehouseId) {
        loadRacks(fromWarehouseId, 'from_rack_id');
    }
    
    let toWarehouseId = document.getElementById('to_warehouse_id').value;
    if (toWarehouseId) {
        loadRacks(toWarehouseId, 'to_rack_id');
    }
    
    let productId = document.getElementById('product_id').value;
    if (productId) {
        onProductChange();
    }
    
    validateLocation();
});
</script>
</x-layouts.app>