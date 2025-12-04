<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use Spatie\Permission\Models\Role as RoleModel;
use Spatie\Permission\Models\Permission as PermissionModel;
use Illuminate\Support\Facades\Validator;

class RoleController extends AdminController
{
    // Show roles list
    public function index()
    {
        $roles = RoleModel::with('permissions')->latest()->paginate(10);
        return view('admin.settings.role_permission.role.role-management', compact('roles'));
    }

    // Show create form
    public function create()
    {
        $permissions = PermissionModel::orderBy('name')->get();
        return view('admin.settings.role_permission.role.role-create', compact('permissions'));
    }

    // Store new role
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {
            $role = RoleModel::create(['name' => $request->name]);
            
            // Sync permissions if any selected
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            
            return redirect()->route('admin.settings.roles.index')->with('success', 'Role created successfully');
        } else {
            return redirect()->route('admin.settings.roles.create')->withInput()->withErrors($validator);
        }
    }

    // Show edit form
    public function edit($id)
    {
        $role = RoleModel::findOrFail($id);
        $permissions = PermissionModel::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.settings.role_permission.role.role-edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update role
    public function update(Request $request, $id)
    {
        $role = RoleModel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id . ',id'
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
            
            return redirect()->route('admin.settings.roles.index')->with('success', 'Role updated successfully');
        } else {
            return redirect()->route('admin.settings.roles.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // Delete role
    public function destroy($id)
    {
        $role = RoleModel::find($id);
        
        if ($role === null) {
            session()->flash('error', 'Role not found');
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ]);
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}