<div class="products-section">
    {{-- Filters --}}
    <div class="filters-bar">
        <div class="category-tabs">
            <button wire:click="setCategory('')" class="cat-btn {{ !$categoryId ? 'active' : '' }}">All Products</button>
            @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat->id }}')" class="cat-btn {{ $categoryId == $cat->id ? 'active' : '' }}">{{ $cat->name }}</button>
            @endforeach
        </div>
        
        <div class="filters-right">
            @if($search)
                <div class="search-tag">
                    "{{ $search }}"
                    <button wire:click="clearFilters">×</button>
                </div>
            @endif
            <span class="results-count">{{ $products->total() }} products</span>
            <select wire:model.live="sortBy" class="sort-select">
                <option value="newest">Newest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="name">Name A-Z</option>
            </select>
        </div>
    </div>
    
    {{-- Loading --}}
    <div wire:loading class="loading-state">
        <div class="loader"></div>
        <span>Loading products...</span>
    </div>
    
    {{-- Products Grid --}}
    <div wire:loading.remove>
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    @php
                        $hasVariants = $product->has_variants && $product->activeVariations->count() > 0;
                        $priceRange = $product->getPriceRange();
                        $currentStock = $product->getCurrentStock();
                        $inStock = $hasVariants ? $product->hasAnyVariationInStock() : $product->isInStock();
                        $isNew = $product->created_at >= now()->subDays(14);
                        $inWishlist = in_array($product->id, $wishlist);
                        $discount = $product->getDiscountPercent();
                    @endphp
                    <div class="product-card">
                        {{-- Image --}}
                        <div class="card-image">
                            <a href="{{ route('ecommerce.product', $product->id) }}">
                                @if($product->getPrimaryImageUrl())
                                    <img src="{{ $product->getPrimaryImageUrl() }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="no-image">
                                        <svg width="40" height="40" fill="none" stroke="#e2e8f0" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Badges --}}
                            <div class="card-badges">
                                @if($isNew)
                                    <span class="badge new">New</span>
                                @endif
                                @if($discount && !$hasVariants)
                                    <span class="badge sale">-{{ $discount }}%</span>
                                @endif
                            </div>
                            
                            {{-- Wishlist --}}
                            <button wire:click="toggleWishlist({{ $product->id }})" class="wishlist-btn {{ $inWishlist ? 'active' : '' }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                            
                            {{-- Stock --}}
                            @if(!$inStock)
                                <div class="out-overlay">Out of Stock</div>
                            @elseif(!$hasVariants && $currentStock > 0 && $currentStock <= 5)
                                <span class="low-badge">Only {{ $currentStock }} left!</span>
                            @endif
                        </div>
                        
                        {{-- Content --}}
                        <div class="card-content">
                            @if($hasVariants)
                                <span class="variant-label">{{ $product->activeVariations->count() }} options</span>
                            @endif
                            
                            <a href="{{ route('ecommerce.product', $product->id) }}" class="card-title">{{ $product->name }}</a>
                            
                            <div class="card-price">
                                @if($hasVariants && $priceRange['has_range'])
                                    <span class="price">₹{{ number_format($priceRange['min']) }} - ₹{{ number_format($priceRange['max']) }}</span>
                                @else
                                    <span class="price">₹{{ number_format($product->sale_price) }}</span>
                                    @if($product->mrp && $product->mrp > $product->sale_price)
                                        <span class="mrp">₹{{ number_format($product->mrp) }}</span>
                                    @endif
                                @endif
                            </div>
                            
                            {{-- Button --}}
                            @if(!$inStock)
                                <button class="add-btn disabled" disabled>Out of Stock</button>
                            @elseif($hasVariants)
                                <a href="{{ route('ecommerce.product', $product->id) }}" class="add-btn outline">View Options</a>
                            @else
                                <button wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled" class="add-btn">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Add to Cart
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Load More --}}
            @if($products->hasMorePages())
                <div class="load-more">
                    <button wire:click="loadMore" wire:loading.attr="disabled" class="load-btn">
                        <span wire:loading.remove wire:target="loadMore">Load More Products</span>
                        <span wire:loading wire:target="loadMore">Loading...</span>
                    </button>
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg width="60" height="60" fill="none" stroke="#cbd5e1" stroke-width="1" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h3>No products found</h3>
                <p>Try adjusting your search or filters</p>
                @if($search || $categoryId)
                    <button wire:click="clearFilters" class="clear-btn">Clear All Filters</button>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
