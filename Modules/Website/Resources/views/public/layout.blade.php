<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings->site_name ?? 'Website')</title>
    <meta name="description" content="@yield('description', $settings->meta_description ?? '')">
    
    @if($settings->getFaviconUrl())
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}" type="image/x-icon">
    @endif
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #667eea;
            --primary-hover: #5a67d8;
            --text-dark: #1a202c;
            --text-muted: #718096;
            --text-light: #4a5568;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
            --white: #ffffff;
            --success: #10b981;
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--white);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Header */
        .site-header {
            background: var(--white);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .site-logo {
            display: flex;
            align-items: center;
        }
        
        .site-logo img {
            max-height: 45px;
            max-width: 180px;
        }
        
        .site-logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        
        .nav-links a {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-dark);
            transition: color 0.2s;
        }
        
        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }
        
        .nav-btn {
            background: var(--primary);
            color: var(--white) !important;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .nav-btn:hover {
            background: var(--primary-hover);
        }
        
        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }
        
        .mobile-menu-btn svg {
            width: 24px;
            height: 24px;
            color: var(--text-dark);
        }
        
        /* Main Content */
        .main-content {
            min-height: calc(100vh - 200px);
        }
        
        /* Footer */
        .site-footer {
            background: var(--text-dark);
            color: var(--white);
            padding: 60px 20px 30px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .footer-brand {
            max-width: 300px;
        }
        
        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 16px;
        }
        
        .footer-logo img {
            max-height: 40px;
            filter: brightness(0) invert(1);
        }
        
        .footer-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
        }
        
        .footer-col h4 {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            color: var(--white);
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col li {
            margin-bottom: 12px;
        }
        
        .footer-col a {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            transition: color 0.2s;
        }
        
        .footer-col a:hover {
            color: var(--white);
        }
        
        .footer-bottom {
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .footer-copyright {
            font-size: 14px;
            color: rgba(255,255,255,0.6);
        }
        
        .footer-social {
            display: flex;
            gap: 16px;
        }
        
        .footer-social a {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: all 0.2s;
        }
        
        .footer-social a:hover {
            background: var(--primary);
        }
        
        .footer-social svg {
            width: 18px;
            height: 18px;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-dark);
        }
        
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .footer-brand {
                grid-column: span 2;
            }
        }
        
        @media (max-width: 480px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-brand {
                grid-column: span 1;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    @php
        $navbar = $headerSettings['navbar'] ?? [];
        $navBg = $navbar['bg_color'] ?? '#ffffff';
        $navText = $navbar['text_color'] ?? '#475569';
        $navHover = $navbar['hover_color'] ?? '#667eea';
    @endphp
    
    <!-- Header -->
    <header class="site-header" style="background: {{ $navBg }}; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;">
        <div class="header-container" style="max-width: 1200px; margin: 0 auto; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ $settings->getHomeUrl() }}" class="site-logo">
                @if($settings->getLogoUrl())
                    <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}" style="max-height: 45px;">
                @else
                    <span style="font-size: 24px; font-weight: 700; color: {{ $navHover }};">{{ $headerSettings['logo_text'] ?? $settings->site_name ?? 'Website' }}</span>
                @endif
            </a>
            
            <nav style="display: flex; align-items: center; gap: 8px;">
                @foreach(($headerSettings['menu'] ?? []) as $index => $item)
                    @php $isLast = $index === count($headerSettings['menu'] ?? []) - 1; @endphp
                    @if(!empty($item['children']))
                        <div style="position: relative;">
                            <a href="{{ $item['url'] }}" style="font-size: 15px; font-weight: 500; color: {{ $navText }}; text-decoration: none; padding: 10px 16px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                {{ $item['title'] }}
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                            </a>
                            <div class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; background: #fff; min-width: 200px; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); padding: 8px; margin-top: 4px;">
                                @foreach($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" style="display: block; padding: 10px 14px; color: {{ $navText }}; font-size: 14px; border-radius: 6px; text-decoration: none;">{{ $child['title'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" style="font-size: 15px; font-weight: 500; color: {{ $isLast ? '#fff' : $navText }}; text-decoration: none; padding: 10px {{ $isLast ? '20px' : '16px' }}; border-radius: 6px; {{ $isLast ? 'background: ' . $navHover . ';' : '' }}">{{ $item['title'] }}</a>
                    @endif
                @endforeach
            </nav>
        </div>
    </header>
    
    <style>
        .site-logo div:hover .dropdown-menu,
        nav > div:hover .dropdown-menu { display: block !important; }
        nav a:hover { opacity: 0.8; }
    </style>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        @if($settings->getLogoUrl())
                            <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}">
                        @else
                            {{ $settings->site_name ?? 'Website' }}
                        @endif
                    </div>
                    <p class="footer-desc">{{ $settings->meta_description ?? 'We provide amazing services and solutions for your business needs.' }}</p>
                </div>
                
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ $settings->getHomeUrl() }}">Home</a></li>
                        <li><a href="{{ $settings->getHomeUrl() }}#about">About Us</a></li>
                        <li><a href="{{ $settings->getHomeUrl() }}#services">Services</a></li>
                        @if($settings->hasEcommerce())
                        <li><a href="{{ $settings->getShopUrl() }}">Shop</a></li>
                        @endif
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{ $settings->getHomeUrl() }}#contact">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        @if($settings->contact_email)
                        <li><a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a></li>
                        @endif
                        @if($settings->contact_phone)
                        <li><a href="tel:{{ $settings->contact_phone }}">{{ $settings->contact_phone }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Website' }}. All rights reserved.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 6a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>
