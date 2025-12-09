<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolNameSeeder extends Seeder
{
    public function run()
    {
        $schools = [
            // Colombo District
            ['id' => 1, 'name' => 'Royal College, Colombo'],
            ['id' => 2, 'name' => 'Ananda College, Colombo'],
            ['id' => 3, 'name' => 'Nalanda College, Colombo'],
            ['id' => 4, 'name' => 'Visakha Vidyalaya, Colombo'],
            ['id' => 5, 'name' => 'Devi Balika Vidyalaya, Colombo'],
            ['id' => 6, 'name' => 'Isipathana College, Colombo'],
            ['id' => 7, 'name' => 'D.S. Senanayake College, Colombo'],
            ['id' => 8, 'name' => 'Thurstan College, Colombo'],
            ['id' => 9, 'name' => 'Mahanama College, Colombo'],
            ['id' => 10, 'name' => 'Musaeus College, Colombo'],
            ['id' => 11, 'name' => 'Ladies College, Colombo'],
            ['id' => 12, 'name' => 'Wesley College, Colombo'],
            ['id' => 13, 'name' => 'St. Thomas College, Mount Lavinia'],
            ['id' => 14, 'name' => 'St. Joseph\'s College, Colombo'],
            ['id' => 15, 'name' => 'St. Peter\'s College, Colombo'],
            
            // Kandy District
            ['id' => 16, 'name' => 'Trinity College, Kandy'],
            ['id' => 17, 'name' => 'Dharmaraja College, Kandy'],
            ['id' => 18, 'name' => 'Kingswood College, Kandy'],
            ['id' => 19, 'name' => 'Mahamaya Girls College, Kandy'],
            ['id' => 20, 'name' => 'Girls High School, Kandy'],
            ['id' => 21, 'name' => 'St. Anthony\'s College, Kandy'],
            ['id' => 22, 'name' => 'St. Sylvester\'s College, Kandy'],
            
            // Galle District
            ['id' => 23, 'name' => 'Richmond College, Galle'],
            ['id' => 24, 'name' => 'Mahinda College, Galle'],
            ['id' => 25, 'name' => 'Southlands College, Galle'],
            ['id' => 26, 'name' => 'Sanghamitta Balika Vidyalaya, Galle'],
            
            // Matara District
            ['id' => 27, 'name' => 'St. Thomas College, Matara'],
            ['id' => 28, 'name' => 'Rahula College, Matara'],
            ['id' => 29, 'name' => 'Sujatha Vidyalaya, Matara'],
            
            // Jaffna District
            ['id' => 30, 'name' => 'Jaffna Hindu College'],
            ['id' => 31, 'name' => 'St. John\'s College, Jaffna'],
            ['id' => 32, 'name' => 'Jaffna Central College'],
            ['id' => 33, 'name' => 'Vembadi Girls High School, Jaffna'],
            ['id' => 34, 'name' => 'Chundikuli Girls College, Jaffna'],
            
            // Kurunegala District
            ['id' => 35, 'name' => 'Maliyadeva College, Kurunegala'],
            ['id' => 36, 'name' => 'St. Anne\'s College, Kurunegala'],
            
            // Ratnapura District
            ['id' => 37, 'name' => 'Sivali Central College, Ratnapura'],
            ['id' => 38, 'name' => 'St. Luke\'s College, Ratnapura'],
            
            // Badulla District
            ['id' => 39, 'name' => 'Badulla Central College'],
            ['id' => 40, 'name' => 'Uva College, Badulla'],
            
            // Other Popular Schools
            ['id' => 41, 'name' => 'President\'s College, Embilipitiya'],
            ['id' => 42, 'name' => 'Bandaranayake College, Gampaha'],
            ['id' => 43, 'name' => 'Taxila Central College, Horana'],
            ['id' => 44, 'name' => 'Ananda Sastralaya, Kotte'],
            ['id' => 45, 'name' => 'Rathnavali Balika Vidyalaya, Gampaha'],
            ['id' => 46, 'name' => 'St. Thomas Preparatory School, Colombo'],
            ['id' => 47, 'name' => 'Gateway College, Colombo'],
            ['id' => 48, 'name' => 'Elizabeth Moir School, Colombo'],
            ['id' => 49, 'name' => 'Lyceum International School'],
            ['id' => 50, 'name' => 'Asian International School'],
        ];

        foreach ($schools as $school) {
            DB::table('tblschool_name')->updateOrInsert(
                ['id' => $school['id']],
                $school
            );
        }
    }
}
