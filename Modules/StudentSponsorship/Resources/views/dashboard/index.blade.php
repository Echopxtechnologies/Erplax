<style>
/* Dashboard Layout */
.dashboard { padding: 24px; background: var(--body-bg, #f3f4f6); min-height: calc(100vh - 60px); }
.dashboard-header { margin-bottom: 28px; }
.dashboard-title { font-size: 28px; font-weight: 800; color: var(--text-primary, #111827); margin: 0 0 8px 0; display: flex; align-items: center; gap: 12px; }
.dashboard-title svg { width: 32px; height: 32px; color: #8b5cf6; }
.dashboard-subtitle { font-size: 15px; color: var(--text-muted, #6b7280); }
.dashboard-date { font-size: 13px; color: var(--text-muted, #9ca3af); margin-top: 4px; }

/* Hero Stats */
.hero-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.hero-stat { background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); border-radius: 16px; padding: 24px; color: #fff; position: relative; overflow: hidden; }
.hero-stat::before { content: ''; position: absolute; top: -50%; right: -30%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
.hero-stat-icon { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.hero-stat-icon svg { width: 24px; height: 24px; }
.hero-stat-value { font-size: 32px; font-weight: 800; margin-bottom: 4px; }
.hero-stat-label { font-size: 14px; opacity: 0.9; }
.hero-stat-change { font-size: 12px; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
.hero-stat-change.up { color: #a7f3d0; }
.hero-stat-change.down { color: #fecaca; }

.hero-stat.purple { --gradient-start: #8b5cf6; --gradient-end: #6d28d9; }
.hero-stat.blue { --gradient-start: #3b82f6; --gradient-end: #1d4ed8; }
.hero-stat.green { --gradient-start: #10b981; --gradient-end: #059669; }
.hero-stat.orange { --gradient-start: #f59e0b; --gradient-end: #d97706; }

/* Stat Cards Grid */
.stats-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card { background: var(--card-bg, #fff); border-radius: 12px; padding: 20px; border: 1px solid var(--card-border, #e5e7eb); }
.stat-card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.stat-card-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.stat-card-icon svg { width: 18px; height: 18px; }
.stat-card-icon.purple { background: #f3e8ff; color: #7c3aed; }
.stat-card-icon.blue { background: #dbeafe; color: #2563eb; }
.stat-card-icon.green { background: #d1fae5; color: #059669; }
.stat-card-icon.orange { background: #fef3c7; color: #d97706; }
.stat-card-icon.red { background: #fee2e2; color: #dc2626; }
.stat-card-icon.cyan { background: #cffafe; color: #0891b2; }
.stat-card-title { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted, #6b7280); font-weight: 600; }
.stat-card-value { font-size: 24px; font-weight: 700; color: var(--text-primary, #111827); }
.stat-card-sub { font-size: 12px; color: var(--text-muted, #9ca3af); margin-top: 4px; }

/* Quick Actions */
.quick-actions { display: flex; gap: 12px; margin-bottom: 28px; flex-wrap: wrap; }
.quick-action { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--card-bg, #fff); border: 1px solid var(--card-border, #e5e7eb); border-radius: 10px; text-decoration: none; color: var(--text-primary, #374151); font-weight: 600; font-size: 14px; transition: all 0.2s; }
.quick-action:hover { border-color: #8b5cf6; background: #f5f3ff; color: #7c3aed; }
.quick-action svg { width: 18px; height: 18px; }
.quick-action.primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; border: none; }
.quick-action.primary:hover { box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4); }

/* Main Grid */
.main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }

/* Cards */
.card { background: var(--card-bg, #fff); border-radius: 16px; border: 1px solid var(--card-border, #e5e7eb); overflow: hidden; }
.card-header { padding: 20px; border-bottom: 1px solid var(--card-border, #e5e7eb); display: flex; justify-content: space-between; align-items: center; }
.card-title { font-size: 16px; font-weight: 700; color: var(--text-primary, #111827); display: flex; align-items: center; gap: 10px; }
.card-title svg { width: 20px; height: 20px; color: #8b5cf6; }
.card-body { padding: 20px; }
.card-link { font-size: 13px; color: #8b5cf6; text-decoration: none; font-weight: 600; }
.card-link:hover { text-decoration: underline; }

/* Charts */
.chart-container { height: 280px; position: relative; }
.chart-placeholder { height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted, #9ca3af); }

/* Activity List */
.activity-list { list-style: none; padding: 0; margin: 0; }
.activity-item { display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--card-border, #f3f4f6); }
.activity-item:last-child { border-bottom: none; }
.activity-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.activity-icon svg { width: 18px; height: 18px; }
.activity-icon.payment { background: #d1fae5; color: #059669; }
.activity-icon.transaction { background: #dbeafe; color: #2563eb; }
.activity-content { flex: 1; min-width: 0; }
.activity-title { font-size: 14px; font-weight: 600; color: var(--text-primary, #111827); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-desc { font-size: 13px; color: var(--text-muted, #6b7280); }
.activity-time { font-size: 12px; color: var(--text-muted, #9ca3af); white-space: nowrap; }
.activity-amount { font-size: 14px; font-weight: 700; color: #059669; }

/* Top Sponsors */
.sponsor-list { list-style: none; padding: 0; margin: 0; }
.sponsor-item { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--card-border, #f3f4f6); }
.sponsor-item:last-child { border-bottom: none; }
.sponsor-rank { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; }
.sponsor-rank.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; }
.sponsor-rank.silver { background: linear-gradient(135deg, #9ca3af, #6b7280); color: #fff; }
.sponsor-rank.bronze { background: linear-gradient(135deg, #d97706, #b45309); color: #fff; }
.sponsor-rank.default { background: var(--body-bg, #f3f4f6); color: var(--text-muted, #6b7280); }
.sponsor-avatar { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6, #6d28d9); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
.sponsor-info { flex: 1; min-width: 0; }
.sponsor-name { font-size: 14px; font-weight: 600; color: var(--text-primary, #111827); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sponsor-email { font-size: 12px; color: var(--text-muted, #9ca3af); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sponsor-amount { font-size: 14px; font-weight: 700; color: var(--text-primary, #111827); }

/* Status Badges */
.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
.status-dot.pending { background: #fbbf24; }
.status-dot.partial { background: #3b82f6; }
.status-dot.completed { background: #10b981; }

/* Empty State */
.empty-state { padding: 40px 20px; text-align: center; color: var(--text-muted, #9ca3af); }
.empty-state svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5; }

/* Responsive */
@media (max-width: 1200px) {
    .hero-stats { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { grid-template-columns: repeat(3, 1fr); }
    .main-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .hero-stats { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .quick-actions { flex-direction: column; }
}
</style>

<div class="dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
            Student Sponsorship
        </h1>
        <p class="dashboard-subtitle">Overview of sponsorship program performance</p>
        <p class="dashboard-date">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- Hero Stats -->
    <div class="hero-stats">
        <div class="hero-stat purple">
            <div class="hero-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="hero-stat-value">{{ number_format($stats['total_collected'], 0) }}</div>
            <div class="hero-stat-label">Total Collected</div>
            <div class="hero-stat-change up">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                {{ number_format($stats['this_month_collected'], 0) }} this month
            </div>
        </div>
        
        <div class="hero-stat blue">
            <div class="hero-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="hero-stat-value">{{ $stats['total_students'] }}</div>
            <div class="hero-stat-label">Active Students</div>
            <div class="hero-stat-change">{{ $stats['school_students'] }} school · {{ $stats['university_students'] }} university</div>
        </div>
        
        <div class="hero-stat green">
            <div class="hero-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <div class="hero-stat-value">{{ $stats['total_sponsors'] }}</div>
            <div class="hero-stat-label">Total Sponsors</div>
            <div class="hero-stat-change">{{ $stats['active_sponsors'] }} with active transactions</div>
        </div>
        
        <div class="hero-stat orange">
            <div class="hero-stat-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="hero-stat-value">{{ number_format($stats['outstanding_balance'], 0) }}</div>
            <div class="hero-stat-label">Outstanding Balance</div>
            <div class="hero-stat-change">{{ $stats['pending_transactions'] }} pending transactions</div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <span class="stat-card-title">Completed</span>
            </div>
            <div class="stat-card-value">{{ $stats['completed_transactions'] }}</div>
            <div class="stat-card-sub">Transactions paid in full</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <span class="stat-card-title">Pending</span>
            </div>
            <div class="stat-card-value">{{ $stats['pending_transactions'] }}</div>
            <div class="stat-card-sub">Awaiting payment</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <span class="stat-card-title">Today</span>
            </div>
            <div class="stat-card-value">{{ $stats['today_payments'] }}</div>
            <div class="stat-card-sub">{{ number_format($stats['today_amount'], 0) }} collected</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon orange"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                <span class="stat-card-title">School</span>
            </div>
            <div class="stat-card-value">{{ $stats['school_students'] }}</div>
            <div class="stat-card-sub">{{ $stats['completed_school'] }} graduated</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon cyan"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg></div>
                <span class="stat-card-title">University</span>
            </div>
            <div class="stat-card-value">{{ $stats['university_students'] }}</div>
            <div class="stat-card-sub">{{ $stats['completed_university'] }} graduated</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon red"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                <span class="stat-card-title">This Year</span>
            </div>
            <div class="stat-card-value">{{ number_format($stats['this_year_collected'], 0) }}</div>
            <div class="stat-card-sub">Collected in {{ date('Y') }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('admin.studentsponsorship.sponsors.create') }}" class="quick-action primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Sponsor
        </a>
        <a href="{{ route('admin.studentsponsorship.transactions.create') }}" class="quick-action">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            New Transaction
        </a>
        <a href="{{ route('admin.studentsponsorship.school-students.create') }}" class="quick-action">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add School Student
        </a>
        <a href="{{ route('admin.studentsponsorship.university-students.create') }}" class="quick-action">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add University Student
        </a>
        <a href="{{ route('admin.studentsponsorship.payments.index') }}" class="quick-action">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Payment History
        </a>
        <a href="{{ route('admin.studentsponsorship.receipts.index') }}" class="quick-action">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Receipt Templates
        </a>
    </div>

    <!-- Main Grid -->
    <div class="main-grid">
        <!-- Left Column -->
        <div>
            <!-- Recent Payments -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Recent Payments
                    </h3>
                    <a href="{{ route('admin.studentsponsorship.payments.index') }}" class="card-link">View All →</a>
                </div>
                <div class="card-body" style="padding: 10px 20px;">
                    @if($recentPayments->count() > 0)
                    <ul class="activity-list">
                        @foreach($recentPayments as $payment)
                        <li class="activity-item">
                            <div class="activity-icon payment">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $payment->transaction?->sponsor?->name ?? 'Unknown' }}</div>
                                <div class="activity-desc">{{ $payment->transaction?->transaction_number ?? '-' }} · {{ ucfirst($payment->payment_method ?? 'N/A') }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="activity-amount">{{ $payment->formatted_amount }}</div>
                                <div class="activity-time">{{ $payment->payment_date?->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <p>No payments recorded yet</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Recent Transactions
                    </h3>
                    <a href="{{ route('admin.studentsponsorship.transactions.index') }}" class="card-link">View All →</a>
                </div>
                <div class="card-body" style="padding: 10px 20px;">
                    @if($recentTransactions->count() > 0)
                    <ul class="activity-list">
                        @foreach($recentTransactions as $txn)
                        <li class="activity-item">
                            <div class="activity-icon transaction">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    <span class="status-dot {{ $txn->status }}"></span>
                                    {{ $txn->transaction_number }}
                                </div>
                                <div class="activity-desc">{{ $txn->sponsor?->name ?? 'Unknown' }} · {{ $txn->student_name ?? 'General' }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $txn->formatted_total }}</div>
                                <div class="activity-time">{{ $txn->created_at?->diffForHumans() ?? 'N/A' }}</div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p>No transactions yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Top Sponsors -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Top Sponsors
                    </h3>
                    <a href="{{ route('admin.studentsponsorship.sponsors.index') }}" class="card-link">View All →</a>
                </div>
                <div class="card-body" style="padding: 10px 20px;">
                    @if($topSponsors->count() > 0)
                    <ul class="sponsor-list">
                        @foreach($topSponsors as $index => $sponsor)
                        <li class="sponsor-item">
                            <div class="sponsor-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'default')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="sponsor-avatar">{{ strtoupper(substr($sponsor->name, 0, 1)) }}</div>
                            <div class="sponsor-info">
                                <div class="sponsor-name">{{ $sponsor->name }}</div>
                                <div class="sponsor-email">{{ $sponsor->email ?? 'No email' }}</div>
                            </div>
                            <div class="sponsor-amount">{{ number_format($sponsor->total_paid ?? 0, 0) }}</div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <p>No sponsors yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
