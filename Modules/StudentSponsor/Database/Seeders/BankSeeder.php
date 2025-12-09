<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    public function run()
    {
        $banks = [
            ['id' => 1, 'name' => 'Bank of Ceylon (BOC)'],
            ['id' => 2, 'name' => 'People\'s Bank'],
            ['id' => 3, 'name' => 'Commercial Bank of Ceylon'],
            ['id' => 4, 'name' => 'Hatton National Bank (HNB)'],
            ['id' => 5, 'name' => 'Sampath Bank'],
            ['id' => 6, 'name' => 'Nations Trust Bank (NTB)'],
            ['id' => 7, 'name' => 'Seylan Bank'],
            ['id' => 8, 'name' => 'DFCC Bank'],
            ['id' => 9, 'name' => 'National Savings Bank (NSB)'],
            ['id' => 10, 'name' => 'Pan Asia Banking Corporation (PABC)'],
            ['id' => 11, 'name' => 'Union Bank of Colombo'],
            ['id' => 12, 'name' => 'National Development Bank (NDB)'],
            ['id' => 13, 'name' => 'Cargills Bank'],
            ['id' => 14, 'name' => 'Amana Bank'],
            ['id' => 15, 'name' => 'Regional Development Bank (RDB)'],
            ['id' => 16, 'name' => 'Sanasa Development Bank'],
            ['id' => 17, 'name' => 'HDFC Bank Sri Lanka'],
            ['id' => 18, 'name' => 'State Mortgage & Investment Bank'],
            ['id' => 19, 'name' => 'Housing Development Finance Corporation'],
            ['id' => 20, 'name' => 'Standard Chartered Bank'],
            ['id' => 21, 'name' => 'HSBC Sri Lanka'],
            ['id' => 22, 'name' => 'Citibank Sri Lanka'],
            ['id' => 23, 'name' => 'Deutsche Bank Sri Lanka'],
            ['id' => 24, 'name' => 'Indian Bank Sri Lanka'],
            ['id' => 25, 'name' => 'Indian Overseas Bank Sri Lanka'],
            ['id' => 26, 'name' => 'State Bank of India Sri Lanka'],
            ['id' => 27, 'name' => 'MCB Bank Sri Lanka'],
            ['id' => 28, 'name' => 'Public Bank Berhad Sri Lanka'],
        ];

        foreach ($banks as $bank) {
            DB::table('tblbank')->updateOrInsert(
                ['id' => $bank['id']],
                $bank
            );
        }
    }
}
