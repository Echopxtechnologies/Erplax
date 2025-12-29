<?php

namespace Modules\StudentSponsorship\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * StudentHelper - Utility functions for student portal
 */
class StudentHelper
{
    /**
     * Check if a user is a student (school or university)
     * 
     * @param int $userId
     * @return array|null Returns ['type' => 'school'|'university', 'student_id' => int] or null
     */
    public static function getStudentByUserId(int $userId): ?array
    {
        // Check school student
        $schoolStudent = DB::table('school_students')
            ->where('user_id', $userId)
            ->first();
        
        if ($schoolStudent) {
            return [
                'type' => 'school',
                'student_id' => $schoolStudent->id,
            ];
        }
        
        // Check university student
        $uniStudent = DB::table('university_students')
            ->where('user_id', $userId)
            ->first();
        
        if ($uniStudent) {
            return [
                'type' => 'university',
                'student_id' => $uniStudent->id,
            ];
        }
        
        return null;
    }

    /**
     * Check if user is a student
     * 
     * @param int $userId
     * @return bool
     */
    public static function isStudent(int $userId): bool
    {
        return self::getStudentByUserId($userId) !== null;
    }

    /**
     * Get the redirect URL for student portal
     * 
     * @return string
     */
    public static function getStudentPortalUrl(): string
    {
        return route('client.student-portal.my-profile');
    }

    /**
     * Get redirect URL based on user type
     * Use this in ClientLoginController after successful login
     * 
     * @param int $userId
     * @return string
     */
    public static function getLoginRedirectUrl(int $userId): string
    {
        if (self::isStudent($userId)) {
            return self::getStudentPortalUrl();
        }
        
        return route('client.dashboard');
    }
}
