<?php

namespace App\Traits;

use Livewire\Attributes\Url;
use Livewire\WithPagination;

trait BaseDataTable
{
    use WithPagination;

    #[Url]
    public $page = 1;
    public $perPage = 25;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'id';
    #[Url]
    public $sortDirection = 'desc';

    public $statusFilter = '';

    public function initializeBaseDataTable()
    {
        $this->paginationView = 'pagination::tailwind';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function getSortIcon($field)
    {
        return $this->sortBy === $field 
            ? ($this->sortDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down')
            : 'fa-arrows-up-down';
    }

    public function isSorted($field)
    {
        return $this->sortBy === $field;
    }
}