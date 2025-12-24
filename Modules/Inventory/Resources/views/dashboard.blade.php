

<style>
    /* ============================================
       INVENTORY DASHBOARD - Dark/Light Mode Ready
       Uses CSS variables + rgba() for transparency
       ============================================ */
    
    .dashboard-wrapper {
        padding: 20px;
        max-width: 100%;
        overflow-x: hidden;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-header-left h1 svg {
        width: 32px;
        height: 32px;
        color: #6366f1;
    }
    
    .page-header-left p {
        margin: 4px 0 0 44px;
        color: var(--text-muted);
        font-size: 14px;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    
    .btn svg { width: 18px; height: 18px; }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: #fff;
    }
    
    .btn-secondary {
        background: var(--card-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }

    /* Main Stats Grid */
    .main-stats {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 1200px) { .main-stats { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .main-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .main-stats { grid-template-columns: 1fr; } }
    
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f97316, #fb923c); }
    .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #f87171); }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon svg { width: 24px; height: 24px; }
    
    .stat-icon.blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .stat-icon.orange { background: rgba(249, 115, 22, 0.15); color: #f97316; }
    .stat-icon.red { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    
    .stat-trend {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 600;
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 4px;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Summary Cards Row */
    .summary-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 1100px) { .summary-row { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 768px) { .summary-row { grid-template-columns: 1fr; } }
    
    .summary-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
    }
    
    .summary-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .summary-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .summary-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .stock-value-display {
        text-align: center;
        padding: 20px 0;
    }
    
    .stock-value-amount {
        font-size: 32px;
        font-weight: 700;
        color: #10b981;
        margin-bottom: 8px;
    }
    
    .stock-value-label {
        font-size: 14px;
        color: var(--text-muted);
    }
    
    .stock-value-breakdown {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--card-border);
    }
    
    .breakdown-item {
        text-align: center;
        padding: 12px;
        background: var(--body-bg);
        border-radius: 10px;
    }
    
    .breakdown-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .breakdown-label {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Today's Activity */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 600px) { .activity-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .activity-item {
        text-align: center;
        padding: 16px 12px;
        background: var(--body-bg);
        border-radius: 12px;
        transition: all 0.2s;
    }
    
    .activity-item:hover { transform: scale(1.02); }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 18px;
    }
    
    .activity-icon.in { background: rgba(16, 185, 129, 0.15); }
    .activity-icon.out { background: rgba(239, 68, 68, 0.15); }
    .activity-icon.transfer { background: rgba(139, 92, 246, 0.15); }
    .activity-icon.adjust { background: rgba(245, 158, 11, 0.15); }
    
    .activity-count {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    .activity-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* Warehouse Overview */
    .warehouse-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .warehouse-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: var(--body-bg);
        border-radius: 12px;
        transition: all 0.2s;
    }
    
    .warehouse-item:hover { background: rgba(99, 102, 241, 0.1); }
    
    .warehouse-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .warehouse-info { flex: 1; min-width: 0; }
    
    .warehouse-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .warehouse-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* Quick Actions */
    .quick-actions {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 1200px) { .quick-actions-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 600px) { .quick-actions-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px 12px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 11px;
        color: #fff;
        transition: all 0.2s;
        text-align: center;
    }
    
    .quick-action-btn:hover { transform: translateY(-3px); color: #fff; }
    .quick-action-btn svg { width: 24px; height: 24px; }
    
    .quick-action-btn.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .quick-action-btn.blue:hover { box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4); }
    .quick-action-btn.green { background: linear-gradient(135deg, #10b981, #059669); }
    .quick-action-btn.green:hover { box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); }
    .quick-action-btn.orange { background: linear-gradient(135deg, #f97316, #ea580c); }
    .quick-action-btn.orange:hover { box-shadow: 0 8px 24px rgba(249, 115, 22, 0.4); }
    .quick-action-btn.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .quick-action-btn.purple:hover { box-shadow: 0 8px 24px rgba(139, 92, 246, 0.4); }
    .quick-action-btn.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .quick-action-btn.cyan:hover { box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4); }
    .quick-action-btn.indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .quick-action-btn.indigo:hover { box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4); }
    .quick-action-btn.pink { background: linear-gradient(135deg, #ec4899, #db2777); }
    .quick-action-btn.pink:hover { box-shadow: 0 8px 24px rgba(236, 72, 153, 0.4); }
    .quick-action-btn.teal { background: linear-gradient(135deg, #14b8a6, #0d9488); }
    .quick-action-btn.teal:hover { box-shadow: 0 8px 24px rgba(20, 184, 166, 0.4); }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    @media (max-width: 992px) { .content-grid { grid-template-columns: 1fr; } }
    
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--body-bg);
    }
    
    .table-card-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .table-card-title svg {
        width: 20px;
        height: 20px;
        color: #6366f1;
    }
    
    .table-card-body {
        padding: 0;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .simple-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .simple-table th,
    .simple-table td {
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .simple-table th {
        background: var(--body-bg);
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    
    .simple-table tbody tr { transition: background 0.15s; }
    .simple-table tbody tr:hover { background: var(--body-bg); }
    .simple-table tbody tr:last-child td { border-bottom: none; }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .badge-success { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .badge-warning { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .badge-info { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .badge-purple { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
    .badge-cyan { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .product-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    
    .product-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-sku {
        font-size: 11px;
        color: var(--text-muted);
    }

    .stock-bar {
        width: 100%;
        height: 6px;
        background: rgba(239, 68, 68, 0.2);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 6px;
    }
    
    .stock-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #ef4444, #f87171);
        border-radius: 3px;
        transition: width 0.3s;
    }

    .qty-badge { font-weight: 700; font-size: 13px; }
    .qty-badge.positive { color: #10b981; }
    .qty-badge.negative { color: #ef4444; }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 56px;
        height: 56px;
        margin-bottom: 16px;
        opacity: 0.4;
    }
    
    .empty-state h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        color: var(--text-primary);
    }
    
    .empty-state p { margin: 0; font-size: 13px; }

    .view-all-link {
        font-size: 12px;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .view-all-link:hover { text-decoration: underline; }
    .view-all-link svg { width: 14px; height: 14px; }

    .transfer-location { font-size: 11px; line-height: 1.5; }
    .transfer-from { color: #ef4444; }
    .transfer-to { color: #10b981; }
    .transfer-arrow { color: var(--text-muted); margin: 0 4px; }

    /* Low Stock Alert Styles */
    .alert-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        background: #ef4444;
        color: #fff;
        border-radius: 11px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 8px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .stock-status-bar {
        display: flex;
        gap: 12px;
        padding: 12px 16px;
        background: var(--body-bg);
        border-bottom: 1px solid var(--card-border);
    }
    
    .status-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
    }
    
    .status-item.status-out {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    .status-item.status-critical {
        background: rgba(249, 115, 22, 0.15);
        color: #f97316;
    }
    
    .status-item.status-warning {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }
    
    .status-count {
        font-weight: 700;
        font-size: 14px;
    }
    
    .status-label {
        font-size: 11px;
        opacity: 0.9;
    }
    
    .product-avatar.out {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    .product-avatar.critical {
        background: rgba(249, 115, 22, 0.15);
        color: #f97316;
    }
    
    .product-avatar.warning {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }
    
    .stock-bar.out { background: rgba(239, 68, 68, 0.2); }
    .stock-bar.out .stock-bar-fill { background: #ef4444; }
    
    .stock-bar.critical { background: rgba(249, 115, 22, 0.2); }
    .stock-bar.critical .stock-bar-fill { background: linear-gradient(90deg, #f97316, #fb923c); }
    
    .stock-bar.warning { background: rgba(245, 158, 11, 0.2); }
    .stock-bar.warning .stock-bar-fill { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    
    .badge-dark {
        background: rgba(30, 30, 30, 0.9);
        color: #fff;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 11px;
        border-radius: 6px;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
    }
    
    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .empty-state.success svg {
        color: #10b981;
        opacity: 1;
    }
    
    .empty-state.success h4 {
        color: #10b981;
    }
</style>

<div class="dashboard-wrapper">
    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                {{ $greeting ?? 'Good Morning' }}!
            </h1>
            <p>Here's what's happening with your inventory today.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('inventory.stock.movements') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                View History
            </a>
            <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>
    </div>

    <!-- Main Stats -->
    <div class="main-stats">
        <div class="stat-card blue">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalProducts'] ?? 0) }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalWarehouses'] ?? 0) }}</div>
            <div class="stat-label">Warehouses</div>
        </div>
        
        <div class="stat-card purple">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalRacks'] ?? 0) }}</div>
            <div class="stat-label">Storage Racks</div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['totalCategories'] ?? 0) }}</div>
            <div class="stat-label">Categories</div>
        </div>
        
        <div class="stat-card red">
            <div class="stat-header">
                <div class="stat-icon red">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                @if(($lowStockCount ?? 0) > 0)
                    <span class="stat-trend">⚠️ Alert</span>
                @endif
            </div>
            <div class="stat-value">{{ number_format($lowStockCount ?? 0) }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="summary-row">
        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Stock Value
                </div>
            </div>
            <div class="stock-value-display">
                <div class="stock-value-amount">₹{{ number_format($totalStockValue ?? 0, 2) }}</div>
                <div class="stock-value-label">Total Inventory Value</div>
            </div>
            <div class="stock-value-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ number_format($totalStockQty ?? 0, 0) }}</div>
                    <div class="breakdown-label">Total Units</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['totalBrands'] ?? 0 }}</div>
                    <div class="breakdown-label">Brands</div>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Today's Activity
                </div>
                <span style="font-size: 11px; color: var(--text-muted);">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="activity-grid">
                <div class="activity-item">
                    <div class="activity-icon in">📥</div>
                    <div class="activity-count">{{ $todayIn ?? 0 }}</div>
                    <div class="activity-label">Received</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon out">📤</div>
                    <div class="activity-count">{{ $todayOut ?? 0 }}</div>
                    <div class="activity-label">Delivered</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon transfer">🔄</div>
                    <div class="activity-count">{{ $todayTransfer ?? 0 }}</div>
                    <div class="activity-label">Transfers</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon adjust">⚖️</div>
                    <div class="activity-count">{{ $todayAdjust ?? 0 }}</div>
                    <div class="activity-label">Adjustments</div>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <div class="summary-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    Warehouses
                </div>
                <a href="{{ route('inventory.warehouses.index') }}" class="view-all-link">View All <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>
            <div class="warehouse-list">
                @forelse(($warehousesWithStock ?? collect())->take(3) as $warehouse)
                    <div class="warehouse-item">
                        <div class="warehouse-icon">{{ strtoupper(substr($warehouse->name, 0, 2)) }}</div>
                        <div class="warehouse-info">
                            <div class="warehouse-name">{{ $warehouse->name }}</div>
                            <div class="warehouse-meta">{{ $warehouse->racks_count ?? 0 }} racks • {{ number_format($warehouse->stock_levels_sum_qty ?? 0, 0) }} units</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="padding: 24px;"><p>No warehouses yet</p></div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Quick Actions
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('inventory.products.create') }}" class="quick-action-btn blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
            <a href="{{ route('inventory.stock.receive') }}" class="quick-action-btn green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                Receive
            </a>
            <a href="{{ route('inventory.stock.deliver') }}" class="quick-action-btn orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                Deliver
            </a>
            <a href="{{ route('inventory.stock.transfer') }}" class="quick-action-btn purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Transfer
            </a>
            <a href="{{ route('inventory.stock.returns') }}" class="quick-action-btn cyan">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Return
            </a>
            <a href="{{ route('inventory.stock.adjustments') }}" class="quick-action-btn teal">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Adjust
            </a>
            <a href="{{ route('inventory.reports.stock-summary') }}" class="quick-action-btn indigo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Reports
            </a>
            <a href="{{ route('inventory.settings.index') }}" class="quick-action-btn pink">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Low Stock Alerts
                    @if(($lowStockCount ?? 0) > 0)
                        <span class="alert-count">{{ $lowStockCount }}</span>
                    @endif
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    @if(($lowStockCount ?? 0) > 0)
                    <button type="button" class="btn btn-sm btn-warning" onclick="createLowStockNotifications()" title="Send notifications">
                        🔔 Notify
                    </button>
                    @endif
                    <a href="{{ route('inventory.products.index') }}?filter=low_stock" class="view-all-link">View All <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                </div>
            </div>
            
            @if(isset($stockStatusSummary) && ($stockStatusSummary['total_low_stock'] ?? 0) > 0)
            <div class="stock-status-bar">
                <div class="status-item status-out" title="Out of Stock">
                    <span class="status-count">{{ $stockStatusSummary['out_of_stock'] ?? 0 }}</span>
                    <span class="status-label">Out</span>
                </div>
                <div class="status-item status-critical" title="Critical (below 50% of min)">
                    <span class="status-count">{{ $stockStatusSummary['critical'] ?? 0 }}</span>
                    <span class="status-label">Critical</span>
                </div>
                <div class="status-item status-warning" title="Warning">
                    <span class="status-count">{{ $stockStatusSummary['warning'] ?? 0 }}</span>
                    <span class="status-label">Warning</span>
                </div>
            </div>
            @endif
            
            <div class="table-card-body">
                @if(($lowStockProducts ?? collect())->count() > 0)
                    <table class="simple-table">
                        <thead><tr><th>Product / SKU</th><th>Current</th><th>Min</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($lowStockProducts as $item)
                            @php 
                                $percentage = $item->min_stock_level > 0 ? min(100, ($item->current_stock / $item->min_stock_level) * 100) : 0;
                                $statusClass = $item->current_stock <= 0 ? 'out' : ($percentage <= 50 ? 'critical' : 'warning');
                                $productUrl = isset($item->product_id) 
                                    ? route('inventory.products.show', $item->product_id) 
                                    : route('inventory.products.show', $item->id);
                            @endphp
                            <tr onclick="window.location='{{ $productUrl }}'" style="cursor: pointer;">
                                <td>
                                    <div class="product-cell">
                                        <div class="product-avatar {{ $statusClass }}">
                                            @if($item->current_stock <= 0)
                                                ⚠️
                                            @else
                                                {{ strtoupper(substr($item->name, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <span class="product-name">
                                                {{ Str::limit($item->name, 18) }}
                                                @if(isset($item->variation_name) && $item->type === 'variation')
                                                    <small style="color: var(--text-muted);">({{ Str::limit($item->variation_name, 12) }})</small>
                                                @endif
                                            </span>
                                            <span class="product-sku">{{ $item->sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->current_stock <= 0 ? 'badge-dark' : 'badge-danger' }}">
                                        {{ number_format($item->current_stock, 0) }}
                                    </span>
                                </td>
                                <td>{{ number_format($item->min_stock_level, 0) }}</td>
                                <td>
                                    <div class="stock-bar {{ $statusClass }}">
                                        <div class="stock-bar-fill" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state success">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4>All Stocked Up! 🎉</h4>
                        <p>All products are above minimum stock levels.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    Recent Movements
                </div>
                <a href="{{ route('inventory.stock.movements') }}" class="view-all-link">View All <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            </div>
            <div class="table-card-body">
                @if(($recentMovements ?? collect())->count() > 0)
                    <table class="simple-table">
                        <thead><tr><th>Product</th><th>Type</th><th>Qty</th><th>Location</th></tr></thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                            @php
                                $badgeConfig = match($movement->movement_type) {
                                    'IN' => ['class' => 'badge-success', 'icon' => '📥', 'label' => 'IN'],
                                    'OUT' => ['class' => 'badge-danger', 'icon' => '📤', 'label' => 'OUT'],
                                    'TRANSFER' => ['class' => 'badge-purple', 'icon' => '🔄', 'label' => 'TRF'],
                                    'RETURN' => ['class' => 'badge-cyan', 'icon' => '↩️', 'label' => 'RTN'],
                                    'ADJUSTMENT' => ['class' => 'badge-warning', 'icon' => '⚖️', 'label' => 'ADJ'],
                                    default => ['class' => 'badge-info', 'icon' => '📦', 'label' => $movement->movement_type]
                                };
                                $isPositive = in_array($movement->movement_type, ['IN', 'RETURN']);
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <span class="product-name">{{ Str::limit($movement->product->name ?? '-', 18) }}</span>
                                        <span class="product-sku">{{ $movement->created_at->format('d M, H:i') }}</span>
                                    </div>
                                </td>
                                <td><span class="badge {{ $badgeConfig['class'] }}">{{ $badgeConfig['icon'] }} {{ $badgeConfig['label'] }}</span></td>
                                <td><span class="qty-badge {{ $isPositive ? 'positive' : 'negative' }}">{{ $isPositive ? '+' : '-' }}{{ number_format($movement->qty, 0) }}</span></td>
                                <td>
                                    @if($movement->movement_type == 'TRANSFER')
                                        @php
                                            $transferNo = str_replace(['-IN', '-OUT'], '', $movement->reference_no);
                                            $transfer = ($transfers ?? collect())->get($transferNo);
                                        @endphp
                                        @if($transfer)
                                            <div class="transfer-location">
                                                <span class="transfer-from">{{ Str::limit($transfer->fromWarehouse->name ?? '-', 8) }}</span>
                                                <span class="transfer-arrow">→</span>
                                                <span class="transfer-to">{{ Str::limit($transfer->toWarehouse->name ?? '-', 8) }}</span>
                                            </div>
                                        @else
                                            {{ Str::limit($movement->warehouse->name ?? '-', 12) }}
                                        @endif
                                    @else
                                        {{ Str::limit($movement->warehouse->name ?? '-', 12) }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <h4>No Movements Yet</h4>
                        <p>Start by receiving some stock.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function createLowStockNotifications() {
    if (!confirm('Create notifications for all low stock items? This will notify admin users.')) {
        return;
    }
    
    fetch('{{ route("inventory.alerts.notify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + (data.message || 'Failed to create notifications'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('❌ Failed to create notifications');
    });
}
</script>
