@php
    $title = $title ?? 'Payments';
    $pageTitle = $pageTitle ?? 'Payment History';
@endphp

<style>
    .page-container { padding: 24px; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 12px; }
    .page-title svg { width: 28px; height: 28px; color: var(--primary); }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; }
    .stat-label { font-size: 13px; color: var(--text-muted); margin-bottom: 8px; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-primary); }
    .stat-value.success { color: var(--success); }
    .stat-value.warning { color: #f59e0b; }
    .stat-value.info { color: var(--primary); }

    .dt-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .dt-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--card-border); flex-wrap: wrap; gap: 12px; }
    .dt-search input { padding: 8px 14px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 14px; width: 220px; background: var(--input-bg); color: var(--input-text); }
    .dt-filters { display: flex; gap: 10px; flex-wrap: wrap; }
    .dt-filters select, .dt-filters input[type="date"] { padding: 8px 12px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 13px; background: var(--input-bg); color: var(--input-text); }

    .dt-table { width: 100%; border-collapse: collapse; }
    .dt-table th { text-align: left; padding: 12px 16px; background: var(--body-bg); font-weight: 600; font-size: 12px; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--card-border); }
    .dt-table td { padding: 12px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; color: var(--text-primary); }
    .dt-table tbody tr:hover { background: rgba(59,130,246,0.05); }

    .dt-footer { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid var(--card-border); flex-wrap: wrap; gap: 12px; }
    .dt-info { font-size: 13px; color: var(--text-muted); }
    .dt-pagination { display: flex; gap: 8px; }
    .dt-pagination button { padding: 8px 14px; border: 1px solid var(--card-border); border-radius: 6px; background: var(--card-bg); cursor: pointer; font-size: 13px; color: var(--text-primary); }
    .dt-pagination button:hover:not(:disabled) { background: var(--primary); color: #fff; border-color: var(--primary); }
    .dt-pagination button:disabled { opacity: 0.5; cursor: not-allowed; }
    .dt-pagination .current-page { padding: 8px 12px; background: var(--primary); color: #fff; border-radius: 6px; font-weight: 600; }

    .amount-cell { font-weight: 600; color: var(--success); }
    .sponsor-link { color: var(--primary); text-decoration: none; font-weight: 500; }
    .sponsor-link:hover { text-decoration: underline; }
    .txn-link { font-family: monospace; color: var(--primary); text-decoration: none; font-weight: 600; }
    .txn-link:hover { text-decoration: underline; }
    .method-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #e5e7eb; color: #374151; }
    .method-badge.bank { background: #dbeafe; color: #1e40af; }
    .method-badge.cash { background: #dcfce7; color: #16a34a; }
    .method-badge.card { background: #fef3c7; color: #d97706; }
    .method-badge.upi { background: #f3e8ff; color: #7c3aed; }
    .method-badge.cheque { background: #fce7f3; color: #be185d; }
    .method-badge.online { background: #cffafe; color: #0891b2; }

    .created-info { font-size: 12px; color: var(--text-muted); }
    .created-info .name { color: var(--text-primary); font-weight: 500; }

    .action-btns { display: flex; gap: 6px; }
    .btn-action { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; }
    .btn-action svg { width: 16px; height: 16px; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: #fff; }
    .btn-receipt { background: #f3e8ff; color: #7c3aed; }
    .btn-receipt:hover { background: #7c3aed; color: #fff; }
    .btn-download { background: #dbeafe; color: #2563eb; }
    .btn-download:hover { background: #2563eb; color: #fff; }
    .btn-email { background: #d1fae5; color: #059669; }
    .btn-email:hover { background: #059669; color: #fff; }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-state svg { width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 16px; }
    .empty-state h3 { font-size: 18px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p { color: var(--text-muted); }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .dt-header { flex-direction: column; }
        .dt-search input { width: 100%; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
            {{ $pageTitle }}
        </h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Payments</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value info">{{ number_format($stats['this_month']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value success">{{ number_format($stats['today']) }}</div>
        </div>
    </div>

    <div class="dt-container">
        <div class="dt-header">
            <div class="dt-search">
                <input type="text" id="searchInput" placeholder="Search payments...">
            </div>
            <div class="dt-filters">
                <input type="date" id="filterDateFrom" title="From Date">
                <input type="date" id="filterDateTo" title="To Date">
                <select id="filterMethod">
                    <option value="">All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                    <option value="upi">UPI</option>
                    <option value="online">Online</option>
                    <option value="card">Card</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="dt-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th data-col="_row_num" style="width:50px;">#</th>
                        <th data-col="payment_date">Date</th>
                        <th data-col="amount">Amount</th>
                        <th data-col="sponsor_name">Sponsor</th>
                        <th data-col="student_name">Student</th>
                        <th data-col="transaction_number">Transaction</th>
                        <th data-col="payment_method">Method</th>
                        <th data-col="reference_number">Reference</th>
                        <th data-col="created_by">Created By</th>
                        <th data-col="actions" style="width:80px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div class="dt-footer">
            <div class="dt-info" id="tableInfo">Showing 0 entries</div>
            <div class="dt-pagination" id="pagination"></div>
        </div>
    </div>
</div>

<script>
var dtState = { data: [], total: 0, currentPage: 1, lastPage: 1, perPage: 25, search: '', filters: {} };

window.dtRenders = {
    _row_num: (v, r, i) => (dtState.currentPage - 1) * dtState.perPage + i + 1,
    payment_date: (v) => v || '-',
    amount: (v, r) => '<span class="amount-cell">' + r.amount + '</span>',
    sponsor_name: (v, r) => r.sponsor_id ? '<a href="{{ url("admin/studentsponsorship/sponsors") }}/' + r.sponsor_id + '" class="sponsor-link">' + v + '</a>' : (v || '-'),
    student_name: (v) => v || '-',
    transaction_number: (v, r) => r.transaction_id ? '<a href="{{ url("admin/studentsponsorship/transactions") }}/' + r.transaction_id + '" class="txn-link">' + v + '</a>' : (v || '-'),
    payment_method: (v, r) => {
        var methodClass = '';
        if (v.toLowerCase().includes('bank')) methodClass = 'bank';
        else if (v.toLowerCase().includes('cash')) methodClass = 'cash';
        else if (v.toLowerCase().includes('card')) methodClass = 'card';
        else if (v.toLowerCase().includes('upi')) methodClass = 'upi';
        else if (v.toLowerCase().includes('cheque')) methodClass = 'cheque';
        else if (v.toLowerCase().includes('online')) methodClass = 'online';
        return '<span class="method-badge ' + methodClass + '">' + v + '</span>';
    },
    reference_number: (v) => v && v !== '-' ? '<span style="font-family:monospace;">' + v + '</span>' : '-',
    created_by: (v, r) => '<div class="created-info"><div class="name">' + v + '</div><div>' + (r.created_at || '') + '</div></div>',
    actions: (v, r) => '<div class="action-btns">' +
        '<a href="{{ url("admin/studentsponsorship/payments") }}/' + r.id + '/receipt" target="_blank" class="btn-action btn-receipt" title="Preview Receipt"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg></a>' +
        '<a href="{{ url("admin/studentsponsorship/payments") }}/' + r.id + '/receipt/download" class="btn-action btn-download" title="Download Receipt"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg></a>' +
        '<button onclick="sendReceipt(' + r.id + ')" class="btn-action btn-email" title="Email Receipt"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg></button>' +
        '<button onclick="deletePayment(' + r.id + ')" class="btn-action btn-delete" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg></button>' +
        '</div>'
};

function loadData() {
    var params = new URLSearchParams({
        draw: 1,
        start: (dtState.currentPage - 1) * dtState.perPage,
        length: dtState.perPage,
        'search[value]': dtState.search,
        date_from: dtState.filters.date_from || '',
        date_to: dtState.filters.date_to || '',
        payment_method: dtState.filters.payment_method || ''
    });

    fetch('{{ route("admin.studentsponsorship.payments.data") }}?' + params.toString(), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        dtState.data = res.data;
        dtState.total = res.recordsFiltered;
        dtState.lastPage = Math.ceil(res.recordsFiltered / dtState.perPage) || 1;
        renderTable();
        renderPagination();
        updateInfo();
    })
    .catch(err => console.error('Load error:', err));
}

function renderTable() {
    var tbody = document.getElementById('tableBody');
    if (dtState.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10"><div class="empty-state"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg><h3>No payments found</h3><p>Payments will appear here when recorded.</p></div></td></tr>';
        return;
    }

    var cols = [];
    document.querySelectorAll('#paymentsTable thead th[data-col]').forEach(th => cols.push(th.getAttribute('data-col')));

    tbody.innerHTML = dtState.data.map((row, idx) => {
        return '<tr>' + cols.map(col => {
            var val = row[col];
            var render = window.dtRenders[col];
            return '<td>' + (render ? render(val, row, idx) : (val ?? '-')) + '</td>';
        }).join('') + '</tr>';
    }).join('');
}

function renderPagination() {
    var pag = document.getElementById('pagination');
    if (dtState.lastPage <= 1) { pag.innerHTML = ''; return; }

    var html = '<button onclick="goToPage(' + (dtState.currentPage - 1) + ')" ' + (dtState.currentPage <= 1 ? 'disabled' : '') + '>← Prev</button>';
    
    var startPage = Math.max(1, dtState.currentPage - 2);
    var endPage = Math.min(dtState.lastPage, startPage + 4);
    if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

    for (var i = startPage; i <= endPage; i++) {
        if (i === dtState.currentPage) {
            html += '<span class="current-page">' + i + '</span>';
        } else {
            html += '<button onclick="goToPage(' + i + ')">' + i + '</button>';
        }
    }

    html += '<button onclick="goToPage(' + (dtState.currentPage + 1) + ')" ' + (dtState.currentPage >= dtState.lastPage ? 'disabled' : '') + '>Next →</button>';
    pag.innerHTML = html;
}

function goToPage(page) {
    if (page < 1 || page > dtState.lastPage) return;
    dtState.currentPage = page;
    loadData();
}

function updateInfo() {
    var start = dtState.total === 0 ? 0 : (dtState.currentPage - 1) * dtState.perPage + 1;
    var end = Math.min(dtState.currentPage * dtState.perPage, dtState.total);
    document.getElementById('tableInfo').textContent = 'Showing ' + start + ' to ' + end + ' of ' + dtState.total + ' payments';
}

function deletePayment(id) {
    if (!confirm('Delete this payment? This will update the transaction totals.')) return;
    
    fetch('{{ url("admin/studentsponsorship/payments") }}/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            loadData();
        } else {
            alert(d.message || 'Error deleting payment');
        }
    });
}

function sendReceipt(id) {
    if (!confirm('Send receipt email to the sponsor?')) return;
    
    var btn = event.target.closest('button');
    var originalContent = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin" style="width:16px;height:16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:0.25"></circle><path fill="currentColor" style="opacity:0.75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    btn.disabled = true;
    
    fetch('{{ url("admin/studentsponsorship/payments") }}/' + id + '/send-receipt', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        if (d.success) {
            alert('✓ ' + d.message);
        } else {
            alert('✗ ' + (d.message || 'Failed to send receipt'));
        }
    })
    .catch(err => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        alert('Error sending receipt');
    });
}

// Event Listeners
document.getElementById('searchInput').addEventListener('input', debounce(function(e) {
    dtState.search = e.target.value;
    dtState.currentPage = 1;
    loadData();
}, 300));

document.getElementById('filterDateFrom').addEventListener('change', function(e) {
    dtState.filters.date_from = e.target.value;
    dtState.currentPage = 1;
    loadData();
});

document.getElementById('filterDateTo').addEventListener('change', function(e) {
    dtState.filters.date_to = e.target.value;
    dtState.currentPage = 1;
    loadData();
});

document.getElementById('filterMethod').addEventListener('change', function(e) {
    dtState.filters.payment_method = e.target.value;
    dtState.currentPage = 1;
    loadData();
});

function debounce(fn, delay) {
    var timer;
    return function() {
        var args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() { fn.apply(this, args); }, delay);
    };
}

// Initial load
loadData();
</script>
