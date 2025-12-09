<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityNameSeeder extends Seeder
{
    public function run()
    {
        $universities = [
            // State Universities
            ['id' => 1, 'name' => 'University of Colombo'],
            ['id' => 2, 'name' => 'University of Peradeniya'],
            ['id' => 3, 'name' => 'University of Sri Jayewardenepura'],
            ['id' => 4, 'name' => 'University of Kelaniya'],
            ['id' => 5, 'name' => 'University of Moratuwa'],
            ['id' => 6, 'name' => 'University of Jaffna'],
            ['id' => 7, 'name' => 'University of Ruhuna'],
            ['id' => 8, 'name' => 'Eastern University, Sri Lanka'],
            ['id' => 9, 'name' => 'South Eastern University of Sri Lanka'],
            ['id' => 10, 'name' => 'Rajarata University of Sri Lanka'],
            ['id' => 11, 'name' => 'Sabaragamuwa University of Sri Lanka'],
            ['id' => 12, 'name' => 'Wayamba University of Sri Lanka'],
            ['id' => 13, 'name' => 'Uva Wellassa University'],
            ['id' => 14, 'name' => 'University of the Visual & Performing Arts'],
            ['id' => 15, 'name' => 'Open University of Sri Lanka'],
            ['id' => 16, 'name' => 'Buddhist and Pali University of Sri Lanka'],
            ['id' => 17, 'name' => 'University of Vavuniya'],
            ['id' => 18, 'name' => 'Gampaha Wickramarachchi University of Indigenous Medicine'],
            
            // Technical Universities
            ['id' => 19, 'name' => 'Sri Lanka Institute of Information Technology (SLIIT)'],
            ['id' => 20, 'name' => 'Informatics Institute of Technology (IIT)'],
            ['id' => 21, 'name' => 'National Institute of Business Management (NIBM)'],
            ['id' => 22, 'name' => 'Institute of Chartered Accountants of Sri Lanka'],
            ['id' => 23, 'name' => 'Sri Lanka Law College'],
            
            // Private/International Universities
            ['id' => 24, 'name' => 'NSBM Green University'],
            ['id' => 25, 'name' => 'CINEC Campus'],
            ['id' => 26, 'name' => 'APIIT Sri Lanka'],
            ['id' => 27, 'name' => 'British College of Applied Studies (BCAS)'],
            ['id' => 28, 'name' => 'Horizon Campus'],
            ['id' => 29, 'name' => 'ICBT Campus'],
            ['id' => 30, 'name' => 'ANC Education'],
            ['id' => 31, 'name' => 'Sri Lanka International Buddhist Academy (SIBA)'],
            ['id' => 32, 'name' => 'Aquinas College of Higher Studies'],
            ['id' => 33, 'name' => 'General Sir John Kotelawala Defence University (KDU)'],
            
            // Medical & Health
            ['id' => 34, 'name' => 'Postgraduate Institute of Medicine'],
            ['id' => 35, 'name' => 'Institute of Indigenous Medicine'],
            
            // Foreign University Affiliates
            ['id' => 36, 'name' => 'University of London (External) - Sri Lanka'],
            ['id' => 37, 'name' => 'Coventry University - NSBM'],
            ['id' => 38, 'name' => 'Plymouth University - NSBM'],
            ['id' => 39, 'name' => 'Victoria University Australia - SLIIT'],
            ['id' => 40, 'name' => 'Curtin University - CINEC'],
        ];

        foreach ($universities as $university) {
            DB::table('tbluniversity_name')->updateOrInsert(
                ['id' => $university['id']],
                $university
            );
        }
    }
}
