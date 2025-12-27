<?php

namespace App\Services\Admin;

use App\Models\Module;

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
    | Menu Configuration (with children for Settings Panel)
    |--------------------------------------------------------------------------
    */

    public static function menu(): array
    {
        return [
            'title' => 'Settings',
            'icon' => 'settings',
            'route' => null,
            'permission' => 'settings.read',
            'section' => 'system',
            'sort_order' => 2,
            'type' => 'panel',
            'children' => [
                [
                    'title' => 'General',
                    'icon' => 'settings',
                    'route' => 'admin.settings.general',
                    'permission' => 'settings.general.read',
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Email',
                    'icon' => 'mail',
                    'route' => 'admin.settings.email',
                    'permission' => 'settings.email.read',
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Cron Jobs',
                    'icon' => 'clock',
                    'route' => 'admin.cronjob.index',
                    'permission' => 'settings.cronjob.read',
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Localization',
                    'icon' => 'globe',
                    'route' => null,
                    'permission' => 'settings.localization.read',
                    'sort_order' => 4,
                    'children' => [
                        [
                            'title' => 'Countries',
                            'route' => 'admin.settings.countries.index',
                            'permission' => 'countries.list.read',
                        ],
                        [
                            'title' => 'Timezones',
                            'route' => 'admin.settings.timezones.index',
                            'permission' => 'timezones.list.read',
                        ],
                        [
                            'title' => 'Currencies',
                            'route' => 'admin.settings.currencies.index',
                            'permission' => 'currencies.list.read',
                        ],
                    ],
                ],
                [
                    'title' => 'Finances',
                    'icon' => 'currency',
                    'route' => null,
                    'permission' => 'settings.finances.read',
                    'sort_order' => 5,
                    'children' => [
                        [
                            'title' => 'Tax Management',
                            'route' => 'admin.settings.taxes.index',
                            'permission' => 'taxes.list.read',
                        ],
                        [
                            'title' => 'Payment Methods',
                            'route' => 'admin.settings.payment-methods.index',
                            'permission' => 'payment-methods.list.read',
                        ],
                       [
                            'title' => 'Banks',
                            'route' => 'admin.settings.banks.index',
                            'permission' => 'banks.list.read',
                        ],
                    ],
                ],
                [
                    'title' => 'Roles/Permission',
                    'icon' => 'shield',
                    'route' => null,
                    'permission' => 'settings.roles.read',
                    'sort_order' => 6,
                    'children' => [
                        [
                            'title' => 'Permission',
                            'route' => 'admin.settings.permissions.index',
                            'permission' => 'permissions.list.read',
                        ],
                        [
                            'title' => 'Role',
                            'route' => 'admin.settings.roles.index',
                            'permission' => 'roles.list.read',
                        ],
                        [
                            'title' => 'Staff',
                            'route' => 'admin.settings.users.index',
                            'permission' => 'users.list.read',
                        ],
                        [
                            'title' => 'Client',
                            'route' => 'admin.settings.client.index',
                            'permission' => 'users.list.read',
                        ],
                    ],
                ],
                // NEW: System Info Menu Item
                [
                    'title' => 'System Info',
                    'icon' => 'server',
                    'route' => 'admin.settings.system-info',
                    'permission' => 'settings.system-info.read',
                    'sort_order' => 7,
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