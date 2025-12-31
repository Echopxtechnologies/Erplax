<?php

namespace Modules\StudentSponsorship\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Core\Traits\DataTableTrait;
use Modules\StudentSponsorship\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SponsorController extends AdminController
{
    use DataTableTrait;

    // =========================================
    // DATATABLE CONFIGURATION
    // =========================================

    protected $model = Sponsor::class;
    protected $viewPrefix = 'studentsponsorship::sponsors';
    protected $routePrefix = 'admin.studentsponsorship.sponsors';
    
    // Only country - NO bank (Bank model doesn't exist)
    protected $with = ['country'];

    protected $searchable = [
        'sponsor_internal_id',
        'name',
        'email',
        'contact_no',
        'city',
        'sponsor_occupation',
    ];

    protected $sortable = [
        'id',
        'sponsor_internal_id',
        'name',
        'email',
        'sponsor_type',
        'city',
        'active',
        'created_at',
    ];

    protected $filterable = [
        'sponsor_type' => 'sponsor_type',
        'active' => 'active',
        'country_id' => 'country_id',
    ];

    protected $uniqueField = 'sponsor_internal_id';

    // Validation rules for import
    protected $importable = [
        'sponsor_internal_id'        => 'required|string|max:50',
        'name'                       => 'required|string|max:255',
        'sponsor_type'               => 'nullable|in:individual,company',
        'sponsor_occupation'         => 'nullable|string|max:255',
        'email'                      => 'nullable|email',
        'country_name'               => 'nullable|string|max:100',
        'contact_no'                 => 'nullable|string|max:50',
        'city'                       => 'nullable|string|max:100',
        'address'                    => 'nullable|string|max:500',
        'zip'                        => 'nullable|string|max:20',
        'bank_name'                  => 'nullable|string|max:255',
        'sponsor_bank_branch_info'   => 'nullable|string|max:255',
        'sponsor_bank_branch_number' => 'nullable|string|max:50',
        'sponsor_bank_account_no'    => 'nullable|string|max:50',
        'membership_start_date'      => 'nullable|date',
        'membership_end_date'        => 'nullable|date',
        'sponsor_frequency'          => 'nullable|in:one_time,monthly,quarterly,half_yearly,yearly',
        'background_info'            => 'nullable|string',
        'internal_comment'           => 'nullable|string',
        'external_comment'           => 'nullable|string',
        'active'                     => 'nullable',
    ];

    // Auto-lookup: Import by name → saves as ID
    protected $importLookups = [
        'country_name' => [
            'table'   => 'countries',
            'search'  => 'short_name',
            'return'  => 'country_id',
            'save_as' => 'country_id',
        ],
        'bank_name' => [
            'table'   => 'banks',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'bank_id',
            'create'  => true,
            'create_data' => ['created_on' => null],
        ],
    ];

    // Default values for empty import columns
    protected $importDefaults = [
        'active'       => 1,
        'sponsor_type' => 'individual',
    ];

    // Bulk action buttons
    protected $bulkActions = [
        'delete'     => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
        'activate'   => ['label' => 'Activate', 'color' => 'green'],
        'deactivate' => ['label' => 'Deactivate', 'color' => 'yellow'],
    ];

    // Template columns for download
    protected $templateColumns = [
        'sponsor_internal_id'        => 'Internal ID*',
        'name'                       => 'Sponsor Name*',
        'sponsor_type'               => 'Type (individual/company)',
        'sponsor_occupation'         => 'Occupation',
        'email'                      => 'Email',
        'country_name'               => 'Country',
        'contact_no'                 => 'Phone',
        'city'                       => 'City',
        'address'                    => 'Address',
        'zip'                        => 'Postal Code',
        'bank_name'                  => 'Bank Name',
        'sponsor_bank_branch_info'   => 'Branch Name',
        'sponsor_bank_branch_number' => 'Branch/IFSC',
        'sponsor_bank_account_no'    => 'Account Number',
        'membership_start_date'      => 'Start Date (YYYY-MM-DD)',
        'membership_end_date'        => 'End Date (YYYY-MM-DD)',
        'sponsor_frequency'          => 'Frequency',
        'active'                     => 'Status (1=Active, 0=Inactive)',
    ];

    // =========================================
    // CONSTRUCTOR
    // =========================================

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
    }

    // =========================================
    // ROW MAPPING FOR DATATABLE
    // =========================================

    protected function mapRow($row): array
    {
        // Get sponsored students
        $sponsoredStudents = $row->sponsored_students_list;
        $sponsoredNames = array_map(fn($s) => $s['name'], $sponsoredStudents);
        
        return [
            'id'                  => $row->id,
            'sponsor_internal_id' => $row->sponsor_internal_id ?? '-',
            'name'                => $row->name,
            'sponsor_type'        => $row->sponsor_type,
            'sponsor_occupation'  => $row->sponsor_occupation,
            'email'               => $row->email,
            'contact_no'          => $row->contact_no,
            'city'                => $row->city,
            'country_name'        => $row->country?->short_name ?? '-',
            'bank_name'           => $row->bank_name ?? '-',
            'membership_start'    => $row->membership_start_date?->format('d M Y'),
            'membership_end'      => $row->membership_end_date?->format('d M Y'),
            'sponsor_frequency'   => $row->frequency_display,
            'sponsored_students'  => $sponsoredStudents,
            'sponsored_students_count' => count($sponsoredStudents),
            'sponsored_students_names' => implode(', ', $sponsoredNames) ?: '-',
            'active'              => $row->active,
            'has_portal'          => !empty($row->staff_id),
            'created_at'          => $row->created_at?->format('d M Y'),
            '_show_url'           => route('admin.studentsponsorship.sponsors.show', $row->id),
            '_edit_url'           => route('admin.studentsponsorship.sponsors.edit', $row->id),
            '_delete_url'         => route('admin.studentsponsorship.sponsors.destroy', $row->id),
        ];
    }

    /**
     * Custom export row mapping
     */
    protected function mapExportRow($item)
    {
        return [
            'sponsor_internal_id'        => $item->sponsor_internal_id,
            'name'                       => $item->name,
            'sponsor_type'               => $item->sponsor_type,
            'sponsor_occupation'         => $item->sponsor_occupation,
            'email'                      => $item->email,
            'contact_no'                 => $item->contact_no,
            'city'                       => $item->city,
            'address'                    => $item->address,
            'zip'                        => $item->zip,
            'country_name'               => $item->country?->short_name,
            'bank_name'                  => $item->bank_name,
            'sponsor_bank_branch_info'   => $item->sponsor_bank_branch_info,
            'sponsor_bank_branch_number' => $item->sponsor_bank_branch_number,
            'sponsor_bank_account_no'    => $item->sponsor_bank_account_no,
            'membership_start_date'      => $item->membership_start_date?->format('Y-m-d'),
            'membership_end_date'        => $item->membership_end_date?->format('Y-m-d'),
            'sponsor_frequency'          => $item->sponsor_frequency,
            'background_info'            => $item->background_info,
            'internal_comment'           => $item->internal_comment,
            'external_comment'           => $item->external_comment,
            'active'                     => $item->active ? 'Active' : 'Inactive',
        ];
    }

    // =========================================
    // BULK ACTIONS
    // =========================================

    public function getBulkActionsConfig(): JsonResponse
    {
        return response()->json([
            'actions' => $this->bulkActions ?? [],
            'route' => route('admin.studentsponsorship.sponsors.bulk-action'),
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
                $count = Sponsor::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => "{$count} sponsor(s) deleted"]);
            
            case 'activate':
                $count = Sponsor::whereIn('id', $ids)->update(['active' => 1]);
                return response()->json(['success' => true, 'message' => "{$count} sponsor(s) activated"]);
            
            case 'deactivate':
                $count = Sponsor::whereIn('id', $ids)->update(['active' => 0]);
                return response()->json(['success' => true, 'message' => "{$count} sponsor(s) deactivated"]);
            
            default:
                return response()->json(['success' => false, 'message' => 'Unknown action'], 400);
        }
    }

    public function bulkActivate(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $count = Sponsor::whereIn('id', $ids)->update(['active' => 1]);
        return response()->json(['success' => true, 'message' => "{$count} sponsor(s) activated"]);
    }

    public function bulkDeactivate(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $count = Sponsor::whereIn('id', $ids)->update(['active' => 0]);
        return response()->json(['success' => true, 'message' => "{$count} sponsor(s) deactivated"]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = Sponsor::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$deleted} sponsor(s) deleted"]);
    }

    // =========================================
    // INDEX PAGE
    // =========================================

    public function index()
    {
        $stats = [
            'total'      => Sponsor::count(),
            'active'     => Sponsor::where('active', 1)->count(),
            'individual' => Sponsor::where('sponsor_type', 'individual')->count(),
            'company'    => Sponsor::where('sponsor_type', 'company')->count(),
        ];

        $countries = DB::table('countries')->orderBy('short_name')->get();
        $pageTitle = 'Sponsors';

        return view('studentsponsorship::sponsors.index', compact('stats', 'countries', 'pageTitle'));
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

        // Bulk Actions
        if ($request->has('bulk_action')) {
            return $this->dtBulkAction($request);
        }

        // List with search, filter, sort, pagination
        try {
            $query = Sponsor::with(['country']);

            // Search
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('contact_no', 'like', "%{$search}%")
                      ->orWhere('sponsor_internal_id', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%")
                      ->orWhere('sponsor_occupation', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('active')) {
                $query->where('active', $request->input('active'));
            }
            if ($request->filled('sponsor_type')) {
                $query->where('sponsor_type', $request->input('sponsor_type'));
            }
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->input('country_id'));
            }

            $totalRecords = Sponsor::count();
            $filteredRecords = $query->count();

            // Sorting
            $sortCol = $request->input('sort', 'id');
            $sortDir = $request->input('dir', 'desc');
            
            if (in_array($sortCol, ['id', 'name', 'email', 'sponsor_type', 'city', 'active', 'created_at'])) {
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

            $items = collect($data->items())->map(function ($sponsor, $index) use ($startRow) {
                $row = $this->mapRow($sponsor);
                $row['_row_num'] = $startRow + $index;
                return $row;
            });

            $response = [
                'data' => $items,
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
            ];

            // Add stats if requested
            if ($request->has('with_stats')) {
                $response['stats'] = [
                    'total'      => Sponsor::count(),
                    'active'     => Sponsor::where('active', 1)->count(),
                    'individual' => Sponsor::where('sponsor_type', 'individual')->count(),
                    'company'    => Sponsor::where('sponsor_type', 'company')->count(),
                ];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Sponsor DataTable Error: ' . $e->getMessage());
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
        $data = [
            'title'     => 'Add New Sponsor',
            'pageTitle' => 'Add New Sponsor',
            'countries' => DB::table('countries')->orderBy('short_name')->get(),
            'banks'     => DB::table('banks')->orderBy('name')->get(),
        ];

        return view('studentsponsorship::sponsors.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_internal_id'        => 'required|string|max:50|unique:sponsors,sponsor_internal_id',
            'name'                       => 'required|string|max:255',
            'sponsor_type'               => 'required|in:individual,company',
            'sponsor_occupation'         => 'nullable|string|max:255',
            'email'                      => 'nullable|email|max:255',
            'country_id'                 => 'nullable|integer',
            'contact_no'                 => 'nullable|string|max:50',
            'city'                       => 'nullable|string|max:100',
            'address'                    => 'nullable|string|max:500',
            'zip'                        => 'nullable|string|max:20',
            'bank_id'                    => 'nullable|integer',
            'sponsor_bank_branch_info'   => 'nullable|string|max:255',
            'sponsor_bank_branch_number' => 'nullable|string|max:50',
            'sponsor_bank_account_no'    => 'nullable|string|max:50',
            'membership_start_date'      => 'nullable|date',
            'membership_end_date'        => 'nullable|date',
            'sponsor_frequency'          => 'nullable|in:one_time,monthly,quarterly,half_yearly,yearly',
            'background_info'            => 'nullable|string',
            'internal_comment'           => 'nullable|string',
            'external_comment'           => 'nullable|string',
            'active'                     => 'nullable',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $sponsor = Sponsor::create($validated);

        return redirect()
            ->route('admin.studentsponsorship.sponsors.show', $sponsor->id)
            ->with('success', 'Sponsor created successfully.');
    }

    /**
     * Add new bank via AJAX
     */
    public function addBank(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name',
        ]);

        $bankId = DB::table('banks')->insertGetId([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bank = DB::table('banks')->find($bankId);

        return response()->json([
            'success' => true,
            'bank' => $bank,
            'message' => 'Bank added successfully.',
        ]);
    }

    public function show($id)
    {
        $sponsor = Sponsor::with(['country', 'staff.admin'])->findOrFail($id);

        $data = [
            'title'     => 'Sponsor Details',
            'pageTitle' => 'Sponsor Details',
            'sponsor'   => $sponsor,
        ];

        return view('studentsponsorship::sponsors.show', $data);
    }

    public function edit($id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $data = [
            'title'     => 'Edit Sponsor',
            'pageTitle' => 'Edit Sponsor',
            'sponsor'   => $sponsor,
            'countries' => DB::table('countries')->orderBy('short_name')->get(),
            'banks'     => DB::table('banks')->orderBy('name')->get(),
        ];

        return view('studentsponsorship::sponsors.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $validated = $request->validate([
            'sponsor_internal_id'        => 'required|string|max:50|unique:sponsors,sponsor_internal_id,' . $id,
            'name'                       => 'required|string|max:255',
            'sponsor_type'               => 'required|in:individual,company',
            'sponsor_occupation'         => 'nullable|string|max:255',
            'email'                      => 'nullable|email|max:255',
            'country_id'                 => 'nullable|integer',
            'contact_no'                 => 'nullable|string|max:50',
            'city'                       => 'nullable|string|max:100',
            'address'                    => 'nullable|string|max:500',
            'zip'                        => 'nullable|string|max:20',
            'bank_id'                    => 'nullable|integer',
            'sponsor_bank_branch_info'   => 'nullable|string|max:255',
            'sponsor_bank_branch_number' => 'nullable|string|max:50',
            'sponsor_bank_account_no'    => 'nullable|string|max:50',
            'membership_start_date'      => 'nullable|date',
            'membership_end_date'        => 'nullable|date',
            'sponsor_frequency'          => 'nullable|in:one_time,monthly,quarterly,half_yearly,yearly',
            'background_info'            => 'nullable|string',
            'internal_comment'           => 'nullable|string',
            'external_comment'           => 'nullable|string',
            'active'                     => 'nullable',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $sponsor->update($validated);

        return redirect()
            ->route('admin.studentsponsorship.sponsors.show', $sponsor->id)
            ->with('success', 'Sponsor updated successfully.');
    }

    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->delete();

        return response()->json(['success' => true, 'message' => 'Sponsor deleted successfully.']);
    }

    // =========================================
    // PORTAL ACCESS MANAGEMENT
    // =========================================

    /**
     * Generate unique employee code for staff
     */
    private function generateEmployeeCode(): string
    {
        $year = date('Y');
        $prefix = "SPO{$year}";
        
        $lastStaff = DB::table('staffs')
            ->where('employee_code', 'like', "{$prefix}%")
            ->orderBy('employee_code', 'desc')
            ->first();
        
        if ($lastStaff && preg_match('/' . $prefix . '(\d+)/', $lastStaff->employee_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Enable portal access for sponsor
     * Creates admin + staff records
     */
    public function enablePortalAccess(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        // Check if already has portal access
        if ($sponsor->staff_id) {
            return response()->json([
                'success' => false,
                'message' => 'Sponsor already has portal access.'
            ], 400);
        }

        // Validate
        $validated = $request->validate([
            'login_email' => 'required|email|unique:admins,email|unique:staffs,email',
            'password'    => 'required|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create Admin record (is_admin = 0 for staff portal)
            $adminId = DB::table('admins')->insertGetId([
                'name'       => $sponsor->name,
                'email'      => $validated['login_email'],
                'password'   => \Hash::make($validated['password']),
                'is_admin'   => 0,  // Not an admin, just staff/sponsor
                'is_active'  => $sponsor->active ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Create Staff record
            $nameParts = explode(' ', $sponsor->name, 2);
            $firstName = $nameParts[0] ?? $sponsor->name;
            $lastName = $nameParts[1] ?? '';

            $staffId = DB::table('staffs')->insertGetId([
                'admin_id'      => $adminId,
                'employee_code' => $this->generateEmployeeCode(),
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $validated['login_email'],
                'phone'         => $sponsor->contact_no,
                'city'          => $sponsor->city,
                'country'       => $sponsor->country?->short_name,
                'department'    => 'Sponsor',
                'designation'   => $sponsor->sponsor_type === 'company' ? 'Company Sponsor' : 'Individual Sponsor',
                'join_date'     => $sponsor->membership_start_date ?? now()->toDateString(),
                'status'        => $sponsor->active ? 1 : 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 3. Link sponsor to staff
            $sponsor->staff_id = $staffId;
            $sponsor->save();

            // 4. Assign sponsor role if exists
            try {
                $admin = \App\Models\Admin::find($adminId);
                if ($admin && method_exists($admin, 'assignRole')) {
                    // Try to assign sponsor role
                    $sponsorRole = \Spatie\Permission\Models\Role::where('name', 'sponsor')
                        ->where('guard_name', 'admin')
                        ->first();
                    
                    if ($sponsorRole) {
                        $admin->assignRole('sponsor');
                    }
                }
            } catch (\Exception $e) {
                // Role assignment failed, continue anyway
                \Log::warning('Could not assign sponsor role: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Portal access enabled. Login email: ' . $validated['login_email'],
                'staff_id' => $staffId,
                'admin_id' => $adminId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Enable portal access failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to enable portal access: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable portal access for sponsor
     * Removes admin + staff records
     */
    public function disablePortalAccess($id)
    {
        $sponsor = Sponsor::findOrFail($id);

        if (!$sponsor->staff_id) {
            return response()->json([
                'success' => false,
                'message' => 'Sponsor does not have portal access.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Get staff record
            $staff = DB::table('staffs')->where('id', $sponsor->staff_id)->first();
            
            if ($staff) {
                // Delete admin record first (due to foreign key)
                if ($staff->admin_id) {
                    DB::table('admins')->where('id', $staff->admin_id)->delete();
                }
                
                // Delete staff record
                DB::table('staffs')->where('id', $sponsor->staff_id)->delete();
            }

            // Remove link from sponsor
            $sponsor->staff_id = null;
            $sponsor->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Portal access disabled successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Disable portal access failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable portal access: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset portal password for sponsor
     */
    public function resetPortalPassword(Request $request, $id)
    {
        $sponsor = Sponsor::with('staff')->findOrFail($id);

        if (!$sponsor->staff_id || !$sponsor->staff) {
            return response()->json([
                'success' => false,
                'message' => 'Sponsor does not have portal access.'
            ], 400);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        try {
            // Update admin password
            if ($sponsor->staff->admin_id) {
                DB::table('admins')
                    ->where('id', $sponsor->staff->admin_id)
                    ->update([
                        'password'   => \Hash::make($validated['password']),
                        'updated_at' => now(),
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Reset portal password failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password.'
            ], 500);
        }
    }
}
