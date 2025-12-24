
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
    .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    
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
    .badge-secondary { background: #e5e7eb; color: #374151; }
    .badge-info { background: #dbeafe; color: #1e40af; }

    .country-code {
        font-weight: 600;
        color: #7c3aed;
        font-size: 13px;
        font-family: monospace;
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
        max-width: 560px;
        margin: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        max-height: 90vh;
        overflow-y: auto;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Countries
        </h1>
        <button type="button" class="btn-add" onclick="openModal()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Country
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Countries</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['un_members'] }}</div>
                <div class="stat-label">UN Members</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['non_members'] }}</div>
                <div class="stat-label">Non-Members</div>
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
                Countries List
            </div>
            <div class="table-filters">
                <select data-dt-filter="un_member">
                    <option value="">All Status</option>
                    <option value="yes">UN Members</option>
                    <option value="no">Non-Members</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-perpage" 
                   id="countriesTable"
                   data-route="{{ route('admin.settings.countries.data') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="iso2">ISO2</th>
                        <th class="dt-sort" data-col="iso3">ISO3</th>
                        <th class="dt-sort" data-col="short_name">Country Name</th>
                        <th data-col="calling_code">Calling Code</th>
                        <th data-col="cctld">TLD</th>
                        <th data-col="un_member" data-render="un_member">UN Member</th>
                        <th data-render="actions">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="countryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="modalTitle">Add Country</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="countryForm">
            <div class="modal-body">
                <input type="hidden" id="countryId" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ISO2 Code <span class="required">*</span></label>
                        <input type="text" name="iso2" id="iso2" class="form-control" maxlength="2" placeholder="IN" required style="text-transform: uppercase;">
                        <div class="form-error" id="iso2Error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ISO3 Code</label>
                        <input type="text" name="iso3" id="iso3" class="form-control" maxlength="3" placeholder="IND" style="text-transform: uppercase;">
                        <div class="form-error" id="iso3Error"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Short Name <span class="required">*</span></label>
                    <input type="text" name="short_name" id="short_name" class="form-control" placeholder="India" required>
                    <div class="form-error" id="short_nameError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Long Name</label>
                    <input type="text" name="long_name" id="long_name" class="form-control" placeholder="Republic of India">
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Num Code</label>
                        <input type="text" name="numcode" id="numcode" class="form-control" maxlength="6" placeholder="356">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Calling Code</label>
                        <input type="text" name="calling_code" id="calling_code" class="form-control" maxlength="8" placeholder="91">
                    </div>
                    <div class="form-group">
                        <label class="form-label">TLD</label>
                        <input type="text" name="cctld" id="cctld" class="form-control" maxlength="5" placeholder=".in">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">UN Member</label>
                    <select name="un_member" id="un_member" class="form-control">
                        <option value="">Select Status</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        <option value="some">Some</option>
                        <option value="former">Former</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitBtnText">Save Country</span>
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
            <p style="margin: 0; color: var(--text-primary);">Are you sure you want to delete <strong id="deleteCountryName"></strong>?</p>
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

window.dtRenders.un_member = function(data, row) {
    if (row.un_member === 'yes') {
        return '<span class="badge badge-success">Yes</span>';
    }
    if (row.un_member === 'no') {
        return '<span class="badge badge-secondary">No</span>';
    }
    if (row.un_member) {
        return '<span class="badge badge-info">' + row.un_member + '</span>';
    }
    return '<span class="badge badge-secondary">-</span>';
};

window.dtRenders.actions = function(data, row) {
    return `
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn" style="padding: 6px 12px; font-size: 12px;" onclick='editCountry(${JSON.stringify(row)})'>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
            <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="confirmDelete(${row.id}, '${row.short_name}')">
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
    document.getElementById('countryModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Add Country';
    document.getElementById('submitBtnText').textContent = 'Save Country';
    document.getElementById('countryForm').reset();
    document.getElementById('countryId').value = '';
    clearErrors();
}

function closeModal() {
    document.getElementById('countryModal').classList.remove('active');
}

function editCountry(row) {
    document.getElementById('countryModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Edit Country';
    document.getElementById('submitBtnText').textContent = 'Update Country';
    document.getElementById('countryId').value = row.id;
    document.getElementById('iso2').value = row.iso2 || '';
    document.getElementById('iso3').value = row.iso3 || '';
    document.getElementById('short_name').value = row.short_name || '';
    document.getElementById('long_name').value = row.long_name || '';
    document.getElementById('numcode').value = row.numcode || '';
    document.getElementById('calling_code').value = row.calling_code || '';
    document.getElementById('cctld').value = row.cctld || '';
    document.getElementById('un_member').value = row.un_member || '';
    clearErrors();
}

function clearErrors() {
    document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
}

// Delete Modal Functions
let deleteId = null;

function confirmDelete(id, name) {
    deleteId = id;
    document.getElementById('deleteCountryName').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteId = null;
}

// Form Submit
document.getElementById('countryForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();

    const id = document.getElementById('countryId').value;
    const data = {
        iso2: document.getElementById('iso2').value.toUpperCase(),
        iso3: document.getElementById('iso3').value.toUpperCase(),
        short_name: document.getElementById('short_name').value,
        long_name: document.getElementById('long_name').value,
        numcode: document.getElementById('numcode').value,
        calling_code: document.getElementById('calling_code').value,
        cctld: document.getElementById('cctld').value,
        un_member: document.getElementById('un_member').value,
    };

    const url = id 
        ? '{{ route("admin.settings.countries.update", ":id") }}'.replace(':id', id)
        : '{{ route("admin.settings.countries.store") }}';

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
            if (window.dtRefresh) {
                window.dtRefresh('countriesTable');
            } else {
                location.reload();
            }
            alert(result.message);
        } else {
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.getElementById(key + 'Error');
                    if (errorEl) {
                        errorEl.textContent = result.errors[key][0];
                    }
                });
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

    const url = '{{ route("admin.settings.countries.destroy", ":id") }}'.replace(':id', deleteId);

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
            if (window.dtRefresh) {
                window.dtRefresh('countriesTable');
            } else {
                location.reload();
            }
            alert(result.message);
        } else {
            alert(result.message || 'Cannot delete this country');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting');
    }
});

// Close modal on outside click
document.getElementById('countryModal').addEventListener('click', function(e) {
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
