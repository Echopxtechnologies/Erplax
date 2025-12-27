<div class="wishlist-page">
    <div class="container">
        <div class="page-header">
            <h1>My Wishlist</h1>
            @if($isLoggedIn && count($wishlistItems) > 0)
                <span class="item-count">{{ count($wishlistItems) }} items</span>
            @endif
        </div>
        
        @if(!$isLoggedIn)
            <div class="login-box">
                <div class="login-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="50" height="50"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h2>Please Login</h2>
                <p>You need to login to view your wishlist</p>
                <a href="{{ route('ecommerce.login') }}" class="btn-primary">Login to Continue</a>
                <p class="alt-link">Don't have an account? <a href="{{ route('ecommerce.register') }}">Register</a></p>
            </div>
        @elseif(count($wishlistItems) > 0)
            <div class="wish-grid">
                @foreach($wishlistItems as $item)
                    <div class="wish-card" wire:key="wish-{{ $item['id'] }}">
                        <button wire:click="removeFromWishlist({{ $item['id'] }})" class="remove-btn" title="Remove">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <a href="{{ route('ecommerce.product', $item['id']) }}" class="card-img">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            @else
                                <div class="no-img"><svg width="40" height="40" fill="none" stroke="#e2e8f0" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>
                            @endif
                            @if($item['discount'])
                                <span class="badge-sale">-{{ $item['discount'] }}%</span>
                            @endif
                        </a>
                        <div class="card-body">
                            <a href="{{ route('ecommerce.product', $item['id']) }}" class="card-title">{{ $item['name'] }}</a>
                            <div class="card-price">
                                <span class="price">₹{{ number_format($item['price'], 0) }}</span>
                                @if($item['mrp'] && $item['mrp'] > $item['price'])
                                    <span class="mrp">₹{{ number_format($item['mrp'], 0) }}</span>
                                @endif
                            </div>
                            @if($item['has_variants'] ?? false)
                                <a href="{{ route('ecommerce.product', $item['id']) }}" class="btn-cart outline">Select Options</a>
                            @else
                                <button wire:click="moveToCart({{ $item['id'] }})" class="btn-cart">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Move to Cart
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-box">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" width="70" height="70"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h2>Your wishlist is empty</h2>
                <p>Save items you love by clicking the heart icon on products</p>
                <a href="{{ route('ecommerce.shop') }}" class="btn-primary">Explore Products</a>
            </div>
        @endif
    </div>
</div>

<style>
.wishlist-page { padding: 40px 0; }
.page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; }
.page-header h1 { font-size: 28px; font-weight: 700; color: #0f172a; }
.item-count { padding: 6px 14px; background: #ecfeff; color: #0891b2; font-size: 14px; font-weight: 600; border-radius: 20px; }

.wish-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
.wish-card { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; overflow: hidden; position: relative; transition: all .3s ease; }
.wish-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); border-color: transparent; }

.remove-btn { position: absolute; top: 12px; right: 12px; z-index: 5; width: 32px; height: 32px; background: #fff; border: 1px solid #e2e8f0; border-radius: 50%; color: #94a3b8; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
.remove-btn:hover { background: #fef2f2; border-color: #ef4444; color: #ef4444; }

.card-img { display: block; aspect-ratio: 1; background: #f8fafc; position: relative; }
.card-img img { width: 100%; height: 100%; object-fit: contain; padding: 20px; transition: transform .3s; }
.wish-card:hover .card-img img { transform: scale(1.05); }
.no-img { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.badge-sale { position: absolute; top: 12px; left: 12px; background: #ef4444; color: #fff; padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }

.card-body { padding: 16px; }
.card-title { display: block; font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 10px; min-height: 42px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.card-title:hover { color: #0891b2; }
.card-price { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.price { font-size: 18px; font-weight: 700; color: #0f172a; }
.mrp { font-size: 14px; color: #94a3b8; text-decoration: line-through; }

.btn-cart { display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 12px; background: #0891b2; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; }
.btn-cart:hover { background: #0e7490; }
.btn-cart.outline { background: transparent; color: #0891b2; border: 2px solid #0891b2; }
.btn-cart.outline:hover { background: #0891b2; color: #fff; }

.empty-box, .login-box { text-align: center; padding: 80px 40px; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; }
.empty-icon, .login-icon { color: #e2e8f0; margin-bottom: 20px; }
.empty-box h2, .login-box h2 { font-size: 22px; color: #0f172a; margin-bottom: 10px; }
.empty-box p, .login-box p { color: #64748b; margin-bottom: 24px; }
.btn-primary { display: inline-block; padding: 14px 32px; background: #0891b2; color: #fff; border-radius: 10px; font-weight: 600; text-decoration: none; transition: all .2s; }
.btn-primary:hover { background: #0e7490; }
.alt-link { margin-top: 20px; font-size: 14px; color: #64748b; }
.alt-link a { color: #0891b2; text-decoration: none; font-weight: 600; }

@media(max-width:1200px) { .wish-grid { grid-template-columns: repeat(4, 1fr); } }
@media(max-width:900px) { .wish-grid { grid-template-columns: repeat(3, 1fr); } }
@media(max-width:600px) { .wish-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } .page-header h1 { font-size: 22px; } }
</style>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('cart-count-updated', (data) => { const el = document.getElementById('headerCartCount'); if (el) el.textContent = data.count; });
    Livewire.on('wishlist-count-updated', (data) => { const el = document.getElementById('headerWishCount'); if (el) el.textContent = data.count; });
});
</script>
