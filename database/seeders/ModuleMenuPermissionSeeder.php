<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Menu;
use App\Models\MenuAction;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleMenuPermissionSeeder extends Seeder
{
    /**
     * Default actions for each menu type
     */
    protected array $defaultActions = [
        'list' => [
            ['action_name' => 'View', 'action_slug' => 'read', 'sort_order' => 1],
            ['action_name' => 'Edit', 'action_slug' => 'edit', 'sort_order' => 2],
            ['action_name' => 'Delete', 'action_slug' => 'delete', 'sort_order' => 3],
            ['action_name' => 'Export', 'action_slug' => 'export', 'sort_order' => 4],
        ],
        'create' => [
            ['action_name' => 'Create', 'action_slug' => 'create', 'sort_order' => 1],
        ],
        'default' => [
            ['action_name' => 'View', 'action_slug' => 'read', 'sort_order' => 1],
            ['action_name' => 'Create', 'action_slug' => 'create', 'sort_order' => 2],
            ['action_name' => 'Edit', 'action_slug' => 'edit', 'sort_order' => 3],
            ['action_name' => 'Delete', 'action_slug' => 'delete', 'sort_order' => 4],
        ],
    ];

    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // Process existing modules
            $this->processExistingModules();

            // Create system menus (Settings, etc.)
            $this->createSystemMenus();

            // Create default roles
            $this->createDefaultRoles();
        });
    }

    /**
     * Process existing modules and create menus/actions/permissions
     */
    private function processExistingModules(): void
    {
        $modules = Module::where('is_installed', true)->get();

        foreach ($modules as $module) {
            $this->createMenusForModule($module);
        }
    }

    /**
     * Create menus for a module
     */
    private function createMenusForModule(Module $module): void
    {
        // Define menus based on module alias
        $menuConfigs = $this->getMenuConfigForModule($module->alias);

        foreach ($menuConfigs as $menuConfig) {
            $this->createMenuWithActions($module, $menuConfig);
        }
    }

    /**
     * Get menu configuration for a module
     */
    private function getMenuConfigForModule(string $alias): array
    {
        $configs = [
            'todo' => [
                [
                    'menu_name' => 'Task List',
                    'slug' => 'list',
                    'icon' => 'list',
                    'route' => "admin.{$alias}.index",
                    'category' => 'main',
                    'sort_order' => 1,
                    'actions' => $this->defaultActions['list'],
                ],
                [
                    'menu_name' => 'Create Task',
                    'slug' => 'create',
                    'icon' => 'plus',
                    'route' => "admin.{$alias}.create",
                    'category' => 'main',
                    'sort_order' => 2,
                    'actions' => $this->defaultActions['create'],
                ],
            ],
            'book' => [
                [
                    'menu_name' => 'Book List',
                    'slug' => 'list',
                    'icon' => 'list',
                    'route' => "admin.books.index",
                    'category' => 'main',
                    'sort_order' => 1,
                    'actions' => $this->defaultActions['list'],
                ],
                [
                    'menu_name' => 'Add Book',
                    'slug' => 'create',
                    'icon' => 'plus',
                    'route' => "admin.books.create",
                    'category' => 'main',
                    'sort_order' => 2,
                    'actions' => $this->defaultActions['create'],
                ],
                [
                    'menu_name' => 'Categories',
                    'slug' => 'categories',
                    'icon' => 'folder',
                    'route' => "admin.books.categories.index",
                    'category' => 'main',
                    'sort_order' => 3,
                    'actions' => $this->defaultActions['default'],
                ],
            ],
            'attendance' => [
                [
                    'menu_name' => 'Attendance List',
                    'slug' => 'list',
                    'icon' => 'list',
                    'route' => "admin.attendance.index",
                    'category' => 'main',
                    'sort_order' => 1,
                    'actions' => $this->defaultActions['list'],
                ],
                [
                    'menu_name' => 'Mark Attendance',
                    'slug' => 'create',
                    'icon' => 'plus',
                    'route' => "admin.attendance.create",
                    'category' => 'main',
                    'sort_order' => 2,
                    'actions' => $this->defaultActions['create'],
                ],
                [
                    'menu_name' => 'Reports',
                    'slug' => 'reports',
                    'icon' => 'chart',
                    'route' => "admin.attendance.reports",
                    'category' => 'main',
                    'sort_order' => 3,
                    'actions' => [
                        ['action_name' => 'View', 'action_slug' => 'read', 'sort_order' => 1],
                        ['action_name' => 'Export', 'action_slug' => 'export', 'sort_order' => 2],
                    ],
                ],
            ],
        ];

        return $configs[$alias] ?? [
            [
                'menu_name' => ucfirst($alias) . ' List',
                'slug' => 'list',
                'icon' => 'list',
                'route' => "admin.{$alias}.index",
                'category' => 'main',
                'sort_order' => 1,
                'actions' => $this->defaultActions['default'],
            ],
        ];
    }

    /**
     * Create menu with actions and permissions
     */
    private function createMenuWithActions(Module $module, array $menuConfig): void
    {
        $actions = $menuConfig['actions'] ?? $this->defaultActions['default'];
        unset($menuConfig['actions']);

        // Create or update menu
        $menu = Menu::updateOrCreate(
            [
                'module_id' => $module->id,
                'slug' => $menuConfig['slug'],
            ],
            array_merge($menuConfig, [
                'menu_visibility' => 'Admin',
                'is_active' => true,
            ])
        );

        // Create actions and permissions
        foreach ($actions as $actionData) {
            $action = MenuAction::updateOrCreate(
                [
                    'menu_id' => $menu->id,
                    'action_slug' => $actionData['action_slug'],
                ],
                $actionData
            );

            // Create Spatie Permission
            $permissionName = "{$module->alias}.{$menu->slug}.{$action->action_slug}";
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        // Update menu's permission_name field
        $menu->update([
            'permission_name' => "{$module->alias}.{$menu->slug}.read"
        ]);
    }

    /**
     * Create system menus (Settings, Users, Roles, etc.)
     */
    private function createSystemMenus(): void
    {
        // Create a virtual "system" module for settings
        $systemModule = Module::firstOrCreate(
            ['alias' => 'settings'],
            [
                'name' => 'Settings',
                'description' => 'System settings and configuration',
                'version' => '1.0.0',
                'is_active' => true,
                'is_installed' => true,
                'is_core' => true,
                'sort_order' => 999,
            ]
        );

        $systemMenus = [
            [
                'menu_name' => 'Roles',
                'slug' => 'roles',
                'icon' => 'shield',
                'route' => 'admin.settings.roles.index',
                'category' => 'settings',
                'sort_order' => 1,
                'actions' => $this->defaultActions['default'],
            ],
            [
                'menu_name' => 'Permissions',
                'slug' => 'permissions',
                'icon' => 'key',
                'route' => 'admin.settings.permissions.index',
                'category' => 'settings',
                'sort_order' => 2,
                'actions' => $this->defaultActions['default'],
            ],
            [
                'menu_name' => 'Users',
                'slug' => 'users',
                'icon' => 'users',
                'route' => 'admin.settings.users.index',
                'category' => 'settings',
                'sort_order' => 3,
                'actions' => $this->defaultActions['default'],
            ],
            [
                'menu_name' => 'General Settings',
                'slug' => 'general',
                'icon' => 'settings',
                'route' => 'admin.settings.general',
                'category' => 'settings',
                'sort_order' => 4,
                'actions' => [
                    ['action_name' => 'View', 'action_slug' => 'read', 'sort_order' => 1],
                    ['action_name' => 'Edit', 'action_slug' => 'edit', 'sort_order' => 2],
                ],
            ],
        ];

        foreach ($systemMenus as $menuConfig) {
            $this->createMenuWithActions($systemModule, $menuConfig);
        }
    }

    /**
     * Create default roles with permissions
     */
    private function createDefaultRoles(): void
    {
        // Super Admin - all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - all permissions
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // Manager - limited permissions
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerPermissions = Permission::where('name', 'not like', 'settings.%')
            ->orWhere('name', 'like', '%.read')
            ->get();
        $manager->syncPermissions($managerPermissions);

        // Staff - read only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staffPermissions = Permission::where('name', 'like', '%.read')->get();
        $staff->syncPermissions($staffPermissions);

        // User - minimal
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }
}