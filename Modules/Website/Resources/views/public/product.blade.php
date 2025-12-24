@extends('website::public.shop-layout')

@section('title', $product->name . ' - ' . ($settings->site_name ?? 'Store'))

@php
    $hasVariants = $product->has_variants && $product->activeVariations->count() > 0;
    $attributes = $hasVariants ? $product->getAttributesWithValues() : [];
    $priceRange = $product->getPriceRange();
    $currentStock = $product->getCurrentStock();
    $inStock = $hasVariants ? $product->hasAnyVariationInStock() : ($currentStock > 0);
    
    // Get cart quantities for this product (key: variation_id or 0 for non-variant)
    $cart = session('cart', []);
    $cartQtyMap = [];
    foreach ($cart as $key => $item) {
        if (($item['product_id'] ?? null) == $product->id) {
            $varId = $item['variation_id'] ?? 0;
            $cartQtyMap[$varId] = $item['qty'];
        }
    }
@endphp

@section('content')
<div class="pdp">
    <div class="container">
        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('website.shop') }}">Shop</a>
            @if($product->category)
            <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#94a3b8" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
            <a href="{{ route('website.shop', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a>
            @endif
            <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#94a3b8" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
            <span>{{ Str::limit($product->name, 35) }}</span>
        </div>

        <div class="product-layout">
            {{-- Left: Gallery --}}
            <div class="gallery-section">
                <div class="main-image-wrap">
                    @if($product->getPrimaryImageUrl())
                    <img src="{{ $product->getPrimaryImageUrl() }}" alt="{{ $product->name }}" id="mainImage" class="main-image">
                    <div class="image-zoom-hint">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        Click to zoom
                    </div>
                    @else
                    <div class="no-image">
                        <svg width="80" height="80" viewBox="0 0 24 24"><path fill="#e2e8f0" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                <div class="thumb-list">
                    @foreach($product->images as $i => $img)
                    <button class="thumb {{ $i === 0 ? 'active' : '' }}" data-src="{{ $img->getImageUrl() }}" data-var="{{ $img->variation_id }}">
                        <img src="{{ $img->getImageUrl() }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right: Details --}}
            <div class="details-section">
                @if($product->category)
                <span class="product-category">{{ $product->category->name }}</span>
                @endif
                
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-meta">
                    @if($product->sku)
                    <span class="sku">SKU: <span id="displaySku">{{ $product->sku }}</span></span>
                    @endif
                </div>

                {{-- Price --}}
                <div class="price-section" id="priceSection">
                    @if($hasVariants && $priceRange['has_range'])
                    <div class="price-range">
                        <span class="price">₹{{ number_format($priceRange['min'], 0) }}</span>
                        <span class="price-sep">-</span>
                        <span class="price">₹{{ number_format($priceRange['max'], 0) }}</span>
                    </div>
                    @else
                    <span class="price" id="currentPrice">₹{{ number_format($product->sale_price, 0) }}</span>
                    @if($product->mrp && $product->mrp > $product->sale_price)
                    <span class="mrp" id="currentMrp">₹{{ number_format($product->mrp, 0) }}</span>
                    <span class="discount-badge" id="discountBadge">{{ $product->getDiscountPercent() }}% OFF</span>
                    @endif
                    @endif
                </div>
                
                <p class="tax-info">Inclusive of all taxes</p>

                {{-- Stock Status --}}
                <div class="stock-status" id="stockStatus">
                    @if(!$inStock)
                    <div class="stock-badge out-of-stock">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                        Out of Stock
                    </div>
                    @elseif($hasVariants)
                    <div class="stock-badge select-options">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        Select options to check stock
                    </div>
                    @elseif($currentStock <= 5)
                    <div class="stock-badge low-stock">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                        Only {{ (int)$currentStock }} left - Order soon!
                    </div>
                    @else
                    <div class="stock-badge in-stock">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        In Stock
                    </div>
                    @endif
                </div>

                {{-- Variations - Pre-render with PHP disabled classes --}}
                @if($hasVariants)
                @php
                    // Build stock map for each attribute value
                    $stockByAttrValue = [];
                    foreach ($product->activeVariations as $var) {
                        $varStock = $var->getCurrentStock();
                        foreach ($var->attributeValues as $av) {
                            $key = $av->attribute_id . '_' . $av->id;
                            if (!isset($stockByAttrValue[$key])) {
                                $stockByAttrValue[$key] = 0;
                            }
                            $stockByAttrValue[$key] += $varStock;
                        }
                    }
                @endphp
                <div class="variations-wrapper" id="variationsWrapper">
                    @foreach($attributes as $attrData)
                    <div class="variation-group" data-attr="{{ $attrData['attribute']->id }}">
                        <label class="variation-label">
                            {{ $attrData['attribute']->name }}: 
                            <span class="selected-value" id="selectedValue{{ $attrData['attribute']->id }}">Select</span>
                        </label>
                        
                        @if($attrData['is_color'])
                        {{-- Color Swatches --}}
                        <div class="color-options">
                            @foreach($attrData['values'] as $val)
                            @php
                                $stockKey = $attrData['attribute']->id . '_' . $val->id;
                                $hasStock = ($stockByAttrValue[$stockKey] ?? 0) > 0;
                            @endphp
                            <button type="button" 
                                class="color-btn {{ !$hasStock ? 'disabled' : '' }}" 
                                data-attr="{{ $attrData['attribute']->id }}" 
                                data-value="{{ $val->id }}" 
                                data-name="{{ $val->value }}"
                                data-has-stock="{{ $hasStock ? '1' : '0' }}"
                                title="{{ $val->value }}{{ !$hasStock ? ' (Out of Stock)' : '' }}"
                                style="--color: {{ $val->getDisplayColor() }}">
                                <span class="color-inner"></span>
                                <svg class="color-check" viewBox="0 0 24 24"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </button>
                            @endforeach
                        </div>
                        @else
                        {{-- Size/Other Options --}}
                        <div class="size-options">
                            @foreach($attrData['values'] as $val)
                            @php
                                $stockKey = $attrData['attribute']->id . '_' . $val->id;
                                $hasStock = ($stockByAttrValue[$stockKey] ?? 0) > 0;
                            @endphp
                            <button type="button" 
                                class="size-btn {{ !$hasStock ? 'disabled' : '' }}" 
                                data-attr="{{ $attrData['attribute']->id }}" 
                                data-value="{{ $val->id }}" 
                                data-name="{{ $val->value }}"
                                data-has-stock="{{ $hasStock ? '1' : '0' }}"
                                title="{{ !$hasStock ? 'Out of Stock' : '' }}">
                                {{ $val->value }}
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Quantity --}}
                <div class="quantity-wrapper" id="quantityWrapper" style="{{ (!$inStock || $hasVariants) ? 'display:none' : '' }}">
                    <label class="quantity-label">Quantity:</label>
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn" id="qtyMinus" onclick="changeQuantity(-1)">−</button>
                        <input type="number" id="qtyInput" value="1" min="1" max="{{ min(99, (int)$currentStock) }}" readonly>
                        <button type="button" class="qty-btn" id="qtyPlus" onclick="changeQuantity(1)">+</button>
                    </div>
                    <span class="qty-hint" id="qtyHint">Max: {{ min(99, (int)$currentStock) }}</span>
                </div>
                
                {{-- Cart Info (shows if item already in cart) --}}
                <div class="cart-info" id="cartInfo" style="display:none">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span id="cartInfoText"></span>
                </div>

                {{-- Add to Cart --}}
                <div class="action-buttons">
                    <button type="button" class="btn-add-cart {{ !$inStock ? 'disabled' : '' }}" id="addToCartBtn" {{ !$inStock ? 'disabled' : '' }}>
                        <svg width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                        <span id="cartBtnText">{{ $inStock ? ($hasVariants ? 'Select Options' : 'Add to Cart') : 'Out of Stock' }}</span>
                    </button>
                    
                    <button type="button" class="btn-wishlist {{ in_array($product->id, session('wishlist', [])) ? 'active' : '' }}" id="wishlistBtn">
                        <svg width="22" height="22" viewBox="0 0 24 24" id="wishlistIcon">
                            <path fill="{{ in_array($product->id, session('wishlist', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>

                {{-- Features --}}
                <div class="features-list">
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#10b981" d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>
                        <span>Free Delivery</span>
                    </div>
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#10b981" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <span>Secure Payment</span>
                    </div>
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#10b981" d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.42 0-8 3.58-8 8H1l4 4 4-4H6z"/></svg>
                        <span>Easy Returns</span>
                    </div>
                </div>

                {{-- Description --}}
                @if($product->description)
                <div class="description-section">
                    <h3>Description</h3>
                    <div class="description-text">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
                @endif

                {{-- Specs --}}
                <div class="specs-section">
                    <h3>Product Details</h3>
                    <div class="specs-grid">
                        @if($product->sku)<div class="spec-row"><span>SKU</span><span>{{ $product->sku }}</span></div>@endif
                        @if($product->barcode)<div class="spec-row"><span>Barcode</span><span>{{ $product->barcode }}</span></div>@endif
                        @if($product->category)<div class="spec-row"><span>Category</span><span>{{ $product->category->name }}</span></div>@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="related-section">
            <h2>You May Also Like</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $rel)
                <a href="{{ route('website.product', $rel->id) }}" class="related-card">
                    <div class="related-img">
                        @if($rel->getPrimaryImageUrl())
                        <img src="{{ $rel->getPrimaryImageUrl() }}" alt="{{ $rel->name }}" loading="lazy">
                        @endif
                    </div>
                    <div class="related-info">
                        <span class="related-name">{{ Str::limit($rel->name, 45) }}</span>
                        <span class="related-price">₹{{ number_format($rel->sale_price, 0) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Reviews Section --}}
        <div class="reviews-section" id="reviews">
            <div class="reviews-header">
                <h2>Customer Reviews</h2>
                @if($product->review_count > 0)
                <div class="reviews-summary">
                    <div class="average-rating">
                        <span class="rating-value">{{ $product->average_rating }}</span>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($product->average_rating) ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                        <span class="rating-count">Based on {{ $product->review_count }} review{{ $product->review_count > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="rating-bars">
                        @php $breakdown = $product->getRatingBreakdown(); @endphp
                        @for($i = 5; $i >= 1; $i--)
                        <div class="bar-row">
                            <span class="bar-label">{{ $i }} ★</span>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $product->review_count > 0 ? ($breakdown[$i] / $product->review_count * 100) : 0 }}%"></div>
                            </div>
                            <span class="bar-count">{{ $breakdown[$i] }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
                @endif
            </div>

            {{-- Write Review Section - Amazon Style --}}
            <div class="write-review-section">
                @auth
                    @php
                        // Check if customer has purchased this product
                        $userEmail = auth()->user()->email;
                        $customer = \Modules\Website\Models\Ecommerce\Customer::where('email', $userEmail)->first();
                        $hasPurchased = false;
                        $hasAlreadyReviewed = false;
                        
                        // Check if already reviewed (by email OR customer_id)
                        $hasAlreadyReviewed = \Modules\Website\Models\Ecommerce\ProductReview::where('product_id', $product->id)
                            ->where(function($q) use ($customer, $userEmail) {
                                if ($customer) {
                                    $q->where('customer_id', $customer->id);
                                }
                                $q->orWhere('reviewer_email', $userEmail);
                            })
                            ->exists();
                        
                        if ($customer && !$hasAlreadyReviewed) {
                            $hasPurchased = \Modules\Website\Models\Ecommerce\WebsiteOrder::where('customer_id', $customer->id)
                                ->whereIn('status', ['delivered', 'shipped', 'processing', 'confirmed'])
                                ->whereHas('items', function($q) use ($product) {
                                    $q->where('product_id', $product->id);
                                })
                                ->exists();
                        }
                    @endphp

                    @if($hasAlreadyReviewed)
                        <div class="review-notice success">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>You have already reviewed this product. Thank you!</span>
                        </div>
                    @elseif($hasPurchased)
                        <button type="button" class="write-review-btn" onclick="document.getElementById('reviewForm').classList.toggle('open')">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Write a Review
                            <span class="verified-tag">✓ Verified Purchase</span>
                        </button>
                        
                        <div class="review-form-wrap" id="reviewForm">
                            <form action="{{ route('website.product.review', $product->id) }}" method="POST" class="review-form">
                                @csrf
                                <h3>Share Your Experience</h3>
                                <p class="form-subtitle">Your review will help other customers make informed decisions</p>
                                
                                <div class="form-group">
                                    <label>Your Rating *</label>
                                    <div class="star-rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                        <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★</label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Review Title</label>
                                    <input type="text" name="title" placeholder="Sum up your experience in a few words">
                                </div>

                                <div class="form-group">
                                    <label>Your Review *</label>
                                    <textarea name="review" rows="4" required placeholder="Tell us what you liked or didn't like about this product..."></textarea>
                                </div>

                                <input type="hidden" name="reviewer_name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="reviewer_email" value="{{ auth()->user()->email }}">

                                <button type="submit" class="submit-review-btn">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="review-notice warning">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <strong>Want to write a review?</strong>
                                <span>Only customers who have purchased this product can leave a review.</span>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="review-notice info">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <div>
                            <strong>Have you purchased this product?</strong>
                            <span>Sign in to write a review</span>
                        </div>
                        <a href="{{ route('website.login') }}?redirect={{ urlencode(request()->url()) }}" class="login-review-btn">Sign In</a>
                    </div>
                @endauth
            </div>

            {{-- Reviews List --}}
            <div class="reviews-list">
                @forelse($product->approvedReviews()->take(10)->get() as $review)
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <span class="reviewer-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</span>
                            <div>
                                <span class="reviewer-name">{{ $review->reviewer_name }}</span>
                                @if($review->is_verified_purchase)
                                <span class="verified-badge">✓ Verified Purchase</span>
                                @endif
                            </div>
                        </div>
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    @if($review->title)
                    <h4 class="review-title">{{ $review->title }}</h4>
                    @endif
                    <p class="review-text">{{ $review->review }}</p>
                    <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                    
                    @if($review->admin_reply)
                    <div class="admin-reply">
                        <span class="reply-label">Store Response:</span>
                        <p>{{ $review->admin_reply }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="no-reviews">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <h4>No Reviews Yet</h4>
                    <p>Be the first to share your experience with this product!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Data for JS --}}
