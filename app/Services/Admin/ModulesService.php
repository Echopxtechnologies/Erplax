<?php

namespace App\Services\Admin;

use App\Models\Module;

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

    public static function menu(): array
    {
        return [
            'title' => 'Modules',
            'icon' => 'cube',
            'route' => 'admin.modules.index',
            'permission' => 'modules.list.read',
            'section' => 'system',
            'sort_order' => 1,
            'children' => [],
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
                'is_core' => true,
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