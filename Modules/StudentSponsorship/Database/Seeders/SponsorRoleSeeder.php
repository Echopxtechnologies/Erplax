<?php

namespace Modules\StudentSponsorship\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SponsorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates sponsor role for admin guard
     */
    public function run(): void
    {
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

            $this->command->info('Sponsor role created successfully.');
        } else {
            $this->command->info('Sponsor role already exists.');
        }
    }

    /**
     * Remove the sponsor role
     * Called during module uninstall
     */
    public static function remove(): void
    {
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
            try {
                app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            } catch (\Exception $e) {
                // Ignore if Spatie not available
            }
        }
    }
}
