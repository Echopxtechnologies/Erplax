<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leads_status')->insert([
            ['name' => 'Customer', 'statusorder' => 1000, 'color' => '#7cb342', 'isdefault' => 1],
            ['name' => 'Followup', 'statusorder' => 2, 'color' => '#2888DA', 'isdefault' => 0],
            ['name' => 'Lead', 'statusorder' => 5, 'color' => '#2888DA', 'isdefault' => 0],
        ]);
    }
}
