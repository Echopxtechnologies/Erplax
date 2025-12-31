<style>
.page-container { padding: 24px; background: var(--body-bg, #f3f4f6); min-height: calc(100vh - 60px); }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-title { font-size: 24px; font-weight: 700; color: var(--text-primary, #1f2937); display: flex; align-items: center; gap: 12px; }
.page-title svg { width: 28px; height: 28px; color: #8b5cf6; }

.templates-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }

.template-card { background: var(--card-bg, #fff); border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.2s; }
.template-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateY(-2px); }

.template-header { padding: 20px; background: linear-gradient(135deg, var(--primary-color, #2563eb), var(--secondary-color, #1e40af)); color: #fff; }
.template-currency { font-size: 32px; font-weight: 700; }
.template-name { font-size: 14px; opacity: 0.9; margin-top: 4px; }

.template-body { padding: 20px; }
.template-info { margin-bottom: 16px; }
.template-info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color, #e5e7eb); font-size: 13px; }
.template-info-row:last-child { border-bottom: none; }
.template-info-label { color: var(--text-muted, #6b7280); }
.template-info-value { font-weight: 600; color: var(--text-primary, #374151); }

.template-actions { display: flex; gap: 10px; }
.template-btn { flex: 1; padding: 10px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s; }
.template-btn svg { width: 16px; height: 16px; }
.btn-edit { background: #dbeafe; color: #2563eb; }
.btn-edit:hover { background: #bfdbfe; }
.btn-preview { background: #f3e8ff; color: #7c3aed; }
.btn-preview:hover { background: #e9d5ff; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
.status-active { background: #d1fae5; color: #065f46; }
.status-inactive { background: #fee2e2; color: #991b1b; }

.add-card { background: var(--card-bg, #fff); border: 2px dashed var(--border-color, #d1d5db); border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; cursor: pointer; transition: all 0.2s; min-height: 280px; }
.add-card:hover { border-color: #8b5cf6; background: #f5f3ff; }
.add-card svg { width: 48px; height: 48px; color: #8b5cf6; margin-bottom: 12px; }
.add-card-text { font-size: 16px; font-weight: 600; color: var(--text-primary, #374151); }
.add-card-sub { font-size: 13px; color: var(--text-muted, #6b7280); margin-top: 4px; }

/* Add Template Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.2s; }
.modal-overlay.active { opacity: 1; visibility: visible; }
.modal-box { background: var(--card-bg, #fff); border-radius: 16px; padding: 24px; width: 90%; max-width: 450px; transform: scale(0.9); transition: transform 0.2s; }
.modal-overlay.active .modal-box { transform: scale(1); }
.modal-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-primary, #1f2937); }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary, #374151); margin-bottom: 6px; }
.form-input { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color, #d1d5db); border-radius: 8px; font-size: 14px; }
.form-input:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1); }
.modal-actions { display: flex; gap: 12px; margin-top: 24px; }
.modal-btn { flex: 1; padding: 12px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
.modal-btn-cancel { background: var(--body-bg, #f3f4f6); color: var(--text-primary, #374151); }
.modal-btn-submit { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }

@media (max-width: 768px) {
    .templates-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Payment Receipt Templates
        </h1>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
        {{ session('success') }}
    </div>
    @endif

    <div class="templates-grid">
        @foreach($templates as $template)
        <div class="template-card" style="--primary-color: {{ $template->primary_color }}; --secondary-color: {{ $template->secondary_color }};">
            <div class="template-header">
                <div class="template-currency">{{ $template->currency }}</div>
                <div class="template-name">{{ $template->currency_name }}</div>
            </div>
            <div class="template-body">
                <div class="template-info">
                    <div class="template-info-row">
                        <span class="template-info-label">Symbol</span>
                        <span class="template-info-value">{{ $template->currency_symbol }}</span>
                    </div>
                    <div class="template-info-row">
                        <span class="template-info-label">Organization</span>
                        <span class="template-info-value">{{ Str::limit($template->organization_name, 25) }}</span>
                    </div>
                    <div class="template-info-row">
                        <span class="template-info-label">Status</span>
                        <span class="status-badge {{ $template->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $template->is_active ? '✓ Active' : '✕ Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="template-actions">
                    <a href="{{ route('admin.studentsponsorship.receipts.edit', $template->currency) }}" class="template-btn btn-edit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.studentsponsorship.receipts.preview', $template->currency) }}" target="_blank" class="template-btn btn-preview">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <div class="add-card" onclick="showAddModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <div class="add-card-text">Add New Currency Template</div>
            <div class="add-card-sub">Create a receipt template for another currency</div>
        </div>
    </div>
</div>

<!-- Add Template Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-title">Add Currency Template</div>
        <form action="{{ route('admin.studentsponsorship.receipts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Currency Code *</label>
                <input type="text" name="currency" class="form-input" placeholder="e.g., INR" maxlength="3" required style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label class="form-label">Currency Name</label>
                <input type="text" name="currency_name" class="form-input" placeholder="e.g., Indian Rupees">
            </div>
            <div class="form-group">
                <label class="form-label">Currency Symbol</label>
                <input type="text" name="currency_symbol" class="form-input" placeholder="e.g., ₹" maxlength="10">
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideAddModal()">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-submit">Create Template</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddModal() { document.getElementById('addModal').classList.add('active'); }
function hideAddModal() { document.getElementById('addModal').classList.remove('active'); }
document.getElementById('addModal').addEventListener('click', function(e) { if (e.target === this) hideAddModal(); });
</script>
