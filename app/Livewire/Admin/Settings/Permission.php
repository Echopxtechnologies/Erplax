<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission as PermissionModel;

class Permission extends Component
{
    use WithPagination;

    public $name = '';
    public $permissionId = null;

    // UI state
    public $isEditing = false;
    public $showModal = false;
    public $search = '';
    public $confirmingDelete = null;

    // Validation rules
    protected function rules()
    {
        $uniqueRule = $this->permissionId 
            ? 'unique:permissions,name,' . $this->permissionId 
            : 'unique:permissions,name';

        return [
            'name' => ['required', 'min:3', $uniqueRule],
        ];
    }

    protected $messages = [
        'name.required' => 'Permission name is required.',
        'name.min' => 'Permission name must be at least 3 characters.',
        'name.unique' => 'This permission already exists.',
    ];

    // Reset pagination when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Open modal for creating
    public function openCreateModal()
    {
        $this->reset(['name', 'permissionId', 'isEditing']);
        $this->resetValidation();
        $this->showModal = true;
    }

    // Open modal for editing
    public function openEditModal($id)
    {
        $permission = PermissionModel::findOrFail($id);
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
        $this->isEditing = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    // Close modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'permissionId', 'isEditing']);
        $this->resetValidation();
    }

    // Create permission
    public function store()
    {
        $this->validate();
        PermissionModel::create(['name' => $this->name]);
        session()->flash('success', 'Permission created successfully.');
        $this->closeModal();
    }

    // Update permission
    public function update()
    {
        $this->validate();
        $permission = PermissionModel::findOrFail($this->permissionId);
        $permission->update(['name' => $this->name]);
        session()->flash('success', 'Permission updated successfully.');
        $this->closeModal();
    }

    // Save (handles both create and update)
    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            $this->store();
        }
    }

    // Delete permission
    public function delete($id)
    {
        $permission = PermissionModel::findOrFail($id);
        $permission->delete();
        session()->flash('success', 'Permission deleted successfully.');
        $this->confirmingDelete = null;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
    }

    public function render()
    {
        $permissions = PermissionModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('admin.settings.permission', [
            'permissions' => $permissions,
        ])->layout('components.layouts.app');
    }
}