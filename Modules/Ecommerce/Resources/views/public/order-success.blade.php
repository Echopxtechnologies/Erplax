@extends('ecommerce::public.shop-layout')

@section('title', 'Order Confirmed - ' . ($settings->site_name ?? 'Store'))

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="#10b981" stroke-width="2"/>
                    <path d="M7 12.5l3 3 7-7" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <h1>Order Placed Successfully!</h1>
            <p class="success-msg">Thank you for your order. We'll send you updates via email/SMS.</p>
            
            <div class="order-info">
                <div class="order-no">
                    <span>Order Number</span>
                    <strong>{{ $order->order_no }}</strong>
                </div>
                <div class="order-date">
                    <span>Order Date</span>
                    <strong>{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</strong>
                </div>
            </div>
            
            <div class="order-details">
                <h3>Order Summary</h3>
                
                <div class="items-list">
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-name">
                            {{ $item->product_name }}
                            @if($item->variation_name)
                            <span class="variant">{{ $item->variation_name }}</span>
                            @endif
                        </div>
                        <div class="item-qty">× {{ (int)$item->qty }}</div>
                        <div class="item-price">₹{{ number_format($item->total, 0) }}</div>
                    </div>
                    @endforeach
                </div>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    @if($order->shipping_fee > 0)
                    <div class="total-row">
                        <span>Shipping</span>
                        <span>₹{{ number_format($order->shipping_fee, 0) }}</span>
                    </div>
                    @endif
                    @if($order->cod_fee > 0)
                    <div class="total-row">
                        <span>COD Fee</span>
                        <span>₹{{ number_format($order->cod_fee, 0) }}</span>
                    </div>
                    @endif
                    <div class="total-row grand">
                        <span>Total</span>
                        <span>₹{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="shipping-info">
                <h3>Shipping To</h3>
                <p>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}<br>
                    📞 {{ $order->customer_phone }}
                </p>
            </div>
            
            <div class="payment-info">
                <span class="badge {{ in_array(strtolower($order->payment_method), ['cod', 'cash']) ? 'badge-warning' : 'badge-success' }}">
                    {{ strtoupper($order->payment_method) == 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                </span>
                <span class="badge badge-info">{{ $order->payment_status == 'pending' ? 'Unpaid' : ucfirst($order->payment_status) }}</span>
            </div>
            
            <div class="action-btns">
                <a href="{{ route('ecommerce.shop') }}" class="btn-continue">Continue Shopping</a>
                @auth
                <a href="{{ route('ecommerce.orders') }}" class="btn-orders">View My Orders</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
.success-page { padding: 48px 0; background: linear-gradient(180deg, #f0fdf4 0%, #f8fafc 100%); min-height: 80vh; }

.success-card { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 20px; padding: 48px 40px; text-align: center; box-shadow: 0 4px 24px rgba(0,0,0,.06); }

.success-icon { margin-bottom: 24px; }
.success-icon svg { animation: scaleIn .5s ease-out; }
@keyframes scaleIn { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.success-card h1 { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
.success-msg { font-size: 15px; color: #64748b; margin-bottom: 32px; }

.order-info { display: flex; justify-content: center; gap: 40px; padding: 20px; background: #f8fafc; border-radius: 12px; margin-bottom: 32px; }
.order-info > div { text-align: center; }
.order-info span { display: block; font-size: 12px; color: #64748b; margin-bottom: 4px; }
.order-info strong { font-size: 16px; color: #1e293b; }

.order-details { text-align: left; border-top: 1px solid #f1f5f9; padding-top: 24px; margin-bottom: 24px; }
.order-details h3 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 16px; }

.items-list { background: #f8fafc; border-radius: 10px; padding: 12px 16px; }
.order-item { display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
.order-item:last-child { border-bottom: none; }
.item-name { flex: 1; font-size: 14px; color: #1e293b; }
.item-name .variant { display: block; font-size: 12px; color: #64748b; }
.item-qty { font-size: 13px; color: #64748b; padding: 0 16px; }
.item-price { font-size: 14px; font-weight: 600; color: #1e293b; }

.order-totals { margin-top: 16px; }
.total-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #64748b; }
.total-row.grand { font-size: 18px; font-weight: 700; color: #1e293b; border-top: 1px solid #e2e8f0; margin-top: 8px; padding-top: 12px; }

.shipping-info { text-align: left; border-top: 1px solid #f1f5f9; padding-top: 24px; margin-bottom: 24px; }
.shipping-info h3 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
.shipping-info p { font-size: 14px; color: #475569; line-height: 1.7; background: #f8fafc; padding: 16px; border-radius: 10px; margin: 0; }

.payment-info { display: flex; justify-content: center; gap: 12px; margin-bottom: 32px; }
.badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-info { background: #dbeafe; color: #1e40af; }

.action-btns { display: flex; justify-content: center; gap: 16px; }
.btn-continue { padding: 14px 28px; background: #3b82f6; color: #fff; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all .2s; }
.btn-continue:hover { background: #2563eb; transform: translateY(-1px); }
.btn-orders { padding: 14px 28px; background: #f1f5f9; color: #475569; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all .2s; }
.btn-orders:hover { background: #e2e8f0; }

@media (max-width: 640px) {
    .success-card { padding: 32px 24px; margin: 0 16px; }
    .order-info { flex-direction: column; gap: 16px; }
    .action-btns { flex-direction: column; }
    .btn-continue, .btn-orders { text-align: center; }
}
</style>
@endsection
