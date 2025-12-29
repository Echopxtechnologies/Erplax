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
        
        try {
            $activeModules = \App\Models\Module::where('is_active', true)->orderBy('sort_order')->get();
        } catch (\Exception $e) {
            $activeModules = collect();
        }
        
        try {
            $notifications = $authUser 
                ? \App\Models\Notification::where('user_id', $authUser->id)
                    ->where('user_type', 'user')
                    ->latest('created_at')
                    ->take(10)
                    ->get() 
                : collect();
            $hasNotifications = $notifications->count() > 0;
            $notificationCount = $notifications->where('is_read', false)->count();
        } catch (\Exception $e) {
            $notifications = collect();
            $hasNotifications = false;
            $notificationCount = 0;
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
        html[data-theme="dark"] { background: #0c1222; }
        html[data-theme="light"] { background: #f0f9ff; }
        html { visibility: hidden; }
        html.ready { visibility: visible; }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-hover: #0284c7;
            --primary-light: #e0f2fe;
            --primary-dark: #0369a1;
            --primary-100: #e0f2fe;
            
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #06b6d4;
            --info-light: #ecfeff;
            
            --navbar-bg: #ffffff;
            --navbar-border: #e0f2fe;
            --navbar-text: #64748b;
            --navbar-shadow: 0 1px 3px rgba(14,165,233,0.08);
            
            --body-bg: #f0f9ff;
            --card-bg: #ffffff;
            --card-border: #e0f2fe;
            --card-shadow: 0 1px 3px rgba(14,165,233,0.06);
            
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --input-focus: #0ea5e9;
            --input-focus-ring: rgba(14,165,233,0.2);
            
            --navbar-height: 64px;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-xs: 11px;
            --font-sm: 12px;
            --font-base: 13px;
            --font-md: 14px;
            --font-lg: 16px;
            --font-xl: 18px;
            --font-2xl: 20px;
            
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 24px;
            
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
            --radius-xl: 12px;
            --radius-full: 9999px;
            
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(14,165,233,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(14,165,233,0.1);
            --shadow-xl: 0 20px 25px -5px rgba(14,165,233,0.1);
        }
        
        [data-theme="dark"] {
            --primary-light: rgba(14,165,233,0.15);
            --navbar-bg: #1e293b;
            --navbar-border: #334155;
            --navbar-text: #94a3b8;
            --body-bg: #0c1222;
            --card-bg: #1e293b;
            --card-border: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --input-bg: #334155;
            --input-border: #475569;
            --input-text: #f1f5f9;
            --success-light: rgba(16,185,129,0.15);
            --warning-light: rgba(245,158,11,0.15);
            --danger-light: rgba(239,68,68,0.15);
            --info-light: rgba(6,182,212,0.15);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-family);
            font-size: var(--font-base);
            background: var(--body-bg);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-hover); }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: var(--font-sm);
            font-weight: 500;
            border-radius: var(--radius-lg);
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(14,165,233,0.35); }
        .btn-primary:hover { background: var(--primary-hover); color: #fff; transform: translateY(-1px); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-light { background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); }
        .btn-outline { background: transparent; color: var(--primary); border: 1px solid var(--primary); }
        .btn-outline:hover { background: var(--primary); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: var(--font-xs); }
        .btn-lg { padding: 12px 24px; font-size: var(--font-md); }
        
        .card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-xl); box-shadow: var(--card-shadow); }
        .card-header { padding: var(--space-lg) var(--space-xl); border-bottom: 1px solid var(--card-border); display: flex; align-items: center; justify-content: space-between; }
        .card-body { padding: var(--space-xl); }
        .card-footer { padding: var(--space-lg) var(--space-xl); border-top: 1px solid var(--card-border); background: var(--body-bg); border-radius: 0 0 var(--radius-xl) var(--radius-xl); }
        .card-title { font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0; }
        
        .form-group { margin-bottom: var(--space-lg); }
        .form-label { display: block; font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); margin-bottom: var(--space-sm); }
        .form-label.required::after { content: ' *'; color: var(--danger); }
        .form-control { width: 100%; padding: 10px 14px; font-size: var(--font-base); color: var(--input-text); background: var(--input-bg); border: 1px solid var(--input-border); border-radius: var(--radius-lg); transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--input-focus); box-shadow: 0 0 0 4px var(--input-focus-ring); }
        .form-control::placeholder { color: var(--text-muted); }
        .form-error { font-size: var(--font-xs); color: var(--danger); margin-top: var(--space-xs); }
        select.form-control { cursor: pointer; }
        textarea.form-control { min-height: 100px; resize: vertical; }
        
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: var(--font-sm); }
        .table th, .table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--card-border); }
        .table th { font-weight: 600; color: var(--text-secondary); background: var(--body-bg); font-size: var(--font-xs); text-transform: uppercase; }
        .table tbody tr:hover { background: var(--body-bg); }
        
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; font-size: var(--font-xs); font-weight: 500; border-radius: var(--radius-full); }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger { background: var(--danger-light); color: var(--danger); }
        .badge-info { background: var(--info-light); color: var(--info); }
        
        /* ========== NAVBAR - PROPERLY CENTERED ========== */
        .top-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--navbar-height);
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            box-shadow: var(--navbar-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-xl);
            z-index: 1000;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            flex-shrink: 0;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: var(--font-lg);
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
        }
        .navbar-brand img { height: 36px; width: auto; }
        .navbar-brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: var(--radius-lg);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(14,165,233,0.35);
        }
        .navbar-brand-icon svg { width: 20px; height: 20px; color: #fff; }
        
        .navbar-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--navbar-text);
            font-size: var(--font-sm);
            font-weight: 500;
            border-radius: var(--radius-lg);
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link:hover { background: var(--primary-light); color: var(--primary); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); }
        .nav-link svg { width: 18px; height: 18px; }
        
        .nav-dropdown { position: relative; }
        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--navbar-text);
            font-size: var(--font-sm);
            font-weight: 500;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: transparent;
            white-space: nowrap;
        }
        .nav-dropdown-toggle:hover { background: var(--primary-light); color: var(--primary); }
        .nav-dropdown-toggle svg { width: 18px; height: 18px; }
        .nav-dropdown-toggle .arrow { width: 14px; height: 14px; transition: transform 0.2s; }
        .nav-dropdown.open .arrow { transform: rotate(180deg); }
        
        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            min-width: 200px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: var(--space-sm);
            margin-top: var(--space-xs);
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 100;
        }
        .nav-dropdown.open .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--text-secondary);
            font-size: var(--font-sm);
            border-radius: var(--radius-md);
            text-decoration: none;
        }
        .nav-dropdown-item:hover { background: var(--primary-light); color: var(--primary); }
        .nav-dropdown-item.active { background: var(--primary-light); color: var(--primary); }
        .nav-dropdown-item svg { width: 16px; height: 16px; }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        
        .navbar-btn {
            position: relative;
            width: 40px; height: 40px;
            border: none; background: transparent;
            border-radius: var(--radius-lg);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--navbar-text);
            transition: all 0.2s;
        }
        .navbar-btn:hover { background: var(--primary-light); color: var(--primary); }
        .navbar-btn svg { width: 20px; height: 20px; }
        
        .notif-count {
            position: absolute; top: 4px; right: 4px;
            min-width: 18px; height: 18px; padding: 0 5px;
            background: var(--danger); color: #fff;
            font-size: 10px; font-weight: 600;
            border-radius: var(--radius-full);
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--navbar-bg);
        }
        .notif-count.hidden { display: none; }
        
        .theme-toggle {
            width: 40px; height: 40px;
            border: none; background: transparent;
            border-radius: var(--radius-lg);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--navbar-text);
            transition: all 0.2s;
        }
        .theme-toggle:hover { background: var(--primary-light); color: var(--primary); }
        .theme-toggle svg { width: 20px; height: 20px; }
        .theme-toggle .icon-dark { display: none; }
        [data-theme="dark"] .theme-toggle .icon-light { display: none; }
        [data-theme="dark"] .theme-toggle .icon-dark { display: block; }
        
        .user-dropdown { position: relative; }
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px;
            background: transparent;
            border: none;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
        }
        .user-dropdown-toggle:hover { background: var(--primary-light); }
        .user-avatar {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: var(--radius-lg);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: var(--font-sm);
            overflow: hidden;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .dropdown-arrow { display: flex; align-items: center; }
        .dropdown-arrow svg { width: 16px; height: 16px; color: var(--text-muted); transition: transform 0.2s; }
        .user-dropdown.active .dropdown-arrow svg { transform: rotate(180deg); }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%; right: 0;
            width: 240px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: var(--space-sm);
            margin-top: var(--space-sm);
            opacity: 0; visibility: hidden;
            transform: translateY(8px);
            transition: all 0.2s;
            z-index: 100;
        }
        .user-dropdown.active .user-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .dropdown-header { padding: var(--space-md) var(--space-lg); border-bottom: 1px solid var(--card-border); margin-bottom: var(--space-sm); }
        .dropdown-header-name { font-weight: 600; color: var(--text-primary); font-size: var(--font-sm); }
        .dropdown-header-email { font-size: var(--font-xs); color: var(--text-muted); margin-top: 2px; }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--text-secondary);
            font-size: var(--font-sm);
            border-radius: var(--radius-md);
            text-decoration: none;
            border: none;
            background: transparent;
            width: 100%;
            cursor: pointer;
        }
        .dropdown-item:hover { background: var(--primary-light); color: var(--primary); }
        .dropdown-item svg { width: 16px; height: 16px; }
        .dropdown-item-danger { color: var(--danger); }
        .dropdown-item-danger:hover { background: var(--danger-light); color: var(--danger); }
        .dropdown-divider { height: 1px; background: var(--card-border); margin: var(--space-sm) 0; }
        
        .mobile-menu-toggle {
            display: none;
            width: 40px; height: 40px;
            border: none; background: transparent;
            border-radius: var(--radius-lg);
            cursor: pointer;
            align-items: center; justify-content: center;
            color: var(--navbar-text);
        }
        .mobile-menu-toggle:hover { background: var(--primary-light); color: var(--primary); }
        .mobile-menu-toggle svg { width: 22px; height: 22px; }
        
        .mobile-menu-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1098;
            opacity: 0; visibility: hidden;
            transition: all 0.3s;
        }
        .mobile-menu-overlay.active { opacity: 1; visibility: visible; }
        
        .mobile-menu {
            position: fixed;
            top: 0; left: 0;
            width: 300px; max-width: 85vw;
            height: 100vh;
            background: var(--card-bg);
            z-index: 1099;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex; flex-direction: column;
            box-shadow: var(--shadow-xl);
        }
        .mobile-menu.active { transform: translateX(0); }
        
        .mobile-menu-header {
            padding: var(--space-lg) var(--space-xl);
            border-bottom: 1px solid var(--card-border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .mobile-menu-close {
            width: 36px; height: 36px;
            border: none; background: var(--body-bg);
            border-radius: var(--radius-lg);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
        }
        .mobile-menu-close:hover { background: var(--danger-light); color: var(--danger); }
        .mobile-menu-close svg { width: 20px; height: 20px; }
        
        .mobile-menu-nav { flex: 1; overflow-y: auto; padding: var(--space-lg); }
        .mobile-nav-title {
            font-size: var(--font-xs);
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: var(--space-sm) var(--space-md);
            margin-top: var(--space-lg);
        }
        .mobile-nav-title:first-child { margin-top: 0; }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px var(--space-md);
            color: var(--text-secondary);
            font-size: var(--font-base);
            font-weight: 500;
            border-radius: var(--radius-lg);
            text-decoration: none;
        }
        .mobile-nav-link:hover { background: var(--primary-light); color: var(--primary); }
        .mobile-nav-link.active { background: var(--primary-light); color: var(--primary); }
        .mobile-nav-link svg { width: 20px; height: 20px; }
        
        /* ========== NOTIFICATION PANEL ========== */
        .notification-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1099;
            opacity: 0; visibility: hidden;
            transition: all 0.3s;
        }
        .notification-overlay.active { opacity: 1; visibility: visible; }

        .notification-panel {
            position: fixed; top: 0; right: 0;
            width: 380px; max-width: 100vw; height: 100vh;
            background: var(--card-bg);
            border-left: 1px solid var(--card-border);
            z-index: 1100;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex; flex-direction: column;
            box-shadow: var(--shadow-xl);
        }
        .notification-panel.active { transform: translateX(0); }

        .panel-header {
            padding: var(--space-lg) var(--space-xl);
            border-bottom: 1px solid var(--card-border);
            display: flex; justify-content: space-between; align-items: center;
            background: var(--primary-light);
        }
        .panel-header h3 {
            font-size: var(--font-lg); font-weight: 600; color: var(--text-primary); margin: 0;
            display: flex; align-items: center; gap: 10px;
        }
        .panel-header h3 svg { width: 22px; height: 22px; color: var(--primary); }
        .panel-close {
            width: 36px; height: 36px; border: none;
            background: var(--card-bg); border-radius: var(--radius-lg);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .panel-close:hover { background: var(--danger-light); }
        .panel-close svg { width: 18px; height: 18px; color: var(--text-secondary); }
        .panel-close:hover svg { color: var(--danger); }

        .notif-body { flex: 1; overflow-y: auto; padding: var(--space-lg); }
        .notif-empty { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .notif-empty svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
        .notif-empty p { font-size: var(--font-md); font-weight: 500; }
        .notif-empty span { font-size: var(--font-sm); display: block; margin-top: 4px; }

        .notif-item {
            display: flex; gap: 12px; padding: 14px;
            border-radius: var(--radius-xl); margin-bottom: 8px;
            background: var(--body-bg); cursor: pointer;
            position: relative; border: 1px solid transparent;
            transition: all 0.2s;
        }
        .notif-item:hover { background: var(--primary-light); border-color: var(--primary); }
        .notif-item-dot { width: 10px; height: 10px; border-radius: var(--radius-full); margin-top: 4px; flex-shrink: 0; }
        .notif-item-dot.info { background: var(--info); }
        .notif-item-dot.success { background: var(--success); }
        .notif-item-dot.warning { background: var(--warning); }
        .notif-item-dot.error { background: var(--danger); }
        
        .notif-item-content { flex: 1; min-width: 0; }
        .notif-item-title { font-size: var(--font-sm); font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0; }
        .notif-item-text { font-size: var(--font-sm); color: var(--text-secondary); margin: 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .notif-item-time { font-size: var(--font-xs); color: var(--text-muted); margin-top: 6px; display: flex; align-items: center; gap: 4px; }
        .notif-item-time svg { width: 12px; height: 12px; }
        
        .notif-item-delete {
            position: absolute; top: 10px; right: 10px;
            width: 24px; height: 24px; border: none;
            background: transparent; border-radius: var(--radius-md);
            cursor: pointer; opacity: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .notif-item:hover .notif-item-delete { opacity: 1; }
        .notif-item-delete:hover { background: var(--danger-light); }
        .notif-item-delete svg { width: 14px; height: 14px; color: var(--danger); }

        .notif-footer { padding: var(--space-lg) var(--space-xl); border-top: 1px solid var(--card-border); text-align: center; background: var(--body-bg); }
        .notif-clear-all {
            font-size: var(--font-sm); color: var(--danger);
            background: var(--danger-light); border: none; cursor: pointer;
            padding: 10px 20px; border-radius: var(--radius-lg); font-weight: 500;
        }
        .notif-clear-all:hover { background: var(--danger); color: #fff; }
        
        /* ========== MAIN CONTENT ========== */
        .main-content { margin-top: var(--navbar-height); min-height: calc(100vh - var(--navbar-height)); }
        .page-container { max-width: 1400px; margin: 0 auto; padding: var(--space-xl); }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-xl); flex-wrap: wrap; gap: var(--space-lg); }
        .page-title { font-size: var(--font-2xl); font-weight: 700; color: var(--text-primary); margin: 0; }
        .page-subtitle { font-size: var(--font-sm); color: var(--text-muted); margin-top: 4px; }
        .page-actions { display: flex; gap: var(--space-sm); flex-wrap: wrap; }
        
        /* ========== TOAST ========== */
        .toast-container { position: fixed; top: calc(var(--navbar-height) + 16px); right: 16px; z-index: 2000; display: flex; flex-direction: column; gap: 8px; }
        .toast {
            display: flex; align-items: center; gap: 12px;
            min-width: 300px; max-width: 420px; padding: 14px 16px;
            background: var(--card-bg); border: 1px solid var(--card-border);
            border-radius: var(--radius-xl); box-shadow: var(--shadow-xl);
            transform: translateX(120%); opacity: 0; transition: all 0.3s;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast.hide { transform: translateX(120%); opacity: 0; }
        .toast-icon { width: 22px; height: 22px; flex-shrink: 0; }
        .toast-title { font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); flex: 1; }
        .toast-close { width: 24px; height: 24px; border: none; background: transparent; border-radius: var(--radius-md); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); }
        .toast-close:hover { background: var(--body-bg); color: var(--text-primary); }
        .toast-close svg { width: 16px; height: 16px; }
        .toast-success { border-left: 4px solid var(--success); }
        .toast-success .toast-icon { color: var(--success); }
        .toast-error { border-left: 4px solid var(--danger); }
        .toast-error .toast-icon { color: var(--danger); }
        .toast-warning { border-left: 4px solid var(--warning); }
        .toast-warning .toast-icon { color: var(--warning); }
        .toast-info { border-left: 4px solid var(--info); }
        .toast-info .toast-icon { color: var(--info); }
        
        /* ========== UTILITIES ========== */
        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
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
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .navbar-center { display: none; }
            .mobile-menu-toggle { display: flex; }
            .page-container { padding: var(--space-lg); }
        }
        
        @media (max-width: 640px) {
            .top-navbar { padding: 0 var(--space-lg); }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-actions { width: 100%; }
            .toast-container { left: 16px; right: 16px; top: auto; bottom: 16px; }
            .toast { min-width: auto; max-width: 100%; }
            .notification-panel { width: 100%; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <a href="{{ route('client.dashboard') }}" class="navbar-brand">
               @if($companyLogo && Storage::disk('public')->exists($companyLogo))
                <img src="{{ Storage::url($companyLogo) }}" alt="{{ $companyName }}">
               @else
                <div class="navbar-brand-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                @endif
                <span>{{ $companyName }}</span>
            </a>
        </div>
        
        <div class="navbar-center">
            <nav class="navbar-menu">
                <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                
                @foreach($activeModules as $module)
                    @if(View::exists(strtolower($module->alias) . '::client-navbar'))
                        @include(strtolower($module->alias) . '::client-navbar')
                    @elseif(View::exists(strtolower($module->alias) . '::client.navbar'))
                        @include(strtolower($module->alias) . '::client.navbar')
                    @endif
                @endforeach

                
            </nav>
        </div>
        
        <div class="navbar-right">
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <svg class="icon-light" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg class="icon-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
            
            <button class="navbar-btn" onclick="toggleNotifications()" title="Notifications">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="notif-count {{ $notificationCount > 0 ? '' : 'hidden' }}" id="notifCount">{{ $notificationCount }}</span>
            </button>
            
            <div class="user-dropdown" id="userDropdown">
                <button class="user-dropdown-toggle" onclick="toggleUserMenu(event)">
                    <div class="user-avatar">
                        @if($authUser && $authUser->avatar)
                            <img src="{{ Storage::url($authUser->avatar) }}" alt="">
                        @else
                            {{ $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '?' }}
                        @endif
                    </div>
                    <div class="dropdown-arrow"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg></div>
                </button>
                <div class="user-dropdown-menu">
                    <div class="dropdown-header">
                        <div class="dropdown-header-name">{{ $authUser->name ?? 'Client' }}</div>
                        <div class="dropdown-header-email">{{ $authUser->email ?? '' }}</div>
                    </div>
                    <div class="dropdown-menu-items">
                        @if(Route::has('client.profile.show'))
                        <a href="{{ route('client.profile.show') }}" class="dropdown-item">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            My Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        <form method="POST" action="{{ route('client.logout') }}" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-danger">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="notification-overlay" id="notifOverlay" onclick="toggleNotifications()"></div>
    <div class="notification-panel" id="notifPanel">
        <div class="panel-header">
            <h3><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>Notifications</h3>
            <button class="panel-close" onclick="toggleNotifications()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="notif-body" id="notifBody">
            @if($hasNotifications)
                @foreach($notifications as $notification)
                    <div class="notif-item" data-id="{{ $notification->id }}" onclick="handleNotificationClick({{ $notification->id }}, '{{ $notification->url }}')">
                        <div class="notif-item-dot {{ $notification->type ?? 'info' }}"></div>
                        <div class="notif-item-content">
                            <p class="notif-item-title">{{ $notification->title }}</p>
                            @if($notification->message)<p class="notif-item-text">{{ $notification->message }}</p>@endif
                            <div class="notif-item-time"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <button class="notif-item-delete" onclick="event.stopPropagation(); deleteNotification({{ $notification->id }})"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                @endforeach
            @else
                <div class="notif-empty">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <p>No notifications</p>
                    <span>You're all caught up!</span>
                </div>
            @endif
        </div>
        @if($hasNotifications)
        <div class="notif-footer"><button class="notif-clear-all" onclick="clearAllNotifications()">Clear All Notifications</button></div>
        @endif
    </div>
    
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <span class="navbar-brand"><div class="navbar-brand-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>{{ $companyName }}</span>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <nav class="mobile-menu-nav">
            <div class="mobile-nav-title">Menu</div>
            <a href="{{ route('client.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>Dashboard</a>
            @foreach($activeModules as $module)
                @if(View::exists(strtolower($module->alias) . '::client-mobile-menu'))@include(strtolower($module->alias) . '::client-mobile-menu')@elseif(View::exists(strtolower($module->alias) . '::client.mobile-menu'))@include(strtolower($module->alias) . '::client.mobile-menu')@endif
            @endforeach
            <div class="mobile-nav-title">Account</div>
            @if(Route::has('client.profile.show'))<a href="{{ route('client.profile.show') }}" class="mobile-nav-link {{ request()->routeIs('client.profile*') ? 'active' : '' }}"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>My Profile</a>@endif
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="mobile-nav-link" style="color: var(--danger);"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>Logout</a>
        </nav>
    </div>
    
    <main class="main-content"><div class="page-container">{{ $slot }}</div></main>
    
    <div class="toast-container" id="toastContainer"></div>
    
    <script>
        document.documentElement.classList.add('ready');
        function toggleTheme() { const html = document.documentElement; const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'; html.setAttribute('data-theme', newTheme); localStorage.setItem('client-theme', newTheme); }
        function toggleMobileMenu() { document.getElementById('mobileMenu').classList.toggle('active'); document.getElementById('mobileMenuOverlay').classList.toggle('active'); }
        function toggleUserMenu(e) { e.stopPropagation(); document.getElementById('userDropdown').classList.toggle('active'); closeAllNavDropdowns(); }
        function toggleNavDropdown(el, e) { e.stopPropagation(); const wasOpen = el.classList.contains('open'); closeAllNavDropdowns(); if (!wasOpen) el.classList.add('open'); }
        function closeAllNavDropdowns() { document.querySelectorAll('.nav-dropdown').forEach(d => d.classList.remove('open')); }
        document.addEventListener('click', function(e) { const userDD = document.getElementById('userDropdown'); if (!userDD.contains(e.target)) userDD.classList.remove('active'); document.querySelectorAll('.nav-dropdown').forEach(d => { if (!d.contains(e.target)) d.classList.remove('open'); }); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { document.getElementById('userDropdown').classList.remove('active'); document.getElementById('mobileMenu').classList.remove('active'); document.getElementById('mobileMenuOverlay').classList.remove('active'); document.getElementById('notifPanel').classList.remove('active'); document.getElementById('notifOverlay').classList.remove('active'); closeAllNavDropdowns(); } });
        
        function toggleNotifications() { document.getElementById('notifPanel').classList.toggle('active'); document.getElementById('notifOverlay').classList.toggle('active'); }
        function handleNotificationClick(id, url) { deleteNotification(id, function() { if (url) window.location.href = url; }); }
        function deleteNotification(id, callback) { fetch(`/client/notifications/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } }).then(r => r.json()).then(data => { if (data.success) { const item = document.querySelector(`.notif-item[data-id="${id}"]`); if (item) item.remove(); updateNotificationCount(); if (callback) callback(); } }).catch(e => console.error('Error:', e)); }
        function clearAllNotifications() { if (!confirm('Clear all notifications?')) return; fetch('/client/notifications/clear-all', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } }).then(r => r.json()).then(data => { if (data.success) { document.getElementById('notifBody').innerHTML = '<div class="notif-empty"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg><p>No notifications</p><span>You\'re all caught up!</span></div>'; const footer = document.querySelector('.notif-footer'); if (footer) footer.remove(); updateNotificationCount(); Toast.success('All notifications cleared'); } }); }
        function updateNotificationCount() { const items = document.querySelectorAll('.notif-item'); const badge = document.getElementById('notifCount'); if (items.length > 0) { badge.textContent = items.length; badge.classList.remove('hidden'); } else { badge.classList.add('hidden'); } }
        
        const Toast = { container: null, init() { this.container = document.getElementById('toastContainer'); }, show(type, message, duration = 4000) { if (!this.container) this.init(); const toast = document.createElement('div'); toast.className = `toast toast-${type}`; const icons = { success: '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>', error: '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>', warning: '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>', info: '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' }; toast.innerHTML = `<svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">${icons[type]}</svg><span class="toast-title">${message}</span><button class="toast-close" onclick="Toast.dismiss(this.parentElement)"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>`; this.container.appendChild(toast); requestAnimationFrame(() => toast.classList.add('show')); toast.timer = setTimeout(() => this.dismiss(toast), duration); return toast; }, dismiss(toast) { if (!toast || toast.classList.contains('hide')) return; if (toast.timer) clearTimeout(toast.timer); toast.classList.remove('show'); toast.classList.add('hide'); setTimeout(() => toast.remove(), 300); }, success(msg) { return this.show('success', msg); }, error(msg) { return this.show('error', msg); }, warning(msg) { return this.show('warning', msg); }, info(msg) { return this.show('info', msg); } };
        document.addEventListener('DOMContentLoaded', () => Toast.init());
    </script>
    
    @if (session('success'))<script>document.addEventListener('DOMContentLoaded', () => Toast.success("{{ session('success') }}"));</script>@endif
    @if (session('error'))<script>document.addEventListener('DOMContentLoaded', () => Toast.error("{{ session('error') }}"));</script>@endif
    @if (session('warning'))<script>document.addEventListener('DOMContentLoaded', () => Toast.warning("{{ session('warning') }}"));</script>@endif
    @if (session('info'))<script>document.addEventListener('DOMContentLoaded', () => Toast.info("{{ session('info') }}"));</script>@endif

    @stack('scripts')
    @livewireScripts
</body>
</html>   