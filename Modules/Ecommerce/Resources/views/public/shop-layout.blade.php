<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($settings && method_exists($settings, 'getFaviconUrl') && $settings->getFaviconUrl())
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --primary: #0891b2;
            --primary-dark: #0e7490;
            --secondary: #f97316;
            --dark: #0f172a;
            --text: #334155;
            --text-light: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 20px; }
        

        /* Header */
        .header { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); position: sticky; top: 0; z-index: 100; }
        .header-main { display: flex; align-items: center; height: 70px; gap: 40px; }
        
        .logo { font-size: 24px; font-weight: 700; color: var(--dark); display: flex; align-items: center; gap: 10px; }
        .logo img { height: 45px; }
        .logo-icon { width: 40px; height: 40px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
        
        /* Search */
        .search-wrap { flex: 1; max-width: 520px; position: relative; }
        .search-box { display: flex; border: 2px solid var(--border); border-radius: 10px; overflow: hidden; transition: border-color .2s; }
        .search-box:focus-within { border-color: var(--primary); }
        .search-box input { flex: 1; padding: 12px 16px; border: none; font-size: 14px; outline: none; }
        .search-box button { padding: 0 18px; background: var(--primary); border: none; color: #fff; cursor: pointer; }
        .search-box button:hover { background: var(--primary-dark); }
        
        /* Nav */
        .header-nav { display: flex; align-items: center; gap: 8px; margin-left: auto; }
        .nav-item { display: flex; flex-direction: column; align-items: center; padding: 8px 14px; border-radius: 8px; font-size: 12px; color: var(--text); transition: all .2s; }
        .nav-item:hover { background: var(--bg); color: var(--primary); }
        .nav-item svg { width: 22px; height: 22px; margin-bottom: 2px; }
        .nav-item.cart { position: relative; }
        .cart-badge { position: absolute; top: 2px; right: 8px; background: var(--secondary); color: #fff; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 10px; }
        
        /* Hero Banner */
        .hero { background: linear-gradient(135deg, var(--primary) 0%, #0e7490 50%, #164e63 100%); color: #fff; padding: 50px 0; margin-bottom: 30px; }
        .hero-content { display: flex; align-items: center; justify-content: space-between; }
        .hero-text h1 { font-size: 36px; font-weight: 700; margin-bottom: 12px; }
        .hero-text p { font-size: 16px; opacity: .9; margin-bottom: 24px; max-width: 400px; }
        .hero-btn { display: inline-block; padding: 14px 32px; background: #fff; color: var(--primary); font-weight: 600; border-radius: 8px; transition: transform .2s; }
        .hero-btn:hover { transform: translateY(-2px); }
        .hero-stats { display: flex; gap: 40px; }
        .stat { text-align: center; }
        .stat-num { font-size: 32px; font-weight: 700; }
        .stat-label { font-size: 13px; opacity: .8; }
        
        /* Features Bar */
        .features { background: #fff; padding: 20px 0; margin-bottom: 30px; border-bottom: 1px solid var(--border); }
        .features-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .feature { display: flex; align-items: center; gap: 12px; }
        .feature-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0; }
        .feature-info { min-width: 0; }
        .feature-text { font-size: 14px; font-weight: 600; color: var(--dark); }
        .feature-sub { font-size: 12px; color: var(--text-light); }
        
        /* Main */
        main { min-height: 60vh; padding-bottom: 60px; }
        
        /* Footer */
        .footer { background: var(--dark); color: #fff; padding: 60px 0 30px; }
        .footer-grid { display: grid; grid-template-columns: 2fr repeat(3, 1fr); gap: 50px; margin-bottom: 40px; }
        .footer-brand { font-size: 22px; font-weight: 700; margin-bottom: 16px; }
        .footer-desc { font-size: 14px; color: #94a3b8; line-height: 1.7; }
        .footer-title { font-size: 15px; font-weight: 600; margin-bottom: 20px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #94a3b8; font-size: 14px; transition: color .2s; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid #334155; color: #64748b; font-size: 13px; }
        
        /* Toast */
        .toast { position: fixed; bottom: 24px; right: 24px; padding: 16px 24px; background: var(--dark); color: #fff; border-radius: 10px; font-size: 14px; display: none; z-index: 9999; box-shadow: 0 10px 40px rgba(0,0,0,.2); }
        .toast.show { display: flex; align-items: center; gap: 10px; animation: slideIn .3s ease; }
        .toast.success { background: #059669; }
        .toast.error { background: #dc2626; }
        .toast.info { background: var(--primary); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        /* Mobile First - Tablet */
        @media (max-width: 1024px) {
            .header-main { gap: 20px; }
            .search-wrap { max-width: 400px; }
            .hero-stats { gap: 30px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 30px; }
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .container { padding: 0 16px; }
            
            /* Header Mobile */
            .header-main { 
                flex-wrap: wrap; 
                height: auto; 
                padding: 12px 0; 
                gap: 12px;
            }
            .logo { font-size: 20px; flex: 1; }
            .logo img { height: 36px; }
            .logo-icon { width: 34px; height: 34px; }
            
            .search-wrap { 
                order: 3; 
                flex: 0 0 100%; 
                max-width: 100%; 
            }
            .search-box input { padding: 10px 14px; font-size: 14px; }
            .search-box button { padding: 0 14px; }
            
            .header-nav { 
                margin-left: 0;
                gap: 4px;
            }
            .nav-item { 
                padding: 6px 10px; 
                font-size: 11px; 
            }
            .nav-item svg { width: 20px; height: 20px; }
            .cart-badge { top: 0; right: 4px; font-size: 9px; padding: 1px 5px; }
            
            /* Hero Mobile */
            .hero { padding: 30px 0; margin-bottom: 0; }
            .hero-content { 
                flex-direction: column; 
                text-align: center; 
                gap: 24px; 
            }
            .hero-text { max-width: 100%; }
            .hero-text h1 { font-size: 24px; margin-bottom: 8px; }
            .hero-text p { font-size: 14px; margin-bottom: 16px; max-width: 100%; }
            .hero-btn { padding: 12px 24px; font-size: 14px; }
            .hero-stats { 
                gap: 16px;
                justify-content: center;
            }
            .stat-num { font-size: 22px; }
            .stat-label { font-size: 11px; }
            
            /* Features Mobile */
            .features { padding: 16px 0; margin-bottom: 20px; }
            .features-grid { 
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
            .feature { 
                flex-direction: column; 
                text-align: center; 
                gap: 8px;
                padding: 12px 8px;
                background: #f8fafc;
                border-radius: 12px;
            }
            .feature-icon { width: 42px; height: 42px; }
            .feature-icon svg { width: 20px; height: 20px; }
            .feature-text { font-size: 13px; }
            .feature-sub { font-size: 11px; }
            
            /* Footer Mobile */
            .footer { padding: 40px 0 20px; }
            .footer-grid { 
                grid-template-columns: 1fr; 
                gap: 30px; 
                text-align: center;
            }
            .footer-brand { font-size: 20px; }
            .footer-desc { font-size: 13px; }
            .footer-title { margin-bottom: 14px; }
            .footer-links li { margin-bottom: 10px; }
            
            /* Toast Mobile */
            .toast { 
                left: 16px; 
                right: 16px; 
                bottom: 16px; 
            }
        }
        
        /* Small Mobile */
        @media (max-width: 480px) {
            .logo { font-size: 18px; }
            .logo img { height: 32px; }
            .hero-text h1 { font-size: 20px; }
            .hero-stats { gap: 12px; }
            .stat-num { font-size: 20px; }
            .stat-label { font-size: 10px; }
            .nav-item span { display: none; }
            .nav-item { padding: 8px; }
        }
        @yield('styles')
    </style>
</head>
<body>

    
    {{-- Header --}}
    <header class="header">
        <div class="container">
            <div class="header-main">
                <a href="{{ route('ecommerce.shop') }}" class="logo">
                    @if($settings->getLogoUrl())
                        <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}">
                    @else
                        <span class="logo-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></span>
                        {{ $settings->site_name ?? 'Store' }}
                    @endif
                </a>
                
                <div class="search-wrap">
                    @livewire('ecommerce::product-search')
                </div>
                
                <nav class="header-nav">
                    @auth
                        <a href="{{ route('ecommerce.account') }}" class="nav-item">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Account
                        </a>
                    @else
                        <a href="{{ route('ecommerce.login') }}" class="nav-item">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Login
                        </a>
                    @endauth
                    <a href="{{ route('ecommerce.wishlist') }}" class="nav-item">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        Wishlist
                        @php $wishCount = Auth::check() ? count(session('wishlist_' . Auth::id(), [])) : 0; @endphp
                        <span class="cart-badge" id="headerWishCount" style="{{ $wishCount > 0 ? '' : 'display:none' }}">{{ $wishCount }}</span>
                    </a>
                    <a href="{{ route('ecommerce.cart') }}" class="nav-item cart">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        Cart
                        @php $cartCount = Auth::check() ? array_sum(array_column(session('cart_' . Auth::id(), []), 'qty')) : 0; @endphp
                        <span class="cart-badge" id="headerCartCount" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
                    </a>
                </nav>
            </div>
        </div>
    </header>
    
    <main>@yield('content')</main>
    
    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">{{ $settings->site_name ?? 'Store' }}</div>
                    <p class="footer-desc">Your one-stop destination for quality products at amazing prices. Shop with confidence!</p>
                </div>
                <div>
                    <div class="footer-title">Quick Links</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('ecommerce.shop') }}">All Products</a></li>
                        <li><a href="{{ route('ecommerce.cart') }}">Shopping Cart</a></li>
                        <li><a href="{{ route('ecommerce.wishlist') }}">My Wishlist</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">My Account</div>
                    <ul class="footer-links">
                        @auth
                            <li><a href="{{ route('ecommerce.account') }}">Account Details</a></li>
                            <li><a href="{{ route('ecommerce.orders') }}">Order History</a></li>
                        @else
                            <li><a href="{{ route('ecommerce.login') }}">Sign In</a></li>
                            <li><a href="{{ route('ecommerce.register') }}">Create Account</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <div class="footer-title">Contact Us</div>
                    <ul class="footer-links">
                        @if($settings->contact_email)<li><a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a></li>@endif
                        @if($settings->contact_phone)<li>{{ $settings->contact_phone }}</li>@endif
                        @if($settings->store_city)<li>{{ $settings->store_city }}, {{ $settings->store_state }}</li>@endif
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">© {{ date('Y') }} {{ $settings->site_name ?? 'Store' }}. All Rights Reserved.</div>
        </div>
    </footer>
    
    <div class="toast" id="toast"><span id="toastMsg"></span></div>
    
    @livewireScripts
    <script>
        function showToast(msg, type) {
            const t = document.getElementById('toast');
            document.getElementById('toastMsg').textContent = msg;
            t.className = 'toast show ' + (type || '');
            setTimeout(() => t.className = 'toast', 3500);
        }
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-notification', (d) => showToast(d.message, d.type));
            Livewire.on('cart-count-updated', (d) => { 
                let c = document.getElementById('headerCartCount');
                if(c) {
                    c.textContent = d.count;
                    c.style.display = d.count > 0 ? '' : 'none';
                }
                if(c) c.textContent = d.count;
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
