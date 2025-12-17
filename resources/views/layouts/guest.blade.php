<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $companyName = \App\Models\Option::get('company_name', config('app.name', 'ERPLax'));
        $companyLogo = \App\Models\Option::get('company_logo');
        $companyFavicon = \App\Models\Option::get('company_favicon');
        $authUser = Auth::guard('web')->user();
        
        // Get active modules
        try {
            $activeModules = \App\Models\Module::where('is_active', true)->orderBy('sort_order')->get();
        } catch (\Exception $e) {
            $activeModules = collect();
        }
    @endphp
    
    <title>{{ $companyName }} - Client Portal</title>
    
    @if($companyFavicon)
        <link rel="icon" href="{{ Storage::url($companyFavicon) }}">
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
            --primary: #10b981;
            --primary-hover: #059669;
            --primary-light: #ecfdf5;
            --primary-dark: #047857;
            --success: #22c55e;
            --success-light: #f0fdf4;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #0ea5e9;
            --info-light: #f0f9ff;
            
            --navbar-bg: #ffffff;
            --navbar-border: #e2e8f0;
            --navbar-text: #64748b;
            
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-text: #1e293b;
            --input-focus: #10b981;
            
            --navbar-height: 60px;
            
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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
            --radius-xl: 12px;
            
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        [data-theme="dark"] {
            --navbar-bg: #1e293b;
            --navbar-border: #334155;
            --navbar-text: #94a3b8;
            
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
            --info-light: rgba(14,165,233,0.15);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            font-size: var(--font-base);
            background: var(--body-bg);
            color: var(--text-primary);
            line-height: 1.5;
        }
        
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-hover); }
        
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
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn svg { width: 14px; height: 14px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-light { background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); }
        .btn-light:hover { background: var(--body-bg); color: var(--text-primary); }
        .btn-outline { background: transparent; color: var(--primary); border: 1px solid var(--primary); }
        .btn-outline:hover { background: var(--primary); color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: var(--font-xs); }
        .btn-lg { padding: 10px 20px; font-size: var(--font-md); }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: var(--space-lg);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body { padding: var(--space-lg); }
        .card-footer { padding: var(--space-lg); border-top: 1px solid var(--card-border); }
        .card-title { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        
        /* Forms */
        .form-group { margin-bottom: var(--space-lg); }
        .form-label { display: block; font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); margin-bottom: var(--space-xs); }
        .form-label.required::after { content: ' *'; color: var(--danger); }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            font-size: var(--font-base);
            font-family: var(--font-family);
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: var(--radius-md);
            color: var(--input-text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .form-control::placeholder { color: var(--text-muted); }
        textarea.form-control { min-height: 100px; resize: vertical; }
        .form-error { font-size: var(--font-xs); color: var(--danger); margin-top: var(--space-xs); }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            font-size: var(--font-xs);
            font-weight: 600;
            border-radius: var(--radius-sm);
        }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        
        /* Tables */
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--card-border);
            font-size: var(--font-sm);
        }
        .table th { font-weight: 600; color: var(--text-secondary); background: var(--body-bg); }
        .table tbody tr:hover { background: var(--body-bg); }
        
        /* Navbar */
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
            padding: 0 var(--space-xl);
            z-index: 1001;
            gap: var(--space-lg);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-primary);
            font-size: var(--font-md);
            font-weight: 600;
            flex-shrink: 0;
        }
        .navbar-brand img { height: 32px; width: auto; }
        .navbar-brand-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-brand-icon svg { width: 18px; height: 18px; color: #fff; }
        
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            flex: 1;
            margin-left: var(--space-xl);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: var(--font-sm);
            font-weight: 500;
            color: var(--navbar-text);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.15s;
            white-space: nowrap;
        }
        .nav-link:hover { background: var(--body-bg); color: var(--text-primary); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); }
        .nav-link svg { width: 16px; height: 16px; }
        
        /* Nav Dropdown */
        .nav-dropdown { position: relative; }
        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: var(--font-sm);
            font-weight: 500;
            color: var(--navbar-text);
            background: none;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.15s;
        }
        .nav-dropdown-toggle:hover { background: var(--body-bg); color: var(--text-primary); }
        .nav-dropdown-toggle.active { background: var(--primary-light); color: var(--primary); }
        .nav-dropdown-toggle svg { width: 16px; height: 16px; }
        .nav-dropdown-toggle .chevron { width: 14px; height: 14px; transition: transform 0.2s; }
        .nav-dropdown.open .nav-dropdown-toggle .chevron { transform: rotate(180deg); }
        
        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 200px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: var(--space-sm);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            z-index: 1100;
        }
        .nav-dropdown.open .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-size: var(--font-sm);
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.15s;
        }
        .nav-dropdown-item:hover { background: var(--body-bg); color: var(--text-primary); }
        .nav-dropdown-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-dropdown-item svg { width: 16px; height: 16px; }
        
        /* Navbar Right */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-left: auto;
        }
        
        .theme-toggle {
            width: 36px; height: 36px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s;
        }
        .theme-toggle:hover { background: var(--body-bg); }
        .theme-toggle svg { width: 18px; height: 18px; color: var(--navbar-text); }
        .theme-toggle .icon-dark { display: none; }
        [data-theme="dark"] .theme-toggle .icon-light { display: none; }
        [data-theme="dark"] .theme-toggle .icon-dark { display: block; }
        
        /* User Dropdown */
        .user-dropdown { position: relative; }
        .user-dropdown-toggle {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 8px; border: none; background: transparent;
            border-radius: var(--radius-md); cursor: pointer;
            transition: background 0.15s;
        }
        .user-dropdown-toggle:hover { background: var(--body-bg); }
        
        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-sm); font-weight: 600; color: #fff;
            overflow: hidden;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
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
        .dropdown-header-email { font-size: var(--font-xs); color: var(--text-muted); margin-top: 2px; }
        
        .dropdown-menu-items { padding: var(--space-sm); }
        .dropdown-item {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px; font-size: var(--font-sm);
            color: var(--text-secondary); text-decoration: none;
            border-radius: var(--radius-md);
            border: none; background: none; width: 100%; cursor: pointer;
            text-align: left; transition: all 0.15s;
        }
        .dropdown-item:hover { background: var(--body-bg); color: var(--text-primary); }
        .dropdown-item svg { width: 16px; height: 16px; }
        .dropdown-item-danger { color: var(--danger); }
        .dropdown-item-danger:hover { background: var(--danger-light); color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--card-border); margin: var(--space-sm) 0; }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 36px; height: 36px;
            border: none; background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            align-items: center; justify-content: center;
        }
        .mobile-menu-toggle svg { width: 20px; height: 20px; color: var(--navbar-text); }
        
        /* Mobile Menu */
        .mobile-menu-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 1100;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-menu-overlay.active { opacity: 1; visibility: visible; }
        
        .mobile-menu {
            position: fixed;
            top: 0; left: 0;
            width: 280px; max-width: 85%;
            height: 100vh;
            background: var(--card-bg);
            z-index: 1101;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .mobile-menu.active { transform: translateX(0); }
        
        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-lg);
            border-bottom: 1px solid var(--card-border);
        }
        .mobile-menu-close {
            width: 32px; height: 32px;
            border: none; background: var(--body-bg);
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .mobile-menu-close svg { width: 16px; height: 16px; color: var(--text-secondary); }
        
        .mobile-menu-nav { padding: var(--space-md); }
        .mobile-nav-title {
            font-size: var(--font-xs);
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: var(--space-md) var(--space-sm);
        }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: var(--font-base);
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            margin-bottom: 2px;
        }
        .mobile-nav-link:hover { background: var(--body-bg); color: var(--text-primary); }
        .mobile-nav-link.active { background: var(--primary); color: #fff; }
        .mobile-nav-link svg { width: 18px; height: 18px; }
        
        /* Main Content */
        .main-content {
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
        }
        
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: var(--space-xl);
        }
        
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--space-xl);
            flex-wrap: wrap;
            gap: var(--space-md);
        }
        .page-title { font-size: var(--font-xl); font-weight: 600; margin: 0; }
        .page-subtitle { font-size: var(--font-sm); color: var(--text-muted); margin-top: 4px; }
        .page-actions { display: flex; gap: var(--space-sm); }
        
        /* Toast */
        .toast-container {
            position: fixed; bottom: 20px; right: 20px;
            z-index: 9999; display: flex; flex-direction: column; align-items: flex-end; gap: 8px;
        }
        .toast {
            min-width: 320px; max-width: 420px;
            padding: 14px 18px; border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: flex; align-items: center; gap: 10px;
            transform: translateX(100px); opacity: 0;
            transition: all 0.3s ease;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast.hide { transform: translateX(100px); opacity: 0; }
        .toast-icon { width: 18px; height: 18px; flex-shrink: 0; }
        .toast-title { font-size: var(--font-sm); font-weight: 500; margin: 0; flex: 1; }
        .toast-close { padding: 2px; background: none; border: none; cursor: pointer; display: flex; opacity: 0.7; }
        .toast-close:hover { opacity: 1; }
        .toast-close svg { width: 14px; height: 14px; }
        
        .toast-success { background: var(--success); color: #fff; }
        .toast-error { background: var(--danger); color: #fff; }
        .toast-warning { background: var(--warning); color: #fff; }
        .toast-info { background: var(--primary); color: #fff; }
        .toast svg, .toast .toast-close svg { color: #fff; }
        
        /* Utilities */
        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .mt-1 { margin-top: var(--space-sm); }
        .mt-2 { margin-top: var(--space-md); }
        .mt-3 { margin-top: var(--space-lg); }
        .mt-4 { margin-top: var(--space-xl); }
        .mb-1 { margin-bottom: var(--space-sm); }
        .mb-2 { margin-bottom: var(--space-md); }
        .mb-3 { margin-bottom: var(--space-lg); }
        .mb-4 { margin-bottom: var(--space-xl); }
        .d-flex { display: flex; }
        .d-none { display: none; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: var(--space-md); }
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .navbar-menu { display: none; }
            .mobile-menu-toggle { display: flex; }
            .user-name { display: none; }
            .page-container { padding: var(--space-lg); }
        }
        
        @media (max-width: 640px) {
            .top-navbar { padding: 0 var(--space-lg); }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-actions { width: 100%; }
            .toast-container { left: 16px; right: 16px; align-items: center; }
            .toast { min-width: auto; max-width: 100%; border-radius: 50px; padding: 12px 16px; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <header class="top-navbar">
        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Brand -->
        <a href="{{ route('client.dashboard') }}" class="navbar-brand">
            @if($companyLogo)
                <img src="{{ Storage::url($companyLogo) }}" alt="{{ $companyName }}">
            @else
                <div class="navbar-brand-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            @endif
            <span>{{ $companyName }}</span>
        </a>
        
        <!-- Navbar Menu (Desktop) -->
        <nav class="navbar-menu">
            <!-- Dashboard -->
            <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            
            {{-- Module Navbar Menus --}}
            @foreach($activeModules as $module)
                @if(View::exists(strtolower($module->alias) . '::client-navbar'))
                    @include(strtolower($module->alias) . '::client-navbar')
                @elseif(View::exists(strtolower($module->alias) . '::client.navbar'))
                    @include(strtolower($module->alias) . '::client.navbar')
                @endif
            @endforeach
            

            <!-- Profile -->
            @if(Route::has('client.profile'))
            <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile
            </a>
            @endif
        </nav>
        
        <!-- Navbar Right -->
        <div class="navbar-right">
            <!-- Theme Toggle -->
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <svg class="icon-light" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg class="icon-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>
            
            <!-- User Dropdown -->
            <div class="user-dropdown" id="userDropdown">
                <button class="user-dropdown-toggle" onclick="toggleUserMenu(event)">
                    <div class="user-avatar">
                        @if($authUser && $authUser->avatar)
                            <img src="{{ Storage::url($authUser->avatar) }}" alt="">
                        @else
                            {{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}
                        @endif
                    </div>
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
                            @if(Route::has('client.profile'))
                            <a href="{{ route('client.profile') }}" class="dropdown-item">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                My Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif
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
    
    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <span class="navbar-brand">
                <div class="navbar-brand-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                {{ $companyName }}
            </span>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <nav class="mobile-menu-nav">
            <div class="mobile-nav-title">Menu</div>
            
            <a href="{{ route('client.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            
            {{-- Module Mobile Menus --}}
            @foreach($activeModules as $module)
                @if(View::exists(strtolower($module->alias) . '::client-mobile-menu'))
                    @include(strtolower($module->alias) . '::client-mobile-menu')
                @elseif(View::exists(strtolower($module->alias) . '::client.mobile-menu'))
                    @include(strtolower($module->alias) . '::client.mobile-menu')
                @endif
            @endforeach
            
            <div class="mobile-nav-title">Account</div>
            
            <a href="{{ route('client.profile') }}" class="mobile-nav-link {{ request()->routeIs('client.profile*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                My Profile
            </a>
            
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="mobile-nav-link" style="color: var(--danger);">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="page-container">
            {{ $slot }}
        </div>
    </main>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <script>
        document.documentElement.classList.add('ready');
        
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('client-theme', newTheme);
        }
        
        // Mobile Menu
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('active');
            document.getElementById('mobileMenuOverlay').classList.toggle('active');
        }
        
        // User Dropdown
        function toggleUserMenu(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
            closeAllNavDropdowns();
        }
        
        // Nav Dropdown
        function toggleNavDropdown(el, e) {
            e.stopPropagation();
            const wasOpen = el.classList.contains('open');
            closeAllNavDropdowns();
            if (!wasOpen) el.classList.add('open');
        }
        
        function closeAllNavDropdowns() {
            document.querySelectorAll('.nav-dropdown').forEach(d => d.classList.remove('open'));
        }
        
        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            const userDD = document.getElementById('userDropdown');
            if (!userDD.contains(e.target)) userDD.classList.remove('active');
            document.querySelectorAll('.nav-dropdown').forEach(d => {
                if (!d.contains(e.target)) d.classList.remove('open');
            });
        });
        
        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('userDropdown').classList.remove('active');
                document.getElementById('mobileMenu').classList.remove('active');
                document.getElementById('mobileMenuOverlay').classList.remove('active');
                closeAllNavDropdowns();
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
                toast.timer = setTimeout(() => this.dismiss(toast), duration);
                return toast;
            },
            dismiss(toast) {
                if (!toast || toast.classList.contains('hide')) return;
                if (toast.timer) clearTimeout(toast.timer);
                toast.classList.remove('show');
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            },
            success(msg) { return this.show('success', msg); },
            error(msg) { return this.show('error', msg); },
            warning(msg) { return this.show('warning', msg); },
            info(msg) { return this.show('info', msg); }
        };
        
        document.addEventListener('DOMContentLoaded', () => Toast.init());
    </script>
    
    {{-- Flash Messages --}}
    @if (session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.success("{{ session('success') }}"));</script>
    @endif
    @if (session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.error("{{ session('error') }}"));</script>
    @endif
    @if (session('warning'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.warning("{{ session('warning') }}"));</script>
    @endif
    @if (session('info'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.info("{{ session('info') }}"));</script>
    @endif

    @stack('scripts')
    @livewireScripts
</body>
</html>