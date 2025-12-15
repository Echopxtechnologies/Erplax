<x-layouts.app>
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
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
        color: var(--primary);
    }

    /* Tabs */
    .settings-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 0;
    }
    
    .tab-btn {
        padding: 12px 20px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tab-btn:hover {
        color: var(--text-primary);
    }
    
    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    
    .tab-btn svg {
        width: 18px;
        height: 18px;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }

    /* Cards Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .settings-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .settings-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .settings-card-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .settings-card-title svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }
    
    .settings-card-body {
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    /* Category Tree */
    .category-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .category-item {
        border-bottom: 1px solid var(--card-border);
    }
    
    .category-item:last-child {
        border-bottom: none;
    }
    
    .category-row {
        display: flex;
        align-items: center;
        padding: 10px 0;
        gap: 10px;
    }
    
    .category-toggle {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        transition: transform 0.2s;
    }
    
    .category-toggle.expanded {
        transform: rotate(90deg);
    }
    
    .category-toggle.hidden {
        visibility: hidden;
    }
    
    .category-toggle svg {
        width: 16px;
        height: 16px;
    }
    
    .category-name {
        flex: 1;
        font-size: 14px;
        color: var(--text-primary);
    }
    
    .category-code {
        font-size: 12px;
        color: var(--text-muted);
        background: var(--body-bg);
        padding: 2px 8px;
        border-radius: 4px;
    }
    
    .category-actions {
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .category-row:hover .category-actions {
        opacity: 1;
    }
    
    .category-children {
        list-style: none;
        padding-left: 34px;
        margin: 0;
        display: none;
    }
    
    .category-children.show {
        display: block;
    }

    /* Brand/Unit Grid */
    .item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }
    
    .item-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .item-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .item-card-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .item-card-code {
        font-size: 11px;
        color: var(--text-muted);
    }
    
    .item-card-status {
        margin-top: 8px;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
        display: inline-block;
    }
    
    .item-card-status.active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .item-card-status.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Unit List */
    .unit-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .unit-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .unit-item:hover {
        border-color: var(--primary);
    }
    
    .unit-short {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        min-width: 50px;
    }
    
    .unit-info {
        flex: 1;
    }
    
    .unit-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .unit-conversion {
        font-size: 11px;
        color: var(--text-muted);
    }
    
    .unit-actions {
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .unit-item:hover .unit-actions {
        opacity: 1;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-sm {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .btn-sm svg {
        width: 14px;
        height: 14px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: #fff;
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
    }
    
    .btn-ghost {
        background: transparent;
        color: var(--text-muted);
        padding: 6px;
    }
    
    .btn-ghost:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .btn-danger {
        background: transparent;
        color: #dc2626;
        padding: 6px;
    }
    
    .btn-danger:hover {
        background: #fee2e2;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    
    .modal-overlay.show {
        display: flex;
    }
    
    .modal {
        background: var(--card-bg);
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .modal-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        color: var(--text-muted);
    }
    
    .modal-close:hover {
        color: var(--text-primary);
    }
    
    .modal-close svg {
        width: 20px;
        height: 20px;
    }
    
    .modal-body {
        padding: 20px;
        overflow-y: auto;
    }
    
    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--card-border);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    /* Form */
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 6px;
    }
    
    .form-label .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        box-sizing: border-box;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    .form-help {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Alert */
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Inventory Settings
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabs -->
    <div class="settings-tabs">
        <button class="tab-btn active" onclick="showTab('categories')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Categories
        </button>
        <button class="tab-btn" onclick="showTab('brands')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Brands
        </button>
        <button class="tab-btn" onclick="showTab('units')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            Units
        </button>
    </div>

    <!-- Categories Tab -->
    <div id="tab-categories" class="tab-content active">
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Product Categories
                </h3>
                <button class="btn btn-primary btn-sm" onclick="openCategoryModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Category
                </button>
            </div>
            <div class="settings-card-body">
                @if($categories->count() > 0)
                    <ul class="category-tree">
                        @foreach($categories as $category)
                            <li class="category-item">
                                <div class="category-row">
                                    <span class="category-toggle {{ $category->children->count() > 0 ? '' : 'hidden' }}" onclick="toggleCategory(this)">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                    <span class="category-name">{{ $category->name }}</span>
                                    <span class="category-code">{{ $category->code }}</span>
                                    <div class="category-actions">
                                        <button class="btn btn-ghost btn-sm" onclick="editCategory({{ $category->id }}, '{{ $category->code }}', '{{ $category->name }}', {{ $category->parent_id ?? 'null' }}, '{{ $category->description }}', {{ $category->sort_order ?? 0 }})">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteCategory({{ $category->id }})">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @if($category->children->count() > 0)
                                    <ul class="category-children">
                                        @foreach($category->children as $child)
                                            <li class="category-item">
                                                <div class="category-row">
                                                    <span class="category-toggle hidden"></span>
                                                    <span class="category-name">{{ $child->name }}</span>
                                                    <span class="category-code">{{ $child->code }}</span>
                                                    <div class="category-actions">
                                                        <button class="btn btn-ghost btn-sm" onclick="editCategory({{ $child->id }}, '{{ $child->code }}', '{{ $child->name }}', {{ $child->parent_id ?? 'null' }}, '{{ $child->description }}', {{ $child->sort_order ?? 0 }})">
                                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteCategory({{ $child->id }})">
                                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p>No categories yet. Add your first category!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Brands Tab -->
    <div id="tab-brands" class="tab-content">
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Product Brands
                </h3>
                <button class="btn btn-primary btn-sm" onclick="openBrandModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Brand
                </button>
            </div>
            <div class="settings-card-body">
                @if($brands->count() > 0)
                    <div class="item-grid">
                        @foreach($brands as $brand)
                            <div class="item-card" onclick="editBrand({{ $brand->id }}, '{{ $brand->name }}', '{{ $brand->description }}', {{ $brand->is_active ? 'true' : 'false' }})">
                                <div class="item-card-name">{{ $brand->name }}</div>
                                <div class="item-card-code">{{ Str::limit($brand->description, 30) ?: 'No description' }}</div>
                                <span class="item-card-status {{ $brand->is_active ? 'active' : 'inactive' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <p>No brands yet. Add your first brand!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Units Tab -->
    <div id="tab-units" class="tab-content">
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    Units of Measurement
                </h3>
                <button class="btn btn-primary btn-sm" onclick="openUnitModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Unit
                </button>
            </div>
            <div class="settings-card-body">
                @if($units->count() > 0)
                    <div class="unit-list">
                        @foreach($units as $unit)
                            <div class="unit-item" onclick="editUnit({{ $unit->id }}, '{{ $unit->name }}', '{{ $unit->short_name }}', {{ $unit->base_unit_id ?? 'null' }}, {{ $unit->conversion_factor }}, {{ $unit->is_active ? 'true' : 'false' }})">
                                <span class="unit-short">{{ $unit->short_name }}</span>
                                <div class="unit-info">
                                    <div class="unit-name">{{ $unit->name }}</div>
                                    <div class="unit-conversion">
                                        @if($unit->base_unit_id && $unit->baseUnit)
                                            1 {{ $unit->short_name }} = {{ $unit->conversion_factor }} {{ $unit->baseUnit->short_name }}
                                        @else
                                            Base Unit ({{ $unit->conversion_factor }})
                                        @endif
                                    </div>
                                </div>
                                <div class="unit-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="event.stopPropagation(); editUnit({{ $unit->id }}, '{{ $unit->name }}', '{{ $unit->short_name }}', {{ $unit->base_unit_id ?? 'null' }}, {{ $unit->conversion_factor }}, {{ $unit->is_active ? 'true' : 'false' }})">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteUnit({{ $unit->id }})">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                        <p>No units yet. Run the migration to seed default units!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="categoryModalTitle">Add Category</h3>
            <button class="modal-close" onclick="closeCategoryModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="categoryForm" onsubmit="saveCategory(event)">
            <div class="modal-body">
                <input type="hidden" id="categoryId" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code <span class="required">*</span></label>
                        <input type="text" id="categoryCode" class="form-control" placeholder="e.g., ELEC" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Name <span class="required">*</span></label>
                        <input type="text" id="categoryName" class="form-control" placeholder="e.g., Electronics" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <select id="categoryParent" class="form-control">
                        <option value="">-- None (Top Level) --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="categoryDescription" class="form-control" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" id="categorySortOrder" class="form-control" min="0" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Modal -->
<div id="brandModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="brandModalTitle">Add Brand</h3>
            <button class="modal-close" onclick="closeBrandModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="brandForm" onsubmit="saveBrand(event)">
            <div class="modal-body">
                <input type="hidden" id="brandId" value="">
                <div class="form-group">
                    <label class="form-label">Name <span class="required">*</span></label>
                    <input type="text" id="brandName" class="form-control" placeholder="e.g., Samsung" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="brandDescription" class="form-control" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group" id="brandActiveGroup" style="display: none;">
                    <label class="form-label">
                        <input type="checkbox" id="brandIsActive" checked> Active
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBrandModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Brand</button>
            </div>
        </form>
    </div>
</div>

<!-- Unit Modal -->
<div id="unitModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="unitModalTitle">Add Unit</h3>
            <button class="modal-close" onclick="closeUnitModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="unitForm" onsubmit="saveUnit(event)">
            <div class="modal-body">
                <input type="hidden" id="unitId" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name <span class="required">*</span></label>
                        <input type="text" id="unitName" class="form-control" placeholder="e.g., Kilogram" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Short Name <span class="required">*</span></label>
                        <input type="text" id="unitShortName" class="form-control" placeholder="e.g., KG" required>
                        <div class="form-help">Abbreviation used in displays</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Base Unit</label>
                        <select id="unitBaseUnit" class="form-control">
                            <option value="">-- None (This is a base unit) --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                            @endforeach
                        </select>
                        <div class="form-help">Select if this unit converts to another</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Conversion Factor <span class="required">*</span></label>
                        <input type="number" id="unitConversionFactor" class="form-control" step="any" min="0.0001" value="1" required>
                        <div class="form-help">How many base units equal 1 of this unit</div>
                    </div>
                </div>
                <div class="form-group" id="unitActiveGroup" style="display: none;">
                    <label class="form-label">
                        <input type="checkbox" id="unitIsActive" checked> Active
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeUnitModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Unit</button>
            </div>
        </form>
    </div>
</div>

<script>
// Tab Switching
function showTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    document.querySelector(`[onclick="showTab('${tabName}')"]`).classList.add('active');
    document.getElementById(`tab-${tabName}`).classList.add('active');
}

// Category Tree Toggle
function toggleCategory(el) {
    el.classList.toggle('expanded');
    let children = el.closest('.category-item').querySelector('.category-children');
    if (children) {
        children.classList.toggle('show');
    }
}

// Category Modal
function openCategoryModal() {
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryCode').value = '';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryParent').value = '';
    document.getElementById('categoryDescription').value = '';
    document.getElementById('categorySortOrder').value = '0';
    document.getElementById('categoryModal').classList.add('show');
}

function editCategory(id, code, name, parentId, description, sortOrder) {
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryCode').value = code;
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryParent').value = parentId || '';
    document.getElementById('categoryDescription').value = description || '';
    document.getElementById('categorySortOrder').value = sortOrder || 0;
    document.getElementById('categoryModal').classList.add('show');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('show');
}

function saveCategory(e) {
    e.preventDefault();
    let id = document.getElementById('categoryId').value;
    let data = {
        code: document.getElementById('categoryCode').value,
        name: document.getElementById('categoryName').value,
        parent_id: document.getElementById('categoryParent').value || null,
        description: document.getElementById('categoryDescription').value,
        sort_order: document.getElementById('categorySortOrder').value,
        _token: '{{ csrf_token() }}'
    };
    
    let url = id ? '{{ url("admin/inventory/settings/categories") }}/' + id : '{{ route("inventory.settings.categories.store") }}';
    let method = id ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Error saving category');
        }
    })
    .catch(error => alert('Error: ' + error));
}

