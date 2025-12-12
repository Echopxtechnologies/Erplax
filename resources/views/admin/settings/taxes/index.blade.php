<x-layouts.app>
<style>
    .page-container {
        padding: 20px;
    }

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
        color: #8b5cf6;
    }
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
        color: #fff;
    }
    
    .btn-add svg {
        width: 18px;
        height: 18px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-icon.green { background: #d1fae5; color: #059669; }
    .stat-icon.red { background: #fee2e2; color: #dc2626; }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }
    
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .table-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .table-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .table-filters select {
        padding: 8px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 150px;
    }
    
    .table-card-body {
        padding: 0;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    .tax-rate {
        font-weight: 600;
        color: #7c3aed;
        font-size: 15px;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--card-bg);
        border-radius: 12px;
        width: 100%;
        max-width: 480px;
        margin: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border-radius: 12px 12px 0 0;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: #5b21b6;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-header h3 svg {
        width: 20px;
        height: 20px;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }

    .modal-close svg {
        width: 20px;
        height: 20px;
    }

    .modal-body {
        padding: 24px;
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
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .input-group {
        display: flex;
    }

    .input-suffix {
        padding: 10px 14px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-left: none;
        border-radius: 0 8px 8px 0;
        font-size: 14px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .input-group .form-control {
        border-radius: 8px 0 0 8px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--text-primary);
        cursor: pointer;
        padding: 10px 14px;
        background: var(--body-bg);
        border-radius: 8px;
        border: 1px solid var(--card-border);
    }

    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #8b5cf6;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--card-border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-primary);
        transition: all 0.2s;
    }

    .btn:hover {
        background: var(--body-bg);
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        border-color: #7c3aed;
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-danger {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .form-error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
    }
</style>

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
            </svg>
            Tax Management
        </h1>
        <button type="button" class="btn-add" onclick="openModal()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Tax
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Taxes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Active Taxes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['inactive'] }}</div>
                <div class="stat-label">Inactive Taxes</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Tax List
            </div>
            <div class="table-filters">
                <select data-dt-filter="is_active">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-perpage" 
                   id="taxesTable"
                   data-route="{{ route('admin.settings.taxes.data') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="id">ID</th>
                        <th class="dt-sort" data-col="name">Tax Name</th>
                        <th class="dt-sort" data-col="rate" data-render="rate">Rate</th>
                        <th data-col="status" data-render="status">Status</th>
                        <th class="dt-sort" data-col="created_at">Created</th>
                        <th data-render="actions">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="taxModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                <span id="modalTitle">Add New Tax</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="taxForm">
            <div class="modal-body">
                <input type="hidden" id="taxId" name="id">
                
                <div class="form-group">
                    <label class="form-label">Tax Name <span class="required">*</span></label>
                    <input type="text" name="name" id="taxName" class="form-control" placeholder="e.g., GST 18%, CGST, SGST" required>
                    <div class="form-error" id="nameError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tax Rate <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" name="rate" id="taxRate" class="form-control" step="0.01" min="0" max="100" placeholder="0.00" required>
                        <span class="input-suffix">%</span>
                    </div>
                    <div class="form-error" id="rateError"></div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" id="taxActive" value="1" checked>
                        Active
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitBtnText">Save Tax</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
            <h3 style="color: #991b1b;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Confirm Delete
            </h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <p style="margin: 0; color: var(--text-primary);">Are you sure you want to delete <strong id="deleteTaxName"></strong>?</p>
            <p style="margin: 10px 0 0; color: var(--text-muted); font-size: 13px;">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </div>
    </div>
</div>

<script>
// Custom DataTable Renders
window.dtRenders = window.dtRenders || {};

window.dtRenders.rate = function(data, row) {
    return '<span class="tax-rate">' + row.rate_display + '</span>';
};

window.dtRenders.status = function(data, row) {
    if (row.is_active) {
        return '<span class="badge badge-success">Active</span>';
    }
    return '<span class="badge badge-danger">Inactive</span>';
};

window.dtRenders.actions = function(data, row) {
    return `
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn" style="padding: 6px 12px; font-size: 12px;" onclick="editTax(${row.id}, '${row.name}', ${row.rate}, ${row.is_active})">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
            <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="confirmDelete(${row.id}, '${row.name}')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </div>
    `;
};

// Modal Functions
function openModal() {
    document.getElementById('taxModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Add New Tax';
    document.getElementById('submitBtnText').textContent = 'Save Tax';
    document.getElementById('taxForm').reset();
    document.getElementById('taxId').value = '';
    document.getElementById('taxActive').checked = true;
    clearErrors();
}

function closeModal() {
    document.getElementById('taxModal').classList.remove('active');
}

function editTax(id, name, rate, isActive) {
    document.getElementById('taxModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Edit Tax';
    document.getElementById('submitBtnText').textContent = 'Update Tax';
    document.getElementById('taxId').value = id;
    document.getElementById('taxName').value = name;
    document.getElementById('taxRate').value = rate;
    document.getElementById('taxActive').checked = isActive;
    clearErrors();
}

function clearErrors() {
    document.getElementById('nameError').textContent = '';
    document.getElementById('rateError').textContent = '';
}

// Delete Modal Functions
let deleteId = null;

function confirmDelete(id, name) {
    deleteId = id;
    document.getElementById('deleteTaxName').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteId = null;
}

// Form Submit
document.getElementById('taxForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();

    const id = document.getElementById('taxId').value;
    const data = {
        name: document.getElementById('taxName').value,
        rate: document.getElementById('taxRate').value,
        is_active: document.getElementById('taxActive').checked ? 1 : 0,
    };

    const url = id 
        ? '{{ route("admin.settings.taxes.update", ":id") }}'.replace(':id', id)
        : '{{ route("admin.settings.taxes.store") }}';

    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeModal();
            // Refresh table
            if (window.dtRefresh) {
                window.dtRefresh('taxesTable');
            } else {
                location.reload();
            }
            // Show success message (you can use toast or alert)
            alert(result.message);
        } else {
            // Handle validation errors
            if (result.errors) {
                if (result.errors.name) {
                    document.getElementById('nameError').textContent = result.errors.name[0];
                }
                if (result.errors.rate) {
                    document.getElementById('rateError').textContent = result.errors.rate[0];
                }
            } else {
                alert(result.message || 'An error occurred');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving');
    }
});

// Delete Confirm
document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
    if (!deleteId) return;

    const url = '{{ route("admin.settings.taxes.destroy", ":id") }}'.replace(':id', deleteId);

    try {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (response.ok && result.success) {
            closeDeleteModal();
            // Refresh table
            if (window.dtRefresh) {
                window.dtRefresh('taxesTable');
            } else {
                location.reload();
            }
            alert(result.message);
        } else {
            alert(result.message || 'Cannot delete this tax');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting');
    }
});

// Close modal on outside click
document.getElementById('taxModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
    }
});
</script>

@include('components.datatable')
</x-layouts.app>