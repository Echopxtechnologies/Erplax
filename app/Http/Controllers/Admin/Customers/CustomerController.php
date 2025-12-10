<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends AdminController
{


    
    public function index()
    {
        return view('admin.customers.index');
    }

    public function data(Request $request)
    {
        $customers = Customer::query();

        return response()->json([
            'data' => $customers->get()->map(function ($c) {
                return [
                    'id'      => $c->id,
                    'name'    => $c->name,
                    'email'   => $c->email,
                    'phone'   => $c->phone,
                    'company' => $c->company,
                ];
            }),
        ]);
    }

    public function create()
    {
        return view('admin.customers.create');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'nullable|string|max:20',
        'customer_type' => 'required|in:individual,company',
        'company' => 'nullable|string|max:255|unique:customers,company',  // ← ADD unique
        'designation' => 'nullable|string|max:255',
        // ... other fields
    ]);
    
    Customer::create($validated);
    
    return redirect()->route('admin.customers.index')
        ->with('success', 'Customer created successfully.');
}

    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        $customer->update($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

 public function destroy(Customer $customer)
{
    $customer->delete();
    
    return redirect()->route('admin.customers.index')
        ->with('success', 'Customer deleted successfully.');
}
public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);
    
    if (empty($ids)) {
        return response()->json(['success' => false, 'message' => 'No items selected'], 400);
    }

    try {
        Customer::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' customers deleted']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
    }
}




    public function addGroup(Request $request)
    {
        try {
            $request->validate([
                'group_name' => 'required|string|max:255',
            ]);

            $groupName = trim($request->group_name);

            return response()->json([
                'success' => true,
                'message' => 'Group added successfully.',
                'group_name' => $groupName,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search companies for autocomplete
     */
    public function searchCompany(Request $request)
    {
        try {
            $search = $request->get('q', '');
            
            if (strlen($search) < 1) {
                return response()->json([]);
            }

            // Use Customer model directly
            $companies = Customer::where('company', 'LIKE', '%' . $search . '%')
                ->whereNotNull('company')
                ->where('company', '!=', '')
                ->orderBy('company')
                ->limit(10)
                ->get([
                    'id',
                    'name',
                    'company',
                    'email',
                    'phone',
                    'designation',
                    'gst_number',
                    'website',
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
                ]);

            return response()->json($companies);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }























}