<script id="productData" type="application/json">{
    "id": {{ $product->id }},
    "hasVariants": {{ $hasVariants ? 'true' : 'false' }},
    "basePrice": {{ $product->sale_price ?? 0 }},
    "baseMrp": {{ $product->mrp ?? $product->sale_price ?? 0 }},
    "baseStock": {{ $currentStock }},
    "variations": {!! $product->getVariationsJson() !!},
    "cartQty": {!! json_encode($cartQtyMap) !!}
}</script>
@endsection

@section('styles')
<style>
.pdp { padding: 20px 0 60px; background: linear-gradient(180deg, #f8fafc 0%, #fff 300px); }
.breadcrumb { display: flex; align-items: center; gap: 4px; font-size: 13px; color: #64748b; margin-bottom: 24px; flex-wrap: wrap; }
.breadcrumb a { color: #475569; text-decoration: none; transition: color .15s; }
.breadcrumb a:hover { color: #2563eb; }
.breadcrumb span { color: #94a3b8; }
.product-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; }
.gallery-section { position: sticky; top: 24px; align-self: start; }
.main-image-wrap { position: relative; aspect-ratio: 1; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.06); }
.main-image { width: 100%; height: 100%; object-fit: contain; padding: 32px; cursor: zoom-in; transition: transform .3s; }
.main-image:hover { transform: scale(1.02); }
.no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc; }
.image-zoom-hint { position: absolute; bottom: 16px; right: 16px; display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: rgba(0,0,0,.6); backdrop-filter: blur(8px); color: #fff; font-size: 12px; border-radius: 20px; opacity: 0; transition: opacity .2s; }
.main-image-wrap:hover .image-zoom-hint { opacity: 1; }
.thumb-list { display: flex; gap: 10px; margin-top: 16px; overflow-x: auto; padding: 4px; }
.thumb-list::-webkit-scrollbar { height: 0; }
.thumb { width: 72px; height: 72px; flex-shrink: 0; padding: 0; background: #fff; border: 2px solid transparent; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all .2s; }
.thumb:hover { border-color: #cbd5e1; }
.thumb.active { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
.thumb img { width: 100%; height: 100%; object-fit: cover; }
.details-section { padding: 8px 0; }
.product-category { display: inline-block; padding: 6px 12px; background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; border-radius: 6px; margin-bottom: 12px; }
.product-title { font-size: 28px; font-weight: 700; color: #0f172a; line-height: 1.3; margin: 0 0 12px; }
.product-meta { margin-bottom: 20px; }
.sku { font-size: 13px; color: #64748b; }
.sku span { font-family: 'SF Mono', Monaco, monospace; color: #475569; }
.price-section { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px; }
.price-range { display: flex; align-items: center; gap: 8px; }
.price-sep { color: #94a3b8; font-weight: 300; }
.price { font-size: 32px; font-weight: 800; color: #0f172a; letter-spacing: -1px; }
.mrp { font-size: 18px; color: #94a3b8; text-decoration: line-through; }
.discount-badge { padding: 6px 12px; background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; font-size: 13px; font-weight: 700; border-radius: 8px; }
.tax-info { font-size: 13px; color: #64748b; margin-bottom: 20px; }
.stock-status { margin-bottom: 24px; }
.stock-badge { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; font-size: 14px; font-weight: 600; }
.stock-badge.in-stock { background: #dcfce7; color: #15803d; }
.stock-badge.low-stock { background: #fef3c7; color: #b45309; animation: pulse 2s infinite; }
.stock-badge.out-of-stock { background: #fee2e2; color: #dc2626; }
.stock-badge.select-options { background: #f1f5f9; color: #475569; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
.variations-wrapper { display: flex; flex-direction: column; gap: 24px; margin-bottom: 24px; }
.variation-label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; }
.selected-value { color: #0f172a; font-weight: 700; }
.color-options { display: flex; gap: 10px; flex-wrap: wrap; }
.color-btn { width: 48px; height: 48px; padding: 4px; background: #fff; border: 2px solid #e2e8f0; border-radius: 50%; cursor: pointer; position: relative; transition: all .2s; }
.color-btn:hover:not(.disabled) { border-color: #94a3b8; transform: scale(1.08); }
.color-btn.active { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,.12); }
.color-btn.disabled { opacity: .35; cursor: not-allowed; }
.color-btn.disabled::after { content: ''; position: absolute; top: 50%; left: 8%; width: 84%; height: 2px; background: #64748b; transform: rotate(-45deg); }
.color-inner { display: block; width: 100%; height: 100%; border-radius: 50%; background: var(--color); box-shadow: inset 0 0 0 1px rgba(0,0,0,.1); }
.color-check { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 22px; height: 22px; color: #fff; filter: drop-shadow(0 1px 2px rgba(0,0,0,.4)); opacity: 0; transition: opacity .15s; }
.color-btn.active .color-check { opacity: 1; }
.size-options { display: flex; gap: 10px; flex-wrap: wrap; }
.size-btn { min-width: 56px; height: 48px; padding: 0 20px; background: #fff; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; font-weight: 600; color: #374151; cursor: pointer; transition: all .2s; }
.size-btn:hover:not(.disabled) { border-color: #94a3b8; background: #f8fafc; }
.size-btn.active { background: #0f172a; border-color: #0f172a; color: #fff; }
.size-btn.disabled { background: #f8fafc; border-color: #e2e8f0; border-style: dashed; color: #cbd5e1; cursor: not-allowed; text-decoration: line-through; }
.quantity-wrapper { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.quantity-label { font-size: 14px; font-weight: 600; color: #374151; }
.quantity-selector { display: flex; align-items: center; background: #f1f5f9; border-radius: 14px; overflow: hidden; }
.qty-btn { width: 48px; height: 48px; border: none; background: transparent; font-size: 20px; font-weight: 500; color: #475569; cursor: pointer; transition: all .15s; }
.qty-btn:hover:not(:disabled) { background: #e2e8f0; color: #0f172a; }
.qty-btn:disabled { opacity: .4; cursor: not-allowed; }
#qtyInput { width: 56px; height: 48px; border: none; background: transparent; text-align: center; font-size: 17px; font-weight: 700; color: #0f172a; }
.qty-hint { font-size: 12px; color: #94a3b8; }
.cart-info { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #eff6ff; border-radius: 10px; font-size: 13px; font-weight: 500; color: #2563eb; margin-bottom: 16px; }
.action-buttons { display: flex; gap: 12px; margin-bottom: 32px; }
.btn-add-cart { flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px; height: 58px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border: none; border-radius: 16px; font-size: 17px; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 4px 16px rgba(37,99,235,.3); }
.btn-add-cart:hover:not(.disabled) { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,.4); }
.btn-add-cart.disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; box-shadow: none; }
.btn-add-cart:disabled { opacity: .8; }
.btn-wishlist { width: 58px; height: 58px; background: #fff; border: 2px solid #e2e8f0; border-radius: 16px; color: #94a3b8; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
.btn-wishlist:hover, .btn-wishlist.active { background: #fef2f2; border-color: #fecaca; color: #ef4444; }
.features-list { display: flex; gap: 24px; padding: 20px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; margin-bottom: 24px; flex-wrap: wrap; }
.feature-item { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #475569; }
.description-section, .specs-section { margin-bottom: 24px; }
.description-section h3, .specs-section h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
.description-text { font-size: 14px; color: #475569; line-height: 1.8; }
.specs-grid { display: flex; flex-direction: column; }
.spec-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.spec-row span:first-child { color: #64748b; }
.spec-row span:last-child { color: #0f172a; font-weight: 500; }
.related-section { margin-top: 64px; }
.related-section h2 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 24px; }
.related-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px; }
.related-card { background: #fff; border-radius: 16px; overflow: hidden; text-decoration: none; transition: all .25s; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
.related-card:hover { transform: translateY(-6px); box-shadow: 0 16px 32px rgba(0,0,0,.1); }
.related-img { aspect-ratio: 1; background: #f8fafc; }
.related-img img { width: 100%; height: 100%; object-fit: contain; padding: 16px; }
.related-info { padding: 14px; }
.related-name { display: block; font-size: 13px; color: #374151; margin-bottom: 6px; line-height: 1.4; }
.related-price { font-size: 17px; font-weight: 700; color: #0f172a; }
@media (max-width: 1280px) { .related-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 1024px) { .product-layout { grid-template-columns: 1fr; gap: 32px; } .gallery-section { position: static; } .related-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .product-title { font-size: 22px; } .price { font-size: 26px; } .features-list { gap: 16px; } .related-grid { grid-template-columns: repeat(2, 1fr); } .action-buttons { flex-direction: column; } .btn-wishlist { width: 100%; } }

/* Reviews Section */
.reviews-section { margin-top: 64px; padding-top: 48px; border-top: 1px solid #e5e7eb; }
.reviews-header h2 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 24px; }
.reviews-summary { display: grid; grid-template-columns: auto 1fr; gap: 40px; margin-bottom: 32px; padding: 24px; background: #f8fafc; border-radius: 16px; }
.average-rating { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.rating-value { font-size: 48px; font-weight: 800; color: #0f172a; line-height: 1; }
.rating-stars { display: flex; gap: 2px; }
.rating-stars .star { font-size: 20px; color: #e2e8f0; }
.rating-stars .star.filled { color: #fbbf24; }
.rating-count { font-size: 13px; color: #64748b; }
.rating-bars { display: flex; flex-direction: column; gap: 8px; justify-content: center; }
.bar-row { display: flex; align-items: center; gap: 12px; }
.bar-label { font-size: 13px; font-weight: 500; color: #64748b; width: 36px; }
.bar-track { flex: 1; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; }
.bar-fill { height: 100%; background: linear-gradient(90deg, #fbbf24, #f59e0b); border-radius: 4px; transition: width 0.3s; }
.bar-count { font-size: 13px; font-weight: 600; color: #0f172a; width: 24px; text-align: right; }

.write-review-section { margin-bottom: 32px; }
.write-review-btn { display: flex; align-items: center; gap: 10px; padding: 14px 24px; background: #fff; border: 2px solid #2563eb; color: #2563eb; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.write-review-btn:hover { background: #2563eb; color: #fff; }
.write-review-btn .verified-tag { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-left: auto; }

/* Review Notices - Amazon Style */
.review-notice { display: flex; align-items: center; gap: 14px; padding: 18px 24px; border-radius: 12px; font-size: 14px; }
.review-notice svg { flex-shrink: 0; }
.review-notice div { display: flex; flex-direction: column; gap: 2px; flex: 1; }
.review-notice strong { font-weight: 600; color: #0f172a; }
.review-notice span { color: #64748b; font-size: 13px; }
.review-notice.info { background: #eff6ff; border: 1px solid #bfdbfe; }
.review-notice.info svg { color: #2563eb; }
.review-notice.warning { background: #fef3c7; border: 1px solid #fcd34d; }
.review-notice.warning svg { color: #d97706; }
.review-notice.success { background: #dcfce7; border: 1px solid #86efac; }
.review-notice.success svg { color: #16a34a; }
.login-review-btn { padding: 10px 20px; background: #2563eb; color: #fff; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all 0.2s; }
.login-review-btn:hover { background: #1d4ed8; color: #fff; }

.form-subtitle { font-size: 14px; color: #64748b; margin: -16px 0 24px; }
.review-form-wrap { max-height: 0; overflow: hidden; transition: max-height 0.4s ease-out; }
.review-form-wrap.open { max-height: 800px; }
.review-form { margin-top: 20px; padding: 28px; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; }
.review-form h3 { font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 24px; }
.review-form .form-group { margin-bottom: 20px; }
.review-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.review-form label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px; }
.review-form input, .review-form textarea { width: 100%; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 15px; transition: border-color 0.2s; }
.review-form input:focus, .review-form textarea:focus { outline: none; border-color: #2563eb; }
.star-rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
.star-rating-input input { display: none; }
.star-rating-input label { font-size: 32px; color: #e2e8f0; cursor: pointer; transition: color 0.15s; }
.star-rating-input label:hover, .star-rating-input label:hover ~ label, .star-rating-input input:checked ~ label { color: #fbbf24; }
.submit-review-btn { padding: 14px 32px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.submit-review-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.3); }

.reviews-list { display: flex; flex-direction: column; gap: 20px; }
.review-card { padding: 24px; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.reviewer-info { display: flex; align-items: center; gap: 12px; }
.reviewer-avatar { width: 44px; height: 44px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; }
.reviewer-name { font-size: 15px; font-weight: 600; color: #0f172a; display: block; }
.verified-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; color: #059669; background: #dcfce7; padding: 3px 8px; border-radius: 4px; margin-top: 4px; }
.review-rating .star { font-size: 16px; color: #e2e8f0; }
.review-rating .star.filled { color: #fbbf24; }
.review-title { font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 8px; }
.review-text { font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 12px; }
.review-date { font-size: 12px; color: #94a3b8; }
.admin-reply { margin-top: 16px; padding: 16px; background: #f8fafc; border-left: 3px solid #2563eb; border-radius: 0 8px 8px 0; }
.reply-label { font-size: 12px; font-weight: 600; color: #2563eb; display: block; margin-bottom: 8px; }
.admin-reply p { font-size: 14px; color: #374151; margin: 0; }
.no-reviews { text-align: center; padding: 48px 24px; background: #f8fafc; border-radius: 16px; }
.no-reviews svg { color: #94a3b8; margin-bottom: 16px; }
.no-reviews h4 { font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.no-reviews p { font-size: 14px; color: #64748b; }
@media (max-width: 640px) { .reviews-summary { grid-template-columns: 1fr; gap: 24px; } .review-form .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('scripts')
<script>
const productData = JSON.parse(document.getElementById('productData').textContent);
const variations = productData.variations || [];
let cartQty = productData.cartQty || {}; // { variationId: qty } or { "0": qty } for non-variant
let selectedOptions = {};
let selectedVariation = null;
let totalStock = 0;
let availableToAdd = 0;

document.addEventListener('DOMContentLoaded', () => {
    // Thumbnail clicks
    document.querySelectorAll('.thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            document.getElementById('mainImage').src = thumb.dataset.src;
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });

    if (productData.hasVariants) {
        // Bind click events
        document.querySelectorAll('.color-btn, .size-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('disabled')) return;
                selectOption(btn.dataset.attr, btn.dataset.value, btn.dataset.name);
            });
        });
        // Note: Disabled classes are already set by PHP on page load!
    } else {
        // Non-variant product - check cart qty
        initNonVariantProduct();
    }

    // Add to cart button
    document.getElementById('addToCartBtn').addEventListener('click', addToCart);
    
    // Wishlist button
    document.getElementById('wishlistBtn').addEventListener('click', toggleWishlist);
});

function initNonVariantProduct() {
    const inCart = cartQty['0'] || cartQty[0] || 0;
    totalStock = productData.baseStock;
    availableToAdd = Math.max(0, totalStock - inCart);
    
    if (inCart > 0) {
        document.getElementById('cartInfo').style.display = 'flex';
        document.getElementById('cartInfoText').textContent = `Already in cart: ${inCart}`;
    }
    
    const qtyHint = document.getElementById('qtyHint');
    const qtyInput = document.getElementById('qtyInput');
    const qtyWrapper = document.getElementById('quantityWrapper');
    const cartBtn = document.getElementById('addToCartBtn');
    const cartBtnText = document.getElementById('cartBtnText');
    
    if (availableToAdd <= 0) {
        qtyWrapper.style.display = 'none';
        cartBtn.classList.add('disabled');
        cartBtn.disabled = true;
        cartBtnText.textContent = 'Max in Cart';
    } else {
        qtyInput.max = Math.min(99, availableToAdd);
        qtyInput.value = 1;
        qtyHint.textContent = `Max: ${Math.min(99, availableToAdd)}`;
        updateQtyButtons();
    }
}

function selectOption(attrId, valueId, valueName) {
    selectedOptions[attrId] = parseInt(valueId);
    
    // Update active state in this group
    const group = document.querySelector(`.variation-group[data-attr="${attrId}"]`);
    group.querySelectorAll('.color-btn, .size-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.value == valueId);
    });
    
    // Update label
    document.getElementById('selectedValue' + attrId).textContent = valueName;
    
    // Update image immediately if this is a color attribute (find first matching variation)
    const mainImg = document.getElementById('mainImage');
    if (mainImg) {
        const matchingVar = variations.find(v => v.attributes[attrId] == valueId);
        if (matchingVar && matchingVar.image) {
            mainImg.src = matchingVar.image;
            // Update thumbnails active state
            document.querySelectorAll('.thumb').forEach(t => {
                t.classList.toggle('active', t.dataset.src === matchingVar.image);
            });
        }
    }
    
    // Update availability of other options based on current selection
    updateAvailability();
    
    // Check if we have a complete match
    checkVariationMatch();
}

function updateAvailability() {
    document.querySelectorAll('.variation-group').forEach(group => {
        const attrId = group.dataset.attr;
        
        group.querySelectorAll('.color-btn, .size-btn').forEach(btn => {
            const valueId = parseInt(btn.dataset.value);
            
            // Build test selection with this value
            const testSelection = { ...selectedOptions, [attrId]: valueId };
            
            // Check if any variation matches this selection AND has stock > 0
            const isAvailable = variations.some(v => {
                let matches = true;
                for (let key in testSelection) {
                    if (v.attributes[key] != testSelection[key]) {
                        matches = false;
                        break;
                    }
                }
                return matches && v.stock > 0;
            });
            
            // Don't disable if it's currently selected
            const isSelected = selectedOptions[attrId] == valueId;
            btn.classList.toggle('disabled', !isAvailable && !isSelected);
        });
    });
}

function checkVariationMatch() {
    const groupCount = document.querySelectorAll('.variation-group').length;
    const selectedCount = Object.keys(selectedOptions).length;
    
    if (selectedCount < groupCount) {
        updateProductUI(null);
        return;
    }
    
    // Find exact match
    selectedVariation = variations.find(v => {
        for (let attrId in selectedOptions) {
            if (v.attributes[attrId] != selectedOptions[attrId]) return false;
        }
        return true;
    });
    
    updateProductUI(selectedVariation);
}

function updateProductUI(variation) {
    const priceEl = document.getElementById('currentPrice');
    const mrpEl = document.getElementById('currentMrp');
    const discountEl = document.getElementById('discountBadge');
    const stockEl = document.getElementById('stockStatus');
    const qtyWrapper = document.getElementById('quantityWrapper');
    const qtyInput = document.getElementById('qtyInput');
    const qtyHint = document.getElementById('qtyHint');
    const cartBtn = document.getElementById('addToCartBtn');
    const cartBtnText = document.getElementById('cartBtnText');
    const skuEl = document.getElementById('displaySku');
    const mainImg = document.getElementById('mainImage');
    const cartInfo = document.getElementById('cartInfo');
    const cartInfoText = document.getElementById('cartInfoText');
    
    if (!variation) {
        stockEl.innerHTML = '<div class="stock-badge select-options"><svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>Select all options</div>';
        qtyWrapper.style.display = 'none';
        cartBtn.classList.add('disabled');
        cartBtn.disabled = true;
        cartBtnText.textContent = 'Select Options';
        cartInfo.style.display = 'none';
        return;
    }
    
    // Get qty in cart for THIS variation
    const inCart = cartQty[variation.id] || 0;
    totalStock = Math.floor(variation.stock);
    availableToAdd = Math.max(0, totalStock - inCart);
    
    // Show cart info
    if (inCart > 0) {
        cartInfo.style.display = 'flex';
        cartInfoText.textContent = `Already in cart: ${inCart}`;
    } else {
        cartInfo.style.display = 'none';
    }
    
    // Update price
    if (priceEl) priceEl.textContent = '₹' + numberFormat(variation.price);
    
    // MRP & discount
    if (variation.mrp && variation.mrp > variation.price) {
        if (mrpEl) { mrpEl.textContent = '₹' + numberFormat(variation.mrp); mrpEl.style.display = ''; }
        if (discountEl) {
            const discount = Math.round((variation.mrp - variation.price) / variation.mrp * 100);
            discountEl.textContent = discount + '% OFF';
            discountEl.style.display = '';
        }
    } else {
        if (mrpEl) mrpEl.style.display = 'none';
        if (discountEl) discountEl.style.display = 'none';
    }
    
    // Stock status
    if (totalStock <= 0) {
        stockEl.innerHTML = '<div class="stock-badge out-of-stock"><svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>Out of Stock</div>';
        qtyWrapper.style.display = 'none';
        cartBtn.classList.add('disabled');
        cartBtn.disabled = true;
        cartBtnText.textContent = 'Out of Stock';
    } else if (availableToAdd <= 0) {
        stockEl.innerHTML = `<div class="stock-badge low-stock"><svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>Maximum (${totalStock}) already in cart</div>`;
        qtyWrapper.style.display = 'none';
        cartBtn.classList.add('disabled');
        cartBtn.disabled = true;
        cartBtnText.textContent = 'Max in Cart';
    } else {
        if (totalStock <= 5) {
            stockEl.innerHTML = `<div class="stock-badge low-stock"><svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>Only ${totalStock} left${inCart > 0 ? ` (${inCart} in cart)` : ''}</div>`;
        } else {
            stockEl.innerHTML = '<div class="stock-badge in-stock"><svg width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>In Stock</div>';
        }
        
        qtyWrapper.style.display = 'flex';
        qtyInput.max = Math.min(99, availableToAdd);
        qtyInput.value = 1;
        qtyHint.textContent = `Max: ${Math.min(99, availableToAdd)}`;
        cartBtn.classList.remove('disabled');
        cartBtn.disabled = false;
        cartBtnText.textContent = 'Add to Cart';
    }
    
    // SKU
    if (skuEl && variation.sku) skuEl.textContent = variation.sku;
    
    // Image
    if (variation.image && mainImg) mainImg.src = variation.image;
    
    updateQtyButtons();
}

function updateQtyButtons() {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    const val = parseInt(input.value) || 1;
    const max = Math.min(99, availableToAdd);
    
    const minusBtn = document.getElementById('qtyMinus');
    const plusBtn = document.getElementById('qtyPlus');
    if (minusBtn) minusBtn.disabled = val <= 1;
    if (plusBtn) plusBtn.disabled = val >= max;
}

function changeQuantity(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + delta;
    const max = Math.min(99, availableToAdd);
    
    if (val < 1) val = 1;
    if (val > max) val = max;
    
    input.value = val;
    updateQtyButtons();
}

function addToCart() {
    const btn = document.getElementById('addToCartBtn');
    const btnText = document.getElementById('cartBtnText');
    
    if (btn.classList.contains('disabled')) return;
    
    if (productData.hasVariants && !selectedVariation) {
        showToast('Please select all options', 'error');
        return;
    }
    
    const qty = parseInt(document.getElementById('qtyInput')?.value || 1);
    
    if (qty > availableToAdd) {
        showToast(`Only ${availableToAdd} more can be added`, 'error');
        return;
    }
    
    btn.disabled = true;
    btnText.textContent = 'Adding...';
    
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productData.id,
            variation_id: selectedVariation?.id || null,
            qty: qty
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.requireLogin) {
            // Redirect to login
            showToast('Please login to add items to cart', 'info');
            setTimeout(() => {
                window.location.href = '{{ route("website.login") }}';
            }, 1000);
            return;
        }
        
        if (res.success) {
            document.getElementById('headerCartCount').textContent = res.cartCount;
            btnText.textContent = '✓ Added!';
            showToast(res.message || 'Added to cart!', 'success');
            
            // Update local cart qty
            const varId = selectedVariation?.id || '0';
            cartQty[varId] = res.totalInCart || ((cartQty[varId] || 0) + (res.addedQty || qty));
            
            // Refresh UI
            if (selectedVariation) {
                updateProductUI(selectedVariation);
            } else {
                initNonVariantProduct();
            }
            
            setTimeout(() => {
                if (!btn.classList.contains('disabled')) {
                    btnText.textContent = 'Add to Cart';
                }
                btn.disabled = false;
            }, 2000);
        } else {
            btnText.textContent = 'Add to Cart';
            btn.disabled = false;
            showToast(res.message || 'Error', 'error');
        }
    })
    .catch(() => {
        btnText.textContent = 'Add to Cart';
        btn.disabled = false;
        showToast('Error adding to cart', 'error');
    });
}

function toggleWishlist() {
    const btn = document.getElementById('wishlistBtn');
    const icon = document.getElementById('wishlistIcon').querySelector('path');
    
    fetch('/api/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productData.id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.requireLogin) {
            showToast('Please login to add to wishlist', 'info');
            setTimeout(() => {
                window.location.href = '{{ route("website.login") }}';
            }, 1000);
            return;
        }
        
        if (res.success) {
            document.getElementById('headerWishCount').textContent = res.wishlistCount;
            btn.classList.toggle('active', res.inWishlist);
            icon.setAttribute('fill', res.inWishlist ? 'currentColor' : 'none');
            showToast(res.inWishlist ? 'Added to wishlist!' : 'Removed', res.inWishlist ? 'success' : 'info');
        }
    });
}

function numberFormat(num) {
    return parseInt(num).toLocaleString('en-IN');
}
</script>
@endsection
