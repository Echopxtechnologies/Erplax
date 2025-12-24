@extends('website::public.auth.auth-layout')

@section('title', 'My Orders')

@section('content')
<div class="orders-page">
    <div class="orders-header">
        <h1>My Orders</h1>
        <a href="{{ route('website.account') }}" class="back-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Account
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    
    @if($orders->isEmpty())
    <div class="empty-orders">
        <div class="empty-icon">
            <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
        </div>
        <h2>No orders yet</h2>
        <p>You haven't placed any orders yet. Start shopping!</p>
        <a href="{{ route('website.shop') }}" class="btn-shop">Browse Products</a>
    </div>
    @else
    <div class="orders-list">
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-info">
                    <span class="order-number">{{ $order->order_no }}</span>
                    <span class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="order-status">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <span class="payment-badge payment-{{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                </div>
            </div>
            
            <div class="order-items">
                @php $itemCount = $order->items->count(); @endphp
                @foreach($order->items->take(3) as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="">
                        @else
                        <div class="no-image">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="item-details">
                        <span class="item-name">{{ $item->product_name }}</span>
                        @if($item->variation_name)
                        <span class="item-variant">{{ $item->variation_name }}</span>
                        @endif
                        <span class="item-qty">Qty: {{ (int)$item->qty }}</span>
                    </div>
                    <div class="item-price">₹{{ number_format($item->total, 2) }}</div>
                </div>
                @endforeach
                @if($itemCount > 3)
                <div class="more-items">+{{ $itemCount - 3 }} more items</div>
                @endif
            </div>
            
            <div class="order-footer">
                <div class="order-total">
                    <span class="total-label">Total:</span>
                    <span class="total-amount">₹{{ number_format($order->total, 2) }}</span>
                </div>
                <div class="order-actions">
                    <a href="{{ route('website.order.detail', $order->id) }}" class="btn-view">View Details</a>
                    @if($order->canBeCancelled())
                    <form action="{{ route('website.order.cancel', $order->id) }}" method="POST" class="cancel-form" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="btn-cancel">Cancel Order</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<style>
.orders-page { max-width: 900px; margin: 0 auto; padding: 20px; }
.orders-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.orders-header h1 { font-size: 24px; font-weight: 600; color: #1a1a2e; margin: 0; }
.back-link { display: flex; align-items: center; gap: 6px; color: #6b7280; font-size: 14px; text-decoration: none; }
.back-link:hover { color: #1a1a2e; }

.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; }
.alert-error { background: #fee2e2; color: #991b1b; }

.empty-orders { text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.empty-icon { margin-bottom: 16px; color: #d1d5db; }
.empty-orders h2 { font-size: 20px; color: #1a1a2e; margin: 0 0 8px; }
.empty-orders p { color: #6b7280; margin: 0 0 24px; }
.btn-shop { display: inline-block; padding: 12px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 500; transition: transform 0.2s, box-shadow 0.2s; }
.btn-shop:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }

.orders-list { display: flex; flex-direction: column; gap: 16px; }

.order-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; transition: box-shadow 0.2s; }
.order-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

.order-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #f3f4f6; background: #fafbfc; }
.order-info { display: flex; flex-direction: column; gap: 4px; }
.order-number { font-weight: 600; color: #1a1a2e; font-size: 15px; }
.order-date { font-size: 13px; color: #6b7280; }
.order-status { display: flex; gap: 8px; }

.status-badge, .payment-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-processing { background: #e0e7ff; color: #3730a3; }
.status-shipped { background: #d1fae5; color: #065f46; }
.status-delivered { background: #10b981; color: #fff; }
.status-cancelled { background: #fee2e2; color: #991b1b; }

.payment-pending { background: #fef3c7; color: #92400e; }
.payment-paid { background: #d1fae5; color: #065f46; }
.payment-failed { background: #fee2e2; color: #991b1b; }
.payment-refunded { background: #e5e7eb; color: #374151; }

.order-items { padding: 16px 20px; }
.order-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
.order-item:last-child { border-bottom: none; }
.item-image { width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: #f3f4f6; flex-shrink: 0; }
.item-image img { width: 100%; height: 100%; object-fit: cover; }
.no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #d1d5db; }
.item-details { flex: 1; }
.item-name { display: block; font-size: 14px; font-weight: 500; color: #1a1a2e; }
.item-variant { display: block; font-size: 12px; color: #6b7280; }
.item-qty { display: block; font-size: 12px; color: #9ca3af; }
.item-price { font-weight: 600; color: #1a1a2e; }
.more-items { font-size: 13px; color: #6b7280; padding: 8px 0; }

.order-footer { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid #f3f4f6; background: #fafbfc; }
.order-total { display: flex; gap: 8px; align-items: center; }
.total-label { font-size: 14px; color: #6b7280; }
.total-amount { font-size: 18px; font-weight: 700; color: #1a1a2e; }
.order-actions { display: flex; gap: 10px; }
.btn-view { padding: 8px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 500; transition: transform 0.2s; }
.btn-view:hover { transform: translateY(-1px); }
.btn-cancel { padding: 8px 16px; background: #fff; border: 1px solid #ef4444; color: #ef4444; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-cancel:hover { background: #ef4444; color: #fff; }
.cancel-form { display: inline; }

.pagination-wrapper { margin-top: 24px; display: flex; justify-content: center; }
.pagination-wrapper nav { display: flex; gap: 4px; }

@media (max-width: 640px) {
    .orders-header { flex-direction: column; gap: 12px; align-items: flex-start; }
    .order-header { flex-direction: column; gap: 12px; align-items: flex-start; }
    .order-footer { flex-direction: column; gap: 16px; align-items: stretch; }
    .order-actions { justify-content: center; }
}
</style>
@endsection
