<?php

namespace Modules\Todo\Http\Livewire;

use Livewire\Component;
use Modules\Todo\Models\Todo;

class EditTodo extends Component
{
    public $todoId = null;
    public $title = '';
    public $description = '';
    public $status = 'pending';
    public $priority = 'medium';
    public $due_date = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'priority' => 'required|in:low,medium,high,urgent',
        'due_date' => 'nullable|date',
    ];

    protected $listeners = ['loadEditTodo'];

    public function loadEditTodo($id)
    {
        $todo = Todo::findOrFail($id);
        $this->todoId = $todo->id;
        $this->title = $todo->title;
        $this->description = $todo->description;
        $this->status = $todo->status;
        $this->priority = $todo->priority;
        $this->due_date = $todo->due_date?->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function update()
    {
        $validated = $this->validate();

        try {
            $todo = Todo::findOrFail($this->todoId);
            $todo->update($validated);
            $this->dispatch('todoUpdated');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Todo updated successfully']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to update todo']);
        }
    }

    public function render()
    {
        return view('todo::livewire.edit-todo', [
            'statuses' => Todo::getStatuses(),
            'priorities' => Todo::getPriorities(),
        ]);
    }
}
