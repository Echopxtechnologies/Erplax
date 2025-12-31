<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\SchoolStudent;
use Modules\StudentSponsorship\Models\SchoolName;
use Modules\StudentSponsorship\Helpers\HashId;
use Modules\Core\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SchoolStudentController extends AdminController
{
    use DataTableTrait;

    // =========================================
    // ID RESOLUTION HELPER
    // =========================================
    
    /**
     * Resolve hashed ID to actual ID
     * Accepts both hashed string and numeric ID for backwards compatibility
     */
    private function resolveId($hashOrId): int
    {
        // If numeric, return as is (backwards compatibility)
        if (is_numeric($hashOrId)) {
            return (int) $hashOrId;
        }
        
        // Decode hash
        $id = HashId::decode($hashOrId);
        
        if (!$id) {
            abort(404, 'Invalid student ID');
        }
        
        return $id;
    }

    // =========================================
    // SECURITY HELPERS
    // =========================================
    
    /**
     * Sanitize string input - removes XSS, SQL injection attempts
     */
    private function sanitizeString($value)
    {
        if (is_null($value)) return null;
        
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Strip HTML tags
        $value = strip_tags($value);
        
        // Convert special chars to HTML entities
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        
        // Trim whitespace
        $value = trim($value);
        
        return $value;
    }
    
    /**
     * Sanitize all string inputs in request
     */
    private function sanitizeRequest(Request $request, array $fields)
    {
        $sanitized = [];
        foreach ($fields as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $sanitized[$field] = $this->sanitizeString($value);
            } else {
                $sanitized[$field] = $value;
            }
        }
        return $sanitized;
    }
    
    /**
     * Validate uploaded file is safe
     */
    private function validateFileContent($file, array $allowedMimes)
    {
        try {
            // Check file size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                return ['valid' => false, 'error' => 'File too large. Maximum 5MB allowed.'];
            }
            
            // Check if file is empty
            if ($file->getSize() === 0) {
                return ['valid' => false, 'error' => 'File is empty.'];
            }
            
            // Get MIME type - use Laravel's method which is reliable
            $actualMime = $file->getMimeType();
            
            // Map of allowed MIME types
            $mimeMap = [
                'pdf' => ['application/pdf'],
                'jpg' => ['image/jpeg', 'image/jpg'],
                'jpeg' => ['image/jpeg', 'image/jpg'],
                'png' => ['image/png'],
                'gif' => ['image/gif'],
            ];
            
            $allowedMimeTypes = [];
            foreach ($allowedMimes as $ext) {
                if (isset($mimeMap[$ext])) {
                    $allowedMimeTypes = array_merge($allowedMimeTypes, $mimeMap[$ext]);
                }
            }
            
            // Allow if MIME type matches
            if (in_array($actualMime, $allowedMimeTypes)) {
                return ['valid' => true, 'mime' => $actualMime];
            }
            
            // Also check by extension as fallback for some edge cases
            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, $allowedMimes)) {
                return ['valid' => true, 'mime' => $actualMime];
            }
            
            return ['valid' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedMimes)];
            
        } catch (\Exception $e) {
            \Log::error('File validation error', ['error' => $e->getMessage()]);
            // On error, let Laravel's validation handle it
            return ['valid' => true, 'mime' => 'application/octet-stream'];
        }
    }

    // =========================================
    // DATATABLE v2.0 CONFIGURATION
    // =========================================
    
    protected $model = SchoolStudent::class;
    protected $with = ['school'];
    protected $searchable = ['full_name', 'email', 'phone', 'school_internal_id'];
    protected $sortable = ['id', 'school_internal_id', 'full_name', 'email', 'grade', 'age', 'status', 'created_at'];
    protected $filterable = ['status', 'grade', 'school_id'];
    protected $routePrefix = 'admin.studentsponsorship.school-students';
    protected $uniqueField = 'school_internal_id';  // For import upsert (update if exists)

    // =========================================
    // IMPORT CONFIGURATION
    // =========================================

    // Validation rules for import
    protected $importable = [
        'school_internal_id'     => 'required|string|max:50',
        'school_student_id'      => 'required|integer',
        'full_name'              => 'required|string|max:255',
        'age'                    => 'required|integer|min:4|max:25',
        'grade'                  => 'required|integer|min:1|max:14',
        'email'                  => 'nullable|email',
        'phone'                  => 'nullable|string|max:30',
        'dob'                    => 'nullable|date',
        'country_name'           => 'nullable|string|max:100',
        'address'                => 'nullable|string|max:500',
        'city'                   => 'nullable|string|max:100',
        'postal_code'            => 'nullable|string|max:20',
        'school_name'            => 'nullable|string|max:255',
        'school_type'            => 'nullable|in:Type 1AB,Type 1C,Type 2,Type 3',
        'current_state'          => 'nullable|in:inprogress,complete',
        'grade_mismatch_reason'  => 'nullable|string|max:255',
        'sponsorship_start_date' => 'nullable|date',
        'sponsorship_end_date'   => 'nullable|date',
        'introduced_by'          => 'nullable|string|max:255',
        'introducer_phone'       => 'nullable|string|max:30',
        'bank_name'              => 'nullable|string|max:255',
        'bank_account_number'    => 'nullable|string|max:50',
        'bank_branch_number'     => 'nullable|string|max:20',
        'bank_branch_info'       => 'nullable|string|max:255',
        'father_name'            => 'nullable|string|max:255',
        'father_income'          => 'nullable|numeric|min:0',
        'mother_name'            => 'nullable|string|max:255',
        'mother_income'          => 'nullable|numeric|min:0',
        'guardian_name'          => 'nullable|string|max:255',
        'guardian_income'        => 'nullable|numeric|min:0',
        'background_info'        => 'nullable|string',
        'internal_comment'       => 'nullable|string',
        'external_comment'       => 'nullable|string',
        'status'                 => 'nullable',
    ];

    // Auto-lookup: Import by name → saves as ID
    protected $importLookups = [
        'school_name' => [
            'table'   => 'school_names',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'school_id',
            'create'  => true,  // Auto-create if not exists
        ],
        'country_name' => [
            'table'   => 'countries',
            'search'  => 'short_name',
            'return'  => 'country_id',
            'save_as' => 'country_id',
            // No create - countries should exist
        ],
        'bank_name' => [
            'table'   => 'banks',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'bank_id',
            'create'  => true,  // Auto-create if not exists
            'create_data' => ['created_on' => null],  // Will be set to now() by trait
        ],
    ];

    // Default values for empty import columns
    protected $importDefaults = [
        'status'        => 1,
        'current_state' => 'inprogress',
    ];

    // Bulk action buttons
    protected $bulkActions = [
        'delete'     => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
        'activate'   => ['label' => 'Activate', 'color' => 'green'],
        'deactivate' => ['label' => 'Deactivate', 'color' => 'yellow'],
    ];

    // =========================================
    // OVERRIDE: Custom Row Mapping
    // =========================================

    /**
     * Custom row mapping for list
     */
    protected function mapRow($item)
    {
        try {
            $hashId = HashId::encode($item->id);
            
            // Get sponsors for this student
            $sponsorsList = $item->sponsors_list;
            $sponsorNames = array_map(fn($s) => $s['name'], $sponsorsList);
            
            return [
                'id' => $item->id,
                'hash_id' => $hashId,
                'school_internal_id' => $item->school_internal_id ?? '',
                'full_name' => $item->full_name ?? '',
                'email' => $item->email ?? '',
                'phone' => $item->phone ?? '',
                'school_name' => $item->school->name ?? 'N/A',
                'grade' => $item->grade ?? '',
                'current_state' => $item->current_state ?? 'inprogress',
                'completed_year' => $item->completed_year ?? null,
                'age' => $item->age ?? '',
                'status' => $item->status ? 1 : 0,
                'profile_photo_url' => $item->hasMedia('profile_photo') ? $item->getFirstMediaUrl('profile_photo') : null,
                'sponsors' => $sponsorsList,
                'sponsors_count' => count($sponsorsList),
                'sponsors_names' => implode(', ', $sponsorNames) ?: '-',
                '_show_url' => route('admin.studentsponsorship.school-students.show', $hashId),
                '_edit_url' => route('admin.studentsponsorship.school-students.edit', $hashId),
                '_delete_url' => route('admin.studentsponsorship.school-students.destroy', $hashId),
            ];
        } catch (\Exception $e) {
            \Log::error('mapRow error', ['id' => $item->id ?? 'unknown', 'error' => $e->getMessage()]);
            return [
                'id' => $item->id ?? 0,
                'hash_id' => '',
                'school_internal_id' => '',
                'full_name' => 'Error loading',
                'email' => '',
                'phone' => '',
                'school_name' => 'N/A',
                'grade' => '',
                'current_state' => 'inprogress',
                'completed_year' => null,
                'age' => '',
                'status' => 0,
                'profile_photo_url' => null,
                'sponsors' => [],
                'sponsors_count' => 0,
                'sponsors_names' => '-',
                '_show_url' => '#',
                '_edit_url' => '#',
                '_delete_url' => '#',
            ];
        }
    }

    /**
     * Custom export row mapping
     * Column names match import template for easy re-import
     */
    protected function mapExportRow($item)
    {
        return [
            'school_internal_id'     => $item->school_internal_id,
            'school_student_id'      => $item->school_student_id,
            'full_name'              => $item->full_name,
            'age'                    => $item->age,
            'grade'                  => $item->grade,
            'email'                  => $item->email,
            'phone'                  => $item->phone,
            'dob'                    => $item->dob?->format('Y-m-d'),
            'country_name'           => $item->country_name,
            'address'                => $item->address,
            'city'                   => $item->city,
            'postal_code'            => $item->postal_code,
            'school_name'            => $item->school_name_display,
            'school_type'            => $item->school_type,
            'current_state'          => $item->current_state,
            'grade_mismatch_reason'  => $item->grade_mismatch_reason,
            'sponsorship_start_date' => $item->sponsorship_start_date?->format('Y-m-d'),
            'sponsorship_end_date'   => $item->sponsorship_end_date?->format('Y-m-d'),
            'introduced_by'          => $item->introduced_by,
            'introducer_phone'       => $item->introducer_phone,
            'bank_name'              => $item->bank_name_display,
            'bank_account_number'    => $item->bank_account_number,
            'bank_branch_number'     => $item->bank_branch_number,
            'bank_branch_info'       => $item->bank_branch_info,
            'father_name'            => $item->father_name,
            'father_income'          => $item->father_income,
            'mother_name'            => $item->mother_name,
            'mother_income'          => $item->mother_income,
            'guardian_name'          => $item->guardian_name,
            'guardian_income'        => $item->guardian_income,
            'background_info'        => $item->background_info,
            'internal_comment'       => $item->internal_comment,
            'external_comment'       => $item->external_comment,
            'status'                 => $item->status ? 'Active' : 'Inactive',
        ];
    }

    // =========================================
    // CUSTOM BULK ACTIONS
    // =========================================

    /**
     * Get bulk actions configuration
     */
    public function getBulkActionsConfig(): JsonResponse
    {
        return response()->json([
            'actions' => $this->bulkActions ?? [],
            'route' => route('admin.studentsponsorship.school-students.bulk-action'),
        ]);
    }

    /**
     * Handle bulk actions from DataTable
     */
    public function handleBulkAction(Request $request): JsonResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        switch ($action) {
            case 'delete':
                $count = SchoolStudent::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => "{$count} students deleted"]);
            
            case 'activate':
                $count = SchoolStudent::whereIn('id', $ids)->update(['status' => 1]);
                return response()->json(['success' => true, 'message' => "{$count} students activated"]);
            
            case 'deactivate':
                $count = SchoolStudent::whereIn('id', $ids)->update(['status' => 0]);
                return response()->json(['success' => true, 'message' => "{$count} students deactivated"]);
            
            default:
                return response()->json(['success' => false, 'message' => 'Unknown action'], 400);
        }
    }

    /**
     * Bulk activate students
     */
    public function bulkActivate(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $count = SchoolStudent::whereIn('id', $ids)->update(['status' => 1]);
        return response()->json(['success' => true, 'message' => "{$count} students activated"]);
    }

    /**
     * Bulk deactivate students
     */
    public function bulkDeactivate(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $count = SchoolStudent::whereIn('id', $ids)->update(['status' => 0]);
        return response()->json(['success' => true, 'message' => "{$count} students deactivated"]);
    }

    /**
     * Bulk delete students
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = SchoolStudent::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$deleted} students deleted"]);
    }

    // =========================================
    // INDEX PAGE
    // =========================================

    public function index()
    {
        // Stats for in-progress students only
        $stats = [
            'total' => DB::table('school_students')->where('current_state', 'inprogress')->count(),
            'active' => DB::table('school_students')->where('current_state', 'inprogress')->where('status', 1)->count(),
            'inactive' => DB::table('school_students')->where('current_state', 'inprogress')->where('status', 0)->count(),
        ];

        $schools = SchoolName::where('status', 1)->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        $currentState = 'inprogress';
        $pageTitle = 'School Students';
        
        return view('studentsponsorship::school-students.index', compact('stats', 'schools', 'grades', 'currentState', 'pageTitle'));
    }

    public function completed()
    {
        // Stats for completed students only
        $stats = [
            'total' => DB::table('school_students')->where('current_state', 'complete')->count(),
            'active' => DB::table('school_students')->where('current_state', 'complete')->where('status', 1)->count(),
            'inactive' => DB::table('school_students')->where('current_state', 'complete')->where('status', 0)->count(),
        ];

        $schools = SchoolName::where('status', 1)->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        
        return view('studentsponsorship::school-students.completed', compact('stats', 'schools', 'grades'));
    }

    // =========================================
    // DATATABLE HANDLER
    // =========================================

    public function handleData(Request $request)
    {
        // Import (POST with file) - delegate to trait
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->dtImport($request);
        }

        // Template Download - delegate to trait
        if ($request->has('template')) {
            return $this->dtTemplate();
        }

        // Export - delegate to trait
        if ($request->has('export')) {
            return $this->dtExport($request);
        }

        // Get bulk actions config
        if ($request->has('bulk_actions')) {
            return $this->getBulkActionsConfig();
        }

        // List with search, filter, sort, pagination
        try {
            $query = SchoolStudent::with(['school']);

            // ALWAYS filter by inprogress (completed has its own endpoint)
            $query->where('current_state', 'inprogress');

            // Search
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('school_internal_id', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('status', $status == '1' || $status == 'active');
            }
            if ($request->filled('grade')) {
                $query->where('grade', $request->grade);
            }
            if ($request->filled('school_id')) {
                $query->where('school_name_id', $request->school_id);
            }

            $totalRecords = SchoolStudent::where('current_state', 'inprogress')->count();
            $filteredRecords = $query->count();

            // Sorting
            $sortCol = $request->input('sort', 'id');
            $sortDir = $request->input('dir', 'desc');
            
            if (in_array($sortCol, ['id', 'full_name', 'email', 'grade', 'status', 'age', 'created_at'])) {
                $query->orderBy($sortCol, $sortDir);
            } else {
                $query->orderBy('id', 'desc');
            }

            // Pagination
            $perPage = min($request->input('per_page', 10), 100);
            $page = $request->input('page', 1);
            
            $data = $query->paginate($perPage);
            
            // Calculate starting row number for this page
            $startRow = ($data->currentPage() - 1) * $data->perPage() + 1;

            $items = collect($data->items())->map(function ($student, $index) use ($startRow) {
                $row = $this->mapRow($student);
                $row['_row_num'] = $startRow + $index;
                return $row;
            });

            return response()->json([
                'data' => $items,
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
            ]);

        } catch (\Exception $e) {
            \Log::error('SchoolStudent DataTable Error: ' . $e->getMessage());
            return response()->json([
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle completed students data (separate endpoint for completed page)
     */
    public function handleCompletedData(Request $request)
    {
        // Export - delegate to trait
        if ($request->has('export')) {
            // Temporarily set current_state for export
            $request->merge(['current_state' => 'complete']);
            return $this->dtExport($request);
        }

        // List with search, filter, sort, pagination - ONLY COMPLETE STUDENTS
        try {
            $query = SchoolStudent::with(['school']);

            // ALWAYS filter by complete state
            $query->where('current_state', 'complete');

            // Search
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('school_internal_id', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('completed_year')) {
                $query->where('completed_year', $request->completed_year);
            }
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('status', $status == '1' || $status == 'active');
            }
            if ($request->filled('grade')) {
                $query->where('grade', $request->grade);
            }
            if ($request->filled('school_id')) {
                $query->where('school_name_id', $request->school_id);
            }

            $totalRecords = SchoolStudent::where('current_state', 'complete')->count();
            $filteredRecords = $query->count();

            // Sorting
            $sortCol = $request->input('sort', 'id');
            $sortDir = $request->input('dir', 'desc');
            
            if (in_array($sortCol, ['id', 'full_name', 'email', 'grade', 'status', 'age', 'completed_year', 'created_at'])) {
                $query->orderBy($sortCol, $sortDir);
            } else {
                $query->orderBy('id', 'desc');
            }

            // Pagination
            $perPage = min($request->input('per_page', 10), 100);
            
            $data = $query->paginate($perPage);
            
            // Calculate starting row number for this page
            $startRow = ($data->currentPage() - 1) * $data->perPage() + 1;

            $items = collect($data->items())->map(function ($student, $index) use ($startRow) {
                $row = $this->mapRow($student);
                $row['_row_num'] = $startRow + $index;
                return $row;
            });

            return response()->json([
                'data' => $items,
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
            ]);

        } catch (\Exception $e) {
            \Log::error('SchoolStudent Completed DataTable Error: ' . $e->getMessage());
            return response()->json([
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10,
                'error' => $e->getMessage()
            ]);
        }
    }

    // =========================================
    // CRUD METHODS (unchanged)
    // =========================================

    public function create()
    {
        $schools = SchoolName::where('status', 1)->orderBy('name')->get();
        $countries = DB::table('countries')->orderBy('short_name')->get();
        $banks = DB::table('banks')->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        $schoolTypes = config('studentsponsorship.school_types', [
            'Type 1AB' => 'Type 1AB',
            'Type 1C' => 'Type 1C',
            'Type 2' => 'Type 2',
            'Type 3' => 'Type 3',
        ]);
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        
        return view('studentsponsorship::school-students.create', compact('schools', 'countries', 'banks', 'grades', 'schoolTypes', 'gradeAgeMapping'));
    }

    public function store(Request $request)
    {
        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        $ageGradeCheck = $this->validateAgeGrade($age, $grade);

        $rules = [
            'school_internal_id' => 'required|string|max:50|unique:school_students,school_internal_id',
            'school_student_id' => 'required|integer|unique:school_students,school_student_id',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:school_students,email',
            'phone' => 'nullable|string|max:30',
            'dob' => 'nullable|date',
            'age' => 'required|integer|min:1|max:100',
            'country_id' => 'nullable|integer',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'grade' => 'required|string|max:20',
            'grade_mismatch_reason' => 'nullable|string|max:255',
            'current_state' => 'required|in:inprogress,complete',
            'school_type' => 'nullable|string|max:50',
            'school_id' => 'nullable|exists:school_names,id',
            'sponsorship_start_date' => 'nullable|date',
            'sponsorship_end_date' => 'nullable|date|after_or_equal:sponsorship_start_date',
            'introduced_by' => 'nullable|string|max:255',
            'introducer_phone' => 'nullable|string|max:30',
            'bank_id' => 'nullable|integer',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch_number' => 'nullable|string|max:50',
            'bank_branch_info' => 'nullable|string|max:500',
            'father_name' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string|max:5000',
            'internal_comment' => 'nullable|string|max:5000',
            'external_comment' => 'nullable|string|max:5000',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'status' => 'boolean',
        ];
        
        if ($ageGradeCheck['is_too_young']) {
            return back()->withErrors(['age' => "Age {$age} is too young for Grade {$grade}. Minimum age is {$ageGradeCheck['expected_min']}."])->withInput();
        }
        if ($ageGradeCheck['is_too_old']) {
            return back()->withErrors(['age' => "Age {$age} is too old for Grade {$grade}. Maximum allowed is {$ageGradeCheck['max_allowed']} (1 year older with reason)."])->withInput();
        }
        
        if ($ageGradeCheck['is_one_year_older']) {
            $rules['grade_mismatch_reason'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($rules);
        $validated['status'] = $request->boolean('status', true);
        
        if ($ageGradeCheck['is_valid']) {
            $validated['grade_mismatch_reason'] = null;
        }

        $student = SchoolStudent::create($validated);

        if ($request->hasFile('profile_photo')) {
            try {
                $student->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo');
            } catch (\Exception $e) {
                \Log::error('Profile photo upload failed on create', ['id' => $student->id, 'error' => $e->getMessage()]);
                // Continue without photo - don't fail the whole operation
            }
        }

        return redirect()->route('admin.studentsponsorship.school-students.edit', HashId::encode($student->id))
            ->with('success', 'Student created successfully!');
    }

    public function show($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::with(['school', 'country'])->findOrFail($id);
        $hashId = HashId::encode($id);
        
        $reportCards = DB::table('school_report_cards')
            ->where('student_school_id', $student->school_student_id)
            ->orderBy('upload_date', 'desc')
            ->get()
            ->map(function ($card) use ($hashId) {
                $card->url = route('admin.studentsponsorship.school-students.view-report-card', [$hashId, $card->id]);
                $card->term_display = str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $card->term);
                return $card;
            });
        
        return view('studentsponsorship::school-students.show', compact('student', 'reportCards'));
    }

    public function edit($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::with(['school', 'country'])->findOrFail($id);
        $schools = SchoolName::where('status', 1)->orderBy('name')->get();
        $countries = DB::table('countries')->orderBy('short_name')->get();
        $banks = DB::table('banks')->orderBy('name')->get();
        $grades = config('studentsponsorship.school_grades', []);
        $schoolTypes = config('studentsponsorship.school_types', [
            'Type 1AB' => 'Type 1AB',
            'Type 1C' => 'Type 1C',
            'Type 2' => 'Type 2',
            'Type 3' => 'Type 3',
        ]);
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        return view('studentsponsorship::school-students.edit', compact('student', 'schools', 'countries', 'banks', 'grades', 'schoolTypes', 'gradeAgeMapping'));
    }

    public function update(Request $request, $hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        $ageGradeCheck = $this->validateAgeGrade($age, $grade);

        $rules = [
            'school_internal_id' => 'required|string|max:50|unique:school_students,school_internal_id,' . $id,
            'school_student_id' => 'required|integer|unique:school_students,school_student_id,' . $id,
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:school_students,email,' . $id,
            'phone' => 'nullable|string|max:30',
            'dob' => 'nullable|date',
            'age' => 'required|integer|min:1|max:100',
            'country_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'grade' => 'required|string|max:20',
            'grade_mismatch_reason' => 'nullable|string|max:255',
            'current_state' => 'required|in:inprogress,complete',
            'school_type' => 'nullable|string|max:50',
            'school_id' => 'nullable|exists:school_names,id',
            'sponsorship_start_date' => 'nullable|date',
            'sponsorship_end_date' => 'nullable|date|after_or_equal:sponsorship_start_date',
            'introduced_by' => 'nullable|string|max:255',
            'introducer_phone' => 'nullable|string|max:30',
            'bank_id' => 'nullable|integer',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch_number' => 'nullable|string|max:50',
            'bank_branch_info' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string',
            'internal_comment' => 'nullable|string',
            'external_comment' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ];
        
        if ($ageGradeCheck['is_too_young']) {
            return back()->withErrors(['age' => "Age {$age} is too young for Grade {$grade}. Minimum age is {$ageGradeCheck['expected_min']}."])->withInput();
        }
        if ($ageGradeCheck['is_too_old']) {
            return back()->withErrors(['age' => "Age {$age} is too old for Grade {$grade}. Maximum allowed is {$ageGradeCheck['max_allowed']} (1 year older with reason)."])->withInput();
        }
        
        if ($ageGradeCheck['is_one_year_older']) {
            $rules['grade_mismatch_reason'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($rules);

        $validated['status'] = $request->boolean('status', true);
        
        if ($ageGradeCheck['is_valid']) {
            $validated['grade_mismatch_reason'] = null;
        }
        
        $student->update($validated);

        if ($request->hasFile('profile_photo')) {
            try {
                $student->clearMediaCollection('profile_photo');
                $student->addMediaFromRequest('profile_photo')->toMediaCollection('profile_photo');
            } catch (\Exception $e) {
                \Log::error('Profile photo upload failed', ['id' => $id, 'error' => $e->getMessage()]);
                return redirect()->route('admin.studentsponsorship.school-students.edit', HashId::encode($student->id))
                    ->with('warning', 'Student updated but photo upload failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.studentsponsorship.school-students.edit', HashId::encode($student->id))
            ->with('success', 'Student updated successfully!');
    }

    public function destroy($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        $student->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }
        return redirect()->route('admin.studentsponsorship.school-students.index')->with('success', 'Student deleted successfully!');
    }

    // =========================================
    // AJAX ENDPOINTS
    // =========================================

    public function addSchool(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $school = SchoolName::create(['name' => $request->name, 'status' => true]);
        return response()->json(['success' => true, 'id' => $school->id, 'name' => $school->name]);
    }

    public function addBank(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        try {
            $bank = DB::table('banks')->insertGetId([
                'name' => $request->name,
                'created_on' => now(),
            ]);
            return response()->json(['success' => true, 'id' => $bank, 'name' => $request->name]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add bank: ' . $e->getMessage()], 500);
        }
    }

    public function validateAgeGradeAjax(Request $request)
    {
        $age = (int)$request->input('age');
        $grade = $request->input('grade');
        return response()->json($this->validateAgeGrade($age, $grade));
    }

    /**
     * Remove profile photo
     */
    public function removeProfilePhoto($hash)
    {
        try {
            $id = $this->resolveId($hash);
            $student = SchoolStudent::findOrFail($id);
            
            if ($student->hasProfilePhoto()) {
                $student->clearMediaCollection('profile_photo');
                return response()->json(['success' => true, 'message' => 'Photo removed successfully']);
            }
            
            return response()->json(['success' => false, 'message' => 'No photo to remove'], 404);
        } catch (\Exception $e) {
            \Log::error('Remove profile photo error', ['hash' => $hash, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to remove photo'], 500);
        }
    }

    // =========================================
    // REPORT CARDS
    // =========================================

    public function uploadReportCard(Request $request, $hash)
    {
        try {
            // Resolve hashed ID
            $id = $this->resolveId($hash);
            $hashId = HashId::encode($id);
            
            // Validate student exists
            $student = SchoolStudent::findOrFail($id);
            
            // Basic validation
            $validator = \Validator::make($request->all(), [
                'report_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'title' => 'required|string|max:255',
                'term' => 'required|string|in:Term1,Term2,Term3',
                'upload_date' => 'required|date|before_or_equal:today',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('report_card');
            
            // Enhanced file security validation
            $fileValidation = $this->validateFileContent($file, ['pdf', 'jpg', 'jpeg', 'png']);
            if (!$fileValidation['valid']) {
                \Log::warning('Report card upload security check failed', [
                    'student_id' => $id,
                    'filename' => $file->getClientOriginalName(),
                    'error' => $fileValidation['error']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $fileValidation['error']
                ], 422);
            }
            
            // Sanitize filename
            $filename = $this->sanitizeString($request->input('title'));
            $filename = preg_replace('/[^a-zA-Z0-9\s\-_\.]/', '', $filename);
            $filename = substr($filename, 0, 255);
            
            $fileContent = file_get_contents($file->getRealPath());
            $sha256 = hash('sha256', $fileContent);
            $mimeType = $fileValidation['mime']; // Use validated MIME type
            $fileSize = $file->getSize();
            $term = $request->input('term');
            $uploadDate = $request->input('upload_date');
            
            // Check for duplicate (same file for same student/term)
            $exists = DB::table('school_report_cards')
                ->where('student_school_id', $student->school_student_id)
                ->where('sha256', $sha256)
                ->exists();
                
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This exact file has already been uploaded for this student.'
                ], 422);
            }
            
            $reportCardId = DB::table('school_report_cards')->insertGetId([
                'student_school_id' => $student->school_student_id,
                'filename' => $filename,
                'term' => $term,
                'upload_date' => $uploadDate,
                'report_card_file' => null,
                'file_blob' => $fileContent,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'sha256' => $sha256,
                'created_on' => now(),
            ]);

            \Log::info('Report card uploaded successfully', [
                'student_id' => $id,
                'report_card_id' => $reportCardId,
                'file_size' => $fileSize
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report card uploaded successfully',
                'report_card' => [
                    'id' => $reportCardId, 
                    'filename' => $filename, 
                    'term' => str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $term),
                    'upload_date' => $uploadDate,
                    'url' => url('admin/studentsponsorship/school-students/'.$hashId.'/report-cards/'.$reportCardId.'/view')
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? 0;
            $errorMessage = 'Database error';
            
            if ($errorCode == 1406) {
                $errorMessage = 'File too large for database column. Please run: ALTER TABLE school_report_cards MODIFY COLUMN file_blob LONGBLOB;';
            } elseif ($errorCode == 1062) {
                $errorMessage = 'Duplicate entry';
            } else {
                $errorMessage = 'Database error: ' . ($e->errorInfo[2] ?? 'Unknown error');
            }
            
            \Log::error('Report card upload database error', ['error_code' => $errorCode, 'student_id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Report card upload error', ['error' => $e->getMessage(), 'student_id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed. Please try again.'
            ], 500);
        }
    }

    public function viewReportCard($hash, $reportCardId)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        $reportCard = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->first();
            
        if (!$reportCard || !$reportCard->file_blob) {
            abort(404, 'Report card not found');
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
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function downloadReportCard($hash, $reportCardId)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        $reportCard = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->first();
            
        if (!$reportCard || !$reportCard->file_blob) {
            abort(404, 'Report card not found');
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
    }

    public function deleteReportCard($hash, $reportCardId)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        $deleted = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->delete();
        
        if ($deleted) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }

    public function approveReportCard($hash, $reportCardId)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        
        // Get report card info
        $reportCard = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->first();
        
        if (!$reportCard) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $updated = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);
        
        if ($updated) {
            // If student has portal account, create notification
            if ($student->user_id) {
                DB::table('notifications')->insert([
                    'user_id' => $student->user_id,
                    'user_type' => 'user',
                    'from_user_id' => auth()->id(),
                    'from_user_type' => 'admin',
                    'title' => 'Report Card Approved ✓',
                    'message' => "Great news! Your report card \"{$reportCard->filename}\" ({$reportCard->term}) has been approved.",
                    'type' => 'success',
                    'url' => '/client/student-portal/my-form',
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Report card approved']);
        }
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }

    public function rejectReportCard(Request $request, $hash, $reportCardId)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        
        // Get the report card before deleting
        $reportCard = DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->where('student_school_id', $student->school_student_id)
            ->first();
        
        if (!$reportCard) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $reason = $request->input('reason', 'No reason provided');
        
        // If student has portal account, create notification
        if ($student->user_id) {
            DB::table('notifications')->insert([
                'user_id' => $student->user_id,
                'user_type' => 'user',
                'from_user_id' => auth()->id(),
                'from_user_type' => 'admin',
                'title' => 'Report Card Rejected',
                'message' => "Your report card \"{$reportCard->filename}\" ({$reportCard->term}) has been rejected. Reason: {$reason}. Please upload a new report card.",
                'type' => 'warning',
                'url' => '/client/student-portal/my-form',
                'is_read' => 0,
                'created_at' => now(),
            ]);
        }
        
        // Delete the report card
        DB::table('school_report_cards')
            ->where('id', $reportCardId)
            ->delete();
        
        return response()->json(['success' => true, 'message' => 'Report card rejected and student notified']);
    }

    // =========================================
    // HELPER METHODS
    // =========================================

    protected function validateAgeGrade($age, $grade)
    {
        $gradeAgeMapping = config('studentsponsorship.grade_age_mapping', []);
        
        if (!isset($gradeAgeMapping[$grade])) {
            return [
                'is_valid' => true,
                'is_too_young' => false,
                'is_too_old' => false,
                'is_one_year_older' => false,
                'expected_min' => null,
                'expected_max' => null,
                'max_allowed' => null,
                'message' => 'Grade not found in mapping',
            ];
        }

        $range = $gradeAgeMapping[$grade];
        $expectedMin = $range['min'];
        $expectedMax = $range['max'];
        $maxAllowed = $expectedMax + 1;

        $isValid = ($age >= $expectedMin && $age <= $expectedMax);
        $isTooYoung = ($age < $expectedMin);
        $isTooOld = ($age > $maxAllowed);
        $isOneYearOlder = ($age == $maxAllowed);

        $message = $isValid ? 'Age is within expected range' : 
                   ($isTooYoung ? 'Age is below minimum' : 
                   ($isOneYearOlder ? 'Age is 1 year older - reason required' : 
                   ($isTooOld ? 'Age exceeds maximum allowed' : 'Unknown')));

        return [
            'is_valid' => $isValid,
            'is_too_young' => $isTooYoung,
            'is_too_old' => $isTooOld,
            'is_one_year_older' => $isOneYearOlder,
            'expected_min' => $expectedMin,
            'expected_max' => $expectedMax,
            'max_allowed' => $maxAllowed,
            'message' => $message,
        ];
    }

    // =========================================
    // STUDENT PORTAL ACCESS METHODS
    // =========================================

    /**
     * Check if email is available for portal account
     */
    public function checkEmailAvailability(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'student_hash' => 'required|string',
        ]);

        $email = strtolower(trim($request->email));
        
        // Check in users table
        $existsInUsers = \DB::table('users')->where('email', $email)->exists();
        if ($existsInUsers) {
            return response()->json([
                'available' => false,
                'message' => 'This email already exists in the system. Not available.'
            ]);
        }

        // Check in university_students table (different student)
        $studentId = $this->resolveId($request->student_hash);
        $existsInUni = \DB::table('university_students')
            ->where('email', $email)
            ->exists();
        if ($existsInUni) {
            return response()->json([
                'available' => false,
                'message' => 'This email is already used by a university student. Not available.'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Email is available.'
        ]);
    }

    /**
     * Create portal account for school student
     */
    public function createPortalAccount(Request $request, $hash)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        $email = strtolower(trim($request->email));

        // Check if student already has portal account
        if ($student->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student already has a portal account.'
            ], 422);
        }

        // Check email availability in users
        if (\DB::table('users')->where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email already exists. Not available.'
            ], 422);
        }

        // Check email in university_students
        if (\DB::table('university_students')->where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is used by a university student. Not available.'
            ], 422);
        }

        try {
            \DB::beginTransaction();

            // Create user account
            $userId = \DB::table('users')->insertGetId([
                'name' => $student->full_name,
                'email' => $email,
                'password' => \Hash::make($request->password),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link user to student
            $student->user_id = $userId;
            $student->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Portal account created successfully.',
                'user_id' => $userId
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create portal account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get portal account status for a student
     */
    public function getPortalStatus($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        if ($student->user_id) {
            $user = \DB::table('users')->where('id', $student->user_id)->first();
            return response()->json([
                'has_account' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                ]
            ]);
        }

        return response()->json([
            'has_account' => false,
            'suggested_email' => $student->email
        ]);
    }

    /**
     * Deactivate portal account
     */
    public function deactivatePortalAccount($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        if (!$student->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student does not have a portal account.'
            ], 422);
        }

        \DB::table('users')->where('id', $student->user_id)->update([
            'is_active' => 0,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Portal account deactivated.'
        ]);
    }

    /**
     * Activate portal account
     */
    public function activatePortalAccount($hash)
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        if (!$student->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student does not have a portal account.'
            ], 422);
        }

        \DB::table('users')->where('id', $student->user_id)->update([
            'is_active' => 1,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Portal account activated.'
        ]);
    }

    /**
     * Reset portal account password
     */
    public function resetPortalPassword(Request $request, $hash)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);

        if (!$student->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student does not have a portal account.'
            ], 422);
        }

        \DB::table('users')->where('id', $student->user_id)->update([
            'password' => \Hash::make($request->password),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }

    /**
     * Rollback a completed student to next year
     * - Removes portal access (sets user_id to null)
     * - Sets student to inactive
     * - Changes current_state to 'in_progress'
     * - Increases grade by 1
     */
    public function studentRollback($hash): JsonResponse
    {
        $id = $this->resolveId($hash);
        $student = SchoolStudent::findOrFail($id);
        
        // Get current grade and calculate new grade
        $currentGrade = (int) $student->grade;
        $newGrade = $currentGrade + 1;
        
        // Cap at grade 13 (or adjust as needed)
        if ($newGrade > 13) {
            $newGrade = 13;
        }
        
        // Update student
        $student->update([
            'user_id' => null,           // Remove portal access
            'status' => 0,                // Set to inactive
            'current_state' => 'complete', // Mark as complete (DB uses 'complete')
            'grade' => $newGrade,         // Promote to next grade
        ]);
        
        // Get grade label for response
        $gradeLabels = [
            1 => 'Grade 1', 2 => 'Grade 2', 3 => 'Grade 3', 4 => 'Grade 4',
            5 => 'Grade 5', 6 => 'Grade 6', 7 => 'Grade 7', 8 => 'Grade 8',
            9 => 'Grade 9', 10 => 'Grade 10', 11 => 'Grade 11', 12 => 'Grade 12',
            13 => 'Grade 13'
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Student rolled back successfully',
            'new_grade' => $gradeLabels[$newGrade] ?? "Grade $newGrade",
            'data' => [
                'user_id' => null,
                'status' => 0,
                'current_state' => 'complete',
                'grade' => $newGrade
            ]
        ]);
    }

    /**
     * Rollback ALL in_progress students at once
     * - Removes portal access (user_id = null)
     * - Sets inactive (status = 0)
     * - Sets current_state to 'completed'
     * - Increases grade by 1
     */
    public function rollbackAll(Request $request): JsonResponse
    {
        $completedYear = $request->input('completed_year', date('Y'));
        
        // Get all IN PROGRESS students
        $students = \DB::table('school_students')
            ->where('current_state', 'inprogress')
            ->whereNull('deleted_at')
            ->get();
        
        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No in-progress students found to rollback'
            ]);
        }
        
        $count = 0;
        $usersDeleted = 0;
        
        foreach ($students as $student) {
            $currentGrade = (int) $student->grade;
            $newGrade = min($currentGrade + 1, 13); // Cap at grade 13
            
            // If student has portal access, delete the user record
            if ($student->user_id) {
                \DB::table('users')->where('id', $student->user_id)->delete();
                $usersDeleted++;
            }
            
            \DB::table('school_students')
                ->where('id', $student->id)
                ->update([
                    'user_id' => null,           // Remove portal access reference
                    'status' => 0,                // Set to inactive
                    'current_state' => 'complete', // Mark as completed
                    'completed_year' => $completedYear, // Save the completed year
                    'grade' => $newGrade,         // Promote to next grade
                    'updated_at' => now(),
                ]);
            
            $count++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Successfully rolled back $count students for year $completedYear",
            'count' => $count,
            'users_deleted' => $usersDeleted,
            'completed_year' => $completedYear
        ]);
    }
}
