<div class="cart-page">
    <div class="container">
        <h1 class="page-title">Shopping Cart</h1>
        
        @if(!$isLoggedIn)
            <div class="login-required">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="60" height="60"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h2>Please Login</h2>
                <p>You need to login to view your cart</p>
                <a href="{{ route('ecommerce.login') }}" class="login-btn">Login</a>
                <p class="register-link">Don't have an account? <a href="{{ route('ecommerce.register') }}">Register</a></p>
            </div>
        @elseif(count($cartItems) > 0)
            <div class="cart-layout">
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item" wire:key="cart-{{ $item['key'] }}">
                            <div class="item-img">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                @else
                                    <div class="no-img">No Image</div>
                                @endif
                            </div>
                            <div class="item-info">
                                <a href="{{ route('ecommerce.product', $item['product_id']) }}" class="item-name">{{ $item['name'] }}</a>
                                @if($item['variation_name'])
                                    <span class="item-variant">{{ $item['variation_name'] }}</span>
                                @endif
                                <div class="item-price">₹{{ $item['allows_decimal'] ? number_format($item['price'], 2) : number_format($item['price'], 0) }}</div>
                            </div>
                            <div class="item-qty">
                                <button wire:click="decrementQty('{{ $item['key'] }}')" class="qty-btn">−</button>
                                <span>{{ $item['allows_decimal'] ? rtrim(rtrim(number_format($item['qty'], 2), '0'), '.') : (int)$item['qty'] }}</span>
                                <button wire:click="incrementQty('{{ $item['key'] }}')" class="qty-btn" {{ $item['qty'] >= $item['stock'] ? 'disabled' : '' }}>+</button>
                            </div>
                            <div class="item-total">₹{{ $item['allows_decimal'] ? number_format($item['subtotal'], 2) : number_format($item['subtotal'], 0) }}</div>
                            <button wire:click="removeItem('{{ $item['key'] }}')" class="remove-btn">×</button>
                        </div>
                    @endforeach
                </div>
                
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="sum-row"><span>Subtotal</span><span>₹{{ number_format($cartTotal, 0) }}</span></div>
                    <div class="sum-row">
                        <span>Shipping</span>
                        @if($shippingFee > 0)
                            <span>₹{{ number_format($shippingFee, 0) }}</span>
                        @else
                            <span class="free">FREE</span>
                        @endif
                    </div>
                    @if($amountForFreeShipping > 0)
                        <div class="free-ship-hint">
                            Add ₹{{ number_format($amountForFreeShipping, 0) }} more for FREE shipping!
                        </div>
                    @endif
                    <div class="sum-row total"><span>Total</span><span>₹{{ number_format($grandTotal, 0) }}</span></div>
                    @if($deliveryDays)
                        <div class="delivery-info">🚚 Estimated delivery: {{ $deliveryDays }}</div>
                    @endif
                    <a href="{{ route('ecommerce.checkout') }}" class="checkout-btn">Proceed to Checkout</a>
                    <a href="{{ route('ecommerce.shop') }}" class="continue-link">← Continue Shopping</a>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="80" height="80"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <a href="{{ route('ecommerce.shop') }}" class="shop-btn">Start Shopping</a>
            </div>
        @endif
    </div>
</div>

<style>
.cart-page{padding:32px 0}
.page-title{font-size:24px;font-weight:700;color:#1e293b;margin-bottom:24px}
.cart-layout{display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start}
.cart-items{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden}
.cart-item{display:grid;grid-template-columns:80px 1fr 120px 100px 40px;gap:16px;align-items:center;padding:16px;border-bottom:1px solid #f1f5f9}
.cart-item:last-child{border-bottom:none}
.item-img{width:80px;height:80px;border-radius:8px;overflow:hidden;background:#f8fafc}
.item-img img{width:100%;height:100%;object-fit:contain}
.no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:11px}
.item-info{min-width:0}
.item-name{font-size:14px;font-weight:500;color:#1e293b;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-decoration:none}
.item-name:hover{color:#3b82f6}
.item-variant{display:block;font-size:12px;color:#64748b;margin-top:2px}
.item-price{font-size:13px;color:#64748b;margin-top:4px}
.item-qty{display:flex;align-items:center;background:#f1f5f9;border-radius:8px;overflow:hidden}
.qty-btn{width:36px;height:36px;border:none;background:transparent;font-size:16px;color:#64748b;cursor:pointer}
.qty-btn:hover:not(:disabled){background:#e2e8f0;color:#1e293b}
.qty-btn:disabled{opacity:.4;cursor:not-allowed}
.item-qty span{width:40px;text-align:center;font-weight:600;color:#1e293b}
.item-total{font-size:16px;font-weight:700;color:#1e293b;text-align:right}
.remove-btn{width:36px;height:36px;border:none;background:#fef2f2;color:#ef4444;border-radius:8px;cursor:pointer;font-size:18px}
.remove-btn:hover{background:#fee2e2}
.cart-summary{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px;position:sticky;top:100px}
.cart-summary h3{font-size:16px;font-weight:700;color:#1e293b;margin-bottom:16px}
.sum-row{display:flex;justify-content:space-between;padding:10px 0;font-size:14px;color:#64748b}
.sum-row .free{color:#10b981;font-weight:500}
.sum-row.total{font-size:18px;font-weight:700;color:#1e293b;border-top:1px solid #e2e8f0;margin-top:8px;padding-top:16px}
.checkout-btn{display:block;width:100%;padding:14px;background:#3b82f6;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;margin-top:16px;text-align:center;text-decoration:none}
.checkout-btn:hover{background:#2563eb}
.free-ship-hint{background:#fef3c7;color:#92400e;padding:10px 12px;border-radius:8px;font-size:13px;margin:8px 0;text-align:center}
.delivery-info{font-size:13px;color:#64748b;text-align:center;margin-top:12px}
.continue-link{display:block;text-align:center;padding:12px;color:#64748b;font-size:14px;margin-top:8px;text-decoration:none}
.continue-link:hover{color:#3b82f6}
.empty-cart{text-align:center;padding:60px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;color:#94a3b8}
.empty-cart h2{font-size:20px;color:#1e293b;margin:20px 0 8px}
.empty-cart p{margin-bottom:20px}
.shop-btn{display:inline-block;padding:12px 28px;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none}
.login-required{text-align:center;padding:60px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;color:#94a3b8}
.login-required h2{font-size:20px;color:#1e293b;margin:20px 0 8px}
.login-required p{margin-bottom:20px}
.login-btn{display:inline-block;padding:12px 32px;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none}
.login-btn:hover{background:#2563eb}
.register-link{margin-top:16px;font-size:14px}
.register-link a{color:#3b82f6;text-decoration:none;font-weight:500}
@media(max-width:900px){.cart-layout{grid-template-columns:1fr}.cart-summary{position:static}}
@media(max-width:600px){.cart-item{grid-template-columns:60px 1fr;gap:12px}.item-qty,.item-total,.remove-btn{grid-column:2}}
</style>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cart-count-updated', (data) => {
            const el = document.getElementById('headerCartCount');
            if (el) el.textContent = data.count;
        });
    });
</script>
