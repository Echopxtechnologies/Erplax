<x-layouts.app>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .page-wrapper { background: var(--body-bg); min-height: 100vh; padding: 0; }
    .page-header { background: var(--card-bg); border-bottom: 1px solid var(--card-border); padding: 12px 24px; display: flex; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 100; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); text-decoration: none; }
    .btn:hover { background: var(--body-bg); }
    .btn svg { width: 16px; height: 16px; }
    .btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); border-color: #2563eb; color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .form-container { background: var(--card-bg); }
    
    .product-header { padding: 20px 32px; border-bottom: 1px solid var(--card-border); display: flex; gap: 20px; background: linear-gradient(135deg, #eff6ff, #dbeafe); }
    .image-upload-area { width: 100px; height: 100px; border: 2px dashed #bfdbfe; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fff; flex-shrink: 0; overflow: hidden; position: relative; transition: all 0.2s; }
    .image-upload-area:hover { border-color: #3b82f6; background: #eff6ff; }
    .image-upload-area img { width: 100%; height: 100%; object-fit: cover; }
    .image-upload-area svg { width: 32px; height: 32px; color: #93c5fd; }
    .image-count { position: absolute; top: 4px; left: 4px; background: #3b82f6; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600; }
    .product-main { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .product-name-input { font-size: 24px; font-weight: 600; border: none; background: transparent; color: var(--text-primary); width: 100%; padding: 0; outline: none; margin-bottom: 12px; }
    .product-name-input:focus { border-bottom: 2px solid #3b82f6; }
    .product-name-input::placeholder { color: #94a3b8; }
    
    .product-flags { display: flex; gap: 6px; flex-wrap: wrap; }
    .flag-chip { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; cursor: pointer; padding: 5px 10px; border-radius: 20px; background: #fff; border: 1px solid #e2e8f0; color: #64748b; transition: all 0.2s; user-select: none; }
    .flag-chip:hover { border-color: #3b82f6; color: #3b82f6; }
    .flag-chip input[type="checkbox"] { position: absolute; opacity: 0; pointer-events: none; }
    .flag-chip.active { background: #eff6ff; border-color: #3b82f6; color: #2563eb; }
    .flag-chip .check-icon { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid currentColor; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .flag-chip.active .check-icon { background: #3b82f6; border-color: #3b82f6; }
    .flag-chip.active .check-icon svg { display: block; }
    .flag-chip .check-icon svg { display: none; width: 8px; height: 8px; color: #fff; }
    
    .tabs-nav { display: flex; border-bottom: 1px solid var(--card-border); padding: 0 32px; background: var(--card-bg); overflow-x: auto; }
    .tab-btn { padding: 14px 20px; font-size: 14px; font-weight: 500; color: var(--text-muted); cursor: pointer; border: none; background: none; border-bottom: 3px solid transparent; margin-bottom: -1px; white-space: nowrap; }
    .tab-btn:hover { color: var(--text-primary); }
    .tab-btn.active { color: #2563eb; border-bottom-color: #3b82f6; }
    .tab-content { display: none; padding: 24px 32px; }
    .tab-content.active { display: block; }
    
    .section-title { font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--card-border); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 48px; }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    .form-row { display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid var(--body-bg); }
    .form-label { width: 140px; flex-shrink: 0; font-size: 13px; font-weight: 500; padding-top: 10px; color: #475569; }
    .form-label .required { color: #ef4444; }
    .form-value { flex: 1; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: #fff; color: var(--text-primary); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; padding: 10px 0; }
    .checkbox-label input { width: 16px; height: 16px; accent-color: #3b82f6; }
    .input-group { display: flex; }
    .input-prefix, .input-suffix { padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 14px; color: #64748b; }
    .input-prefix { border-right: none; border-radius: 8px 0 0 8px; }
    .input-suffix { border-left: none; border-radius: 0 8px 8px 0; }
    .input-group .form-control.with-prefix { border-radius: 0 8px 8px 0; border-left: none; }
    .input-group .form-control.with-suffix { border-radius: 8px 0 0 8px; border-right: none; }
    .form-error { color: #ef4444; font-size: 12px; margin-top: 6px; }
    .form-hint { color: #94a3b8; font-size: 12px; margin-top: 6px; }
    .section-divider { height: 1px; background: var(--card-border); margin: 28px 0; }
    
    .tags-container { display: flex; flex-wrap: wrap; gap: 6px; padding: 8px 12px; min-height: 44px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: text; background: #fff; }
    .tags-container:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .tag-item { display: inline-flex; align-items: center; gap: 6px; background: #3b82f6; color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .tag-item button { background: none; border: none; color: rgba(255,255,255,0.8); cursor: pointer; padding: 0; font-size: 14px; }
    .tags-input { border: none; outline: none; background: transparent; font-size: 14px; flex: 1; min-width: 120px; padding: 4px; }
    
    .image-drop-zone { border: 2px dashed #e2e8f0; border-radius: 12px; padding: 40px; text-align: center; cursor: pointer; background: #f8fafc; }
    .image-drop-zone:hover { border-color: #3b82f6; background: #eff6ff; }
    .image-drop-zone svg { width: 48px; height: 48px; color: #94a3b8; margin-bottom: 12px; }
    .image-drop-zone p { color: #64748b; margin: 0; font-size: 14px; }
    .images-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; margin-top: 16px; }
    .image-item { position: relative; aspect-ratio: 1; border-radius: 10px; overflow: hidden; border: 2px solid transparent; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .image-item.primary { border-color: #3b82f6; }
    .image-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-item .btn-star { position: absolute; top: 5px; right: 5px; width: 26px; height: 26px; border-radius: 6px; border: none; cursor: pointer; background: rgba(255,255,255,0.95); color: #f59e0b; display: flex; align-items: center; justify-content: center; }
    .image-item .primary-badge { position: absolute; bottom: 5px; left: 5px; background: #3b82f6; color: #fff; font-size: 10px; padding: 2px 8px; border-radius: 4px; font-weight: 600; }
    .upload-info { margin-top: 12px; padding: 12px 16px; border-radius: 8px; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 13px; }
    
    .units-table { width: 100%; border-collapse: collapse; font-size: 14px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
    .units-table th { text-align: left; padding: 12px; font-weight: 600; color: #64748b; background: #f8fafc; font-size: 11px; text-transform: uppercase; }
    .units-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; }
    .units-table input, .units-table select { width: 100%; padding: 8px 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; background: #fff; }
    .units-table .checkbox-cell { text-align: center; }
    .units-table .checkbox-cell input { width: 16px; height: 16px; accent-color: #3b82f6; }
    .btn-link { background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 14px; padding: 12px 0; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
    .btn-icon-danger { background: none; border: none; color: #ef4444; cursor: pointer; padding: 6px; border-radius: 6px; }
    .btn-icon-danger:hover { background: #fee2e2; }
    
    /* Tom Select - Modern Styling */
    .ts-wrapper { width: 100%; }
    .ts-wrapper.single .ts-control { padding: 10px 14px !important; cursor: pointer !important; }
    .ts-wrapper.single .ts-control::after { content: ''; border: solid #94a3b8; border-width: 0 2px 2px 0; display: inline-block; padding: 3px; transform: rotate(45deg); position: absolute; right: 14px; top: 50%; margin-top: -4px; }
    .ts-wrapper.multi .ts-control { padding: 8px 12px !important; min-height: 48px !important; gap: 6px !important; flex-wrap: wrap !important; }
    .ts-control { padding: 10px 14px !important; border-radius: 10px !important; border: 1px solid #e2e8f0 !important; min-height: 48px !important; background: #fff !important; box-shadow: none !important; }
    .ts-control:hover { border-color: #cbd5e1 !important; }
    .ts-wrapper.focus .ts-control { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important; }
    .ts-dropdown { border-radius: 12px !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 12px 40px rgba(0,0,0,0.15) !important; margin-top: 6px !important; overflow: hidden !important; }
    .ts-dropdown .ts-dropdown-content { max-height: 280px !important; padding: 6px !important; }
    .ts-dropdown .option { padding: 12px 14px !important; border-radius: 8px !important; margin: 2px 0 !important; cursor: pointer !important; }
    .ts-dropdown .option:hover { background: #f1f5f9 !important; }
    .ts-dropdown .option.active, .ts-dropdown .option.selected { background: #eff6ff !important; color: #1e40af !important; }
    .ts-dropdown .optgroup-header { padding: 10px 14px !important; font-weight: 600 !important; color: #64748b !important; font-size: 11px !important; text-transform: uppercase !important; }
    
    /* Multi-select items (tags) */
    .ts-wrapper.multi .ts-control > .item { 
        background: linear-gradient(135deg, #3b82f6, #2563eb) !important; 
        color: #fff !important; 
        border: none !important; 
        border-radius: 8px !important; 
        padding: 6px 12px !important; 
        margin: 2px !important; 
        font-size: 13px !important; 
        font-weight: 500 !important; 
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }
    .ts-wrapper.multi .ts-control > .item .remove { 
        color: rgba(255,255,255,0.7) !important; 
        border: none !important;
        margin-left: 4px !important; 
        padding: 0 0 0 8px !important; 
        font-size: 18px !important; 
        line-height: 1 !important;
        border-left: 1px solid rgba(255,255,255,0.3) !important;
    }
    .ts-wrapper.multi .ts-control > .item .remove:hover { 
        color: #fff !important; 
        background: transparent !important; 
    }
    .ts-control > input { font-size: 14px !important; }
    .ts-control > input::placeholder { color: #94a3b8 !important; }
    
    /* Dropdown input plugin - clean search at top */
    .ts-dropdown .dropdown-input-wrap { padding: 10px !important; border-bottom: 1px solid #e2e8f0 !important; }
    .ts-dropdown .dropdown-input { width: 100% !important; padding: 10px 14px !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; font-size: 14px !important; background: #fff !important; }
    .ts-dropdown .dropdown-input:focus { border-color: #3b82f6 !important; outline: none !important; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important; }
    .ts-dropdown .dropdown-input::placeholder { color: #94a3b8 !important; }
    
    /* Checkbox options plugin */
    .ts-dropdown .option input[type="checkbox"] { margin-right: 10px !important; width: 16px !important; height: 16px !important; accent-color: #3b82f6 !important; }
    
    /* Clear button */
    .ts-wrapper .clear-button { color: #94a3b8 !important; }
    .ts-wrapper .clear-button:hover { color: #ef4444 !important; }
    
    /* Attribute preview */
    .attr-preview-box { margin-top: 20px; padding: 20px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: 12px; border: 1px solid #e2e8f0; }
    .attr-preview-box h4 { font-size: 13px; font-weight: 600; color: #475569; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; }
    .attr-preview-box h4::before { content: ''; width: 3px; height: 14px; background: #3b82f6; border-radius: 2px; }
    .attr-preview-item { margin-bottom: 14px; }
    .attr-preview-item:last-child { margin-bottom: 0; }
    .attr-preview-name { font-size: 12px; font-weight: 600; color: #1e293b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .attr-preview-values { display: flex; flex-wrap: wrap; gap: 6px; }
    .attr-value-tag { background: #fff; border: 1px solid #e2e8f0; padding: 5px 10px; border-radius: 6px; font-size: 12px; color: #475569; display: inline-flex; align-items: center; gap: 6px; }
    .attr-value-tag .color-swatch { width: 12px; height: 12px; border-radius: 3px; border: 1px solid rgba(0,0,0,0.1); }
    
    @media (max-width: 768px) {
        .product-header { flex-direction: column; align-items: center; text-align: center; padding: 20px; }
        .product-flags { justify-content: center; }
        .form-row { flex-direction: column; }
        .form-label { width: 100%; padding-bottom: 6px; }
        .tabs-nav { padding: 0 16px; }
        .tab-content { padding: 20px 16px; }
    }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <a href="{{ route('admin.inventory.products.index') }}" class="btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <div style="flex:1;"></div>
        <button type="submit" form="productForm" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Product
        </button>
    </div>

    <form action="{{ route('admin.inventory.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" style="display:none !important;">
        <input type="hidden" name="primary_image" id="primaryImageInput" value="0">

        <div class="form-container">
            <div class="product-header">
                <div class="image-upload-area" onclick="document.getElementById('imageInput').click();">
                    <svg id="cameraIcon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                    </svg>
                    <img id="mainPreview" src="" alt="" style="display:none;">
                    <span class="image-count" id="imageCount" style="display:none;">0</span>
                </div>

                <div class="product-main">
                    <input type="text" name="name" class="product-name-input" placeholder="Enter Product Name" value="{{ old('name') }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="product-flags">
                        @php
                            $canBeSold = old('can_be_sold', true);
                            $canBePurchased = old('can_be_purchased', true);
                            $trackInventory = old('track_inventory', true);
                            $hasVariants = old('has_variants', false);
                        @endphp
                        <label class="flag-chip {{ $canBeSold ? 'active' : '' }}">
                            <input type="hidden" name="can_be_sold" value="0">
                            <input type="checkbox" name="can_be_sold" value="1" {{ $canBeSold ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Sell
                        </label>
                        <label class="flag-chip {{ $canBePurchased ? 'active' : '' }}">
                            <input type="hidden" name="can_be_purchased" value="0">
                            <input type="checkbox" name="can_be_purchased" value="1" {{ $canBePurchased ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Purchase
                        </label>
                        <label class="flag-chip {{ $trackInventory ? 'active' : '' }}">
                            <input type="hidden" name="track_inventory" value="0">
                            <input type="checkbox" name="track_inventory" value="1" {{ $trackInventory ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Track Stock
                        </label>
                        <label class="flag-chip {{ $hasVariants ? 'active' : '' }}" id="variantChip">
                            <input type="hidden" name="has_variants" value="0">
                            <input type="checkbox" name="has_variants" id="hasVariantsCheckbox" value="1" {{ $hasVariants ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Variants
                        </label>
                    </div>
                </div>
            </div>

            <div class="tabs-nav">
                <button type="button" class="tab-btn active" data-tab="general">General Information</button>
                <button type="button" class="tab-btn" data-tab="pricing">Pricing & Taxes</button>
                <button type="button" class="tab-btn" data-tab="inventory">Inventory</button>
                <button type="button" class="tab-btn" data-tab="images">Images</button>
                <button type="button" class="tab-btn" id="variationsTab" data-tab="variations" style="{{ $hasVariants ? '' : 'display:none;' }}">Variations</button>
            </div>

            <!-- General Information -->
            <div class="tab-content active" id="tab-general">
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">SKU <span class="required">*</span></label>
                            <div class="form-value">
                                <input type="text" name="sku" class="form-control" placeholder="e.g., PRD-001" value="{{ old('sku') }}" required>
                                @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Barcode</label>
                            <div class="form-value"><input type="text" name="barcode" class="form-control" placeholder="e.g., 8901234567890" value="{{ old('barcode') }}"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Category</label>
                            <div class="form-value">
                                <select name="category_id" class="form-control searchable-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Brand</label>
                            <div class="form-value">
                                <select name="brand_id" class="form-control searchable-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)<option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">HSN Code</label>
                            <div class="form-value"><input type="text" name="hsn_code" class="form-control" placeholder="e.g., 8471" value="{{ old('hsn_code') }}"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Base Unit <span class="required">*</span></label>
                            <div class="form-value">
                                <select name="unit_id" class="form-control searchable-select" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)<option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }} {{ $unit->short_name == 'PCS' && !old('unit_id') ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Short Description</label>
                            <div class="form-value"><input type="text" name="short_description" class="form-control" placeholder="Brief description" value="{{ old('short_description') }}" maxlength="500"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Full Description</label>
                            <div class="form-value"><textarea name="description" class="form-control" placeholder="Detailed description">{{ old('description') }}</textarea></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Tags</label>
                            <div class="form-value">
                                <div class="tags-container" id="tagsWrapper">
                                    <input type="text" class="tags-input" id="tagsInput" placeholder="Type and press Enter">
                                </div>
                                <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags') }}">
                                <div class="form-hint">Press Enter to add tags</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Taxes -->
            <div class="tab-content" id="tab-pricing">
                <div class="section-title">Pricing</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Purchase Price <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="purchase_price" id="purchasePrice" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('purchase_price') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Sale Price <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="sale_price" id="salePrice" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('sale_price') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">MRP</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="mrp" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('mrp') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Profit Rate</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <input type="number" name="default_profit_rate" id="profitRate" class="form-control with-suffix" step="0.01" placeholder="0" value="{{ old('default_profit_rate', '') }}">
                                    <span class="input-suffix">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Calculated</label>
                            <div class="form-value">
                                <input type="text" id="calculatedSale" class="form-control" readonly placeholder="--" style="background:#f8fafc;cursor:pointer;" onclick="applyCalc()">
                                <div class="form-hint">Click to apply</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>
                <div class="section-title">Taxes</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Tax 1</label>
                            <div class="form-value">
                                <select name="tax_1_id" class="form-control searchable-select">
                                    <option value="">No Tax</option>
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_1_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Tax 2</label>
                            <div class="form-value">
                                <select name="tax_2_id" class="form-control searchable-select">
                                    <option value="">No Tax</option>
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_2_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>
                <div class="section-title">Alternate Units</div>
                <table class="units-table">
                    <thead>
                        <tr><th>Unit</th><th>Custom Name</th><th>Conversion</th><th>Purchase ₹</th><th>Sale ₹</th><th>Barcode</th><th class="checkbox-cell">Buy</th><th class="checkbox-cell">Sell</th><th></th></tr>
                    </thead>
                    <tbody id="productUnitsBody"></tbody>
                </table>
                <button type="button" class="btn-link" onclick="addUnitRow()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Unit
                </button>
            </div>

            <!-- Inventory -->
            <div class="tab-content" id="tab-inventory">
                <div class="section-title">Stock Settings</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Min Stock Level</label>
                            <div class="form-value">
                                <input type="number" name="min_stock_level" class="form-control" step="0.001" min="0" placeholder="0" value="{{ old('min_stock_level', 0) }}">
                                <div class="form-hint">Alert when stock falls below</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Max Stock Level</label>
                            <div class="form-value"><input type="number" name="max_stock_level" class="form-control" step="0.001" min="0" placeholder="0" value="{{ old('max_stock_level', 0) }}"></div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Batch Management</label>
                            <div class="form-value">
                                <label class="checkbox-label"><input type="checkbox" name="is_batch_managed" value="1" {{ old('is_batch_managed') ? 'checked' : '' }}> Enable Batch/Lot Management</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Tab -->
            <div class="tab-content" id="tab-images">
                <div class="section-title">Product Images</div>
                <div class="image-drop-zone" onclick="document.getElementById('imageInput').click();">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p><strong style="color:#3b82f6;">Click to browse</strong> or drag and drop</p>
                    <p style="font-size:12px;margin-top:8px;color:#94a3b8;">Max 2MB per image</p>
                </div>
                <div class="upload-info" id="uploadInfo" style="display:none;">
                    <strong id="uploadCount">0</strong> image(s) selected. Click ★ to set primary.
                </div>
                <div class="images-grid" id="imagePreviewGrid"></div>
            </div>

            <!-- Variations Tab -->
            <div class="tab-content" id="tab-variations">
                @if(isset($attributes) && count($attributes) > 0)
                <div class="section-title">Select Attributes for Variations</div>
                <p class="form-hint" style="margin-bottom:16px;">Type to search attributes (Color, Size, Material, Style). Click × on selected items to remove them.</p>
                
                <select id="attributesSelect" name="attributes[]" multiple placeholder="Type to search attributes...">
                    @foreach($attributes as $attribute)
                    <option value="{{ $attribute->id }}" 
                        data-name="{{ $attribute->name }}"
                        data-values='@json($attribute->values->map(fn($v) => ["value" => $v->value, "color" => $v->color_code]))'>
                        {{ $attribute->name }}
                    </option>
                    @endforeach
                </select>

                <div id="attrPreview" class="attr-preview-box" style="display:none;">
                    <h4>Selected Attributes & Values</h4>
                    <div id="attrPreviewContent"></div>
                </div>

                <div class="section-divider"></div>
                <label class="checkbox-label">
                    <input type="checkbox" name="generate_variations" value="1" checked> 
                    Auto-generate all variation combinations after saving
                </label>
                @else
                <p class="form-hint">No attributes available. Create attributes first to enable product variations.</p>
                @endif
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ FLAG CHIPS (Fixed) ============
    document.querySelectorAll('.flag-chip').forEach(function(chip) {
        var checkbox = chip.querySelector('input[type="checkbox"]');
        
        function updateChipUI() {
            if (checkbox.checked) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
            // Special: Variations tab
            if (checkbox.id === 'hasVariantsCheckbox') {
                document.getElementById('variationsTab').style.display = checkbox.checked ? '' : 'none';
            }
        }
        
        // Initialize UI on load
        updateChipUI();
        
        // Listen for changes
        checkbox.addEventListener('change', updateChipUI);
    });
    
    // ============ TOM SELECT - Single Selects with dropdown search ============
    document.querySelectorAll('.searchable-select').forEach(function(el) {
        new TomSelect(el, {
            plugins: ['dropdown_input'],
            create: false,
            allowEmptyOption: true,
            maxOptions: 100
        });
    });
    
    // ============ TOM SELECT - Attributes Multi-Select ============
    var attrSelect = document.getElementById('attributesSelect');
    if (attrSelect) {
        var attrTS = new TomSelect(attrSelect, {
            plugins: ['remove_button', 'checkbox_options', 'dropdown_input'],
            create: false,
            hideSelected: false,
            closeAfterSelect: false,
            persist: false,
            maxOptions: 50,
            render: {
                option: function(data, escape) {
                    var vals = '';
                    try {
                        var arr = JSON.parse(data.values || '[]');
                        vals = arr.map(function(v){ return v.value; }).slice(0,5).join(', ');
                        if (arr.length > 5) vals += '...';
                    } catch(e){}
                    return '<div class="option">' +
                        '<div style="font-weight:600;">' + escape(data.text) + '</div>' +
                        '<div style="font-size:12px;color:#94a3b8;margin-top:2px;">' + escape(vals) + '</div>' +
                    '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                }
            },
            onChange: function(values) {
                updateAttrPreview(values);
            }
        });
        
        function updateAttrPreview(ids) {
            var box = document.getElementById('attrPreview');
            var content = document.getElementById('attrPreviewContent');
            if (!ids || ids.length === 0) {
                box.style.display = 'none';
                return;
            }
            box.style.display = 'block';
            var html = '';
            ids.forEach(function(id) {
                var opt = attrSelect.querySelector('option[value="' + id + '"]');
                if (!opt) return;
                var name = opt.dataset.name || opt.text;
                var values = [];
                try { values = JSON.parse(opt.dataset.values || '[]'); } catch(e){}
                html += '<div class="attr-preview-item">';
                html += '<div class="attr-preview-name">' + name + '</div>';
                html += '<div class="attr-preview-values">';
                values.forEach(function(v) {
                    html += '<span class="attr-value-tag">';
                    if (v.color) html += '<span class="color-swatch" style="background:' + v.color + '"></span>';
                    html += v.value + '</span>';
                });
                html += '</div></div>';
            });
            content.innerHTML = html;
        }
    }
    
    // ============ TABS ============
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c){ c.classList.remove('active'); });
            this.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        });
    });
    
    // ============ TAGS ============
    var tags = [];
    var tagsHidden = document.getElementById('tagsHidden');
    if (tagsHidden && tagsHidden.value) {
        tags = tagsHidden.value.split(',').map(function(t){ return t.trim(); }).filter(Boolean);
        renderTags();
    }
    var tagsInput = document.getElementById('tagsInput');
    if (tagsInput) {
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                var val = this.value.trim().replace(',','');
                if (val && tags.indexOf(val) === -1) { tags.push(val); renderTags(); }
                this.value = '';
            }
            if (e.key === 'Backspace' && !this.value && tags.length) { tags.pop(); renderTags(); }
        });
    }
    window.renderTags = function() {
        var wrapper = document.getElementById('tagsWrapper');
        var input = document.getElementById('tagsInput');
        wrapper.querySelectorAll('.tag-item').forEach(function(t){ t.remove(); });
        tags.forEach(function(tag, i) {
            var span = document.createElement('span');
            span.className = 'tag-item';
            span.innerHTML = tag + '<button type="button" onclick="removeTag('+i+')">&times;</button>';
            wrapper.insertBefore(span, input);
        });
        document.getElementById('tagsHidden').value = tags.join(', ');
    };
    window.removeTag = function(i) { tags.splice(i, 1); renderTags(); };
    
    // ============ IMAGES ============
    var primaryIndex = 0, previewUrls = [];
    var imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            var files = this.files;
            if (!files.length) return;
            document.getElementById('imagePreviewGrid').innerHTML = '';
            previewUrls = [];
            primaryIndex = 0;
            document.getElementById('primaryImageInput').value = 0;
            document.getElementById('uploadInfo').style.display = 'block';
            document.getElementById('uploadCount').textContent = files.length;
            for (var i = 0; i < files.length; i++) {
                (function(idx, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        previewUrls[idx] = e.target.result;
                        var div = document.createElement('div');
                        div.className = 'image-item' + (idx === 0 ? ' primary' : '');
                        div.id = 'img-' + idx;
                        div.innerHTML = '<img src="'+e.target.result+'"><button type="button" class="btn-star" onclick="setPrimary('+idx+')"><svg fill="'+(idx===0?'currentColor':'none')+'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></button>' + (idx===0?'<span class="primary-badge">Primary</span>':'');
                        document.getElementById('imagePreviewGrid').appendChild(div);
                        if (idx === 0) updateMainPreview(e.target.result, files.length);
                    };
                    reader.readAsDataURL(file);
                })(i, files[i]);
            }
        });
    }
    window.updateMainPreview = function(url, count) {
        document.getElementById('cameraIcon').style.display = 'none';
        var p = document.getElementById('mainPreview');
        p.src = url; p.style.display = 'block';
        var c = document.getElementById('imageCount');
        c.textContent = count; c.style.display = count > 1 ? 'block' : 'none';
    };
    window.setPrimary = function(idx) {
        primaryIndex = idx;
        document.getElementById('primaryImageInput').value = idx;
        document.querySelectorAll('.image-item').forEach(function(item, i) {
            var badge = item.querySelector('.primary-badge');
            var svg = item.querySelector('.btn-star svg');
            if (i === idx) {
                item.classList.add('primary');
                if (svg) svg.setAttribute('fill','currentColor');
                if (!badge) { badge = document.createElement('span'); badge.className='primary-badge'; badge.textContent='Primary'; item.appendChild(badge); }
            } else {
                item.classList.remove('primary');
                if (svg) svg.setAttribute('fill','none');
                if (badge) badge.remove();
            }
        });
        if (previewUrls[idx]) updateMainPreview(previewUrls[idx], document.getElementById('imageInput').files.length);
    };
    
    // ============ PRICE CALC ============
    var pp = document.getElementById('purchasePrice');
    var pr = document.getElementById('profitRate');
    if (pp) pp.addEventListener('input', calcPrice);
    if (pr) pr.addEventListener('input', calcPrice);
    window.calcPrice = function() {
        var p = parseFloat(document.getElementById('purchasePrice').value) || 0;
        var r = parseFloat(document.getElementById('profitRate').value) || 0;
        document.getElementById('calculatedSale').value = (p > 0 && r > 0) ? '₹ ' + (p*(1+r/100)).toFixed(2) : '--';
    };
    window.applyCalc = function() {
        var p = parseFloat(document.getElementById('purchasePrice').value) || 0;
        var r = parseFloat(document.getElementById('profitRate').value) || 0;
        if (p > 0 && r > 0) document.getElementById('salePrice').value = (p*(1+r/100)).toFixed(2);
    };
    
    // ============ UNITS ============
    var unitIdx = 0;
    window.addUnitRow = function() {
        var tbody = document.getElementById('productUnitsBody');
        var row = document.createElement('tr');
        row.innerHTML = '<td><select name="product_units['+unitIdx+'][unit_id]" class="form-control" required><option value="">Select</option>@foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->short_name }}</option>@endforeach</select></td><td><input type="text" name="product_units['+unitIdx+'][unit_name]" class="form-control" placeholder="Box of 12"></td><td><input type="number" name="product_units['+unitIdx+'][conversion_factor]" class="form-control" step="0.0001" min="0.0001" placeholder="1" required></td><td><input type="number" name="product_units['+unitIdx+'][purchase_price]" class="form-control" step="0.01" min="0" placeholder="0.00"></td><td><input type="number" name="product_units['+unitIdx+'][sale_price]" class="form-control" step="0.01" min="0" placeholder="0.00"></td><td><input type="text" name="product_units['+unitIdx+'][barcode]" class="form-control" placeholder="Barcode"></td><td class="checkbox-cell"><input type="checkbox" name="product_units['+unitIdx+'][is_purchase_unit]" value="1"></td><td class="checkbox-cell"><input type="checkbox" name="product_units['+unitIdx+'][is_sale_unit]" value="1"></td><td><button type="button" class="btn-icon-danger" onclick="this.closest(\'tr\').remove()">×</button></td>';
        tbody.appendChild(row);
        unitIdx++;
    };
});
</script>
</x-layouts.app>