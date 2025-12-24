<?php

namespace Modules\Todo\Http\Controllers\Client;

use App\Http\Controllers\Client\ClientController;
use Modules\Todo\Models\Todo;
use Illuminate\Http\Request;

class ClientTodoController extends ClientController
{
    /**
     * Display task list
     */
    public function index(Request $request)
    {
        $client = $this->client();
        
        // Get stats for this client
        $baseQuery = Todo::forUser($client->id);
        
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        return view('todo::client.index', compact('stats'));
    }

    /**
     * DataTable endpoint for client tasks
     */
    public function dataTable(Request $request)
    {
        $client = $this->client();
        $query = Todo::with(['user', 'assignee'])->forUser($client->id);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Priority filter
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        // Overdue filter
        if ($request->input('overdue')) {
            $query->overdue();
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $sortable = ['id', 'title', 'priority', 'status', 'due_date', 'created_at'];
        if (in_array($sortCol, $sortable)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Paginate
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        // Map rows
        $items = collect($data->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'priority' => $item->priority,
                'status' => $item->status,
                'due_date' => $item->due_date?->format('Y-m-d'),
                'is_overdue' => $item->is_overdue,
                'created_by' => $item->user->name ?? 'Unknown',
                '_show_url' => route('client.todo.show', $item->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Show single task
     */
    public function show($id)
    {
        $client = $this->client();
        $todo = Todo::with(['user', 'assignee'])->findOrFail($id);

        // Check access - client can only see their own tasks or tasks assigned to them
        if ($todo->user_id !== $client->id && $todo->assigned_to !== $client->id) {
            abort(403, 'You do not have permission to view this task.');
        }

        return view('todo::client.show', compact('todo'));
    }

    /**
     * Toggle task status (AJAX)
     */
    public function toggleStatus(Request $request, $id)
    {
        $client = $this->client();
        $todo = Todo::findOrFail($id);

        // Check access
        if ($todo->user_id !== $client->id && $todo->assigned_to !== $client->id) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $newStatus = $request->input('status');
        $todo->status = $newStatus;

        if ($newStatus === 'completed') {
            $todo->completed_at = now();
        } else {
            $todo->completed_at = null;
        }

        $todo->save();

        return response()->json(['success' => true, 'status' => $todo->status]);
    }
}
