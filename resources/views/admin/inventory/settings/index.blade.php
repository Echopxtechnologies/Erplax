<x-layouts.app>
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
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
    .tabs-container {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .tabs-header {
        display: flex;
        border-bottom: 1px solid var(--card-border);
        background: var(--body-bg);
    }
    
    .tab-btn {
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        background: transparent;
        border: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tab-btn:hover {
        color: var(--text-primary);
        background: var(--card-bg);
    }
    
    .tab-btn.active {
        color: var(--primary);
        background: var(--card-bg);
    }
    
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary);
        border-radius: 3px 3px 0 0;
    }
    
    .tab-btn svg {
        width: 18px;
        height: 18px;
    }
    
    .tab-content {
        display: none;
        padding: 24px;
    }
    
    .tab-content.active {
        display: block;
    }

    /* Section Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: #fff;
    }
    
    .btn-add svg {
        width: 16px;
        height: 16px;
    }

    /* Table Styles */
    .table-wrapper {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        overflow: hidden;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
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
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
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
    }
    
    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 6px;
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
        background: var(--card-border);
        color: var(--text-primary);
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
    }

    /* Form Styles */
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .form-check-label {
        font-size: 14px;
        color: var(--text-primary);
        cursor: pointer;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: #fff;
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }
    
    .btn-danger {
        background: #dc2626;
        color: #fff;
    }
    
    .btn-danger:hover {
        background: #b91c1c;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* Action buttons in table */
    .action-btns {
        display: flex;
        gap: 6px;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .action-btn.edit:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }
    
    .action-btn.delete:hover {
        border-color: #dc2626;
        color: #dc2626;
        background: #fef2f2;
    }
    
    .action-btn svg {
        width: 16px;
        height: 16px;
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

    /* Logo preview */
    .logo-preview {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--card-border);
    }
    
    .logo-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        background: var(--body-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }
    
    .logo-placeholder svg {
        width: 20px;
        height: 20px;
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

    <!-- Tabs Container -->
    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" onclick="switchTab('categories')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Categories
            </button>
            <button class="tab-btn" onclick="switchTab('brands')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Brands
            </button>
        </div>

        <!-- Categories Tab -->
        <div class="tab-content active" id="tab-categories">
            <div class="section-header">
                <div class="section-title">Product Categories</div>
                <button class="btn-add" onclick="openCategoryModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Category
                </button>
            </div>
            <div class="table-wrapper">
                <table class="dt-table dt-search dt-perpage" 
                       id="categoriesTable"
                       data-route="{{ route('admin.inventory.settings.categories.data') }}">
                    <thead>
                        <tr>
                            <th class="dt-sort" data-col="id">ID</th>
                            <th class="dt-sort" data-col="code">Code</th>
                            <th class="dt-sort" data-col="name">Name</th>
                            <th class="dt-sort" data-col="parent_name">Parent</th>
                            <th class="dt-sort" data-col="sort_order">Order</th>
                            <th data-col="status" data-render="cat_status">Status</th>
                            <th data-render="cat_actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Brands Tab -->
        <div class="tab-content" id="tab-brands">
            <div class="section-header">
                <div class="section-title">Brands</div>
                <button class="btn-add" onclick="openBrandModal()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Brand
                </button>
            </div>
            <div class="table-wrapper">
                <table class="dt-table dt-search dt-perpage" 
                       id="brandsTable"
                       data-route="{{ route('admin.inventory.settings.brands.data') }}">
                    <thead>
                        <tr>
                            <th class="dt-sort" data-col="id">ID</th>
                            <th data-col="logo" data-render="brand_logo">Logo</th>
                            <th class="dt-sort" data-col="name">Name</th>
                            <th class="dt-sort" data-col="description">Description</th>
                            <th data-col="status" data-render="brand_status">Status</th>
                            <th data-render="brand_actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal-overlay" id="categoryModal">
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
            <input type="hidden" id="categoryId" name="id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code <span class="required">*</span></label>
                        <input type="text" name="code" id="catCode" class="form-control" placeholder="e.g., CAT001" required>
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
                        <option value="">-- No Parent (Root Category) --</option>
                        @foreach($categories->where('parent_id', null) as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="catDescription" class="form-control" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group" id="catActiveGroup" style="display: none;">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" id="catIsActive" value="1" checked>
                        <span class="form-check-label">Active</span>
                    </label>
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
            <h3 class="modal-title" id="brandModalTitle">Add Brand</h3>
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
                    <label class="form-label">Name <span class="required">*</span></label>
                    <input type="text" name="name" id="brandName" class="form-control" placeholder="Brand name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="brandDescription" class="form-control" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" id="brandLogo" class="form-control" accept="image/*">
                    <div id="brandLogoPreview" style="margin-top: 10px;"></div>
                </div>
                <div class="form-group" id="brandActiveGroup" style="display: none;">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" id="brandIsActive" value="1" checked>
                        <span class="form-check-label">Active</span>
                    </label>
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

// Tab switching
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    document.querySelector(`.tab-btn[onclick="switchTab('${tab}')"]`).classList.add('active');
    document.getElementById(`tab-${tab}`).classList.add('active');
}

// Alert helper
function showAlert(message, type = 'success') {
    let container = document.getElementById('alertContainer');
    container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => container.innerHTML = '', 5000);
}

// DataTable custom renderers
window.dtRenders = window.dtRenders || {};

window.dtRenders.cat_status = function(data, row) {
    return row.is_active 
        ? '<span class="badge badge-success">Active</span>'
        : '<span class="badge badge-danger">Inactive</span>';
};

window.dtRenders.cat_actions = function(data, row) {
    return `
        <div class="action-btns">
            <button class="action-btn edit" onclick="editCategory(${row.id}, '${row.code}', '${row.name}', ${row.parent_id || 'null'}, '${row.description || ''}', ${row.sort_order}, ${row.is_active})">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            <button class="action-btn delete" onclick="deleteCategory(${row.id})">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `;
};

window.dtRenders.brand_logo = function(data, row) {
    if (row.logo) {
        return `<img src="/storage/${row.logo}" class="logo-preview" alt="${row.name}">`;
    }
    return `<div class="logo-placeholder"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>`;
};

window.dtRenders.brand_status = function(data, row) {
    return row.is_active 
        ? '<span class="badge badge-success">Active</span>'
        : '<span class="badge badge-danger">Inactive</span>';
};

window.dtRenders.brand_actions = function(data, row) {
    return `
        <div class="action-btns">
            <button class="action-btn edit" onclick="editBrand(${row.id}, '${row.name}', '${row.description || ''}', '${row.logo || ''}', ${row.is_active})">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            <button class="action-btn delete" onclick="deleteBrand(${row.id})">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `;
};

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

function editCategory(id, code, name, parentId, description, sortOrder, isActive) {
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = id;
    document.getElementById('catCode').value = code;
    document.getElementById('catName').value = name;
    document.getElementById('catParentId').value = parentId || '';
    document.getElementById('catDescription').value = description;
    document.getElementById('catSortOrder').value = sortOrder;
    document.getElementById('catIsActive').checked = isActive;
    document.getElementById('catActiveGroup').style.display = 'block';
    document.getElementById('categoryModal').classList.add('show');
}

function saveCategory(e) {
    e.preventDefault();
    
    let id = document.getElementById('categoryId').value;
    let url = id 
        ? '{{ url("admin/inventory/settings/categories") }}/' + id 
        : '{{ route("admin.inventory.settings.categories.store") }}';
    
    let formData = {
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
            // Reload table
            if (window.dtInstance && window.dtInstance['categoriesTable']) {
                window.dtInstance['categoriesTable'].reload();
            } else {
                location.reload();
            }
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
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            if (window.dtInstance && window.dtInstance['categoriesTable']) {
                window.dtInstance['categoriesTable'].reload();
            } else {
                location.reload();
            }
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
    document.getElementById('brandLogoPreview').innerHTML = '';
    document.getElementById('brandModal').classList.add('show');
}

function closeBrandModal() {
    document.getElementById('brandModal').classList.remove('show');
}

function editBrand(id, name, description, logo, isActive) {
    document.getElementById('brandModalTitle').textContent = 'Edit Brand';
    document.getElementById('brandId').value = id;
    document.getElementById('brandName').value = name;
    document.getElementById('brandDescription').value = description;
    document.getElementById('brandIsActive').checked = isActive;
    document.getElementById('brandActiveGroup').style.display = 'block';
    
    if (logo) {
        document.getElementById('brandLogoPreview').innerHTML = `<img src="/storage/${logo}" class="logo-preview" alt="${name}">`;
    } else {
        document.getElementById('brandLogoPreview').innerHTML = '';
    }
    
    document.getElementById('brandModal').classList.add('show');
}

function saveBrand(e) {
    e.preventDefault();
    
    let id = document.getElementById('brandId').value;
    let url = id 
        ? '{{ url("admin/inventory/settings/brands") }}/' + id 
        : '{{ route("admin.inventory.settings.brands.store") }}';
    
    let formData = new FormData();
    formData.append('name', document.getElementById('brandName').value);
    formData.append('description', document.getElementById('brandDescription').value);
    formData.append('is_active', document.getElementById('brandIsActive').checked ? 1 : 0);
    
    let logoFile = document.getElementById('brandLogo').files[0];
    if (logoFile) {
        formData.append('logo', logoFile);
    }
    
    if (id) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBrandModal();
            showAlert(data.message);
            if (window.dtInstance && window.dtInstance['brandsTable']) {
                window.dtInstance['brandsTable'].reload();
            } else {
                location.reload();
            }
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
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            if (window.dtInstance && window.dtInstance['brandsTable']) {
                window.dtInstance['brandsTable'].reload();
            } else {
                location.reload();
            }
        } else {
            showAlert(data.message || 'Error deleting brand', 'error');
        }
    })
    .catch(error => {
        showAlert('Error deleting brand', 'error');
    });
}

// Close modal on overlay click
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeCategoryModal();
});

document.getElementById('brandModal').addEventListener('click', function(e) {
    if (e.target === this) closeBrandModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
        closeBrandModal();
    }
});
</script>

@include('core::datatable')
</x-layouts.app>