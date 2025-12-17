<?php

namespace App\Services\Admin;

use App\Models\Module;

class SalesService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Sales',
            'alias' => 'sales',
            'description' => 'Sales Management - Proposals, Estimates, Follow-ups, Conversions',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 110, // After Inventory (100)
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */

    public static function menu(): array
    {
        return [
            'title'       => 'Sales',
            'icon'        => 'shopping-cart',
            'route'       => null,
            'permission'  => 'sales.read',
            'section'     => 'core',
            'sort_order'  => 110,

            'children' => [

                [
                            'title' => 'Proposals',
                            'route' => 'admin.sales.proposals.index',
                            'icon' => 'list',
                        ],
                        [
                            'title' => 'Estimations',
                            'route' => 'admin.sales.estimations.index',
                            'icon' => 'estimations',
                        ],
                         [
                            'title' => 'Invoice',
                            'route' => 'admin.sales.invoices.index',
                            'icon' => 'invoice',
                        ],
            
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
        $config = self::config();

        return Module::updateOrCreate(
            ['alias' => $config['alias']],
            [
                'name'         => $config['name'],
                'description'  => $config['description'],
                'version'      => $config['version'],
                'is_active'    => true,
                'is_installed' => true,
                'is_core'      => $config['is_core'],
                'sort_order'   => $config['sort_order'],
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
