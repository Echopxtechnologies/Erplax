<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadsSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leads_sources')->insert([
            ['name' => 'Facebook'],
            ['name' => 'Google'],
            ['name' => 'Referral'],
            ['name' => 'Website'],
            ['name' => 'Youtube'],
        ]);
    }
}
