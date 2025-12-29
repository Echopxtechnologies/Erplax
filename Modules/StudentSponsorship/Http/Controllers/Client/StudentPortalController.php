<?php

namespace Modules\StudentSponsorship\Http\Controllers\Client;

use App\Http\Controllers\Client\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\StudentSponsorship\Models\SchoolStudent;
use Modules\StudentSponsorship\Models\UniversityStudent;
use Modules\StudentSponsorship\Models\UniversityReportCard;

class StudentPortalController extends ClientController
{
    /**
     * Get current student data
     */
    protected function getStudentData()
    {
        $userId = $this->client()->id;
        
        // Check school student first
        $schoolStudent = SchoolStudent::where('user_id', $userId)->first();
        if ($schoolStudent) {
            return [
                'type' => 'school',
                'student' => $schoolStudent,
            ];
        }
        
        // Check university student
        $uniStudent = UniversityStudent::with(['university', 'program'])->where('user_id', $userId)->first();
        if ($uniStudent) {
            return [
                'type' => 'university',
                'student' => $uniStudent,
            ];
        }
        
        return null;
    }

    /**
     * My Form - Main student portal page (read-only form)
     */
    public function myProfile()
    {
        $data = $this->getStudentData();
        
        if (!$data) {
            abort(403, 'You are not registered as a student.');
        }
        
        $student = $data['student'];
        $type = $data['type'];
        
        // Get report cards
        if ($type === 'school') {
            $reportCards = DB::table('school_report_cards')
                ->where('student_school_id', $student->school_student_id)
                ->orderBy('upload_date', 'desc')
                ->get();
        } else {
            $reportCards = $student->reportCards()->orderBy('upload_date', 'desc')->get();
        }
        
        return view('studentsponsorship::client.student-form', compact('student', 'type', 'reportCards'));
    }

