<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use Spatie\Permission\Models\Permission as PermissionModel;
use Illuminate\Support\Facades\Validator;

class PermissionController extends AdminController
{
    // Show permissions list
    public function index()
    {
        $permissions = PermissionModel::latest()->paginate(10);
        return view('admin.settings.role_permission.permission.permission-management', compact('permissions'));
    }

    // Show create form
    public function create()
    {
        return view('admin.settings.role_permission.permission.permission-create');
    }

    // Store new permission
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            PermissionModel::create(['name' => $request->name]);
            return redirect()->route('admin.settings.permissions.index')->with('success', 'Permission added successfully');
        } else {
            return redirect()->route('admin.settings.permissions.create')->withInput()->withErrors($validator);
        }
    }

    // Show edit form
    public function edit($id)
    {
        $permission = PermissionModel::findOrFail($id);
        return view('admin.settings.role_permission.permission.permission-edit', compact('permission'));
    }

    // Update permission
    public function update(Request $request, $id)
    {
        $permission = PermissionModel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);

        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('admin.settings.permissions.index')->with('success', 'Permission updated successfully');
        } else {
            return redirect()->route('admin.settings.permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // Delete permission
    public function destroy($id)
    {
        $permission = PermissionModel::find($id);
        
        if ($permission === null) {
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false,
                'message' => 'Permission not found'
            ]);
        }

        $permission->delete();
        session()->flash('success', 'Permission deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}