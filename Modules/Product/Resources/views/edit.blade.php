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
    .btn-delete { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: var(--danger-light); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; margin-left: auto; }
    .btn-delete:hover { background: var(--danger); color: #fff; }
    .alert-errors { background: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; padding: 16px; margin-bottom: 20px; }
    .alert-errors-title { font-size: 14px; font-weight: 600; color: var(--danger); margin-bottom: 8px; }
    .alert-errors ul { margin: 0; padding-left: 20px; }
    .alert-errors li { font-size: 13px; color: var(--danger); }
</style>

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.product.index') }}" class="btn-back"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></a>
        <h1>Edit Product</h1>
    </div>

    @if($errors->any())
        <div class="alert-errors">
            <div class="alert-errors-title">Please fix the following errors:</div>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.product.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>Edit Product</h2>
            </div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="form-label">Product Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">SKU <span class="required">*</span></label>
                    <input type="text" name="sku" class="form-input" value="{{ old('sku', $product->sku) }}" required>
                    @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Purchase Price <span class="required">*</span></label>
                        <input type="number" name="purchase_price" class="form-input" value="{{ old('purchase_price', $product->purchase_price) }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sale Price <span class="required">*</span></label>
                        <input type="number" name="sale_price" class="form-input" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">MRP</label>
                        <input type="number" name="mrp" class="form-input" value="{{ old('mrp', $product->mrp) }}" step="0.01" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="toggle-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>Active</span>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px"><path d="M5 13l4 4L19 7"></path></svg>Update Product</button>
                <a href="{{ route('admin.product.index') }}" class="btn-cancel">Cancel</a>
                <button type="button" class="btn-delete" onclick="confirmDelete()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>Delete</button>
            </div>
        </div>
    </form>
    <form id="delete-form" action="{{ route('admin.product.destroy', $product->id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this product?')) document.getElementById('delete-form').submit();
}
</script>
