<div class="shop-page">
    <div class="container">
        {{-- Categories --}}
        <div class="cat-tabs">
            <button wire:click="setCategory('')" class="cat-tab {{ !$categoryId ? 'active' : '' }}">All</button>
            @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat->id }}')" class="cat-tab {{ $categoryId == $cat->id ? 'active' : '' }}">{{ $cat->name }}</button>
            @endforeach
        </div>
        
        {{-- Filter --}}
        <div class="filter-row">
            <div class="filter-info">
                @if($search)
                    Results for "<strong>{{ $search }}</strong>"
                    <button wire:click="clearFilters" class="clear-x">×</button>
                @else
                    <strong>{{ $products->total() }}</strong> Products
                @endif
            </div>
            <select wire:model.live="sortBy" class="sort-sel">
                <option value="newest">Newest</option>
                <option value="price_low">Price: Low-High</option>
                <option value="price_high">Price: High-Low</option>
                <option value="name">Name A-Z</option>
            </select>
        </div>
        
        {{-- Loading --}}
        <div wire:loading class="loading-box">
            <div class="ld-spinner"></div>
        </div>
        
        {{-- Products --}}
        <div wire:loading.remove>
            @if($products->count() > 0)
                <div class="prod-grid">
                    @foreach($products as $product)
                        @php
                            $hasVariants = $product->has_variants && $product->activeVariations->count() > 0;
                            $priceRange = $product->getPriceRange();
                            $stockStatus = $product->getStockStatus();
                            $inStock = $hasVariants ? $product->hasAnyVariationInStock() : $product->isInStock();
                        @endphp
                        <div class="prod-card">
                            {{-- Wishlist --}}
                            <button wire:click="toggleWishlist({{ $product->id }})" class="wl-btn {{ in_array($product->id, $wishlist) ? 'active' : '' }}">
                                <svg fill="{{ in_array($product->id, $wishlist) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                            
                            {{-- Image --}}
                            <a href="{{ route('website.product', $product->id) }}" class="prod-img">
                                @if($product->getPrimaryImageUrl())
                                    <img src="{{ $product->getPrimaryImageUrl() }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="no-img"><svg fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" width="40" height="40"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                @endif
                                @if($product->getDiscountPercent() && !$hasVariants)
                                    <span class="disc-badge">-{{ $product->getDiscountPercent() }}%</span>
                                @endif
                                @if(!$inStock)
                                    <span class="stock-badge out">Out of Stock</span>
                                @elseif($stockStatus === 'low_stock' && !$hasVariants)
                                    <span class="stock-badge low">Low Stock</span>
                                @endif
                                @if($hasVariants)
                                    <span class="var-badge">{{ $product->activeVariations->count() }} Options</span>
                                @endif
                            </a>
                            
                            {{-- Info --}}
                            <div class="prod-info">
                                <a href="{{ route('website.product', $product->id) }}" class="prod-name">{{ $product->name }}</a>
                                <div class="prod-price">
                                    @if($hasVariants && $priceRange['has_range'])
                                        <span class="price">₹{{ \Modules\Website\Models\Ecommerce\Product::formatPrice($priceRange['min']) }} - ₹{{ \Modules\Website\Models\Ecommerce\Product::formatPrice($priceRange['max']) }}</span>
                                    @else
                                        <span class="price">₹{{ \Modules\Website\Models\Ecommerce\Product::formatPrice($product->sale_price) }}</span>
                                        @if($product->mrp && $product->mrp > $product->sale_price)
                                            <span class="mrp">₹{{ \Modules\Website\Models\Ecommerce\Product::formatPrice($product->mrp) }}</span>
                                        @endif
                                    @endif
                                </div>
                                @if(!$inStock)
                                    <button class="add-btn disabled" disabled>Out of Stock</button>
                                @elseif($hasVariants)
                                    <a href="{{ route('website.product', $product->id) }}" class="add-btn opts">Select Options</a>
                                @else
                                    <button wire:click="addToCart({{ $product->id }})" class="add-btn">Add to Cart</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($products->hasMorePages())
                    <div class="load-more">
                        <button wire:click="loadMore" wire:loading.attr="disabled" class="more-btn">
                            <span wire:loading.remove wire:target="loadMore">Load More</span>
                            <span wire:loading wire:target="loadMore">Loading...</span>
                        </button>
                    </div>
                @endif
            @else
                <div class="empty-box">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="60" height="60"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <h3>No products found</h3>
                    @if($search || $categoryId)
                        <button wire:click="clearFilters" class="reset-btn">Clear Filters</button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shop-page{padding:24px 0 48px}
