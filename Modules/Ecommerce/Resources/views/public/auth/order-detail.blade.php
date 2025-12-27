@extends('ecommerce::public.auth.auth-layout')

@section('title', 'Order ' . $order->order_no)

@section('content')
<div class="order-detail-page">
    <div class="order-header">
        <div class="header-left">
            <a href="{{ route('ecommerce.orders') }}" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Orders
            </a>
            <h1>Order {{ $order->order_no }}</h1>
            <p class="order-date">Placed on {{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</p>
        </div>
        <div class="header-right">
            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            <span class="payment-badge payment-{{ $order->payment_status }}">{{ $order->payment_status == 'pending' ? 'Unpaid' : ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    
    <div class="order-content">
        {{-- Order Progress Tracker --}}
        @if(!in_array($order->status, ['cancelled', 'returned']))
        <div class="order-section progress-section">
            @php
                $steps = ['pending' => 'Order Placed', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                $currentIndex = array_search($order->status, array_keys($steps));
                if ($currentIndex === false) $currentIndex = 0;
            @endphp
            <div class="progress-tracker">
                @foreach($steps as $key => $label)
                @php 
                    $stepIndex = array_search($key, array_keys($steps));
                    $isCompleted = $stepIndex < $currentIndex;
                    $isCurrent = $stepIndex == $currentIndex;
                @endphp
                <div class="progress-step {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                    <div class="step-icon">
                        @if($isCompleted)
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @elseif($key == 'pending')
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        @elseif($key == 'confirmed')
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($key == 'processing')
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        @elseif($key == 'shipped')
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        @elseif($key == 'delivered')
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @endif
                    </div>
                    <span class="step-label">{{ $label }}</span>
                    @if(!$loop->last)
                    <div class="step-line {{ $isCompleted ? 'completed' : '' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @elseif($order->status == 'cancelled')
        <div class="order-section" style="background: #fee2e2; border: 1px solid #fecaca;">
            <div style="display: flex; align-items: center; gap: 12px; color: #dc2626;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-weight: 600;">This order has been cancelled</span>
            </div>
        </div>
        @endif

        {{-- Order Timeline --}}
        <div class="order-section timeline-section">
            <h2>Order Timeline</h2>
            <div class="timeline">
                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                <div class="timeline-item">
                    <div class="timeline-dot {{ $history->status }}"></div>
                    <div class="timeline-content">
                        <span class="timeline-status">{{ ucfirst($history->status) }}</span>
                        <span class="timeline-date">{{ $history->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                        @if($history->comment)
                        <span class="timeline-comment">{{ $history->comment }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Order Items --}}
        <div class="order-section items-section">
            <h2>Order Items</h2>
            <div class="items-list">
                @foreach($order->items as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="">
                        @else
                        <div class="no-image">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="item-details">
                        <h3 class="item-name">{{ $item->product_name }}</h3>
                        @if($item->variation_name)
                        <span class="item-variant">{{ $item->variation_name }}</span>
                        @endif
                        @if($item->sku)
                        <span class="item-sku">SKU: {{ $item->sku }}</span>
                        @endif
                        <div class="item-pricing">
                            <span class="item-price">₹{{ number_format($item->unit_price, 2) }}</span>
                            <span class="item-qty">× {{ (int)$item->qty }} {{ $item->unit_name }}</span>
                        </div>
                    </div>
                    <div class="item-total">
                        ₹{{ number_format($item->total, 2) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Order Summary --}}
        <div class="order-section summary-section">
            <h2>Order Summary</h2>
            <div class="summary-rows">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax_amount > 0)
                <div class="summary-row">
                    <span>Tax</span>
                    <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>{{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'FREE' }}</span>
                </div>
                @if($order->cod_fee > 0)
                <div class="summary-row">
                    <span>COD Fee</span>
                    <span>₹{{ number_format($order->cod_fee, 2) }}</span>
                </div>
                @endif
                @if($order->discount > 0)
                <div class="summary-row discount">
                    <span>Discount</span>
                    <span>-₹{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        
        {{-- Shipping & Payment Info --}}
        <div class="order-info-grid">
            <div class="order-section shipping-section">
                <h2>Shipping Address</h2>
                <div class="address-card">
                    <p class="name">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</p>
                    <p class="phone">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        {{ $order->customer_phone }}
                    </p>
                    @if($order->customer_email)
                    <p class="email">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        {{ $order->customer_email }}
                    </p>
                    @endif
                </div>
            </div>
            
            <div class="order-section payment-section">
                <h2>Payment Information</h2>
                <div class="payment-card">
                    <div class="payment-row">
                        <span class="label">Payment Method</span>
                        <span class="value">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="label">Payment Status</span>
                        <span class="payment-badge payment-{{ $order->payment_status }}">{{ $order->payment_status == 'pending' ? 'Unpaid' : ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                    </div>
                    @if($order->payment_id)
                    <div class="payment-row">
                        <span class="label">Transaction ID</span>
                        <span class="value">{{ $order->payment_id }}</span>
                    </div>
                    @endif
                    @if($invoice)
                    <div class="payment-row">
                        <span class="label">Invoice Number</span>
                        <span class="value">{{ $invoice->invoice_number }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if($order->customer_notes)
        <div class="order-section notes-section">
            <h2>Order Notes</h2>
            <p>{{ $order->customer_notes }}</p>
        </div>
        @endif
        
        {{-- Tracking Info --}}
        @if($order->tracking_number)
        <div class="order-section tracking-section">
            <h2>Tracking Information</h2>
            <div class="tracking-card">
                <div class="tracking-row">
                    <span class="label">Carrier</span>
                    <span class="value">{{ $order->shipping_carrier ?? 'Standard Delivery' }}</span>
                </div>
                <div class="tracking-row">
                    <span class="label">Tracking Number</span>
                    <span class="value tracking-number">{{ $order->tracking_number }}</span>
                </div>
                @if($order->tracking_url)
                <a href="{{ $order->tracking_url }}" target="_blank" class="track-btn">
                    Track Package
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
        @endif
        
        {{-- Actions --}}
        <div class="order-actions">
            <a href="{{ route('ecommerce.shop') }}" class="btn-continue">Continue Shopping</a>
            @if($order->canBeCancelled())
            <form action="{{ route('ecommerce.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                @csrf
                <button type="submit" class="btn-cancel">Cancel Order</button>
            </form>
            @endif
        </div>
    </div>
</div>

<style>
.order-detail-page { max-width: 900px; margin: 0 auto; padding: 20px; }

.order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #e5e7eb; }
.header-left { display: flex; flex-direction: column; gap: 8px; }
.back-link { display: flex; align-items: center; gap: 6px; color: #6b7280; font-size: 14px; text-decoration: none; margin-bottom: 8px; }
.back-link:hover { color: #1a1a2e; }
.order-header h1 { font-size: 24px; font-weight: 600; color: #1a1a2e; margin: 0; }
.order-date { font-size: 14px; color: #6b7280; margin: 0; }
.header-right { display: flex; gap: 8px; }

.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; }
.alert-error { background: #fee2e2; color: #991b1b; }

.status-badge, .payment-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
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

.order-content { display: flex; flex-direction: column; gap: 24px; }

.order-section { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.order-section h2 { font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0 0 16px; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6; }

/* Progress Tracker */
.progress-section { padding: 24px; }
.progress-tracker { display: flex; align-items: flex-start; justify-content: space-between; position: relative; }
.progress-step { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; z-index: 1; }
.step-icon { width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; border: 3px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; transition: all 0.3s; }
.progress-step.completed .step-icon { background: #10b981; border-color: #10b981; color: #fff; }
.progress-step.current .step-icon { background: #3b82f6; border-color: #3b82f6; color: #fff; animation: pulse-ring 2s ease infinite; }
@keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); } 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); } }
.step-label { margin-top: 10px; font-size: 12px; font-weight: 600; color: #94a3b8; text-align: center; }
.progress-step.completed .step-label, .progress-step.current .step-label { color: #1e293b; }
.step-line { position: absolute; top: 24px; left: calc(50% + 24px); width: calc(100% - 48px); height: 3px; background: #e2e8f0; z-index: 0; }
.step-line.completed { background: #10b981; }
@media (max-width: 640px) { 
    .progress-tracker { flex-wrap: wrap; gap: 16px; justify-content: center; }
    .progress-step { flex: 0 0 auto; width: 60px; }
    .step-line { display: none; }
}

/* Timeline */
.timeline { display: flex; flex-direction: column; gap: 0; }
.timeline-item { display: flex; gap: 16px; padding: 12px 0; position: relative; }
.timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 7px; top: 28px; bottom: -12px; width: 2px; background: #e5e7eb; }
.timeline-dot { width: 16px; height: 16px; border-radius: 50%; flex-shrink: 0; margin-top: 2px; }
.timeline-dot.pending { background: #fbbf24; }
.timeline-dot.confirmed { background: #3b82f6; }
.timeline-dot.processing { background: #8b5cf6; }
.timeline-dot.shipped { background: #10b981; }
.timeline-dot.delivered { background: #059669; }
.timeline-dot.cancelled { background: #ef4444; }
.timeline-content { display: flex; flex-direction: column; gap: 2px; }
.timeline-status { font-weight: 600; color: #1a1a2e; font-size: 14px; }
.timeline-date { font-size: 12px; color: #6b7280; }
.timeline-comment { font-size: 13px; color: #6b7280; margin-top: 4px; }

/* Items */
.items-list { display: flex; flex-direction: column; gap: 16px; }
.order-item { display: flex; align-items: center; gap: 16px; padding: 12px; background: #fafbfc; border-radius: 8px; }
.item-image { width: 70px; height: 70px; border-radius: 8px; overflow: hidden; background: #f3f4f6; flex-shrink: 0; }
.item-image img { width: 100%; height: 100%; object-fit: cover; }
.no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #d1d5db; }
.item-details { flex: 1; }
.item-name { font-size: 15px; font-weight: 600; color: #1a1a2e; margin: 0 0 4px; }
.item-variant { display: block; font-size: 13px; color: #6b7280; }
.item-sku { display: block; font-size: 12px; color: #9ca3af; }
.item-pricing { display: flex; gap: 8px; align-items: center; margin-top: 8px; }
.item-price { font-weight: 500; color: #1a1a2e; }
.item-qty { font-size: 13px; color: #6b7280; }
.item-total { font-size: 16px; font-weight: 700; color: #1a1a2e; }

/* Summary */
.summary-rows { display: flex; flex-direction: column; gap: 10px; }
.summary-row { display: flex; justify-content: space-between; font-size: 14px; color: #6b7280; }
.summary-row.discount { color: #10b981; }
.summary-row.total { font-size: 18px; font-weight: 700; color: #1a1a2e; padding-top: 12px; border-top: 2px solid #f3f4f6; margin-top: 4px; }

/* Info Grid */
.order-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.address-card, .payment-card { font-size: 14px; color: #374151; line-height: 1.6; }
.address-card .name { font-weight: 600; color: #1a1a2e; margin-bottom: 8px; }
.address-card .phone, .address-card .email { display: flex; align-items: center; gap: 8px; margin-top: 12px; color: #6b7280; }
.payment-card { display: flex; flex-direction: column; gap: 12px; }
.payment-row { display: flex; justify-content: space-between; align-items: center; }
.payment-row .label { color: #6b7280; }
.payment-row .value { font-weight: 500; color: #1a1a2e; }

/* Tracking */
.tracking-card { display: flex; flex-direction: column; gap: 12px; }
.tracking-row { display: flex; justify-content: space-between; align-items: center; }
.tracking-number { font-family: monospace; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; }
.track-btn { display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; width: fit-content; }
.track-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }

/* Notes */
.notes-section p { font-size: 14px; color: #374151; line-height: 1.6; margin: 0; }

/* Actions */
.order-actions { display: flex; gap: 16px; justify-content: center; padding-top: 24px; }
.btn-continue { padding: 12px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.btn-continue:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
.btn-cancel { padding: 12px 24px; background: #fff; border: 2px solid #ef4444; color: #ef4444; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.btn-cancel:hover { background: #ef4444; color: #fff; }

@media (max-width: 768px) {
    .order-header { flex-direction: column; gap: 16px; }
    .order-info-grid { grid-template-columns: 1fr; }
    .order-actions { flex-direction: column; }
    .order-actions .btn-continue, .order-actions .btn-cancel { width: 100%; text-align: center; }
}
</style>
@endsection
