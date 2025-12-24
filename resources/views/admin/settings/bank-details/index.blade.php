<style>
    .page-wrapper { background: var(--body-bg); min-height: 100vh; }
    .page-header { background: var(--card-bg); border-bottom: 1px solid var(--card-border); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .page-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); text-decoration: none; transition: all 0.2s; }
    .btn:hover { background: var(--body-bg); }
    .btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); border-color: #2563eb; color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-danger { background: #ef4444; border-color: #ef4444; color: #fff; }
    .page-content { padding: 24px; }
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; text-align: center; }
    .stat-card .value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
    .stat-card .label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    .data-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .data-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .search-input, .filter-select { padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); }
    .search-input { width: 280px; }
    .filter-select { min-width: 140px; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; background: var(--body-bg); border-bottom: 1px solid var(--card-border); }
    .data-table td { padding: 14px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; color: var(--text-primary); }
    .data-table tr:hover { background: var(--body-bg); }
    .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .badge-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .badge-secondary { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .badge-primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .actions { display: flex; gap: 8px; }
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal { background: var(--card-bg); border-radius: 12px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
    .modal-header { padding: 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); }
    .modal-body { padding: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; color: var(--text-secondary); }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; background: var(--card-bg); color: var(--text-primary); box-sizing: border-box; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; }
    .checkbox-label input { width: 16px; height: 16px; }
    .modal-footer { padding: 16px 20px; border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 12px; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h1>Bank Details</h1>
        <button class="btn btn-primary" onclick="openModal()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Bank Detail
        </button>
    </div>

    <div class="page-content">
        <div class="stats-row">
            <div class="stat-card"><div class="value">{{ $stats['total'] }}</div><div class="label">Total</div></div>
            <div class="stat-card"><div class="value">{{ $stats['active'] }}</div><div class="label">Active</div></div>
            <div class="stat-card"><div class="value">{{ $stats['vendors'] }}</div><div class="label">Vendors</div></div>
            <div class="stat-card"><div class="value">{{ $stats['customers'] }}</div><div class="label">Customers</div></div>
        </div>

        <div class="data-card">
            <div class="data-card-header">
                <input type="text" id="search" class="search-input" placeholder="Search...">
                <select id="holderTypeFilter" class="filter-select">
                    <option value="">All Types</option>
                    <option value="vendor">Vendor</option>
                    <option value="customer">Customer</option>
                    <option value="employee">Employee</option>
                    <option value="company">Company</option>
                </select>
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Account Holder</th>
                        <th>Bank Name</th>
                        <th>Account No</th>
                        <th>IFSC</th>
                        <th>Type</th>
                        <th>Holder</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Add Bank Detail</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="bankForm">
            <div class="modal-body">
                <input type="hidden" id="editId" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Holder Type *</label>
                        <select id="holder_type" class="form-control" required>
                            <option value="vendor">Vendor</option>
                            <option value="customer">Customer</option>
                            <option value="employee">Employee</option>
                            <option value="company">Company</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Holder ID *</label>
                        <input type="number" id="holder_id" class="form-control" required min="1">
                    </div>
                </div>
                <div class="form-group">
                    <label>Account Holder Name *</label>
                    <input type="text" id="account_holder_name" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Bank Name *</label>
                        <input type="text" id="bank_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Account Number *</label>
                        <input type="text" id="account_number" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" id="ifsc_code" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" id="branch_name" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>UPI ID</label>
                        <input type="text" id="upi_id" class="form-control" placeholder="name@upi">
                    </div>
                    <div class="form-group">
                        <label>Account Type</label>
                        <select id="account_type" class="form-control">
                            <option value="CURRENT">Current</option>
                            <option value="SAVINGS">Savings</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="checkbox-label"><input type="checkbox" id="is_primary"> Primary Account</label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label"><input type="checkbox" id="is_active" checked> Active</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
const dataUrl = '{{ route("admin.settings.bank-details.data") }}';
const storeUrl = '{{ route("admin.settings.bank-details.store") }}';
const csrfToken = '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    document.getElementById('search').addEventListener('input', debounce(loadData, 300));
    document.getElementById('holderTypeFilter').addEventListener('change', loadData);
    document.getElementById('statusFilter').addEventListener('change', loadData);
    document.getElementById('bankForm').addEventListener('submit', saveBank);
});