.cat-tabs{display:flex;gap:8px;overflow-x:auto;padding:8px 0;margin-bottom:20px}
.cat-tabs::-webkit-scrollbar{display:none}
.cat-tab{padding:10px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:500;color:#64748b;cursor:pointer;white-space:nowrap;transition:all .15s}
.cat-tab:hover{border-color:#3b82f6;color:#3b82f6}
.cat-tab.active{background:#3b82f6;border-color:#3b82f6;color:#fff}
.filter-row{display:flex;justify-content:space-between;align-items:center;padding:12px 0;margin-bottom:20px;border-bottom:1px solid #e2e8f0}
.filter-info{font-size:14px;color:#64748b}
.filter-info strong{color:#1e293b}
.clear-x{background:#fee2e2;border:none;color:#ef4444;width:20px;height:20px;border-radius:50%;cursor:pointer;margin-left:8px;font-size:14px}
.sort-sel{padding:8px 32px 8px 12px;background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") right 8px center/14px no-repeat;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;cursor:pointer;appearance:none}
.loading-box{display:flex;justify-content:center;padding:60px}
.ld-spinner{width:40px;height:40px;border:3px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.prod-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px}
.prod-card{background:#fff;border-radius:12px;overflow:hidden;border:1px solid #f1f5f9;position:relative;transition:all .2s}
.prod-card:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,.08);border-color:transparent}
.wl-btn{position:absolute;top:10px;right:10px;z-index:5;width:32px;height:32px;background:#fff;border:1px solid #e2e8f0;border-radius:50%;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s}
.wl-btn:hover,.wl-btn.active{background:#fef2f2;border-color:#ef4444;color:#ef4444}
.prod-img{display:block;aspect-ratio:1;background:#f8fafc;position:relative}
.prod-img img{width:100%;height:100%;object-fit:contain;padding:16px}
.no-img{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#e2e8f0}
.disc-badge{position:absolute;top:10px;left:10px;background:#ef4444;color:#fff;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600}
.stock-badge{position:absolute;bottom:10px;left:10px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600}
.stock-badge.out{background:#fef2f2;color:#dc2626}
.stock-badge.low{background:#fefce8;color:#ca8a04}
.var-badge{position:absolute;bottom:10px;left:10px;background:#eff6ff;color:#3b82f6;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600}
.prod-info{padding:14px}
.prod-name{display:block;font-size:13px;font-weight:500;color:#1e293b;margin-bottom:8px;min-height:36px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prod-name:hover{color:#3b82f6}
.prod-price{display:flex;align-items:center;gap:8px;margin-bottom:12px}
.price{font-size:16px;font-weight:700;color:#1e293b}
.mrp{font-size:13px;color:#94a3b8;text-decoration:line-through}
.add-btn{width:100%;padding:10px;background:#3b82f6;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background .15s;text-align:center;text-decoration:none;display:block}
.add-btn:hover{background:#2563eb}
.add-btn.opts{background:#fff;color:#3b82f6;border:1px solid #3b82f6}
.add-btn.opts:hover{background:#eff6ff}
.add-btn.disabled{background:#e2e8f0;color:#94a3b8;cursor:not-allowed}
.load-more{text-align:center;padding:32px}
.more-btn{padding:12px 40px;background:#fff;color:#3b82f6;border:2px solid #3b82f6;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer}
.more-btn:hover{background:#3b82f6;color:#fff}
.more-btn:disabled{opacity:.6;cursor:wait}
.empty-box{text-align:center;padding:60px;background:#fff;border-radius:16px;border:1px solid #e2e8f0;color:#64748b}
.empty-box h3{font-size:18px;color:#1e293b;margin:16px 0 20px}
.reset-btn{padding:10px 24px;background:#3b82f6;color:#fff;border:none;border-radius:8px;cursor:pointer}
@media(max-width:1280px){.prod-grid{grid-template-columns:repeat(4,1fr)}}
@media(max-width:1024px){.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){.prod-grid{grid-template-columns:repeat(2,1fr);gap:12px}.prod-info{padding:10px}.price{font-size:14px}}
</style>
