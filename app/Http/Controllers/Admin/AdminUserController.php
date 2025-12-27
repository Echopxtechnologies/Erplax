<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use App\Models\Admin\Staff;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUserController extends AdminController
{
    /**
     * Display staff listing
     */
    public function index(Request $request)
    {
        $query = Staff::with(['admin.roles']);

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('admin.roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $staffs = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();

        return view('admin.settings.role_permission.users.user-management', compact('staffs', 'roles'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        
        return view('admin.settings.role_permission.users.user-create', compact('roles'));
    }

    /**
     * Generate unique employee code
     */
    private function generateEmployeeCode()
    {
        $year = date('Y');
        $lastStaff = Staff::where('employee_code', 'like', "EMP{$year}%")
            ->orderBy('employee_code', 'desc')
            ->first();
        
        if ($lastStaff && preg_match('/EMP' . $year . '(\d+)/', $lastStaff->employee_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        return 'EMP' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store new staff with admin account
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Info
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email|unique:admins,email|unique:staffs,email',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            
            // Address
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            
            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            
            // Employment (text fields)
            'employee_code' => 'nullable|string|max:20|unique:staffs,employee_code',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date|after_or_equal:join_date',
            
            // Credentials
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name'
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already registered',
            'dob.before' => 'Date of birth must be in the past',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Passwords do not match',
            'roles.required' => 'Please assign at least one role',
            'confirmation_date.after_or_equal' => 'Confirmation date must be after join date',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.settings.users.create')
                ->withInput()
                ->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $fullName = trim($request->first_name . ' ' . $request->last_name);
            
            // Checkbox sends value only when checked, so use has() instead of boolean()
            $isActive = $request->has('is_active');

            // Create Admin record for authentication
            $admin = Admin::create([
                'name' => $fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
                'is_active' => $isActive,
            ]);

            // Assign roles
            $roles = $request->input('roles', []);
            $admin->syncRoles($roles);

            // Generate employee code if not provided
            $employeeCode = $request->employee_code ?: $this->generateEmployeeCode();

            // Create Staff record for profile data
            Staff::create([
                'admin_id' => $admin->id,
                'employee_code' => $employeeCode,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'dob' => $request->dob ?: null,
                'gender' => $request->gender,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'department' => $request->department,
                'designation' => $request->designation,
                'join_date' => $request->join_date ?: null,
                'confirmation_date' => $request->confirmation_date ?: null,
                'status' => $isActive,
            ]);

            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('admin.settings.users.index')
                ->with('success', 'Staff member created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Staff creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Show actual error for debugging
            return redirect()
                ->route('admin.settings.users.create')
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form - $id is Admin ID
     */
    public function edit($id)
    {
        $admin = Admin::with(['staff', 'roles'])->findOrFail($id);
        $staff = $admin->staff;
        
        // If no staff record exists, create empty object for form
        if (!$staff) {
            $staff = new Staff();
            $nameParts = explode(' ', $admin->name, 2);
            $staff->first_name = $nameParts[0] ?? '';
            $staff->last_name = $nameParts[1] ?? '';
        }
        
        $roles = Role::where('guard_name', 'admin')->orderBy('name')->get();
        $userRoles = $admin->roles->pluck('name')->toArray();

        $user = $admin;

        return view('admin.settings.role_permission.users.user-edit', compact(
            'user', 'staff', 'roles', 'userRoles'
        ));
    }

    /**
     * Update staff and admin records - $id is Admin ID
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::with('staff')->findOrFail($id);
        $staff = $admin->staff;
        $staffId = $staff ? $staff->id : null;

        $rules = [
            // Personal Info
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'email' => 'required|email|unique:admins,email,' . $id . '|unique:staffs,email,' . $staffId,
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            
            // Address
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            
            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            
            // Employment (text fields)
            'employee_code' => 'nullable|string|max:20|unique:staffs,employee_code,' . $staffId,
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date|after_or_equal:join_date',
            'exit_date' => 'nullable|date|after_or_equal:join_date',
            
            // Roles
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name'
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validator = Validator::make($request->all(), $rules, [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already registered',
            'dob.before' => 'Date of birth must be in the past',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Passwords do not match',
            'roles.required' => 'Please assign at least one role',
            'confirmation_date.after_or_equal' => 'Confirmation date must be after join date',
            'exit_date.after_or_equal' => 'Exit date must be after join date',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.settings.users.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $fullName = trim($request->first_name . ' ' . $request->last_name);
            
            // Checkbox sends value only when checked, so use has() instead of boolean()
            $isActive = $request->has('is_active');

            // Prevent self-deactivation
            if ($admin->id === Auth::guard('admin')->id()) {
                $isActive = true;
            }

            // Update Admin record
            $admin->name = $fullName;
            $admin->email = $request->email;
            $admin->is_active = $isActive;

            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            $admin->save();
            
            // Sync roles from request
            $roles = $request->input('roles', []);
            $admin->syncRoles($roles);

            // Staff data
            $staffData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'dob' => $request->dob ?: null,
                'gender' => $request->gender,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'department' => $request->department,
                'designation' => $request->designation,
                'join_date' => $request->join_date ?: null,
                'confirmation_date' => $request->confirmation_date ?: null,
                'exit_date' => $request->exit_date ?: null,
                'status' => $isActive,
            ];

            // Update or create Staff record
            if ($staff) {
                $staffData['employee_code'] = $request->employee_code ?: $staff->employee_code;
                $staff->update($staffData);
            } else {
                $staffData['admin_id'] = $admin->id;
                $staffData['employee_code'] = $request->employee_code ?: $this->generateEmployeeCode();
                Staff::create($staffData);
            }

            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            return redirect()
                ->route('admin.settings.users.index')
                ->with('success', 'Staff member updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Staff update failed', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()
                ->route('admin.settings.users.edit', $id)
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete staff and admin records - $id is Admin ID
     */
    public function destroy($id)
    {
        $admin = Admin::with('staff')->find($id);

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Prevent self-deletion
        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        // Prevent deleting last super-admin
        if ($admin->hasRole('super-admin')) {
            $superAdminCount = Admin::role('super-admin')->count();
            if ($superAdminCount <= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot delete the last super-admin'
                ], 403);
            }
        }

        DB::beginTransaction();

        try {
            if ($admin->staff) {
                $admin->staff->delete();
            }
            
            $admin->delete();

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User deletion failed', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }
    }

    /**
     * Toggle staff status - $id is Admin ID
     */
    public function toggleStatus($id)
    {
        $admin = Admin::with('staff')->findOrFail($id);

        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot deactivate your own account'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $newStatus = !$admin->is_active;
            $admin->is_active = $newStatus;
            $admin->save();

            if ($admin->staff) {
                $admin->staff->status = $newStatus;
                $admin->staff->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'is_active' => $newStatus,
                'message' => $newStatus ? 'Staff activated' : 'Staff deactivated'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
}