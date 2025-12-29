<?php

namespace App\Services\Admin;

use App\Models\Module;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Settings',
            'alias' => 'settings',
            'description' => 'System settings and configuration',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 995,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Menus & Permissions
    | 
    | Permission Format: {module_alias}.{menu_slug}.{action}
    | Example: settings.general.read, settings.taxes.create
    |
    | category: 'settings' = Shows in Settings Panel (slide-out)
    |           'core'     = Shows in main sidebar
    |           'system'   = Shows in System section
    |--------------------------------------------------------------------------
    */

    public static function menus(): array
    {
        return [
            // General Settings
            [
                'menu_name' => 'General',
                'slug' => 'general',
                'icon' => 'settings',
                'route' => 'admin.settings.general',
                'category' => 'settings',
                'sort_order' => 1,
                'actions' => ['read', 'edit'],
            ],

            // Email Settings
            [
                'menu_name' => 'Email',
                'slug' => 'email',
                'icon' => 'mail',
                'route' => 'admin.settings.email',
                'category' => 'settings',
                'sort_order' => 2,
                'actions' => ['read', 'edit'],
            ],

            // Cron Jobs
            [
                'menu_name' => 'Cron Jobs',
                'slug' => 'cronjob',
                'icon' => 'clock',
                'route' => 'admin.cronjob.index',
                'category' => 'settings',
                'sort_order' => 3,
                'actions' => ['read', 'create', 'edit', 'delete','update'],
            ],

            // Localization - Parent with children
            [
                'menu_name' => 'Localization',
                'slug' => 'localization',
                'icon' => 'globe',
                'route' => null, // null = dropdown parent
                'category' => 'settings',
                'sort_order' => 4,
                'actions' => ['read'],
                'children' => [
                    [
                        'menu_name' => 'Countries',
                        'slug' => 'countries',
                        'icon' => 'flag',
                        'route' => 'admin.settings.countries.index',
                        'sort_order' => 1,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Timezones',
                        'slug' => 'timezones',
                        'icon' => 'clock',
                        'route' => 'admin.settings.timezones.index',
                        'sort_order' => 2,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Currencies',
                        'slug' => 'currencies',
                        'icon' => 'currency',
                        'route' => 'admin.settings.currencies.index',
                        'sort_order' => 3,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                ],
            ],

            // Finances - Parent with children
            [
                'menu_name' => 'Finances',
                'slug' => 'finances',
                'icon' => 'banknotes',
                'route' => null,
                'category' => 'settings',
                'sort_order' => 5,
                'actions' => ['read'],
                'children' => [
                    [
                        'menu_name' => 'Tax Management',
                        'slug' => 'taxes',
                        'icon' => 'receipt',
                        'route' => 'admin.settings.taxes.index',
                        'sort_order' => 1,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Payment Methods',
                        'slug' => 'payment-methods',
                        'icon' => 'credit-card',
                        'route' => 'admin.settings.payment-methods.index',
                        'sort_order' => 2,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Banks',
                        'slug' => 'banks',
                        'icon' => 'building',
                        'route' => 'admin.settings.banks.index',
                        'sort_order' => 3,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                ],
            ],

            // Roles & Permissions - Parent with children
            [
                'menu_name' => 'Roles/Permission',
                'slug' => 'roles-permission',
                'icon' => 'shield',
                'route' => null,
                'category' => 'settings',
                'sort_order' => 6,
                'actions' => ['read'],
                'children' => [
                    [
                        'menu_name' => 'Permissions',
                        'slug' => 'permissions',
                        'icon' => 'key',
                        'route' => 'admin.settings.permissions.index',
                        'sort_order' => 1,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Roles',
                        'slug' => 'roles',
                        'icon' => 'shield',
                        'route' => 'admin.settings.roles.index',
                        'sort_order' => 2,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Staff',
                        'slug' => 'staff',
                        'icon' => 'user',
                        'route' => 'admin.settings.users.index',
                        'sort_order' => 3,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                    [
                        'menu_name' => 'Clients',
                        'slug' => 'clients',
                        'icon' => 'users',
                        'route' => 'admin.settings.client.index',
                        'sort_order' => 4,
                        'actions' => ['read', 'create', 'edit', 'delete','update'],
                    ],
                ],
            ],

            // System Info
            [
                'menu_name' => 'System Info',
                'slug' => 'system-info',
                'icon' => 'server',
                'route' => 'admin.settings.system-info',
                'category' => 'settings',
                'sort_order' => 7,
                'actions' => ['read'],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Register Module + Menus + Permissions to Database
    |--------------------------------------------------------------------------
    */

    public static function register(): Module
    {
        return DB::transaction(function () {
            // 1. Create/Update Module
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

            // 2. Create Menus & Permissions
            self::registerMenusAndPermissions($module);

            // 3. Clear caches
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            CoreMenuService::clearCache();

            return $module;
        });
    }

    /**
     * Register all menus and their permissions
     */
    protected static function registerMenusAndPermissions(Module $module): void
    {
        $moduleAlias = self::config()['alias'];

        foreach (self::menus() as $menuData) {
            self::createMenuWithPermissions($menuData, $module, $moduleAlias, null);
        }
    }

    /**
     * Create a single menu with its permissions (recursive for children)
     */
    protected static function createMenuWithPermissions(array $menuData, Module $module, string $moduleAlias, ?int $parentId): Menu
    {
        // Permission name for this menu
        $permissionName = "{$moduleAlias}.{$menuData['slug']}.read";

        // Create/Update Menu
        $menu = Menu::updateOrCreate(
            ['module_id' => $module->id, 'slug' => $menuData['slug']],
            [
                'parent_id' => $parentId,
                'menu_name' => $menuData['menu_name'],
                'icon' => $menuData['icon'] ?? null,
                'route' => $menuData['route'] ?? null,
                'category' => $menuData['category'] ?? 'settings',
                'permission_name' => $permissionName,
                'menu_visibility' => 'Admin',
                'sort_order' => $menuData['sort_order'] ?? 0,
                'is_active' => true,
            ]
        );

        // Create Permissions for this menu
        $actions = $menuData['actions'] ?? ['read'];
        foreach ($actions as $action) {
            self::createPermission($moduleAlias, $menuData['slug'], $action, $module->id);
        }

        // Process children recursively
        if (!empty($menuData['children'])) {
            foreach ($menuData['children'] as $childData) {
                $childData['category'] = $menuData['category'] ?? 'settings';
                self::createMenuWithPermissions($childData, $module, $moduleAlias, $menu->id);
            }
        }

        return $menu;
    }

    /**
     * Create a single permission
     */
    protected static function createPermission(string $moduleAlias, string $menuSlug, string $action, int $moduleId): void
    {
        $permissionName = "{$moduleAlias}.{$menuSlug}.{$action}";

        $actionNames = [
            'read' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
            'approve' => 'Approve',
            'print' => 'Print',
        ];

        Permission::updateOrCreate(
            ['name' => $permissionName, 'guard_name' => 'admin'],
            [
                'module_id' => $moduleId,
                'action_name' => $actionNames[$action] ?? ucfirst($action),
                'sort_order' => array_search($action, array_keys($actionNames)) ?: 0,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public static function isRegistered(): bool
    {
        return Module::where('alias', self::config()['alias'])->exists();
    }

    public static function getModule(): ?Module
    {
        return Module::where('alias', self::config()['alias'])->first();
    }

    /**
     * Uninstall - Remove menus and permissions
     */
    public static function uninstall(): void
    {
        $module = self::getModule();
        if (!$module) return;

        DB::transaction(function () use ($module) {
            Permission::where('module_id', $module->id)->delete();
            Menu::where('module_id', $module->id)->delete();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            CoreMenuService::clearCache();
        });
    }
}