function loadData() {
    const params = new URLSearchParams({
        search: document.getElementById('search').value,
        holder_type: document.getElementById('holderTypeFilter').value,
        is_active: document.getElementById('statusFilter').value
    });
    
    fetch(dataUrl + '?' + params)
        .then(r => r.json())
        .then(res => {
            let html = '';
            if (!res.data || res.data.length === 0) {
                html = '<tr><td colspan="8" class="empty-state">No bank details found</td></tr>';
            } else {
                res.data.forEach(row => {
                    html += `<tr>
                        <td><strong>${row.account_holder_name}</strong></td>
                        <td>${row.bank_name}</td>
                        <td>${row.account_number}</td>
                        <td>${row.ifsc_code || '-'}</td>
                        <td>${row.account_type}</td>
                        <td><span class="badge badge-primary">${row.holder_type} #${row.holder_id}</span></td>
                        <td><span class="badge ${row.is_active ? 'badge-success' : 'badge-secondary'}">${row.is_active ? 'Active' : 'Inactive'}</span></td>
                        <td class="actions">
                            <button class="btn btn-sm" onclick='editBank(${JSON.stringify(row)})'>Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBank(${row.id})">Delete</button>
                        </td>
                    </tr>`;
                });
            }
            document.getElementById('tableBody').innerHTML = html;
        });
}

function openModal() {
    document.getElementById('modal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Add Bank Detail';
    document.getElementById('bankForm').reset();
    document.getElementById('editId').value = '';
    document.getElementById('is_active').checked = true;
}

function closeModal() {
    document.getElementById('modal').classList.remove('active');
}

function editBank(row) {
    document.getElementById('modal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Edit Bank Detail';
    document.getElementById('editId').value = row.id;
    document.getElementById('holder_type').value = row.holder_type.toLowerCase();
    document.getElementById('holder_id').value = row.holder_id;
    document.getElementById('account_holder_name').value = row.account_holder_name;
    document.getElementById('bank_name').value = row.bank_name;
    document.getElementById('account_number').value = row.account_number;
    document.getElementById('ifsc_code').value = row.ifsc_code || '';
    document.getElementById('branch_name').value = row.branch_name || '';
    document.getElementById('upi_id').value = row.upi_id || '';
    document.getElementById('account_type').value = row.account_type;
    document.getElementById('is_primary').checked = row.is_primary;
    document.getElementById('is_active').checked = row.is_active;
}

function saveBank(e) {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const url = id ? `{{ url("admin/settings/bank-details") }}/${id}` : storeUrl;
    const method = id ? 'PUT' : 'POST';
    
    const data = {
        holder_type: document.getElementById('holder_type').value,
        holder_id: document.getElementById('holder_id').value,
        account_holder_name: document.getElementById('account_holder_name').value,
        bank_name: document.getElementById('bank_name').value,
        account_number: document.getElementById('account_number').value,
        ifsc_code: document.getElementById('ifsc_code').value,
        branch_name: document.getElementById('branch_name').value,
        upi_id: document.getElementById('upi_id').value,
        account_type: document.getElementById('account_type').value,
        is_primary: document.getElementById('is_primary').checked ? 1 : 0,
        is_active: document.getElementById('is_active').checked ? 1 : 0,
    };
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            loadData();
        } else {
            alert(res.message || 'Error saving');
        }
    });
}

function deleteBank(id) {
    if (!confirm('Are you sure?')) return;
    fetch(`{{ url("admin/settings/bank-details") }}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    }).then(() => loadData());
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
</script>
