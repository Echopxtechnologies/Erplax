<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'user']);

        // Create admin user if not exists
        $adminEmail = 'echopx@gmail.com';
        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            ['name' => 'Admin', 'password' => bcrypt('admin@123'), 'role' => 'admin']
        );

        $admin->assignRole('admin'); // assign spatie role
    }
}