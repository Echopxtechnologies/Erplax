<style>
.wo-container { padding: 24px; max-width: 1400px; margin: 0 auto; }
.wo-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.wo-title { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
.wo-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
.wo-stat { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #e5e7eb; }
.wo-stat.pending { border-left-color: #f59e0b; }
.wo-stat.processing { border-left-color: #3b82f6; }
.wo-stat.completed { border-left-color: #10b981; }
.wo-stat.revenue { border-left-color: #8b5cf6; }
.wo-stat-value { font-size: 28px; font-weight: 700; color: #1f2937; }
.wo-stat-label { font-size: 13px; color: #6b7280; margin-top: 4px; }
.wo-filters { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.wo-filters form { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
.wo-input { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; }
.wo-input:focus { outline: none; border-color: #3b82f6; }
.wo-btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.wo-btn-primary { background: #3b82f6; color: #fff; }
.wo-btn-primary:hover { background: #2563eb; }
.wo-btn-secondary { background: #f3f4f6; color: #374151; }
.wo-btn-secondary:hover { background: #e5e7eb; }
.wo-table-wrap { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.wo-table { width: 100%; border-collapse: collapse; }
.wo-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
.wo-table td { padding: 14px 16px; border-top: 1px solid #f3f4f6; }
.wo-table tr:hover { background: #fafafa; }
.wo-link { color: #3b82f6; text-decoration: none; font-weight: 600; }
.wo-link:hover { text-decoration: underline; }
.wo-customer { display: flex; flex-direction: column; }
.wo-customer-name { font-weight: 500; color: #1f2937; }
.wo-customer-email { font-size: 12px; color: #6b7280; }
.wo-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.wo-badge-pending { background: #fef3c7; color: #92400e; }
.wo-badge-confirmed { background: #dbeafe; color: #1e40af; }
.wo-badge-processing { background: #e0e7ff; color: #3730a3; }
.wo-badge-shipped { background: #cffafe; color: #0e7490; }
.wo-badge-delivered { background: #d1fae5; color: #065f46; }
.wo-badge-cancelled { background: #fee2e2; color: #991b1b; }
.wo-badge-paid { background: #d1fae5; color: #065f46; }
.wo-badge-unpaid { background: #fef3c7; color: #92400e; }
.wo-badge-failed { background: #fee2e2; color: #991b1b; }
.wo-amount { font-weight: 600; color: #1f2937; }
.wo-date { font-size: 13px; color: #6b7280; }
.wo-empty { text-align: center; padding: 60px 20px; color: #6b7280; }
.wo-empty-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
.wo-pagination { margin-top: 20px; display: flex; justify-content: center; }
</style>

<div class="wo-container">
    <div class="wo-header">
        <h1 class="wo-title">Website Orders</h1>
    </div>

    {{-- Stats --}}
    <div class="wo-stats">
        <div class="wo-stat pending">
            <div class="wo-stat-value">{{ $stats['pending'] ?? 0 }}</div>
            <div class="wo-stat-label">Pending Orders</div>
        </div>
        <div class="wo-stat processing">
            <div class="wo-stat-value">{{ $stats['processing'] ?? 0 }}</div>
            <div class="wo-stat-label">Processing</div>
        </div>
        <div class="wo-stat completed">
            <div class="wo-stat-value">{{ $stats['delivered'] ?? 0 }}</div>
            <div class="wo-stat-label">Delivered</div>
        </div>
        <div class="wo-stat revenue">
            <div class="wo-stat-value">₹{{ number_format($stats['revenue'] ?? 0, 0) }}</div>
            <div class="wo-stat-label">Total Revenue</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="wo-filters">
        <form method="GET" action="{{ route('admin.ecommerce.orders') }}">
            <input type="text" name="search" class="wo-input" placeholder="Search order/customer..." value="{{ request('search') }}" style="min-width: 200px;">
            <select name="status" class="wo-input">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="payment_status" class="wo-input">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            <button type="submit" class="wo-btn wo-btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.ecommerce.orders') }}" class="wo-btn wo-btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="wo-table-wrap">
        @if($orders->count() > 0)
        <table class="wo-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.ecommerce.orders.show', $order->id) }}" class="wo-link">{{ $order->order_no }}</a>
                    </td>
                    <td>
                        <div class="wo-customer">
                            <span class="wo-customer-name">{{ $order->customer->name ?? $order->shipping_name ?? 'Guest' }}</span>
                            <span class="wo-customer-email">{{ $order->customer->email ?? $order->shipping_email ?? '' }}</span>
                        </div>
                    </td>
                    <td>{{ $order->items->count() }} item(s)</td>
                    <td class="wo-amount">₹{{ number_format($order->total, 2) }}</td>
                    <td>
                        <span class="wo-badge wo-badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>
                        <span class="wo-badge wo-badge-{{ $order->payment_status == 'paid' ? 'paid' : ($order->payment_status == 'failed' ? 'failed' : 'unpaid') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="wo-date">{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.ecommerce.orders.show', $order->id) }}" class="wo-btn wo-btn-secondary" style="padding: 6px 12px; font-size: 13px;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="wo-empty">
            <div class="wo-empty-icon">📦</div>
            <p>No orders found</p>
        </div>
        @endif
    </div>

    @if($orders->hasPages())
    <div class="wo-pagination">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
