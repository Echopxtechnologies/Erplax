<?php

namespace App\Livewire\Admin\Customers;

use App\Livewire\Admin\AdminComponent;
use App\Models\Customer;

class Show extends AdminComponent
{
    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        return view('admin.customers.show', [
            'customer' => $this->customer,
        ])->layout('components.layouts.app');
    }
}
