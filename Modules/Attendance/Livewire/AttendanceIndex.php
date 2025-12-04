<?php

namespace Modules\Attendance\Livewire;

use App\Livewire\Admin\AdminComponent;
use Modules\Attendance\Models\Attendance;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class AttendanceIndex extends AdminComponent
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $search = '';
    public $user_id = '';
    public $status = '';
    public $from_date = '';
    public $to_date = '';
    public $sortBy = 'attendance_date';
    public $sortDirection = 'desc';
    
    // Override parent's perPage with correct type
    protected int $perPage = 15;
    protected string $paginationTheme = 'tailwind';

    public $showDeleteModal = false;
    public $deleteId = null;

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    protected $rules = [
        'search' => 'nullable|string|max:255',
        'user_id' => 'nullable|exists:users,id',
        'status' => 'nullable|in:' . 'present,absent,late,half-day,on-leave',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date|after_or_equal:from_date',
        'perPage' => 'integer|min:1|max:100',
    ];

    /*
    |--------------------------------------------------------------------------
    | Initialization
    |--------------------------------------------------------------------------
    */

    protected function init(): void
    {
        // Load initial data
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    public function getAttendancesProperty()
    {
        $query = Attendance::with('user');

        // Search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by user
        if ($this->user_id) {
            $query->byUser($this->user_id);
        }

        // Filter by status
        if ($this->status) {
            $query->byStatus($this->status);
        }

        // Filter by date range
        if ($this->from_date && $this->to_date) {
            $query->byDateRange($this->from_date, $this->to_date);
        } else {
            if ($this->from_date) {
                $query->whereDate('attendance_date', '>=', $this->from_date);
            }
            if ($this->to_date) {
                $query->whereDate('attendance_date', '<=', $this->to_date);
            }
        }

        // Sort
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function getStatusesProperty()
    {
        return Attendance::getStatuses();
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function resetFilters(): void
    {
        $this->search = '';
        $this->user_id = '';
        $this->status = '';
        $this->from_date = '';
        $this->to_date = '';
        $this->resetPagination();
        $this->toastSuccess('Filters reset');
    }

    public function changeSort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPagination();
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        try {
            $attendance = Attendance::findOrFail($this->deleteId);
            $attendance->delete();
            
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->resetPagination();
            
            $this->logAction('delete_attendance', ['attendance_id' => $this->deleteId]);
            $this->toastSuccess('Attendance record deleted successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to delete attendance', $e);
            $this->toastError('Failed to delete record: ' . $e->getMessage());
        }
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function export(): void
    {
        // Placeholder for export functionality
        $this->toastInfo('Export functionality coming soon');
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('attendance::livewire.attendance-index')
            ->with([
                'attendances' => $this->attendances,
                'users' => $this->users,
                'statuses' => $this->statuses,
            ]);
    }
}
