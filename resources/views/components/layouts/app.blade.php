<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
        $companyName = \App\Models\Option::companyName();
        $companyLogo = \App\Models\Option::companyLogo();
        $companyFavicon = \App\Models\Option::companyFavicon();
    @endphp
    
    <title>{{ $companyName }}</title>
    
    @if($companyFavicon)
        <link rel="icon" href="{{ $companyFavicon }}">
    @endif
    
    <script>
        
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
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
    
    @php
        $authUser = Auth::user();
        
        // Notifications from custom table
        try {
            $notifications = $authUser 
                ? \App\Models\Notification::where('user_id', $authUser->id)
                    ->latest()
                    ->take(10)
                    ->get() 
                : collect();
            $hasNotifications = $notifications->count() > 0;
            $notificationCount = $notifications->count();
        } catch (\Exception $e) {
            $notifications = collect();
            $hasNotifications = false;
            $notificationCount = 0;
        }
        
        try {
            $activeModules = \App\Models\Module::where('is_active', true)->orderBy('sort_order')->get();
        } catch (\Exception $e) {
            $activeModules = collect();
        }
        
        $currentModule = null;
        foreach ($activeModules as $module) {
            $routePrefix = strtolower($module->alias);
            if (request()->routeIs($routePrefix . '.*') || request()->is('admin/' . $routePrefix . '*')) {
                $currentModule = $module;
                break;
            }
        }
        
        $currentPage = 'none';
        if (request()->routeIs('admin.dashboard')) {
            $currentPage = 'dashboard';
        } elseif (request()->routeIs('admin.settings.*')) {
            $currentPage = 'settings';
        } elseif (request()->routeIs('admin.modules.*')) {
            $currentPage = 'modules';
        } elseif ($currentModule) {
            $currentPage = 'module';
        }
    @endphp
