<?php

namespace Modules\Todo\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Todo\Models\Todo;

class TodoList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPriority = '';

    protected $listeners = ['todoCreated', 'todoUpdated', 'todoDeleted'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterPriority()
    {
        $this->resetPage();
    }

    public function getTodos()
    {
        $query = Todo::query();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPriority) {
            $query->where('priority', $this->filterPriority);
        }

        return $query->latest()->paginate(10);
    }

    public function openEditModal($id)
    {
        $this->dispatch('loadEditTodo', $id);
    }

    public function deleteTodo($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $title = $todo->title;
            $todo->delete();

            $this->dispatch('notify', ['type' => 'success', 'message' => "Todo '$title' deleted successfully"]);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to delete todo']);
        }
    }

    public function todoCreated()
    {
        $this->showCreateModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Todo created successfully']);
    }

    public function todoUpdated()
    {
        $this->showEditModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Todo updated successfully']);
    }

    public function todoDeleted()
    {
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Todo deleted successfully']);
    }

    public function render()
    {
        return view('todo::livewire.todo-list', [
            'todos' => $this->getTodos(),
            'statuses' => Todo::getStatuses(),
            'priorities' => Todo::getPriorities(),
        ]);
    }
}
