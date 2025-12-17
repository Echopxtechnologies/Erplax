<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class ContactsForm extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Private Properties (Getter/Setter Pattern)
    |--------------------------------------------------------------------------
    */
    
    private $contact = null;
    private $customer = null;

    /*
    |--------------------------------------------------------------------------
    | GETTER - Fetch Contact
    |--------------------------------------------------------------------------
    */
    
    private function getContact($id)
    {
        try {
            if (!$this->contact || $this->contact->id != $id) {
                $this->contact = Customer::findOrFail($id);
            }
            return $this->contact;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GETTER - Fetch Parent Customer
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
    
    public function create($customerId)
    {
        try {
            $parentCustomer = $this->getCustomer($customerId);
            
            if (!$parentCustomer || $parentCustomer->customer_type !== 'company') {
                return back()->with('error', 'Cannot add contacts to individual customers');
            }
            
            $companyName = $parentCustomer->company;
            $existingContacts = Customer::where('company', $companyName)
                ->where('customer_type', 'company')
                ->get();
            
            $directions = ['ltr' => 'Left to Right', 'rtl' => 'Right to Left'];
            $countries = [
                'India' => 'India',
                'United States' => 'United States',
                'United Kingdom' => 'United Kingdom',
                'Canada' => 'Canada',
                'Australia' => 'Australia',
            ];
            
            return view('admin.customers.contacts.create', compact(
                'parentCustomer',
                'companyName',
                'existingContacts',
                'directions',
                'countries'
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
    
    public function store(Request $request, $customerId)
    {
        $rules = [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'email' => 'required|email|max:100|unique:customers,email',
            'phone' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
        ];
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $parentCustomer = $this->getCustomer($customerId);
            
            if (!$parentCustomer || $parentCustomer->customer_type !== 'company') {
                throw new Exception('Cannot add contacts to individual customers');
            }
            
            $data = [
                'name' => trim($request->firstname . ' ' . $request->lastname),
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
                
                // DUPLICATE company data
                'customer_type' => 'company',
                'company' => $parentCustomer->company,
                'vat' => $parentCustomer->vat,
                'website' => $parentCustomer->website,
                'group_name' => $parentCustomer->group_name,
                'address' => $parentCustomer->address,
                'city' => $parentCustomer->city,
                'state' => $parentCustomer->state,
                'zip_code' => $parentCustomer->zip_code,
                'country' => $parentCustomer->country,
                'billing_street' => $parentCustomer->billing_street,
                'billing_city' => $parentCustomer->billing_city,
                'billing_state' => $parentCustomer->billing_state,
                'billing_zip_code' => $parentCustomer->billing_zip_code,
                'billing_country' => $parentCustomer->billing_country,
                'shipping_address' => $parentCustomer->shipping_address,
                'shipping_city' => $parentCustomer->shipping_city,
                'shipping_state' => $parentCustomer->shipping_state,
                'shipping_zip_code' => $parentCustomer->shipping_zip_code,
                'shipping_country' => $parentCustomer->shipping_country,
                'active' => $parentCustomer->active,
                'currency' => $parentCustomer->currency,
                'added_by' => Auth::id() ?? 0,
                
                'invoice_emails' => $request->has('invoice_emails'),
                'estimate_emails' => $request->has('estimate_emails'),
                'credit_note_emails' => $request->has('credit_note_emails'),
                'contract_emails' => $request->has('contract_emails'),
                'task_emails' => $request->has('task_emails'),
                'project_emails' => $request->has('project_emails'),
                'ticket_emails' => $request->has('ticket_emails'),
            ];
            
            $newContact = Customer::create($data);
            
            DB::commit();
            
            return redirect()
                ->route('admin.customers.show', $parentCustomer->id)
                ->with('success', 'Contact added successfully to ' . $parentCustomer->company);
                
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to add contact: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    
    public function edit($id)
    {
        try {
            $contact = $this->getContact($id);
            
            if (!$contact) {
                return back()->with('error', 'Contact not found');
            }
            
            $companyContacts = Customer::where('company', $contact->company)
                ->where('customer_type', 'company')
                ->get();
            
            $directions = ['ltr' => 'Left to Right', 'rtl' => 'Right to Left'];
            $countries = [
                'India' => 'India',
                'United States' => 'United States',
                'United Kingdom' => 'United Kingdom',
                'Canada' => 'Canada',
                'Australia' => 'Australia',
            ];
            
            $names = explode(' ', $contact->name, 2);
            $firstname = $names[0] ?? '';
            $lastname = $names[1] ?? '';
            
            return view('admin.customers.contacts.edit', compact(
                'contact',
                'companyContacts',
                'directions',
                'countries',
                'firstname',
                'lastname'
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
        $contact = $this->getContact($id);
        
        if (!$contact) {
            return back()->with('error', 'Contact not found');
        }
        
        $rules = [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'email' => ['required', 'email', 'max:100', Rule::unique('customers')->ignore($contact->id)],
            'phone' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'active' => 'nullable|boolean',
        ];
        
        $validated = $request->validate($rules);
        
        try {
            DB::beginTransaction();
            
            $data = [
                'name' => trim($request->firstname . ' ' . $request->lastname),
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
                'active' => $request->has('active') ? 1 : ($request->active ?? $contact->active),
                'invoice_emails' => $request->has('invoice_emails'),
                'estimate_emails' => $request->has('estimate_emails'),
                'credit_note_emails' => $request->has('credit_note_emails'),
                'contract_emails' => $request->has('contract_emails'),
                'task_emails' => $request->has('task_emails'),
                'project_emails' => $request->has('project_emails'),
                'ticket_emails' => $request->has('ticket_emails'),
            ];
            
            $contact->update($data);
            
            DB::commit();
            
            return redirect()
                ->route('admin.customers.show', $contact->id)
                ->with('success', 'Contact updated successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', 'Failed to update contact: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    
    public function destroy($id)
    {
        try {
            $contact = $this->getContact($id);
            
            if (!$contact) {
                return back()->with('error', 'Contact not found');
            }
            
            $name = $contact->name;
            $companyName = $contact->company;
            
            // Check if last contact
            $contactCount = Customer::where('company', $companyName)
                ->where('customer_type', 'company')
                ->count();
            
            if ($contactCount <= 1) {
                return back()->with('error', 'Cannot delete the last contact of a company. Delete the entire company instead.');
            }
            
            $redirectContact = Customer::where('company', $companyName)
                ->where('customer_type', 'company')
                ->where('id', '!=', $id)
                ->first();
            
            $contact->delete();
            
            return redirect()
                ->route('admin.customers.show', $redirectContact->id)
                ->with('success', "Contact '{$name}' deleted successfully");
                
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Failed to delete contact: ' . $e->getMessage());
        }
    }
}