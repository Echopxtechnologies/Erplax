<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop - ' . ($settings->site_name ?? 'Store'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($settings && method_exists($settings, 'getFaviconUrl') && $settings->getFaviconUrl())
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#334155;line-height:1.5}
        a{text-decoration:none;color:inherit}
        .container{max-width:1400px;margin:0 auto;padding:0 20px}
        
        /* Header */
        .header{background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.05);position:sticky;top:0;z-index:100}
        .header-inner{display:flex;align-items:center;gap:24px;padding:16px 0}
        .logo{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:700;color:#1e293b}
        .logo img{max-height:40px}
        .logo-icon{width:40px;height:40px;background:#3b82f6;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff}
        .logo-icon svg{width:22px;height:22px}
        .header-actions{display:flex;align-items:center;gap:12px;margin-left:auto}
        .header-btn{position:relative;display:flex;align-items:center;justify-content:center;width:44px;height:44px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;color:#64748b;transition:all .15s}
        .header-btn:hover{background:#fff;border-color:#3b82f6;color:#3b82f6}
        .header-btn svg{width:22px;height:22px}
        .badge{position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;background:#ef4444;color:#fff;font-size:11px;font-weight:600;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 5px}
        .cart-link{display:flex;align-items:center;gap:8px;padding:10px 18px;background:#1e293b;color:#fff;border-radius:10px;font-size:14px;font-weight:600}
        .cart-link:hover{background:#334155}
        .cart-link svg{width:20px;height:20px}
        .cart-link .cnt{background:#f59e0b;color:#1e293b;padding:2px 8px;border-radius:10px;font-size:12px;font-weight:700}
        .login-link{padding:10px 18px;background:#3b82f6;color:#fff;border-radius:8px;font-size:14px;font-weight:500}
        .login-link:hover{background:#2563eb}
        .user-btn{background:#3b82f6;border-color:#3b82f6;color:#fff}
        .user-dropdown{position:relative}
        .dropdown-menu{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.15);min-width:220px;opacity:0;visibility:hidden;transform:translateY(-10px);transition:all .2s;z-index:1000}
        .dropdown-menu.show{opacity:1;visibility:visible;transform:translateY(0)}
        .dropdown-header{padding:16px;border-bottom:1px solid #f1f5f9}
        .user-name{display:block;font-weight:600;color:#1e293b;font-size:14px}
        .user-email{display:block;font-size:12px;color:#64748b;margin-top:2px}
        .dropdown-item{display:flex;align-items:center;gap:10px;padding:12px 16px;color:#475569;font-size:14px;text-decoration:none;transition:background .15s}
        .dropdown-item:hover{background:#f8fafc;color:#1e293b}
        .dropdown-form{margin:0}
        .logout-item{width:100%;border:none;background:none;cursor:pointer;text-align:left;color:#ef4444}
        .logout-item:hover{background:#fef2f2}
        
        /* Nav */
        .nav{background:#fff;border-bottom:1px solid #e2e8f0}
        .nav-inner{display:flex;gap:4px}
        .nav-link{display:flex;align-items:center;gap:6px;padding:14px 18px;font-size:14px;font-weight:500;color:#64748b;transition:color .15s}
        .nav-link:hover{color:#3b82f6}
        .nav-link.active{color:#3b82f6}
        .nav-link svg{width:18px;height:18px}
        
        /* Main */
        .main{min-height:60vh}
        
        /* Footer */
        .footer{background:#1e293b;color:#fff;padding:40px 0 24px;margin-top:48px}
        .footer-inner{display:grid;grid-template-columns:1.5fr repeat(3,1fr);gap:40px}
        .footer-brand{font-size:18px;font-weight:700;margin-bottom:12px}
        .footer-desc{color:#94a3b8;font-size:13px;line-height:1.7}
        .footer-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px}
        .footer-links{list-style:none}
        .footer-links li{margin-bottom:10px}
        .footer-links a{color:#94a3b8;font-size:13px;transition:color .15s}
        .footer-links a:hover{color:#fff}
        .footer-bottom{text-align:center;padding-top:24px;margin-top:24px;border-top:1px solid rgba(255,255,255,.1);color:#64748b;font-size:13px}
        
        /* Toast */
        #toast{position:fixed;bottom:24px;right:24px;z-index:9999;display:none}
        #toast.show{display:block;animation:slideIn .3s ease}
        #toast .toast-box{display:flex;align-items:center;gap:10px;padding:14px 20px;background:#1e293b;color:#fff;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,.2);font-size:14px;font-weight:500}
        #toast.success .toast-box{background:#059669}
        #toast.error .toast-box{background:#dc2626}
        #toast.info .toast-box{background:#3b82f6}
        @keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
        
        @media(max-width:768px){
            .header-inner{flex-wrap:wrap;gap:12px}
            .footer-inner{grid-template-columns:1fr 1fr;gap:24px}
            .cart-link span:not(.cnt){display:none}
        }
        @yield('styles')
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ route('website.shop') }}" class="logo">
                    @if($settings->getLogoUrl())
                        <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}">
                    @else
                        <div class="logo-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        {{ $settings->site_name ?? 'Store' }}
                    @endif
                </a>
                
                @livewire('website::product-search')
                
                <div class="header-actions">
                    @auth
                        <div class="user-dropdown">
                            <button class="header-btn user-btn" id="userDropdownBtn">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </button>
                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="user-email">{{ Auth::user()->email }}</span>
                                </div>
                                <a href="{{ route('website.account') }}" class="dropdown-item">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Account
                                </a>
                                <form method="POST" action="{{ route('website.logout') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('website.login') }}" class="login-link">Login</a>
                    @endauth
                    
                    <a href="{{ route('website.wishlist') }}" class="header-btn" title="Wishlist">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        @php
                            $wishlistCount = Auth::check() ? count(session('wishlist_' . Auth::id(), [])) : 0;
                        @endphp
                        <span class="badge" id="headerWishCount">{{ $wishlistCount }}</span>
                    </a>
                    
                    <a href="{{ route('website.cart') }}" class="cart-link">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Cart</span>
                        @php
                            $cartCount = Auth::check() ? array_sum(array_column(session('cart_' . Auth::id(), []), 'qty')) : 0;
                        @endphp
                        <span class="cnt" id="headerCartCount">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <nav class="nav">
        <div class="container">
            <div class="nav-inner">
                <a href="{{ route('website.shop') }}" class="nav-link {{ request()->routeIs('website.shop') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Shop
                </a>
                <a href="{{ route('website.wishlist') }}" class="nav-link {{ request()->routeIs('website.wishlist') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Wishlist
                </a>
                <a href="{{ route('website.cart') }}" class="nav-link {{ request()->routeIs('website.cart') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Cart
                </a>
                @if($settings->site_mode === 'both')
                <a href="{{ $settings->getPrefixPath() }}" class="nav-link">Website</a>
                @endif
            </div>
        </div>
    </nav>
    
    <main class="main">
        @yield('content')
    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <div class="footer-brand">{{ $settings->site_name ?? 'Store' }}</div>
                    <p class="footer-desc">{{ $settings->meta_description ?? 'Quality products at great prices.' }}</p>
                </div>
                <div>
                    <div class="footer-title">Shop</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('website.shop') }}">All Products</a></li>
                        <li><a href="{{ route('website.cart') }}">Cart</a></li>
                        <li><a href="{{ route('website.wishlist') }}">Wishlist</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">Account</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('website.login') }}">Login</a></li>
                        <li><a href="{{ route('website.account') }}">My Account</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">Help</div>
                    <ul class="footer-links">
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">© {{ date('Y') }} {{ $settings->site_name ?? 'Store' }}. All rights reserved.</div>
        </div>
    </footer>
    
    <div id="toast"><div class="toast-box"><span id="toastMsg"></span></div></div>

    @livewireScripts
    
    <script>
        // Toast notification
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            toast.className = 'show ' + type;
            toastMsg.textContent = msg;
            setTimeout(() => { toast.className = ''; }, 3000);
        }
        
        // Listen for Livewire events
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('cart-count-updated', (data) => {
                document.getElementById('headerCartCount').textContent = data.count || data[0]?.count || 0;
            });
            
            Livewire.on('wishlist-count-updated', (data) => {
                document.getElementById('headerWishCount').textContent = data.count || data[0]?.count || 0;
            });
            
            Livewire.on('show-notification', (data) => {
                const msg = data.message || data[0]?.message || 'Done!';
                const type = data.type || data[0]?.type || 'success';
                showToast(msg, type);
            });
        });
        
        // Fallback for older Livewire
        window.addEventListener('cart-count-updated', (e) => {
            document.getElementById('headerCartCount').textContent = e.detail.count || 0;
        });
        
        window.addEventListener('wishlist-count-updated', (e) => {
            document.getElementById('headerWishCount').textContent = e.detail.count || 0;
        });
        
        window.addEventListener('show-notification', (e) => {
            showToast(e.detail.message || 'Done!', e.detail.type || 'success');
        });
        
        // User dropdown toggle
        const dropdownBtn = document.getElementById('userDropdownBtn');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        
        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    </script>
    
    @yield('scripts')
</body>
</html>
