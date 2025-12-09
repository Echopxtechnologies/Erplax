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
        color: #8b5cf6;
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

    .transfer-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .transfer-section:last-of-type {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-title svg {
        width: 18px;
        height: 18px;
    }
    
    .section-title.from {
        color: #ea580c;
    }
    
    .section-title.to {
        color: #059669;
    }

    .warehouse-grid {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 20px;
        align-items: start;
    }
    
    @media (max-width: 768px) {
        .warehouse-grid {
            grid-template-columns: 1fr;
        }
        .transfer-arrow {
            transform: rotate(90deg);
            justify-self: center;
        }
    }
    
    .warehouse-card {
        background: var(--body-bg);
        border: 2px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
    }
    
    .warehouse-card.from {
        border-color: #fed7aa;
        background: #fff7ed;
    }
    
    .warehouse-card.to {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }
    
    .transfer-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
    }
    
    .transfer-arrow svg {
        width: 40px;
        height: 40px;
        color: #8b5cf6;
    }

    .stock-info {
        background: #fff;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 12px;
        display: none;
    }
    
    .stock-info.show {
        display: block;
    }
    
    .stock-info-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .stock-info-value {
        font-size: 24px;
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
    
    .btn-purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
    }
    
    .btn-purple:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    
    .info-box svg {
        width: 20px;
        height: 20px;
        color: #2563eb;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .info-box p {
        margin: 0;
        font-size: 13px;
        color: #1e40af;
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transfer Stock Between Warehouses
            </h3>
        </div>
        <div class="form-card-body">
            <div class="info-box">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Transfer stock from one warehouse to another. This will create an OUT movement from source and an IN movement to destination.</p>
            </div>

            <form action="{{ route('admin.inventory.stock.transfer.store') }}" method="POST">
                @csrf

                <!-- Product Selection -->
                <div class="transfer-section">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Select Product
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" class="form-control" required onchange="onProductChange()">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-batch="{{ $product->is_batch_managed ? '1' : '0' }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" id="lotGroup" style="display: none;">
                        <label class="form-label">Lot / Batch</label>
                        <select name="lot_id" id="lot_id" class="form-control" onchange="checkSourceStock()">
                            <option value="">-- Select Lot (Optional) --</option>
                        </select>
                        <div class="form-help">Transfer from a specific lot</div>
                    </div>
                </div>

                <!-- Warehouse Transfer -->
                <div class="transfer-section">
                    <div class="warehouse-grid">
                        <!-- Source Warehouse -->
                        <div class="warehouse-card from">
                            <div class="section-title from">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                From (Source)
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Source Warehouse <span class="required">*</span></label>
                                <select name="from_warehouse_id" id="from_warehouse_id" class="form-control" required onchange="checkSourceStock()">
                                    <option value="">-- Select Source --</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} {{ $warehouse->is_default ? '(Default)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="stock-info" id="sourceStockInfo">
                                <div class="stock-info-label">Available Stock</div>
                                <div class="stock-info-value" id="sourceStock">0</div>
                            </div>
                        </div>

                        <!-- Transfer Arrow -->
                        <div class="transfer-arrow">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>

                        <!-- Destination Warehouse -->
                        <div class="warehouse-card to">
                            <div class="section-title to">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                To (Destination)
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Destination Warehouse <span class="required">*</span></label>
                                <select name="to_warehouse_id" id="to_warehouse_id" class="form-control" required onchange="checkDestStock()">
                                    <option value="">-- Select Destination --</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} {{ $warehouse->is_default ? '(Default)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="stock-info" id="destStockInfo">
                                <div class="stock-info-label">Current Stock</div>
                                <div class="stock-info-value" id="destStock" style="color: #059669;">0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Details -->
                <div class="transfer-section">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Transfer Details
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Quantity to Transfer <span class="required">*</span></label>
                            <input type="number" name="qty" id="qty" class="form-control" step="any" min="0" placeholder="Enter quantity" value="{{ old('qty') }}" required>
                            <div class="form-help" id="qtyHelp">Enter the quantity to transfer</div>
                            @error('qty')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reason</label>
                            <select name="reason" class="form-control">
                                <option value="Stock Transfer" {{ old('reason', 'Stock Transfer') == 'Stock Transfer' ? 'selected' : '' }}>Stock Transfer</option>
                                <option value="Rebalancing" {{ old('reason') == 'Rebalancing' ? 'selected' : '' }}>Rebalancing</option>
                                <option value="Fulfillment" {{ old('reason') == 'Fulfillment' ? 'selected' : '' }}>Fulfillment</option>
                                <option value="Consolidation" {{ old('reason') == 'Consolidation' ? 'selected' : '' }}>Consolidation</option>
                                <option value="Other" {{ old('reason') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Additional notes about this transfer...">{{ old('notes') }}</textarea>
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
    
    if (isBatchManaged && productId) {
        document.getElementById('lotGroup').style.display = 'block';
        loadLots(productId);
    } else {
        document.getElementById('lotGroup').style.display = 'none';
        let lotSelect = document.getElementById('lot_id');
        lotSelect.innerHTML = '';
        let defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '-- Select Lot (Optional) --';
        lotSelect.appendChild(defaultOpt);
    }
    
    checkSourceStock();
    checkDestStock();
}

function checkSourceStock() {
    let productId = document.getElementById('product_id').value;
    let warehouseId = document.getElementById('from_warehouse_id').value;
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
                let stockEl = document.getElementById('sourceStock');
                stockEl.textContent = availableStock;
                
                if (availableStock <= 0) {
                    stockEl.classList.add('low');
                } else {
                    stockEl.classList.remove('low');
                }
                
                document.getElementById('sourceStockInfo').classList.add('show');
                document.getElementById('qtyHelp').textContent = 'Max available: ' + availableStock;
            })
            .catch(error => {
                console.error('Error checking source stock:', error);
            });
    } else {
        document.getElementById('sourceStockInfo').classList.remove('show');
    }
    
    validateWarehouses();
}

function checkDestStock() {
    let productId = document.getElementById('product_id').value;
    let warehouseId = document.getElementById('to_warehouse_id').value;
    
    if (productId && warehouseId) {
        let url = '{{ route("admin.inventory.stock.check") }}?product_id=' + productId + '&warehouse_id=' + warehouseId;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById('destStock').textContent = data.quantity || 0;
                document.getElementById('destStockInfo').classList.add('show');
            })
            .catch(error => {
                console.error('Error checking dest stock:', error);
            });
    } else {
        document.getElementById('destStockInfo').classList.remove('show');
    }
    
    validateWarehouses();
}

function validateWarehouses() {
    let fromId = document.getElementById('from_warehouse_id').value;
    let toId = document.getElementById('to_warehouse_id').value;
    let submitBtn = document.getElementById('submitBtn');
    
    if (fromId && toId && fromId === toId) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        alert('Source and destination warehouses cannot be the same!');
    } else {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }
}

function loadLots(productId) {
    let select = document.getElementById('lot_id');
    
    select.innerHTML = '';
    let loadingOpt = document.createElement('option');
    loadingOpt.value = '';
    loadingOpt.textContent = 'Loading lots...';
    select.appendChild(loadingOpt);
    
    fetch('{{ url("admin/inventory/lots/by-product") }}/' + productId)
        .then(response => response.json())
        .then(lots => {
            select.innerHTML = '';
            
            let defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Select Lot (Optional) --';
            select.appendChild(defaultOpt);
            
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
    let productId = document.getElementById('product_id').value;
    if (productId) {
        onProductChange();
    }
});
</script>
</x-layouts.app>