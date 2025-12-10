<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class CoreMenuService
{
    /**
     * Services to exclude from menu collection
     */
    protected static array $exclude = [
        'AdminService',
        'CoreMenuService',
    ];

    /**
     * Icon SVG paths
     */
    protected static array $icons = [
        // Navigation
        'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'layout-dashboard' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
        // Customers icon (add this)
        'customers' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        // Inventory & Products
        'cube' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'box' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'package' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'package-check' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'layers' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'file' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        
        // Warehouse & Location
        'warehouse' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z',
        'rack' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'truck' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
        
        // Categories & Organization
        'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
        'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'clipboard-list' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        
        // Reports & Charts
        'chart-bar' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'report' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        
        // System & Settings
        'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'mail' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        
        // Actions
        'plus' => 'M12 4v16m8-8H4',
        'minus' => 'M20 12H4',
        'check' => 'M5 13l4 4L19 7',
        'x' => 'M6 18L18 6M6 6l12 12',
        'refresh' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'transfer' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
        'arrow-right' => 'M17 8l4 4m0 0l-4 4m4-4H3',
        'return' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',
        'adjustment' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
    ];

    /*
    |--------------------------------------------------------------------------
    | Get All Menus
    |--------------------------------------------------------------------------
    */

    public static function getAllMenus(): Collection
    {
        $menus = collect();
        $servicesPath = app_path('Services/Admin');

        if (!File::isDirectory($servicesPath)) {
            return $menus;
        }

        $files = File::files($servicesPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $className = str_replace('.php', '', $filename);

            if (in_array($className, self::$exclude)) {
                continue;
            }

            if (!str_ends_with($className, 'Service')) {
                continue;
            }

            $fullClass = 'App\\Services\\Admin\\' . $className;

            if (class_exists($fullClass) && method_exists($fullClass, 'menu')) {
                $menu = $fullClass::menu();
                if (!empty($menu)) {
                    $menus->push($menu);
                }
            }
        }

        return $menus->sortBy('sort_order');
    }

    public static function getMenusBySection(string $section): Collection
    {
        return self::getAllMenus()->filter(function ($menu) use ($section) {
            return ($menu['section'] ?? '') === $section;
        });
    }

    public static function getSystemMenus(): Collection
    {
        return self::getMenusBySection('system');
    }

    public static function getCoreMenus(): Collection
    {
        return self::getMenusBySection('core');
    }

    /*
    |--------------------------------------------------------------------------
    | Render Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Render menus by section name
     */
    public static function renderMenusBySection(string $section): string
    {
        $html = '';
        $menus = self::getMenusBySection($section);

        foreach ($menus as $menu) {
            $html .= self::renderMenuItem($menu);
        }

        return $html;
    }

    /**
     * Render System section menus
     */
    public static function renderSystemMenu(): string
    {
        return self::renderMenusBySection('system');
    }

    /**
     * Render Core section menus
     */
    public static function renderCoreMenu(): string
    {
        return self::renderMenusBySection('core');
    }

    /**
     * Render a single menu item (with children support)
     */
    public static function renderMenuItem(array $menu): string
    {
        $type = $menu['type'] ?? 'link';

        // Panel type (Settings slide-out)
        if ($type === 'panel') {
            return self::renderPanelMenuItem($menu);
        }

        // Has children - render as dropdown
        if (!empty($menu['children'])) {
            return self::renderDropdownMenuItem($menu);
        }

        // Simple link
        $route = $menu['route'] ?? '#';
        $isActive = $route && request()->routeIs($route . '*') ? 'active' : '';
        $href = $route && \Route::has($route) ? route($route) : '#';
        $icon = self::getIcon($menu['icon'] ?? 'cube');

        return '<a href="' . $href . '" class="nav-item ' . $isActive . '">' . 
               $icon . 
               '<span>' . e($menu['title']) . '</span>' . 
               '</a>';
    }

    /**
     * Render dropdown menu item with children (supports nested submenus)
     */
    protected static function renderDropdownMenuItem(array $menu): string
    {
        $icon = self::getIcon($menu['icon'] ?? 'cube');
        $isOpen = self::isMenuActive($menu) ? 'open' : '';
        $isActive = self::isMenuActive($menu) ? 'active' : '';
        
        $html = '<div class="nav-item ' . $isActive . '" onclick="toggleSubmenu(this)" style="cursor: pointer;">';
        $html .= $icon;
        $html .= '<span>' . e($menu['title']) . '</span>';
        $html .= '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
        $html .= '</div>';
        
        $html .= '<div class="nav-submenu ' . $isOpen . '">';
        
        foreach ($menu['children'] as $child) {
            // Check if child has nested children
            if (!empty($child['children'])) {
                $html .= self::renderNestedSubmenu($child);
            } else {
                $childRoute = $child['route'] ?? '#';
                $childActive = $childRoute && request()->routeIs($childRoute . '*') ? 'active' : '';
                $childHref = $childRoute && \Route::has($childRoute) ? route($childRoute) : '#';
                $childIcon = isset($child['icon']) ? self::getIcon($child['icon'], 'width:16px;height:16px;') : '';
                
                $html .= '<a href="' . $childHref . '" class="nav-item ' . $childActive . '">';
                $html .= $childIcon;
                $html .= e($child['title']);
                $html .= '</a>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render nested submenu (2nd level dropdown)
     */
    protected static function renderNestedSubmenu(array $item): string
    {
        $icon = self::getIcon($item['icon'] ?? 'folder', 'width:16px;height:16px;');
        $isActive = self::isNestedMenuActive($item) ? 'active expanded' : '';
        $isOpen = self::isNestedMenuActive($item) ? 'open' : '';
        
        $html = '<div class="nav-item has-nested ' . $isActive . '" onclick="toggleNestedSubmenu(event, this)">';
        $html .= $icon;
        $html .= '<span>' . e($item['title']) . '</span>';
        $html .= '<svg class="chevron-nested" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';
        $html .= '</div>';
        
        $html .= '<div class="nav-nested-submenu ' . $isOpen . '">';
        
        foreach ($item['children'] as $child) {
            $childRoute = $child['route'] ?? '#';
            $childActive = $childRoute && request()->routeIs($childRoute . '*') ? 'active' : '';
            $childHref = $childRoute && \Route::has($childRoute) ? route($childRoute) : '#';
            $childIcon = isset($child['icon']) ? self::getIcon($child['icon'], 'width:16px;height:16px;') : '';
            
            $html .= '<a href="' . $childHref . '" class="nav-item ' . $childActive . '">';
            $html .= $childIcon;
            $html .= e($child['title']);
            $html .= '</a>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render panel menu item (opens slide-out)
     */
    protected static function renderPanelMenuItem(array $menu): string
    {
        $icon = self::getIcon($menu['icon'] ?? 'settings');

        return '<div class="nav-item" onclick="toggleSettingsPanel()" style="cursor: pointer;">' . 
               $icon . 
               '<span>' . e($menu['title']) . '</span>' . 
               '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M9 5l7 7-7 7"></path></svg>' .
               '</div>';
    }

    /**
     * Render Settings Panel content
     */
    public static function renderSettingsPanel(): string
    {
        $html = '';
        
        $menus = self::getAllMenus()->filter(function ($menu) {
            return ($menu['type'] ?? '') === 'panel';
        });

        foreach ($menus as $menu) {
            if (!empty($menu['children'])) {
                $html .= self::renderSettingsPanelItems($menu['children']);
            }
        }

        return $html;
    }

    protected static function renderSettingsPanelItems(array $items): string
    {
        $html = '';

        foreach ($items as $item) {
            if (!empty($item['children'])) {
                $html .= self::renderSettingsSubmenu($item);
            } else {
                $route = $item['route'] ?? '#';
                $isActive = $route && request()->routeIs($route) ? 'active' : '';
                $href = $route && \Route::has($route) ? route($route) : '#';
                $icon = self::getIcon($item['icon'] ?? 'settings');

                $html .= '<a href="' . $href . '" class="setup-nav-item ' . $isActive . '">' . 
                         $icon . 
                         '<span>' . e($item['title']) . '</span>' . 
                         '</a>';
            }
        }

        return $html;
    }

    protected static function renderSettingsSubmenu(array $item): string
    {
        $icon = self::getIcon($item['icon'] ?? 'shield');
        $arrow = '<svg class="arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';

        $html = '<div class="setup-nav-item" onclick="toggleSetupSubmenu(this)">' . 
                $icon . 
                '<span>' . e($item['title']) . '</span>' . 
                $arrow . 
                '</div>';
        $html .= '<div class="setup-submenu">';

        foreach ($item['children'] as $child) {
            $route = $child['route'] ?? '#';
            $isActive = $route && request()->routeIs($route) ? 'active' : '';
            $href = $route && \Route::has($route) ? route($route) : '#';

            $html .= '<a href="' . $href . '" class="setup-nav-item ' . $isActive . '">' . 
                     e($child['title']) . 
                     '</a>';
        }

        $html .= '</div>';

        return $html;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if menu or any of its children is active
     */
    protected static function isMenuActive(array $menu): bool
    {
        // Check main route
        if (!empty($menu['route']) && request()->routeIs($menu['route'] . '*')) {
            return true;
        }
        
        // Check children routes (including nested)
        if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if (!empty($child['route']) && request()->routeIs($child['route'] . '*')) {
                    return true;
                }
                // Check nested children
                if (!empty($child['children'])) {
                    foreach ($child['children'] as $grandchild) {
                        if (!empty($grandchild['route']) && request()->routeIs($grandchild['route'] . '*')) {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Check if nested menu item or its children is active
     */
    protected static function isNestedMenuActive(array $item): bool
    {
        if (!empty($item['route']) && request()->routeIs($item['route'] . '*')) {
            return true;
        }
        
        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if (!empty($child['route']) && request()->routeIs($child['route'] . '*')) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Get icon SVG
     */
    protected static function getIcon(string $name, string $style = ''): string
    {
        $path = self::$icons[$name] ?? self::$icons['cube'];
        $styleAttr = $style ? ' style="' . $style . '"' : '';
        
        // Settings icon needs circle
        if ($name === 'settings') {
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"' . $styleAttr . '>' .
                   '<path d="' . $path . '"></path>' .
                   '<circle cx="12" cy="12" r="3"></circle>' .
                   '</svg>';
        }

        return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"' . $styleAttr . '>' .
               '<path d="' . $path . '"></path>' .
               '</svg>';
    }

    /**
     * Check if a section has any menus
     */
    public static function hasMenusInSection(string $section): bool
    {
        return self::getMenusBySection($section)->count() > 0;
    }
}