<?php

namespace App\Http\Controllers\Admin\Lead;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Lead;
use App\Traits\DataTable;
use App\Models\LeadsStatus;
use App\Models\LeadsSource;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class LeadController extends AdminController
{

use DataTable;

    protected $model = Lead::class;
    protected $with = ['leadStatus', 'leadSource', 'assignedUser'];
    protected $searchable = ['name', 'company', 'email', 'phonenumber'];
    protected $sortable = ['id', 'name', 'company', 'lead_value', 'created_at'];
    protected $filterable = ['status', 'source', 'assigned'];

    // EXPORT CONFIGURATION - JUST ADD THIS
    protected $exportable = [
        'id',
        'name',
        'company',
        'email',
        'phonenumber',
        'lead_value',
        'leadStatus.name',
        'leadSource.name',
        'assignedUser.name',
    ];

    // IMPORT CONFIGURATION - JUST ADD THIS
    protected $importable = [
        'name'        => 'required|string|max:255',
        'company'     => 'nullable|string|max:255',
        'email'       => 'nullable|email|max:255',
        'phonenumber' => 'nullable|string|max:20',
        'title'       => 'nullable|string|max:255',
        'address'     => 'nullable|string',
        'city'        => 'nullable|string|max:100',
        'state'       => 'nullable|string|max:100',
        'country'     => 'nullable|string|max:100',
        'zip'         => 'nullable|string|max:20',
        'website'     => 'nullable|url|max:255',
        'lead_value'  => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
        'status'      => 'nullable|integer',
        'source'      => 'nullable|integer',
        'assigned'    => 'nullable|integer',
    ];

    protected $uniqueField = 'email'; // Prevent duplicate emails



          

// ADD THIS METHOD - DataTable AJAX endpoint
public function data(Request $request)
{
    // HANDLE IMPORT - Pass to trait
    if ($request->hasFile('import_file') || $request->input('action') === 'import') {
        return $this->handleData($request);
    }


    // Build base query with relationships
    $query = Lead::with(['leadStatus', 'leadSource', 'assignedUser']);

    // Apply search
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phonenumber', 'like', "%{$search}%");
        });
    }

    // Apply filters
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }
    if ($request->has('source') && $request->source != '') {
        $query->where('source', $request->source);
    }
    if ($request->has('assigned') && $request->assigned != '') {
        $query->where('assigned', $request->assigned);
    }

    // Apply sorting
    $sortCol = $request->input('sort', 'id');
    $sortDir = $request->input('dir', 'desc');
    $query->orderBy($sortCol, $sortDir);

    // CHECK FOR EXPORT FIRST - Before pagination
    if ($request->has('export')) {
        $format = $request->input('export');
        $allData = $query->get(); // Get ALL filtered data (no pagination)
        
        // Map rows
        $exportData = $allData->map(function ($lead) {
            return $this->mapRow($lead);
        })->toArray();
        
        // Remove internal fields (starting with _)
        $exportData = array_map(function($row) {
            return array_filter($row, function($key) {
                return substr($key, 0, 1) !== '_';
            }, ARRAY_FILTER_USE_KEY);
        }, $exportData);
        
        // Route to correct export method
        return $this->exportData($exportData, $format);
    }

    // Normal pagination for table display
    $perPage = $request->input('per_page', 25);
    $data = $query->paginate($perPage);

    $items = $data->getCollection()->map(function ($lead) {
        return $this->mapRow($lead);
    });

    return response()->json([
        'data' => $items,
        'total' => $data->total(),
        'per_page' => $data->perPage(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
    ]);
}

/**
 * Route export to correct format
 */
protected function exportData($data, $format)
{
    $filename = 'leads_' . date('Y-m-d_His');
    
    switch ($format) {
        case 'csv':
            return $this->exportCsv($data, $filename);
        case 'excel':
            return $this->exportExcel($data, $filename);
        case 'pdf':
            return $this->exportPdf($data, $filename);
        default:
            return response()->json(['error' => 'Invalid export format'], 400);
    }
}

