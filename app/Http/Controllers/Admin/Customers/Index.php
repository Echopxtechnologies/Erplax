<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\DataTable; 

class Index extends AdminController
{
    use DataTable;

    protected $model;
    protected $searchable = ['name', 'email', 'phone', 'company'];
    protected $sortable   = ['id', 'name', 'email', 'phone', 'company', 'customer_type'];
    protected $exportable = [
        'id', 
        'name', 
        'email', 
        'phone', 
        'customer_type',
        'company', 
        'designation', 
        'website', 
        'gst_number',
        'group_name',
        'address', 
        'city', 
        'state', 
        'zip_code', 
        'country',
        'shipping_address', 
        'shipping_city', 
        'shipping_state', 
        'shipping_zip_code', 
        'shipping_country',
        'notes',
        'created_at',
        'updated_at'
    ];

    // Template columns for import (without id, created_at, updated_at)
    protected $templateColumns = [
        'name', 
        'email', 
        'phone', 
        'customer_type',
        'company', 
        'designation', 
        'website', 
        'gst_number',
        'group_name',
        'address', 
        'city', 
        'state', 
        'zip_code', 
        'country',
        'shipping_address', 
        'shipping_city', 
        'shipping_state', 
        'shipping_zip_code', 
        'shipping_country',
        'notes'
    ];

    public function index()
    {
        return view('admin.customers.index');
    }

    public function template()
    {
        $filename = 'customers_import_template.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, $this->templateColumns);
            
            // Sample data rows
            $sampleData = [
                [
                    'John Doe',
                    'john.doe@example.com',
                    '9876543210',
                    'individual',
                    '',
                    '',
                    '',
                    '',
                    'VIP',
                    '123 Main Street',
                    'Mumbai',
                    'Maharashtra',
                    '400001',
                    'India',
                    '123 Main Street',
                    'Mumbai',
                    'Maharashtra',
                    '400001',
                    'India',
                    'Sample individual customer'
                ],
                [
                    'Jane Smith',
                    'jane.smith@techcorp.com',
                    '9876543211',
                    'company',
                    'TechCorp Solutions',
                    'Manager',
                    'https://techcorp.com',
                    '29ABCDE1234F1Z5',
                    'Corporate',
                    '456 Business Park',
                    'Bangalore',
                    'Karnataka',
                    '560001',
                    'India',
                    '789 Warehouse Road',
                    'Bangalore',
                    'Karnataka',
                    '560002',
                    'India',
                    'Sample company customer'
                ],
            ];
            
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function data(Request $request)
    {
        $query = Customer::query();

        // EXPORT (all or selected)
        if ($request->has('export')) {
            if ($request->filled('ids')) {
                $ids = $request->get('ids');

                if (!is_array($ids)) {
                    $ids = explode(',', $ids);
                }

                $ids = array_filter($ids);
                if (!empty($ids)) {
                    $query->whereIn('id', $ids);
                }
            }

            return $this->dtExport($query, $request->get('export'));
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');

        if (! in_array($sortCol, ['id', 'name', 'email', 'phone', 'company', 'customer_type'])) {
            $sortCol = 'id';
        }
        if (! in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortCol, $sortDir);

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $data = $query->paginate($perPage);

        // Calculate starting serial number for current page
        $startSno = ($data->currentPage() - 1) * $perPage;

        $items = $data->getCollection()->map(function (Customer $customer, $index) use ($startSno) {
            // Customer type badge with CSS class for dark mode support
            $typeLabel = $customer->customer_type === 'company' 
                ? '<span class="badge-type badge-company">Company</span>'
                : '<span class="badge-type badge-individual">Individual</span>';

            return [
                'id'                  => $customer->id,
                'sno'                 => $startSno + $index + 1,
                'name'                => $customer->name ?? '-',
                'email'               => $customer->email ?? '-',
                'phone'               => $customer->phone ?? '-',
                'company'             => $customer->company ?? '-',
                'customer_type'       => $typeLabel,

                '_show_url'   => route('admin.customers.show', $customer->id),
                '_edit_url'   => route('admin.customers.edit', $customer->id),
                '_delete_url' => route('admin.customers.destroy', $customer->id),
            ];
        })->values();

        return response()->json([
            'data'         => $items,
            'total'        => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page'    => $data->lastPage(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            $data = [];
            
            if ($extension === 'csv') {
                // Parse CSV
                $handle = fopen($file->getPathname(), 'r');
                $headers = fgetcsv($handle);
                $headers = array_map('trim', $headers);
                $headers = array_map('strtolower', $headers);
                
                while (($row = fgetcsv($handle)) !== false) {
                    $data[] = array_combine($headers, $row);
                }
                fclose($handle);
            } else {
                // Parse Excel using PhpSpreadsheet
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
                $spreadsheet = $reader->load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                if (count($rows) > 0) {
                    $headers = array_map('trim', $rows[0]);
                    $headers = array_map('strtolower', $headers);
                    
                    for ($i = 1; $i < count($rows); $i++) {
                        $row = $rows[$i];
                        if (array_filter($row)) { // Skip empty rows
                            $data[] = array_combine($headers, $row);
                        }
                    }
                }
            }

            // Column mapping (file column => database column)
            $columnMap = [
                'name' => 'name',
                'email' => 'email',
                'phone' => 'phone',
                'customer_type' => 'customer_type',
                'company' => 'company',
                'designation' => 'designation',
                'website' => 'website',
                'gst_number' => 'gst_number',
                'gst number' => 'gst_number',
                'group_name' => 'group_name',
                'group' => 'group_name',
                'address' => 'address',
                'city' => 'city',
                'state' => 'state',
                'zip_code' => 'zip_code',
                'zip' => 'zip_code',
                'zipcode' => 'zip_code',
                'country' => 'country',
                'shipping_address' => 'shipping_address',
                'shipping address' => 'shipping_address',
                'shipping_city' => 'shipping_city',
                'shipping city' => 'shipping_city',
                'shipping_state' => 'shipping_state',
                'shipping state' => 'shipping_state',
                'shipping_zip_code' => 'shipping_zip_code',
                'shipping zip' => 'shipping_zip_code',
                'shipping_country' => 'shipping_country',
                'shipping country' => 'shipping_country',
                'notes' => 'notes',
            ];

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                $rowNum = $index + 2; // Account for header row
                
                // Map columns
                $customerData = [];
                foreach ($row as $key => $value) {
                    $key = strtolower(trim($key));
                    if (isset($columnMap[$key])) {
                        $customerData[$columnMap[$key]] = trim($value);
                    }
                }

                // Skip if no name or email
                if (empty($customerData['name']) || empty($customerData['email'])) {
                    $skipped++;
                    continue;
                }

                // Set default customer_type if not provided
                if (empty($customerData['customer_type'])) {
                    $customerData['customer_type'] = 'individual';
                }

                // Check if email already exists
                $existing = Customer::where('email', $customerData['email'])->first();
                
                if ($existing) {
                    // Update existing record
                    $existing->update($customerData);
                } else {
                    // Create new record
                    Customer::create($customerData);
                }
                
                $imported++;
            }

            $message = "{$imported} records imported successfully.";
            if ($skipped > 0) {
                $message .= " {$skipped} rows skipped (missing name or email).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}