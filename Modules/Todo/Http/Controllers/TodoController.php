<?php

namespace Modules\Todo\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use App\Models\Notification;
use Modules\Todo\Models\Todo;
use Modules\Core\Traits\DataTableTrait;
use Illuminate\Http\Request;

class TodoController extends AdminController
{
    use DataTableTrait;

    // =========================================
    // DATATABLE v2.0 CONFIGURATION
    // =========================================
    
    protected $model = Todo::class;
    protected $with = ['user', 'assignee'];
    protected $searchable = ['title', 'description'];
    protected $sortable = ['id', 'title', 'priority', 'status', 'due_date', 'created_at'];
    protected $filterable = ['status', 'priority', 'assigned_to', 'user_id'];
    protected $routePrefix = 'admin.todo';
    protected $exportTitle = 'Todo Tasks Export';

    // Import validation rules
    protected $importable = [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority'    => 'in:low,medium,high',
        'status'      => 'in:pending,in_progress,completed',
        'due_date'    => 'nullable|date',
    ];

    // Import lookups - convert names to IDs automatically
    protected $importLookups = [
        'assignee_name' => [
            'table'   => 'users',
            'search'  => 'name',
            'return'  => 'id',
            'save_as' => 'assigned_to',
        ],
    ];

    // Default values for empty import columns
    protected $importDefaults = [
        'priority' => 'medium',
        'status'   => 'pending',
    ];

    // Bulk actions dropdown
    protected $bulkActions = [
        'delete'      => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
        'complete'    => ['label' => 'Mark Completed', 'confirm' => false, 'color' => 'green'],
        'pending'     => ['label' => 'Mark Pending', 'confirm' => false, 'color' => 'yellow'],
        'in_progress' => ['label' => 'Mark In Progress', 'confirm' => false, 'color' => 'blue'],
    ];

    // =========================================
    // OVERRIDE: Custom Query with Access Control
    // =========================================
    
