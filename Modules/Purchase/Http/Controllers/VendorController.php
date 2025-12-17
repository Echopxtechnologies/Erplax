<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Core\Traits\DataTableTrait;

class VendorController extends AdminController
{
    use DataTableTrait;
    
    // DataTable Configuration
    protected $model = Vendor::class;
    protected $searchable = ['name', 'vendor_code', 'contact_person', 'phone', 'gst_number', 'email'];
    protected $sortable = ['id', 'name', 'vendor_code', 'status', 'created_at'];
    protected $filterable = ['status'];
    protected $exportTitle = 'Vendors Export';

    public function index()
    {
        $stats = [
            'total' => Vendor::count(),
            'active' => Vendor::where('status', 'ACTIVE')->count(),
            'inactive' => Vendor::where('status', 'INACTIVE')->count(),
            'blocked' => Vendor::where('status', 'BLOCKED')->count(),
        ];
        
        return $this->moduleView('purchase::vendor.index', compact('stats'));
    }

    /**
     * DataTable row mapping for list view
     */
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'vendor_code' => $item->vendor_code,
            'name' => $item->name,
            'contact_person' => $item->contact_person ?? '-',
            'phone' => $item->phone ?? '-',
            'gst_number' => $item->gst_number ?? '-',
            'billing_city' => $item->billing_city ?? '-',
            'status' => $item->status,
            '_show_url' => route('admin.purchase.vendors.show', $item->id),
            '_edit_url' => route('admin.purchase.vendors.edit', $item->id),
        ];
    }

    /**
     * DataTable row mapping for export
     */
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Code' => $item->vendor_code,
            'Name' => $item->name,
            'Contact Person' => $item->contact_person ?? '',
            'Phone' => $item->phone ?? '',
            'Email' => $item->email ?? '',
            'GST Number' => $item->gst_number ?? '',
            'PAN Number' => $item->pan_number ?? '',
            'City' => $item->billing_city ?? '',
            'State' => $item->billing_state ?? '',
            'Status' => $item->status,
        ];
    }

    /**
     * DataTable endpoint
     */
    public function dataTable(Request $request)
    {
        return $this->handleData($request);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = Vendor::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$deleted} vendor(s) deleted!"]);
    }

    public function create()
    {
        $vendorCode = Vendor::generateCode();
        return $this->moduleView('purchase::vendor.create', compact('vendorCode'));
    }

    public function store(Request $request)
    {
        // Conditional GST validation
        $gstRules = 'nullable|string|max:15';
        if ($request->input('gst_type') !== 'UNREGISTERED') {
            $gstRules = 'required|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/';
        }
        
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:191',
            'vendor_code' => 'required|string|max:50|unique:vendors,vendor_code',
            'display_name' => 'nullable|string|max:191',
            'contact_person' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'gst_number' => $gstRules,
            'pan_number' => 'nullable|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'gst_type' => 'required|in:REGISTERED,UNREGISTERED,COMPOSITION,SEZ',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_pincode' => 'nullable|string|max:10',
            'billing_country' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_days' => 'nullable|integer|min:0|max:365',
            'credit_limit' => 'nullable|numeric|min:0',
            'opening_balance' => 'nullable|numeric',
            'status' => 'required|in:ACTIVE,INACTIVE,BLOCKED',
            // Bank details validation
            'bank_account_holder' => 'nullable|string|max:191',
            'bank_name' => 'nullable|string|max:191',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
            'bank_branch' => 'nullable|string|max:191',
            'bank_upi_id' => 'nullable|string|max:100',
            'bank_account_type' => 'nullable|in:SAVINGS,CURRENT,OTHER',
        ], [
            'gst_number.required' => 'GST Number is required for registered vendors.',
            'gst_number.size' => 'GST Number must be exactly 15 characters.',
            'gst_number.regex' => 'Please enter a valid GST Number (e.g., 22AAAAA0000A1Z5).',
            'pan_number.size' => 'PAN Number must be exactly 10 characters.',
            'pan_number.regex' => 'Please enter a valid PAN Number (e.g., AAAAA0000A).',
            'name.min' => 'Vendor name must be at least 2 characters.',
        ]);

        // Clear GST if unregistered
        if ($validated['gst_type'] === 'UNREGISTERED') {
            $validated['gst_number'] = null;
        }
        
        // Uppercase GST and PAN
        if (!empty($validated['gst_number'])) {
            $validated['gst_number'] = strtoupper($validated['gst_number']);
        }
        if (!empty($validated['pan_number'])) {
            $validated['pan_number'] = strtoupper($validated['pan_number']);
        }

        // Extract bank fields
        $bankData = [
            'account_holder_name' => $request->bank_account_holder,
            'bank_name' => $request->bank_name,
            'account_number' => $request->bank_account_number,
            'ifsc_code' => $request->bank_ifsc ? strtoupper($request->bank_ifsc) : null,
            'branch_name' => $request->bank_branch,
            'upi_id' => $request->bank_upi_id,
            'account_type' => $request->bank_account_type ?? 'CURRENT',
        ];
        
        // Remove bank fields from vendor data
        unset($validated['bank_account_holder'], $validated['bank_name'], $validated['bank_account_number'], 
              $validated['bank_ifsc'], $validated['bank_branch'], $validated['bank_upi_id'], $validated['bank_account_type']);

        $validated['created_by'] = auth()->id();
        $vendor = Vendor::create($validated);
        
        // Save bank details if account number provided
        if (!empty($bankData['account_number']) && \Schema::hasTable('bank_details')) {
            \DB::table('bank_details')->insert([
                'holder_type' => 'vendor',
                'holder_id' => $vendor->id,
                'account_holder_name' => $bankData['account_holder_name'] ?? $vendor->name,
                'bank_name' => $bankData['bank_name'],
                'account_number' => $bankData['account_number'],
                'ifsc_code' => $bankData['ifsc_code'],
                'branch_name' => $bankData['branch_name'],
                'upi_id' => $bankData['upi_id'],
                'account_type' => $bankData['account_type'],
                'is_primary' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.purchase.vendors.index')->with('success', 'Vendor created successfully!');
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        $bankDetail = null;
        if (\Schema::hasTable('bank_details')) {
            $bankDetail = \DB::table('bank_details')
                ->where('holder_type', 'vendor')
                ->where('holder_id', $id)
                ->where('is_primary', true)
                ->first();
        }
        return $this->moduleView('purchase::vendor.show', compact('vendor', 'bankDetail'));
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $bankDetail = null;
        if (\Schema::hasTable('bank_details')) {
            $bankDetail = \DB::table('bank_details')
                ->where('holder_type', 'vendor')
                ->where('holder_id', $id)
                ->where('is_primary', true)
                ->first();
        }
        return $this->moduleView('purchase::vendor.edit', compact('vendor', 'bankDetail'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        // Conditional GST validation
        $gstRules = 'nullable|string|max:15';
        if ($request->input('gst_type') !== 'UNREGISTERED') {
            $gstRules = 'required|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/';
        }

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:191',
            'display_name' => 'nullable|string|max:191',
            'contact_person' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'gst_number' => $gstRules,
            'pan_number' => 'nullable|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'gst_type' => 'required|in:REGISTERED,UNREGISTERED,COMPOSITION,SEZ',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_pincode' => 'nullable|string|max:10',
            'billing_country' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_days' => 'nullable|integer|min:0|max:365',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:ACTIVE,INACTIVE,BLOCKED',
            // Bank details validation
            'bank_account_holder' => 'nullable|string|max:191',
            'bank_name' => 'nullable|string|max:191',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
            'bank_branch' => 'nullable|string|max:191',
            'bank_upi_id' => 'nullable|string|max:100',
            'bank_account_type' => 'nullable|in:SAVINGS,CURRENT,OTHER',
        ], [
            'gst_number.required' => 'GST Number is required for registered vendors.',
            'gst_number.size' => 'GST Number must be exactly 15 characters.',
            'gst_number.regex' => 'Please enter a valid GST Number (e.g., 22AAAAA0000A1Z5).',
            'pan_number.size' => 'PAN Number must be exactly 10 characters.',
            'pan_number.regex' => 'Please enter a valid PAN Number (e.g., AAAAA0000A).',
        ]);

        // Clear GST if unregistered
        if ($validated['gst_type'] === 'UNREGISTERED') {
            $validated['gst_number'] = null;
        }
        
        // Uppercase GST and PAN
        if (!empty($validated['gst_number'])) {
            $validated['gst_number'] = strtoupper($validated['gst_number']);
        }
        if (!empty($validated['pan_number'])) {
            $validated['pan_number'] = strtoupper($validated['pan_number']);
        }
        
        // Extract bank fields
        $bankData = [
            'account_holder_name' => $request->bank_account_holder,
            'bank_name' => $request->bank_name,
            'account_number' => $request->bank_account_number,
            'ifsc_code' => $request->bank_ifsc ? strtoupper($request->bank_ifsc) : null,
            'branch_name' => $request->bank_branch,
            'upi_id' => $request->bank_upi_id,
            'account_type' => $request->bank_account_type ?? 'CURRENT',
        ];
        
        // Remove bank fields from vendor data
        unset($validated['bank_account_holder'], $validated['bank_name'], $validated['bank_account_number'], 
              $validated['bank_ifsc'], $validated['bank_branch'], $validated['bank_upi_id'], $validated['bank_account_type']);

        $vendor->update($validated);
        
        // Update or create bank details
        if (\Schema::hasTable('bank_details')) {
            if (!empty($bankData['account_number'])) {
                \DB::table('bank_details')->updateOrInsert(
                    ['holder_type' => 'vendor', 'holder_id' => $vendor->id, 'is_primary' => true],
                    [
                        'account_holder_name' => $bankData['account_holder_name'] ?? $vendor->name,
                        'bank_name' => $bankData['bank_name'],
                        'account_number' => $bankData['account_number'],
                        'ifsc_code' => $bankData['ifsc_code'],
                        'branch_name' => $bankData['branch_name'],
                        'upi_id' => $bankData['upi_id'],
                        'account_type' => $bankData['account_type'],
                        'is_active' => true,
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Remove bank details if account number is cleared
                \DB::table('bank_details')
                    ->where('holder_type', 'vendor')
                    ->where('holder_id', $vendor->id)
                    ->delete();
            }
        }

        return redirect()->route('admin.purchase.vendors.index')->with('success', 'Vendor updated successfully!');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Vendor deleted!']);
        }
        return redirect()->route('admin.purchase.vendors.index')->with('success', 'Vendor deleted!');
    }
}
