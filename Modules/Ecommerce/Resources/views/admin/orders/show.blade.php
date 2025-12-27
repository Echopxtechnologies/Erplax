<style>
.order-container { padding: 24px; max-width: 1400px; margin: 0 auto; }
.order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.order-title { display: flex; align-items: center; gap: 12px; }
.order-title h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
.back-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; font-size: 14px; }
.back-btn:hover { background: #e5e7eb; }
.back-btn svg { width: 18px; height: 18px; }

.header-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
.btn svg { width: 18px; height: 18px; }
.btn-primary { background: #3b82f6; color: #fff; }
.btn-primary:hover { background: #2563eb; }
.btn-success { background: #10b981; color: #fff; }
.btn-success:hover { background: #059669; }
.btn-warning { background: #f59e0b; color: #fff; }
.btn-warning:hover { background: #d97706; }
.btn-danger { background: #ef4444; color: #fff; }
.btn-danger:hover { background: #dc2626; }
.btn-outline { background: #fff; border: 1px solid #d1d5db; color: #374151; }
.btn-outline:hover { background: #f3f4f6; }

/* Badges */
.badge { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
.badge-lg { padding: 8px 18px; font-size: 14px; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-confirmed { background: #dbeafe; color: #1e40af; }
.badge-processing { background: #e0e7ff; color: #3730a3; }
.badge-shipped { background: #ede9fe; color: #5b21b6; }
.badge-delivered { background: #d1fae5; color: #065f46; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }
.badge-paid { background: #d1fae5; color: #065f46; }
.badge-unpaid { background: #fef3c7; color: #92400e; }

/* Alert boxes */
.alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert svg { width: 20px; height: 20px; flex-shrink: 0; }
.alert-success { background: #d1fae5; border: 1px solid #10b981; color: #065f46; }
.alert-error { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; }
.alert-warning { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; }
.alert-info { background: #dbeafe; border: 1px solid #3b82f6; color: #1e40af; }

/* Grid Layout */
.order-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
@media (max-width: 1024px) { .order-grid { grid-template-columns: 1fr; } }

/* Cards */
.card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px; }
.card-header { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; display: flex; justify-content: space-between; align-items: center; }
.card-title { font-size: 16px; font-weight: 600; color: #1f2937; display: flex; align-items: center; gap: 8px; margin: 0; }
.card-title svg { width: 20px; height: 20px; color: #3b82f6; }
.card-body { padding: 20px; }

/* Status Timeline */
.timeline { position: relative; padding-left: 28px; }
.timeline::before { content: ''; position: absolute; left: 8px; top: 8px; bottom: 8px; width: 2px; background: #e5e7eb; }
.timeline-item { position: relative; padding-bottom: 20px; }
.timeline-item:last-child { padding-bottom: 0; }
.timeline-dot { position: absolute; left: -24px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #d1d5db; border: 2px solid #fff; box-shadow: 0 0 0 2px #e5e7eb; }
.timeline-item.active .timeline-dot { background: #10b981; box-shadow: 0 0 0 2px #d1fae5; }
.timeline-item.current .timeline-dot { background: #3b82f6; box-shadow: 0 0 0 2px #dbeafe; }
.timeline-content { background: #f9fafb; padding: 12px 16px; border-radius: 8px; }
.timeline-status { font-weight: 600; color: #1f2937; margin-bottom: 4px; }
.timeline-meta { font-size: 12px; color: #6b7280; }
.timeline-comment { font-size: 13px; color: #4b5563; margin-top: 6px; }

/* Order Items */
.order-items { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
.order-item { display: flex; gap: 14px; padding: 14px; border-bottom: 1px solid #f3f4f6; }
.order-item:last-child { border-bottom: none; }
.item-image { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; background: #f3f4f6; }
.item-details { flex: 1; }
.item-name { font-weight: 500; color: #1f2937; margin-bottom: 4px; }
.item-variant { font-size: 13px; color: #6b7280; }
.item-sku { font-size: 12px; color: #9ca3af; }
.item-price { text-align: right; }
.item-qty { font-size: 13px; color: #6b7280; }
.item-total { font-weight: 600; color: #1f2937; }

/* Summary */
.summary-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
.summary-row:last-child { border-bottom: none; }
.summary-row.total { border-top: 2px solid #e5e7eb; padding-top: 14px; margin-top: 6px; font-weight: 700; font-size: 16px; }
.summary-label { color: #6b7280; }
.summary-value { color: #1f2937; font-weight: 500; }

/* Info Grid */
.info-grid { display: grid; gap: 12px; }
.info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
.info-row:last-child { border-bottom: none; }
.info-label { color: #6b7280; font-size: 14px; }
.info-value { color: #1f2937; font-weight: 500; font-size: 14px; text-align: right; }

/* Address Card */
.address-text { line-height: 1.7; color: #374151; }
.address-name { font-weight: 600; color: #1f2937; }
.address-phone { display: flex; align-items: center; gap: 6px; margin-top: 10px; color: #3b82f6; }
.address-phone svg { width: 16px; height: 16px; }

/* Action Cards */
.action-card { background: #f9fafb; border-radius: 10px; padding: 16px; margin-bottom: 16px; }
.action-card:last-child { margin-bottom: 0; }
.action-card-title { font-weight: 600; color: #1f2937; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.action-card-title svg { width: 18px; height: 18px; color: #3b82f6; }

/* Forms in cards */
.form-group { margin-bottom: 14px; }
.form-group:last-child { margin-bottom: 0; }
.form-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-input, .form-select, .form-textarea { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.form-textarea { min-height: 80px; resize: vertical; }
.form-checkbox { display: flex; align-items: center; gap: 8px; }
.form-checkbox input { width: 18px; height: 18px; accent-color: #3b82f6; }

/* Quick Actions */
.quick-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
.quick-btn { flex: 1; min-width: 120px; display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; text-decoration: none; }
.quick-btn:hover { border-color: #3b82f6; background: #f0f7ff; }
.quick-btn svg { width: 24px; height: 24px; color: #6b7280; }
.quick-btn:hover svg { color: #3b82f6; }
.quick-btn span { font-size: 13px; font-weight: 500; color: #374151; }
.quick-btn.success { border-color: #10b981; background: #ecfdf5; }
.quick-btn.success svg { color: #10b981; }
.quick-btn.warning { border-color: #f59e0b; background: #fffbeb; }
.quick-btn.warning svg { color: #f59e0b; }
</style>

<div class="order-container">
    {{-- Header --}}
    <div class="order-header">
        <div class="order-title">
            <a href="{{ route('admin.ecommerce.orders') }}" class="back-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back
            </a>
            <h1>{{ $order->order_no }}</h1>
            <span class="badge badge-lg badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            <span class="badge badge-lg badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.ecommerce.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        {{ session('error') }}
    </div>
    @endif
    
    {{-- COD Payment Alert --}}
    @if(in_array($order->payment_method, ['cash', 'cod']) && $order->payment_status === 'pending' && $order->status === 'delivered')
    <div class="alert alert-warning">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <strong>COD Payment Pending!</strong> Order delivered but payment not yet confirmed. 
            <a href="#payment-section" style="text-decoration: underline;">Confirm payment</a>
        </div>
    </div>
    @endif
    
    <div class="order-grid">
        {{-- Left Column --}}
        <div class="order-main">
            {{-- Quick Actions --}}
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <div class="quick-actions">
                @if($order->status === 'pending')
                <form action="{{ route('admin.ecommerce.orders.status', $order->id) }}" method="POST" style="flex:1; min-width: 120px;">
                    @csrf
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="quick-btn success" style="width:100%;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Confirm Order</span>
                    </button>
                </form>
                @endif
                
                @if($order->status === 'confirmed')
                <form action="{{ route('admin.ecommerce.orders.status', $order->id) }}" method="POST" style="flex:1; min-width: 120px;">
                    @csrf
                    <input type="hidden" name="status" value="processing">
                    <button type="submit" class="quick-btn" style="width:100%;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Start Processing</span>
                    </button>
                </form>
                @endif
                
                @if(in_array($order->status, ['confirmed', 'processing']) && !$order->tracking_number)
                <button type="button" class="quick-btn" onclick="document.getElementById('shipping-modal').style.display='flex'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>Add Shipping</span>
                </button>
                @endif
                
                @if($order->status === 'shipped')
                <button type="button" class="quick-btn success" onclick="document.getElementById('delivery-modal').style.display='flex'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Confirm Delivery</span>
                </button>
                @endif
                
                @if(!in_array($order->status, ['cancelled', 'delivered']))
                <form action="{{ route('admin.ecommerce.orders.status', $order->id) }}" method="POST" style="flex:1; min-width: 120px;" onsubmit="return confirm('Are you sure you want to cancel this order? Stock will be restored.')">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <input type="hidden" name="comment" value="Cancelled by admin">
                    <button type="submit" class="quick-btn warning" style="width:100%;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>Cancel Order</span>
                    </button>
                </form>
                @endif
            </div>
            @endif
            
            {{-- Order Items --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Order Items ({{ $order->items->count() }})
                    </h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="order-items">
                        @foreach($order->items as $item)
                        <div class="order-item">
                            @php
                                $image = $item->product->primaryImage ?? $item->product->images->first();
                            @endphp
                            @if($image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $item->product_name }}" class="item-image">
                            @else
                            <div class="item-image" style="display:flex;align-items:center;justify-content:center;">
                                <svg width="24" height="24" fill="none" stroke="#9ca3af" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            @endif
                            <div class="item-details">
                                <div class="item-name">{{ $item->product_name }}</div>
                                @if($item->variation_name)
                                <div class="item-variant">{{ $item->variation_name }}</div>
                                @endif
                                <div class="item-sku">SKU: {{ $item->sku ?? 'N/A' }}</div>
                            </div>
                            <div class="item-price">
                                <div class="item-qty">{{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</div>
                                <div class="item-total">₹{{ number_format($item->total_price, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Order Summary
                    </h2>
                </div>
                <div class="card-body">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="summary-row">
                        <span class="summary-label">Tax</span>
                        <span class="summary-value">₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value">{{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'Free' }}</span>
                    </div>
                    @if($order->cod_fee > 0)
                    <div class="summary-row">
                        <span class="summary-label">COD Fee</span>
                        <span class="summary-value">₹{{ number_format($order->cod_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount_amount > 0)
                    <div class="summary-row">
                        <span class="summary-label">Discount</span>
                        <span class="summary-value" style="color: #10b981;">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="summary-row total">
                        <span class="summary-label">Total</span>
                        <span class="summary-value">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Status History --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Order Timeline
                    </h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($order->statusHistory->sortByDesc('created_at') as $history)
                        <div class="timeline-item {{ $loop->first ? 'current' : 'active' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-status">{{ ucfirst($history->status) }}</div>
                                <div class="timeline-meta">{{ $history->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</div>
                                @if($history->comment)
                                <div class="timeline-comment">{{ $history->comment }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p style="color: #6b7280;">No status history available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Right Column --}}
        <div class="order-sidebar">
            {{-- Customer Info --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Customer
                    </h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value">{{ $order->customer_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">
                                <a href="tel:{{ $order->customer_phone }}" style="color: #3b82f6;">{{ $order->customer_phone }}</a>
                            </span>
                        </div>
                        @if($order->customer_email)
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value" style="font-size: 12px;">{{ $order->customer_email }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Shipping Address --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Shipping Address
                    </h2>
                </div>
                <div class="card-body">
                    <div class="address-text">
                        <div class="address-name">{{ $order->customer_name }}</div>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                        {{ $order->shipping_pincode }}
                        <div class="address-phone">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $order->customer_phone }}
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Payment Info --}}
            <div class="card" id="payment-section">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Payment
                    </h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Method</span>
                            <span class="info-value">{{ $order->payment_method_label }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="badge badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        @if($order->transaction_id)
                        <div class="info-row">
                            <span class="info-label">Transaction ID</span>
                            <span class="info-value" style="font-size: 12px;">{{ $order->transaction_id }}</span>
                        </div>
                        @endif
                        @if($order->paid_at)
                        <div class="info-row">
                            <span class="info-label">Paid At</span>
                            <span class="info-value">{{ $order->paid_at->format('d M Y, h:i A') }}</span>
                        </div>
                        @endif
                        @if($invoice)
                        <div class="info-row">
                            <span class="info-label">Invoice</span>
                            <span class="info-value">{{ $invoice->invoice_number }}</span>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Update Payment Form --}}
                    @if($order->payment_status !== 'paid')
                    <div class="action-card" style="margin-top: 16px;">
                        <div class="action-card-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Mark Payment
                        </div>
                        <form action="{{ route('admin.ecommerce.orders.payment', $order->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Transaction ID (optional)</label>
                                <input type="text" name="transaction_id" class="form-input" placeholder="Enter transaction ID">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Note (optional)</label>
                                <input type="text" name="payment_note" class="form-input" placeholder="e.g., Cash collected by delivery boy">
                            </div>
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Update Payment
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Shipping Info --}}
            @if($order->tracking_number)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Shipping
                    </h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Carrier</span>
                            <span class="info-value">{{ $order->carrier ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tracking #</span>
                            <span class="info-value">{{ $order->tracking_number }}</span>
                        </div>
                        @if($order->shipped_at)
                        <div class="info-row">
                            <span class="info-label">Shipped At</span>
                            <span class="info-value">{{ $order->shipped_at->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Order Notes --}}
            @if($order->customer_notes)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        Customer Notes
                    </h2>
                </div>
                <div class="card-body">
                    <p style="color: #4b5563; line-height: 1.6;">{{ $order->customer_notes }}</p>
                </div>
            </div>
            @endif
            
            {{-- Update Status Form --}}
            @if(!in_array($order->status, ['delivered', 'cancelled']))
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Update Status
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ecommerce.orders.status', $order->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">New Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Comment (optional)</label>
                            <textarea name="comment" class="form-textarea" placeholder="Add a note about this status change"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Update Status</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Shipping Modal --}}
<div id="shipping-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:24px; max-width:400px; width:90%; max-height:90vh; overflow-y:auto;">
        <h3 style="font-size:18px; font-weight:600; margin-bottom:20px;">Add Shipping Information</h3>
        <form action="{{ route('admin.ecommerce.orders.shipping', $order->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Carrier / Courier</label>
                <select name="carrier" class="form-select">
                    <option value="">Select Carrier</option>
                    <option value="Delhivery">Delhivery</option>
                    <option value="BlueDart">BlueDart</option>
                    <option value="DTDC">DTDC</option>
                    <option value="Ecom Express">Ecom Express</option>
                    <option value="India Post">India Post</option>
                    <option value="Shadowfax">Shadowfax</option>
                    <option value="Shiprocket">Shiprocket</option>
                    <option value="Self Delivery">Self Delivery</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tracking Number *</label>
                <input type="text" name="tracking_number" class="form-input" required placeholder="Enter tracking number">
            </div>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="button" onclick="document.getElementById('shipping-modal').style.display='none'" class="btn btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Save & Ship</button>
            </div>
        </form>
    </div>
</div>

{{-- Delivery Confirmation Modal --}}
<div id="delivery-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:24px; max-width:400px; width:90%; max-height:90vh; overflow-y:auto;">
        <h3 style="font-size:18px; font-weight:600; margin-bottom:20px;">Confirm Delivery</h3>
        <form action="{{ route('admin.ecommerce.orders.confirm-delivery', $order->id) }}" method="POST">
            @csrf
            @if(in_array($order->payment_method, ['cash', 'cod']) && $order->payment_status === 'pending')
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="confirm_payment" value="1" checked>
                    <span>Confirm COD payment collected (₹{{ number_format($order->total, 2) }})</span>
                </label>
            </div>
            @endif
            <div class="form-group">
                <label class="form-label">Delivery Note (optional)</label>
                <textarea name="delivery_note" class="form-textarea" placeholder="e.g., Delivered to security guard"></textarea>
            </div>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="button" onclick="document.getElementById('delivery-modal').style.display='none'" class="btn btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn btn-success" style="flex:1;">Confirm Delivery</button>
            </div>
        </form>
    </div>
</div>