.products-section { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* Filters */
.filters-bar { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 24px; flex-wrap: wrap; }
.category-tabs { display: flex; gap: 8px; overflow-x: auto; flex: 1; }
.cat-btn { padding: 10px 20px; background: #f1f5f9; border: none; border-radius: 25px; font-size: 14px; font-weight: 500; color: #64748b; cursor: pointer; white-space: nowrap; transition: all .2s; }
.cat-btn:hover { background: #e2e8f0; color: #334155; }
.cat-btn.active { background: #0891b2; color: #fff; }

.filters-right { display: flex; align-items: center; gap: 12px; }
.search-tag { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fef3c7; color: #92400e; border-radius: 6px; font-size: 13px; }
.search-tag button { background: none; border: none; font-size: 16px; cursor: pointer; color: inherit; }
.results-count { font-size: 14px; color: #64748b; }
.sort-select { padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: #fff; cursor: pointer; }

/* Loading */
.loading-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; gap: 12px; }
.loader { width: 36px; height: 36px; border: 3px solid #e2e8f0; border-top-color: #0891b2; border-radius: 50%; animation: spin .8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Grid */
.products-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }

/* Card */
.product-card { background: #fff; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; transition: all .3s ease; }
.product-card:hover { border-color: #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,.08); transform: translateY(-4px); }

.card-image { position: relative; aspect-ratio: 1; background: #f8fafc; }
.card-image a { display: block; width: 100%; height: 100%; }
.card-image img { width: 100%; height: 100%; object-fit: contain; padding: 16px; transition: transform .3s; }
.product-card:hover .card-image img { transform: scale(1.05); }
.no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }

/* Badges */
.card-badges { position: absolute; top: 12px; left: 12px; display: flex; flex-direction: column; gap: 6px; }
.badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
.badge.new { background: #10b981; color: #fff; }
.badge.sale { background: #ef4444; color: #fff; }

/* Wishlist */
.wishlist-btn { position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #fff; border: 1px solid #e2e8f0; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #94a3b8; transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.wishlist-btn:hover { color: #ef4444; border-color: #ef4444; }
.wishlist-btn.active { background: #fef2f2; color: #ef4444; border-color: #ef4444; }

/* Stock */
.out-overlay { position: absolute; inset: 0; background: rgba(0,0,0,.6); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 600; }
.low-badge { position: absolute; bottom: 10px; left: 10px; padding: 5px 10px; background: #fef3c7; color: #92400e; border-radius: 6px; font-size: 11px; font-weight: 600; }

/* Content */
.card-content { padding: 16px; }
.variant-label { display: inline-block; padding: 4px 10px; background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 500; border-radius: 5px; margin-bottom: 8px; }
.card-title { display: block; font-size: 14px; font-weight: 500; color: #1e293b; line-height: 1.5; margin-bottom: 10px; min-height: 42px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.card-title:hover { color: #0891b2; }

.card-price { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.price { font-size: 18px; font-weight: 700; color: #0f172a; }
.mrp { font-size: 13px; color: #94a3b8; text-decoration: line-through; }

/* Button */
.add-btn { display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 12px; background: #0891b2; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; }
.add-btn:hover { background: #0e7490; }
.add-btn.outline { background: transparent; color: #0891b2; border: 2px solid #0891b2; }
.add-btn.outline:hover { background: #0891b2; color: #fff; }
.add-btn.disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }

/* Load More */
.load-more { text-align: center; padding: 40px 0 20px; }
.load-btn { padding: 14px 40px; background: #fff; border: 2px solid #0891b2; border-radius: 10px; color: #0891b2; font-size: 15px; font-weight: 600; cursor: pointer; transition: all .2s; }
.load-btn:hover { background: #0891b2; color: #fff; }

/* Empty */
.empty-state { text-align: center; padding: 80px 20px; }
.empty-state h3 { font-size: 20px; color: #1e293b; margin: 20px 0 10px; }
.empty-state p { color: #64748b; margin-bottom: 24px; }
.clear-btn { padding: 12px 28px; background: #0891b2; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; }

/* Responsive */
@media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 900px) { .products-grid { grid-template-columns: repeat(3, 1fr); } .filters-bar { flex-direction: column; align-items: stretch; gap: 12px; } .filters-right { justify-content: space-between; } }
@media (max-width: 600px) { 
    .products-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } 
    .card-content { padding: 10px; } 
    .price { font-size: 15px; } 
    .mrp { font-size: 12px; }
    .card-title { font-size: 13px; min-height: 36px; }
    .add-btn { padding: 10px; font-size: 12px; gap: 4px; }
    .add-btn svg { width: 14px; height: 14px; }
    .badge { font-size: 10px; padding: 3px 6px; }
    .wish-btn { width: 30px; height: 30px; }
    .category-pills { gap: 6px; }
    .cat-pill { padding: 6px 12px; font-size: 12px; }
    .filters-bar { padding: 14px; }
    .result-count { font-size: 13px; }
    .sort-select { font-size: 13px; padding: 8px 12px; }
    .card-img { padding: 10px; }
    .loading { padding: 40px; }
}
</style>
