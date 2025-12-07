<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use Spatie\Permission\Models\Role as RoleModel;
use Spatie\Permission\Models\Permission as PermissionModel;
use Illuminate\Support\Facades\Validator;

class RoleController extends AdminController
{
    /**
     * Show roles list
     */
    public function index()
    {
        // Only get roles for admin guard
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
        // Only get permissions for admin guard
        $permissions = PermissionModel::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
            
        return view('admin.settings.role_permission.role.role-create', compact('permissions'));
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
            // Create role with admin guard
            $role = RoleModel::create([
                'name' => $request->name,
                'guard_name' => 'admin'  // ← Admin guard
            ]);
            
            // Sync permissions if any selected
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            // Clear cache
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
        $permissions = PermissionModel::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.settings.role_permission.role.role-edit', compact('role', 'permissions', 'rolePermissions'));
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
            
            // Sync permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            // Clear cache
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

        // Prevent deleting super-admin role
        if ($role->name === 'super-admin') {
            session()->flash('error', 'Cannot delete super-admin role');
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete super-admin role'
            ]);
        }

        $role->delete();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', 'Role deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}