function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;
    
    fetch('{{ url("admin/inventory/settings/categories") }}/' + id, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Error deleting category');
        }
    })
    .catch(error => alert('Error: ' + error));
}

// Brand Modal
function openBrandModal() {
    document.getElementById('brandModalTitle').textContent = 'Add Brand';
    document.getElementById('brandId').value = '';
    document.getElementById('brandName').value = '';
    document.getElementById('brandDescription').value = '';
    document.getElementById('brandActiveGroup').style.display = 'none';
    document.getElementById('brandModal').classList.add('show');
}

function editBrand(id, name, description, isActive) {
    document.getElementById('brandModalTitle').textContent = 'Edit Brand';
    document.getElementById('brandId').value = id;
    document.getElementById('brandName').value = name;
    document.getElementById('brandDescription').value = description || '';
    document.getElementById('brandIsActive').checked = isActive;
    document.getElementById('brandActiveGroup').style.display = 'block';
    document.getElementById('brandModal').classList.add('show');
}

function closeBrandModal() {
    document.getElementById('brandModal').classList.remove('show');
}

function saveBrand(e) {
    e.preventDefault();
    let id = document.getElementById('brandId').value;
    let data = {
        name: document.getElementById('brandName').value,
        description: document.getElementById('brandDescription').value,
        _token: '{{ csrf_token() }}'
    };
    
    if (id) {
        data.is_active = document.getElementById('brandIsActive').checked;
    }
    
    let url = id ? '{{ url("admin/inventory/settings/brands") }}/' + id : '{{ route("inventory.settings.brands.store") }}';
    let method = id ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Error saving brand');
        }
    })
    .catch(error => alert('Error: ' + error));
}

