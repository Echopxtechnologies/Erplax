@extends('ecommerce::public.shop-layout')

@section('title', 'My Orders')

@section('content')
<div class="container">
<div class="orders-page">
    <div class="page-header">
        <h1>My Orders</h1>
        <a href="{{ route('ecommerce.account') }}" class="back-link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Account
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif
    
    @if($orders->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="70" height="70" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
        </div>
        <h2>No orders yet</h2>
        <p>You haven't placed any orders yet. Start shopping!</p>
        <a href="{{ route('ecommerce.shop') }}" class="btn-primary">Browse Products</a>
    </div>
    @else
    <div class="orders-list">
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-top">
                <div class="order-info">
                    <span class="order-no">{{ $order->order_no }}</span>
                    <span class="order-date">{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                </div>
                <div class="order-badges">
                    <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <span class="badge payment-{{ $order->payment_status }}">{{ $order->payment_status == 'pending' ? 'Unpaid' : ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                </div>
            </div>
            
            <div class="order-items">
                @php $itemCount = $order->items->count(); @endphp
                @foreach($order->items->take(3) as $item)
                <div class="item">
                    <div class="item-img">
                        @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="">
                        @else
                        <svg width="24" height="24" fill="none" stroke="#e2e8f0" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        @endif
                    </div>
                    <div class="item-info">
                        <span class="item-name">{{ $item->product_name }}</span>
                        @if($item->variation_name)
                        <span class="item-var">{{ $item->variation_name }}</span>
                        @endif
                        <span class="item-qty">Qty: {{ (int)$item->qty }}</span>
                    </div>
                    <div class="item-price">₹{{ number_format($item->total, 0) }}</div>
                </div>
                @endforeach
                @if($itemCount > 3)
                <div class="more-items">+{{ $itemCount - 3 }} more items</div>
                @endif
            </div>
            
            <div class="order-bottom">
                <div class="order-total">
                    <span>Total</span>
                    <strong>₹{{ number_format($order->total, 0) }}</strong>
                </div>
                <div class="order-actions">
                    <a href="{{ route('ecommerce.order.detail', $order->id) }}" class="btn-view">View Details</a>
                    @if($order->canBeCancelled())
                    <form action="{{ route('ecommerce.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="btn-cancel">Cancel</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="pagination-wrap">{{ $orders->links() }}</div>
    @endif
</div>
</div>

<style>
.orders-page { max-width: 900px; margin: 0 auto; padding: 40px 0; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
.page-header h1 { font-size: 28px; font-weight: 700; color: #0f172a; margin: 0; }
.back-link { display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px; font-weight: 500; text-decoration: none; padding: 10px 16px; background: #fff; border-radius: 10px; transition: all .2s; }
.back-link:hover { background: #f1f5f9; color: #0891b2; }

.alert { display: flex; align-items: center; gap: 10px; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
.alert-success { background: #ecfdf5; color: #059669; }
.alert-error { background: #fef2f2; color: #dc2626; }

.empty-state { text-align: center; padding: 80px 40px; background: #fff; border-radius: 20px; }
.empty-icon { color: #e2e8f0; margin-bottom: 24px; }
.empty-state h2 { font-size: 22px; color: #0f172a; margin: 0 0 10px; }
.empty-state p { color: #64748b; margin: 0 0 28px; }
.btn-primary { display: inline-block; padding: 14px 32px; background: #0891b2; color: #fff; text-decoration: none; border-radius: 12px; font-weight: 600; transition: all .2s; }
.btn-primary:hover { background: #0e7490; }

.orders-list { display: flex; flex-direction: column; gap: 20px; }

.order-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05); transition: all .3s; }
.order-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); }

.order-top { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
.order-info { display: flex; flex-direction: column; gap: 4px; }
.order-no { font-weight: 700; color: #0f172a; font-size: 16px; }
.order-date { font-size: 14px; color: #64748b; }
.order-badges { display: flex; gap: 8px; }

.badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-processing { background: #e0e7ff; color: #3730a3; }
.status-shipped { background: #d1fae5; color: #065f46; }
.status-delivered { background: #059669; color: #fff; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.payment-pending { background: #fef3c7; color: #92400e; }
.payment-paid { background: #d1fae5; color: #065f46; }
.payment-failed { background: #fee2e2; color: #991b1b; }

.order-items { padding: 20px 24px; }
.item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.item:last-child { border-bottom: none; }
.item-img { width: 56px; height: 56px; border-radius: 12px; overflow: hidden; background: #f8fafc; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.item-img img { width: 100%; height: 100%; object-fit: contain; }
.item-info { flex: 1; }
.item-name { display: block; font-size: 14px; font-weight: 500; color: #0f172a; margin-bottom: 4px; }
.item-var { display: block; font-size: 13px; color: #64748b; }
.item-qty { display: block; font-size: 13px; color: #94a3b8; }
.item-price { font-size: 15px; font-weight: 700; color: #0f172a; }
.more-items { font-size: 14px; color: #64748b; padding-top: 12px; }

.order-bottom { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; }
.order-total { display: flex; align-items: center; gap: 12px; }
.order-total span { font-size: 14px; color: #64748b; }
.order-total strong { font-size: 20px; color: #0f172a; }
.order-actions { display: flex; gap: 10px; }
.btn-view { padding: 10px 24px; background: #0891b2; color: #fff; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .2s; }
.btn-view:hover { background: #0e7490; }
.btn-cancel { padding: 10px 20px; background: #fff; border: 2px solid #ef4444; color: #ef4444; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; }
.btn-cancel:hover { background: #ef4444; color: #fff; }

.pagination-wrap { margin-top: 28px; display: flex; justify-content: center; }

@media (max-width: 640px) {
    .page-header { flex-direction: column; gap: 16px; align-items: flex-start; }
    .order-top { flex-direction: column; gap: 14px; align-items: flex-start; }
    .order-bottom { flex-direction: column; gap: 16px; }
    .order-actions { width: 100%; justify-content: center; }
}
</style>
@endsection