/**
 * Export as CSV
 */
protected function exportCsv($data, $filename)
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];

    $callback = function() use ($data) {
        $file = fopen('php://output', 'w');
        
        // Add CSV headers (column names)
        if (!empty($data)) {
            fputcsv($file, array_keys($data[0]));
        }
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Export as Excel
 */
protected function exportExcel($data, $filename)
{
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Add headers
    if (!empty($data)) {
        $headers = array_keys($data[0]);
        $sheet->fromArray([$headers], null, 'A1');
        
        // Bold headers
        $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers)) . '1')
              ->getFont()->setBold(true);
    }
    
    // Add data
    $sheet->fromArray($data, null, 'A2');
    
    // Auto-size columns
    foreach (range('A', $sheet->getHighestColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Generate file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
    $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
        'Cache-Control' => 'max-age=0',
    ];
    
    return response()->stream(function() use ($writer) {
        $writer->save('php://output');
    }, 200, $headers);
}

/**
 * Export as PDF
 */
protected function exportPdf($data, $filename)
{
    $html = '<html><head><style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #333; color: white; padding: 10px; text-align: left; font-weight: bold; }
        td { border: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background: #f9f9f9; }
        h2 { margin-bottom: 5px; }
        .timestamp { color: #666; font-size: 10px; }
    </style></head><body>';
    
    $html .= '<h2>Leads Export</h2>';
    $html .= '<p class="timestamp">Generated: ' . date('Y-m-d H:i:s') . '</p>';
    $html .= '<table><thead><tr>';
    
    // Headers
    if (!empty($data)) {
        foreach (array_keys($data[0]) as $header) {
            $html .= '<th>' . ucwords(str_replace('_', ' ', $header)) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        
        // Data rows
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
    }
    
    $html .= '</tbody></table></body></html>';
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
    $pdf->setPaper('A4', 'landscape');
    
    return $pdf->download($filename . '.pdf');
}

// ADD THIS METHOD - Transform database rows for table display
protected function mapRow($lead)
{
    return [
        'id' => $lead->id,
        'name' => $lead->name,
        'company' => $lead->company ?? '-',
        'email' => $lead->email ?? '-',
        'phone' => $lead->phonenumber ?? '-',
        'lead_value' => $lead->lead_value > 0 ? '₹' . number_format($lead->lead_value, 2) : '-',
        'status' => $lead->leadStatus->name ?? '-',
        'status_color' => $lead->leadStatus->color ?? '#3498db',
        'status_id' => $lead->status,
        'source' => $lead->leadSource->name ?? '-',
        'assigned' => $lead->assignedUser->name ?? '-',
        'assigned_avatar' => strtoupper(substr($lead->assignedUser->name ?? 'A', 0, 1)),
        'created_at' => $lead->created_at->format('Y-m-d'),

        '_show_url' => route('admin.leads.show', $lead->id),
        '_edit_url' => route('admin.leads.edit', $lead->id),
        '_delete_url' => route('admin.leads.destroy', $lead->id),
    ];
}









    public function index(Request $request)
{
    // Get stats ONLY
    $followupStatus = LeadsStatus::where('name', 'Followup')->first();
    $leadStatus = LeadsStatus::where('name', 'Lead')->first();
    $customerStatus = LeadsStatus::where('name', 'Customer')->first();

    $stats = [
        'followup' => $followupStatus ? Lead::where('status', $followupStatus->id)->count() : 0,
        'lead' => $leadStatus ? Lead::where('status', $leadStatus->id)->count() : 0,
        'customer' => $customerStatus ? Lead::where('status', $customerStatus->id)->count() : 0,
        'lost' => Lead::where('lost', 1)->count(),
        'lost_percentage' => 0,
    ];

    $totalLeads = Lead::count();
    if ($totalLeads > 0) {
        $stats['lost_percentage'] = round(($stats['lost'] / $totalLeads) * 100, 2);
    }

    // Get statuses and sources for filter dropdowns
    $statuses = LeadsStatus::ordered()->get();
    $sources = LeadsSource::orderBy('name')->get();
    $users = \App\Models\Admin::where('is_active', 1)->get();

    // DO NOT query $leads - DataTable will handle it via AJAX
    return view('admin.leads.index', compact('stats', 'statuses', 'sources','users'));
}





    public function create()
    {
       // $this->authorize('leads.create.create');

        $statuses = LeadsStatus::ordered()->get();
        $sources = LeadsSource::orderBy('name')->get();
        $admins = \App\Models\Admin::all();

        return view('admin.leads.create', compact('statuses', 'sources', 'admins'));
    }

    public function store(Request $request)
{
    // $this->authorizeAdmin();
    // $this->authorize('leads.create.create');
    
    try {
        $validated = $this->validateRequest($request, [
           // $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'assigned' => 'nullable|integer',
            'source' => 'required|integer|exists:leads_sources,id',
            'status' => 'required|integer|exists:leads_status,id',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'website' => 'nullable|url|max:255',
            'phonenumber' => 'nullable|string|max:20',
            'lead_value' => 'nullable|numeric|min:0',
        ]);

        // Set default assigned to current admin if not provided
        if (empty($validated['assigned'])) {
            $validated['assigned'] = auth()->guard('admin')->id();
        }

        // Set default status if not provided
        if (empty($validated['status'])) {
            $defaultStatus = LeadsStatus::where('isdefault', 1)->first();
            $validated['status'] = $defaultStatus ? $defaultStatus->id : 1;
        }

        // Set default source if not provided
        if (empty($validated['source'])) {
            $validated['source'] = 0;
        }

        // Set is_public checkbox
        $validated['is_public'] = $request->has('is_public') ? 1 : 0;

        // Create the lead (dateadded is set automatically by database default)
        $lead = Lead::create($validated);

        $this->logAction('create', [
            'entity' => 'Lead',
            'lead_id' => $lead->id,
            'name' => $lead->name,
        ]);

        return $this->redirectWithSuccess('admin.leads.index', 'Lead created successfully');
    } catch (\Exception $e) {
    $this->logError('Failed to create Lead', $e);
    return $this->redirectWithError('admin.leads.create', 'Failed to create lead: ' . $e->getMessage())
        ->withInput();  // ✅ Add this!
}
//     } catch (\Exception $e) {
//     \Log::error('Failed to create Lead: ' . $e->getMessage());
//     return redirect()->route('admin.leads.create')
//         ->withInput()
//         ->withErrors(['error' => 'Failed to create lead: ' . $e->getMessage()]);
// }
}

    public function show($id)
    {
        //$this->authorize('leads.list.read');
        $lead = Lead::with(['leadStatus', 'leadSource', 'assignedUser'])->findOrFail($id);
        return view('admin.leads.show', compact('lead'));
    }

    public function edit($id)
    {
        //$this->authorize('leads.list.edit');

        $lead = Lead::findOrFail($id);
        $statuses = LeadsStatus::ordered()->get();
        $sources = LeadsSource::orderBy('name')->get();
        $admins = \App\Models\Admin::all();

        return view('admin.leads.edit', compact('lead', 'statuses', 'sources', 'admins'));
    }
public function update(Request $request, $id)
{
    // $this->authorizeAdmin();
    // $this->authorize('leads.list.edit');

    try {
        $lead = Lead::findOrFail($id);

        $validated = $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'assigned' => 'nullable|integer',
            'source' => 'required|integer|exists:leads_sources,id',
            'status' => 'required|integer|exists:leads_status,id',
            'email' => 'nullable|email|max:255|unique:leads,email,' . $id,
            'website' => 'nullable|url|max:255',
            'phonenumber' => 'nullable|string|max:20',
            'lead_value' => 'nullable|numeric|min:0',
        ]);

        // Set is_public checkbox
        $validated['is_public'] = $request->has('is_public') ? 1 : 0;

        // Update the lead
        $lead->update($validated);

        $this->logAction('update', [
            'entity' => 'Lead',
            'lead_id' => $lead->id,
            'name' => $lead->name,
        ]);

        return $this->redirectWithSuccess('admin.leads.index', 'Lead updated successfully');
    } catch (\Exception $e) {
        $this->logError('Failed to update Lead', $e);
        return $this->redirectWithError('admin.leads.edit', 'Failed to update lead: ' . $e->getMessage(), ['id' => $id]);
    }
}



//     public function updateStatus(Request $request, $id)
// {
//     try {
//         $lead = Lead::findOrFail($id);
        
//         $validated = $request->validate([
//             'status' => 'required|integer|exists:leads_status,id'
//         ]);
        
//         $lead->update(['status' => $validated['status']]);
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Status updated successfully',
//             'status' => $lead->leadStatus
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to update status: ' . $e->getMessage()
//         ], 500);
//     }
// }








public function updateStatus(Request $request, $id)
{
    try {
        $lead = Lead::with(['leadStatus'])->findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|integer|exists:leads_status,id'
        ]);
        
        $oldStatusId = $lead->status;
        $newStatusId = $validated['status'];
        
        // Get "Customer" status ID
        $customerStatus = LeadsStatus::where('name', 'Customer')->first();
        $customerStatusId = $customerStatus ? $customerStatus->id : null;
        
        if (!$customerStatusId) {
            $lead->update(['status' => $newStatusId]);
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $lead->leadStatus
            ]);
        }
        
        // ========== CASE 1: Converting TO "Customer" ==========
        if ($newStatusId == $customerStatusId && $oldStatusId != $customerStatusId) {
            \Log::info("Converting lead #{$lead->id} to customer via dropdown");
            
            // Check if customer already exists
            $existingCustomer = null;
            if ($lead->email) {
                $existingCustomer = \App\Models\Customer::where('email', $lead->email)->first();
            }
            
            if (!$existingCustomer) {
                // Determine type
                $customerType = !empty($lead->company) ? 'company' : 'individual';
                
                try {
                    // Create customer record - MATCH THE WORKING convertToCustomer() METHOD
                    $customer = \App\Models\Customer::create([
                        'name' => $lead->name,  // ← FIXED: Use 'name' not firstname/lastname
                        'customer_type' => $customerType,
                        'email' => $lead->email ?? '',
                        'phone' => $lead->phonenumber ?? '',
                        'company' => $lead->company ?? '',
                        'designation' => $lead->title ?? '',
                        'website' => $lead->website ?? '',
                        'currency' => 1,
                        'address' => $lead->address ?? '',
                        'city' => $lead->city ?? '',
                        'state' => $lead->state ?? '',
                        'zip_code' => $lead->zip ?? '',
                        'country' => $lead->country ?? '',
                        'notes' => $lead->description ?? '',
                        'shipping_address' => $lead->address ?? '',
                        'shipping_city' => $lead->city ?? '',
                        'shipping_state' => $lead->state ?? '',
                        'shipping_zip_code' => $lead->zip ?? '',
                        'shipping_country' => $lead->country ?? '',
                        'gst_number' => null,
                        'group_name' => null,
                    ]);
                    
                    \Log::info("✅ Customer created: ID {$customer->id} from lead #{$lead->id}");
                    
                } catch (\Exception $e) {
                    \Log::error("❌ Failed to create customer: " . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create customer: ' . $e->getMessage()
                    ], 500);
                }
            }
        }
        
        // ========== CASE 2: Converting FROM "Customer" to something else ==========
        if ($oldStatusId == $customerStatusId && $newStatusId != $customerStatusId) {
            \Log::info("Removing customer status from lead #{$lead->id}");
            
            if ($lead->email) {
                $customer = \App\Models\Customer::where('email', $lead->email)->first();
                
                if ($customer) {
                    $customer->delete();
                    \Log::info("✅ Customer deleted: ID {$customer->id} for lead #{$lead->id}");
                }
            }
        }
        
        // Update lead status
        $lead->update(['status' => $newStatusId]);
        $lead->load('leadStatus');
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $lead->leadStatus
        ]);
        
    } catch (\Exception $e) {
        \Log::error('❌ Status update failed: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update status: ' . $e->getMessage()
        ], 500);
    }
}