// Unit Modal
function openUnitModal() {
    document.getElementById('unitModalTitle').textContent = 'Add Unit';
    document.getElementById('unitId').value = '';
    document.getElementById('unitName').value = '';
    document.getElementById('unitShortName').value = '';
    document.getElementById('unitBaseUnit').value = '';
    document.getElementById('unitConversionFactor').value = '1';
    document.getElementById('unitActiveGroup').style.display = 'none';
    document.getElementById('unitModal').classList.add('show');
}

function editUnit(id, name, shortName, baseUnitId, conversionFactor, isActive) {
    document.getElementById('unitModalTitle').textContent = 'Edit Unit';
    document.getElementById('unitId').value = id;
    document.getElementById('unitName').value = name;
    document.getElementById('unitShortName').value = shortName;
    document.getElementById('unitBaseUnit').value = baseUnitId || '';
    document.getElementById('unitConversionFactor').value = conversionFactor;
    document.getElementById('unitIsActive').checked = isActive;
    document.getElementById('unitActiveGroup').style.display = 'block';
    document.getElementById('unitModal').classList.add('show');
}

function closeUnitModal() {
    document.getElementById('unitModal').classList.remove('show');
}

function saveUnit(e) {
    e.preventDefault();
    let id = document.getElementById('unitId').value;
    let data = {
        name: document.getElementById('unitName').value,
        short_name: document.getElementById('unitShortName').value,
        base_unit_id: document.getElementById('unitBaseUnit').value || null,
        conversion_factor: document.getElementById('unitConversionFactor').value,
        _token: '{{ csrf_token() }}'
    };
    
    if (id) {
        data.is_active = document.getElementById('unitIsActive').checked;
    }
    
    let url = id ? '{{ url("admin/inventory/settings/units") }}/' + id : '{{ route("inventory.settings.units.store") }}';
    let method = id ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Error saving unit');
        }
    })
    .catch(error => alert('Error: ' + error));
}

function deleteUnit(id) {
    if (!confirm('Are you sure you want to delete this unit?')) return;
    
    fetch('{{ url("admin/inventory/settings/units") }}/' + id, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Error deleting unit');
        }
    })
    .catch(error => alert('Error: ' + error));
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
});
</script>
</x-layouts.app>