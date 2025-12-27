<style>
.ws-dashboard { padding: 0; }
.ws-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
.ws-header h1 { font-size: 26px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px; }
.ws-header h1 svg { width: 32px; height: 32px; color: #6366f1; }
.header-actions { display: flex; gap: 12px; }
.btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); color: #fff; }
.btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #fff; border: 1px solid #e2e8f0; color: #475569; border-radius: 10px; font-weight: 500; font-size: 14px; text-decoration: none; transition: all 0.2s; }
.btn-secondary:hover { border-color: #6366f1; color: #6366f1; }

/* Stats Grid */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 1200px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .stats-row { grid-template-columns: 1fr; } }

.stat-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; position: relative; overflow: hidden; }
.stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, transparent 50%, rgba(99,102,241,0.05) 50%); }
.stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.stat-icon svg { width: 26px; height: 26px; }
.stat-icon.revenue { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.stat-icon.orders { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }
.stat-icon.pending { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.stat-icon.delivered { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
.stat-label { font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-value { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.2; }
.stat-sub { font-size: 13px; color: #64748b; margin-top: 8px; display: flex; align-items: center; gap: 6px; }
.stat-sub .growth { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 12px; }
.stat-sub .growth.up { background: #dcfce7; color: #15803d; }
.stat-sub .growth.down { background: #fee2e2; color: #dc2626; }

/* Dashboard Grid */
.dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px; }
@media (max-width: 1024px) { .dashboard-grid { grid-template-columns: 1fr; } }

.card { background: #fff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; overflow: hidden; }
.card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
.card-title { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; }
.card-action { font-size: 13px; color: #6366f1; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 4px; }
.card-action:hover { text-decoration: underline; }
.card-body { padding: 24px; }

/* Revenue Chart */
.chart-container { height: 220px; display: flex; align-items: flex-end; gap: 12px; padding-top: 20px; }
.chart-bar { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.bar-wrapper { width: 100%; height: 160px; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; }
.bar { width: 100%; max-width: 40px; background: linear-gradient(180deg, #6366f1, #4f46e5); border-radius: 6px 6px 0 0; transition: all 0.3s; position: relative; min-height: 4px; }
.bar:hover { background: linear-gradient(180deg, #818cf8, #6366f1); transform: scaleY(1.02); }
.bar-tooltip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #0f172a; color: #fff; padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; white-space: nowrap; opacity: 0; transition: opacity 0.2s; pointer-events: none; margin-bottom: 8px; }
.bar:hover .bar-tooltip { opacity: 1; }
.bar-label { font-size: 12px; color: #64748b; font-weight: 500; }
.chart-summary { display: flex; gap: 24px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
.summary-item { display: flex; align-items: center; gap: 8px; }
.summary-dot { width: 10px; height: 10px; border-radius: 50%; }
.summary-dot.revenue { background: #6366f1; }
.summary-dot.orders { background: #10b981; }
.summary-text { font-size: 13px; color: #64748b; }
.summary-text strong { color: #0f172a; }

/* Best Sellers */
.bestseller-list { display: flex; flex-direction: column; }
.bestseller-item { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid #f1f5f9; }
.bestseller-item:last-child { border-bottom: none; }
.bestseller-rank { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; }
.bestseller-rank.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; }
.bestseller-rank.silver { background: linear-gradient(135deg, #94a3b8, #64748b); color: #fff; }
.bestseller-rank.bronze { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; }
.bestseller-rank.default { background: #f1f5f9; color: #64748b; }
.bestseller-info { flex: 1; min-width: 0; }
.bestseller-name { font-size: 14px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bestseller-stats { font-size: 12px; color: #64748b; margin-top: 2px; }
.bestseller-revenue { font-size: 14px; font-weight: 700; color: #10b981; white-space: nowrap; }

/* Recent Orders Table */
.orders-table { width: 100%; }
.orders-table th { text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; background: #f8fafc; }
.orders-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #374151; }
.orders-table tr:last-child td { border-bottom: none; }
.orders-table tr:hover td { background: #fafbfc; }
.order-id { font-weight: 600; color: #6366f1; text-decoration: none; }
.order-id:hover { text-decoration: underline; }
.order-customer { max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #b45309; }
.status-confirmed, .status-processing { background: #dbeafe; color: #1d4ed8; }
.status-shipped { background: #d1fae5; color: #059669; }
.status-delivered { background: #10b981; color: #fff; }
.status-cancelled { background: #fee2e2; color: #dc2626; }

/* Quick Actions */
.quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.quick-action { display: flex; align-items: center; gap: 12px; padding: 16px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; text-decoration: none; transition: all 0.2s; }
.quick-action:hover { background: #fff; border-color: #6366f1; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.action-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.action-icon svg { width: 22px; height: 22px; }
.action-icon.settings { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; }
.action-icon.orders { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.action-icon.reviews { background: linear-gradient(135deg, #ec4899, #db2777); color: #fff; }
.action-icon.shop { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.action-text .title { font-weight: 600; color: #0f172a; font-size: 14px; }
.action-text .desc { font-size: 12px; color: #64748b; margin-top: 2px; }
.action-badge { background: #ef4444; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 10px; margin-left: auto; }

/* Alerts */
.alert-card { padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.alert-card.warning { background: #fef3c7; border: 1px solid #fcd34d; }
.alert-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.alert-card.warning .alert-icon { background: #f59e0b; color: #fff; }
.alert-content { flex: 1; }
.alert-title { font-weight: 600; color: #0f172a; font-size: 14px; }
.alert-desc { font-size: 13px; color: #64748b; margin-top: 2px; }
.alert-action { padding: 8px 16px; background: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #6366f1; cursor: pointer; text-decoration: none; }
.alert-action:hover { background: #f1f5f9; }
</style>

<div class="ws-dashboard">
    {{-- Header --}}
    <div class="ws-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Ecommerce Dashboard
        </h1>
        <div class="header-actions">
            <a href="{{ route('admin.ecommerce.settings') }}" class="btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
            <a href="{{ $stats['shop_url'] }}" target="_blank" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Shop
            </a>
        </div>
    </div>

    {{-- Pending Reviews Alert --}}
    @if($pendingReviews > 0)
    <div class="alert-card warning">
        <div class="alert-icon"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
        <div class="alert-content">
            <div class="alert-title">{{ $pendingReviews }} Review{{ $pendingReviews > 1 ? 's' : '' }} Pending Approval</div>
            <div class="alert-desc">Customer reviews are waiting for your approval</div>
        </div>
        <a href="{{ route('admin.ecommerce.reviews') }}" class="alert-action">Review Now</a>
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon revenue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₹{{ number_format($revenueStats['total'], 0) }}</div>
            <div class="stat-sub">
                @if($revenueStats['growth'] >= 0)
                <span class="growth up">↑ {{ $revenueStats['growth'] }}%</span>
                @else
                <span class="growth down">↓ {{ abs($revenueStats['growth']) }}%</span>
                @endif
                vs last month
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orders"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($orderStats['total']) }}</div>
            <div class="stat-sub">{{ $orderStats['this_month'] }} this month</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">{{ $orderStats['pending'] }}</div>
            <div class="stat-sub">{{ $orderStats['processing'] }} in progress</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon delivered"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="stat-label">Delivered</div>
            <div class="stat-value">{{ $orderStats['delivered'] }}</div>
            <div class="stat-sub">₹{{ number_format($revenueStats['today'], 0) }} today</div>
        </div>
    </div>

    {{-- Charts & Best Sellers --}}
    <div class="dashboard-grid">
        {{-- Revenue Chart --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Revenue Overview (Last 7 Days)</h3>
                <span class="card-action">₹{{ number_format(array_sum(array_column($dailyRevenue, 'revenue')), 0) }} total</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    @php
                        $maxRevenue = max(array_column($dailyRevenue, 'revenue')) ?: 1;
                    @endphp
                    @foreach($dailyRevenue as $day)
                    <div class="chart-bar">
                        <div class="bar-wrapper">
                            <div class="bar" style="height: {{ max(4, ($day['revenue'] / $maxRevenue) * 100) }}%">
                                <div class="bar-tooltip">₹{{ number_format($day['revenue'], 0) }} • {{ $day['orders'] }} orders</div>
                            </div>
                        </div>
                        <span class="bar-label">{{ $day['date'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="chart-summary">
                    <div class="summary-item">
                        <span class="summary-dot revenue"></span>
                        <span class="summary-text">This Month: <strong>₹{{ number_format($revenueStats['this_month'], 0) }}</strong></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-dot orders"></span>
                        <span class="summary-text">Today: <strong>₹{{ number_format($revenueStats['today'], 0) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Best Sellers --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Best Sellers</h3>
            </div>
            <div class="card-body">
                <div class="bestseller-list">
                    @forelse($bestSellers as $index => $product)
                    <div class="bestseller-item">
                        <span class="bestseller-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'default')) }}">{{ $index + 1 }}</span>
                        <div class="bestseller-info">
                            <div class="bestseller-name">{{ $product->product_name }}</div>
                            <div class="bestseller-stats">{{ number_format($product->total_qty) }} sold</div>
                        </div>
                        <span class="bestseller-revenue">₹{{ number_format($product->total_revenue, 0) }}</span>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: #94a3b8;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p>No sales data yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders & Quick Actions --}}
    <div class="dashboard-grid">
        {{-- Recent Orders --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Orders</h3>
                <a href="{{ route('admin.ecommerce.orders') }}" class="card-action">View All →</a>
            </div>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><a href="{{ route('admin.ecommerce.orders.show', $order->id) }}" class="order-id">{{ $order->order_no }}</a></td>
                        <td class="order-customer">{{ $order->customer_name }}</td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td><strong>₹{{ number_format($order->total, 0) }}</strong></td>
                        <td>{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">No orders yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.ecommerce.settings') }}" class="quick-action">
                        <div class="action-icon settings"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                        <div class="action-text">
                            <div class="title">Settings</div>
                            <div class="desc">Configure store</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.ecommerce.orders') }}" class="quick-action">
                        <div class="action-icon orders"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                        <div class="action-text">
                            <div class="title">All Orders</div>
                            <div class="desc">Manage orders</div>
                        </div>
                        @if($orderStats['pending'] > 0)
                        <span class="action-badge">{{ $orderStats['pending'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.ecommerce.reviews') }}" class="quick-action">
                        <div class="action-icon reviews"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
                        <div class="action-text">
                            <div class="title">Reviews</div>
                            <div class="desc">Moderate reviews</div>
                        </div>
                        @if($pendingReviews > 0)
                        <span class="action-badge">{{ $pendingReviews }}</span>
                        @endif
                    </a>
                    <a href="{{ $stats['shop_url'] }}" target="_blank" class="quick-action">
                        <div class="action-icon shop"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                        <div class="action-text">
                            <div class="title">View Shop</div>
                            <div class="desc">Open storefront</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Info --}}
    <div class="card" style="margin-top: 0;">
        <div class="card-header">
            <h3 class="card-title">Store Information</h3>
        </div>
        <div class="card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Status</div>
                <div style="font-weight: 600; color: {{ $stats['is_active'] ? '#10b981' : '#f59e0b' }};">{{ $stats['is_active'] ? '● Active' : '○ Inactive' }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Mode</div>
                <div style="font-weight: 600; color: #0f172a;">{{ $stats['site_mode'] }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Store URL</div>
                <a href="{{ $stats['site_url'] }}" target="_blank" style="font-weight: 500; color: #6366f1; text-decoration: none;">{{ $stats['site_url'] }}</a>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Logo</div>
                <div style="font-weight: 600; color: {{ $stats['has_logo'] ? '#10b981' : '#94a3b8' }};">{{ $stats['has_logo'] ? '✓ Uploaded' : '○ Not set' }}</div>
            </div>
        </div>
    </div>
</div>
