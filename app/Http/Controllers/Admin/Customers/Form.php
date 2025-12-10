<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Form extends Controller
{
    // Validation rules configuration
    protected $validationRules = [
        // Basic Information
        'name' => [
            'rule' => 'required|string|min:2|max:100',
            'message' => 'Name is required (2-100 characters)'
        ],
        'email' => [
            'rule' => 'required|email|max:150',
            'message' => 'Valid email is required (max 150 characters)'
        ],
        'phone' => [
            'rule' => 'required|string|min:10|max:20',
            'message' => 'Phone must be 10-20 characters'
        ],
        'customer_type' => [
            'rule' => 'required|in:individual,company',
            'message' => 'Customer type must be individual or company'
        ],
        'group_name' => [
            'rule' => 'nullable|string|max:50',
            'message' => 'Group name max 50 characters'
        ],

        // Company Details
        'company' => [
            'rule' => 'nullable|string|max:150',
            'message' => 'Company name max 150 characters'
        ],
        'designation' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'Designation max 100 characters'
        ],
        'website' => [
            'rule' => 'nullable|url|max:200',
            'message' => 'Website must be a valid URL (max 200 characters)'
        ],
        'gst_number' => [
            'rule' => 'nullable|string|max:20',
            'message' => 'GST number max 20 characters'
        ],

        // Billing Address
        'address' => [
            'rule' => 'nullable|string|max:500',
            'message' => 'Address max 500 characters'
        ],
        'city' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'City max 100 characters'
        ],
        'state' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'State max 100 characters'
        ],
        'zip_code' => [
            'rule' => 'nullable|string|max:20',
            'message' => 'ZIP code max 20 characters'
        ],
        'country' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'Country max 100 characters'
        ],

        // Shipping Address
        'shipping_address' => [
            'rule' => 'nullable|string|max:500',
            'message' => 'Shipping address max 500 characters'
        ],
        'shipping_city' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'Shipping city max 100 characters'
        ],
        'shipping_state' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'Shipping state max 100 characters'
        ],
        'shipping_zip_code' => [
            'rule' => 'nullable|string|max:20',
            'message' => 'Shipping ZIP max 20 characters'
        ],
        'shipping_country' => [
            'rule' => 'nullable|string|max:100',
            'message' => 'Shipping country max 100 characters'
        ],

        // Notes
        'notes' => [
            'rule' => 'nullable|string|max:2000',
            'message' => 'Notes max 2000 characters'
        ],
    ];

    /**
     * Get validation rules array
     */
    protected function getRules($customerId = null)
    {
        $rules = [];
        foreach ($this->validationRules as $field => $config) {
            $rules[$field] = $config['rule'];
        }

        // Add unique rules with exception for updates
        if ($customerId) {
            $rules['email'] .= ',unique:customers,email,' . $customerId;
            $rules['company'] = 'nullable|string|max:150|unique:customers,company,' . $customerId;
        } else {
            $rules['email'] .= '|unique:customers,email';
            $rules['company'] = 'nullable|string|max:150|unique:customers,company';
        }

        return $rules;
    }

    /**
     * Get custom error messages
     */
    protected function getMessages()
    {
        $messages = [];
        foreach ($this->validationRules as $field => $config) {
            $messages[$field . '.*'] = $config['message'];
        }

        // Add specific messages
        $messages['email.unique'] = 'This email is already registered.';
        $messages['email.email'] = 'Please enter a valid email address.';
        $messages['company.unique'] = 'This company name already exists.';
        $messages['website.url'] = 'Please enter a valid URL (e.g., https://example.com).';
        $messages['phone.min'] = 'Phone number must be at least 10 digits.';
        $messages['name.min'] = 'Name must be at least 2 characters.';

        return $messages;
    }

    /**
     * Show create form
     */
    public function create()
    {
        $customer = new Customer();
        $groups = Customer::whereNotNull('group_name')
            ->distinct()
            ->pluck('group_name')
            ->filter()
            ->values();

        return view('admin.customers.create', compact('customer', 'groups'));
    }

    /**
     * Store new customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->getRules(), $this->getMessages());

        // Clean up data
        $validated = $this->cleanData($validated);

        // Additional validation for company type
        if ($request->customer_type === 'company' && empty($validated['company'])) {
            return back()->withInput()->withErrors(['company' => 'Company name is required for company type customers.']);
        }

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show customer details
     */
    public function show(Customer $customer)
    {
        $companyMembers = collect();
        
        if ($customer->customer_type === 'company' && $customer->company) {
            $companyMembers = Customer::where('company', $customer->company)
                ->where('id', '!=', $customer->id)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'designation', 'phone']);
        }

        return view('admin.customers.show', compact('customer', 'companyMembers'));
    }

    /**
     * Show edit form
     */
    public function edit(Customer $customer)
    {
        $groups = Customer::whereNotNull('group_name')
            ->distinct()
            ->pluck('group_name')
            ->filter()
            ->values();

        return view('admin.customers.edit', compact('customer', 'groups'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate($this->getRules($customer->id), $this->getMessages());

        // Clean up data
        $validated = $this->cleanData($validated);

        // Additional validation for company type
        if ($request->customer_type === 'company' && empty($validated['company'])) {
            return back()->withInput()->withErrors(['company' => 'Company name is required for company type customers.']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Delete customer
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Clean and sanitize input data
     */
    protected function cleanData(array $data)
    {
        // Trim all string values
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
                // Convert empty strings to null
                if ($data[$key] === '') {
                    $data[$key] = null;
                }
            }
        }

        // Format phone number (remove spaces, dashes)
        if (!empty($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);
        }

        // Format website URL
        if (!empty($data['website'])) {
            if (!preg_match('/^https?:\/\//', $data['website'])) {
                $data['website'] = 'https://' . $data['website'];
            }
        }

        // Uppercase GST number
        if (!empty($data['gst_number'])) {
            $data['gst_number'] = strtoupper($data['gst_number']);
        }

        return $data;
    }
}