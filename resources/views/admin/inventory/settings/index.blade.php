<x-layouts.app>
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    
    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-header h1 svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }

    /* Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    @media (max-width: 1024px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Section Card */
    .section-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
    }
    
    .section-header.purple {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(139, 92, 246, 0.02));
    }
    
    .section-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .section-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), #2563eb);
        color: #fff;
    }
    
    .section-icon.purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .section-icon svg {
        width: 22px;
        height: 22px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .section-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .section-count {
        background: var(--body-bg);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
    }
    
    .section-body {
        padding: 20px 24px;
        max-height: 500px;
        overflow-y: auto;
    }

    /* Add Button */
    .add-item-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        border: 2px dashed var(--card-border);
        border-radius: 12px;
        background: transparent;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 16px;
    }
    
    .add-item-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(59, 130, 246, 0.05);
    }
    
    .add-item-btn svg {
        width: 20px;
        height: 20px;
    }

    /* Category Tree */
    .category-tree {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .category-item {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.2s;
    }
    
    .category-item:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }
    
    .category-main {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        gap: 12px;
    }
    
    .category-toggle {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .category-toggle:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    
    .category-toggle svg {
        width: 14px;
        height: 14px;
        transition: transform 0.2s;
    }
    
    .category-toggle.open svg {
        transform: rotate(90deg);
    }
    
    .category-toggle.no-children {
        visibility: hidden;
    }
    
    .category-color {
        width: 8px;
        height: 32px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    
    .category-info {
        flex: 1;
        min-width: 0;
    }
    
    .category-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .category-code {
        font-size: 11px;
        color: var(--text-muted);
        background: var(--card-bg);
        padding: 2px 8px;
        border-radius: 4px;
        font-family: monospace;
    }
    
    .category-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .category-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .category-actions {
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .category-item:hover .category-actions {
        opacity: 1;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: var(--card-bg);
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background: var(--primary);
        color: #fff;
    }
    
    .action-btn.delete:hover {
        background: #dc2626;
    }
    
    .action-btn svg {
        width: 16px;
        height: 16px;
    }
    
    /* Subcategories */
    .category-children {
        display: none;
        padding: 0 16px 12px 52px;
    }
    
    .category-children.open {
        display: block;
    }
    
    .subcategory-item {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        background: var(--card-bg);
        border-radius: 8px;
        margin-top: 8px;
        gap: 10px;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    
    .subcategory-item:hover {
        border-color: var(--card-border);
    }
    
    .subcategory-item:hover .category-actions {
        opacity: 1;
    }
    
    .subcategory-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Brand Cards */
    .brands-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 640px) {
        .brands-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .brand-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s;
        position: relative;
    }
    
    .brand-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .brand-logo {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .brand-logo svg {
        width: 28px;
        height: 28px;
        color: var(--text-muted);
    }
    
    .brand-info {
        flex: 1;
        min-width: 0;
    }
    
    .brand-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .brand-desc {
        font-size: 12px;
        color: var(--text-muted);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .brand-status {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .brand-status.active {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    
    .brand-status.inactive {
        background: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }
    
    .brand-actions {
        display: flex;
        flex-direction: column;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .brand-card:hover .brand-actions {
        opacity: 1;
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
    
    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
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
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlide 0.3s ease;
    }
    
    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-title-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), #2563eb);
        color: #fff;
    }
    
    .modal-title-icon svg {
        width: 18px;
        height: 18px;
    }
    
    .modal-close {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--body-bg);
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .modal-close svg {
        width: 18px;
        height: 18px;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--card-border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: var(--body-bg);
        border-radius: 0 0 16px 16px;
    }

    /* Form */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .form-label .required {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        transition: all 0.2s;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
    }
    
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-switch {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: var(--body-bg);
        border-radius: 10px;
    }
    
    .switch {
        position: relative;
        width: 44px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--card-border);
        border-radius: 24px;
        transition: 0.3s;
    }
    
    .switch-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .switch input:checked + .switch-slider {
        background: #10b981;
    }
    
    .switch input:checked + .switch-slider:before {
        transform: translateX(20px);
    }
    
    .switch-label {
        font-size: 14px;
        color: var(--text-primary);
    }

    /* Logo Upload */
    .logo-upload {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .logo-preview-box {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        border: 2px dashed var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: var(--body-bg);
        flex-shrink: 0;
    }
    
    .logo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .logo-preview-box svg {
        width: 32px;
        height: 32px;
        color: var(--text-muted);
    }
    
    .logo-upload-info {
        flex: 1;
    }
    
    .logo-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .logo-upload-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .logo-upload-btn svg {
        width: 16px;
        height: 16px;
    }
    
    .logo-upload-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
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
        background: linear-gradient(135deg, var(--primary), #2563eb);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: var(--card-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--body-bg);
    }

    /* Alert */
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: alertSlide 0.3s ease;
    }
    
    @keyframes alertSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* Color picker dots */
    .color-options {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    
    .color-option {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .color-option:hover {
        transform: scale(1.1);
    }
    
    .color-option.selected {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 2px var(--card-bg);
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

    <div id="alertContainer"></div>

    <!-- Settings Grid -->
    <div class="settings-grid">
        <!-- Categories Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title-wrap">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="section-title">Categories</h2>
                        <p class="section-subtitle">Organize your products</p>
                    </div>
                </div>
                <span class="section-count" id="categoryCount">{{ $categories->count() }}</span>
            </div>
            <div class="section-body">
                <button class="add-item-btn" onclick="openCategoryModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Category
                </button>

                <div class="category-tree" id="categoryTree">
                    @forelse($categories->whereNull('parent_id') as $category)
                        @php
                            $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="category-item" data-id="{{ $category->id }}">
                            <div class="category-main">
                                <button class="category-toggle {{ $category->children->count() > 0 ? '' : 'no-children' }}" onclick="toggleChildren(this)">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <div class="category-color" style="background: {{ $color }};"></div>
                                <div class="category-info">
                                    <div class="category-name">
                                        {{ $category->name }}
                                        <span class="category-code">{{ $category->code }}</span>
                                    </div>
                                    <div class="category-meta">
                                        {{ $category->children->count() }} subcategories • Order: {{ $category->sort_order }}
                                    </div>
                                </div>
                                <span class="category-badge {{ $category->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="category-actions">
                                    <button class="action-btn" onclick="editCategory({{ json_encode($category) }})">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteCategory({{ $category->id }})">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @if($category->children->count() > 0)
                                <div class="category-children">
                                    @foreach($category->children as $child)
                                        <div class="subcategory-item" data-id="{{ $child->id }}">
                                            <div class="subcategory-dot" style="background: {{ $color }};"></div>
                                            <div class="category-info">
                                                <div class="category-name">
                                                    {{ $child->name }}
                                                    <span class="category-code">{{ $child->code }}</span>
                                                </div>
                                            </div>
                                            <span class="category-badge {{ $child->is_active ? 'badge-active' : 'badge-inactive' }}">
                                                {{ $child->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <div class="category-actions">
                                                <button class="action-btn" onclick="editCategory({{ json_encode($child) }})">
                                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button class="action-btn delete" onclick="deleteCategory({{ $child->id }})">
                                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p>No categories yet. Add your first category!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Brands Section -->
        <div class="section-card">
            <div class="section-header purple">
                <div class="section-title-wrap">
                    <div class="section-icon purple">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="section-title">Brands</h2>
                        <p class="section-subtitle">Manage product brands</p>
                    </div>
                </div>
                <span class="section-count" id="brandCount">{{ $brands->count() }}</span>
            </div>
            <div class="section-body">
                <button class="add-item-btn" onclick="openBrandModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Brand
                </button>

                <div class="brands-grid" id="brandsGrid">
                    @forelse($brands as $brand)
                        <div class="brand-card" data-id="{{ $brand->id }}">
                            <div class="brand-status {{ $brand->is_active ? 'active' : 'inactive' }}"></div>
                            <div class="brand-logo">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                                @else
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="brand-info">
                                <div class="brand-name">{{ $brand->name }}</div>
                                <div class="brand-desc">{{ $brand->description ?: 'No description' }}</div>
                            </div>
                            <div class="brand-actions">
                                <button class="action-btn" onclick="editBrand({{ json_encode($brand) }})">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="action-btn delete" onclick="deleteBrand({{ $brand->id }})">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p>No brands yet. Add your first brand!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">
                <span class="modal-title-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </span>
                <span id="categoryModalTitle">Add Category</span>
            </h3>
            <button class="modal-close" onclick="closeCategoryModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="categoryForm" onsubmit="saveCategory(event)">
            <input type="hidden" id="categoryId" name="id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code <span class="required">*</span></label>
                        <input type="text" name="code" id="catCode" class="form-control" placeholder="e.g., ELEC" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="catSortOrder" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Name <span class="required">*</span></label>
                    <input type="text" name="name" id="catName" class="form-control" placeholder="Category name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" id="catParentId" class="form-control">
                        <option value="">— Root Category —</option>
                        @foreach($categories->whereNull('parent_id') as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="catDescription" class="form-control" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group" id="catActiveGroup" style="display: none;">
                    <div class="form-switch">
                        <label class="switch">
                            <input type="checkbox" name="is_active" id="catIsActive" value="1" checked>
                            <span class="switch-slider"></span>
                        </label>
                        <span class="switch-label">Active Category</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Modal -->
<div class="modal-overlay" id="brandModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">
                <span class="modal-title-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </span>
                <span id="brandModalTitle">Add Brand</span>
            </h3>
            <button class="modal-close" onclick="closeBrandModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="brandForm" onsubmit="saveBrand(event)" enctype="multipart/form-data">
            <input type="hidden" id="brandId" name="id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Brand Name <span class="required">*</span></label>
                    <input type="text" name="name" id="brandName" class="form-control" placeholder="e.g., Apple, Samsung" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="brandDescription" class="form-control" placeholder="Brief description of the brand"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <div class="logo-upload">
                        <div class="logo-preview-box" id="logoPreviewBox">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="logo-upload-info">
                            <label class="logo-upload-btn">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload Logo
                                <input type="file" name="logo" id="brandLogo" accept="image/*" style="display: none;" onchange="previewLogo(this)">
                            </label>
                            <p class="logo-upload-hint">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="brandActiveGroup" style="display: none;">
                    <div class="form-switch">
                        <label class="switch">
                            <input type="checkbox" name="is_active" id="brandIsActive" value="1" checked>
                            <span class="switch-slider"></span>
                        </label>
                        <span class="switch-label">Active Brand</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBrandModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Brand
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

// Toggle category children
function toggleChildren(btn) {
    if (btn.classList.contains('no-children')) return;
    
    btn.classList.toggle('open');
    const children = btn.closest('.category-item').querySelector('.category-children');
    if (children) {
        children.classList.toggle('open');
    }
}

// Alert helper
function showAlert(message, type = 'success') {
    const container = document.getElementById('alertContainer');
    const icon = type === 'success' 
        ? '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    
    container.innerHTML = `<div class="alert alert-${type}">${icon} ${message}</div>`;
    setTimeout(() => container.innerHTML = '', 5000);
}

// Logo preview
function previewLogo(input) {
    const preview = document.getElementById('logoPreviewBox');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ============ CATEGORIES ============
function openCategoryModal() {
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('catActiveGroup').style.display = 'none';
    document.getElementById('categoryModal').classList.add('show');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('show');
}

function editCategory(category) {
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = category.id;
    document.getElementById('catCode').value = category.code;
    document.getElementById('catName').value = category.name;
    document.getElementById('catParentId').value = category.parent_id || '';
    document.getElementById('catDescription').value = category.description || '';
    document.getElementById('catSortOrder').value = category.sort_order || 0;
    document.getElementById('catIsActive').checked = category.is_active;
    document.getElementById('catActiveGroup').style.display = 'block';
    document.getElementById('categoryModal').classList.add('show');
}

function saveCategory(e) {
    e.preventDefault();
    
    const id = document.getElementById('categoryId').value;
    const url = id 
        ? '{{ url("admin/inventory/settings/categories") }}/' + id 
        : '{{ route("admin.inventory.settings.categories.store") }}';
    
    const formData = {
        code: document.getElementById('catCode').value,
        name: document.getElementById('catName').value,
        parent_id: document.getElementById('catParentId').value || null,
        description: document.getElementById('catDescription').value,
        sort_order: document.getElementById('catSortOrder').value,
        is_active: document.getElementById('catIsActive').checked ? 1 : 0
    };
    
    fetch(url, {
        method: id ? 'PUT' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCategoryModal();
            showAlert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Error saving category', 'error');
        }
    })
    .catch(error => {
        showAlert('Error saving category', 'error');
    });
}

function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;
    
    fetch('{{ url("admin/inventory/settings/categories") }}/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Error deleting category', 'error');
        }
    })
    .catch(error => {
        showAlert('Error deleting category', 'error');
    });
}

// ============ BRANDS ============
function openBrandModal() {
    document.getElementById('brandModalTitle').textContent = 'Add Brand';
    document.getElementById('brandForm').reset();
    document.getElementById('brandId').value = '';
    document.getElementById('brandActiveGroup').style.display = 'none';
    document.getElementById('logoPreviewBox').innerHTML = `
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>`;
    document.getElementById('brandModal').classList.add('show');
}

function closeBrandModal() {
    document.getElementById('brandModal').classList.remove('show');
}

function editBrand(brand) {
    document.getElementById('brandModalTitle').textContent = 'Edit Brand';
    document.getElementById('brandId').value = brand.id;
    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandDescription').value = brand.description || '';
    document.getElementById('brandIsActive').checked = brand.is_active;
    document.getElementById('brandActiveGroup').style.display = 'block';
    
    const preview = document.getElementById('logoPreviewBox');
    if (brand.logo) {
        preview.innerHTML = `<img src="/storage/${brand.logo}" alt="${brand.name}">`;
    } else {
        preview.innerHTML = `
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>`;
    }
    
    document.getElementById('brandModal').classList.add('show');
}

function saveBrand(e) {
    e.preventDefault();
    
    const id = document.getElementById('brandId').value;
    const url = id 
        ? '{{ url("admin/inventory/settings/brands") }}/' + id 
        : '{{ route("admin.inventory.settings.brands.store") }}';
    
    const formData = new FormData();
    formData.append('name', document.getElementById('brandName').value);
    formData.append('description', document.getElementById('brandDescription').value);
    formData.append('is_active', document.getElementById('brandIsActive').checked ? 1 : 0);
    
    const logoFile = document.getElementById('brandLogo').files[0];
    if (logoFile) {
        formData.append('logo', logoFile);
    }
    
    if (id) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBrandModal();
            showAlert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Error saving brand', 'error');
        }
    })
    .catch(error => {
        showAlert('Error saving brand', 'error');
    });
}

function deleteBrand(id) {
    if (!confirm('Are you sure you want to delete this brand?')) return;
    
    fetch('{{ url("admin/inventory/settings/brands") }}/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Error deleting brand', 'error');
        }
    })
    .catch(error => {
        showAlert('Error deleting brand', 'error');
    });
}

// Close modals
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeCategoryModal();
});

document.getElementById('brandModal').addEventListener('click', function(e) {
    if (e.target === this) closeBrandModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
        closeBrandModal();
    }
});
</script>
</x-layouts.app>