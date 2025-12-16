<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); color: #fff; }
    .btn-add svg { width: 18px; height: 18px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.total { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .stat-icon.draft { background: rgba(156, 163, 175, 0.1); color: #9ca3af; }
    .stat-icon.sent { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.confirmed { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-icon.received { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
    .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); }
    .table-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .table-card-title svg { width: 20px; height: 20px; color: var(--text-muted); }
</style>

<div style="padding: 20px;">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Purchase Orders
        </h1>
        <a href="{{ route('admin.purchase.orders.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            New Order
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon sent"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></div>
            <div><div class="stat-value">{{ $stats['sent'] }}</div><div class="stat-label">Sent</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon confirmed"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">Confirmed</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon received"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg></div>
            <div><div class="stat-value">{{ $stats['received'] }}</div><div class="stat-label">Received</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Purchase Order List
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.orders.data') }}"
                   data-delete-route="{{ route('admin.purchase.orders.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="po_number">PO Number</th>
                        <th class="dt-sort" data-col="po_date">Date</th>
                        <th data-col="vendor_name">Vendor</th>
                        <th data-col="pr_number">From PR</th>
                        <th data-col="items_count">Items</th>
                        <th data-col="total_amount">Amount</th>
                        <th class="dt-sort" data-col="status" data-render="badge">Status</th>
                        <th data-render="actions">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@include('core::datatable')
