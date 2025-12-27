@extends('ecommerce::public.shop-layout')

@section('title', 'Checkout - ' . ($settings->site_name ?? 'Store'))

@section('content')
<div class="checkout-page">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <form action="{{ route('ecommerce.checkout.place') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-layout">
                {{-- Left: Forms --}}
                <div class="checkout-forms">
                    {{-- Contact & Shipping --}}
                    <div class="form-section">
                        <h2>Shipping Information</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name <span class="required">*</span></label>
                                <input type="text" name="customer_name" class="form-input" required
                                    value="{{ old('customer_name', $shippingAddress['name'] ?? $user->name ?? '') }}">
                                @error('customer_name')<span class="error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Phone Number <span class="required">*</span></label>
                                <input type="tel" name="customer_phone" class="form-input" required
                                    value="{{ old('customer_phone', $shippingAddress['phone'] ?? '') }}">
                                @error('customer_phone')<span class="error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Email (for order updates)</label>
                            <input type="email" name="customer_email" class="form-input"
                                value="{{ old('customer_email', $customer->email ?? $user->email ?? '') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Address <span class="required">*</span></label>
                            <textarea name="shipping_address" class="form-input" rows="2" required
                                placeholder="House no, Building, Street, Locality">{{ old('shipping_address', $shippingAddress['address'] ?? '') }}</textarea>
                            @error('shipping_address')<span class="error">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-row form-row-3">
                            <div class="form-group">
                                <label>City <span class="required">*</span></label>
                                <input type="text" name="shipping_city" class="form-input" required
                                    value="{{ old('shipping_city', $shippingAddress['city'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>State <span class="required">*</span></label>
                                <input type="text" name="shipping_state" class="form-input" required
                                    value="{{ old('shipping_state', $shippingAddress['state'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>PIN Code <span class="required">*</span></label>
                                <input type="text" name="shipping_pincode" class="form-input" required
                                    value="{{ old('shipping_pincode', $shippingAddress['pincode'] ?? '') }}" maxlength="6">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Payment Method --}}
                    <div class="form-section">
                        <h2>Payment Method</h2>
                        
                        <div class="payment-options">
                            @php 
                                $firstActive = true; 
                                $codDisabledByAmount = $wsSettings->cod_max_amount > 0 && $grandTotal > $wsSettings->cod_max_amount;
                            @endphp
                            
                            {{-- Cash on Delivery --}}
                            @if($wsSettings->cod_enabled ?? true)
                                @if($codDisabledByAmount)
                                <div class="payment-option disabled">
                                    <div class="option-content">
                                        <div class="option-icon">💵</div>
                                        <div class="option-info">
                                            <span class="option-title">Cash on Delivery</span>
                                            <span class="option-desc">Not available for orders above ₹{{ number_format($wsSettings->cod_max_amount, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <label class="payment-option {{ $firstActive ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="cod" {{ $firstActive ? 'checked' : '' }}>
                                    <div class="option-content">
                                        <div class="option-icon">💵</div>
                                        <div class="option-info">
                                            <span class="option-title">Cash on Delivery</span>
                                            <span class="option-desc">Pay when you receive your order</span>
                                        </div>
                                        @if(($wsSettings->cod_fee ?? 0) > 0)
                                        <span class="option-fee">+₹{{ number_format($wsSettings->cod_fee, 0) }}</span>
                                        @endif
                                    </div>
                                </label>
                                @php $firstActive = false; @endphp
                                @endif
                            @endif

                            {{-- Online Payment --}}
                            @if($wsSettings->online_payment_enabled ?? false)
                            <label class="payment-option {{ $firstActive ? 'active' : '' }}">
                                <input type="radio" name="payment_method" value="online" {{ $firstActive ? 'checked' : '' }}>
                                <div class="option-content">
                                    <div class="option-icon">💳</div>
                                    <div class="option-info">
                                        <span class="option-title">{{ $wsSettings->online_payment_label ?? 'Pay Online (UPI/Card/NetBanking)' }}</span>
                                        <span class="option-desc">Secure payment via payment gateway</span>
                                    </div>
                                </div>
                            </label>
                            @php $firstActive = false; @endphp
                            @endif

                            {{-- No payment methods available --}}
                            @if(!($wsSettings->cod_enabled ?? true) && !($wsSettings->online_payment_enabled ?? false))
                            <div class="payment-option disabled">
                                <div class="option-content">
                                    <div class="option-icon">⚠️</div>
                                    <div class="option-info">
                                        <span class="option-title">No Payment Methods Available</span>
                                        <span class="option-desc">Please contact store for assistance</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($minOrderAmount > 0 && $subtotal < $minOrderAmount)
                        <div class="min-order-warning">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Minimum order amount is ₹{{ number_format($minOrderAmount, 0) }}. Add ₹{{ number_format($minOrderAmount - $subtotal, 0) }} more.
                        </div>
                        @endif
                    </div>
                    
                    {{-- Order Notes --}}
                    <div class="form-section">
                        <h2>Order Notes (Optional)</h2>
                        <textarea name="customer_notes" class="form-input" rows="2" 
                            placeholder="Any special instructions for delivery...">{{ old('customer_notes') }}</textarea>
                    </div>
                </div>
                
                {{-- Right: Order Summary --}}
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    
                    <div class="summary-items">
                        @foreach($cartItems as $item)
                        <div class="summary-item">
                            <div class="item-img">
                                @if($item['product']->getPrimaryImageUrl())
                                <img src="{{ $item['product']->getPrimaryImageUrl() }}" alt="">
                                @endif
                            </div>
                            <div class="item-info">
                                <span class="item-name">{{ $item['product']->name }}</span>
                                @if($item['variation_name'])
                                <span class="item-variant">{{ $item['variation_name'] }}</span>
                                @endif
                                <span class="item-qty">Qty: {{ (int)$item['qty'] }}</span>
                            </div>
                            <div class="item-price">₹{{ number_format($item['total'], 0) }}</div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-totals">
                        <div class="sum-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="sum-row">
                            <span>Shipping</span>
                            @if($shippingFee > 0)
                            <span>₹{{ number_format($shippingFee, 0) }}</span>
                            @else
                            <span class="free">FREE</span>
                            @endif
                        </div>
                        @if($codFee > 0 && $codEnabled)
                        <div class="sum-row cod-row" id="codFeeRow">
                            <span>COD Fee</span>
                            <span>₹{{ number_format($codFee, 0) }}</span>
                        </div>
                        @endif
                        <div class="sum-row total">
                            <span>Total</span>
                            <span id="grandTotal">₹{{ number_format($grandTotal + ($codEnabled ? $codFee : 0), 0) }}</span>
                        </div>
                    </div>
                    
                    @if($wsSettings->delivery_days)
                    <div class="delivery-info">🚚 Estimated delivery: {{ $wsSettings->delivery_days }}</div>
                    @endif
                    
                    <button type="submit" class="place-order-btn">
                        Place Order
                    </button>
                    
                    <a href="{{ route('ecommerce.cart') }}" class="back-link">← Back to Cart</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.checkout-page { padding: 32px 0 60px; background: #f8fafc; min-height: 80vh; }
.page-title { font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 24px; }
.alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
.checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 32px; align-items: start; }

.form-section { background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
.form-section h2 { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
.form-group label .required { color: #ef4444; }
.form-input { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all .2s; }
.form-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
.form-group .error { display: block; font-size: 12px; color: #ef4444; margin-top: 4px; }

.payment-options { display: flex; flex-direction: column; gap: 12px; }
.payment-option { border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all .2s; }
.payment-option:hover { border-color: #cbd5e1; }
.payment-option.active { border-color: #3b82f6; background: #eff6ff; }
.payment-option.disabled { opacity: .5; cursor: not-allowed; background: #f8fafc; }
.payment-option input { display: none; }
.option-content { display: flex; align-items: center; gap: 16px; padding: 16px; }
.option-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; color: #fff; }
.option-icon svg { width: 22px; height: 22px; }
.option-info { flex: 1; }
.option-title { display: block; font-size: 15px; font-weight: 600; color: #1e293b; }
.option-desc { display: block; font-size: 12px; color: #64748b; margin-top: 2px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.option-fee { font-size: 13px; font-weight: 600; color: #f59e0b; background: #fef3c7; padding: 4px 10px; border-radius: 6px; }

.min-order-warning { display: flex; align-items: center; gap: 10px; margin-top: 16px; padding: 12px 16px; background: #fef3c7; border-radius: 8px; color: #92400e; font-size: 13px; font-weight: 500; }
.min-order-warning svg { flex-shrink: 0; }

.order-summary { background: #fff; border-radius: 12px; padding: 24px; position: sticky; top: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
.order-summary h2 { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px; }

.summary-items { border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 16px; max-height: 300px; overflow-y: auto; }
.summary-item { display: flex; gap: 12px; padding: 10px 0; }
.item-img { width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: #f8fafc; flex-shrink: 0; }
.item-img img { width: 100%; height: 100%; object-fit: contain; }
.item-info { flex: 1; min-width: 0; }
.item-name { display: block; font-size: 13px; font-weight: 500; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.item-variant { display: block; font-size: 11px; color: #64748b; }
.item-qty { display: block; font-size: 12px; color: #94a3b8; }
.item-price { font-size: 14px; font-weight: 600; color: #1e293b; }

.summary-totals { padding: 16px 0; }
.sum-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #64748b; }
.sum-row .free { color: #10b981; font-weight: 500; }
.sum-row.total { font-size: 18px; font-weight: 700; color: #1e293b; border-top: 1px solid #e2e8f0; margin-top: 8px; padding-top: 16px; }

.delivery-info { font-size: 13px; color: #64748b; text-align: center; margin: 16px 0; }

.place-order-btn { width: 100%; padding: 16px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 8px; transition: all .2s; box-shadow: 0 4px 12px rgba(16,185,129,.3); }
.place-order-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(16,185,129,.4); }

.back-link { display: block; text-align: center; padding: 12px; color: #64748b; font-size: 14px; margin-top: 8px; text-decoration: none; }
.back-link:hover { color: #3b82f6; }

@media (max-width: 900px) {
    .checkout-layout { grid-template-columns: 1fr; }
    .order-summary { position: static; order: -1; }
}
@media (max-width: 640px) {
    .form-row, .form-row-3 { grid-template-columns: 1fr; }
}
</style>

<script>
document.querySelectorAll('.payment-option:not(.disabled)').forEach(opt => {
    opt.addEventListener('click', () => {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
        opt.classList.add('active');
        const radio = opt.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    });
});
</script>
@endsection
