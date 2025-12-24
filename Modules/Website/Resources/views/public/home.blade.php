<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_name ?? 'Welcome' }}</title>
    <meta name="description" content="{{ $settings->meta_description ?? '' }}">
    
    @if($settings->getFaviconUrl())
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}">
    @endif
    
    @php
        $navbar = $headerSettings['navbar'] ?? [];
        $navBg = $navbar['bg_color'] ?? '#ffffff';
        $navText = $navbar['text_color'] ?? '#475569';
        $navHover = $navbar['hover_color'] ?? '#667eea';
        $navDropdown = $navbar['dropdown_bg'] ?? '#ffffff';
        $navShadow = $navbar['shadow'] ?? true;
        $navSticky = $navbar['sticky'] ?? true;
        $navLogoSize = $navbar['logo_size'] ?? '26px';
        $navLogoWeight = $navbar['logo_weight'] ?? '800';
        $navMenuSize = $navbar['menu_size'] ?? '15px';
        $navMenuWeight = $navbar['menu_weight'] ?? '500';
        
        $footer = $headerSettings['footer'] ?? [];
        $footerBg = $footer['bg_color'] ?? '#0f172a';
        $footerText = $footer['text_color'] ?? '#94a3b8';
        $footerHeading = $footer['heading_color'] ?? '#e2e8f0';
        $footerHover = $footer['link_hover'] ?? '#667eea';
        $footerHeadingSize = $footer['heading_size'] ?? '14px';
        $footerHeadingWeight = $footer['heading_weight'] ?? '700';
        $footerLinkSize = $footer['link_size'] ?? '14px';
        $footerLinkWeight = $footer['link_weight'] ?? '400';
        $footerColumns = $footer['columns'] ?? [];
        $copyright = $footer['copyright'] ?? '© ' . date('Y') . ' All rights reserved.';
    @endphp
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }
        
        /* NAVBAR */
        .navbar {
            background: {{ $navBg }};
            @if($navShadow) box-shadow: 0 1px 3px rgba(0,0,0,0.1); @endif
            @if($navSticky) position: sticky; top: 0; @endif
            z-index: 1000;
        }
        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        .navbar-logo {
            font-size: {{ $navLogoSize }};
            font-weight: {{ $navLogoWeight }};
            color: {{ $navHover }};
        }
        .navbar-logo img { height: 40px; }
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link {
            position: relative;
            color: {{ $navText }};
            font-weight: {{ $navMenuWeight }};
            font-size: {{ $navMenuSize }};
            padding: 10px 16px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .nav-link:hover {
            color: {{ $navHover }};
            background: {{ $navHover }}0a;
        }
        .nav-link.has-dropdown::after {
            content: '';
            margin-left: 6px;
            border: 4px solid transparent;
            border-top-color: currentColor;
            display: inline-block;
            vertical-align: middle;
        }
        .nav-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: {{ $navDropdown }};
            min-width: 200px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 8px;
            margin-top: 4px;
        }
        .nav-link:hover .nav-dropdown { display: block; }
        .nav-dropdown a {
            display: block;
            padding: 10px 14px;
            color: {{ $navText }};
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.15s;
        }
        .nav-dropdown a:hover {
            background: {{ $navHover }}0a;
            color: {{ $navHover }};
        }
        
        /* Mobile */
        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }
        .navbar-toggle span {
            display: block;
            width: 24px;
            height: 2px;
            background: {{ $navText }};
            margin: 5px 0;
            transition: 0.3s;
        }
        .navbar-toggle.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .navbar-toggle.active span:nth-child(2) { opacity: 0; }
        .navbar-toggle.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        
        /* FOOTER */
        .footer {
            background: {{ $footerBg }};
            color: {{ $footerText }};
            padding: 60px 20px 30px;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-col h4 {
            color: {{ $footerHeading }};
            font-size: {{ $footerHeadingSize }};
            font-weight: {{ $footerHeadingWeight }};
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 12px; }
        .footer-col a {
            color: {{ $footerText }};
            font-size: {{ $footerLinkSize }};
            font-weight: {{ $footerLinkWeight }};
            transition: color 0.2s;
        }
        .footer-col a:hover { color: {{ $footerHover }}; }
        .footer-contact p {
            margin-bottom: 12px;
            font-size: {{ $footerLinkSize }};
            font-weight: {{ $footerLinkWeight }};
        }
        .footer-contact .label {
            color: {{ $footerText }}80;
            font-size: 12px;
            display: block;
            margin-bottom: 2px;
        }
        .footer-bottom {
            border-top: 1px solid {{ $footerText }}20;
            padding-top: 24px;
            text-align: center;
            font-size: 14px;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar-toggle { display: block; }
            .navbar-menu {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                bottom: 0;
                background: {{ $navBg }};
                flex-direction: column;
                padding: 20px;
                gap: 0;
                overflow-y: auto;
            }
            .navbar-menu.active { display: flex; }
            .nav-link {
                width: 100%;
                padding: 16px;
                border-bottom: 1px solid #eee;
            }
            .nav-dropdown {
                position: static;
                box-shadow: none;
                margin: 0;
                padding-left: 20px;
                background: transparent;
            }
            .nav-link.has-dropdown.open .nav-dropdown { display: block; }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
        
        /* Custom CSS */
        {!! $headerSettings['custom_navbar_css'] ?? '' !!}
        {!! $headerSettings['custom_footer_css'] ?? '' !!}
        {!! $headerSettings['custom_site_css'] ?? '' !!}
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ $settings->getHomeUrl() }}" class="navbar-logo">
                @if($settings->getLogoUrl())
                    <img src="{{ $settings->getLogoUrl() }}" alt="{{ $settings->site_name }}">
                @else
                    {{ $headerSettings['logo_text'] ?? $settings->site_name ?? 'Laravel' }}
                @endif
            </a>
            
            <button class="navbar-toggle" onclick="toggleMenu(this)">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <div class="navbar-menu" id="navMenu">
                @foreach(($headerSettings['menu'] ?? []) as $index => $item)
                    @if(!empty($item['children']))
                        <a href="{{ $item['url'] }}" class="nav-link has-dropdown" onclick="toggleDropdown(event, this)">
                            {{ $item['title'] }}
                            <div class="nav-dropdown">
                                @foreach($item['children'] as $child)
                                    <a href="{{ $child['url'] }}">{{ $child['title'] }}</a>
                                @endforeach
                            </div>
                        </a>
                    @else
                        <a href="{{ $item['url'] }}" class="nav-link">{{ $item['title'] }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>

    {!! $content !!}

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                @foreach($footerColumns as $column)
                    <div class="footer-col">
                        <h4>{{ $column['title'] }}</h4>
                        @if(($column['type'] ?? 'links') === 'contact')
                            <div class="footer-contact">
                                @if(!empty($column['address']))
                                    <p><span class="label">Address</span>{{ $column['address'] }}</p>
                                @endif
                                @if(!empty($column['phone']))
                                    <p><span class="label">Phone</span><a href="tel:{{ $column['phone'] }}">{{ $column['phone'] }}</a></p>
                                @endif
                                @if(!empty($column['email']))
                                    <p><span class="label">Email</span><a href="mailto:{{ $column['email'] }}">{{ $column['email'] }}</a></p>
                                @endif
                            </div>
                        @else
                            <ul>
                                @foreach(($column['links'] ?? []) as $link)
                                    <li><a href="{{ $link['url'] }}">{{ $link['text'] }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="footer-bottom">
                {{ $copyright }}
            </div>
        </div>
    </footer>
    
    <script>
        function toggleMenu(btn) {
            btn.classList.toggle('active');
            document.getElementById('navMenu').classList.toggle('active');
        }
        function toggleDropdown(e, link) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                link.classList.toggle('open');
            }
        }
    </script>
</body>
</html>
