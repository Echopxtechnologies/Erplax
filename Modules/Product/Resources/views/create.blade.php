<style>
    .form-page { max-width: 700px; margin: 0 auto; padding: 20px; }
    .form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .btn-back { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-secondary); text-decoration: none; }
    .btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
    .btn-back svg { width: 20px; height: 20px; }
    .form-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; overflow: hidden; }
    .form-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: var(--body-bg); }
    .form-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .form-card-title svg { width: 20px; height: 20px; color: var(--primary); }
    .form-card-body { padding: 24px; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    .form-input, .form-textarea { width: 100%; padding: 12px 16px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 10px; color: var(--input-text); }
    .form-input:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-light); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
    .form-error { font-size: 12px; color: var(--danger); margin-top: 6px; }

    .toggle-wrapper { display: flex; align-items: center; gap: 12px; }
    .toggle-switch { position: relative; width: 48px; height: 26px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: var(--card-border); border-radius: 26px; transition: .3s; }
    .toggle-slider:before { content: ""; position: absolute; height: 20px; width: 20px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .3s; }
    .toggle-switch input:checked + .toggle-slider { background: var(--success); }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(22px); }
    .form-actions { display: flex; gap: 12px; padding: 20px 24px; background: var(--body-bg); border-top: 1px solid var(--card-border); }
    .btn-submit { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-cancel { display: inline-flex; align-items: center; padding: 12px 24px; background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; }
    .btn-cancel:hover { background: var(--body-bg); color: var(--text-primary); }
    .alert-errors { background: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; padding: 16px; margin-bottom: 20px; }
    .alert-errors-title { font-size: 14px; font-weight: 600; color: var(--danger); margin-bottom: 8px; }
    .alert-errors ul { margin: 0; padding-left: 20px; }
    .alert-errors li { font-size: 13px; color: var(--danger); }
</style>

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.product.index') }}" class="btn-back"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></a>
        <h1>Create Product</h1>
    </div>

    @if($errors->any())
        <div class="alert-errors">
            <div class="alert-errors-title">Please fix the following errors:</div>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.product.store') }}" method="POST">
        @csrf
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>Product Details</h2>
            </div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="form-label">Product Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Enter product name..." required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">SKU <span class="required">*</span></label>
                    <input type="text" name="sku" id="sku" class="form-input" value="{{ old('sku') }}" placeholder="Enter unique SKU..." required>
                    @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" placeholder="Enter description...">{{ old('description') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Purchase Price <span class="required">*</span></label>
                        <input type="number" name="purchase_price" class="form-input" value="{{ old('purchase_price', '0.00') }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sale Price <span class="required">*</span></label>
                        <input type="number" name="sale_price" class="form-input" value="{{ old('sale_price', '0.00') }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">MRP</label>
                        <input type="number" name="mrp" class="form-input" value="{{ old('mrp') }}" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="toggle-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Active</span>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px"><path d="M5 13l4 4L19 7"></path></svg>Create Product</button>
                <a href="{{ route('admin.product.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

