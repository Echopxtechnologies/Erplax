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

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    
    .form-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .form-check-label {
        font-size: 14px;
        color: var(--text-primary);
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

    .current-stock-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .current-stock-info svg {
        width: 24px;
        height: 24px;
        color: #059669;
    }
    
    .current-stock-info-content {
        flex: 1;
    }
    
    .current-stock-info-label {
        font-size: 12px;
        color: #166534;
        margin-bottom: 2px;
    }
    
    .current-stock-info-value {
        font-size: 18px;
        font-weight: 700;
        color: #059669;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.inventory.products.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>Edit Product</h1>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">Edit: {{ $product->name }}</h3>
        </div>
        <div class="form-card-body">
            <!-- Current Stock Info -->
            <div class="current-stock-info">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <div class="current-stock-info-content">
                    <div class="current-stock-info-label">Current Total Stock</div>
                    <div class="current-stock-info-value">
                        {{ $product->current_stock ?? 0 }} {{ $product->unit?->short_name ?? 'PCS' }}
                    </div>
                </div>
                <a href="{{ route('admin.inventory.products.show', $product->id) }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                    View Details
                </a>
            </div>

            <form action="{{ route('admin.inventory.products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="form-section-title">Basic Information</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">SKU <span class="required">*</span></label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-control">
                                <option value="">-- Select Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Unit <span class="required">*</span></label>
                            <select name="unit_id" class="form-control" required>
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->short_name }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">Base unit of measurement for this product</div>
                            @error('unit_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">HSN Code</label>
                            <input type="text" name="hsn_code" class="form-control" value="{{ old('hsn_code', $product->hsn_code) }}">
                            <div class="form-help">Harmonized System Nomenclature code for GST</div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <div class="form-section-title">Pricing</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Purchase Price <span class="required">*</span></label>
                            <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                            @error('purchase_price')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sale Price <span class="required">*</span></label>
                            <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}" required>
                            @error('sale_price')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Stock Settings -->
                <div class="form-section">
                    <div class="form-section-title">Stock Settings</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Minimum Stock Level</label>
                            <input type="number" name="min_stock_level" class="form-control" step="0.001" min="0" value="{{ old('min_stock_level', $product->min_stock_level) }}">
                            <div class="form-help">Alert when stock falls below this level</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maximum Stock Level</label>
                            <input type="number" name="max_stock_level" class="form-control" step="0.001" min="0" value="{{ old('max_stock_level', $product->max_stock_level) }}">
                            <div class="form-help">Maximum stock to maintain</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_batch_managed" value="1" {{ old('is_batch_managed', $product->is_batch_managed) ? 'checked' : '' }}>
                                <span class="form-check-label">Enable Batch/Lot Management</span>
                            </label>
                            <div class="form-help">Track expiry dates and batch numbers</div>
                        </div>
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <span class="form-check-label">Active</span>
                            </label>
                            <div class="form-help">Inactive products won't appear in stock operations</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Product
                    </button>
                    <a href="{{ route('admin.inventory.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>