<style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --primary-light: #eff6ff;
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
            --sidebar-active-bg: #3b82f6;
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
            
            --primary-light: rgba(59,130,246,0.15);
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
        }
        .btn svg { width: 14px; height: 14px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #16a34a; color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #d97706; color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; color: #fff; }
        .btn-light { background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); }
        .btn-light:hover { background: var(--body-bg); color: var(--text-primary); }
        .btn-xs { padding: 4px 8px; font-size: var(--font-xs); }
        .btn-sm { padding: 5px 10px; font-size: var(--font-sm); }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
        }
        .card-header { padding: var(--space-lg); border-bottom: 1px solid var(--card-border); }
        .card-body { padding: var(--space-lg); }
        .card-title { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        
        /* Forms */
        .form-label { display: block; font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); margin-bottom: var(--space-xs); }
        .form-control { width: 100%; padding: 8px 12px; font-size: var(--font-base); background: var(--input-bg); border: 1px solid var(--input-border); border-radius: var(--radius-md); color: var(--input-text); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; font-size: var(--font-xs); font-weight: 600; border-radius: var(--radius-sm); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        .badge-info { background: var(--primary-light); color: var(--primary); }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        
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
        
        .navbar-static-menu {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: var(--primary-light); color: var(--primary);
            border-radius: var(--radius-md);
            font-size: var(--font-sm); font-weight: 600;
        }
        .navbar-static-menu svg { width: 16px; height: 16px; }
        
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
        
        .notif-dot {
            position: absolute; top: 8px; right: 8px;
            width: 8px; height: 8px;
            background: var(--danger); border-radius: 50%;
            border: 2px solid var(--navbar-bg);
        }
        .notif-dot.hidden { display: none; }
        
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
        }
        .dropdown-item:hover { background: var(--body-bg); color: var(--text-primary); }
        .dropdown-item svg { width: 16px; height: 16px; }
        .dropdown-item-danger { color: var(--danger); }
        .dropdown-item-danger:hover { background: var(--danger-light); color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--card-border); margin: var(--space-sm) 0; }
        
        /* Notification Panel */
        .notification-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 1100;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .notification-overlay.active { opacity: 1; visibility: visible; }
        
        .notification-panel {
            position: fixed; top: 0; right: 0;
            width: 320px; max-width: 100%; height: 100vh;
            background: var(--card-bg); z-index: 1101;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex; flex-direction: column;
        }
        .notification-panel.active { transform: translateX(0); }
        
        .panel-header {
            padding: 14px var(--space-lg);
            border-bottom: 1px solid var(--card-border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .panel-header h3 { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        .panel-close {
            width: 28px; height: 28px;
            border: none; background: var(--body-bg);
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .panel-close:hover { background: var(--card-border); }
        .panel-close svg { width: 16px; height: 16px; color: var(--text-secondary); }
        
        .notif-body { flex: 1; overflow-y: auto; padding: var(--space-md); }
        .notif-empty { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .notif-empty svg { width: 32px; height: 32px; margin-bottom: 8px; }
        
        .notif-item {
            display: flex; gap: 10px; padding: 10px;
            border-radius: var(--radius-md); margin-bottom: 6px;
            background: var(--body-bg); cursor: pointer;
            text-decoration: none;
            position: relative;
        }
        .notif-item:hover { background: var(--card-border); }
        .notif-item-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
        .notif-item-dot.info { background: var(--primary); }
        .notif-item-dot.success { background: var(--success); }
        .notif-item-dot.warning { background: var(--warning); }
        .notif-item-dot.error { background: var(--danger); }
        .notif-item-content { flex: 1; min-width: 0; }
        .notif-item-title { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); margin: 0 0 2px 0; }
        .notif-item-text { font-size: var(--font-sm); color: var(--text-secondary); margin: 0; line-height: 1.4; }
        .notif-item-time { font-size: var(--font-xs); color: var(--text-muted); margin-top: 4px; }
        .notif-item-delete {
            position: absolute; top: 8px; right: 8px;
            width: 20px; height: 20px;
            border: none; background: transparent;
            border-radius: var(--radius-sm);
            cursor: pointer; opacity: 0;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.2s;
        }
        .notif-item:hover .notif-item-delete { opacity: 1; }
        .notif-item-delete:hover { background: var(--danger-light); }
        .notif-item-delete svg { width: 14px; height: 14px; color: var(--danger); }
        
        .notif-footer {
            padding: var(--space-md);
            border-top: 1px solid var(--card-border);
            text-align: center;
        }
        .notif-clear-all {
            font-size: var(--font-sm);
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: var(--radius-md);
        }
        .notif-clear-all:hover { background: var(--danger-light); }
        
        /* ========== SETUP PANEL (Slide-out from left) ========== */
        .setup-panel {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--card-bg);
            border-right: 1px solid var(--card-border);
            z-index: 1102;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-lg);
        }
        .setup-panel.active { transform: translateX(0); }
        
        .setup-header {
            padding: 14px var(--space-lg);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .setup-header h3 {
            font-size: var(--font-md);
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }
        .setup-close {
            width: 28px; height: 28px;
            border: none;
            background: transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .setup-close:hover { background: var(--body-bg); }
        .setup-close svg { width: 18px; height: 18px; color: var(--text-secondary); }
        
        .setup-body {
            flex: 1;
            overflow-y: auto;
            padding: var(--space-md);
        }
        
        .setup-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: var(--font-base);
            border-radius: var(--radius-md);
            margin-bottom: 2px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .setup-nav-item:hover {
            background: var(--body-bg);
            color: var(--text-primary);
        }
        .setup-nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        .setup-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .setup-nav-item span { flex: 1; }
        .setup-nav-item .arrow {
            width: 16px; height: 16px;
            transition: transform 0.2s;
        }
        .setup-nav-item.open .arrow { transform: rotate(180deg); }
        
        .setup-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding-left: 28px;
        }
        .setup-submenu.open { max-height: 200px; }
        
        .setup-submenu-item {
            display: block;
            padding: 8px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: var(--font-sm);
            border-radius: var(--radius-md);
            margin-bottom: 2px;
        }
        .setup-submenu-item:hover {
            background: var(--body-bg);
            color: var(--text-primary);
        }
        .setup-submenu-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .setup-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.3); z-index: 1101;
            opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .setup-overlay.active { opacity: 1; visibility: visible; }
        
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
            transition: transform 0.3s ease, width 0.3s ease;
            z-index: 1000;
            /* Modern Scrollbar - Firefox */
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }
        .sidebar:hover {
            scrollbar-color: var(--sidebar-border) transparent;
        }
        .sidebar.hidden { transform: translateX(-100%); }
        
        /* Modern Scrollbar - Webkit (Chrome, Safari, Edge) */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 3px;
            transition: background 0.2s;
        }
        .sidebar:hover::-webkit-scrollbar-thumb {
            background: var(--sidebar-border);
        }
        .sidebar:hover::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        
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
        .sidebar-user-name { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); }
        .sidebar-user-email { font-size: var(--font-xs); color: var(--text-muted); }
        
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
        .nav-item .chevron { width: 16px; height: 16px; transition: transform 0.2s; }
        .nav-item.open .chevron { transform: rotate(90deg); }
        
        .nav-submenu {
            max-height: 0; 
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding-left: 28px;
        }
        .nav-submenu.open { max-height: 9999px; }
        
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
            position: fixed; bottom: 20px; right: 20px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
        }
        .toast {
            min-width: 300px; max-width: 400px;
            background: var(--card-bg); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg); padding: 14px 16px;
            display: flex; align-items: flex-start; gap: 12px;
            transform: translateX(120%); opacity: 0;
            transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast.hide { transform: translateX(120%); opacity: 0; }
        .toast-icon { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
        .toast-success .toast-icon { color: var(--success); }
        .toast-error .toast-icon { color: var(--danger); }
        .toast-warning .toast-icon { color: var(--warning); }
        .toast-info .toast-icon { color: var(--primary); }
        .toast-title { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); margin: 0; }
        .toast-message { font-size: var(--font-sm); color: var(--text-secondary); margin: 4px 0 0; }
        .toast-close {
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--text-muted); display: flex;
        }
        .toast-close svg { width: 16px; height: 16px; }
        .toast-close:hover { color: var(--text-primary); }
        .toast-progress {
            position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
            transform-origin: left;
        }
        .toast-success .toast-progress { background: var(--success); }
        .toast-error .toast-progress { background: var(--danger); }
        .toast-warning .toast-progress { background: var(--warning); }
        .toast-info .toast-progress { background: var(--primary); }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .navbar-center { display: none; }
            .user-name { display: none; }
        }
        
        /* Nested Submenu Styles */
        .nav-item.has-nested {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-item.has-nested span {
            flex: 1;
        }
        .chevron-nested {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
            margin-left: auto;
        }
        .nav-item.has-nested.expanded .chevron-nested {
            transform: rotate(180deg);
        }
        .nav-nested-submenu {
            display: none;
            padding-left: 20px;
            border-left: 2px solid var(--card-border);
            margin-left: 16px;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        .nav-nested-submenu.open {
            display: block;
        }
        .nav-nested-submenu .nav-item {
            padding: 8px 12px;
            font-size: 13px;
            border-radius: 6px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-nested-submenu .nav-item:hover {
            background: var(--body-bg);
            color: var(--text-primary);
        }
        .nav-nested-submenu .nav-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
        }
        .nav-nested-submenu .nav-item svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        
        /* ========== Modern Scrollbar for Panels ========== */
        .notif-body::-webkit-scrollbar,
        .setup-body::-webkit-scrollbar {
            width: 4px;
        }
        .notif-body::-webkit-scrollbar-track,
        .setup-body::-webkit-scrollbar-track {
            background: transparent;
        }
        .notif-body::-webkit-scrollbar-thumb,
        .setup-body::-webkit-scrollbar-thumb {
            background: var(--card-border);
            border-radius: 2px;
        }
        .notif-body::-webkit-scrollbar-thumb:hover,
        .setup-body::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        .notif-body,
        .setup-body {
            scrollbar-width: thin;
            scrollbar-color: var(--card-border) transparent;
        }
     @media (max-width: 768px) {    
    /* Toast Container - Bottom center like Android snackbar */
    .toast-container {
        top: auto;
        bottom: 20px;
        left: 16px;
        right: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    
    /* Toast - Compact pill style */
    .toast {
        min-width: auto;
        max-width: 100%;
        width: auto;
        padding: 10px 16px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        gap: 10px;
        align-items: center;
    }
    
    /* Hide progress bar on mobile - cleaner look */
    .toast .toast-progress {
        display: none;
    }
    
    /* Smaller icon */
    .toast .toast-icon {
        width: 18px;
        height: 18px;
        margin: 0;
    }
    
    /* Single line text */
    .toast .toast-title {
        font-size: 13px;
        font-weight: 500;
    }
    
    /* Hide detailed message on mobile */
    .toast .toast-message {
        display: none;
    }
    
    /* Compact close button */
    .toast .toast-close {
        padding: 2px;
        margin-left: 4px;
    }
    .toast .toast-close svg {
        width: 14px;
        height: 14px;
    }
    
    /* Animation - Slide up from bottom */
    .toast {
        transform: translateY(100px);
        opacity: 0;
    }
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .toast.hide {
        transform: translateY(100px);
        opacity: 0;
    }
    
    /* Color variants - Solid background for better visibility */
    .toast-success {
        background: var(--success);
        color: #fff;
    }
    .toast-success .toast-icon,
    .toast-success .toast-title,
    .toast-success .toast-close {
        color: #fff;
    }
    
    .toast-error {
        background: var(--danger);
        color: #fff;
    }
    .toast-error .toast-icon,
    .toast-error .toast-title,
    .toast-error .toast-close {
        color: #fff;
    }
    
    .toast-warning {
        background: var(--warning);
        color: #fff;
    }
    .toast-warning .toast-icon,
    .toast-warning .toast-title,
    .toast-warning .toast-close {
        color: #fff;
    }
    
    .toast-info {
        background: var(--primary);
        color: #fff;
    }
    .toast-info .toast-icon,
    .toast-info .toast-title,
    .toast-info .toast-close {
        color: #fff;
    }
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
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $companyName }}" style="height:32px;">
                @else
                    <div class="navbar-brand-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                @endif
                <span>{{ $companyName }}</span>
            </a>
        </div>
        
        <div class="navbar-center">
            @if($currentModule)
                <div class="navbar-static-menu">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    {{ $currentModule->name }}
                </div>
            @endif
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
            
            <button class="navbar-btn" onclick="toggleNotifications()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="notif-count {{ $hasNotifications ? '' : 'hidden' }}" id="notifCount">{{ $notificationCount }}</span>
            </button>
            
            <div class="user-dropdown" id="userDropdown">
                <button class="user-dropdown-toggle" onclick="toggleUserMenu(event)">
                    <div class="user-avatar">{{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}</div>
                    <span class="user-name">{{ $authUser?->name ?? 'User' }}</span>
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
                        @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                            @csrf
                            <a href="#" class="dropdown-item dropdown-item-danger" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Notification Panel -->
    <div class="notification-overlay" id="notifOverlay" onclick="toggleNotifications()"></div>
    <div class="notification-panel" id="notifPanel">
        <div class="panel-header">
            <h3>Notifications</h3>
            <button class="panel-close" onclick="toggleNotifications()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="notif-body" id="notifBody">
            @if($hasNotifications)
                @foreach($notifications as $notification)
                    <div class="notif-item" data-id="{{ $notification->id }}" onclick="handleNotificationClick({{ $notification->id }}, '{{ $notification->url }}')">
                        <div class="notif-item-dot {{ $notification->type ?? 'info' }}"></div>
                        <div class="notif-item-content">
                            <p class="notif-item-title">{{ $notification->title }}</p>
                            @if($notification->message)
                                <p class="notif-item-text">{{ $notification->message }}</p>
                            @endif
                            <div class="notif-item-time">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <button class="notif-item-delete" onclick="event.stopPropagation(); deleteNotification({{ $notification->id }})">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            @else
                <div class="notif-empty">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p>No notifications</p>
                </div>
            @endif
        </div>
        @if($hasNotifications)
        <div class="notif-footer">
            <button class="notif-clear-all" onclick="clearAllNotifications()">Clear All</button>
        </div>
        @endif
    </div>
    
    <!-- Settings Panel (Slide-out) - DYNAMIC -->
    <div class="setup-overlay" id="setupOverlay" onclick="toggleSettingsPanel()"></div>
    <div class="setup-panel" id="setupPanel">
        <div class="setup-header">
            <h3>Settings</h3>
            <button class="setup-close" onclick="toggleSettingsPanel()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="setup-body">
            {!! \App\Services\Admin\CoreMenuService::renderSettingsPanel() !!}
        </div>
    </div>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}</div>
            <div>
                <div class="sidebar-user-name">{{ $authUser?->name ?? 'User' }}</div>
                <div class="sidebar-user-email">{{ $authUser?->email ?? '' }}</div>
            </div>
        </div>
        
<nav class="sidebar-nav">
    <div class="sidebar-nav-title">Menu</div>
    
    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span>Dashboard</span>
    </a>

    {{-- ========== CORE SECTION ========== --}}
    {!! \App\Services\Admin\CoreMenuService::renderCoreMenu() !!}

    {{-- ========== MODULES SECTION ========== --}}
    @php
        $nonCoreModules = $activeModules->filter(fn($m) => !$m->is_core)->sortBy('sort_order');
    @endphp
    
    @if($nonCoreModules->count() > 0)
        <div class="sidebar-nav-title">Modules</div>
        
        @foreach($nonCoreModules as $module)
            @if(View::exists(strtolower($module->alias) . '::sidebar'))
                @include(strtolower($module->alias) . '::sidebar')
            @elseif(View::exists(strtolower($module->alias) . '::menu'))
                @include(strtolower($module->alias) . '::menu')
            @endif
        @endforeach
    @endif
    
    {{-- ========== SYSTEM SECTION ========== --}}
    <div class="sidebar-nav-title">System</div>
    {!! \App\Services\Admin\CoreMenuService::renderSystemMenu() !!}
    
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
            localStorage.setItem('theme', newTheme);
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
                localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden'));
            }
        }
        
        function toggleUserMenu(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('active');
        }
        
        function toggleNotifications() {
            document.getElementById('notifPanel').classList.toggle('active');
            document.getElementById('notifOverlay').classList.toggle('active');
        }
        
        function toggleNestedSubmenu(event, element) {
            event.stopPropagation(); // Prevent parent submenu from closing
            
            element.classList.toggle('expanded');
            
            // Find next sibling with class nav-nested-submenu
            let nestedSubmenu = element.nextElementSibling;
            if (nestedSubmenu && nestedSubmenu.classList.contains('nav-nested-submenu')) {
                nestedSubmenu.classList.toggle('open');
            }
        }
        //customer drop down 
        function toggleSubmenu(el) {
            el.classList.toggle('open');
            const submenu = el.nextElementSibling;
            if (submenu && submenu.classList.contains('nav-submenu')) {
                submenu.classList.toggle('open');
            }
        }
        // Settings Panel Functions
        function toggleSettingsPanel() {
            document.getElementById('setupPanel').classList.toggle('active');
            document.getElementById('setupOverlay').classList.toggle('active');
        }

        // permsiioon roles drop down menu 
        function toggleSetupSubmenu(el) {
        el.classList.toggle('open');
        const submenu = el.nextElementSibling;
        if (submenu && submenu.classList.contains('setup-submenu')) {
            submenu.classList.toggle('open');
        }
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (!dd.contains(e.target)) dd.classList.remove('active');
        });
        
        // Escape key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('notifPanel').classList.remove('active');
                document.getElementById('notifOverlay').classList.remove('active');
                document.getElementById('setupPanel').classList.remove('active');
                document.getElementById('setupOverlay').classList.remove('active');
                document.getElementById('userDropdown').classList.remove('active');
            }
        });
        
        // Load sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth > 1024 && localStorage.getItem('sidebarHidden') === 'true') {
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
        
        // ========== NOTIFICATION FUNCTIONS ==========
        
        // Handle notification click - delete and redirect
        function handleNotificationClick(id, url) {
            deleteNotification(id, function() {
                if (url) {
                    window.location.href = url;
                }
            });
        }
        
        // Delete single notification
        function deleteNotification(id, callback) {
            fetch(`/admin/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from DOM
                    const item = document.querySelector(`.notif-item[data-id="${id}"]`);
                    if (item) item.remove();
                    
                    // Update count
                    updateNotificationCount();
                    
                    // Callback (for redirect)
                    if (callback) callback();
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Clear all notifications
        function clearAllNotifications() {
            if (!confirm('Clear all notifications?')) return;
            
            fetch('/admin/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('notifBody').innerHTML = `
                        <div class="notif-empty">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p>No notifications</p>
                        </div>
                    `;
                    // Remove footer
                    const footer = document.querySelector('.notif-footer');
                    if (footer) footer.remove();
                    
                    // Update count
                    updateNotificationCount();
                    
                    Toast.success('All notifications cleared');
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Update notification count badge
        function updateNotificationCount() {
            const items = document.querySelectorAll('.notif-item');
            const count = items.length;
            const badge = document.getElementById('notifCount');
            
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        
        // Toast System
        const Toast = {
            container: null,
            init() { this.container = document.getElementById('toastContainer'); },
            show(type, message, title = null, duration = 5000) {
                if (!this.container) this.init();
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                const icons = {
                    success: '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    error: '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    warning: '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                    info: '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                };
                const titles = { success: 'Success', error: 'Error', warning: 'Warning', info: 'Info' };
                toast.innerHTML = `
                    <svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">${icons[type]}</svg>
                    <div style="flex:1"><p class="toast-title">${title || titles[type]}</p><p class="toast-message">${message}</p></div>
                    <button class="toast-close" onclick="Toast.dismiss(this.parentElement)"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <div class="toast-progress"></div>
                `;
                this.container.appendChild(toast);
                requestAnimationFrame(() => toast.classList.add('show'));
                const progress = toast.querySelector('.toast-progress');
                progress.style.transition = `transform ${duration}ms linear`;
                requestAnimationFrame(() => progress.style.transform = 'scaleX(0)');
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
            success(msg, title) { return this.show('success', msg, title); },
            error(msg, title) { return this.show('error', msg, title); },
            warning(msg, title) { return this.show('warning', msg, title); },
            info(msg, title) { return this.show('info', msg, title); }
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