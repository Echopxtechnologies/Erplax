<style>
.pos-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px}
.pos-header h1{font-size:24px;font-weight:700;color:var(--text-primary);margin:0;display:flex;align-items:center;gap:10px}
.pos-header h1 svg{width:28px;height:28px;color:var(--primary)}
.btn-add{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--primary),var(--primary-hover));color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;box-shadow:0 4px 12px rgba(59,130,246,0.3);transition:all 0.2s}
.btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(59,130,246,0.4);color:#fff}
.btn-add svg{width:18px;height:18px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px;transition:all 0.2s}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-icon svg{width:24px;height:24px}
.stat-icon.total{background:var(--primary-light);color:var(--primary)}
.stat-icon.revenue{background:var(--success-light);color:var(--success)}
.stat-icon.today{background:var(--warning-light);color:var(--warning)}
.stat-icon.avg{background:#f3e8ff;color:#9333ea}
.stat-value{font-size:28px;font-weight:700;color:var(--text-primary);line-height:1}
.stat-label{font-size:13px;color:var(--text-muted);margin-top:4px}
.table-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;overflow:hidden}
.table-card-header{padding:16px 20px;border-bottom:1px solid var(--card-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px}
.table-card-title{font-size:16px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:8px}
.table-card-title svg{width:20px;height:20px;color:var(--text-muted)}
.filter-section{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.filter-select{padding:8px 12px;border:1px solid var(--input-border);border-radius:var(--radius-md);font-size:13px;background:var(--input-bg);color:var(--input-text);cursor:pointer;min-width:140px}
.filter-select:focus{outline:none;border-color:var(--primary)}
</style>

<div style="padding:20px">
<div class="pos-header">
<h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>Sales History</h1>
<a href="{{ route('admin.pos.billing') }}" class="btn-add"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>New Sale</a>
</div>

<div class="stats-grid">
<div class="stat-card"><div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><div class="stat-content"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Sales</div></div></div>
<div class="stat-card"><div class="stat-icon revenue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div class="stat-content"><div class="stat-value">₹{{ number_format($stats['revenue'], 0) }}</div><div class="stat-label">Total Revenue</div></div></div>
<div class="stat-card"><div class="stat-icon today"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div><div class="stat-content"><div class="stat-value">{{ $stats['today'] }}</div><div class="stat-label">Today's Sales</div></div></div>
<div class="stat-card"><div class="stat-icon avg"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div><div class="stat-content"><div class="stat-value">₹{{ number_format($stats['avg'], 0) }}</div><div class="stat-label">Average Sale</div></div></div>
</div>

<div class="table-card">
<div class="table-card-header">
<div class="table-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>Sales List</div>
<div class="filter-section">
<select class="filter-select" data-dt-filter="payment_method" data-dt-table="salesTable"><option value="">All Payments</option><option value="cash">Cash</option><option value="card">Card</option><option value="upi">UPI</option></select>
<select class="filter-select" data-dt-filter="status" data-dt-table="salesTable"><option value="">All Status</option><option value="completed">Completed</option><option value="voided">Voided</option></select>
</div>
</div>
<div style="padding:0">
<table id="salesTable" class="dt-table dt-search dt-export dt-perpage" data-route="{{ route('admin.pos.sales.data') }}">
<thead><tr>
<th class="dt-sort" data-col="id">ID</th>
<th class="dt-sort dt-clickable" data-col="invoice_no">Invoice</th>
<th data-col="customer_name">Customer</th>
<th data-col="items_count">Items</th>
<th class="dt-sort" data-col="payment_method" data-render="payment">Payment</th>
<th class="dt-sort" data-col="total" data-render="amount">Amount</th>
<th class="dt-sort" data-col="status" data-render="badge">Status</th>
<th class="dt-sort" data-col="created_at" data-render="datetime">Date</th>
<th data-render="actions">Actions</th>
</tr></thead>
<tbody></tbody>
</table>
</div>
</div>
</div>

@include('core::datatable')
<script>
window.dtRenders = window.dtRenders || {};
window.dtRenders.payment = function(v,r){var c={'cash':'#16a34a','card':'#2563eb','upi':'#9333ea'};return '<span style="color:'+(c[v]||'#6b7280')+';font-weight:600;text-transform:uppercase;">'+(v||'-')+'</span>';};
window.dtRenders.amount = function(v,r){return '<strong>₹'+parseFloat(v||0).toLocaleString('en-IN',{minimumFractionDigits:2})+'</strong>';};
window.dtRenders.datetime = function(v,r){if(!v)return'-';var d=new Date(v);return d.toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})+'<br><small style="color:var(--text-muted);">'+d.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit'})+'</small>';};
window.dtRenders.actions = function(v,r){
    var html = '<a href="'+r._show_url+'" class="btn-icon" title="View"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>';
    html += ' <a href="/admin/pos/invoice/'+r.id+'" target="_blank" class="btn-icon" title="Download Invoice PDF" style="color:#dc2626"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></a>';
    html += ' <a href="/admin/pos/receipt/'+r.id+'" target="_blank" class="btn-icon" title="Print Receipt" style="color:var(--success)"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg></a>';
    return html;
};
</script>
