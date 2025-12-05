<?php

namespace App\Livewire\Admin\Customers;

use App\Livewire\Admin\AdminComponent;
use App\Models\Customer;
use Livewire\WithPagination;

class Index extends AdminComponent
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Customer::query();

        $query = $this->applySearch($query, $this->search, [
            'name', 'email', 'phone', 'company'
        ]);

        $customers   = $query->latest()->paginate($this->getPerPage());
        $total       = $customers->total();
        $activeCount = Customer::where('active', true)->count();

        return view('admin.customers.index', [
            'customers'   => $customers,
            'total'       => $total,
            'activeCount' => $activeCount,
        ])->layout('components.layouts.app');
    }

    public static function menu(): ?array
    {
        return [
            'title' => 'Customers',
            'icon'  => 'users',
            'route' => 'admin.customers.index',
        ];
    }
}
