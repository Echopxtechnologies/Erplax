<?php

namespace App\Services\Admin;

use App\Models\Module;

class CustomersService
{
    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Customers',
            'alias' => 'customers',
            'description' => 'Customer & Contact Management - Individual customers, Companies, Contact management',
            'version' => '1.0.0',
            'is_core' => true,
            'sort_order' => 191,
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
            'title'       => 'Customers',
            'icon'        => 'users',
            'route'       => 'admin.customers.index',
            'permission'  => 'customers.read',
            'section'     => 'core',
            'sort_order'  => 50,

            'children' => [
                // [
                //     'title' => 'All Customers',
                //     'route' => 'admin.customers.index',
                //     'icon' => 'list',
                //     'permission' => 'customers.read',
                // ],
                // [
                //     'title' => 'Create Customer',
                //     'route' => 'admin.customers.create',
                //     'icon' => 'plus',
                //     'permission' => 'customers.create',
                // ],
                // [
                //     'title' => 'Customer Groups',
                //     'route' => 'admin.customer-groups.index',
                //     'icon' => 'folder',
                //     'permission' => 'customers.read',
                // ],
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