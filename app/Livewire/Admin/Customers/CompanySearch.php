<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Livewire\Component;

class CompanySearch extends Component
{
    public $search = '';
    public $results = [];
    public $showDropdown = false;

    public function mount($value = '')
    {
        $this->search = $value ?? '';
    }

    public function updatedSearch()
    {
        if (strlen(trim($this->search)) < 1) {
            $this->results = [];
            $this->showDropdown = false;
            return;
        }

        $this->results = Customer::where('company', 'LIKE', '%' . $this->search . '%')
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->groupBy('company')
            ->select('company', \DB::raw('MAX(gst_number) as gst_number'), \DB::raw('MAX(website) as website'))
            ->limit(8)
            ->get()
            ->map(function($c) {
                return [
                    'company' => $c->company,
                    'gst_number' => $c->gst_number,
                    'website' => $c->website,
                ];
            })
            ->toArray();

        $this->showDropdown = count($this->results) > 0;
    }

    public function selectCompany($index)
    {
        if (isset($this->results[$index])) {
            $company = $this->results[$index];
            $this->search = $company['company'];
            $this->showDropdown = false;
            $this->results = [];
            $this->dispatch('companySelected', data: $company);
        }
    }

    public function hideDropdown()
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.admin.customers.company-search');
    }
}