public function quickCreateStatus(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        // Get max order
        $maxOrder = LeadsStatus::max('statusorder') ?? 0;
        
        $status = LeadsStatus::create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? '#3498db',
            'statusorder' => $maxOrder + 1,
            'isdefault' => 0,
        ]);

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => 'Status created successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create status: ' . $e->getMessage()
        ], 500);
    }
}

public function quickCreateSource(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $source = LeadsSource::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'success' => true,
            'source' => $source,
            'message' => 'Source created successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create source: ' . $e->getMessage()
        ], 500);
    }
}
















    public function destroy($id)
    {
       // $this->authorizeAdmin();
       // $this->authorize('leads.list.delete');

        try {
            $lead = Lead::findOrFail($id);
            $name = $lead->name;

            $lead->delete();

            $this->logAction('delete', ['entity' => 'Lead', 'name' => $name]);

            return $this->redirectWithSuccess('admin.leads.index', 'Lead deleted successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to delete Lead', $e);
            return $this->redirectWithError('admin.leads.index', 'Failed to delete lead: ' . $e->getMessage());
        }
    }





    public function convertToCustomer($id)
{
    try {
        $lead = Lead::with(['leadStatus', 'leadSource', 'assignedUser'])->findOrFail($id);
        
        // Check if already converted
        $customerStatus = LeadsStatus::where('name', 'Customer')->first();
        if ($customerStatus && $lead->status == $customerStatus->id) {
            return redirect()->back()->with('error', 'This lead is already converted to a customer.');
        }
        
        // Check if customer with this email already exists
        if ($lead->email && \App\Models\Customer::where('email', $lead->email)->exists()) {
            return redirect()->back()->with('error', 'A customer with this email already exists.');
        }
        
        // Determine customer type based on company
       // $customerType = !empty($lead->company) ? 'business' : 'individual';
       $customerType = !empty($lead->company) ? 'company' : 'individual';
        
        // Create customer
        $customer = \App\Models\Customer::create([
            'name' => $lead->name,
            'customer_type' => $customerType,
            'email' => $lead->email ?? '',
            'phone' => $lead->phonenumber ?? '',
            'company' => $lead->company ?? '',
            'designation' => $lead->title ?? '',
            'website' => $lead->website ?? '',
            'currency' => 1,
            'address' => $lead->address ?? '',
            'city' => $lead->city ?? '',
            'state' => $lead->state ?? '',
            'zip_code' => $lead->zip ?? '',
            'country' => $lead->country ?? '',
            'notes' => $lead->description ?? '',
            // Copy billing address to shipping address
            'shipping_address' => $lead->address ?? '',
            'shipping_city' => $lead->city ?? '',
            'shipping_state' => $lead->state ?? '',
            'shipping_zip_code' => $lead->zip ?? '',
            'shipping_country' => $lead->country ?? '',
            'gst_number' => null,
            'group_name' => null,
        ]);
        
        // Update lead status to "Customer"
        if ($customerStatus) {
            $lead->update([
                'status' => $customerStatus->id,
                'date_converted' => now(),
            ]);
        }
        
        $this->logAction('convert', [
            'entity' => 'Lead to Customer',
            'lead_id' => $lead->id,
            'customer_id' => $customer->id,
            'name' => $lead->name,
        ]);
        
        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead successfully converted to customer!');
            
    } catch (\Exception $e) {
        \Log::error('Failed to convert lead to customer: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Failed to convert lead: ' . $e->getMessage());
    }
}









}