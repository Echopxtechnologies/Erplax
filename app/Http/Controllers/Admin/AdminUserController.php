<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends AdminController
{
    /**
     * Show admin users list
     */
    public function index()
    {
        $users = Admin::with('roles')->latest()->paginate(10);
        return view('admin.settings.role_permission.users.user-management', compact('users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Only get roles for admin guard
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        return view('admin.settings.role_permission.users.user-create', compact('roles'));
    }

    /**
     * Store new admin user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array|min:1'
        ]);

        if ($validator->passes()) {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => true,
                'is_active' => $request->has('is_active') ? true : true,
            ]);

            // Assign roles (using admin guard)
            if ($request->has('roles')) {
                $admin->syncRoles($request->roles);
            }

            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()
                ->route('admin.settings.users.index')
                ->with('success', 'Admin user created successfully');
        } else {
            return redirect()
                ->route('admin.settings.users.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.settings.role_permission.users.user-edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update admin user
     */
    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins,email,' . $id,
            'roles' => 'required|array|min:1'
        ];

        // Only validate password if provided
        if ($request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->is_active = $request->has('is_active');
            $user->save();

            // Sync roles
            $user->syncRoles($request->roles);

            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()
                ->route('admin.settings.users.index')
                ->with('success', 'Admin user updated successfully');
        } else {
            return redirect()
                ->route('admin.settings.users.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Delete admin user
     */
    public function destroy($id)
    {
        $user = Admin::find($id);

        if ($user === null) {
            session()->flash('error', 'Admin user not found');
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        // Prevent deleting yourself
        if ($user->id === Auth::guard('admin')->id()) {
            session()->flash('error', 'You cannot delete yourself');
            return response()->json(['status' => false, 'message' => 'Cannot delete yourself']);
        }

        // Prevent deleting the last super-admin
        if ($user->hasRole('super-admin')) {
            $superAdminCount = Admin::role('super-admin')->count();
            if ($superAdminCount <= 1) {
                session()->flash('error', 'Cannot delete the last super-admin');
                return response()->json(['status' => false, 'message' => 'Cannot delete the last super-admin']);
            }
        }

        $user->delete();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash('success', 'Admin user deleted successfully');
        return response()->json(['status' => true]);
    }
}