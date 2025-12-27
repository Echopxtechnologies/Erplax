<?php

namespace App\Services\Admin;

use App\Models\Module;

class LeadService // LeadService
{
    /*
    |---------------------- Module Configuration -----------------------------
    */

    public static function config(): array
    {
        return [
            'name' => 'Lead Management',
            'alias' => 'lead',
            'description' => 'Manage leads, sources, status, and assignments.',
            'version' => '1.0.0',
            'is_core' => true, // Assuming leads aren't a core module
            'sort_order' => 112, // Sorting order for this module
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
        'title'       => 'Leads',
        'icon'        => 'users',
        'route'       => 'admin.leads.index',
        'permission'  => 'leads.read',
        'section'     => 'core',
        'sort_order'  => 45,
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