    /**
     * Override dtList to add access control
     * Admin sees all, regular user sees own + assigned to them
     */
    protected function dtList(Request $request)
    {
        $user = $this->admin();
        $query = $this->model::query();

        // Eager load relations
        if (!empty($this->with)) {
            $query->with($this->with);
        }

        // ACCESS CONTROL
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchable as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        // Apply standard filters
        $this->applyFilters($query, $request);

        // Custom: Overdue filter
        if ($request->input('overdue')) {
            $query->overdue();
        }

        // Custom: Date range on due_date
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('due_date', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('due_date', '<=', $toDate);
        }

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        if (in_array($sortCol, $this->sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Paginate
        $perPage = min($request->get('per_page', 10), 100);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(fn($item) => $this->mapRow($item));

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    /**
     * Custom row mapping for list
     */
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'user_name' => $item->user->name ?? 'Unknown',
            'assignee_name' => $item->assignee->name ?? 'Unassigned',
            'priority' => $item->priority,
            'status' => $item->status,
            'due_date' => $item->due_date?->format('Y-m-d'),
            'is_overdue' => $item->is_overdue,
            '_show_url' => route('admin.todo.show', $item->id),
            '_edit_url' => route('admin.todo.edit', $item->id),
            '_delete_url' => route('admin.todo.destroy', $item->id),
        ];
    }

    /**
     * Custom export row mapping
     */
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Title' => $item->title,
            'Description' => $item->description,
            'Created By' => $item->user->name ?? 'Unknown',
            'Assigned To' => $item->assignee->name ?? 'Unassigned',
            'Priority' => ucfirst($item->priority),
            'Status' => ucfirst(str_replace('_', ' ', $item->status)),
            'Due Date' => $item->due_date?->format('Y-m-d'),
            'Completed At' => $item->completed_at?->format('Y-m-d H:i'),
            'Created At' => $item->created_at?->format('Y-m-d H:i'),
        ];
    }

    /**
     * Override getExportData to add access control
     */
    protected function getExportData(Request $request)
    {
        $user = $this->admin();
        $query = $this->model::query()->with($this->with);

        // ACCESS CONTROL
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Apply filters
        $this->applyFilters($query, $request);

        // Custom: Overdue filter
        if ($request->input('overdue')) {
            $query->overdue();
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchable as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        // Selected IDs only
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        return $query->get();
    }

    /**
     * Custom import row - set user_id to current user
     */
    protected function importRow($data, $raw)
    {
        $user = $this->admin();
        $data['user_id'] = $user->id;

        // Non-admin can't assign to others
        if (!$user->is_admin) {
            unset($data['assigned_to']);
        }

        // Set completed_at if importing as completed
        if (($data['status'] ?? 'pending') === 'completed') {
            $data['completed_at'] = now();
        }

        return Todo::create($data);
    }

    // =========================================
    // CUSTOM BULK ACTIONS
    // =========================================

    /**
     * Bulk delete with access control
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $user = $this->admin();
        $query = Todo::whereIn('id', $ids);

        // Access control
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Delete notifications first
        $todos = $query->get();
        foreach ($todos as $todo) {
            $this->deleteTaskNotifications($todo);
        }

        $deleted = Todo::whereIn('id', $todos->pluck('id'))->delete();

        return response()->json(['success' => true, 'message' => "{$deleted} tasks deleted"]);
    }

    /**
     * Bulk mark as completed
     */
    public function bulkComplete(Request $request)
    {
        return $this->bulkUpdateStatus($request, 'completed');
    }

    /**
     * Bulk mark as pending
     */
    public function bulkPending(Request $request)
    {
        return $this->bulkUpdateStatus($request, 'pending');
    }

    /**
     * Bulk mark as in_progress
     */
    public function bulkIn_progress(Request $request)
    {
        return $this->bulkUpdateStatus($request, 'in_progress');
    }

    /**
     * Helper: Bulk update status with access control
     */
    protected function bulkUpdateStatus(Request $request, $status)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $user = $this->admin();
        $query = Todo::whereIn('id', $ids);

        // Access control
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        $updateData = ['status' => $status];
        
        if ($status === 'completed') {
            $updateData['completed_at'] = now();
        } else {
            $updateData['completed_at'] = null;
        }

        $count = $query->update($updateData);
        $statusLabel = ucfirst(str_replace('_', ' ', $status));

        return response()->json(['success' => true, 'message' => "{$count} tasks marked as {$statusLabel}"]);
    }

    // =========================================
    // STANDARD CRUD METHODS
    // =========================================

    /**
     * Display listing page
     */
    public function index()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;

        $baseQuery = Todo::query();
        if (!$isAdmin) {
            $baseQuery->forUser($user->id);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        return view('todo::index', compact('stats', 'isAdmin', 'users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;
        $users = $isAdmin ? User::orderBy('name')->get() : collect();
        
        return view('todo::create', compact('isAdmin', 'users'));
    }

    /**
     * Store new todo
     */
    public function store(Request $request)
    {
        $user = $this->admin();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['user_id'] = $user->id;

        if (!$user->is_admin) {
            $validated['assigned_to'] = null;
        }

        if (($validated['status'] ?? 'pending') === 'completed') {
            $validated['completed_at'] = now();
        }

        $todo = Todo::create($validated);

        // Notify assigned user
        if ($todo->assigned_to && $todo->assigned_to !== $user->id) {
            $todo->notifyAssignee(
                'New Task Assigned',
                "{$user->name} assigned you a task: \"{$todo->title}\"",
                $user
            );
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show single todo
     */
    public function show($id)
    {
        $todo = $this->getTodoWithAccess($id);
        return view('todo::show', compact('todo'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $todo = $this->getTodoWithAccess($id);
        $user = $this->admin();
        $isAdmin = $user->is_admin;
        $users = $isAdmin ? User::orderBy('name')->get() : collect();
        
        return view('todo::edit', compact('todo', 'isAdmin', 'users'));
    }

    /**
     * Update todo
     */
    public function update(Request $request, $id)
    {
        $todo = $this->getTodoWithAccess($id);
        $user = $this->admin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if (!$user->is_admin) {
            unset($validated['assigned_to']);
        }

        $oldAssignee = $todo->assigned_to;
        $wasOverdue = $todo->is_overdue;

        if ($validated['status'] === 'completed' && $todo->status !== 'completed') {
            $validated['completed_at'] = now();
            if ($wasOverdue) {
                $this->deleteOverdueNotification($todo);
            }
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
            if ($todo->due_date != ($validated['due_date'] ?? null) || $todo->status === 'completed') {
                $validated['overdue_notified'] = false;
            }
        }

        $todo->update($validated);

        // Notify new assignee if changed
        if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $oldAssignee && $validated['assigned_to'] !== $user->id) {
            $todo->notifyAssignee(
                'Task Assigned to You',
                "{$user->name} assigned you a task: \"{$todo->title}\"",
                $user
            );
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Delete todo
     */
    public function destroy($id)
    {
        $todo = $this->getTodoWithAccess($id);
        $this->deleteTaskNotifications($todo);
        $todo->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        }

        return redirect()->route('admin.todo.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Quick toggle status (AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        $todo = $this->getTodoWithAccess($id);
        $wasOverdue = $todo->is_overdue;
        
        $newStatus = $request->input('status');
        $todo->status = $newStatus;
        
        if ($newStatus === 'completed') {
            $todo->completed_at = now();
            if ($wasOverdue) {
                $this->deleteOverdueNotification($todo);
            }
        } else {
            $todo->completed_at = null;
        }
        
        $todo->save();

        return response()->json(['success' => true, 'status' => $todo->status]);
    }

    /**
     * Check overdue tasks (for scheduler/cron)
     */
    public function checkOverdueTasks()
    {
        $overdueTodos = Todo::overdue()
            ->where('overdue_notified', false)
            ->get();

        $count = 0;
        foreach ($overdueTodos as $todo) {
            $todo->sendOverdueNotification();
            $count++;
        }

        return response()->json(['success' => true, 'notified' => $count]);
    }

    // =========================================
    // PRIVATE HELPERS
    // =========================================

    private function deleteOverdueNotification(Todo $todo)
    {
        $notifyUserId = $todo->assigned_to ?? $todo->user_id;
        
        Notification::where('user_id', $notifyUserId)
            ->where('title', 'Task Overdue!')
            ->where('url', 'LIKE', '%/todo/' . $todo->id . '%')
            ->delete();
    }

    private function deleteTaskNotifications(Todo $todo)
    {
        Notification::where('url', 'LIKE', '%/todo/' . $todo->id . '%')->delete();
    }

    private function getTodoWithAccess($id)
    {
        $user = $this->admin();
        $todo = Todo::with(['user', 'assignee'])->findOrFail($id);

        if (!$user->is_admin && $todo->user_id !== $user->id && $todo->assigned_to !== $user->id) {
            abort(403, 'You do not have permission to access this task.');
        }

        return $todo;
    }
}
