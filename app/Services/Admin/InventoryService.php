<?php

namespace App\Services\Admin;

use App\Models\Module;

class InventoryService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Inventory',
            'alias' => 'inventory',
            'description' => 'Inventory & Stock Management - products, warehouses, lots, stock movements',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 100,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration (Exact match to hardcoded menu)
    |--------------------------------------------------------------------------
    */

    public static function menu(): array
    {
        return [
            'title' => 'Inventory',
            'icon' => 'box',
            'route' => null,
            'permission' => 'inventory.read',
            'section' => 'core',
            'sort_order' => 100,
            'children' => [
                // Dashboard
                [
                    'title' => 'Dashboard',
                    'route' => 'admin.inventory.dashboard',
                    'icon' => 'layout-dashboard',
                ],
                // Products
                [
                    'title' => 'Products',
                    'route' => 'admin.inventory.products.index',
                    'icon' => 'package',
                ],
                // Warehouses & Racks (Nested)
                [
                    'title' => 'Warehouses & Racks',
                    'icon' => 'warehouse',
                    'children' => [
                        [
                            'title' => 'Warehouses',
                            'route' => 'admin.inventory.warehouses.index',
                            'icon' => 'warehouse',
                        ],
                        [
                            'title' => 'Racks / Locations',
                            'route' => 'admin.inventory.racks.index',
                            'icon' => 'rack',
                        ],
                    ],
                ],
                // Lots / Batches
                [
                    'title' => 'Lots / Batches',
                    'route' => 'admin.inventory.lots.index',
                    'icon' => 'file',
                ],
                // Stock Management (Nested)
                [
                    'title' => 'Stock Management',
                    'icon' => 'clipboard',
                    'children' => [
                        [
                            'title' => 'Receive Stock',
                            'route' => 'admin.inventory.stock.receive',
                            'icon' => 'plus',
                        ],
                        [
                            'title' => 'Deliver Stock',
                            'route' => 'admin.inventory.stock.deliver',
                            'icon' => 'arrow-right',
                        ],
                        [
                            'title' => 'Transfer Stock',
                            'route' => 'admin.inventory.stock.transfer',
                            'icon' => 'transfer',
                        ],
                        [
                            'title' => 'Returns',
                            'route' => 'admin.inventory.stock.returns',
                            'icon' => 'return',
                        ],
                        [
                            'title' => 'Adjustments',
                            'route' => 'admin.inventory.stock.adjustments',
                            'icon' => 'adjustment',
                        ],
                        [
                            'title' => 'Movement History',
                            'route' => 'admin.inventory.stock.movements',
                            'icon' => 'clipboard-list',
                        ],
                    ],
                ],
                // Reports
                [
                    'title' => 'Reports',
                    'route' => 'admin.inventory.reports.stock-summary',
                    'icon' => 'report',
                ],
                // Settings
                [
                    'title' => 'Settings',
                    'route' => 'admin.inventory.settings.index',
                    'icon' => 'settings',
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    public static function register(): Module
    {
        $config = self::config();

        return Module::updateOrCreate(
            ['alias' => $config['alias']],
            [
                'name' => $config['name'],
                'description' => $config['description'],
                'version' => $config['version'],
                'is_active' => true,
                'is_installed' => true,
                'is_core' => $config['is_core'],
                'sort_order' => $config['sort_order'],
                'installed_at' => now(),
            ]
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