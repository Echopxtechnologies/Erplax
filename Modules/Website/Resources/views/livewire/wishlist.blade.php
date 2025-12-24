<div class="wishlist-page">
    <div class="container">
        <h1 class="page-title">My Wishlist</h1>
        
        @if(!$isLoggedIn)
            <div class="login-required">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="60" height="60"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h2>Please Login</h2>
                <p>You need to login to view your wishlist</p>
                <a href="{{ route('website.login') }}" class="login-btn">Login</a>
                <p class="register-link">Don't have an account? <a href="{{ route('website.register') }}">Register</a></p>
            </div>
        @elseif(count($wishlistItems) > 0)
            <div class="wish-grid">
                @foreach($wishlistItems as $item)
                    <div class="wish-card" wire:key="wish-{{ $item['id'] }}">
                        <button wire:click="removeFromWishlist({{ $item['id'] }})" class="remove-btn">×</button>
                        <a href="{{ route('website.product', $item['id']) }}" class="card-img">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            @else
                                <div class="no-img">No Image</div>
                            @endif
                            @if($item['discount'])
                                <span class="disc">-{{ $item['discount'] }}%</span>
                            @endif
                        </a>
                        <div class="card-info">
                            <a href="{{ route('website.product', $item['id']) }}" class="card-name">{{ $item['name'] }}</a>
                            <div class="card-price">
                                <span class="price">₹{{ number_format($item['price'], 0) }}</span>
                                @if($item['mrp'] && $item['mrp'] > $item['price'])
                                    <span class="mrp">₹{{ number_format($item['mrp'], 0) }}</span>
                                @endif
                            </div>
                            @if($item['has_variants'] ?? false)
                                <a href="{{ route('website.product', $item['id']) }}" class="cart-btn">Select Options</a>
                            @else
                                <button wire:click="moveToCart({{ $item['id'] }})" class="cart-btn">Move to Cart</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-wish">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="80" height="80"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <h2>Your wishlist is empty</h2>
                <p>Save items you love by clicking the heart icon</p>
                <a href="{{ route('website.shop') }}" class="shop-btn">Explore Products</a>
            </div>
        @endif
    </div>
</div>

<style>
.wishlist-page{padding:32px 0}
.page-title{font-size:24px;font-weight:700;color:#1e293b;margin-bottom:24px}
.wish-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px}
.wish-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;position:relative;transition:all .2s}
.wish-card:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,.08)}
.remove-btn{position:absolute;top:10px;right:10px;z-index:5;width:28px;height:28px;background:#fff;border:1px solid #e2e8f0;border-radius:50%;color:#94a3b8;cursor:pointer;font-size:16px}
.remove-btn:hover{background:#fef2f2;border-color:#ef4444;color:#ef4444}
.card-img{display:block;aspect-ratio:1;background:#f8fafc;position:relative}
.card-img img{width:100%;height:100%;object-fit:contain;padding:16px}
.no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:12px}
.disc{position:absolute;top:10px;left:10px;background:#ef4444;color:#fff;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600}
.card-info{padding:14px}
.card-name{display:block;font-size:13px;font-weight:500;color:#1e293b;margin-bottom:8px;min-height:36px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.card-name:hover{color:#3b82f6}
.card-price{display:flex;align-items:center;gap:8px;margin-bottom:12px}
.price{font-size:16px;font-weight:700;color:#1e293b}
.mrp{font-size:13px;color:#94a3b8;text-decoration:line-through}
.cart-btn{width:100%;padding:10px;background:#3b82f6;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.cart-btn:hover{background:#2563eb}
.empty-wish{text-align:center;padding:60px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;color:#fca5a5}
.empty-wish h2{font-size:20px;color:#1e293b;margin:20px 0 8px}
.empty-wish p{color:#64748b;margin-bottom:20px}
.shop-btn{display:inline-block;padding:12px 28px;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none}
.login-required{text-align:center;padding:60px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;color:#94a3b8}
.login-required h2{font-size:20px;color:#1e293b;margin:20px 0 8px}
.login-required p{margin-bottom:20px}
.login-btn{display:inline-block;padding:12px 32px;background:#3b82f6;color:#fff;border-radius:8px;font-weight:600;text-decoration:none}
.login-btn:hover{background:#2563eb}
.register-link{margin-top:16px;font-size:14px}
.register-link a{color:#3b82f6;text-decoration:none;font-weight:500}
@media(max-width:1200px){.wish-grid{grid-template-columns:repeat(4,1fr)}}
@media(max-width:900px){.wish-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){.wish-grid{grid-template-columns:repeat(2,1fr);gap:12px}}
</style>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cart-count-updated', (data) => {
            const el = document.getElementById('headerCartCount');
            if (el) el.textContent = data.count;
        });
        Livewire.on('wishlist-count-updated', (data) => {
            const el = document.getElementById('headerWishCount');
            if (el) el.textContent = data.count;
        });
    });
</script>
