<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update all existing permissions to use admin guard
        DB::table('permissions')->update(['guard_name' => 'admin']);
        
        // Update all existing roles to use admin guard
        DB::table('roles')->update(['guard_name' => 'admin']);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Revert to web guard if needed
        DB::table('permissions')->update(['guard_name' => 'web']);
        DB::table('roles')->update(['guard_name' => 'web']);
        
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};