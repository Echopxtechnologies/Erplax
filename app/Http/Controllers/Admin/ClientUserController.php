<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ClientUserController extends AdminController
{

    protected $model = User::class;
    // protected $with = ['category', 'brand', 'unit'];
    protected $searchable = ['name', 'email', 'is_active'];
    protected $sortable = [
        'name',
        'email',
        'is_active'
    ];
    protected $filterable = ['is_active'];

    protected $importable = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    protected $uniqueField = 'email';

    protected $exportable = [
        'name',
        'email',
        'is_active',
    ];

    /**
     * Show client users list
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.settings.role_permission.users.client_users.index', compact('users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.settings.role_permission.users.client_users.create');
    }

    /**
     * Store new client user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()
                ->route('admin.settings.client.index')
                ->with('success', 'Client user created successfully');
        } else {
            return redirect()
                ->route('admin.settings.client.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.settings.role_permission.users.client_users.show', compact('user'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.settings.role_permission.users.client_users.edit', compact('user'));
    }

    /**
     * Update client user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
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

            // Only use is_active (no status column in users table)
            $user->is_active = $request->has('is_active');
            $user->save();

            return redirect()
                ->route('admin.settings.client.index')
                ->with('success', 'Client user updated successfully');
        } else {
            return redirect()
                ->route('admin.settings.client.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        // Only toggle is_active (no status column in users table)
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User status updated successfully',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Delete client user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user === null) {
            session()->flash('error', 'Client user not found');
            return response()->json(['status' => false, 'message' => 'User not found']);
        }

        $user->delete();

        session()->flash('success', 'Client user deleted successfully');
        return response()->json(['status' => true]);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['status' => false, 'message' => 'No users selected']);
        }

        User::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => true,
            'message' => count($ids) . ' user(s) deleted successfully'
        ]);
    }

    /**
     * Export users
     */
    public function export(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'is_active', 'created_at')->get();
        
        $filename = 'client_users_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Active', 'Created At']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->is_active ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}