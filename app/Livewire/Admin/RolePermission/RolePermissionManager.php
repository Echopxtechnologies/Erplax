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

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->loadModulePermissions();
    }

    /**
     * Load permissions grouped by module (admin guard only)
     */
    public function loadModulePermissions()
    {
        $this->modulePermissions = [];

        // Get all permissions for admin guard
        $allPermissions = Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();

        // Group permissions by module alias
        $groupedByModule = [];
        
        foreach ($allPermissions as $permission) {
            $parts = explode('.', $permission->name);
            
            if (count($parts) >= 3) {
                $moduleAlias = $parts[0];
                $menuSlug = $parts[1];
                $actionSlug = $parts[2];
            } elseif (count($parts) == 2) {
                $moduleAlias = $parts[0];
                $menuSlug = 'general';
                $actionSlug = $parts[1];
            } else {
                $moduleAlias = 'other';
                $menuSlug = 'general';
                $actionSlug = $parts[0];
            }

            if (!isset($groupedByModule[$moduleAlias])) {
                $groupedByModule[$moduleAlias] = [];
            }

            if (!isset($groupedByModule[$moduleAlias][$menuSlug])) {
                $groupedByModule[$moduleAlias][$menuSlug] = [];
            }

            $groupedByModule[$moduleAlias][$menuSlug][] = $permission;
        }

        // Get modules from database
        $modules = Module::where('is_active', true)
            ->where('is_installed', true)
            ->get()
            ->keyBy('alias');

        // Build final structure
        foreach ($groupedByModule as $moduleAlias => $menus) {
            $module = $modules[$moduleAlias] ?? null;
            
            $this->modulePermissions[$moduleAlias] = [
                'module' => $module ? $module : (object)[
                    'id' => $moduleAlias,
                    'name' => ucfirst($moduleAlias),
                    'alias' => $moduleAlias,
                ],
                'permissions' => collect($menus)->map(function ($perms) {
                    return collect($perms);
                }),
            ];
        }

        // Sort by module name
        uksort($this->modulePermissions, function ($a, $b) {
            if ($a === 'other') return 1;
            if ($b === 'other') return -1;
            return strcasecmp($a, $b);
        });
    }

    /**
     * Toggle a single permission
     */
    public function togglePermission(string $permissionName)
    {
        if (in_array($permissionName, $this->selectedPermissions)) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permissionName]));
        } else {
            $this->selectedPermissions[] = $permissionName;
        }

        $this->savePermissions();
    }

    /**
     * Toggle all permissions for a module
     */
    public function toggleModule(string $moduleAlias, bool $checked)
    {
        $moduleData = $this->modulePermissions[$moduleAlias] ?? null;
        
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
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $modulePermissionNames));
        }

        $this->savePermissions();
    }

    /**
     * Toggle all permissions for a menu
     */
    public function toggleMenu(string $moduleAlias, string $menuSlug, bool $checked)
    {
        $moduleData = $this->modulePermissions[$moduleAlias] ?? null;
        
        if (!$moduleData || !isset($moduleData['permissions'][$menuSlug])) return;

        $menuPermissionNames = $moduleData['permissions'][$menuSlug]->pluck('name')->toArray();

        if ($checked) {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $menuPermissionNames));
        } else {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $menuPermissionNames));
        }

        $this->savePermissions();
    }

    /**
     * Select all permissions
     */
    public function selectAll()
    {
        $this->selectedPermissions = Permission::where('guard_name', 'admin')
            ->pluck('name')
            ->toArray();
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
        $this->selectedPermissions = Permission::where('guard_name', 'admin')
            ->where(function ($query) {
                $query->where('name', 'like', '%.read')
                    ->orWhere('name', 'read');
            })
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
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Permissions updated!',
        ]);
    }

    /**
     * Check if module has all permissions selected
     */
    public function isModuleFullySelected(string $moduleAlias): bool
    {
        $moduleData = $this->modulePermissions[$moduleAlias] ?? null;
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
     * Check if menu has all permissions selected
     */
    public function isMenuFullySelected(string $moduleAlias, string $menuSlug): bool
    {
        $moduleData = $this->modulePermissions[$moduleAlias] ?? null;
        if (!$moduleData || !isset($moduleData['permissions'][$menuSlug])) return false;

        foreach ($moduleData['permissions'][$menuSlug] as $permission) {
            if (!in_array($permission->name, $this->selectedPermissions)) {
                return false;
            }
        }
        return true;
    }

    public function getSelectedCountProperty(): int
    {
        return count($this->selectedPermissions);
    }

    public function getTotalCountProperty(): int
    {
        return Permission::where('guard_name', 'admin')->count();
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}