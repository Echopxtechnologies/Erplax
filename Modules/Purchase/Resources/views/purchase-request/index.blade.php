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
    .stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.approved { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-icon.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
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
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Purchase Requests
        </h1>
        <a href="{{ route('admin.purchase.requests.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            New Request
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon draft"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['approved'] }}</div><div class="stat-label">Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-label">Rejected</div></div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Purchase Request List
            </div>
        </div>
        <div class="table-card-body">
            <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.purchase.requests.data') }}"
                   data-delete-route="{{ route('admin.purchase.requests.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort dt-clickable" data-col="pr_number">PR Number</th>
                        <th class="dt-sort" data-col="pr_date">Date</th>
                        <th data-col="department">Department</th>
                        <th data-col="items_count">Items</th>
                        <th class="dt-sort" data-col="priority" data-render="badge">Priority</th>
                        <th data-col="requester_name">Requested By</th>
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
