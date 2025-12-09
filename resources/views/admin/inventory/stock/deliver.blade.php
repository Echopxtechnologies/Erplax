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
        color: #ea580c;
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
        background: linear-gradient(135deg, #ffedd5, #fed7aa);
        border-radius: 12px 12px 0 0;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #9a3412;
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
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: none;
    }
    
    .stock-info.show {
        display: block;
    }
    
    .stock-info-label {
        font-size: 12px;
        color: #9a3412;
        margin-bottom: 4px;
    }
    
    .stock-info-value {
        font-size: 20px;
        font-weight: 700;
        color: #ea580c;
    }
    
    .stock-info-value.low {
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
    
    .btn-orange {
        background: linear-gradient(135deg, #ea580c, #c2410c);
        color: #fff;
    }
    
    .btn-orange:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
            Deliver Stock
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                Stock Out - Deliver Goods
            </h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.inventory.stock.deliver.store') }}" method="POST">
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
                    <div class="form-help">Select a specific lot to deliver from</div>
                </div>

                <div class="stock-info" id="stockInfo">
                    <div class="stock-info-label">Available Stock</div>
                    <div class="stock-info-value" id="currentStock">0</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quantity <span class="required">*</span></label>
                        <input type="number" name="qty" id="qty" class="form-control" step="any" min="0" placeholder="Enter quantity" value="{{ old('qty') }}" required>
                        <div class="form-help" id="qtyHelp">Enter quantity to deliver</div>
                        @error('qty')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g., Sales Order #456" value="{{ old('reason', 'Stock delivered') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-orange">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        Deliver Stock
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
                availableStock = parseFloat(data.quantity) || 0;
                let stockEl = document.getElementById('currentStock');
                stockEl.textContent = availableStock;
                
                if (availableStock <= 0) {
                    stockEl.classList.add('low');
                } else {
                    stockEl.classList.remove('low');
                }
                
                document.getElementById('stockInfo').classList.add('show');
                document.getElementById('qtyHelp').textContent = 'Max available: ' + availableStock;
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