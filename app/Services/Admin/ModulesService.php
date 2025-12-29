<?php

namespace App\Services\Admin;

use App\Models\Module;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class ModulesService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Modules',
            'alias' => 'modules',
            'description' => 'Module management - install, uninstall, activate/deactivate',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 990,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */

    public static function menus(): array
    {
        return [
            [
            'menu_name' => 'Modules',
            'slug'  =>'modules',
            'icon' => 'cube',
            'route' => 'admin.modules.index',
            // 'permission' => 'modules.list.read',
            'actions' => ['read', 'create', 'edit', 'delete','update'],
            'section' => 'system',
            'sort_order' => 1,
            'children' => [],
            ],
        ];
    }
/*
    |--------------------------------------------------------------------------
    | Registration Handling
    |--------------------------------------------------------------------------
    */

     public static function register(): Module
    {
        return DB::transaction(function () {
            $config = self::config();
            $module = Module::updateOrCreate(
                ['alias' => $config['alias']],
                [
                    'name' => $config['name'],
                    'description' => $config['description'],
                    'version' => $config['version'],
                    'is_active' => true,
                    'is_installed' => true,
                    'is_core' => $config['is_core'] ?? false,
                    'sort_order' => $config['sort_order'] ?? 0,
                    'installed_at' => now(),
                ]
            );
            
            // ⚠️ This was missing - registers menus & permissions
            self::registerMenusAndPermissions($module);
            
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            CoreMenuService::clearCache();
            return $module;
        });
    }

    protected static function registerMenusAndPermissions(Module $module): void
    {
        $moduleAlias = self::config()['alias'];
        foreach (self::menus() as $menuData) {
            self::createMenuWithPermissions($menuData, $module, $moduleAlias, null);
        }
    }

    protected static function createMenuWithPermissions(array $menuData, Module $module, string $moduleAlias, ?int $parentId): Menu
    {
        $permissionName = "{$moduleAlias}.{$menuData['slug']}.read";
        $menu = Menu::updateOrCreate(
            ['module_id' => $module->id, 'slug' => $menuData['slug']],
            [
                'parent_id' => $parentId,
                'menu_name' => $menuData['menu_name'],
                'icon' => $menuData['icon'] ?? null,
                'route' => $menuData['route'] ?? null,
                'category' => $menuData['category'] ?? 'core',
                'permission_name' => $permissionName,
                'menu_visibility' => 'Admin',
                'sort_order' => $menuData['sort_order'] ?? 0,
                'is_active' => true,
            ]
        );
        foreach ($menuData['actions'] ?? ['read'] as $action) {
            self::createPermission($moduleAlias, $menuData['slug'], $action, $module->id);
        }
        if (!empty($menuData['children'])) {
            foreach ($menuData['children'] as $childData) {
                $childData['category'] = $menuData['category'] ?? 'core';
                self::createMenuWithPermissions($childData, $module, $moduleAlias, $menu->id);
            }
        }
        return $menu;
    }

    protected static function createPermission(string $moduleAlias, string $menuSlug, string $action, int $moduleId): void
    {
        $permissionName = "{$moduleAlias}.{$menuSlug}.{$action}";
        $actionNames = ['read' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete', 'export' => 'Export', 'import' => 'Import'];
        Permission::updateOrCreate(
            ['name' => $permissionName, 'guard_name' => 'admin'],
            ['module_id' => $moduleId, 'action_name' => $actionNames[$action] ?? ucfirst($action), 'sort_order' => array_search($action, array_keys($actionNames)) ?: 0]
        );
    }

    public static function isRegistered(): bool
    {
        return Module::where('alias', self::config()['alias'])->exists();
    }

    public static function getModule(): ?Module
    {
        return Module::where('alias', self::config()['alias'])->first();
    }
}