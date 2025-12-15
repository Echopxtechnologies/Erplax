<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $companyName = \App\Models\Option::companyName() ?? config('app.name', 'ERPLax');
        $companyLogo = \App\Models\Option::companyLogo() ?? null;
        $companyFavicon = \App\Models\Option::companyFavicon() ?? null;
        $authUser = Auth::user();
    @endphp
    
    <title>{{ $companyName }} - Client Portal</title>
    
    @if($companyFavicon)
        <link rel="icon" href="{{ $companyFavicon }}">
    @endif
    
    <script>
        (function() {
            const theme = localStorage.getItem('client-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    
    <style>
        html[data-theme="dark"] { background: #0f172a; }
        html[data-theme="light"] { background: #f1f5f9; }
        html { visibility: hidden; }
        html.ready { visibility: visible; }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Green Theme for Client */
            --primary: #10b981;
            --primary-hover: #059669;
            --primary-light: #ecfdf5;
            --success: #22c55e;
            --success-light: #f0fdf4;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            
            --navbar-bg: #ffffff;
            --navbar-border: #e2e8f0;
            --navbar-text: #64748b;
            
            --sidebar-bg: #ffffff;
            --sidebar-border: #e2e8f0;
            --sidebar-text: #64748b;
            --sidebar-text-hover: #1e293b;
            --sidebar-active-bg: #10b981;
            --sidebar-active-text: #ffffff;
            --sidebar-user-bg: #f8fafc;
            --sidebar-section-text: #94a3b8;
            
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-text: #1e293b;
            
            --sidebar-width: 240px;
            --navbar-height: 56px;
            
            --font-family: 'Inter', sans-serif;
            --font-xs: 10px;
            --font-sm: 12px;
            --font-base: 13px;
            --font-md: 14px;
            --font-lg: 16px;
            --font-xl: 18px;
            
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 24px;
            
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
            
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        [data-theme="dark"] {
            --navbar-bg: #1e293b;
            --navbar-border: #334155;
            --navbar-text: #94a3b8;
            
            --sidebar-bg: #0f172a;
            --sidebar-border: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-hover: #ffffff;
            --sidebar-user-bg: rgba(255,255,255,0.05);
            --sidebar-section-text: #64748b;
            
            --body-bg: #0f172a;
            --card-bg: #1e293b;
            --card-border: #334155;
            
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            --input-bg: #334155;
            --input-border: #475569;
            --input-text: #f1f5f9;
            
            --primary-light: rgba(16,185,129,0.15);
            --success-light: rgba(34,197,94,0.15);
            --warning-light: rgba(245,158,11,0.15);
            --danger-light: rgba(239,68,68,0.15);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            font-size: var(--font-base);
            background: var(--body-bg);
            color: var(--text-primary);
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: var(--font-sm);
            font-weight: 500;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn svg { width: 14px; height: 14px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-light { background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); }
        .btn-light:hover { background: var(--body-bg); color: var(--text-primary); }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
        }
        .card-header { padding: var(--space-lg); border-bottom: 1px solid var(--card-border); }
        .card-body { padding: var(--space-lg); }
        .card-title { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; font-size: var(--font-xs); font-weight: 600; border-radius: var(--radius-sm); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        .badge-info { background: var(--primary-light); color: var(--primary); }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-lg);
            z-index: 1001;
        }
        
        .navbar-left { display: flex; align-items: center; gap: var(--space-md); }
        
        .hamburger-btn {
            width: 36px; height: 36px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .hamburger-btn:hover { background: var(--body-bg); }
        .hamburger-btn svg { width: 20px; height: 20px; color: var(--navbar-text); }
        
        .navbar-brand {
            display: flex; align-items: center; gap: 8px;
            text-decoration: none; color: var(--text-primary);
            font-size: var(--font-md); font-weight: 600;
        }
        .navbar-brand-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-brand-icon svg { width: 16px; height: 16px; color: #fff; }
        
        .navbar-center { display: flex; align-items: center; flex: 1; margin-left: var(--space-xl); }
        
        .navbar-badge {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: var(--primary-light); color: var(--primary);
            border-radius: var(--radius-md);
            font-size: var(--font-sm); font-weight: 600;
        }
        .navbar-badge svg { width: 16px; height: 16px; }
        
        .navbar-right { display: flex; align-items: center; gap: var(--space-sm); }
        
        .theme-toggle, .navbar-btn {
            width: 36px; height: 36px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .theme-toggle:hover, .navbar-btn:hover { background: var(--body-bg); }
        .theme-toggle svg, .navbar-btn svg { width: 18px; height: 18px; color: var(--navbar-text); }
        .theme-toggle .icon-dark { display: none; }
        [data-theme="dark"] .theme-toggle .icon-light { display: none; }
        [data-theme="dark"] .theme-toggle .icon-dark { display: block; }
        
        .notif-count {
            position: absolute; top: 4px; right: 4px;
            min-width: 16px; height: 16px;
            background: var(--danger); border-radius: 8px;
            font-size: 10px; font-weight: 600; color: #fff;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
        }
        .notif-count.hidden { display: none; }
        
        /* User Dropdown */
        .user-dropdown { position: relative; }
        .user-dropdown-toggle {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 8px; border: none; background: transparent;
            border-radius: var(--radius-md); cursor: pointer;
        }
        .user-dropdown-toggle:hover { background: var(--body-bg); }
        
        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-sm); font-weight: 600; color: #fff;
        }
        .user-name { font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); }
        .dropdown-arrow svg { width: 14px; height: 14px; color: var(--text-muted); }
        
        .user-dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 220px; background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            opacity: 0; visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            z-index: 1100;
        }
        .user-dropdown.active .user-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .dropdown-header { padding: var(--space-md) var(--space-lg); border-bottom: 1px solid var(--card-border); }
        .dropdown-header-name { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); }
        .dropdown-header-email { font-size: var(--font-xs); color: var(--text-muted); }
        
        .dropdown-menu-items { padding: var(--space-sm); }
        .dropdown-item {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px; font-size: var(--font-sm);
            color: var(--text-secondary); text-decoration: none;
            border-radius: var(--radius-md);
            border: none; background: none; width: 100%; cursor: pointer;
        }
        .dropdown-item:hover { background: var(--body-bg); color: var(--text-primary); }
        .dropdown-item svg { width: 16px; height: 16px; }
        .dropdown-item-danger { color: var(--danger); }
        .dropdown-item-danger:hover { background: var(--danger-light); color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--card-border); margin: var(--space-sm) 0; }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }
        .sidebar:hover { scrollbar-color: var(--sidebar-border) transparent; }
        .sidebar.hidden { transform: translateX(-100%); }
        
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: transparent; border-radius: 3px; }
        .sidebar:hover::-webkit-scrollbar-thumb { background: var(--sidebar-border); }
        
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: var(--space-lg);
            background: var(--sidebar-user-bg);
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-sm); font-weight: 600; color: #fff;
        }
        .sidebar-user-info { flex: 1; overflow: hidden; }
        .sidebar-user-name { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); }
        .sidebar-user-email { font-size: var(--font-xs); color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: 600;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: var(--radius-sm);
            text-transform: uppercase;
        }
        
        .sidebar-nav { padding: var(--space-md); }
        .sidebar-nav-title {
            font-size: var(--font-xs); font-weight: 600;
            color: var(--sidebar-section-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: var(--space-md) var(--space-md) var(--space-sm);
        }
        
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: var(--font-base);
            border-radius: var(--radius-md);
            margin-bottom: 2px;
            transition: all 0.15s;
        }
        .nav-item:hover { background: var(--body-bg); color: var(--sidebar-text-hover); }
        .nav-item.active { background: var(--sidebar-active-bg); color: var(--sidebar-active-text); }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-item span { flex: 1; }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded { margin-left: 0; }
        
        .page-container { padding: var(--space-xl); }
        
        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 999;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }
        
        /* Toast */
        .toast-container {
            position: fixed; bottom: 20px; left: 16px; right: 16px;
            z-index: 9999; display: flex; flex-direction: column; align-items: center; gap: 8px;
        }
        .toast {
            min-width: auto; max-width: 100%; width: auto;
            padding: 10px 16px; border-radius: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex; align-items: center; gap: 10px;
            transform: translateY(100px); opacity: 0;
            transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.hide { transform: translateY(100px); opacity: 0; }
        .toast-icon { width: 18px; height: 18px; flex-shrink: 0; }
        .toast-title { font-size: 13px; font-weight: 500; }
        .toast-close { padding: 2px; margin-left: 4px; background: none; border: none; cursor: pointer; display: flex; }
        .toast-close svg { width: 14px; height: 14px; }
        
        .toast-success { background: var(--success); color: #fff; }
        .toast-success .toast-icon, .toast-success .toast-title, .toast-success .toast-close { color: #fff; }
        .toast-error { background: var(--danger); color: #fff; }
        .toast-error .toast-icon, .toast-error .toast-title, .toast-error .toast-close { color: #fff; }
        .toast-warning { background: var(--warning); color: #fff; }
        .toast-warning .toast-icon, .toast-warning .toast-title, .toast-warning .toast-close { color: #fff; }
        .toast-info { background: var(--primary); color: #fff; }
        .toast-info .toast-icon, .toast-info .toast-title, .toast-info .toast-close { color: #fff; }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .navbar-center { display: none; }
            .user-name { display: none; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="hamburger-btn" onclick="toggleSidebar()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <a href="{{ route('client.dashboard') }}" class="navbar-brand">
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="height:32px;">
                @else
                    <div class="navbar-brand-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                @endif
                <span>{{ $companyName }}</span>
            </a>
        </div>
        
        <div class="navbar-center">
            <div class="navbar-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Client Portal
            </div>
        </div>
        
        <div class="navbar-right">
            <button class="theme-toggle" onclick="toggleTheme()">
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
                    <span class="user-name">{{ $authUser->name ?? 'Client' }}</span>
                    <div class="dropdown-arrow">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                <div class="user-dropdown-menu">
                    <div class="dropdown-header">
                        <div class="dropdown-header-name">{{ $authUser->name ?? 'Client' }}</div>
                        <div class="dropdown-header-email">{{ $authUser->email ?? '' }}</div>
                    </div>
                    <div class="dropdown-menu-items">
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('client.logout') }}" id="logoutForm">
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
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $authUser->name ?? 'Client' }}</div>
                <div class="sidebar-user-email">{{ $authUser->email ?? '' }}</div>
            </div>
            <span class="sidebar-user-badge">Client</span>
        </div>
        
        <nav class="sidebar-nav">
            <div class="sidebar-nav-title">Menu</div>
            
            <a href="{{ route('client.dashboard') }}" class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            
            {{-- Add your client menu items here --}}
            {{-- Example:
            <a href="{{ route('client.orders') }}" class="nav-item {{ request()->routeIs('client.orders*') ? 'active' : '' }}">
                <svg>...</svg>
                <span>My Orders</span>
            </a>
            --}}
            
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="page-container">
            {{ $slot }}
        </div>
    </main>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <script>
        document.documentElement.classList.add('ready');
        
        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('client-theme', newTheme);
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const mobile = window.innerWidth <= 1024;
            
            if (mobile) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('hidden');
                main.classList.toggle('expanded');
                localStorage.setItem('clientSidebarHidden', sidebar.classList.contains('hidden'));
            }
        }
        
        function toggleUserMenu(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
        }
        
        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (!dd.contains(e.target)) dd.classList.remove('active');
        });
        
        // Escape key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('userDropdown').classList.remove('active');
            }
        });
        
        // Load sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth > 1024 && localStorage.getItem('clientSidebarHidden') === 'true') {
                document.getElementById('sidebar').classList.add('hidden');
                document.getElementById('mainContent').classList.add('expanded');
            }
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.remove('mobile-open');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });
        
        // Toast System
        const Toast = {
            container: null,
            init() { this.container = document.getElementById('toastContainer'); },
            show(type, message, duration = 4000) {
                if (!this.container) this.init();
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                const icons = {
                    success: '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    error: '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    warning: '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                    info: '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                };
                toast.innerHTML = `
                    <svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">${icons[type]}</svg>
                    <span class="toast-title">${message}</span>
                    <button class="toast-close" onclick="Toast.dismiss(this.parentElement)"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                `;
                this.container.appendChild(toast);
                requestAnimationFrame(() => toast.classList.add('show'));
                toast.dismissTimer = setTimeout(() => this.dismiss(toast), duration);
                return toast;
            },
            dismiss(toast) {
                if (!toast || toast.classList.contains('hide')) return;
                if (toast.dismissTimer) clearTimeout(toast.dismissTimer);
                toast.classList.remove('show');
                toast.classList.add('hide');
                setTimeout(() => { if (toast.parentElement) toast.remove(); }, 300);
            },
            success(msg) { return this.show('success', msg); },
            error(msg) { return this.show('error', msg); },
            warning(msg) { return this.show('warning', msg); },
            info(msg) { return this.show('info', msg); }
        };
        
        document.addEventListener('DOMContentLoaded', function() { Toast.init(); });
    </script>
    
    @if (session('success'))
        <script>document.addEventListener('DOMContentLoaded', function() { Toast.success("{{ session('success') }}"); });</script>
    @endif
    @if (session('error'))
        <script>document.addEventListener('DOMContentLoaded', function() { Toast.error("{{ session('error') }}"); });</script>
    @endif
    @if (session('warning'))
        <script>document.addEventListener('DOMContentLoaded', function() { Toast.warning("{{ session('warning') }}"); });</script>
    @endif
    @if (session('info'))
        <script>document.addEventListener('DOMContentLoaded', function() { Toast.info("{{ session('info') }}"); });</script>
    @endif

    @stack('scripts')
</body>
</html>