<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class StaffUserController extends Controller
{
    /**
     * Display a listing of staff
     */
    public function index(Request $request)
    {
        $query = Staff::with(['admin.roles'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->withRole($request->role);
        }

        // Filter by system access
        if ($request->filled('access')) {
            if ($request->access === 'with') {
                $query->withSystemAccess();
            } else {
                $query->withoutSystemAccess();
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $staffMembers = $query->paginate(15)->withQueryString();
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();

        return view('admin.settings.role_permission.users.user-management', compact('staffMembers', 'roles'));
    }

    /**
     * Show the form for creating new staff
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        return view('admin.staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member
     */
    public function store(Request $request)
    {
        $hasSystemAccess = $request->boolean('has_system_access');

        // Validation
        $rules = [
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|integer',
            'designation_id' => 'nullable|integer',
        ];

        if ($hasSystemAccess) {
            $rules['login_email'] = 'required|email|unique:admins,email';
            $rules['password'] = 'required|min:8|confirmed';
            $rules['roles'] = 'required|array|min:1';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $adminId = null;

            // Create Admin login if system access enabled
            if ($hasSystemAccess) {
                $admin = Admin::create([
                    'name' => trim($request->first_name . ' ' . $request->last_name),
                    'email' => $request->login_email,
                    'password' => Hash::make($request->password),
                    'is_active' => $request->boolean('is_active', true),
                ]);

                if ($request->filled('roles')) {
                    $admin->syncRoles($request->roles);
                }

                $adminId = $admin->id;
            }

            // Create Staff profile
            $staff = Staff::create([
                'admin_id' => $adminId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'status' => $request->boolean('status', true),
            ]);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('admin.staff.index')
                ->with('success', 'Staff member created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create staff', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create staff member.');
        }
    }

    /**
     * Show the form for editing staff
     */
    public function edit(Staff $staff)
    {
        $staff->load('admin.roles');
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified staff member
     */
    public function update(Request $request, Staff $staff)
    {
        $hasSystemAccess = $request->boolean('has_system_access');
        $adminId = $staff->admin_id;

        // Validation
        $rules = [
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|integer',
            'designation_id' => 'nullable|integer',
        ];

        if ($hasSystemAccess) {
            $rules['login_email'] = 'required|email|unique:admins,email,' . $adminId;
            $rules['roles'] = 'required|array|min:1';
            if (!$adminId) {
                $rules['password'] = 'required|min:8|confirmed';
            } else {
                $rules['password'] = 'nullable|min:8|confirmed';
            }
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Handle Admin record
            if ($hasSystemAccess) {
                if ($staff->admin) {
                    // Update existing admin
                    $staff->admin->email = $request->login_email;
                    $staff->admin->name = trim($request->first_name . ' ' . $request->last_name);
                    $staff->admin->is_active = $request->boolean('is_active', true);
                    
                    if ($request->filled('password')) {
                        $staff->admin->password = Hash::make($request->password);
                    }
                    
                    $staff->admin->save();
                    $staff->admin->syncRoles($request->roles);
                } else {
                    // Create new admin
                    $admin = Admin::create([
                        'name' => trim($request->first_name . ' ' . $request->last_name),
                        'email' => $request->login_email,
                        'password' => Hash::make($request->password),
                        'is_active' => $request->boolean('is_active', true),
                    ]);

                    $admin->syncRoles($request->roles);
                    $staff->admin_id = $admin->id;
                }
            } else {
                // Remove system access
                if ($staff->admin) {
                    // Prevent removing own access
                    if ($staff->admin->id === Auth::guard('admin')->id()) {
                        return redirect()
                            ->back()
                            ->with('error', 'You cannot remove your own system access.');
                    }

                    $staff->admin->delete();
                    $staff->admin_id = null;
                }
            }

            // Update Staff profile
            $staff->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'status' => $request->boolean('status', true),
            ]);
            $staff->save();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('admin.staff.index')
                ->with('success', 'Staff member updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update staff', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update staff member.');
        }
    }

    /**
     * Remove the specified staff member
     */
    public function destroy(Staff $staff)
    {
        // Prevent deleting self
        if ($staff->admin && $staff->admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot delete your own account.'
            ]);
        }

        DB::beginTransaction();

        try {
            if ($staff->admin) {
                $staff->admin->delete();
            }
            $staff->delete();

            DB::commit();

            session()->flash('success', 'Staff member deleted successfully.');
            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete staff member.'
            ]);
        }
    }

    /**
     * Toggle staff status
     */
    public function toggleStatus(Staff $staff)
    {
        DB::beginTransaction();

        try {
            $staff->status = !$staff->status;
            $staff->save();

            if ($staff->admin) {
                $staff->admin->is_active = $staff->status;
                $staff->admin->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'new_status' => $staff->status,
                'message' => 'Status updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update status.'
            ]);
        }
    }
}