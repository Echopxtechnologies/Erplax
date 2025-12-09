<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityProgramSeeder extends Seeder
{
    public function run()
    {
        $programs = [
            // Engineering & Technology
            ['id' => 1, 'name' => 'BSc in Computer Science'],
            ['id' => 2, 'name' => 'BSc in Information Technology'],
            ['id' => 3, 'name' => 'BSc in Software Engineering'],
            ['id' => 4, 'name' => 'BSc in Computer Engineering'],
            ['id' => 5, 'name' => 'BSc in Electronic Engineering'],
            ['id' => 6, 'name' => 'BSc in Electrical Engineering'],
            ['id' => 7, 'name' => 'BSc in Mechanical Engineering'],
            ['id' => 8, 'name' => 'BSc in Civil Engineering'],
            ['id' => 9, 'name' => 'BSc in Chemical Engineering'],
            ['id' => 10, 'name' => 'BSc in Biomedical Engineering'],
            ['id' => 11, 'name' => 'BSc in Textile Engineering'],
            ['id' => 12, 'name' => 'BSc in Materials Engineering'],
            
            // Medicine & Health Sciences
            ['id' => 13, 'name' => 'MBBS (Medicine)'],
            ['id' => 14, 'name' => 'BDS (Dental Surgery)'],
            ['id' => 15, 'name' => 'BSc in Nursing'],
            ['id' => 16, 'name' => 'BSc in Pharmacy'],
            ['id' => 17, 'name' => 'BSc in Medical Laboratory Sciences'],
            ['id' => 18, 'name' => 'BSc in Physiotherapy'],
            ['id' => 19, 'name' => 'BSc in Radiography'],
            ['id' => 20, 'name' => 'Bachelor of Ayurveda Medicine and Surgery (BAMS)'],
            
            // Business & Management
            ['id' => 21, 'name' => 'Bachelor of Business Administration (BBA)'],
            ['id' => 22, 'name' => 'BSc in Business Management'],
            ['id' => 23, 'name' => 'BSc in Accounting & Finance'],
            ['id' => 24, 'name' => 'BSc in Marketing'],
            ['id' => 25, 'name' => 'BSc in Human Resource Management'],
            ['id' => 26, 'name' => 'BSc in Economics'],
            ['id' => 27, 'name' => 'BSc in Banking & Finance'],
            ['id' => 28, 'name' => 'Bachelor of Commerce (BCom)'],
            
            // Sciences
            ['id' => 29, 'name' => 'BSc in Mathematics'],
            ['id' => 30, 'name' => 'BSc in Physics'],
            ['id' => 31, 'name' => 'BSc in Chemistry'],
            ['id' => 32, 'name' => 'BSc in Biology'],
            ['id' => 33, 'name' => 'BSc in Zoology'],
            ['id' => 34, 'name' => 'BSc in Botany'],
            ['id' => 35, 'name' => 'BSc in Environmental Science'],
            ['id' => 36, 'name' => 'BSc in Agricultural Science'],
            ['id' => 37, 'name' => 'BSc in Food Science & Technology'],
            ['id' => 38, 'name' => 'BSc in Statistics'],
            
            // Arts & Humanities
            ['id' => 39, 'name' => 'Bachelor of Arts (BA)'],
            ['id' => 40, 'name' => 'BA in English'],
            ['id' => 41, 'name' => 'BA in Sinhala'],
            ['id' => 42, 'name' => 'BA in Tamil'],
            ['id' => 43, 'name' => 'BA in History'],
            ['id' => 44, 'name' => 'BA in Geography'],
            ['id' => 45, 'name' => 'BA in Economics'],
            ['id' => 46, 'name' => 'BA in Political Science'],
            ['id' => 47, 'name' => 'BA in Sociology'],
            ['id' => 48, 'name' => 'BA in Philosophy'],
            ['id' => 49, 'name' => 'BA in Mass Communication'],
            ['id' => 50, 'name' => 'BA in Journalism'],
            
            // Law
            ['id' => 51, 'name' => 'Bachelor of Laws (LLB)'],
            
            // Architecture & Design
            ['id' => 52, 'name' => 'Bachelor of Architecture'],
            ['id' => 53, 'name' => 'BSc in Quantity Surveying'],
            ['id' => 54, 'name' => 'BSc in Town & Country Planning'],
            ['id' => 55, 'name' => 'Bachelor of Design'],
            
            // Education
            ['id' => 56, 'name' => 'Bachelor of Education (BEd)'],
            ['id' => 57, 'name' => 'BA in Education'],
            
            // Postgraduate Programs
            ['id' => 58, 'name' => 'Master of Business Administration (MBA)'],
            ['id' => 59, 'name' => 'MSc in Computer Science'],
            ['id' => 60, 'name' => 'MSc in Information Technology'],
            ['id' => 61, 'name' => 'MEng in Engineering'],
            ['id' => 62, 'name' => 'MA in Economics'],
            ['id' => 63, 'name' => 'LLM (Master of Laws)'],
            ['id' => 64, 'name' => 'PhD Program'],
            ['id' => 65, 'name' => 'MPhil Program'],
            
            // Professional Courses
            ['id' => 66, 'name' => 'Chartered Accountancy (CA)'],
            ['id' => 67, 'name' => 'CIMA'],
            ['id' => 68, 'name' => 'ACCA'],
            ['id' => 69, 'name' => 'CIM (Chartered Institute of Marketing)'],
            ['id' => 70, 'name' => 'Attorney-at-Law Program'],
        ];

        foreach ($programs as $program) {
            DB::table('tbluniversity_program')->updateOrInsert(
                ['id' => $program['id']],
                $program
            );
        }
    }
}
