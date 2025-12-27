@extends('ecommerce::public.shop-layout')

@section('title', 'Shop - ' . ($settings->site_name ?? 'Store'))

@php
    use Modules\Ecommerce\Models\Product;
    use Modules\Ecommerce\Models\WebsiteOrder;
    use Modules\Ecommerce\Models\Customer;
    
    // Get real stats
    $totalProducts = Product::where('is_active', 1)->where('can_be_sold', 1)->count();
    $totalCustomers = Customer::where('is_website_user', 1)->count();
    $totalOrders = WebsiteOrder::count();
@endphp

@section('content')
{{-- Hero Banner --}}
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>{{ $settings->site_name ?? 'Welcome to Our Store' }}</h1>
                <p>{{ $settings->site_tagline ?? 'Shop the latest collection with unbeatable prices and quality you can trust.' }}</p>
                <a href="#products" class="hero-btn">Shop Now →</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">{{ $totalProducts }}+</div>
                    <div class="stat-label">Products</div>
                </div>
                <div class="stat">
                    <div class="stat-num">{{ $totalCustomers > 0 ? $totalCustomers : $totalOrders }}+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat">
                    <div class="stat-num">{{ $totalOrders }}+</div>
                    <div class="stat-label">Orders</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<div class="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                </div>
                <div class="feature-info">
                    <div class="feature-text">Free Delivery</div>
                    <div class="feature-sub">On orders above ₹{{ number_format($settings->free_shipping_min ?? 499) }}</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <div class="feature-info">
                    <div class="feature-text">Secure Payment</div>
                    <div class="feature-sub">100% Protected</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                </div>
                <div class="feature-info">
                    <div class="feature-text">Easy Returns</div>
                    <div class="feature-sub">7 Days Return Policy</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                </div>
                <div class="feature-info">
                    <div class="feature-text">24/7 Support</div>
                    <div class="feature-sub">Dedicated Help</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" id="products">
    @livewire('ecommerce::product-grid')
</div>
@endsection
