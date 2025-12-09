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
        max-width: 700px;
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
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
        display: none;
    }
    
    .stock-info.show {
        display: block;
    }
    
    .stock-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        text-align: center;
    }
    
    .stock-info-item {
        padding: 8px;
    }
    
    .stock-info-label {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stock-info-value {
        font-size: 24px;
        font-weight: 700;
    }
    
    .stock-current {
        color: #7c3aed;
    }
    
    .stock-new {
        color: #059669;
    }
    
    .stock-diff {
        color: #ea580c;
    }
    
    .stock-diff.positive {
        color: #059669;
    }
    
    .stock-diff.negative {
        color: #dc2626;
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

    .warning-box {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    
    .warning-box svg {
        width: 20px;
        height: 20px;
        color: #d97706;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .warning-box p {
        margin: 0;
        font-size: 13px;
        color: #92400e;
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

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Adjust Stock Level
            </h3>
        </div>
        <div class="form-card-body">
            <div class="warning-box">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p><strong>Caution:</strong> Stock adjustments directly modify inventory levels. This should be used for physical inventory counts, damaged goods, or corrections. All adjustments are logged.</p>
            </div>

            <form action="{{ route('admin.inventory.stock.adjustments.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Product <span class="required">*</span></label>
                    <select name="product_id" id="product_id" class="form-control" required onchange="onProductChange()">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                data-batch="{{ $product->is_batch_managed ? '1' : '0' }}"
                                {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Warehouse <span class="required">*</span></label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control" required onchange="checkStock()">
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouse->is_default ? 'selected' : '' }}>
                                {{ $warehouse->name }} {{ $warehouse->is_default ? '(Default)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" id="lotGroup" style="display: none;">
                    <label class="form-label">Lot / Batch</label>
                    <select name="lot_id" id="lot_id" class="form-control" onchange="checkStock()">
                        <option value="">-- Select Lot (Optional) --</option>
                    </select>
                    <div class="form-help">Adjust stock for a specific lot</div>
                </div>

                <div class="stock-info" id="stockInfo">
                    <div class="stock-info-grid">
                        <div class="stock-info-item">
                            <div class="stock-info-label">Current Stock</div>
                            <div class="stock-info-value stock-current" id="currentStock">0</div>
                        </div>
                        <div class="stock-info-item">
                            <div class="stock-info-label">New Stock</div>
                            <div class="stock-info-value stock-new" id="newStock">0</div>
                        </div>
                        <div class="stock-info-item">
                            <div class="stock-info-label">Difference</div>
                            <div class="stock-info-value stock-diff" id="stockDiff">0</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Quantity <span class="required">*</span></label>
                        <input type="number" name="new_qty" id="new_qty" class="form-control" step="any" min="0" placeholder="Enter new stock level" value="{{ old('new_qty') }}" required oninput="calculateDiff()">
                        <div class="form-help">Enter the actual physical count</div>
                        @error('new_qty')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reason <span class="required">*</span></label>
                        <select name="reason" class="form-control" required>
                            <option value="">-- Select Reason --</option>
                            <option value="Physical Count" {{ old('reason') == 'Physical Count' ? 'selected' : '' }}>Physical Count</option>
                            <option value="Damaged Goods" {{ old('reason') == 'Damaged Goods' ? 'selected' : '' }}>Damaged Goods</option>
                            <option value="Expired Goods" {{ old('reason') == 'Expired Goods' ? 'selected' : '' }}>Expired Goods</option>
                            <option value="Lost/Theft" {{ old('reason') == 'Lost/Theft' ? 'selected' : '' }}>Lost/Theft</option>
                            <option value="Data Correction" {{ old('reason') == 'Data Correction' ? 'selected' : '' }}>Data Correction</option>
                            <option value="Opening Balance" {{ old('reason') == 'Opening Balance' ? 'selected' : '' }}>Opening Balance</option>
                            <option value="Other" {{ old('reason') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Additional details about this adjustment...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-purple">
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

function onProductChange() {
    let productId = document.getElementById('product_id').value;
    let selectedOption = document.getElementById('product_id').selectedOptions[0];
    let isBatchManaged = selectedOption && selectedOption.dataset.batch === '1';
    
    if (isBatchManaged && productId) {
        document.getElementById('lotGroup').style.display = 'block';
        loadLots(productId);
    } else {
        document.getElementById('lotGroup').style.display = 'none';
        // Reset lot dropdown
        let lotSelect = document.getElementById('lot_id');
        lotSelect.innerHTML = '';
        let defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Select Lot (Optional) --';
        lotSelect.appendChild(defaultOpt);
    }
    
    checkStock();
}

function checkStock() {
    let productId = document.getElementById('product_id').value;
    let warehouseId = document.getElementById('warehouse_id').value;
    let lotSelect = document.getElementById('lot_id');
    let lotId = lotSelect ? lotSelect.value : '';
    
    if (productId && warehouseId) {
        let url = '{{ route("admin.inventory.stock.check") }}?product_id=' + productId + '&warehouse_id=' + warehouseId;
        if (lotId) {
            url += '&lot_id=' + lotId;
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                currentStockValue = parseFloat(data.quantity) || 0;
                document.getElementById('currentStock').textContent = currentStockValue;
                document.getElementById('stockInfo').classList.add('show');
                calculateDiff();
            })
            .catch(error => {
                console.error('Error checking stock:', error);
            });
    } else {
        document.getElementById('stockInfo').classList.remove('show');
    }
}

function loadLots(productId) {
    let select = document.getElementById('lot_id');
    
    // Clear and add loading option
    select.innerHTML = '';
    let loadingOpt = document.createElement('option');
    loadingOpt.value = '';
    loadingOpt.textContent = 'Loading lots...';
    select.appendChild(loadingOpt);
    
    fetch('{{ url("admin/inventory/lots/by-product") }}/' + productId)
        .then(response => response.json())
        .then(lots => {
            // Clear select
            select.innerHTML = '';
            
            // Add default option
            let defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Select Lot (Optional) --';
            select.appendChild(defaultOpt);
            
            // Add lot options
            if (lots && lots.length > 0) {
                lots.forEach(function(lot) {
                    let option = document.createElement('option');
                    option.value = lot.id;
                    let text = lot.lot_no;
                    if (lot.expiry_date) {
                        text += ' (Exp: ' + lot.expiry_date + ')';
                    }
                    option.textContent = text;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading lots:', error);
            select.innerHTML = '';
            let errorOpt = document.createElement('option');
            errorOpt.value = '';
            errorOpt.textContent = '-- Select Lot (Optional) --';
            select.appendChild(errorOpt);
        });
}

function calculateDiff() {
    let newQty = parseFloat(document.getElementById('new_qty').value) || 0;
    let diff = newQty - currentStockValue;
    
    document.getElementById('newStock').textContent = newQty;
    
    let diffEl = document.getElementById('stockDiff');
    diffEl.textContent = (diff >= 0 ? '+' : '') + diff.toFixed(2);
    diffEl.classList.remove('positive', 'negative');
    
    if (diff > 0) {
        diffEl.classList.add('positive');
    } else if (diff < 0) {
        diffEl.classList.add('negative');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if product is pre-selected
    let productId = document.getElementById('product_id').value;
    if (productId) {
        onProductChange();
    } else {
        checkStock();
    }
});
</script>
</x-layouts.app>