<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            ['name' => 'GST 5%', 'rate' => 5.00, 'is_active' => true],
            ['name' => 'GST 12%', 'rate' => 12.00, 'is_active' => true],
            ['name' => 'GST 18%', 'rate' => 18.00, 'is_active' => true],
            ['name' => 'GST 28%', 'rate' => 28.00, 'is_active' => true],
            ['name' => 'IGST 5%', 'rate' => 5.00, 'is_active' => true],
            ['name' => 'IGST 12%', 'rate' => 12.00, 'is_active' => true],
            ['name' => 'IGST 18%', 'rate' => 18.00, 'is_active' => true],
            ['name' => 'IGST 28%', 'rate' => 28.00, 'is_active' => true],
            ['name' => 'No Tax', 'rate' => 0.00, 'is_active' => true],
        ];

        foreach ($taxes as $tax) {
            DB::table('taxes')->insert(array_merge($tax, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}