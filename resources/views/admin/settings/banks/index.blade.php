<style>
    .page-container { padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: #8b5cf6; }
    .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); transition: all 0.2s; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4); color: #fff; }
    .btn-add svg { width: 18px; height: 18px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
    .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }
    .table-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .table-card-title svg { width: 20px; height: 20px; color: var(--text-muted); }
    .table-card-body { padding: 0; }
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-content { background: var(--card-bg); border-radius: 12px; width: 100%; max-width: 450px; margin: 20px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f5f3ff, #ede9fe); border-radius: 12px 12px 0 0; }
    .modal-header h3 { font-size: 18px; font-weight: 600; color: #5b21b6; margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-header h3 svg { width: 20px; height: 20px; }
    .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 6px; }
    .modal-close:hover { background: var(--body-bg); color: var(--text-primary); }
    .modal-close svg { width: 20px; height: 20px; }
    .modal-body { padding: 24px; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: #ef4444; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 12px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); transition: all 0.2s; }
    .btn:hover { background: var(--body-bg); }
    .btn svg { width: 16px; height: 16px; }
    .btn-primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-color: #7c3aed; color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
    .btn-danger { background: #ef4444; border-color: #ef4444; color: #fff; }
    .btn-danger:hover { background: #dc2626; }
    .form-error { color: #ef4444; font-size: 12px; margin-top: 6px; }
</style>

<div class="page-container">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            Banks
        </h1>
        <button type="button" class="btn-add" onclick="openModal()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Bank
        </button>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Banks</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Banks List
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-perpage" id="banksTable" data-route="{{ route('admin.settings.banks.data') }}">
                <thead>
                    <tr>
                        <th data-col="id" style="width: 80px;">ID</th>
                        <th class="dt-sort" data-col="name">Bank Name</th>
                        <th data-col="created_on" style="width: 150px;">Created On</th>
                        <th data-render="actions" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="bankModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                <span id="modalTitle">Add Bank</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="bankForm">
            <div class="modal-body">
                <input type="hidden" id="bankId" name="id">
                <div class="form-group">
                    <label class="form-label">Bank Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. HDFC Bank, ICICI Bank" required>
                    <div class="form-error" id="nameError"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitBtnText">Save Bank</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
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
            <p style="margin: 0;">Are you sure you want to delete <strong id="deleteBankName"></strong>?</p>
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
window.dtRenders = window.dtRenders || {};
window.dtRenders.actions = function(data, row) {
    return `<div style="display: flex; gap: 8px;">
        <button type="button" class="btn" style="padding: 6px 12px; font-size: 12px;" onclick='editBank(${JSON.stringify(row)})'>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </button>
        <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="confirmDelete(${row.id}, '${row.name.replace(/'/g, "\\'")}')">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </button>
    </div>`;
};

function openModal() {
    document.getElementById('bankModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Add Bank';
    document.getElementById('submitBtnText').textContent = 'Save Bank';
    document.getElementById('bankForm').reset();
    document.getElementById('bankId').value = '';
    document.getElementById('nameError').textContent = '';
}

function closeModal() {
    document.getElementById('bankModal').classList.remove('active');
}

function editBank(row) {
    document.getElementById('bankModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Edit Bank';
    document.getElementById('submitBtnText').textContent = 'Update Bank';
    document.getElementById('bankId').value = row.id;
    document.getElementById('name').value = row.name || '';
    document.getElementById('nameError').textContent = '';
}

let deleteId = null;
function confirmDelete(id, name) {
    deleteId = id;
    document.getElementById('deleteBankName').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteId = null;
}

// Form Submit - reload page on success (layout shows toast)
document.getElementById('bankForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    document.getElementById('nameError').textContent = '';
    
    const id = document.getElementById('bankId').value;
    const url = id 
        ? '{{ route("admin.settings.banks.update", ":id") }}'.replace(':id', id)
        : '{{ route("admin.settings.banks.store") }}';

    try {
        const response = await fetch(url, {
            method: id ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ name: document.getElementById('name').value }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Reload page - layout will show session flash
            window.location.href = '{{ route("admin.settings.banks.index") }}?success=' + encodeURIComponent(result.message);
        } else if (result.errors && result.errors.name) {
            document.getElementById('nameError').textContent = result.errors.name[0];
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Delete - reload page on success
document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
    if (!deleteId) return;

    try {
        const response = await fetch('{{ route("admin.settings.banks.destroy", ":id") }}'.replace(':id', deleteId), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        });

        const result = await response.json();
        if (response.ok && result.success) {
            window.location.href = '{{ route("admin.settings.banks.index") }}?success=' + encodeURIComponent(result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Close on outside click / Escape
document.getElementById('bankModal').addEventListener('click', e => { if (e.target.id === 'bankModal') closeModal(); });
document.getElementById('deleteModal').addEventListener('click', e => { if (e.target.id === 'deleteModal') closeDeleteModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); closeDeleteModal(); } });
</script>

@include('components.datatable')