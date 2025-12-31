<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class Form extends AdminController
{
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
    | CREATE
    |--------------------------------------------------------------------------
    */
    
public function create()
{
    $this->authorize('customers.customers.create');
    try {
        $customerGroups = DB::table('customer_groups')->orderBy('name')->pluck('name');
        
        // Get distinct company names
        $existingCompanies = Customer::where('customer_type', 'company')
            ->whereNotNull('company')
            ->distinct()
            ->pluck('company')
            ->toArray();
        
        $customerTypes = [
            'individual' => 'Individual',
            'company' => 'Company'
        ];
        
        $directions = [
            'ltr' => 'Left to Right',
            'rtl' => 'Right to Left'
        ];
        
        $countries = [
            'India' => 'India',
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Canada' => 'Canada',
            'Australia' => 'Australia',
        ];
        
        return view('admin.customers.create', compact(
            'customerGroups',
            'customerTypes',
            'directions',
            'countries',
            'existingCompanies'
        ));
        
    } catch (Exception $e) {
        report($e);
        return back()->with('error', 'Failed to load form: ' . $e->getMessage());
    }
}

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    
    public function store(Request $request)
    {
        $this->authorize('customers.customers.create');
        $rules = [
            'customer_type' => 'required|in:individual,company',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'email' => 'required|email|max:100|unique:customers,email',
            'phone' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'group_name' => 'nullable|string|max:100',
            'active' => 'nullable|boolean',
            'company' => Rule::requiredIf($request->customer_type === 'company'),
            'vat' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:150',
        ];
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $data = [
                'name' => trim($request->firstname . ' ' . $request->lastname),
                'customer_type' => $request->customer_type,
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
                'group_name' => $request->group_name,
                //'active' => $request->has('active') ? 1 : ($request->active ?? 1),
                'active' => (int) $request->input('active', 1),
                'added_by' => Auth::id() ?? 0,
            ];
            
            if ($request->customer_type === 'company') {
                $data['company'] = $request->company;
                $data['vat'] = $request->vat;
                $data['website'] = $request->website;
            }
            
            $data['billing_street'] = $request->billing_street;
            $data['billing_city'] = $request->billing_city;
            $data['billing_state'] = $request->billing_state;
            $data['billing_zip_code'] = $request->billing_zip_code;
            $data['billing_country'] = $request->billing_country;
            
            $data['shipping_address'] = $request->shipping_address;
            $data['shipping_city'] = $request->shipping_city;
            $data['shipping_state'] = $request->shipping_state;
            $data['shipping_zip_code'] = $request->shipping_zip_code;
            $data['shipping_country'] = $request->shipping_country;
            
            $data['invoice_emails'] = $request->has('invoice_emails');
            $data['estimate_emails'] = $request->has('estimate_emails');
            $data['credit_note_emails'] = $request->has('credit_note_emails');
            $data['contract_emails'] = $request->has('contract_emails');
            $data['task_emails'] = $request->has('task_emails');
            $data['project_emails'] = $request->has('project_emails');
            $data['ticket_emails'] = $request->has('ticket_emails');
            
            $customer = Customer::create($data);
            
            DB::commit();
            
            return redirect()
                ->route('admin.customers.show', $customer->id)
                ->with('success', 'Customer created successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    
    public function show($id)
    {
        $this->authorize('customers.customers.show');
        try {
            $customer = $this->getCustomer($id);
            
            if (!$customer) {
                return redirect()
                    ->route('admin.customers.index')
                    ->with('error', 'Customer not found');
            }
            
            // If company, get all contacts
            $contacts = null;
            if ($customer->customer_type === 'company') {
                $contacts = Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
            
            return view('admin.customers.show', compact('customer', 'contacts'));
            
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to load customer: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    
    public function edit($id)
    {
        $this->authorize('customers.customers.edit');
        try {
            $customer = $this->getCustomer($id);
            
            if (!$customer) {
                return back()->with('error', 'Customer not found');
            }
            
            $customerGroups = DB::table('customer_groups')->orderBy('name')->pluck('name');
            $customerTypes = ['individual' => 'Individual', 'company' => 'Company'];
            $directions = ['ltr' => 'Left to Right', 'rtl' => 'Right to Left'];
            $countries = [
                'India' => 'India',
                'United States' => 'United States',
                'United Kingdom' => 'United Kingdom',
                'Canada' => 'Canada',
                'Australia' => 'Australia',
            ];
            
            $contactCount = null;
            if ($customer->customer_type === 'company') {
                $contactCount = Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->count();
            }
            
            $names = explode(' ', $customer->name, 2);
            $firstname = $names[0] ?? '';
            $lastname = $names[1] ?? '';
            
            return view('admin.customers.edit', compact(
                'customer',
                'customerGroups',
                'customerTypes',
                'directions',
                'countries',
                'firstname',
                'lastname',
                'contactCount'
            ));
            
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    
    public function update(Request $request, $id)
    {
        $this->authorize('customers.customers.edit');
        $customer = $this->getCustomer($id);
        
        if (!$customer) {
            return back()->with('error', 'Customer not found');
        }
        
        $rules = [
            'customer_type' => 'required|in:individual,company',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'email' => ['required', 'email', 'max:100', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'group_name' => 'nullable|string|max:100',
            'active' => 'nullable|boolean',
            'company' => Rule::requiredIf($request->customer_type === 'company'),
            'vat' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:150',
        ];
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            if ($customer->customer_type === 'company') {
                // Update ALL rows with same company
                $companyData = [
                    'company' => $request->company,
                    'vat' => $request->vat,
                    'website' => $request->website,
                    'group_name' => $request->group_name,
                   // 'active' => $request->has('active') ? 1 : ($request->active ?? $customer->active),
                   'active' => (int) $request->input('active', $customer->active),
                    'billing_street' => $request->billing_street,
                    'billing_city' => $request->billing_city,
                    'billing_state' => $request->billing_state,
                    'billing_zip_code' => $request->billing_zip_code,
                    'billing_country' => $request->billing_country,
                    'shipping_address' => $request->shipping_address,
                    'shipping_city' => $request->shipping_city,
                    'shipping_state' => $request->shipping_state,
                    'shipping_zip_code' => $request->shipping_zip_code,
                    'shipping_country' => $request->shipping_country,
                ];
                
                Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->update($companyData);
                
                // Update THIS contact's personal fields
                $customer->update([
                    'name' => trim($request->firstname . ' ' . $request->lastname),
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'designation' => $request->designation,
                ]);
                
            } else {
                // Individual: update only this row
                $data = [
                    'name' => trim($request->firstname . ' ' . $request->lastname),
                    'customer_type' => $request->customer_type,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'designation' => $request->designation,
                    'group_name' => $request->group_name,
                    //'active' => $request->has('active') ? 1 : ($request->active ?? $customer->active),
                    'active' => (int) $request->input('active', $customer->active),
                    'billing_street' => $request->billing_street,
                    'billing_city' => $request->billing_city,
                    'billing_state' => $request->billing_state,
                    'billing_zip_code' => $request->billing_zip_code,
                    'billing_country' => $request->billing_country,
                    'shipping_address' => $request->shipping_address,
                    'shipping_city' => $request->shipping_city,
                    'shipping_state' => $request->shipping_state,
                    'shipping_zip_code' => $request->shipping_zip_code,
                    'shipping_country' => $request->shipping_country,
                ];
                
                $data['invoice_emails'] = $request->has('invoice_emails');
                $data['estimate_emails'] = $request->has('estimate_emails');
                $data['credit_note_emails'] = $request->has('credit_note_emails');
                $data['contract_emails'] = $request->has('contract_emails');
                $data['task_emails'] = $request->has('task_emails');
                $data['project_emails'] = $request->has('project_emails');
                $data['ticket_emails'] = $request->has('ticket_emails');
                
                $customer->update($data);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.customers.show', $customer->id)
                ->with('success', 'Customer updated successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    
   
    



//     public function destroy(Request $request, $id)
// {
//     try {
//         $customer = $this->getCustomer($id);
        
//         if (!$customer) {
//             // AJAX Request
//             if ($request->wantsJson() || $request->ajax()) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Customer not found'
//                 ], 404);
//             }
            
//             return back()->with('error', 'Customer not found');
//         }
        
//         $name = $customer->display_name;
        
//         DB::beginTransaction();
        
//         if ($customer->customer_type === 'company') {
//             // Delete ALL contacts of this company
//             Customer::where('company', $customer->company)
//                 ->where('customer_type', 'company')
//                 ->delete();
//         } else {
//             $customer->delete();
//         }
        
//         DB::commit();
        
//         // ✅ AJAX Request (DataTable delete) → Return JSON
//         if ($request->wantsJson() || $request->ajax()) {
//             return response()->json([
//                 'success' => true,
//                 'message' => "Customer '{$name}' deleted successfully"
//             ]);
//         }
        
//         // ✅ Form Submit (show page delete) → Redirect
//         return redirect()
//             ->route('admin.customers.index')
//             ->with('success', "Customer '{$name}' deleted successfully");
            
//     } catch (Exception $e) {
//         DB::rollBack();
//         report($e);
        
//         // AJAX Request
//         if ($request->wantsJson() || $request->ajax()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to delete customer: ' . $e->getMessage()
//             ], 500);
//         }
        
//         return back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
//     }
// }


public function destroy(Request $request, $id)
{
    $this->authorize('customers.customers.delete');
    try {
        $customer = $this->getCustomer($id);
        
        if (!$customer) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            return back()->with('error', 'Customer not found');
        }
        
        $name = $customer->name;
        
        DB::beginTransaction();
        
        if ($customer->customer_type === 'company' && $customer->company) {
            // Count contacts in this company
            $contactCount = Customer::where('company', $customer->company)
                ->where('customer_type', 'company')
                ->count();
            
            if ($contactCount > 1) {
                // ✅ Multiple contacts: Delete ONLY this one
                $customer->delete();
                
                DB::commit();
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Contact '{$name}' deleted successfully"
                    ]);
                }
                
                return redirect()
                    ->route('admin.customers.index')
                    ->with('success', "Contact '{$name}' deleted successfully");
                
            } else {
                // ✅ Last contact: Delete entire company (all rows with same company name)
                Customer::where('company', $customer->company)
                    ->where('customer_type', 'company')
                    ->delete();
                
                DB::commit();
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Company '{$customer->company}' and all contacts deleted successfully"
                    ]);
                }
                
                return redirect()
                    ->route('admin.customers.index')
                    ->with('success', "Company '{$customer->company}' deleted successfully");
            }
        } else {
            // ✅ Individual customer: Delete only this one
            $customer->delete();
            
            DB::commit();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Customer '{$name}' deleted successfully"
                ]);
            }
            
            return redirect()
                ->route('admin.customers.index')
                ->with('success', "Customer '{$name}' deleted successfully");
        }
        
    } catch (Exception $e) {
        DB::rollBack();
        report($e);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
    }
}
    /*
|--------------------------------------------------------------------------
| AJAX - Get Company Details
|--------------------------------------------------------------------------
*/

public function getCompanyDetails(Request $request)
{
    try {
        $companyName = $request->input('company');
        
        // Get first customer record with this company name
        $company = Customer::where('customer_type', 'company')
            ->where('company', $companyName)
            ->first();
        
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company->company,
                'vat' => $company->vat,
                'website' => $company->website,
                'group_name' => $company->group_name, 
                'billing_street' => $company->billing_street,
                'billing_city' => $company->billing_city,
                'billing_state' => $company->billing_state,
                'billing_zip_code' => $company->billing_zip_code,
                'billing_country' => $company->billing_country,
                'shipping_address' => $company->shipping_address,
                'shipping_city' => $company->shipping_city,
                'shipping_state' => $company->shipping_state,
                'shipping_zip_code' => $company->shipping_zip_code,
                'shipping_country' => $company->shipping_country,
            ]
        ]);
        
    } catch (Exception $e) {
        report($e);
        return response()->json(['error' => 'Failed to fetch company details'], 500);
    }
}
}