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
        'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'cube' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'settings' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        'mail' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    ];

    /*
    |--------------------------------------------------------------------------
    | Get All Core Menus
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

    /*
    |--------------------------------------------------------------------------
    | Render Methods
    |--------------------------------------------------------------------------
    */

    public static function renderSystemMenu(): string
    {
        $html = '';
        $menus = self::getSystemMenus();

        foreach ($menus as $menu) {
            $html .= self::renderMenuItem($menu);
        }

        return $html;
    }

    public static function renderMenuItem(array $menu): string
    {
        $type = $menu['type'] ?? 'link';

        if ($type === 'panel') {
            return self::renderPanelMenuItem($menu);
        }

        $route = $menu['route'] ?? '#';
        $isActive = $route && request()->routeIs($route . '*') ? 'active' : '';
        $href = $route && \Route::has($route) ? route($route) : '#';
        $icon = self::getIcon($menu['icon'] ?? 'cube');

        return '<a href="' . $href . '" class="nav-item ' . $isActive . '">' . $icon . '<span>' . e($menu['title']) . '</span></a>';
    }

    protected static function renderPanelMenuItem(array $menu): string
    {
        $icon = self::getIcon($menu['icon'] ?? 'settings');

        return '<div class="nav-item" onclick="toggleSettingsPanel()" style="cursor: pointer;">' . 
               $icon . 
               '<span>' . e($menu['title']) . '</span>' . 
               '<svg class="chevron" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M9 5l7 7-7 7"></path></svg>' .
               '</div>';
    }

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

                $html .= '<a href="' . $href . '" class="setup-nav-item ' . $isActive . '">' . $icon . '<span>' . e($item['title']) . '</span></a>';
            }
        }

        return $html;
    }

    protected static function renderSettingsSubmenu(array $item): string
    {
        $icon = self::getIcon($item['icon'] ?? 'shield');
        $arrow = '<svg class="arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>';

        $html = '<div class="setup-nav-item" onclick="toggleSetupSubmenu(this)">' . $icon . '<span>' . e($item['title']) . '</span>' . $arrow . '</div>';
        $html .= '<div class="setup-submenu">';

        foreach ($item['children'] as $child) {
            $route = $child['route'] ?? '#';
            $isActive = $route && request()->routeIs($route) ? 'active' : '';
            $href = $route && \Route::has($route) ? route($route) : '#';

            $html .= '<a href="' . $href . '" class="setup-nav-item ' . $isActive . '">' . e($child['title']) . '</a>';
        }

        $html .= '</div>';

        return $html;
    }

    protected static function getIcon(string $name, string $style = ''): string
    {
        $path = self::$icons[$name] ?? self::$icons['cube'];
        $styleAttr = $style ? ' style="' . $style . '"' : '';
        
        if ($name === 'settings') {
            return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"' . $styleAttr . '><path d="' . $path . '"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        }

        return '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"' . $styleAttr . '><path d="' . $path . '"></path></svg>';
    }
}