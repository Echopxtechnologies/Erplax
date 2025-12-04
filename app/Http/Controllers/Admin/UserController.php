<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    // Show users list
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('admin.settings.role_permission.users.user-management', compact('users'));
    }

    // Show create form
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.settings.role_permission.users.user-create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array|min:1'
        ]);

        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => in_array('admin', $request->roles ?? []) ? 1 : 0,
            ]);

            // Assign roles
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            return redirect()->route('admin.settings.users.index')->with('success', 'User created successfully');
        } else {
            return redirect()->route('admin.settings.users.create')->withInput()->withErrors($validator);
        }
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.settings.role_permission.users.user-edit', compact('user', 'roles', 'userRoles'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
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

            $user->is_admin = in_array('admin', $request->roles ?? []) ? 1 : 0;
            $user->save();

            // Sync roles
            $user->syncRoles($request->roles);

            return redirect()->route('admin.settings.users.index')->with('success', 'User updated successfully');
        } else {
            return redirect()->route('admin.settings.users.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user === null) {
            session()->flash('error', 'User not found');
            return response()->json(['status' => false]);
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself');
            return response()->json(['status' => false, 'message' => 'Cannot delete yourself']);
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully');

        return response()->json(['status' => true]);
    }
}