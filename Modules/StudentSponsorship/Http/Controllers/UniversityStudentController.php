<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\StudentSponsorship\Models\UniversityStudent;
use Modules\StudentSponsorship\Models\UniversityName;
use Modules\StudentSponsorship\Models\UniversityProgram;
use Modules\StudentSponsorship\Models\UniversityReportCard;
use Modules\StudentSponsorship\Helpers\HashId;
use Modules\Core\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UniversityStudentController extends AdminController
{
    use DataTableTrait;

    // =========================================
    // DATATABLE v2.0 CONFIGURATION
    // =========================================
    
    protected $model = UniversityStudent::class;
    protected $with = ['university', 'program'];
    protected $searchable = ['name', 'email', 'contact_no', 'university_internal_id', 'university_id'];
    protected $sortable = ['id', 'name', 'email', 'university_year_of_study', 'active', 'created_at'];
    protected $filterable = ['status', 'year_of_study', 'university_id', 'program_id'];
    protected $routePrefix = 'admin.studentsponsorship.university-students';
    protected $uniqueField = 'university_internal_id';

    // =========================================
    // IMPORT CONFIGURATION
    // =========================================

    protected $importable = [
        'university_internal_id'          => 'required|string|max:50',
        'name'                            => 'required|string|max:255',
        'email'                           => 'nullable|email|max:255',
        'contact_no'                      => 'nullable|string|max:50',
        'university_name'                 => 'nullable|string|max:255',
        'program_name'                    => 'nullable|string|max:255',
        'university_year_of_study'        => 'nullable|in:1Y1S,1Y2S,2Y1S,2Y2S,3Y1S,3Y2S,4Y1S,4Y2S,5Y1S,5Y2S',
        'university_student_dob'          => 'nullable|date',
        'country_name'                    => 'nullable|string|max:100',
        'address'                         => 'nullable|string|max:1000',
        'city'                            => 'nullable|string|max:100',
        'zip'                             => 'nullable|string|max:20',
        'bank_name'                       => 'nullable|string|max:255',
        'university_bank_branch_info'     => 'nullable|string|max:255',
        'university_bank_branch_number'   => 'nullable|string|max:100',
        'university_bank_account_no'      => 'nullable|string|max:100',
        'university_sponsorship_start_date' => 'nullable|date',
        'university_sponsorship_end_date'   => 'nullable|date',
        'university_father_name'          => 'nullable|string|max:255',
        'university_father_income'        => 'nullable|numeric|min:0',
        'university_mother_name'          => 'nullable|string|max:255',
        'university_mother_income'        => 'nullable|numeric|min:0',
        'university_guardian_name'        => 'nullable|string|max:255',
        'university_guardian_income'      => 'nullable|numeric|min:0',
        'university_introducedby'         => 'nullable|string|max:255',
        'university_introducedph'         => 'nullable|string|max:50',
        'background_info'                 => 'nullable|string',
        'internal_comment'                => 'nullable|string',
        'external_comment'                => 'nullable|string',
        'current_state'                   => 'nullable|in:inprogress,complete',
        'active'                          => 'nullable',
    ];

    protected $importLookups = [
        'university_name' => [
            'table'   => 'university_names',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'university_name_id',
            'create'  => true,  // Auto-create if not exists
        ],
        'program_name' => [
            'table'   => 'university_programs',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'university_program_id',
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
            'create_data' => ['created_on' => null],
        ],
    ];

    // Default values for empty import columns
    protected $importDefaults = [
        'active'        => 1,
        'current_state' => 'inprogress',
    ];

    protected $bulkActions = [
        'delete'     => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
        'activate'   => ['label' => 'Activate', 'color' => 'green'],
        'deactivate' => ['label' => 'Deactivate', 'color' => 'yellow'],
    ];

    // =========================================
    // OVERRIDE: Custom Row Mapping
    // =========================================

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
                'university_internal_id' => $item->university_internal_id ?? '',
                'name' => $item->name ?? '',
                'email' => $item->email ?? '',
                'contact_no' => $item->contact_no ?? '',
                'university_name' => $item->university->name ?? 'N/A',
                'program_name' => $item->program->name ?? 'N/A',
                'university_year_of_study' => $item->university_year_of_study ?? '',
                'current_state' => $item->current_state ?? 'inprogress',
                'completed_year' => $item->completed_year ?? null,
                'active' => $item->active ? 1 : 0,
                'profile_photo_url' => $item->hasMedia('profile_photo') ? $item->getFirstMediaUrl('profile_photo') : null,
                'sponsors' => $sponsorsList,
                'sponsors_count' => count($sponsorsList),
                'sponsors_names' => implode(', ', $sponsorNames) ?: '-',
                '_show_url' => route('admin.studentsponsorship.university-students.show', $hashId),
                '_edit_url' => route('admin.studentsponsorship.university-students.edit', $hashId),
                '_delete_url' => route('admin.studentsponsorship.university-students.destroy', $hashId),
            ];
        } catch (\Exception $e) {
            Log::error('UniversityStudent mapRow error', ['id' => $item->id ?? 'unknown', 'error' => $e->getMessage()]);
            return [
                'id' => $item->id ?? 0,
                'hash_id' => '',
                'university_internal_id' => '',
                'name' => 'Error loading',
                'email' => '',
                'contact_no' => '',
                'university_name' => 'N/A',
                'program_name' => 'N/A',
                'university_year_of_study' => '',
                'current_state' => 'inprogress',
                'completed_year' => null,
                'active' => 0,
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

    protected function mapExportRow($item)
    {
        return [
            'university_internal_id'       => $item->university_internal_id,
            'university_id'                => $item->university_id,
            'name'                         => $item->name,
            'email'                        => $item->email,
            'contact_no'                   => $item->contact_no,
            'university_name'              => $item->university->name ?? '',
            'program_name'                 => $item->program->name ?? '',
            'university_year_of_study'     => $item->university_year_of_study,
            'university_student_dob'       => $item->university_student_dob?->format('Y-m-d'),
            'address'                      => $item->address,
            'city'                         => $item->city,
            'zip'                          => $item->zip,
            'university_bank_branch_info'  => $item->university_bank_branch_info,
            'university_bank_branch_number'=> $item->university_bank_branch_number,
            'university_bank_account_no'   => $item->university_bank_account_no,
            'university_sponsorship_start_date' => $item->university_sponsorship_start_date?->format('Y-m-d'),
            'university_sponsorship_end_date'   => $item->university_sponsorship_end_date?->format('Y-m-d'),
            'university_father_name'       => $item->university_father_name,
            'university_father_income'     => $item->university_father_income,
            'university_mother_name'       => $item->university_mother_name,
            'university_mother_income'     => $item->university_mother_income,
            'university_guardian_name'     => $item->university_guardian_name,
            'university_guardian_income'   => $item->university_guardian_income,
            'university_introducedby'      => $item->university_introducedby,
            'university_introducedph'      => $item->university_introducedph,
            'background_info'              => $item->background_info,
            'internal_comment'             => $item->internal_comment,
            'external_comment'             => $item->external_comment,
            'active'                       => $item->active ? 'Active' : 'Inactive',
        ];
    }

    // =========================================
    // INDEX
    // =========================================

    public function index(Request $request)
    {
        $stats = [
            'total' => UniversityStudent::where('current_state', 'inprogress')->count(),
            'active' => UniversityStudent::where('current_state', 'inprogress')->where('active', true)->count(),
            'inactive' => UniversityStudent::where('current_state', 'inprogress')->where('active', false)->count(),
        ];

        $universities = UniversityName::orderBy('name')->get();
        $programs = UniversityProgram::orderBy('name')->get();
        $yearsOfStudy = UniversityStudent::YEAR_OF_STUDY;
        $currentState = 'inprogress';
        $pageTitle = 'University Students';

        return view('studentsponsorship::university-students.index', compact(
            'stats', 'universities', 'programs', 'yearsOfStudy', 'currentState', 'pageTitle'
        ));
    }

    public function completed(Request $request)
    {
        $stats = [
            'total' => UniversityStudent::where('current_state', 'complete')->count(),
            'active' => UniversityStudent::where('current_state', 'complete')->where('active', true)->count(),
            'inactive' => UniversityStudent::where('current_state', 'complete')->where('active', false)->count(),
        ];

        $universities = UniversityName::orderBy('name')->get();
        $programs = UniversityProgram::orderBy('name')->get();
        $yearsOfStudy = UniversityStudent::YEAR_OF_STUDY;

        return view('studentsponsorship::university-students.completed', compact(
            'stats', 'universities', 'programs', 'yearsOfStudy'
        ));
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
            // Optimized: Select only needed columns, eager load with specific columns
            $query = UniversityStudent::query()
                ->select([
                    'id', 'name', 'email', 'contact_no', 'university_internal_id',
                    'university_name_id', 'university_program_id', 'university_year_of_study',
                    'current_state', 'active'
                ])
                ->with([
                    'university:id,name',
                    'program:id,name'
                ]);

            // ALWAYS filter by inprogress (completed has its own endpoint)
            $query->where('current_state', 'inprogress');

            // Search - support both formats
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('contact_no', 'like', "%{$search}%")
                      ->orWhere('university_internal_id', 'like', "%{$search}%")
                      ->orWhere('university_id', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('active', $status == '1' || $status == 'active');
            }
            if ($request->filled('year_of_study')) {
                $query->where('university_year_of_study', $request->year_of_study);
            }
            if ($request->filled('university_id')) {
                $query->where('university_name_id', $request->university_id);
            }
            if ($request->filled('program_id')) {
                $query->where('university_program_id', $request->program_id);
            }

            $totalRecords = UniversityStudent::where('current_state', 'inprogress')->count();
            $filteredRecords = $query->count();

            // Sorting - support both formats
            $sortCol = $request->input('sort', 'id');
            $sortDir = $request->input('dir', 'desc');
            
            if (in_array($sortCol, ['id', 'name', 'email', 'university_year_of_study', 'active', 'created_at'])) {
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
            Log::error('UniversityStudent DataTable Error: ' . $e->getMessage());
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
            $request->merge(['current_state' => 'complete']);
            return $this->dtExport($request);
        }

        // List with search, filter, sort, pagination - ONLY COMPLETE STUDENTS
        try {
            $query = UniversityStudent::with(['university', 'program']);

            // ALWAYS filter by complete state
            $query->where('current_state', 'complete');

            // Search
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('contact_no', 'like', "%{$search}%")
                      ->orWhere('university_internal_id', 'like', "%{$search}%")
                      ->orWhere('university_id', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('completed_year')) {
                $query->where('completed_year', $request->completed_year);
            }
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('active', $status == '1' || $status == 'active');
            }
            if ($request->filled('university_year_of_study')) {
                $query->where('university_year_of_study', $request->university_year_of_study);
            }
            if ($request->filled('year_of_study')) {
                $query->where('university_year_of_study', $request->year_of_study);
            }
            if ($request->filled('university_id')) {
                $query->where('university_name_id', $request->university_id);
            }
            if ($request->filled('program_id')) {
                $query->where('university_program_id', $request->program_id);
            }

            $totalRecords = UniversityStudent::where('current_state', 'complete')->count();
            $filteredRecords = $query->count();

            // Sorting
            $sortCol = $request->input('sort', 'id');
            $sortDir = $request->input('dir', 'desc');
            
            if (in_array($sortCol, ['id', 'name', 'email', 'university_year_of_study', 'active', 'completed_year', 'created_at'])) {
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
            Log::error('UniversityStudent Completed DataTable Error: ' . $e->getMessage());
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
    // CRUD OPERATIONS
    // =========================================

    public function create()
    {
        $data = $this->getFormData();
        return view('studentsponsorship::university-students.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        DB::beginTransaction();
        try {
            // Handle new university
            if ($request->filled('new_university_name')) {
                $university = UniversityName::firstOrCreate(
                    ['name' => $request->new_university_name],
                    []
                );
                $validated['university_name_id'] = $university->id;
            }

            // Handle new program
            if ($request->filled('new_program_name')) {
                $program = UniversityProgram::firstOrCreate(
                    ['name' => $request->new_program_name],
                    []
                );
                $validated['university_program_id'] = $program->id;
            }

            // Set defaults
            $validated['active'] = $validated['active'] ?? true;
            
            $student = UniversityStudent::create($validated);

            // Handle photo
            if ($request->hasFile('profile_photo')) {
                $student->addMedia($request->file('profile_photo'))
                    ->toMediaCollection('profile_photo');
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student created successfully',
                    'redirect' => route('admin.studentsponsorship.university-students.edit', $student->hash_id),
                ]);
            }

            return redirect()
                ->route('admin.studentsponsorship.university-students.edit', $student->hash_id)
                ->with('success', 'Student created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create UniversityStudent Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withInput()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    public function show(string $hash)
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        $student->load(['university', 'program', 'reportCards']);
        
        return view('studentsponsorship::university-students.show', compact('student'));
    }

    public function edit(string $hash)
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        $data = $this->getFormData();
        $data['student'] = $student;
        
        return view('studentsponsorship::university-students.edit', $data);
    }

    public function update(Request $request, string $hash)
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        $validated = $this->validateStudent($request, $student->id);

        DB::beginTransaction();
        try {
            // Handle new university
            if ($request->filled('new_university_name')) {
                $university = UniversityName::firstOrCreate(
                    ['name' => $request->new_university_name],
                    []
                );
                $validated['university_name_id'] = $university->id;
            }

            // Handle new program
            if ($request->filled('new_program_name')) {
                $program = UniversityProgram::firstOrCreate(
                    ['name' => $request->new_program_name],
                    []
                );
                $validated['university_program_id'] = $program->id;
            }

            $student->update($validated);

            // Handle photo
            if ($request->hasFile('profile_photo')) {
                $student->clearMediaCollection('profile_photo');
                $student->addMedia($request->file('profile_photo'))
                    ->toMediaCollection('profile_photo');
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student updated successfully',
                ]);
            }

            return redirect()
                ->route('admin.studentsponsorship.university-students.edit', $student->hash_id)
                ->with('success', 'Student updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update UniversityStudent Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    public function destroy(string $hash)
    {
        $student = UniversityStudent::findByHashOrFail($hash);

        try {
            $student->clearMediaCollection('profile_photo');
            $student->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
            }

            return redirect()
                ->route('admin.studentsponsorship.university-students.index')
                ->with('success', 'Student deleted successfully');

        } catch (\Exception $e) {
            Log::error('Delete UniversityStudent Error: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to delete student');
        }
    }

    // =========================================
    // BULK ACTIONS
    // =========================================

    /**
     * Get bulk actions configuration
     */
    public function getBulkActionsConfig(): JsonResponse
    {
        return response()->json([
            'actions' => $this->bulkActions ?? [],
            'route' => route('admin.studentsponsorship.university-students.bulk-action'),
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
                $count = UniversityStudent::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => "{$count} students deleted"]);
            
            case 'activate':
                $count = UniversityStudent::whereIn('id', $ids)->update(['active' => 1]);
                return response()->json(['success' => true, 'message' => "{$count} students activated"]);
            
            case 'deactivate':
                $count = UniversityStudent::whereIn('id', $ids)->update(['active' => 0]);
                return response()->json(['success' => true, 'message' => "{$count} students deactivated"]);
            
            default:
                return response()->json(['success' => false, 'message' => 'Unknown action'], 400);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $count = UniversityStudent::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$count} students deleted"]);
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status', 1);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $count = UniversityStudent::whereIn('id', $ids)->update(['active' => $status]);
        $action = $status ? 'activated' : 'deactivated';
        return response()->json(['success' => true, 'message' => "{$count} students {$action}"]);
    }

    // =========================================
    // PHOTO
    // =========================================

    public function removePhoto(string $hash)
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        $student->clearMediaCollection('profile_photo');
        
        return response()->json(['success' => true, 'message' => 'Photo removed']);
    }

    // =========================================
    // ADD NEW ENTITIES (AJAX)
    // =========================================

    public function addUniversity(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $university = UniversityName::firstOrCreate(
                ['name' => $request->name],
                []
            );

            return response()->json([
                'success' => true,
                'id' => $university->id,
                'name' => $university->name,
                'message' => 'University added'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addProgram(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $program = UniversityProgram::firstOrCreate(
                ['name' => $request->name],
                []
            );

            return response()->json([
                'success' => true,
                'id' => $program->id,
                'name' => $program->name,
                'message' => 'Program added'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addBank(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $bank = DB::table('banks')->where('name', $request->name)->first();
            
            if (!$bank) {
                $id = DB::table('banks')->insertGetId([
                    'name' => $request->name,
                    'created_on' => now()
                ]);
                $bank = (object)['id' => $id, 'name' => $request->name];
            }

            return response()->json([
                'success' => true,
                'id' => $bank->id,
                'name' => $bank->name,
                'message' => 'Bank added'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================
    // EXPORT
    // =========================================

    public function export(Request $request)
    {
        $query = UniversityStudent::with(['university', 'program']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('active', $request->status == '1');
        }
        if ($request->filled('year_of_study')) {
            $query->where('university_year_of_study', $request->year_of_study);
        }

        $students = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = array_keys($this->mapExportRow($students->first() ?? new UniversityStudent()));
        $sheet->fromArray($headers, null, 'A1');

        // Data
        $row = 2;
        foreach ($students as $student) {
            $sheet->fromArray(array_values($this->mapExportRow($student)), null, 'A' . $row);
            $row++;
        }

        $format = $request->input('format', 'xlsx');
        $filename = 'university_students_' . date('Y-m-d_His');

        if ($format === 'csv') {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $filename .= '.csv';
            $contentType = 'text/csv';
        } else {
            $writer = new Xlsx($spreadsheet);
            $filename .= '.xlsx';
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => $contentType,
        ])->deleteFileAfterSend(true);
    }

    // =========================================
    // IMPORT
    // =========================================

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            // Remove header
            $headers = array_shift($rows);
            
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                try {
                    // Skip empty rows - Internal ID and Name are required
                    if (empty($row[0]) || empty($row[1])) {
                        if (!empty($row[1])) {
                            $errors[] = "Row {$rowNum}: Internal ID is required";
                        }
                        continue;
                    }

                    $data = [
                        'university_internal_id' => $row[0] ?? null,
                        'name' => $row[1] ?? null,
                        'email' => $row[2] ?? null,
                        'contact_no' => $row[3] ?? null,
                        // row[4] = university_name (handled below)
                        // row[5] = program_name (handled below)
                        'university_year_of_study' => $row[6] ?? null,
                        'university_student_dob' => $this->parseDate($row[7] ?? null),
                        // row[8] = country_name (handled below)
                        'address' => $row[9] ?? null,
                        'city' => $row[10] ?? null,
                        'zip' => $row[11] ?? null,
                        // row[12] = bank_name (handled below)
                        'university_bank_branch_info' => $row[13] ?? null,
                        'university_bank_branch_number' => $row[14] ?? null,
                        'university_bank_account_no' => $row[15] ?? null,
                        'university_sponsorship_start_date' => $this->parseDate($row[16] ?? null),
                        'university_sponsorship_end_date' => $this->parseDate($row[17] ?? null),
                        'university_father_name' => $row[18] ?? null,
                        'university_father_income' => $this->parseDecimal($row[19] ?? null),
                        'university_mother_name' => $row[20] ?? null,
                        'university_mother_income' => $this->parseDecimal($row[21] ?? null),
                        'university_guardian_name' => $row[22] ?? null,
                        'university_guardian_income' => $this->parseDecimal($row[23] ?? null),
                        'university_introducedby' => $row[24] ?? null,
                        'university_introducedph' => $row[25] ?? null,
                        'background_info' => $row[26] ?? null,
                        'internal_comment' => $row[27] ?? null,
                        'external_comment' => $row[28] ?? null,
                        'current_state' => strtolower($row[29] ?? 'inprogress') === 'complete' ? 'complete' : 'inprogress',
                        'active' => strtolower($row[30] ?? '1') !== '0' && strtolower($row[30] ?? 'active') !== 'inactive',
                    ];

                    // Handle university (column 4)
                    $universityName = $row[4] ?? null;
                    if ($universityName) {
                        $university = UniversityName::firstOrCreate(
                            ['name' => $universityName],
                            []
                        );
                        $data['university_name_id'] = $university->id;
                    }

                    // Handle program
                    $programName = $row[5] ?? null;
                    if ($programName) {
                        $program = UniversityProgram::firstOrCreate(
                            ['name' => $programName],
                            []
                        );
                        $data['university_program_id'] = $program->id;
                    }

                    // Handle country (column 8)
                    $countryName = $row[8] ?? null;
                    if ($countryName) {
                        $country = DB::table('countries')
                            ->where('short_name', 'like', "%{$countryName}%")
                            ->first();
                        if ($country) {
                            $data['country_id'] = $country->country_id;
                        }
                    }

                    // Handle bank (column 12)
                    $bankName = $row[12] ?? null;
                    if ($bankName) {
                        $bank = DB::table('banks')->where('name', $bankName)->first();
                        if (!$bank) {
                            $bankId = DB::table('banks')->insertGetId([
                                'name' => $bankName,
                                'created_on' => now()
                            ]);
                            $data['bank_id'] = $bankId;
                        } else {
                            $data['bank_id'] = $bank->id;
                        }
                    }

                    // Check for existing by internal_id or email
                    $existing = null;
                    if (!empty($data['university_internal_id'])) {
                        $existing = UniversityStudent::where('university_internal_id', $data['university_internal_id'])->first();
                    }
                    if (!$existing && !empty($data['email'])) {
                        $existing = UniversityStudent::where('email', $data['email'])->first();
                    }

                    if ($existing) {
                        $existing->update($data);
                    } else {
                        UniversityStudent::create($data);
                    }

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNum}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$imported} student(s) imported successfully",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Internal ID*', 'Name*', 'Email', 'Contact No',
            'University Name', 'Program Name', 'Year of Study',
            'DOB (YYYY-MM-DD)', 'Age', 'Address', 'City', 'Zip', 'Country',
            'Bank Name', 'Branch Info', 'Branch Number', 'Account No',
            'Sponsor Name', 'Sponsorship Start', 'Sponsorship End',
            'Father Name', 'Father Income', 'Mother Name', 'Mother Income',
            'Guardian Name', 'Guardian Income',
            'Introduced By', 'Introducer Phone',
            'Background Info', 'Internal Comment', 'External Comment',
            'Status'
        ];

        $sheet->fromArray($headers, null, 'A1');

        // Sample data
        $sample = [
            'UNI-2024-0001', 'John Doe', 'john@example.com', '0771234567',
            'University of Colombo', 'Computer Science', '2Y1S',
            '2000-05-15', '', '123 Main St', 'Colombo', '10000', 'Sri Lanka',
            'Bank of Ceylon', 'Main Branch', '001', '1234567890',
            '', '', '',
            'James Doe', '50000', 'Jane Doe', '45000',
            '', '',
            'Self', '0777777777',
            '', '', '',
            'Active'
        ];

        $sheet->fromArray($sample, null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempFile);

        return response()->download($tempFile, 'university_students_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // =========================================
    // REPORT CARDS
    // =========================================

    public function getReportCards(string $hash): JsonResponse
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        $reportCards = $student->reportCards()
            ->orderBy('semester_end_year', 'desc')
            ->orderBy('semester_end_month', 'desc')
            ->get();

        $data = $reportCards->map(function ($card) {
            return [
                'id' => $card->id,
                'filename' => $card->filename,
                'term' => $card->term_display,
                'semester_end' => $card->semester_end_display,
                'upload_date' => $card->upload_date->format('Y-m-d'),
                'file_size' => $card->file_size ? round($card->file_size / 1024, 1) . ' KB' : 'N/A',
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function uploadReportCard(Request $request): JsonResponse
    {
        $request->validate([
            'student_hash' => 'required|string',
            'filename' => 'required|string|max:255',
            'report_card_term' => 'required|in:1Y1S,1Y2S,2Y1S,2Y2S,3Y1S,3Y2S,4Y1S,4Y2S,5Y1S,5Y2S',
            'semester_end_month' => 'nullable|integer|min:1|max:12',
            'semester_end_year' => 'nullable|integer|min:2000|max:2100',
            'report_card_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $student = UniversityStudent::findByHashOrFail($request->student_hash);

        try {
            $file = $request->file('report_card_file');
            $fileData = base64_encode(file_get_contents($file->getPathname()));

            UniversityReportCard::create([
                'university_student_id' => $student->id,
                'filename' => $request->filename,
                'upload_date' => now(),
                'report_card_term' => $request->report_card_term,
                'current_term' => $student->university_year_of_study,
                'semester_end_month' => $request->semester_end_month ?? (int) date('m'),
                'semester_end_year' => $request->semester_end_year ?? (int) date('Y'),
                'file_data' => $fileData,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return response()->json(['success' => true, 'message' => 'Report card uploaded']);

        } catch (\Exception $e) {
            Log::error('Upload ReportCard Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed'], 500);
        }
    }

    public function viewReportCard(int $id)
    {
        $reportCard = UniversityReportCard::findOrFail($id);

        if (!$reportCard->file_data) {
            abort(404, 'File not found');
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
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function downloadReportCard(int $id)
    {
        $reportCard = UniversityReportCard::findOrFail($id);

        if (!$reportCard->file_data) {
            abort(404, 'File not found');
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

    public function deleteReportCard(int $id): JsonResponse
    {
        try {
            UniversityReportCard::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Report card deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
        }
    }

    public function approveReportCard(int $id): JsonResponse
    {
        try {
            $reportCard = UniversityReportCard::findOrFail($id);
            $student = UniversityStudent::find($reportCard->university_student_id);
            
            $reportCard->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);
            
            // If student has portal account, create notification
            if ($student && $student->user_id) {
                DB::table('notifications')->insert([
                    'user_id' => $student->user_id,
                    'user_type' => 'user',
                    'from_user_id' => auth()->id(),
                    'from_user_type' => 'admin',
                    'title' => 'Report Card Approved ✓',
                    'message' => "Great news! Your report card \"{$reportCard->filename}\" ({$reportCard->term_display}) has been approved.",
                    'type' => 'success',
                    'url' => '/client/student-portal/my-form',
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
            
            return response()->json(['success' => true, 'message' => 'Report card approved']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Approval failed'], 500);
        }
    }

    public function rejectReportCard(Request $request, int $id): JsonResponse
    {
        try {
            $reportCard = UniversityReportCard::findOrFail($id);
            $student = UniversityStudent::find($reportCard->university_student_id);
            
            $reason = $request->input('reason', 'No reason provided');
            
            // If student has portal account, create notification
            if ($student && $student->user_id) {
                DB::table('notifications')->insert([
                    'user_id' => $student->user_id,
                    'user_type' => 'user',
                    'from_user_id' => auth()->id(),
                    'from_user_type' => 'admin',
                    'title' => 'Report Card Rejected',
                    'message' => "Your report card \"{$reportCard->filename}\" ({$reportCard->term_display}) has been rejected. Reason: {$reason}. Please upload a new report card.",
                    'type' => 'warning',
                    'url' => '/client/student-portal/my-form',
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
            
            // Delete the report card
            $reportCard->delete();
            
            return response()->json(['success' => true, 'message' => 'Report card rejected and student notified']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Rejection failed: ' . $e->getMessage()], 500);
        }
    }

    // =========================================
    // HELPERS
    // =========================================

    protected function getFormData(): array
    {
        $countries = collect();
        try {
            $countries = DB::table('countries')
                ->select('country_id as id', 'short_name as name')
                ->orderBy('short_name')
                ->get();
        } catch (\Exception $e) {
            // Table might not exist
        }

        $banks = collect();
        try {
            $banks = DB::table('banks')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            // Table might not exist
        }

        return [
            'universities' => UniversityName::orderBy('name')->get(),
            'programs' => UniversityProgram::orderBy('name')->get(),
            'countries' => $countries,
            'banks' => $banks,
            'yearsOfStudy' => UniversityStudent::YEAR_OF_STUDY,
            'terms' => UniversityReportCard::TERMS,
        ];
    }

    protected function validateStudent(Request $request, ?int $studentId = null): array
    {
        $emailRule = 'nullable|email|max:255';
        if ($studentId) {
            $emailRule .= '|unique:university_students,email,' . $studentId;
        } else {
            $emailRule .= '|unique:university_students,email';
        }

        return $request->validate([
            'university_internal_id' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'contact_no' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country_id' => 'nullable|integer',
            'university_name_id' => 'nullable|integer',
            'university_program_id' => 'nullable|integer',
            'university_year_of_study' => 'nullable|in:1Y1S,1Y2S,2Y1S,2Y2S,3Y1S,3Y2S,4Y1S,4Y2S,5Y1S,5Y2S',
            'university_student_dob' => 'nullable|date',
            'bank_id' => 'nullable|integer',
            'university_bank_branch_info' => 'nullable|string|max:255',
            'university_bank_branch_number' => 'nullable|string|max:100',
            'university_bank_account_no' => 'nullable|string|max:100',
            'university_sponsorship_start_date' => 'nullable|date',
            'university_sponsorship_end_date' => 'nullable|date',
            'university_introducedby' => 'nullable|string|max:255',
            'university_introducedph' => 'nullable|string|max:50',
            'university_father_name' => 'nullable|string|max:255',
            'university_mother_name' => 'nullable|string|max:255',
            'university_father_income' => 'nullable|numeric|min:0',
            'university_mother_income' => 'nullable|numeric|min:0',
            'university_guardian_name' => 'nullable|string|max:255',
            'university_guardian_income' => 'nullable|numeric|min:0',
            'background_info' => 'nullable|string|max:5000',
            'internal_comment' => 'nullable|string|max:5000',
            'external_comment' => 'nullable|string|max:5000',
            'active' => 'nullable',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    protected function parseDate($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseDecimal($value)
    {
        if (empty($value)) return null;
        $value = preg_replace('/[^0-9.]/', '', $value);
        return is_numeric($value) ? floatval($value) : null;
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

        // Check in school_students table
        $existsInSchool = \DB::table('school_students')
            ->where('email', $email)
            ->exists();
        if ($existsInSchool) {
            return response()->json([
                'available' => false,
                'message' => 'This email is already used by a school student. Not available.'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Email is available.'
        ]);
    }

    /**
     * Create portal account for university student
     */
    public function createPortalAccount(Request $request, $hash)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $student = UniversityStudent::findByHashOrFail($hash);
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

        // Check email in school_students
        if (\DB::table('school_students')->where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is used by a school student. Not available.'
            ], 422);
        }

        try {
            \DB::beginTransaction();

            // Create user account
            $userId = \DB::table('users')->insertGetId([
                'name' => $student->name,
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
        $student = UniversityStudent::findByHashOrFail($hash);

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
        $student = UniversityStudent::findByHashOrFail($hash);

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
        $student = UniversityStudent::findByHashOrFail($hash);

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

        $student = UniversityStudent::findByHashOrFail($hash);

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
     * - Increases year by 1
     */
    public function studentRollback($hash): JsonResponse
    {
        $student = UniversityStudent::findByHashOrFail($hash);
        
        // Get current year and calculate new year
        $currentYear = (int) $student->university_year_of_study;
        $newYear = $currentYear + 1;
        
        // Cap at year 6 (or adjust as needed for PhD etc)
        if ($newYear > 6) {
            $newYear = 6;
        }
        
        // Update student
        $student->update([
            'user_id' => null,           // Remove portal access
            'active' => 0,                // Set to inactive
            'current_state' => 'complete', // Mark as complete (DB uses 'complete')
            'university_year_of_study' => $newYear, // Promote to next year
        ]);
        
        // Get year label for response
        $yearLabels = [
            1 => '1st Year',
            2 => '2nd Year', 
            3 => '3rd Year',
            4 => '4th Year',
            5 => '5th Year',
            6 => '6th Year'
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Student rolled back successfully',
            'new_year' => $yearLabels[$newYear] ?? "Year $newYear",
            'data' => [
                'user_id' => null,
                'active' => 0,
                'current_state' => 'complete',
                'university_year_of_study' => $newYear
            ]
        ]);
    }

    /**
     * Rollback ALL in_progress students at once
     * - Removes portal access (user_id = null)
     * - Sets inactive (active = 0)
     * - Sets current_state to 'completed'
     * - Increases year by 1
     */
    public function rollbackAll(Request $request): JsonResponse
    {
        $completedYear = $request->input('completed_year', date('Y'));
        
        // Get all IN PROGRESS students
        $students = \DB::table('university_students')
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
            $currentYear = (int) $student->university_year_of_study;
            $newYear = min($currentYear + 1, 6); // Cap at year 6
            
            // If student has portal access, delete the user record
            if ($student->user_id) {
                \DB::table('users')->where('id', $student->user_id)->delete();
                $usersDeleted++;
            }
            
            \DB::table('university_students')
                ->where('id', $student->id)
                ->update([
                    'user_id' => null,           // Remove portal access reference
                    'active' => 0,                // Set to inactive
                    'current_state' => 'complete', // Mark as completed
                    'completed_year' => $completedYear, // Save the completed year
                    'university_year_of_study' => $newYear, // Promote to next year
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
