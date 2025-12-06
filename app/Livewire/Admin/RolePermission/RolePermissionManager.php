<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Module;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionManager extends Component
{
    public Role $role;
    public array $selectedPermissions = [];
    public array $modulePermissions = [];
    public string $searchQuery = '';

    protected $listeners = ['refreshPermissions' => '$refresh'];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->loadModulePermissions();
    }

    /**
     * Load permissions grouped by module
     */
    public function loadModulePermissions()
    {
        $modules = Module::where('is_active', true)
            ->where('is_installed', true)
            ->orderBy('sort_order')
            ->get();

        $this->modulePermissions = [];

        foreach ($modules as $module) {
            $permissions = Permission::where('module_id', $module->id)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->groupBy(function ($permission) {
                    // Group by menu (second part of permission name)
                    $parts = explode('.', $permission->name);
                    return $parts[1] ?? 'general';
                });

            if ($permissions->count() > 0) {
                $this->modulePermissions[$module->id] = [
                    'module' => $module,
                    'permissions' => $permissions,
                ];
            }
        }

        // Add orphan permissions (no module)
        $orphanPermissions = Permission::whereNull('module_id')
            ->orderBy('name')
            ->get();

        if ($orphanPermissions->count() > 0) {
            $this->modulePermissions['orphan'] = [
                'module' => (object)[
                    'id' => 'orphan',
                    'name' => 'Other Permissions',
                    'alias' => 'other',
                ],
                'permissions' => $orphanPermissions->groupBy(function ($permission) {
                    $parts = explode('.', $permission->name);
                    return $parts[0] ?? 'general';
                }),
            ];
        }
    }

    /**
     * Toggle a single permission
     */
    public function togglePermission(string $permissionName)
    {
        if (in_array($permissionName, $this->selectedPermissions)) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permissionName]);
        } else {
            $this->selectedPermissions[] = $permissionName;
        }

        // Auto-save
        $this->savePermissions();
    }

    /**
     * Toggle all permissions for a module
     */
    public function toggleModule(int $moduleId, bool $checked)
    {
        $moduleData = $this->modulePermissions[$moduleId] ?? null;
        
        if (!$moduleData) return;

        $modulePermissionNames = [];
        foreach ($moduleData['permissions'] as $menuPermissions) {
            foreach ($menuPermissions as $permission) {
                $modulePermissionNames[] = $permission->name;
            }
        }

        if ($checked) {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $modulePermissionNames));
        } else {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $modulePermissionNames);
        }

        $this->savePermissions();
    }

    /**
     * Toggle all permissions for a menu within a module
     */
    public function toggleMenu(int $moduleId, string $menuSlug, bool $checked)
    {
        $moduleData = $this->modulePermissions[$moduleId] ?? null;
        
        if (!$moduleData || !isset($moduleData['permissions'][$menuSlug])) return;

        $menuPermissionNames = $moduleData['permissions'][$menuSlug]->pluck('name')->toArray();

        if ($checked) {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $menuPermissionNames));
        } else {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $menuPermissionNames);
        }

        $this->savePermissions();
    }

    /**
     * Select all permissions
     */
    public function selectAll()
    {
        $this->selectedPermissions = Permission::pluck('name')->toArray();
        $this->savePermissions();
    }

    /**
     * Deselect all permissions
     */
    public function deselectAll()
    {
        $this->selectedPermissions = [];
        $this->savePermissions();
    }

    /**
     * Select only read permissions
     */
    public function selectReadOnly()
    {
        $this->selectedPermissions = Permission::where('name', 'like', '%.read')
            ->orWhere('name', 'read')
            ->pluck('name')
            ->toArray();
        $this->savePermissions();
    }

    /**
     * Save permissions to database
     */
    public function savePermissions()
    {
        DB::transaction(function () {
            $this->role->syncPermissions($this->selectedPermissions);
            
            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Permissions updated successfully!',
        ]);
    }

    /**
     * Check if module has all permissions selected
     */
    public function isModuleFullySelected(int $moduleId): bool
    {
        $moduleData = $this->modulePermissions[$moduleId] ?? null;
        
        if (!$moduleData) return false;

        foreach ($moduleData['permissions'] as $menuPermissions) {
            foreach ($menuPermissions as $permission) {
                if (!in_array($permission->name, $this->selectedPermissions)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if module has some permissions selected
     */
    public function isModulePartiallySelected(int $moduleId): bool
    {
        $moduleData = $this->modulePermissions[$moduleId] ?? null;
        
        if (!$moduleData) return false;

        $hasSelected = false;
        $hasUnselected = false;

        foreach ($moduleData['permissions'] as $menuPermissions) {
            foreach ($menuPermissions as $permission) {
                if (in_array($permission->name, $this->selectedPermissions)) {
                    $hasSelected = true;
                } else {
                    $hasUnselected = true;
                }
            }
        }

        return $hasSelected && $hasUnselected;
    }

    /**
     * Check if menu has all permissions selected
     */
    public function isMenuFullySelected(int $moduleId, string $menuSlug): bool
    {
        $moduleData = $this->modulePermissions[$moduleId] ?? null;
        
        if (!$moduleData || !isset($moduleData['permissions'][$menuSlug])) return false;

        foreach ($moduleData['permissions'][$menuSlug] as $permission) {
            if (!in_array($permission->name, $this->selectedPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get permission count for display
     */
    public function getSelectedCountProperty(): int
    {
        return count($this->selectedPermissions);
    }

    /**
     * Get total permission count
     */
    public function getTotalCountProperty(): int
    {
        return Permission::count();
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}