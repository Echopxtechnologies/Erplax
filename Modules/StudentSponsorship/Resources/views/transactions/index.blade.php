@php
    $title = $title ?? 'Transactions';
    $pageTitle = $pageTitle ?? 'Sponsor Transactions';
@endphp

<style>
    .page-container { padding: 24px; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 12px; }
    .page-title svg { width: 28px; height: 28px; color: var(--primary); }
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; transition: all 0.2s; }
    .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
    .btn-primary:hover { transform: translateY(-2px); }
    .btn svg { width: 18px; height: 18px; }

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

    /* Searchable Select */
    .searchable-select { position: relative; display: inline-block; min-width: 160px; }
    .searchable-select .ss-display { width: 100%; padding: 8px 12px; font-size: 13px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); cursor: pointer; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; min-height: 38px; white-space: nowrap; }
    .searchable-select .ss-display:hover { border-color: var(--primary); }
    .searchable-select .ss-display.open { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); border-radius: 8px 8px 0 0; }
    .searchable-select .ss-arrow { transition: transform 0.2s; margin-left: 8px; }
    .searchable-select .ss-display.open .ss-arrow { transform: rotate(180deg); }
    .searchable-select .ss-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg); border: 1px solid var(--primary); border-top: none; border-radius: 0 0 8px 8px; max-height: 280px; overflow: hidden; display: none; z-index: 99999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 200px; }
    .searchable-select .ss-dropdown.show { display: block; }
    .searchable-select .ss-search { padding: 8px; border-bottom: 1px solid var(--card-border); }
    .searchable-select .ss-search input { width: 100%; padding: 8px 10px; border: 1px solid var(--input-border); border-radius: 6px; font-size: 13px; background: var(--input-bg); color: var(--input-text); box-sizing: border-box; }
    .searchable-select .ss-options { max-height: 200px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 12px; cursor: pointer; font-size: 13px; }
    .searchable-select .ss-option:hover, .searchable-select .ss-option.highlighted { background: var(--primary-light); color: var(--primary); }
    .searchable-select .ss-option.selected { background: var(--primary); color: #fff; }
    .searchable-select .ss-no-results { padding: 12px; color: var(--text-muted); font-size: 13px; text-align: center; }

    .dt-table { width: 100%; border-collapse: collapse; }
    .dt-table th { text-align: left; padding: 12px 16px; background: var(--body-bg); font-weight: 600; font-size: 12px; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--card-border); }
    .dt-table td { padding: 12px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; color: var(--text-primary); }
    .dt-table tbody tr:hover { background: rgba(59,130,246,0.05); }
    .dt-table th input[type="checkbox"], .dt-table td input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }

    .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-success { background: #dcfce7; color: #16a34a; }
    .badge-warning { background: #fef3c7; color: #d97706; }
    .badge-danger { background: #fee2e2; color: #dc2626; }
    .badge-secondary { background: #e5e7eb; color: #6b7280; }
    .badge-info { background: #dbeafe; color: #2563eb; }

    .action-btns { display: flex; gap: 6px; }
    .btn-action { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; }
    .btn-action svg { width: 16px; height: 16px; }
    .btn-view { background: #dbeafe; color: #2563eb; }
    .btn-view:hover { background: #2563eb; color: #fff; }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-edit:hover { background: #d97706; color: #fff; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: #fff; }

    .dt-footer { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid var(--card-border); flex-wrap: wrap; gap: 12px; }
    .dt-info { font-size: 13px; color: var(--text-muted); }
    .dt-pagination { display: flex; gap: 8px; }
    .dt-pagination button { padding: 8px 14px; border: 1px solid var(--card-border); border-radius: 6px; background: var(--card-bg); cursor: pointer; font-size: 13px; color: var(--text-primary); }
    .dt-pagination button:hover:not(:disabled) { background: var(--primary); color: #fff; border-color: var(--primary); }
    .dt-pagination button:disabled { opacity: 0.5; cursor: not-allowed; }
    .dt-pagination .current-page { padding: 8px 12px; background: var(--primary); color: #fff; border-radius: 6px; font-weight: 600; }

    .txn-number { font-family: monospace; font-weight: 600; color: var(--primary); }
    .sponsor-cell { display: flex; align-items: center; gap: 10px; }
    .sponsor-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; }

    .progress-mini { width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; }
    .progress-mini-fill { height: 100%; background: var(--primary); transition: width 0.3s; }
    .amount-cell { font-weight: 600; }

    .bulk-actions { display: none; padding: 12px 20px; background: #fef3c7; border-bottom: 1px solid #fcd34d; align-items: center; gap: 12px; }
    .bulk-actions.show { display: flex; }
    .bulk-actions span { font-size: 14px; font-weight: 600; color: #92400e; }
    .bulk-actions button { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; }
    .bulk-delete { background: #dc2626; color: #fff; }

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
        <a href="{{ route('admin.studentsponsorship.transactions.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Transaction
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value warning">{{ number_format($stats['pending']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Partial</div>
            <div class="stat-value info">{{ number_format($stats['partial']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value success">{{ number_format($stats['completed']) }}</div>
        </div>
    </div>

    <div class="dt-container">
        <div class="bulk-actions" id="bulkActions">
            <span><span id="selectedCount">0</span> selected</span>
            <button class="bulk-delete" onclick="bulkAction('delete')">Delete</button>
        </div>

        <div class="dt-header">
            <div class="dt-search">
                <input type="text" id="searchInput" placeholder="Search transactions...">
            </div>
            <div class="dt-filters">
                <!-- Sponsor Filter -->
                <div class="searchable-select" id="filterSponsorSelect">
                    <div class="ss-display" onclick="toggleDropdown('filterSponsorSelect')">
                        <span class="ss-text">All Sponsors</span>
                        <span class="ss-arrow">▼</span>
                    </div>
                    <div class="ss-dropdown">
                        <div class="ss-search">
                            <input type="text" placeholder="Search sponsor..." oninput="filterOptions('filterSponsorSelect', this.value)">
                        </div>
                        <div class="ss-options">
                            <div class="ss-option selected" data-value="" onclick="selectFilterOption('filterSponsorSelect', '', 'All Sponsors', 'sponsor_id')">All Sponsors</div>
                            @foreach($sponsors as $sponsor)
                                <div class="ss-option" data-value="{{ $sponsor->id }}" data-search="{{ strtolower($sponsor->name . ' ' . $sponsor->sponsor_internal_id) }}" onclick="selectFilterOption('filterSponsorSelect', '{{ $sponsor->id }}', '{{ addslashes($sponsor->name) }}', 'sponsor_id')">
                                    {{ $sponsor->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="searchable-select" id="filterStatusSelect">
                    <div class="ss-display" onclick="toggleDropdown('filterStatusSelect')">
                        <span class="ss-text">All Status</span>
                        <span class="ss-arrow">▼</span>
                    </div>
                    <div class="ss-dropdown">
                        <div class="ss-search">
                            <input type="text" placeholder="Search status..." oninput="filterOptions('filterStatusSelect', this.value)">
                        </div>
                        <div class="ss-options">
                            <div class="ss-option selected" data-value="" onclick="selectFilterOption('filterStatusSelect', '', 'All Status', 'status')">All Status</div>
                            <div class="ss-option" data-value="pending" data-search="pending" onclick="selectFilterOption('filterStatusSelect', 'pending', 'Pending', 'status')">Pending</div>
                            <div class="ss-option" data-value="partial" data-search="partial" onclick="selectFilterOption('filterStatusSelect', 'partial', 'Partial', 'status')">Partial</div>
                            <div class="ss-option" data-value="completed" data-search="completed" onclick="selectFilterOption('filterStatusSelect', 'completed', 'Completed', 'status')">Completed</div>
                            <div class="ss-option" data-value="cancelled" data-search="cancelled" onclick="selectFilterOption('filterStatusSelect', 'cancelled', 'Cancelled', 'status')">Cancelled</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Type Filter -->
                <div class="searchable-select" id="filterTypeSelect">
                    <div class="ss-display" onclick="toggleDropdown('filterTypeSelect')">
                        <span class="ss-text">All Types</span>
                        <span class="ss-arrow">▼</span>
                    </div>
                    <div class="ss-dropdown">
                        <div class="ss-search">
                            <input type="text" placeholder="Search type..." oninput="filterOptions('filterTypeSelect', this.value)">
                        </div>
                        <div class="ss-options">
                            <div class="ss-option selected" data-value="" onclick="selectFilterOption('filterTypeSelect', '', 'All Types', 'payment_type')">All Types</div>
                            <div class="ss-option" data-value="one_time" data-search="one time" onclick="selectFilterOption('filterTypeSelect', 'one_time', 'One time', 'payment_type')">One time</div>
                            <div class="ss-option" data-value="monthly" data-search="monthly" onclick="selectFilterOption('filterTypeSelect', 'monthly', 'Monthly', 'payment_type')">Monthly</div>
                            <div class="ss-option" data-value="quarterly" data-search="quarterly" onclick="selectFilterOption('filterTypeSelect', 'quarterly', 'Quarterly', 'payment_type')">Quarterly</div>
                            <div class="ss-option" data-value="yearly" data-search="yearly" onclick="selectFilterOption('filterTypeSelect', 'yearly', 'Yearly', 'payment_type')">Yearly</div>
                            <div class="ss-option" data-value="custom" data-search="custom" onclick="selectFilterOption('filterTypeSelect', 'custom', 'Custom', 'payment_type')">Custom</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="dt-table" id="transactionsTable">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="selectAll"></th>
                        <th data-col="_row_num" style="width:50px;">#</th>
                        <th data-col="transaction_number">Txn #</th>
                        <th data-col="sponsor_name">Sponsor</th>
                        <th data-col="formatted_total">Total</th>
                        <th data-col="formatted_paid">Paid</th>
                        <th data-col="progress">Progress</th>
                        <th data-col="payment_type">Type</th>
                        <th data-col="status">Status</th>
                        <th data-col="actions" style="width:120px;">Actions</th>
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
// Searchable Select Functions
function toggleDropdown(selectId) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display');
    var dropdown = container.querySelector('.ss-dropdown');
    var isOpen = dropdown.classList.contains('show');
    
    document.querySelectorAll('.ss-dropdown.show').forEach(d => {
        d.classList.remove('show');
        d.parentElement.querySelector('.ss-display').classList.remove('open');
    });
    
    if (!isOpen) {
        dropdown.classList.add('show');
        display.classList.add('open');
        var searchInput = dropdown.querySelector('.ss-search input');
        if (searchInput) setTimeout(() => searchInput.focus(), 50);
    }
}

function filterOptions(selectId, searchText) {
    var container = document.getElementById(selectId);
    var options = container.querySelectorAll('.ss-option');
    var hasResults = false;
    
    searchText = searchText.toLowerCase();
    options.forEach(opt => {
        var text = opt.getAttribute('data-search') || opt.textContent.toLowerCase();
        if (text.includes(searchText) || opt.getAttribute('data-value') === '') {
            opt.style.display = 'block';
            hasResults = true;
        } else {
            opt.style.display = 'none';
        }
    });

    var noResults = container.querySelector('.ss-no-results');
    if (!hasResults) {
        if (!noResults) {
            noResults = document.createElement('div');
            noResults.className = 'ss-no-results';
            noResults.textContent = 'No results found';
            container.querySelector('.ss-options').appendChild(noResults);
        }
        noResults.style.display = 'block';
    } else if (noResults) {
        noResults.style.display = 'none';
    }
}

function selectFilterOption(selectId, value, text, filterKey) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display .ss-text');
    var dropdown = container.querySelector('.ss-dropdown');
    
    display.textContent = text;
    dropdown.classList.remove('show');
    container.querySelector('.ss-display').classList.remove('open');
    
    container.querySelectorAll('.ss-option').forEach(opt => opt.classList.remove('selected'));
    container.querySelector('.ss-option[data-value="' + value + '"]')?.classList.add('selected');
    
    // Apply filter
    dtState.filters[filterKey] = value;
    dtState.currentPage = 1;
    loadData();
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.searchable-select')) {
        document.querySelectorAll('.ss-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.parentElement.querySelector('.ss-display').classList.remove('open');
        });
    }
});

var dtState = { data: [], total: 0, currentPage: 1, lastPage: 1, perPage: 25, search: '', filters: {}, selected: [] };

window.dtRenders = {
    _row_num: (v, r, i) => (dtState.currentPage - 1) * dtState.perPage + i + 1,
    transaction_number: (v) => '<span class="txn-number">' + v + '</span>',
    sponsor_name: (v, r) => '<div class="sponsor-cell"><div class="sponsor-avatar">' + (v ? v.charAt(0).toUpperCase() : '?') + '</div><div><div>' + (v || '-') + '</div><div style="font-size:11px;color:var(--text-muted);">' + (r.sponsor_id_display || '') + '</div></div></div>',
    formatted_total: (v) => '<span class="amount-cell">' + v + '</span>',
    formatted_paid: (v, r) => '<span class="amount-cell" style="color:var(--success);">' + v + '</span>',
    progress: (v, r) => '<div style="display:flex;align-items:center;gap:8px;"><div class="progress-mini"><div class="progress-mini-fill" style="width:' + r.payment_progress + '%;background:' + (r.payment_progress >= 100 ? 'var(--success)' : 'var(--primary)') + ';"></div></div><span style="font-size:12px;">' + Math.round(r.payment_progress) + '%</span></div>',
    payment_type: (v, r) => r.payment_type_display,
    status: (v, r) => '<span class="badge badge-' + r.status_badge + '">' + r.status_display + '</span>',
    actions: (v, r) => '<div class="action-btns">' +
        '<a href="' + r._show_url + '" class="btn-action btn-view" title="View"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg></a>' +
        '<a href="' + r._edit_url + '" class="btn-action btn-edit" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg></a>' +
        '<button onclick="deleteTransaction(' + r.id + ',\'' + r._delete_url + '\')" class="btn-action btn-delete" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg></button></div>'
};

function loadData() {
    var params = new URLSearchParams({ page: dtState.currentPage, per_page: dtState.perPage, search: dtState.search, sort: 'created_at', dir: 'desc' });
    Object.keys(dtState.filters).forEach(k => { if (dtState.filters[k]) params.append(k, dtState.filters[k]); });

    fetch('{{ route("admin.studentsponsorship.transactions.data") }}?' + params.toString())
        .then(r => r.json())
        .then(data => {
            dtState.data = data.data || [];
            dtState.total = data.total || 0;
            dtState.currentPage = data.current_page || 1;
            dtState.lastPage = data.last_page || 1;
            renderTable();
            renderPagination();
        });
}

function renderTable() {
    var tbody = document.getElementById('tableBody');
    if (!dtState.data.length) {
        tbody.innerHTML = '<tr><td colspan="10" class="empty-state"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg><h3>No transactions found</h3><p>Create your first transaction</p></td></tr>';
        return;
    }

    var cols = ['_row_num', 'transaction_number', 'sponsor_name', 'formatted_total', 'formatted_paid', 'progress', 'payment_type', 'status', 'actions'];
    var html = '';
    dtState.data.forEach((row, i) => {
        html += '<tr data-id="' + row.id + '"><td><input type="checkbox" class="row-checkbox" value="' + row.id + '" ' + (dtState.selected.includes(row.id) ? 'checked' : '') + '></td>';
        cols.forEach(col => {
            var val = row[col] ?? '';
            if (window.dtRenders[col]) val = window.dtRenders[col](val, row, i);
            html += '<td>' + val + '</td>';
        });
        html += '</tr>';
    });
    tbody.innerHTML = html;
    updateBulkUI();
}

function renderPagination() {
    var c = document.getElementById('pagination');
    c.innerHTML = '<button onclick="goToPage(1)" ' + (dtState.currentPage <= 1 ? 'disabled' : '') + '>First</button>' +
        '<button onclick="goToPage(' + (dtState.currentPage - 1) + ')" ' + (dtState.currentPage <= 1 ? 'disabled' : '') + '>Prev</button>' +
        '<span class="current-page">' + dtState.currentPage + '/' + dtState.lastPage + '</span>' +
        '<button onclick="goToPage(' + (dtState.currentPage + 1) + ')" ' + (dtState.currentPage >= dtState.lastPage ? 'disabled' : '') + '>Next</button>' +
        '<button onclick="goToPage(' + dtState.lastPage + ')" ' + (dtState.currentPage >= dtState.lastPage ? 'disabled' : '') + '>Last</button>';
    document.getElementById('tableInfo').textContent = 'Showing ' + dtState.data.length + ' of ' + dtState.total + ' entries';
}

function goToPage(p) { dtState.currentPage = p; loadData(); }

function updateBulkUI() {
    var bulk = document.getElementById('bulkActions');
    document.getElementById('selectedCount').textContent = dtState.selected.length;
    bulk.classList.toggle('show', dtState.selected.length > 0);
}

function deleteTransaction(id, url) {
    if (!confirm('Delete this transaction?')) return;
    fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(d => { if (d.success) loadData(); else alert(d.message); });
}

function bulkAction(action) {
    if (!dtState.selected.length) return;
    if (action === 'delete' && !confirm('Delete ' + dtState.selected.length + ' transactions?')) return;
    
    fetch('{{ route("admin.studentsponsorship.transactions.bulk-action") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ action: action, ids: dtState.selected })
    }).then(r => r.json()).then(d => {
        alert(d.message);
        dtState.selected = [];
        loadData();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    
    var searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            dtState.search = this.value;
            dtState.currentPage = 1;
            loadData();
        }, 300);
    });
    
    document.getElementById('selectAll').addEventListener('change', function() {
        dtState.selected = this.checked ? dtState.data.map(r => r.id) : [];
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        updateBulkUI();
    });
    
    document.getElementById('tableBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
            var id = parseInt(e.target.value);
            if (e.target.checked) { if (!dtState.selected.includes(id)) dtState.selected.push(id); }
            else { dtState.selected = dtState.selected.filter(x => x !== id); }
            updateBulkUI();
        }
    });
});
</script>
