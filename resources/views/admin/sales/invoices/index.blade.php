<x-layouts.app>
<style>
    .invoice-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Compact Header */
    .inv-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
    }
    
    .inv-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .inv-header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    
    .inv-header-icon svg {
        width: 22px;
        height: 22px;
    }
    
    .inv-header h1 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    
    .inv-header-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .btn-add-invoice {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .btn-add-invoice:hover {
        background: var(--primary-hover);
        color: #fff;
    }
    
    .btn-add-invoice svg {
        width: 16px;
        height: 16px;
    }
    
    /* Compact Stats Bar */
    .inv-stats-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .inv-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        padding: 10px 16px;
        border-radius: 8px;
        min-width: 140px;
    }
    
    .inv-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .inv-stat-icon svg {
        width: 16px;
        height: 16px;
    }
    
    .inv-stat-icon.total { background: #eff6ff; color: #2563eb; }
    .inv-stat-icon.draft { background: #f3f4f6; color: #6b7280; }
    .inv-stat-icon.sent { background: #fef3c7; color: #d97706; }
    .inv-stat-icon.paid { background: #ecfdf5; color: #059669; }
    .inv-stat-icon.overdue { background: #fef2f2; color: #dc2626; }
    
    .inv-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }
    
    .inv-stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 2px;
    }
    
    /* Filters */
    .inv-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .inv-filter-select {
        padding: 7px 12px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
        min-width: 130px;
        cursor: pointer;
    }
    
    .inv-filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    /* Table Container */
    .inv-table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    
    .inv-table-wrap table {
        min-width: 1000px;
    }
    
    /* Smaller action buttons */
    .inv-table-wrap .btn,
    .inv-table-wrap .dt-actions .btn,
    .inv-table-wrap [class*="btn-"] {
        padding: 4px 10px !important;
        font-size: 11px !important;
        border-radius: 4px !important;
    }
    
    .inv-table-wrap td:last-child .btn,
    .inv-table-wrap td:last-child [class*="btn-"] {
        padding: 3px 8px !important;
        font-size: 10px !important;
        margin: 1px !important;
    }
    
    /* Badge Styling */
    .inv-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .inv-badge.draft { background: #f3f4f6; color: #6b7280; }
    .inv-badge.sent { background: #dbeafe; color: #1d4ed8; }
    .inv-badge.paid { background: #ecfdf5; color: #059669; }
    .inv-badge.partial { background: #fef3c7; color: #92400e; }
    .inv-badge.overdue { background: #fef2f2; color: #dc2626; }
    .inv-badge.unpaid { background: #fef2f2; color: #dc2626; }
    .inv-badge.cancelled { background: #f3f4f6; color: #6b7280; }
    
    .inv-badge svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }
    
    /* Amount styling */
    .amount-display {
        font-weight: 600;
        font-size: 13px;
    }
    
    .amount-display.total { color: var(--text-primary); }
    .amount-display.paid { color: #059669; }
    .amount-display.due { color: #dc2626; }
    
    /* Invoice number styling */
    .invoice-number {
        font-weight: 600;
        color: var(--primary);
        font-size: 13px;
    }
    
    /* Prevent table cell content from wrapping */
    .inv-table-wrap td {
        white-space: nowrap;
    }
    
    .inv-table-wrap td:nth-child(3) {
        white-space: normal;
        min-width: 150px;
    }
</style>

<div class="invoice-container">
    <!-- Header -->
    <div class="inv-header">
        <div class="inv-header-left">
            <div class="inv-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1>Invoices</h1>
                <div class="inv-header-sub">Manage invoices and track payments</div>
            </div>
        </div>
        <a href="{{ route('admin.sales.invoices.create') }}" class="btn-add-invoice">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Invoice
        </a>
    </div>

    <!-- Compact Stats Bar -->
    <div class="inv-stats-bar">
        <div class="inv-stat">
            <div class="inv-stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="inv-stat-label">Total</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon draft">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['draft'] ?? 0 }}</div>
                <div class="inv-stat-label">Draft</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon sent">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['sent'] ?? 0 }}</div>
                <div class="inv-stat-label">Sent</div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon paid">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['paid'] ?? 0 }}</div>
                <div class="inv-stat-label">Paid</div>
            </div>
        </div>
        @if(isset($stats['overdue']) && $stats['overdue'] > 0)
        <div class="inv-stat">
            <div class="inv-stat-icon overdue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="inv-stat-value">{{ $stats['overdue'] }}</div>
                <div class="inv-stat-label">Overdue</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Invoices Table -->
    <div class="inv-table-wrap">
        <div class="inv-filters" style="padding: 12px 16px; border-bottom: 1px solid var(--card-border);">
            <select class="inv-filter-select" data-dt-filter="status">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select class="inv-filter-select" data-dt-filter="payment_status">
                <option value="">All Payments</option>
                <option value="unpaid">Unpaid</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>
        <table class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
               id="invoicesTable"
               data-route="{{ route('admin.sales.invoices.data') }}"
               data-import-route="{{ route('admin.sales.invoices.import') }}">
            <thead>
                <tr>
                    <th class="dt-sort dt-clickable" data-col="id">ID</th>
                    <th class="dt-sort dt-clickable" data-col="invoice_number" data-render="invoice_number">Invoice #</th>
                    <th class="dt-sort" data-col="subject">Subject</th>
                    <th class="dt-sort" data-col="customer_name">Customer</th>
                    <th class="dt-sort" data-col="date">Date</th>
                    <th class="dt-sort" data-col="due_date">Due Date</th>
                    <th class="dt-sort" data-col="total" data-render="total">Total</th>
                    <th data-col="amount_paid" data-render="paid">Paid</th>
                    <th data-col="amount_due" data-render="due">Due</th>
                    <th data-col="payment_status" data-render="status">Status</th>
                    <th data-render="actions" style="min-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.invoice_number = function(data, row) {
    return '<span class="invoice-number">' + row.invoice_number + '</span>';
};

window.dtRenders.total = function(data, row) {
    return '<span class="amount-display total">' + row.total + '</span>';
};

window.dtRenders.paid = function(data, row) {
    return '<span class="amount-display paid">' + row.amount_paid + '</span>';
};

window.dtRenders.due = function(data, row) {
    var amount = parseFloat(row.amount_due.replace(/,/g, '')) || 0;
    var cls = amount > 0 ? 'due' : 'paid';
    return '<span class="amount-display ' + cls + '">' + row.amount_due + '</span>';
};

window.dtRenders.status = function(data, row) {
    var statusMap = {
        'draft': 'draft',
        'sent': 'sent',
        'paid': 'paid',
        'partial': 'partial',
        'overdue': 'overdue',
        'unpaid': 'unpaid',
        'cancelled': 'cancelled'
    };
    var status = row.payment_status || 'draft';
    var cls = statusMap[status] || 'draft';
    var label = status.charAt(0).toUpperCase() + status.slice(1);
    return '<span class="inv-badge ' + cls + '">' + label + '</span>';
};

window.dtRenders.actions = function(data, row) {
    return '<a href="/admin/sales/invoices/' + row.id + '" class="btn btn-sm btn-info">View</a> ' +
           '<a href="/admin/sales/invoices/' + row.id + '/edit" class="btn btn-sm btn-warning">Edit</a> ' +
           '<button type="button" class="btn btn-sm btn-danger" onclick="deleteInvoice(' + row.id + ')">Delete</button>';
};

function deleteInvoice(id) {
    if (!confirm('Are you sure you want to delete this invoice?')) return;
    
    fetch('/admin/sales/invoices/' + id, {
        method: 'DELETE',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            if (window.dtReload) window.dtReload();
            else location.reload();
        }
        alert(data.message || 'Invoice deleted successfully');
    })
    .catch(function() { alert('Error deleting invoice'); });
}
</script>

@include('components.datatable')
</x-layouts.app>