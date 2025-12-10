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
        max-width: 700px;
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
        max-width: 700px;
        margin: 0 auto;
        width: 100%;
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

    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .form-section-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-section-title svg {
        width: 16px;
        height: 16px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 640px) {
        .form-row, .form-row-3 {
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
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
    }
    
    .btn-primary:hover {
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
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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

    .code-preview {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 6px;
        margin-top: 8px;
    }
    
    .code-preview-label {
        font-size: 12px;
        color: #7c3aed;
    }
    
    .code-preview-value {
        font-size: 14px;
        font-weight: 600;
        color: #5b21b6;
        font-family: monospace;
    }

    .stock-info-banner {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .stock-info-banner svg {
        width: 24px;
        height: 24px;
        color: #d97706;
        flex-shrink: 0;
    }
    
    .stock-info-banner-content {
        flex: 1;
    }
    
    .stock-info-banner-title {
        font-weight: 600;
        color: #92400e;
        margin-bottom: 2px;
    }
    
    .stock-info-banner-text {
        font-size: 13px;
        color: #a16207;
    }
</style>

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('admin.inventory.racks.index') }}" class="back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Rack
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stock Warning if rack has stock -->
    @if(isset($rack->stock_count) && $rack->stock_count > 0)
        <div class="stock-info-banner">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="stock-info-banner-content">
                <div class="stock-info-banner-title">This rack contains stock</div>
                <div class="stock-info-banner-text">{{ $rack->stock_count }} item(s) are stored in this rack. Changing the warehouse will affect stock locations.</div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Edit: {{ $rack->code }} - {{ $rack->name }}
            </h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('admin.inventory.racks.update', $rack->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Basic Information
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Warehouse <span class="required">*</span></label>
                        <select name="warehouse_id" id="warehouse_id" class="form-control" required onchange="updateCodePreview()">
                            <option value="">-- Select Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                    data-code="{{ $warehouse->code ?? strtoupper(substr($warehouse->name, 0, 3)) }}" 
                                    {{ old('warehouse_id', $rack->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Rack Code <span class="required">*</span></label>
                            <input type="text" name="code" id="rack_code" class="form-control" value="{{ old('code', $rack->code) }}" required oninput="updateCodePreview()">
                            <div class="form-help">Unique code within the warehouse</div>
                            @error('code')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                            <div class="code-preview">
                                <span class="code-preview-label">Full Location:</span>
                                <span class="code-preview-value" id="codePreview">---</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rack Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $rack->name) }}" required>
                            @error('name')<div class="form-help" style="color: #ef4444;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Location Details -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Location Details
                    </div>
                    
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Zone</label>
                            <input type="text" name="zone" class="form-control" placeholder="e.g., Zone A, North" value="{{ old('zone', $rack->zone) }}">
                            <div class="form-help">Warehouse zone/area</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aisle</label>
                            <input type="text" name="aisle" class="form-control" placeholder="e.g., 1, A" value="{{ old('aisle', $rack->aisle) }}">
                            <div class="form-help">Aisle number/letter</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Level</label>
                            <input type="text" name="level" class="form-control" placeholder="e.g., 1, Ground" value="{{ old('level', $rack->level) }}">
                            <div class="form-help">Shelf level/floor</div>
                        </div>
                    </div>
                </div>

                <!-- Capacity Settings -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        Capacity (Optional)
                    </div>
                    
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Max Capacity</label>
                            <input type="number" name="max_capacity" class="form-control" step="0.01" min="0" placeholder="0" value="{{ old('max_capacity', $rack->max_capacity) }}">
                            <div class="form-help">Maximum storage capacity</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacity Unit</label>
                            <select name="capacity_unit_id" class="form-control">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('capacity_unit_id', $rack->capacity_unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->short_name }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">Unit of measurement</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Weight (kg)</label>
                            <input type="number" name="max_weight" class="form-control" step="0.01" min="0" placeholder="0" value="{{ old('max_weight', $rack->max_weight) }}">
                            <div class="form-help">Weight limit in kg</div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info & Status -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="Optional notes about this rack...">{{ old('description', $rack->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $rack->is_active) ? 'checked' : '' }}>
                            <span class="form-check-label">Active</span>
                        </label>
                        <div class="form-help">Inactive racks won't appear in stock operations</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Rack
                    </button>
                    <a href="{{ route('admin.inventory.racks.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateCodePreview() {
    let warehouseSelect = document.getElementById('warehouse_id');
    let selectedOption = warehouseSelect.selectedOptions[0];
    let warehouseCode = selectedOption && selectedOption.dataset.code ? selectedOption.dataset.code : '';
    let rackCode = document.getElementById('rack_code').value.toUpperCase();
    
    let preview = document.getElementById('codePreview');
    
    if (warehouseCode && rackCode) {
        preview.textContent = warehouseCode + '-' + rackCode;
    } else if (warehouseCode) {
        preview.textContent = warehouseCode + '-???';
    } else if (rackCode) {
        preview.textContent = '???-' + rackCode;
    } else {
        preview.textContent = '---';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateCodePreview);
</script>
</x-layouts.app>