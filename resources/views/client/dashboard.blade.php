{{-- Client Dashboard --}}

<div class="page-header">
    <div>
        <h1 class="page-title">Welcome, {{ Auth::guard('web')->user()->name ?? 'Client' }}!</h1>
        <p class="page-subtitle">Your account overview</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_invoices'] ?? 0 }}</div>
            <div class="stat-label">Total Invoices</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon stat-icon-warning">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['unpaid'] ?? 0 }}</div>
            <div class="stat-label">Unpaid</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['paid'] ?? 0 }}</div>
            <div class="stat-label">Paid</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon stat-icon-danger">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-value">₹{{ number_format($stats['amount_due'] ?? 0, 2) }}</div>
            <div class="stat-label">Amount Due</div>
        </div>
    </div>
</div>

<!-- Recent Invoices -->
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Recent Invoices</h3>
    </div>
    <div class="card-body">
        @if(isset($invoices) && $invoices->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ $invoice->date?->format('M d, Y') ?? '-' }}</td>
                                <td>{{ $invoice->due_date?->format('M d, Y') ?? '-' }}</td>
                                <td>₹{{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $invoice->payment_status === 'paid' ? 'success' : ($invoice->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($invoice->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:48px;height:48px;color:var(--text-muted);margin-bottom:12px;">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p style="color:var(--text-muted);">No invoices yet</p>
            </div>
        @endif
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-lg);
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    padding: var(--space-lg);
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon svg { width: 24px; height: 24px; }

.stat-icon-primary { background: var(--primary-light); color: var(--primary); }
.stat-icon-success { background: var(--success-light); color: var(--success); }
.stat-icon-warning { background: var(--warning-light); color: var(--warning); }
.stat-icon-danger { background: var(--danger-light); color: var(--danger); }
.stat-icon-info { background: var(--info-light); color: var(--info); }

.stat-value { font-size: 24px; font-weight: 700; color: var(--text-primary); }
.stat-label { font-size: var(--font-sm); color: var(--text-muted); }

.empty-state {
    text-align: center;
    padding: var(--space-xl);
}
</style>