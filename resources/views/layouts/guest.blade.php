<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $companyName = config('app.name', 'Laravel');
        $companyLogo = null;
        $companyFavicon = null;
        $authUser = Auth::user();
    @endphp
    
    <title>{{ $companyName }} - Dashboard</title>
    
    @if($companyFavicon)
        <link rel="icon" href="{{ $companyFavicon }}">
    @endif
    
    <script>
        (function() {
            const theme = localStorage.getItem('crm-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    
    <style>
        html[data-theme="dark"] { background: #0c1222; }
        html[data-theme="light"] { background: #f4f6f8; }
        html { visibility: hidden; }
        html.ready { visibility: visible; }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d9488;
            --primary-hover: #0f766e;
            --primary-light: #f0fdfa;
            --primary-dark: #134e4a;
            
            --secondary: #475569;
            --secondary-hover: #334155;
            --secondary-light: #f1f5f9;
            
            --accent: #06b6d4;
            --accent-light: #ecfeff;
            
            --success: #059669;
            --success-light: #ecfdf5;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --info: #0284c7;
            --info-light: #f0f9ff;
            
            --navbar-bg: #ffffff;
            --navbar-border: #e2e8f0;
            --navbar-text: #64748b;
            
            --sidebar-bg: #0f172a;
            --sidebar-border: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-hover: #ffffff;
            --sidebar-active-bg: rgba(13,148,136,0.15);
            --sidebar-active-text: #2dd4bf;
            --sidebar-active-border: #0d9488;
            --sidebar-header-bg: #1e293b;
            --sidebar-section-text: #64748b;
            
            --body-bg: #f4f6f8;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --input-focus: #0d9488;
            
            --sidebar-width: 260px;
            --navbar-height: 64px;
            
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-xs: 11px;
            --font-sm: 12px;
            --font-base: 13px;
            --font-md: 14px;
            --font-lg: 16px;
            --font-xl: 18px;
            --font-2xl: 22px;
            
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 24px;
            --space-2xl: 32px;
            
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
            --radius-xl: 12px;
            
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.08), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
        }
        
        [data-theme="dark"] {
            --navbar-bg: #1e293b;
            --navbar-border: #334155;
            --navbar-text: #94a3b8;
            
            --sidebar-bg: #0c1222;
            --sidebar-border: #1e293b;
            --sidebar-header-bg: #0f172a;
            
            --body-bg: #0c1222;
            --card-bg: #1e293b;
            --card-border: #334155;
            
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-text: #f1f5f9;
            
            --primary-light: rgba(13,148,136,0.15);
            --secondary-light: rgba(71,85,105,0.2);
            --success-light: rgba(5,150,105,0.15);
            --warning-light: rgba(217,119,6,0.15);
            --danger-light: rgba(220,38,38,0.15);
            --info-light: rgba(2,132,199,0.15);
            --accent-light: rgba(6,182,212,0.15);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            font-size: var(--font-base);
            background: var(--body-bg);
            color: var(--text-primary);
            transition: background 0.2s ease, color 0.2s ease;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: var(--font-sm);
            font-weight: 500;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); color: #fff; }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xs);
        }
        .card-header { padding: var(--space-lg) var(--space-xl); border-bottom: 1px solid var(--card-border); }
        .card-body { padding: var(--space-xl); }
        .card-title { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        
        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            font-size: var(--font-xs);
            font-weight: 500;
            border-radius: var(--radius-sm);
        }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-xl);
            z-index: 100;
        }
        
        .navbar-left { display: flex; align-items: center; gap: var(--space-lg); }
        
        .navbar-search {
            position: relative;
            width: 300px;
        }
        .navbar-search input {
            width: 100%;
            padding: 9px 12px 9px 38px;
            font-size: var(--font-sm);
            background: var(--secondary-light);
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            color: var(--text-primary);
            transition: all 0.15s ease;
        }
        .navbar-search input:focus {
            outline: none;
            background: var(--card-bg);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .navbar-search input::placeholder { color: var(--text-muted); }
        .navbar-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted);
        }
        
        .navbar-right { display: flex; align-items: center; gap: var(--space-sm); }
        
        .navbar-btn {
            width: 38px; height: 38px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s ease;
        }
        .navbar-btn:hover { background: var(--secondary-light); }
        .navbar-btn svg { width: 20px; height: 20px; color: var(--navbar-text); }
        
        .theme-toggle .icon-dark { display: none; }
        [data-theme="dark"] .theme-toggle .icon-light { display: none; }
        [data-theme="dark"] .theme-toggle .icon-dark { display: block; }
        
        /* User Dropdown */
        .user-dropdown { position: relative; }
        .user-dropdown-toggle {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 10px; border: none; background: transparent;
            border-radius: var(--radius-md); cursor: pointer;
            transition: all 0.15s ease;
        }
        .user-dropdown-toggle:hover { background: var(--secondary-light); }
        
        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-sm); font-weight: 600; color: #fff;
        }
        .user-info { text-align: left; }
        .user-name { font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); line-height: 1.2; }
        .user-role { font-size: var(--font-xs); color: var(--text-muted); text-transform: capitalize; }
        .dropdown-arrow svg { width: 14px; height: 14px; color: var(--text-muted); transition: transform 0.2s ease; }
        .user-dropdown.active .dropdown-arrow svg { transform: rotate(180deg); }
        
        .user-dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 220px; background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            opacity: 0; visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            z-index: 200;
        }
        .user-dropdown.active .user-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .dropdown-header {
            padding: var(--space-md) var(--space-lg);
            border-bottom: 1px solid var(--card-border);
        }
        .dropdown-header-name { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); }
        .dropdown-header-email { font-size: var(--font-xs); color: var(--text-muted); margin-top: 2px; }
        
        .dropdown-menu-items { padding: var(--space-sm); }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; font-size: var(--font-sm);
            color: var(--text-secondary); text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.15s ease;
            border: none; background: none; width: 100%; cursor: pointer;
        }
        .dropdown-item:hover { background: var(--secondary-light); color: var(--text-primary); }
        .dropdown-item svg { width: 16px; height: 16px; }
        .dropdown-item-danger { color: var(--danger); }
        .dropdown-item-danger:hover { background: var(--danger-light); color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--card-border); margin: var(--space-sm) 0; }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 101;
        }
        
        .sidebar-header {
            height: var(--navbar-height);
            padding: 0 var(--space-lg);
            background: var(--sidebar-header-bg);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-logo svg { width: 20px; height: 20px; color: #fff; }
        .sidebar-brand {
            font-size: var(--font-lg);
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }
        
        .sidebar-user {
            padding: var(--space-lg);
            margin: var(--space-md);
            background: var(--sidebar-header-bg);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-user-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: var(--radius-lg);
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-md); font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-info { overflow: hidden; }
        .sidebar-user-name { font-size: var(--font-sm); font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-email { font-size: var(--font-xs); color: var(--sidebar-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 var(--space-md) var(--space-lg);
        }
        .sidebar-nav-title {
            font-size: var(--font-xs);
            font-weight: 600;
            color: var(--sidebar-section-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: var(--space-xl) var(--space-md) var(--space-sm);
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: var(--font-base);
            font-weight: 500;
            border-radius: var(--radius-md);
            margin-bottom: 2px;
            transition: all 0.15s ease;
            position: relative;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--sidebar-text-hover);
        }
        .nav-item.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: var(--sidebar-active-border);
            border-radius: 0 2px 2px 0;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.7; }
        .nav-item.active svg { opacity: 1; }
        .nav-item span { flex: 1; }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            background: var(--body-bg);
        }
        
        .page-container { padding: var(--space-xl); max-width: 1400px; }
        
        /* Scrollbar */
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--sidebar-border); border-radius: 2px; }
        
        /* Mobile */
        .mobile-menu-btn {
            display: none;
            width: 38px; height: 38px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            align-items: center; justify-content: center;
        }
        .mobile-menu-btn:hover { background: var(--secondary-light); }
        .mobile-menu-btn svg { width: 20px; height: 20px; color: var(--navbar-text); }
        
        .sidebar-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 100;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }
        
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .top-navbar { left: 0; }
            .mobile-menu-btn { display: flex; }
            .navbar-search { width: 200px; }
        }
        
        @media (max-width: 768px) {
            .navbar-search { display: none; }
            .user-info { display: none; }
        }
        
        @media (max-width: 640px) {
            .page-container { padding: var(--space-lg); }
        }
        
        .hidden { display: none !important; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <a href="{{ route('dashboard') }}" class="sidebar-brand">{{ $companyName }}</a>
        </div>
        
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $authUser?->name ?? 'User' }}</div>
                <div class="sidebar-user-email">{{ $authUser?->email ?? '' }}</div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="sidebar-nav-title">Main Menu</div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            
            {{-- Add more menu items here --}}
            
        </nav>
    </aside>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileMenu()"></div>
    
    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="mobile-menu-btn" onclick="openMobileMenu()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <div class="navbar-search">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search...">
            </div>
        </div>
        
        <div class="navbar-right">
            <button class="navbar-btn theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <svg class="icon-light" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg class="icon-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>
            
            <div class="user-dropdown" id="userDropdown">
                <button class="user-dropdown-toggle" onclick="toggleUserMenu(event)">
                    <div class="user-avatar">{{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $authUser?->name ?? 'User' }}</div>
                        <div class="user-role">{{ $authUser?->role ?? 'user' }}</div>
                    </div>
                    <div class="dropdown-arrow">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                
                <div class="user-dropdown-menu">
                    <div class="dropdown-header">
                        <div class="dropdown-header-name">{{ $authUser?->name ?? 'User' }}</div>
                        <div class="dropdown-header-email">{{ $authUser?->email ?? '' }}</div>
                    </div>
                    <div class="dropdown-menu-items">
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('client.logout') }}" id="logoutForm" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-danger">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="page-container">
            {{ $slot }}
        </div>
    </main>
    
    <script>
        document.documentElement.classList.add('ready');
        
        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('crm-theme', newTheme);
        }
        
        function openMobileMenu() {
            document.getElementById('sidebar').classList.add('mobile-open');
            document.getElementById('sidebarOverlay').classList.add('active');
        }
        
        function closeMobileMenu() {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
        
        function toggleUserMenu(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
        }
        
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (!dd.contains(e.target)) dd.classList.remove('active');
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('userDropdown').classList.remove('active');
                closeMobileMenu();
            }
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeMobileMenu();
            }
        });
    </script>
</body>
</html>