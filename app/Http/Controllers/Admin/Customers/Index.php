<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use App\Traits\DataTable;
use Exception;
use Illuminate\Support\Facades\DB;

class Index extends AdminController
{
    use DataTable;

    /*
    |--------------------------------------------------------------------------
    | Properties for DataTable
    |--------------------------------------------------------------------------
    */
    
    protected $model = Customer::class;
    protected $searchable = ['name', 'email', 'phone', 'company', 'designation'];
    protected $exportable = ['id', 'name', 'email', 'phone', 'company', 'customer_type', 'group_name', 'active'];
    protected $routePrefix = 'admin.customers';
    protected $importable = [
    // Customer Type
    'customer_type'        => 'required|in:individual,company',
    
    // Contact Information
    'firstname'            => 'required|string|max:100',
    'lastname'             => 'required|string|max:100',
    'email'                => 'required|email|max:191|unique:customers,email',
    'phone'                => 'nullable|string|max:20',
    'designation'          => 'nullable|string|max:100',
    'group_name'           => 'nullable|string|max:100',
    
    // Company Information (required only for company type)
    'company'              => 'nullable|string|max:191',
    'vat'                  => 'nullable|string|max:50',
    'website'              => 'nullable|url|max:255',
    
    // Billing Address
    'billing_street'       => 'nullable|string|max:500',
    'billing_city'         => 'nullable|string|max:100',
    'billing_state'        => 'nullable|string|max:100',
    'billing_zip_code'     => 'nullable|string|max:20',
    'billing_country'      => 'nullable|string|max:100',
    
    // Shipping Address
    'shipping_address'     => 'nullable|string|max:500',
    'shipping_city'        => 'nullable|string|max:100',
    'shipping_state'       => 'nullable|string|max:100',
    'shipping_zip_code'    => 'nullable|string|max:20',
    'shipping_country'     => 'nullable|string|max:100',
    
    // Email Notifications (boolean 0/1)
    'invoice_emails'       => 'nullable|boolean',
    'estimate_emails'      => 'nullable|boolean',
    'credit_note_emails'   => 'nullable|boolean',
    'contract_emails'      => 'nullable|boolean',
    'task_emails'          => 'nullable|boolean',
    'project_emails'       => 'nullable|boolean',
    'ticket_emails'        => 'nullable|boolean',
    
    // Account Settings
    'active'               => 'nullable|boolean',
];



    /*
    |--------------------------------------------------------------------------
    | Private Properties (Getter/Setter Pattern)
    |--------------------------------------------------------------------------
    */
    
    private $customer = null;

    /*
    |--------------------------------------------------------------------------
    | GETTER - Fetch Single Customer
    |--------------------------------------------------------------------------
    */
    
