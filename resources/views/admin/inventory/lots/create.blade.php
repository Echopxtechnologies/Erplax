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
    
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .form-help {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
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
        <a href="{{ route('admin.inventory.lots.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>Add New Lot / Batch</h1>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">Lot Information</h3>
        </div>
        <div class="form-card-body">
            <div class="info-box">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Lots are used to track batch numbers, expiry dates, and manage inventory at a granular level. Only products with "Batch Managed" enabled will appear here.</p>
            </div>

            <form action="{{ route('admin.inventory.lots.store') }}" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="form-section-title">Basic Information</div>
                    
                    <div class="form-group">
                        <label class="form-label">Product <span class="required">*</span></label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Lot Number <span class="required">*</span></label>
                            <input type="text" name="lot_no" class="form-control" placeholder="e.g., LOT-2024-001" value="{{ old('lot_no') }}" required>
                            @error('lot_no')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Initial Quantity <span class="required">*</span></label>
                            <input type="number" name="initial_qty" class="form-control" step="0.01" min="0" placeholder="0" value="{{ old('initial_qty') }}" required>
                            @error('initial_qty')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="AVAILABLE" {{ old('status', 'AVAILABLE') == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                            <option value="RESERVED" {{ old('status') == 'RESERVED' ? 'selected' : '' }}>Reserved</option>
                            <option value="EXPIRED" {{ old('status') == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                            <option value="CONSUMED" {{ old('status') == 'CONSUMED' ? 'selected' : '' }}>Consumed</option>
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <div class="form-section-title">Pricing</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Purchase Price</label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" placeholder="0.00" value="{{ old('purchase_price') }}">
                            <div class="form-help">Cost price for this specific lot</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sale Price</label>
                            <input type="number" name="sale_price" class="form-control" step="0.01" min="0" placeholder="0.00" value="{{ old('sale_price') }}">
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
                        <textarea name="remarks" class="form-control" placeholder="Any additional notes about this lot...">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Lot
                    </button>
                    <a href="{{ route('admin.inventory.lots.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>