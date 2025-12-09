<style>
.dashboard-page { padding: 24px; }
.dashboard-header { margin-bottom: 32px; }
.dashboard-header h1 { font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0; display: flex; align-items: center; gap: 12px; }
.dashboard-header h1 svg { width: 32px; height: 32px; color: var(--primary); }
.dashboard-subtitle { color: var(--text-muted); font-size: 15px; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg { width: 28px; height: 28px; }
.stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
.stat-icon.purple { background: linear-gradient(135deg, #f3e8ff, #e9d5ff); color: #9333ea; }
.stat-icon.amber { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
.stat-icon.green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
.stat-icon.red { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
.stat-icon.indigo { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4f46e5; }

.stat-content { flex: 1; }
.stat-value { font-size: 32px; font-weight: 700; color: var(--text-primary); line-height: 1.1; }
.stat-label { font-size: 14px; color: var(--text-muted); margin-top: 4px; }

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title { font-size: 20px; font-weight: 600; color: var(--text-primary); }

.quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.quick-link-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    padding: 24px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.quick-link-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    border-color: var(--primary);
}

.quick-link-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-light);
    color: var(--primary);
    flex-shrink: 0;
}

.quick-link-icon svg { width: 24px; height: 24px; }

.quick-link-content h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 4px 0;
}

.quick-link-content p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.quick-link-arrow {
    margin-left: auto;
    color: var(--text-muted);
    transition: transform 0.2s;
}

.quick-link-card:hover .quick-link-arrow {
    transform: translateX(4px);
    color: var(--primary);
}
</style>

<div class="dashboard-page">
    <div class="dashboard-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Student Sponsor Portal
        </h1>
        <p class="dashboard-subtitle">Manage students and sponsors in one place</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['school_students']) }}</div>
                <div class="stat-label">School Students</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['university_students']) }}</div>
                <div class="stat-label">University Students</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['sponsors']) }}</div>
                <div class="stat-label">Total Sponsors</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['active_sponsors']) }}</div>
                <div class="stat-label">Active Sponsors</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['transactions']) }}</div>
                <div class="stat-label">Transactions</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon indigo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($stats['total_payments'], 2) }}</div>
                <div class="stat-label">Total Payments</div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="section-header">
        <h2 class="section-title">Quick Actions</h2>
    </div>

    <div class="quick-links">
        <a href="{{ route('admin.studentsponsor.school.index') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>School Students</h3>
                <p>View and manage school student records</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.university.index') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>University Students</h3>
                <p>View and manage university student records</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.sponsor.index') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Sponsors</h3>
                <p>View and manage sponsor information</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.school.create') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Add School Student</h3>
                <p>Register a new school student</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.university.create') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Add University Student</h3>
                <p>Register a new university student</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.sponsor.create') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Add Sponsor</h3>
                <p>Register a new sponsor</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.transaction.index') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Transactions</h3>
                <p>View and manage sponsor transactions</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.payment.index') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Payments</h3>
                <p>View all sponsor payments</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <a href="{{ route('admin.studentsponsor.transaction.create') }}" class="quick-link-card">
            <div class="quick-link-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Add Transaction</h3>
                <p>Create a new sponsor transaction</p>
            </div>
            <svg class="quick-link-arrow" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>
