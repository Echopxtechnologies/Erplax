<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;

class StudentSponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            BankSeeder::class,
            SchoolNameSeeder::class,
            UniversityNameSeeder::class,
            UniversityProgramSeeder::class,
        ]);
    }
}
