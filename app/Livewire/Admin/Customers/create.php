<?php

namespace App\Livewire\Admin\Customers;

use App\Livewire\Admin\AdminComponent;
use App\Models\Customer;
use Illuminate\Validation\Rule;

class Create extends AdminComponent
{
    public string $name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $company = null;
    public ?string $gst_number = null;
    public ?string $address_line1 = null;
    public ?string $address_line2 = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $country = null;
    public ?string $pincode = null;
    public bool $active = true;

    protected function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone'         => ['nullable', 'string', 'max:20'],
            'company'       => ['nullable', 'string', 'max:255'],
            'gst_number'    => ['nullable', 'string', 'max:50'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:120'],
            'state'         => ['nullable', 'string', 'max:120'],
            'country'       => ['nullable', 'string', 'max:120'],
            'pincode'       => ['nullable', 'string', 'max:10'],
            'active'        => ['boolean'],
        ];
    }

    public function save()
    {
        $data = $this->validate();
        $data['active'] = (bool) $this->active;

        $customer = Customer::create($data);

        $this->logAction('customer_created', ['customer_id' => $customer->id]);
        $this->toastSuccess('Customer created successfully');

        return $this->redirect(route('admin.customers.show', $customer));
    }

    public function render()
    {
        return view('admin.customers.create')->layout('components.layouts.app');
    }
}
