<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomerGroupsForm extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Private Properties (Getter/Setter Pattern)
    |--------------------------------------------------------------------------
    */
    
    private $customerGroup = null;

    /*
    |--------------------------------------------------------------------------
    | GETTER - Fetch Single Customer Group
    |--------------------------------------------------------------------------
    */
    
    private function getCustomerGroup($id)
    {
        try {
            if (!$this->customerGroup || $this->customerGroup->id != $id) {
                $this->customerGroup = CustomerGroup::with('customers')->findOrFail($id);
            }
            return $this->customerGroup;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SETTER - Create New Customer Group
    |--------------------------------------------------------------------------
    */
    
    private function setCustomerGroup($data)
    {
        DB::beginTransaction();
        
        try {
            $customerGroup = new CustomerGroup();
            $customerGroup->name = $data['name'];
            $customerGroup->description = $data['description'] ?? null;
            $customerGroup->save();
            
            DB::commit();
            
            $this->customerGroup = $customerGroup;
            return $this->customerGroup;
            
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SETTER - Update Existing Customer Group
    |--------------------------------------------------------------------------
    */
    
    private function updateCustomerGroup($id, $data)
    {
        DB::beginTransaction();
        
        try {
            $customerGroup = $this->getCustomerGroup($id);
            
            if (!$customerGroup) {
                throw new Exception('Customer group not found');
            }
            
            $customerGroup->name = $data['name'];
            $customerGroup->description = $data['description'] ?? null;
            $customerGroup->save();
            
            DB::commit();
            
            return $customerGroup->fresh();
            
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX - List All Customer Groups
    |--------------------------------------------------------------------------
    | Route: GET /admin/customer-groups
    | Returns: View with groups list
    |--------------------------------------------------------------------------
    */
    
    // public function index()
    // {
    //     try {
    //         $customerGroups = CustomerGroup::withCount('customers')
    //                                       ->orderBy('name')
    //                                       ->get();
            
    //         return view('admin.customer-groups.index', compact('customerGroups'));
            
    //     } catch (Exception $e) {
    //         report($e);
    //         return back()->with('error', 'Failed to load customer groups: ' . $e->getMessage());
    //     }
    // }


    public function index()
{
    try {
        // FIXED: Use custom count since relationship is string-based
        $customerGroups = CustomerGroup::all()->map(function($group) {
            $group->customers_count = $group->customers()->count();
            return $group;
        })->sortBy('name');
        
        return view('admin.customers.groups.index', compact('customerGroups'));
        
    } catch (Exception $e) {
        report($e);
        return back()->with('error', 'Failed to load customer groups: ' . $e->getMessage());
    }
}

    /*
    |--------------------------------------------------------------------------
    | CREATE - Show Create Form (Modal or Page)
    |--------------------------------------------------------------------------
    | Route: GET /admin/customer-groups/create
    | Returns: View with empty form
    |--------------------------------------------------------------------------
    */
    
    public function create()
    {
        try {
            return view('admin.customers.groups.create');
            
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to load form: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE - Save New Customer Group
    |--------------------------------------------------------------------------
    | Route: POST /admin/customer-groups
    | Returns: JSON response (for AJAX) or Redirect
    |--------------------------------------------------------------------------
    */
    
    public function store(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191|unique:customer_groups,name',
                'description' => 'nullable|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                // If AJAX request, return JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors');
            }
            
            // Create customer group using setter
            $customerGroup = $this->setCustomerGroup($request->all());
            
            if ($customerGroup) {
                // If AJAX request, return JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Customer group created successfully',
                        'data' => [
                            'id' => $customerGroup->id,
                            'name' => $customerGroup->name,
                        ]
                    ]);
                }
                
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('success', 'Customer group created successfully!');
            } else {
                throw new Exception('Failed to create customer group');
            }
            
        } catch (Exception $e) {
            report($e);
            
            // If AJAX request, return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create customer group: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create customer group: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW - View Customer Group Details
    |--------------------------------------------------------------------------
    | Route: GET /admin/customer-groups/{id}
    | Returns: View with group details and customers
    |--------------------------------------------------------------------------
    */
    
    public function show($id)
    {
        try {
            $customerGroup = $this->getCustomerGroup($id);
            
            if (!$customerGroup) {
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('error', 'Customer group not found');
            }
            
            return view('admin.customer-groups.show', compact('customerGroup'));
            
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to load customer group: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT - Show Edit Form
    |--------------------------------------------------------------------------
    | Route: GET /admin/customer-groups/{id}/edit
    | Returns: View with populated form
    |--------------------------------------------------------------------------
    */
    
    public function edit($id)
    {
        try {
            $customerGroup = $this->getCustomerGroup($id);
            
            if (!$customerGroup) {
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('error', 'Customer group not found');
            }
            
            return view('admin.customers.groups.edit', compact('customerGroup'));
            
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to load customer group: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE - Update Existing Customer Group
    |--------------------------------------------------------------------------
    | Route: PUT /admin/customer-groups/{id}
    | Returns: JSON response (for AJAX) or Redirect
    |--------------------------------------------------------------------------
    */
    
    public function update(Request $request, $id)
    {
        try {
            $customerGroup = $this->getCustomerGroup($id);
            
            if (!$customerGroup) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer group not found'
                    ], 404);
                }
                
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('error', 'Customer group not found');
            }
            
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191|unique:customer_groups,name,' . $id,
                'description' => 'nullable|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors');
            }
            
            // Update customer group using setter
            $updated = $this->updateCustomerGroup($id, $request->all());
            
            if ($updated) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Customer group updated successfully',
                        'data' => [
                            'id' => $updated->id,
                            'name' => $updated->name,
                        ]
                    ]);
                }
                
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('success', 'Customer group updated successfully!');
            } else {
                throw new Exception('Failed to update customer group');
            }
            
        } catch (Exception $e) {
            report($e);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update customer group: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update customer group: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY - Delete Customer Group
    |--------------------------------------------------------------------------
    | Route: DELETE /admin/customer-groups/{id}
    | Returns: JSON response (for AJAX) or Redirect
    |--------------------------------------------------------------------------
    */
    
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $customerGroup = $this->getCustomerGroup($id);
            
            if (!$customerGroup) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer group not found'
                    ], 404);
                }
                
                return redirect()
                    ->route('admin.customer-groups.index')
                    ->with('error', 'Customer group not found');
            }
            
            // Check if group can be deleted
            if (!$customerGroup->isDeletable()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete customer group that has customers assigned. Please reassign customers first.'
                    ], 400);
                }
                
                return back()->with('warning', 'Cannot delete customer group that has customers assigned. Please reassign customers first.');
            }
            
            $groupName = $customerGroup->name;
            
            // Delete customer group
            $customerGroup->delete();
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer group "' . $groupName . '" deleted successfully'
                ]);
            }
            
            return redirect()
                ->route('admin.customer-groups.index')
                ->with('success', 'Customer group "' . $groupName . '" deleted successfully');
            
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete customer group: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete customer group: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL - Get All Groups for Dropdown (AJAX)
    |--------------------------------------------------------------------------
    | Route: GET /admin/customer-groups/all
    | Returns: JSON with all groups
    |--------------------------------------------------------------------------
    */
    
    public function getAll()
    {
        try {
            $customerGroups = CustomerGroup::orderBy('name')
                                          ->select('id', 'name')
                                          ->get();
            
            return response()->json([
                'success' => true,
                'data' => $customerGroups
            ]);
            
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load customer groups: ' . $e->getMessage()
            ], 500);
        }
    }
}