    private function getCustomer($id)
    {
        try {
            if (!$this->customer || $this->customer->id != $id) {
                $this->customer = Customer::findOrFail($id);
            }
            return $this->customer;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX - Main List Page
    |--------------------------------------------------------------------------
    */
    
    // public function index(Request $request)
    // {
    //     try {
    //         // Statistics
    //         $stats = [
    //             'total_customers' => Customer::select('company')
    //                 ->where('customer_type', 'company')
    //                 ->distinct()
    //                 ->count() + Customer::where('customer_type', 'individual')->count(),
    //             'active_customers' => Customer::where('active', 1)->count(),
    //             'individual_customers' => Customer::where('customer_type', 'individual')->count(),
    //             'company_customers' => Customer::select('company')
    //                 ->where('customer_type', 'company')
    //                 ->distinct()
    //                 ->count(),
    //             'total_contacts' => Customer::count(),
    //             'active_contacts' => Customer::where('active', 1)->count(),
    //         ];
            
    //         $customerTypes = [
    //             'individual' => 'Individual',
    //             'company' => 'Company',
    //         ];
            
    //         $customerGroups = CustomerGroup::orderBy('name')->get();
            
    //         return view('admin.customers.index', compact('stats', 'customerTypes', 'customerGroups'));
            
    //     } catch (Exception $e) {
    //         report($e);
    //         return back()->with('error', 'Failed to load customers: ' . $e->getMessage());
    //     }
    // }


public function index(Request $request)
{
    $this->authorize('customers.customers.read');
    try {
        // Statistics - Count ALL contacts
        $stats = [
            'total_customers' => Customer::count(), // Total rows
            'active_customers' => Customer::where('active', 1)->count(),
            'individual_customers' => Customer::where('customer_type', 'individual')->count(),
            'company_customers' => Customer::where('customer_type', 'company')->count(),
            
            // Optional: Unique companies count
            'unique_companies' => Customer::where('customer_type', 'company')
                ->distinct('company')
                ->count('company'),
        ];
        
        $customerTypes = [
            'individual' => 'Individual',
            'company' => 'Company',
        ];
        
        $customerGroups = CustomerGroup::orderBy('name')->pluck('name');
        
        return view('admin.customers.index', compact('stats', 'customerTypes', 'customerGroups'));
        
    } catch (Exception $e) {
        report($e);
        return back()->with('error', 'Failed to load customers: ' . $e->getMessage());
    }
}











    /*
    |--------------------------------------------------------------------------
    | DATA - DataTable AJAX Endpoint
    |--------------------------------------------------------------------------
    */
    
// public function data(Request $request)
// {
//     try {
//         // EXPORT
//         if ($request->has('export')) {
//             return $this->dtExport($request);
//         }

//         $search = $request->get('search');
//         $customerType = $request->get('customer_type');
//         $groupName = $request->get('group_name');
//         $active = $request->get('active');
        
//         // Build base query
//         $query = Customer::query();
        
//         // Apply customer type filter first
//         if ($customerType === 'individual') {
//             // Only individuals
//             $query->where('customer_type', 'individual');
            
//         } elseif ($customerType === 'company') {
//             // Only companies - get first contact per company
//             $subquery = Customer::selectRaw('MIN(id) as min_id')
//                 ->where('customer_type', 'company')
//                 ->groupBy('company');
            
//             $query->whereIn('id', $subquery->pluck('min_id'));
            
//         } else {
//             // Both types - get all individuals + first contact per company
//             $companyIds = Customer::selectRaw('MIN(id) as min_id')
//                 ->where('customer_type', 'company')
//                 ->groupBy('company')
//                 ->pluck('min_id');
            
//             $query->where(function($q) use ($companyIds) {
//                 $q->where('customer_type', 'individual')
//                   ->orWhereIn('id', $companyIds);
//             });
//         }
        
//         // Apply search
//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('name', 'LIKE', "%{$search}%")
//                   ->orWhere('email', 'LIKE', "%{$search}%")
//                   ->orWhere('phone', 'LIKE', "%{$search}%")
//                   ->orWhere('company', 'LIKE', "%{$search}%")
//                   ->orWhere('designation', 'LIKE', "%{$search}%");
//             });
//         }
        
//         // Apply group filter
//         if ($groupName) {
//             $query->where('group_name', $groupName);
//         }
        
//         // Apply status filter
//         if ($active !== null && $active !== '') {
//             $query->where('active', $active);
//         }
        
//         // Sorting
//         $sortCol = $request->get('sort', 'id');
//         $sortDir = $request->get('dir', 'desc');
        
//         $allowedSorts = ['id', 'name', 'email', 'phone', 'customer_type', 'created_at'];
//         if (!in_array($sortCol, $allowedSorts)) {
//             $sortCol = 'id';
//         }
//         if (!in_array($sortDir, ['asc', 'desc'])) {
//             $sortDir = 'desc';
//         }
        
//         $query->orderBy($sortCol, $sortDir);
        
//         // Pagination
//         $perPage = (int) $request->get('per_page', 10);
//         if ($perPage <= 0 || $perPage > 100) {
//             $perPage = 10;
//         }
        
//         $data = $query->paginate($perPage);
//         $startSno = ($data->currentPage() - 1) * $perPage;
        
//         // Format data
//         $items = $data->getCollection()->map(function ($customer, $index) use ($startSno) {
//             // For companies, count total contacts
//             $contactCount = null;
//             if ($customer->customer_type === 'company') {
//                 $contactCount = Customer::where('company', $customer->company)
//                     ->where('customer_type', 'company')
//                     ->count();
//             }
            
//             return [
//                 'id' => $customer->id,
//                 'sno' => $startSno + $index + 1,
//                 'customer_type' => $customer->customer_type,
                
//                 // For display
//                 'name' => $customer->name,
//                 'company' => $customer->company,
//                 'full_name' => $customer->display_name,
                
//                 'email' => $customer->email,
//                 'phone' => $customer->phone ?? '-',
//                 'designation' => $customer->designation ?? '-',
//                 'group_name' => $customer->group_name ?? '-',
//                 'contact_count' => $contactCount,
                
//                 'active' => $customer->active ? 1 : 0,
//                 'status_badge' => $customer->status_badge,
//                 'type_badge' => $customer->type_badge,
                
//                 'created_at' => $customer->created_at ? $customer->created_at->format('d M Y') : '-',
                
//                 // URLs
//                 '_show_url' => route('admin.customers.show', $customer->id),
//                 '_edit_url' => route('admin.customers.edit', $customer->id),
//                 '_delete_url' => route('admin.customers.destroy', $customer->id),
//             ];
//         })->values();
        
//         return response()->json([
//             'data' => $items,
//             'total' => $data->total(),
//             'current_page' => $data->currentPage(),
//             'last_page' => $data->lastPage(),
//         ]);
        
//     } catch (Exception $e) {
//         report($e);
//         return response()->json([
//             'data' => [],
//             'total' => 0,
//             'current_page' => 1,
//             'last_page' => 1,
//             'error' => 'Failed to load data: ' . $e->getMessage(),
//         ], 500);
//     }
// }





public function data(Request $request)
{
    // $this->authorize('customers.customers.read');
    try {
        // EXPORT
        if ($request->has('export')) {
            return $this->dtExport($request);
        }

        $search = $request->get('search');
        $customerType = $request->get('customer_type');
        $groupName = $request->get('group_name');
        $active = $request->get('active');
        
        // Build base query - SHOW ALL CONTACTS (no grouping)
        $query = Customer::query();
        
        // Apply customer type filter - NO MIN(id) grouping
        if ($customerType === 'individual') {
            $query->where('customer_type', 'individual');
        } elseif ($customerType === 'company') {
            $query->where('customer_type', 'company'); // Show ALL company contacts
        }
        // else: show both types (all individuals + all company contacts)
        
        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('company', 'LIKE', "%{$search}%")
                  ->orWhere('designation', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply group filter
        if ($groupName) {
            $query->where('group_name', $groupName);
        }
        
        // Apply status filter
        if ($active !== null && $active !== '') {
            $query->where('active', $active);
        }
        
        // Sorting - Group company contacts together
        $sortCol = $request->get('sort', 'company');
        $sortDir = $request->get('dir', 'asc');
        
        $allowedSorts = ['id', 'name', 'email', 'phone', 'customer_type', 'created_at', 'company'];
        if (!in_array($sortCol, $allowedSorts)) {
            $sortCol = 'company';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'asc';
        }
        
        // Sort by company name first (groups contacts together), then by name
        if ($sortCol === 'company' || $sortCol === 'id') {
            $query->orderByRaw('CASE WHEN customer_type = "company" THEN company ELSE name END ' . $sortDir)
                  ->orderBy('name', 'asc')
                  ->orderBy('id', 'asc');
        } else {
            $query->orderBy($sortCol, $sortDir);
        }
        
        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 10;
        }
        
        $data = $query->paginate($perPage);
        $startSno = ($data->currentPage() - 1) * $perPage;
        
        // Format data
        $items = $data->getCollection()->map(function ($customer, $index) use ($startSno) {
            // For companies, count total contacts (optional display info)
            $contactCount = null;
            if ($customer->customer_type === 'company' && $customer->company) {
                $contactCount = Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->count();
            }
            
            return [
    'id' => $customer->id,
    'sno' => $startSno + $index + 1,
    'customer_type' => $customer->customer_type,
    
    // ✅ NEW: Format name as clickable HTML
    'name' => $customer->customer_type === 'company' && $customer->company
        ? '<a href="' . route('admin.customers.show', $customer->id) . '" style="text-decoration:none;color:inherit;"><div style="font-weight:600;color:#1e293b;font-size:14px;">' . e($customer->company) . '</div><div style="font-size:12px;color:#64748b;margin-top:3px;">' . e($customer->name) . '</div></a>'
        : '<a href="' . route('admin.customers.show', $customer->id) . '" style="text-decoration:none;color:inherit;"><div style="font-weight:600;color:#1e293b;font-size:14px;">' . e($customer->name) . '</div></a>',
    
    'company' => $customer->company,
    'full_name' => $customer->customer_type === 'company' && $customer->company
        ? $customer->name . ' • ' . $customer->company
        : $customer->name,
                
                'email' => $customer->email,
                'phone' => $customer->phone ?? '-',
                'designation' => $customer->designation ?? '-',
                'group_name' => $customer->group_name ?? '-',
                'contact_count' => $contactCount,
                
                //'active' => $customer->active ? 1 : 0,


                                'active' => '<div style="display:flex;align-items:center;gap:8px;">
                    <label class="status-toggle ' . ($customer->active ? 'toggle-active' : 'toggle-inactive') . '">
                        <input type="checkbox" ' . ($customer->active ? 'checked' : '') . ' onchange="toggleStatus(' . $customer->id . ', this)">
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="dt-badge ' . ($customer->active ? 'dt-badge-success' : 'dt-badge-secondary') . '">' . ($customer->active ? 'Active' : 'Inactive') . '</span>
                </div>',
                
                'status_badge' => $customer->status_badge,
                'type_badge' => $customer->type_badge,
                
                'created_at' => $customer->created_at ? $customer->created_at->format('d M Y') : '-',
                
                // URLs
                '_show_url' => route('admin.customers.show', $customer->id),
                '_edit_url' => route('admin.customers.edit', $customer->id),
                '_delete_url' => route('admin.customers.destroy', $customer->id),
            ];
        })->values();
        
        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
        
    } catch (Exception $e) {
        report($e);
        return response()->json([
            'data' => [],
            'total' => 0,
            'current_page' => 1,
            'last_page' => 1,
            'error' => 'Failed to load data: ' . $e->getMessage(),
        ], 500);
    }
}

    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    */
    
    // public function bulkDelete(Request $request)
    // {
    //     try {
    //         $ids = $request->input('ids', []);
            
    //         if (empty($ids)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No items selected'
    //             ], 400);
    //         }

    //         $customers = Customer::whereIn('id', $ids)->get();
    //         $count = 0;

    //         foreach ($customers as $customer) {
    //             if ($customer->customer_type === 'company') {
    //                 // Delete all contacts of this company
    //                 Customer::where('company', $customer->company)
    //                     ->where('customer_type', 'company')
    //                     ->delete();
    //             } else {
    //                 $customer->delete();
    //             }
    //             $count++;
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => $count . ' customer(s) deleted successfully'
    //         ]);
            
    //     } catch (Exception $e) {
    //         report($e);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Delete failed: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }





    public function bulkDelete(Request $request)
{
    try {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected'
            ], 400);
        }

        $customers = Customer::whereIn('id', $ids)->get();
        $deletedCount = 0;
        $processedCompanies = []; // Track companies already processed

        foreach ($customers as $customer) {
            // Skip if already processed (company was deleted in previous iteration)
            if (!Customer::find($customer->id)) {
                continue;
            }
            
            if ($customer->customer_type === 'company' && $customer->company) {
                // Skip if we already processed this company
                if (in_array($customer->company, $processedCompanies)) {
                    continue;
                }
                
                // Count total contacts in this company
                $totalContacts = Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->count();
                
                // Count how many from this company are selected
                $selectedFromCompany = Customer::whereIn('id', $ids)
                    ->where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->count();
                
                if ($selectedFromCompany >= $totalContacts) {
                    // ✅ All contacts selected or last contact → Delete entire company
                    $deleted = Customer::where('company', $customer->company)
                        ->where('customer_type', 'company')
                        ->delete();
                    $deletedCount += $deleted;
                } else {
                    // ✅ Only some contacts selected → Delete only selected ones
                    $deleted = Customer::whereIn('id', $ids)
                        ->where('company', $customer->company)
                        ->where('customer_type', 'company')
                        ->delete();
                    $deletedCount += $deleted;
                }
                
                // Mark this company as processed
                $processedCompanies[] = $customer->company;
                
            } else {
                // ✅ Individual customer
                $customer->delete();
                $deletedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' customer(s) deleted successfully'
        ]);
        
    } catch (Exception $e) {
        report($e);
        return response()->json([
            'success' => false,
            'message' => 'Delete failed: ' . $e->getMessage()
        ], 500);
    }
}
    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */
    
    public function toggleStatus(Request $request, $id)
    {
        try {
            $customer = $this->getCustomer($id);
            
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            
            $customer->active = $request->input('active', 0);
            $customer->save();
            
            // If company, update all contacts
            if ($customer->customer_type === 'company') {
                Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->update(['active' => $customer->active]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
            
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT MAPPING
    |--------------------------------------------------------------------------
    */
    
    protected function mapExportRow($customer)
    {
        return [
            'ID' => $customer->id,
            'Name' => $customer->name,
            'Email' => $customer->email,
            'Phone' => $customer->phone ?? '-',
            'Company' => $customer->company ?? '-',
            'Type' => ucfirst($customer->customer_type),
            'Group' => $customer->group_name ?? '-',
            'Active' => $customer->active ? 'Yes' : 'No',
            'Created Date' => $customer->created_at ? $customer->created_at->format('Y-m-d H:i:s') : '-',
        ];
   }
}