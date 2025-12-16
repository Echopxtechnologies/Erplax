{{-- Shared styles for Purchase module - Dark mode compatible --}}
<style>
/* CSS Variables - inherits from parent theme */
:root {
    --card-bg: #fff;
    --card-border: #e5e7eb;
    --body-bg: #f9fafb;
    --text-primary: #1f2937;
    --text-secondary: #374151;
    --text-muted: #6b7280;
    --primary: #6366f1;
    --primary-hover: #4f46e5;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #3b82f6;
    --info-light: #dbeafe;
}

[data-theme="dark"], .dark {
    --card-bg: #1f2937;
    --card-border: #374151;
    --body-bg: #111827;
    --text-primary: #f9fafb;
    --text-secondary: #e5e7eb;
    --text-muted: #9ca3af;
    --success-light: rgba(16, 185, 129, 0.15);
    --warning-light: rgba(245, 158, 11, 0.15);
    --danger-light: rgba(239, 68, 68, 0.15);
    --info-light: rgba(59, 130, 246, 0.15);
}

/* Base Layout */
.page-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 24px; 
    padding-bottom: 16px; 
    border-bottom: 1px solid var(--card-border); 
    flex-wrap: wrap;
    gap: 16px;
}
.page-header h1 { 
    font-size: 24px; 
    font-weight: 600; 
    color: var(--text-primary); 
    margin: 0; 
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-actions { display: flex; gap: 8px; flex-wrap: wrap; }

/* Buttons */
.btn { 
    display: inline-flex; 
    align-items: center; 
    gap: 6px; 
    padding: 10px 18px; 
    border-radius: 8px; 
    font-weight: 500; 
    font-size: 14px; 
    cursor: pointer; 
    text-decoration: none; 
    border: none; 
    transition: all 0.2s; 
}
.btn-primary { 
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); 
    color: #fff; 
}
.btn-primary:hover { 
    background: linear-gradient(135deg, var(--primary-hover) 0%, #4338ca 100%); 
    color: #fff; 
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}
.btn-success { background: var(--success); color: #fff; }
.btn-success:hover { background: #059669; color: #fff; }
.btn-warning { background: var(--warning); color: #fff; }
.btn-warning:hover { background: #d97706; color: #fff; }
.btn-danger { background: var(--danger); color: #fff; }
.btn-danger:hover { background: #dc2626; color: #fff; }
.btn-outline { 
    background: var(--card-bg); 
    color: var(--text-secondary); 
    border: 1px solid var(--card-border); 
}
.btn-outline:hover { 
    background: var(--body-bg); 
    color: var(--text-primary); 
}
.btn-sm { padding: 6px 12px; font-size: 13px; }
.btn-add { 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    background: linear-gradient(135deg, var(--primary), var(--primary-hover)); 
    color: #fff; 
    padding: 10px 20px; 
    border-radius: 8px; 
    text-decoration: none; 
    font-weight: 600; 
    font-size: 14px; 
    transition: all 0.2s; 
}
.btn-add:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); 
    color: #fff; 
}
.btn-add svg { width: 18px; height: 18px; }
.btn-danger-outline { 
    background: transparent; 
    border: 1px solid var(--danger); 
    color: var(--danger); 
    padding: 4px 8px; 
    border-radius: 4px; 
    cursor: pointer; 
}
.btn-danger-outline:hover { background: var(--danger-light); }

/* Cards */
.card { 
    background: var(--card-bg); 
    border-radius: 12px; 
    box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
    border: 1px solid var(--card-border); 
    margin-bottom: 20px; 
}
.card-header { 
    padding: 16px 24px; 
    border-bottom: 1px solid var(--card-border); 
    background: var(--body-bg); 
    border-radius: 12px 12px 0 0; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
}
.card-header h5 { 
    margin: 0; 
    font-size: 16px; 
    font-weight: 600; 
    color: var(--text-primary); 
}
.card-body { padding: 24px; }
.card-body.p-0 { padding: 0; }

/* Table Card (for index pages) */
.table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
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
.table-card-title svg { width: 20px; height: 20px; color: var(--text-muted); }
.filter-group { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.filter-select { 
    padding: 8px 12px; 
    border: 1px solid var(--card-border); 
    border-radius: 6px; 
    font-size: 13px; 
    background: var(--card-bg); 
    color: var(--text-primary); 
    min-width: 140px; 
}
.filter-select:focus { outline: none; border-color: var(--primary); }

/* Forms */
.form-row { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 20px; 
    margin-bottom: 20px; 
}
.form-row:last-child { margin-bottom: 0; }
.form-group { display: flex; flex-direction: column; }
.form-group.col-2 { grid-column: span 2; }
.form-group.col-3 { grid-column: span 3; }
.form-group.col-full { grid-column: 1 / -1; }

.form-label { 
    font-size: 14px; 
    font-weight: 500; 
    color: var(--text-secondary); 
    margin-bottom: 6px; 
}
.form-label .required { color: var(--danger); }

.form-control { 
    padding: 10px 14px; 
    border: 1px solid var(--card-border); 
    border-radius: 8px; 
    font-size: 14px; 
    transition: border-color 0.2s, box-shadow 0.2s; 
    width: 100%; 
    box-sizing: border-box; 
    background: var(--card-bg);
    color: var(--text-primary);
}
.form-control:focus { 
    outline: none; 
    border-color: #3b82f6; 
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
}
.form-control::placeholder { color: var(--text-muted); }
.form-control:read-only, .form-control:disabled { 
    background: var(--body-bg); 
    color: var(--text-muted); 
    cursor: not-allowed; 
}
.form-control:focus { 
    outline: none; 
    border-color: var(--primary); 
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1); 
}
.form-control:read-only, .form-control:disabled { 
    background: var(--body-bg); 
    color: var(--text-muted); 
    cursor: not-allowed; 
}
.form-control::placeholder { color: var(--text-muted); }

select.form-control { 
    appearance: none; 
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); 
    background-position: right 10px center; 
    background-repeat: no-repeat; 
    background-size: 16px; 
    padding-right: 36px; 
}
textarea.form-control { min-height: 80px; resize: vertical; }

.form-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
.form-actions { display: flex; gap: 12px; margin-top: 24px; }

/* Alerts */
.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-success { background: var(--success-light); border: 1px solid var(--success); color: var(--success); }
.alert-danger { background: var(--danger-light); border: 1px solid var(--danger); color: var(--danger); }
.alert-warning { background: var(--warning-light); border: 1px solid var(--warning); color: var(--warning); }
.alert-info { background: var(--info-light); border: 1px solid var(--info); color: var(--info); }
.alert ul { margin: 0; padding-left: 20px; }

/* Badges */
.badge { 
    display: inline-flex; 
    align-items: center; 
    padding: 4px 10px; 
    border-radius: 20px; 
    font-size: 12px; 
    font-weight: 500; 
}
.badge-draft, .badge-secondary { background: var(--body-bg); color: var(--text-muted); border: 1px solid var(--card-border); }
.badge-pending, .badge-inspecting, .badge-warning { background: var(--warning-light); color: var(--warning); }
.badge-approved, .badge-confirmed, .badge-success, .badge-paid { background: var(--success-light); color: var(--success); }
.badge-rejected, .badge-cancelled, .badge-danger, .badge-unpaid { background: var(--danger-light); color: var(--danger); }
.badge-partially_paid { background: var(--warning-light); color: var(--warning); }
.badge-info { background: var(--info-light); color: var(--info); }
.badge-overdue { background: var(--danger); color: #fff; }
.badge-lg { padding: 8px 16px; font-size: 14px; }

/* Tables */
.data-table, .items-table { width: 100%; border-collapse: collapse; }
.data-table th, .items-table th { 
    background: var(--body-bg); 
    padding: 12px 16px; 
    text-align: left; 
    font-weight: 600; 
    font-size: 13px; 
    color: var(--text-secondary); 
    border-bottom: 2px solid var(--card-border); 
    white-space: nowrap;
}
.data-table td, .items-table td { 
    padding: 14px 16px; 
    border-bottom: 1px solid var(--card-border); 
    font-size: 14px; 
    color: var(--text-primary);
}
.data-table tr:hover { background: var(--body-bg); }
.data-table tr:last-child td, .items-table tr:last-child td { border-bottom: none; }
.items-table .form-control { padding: 8px 10px; font-size: 13px; }
.items-table input[type="number"] { width: 90px; text-align: right; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { 
    background: var(--card-bg); 
    border-radius: 12px; 
    padding: 20px; 
    border: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-card .label { font-size: 13px; color: var(--text-muted); margin-bottom: 4px; }
.stat-card .value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
.stat-card.draft .value { color: var(--text-muted); }
.stat-card.pending .value, .stat-card.inspecting .value { color: var(--warning); }
.stat-card.approved .value, .stat-card.confirmed .value { color: var(--success); }
.stat-card.unpaid .value { color: var(--danger); }
.stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-icon svg { width: 24px; height: 24px; }
.stat-icon.total { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
.stat-icon.active { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
.stat-icon.inactive { background: rgba(156, 163, 175, 0.1); color: #9ca3af; }
.stat-icon.blocked { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.stat-icon.draft { background: rgba(156, 163, 175, 0.1); color: #9ca3af; }
.stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-icon.inspecting { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-icon.approved { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
.stat-icon.confirmed { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
.stat-icon.unpaid { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
.stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

/* Info Grid */
.info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.info-item { display: flex; flex-direction: column; gap: 4px; }
.info-item .label { font-size: 13px; color: var(--text-muted); text-transform: uppercase; }
.info-item .value { font-size: 15px; font-weight: 500; color: var(--text-primary); }
.info-item .value a { color: var(--primary); text-decoration: none; }
.info-item .value a:hover { text-decoration: underline; }

/* Summary Box */
.summary-box { background: var(--body-bg); border-radius: 8px; padding: 20px; }
.summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--card-border); color: var(--text-primary); }
.summary-row:last-child { border-bottom: none; }
.summary-row.total { font-size: 18px; font-weight: 700; padding-top: 12px; border-top: 2px solid var(--card-border); }
.summary-row.balance { color: var(--danger); }

/* Summary Grid - for forms with notes + totals */
.summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.summary-left { }
.summary-right { }
@media (max-width: 768px) { .summary-grid { grid-template-columns: 1fr; } }

/* Filters */
.filters { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 16px; }
.filters .form-control { width: auto; min-width: 150px; }

/* Pagination */
.pagination { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 16px 24px; 
    border-top: 1px solid var(--card-border); 
}
.pagination-info { color: var(--text-muted); font-size: 14px; }
.pagination-links { display: flex; gap: 4px; }
.pagination-links button { 
    padding: 8px 12px; 
    border: 1px solid var(--card-border); 
    background: var(--card-bg); 
    color: var(--text-primary);
    border-radius: 6px; 
    cursor: pointer; 
}
.pagination-links button:hover { background: var(--body-bg); }
.pagination-links button.active { background: var(--primary); color: #fff; border-color: var(--primary); }
.pagination-links button:disabled { opacity: 0.5; cursor: not-allowed; }

/* Checkbox */
.checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary); }

/* Empty State */
.empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
.empty-state h4 { margin: 16px 0 8px; color: var(--text-primary); }

/* Modal */
.modal { 
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(0,0,0,0.5); 
}
.modal.show { display: flex; align-items: center; justify-content: center; }
.modal-content { 
    background: var(--card-bg); 
    border-radius: 12px; 
    width: 100%; 
    max-width: 500px; 
    max-height: 90vh; 
    overflow-y: auto;
    border: 1px solid var(--card-border);
}
.modal-header { 
    padding: 16px 24px; 
    border-bottom: 1px solid var(--card-border); 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
}
.modal-header h5 { margin: 0; font-size: 18px; color: var(--text-primary); }
.modal-close { 
    background: none; 
    border: none; 
    font-size: 24px; 
    cursor: pointer; 
    color: var(--text-muted); 
}
.modal-close:hover { color: var(--text-primary); }
.modal-body { padding: 24px; }
.modal-footer { 
    padding: 16px 24px; 
    border-top: 1px solid var(--card-border); 
    display: flex; 
    justify-content: flex-end; 
    gap: 12px; 
}

/* Product Info */
.product-info { display: flex; flex-direction: column; gap: 2px; }
.product-name { font-weight: 600; color: var(--text-primary); }
.product-sku { font-size: 12px; color: var(--text-muted); }
.product-meta { font-size: 12px; color: var(--text-muted); display: flex; flex-wrap: wrap; gap: 4px; }

/* =========================================
   DETAIL / SHOW PAGES
   ========================================= */

.detail-page { padding: 20px; }

/* Detail Header */
.detail-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 16px;
}
.detail-header-left { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.detail-header h1 { 
    font-size: 22px; 
    font-weight: 700; 
    color: var(--text-primary); 
    margin: 0; 
    display: flex; 
    align-items: center; 
    gap: 12px;
    flex-wrap: wrap;
}
.btn-back {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    color: var(--text-muted);
    text-decoration: none;
    transition: all 0.2s;
}
.btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
.btn-back svg { width: 20px; height: 20px; }

/* Grid Layouts */
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }

/* Detail Card */
.detail-card { 
    background: var(--card-bg); 
    border: 1px solid var(--card-border); 
    border-radius: 12px; 
    overflow: hidden;
    margin-bottom: 20px;
}
.detail-card-header { 
    padding: 14px 20px; 
    background: var(--body-bg); 
    border-bottom: 1px solid var(--card-border);
}
.detail-card-title { 
    margin: 0; 
    font-size: 14px; 
    font-weight: 600; 
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-card-title svg { width: 18px; height: 18px; color: var(--primary); flex-shrink: 0; }
.detail-card-body { padding: 16px 20px; }

/* Detail Rows - Key/Value pairs */
.detail-row { 
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 12px;
    padding: 10px 0; 
    border-bottom: 1px solid var(--card-border);
    align-items: flex-start;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { 
    font-size: 13px; 
    color: var(--text-muted); 
    font-weight: 500;
}
.detail-value { 
    font-size: 14px; 
    color: var(--text-primary);
    word-break: break-word;
}
.detail-value a { color: var(--primary); text-decoration: none; }
.detail-value a:hover { text-decoration: underline; }

/* Rejection Box */
.rejection-box {
    background: var(--danger-light);
    border: 1px solid var(--danger);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}
.rejection-box h6 { margin: 0 0 8px 0; font-size: 14px; color: var(--danger); }
.rejection-box p { margin: 0; font-size: 14px; color: var(--text-primary); }

/* Bank Card */
.bank-card {
    background: var(--body-bg);
    border: 1px solid var(--card-border);
    border-radius: 8px;
    padding: 16px;
}
.bank-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.bank-title svg { width: 16px; height: 16px; }
.bank-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px dashed var(--card-border);
}
.bank-row:last-child { border-bottom: none; }
.bank-label { color: var(--text-muted); }
.bank-value { color: var(--text-primary); font-weight: 500; }

/* Payment History Card */
.payment-card {
    background: var(--body-bg);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.payment-info h6 { margin: 0 0 4px 0; font-size: 14px; color: var(--text-primary); }
.payment-info p { margin: 0; font-size: 12px; color: var(--text-muted); }
.payment-amount { font-size: 16px; font-weight: 700; color: var(--success); }

/* Cheque Fields */
.cheque-fields { display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--card-border); }
.cheque-fields.show { display: block; }

/* Section Title */
.section-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Responsive for Detail Pages */
@media (max-width: 1024px) {
    .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
    .detail-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 768px) {
    .detail-row { grid-template-columns: 1fr; gap: 4px; }
    .detail-label { font-size: 12px; }
}

/* Table Responsive */
.table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

/* Divider */
hr.divider { margin: 20px 0; border: none; border-top: 1px solid var(--card-border); }

/* Text utilities */
.text-end { text-align: right; }
.text-center { text-align: center; }
.text-muted { color: var(--text-muted); }
.text-success { color: var(--success); }
.text-danger { color: var(--danger); }
.text-warning { color: var(--warning); }

/* Settings Grid */
.settings-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
.form-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }

/* Toggle Switch */
.toggle-switch { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    padding: 12px 0; 
    border-bottom: 1px solid var(--card-border); 
}
.toggle-switch:last-child { border-bottom: none; }
.toggle-label { font-size: 14px; color: var(--text-secondary); }
.toggle-desc { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { 
    position: absolute; 
    cursor: pointer; 
    top: 0; left: 0; right: 0; bottom: 0; 
    background-color: var(--card-border); 
    transition: .3s; 
    border-radius: 24px; 
}
.slider:before { 
    position: absolute; 
    content: ""; 
    height: 18px; 
    width: 18px; 
    left: 3px; 
    bottom: 3px; 
    background-color: white; 
    transition: .3s; 
    border-radius: 50%; 
}
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(20px); }

/* Color Input */
.color-input-wrapper { display: flex; align-items: center; gap: 10px; }
.color-input-wrapper input[type="color"] { 
    width: 50px; 
    height: 38px; 
    border: 1px solid var(--card-border); 
    border-radius: 6px; 
    padding: 2px; 
    cursor: pointer; 
    background: var(--card-bg);
}
.color-input-wrapper input[type="text"] { flex: 1; }

/* Preview Box */
.preview-box { 
    background: var(--body-bg); 
    border: 1px solid var(--card-border); 
    border-radius: 8px; 
    padding: 20px; 
    margin-top: 16px; 
}
.preview-header { padding: 15px; border-radius: 6px; color: #fff; margin-bottom: 15px; }
.preview-header h4 { margin: 0; font-size: 14px; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 11px; }
.preview-table th { padding: 8px; text-align: left; border-bottom: 2px solid var(--card-border); color: var(--text-primary); }
.preview-table td { padding: 8px; border-bottom: 1px solid var(--card-border); color: var(--text-secondary); }
.preview-total { padding: 8px; text-align: right; font-weight: bold; border-radius: 4px; }

/* Responsive */
@media (max-width: 1024px) { 
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .form-row { grid-template-columns: repeat(2, 1fr); }
    .form-row-3 { grid-template-columns: repeat(2, 1fr); }
    .settings-grid { grid-template-columns: 1fr; }
    .info-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) { 
    .form-row { grid-template-columns: 1fr; } 
    .form-row-3 { grid-template-columns: 1fr; }
    .form-group.col-2, .form-group.col-3 { grid-column: span 1; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>
