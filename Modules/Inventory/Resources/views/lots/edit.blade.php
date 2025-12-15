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
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #dbeafe; color: #1e40af; }

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
    
    .form-control:disabled {
        background: var(--body-bg);
        cursor: not-allowed;
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

    /* Product Preview Card */
    .product-preview {
        display: flex;
        align-items: center;
        gap: 16px;
        background: var(--body-bg);
        padding: 16px;
        border-radius: 10px;
        border: 1px solid var(--card-border);
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

    .readonly-field {
        background: var(--body-bg);
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 14px;
        color: var(--text-primary);
        border: 1px solid var(--card-border);
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
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }
</style>

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('inventory.lots.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>Edit Lot</h1>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">Edit: {{ $lot->lot_no }}</h3>
            @php
                $statusClass = [
                    'ACTIVE' => 'badge-success',
                    'RECALLED' => 'badge-warning',
                    'EXPIRED' => 'badge-danger',
                    'CONSUMED' => 'badge-info'
                ][$lot->status] ?? 'badge-info';
            @endphp
            <span class="badge {{ $statusClass }}">{{ $lot->status }}</span>
        </div>
        <div class="form-card-body">
            <form action="{{ route('inventory.lots.update', $lot->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Product Info (Read Only) -->
                <div class="form-section">
                    <div class="form-section-title">Product Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Product</label>
                        @php
                            $primaryImage = $lot->product?->images?->where('is_primary', true)->first() 
                                ?? $lot->product?->images?->first();
                        @endphp
                        <div class="product-preview">
                            @if($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                     class="product-preview-image" 
                                     alt="{{ $lot->product->name }}"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="product-preview-placeholder" style="display:none;">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @else
                                <div class="product-preview-placeholder">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="product-preview-info">
                                <div class="product-preview-name">{{ $lot->product->name ?? 'N/A' }}</div>
                                <div class="product-preview-sku">SKU: {{ $lot->product->sku ?? 'N/A' }}</div>
                                @if($lot->product)
                                <div class="product-preview-prices">
                                    <span class="product-preview-price">Purchase: <strong>₹{{ number_format($lot->product->purchase_price, 2) }}</strong></span>
                                    <span class="product-preview-price">Sale: <strong>₹{{ number_format($lot->product->sale_price, 2) }}</strong></span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-help">Product cannot be changed after lot creation</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lot Number <span class="required">*</span></label>
                            <input type="text" name="lot_no" class="form-control" value="{{ old('lot_no', $lot->lot_no) }}" required>
                            @error('lot_no')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Initial Quantity</label>
                            <div class="readonly-field">{{ number_format($lot->initial_qty, 2) }}</div>
                            <div class="form-help">Initial quantity cannot be changed</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                           <option value="ACTIVE" {{ old('status', $lot->status) == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="RECALLED" {{ old('status', $lot->status) == 'RECALLED' ? 'selected' : '' }}>Recalled</option>
                            <option value="EXPIRED" {{ old('status', $lot->status) == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                            <option value="CONSUMED" {{ old('status', $lot->status) == 'CONSUMED' ? 'selected' : '' }}>Consumed</option>
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <div class="form-section-title">Lot-Specific Pricing</div>
                    <div class="form-help" style="margin-bottom: 16px;">Override the product's default prices for this specific lot</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="{{ old('purchase_price', $lot->purchase_price) }}" placeholder="{{ $lot->product?->purchase_price ?? '0.00' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sale Price</label>
                            <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="{{ old('sale_price', $lot->sale_price) }}" placeholder="{{ $lot->product?->sale_price ?? '0.00' }}">
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="form-section">
                    <div class="form-section-title">Dates</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Manufacturing Date</label>
                            <input type="date" name="manufacturing_date" class="form-control" value="{{ old('manufacturing_date', $lot->manufacturing_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $lot->expiry_date?->format('Y-m-d')) }}">
                            @if($lot->expiry_date)
                                @php
                                    $daysToExpiry = now()->diffInDays($lot->expiry_date, false);
                                @endphp
                                @if($daysToExpiry < 0)
                                    <div class="form-help" style="color: #dc2626;">⚠️ This lot has expired {{ abs($daysToExpiry) }} days ago</div>
                                @elseif($daysToExpiry <= 30)
                                    <div class="form-help" style="color: #ea580c;">⚠️ Expires in {{ $daysToExpiry }} days</div>
                                @else
                                    <div class="form-help">{{ $daysToExpiry }} days until expiry</div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="form-section">
                    <div class="form-section-title">Additional Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Remarks</label>
                        <textarea name="notes" class="form-control" placeholder="Any additional notes about this lot...">{{ old('notes', $lot->notes) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Lot
                    </button>
                    <a href="{{ route('inventory.lots.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>