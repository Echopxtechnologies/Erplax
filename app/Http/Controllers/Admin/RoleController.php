<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Module;
use App\Models\Permission;
use Spatie\Permission\Models\Role as RoleModel;
use Illuminate\Support\Facades\Validator;

class RoleController extends AdminController
{
    /**
     * Show roles list
     */
    public function index()
    {
        $roles = RoleModel::where('guard_name', 'admin')
            ->with('permissions')
            ->latest()
            ->paginate(10);
            
        return view('admin.settings.role_permission.role.role-management', compact('roles'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get permissions grouped by module
        $permissionsByModule = $this->getPermissionsGroupedByModule();
        
        return view('admin.settings.role_permission.role.role-create', compact('permissionsByModule'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,NULL,id,guard_name,admin'
        ]);

        if ($validator->passes()) {
            $role = RoleModel::create([
                'name' => $request->name,
                'guard_name' => 'admin'
            ]);
            
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            return redirect()
                ->route('admin.settings.roles.index')
                ->with('success', 'Role created successfully');
        } else {
            return redirect()
                ->route('admin.settings.roles.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $role = RoleModel::where('guard_name', 'admin')->findOrFail($id);
        
        // Get permissions grouped by module
        $permissionsByModule = $this->getPermissionsGroupedByModule();
        
        // Get role's current permissions as array of names
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.settings.role_permission.role.role-edit', compact('role', 'permissionsByModule', 'rolePermissions'));
    }

    /**
     * Update role
     */
    public function update(Request $request, $id)
    {
        $role = RoleModel::where('guard_name', 'admin')->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id . ',id,guard_name,admin'
        ]);

        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();
            
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            return redirect()
                ->route('admin.settings.roles.index')
                ->with('success', 'Role updated successfully');
        } else {
            return redirect()
                ->route('admin.settings.roles.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Delete role
     */
    public function destroy($id)
    {
        $role = RoleModel::where('guard_name', 'admin')->find($id);
        
        if ($role === null) {
            session()->flash('error', 'Role not found');
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ]);
        }

        if ($role->name === 'super-admin') {
            session()->flash('error', 'Cannot delete super-admin role');
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete super-admin role'
            ]);
        }

        $role->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', 'Role deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Get permissions grouped by module (using App\Models\Permission which has module relationship)
     */
    private function getPermissionsGroupedByModule()
    {
        // Use App\Models\Permission (not Spatie's) which has the module relationship
        $permissions = Permission::with('module')
            ->where('guard_name', 'admin')
            ->orderBy('name')
            ->get();

        $permissionsByModule = [];
        
        foreach ($permissions as $permission) {
            $moduleId = $permission->module_id ?? 'orphan';
            
            if (!isset($permissionsByModule[$moduleId])) {
                $permissionsByModule[$moduleId] = [
                    'module' => $permission->module ?? (object)[
                        'id' => 'orphan',
                        'name' => 'Other Permissions',
                        'alias' => 'unassigned'
                    ],
                    'permissions' => []
                ];
            }

            // Get menu slug (second part of permission name)
            $parts = explode('.', $permission->name);
            $menuSlug = $parts[1] ?? $parts[0];

            if (!isset($permissionsByModule[$moduleId]['permissions'][$menuSlug])) {
                $permissionsByModule[$moduleId]['permissions'][$menuSlug] = [];
            }

            $permissionsByModule[$moduleId]['permissions'][$menuSlug][] = $permission;
        }

        // Sort by module name, orphan last
        uasort($permissionsByModule, function($a, $b) {
            if ($a['module']->id === 'orphan') return 1;
            if ($b['module']->id === 'orphan') return -1;
            return $a['module']->name <=> $b['module']->name;
        });

        return $permissionsByModule;
    }
}