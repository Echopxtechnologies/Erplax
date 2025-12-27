<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceRecord;
use Modules\Service\Models\ServiceRecordMaterial;
use Modules\Service\Models\ServiceVisit;
use Modules\Service\Models\ServiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ServiceController extends AdminController
{
    /**
     * DataTable endpoint
     */
    public function dataTable(Request $request)
    {
        if ($request->isMethod('post') && $request->hasFile('file')) {
            return $this->importServices($request);
        }

        if ($request->has('template')) {
            return $this->downloadTemplate();
        }

        if ($request->has('export')) {
            return $this->exportServices($request);
        }

        return $this->listServices($request);
    }

    /**
     * List services
     */
    protected function listServices(Request $request)
    {
        $query = Service::query()->with(['client', 'creator']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('machine_name', 'LIKE', "%{$search}%")
                  ->orWhere('equipment_no', 'LIKE', "%{$search}%")
                  ->orWhere('model_no', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('company', 'LIKE', "%{$search}%")
                         ->orWhere('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $this->applyFilters($query, $request);

        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $sortable = ['id', 'machine_name', 'service_frequency', 'first_service_date', 'next_service_date', 'status', 'service_status', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'client_name' => $item->client->company ?? $item->client->name ?? 'Unknown',
                'machine_name' => $item->machine_name,
                'equipment_no' => $item->equipment_no,
                'model_no' => $item->model_no,
                'serial_number' => $item->serial_number,
                'service_frequency' => $item->service_frequency,
                'frequency_label' => $item->frequency_label,
                'first_service_date' => $item->first_service_date?->format('Y-m-d'),
                'last_service_date' => $item->last_service_date?->format('Y-m-d'),
                'next_service_date' => $item->next_service_date?->format('Y-m-d'),
                'days_left' => $item->days_left,
                'status' => $item->status,
                'service_status' => $item->service_status,
                'is_overdue' => $item->is_overdue,
                '_edit_url' => route('admin.service.edit', $item->id),
                '_show_url' => route('admin.service.show', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    protected function applyFilters($query, Request $request)
    {
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($serviceStatus = $request->input('service_status')) {
            $query->where('service_status', $serviceStatus);
        }
        if ($clientId = $request->input('client_id')) {
            $query->where('client_id', $clientId);
        }
        if ($frequency = $request->input('service_frequency')) {
            $query->where('service_frequency', $frequency);
        }
        if ($request->input('overdue')) {
            $query->overdue();
        }
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('next_service_date', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('next_service_date', '<=', $toDate);
        }
    }

    public function index()
    {
        $stats = [
            'total' => Service::count(),
            'active' => Service::where('status', 'active')->count(),
            'pending' => Service::where('service_status', 'pending')->count(),
            'completed' => Service::where('service_status', 'completed')->count(),
            'overdue' => Service::overdue()->count(),
        ];
        $clients = Customer::orderBy('company')->orderBy('name')->get();
        return view('service::index', compact('stats', 'clients'));
    }

    public function create()
    {
        $clients = Customer::orderBy('company')->orderBy('name')->get();
        $engineers = User::orderBy('name')->get();
        return view('service::create', compact('clients', 'engineers'));
    }

    public function store(Request $request)
    {
        $user = $this->admin();
        
        $validated = $request->validate([
            'client_id' => 'required|exists:customers,id',
            'machine_name' => 'required|string|max:255',
            'equipment_no' => 'nullable|string|max:255',
            'model_no' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'service_frequency' => 'required|in:monthly,quarterly,half_yearly,yearly,custom',
            'custom_days' => 'nullable|integer|min:1',
            'first_service_date' => 'required|date',
            'next_service_date' => 'nullable|date',
            'status' => 'in:active,inactive',
            'service_status' => 'in:draft,pending,completed,overdue,canceled',
            'notes' => 'nullable|string',
            'reminder_days' => 'nullable|integer|min:1|max:90',
            'auto_reminder' => 'nullable',
        ]);

        $validated['created_by'] = $user->id;
        $validated['auto_reminder'] = $request->has('auto_reminder') ? true : false;
        
        // Calculate next_service_date based on frequency (server-side calculation)
        $firstDate = Carbon::parse($validated['first_service_date']);
        $frequency = $validated['service_frequency'];
        
        if ($frequency === 'custom' && !empty($validated['next_service_date'])) {
            // Use the manually entered date for custom frequency
            $validated['next_service_date'] = $validated['next_service_date'];
        } else {
            // Calculate based on frequency
            switch ($frequency) {
                case 'monthly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonth()->format('Y-m-d');
                    break;
                case 'quarterly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonths(3)->format('Y-m-d');
                    break;
                case 'half_yearly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonths(6)->format('Y-m-d');
                    break;
                case 'yearly':
                    $validated['next_service_date'] = $firstDate->copy()->addYear()->format('Y-m-d');
                    break;
                default:
                    $validated['next_service_date'] = $validated['first_service_date'];
            }
        }

        Service::create($validated);
        return redirect()->route('admin.service.index')->with('success', 'Service contract created successfully!');
    }

    public function show($id)
    {
        $service = Service::with([
            'client', 
            'creator', 
            'serviceRecords.engineer', 
            'serviceRecords.materials',
            'visits.engineer',
            'notifications'
        ])->findOrFail($id);
        
        $engineers = User::orderBy('name')->get();
        
        // Get products from database directly (most reliable)
        $products = collect([]);
        if (Schema::hasTable('products')) {
            $products = DB::table('products')
                ->select('id', 'name', 'sku', 'purchase_price', 'sale_price')
                ->orderBy('name')
                ->get();
        }
        
        // Get taxes for material selection
        $taxes = collect([]);
        if (Schema::hasTable('taxes')) {
            // Try with active column, fallback without it
            try {
                $taxes = DB::table('taxes')
                    ->select('id', 'name', 'rate')
                    ->where('active', 1)
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                // Try without active column
                $taxes = DB::table('taxes')
                    ->select('id', 'name', 'rate')
                    ->orderBy('name')
                    ->get();
            }
        }
        
        \Log::info('Service Show - Products count: ' . $products->count() . ', Taxes count: ' . $taxes->count());
        
        // Get related invoices through service records
        $invoiceIds = $service->serviceRecords->pluck('invoice_id')->filter()->unique()->toArray();
        $invoices = collect([]);
        if (!empty($invoiceIds) && Schema::hasTable('invoices')) {
            $invoices = DB::table('invoices')
                ->whereIn('id', $invoiceIds)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('service::show', compact('service', 'engineers', 'products', 'invoices', 'taxes'));
    }

    public function edit($id)
    {
        $service = Service::with(['client'])->findOrFail($id);
        $clients = Customer::orderBy('company')->orderBy('name')->get();
        $engineers = User::orderBy('name')->get();
        return view('service::edit', compact('service', 'clients', 'engineers'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:customers,id',
            'machine_name' => 'required|string|max:255',
            'equipment_no' => 'nullable|string|max:255',
            'model_no' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'service_frequency' => 'required|in:monthly,quarterly,half_yearly,yearly,custom',
            'custom_days' => 'nullable|integer|min:1',
            'first_service_date' => 'required|date',
            'next_service_date' => 'nullable|date',
            'status' => 'in:active,inactive',
            'service_status' => 'in:draft,pending,completed,overdue,canceled',
            'notes' => 'nullable|string',
            'reminder_days' => 'nullable|integer|min:1|max:90',
            'auto_reminder' => 'nullable',
        ]);

        // Handle auto_reminder checkbox (unchecked = false)
        $validated['auto_reminder'] = $request->has('auto_reminder') ? true : false;

        // Calculate next_service_date based on frequency (server-side)
        $firstDate = Carbon::parse($validated['first_service_date']);
        $frequency = $validated['service_frequency'];
        
        if ($frequency === 'custom' && !empty($validated['next_service_date'])) {
            // Use the manually entered date for custom frequency
            // Keep as is
        } else {
            // Calculate based on frequency
            switch ($frequency) {
                case 'monthly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonth()->format('Y-m-d');
                    break;
                case 'quarterly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonths(3)->format('Y-m-d');
                    break;
                case 'half_yearly':
                    $validated['next_service_date'] = $firstDate->copy()->addMonths(6)->format('Y-m-d');
                    break;
                case 'yearly':
                    $validated['next_service_date'] = $firstDate->copy()->addYear()->format('Y-m-d');
                    break;
                default:
                    $validated['next_service_date'] = $validated['first_service_date'];
            }
        }

        $service->update($validated);
        return redirect()->route('admin.service.index')->with('success', 'Service contract updated successfully!');
    }

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Service deleted successfully']);
        }
        return redirect()->route('admin.service.index')->with('success', 'Service deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = Service::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => $deleted . ' items deleted']);
    }

    public function refreshDates($id)
    {
        $service = Service::findOrFail($id);
        
        $lastRecord = $service->serviceRecords()
                              ->where('status', 'completed')
                              ->orderBy('service_date', 'desc')
                              ->first();

        if ($lastRecord) {
            $service->last_service_date = $lastRecord->service_date;
            $service->next_service_date = $service->calculateNextServiceDate($lastRecord->service_date);
        } else {
            $service->next_service_date = $service->first_service_date;
        }
        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Dates refreshed successfully',
            'last_service_date' => $service->last_service_date?->format('d-m-Y'),
            'next_service_date' => $service->next_service_date?->format('d-m-Y'),
        ]);
    }

    // ==================== SERVICE RECORDS ====================

    public function storeRecord(Request $request, $serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $user = $this->admin();

        $validated = $request->validate([
            'engineer_id' => 'nullable|exists:users,id',
            'service_type' => 'nullable|string|max:255',
            'service_date' => 'required|date',
            'service_time' => 'nullable',
            'time_taken' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,in_progress,completed,canceled',
            'remarks' => 'nullable|string',
            'work_done' => 'nullable|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'is_paid' => 'nullable|boolean',
            'service_charge' => 'nullable|numeric|min:0',
        ]);

        $validated['service_id'] = $serviceId;
        $validated['created_by'] = $user->id;
        $validated['labor_cost'] = $validated['labor_cost'] ?? 0;
        $validated['is_paid'] = $request->has('is_paid') && $request->is_paid == '1';
        $validated['service_charge'] = $validated['service_charge'] ?? 0;

        // Generate service reference if paid
        if ($validated['is_paid']) {
            $validated['service_reference'] = ServiceRecord::generateServiceReference();
        }

        $record = ServiceRecord::create($validated);

        if ($request->has('materials')) {
            foreach ($request->input('materials') as $material) {
                if (!empty($material['material_name']) || !empty($material['product_id'])) {
                    $quantity = floatval($material['quantity'] ?? 1);
                    $unitPrice = floatval($material['unit_price'] ?? 0);
                    $subtotal = $quantity * $unitPrice;
                    
                    // Calculate tax amount
                    $taxIds = $material['tax_ids'] ?? null;
                    $taxAmount = 0;
                    $taxIdsJson = null;
                    
                    if ($taxIds && $taxIds !== '') {
                        // Parse tax_ids - could be single value, array, or JSON string
                        $taxIdsArray = [];
                        
                        if (is_array($taxIds)) {
                            $taxIdsArray = $taxIds;
                        } elseif (is_numeric($taxIds)) {
                            // Single tax ID selected from dropdown
                            $taxIdsArray = [intval($taxIds)];
                        } else {
                            // Try JSON decode
                            $decoded = json_decode($taxIds, true);
                            if (is_array($decoded)) {
                                $taxIdsArray = $decoded;
                            } elseif (is_numeric($decoded)) {
                                $taxIdsArray = [intval($decoded)];
                            }
                        }
                        
                        // Calculate tax if we have valid tax IDs
                        if (!empty($taxIdsArray) && Schema::hasTable('taxes')) {
                            $taxes = DB::table('taxes')->whereIn('id', $taxIdsArray)->get();
                            foreach ($taxes as $tax) {
                                $taxAmount += ($subtotal * $tax->rate / 100);
                            }
                        }
                        
                        // Store as JSON
                        $taxIdsJson = !empty($taxIdsArray) ? json_encode($taxIdsArray) : null;
                    }
                    
                    ServiceRecordMaterial::create([
                        'service_record_id' => $record->id,
                        'product_id' => $material['product_id'] ?? null,
                        'material_name' => $material['material_name'] ?? '',
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $subtotal + $taxAmount, // Include tax in total
                        'tax_ids' => $taxIdsJson,
                        'tax_amount' => $taxAmount,
                        'notes' => $material['notes'] ?? null,
                    ]);
                }
            }
            $record->updateTotalCost();
        }

        // Create invoice if paid service
        $invoiceCreated = false;
        $invoiceId = null;
        
        \Log::info('=== Invoice Creation Check ===');
        \Log::info('is_paid value: ' . var_export($validated['is_paid'], true));
        \Log::info('service_charge value: ' . var_export($validated['service_charge'], true));
        \Log::info('Condition result: ' . (($validated['is_paid'] && $validated['service_charge'] > 0) ? 'TRUE - Will create invoice' : 'FALSE - Will NOT create invoice'));
        
        if ($validated['is_paid'] && $validated['service_charge'] > 0) {
            \Log::info('Attempting to create invoice for service record: ' . $record->id);
            \Log::info('Service ID: ' . $service->id);
            \Log::info('Client ID: ' . ($service->client_id ?? 'NULL'));
            $invoiceId = $this->createServiceInvoice($record, $service);
            $invoiceCreated = $invoiceId !== false;
            \Log::info('Invoice creation result: ' . ($invoiceCreated ? 'Success, ID: ' . $invoiceId : 'Failed'));
        } else {
            \Log::info('Skipping invoice creation - conditions not met');
        }

        // Send email notifications
        $emailsSent = [];
        
        // Send service completed email if status is completed
        if ($record->status === 'completed') {
            $record->load('engineer'); // Load engineer for email
            if ($this->sendServiceCompletedEmail($service, $record)) {
                $emailsSent[] = 'Service completed notification sent';
            }
        }
        
        // Send invoice email if invoice was created
        if ($invoiceCreated && $invoiceId) {
            if ($this->sendInvoiceCreatedEmail($service, $record, $invoiceId)) {
                $emailsSent[] = 'Invoice notification sent';
            }
        }

        $message = 'Service record created successfully';
        if ($invoiceCreated) {
            $message .= '. Invoice created.';
        }
        if (!empty($emailsSent)) {
            $message .= ' ' . implode('. ', $emailsSent) . '.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'record' => $record->load('engineer', 'materials'),
                'invoice_created' => $invoiceCreated,
                'emails_sent' => $emailsSent,
            ]);
        }

        return redirect()->route('admin.service.show', $serviceId)
            ->with('success', $message);
    }

    /**
     * Create invoice for paid service
     */
    protected function createServiceInvoice($record, $service)
    {
        try {
            // Reload materials to ensure fresh data with tax_ids
            $record->load('materials');
            
            \Log::info('Creating invoice for record ID: ' . $record->id);
            \Log::info('Materials count: ' . $record->materials->count());
            
            // Calculate subtotal and tax amounts
            $serviceCharge = floatval($record->service_charge ?? 0);
            $materialsSubtotal = 0;
            $totalTaxAmount = 0;
            
            foreach ($record->materials as $material) {
                if ($material->quantity > 0 && $material->unit_price > 0) {
                    $materialsSubtotal += ($material->quantity * $material->unit_price);
                    $totalTaxAmount += floatval($material->tax_amount ?? 0);
                }
            }
            
            $subtotal = $serviceCharge + $materialsSubtotal;
            $totalAmount = $subtotal + $totalTaxAmount;
            
            \Log::info('Invoice totals - Subtotal: ' . $subtotal . ', Tax: ' . $totalTaxAmount . ', Total: ' . $totalAmount);
            
            if ($subtotal <= 0) {
                \Log::warning('Invoice not created: subtotal is zero or negative');
                return false;
            }

            // Generate invoice number matching existing format: INV-2025-000004
            // Use MAX to find the highest existing number, not COUNT
            $year = date('Y');
            $prefix = 'INV-' . $year . '-';
            
            $lastInvoice = DB::table('invoices')
                ->where('invoice_number', 'LIKE', $prefix . '%')
                ->orderBy('invoice_number', 'desc')
                ->first();
            
            $nextNumber = 1;
            if ($lastInvoice && $lastInvoice->invoice_number) {
                // Extract the number part (last 6 digits)
                $lastNumber = (int) substr($lastInvoice->invoice_number, -6);
                $nextNumber = $lastNumber + 1;
            }
            
            $invoiceNumber = $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            
            \Log::info('Generated invoice number: ' . $invoiceNumber);

            // Service reference for tracking
            $serviceReference = $record->service_reference ?? $record->reference_no;

            // Handle date - ensure it's properly formatted
            $serviceDate = $record->service_date;
            if ($serviceDate instanceof Carbon) {
                $invoiceDate = $serviceDate->format('Y-m-d');
                $dueDate = $serviceDate->copy()->addDays(30)->format('Y-m-d');
            } elseif ($serviceDate) {
                $invoiceDate = date('Y-m-d', strtotime($serviceDate));
                $dueDate = date('Y-m-d', strtotime($serviceDate . ' +30 days'));
            } else {
                $invoiceDate = date('Y-m-d');
                $dueDate = date('Y-m-d', strtotime('+30 days'));
            }
            
            \Log::info('Invoice dates - Date: ' . $invoiceDate . ', Due: ' . $dueDate);

            // Check customer_id
            $customerId = $service->client_id ?? $service->customer_id ?? null;
            \Log::info('Customer ID: ' . ($customerId ?? 'NULL'));
            
            if (!$customerId) {
                \Log::warning('No customer_id found for service: ' . $service->id);
            }

            // Create invoice with service reference for tracking
            // Check which column name is used for invoice number
            $invoiceNumberColumn = Schema::hasColumn('invoices', 'invoice_number') ? 'invoice_number' : 'number';
            
            $invoiceData = [
                'customer_id' => $customerId,
                $invoiceNumberColumn => $invoiceNumber,
                'subject' => 'Service Invoice - ' . ($service->machine_name ?? 'Service'),
                'date' => $invoiceDate,
                'due_date' => $dueDate,
                'subtotal' => $subtotal,
                'discount' => 0,
                'discount_type' => 'no_discount',
                'discount_percent' => 0,
                'discount_amount' => 0,
                'tax' => $totalTaxAmount,
                'tax_amount' => $totalTaxAmount,
                'adjustment' => 0,
                'total' => $totalAmount,
                'amount_paid' => 0,
                'amount_due' => $totalAmount,
                'status' => 'draft',
                'payment_status' => 'unpaid',
                'currency' => 'INR',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Add content/notes if column exists  
            if (Schema::hasColumn('invoices', 'content')) {
                $invoiceData['content'] = 'Service Reference: ' . $serviceReference;
            }
            
            // Add admin note
            if (Schema::hasColumn('invoices', 'admin_note')) {
                $invoiceData['admin_note'] = 'Auto-generated from Service Module. Record: ' . $record->reference_no;
            }
            
            // Add created_by if column exists
            if (Schema::hasColumn('invoices', 'created_by')) {
                $invoiceData['created_by'] = $record->created_by;
            }
            
            // Add service tracking fields if columns exist
            if (Schema::hasColumn('invoices', 'service_reference')) {
                $invoiceData['service_reference'] = $serviceReference;
            }
            if (Schema::hasColumn('invoices', 'service_id')) {
                $invoiceData['service_id'] = $service->id;
            }
            if (Schema::hasColumn('invoices', 'service_record_id')) {
                $invoiceData['service_record_id'] = $record->id;
            }
            
            \Log::info('Creating service invoice with data:', $invoiceData);
            
            $invoiceId = DB::table('invoices')->insertGetId($invoiceData);
            
            \Log::info('Invoice created with ID: ' . $invoiceId);
            
            // Initialize sort order for items
            $sortOrder = 0;

            // Add service charge as line item
            if ($serviceCharge > 0) {
                $serviceItemData = [
                    'invoice_id' => $invoiceId,
                    'item_type' => 'service',
                    'product_id' => null,
                    'description' => 'Service Charge - ' . ($record->service_type ?? 'Maintenance'),
                    'long_description' => 'Equipment: ' . $service->machine_name . 
                        ($service->serial_number ? ' | S/N: ' . $service->serial_number : '') .
                        ' | Ref: ' . $serviceReference,
                    'quantity' => 1,
                    'unit' => null,
                    'rate' => $serviceCharge,
                    'tax_ids' => null,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'amount' => $serviceCharge,
                    'sort_order' => $sortOrder++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                DB::table('invoice_items')->insert($serviceItemData);
                \Log::info('Service charge item added to invoice ' . $invoiceId);
            }

            // Add materials as line items with taxes
            foreach ($record->materials as $material) {
                if ($material->quantity > 0 && $material->unit_price > 0) {
                    // Calculate tax rate from tax_ids
                    $taxRate = 0;
                    $taxIds = $material->tax_ids;
                    $taxIdsArray = [];
                    
                    if ($taxIds) {
                        // Parse tax_ids safely
                        if (is_array($taxIds)) {
                            $taxIdsArray = $taxIds;
                        } elseif (is_numeric($taxIds)) {
                            $taxIdsArray = [intval($taxIds)];
                        } elseif (is_string($taxIds)) {
                            $decoded = json_decode($taxIds, true);
                            if (is_array($decoded)) {
                                $taxIdsArray = $decoded;
                            } elseif (is_numeric($decoded)) {
                                $taxIdsArray = [intval($decoded)];
                            }
                        }
                        
                        if (!empty($taxIdsArray) && Schema::hasTable('taxes')) {
                            $taxes = DB::table('taxes')->whereIn('id', $taxIdsArray)->get();
                            foreach ($taxes as $tax) {
                                $taxRate += floatval($tax->rate);
                            }
                        }
                    }
                    
                    // Store tax_ids as JSON string for invoice_items
                    $taxIdsJson = !empty($taxIdsArray) ? json_encode($taxIdsArray) : null;
                    
                    // Get product name safely
                    $productName = $material->material_name;
                    if (empty($productName) && $material->product_id) {
                        $product = DB::table('products')->where('id', $material->product_id)->first();
                        $productName = $product->name ?? 'Material';
                    }
                    
                    // Calculate amounts
                    $lineSubtotal = $material->quantity * $material->unit_price;
                    $lineTaxAmount = floatval($material->tax_amount ?? 0);
                    $lineAmount = $lineSubtotal + $lineTaxAmount;
                    
                    $materialItemData = [
                        'invoice_id' => $invoiceId,
                        'item_type' => 'product',
                        'product_id' => $material->product_id,
                        'description' => $productName ?: 'Material',
                        'long_description' => $material->notes,
                        'quantity' => $material->quantity,
                        'unit' => null,
                        'rate' => $material->unit_price,
                        'tax_ids' => $taxIdsJson,
                        'tax_rate' => $taxRate,
                        'tax_amount' => $lineTaxAmount,
                        'amount' => $lineAmount,
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    DB::table('invoice_items')->insert($materialItemData);
                }
            }

            // Link invoice to service record
            $record->invoice_id = $invoiceId;
            $record->saveQuietly();
            
            \Log::info("Service Invoice created: {$invoiceNumber} for Service Ref: {$serviceReference}, Total: {$totalAmount}");

            return $invoiceId;
            
        } catch (\Exception $e) {
            \Log::error('Failed to create service invoice: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return false;
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markInvoicePaid($invoiceId)
    {
        try {
            $updated = DB::table('invoices')
                ->where('id', $invoiceId)
                ->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice marked as paid'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('Failed to mark invoice as paid: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice'
            ], 500);
        }
    }

    public function updateRecord(Request $request, $serviceId, $recordId)
    {
        $record = ServiceRecord::where('service_id', $serviceId)->findOrFail($recordId);

        $validated = $request->validate([
            'engineer_id' => 'nullable|exists:users,id',
            'service_type' => 'nullable|string|max:255',
            'service_date' => 'required|date',
            'service_time' => 'nullable',
            'time_taken' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,in_progress,completed,canceled',
            'remarks' => 'nullable|string',
            'work_done' => 'nullable|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'is_paid' => 'nullable|boolean',
            'service_charge' => 'nullable|numeric|min:0',
        ]);

        $validated['is_paid'] = $request->has('is_paid') && $request->is_paid == '1';
        
        // Generate service reference if newly marked as paid
        if ($validated['is_paid'] && !$record->is_paid && empty($record->service_reference)) {
            $validated['service_reference'] = ServiceRecord::generateServiceReference();
        }

        $record->update($validated);

        if ($request->has('materials')) {
            $record->materials()->delete();
            foreach ($request->input('materials') as $material) {
                if (!empty($material['material_name']) || !empty($material['product_id'])) {
                    ServiceRecordMaterial::create([
                        'service_record_id' => $record->id,
                        'product_id' => $material['product_id'] ?? null,
                        'material_name' => $material['material_name'] ?? '',
                        'quantity' => $material['quantity'] ?? 1,
                        'unit_price' => $material['unit_price'] ?? 0,
                        'total' => ($material['quantity'] ?? 1) * ($material['unit_price'] ?? 0),
                        'notes' => $material['notes'] ?? null,
                    ]);
                }
            }
            $record->updateTotalCost();
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Service record updated successfully']);
        }
        return redirect()->route('admin.service.show', $serviceId)->with('success', 'Service record updated!');
    }

    /**
     * Get single record for editing
     */
    public function getRecord($serviceId, $recordId)
    {
        $record = ServiceRecord::with(['engineer', 'materials.product'])
            ->where('service_id', $serviceId)
            ->findOrFail($recordId);
        
        return response()->json([
            'success' => true,
            'record' => $record,
        ]);
    }

    public function deleteRecord($serviceId, $recordId)
    {
        try {
            // First verify the service exists
            $service = Service::findOrFail($serviceId);
            
            // Then find and delete the record
            $record = ServiceRecord::where('service_id', $serviceId)
                                   ->where('id', $recordId)
                                   ->first();
            
            if (!$record) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Service record not found'], 404);
                }
                return redirect()->route('admin.service.show', $serviceId)->with('error', 'Service record not found!');
            }
            
            $record->delete();
            
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Service record deleted']);
            }
            return redirect()->route('admin.service.show', $serviceId)->with('success', 'Service record deleted!');
            
        } catch (\Exception $e) {
            \Log::error('Delete record error: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.service.show', $serviceId)->with('error', 'Failed to delete record!');
        }
    }

    // ==================== SERVICE VISITS ====================

    public function storeVisit(Request $request, $serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $user = $this->admin();

        $validated = $request->validate([
            'engineer_id' => 'nullable|exists:users,id',
            'visit_date' => 'required|date',
            'visit_time' => 'nullable',
            'status' => 'required|in:scheduled,in_progress,completed,canceled,rescheduled',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['service_id'] = $serviceId;
        $validated['created_by'] = $user->id;

        $visit = ServiceVisit::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Visit scheduled successfully',
                'visit' => $visit->load('engineer'),
            ]);
        }
        return redirect()->route('admin.service.show', $serviceId)->with('success', 'Visit scheduled!');
    }

    public function updateVisit(Request $request, $serviceId, $visitId)
    {
        $visit = ServiceVisit::where('service_id', $serviceId)->findOrFail($visitId);

        $validated = $request->validate([
            'engineer_id' => 'nullable|exists:users,id',
            'visit_date' => 'nullable|date',
            'visit_time' => 'nullable',
            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',
            'status' => 'nullable|in:scheduled,in_progress,completed,canceled,rescheduled',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $visit->update(array_filter($validated, fn($v) => $v !== null));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Visit updated successfully']);
        }
        return redirect()->route('admin.service.show', $serviceId)->with('success', 'Visit updated!');
    }

    public function deleteVisit($serviceId, $visitId)
    {
        ServiceVisit::where('service_id', $serviceId)->findOrFail($visitId)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Visit deleted']);
        }
        return redirect()->route('admin.service.show', $serviceId)->with('success', 'Visit deleted!');
    }

    // ==================== NOTIFICATIONS ====================

    /**
     * Send service reminder email to client
     */
    public function sendReminder($id)
    {
        $service = Service::with('client')->findOrFail($id);
        $clientEmail = $service->client->email ?? null;
        
        if (!$clientEmail) {
            return response()->json(['success' => false, 'message' => 'Client email not found'], 400);
        }

        try {
            $subject = "Service Reminder - {$service->machine_name}";
            $body = $this->getServiceReminderEmailBody($service);

            // Send email using MailService helper
            $sent = false;
            if (function_exists('send_mail')) {
                $sent = send_mail($clientEmail, $subject, $body);
            }

            // Log notification
            ServiceNotification::create([
                'service_id' => $service->id,
                'type' => 'reminder',
                'email_to' => $clientEmail,
                'subject' => $subject,
                'message' => $body,
                'status' => $sent ? 'sent' : 'failed',
                'sent_at' => $sent ? now() : null,
            ]);

            if ($sent) {
                $service->last_reminder_sent = now();
                $service->save();
            }

            return response()->json([
                'success' => $sent,
                'message' => $sent 
                    ? 'Reminder sent successfully to ' . $clientEmail 
                    : 'Failed to send email. Check mail settings.',
                'sent_at' => $sent ? now()->format('d-m-Y h:i A') : null,
            ]);

        } catch (\Exception $e) {
            \Log::error('Service reminder failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send service completed notification
     */
    public function sendServiceCompletedEmail($service, $record)
    {
        $clientEmail = $service->client->email ?? null;
        if (!$clientEmail) {
            return false;
        }

        try {
            $subject = "Service Completed - {$service->machine_name}";
            $body = $this->getServiceCompletedEmailBody($service, $record);

            $sent = false;
            if (function_exists('send_mail')) {
                $sent = send_mail($clientEmail, $subject, $body);
            }

            ServiceNotification::create([
                'service_id' => $service->id,
                'type' => 'service_completed',
                'email_to' => $clientEmail,
                'subject' => $subject,
                'message' => $body,
                'status' => $sent ? 'sent' : 'failed',
                'sent_at' => $sent ? now() : null,
            ]);

            return $sent;
        } catch (\Exception $e) {
            \Log::error('Service completed email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send invoice created notification
     */
    public function sendInvoiceCreatedEmail($service, $record, $invoiceId)
    {
        $clientEmail = $service->client->email ?? null;
        if (!$clientEmail) {
            return false;
        }

        try {
            // Get invoice details
            $invoice = DB::table('invoices')->find($invoiceId);
            if (!$invoice) {
                return false;
            }

            $subject = "Invoice Generated - {$invoice->number}";
            $body = $this->getInvoiceCreatedEmailBody($service, $record, $invoice);

            $sent = false;
            if (function_exists('send_mail')) {
                $sent = send_mail($clientEmail, $subject, $body);
            }

            ServiceNotification::create([
                'service_id' => $service->id,
                'type' => 'invoice_created',
                'email_to' => $clientEmail,
                'subject' => $subject,
                'message' => $body,
                'status' => $sent ? 'sent' : 'failed',
                'sent_at' => $sent ? now() : null,
            ]);

            return $sent;
        } catch (\Exception $e) {
            \Log::error('Invoice email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Manual endpoint to send service completed email
     */
    public function sendCompletedEmailManual($serviceId, $recordId)
    {
        $service = Service::with('client')->findOrFail($serviceId);
        $record = ServiceRecord::with('engineer')->where('service_id', $serviceId)->findOrFail($recordId);
        
        $sent = $this->sendServiceCompletedEmail($service, $record);
        
        return response()->json([
            'success' => $sent,
            'message' => $sent 
                ? 'Service completed email sent successfully' 
                : 'Failed to send email. Check mail settings.'
        ]);
    }

    /**
     * Manual endpoint to send invoice email
     */
    public function sendInvoiceEmailManual($serviceId, $recordId)
    {
        $service = Service::with('client')->findOrFail($serviceId);
        $record = ServiceRecord::where('service_id', $serviceId)->findOrFail($recordId);
        
        if (!$record->invoice_id) {
            return response()->json([
                'success' => false,
                'message' => 'No invoice linked to this service record'
            ], 400);
        }
        
        $sent = $this->sendInvoiceCreatedEmail($service, $record, $record->invoice_id);
        
        return response()->json([
            'success' => $sent,
            'message' => $sent 
                ? 'Invoice email sent successfully' 
                : 'Failed to send email. Check mail settings.'
        ]);
    }

    /**
     * Get service reminder email body
     */
    protected function getServiceReminderEmailBody($service): string
    {
        $nextDate = $service->next_service_date ? $service->next_service_date->format('d M Y') : 'N/A';
        $daysLeft = $service->days_left ?? 0;
        $daysText = $daysLeft > 0 ? "{$daysLeft} days remaining" : ($daysLeft == 0 ? "Due today" : abs($daysLeft) . " days overdue");

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%); padding: 20px; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #fff; margin: 0;'>Service Reminder</h2>
            </div>
            
            <div style='background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0;'>
                <p style='color: #1e293b;'>Dear <strong>{$service->client->name}</strong>,</p>
                
                <p style='color: #475569;'>This is a friendly reminder that your scheduled service is approaching:</p>
                
                <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Equipment</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;'>{$service->machine_name}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Serial No</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>" . ($service->serial_number ?? 'N/A') . "</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Due</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;'>{$nextDate}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Status</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: " . ($daysLeft < 0 ? '#dc2626' : '#16a34a') . "; font-weight: 600;'>{$daysText}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Frequency</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$service->frequency_label}</td>
                    </tr>
                </table>
                
                <p style='color: #475569;'>Please contact us to schedule your service appointment at your earliest convenience.</p>
                
                <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>
                    If you have any questions, feel free to reply to this email or contact our support team.
                </p>
            </div>
            
            <div style='background: #1e293b; padding: 15px; border-radius: 0 0 8px 8px; text-align: center;'>
                <p style='color: #94a3b8; margin: 0; font-size: 12px;'>This is an automated reminder from {company_name}</p>
            </div>
        </div>";
    }

    /**
     * Get service completed email body
     */
    protected function getServiceCompletedEmailBody($service, $record): string
    {
        $serviceDate = $record->service_date ? $record->service_date->format('d M Y') : date('d M Y');
        $nextDate = $service->next_service_date ? $service->next_service_date->format('d M Y') : 'To be scheduled';
        $materialsTotal = $record->materials_total ?? 0;
        $laborCost = $record->labor_cost ?? 0;
        $totalCost = $record->total_cost ?? ($materialsTotal + $laborCost);

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 20px; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #fff; margin: 0;'>✓ Service Completed</h2>
            </div>
            
            <div style='background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0;'>
                <p style='color: #1e293b;'>Dear <strong>{$service->client->name}</strong>,</p>
                
                <p style='color: #475569;'>We are pleased to inform you that the scheduled service has been completed successfully.</p>
                
                <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Reference</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;'>{$record->reference_no}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Equipment</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$service->machine_name}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Date</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$serviceDate}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Type</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>" . ($record->service_type ?? 'Maintenance') . "</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Engineer</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>" . ($record->engineer->name ?? 'N/A') . "</td>
                    </tr>
                    " . ($record->remarks ? "
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Work Done</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$record->remarks}</td>
                    </tr>" : "") . "
                    <tr style='background: #dcfce7;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Next Service Due</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #16a34a; font-weight: 600;'>{$nextDate}</td>
                    </tr>
                </table>
                
                <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>
                    Thank you for choosing our services. We look forward to serving you again.
                </p>
            </div>
            
            <div style='background: #1e293b; padding: 15px; border-radius: 0 0 8px 8px; text-align: center;'>
                <p style='color: #94a3b8; margin: 0; font-size: 12px;'>Thank you for your business - {company_name}</p>
            </div>
        </div>";
    }

    /**
     * Get invoice created email body
     */
    protected function getInvoiceCreatedEmailBody($service, $record, $invoice): string
    {
        $invoiceDate = Carbon::parse($invoice->date)->format('d M Y');
        $dueDate = Carbon::parse($invoice->due_date)->format('d M Y');

        // Get invoice items
        $items = DB::table('invoice_items')->where('invoice_id', $invoice->id)->get();
        
        $itemsHtml = '';
        foreach ($items as $item) {
            $itemTotal = $item->quantity * $item->rate;
            $itemsHtml .= "
            <tr style='background: #fff;'>
                <td style='padding: 10px; border: 1px solid #e2e8f0;'>{$item->description}</td>
                <td style='padding: 10px; border: 1px solid #e2e8f0; text-align: center;'>{$item->quantity}</td>
                <td style='padding: 10px; border: 1px solid #e2e8f0; text-align: right;'>₹" . number_format($item->rate, 2) . "</td>
                <td style='padding: 10px; border: 1px solid #e2e8f0; text-align: right;'>₹" . number_format($itemTotal, 2) . "</td>
            </tr>";
        }

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); padding: 20px; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #fff; margin: 0;'>Invoice Generated</h2>
                <p style='color: rgba(255,255,255,0.8); margin: 5px 0 0 0;'>{$invoice->number}</p>
            </div>
            
            <div style='background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0;'>
                <p style='color: #1e293b;'>Dear <strong>{$service->client->name}</strong>,</p>
                
                <p style='color: #475569;'>An invoice has been generated for the recent service completed on your equipment.</p>
                
                <div style='background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin: 20px 0;'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='color: #64748b;'>Invoice Number:</td>
                            <td style='text-align: right; font-weight: 600; color: #1e293b;'>{$invoice->number}</td>
                        </tr>
                        <tr>
                            <td style='color: #64748b;'>Invoice Date:</td>
                            <td style='text-align: right; color: #1e293b;'>{$invoiceDate}</td>
                        </tr>
                        <tr>
                            <td style='color: #64748b;'>Due Date:</td>
                            <td style='text-align: right; color: #dc2626; font-weight: 600;'>{$dueDate}</td>
                        </tr>
                        <tr>
                            <td style='color: #64748b;'>Service Reference:</td>
                            <td style='text-align: right; color: #1e293b;'>" . ($record->service_reference ?? $record->reference_no) . "</td>
                        </tr>
                    </table>
                </div>
                
                <h4 style='color: #1e293b; margin-bottom: 10px;'>Invoice Items</h4>
                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background: #f1f5f9;'>
                            <th style='padding: 10px; border: 1px solid #e2e8f0; text-align: left;'>Description</th>
                            <th style='padding: 10px; border: 1px solid #e2e8f0; text-align: center;'>Qty</th>
                            <th style='padding: 10px; border: 1px solid #e2e8f0; text-align: right;'>Rate</th>
                            <th style='padding: 10px; border: 1px solid #e2e8f0; text-align: right;'>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$itemsHtml}
                    </tbody>
                    <tfoot>
                        <tr style='background: #1e293b;'>
                            <td colspan='3' style='padding: 12px; color: #fff; font-weight: 600; text-align: right;'>Total Amount</td>
                            <td style='padding: 12px; color: #fff; font-weight: 600; text-align: right;'>₹" . number_format($invoice->total, 2) . "</td>
                        </tr>
                    </tfoot>
                </table>
                
                <div style='background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #f59e0b;'>
                    <p style='color: #92400e; margin: 0; font-size: 14px;'>
                        <strong>Payment Due:</strong> Please make payment by {$dueDate} to avoid any late fees.
                    </p>
                </div>
                
                <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>
                    For any queries regarding this invoice, please contact our accounts department.
                </p>
            </div>
            
            <div style='background: #1e293b; padding: 15px; border-radius: 0 0 8px 8px; text-align: center;'>
                <p style='color: #94a3b8; margin: 0; font-size: 12px;'>Thank you for your business - {company_name}</p>
            </div>
        </div>";
    }

    // ==================== PRODUCTS API ====================

    public function getProducts(Request $request)
    {
        if (!class_exists(\App\Models\Product::class)) {
            return response()->json([]);
        }
        
        $search = $request->input('search');
        $query = Product::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }
        
        return response()->json($query->limit(50)->get(['id', 'name', 'sku', 'purchase_price']));
    }

    // ==================== EXPORT/IMPORT ====================

    protected function exportServices(Request $request)
    {
        $format = strtolower($request->get('export', 'csv'));
        $query = Service::query()->with(['client']);
        $this->applyFilters($query, $request);

        if ($request->filled('ids')) {
            $query->whereIn('id', array_filter(explode(',', $request->ids)));
        }

        $data = $query->get();
        $filename = 'services_' . date('Y-m-d_His');

        switch ($format) {
            case 'xlsx': return $this->exportToExcel($data, $filename, 'Services Export');
            case 'pdf': return $this->exportToPdf($data, $filename, 'Services Export');
            default: return $this->exportToCsv($data, $filename);
        }
    }

    protected function getExportData($data)
    {
        $headers = ['ID', 'Client', 'Machine Name', 'Equipment No', 'Model No', 'Serial Number', 'Frequency', 'First Service', 'Last Service', 'Next Service', 'Status'];
        $rows = $data->map(fn($item) => [
            $item->id,
            $item->client->company ?? $item->client->name ?? 'Unknown',
            $item->machine_name,
            $item->equipment_no,
            $item->model_no,
            $item->serial_number,
            $item->frequency_label,
            $item->first_service_date?->format('Y-m-d'),
            $item->last_service_date?->format('Y-m-d'),
            $item->next_service_date?->format('Y-m-d'),
            ucfirst($item->status),
        ])->toArray();
        return [$headers, $rows];
    }

    protected function exportToCsv($data, $filename)
    {
        [$headers, $rows] = $this->getExportData($data);
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);
            foreach ($rows as $row) fputcsv($file, $row);
            fclose($file);
        };
        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ]);
    }

    protected function exportToExcel($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', $title);

        foreach ($headers as $i => $h) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . '3', $h);
        }

        $row = 4;
        foreach ($rows as $r) {
            foreach ($r as $i => $v) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . $row, $v);
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $temp = tempnam(sys_get_temp_dir(), 'exp_');
        $writer->save($temp);
        return response()->download($temp, $filename . '.xlsx')->deleteFileAfterSend(true);
    }

    protected function exportToPdf($data, $filename, $title)
    {
        [$headers, $rows] = $this->getExportData($data);
        $html = "<h2>{$title}</h2><table border='1' cellpadding='4'><tr>";
        foreach ($headers as $h) $html .= "<th>{$h}</th>";
        $html .= "</tr>";
        foreach ($rows as $r) {
            $html .= "<tr>";
            foreach ($r as $v) $html .= "<td>" . htmlspecialchars($v ?? '') . "</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";
        return Pdf::loadHTML($html)->setPaper('a4', 'landscape')->download($filename . '.pdf');
    }

    protected function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['Client ID', 'Machine Name', 'Equipment No', 'Model No', 'Serial Number', 'Frequency', 'First Service Date', 'Status', 'Notes'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . '1', $h);
        }
        $writer = new Xlsx($spreadsheet);
        $temp = tempnam(sys_get_temp_dir(), 'tpl_');
        $writer->save($temp);
        return response()->download($temp, 'services_template.xlsx')->deleteFileAfterSend(true);
    }

    protected function importServices(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);
        try {
            $rows = IOFactory::load($request->file('file')->getPathname())->getActiveSheet()->toArray();
            array_shift($rows);
            $imported = 0;
            $user = $this->admin();

            foreach ($rows as $row) {
                if (empty(array_filter($row)) || empty($row[0]) || empty($row[1])) continue;
                Service::create([
                    'client_id' => $row[0],
                    'machine_name' => $row[1],
                    'equipment_no' => $row[2] ?? null,
                    'model_no' => $row[3] ?? null,
                    'serial_number' => $row[4] ?? null,
                    'service_frequency' => $row[5] ?? 'monthly',
                    'first_service_date' => $row[6] ?? now(),
                    'next_service_date' => $row[6] ?? now(),
                    'status' => $row[7] ?? 'active',
                    'notes' => $row[8] ?? null,
                    'created_by' => $user->id,
                ]);
                $imported++;
            }
            return response()->json(['success' => true, 'imported' => $imported, 'message' => "{$imported} services imported"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}