    /**
     * Download Report Card
     */
    public function downloadReportCard($id)
    {
        $data = $this->getStudentData();
        
        if (!$data) {
            abort(403, 'You are not registered as a student.');
        }
        
        $student = $data['student'];
        $type = $data['type'];
        
        if ($type === 'school') {
            $reportCard = DB::table('school_report_cards')
                ->where('id', $id)
                ->where('student_school_id', $student->school_student_id)
                ->first();
            
            if (!$reportCard || !$reportCard->file_blob) {
                abort(404, 'Report card not found.');
            }
            
            // Get file extension from mime type
            $extensions = [
                'application/pdf' => 'pdf',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
            ];
            $extension = $extensions[$reportCard->mime_type] ?? 'pdf';
            
            // Build filename with extension if not present
            $filename = $reportCard->filename;
            if (!str_contains($filename, '.')) {
                $filename .= '.' . $extension;
            }
            
            // Use stream response for binary data
            return response()->stream(function () use ($reportCard) {
                echo $reportCard->file_blob;
            }, 200, [
                'Content-Type' => $reportCard->mime_type,
                'Content-Length' => strlen($reportCard->file_blob),
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } else {
            $reportCard = UniversityReportCard::where('id', $id)
                ->where('university_student_id', $student->id)
                ->first();
            
            if (!$reportCard || !$reportCard->file_data) {
                abort(404, 'Report card not found.');
            }
            
            $fileContent = base64_decode($reportCard->file_data);
            $extension = $reportCard->file_extension ?? 'pdf';
            
            // Build filename with extension
            $filename = $reportCard->filename;
            if (!str_contains($filename, '.')) {
                $filename .= '.' . $extension;
            }
            
            // Use stream response for binary data
            return response()->stream(function () use ($fileContent) {
                echo $fileContent;
            }, 200, [
                'Content-Type' => $reportCard->mime_type,
                'Content-Length' => strlen($fileContent),
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }
    }

    /**
     * Upload Report Card (student can upload their own)
     */
    public function uploadReportCard(Request $request)
    {
        $data = $this->getStudentData();
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'You are not registered as a student.'
            ], 403);
        }
        
        $student = $data['student'];
        $type = $data['type'];
        
        // Validate based on type
        if ($type === 'school') {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'filename' => 'required|string|max:255',
                'term' => 'required|string|in:Term1,Term2,Term3',
                'upload_date' => 'required|date',
            ]);
        } else {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'filename' => 'required|string|max:255',
                'term' => 'required|string|in:1Y1S,1Y2S,2Y1S,2Y2S,3Y1S,3Y2S,4Y1S,4Y2S,5Y1S,5Y2S',
                'semester_year' => 'required|integer|min:2015|max:' . date('Y'),
            ]);
        }
        
        $file = $request->file('file');
        $filename = $request->input('filename');
        $term = $request->input('term');
        
        // Read file content
        $fileContent = file_get_contents($file->getRealPath());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        
        // Save to database
        if ($type === 'school') {
            $uploadDate = $request->input('upload_date');
            
            DB::table('school_report_cards')->insert([
                'student_school_id' => $student->school_student_id,
                'filename' => $filename,
                'term' => $term,
                'file_blob' => $fileContent,
                'mime_type' => $mimeType,
                'upload_date' => $uploadDate,
                'created_on' => now(),
            ]);
            
            // Notify admins - School Student
            $studentName = trim($student->first_name . ' ' . $student->last_name);
            $studentId = $student->school_student_id;
            $termDisplay = str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $term);
            $studentUrl = '/admin/studentsponsorship/school-students/' . \Modules\StudentSponsorship\Helpers\HashId::encode($student->id);
            
            $this->notifyAdmins(
                '📄 School Student Report Card',
                "Student: {$studentName} (ID: {$studentId})\nFile: \"{$filename}\" ({$termDisplay})\nPlease review and approve.",
                $studentUrl
            );
            
        } else {
            $semesterYear = $request->input('semester_year');
            $base64Content = base64_encode($fileContent);
            
            UniversityReportCard::create([
                'university_student_id' => $student->id,
                'filename' => $filename,
                'report_card_term' => $term,
                'current_term' => $student->university_year_of_study,
                'semester_end_month' => (int) date('m'),
                'semester_end_year' => $semesterYear,
                'file_data' => $base64Content,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'upload_date' => now(),
            ]);
            
            // Notify admins - University Student
            $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
            if (empty(trim($studentName))) {
                $studentName = $student->name ?? 'Unknown';
            }
            $studentId = $student->university_id ?? ('ID-' . $student->id);
            $termDisplay = $this->getUniversityTermDisplay($term);
            $studentUrl = '/admin/studentsponsorship/university-students/' . \Modules\StudentSponsorship\Helpers\HashId::encode($student->id);
            
            $this->notifyAdmins(
                '📄 University Student Report Card',
                "Student: {$studentName} (ID: {$studentId})\nFile: \"{$filename}\" ({$termDisplay})\nPlease review and approve.",
                $studentUrl
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Report card uploaded successfully.'
        ]);
    }
    
    /**
     * Notify all admins about report card upload
     */
    private function notifyAdmins(string $title, string $message, string $url): void
    {
        try {
            // Get all active admins
            $admins = DB::table('admins')
                ->where('is_active', 1)
                ->pluck('id');
            
            foreach ($admins as $adminId) {
                DB::table('notifications')->insert([
                    'user_id' => $adminId,
                    'user_type' => 'admin',
                    'from_user_id' => auth()->id(),
                    'from_user_type' => 'user',
                    'title' => $title,
                    'message' => $message,
                    'type' => 'info',
                    'url' => $url,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail - don't break upload if notification fails
            \Log::warning('Failed to notify admins: ' . $e->getMessage());
        }
    }
    
    /**
     * Get display text for university term
     */
    private function getUniversityTermDisplay(string $term): string
    {
        $terms = [
            '1Y1S' => 'Year 1 - Semester 1',
            '1Y2S' => 'Year 1 - Semester 2',
            '2Y1S' => 'Year 2 - Semester 1',
            '2Y2S' => 'Year 2 - Semester 2',
            '3Y1S' => 'Year 3 - Semester 1',
            '3Y2S' => 'Year 3 - Semester 2',
            '4Y1S' => 'Year 4 - Semester 1',
            '4Y2S' => 'Year 4 - Semester 2',
            '5Y1S' => 'Year 5 - Semester 1',
            '5Y2S' => 'Year 5 - Semester 2',
        ];
        return $terms[$term] ?? $term;
    }
}
