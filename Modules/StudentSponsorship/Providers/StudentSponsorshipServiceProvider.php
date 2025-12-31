<?php

namespace Modules\StudentSponsorship\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class StudentSponsorshipServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'StudentSponsorship';
    protected string $moduleNameLower = 'studentsponsorship';

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        
        // Auto-create sponsor role if not exists
        $this->ensureSponsorRoleExists();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), 
            $this->moduleNameLower
        );
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    /**
     * Ensure sponsor role exists in database
     * Runs on every boot but only creates if missing
     */
    protected function ensureSponsorRoleExists(): void
    {
        // Only run if roles table exists (after migrations)
        try {
            if (!$this->app->runningInConsole() || $this->app->runningUnitTests()) {
                // Check if role exists
                $exists = DB::table('roles')
                    ->where('name', 'sponsor')
                    ->where('guard_name', 'admin')
                    ->exists();

                if (!$exists) {
                    DB::table('roles')->insert([
                        'name'       => 'sponsor',
                        'guard_name' => 'admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Clear permission cache
                    $this->clearPermissionCache();
                }
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet or other error - ignore
            // Role will be created on next request after migrations
        }
    }

    /**
     * Clear Spatie permission cache
     */
    protected function clearPermissionCache(): void
    {
        try {
            if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
                app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            }
        } catch (\Exception $e) {
            // Ignore
        }
    }

    /**
     * Module uninstall - removes sponsor role and cleans up
     * Call this before disabling/removing the module
     * 
     * Usage: 
     *   app(\Modules\StudentSponsorship\Providers\StudentSponsorshipServiceProvider::class)->uninstall();
     * Or:
     *   \Modules\StudentSponsorship\Providers\StudentSponsorshipServiceProvider::cleanup();
     */
    public function uninstall(): void
    {
        self::cleanup();
    }

    /**
     * Static cleanup method for module removal
     * Removes sponsor role and all related data
     */
    public static function cleanup(): void
    {
        try {
            // Get role ID
            $role = DB::table('roles')
                ->where('name', 'sponsor')
                ->where('guard_name', 'admin')
                ->first();

            if ($role) {
                // Remove role assignments from model_has_roles
                DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->delete();

                // Remove role permissions from role_has_permissions
                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->delete();

                // Delete the role
                DB::table('roles')
                    ->where('id', $role->id)
                    ->delete();

                // Clear permission cache
                if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
                    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                }

                \Log::info('StudentSponsorship: Sponsor role removed during uninstall');
            }

            // Optional: Clean up sponsor portal access (staff + admin records)
            // This removes portal access but keeps sponsor data
            $sponsorsWithPortal = DB::table('sponsors')
                ->whereNotNull('staff_id')
                ->get();

            foreach ($sponsorsWithPortal as $sponsor) {
                $staff = DB::table('staffs')->where('id', $sponsor->staff_id)->first();
                
                if ($staff) {
                    // Delete admin record
                    if ($staff->admin_id) {
                        DB::table('admins')->where('id', $staff->admin_id)->delete();
                    }
                    
                    // Delete staff record
                    DB::table('staffs')->where('id', $sponsor->staff_id)->delete();
                }

                // Clear staff_id from sponsor
                DB::table('sponsors')
                    ->where('id', $sponsor->id)
                    ->update(['staff_id' => null]);
            }

            \Log::info('StudentSponsorship: Module cleanup completed');

        } catch (\Exception $e) {
            \Log::error('StudentSponsorship cleanup error: ' . $e->getMessage());
        }
    }

    public function provides(): array
    {
        return [];
    }
}
