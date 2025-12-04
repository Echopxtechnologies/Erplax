<?php

namespace Modules\Todo\Http\Livewire;

use Livewire\Component;
use Modules\Todo\Models\Todo;

class CreateTodo extends Component
{
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

    public function save()
    {
        $validated = $this->validate();

        try {
            Todo::create($validated);
            $this->resetForm();
            $this->dispatch('todoCreated');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Todo created successfully']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to create todo']);
        }
    }

    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->status = 'pending';
        $this->priority = 'medium';
        $this->due_date = '';
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('todo::livewire.create-todo', [
            'statuses' => Todo::getStatuses(),
            'priorities' => Todo::getPriorities(),
        ]);
    }
}
