<x-layouts.app>
<style>
    .page-container {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
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
        flex-shrink: 0;
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
    }

    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
    }
    
    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .form-card-body {
        padding: 24px;
    }

    .form-section {
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
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
    
    .form-control.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .form-control.is-valid {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .form-control.is-checking {
        border-color: #f59e0b;
        background: #fffbeb;
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
    
    .form-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-error svg {
        width: 14px;
        height: 14px;
    }
    
    .form-success {
        font-size: 12px;
        color: #10b981;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-success svg {
        width: 14px;
        height: 14px;
    }
    
    .form-checking {
        font-size: 12px;
        color: #f59e0b;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-checking svg {
        width: 14px;
        height: 14px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Product Preview Card */
    .product-preview {
        display: none;
        align-items: center;
        gap: 16px;
        background: var(--body-bg);
        padding: 16px;
        border-radius: 10px;
        border: 1px solid var(--card-border);
        margin-top: 12px;
    }
    
    .product-preview.show {
        display: flex;
    }
    
    .product-preview-image {
        width: 64px;
        height: 64px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
    }
    
    .product-preview-placeholder {
        width: 64px;
        height: 64px;
        border-radius: 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }
    
    .product-preview-placeholder svg {
        width: 28px;
        height: 28px;
    }
    
    .product-preview-info {
        flex: 1;
    }
    
    .product-preview-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .product-preview-sku {
        font-size: 13px;
        color: var(--text-muted);
        font-family: monospace;
    }
    
    .product-preview-prices {
        display: flex;
        gap: 16px;
        margin-top: 8px;
    }
    
    .product-preview-price {
        font-size: 12px;
        color: var(--text-muted);
    }
    
    .product-preview-price strong {
        color: var(--text-primary);
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
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
    }
    
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }

    .input-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
    }
    
    .input-icon.checking { color: #f59e0b; }
    .input-icon.valid { color: #10b981; }
    .input-icon.invalid { color: #ef4444; }
</style>

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('inventory.lots.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>Create New Lot</h1>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">Lot / Batch Details</h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('inventory.lots.store') }}" method="POST" id="lotForm">
                @csrf

                <!-- Product Selection -->
                <div class="form-section">
                    <div class="form-section-title">Product Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Select Product <span class="required">*</span></label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">-- Select a batch-managed product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-name="{{ $product->name }}"
                                        data-sku="{{ $product->sku }}"
                                        data-purchase="{{ $product->purchase_price }}"
                                        data-sale="{{ $product->sale_price }}"
                                        data-image="{{ $product->images->where('is_primary', true)->first()?->image_path ?? $product->images->first()?->image_path }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="form-error">{{ $message }}</div>@enderror
                        <div class="form-help">Only products with "Batch Managed" enabled are shown</div>
                        
                        <!-- Product Preview -->
                        <div class="product-preview" id="productPreview">
                            <div class="product-preview-placeholder" id="previewPlaceholder">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <img src="" class="product-preview-image" id="previewImage" style="display:none;" alt="Product">
                            <div class="product-preview-info">
                                <div class="product-preview-name" id="previewName"></div>
                                <div class="product-preview-sku" id="previewSku"></div>
                                <div class="product-preview-prices">
                                    <span class="product-preview-price">Purchase: <strong id="previewPurchase"></strong></span>
                                    <span class="product-preview-price">Sale: <strong id="previewSale"></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lot Number <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       name="lot_no" 
                                       id="lot_no" 
                                       class="form-control @error('lot_no') is-invalid @enderror" 
                                       placeholder="e.g., LOT-2024-001" 
                                       value="{{ old('lot_no') }}" 
                                       required
                                       autocomplete="off">
                                <svg id="lotNoIcon" class="input-icon" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div id="lotNoFeedback"></div>
                            @error('lot_no')<div class="form-error">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Initial Quantity <span class="required">*</span></label>
                            <input type="number" name="initial_qty" class="form-control" step="any" min="0" placeholder="0" value="{{ old('initial_qty') }}" required>
                            @error('initial_qty')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="RECALLED" {{ old('status') == 'RECALLED' ? 'selected' : '' }}>Recalled</option>
                            <option value="EXPIRED" {{ old('status') == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                            <option value="CONSUMED" {{ old('status') == 'CONSUMED' ? 'selected' : '' }}>Consumed</option>
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <div class="form-section-title">Lot-Specific Pricing (Optional)</div>
                    <div class="form-help" style="margin-bottom: 16px;">Override the product's default prices for this specific lot. Leave empty to use product defaults.</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" id="purchasePrice" class="form-control" step="0.01" min="0" placeholder="Use product default" value="{{ old('purchase_price') }}">
                            <div class="form-help">Cost price for this specific lot</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sale Price</label>
                            <input type="number" name="sale_price" id="salePrice" class="form-control" step="0.01" min="0" placeholder="Use product default" value="{{ old('sale_price') }}">
                            <div class="form-help">Selling price for this specific lot</div>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="form-section">
                    <div class="form-section-title">Dates</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Manufacturing Date</label>
                            <input type="date" name="manufacturing_date" class="form-control" value="{{ old('manufacturing_date') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
                            <div class="form-help">Leave empty if product doesn't expire</div>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="form-section">
                    <div class="form-section-title">Additional Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Remarks</label>
                        <textarea name="notes" class="form-control" placeholder="Any additional notes about this lot...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Lot
                    </button>
                    <a href="{{ route('inventory.lots.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let checkTimeout = null;
let isLotValid = true;

const productSelect = document.getElementById('product_id');
const productPreview = document.getElementById('productPreview');
const previewPlaceholder = document.getElementById('previewPlaceholder');
const previewImage = document.getElementById('previewImage');
const previewName = document.getElementById('previewName');
const previewSku = document.getElementById('previewSku');
const previewPurchase = document.getElementById('previewPurchase');
const previewSale = document.getElementById('previewSale');
const purchasePriceInput = document.getElementById('purchasePrice');
const salePriceInput = document.getElementById('salePrice');

const lotNoInput = document.getElementById('lot_no');
const lotNoIcon = document.getElementById('lotNoIcon');
const lotNoFeedback = document.getElementById('lotNoFeedback');
const submitBtn = document.getElementById('submitBtn');

// Product selection - show preview
productSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    
    if (!this.value) {
        productPreview.classList.remove('show');
        purchasePriceInput.placeholder = 'Use product default';
        salePriceInput.placeholder = 'Use product default';
        return;
    }
    
    const name = selected.dataset.name;
    const sku = selected.dataset.sku;
    const purchase = parseFloat(selected.dataset.purchase) || 0;
    const sale = parseFloat(selected.dataset.sale) || 0;
    const imagePath = selected.dataset.image;
    
    previewName.textContent = name;
    previewSku.textContent = 'SKU: ' + sku;
    previewPurchase.textContent = '₹' + purchase.toFixed(2);
    previewSale.textContent = '₹' + sale.toFixed(2);
    
    // Update placeholders
    purchasePriceInput.placeholder = purchase.toFixed(2);
    salePriceInput.placeholder = sale.toFixed(2);
    
    // Handle image
    if (imagePath) {
        previewImage.src = '/storage/' + imagePath;
        previewImage.style.display = 'block';
        previewPlaceholder.style.display = 'none';
        
        previewImage.onerror = function() {
            this.style.display = 'none';
            previewPlaceholder.style.display = 'flex';
        };
    } else {
        previewImage.style.display = 'none';
        previewPlaceholder.style.display = 'flex';
    }
    
    productPreview.classList.add('show');
    
    // Re-check lot number if already entered
    const lotNo = lotNoInput.value.trim();
    if (lotNo.length >= 2) {
        checkLotNumber(lotNo, this.value);
    }
});

// Initialize preview if product was pre-selected (e.g., from old() values)
if (productSelect.value) {
    productSelect.dispatchEvent(new Event('change'));
}

// Check lot number on input
lotNoInput.addEventListener('input', function() {
    clearTimeout(checkTimeout);
    
    const lotNo = this.value.trim();
    const productId = productSelect.value;
    
    // Reset state
    lotNoInput.classList.remove('is-valid', 'is-invalid', 'is-checking');
    lotNoFeedback.innerHTML = '';
    lotNoIcon.style.display = 'none';
    
    if (lotNo.length < 2) {
        isLotValid = true;
        updateSubmitButton();
        return;
    }
    
    // Show checking state
    lotNoInput.classList.add('is-checking');
    lotNoIcon.style.display = 'block';
    lotNoIcon.classList.remove('valid', 'invalid');
    lotNoIcon.classList.add('checking');
    lotNoIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>';
    lotNoFeedback.innerHTML = '<div class="form-checking"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Checking availability...</div>';
    
    // Debounce the check
    checkTimeout = setTimeout(() => {
        checkLotNumber(lotNo, productId);
    }, 500);
});

function checkLotNumber(lotNo, productId) {
    let url = '{{ route("inventory.lots.check") }}?lot_no=' + encodeURIComponent(lotNo);
    if (productId) {
        url += '&product_id=' + productId;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            lotNoInput.classList.remove('is-checking');
            lotNoIcon.classList.remove('checking');
            
            if (data.exists) {
                // Lot exists - show error
                isLotValid = false;
                lotNoInput.classList.add('is-invalid');
                lotNoIcon.classList.add('invalid');
                lotNoIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>';
                
                let message = 'This lot number already exists';
                if (data.product_name) {
                    message += ' for product: ' + data.product_name;
                }
                
                lotNoFeedback.innerHTML = '<div class="form-error"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + message + '</div>';
            } else {
                // Lot is available
                isLotValid = true;
                lotNoInput.classList.add('is-valid');
                lotNoIcon.classList.add('valid');
                lotNoIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
                
                lotNoFeedback.innerHTML = '<div class="form-success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Lot number is available</div>';
            }
            
            updateSubmitButton();
        })
        .catch(error => {
            console.error('Error checking lot:', error);
            lotNoInput.classList.remove('is-checking');
            lotNoIcon.style.display = 'none';
            lotNoFeedback.innerHTML = '';
            isLotValid = true;
            updateSubmitButton();
        });
}

function updateSubmitButton() {
    submitBtn.disabled = !isLotValid;
}

// Form submission validation
document.getElementById('lotForm').addEventListener('submit', function(e) {
    if (!isLotValid) {
        e.preventDefault();
        alert('Please use a unique lot number. The current lot number already exists.');
        lotNoInput.focus();
        return false;
    }
});
</script>
</x-layouts.app>