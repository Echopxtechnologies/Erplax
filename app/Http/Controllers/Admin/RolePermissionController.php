<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Module;
use App\Models\Menu;
use App\Models\MenuAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends AdminController
{
    /**
     * Display all roles with permission management
     */
    public function index()
    {
        $redirect = $this->authorizeAdmin();
        if ($redirect) return $redirect;

        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('admin.settings.role_permission.role.role-management', compact('roles'));
    }

    /**
     * Show edit form for role permissions
     */
    public function edit($roleId)
    {
        $redirect = $this->authorizeAdmin();
        if ($redirect) return $redirect;

        $role = Role::with('permissions')->findOrFail($roleId);
        
        // Get modules with their menus and actions
        $modules = Module::with(['menus' => function ($query) {
            $query->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with(['actions' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                }, 'children' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('sort_order')
                        ->with(['actions' => function ($qa) {
                            $qa->where('is_active', true)->orderBy('sort_order');
                        }]);
                }]);
        }])
        ->where('is_active', true)
        ->where('is_installed', true)
        ->orderBy('sort_order')
        ->get();

        // Get current role permissions
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.settings.role_permission.role.role-edit', compact('role', 'modules', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function update(Request $request, $roleId)
    {
        $redirect = $this->authorizeAdmin();
        if ($redirect) return $redirect;

        $role = Role::findOrFail($roleId);

        $validator = Validator::make($request->all(), [
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($role, $request) {
            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);

            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        $this->logAction('Updated role permissions', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'permissions_count' => count($request->input('permissions', [])),
        ]);

        return redirect()
            ->route('admin.settings.roles.edit', $roleId)
            ->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Sync menu access for role (optional - if you want menu-based access)
     */
    public function syncMenuAccess(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $validator = Validator::make($request->all(), [
            'menus' => 'nullable|array',
            'menus.*' => 'integer|exists:menus,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Sync menus to role via pivot table
        $menuIds = $request->input('menus', []);
        
        DB::table('menu_role')->where('role_id', $role->id)->delete();
        
        foreach ($menuIds as $menuId) {
            DB::table('menu_role')->insert([
                'menu_id' => $menuId,
                'role_id' => $role->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu access updated successfully.',
        ]);
    }
}