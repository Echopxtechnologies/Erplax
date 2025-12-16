<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Purchase\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class VendorController extends AdminController
{
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

    public function dataTable(Request $request): JsonResponse
    {
        $query = Vendor::query();

        // Export selected IDs
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) $query->whereIn('id', $ids);
            return $this->export($query, $request->input('export'));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('vendor_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('gst_number', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Export all
        if ($request->has('export')) {
            return $this->export($query, $request->input('export'));
        }

        // Pagination
        $data = $query->paginate($request->input('per_page', 15));

        // Map data with URLs for dt-table
        $items = collect($data->items())->map(function($item) {
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
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    protected function export($query, $format = 'csv')
    {
        $data = $query->get();
        $filename = 'vendors_' . date('Y-m-d') . '.' . $format;
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'Code', 'Name', 'Contact Person', 'Phone', 'Email', 'GST Number', 'City', 'Status']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id, 
                    $row->vendor_code, 
                    $row->name, 
                    $row->contact_person, 
                    $row->phone, 
                    $row->email, 
                    $row->gst_number, 
                    $row->billing_city, 
                    $row->status
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function bulkDelete(Request $request): JsonResponse
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

        $validated['created_by'] = auth()->id();
        Vendor::create($validated);

        return redirect()->route('admin.purchase.vendors.index')->with('success', 'Vendor created successfully!');
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return $this->moduleView('purchase::vendor.show', compact('vendor'));
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return $this->moduleView('purchase::vendor.edit', compact('vendor'));
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

        $vendor->update($validated);

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
