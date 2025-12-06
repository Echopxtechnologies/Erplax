<?php

namespace Modules\Todo\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use App\Models\Notification;
use Modules\Todo\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TodoController extends AdminController
{
    /**
     * DataTable endpoint
     */
    public function dataTable(Request $request)
    {
        $user = $this->admin();
        $query = Todo::query()->with(['user', 'assignee']);

        // Filter: Admin sees all, regular user sees own + assigned to them
        if (!$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        // Export selected IDs
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
            return $this->dtExportTodo($query);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        if ($filters = $request->input('filters')) {
            $decoded = is_array($filters) ? $filters : json_decode($filters, true);
            foreach ($decoded ?? [] as $key => $value) {
                if ($value !== '' && $value !== null) {
                    if ($key === 'overdue' && $value) {
                        $query->overdue();
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Export all
        if ($request->has('export')) {
            return $this->dtExportTodo($query);
        }

        // Paginate
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        // Add URLs and user names
        $items = collect($data->items())->map(function ($item) {
            $item->_edit_url = route('admin.todo.edit', $item->id);
            $item->_show_url = route('admin.todo.show', $item->id);
            $item->user_name = $item->user->name ?? 'Unknown';
            $item->assignee_name = $item->assignee->name ?? 'Unassigned';
            $item->is_overdue = $item->is_overdue;
            return $item;
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Export helper
     */
    protected function dtExportTodo($query)
    {
        $data = $query->get();
        $filename = 'todos_' . date('Y-m-d');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Created By', 'Assigned To', 'Priority', 'Status', 'Due Date']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->title,
                    $row->user->name ?? 'Unknown',
                    $row->assignee->name ?? 'Unassigned',
                    $row->priority,
                    $row->status,
                    $row->due_date?->format('Y-m-d'),
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Display listing
     */
    public function index()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;

        // Stats for dashboard cards
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

        // Get all users for assign dropdown (admin only)
        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        return $this->moduleView('todo::index', compact('stats', 'isAdmin', 'users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = $this->admin();
        $isAdmin = $user->is_admin;
        $users = $isAdmin ? User::orderBy('name')->get() : collect();
        
        return $this->moduleView('todo::create', compact('isAdmin', 'users'));
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

        // Non-admin can't assign to others
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
        return $this->moduleView('todo::show', compact('todo'));
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
        
        return $this->moduleView('todo::edit', compact('todo', 'isAdmin', 'users'));
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

        // Non-admin can't change assignment
        if (!$user->is_admin) {
            unset($validated['assigned_to']);
        }

        $oldAssignee = $todo->assigned_to;
        $wasOverdue = $todo->is_overdue;

        // Set completed_at when marking as completed
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
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        $user = $this->admin();
        $query = Todo::whereIn('id', $ids);

        if (!$user->is_admin) {
            $query->forUser($user->id);
        }

        $todos = $query->get();
        foreach ($todos as $todo) {
            $this->deleteTaskNotifications($todo);
        }

        $deleted = $query->delete();

        return response()->json(['success' => true, 'message' => $deleted . ' items deleted']);
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
     * Check overdue tasks
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
