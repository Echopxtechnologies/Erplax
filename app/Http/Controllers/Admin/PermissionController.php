<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermissionController extends AdminController
{
    /**
     * Show permissions grouped by module
     */
    public function index()
    {
        // Get all permissions with their modules
        $permissions = Permission::with('module')
            ->orderBy('name')
            ->get();

        // Group permissions by module
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

        // Sort by module name
        uasort($permissionsByModule, function($a, $b) {
            if ($a['module']->id === 'orphan') return 1;
            if ($b['module']->id === 'orphan') return -1;
            return $a['module']->name <=> $b['module']->name;
        });

        // Stats
        $totalPermissions = $permissions->count();
        $modulesCount = Module::where('is_installed', true)->count();
        $orphanPermissions = $permissions->whereNull('module_id')->count();

        return view('admin.settings.role_permission.permission.permission-management', compact(
            'permissionsByModule',
            'totalPermissions',
            'modulesCount',
            'orphanPermissions'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $modules = Module::where('is_active', true)
            ->where('is_installed', true)
            ->orderBy('name')
            ->get();

        return view('admin.settings.role_permission.permission.permission-create', compact('modules'));
    }

    /**
     * Store new permission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
            'name' => 'required|unique:permissions,name|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get action name from the permission name
        $parts = explode('.', $request->name);
        $actionSlug = end($parts);
        $actionNames = [
            'read' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
        ];

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'admin',  // ← Changed from 'web' to 'admin'
            'module_id' => $request->module_id,
            'action_name' => $actionNames[$actionSlug] ?? ucfirst($actionSlug),
        ]);

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.settings.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Store bulk permissions
     */
    public function storeBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulk_module_id' => 'required|exists:modules,id',
            'bulk_menu_name' => 'required|string|min:2',
            'bulk_actions' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = Module::find($request->bulk_module_id);
        $menuName = strtolower(str_replace(' ', '_', $request->bulk_menu_name));
        $actions = $request->bulk_actions;

        $actionNames = [
            'read' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
        ];

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($module, $menuName, $actions, $actionNames, &$created, &$skipped) {
            foreach ($actions as $action) {
                $permissionName = "{$module->alias}.{$menuName}.{$action}";
                
                // Check if permission already exists
                if (Permission::where('name', $permissionName)->exists()) {
                    $skipped++;
                    continue;
                }

                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'admin',  // ← Changed from 'web' to 'admin'
                    'module_id' => $module->id,
                    'action_name' => $actionNames[$action] ?? ucfirst($action),
                ]);
                $created++;
            }
        });

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $message = "{$created} permission(s) created successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} permission(s) already existed.";
        }

        return redirect()
            ->route('admin.settings.permissions.index')
            ->with('success', $message);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $modules = Module::where('is_active', true)
            ->where('is_installed', true)
            ->orderBy('name')
            ->get();

        return view('admin.settings.role_permission.permission.permission-edit', compact('permission', 'modules'));
    }

    /**
     * Update permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
            'name' => 'required|min:3|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $parts = explode('.', $request->name);
        $actionSlug = end($parts);
        $actionNames = [
            'read' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
        ];

        $permission->update([
            'name' => $request->name,
            'guard_name' => 'admin',  // ← Ensure admin guard
            'module_id' => $request->module_id,
            'action_name' => $actionNames[$actionSlug] ?? ucfirst($actionSlug),
        ]);

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.settings.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Delete permission
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => false,
                'message' => 'Permission not found'
            ]);
        }

        $permission->delete();

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', 'Permission deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}