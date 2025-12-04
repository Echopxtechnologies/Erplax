<?php

namespace Modules\Attendance\Livewire;

use App\Livewire\Admin\AdminComponent;
use Modules\Attendance\Models\Attendance;
use App\Models\User;

class AttendanceForm extends AdminComponent
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $attendanceId = null;
    public $isEdit = false;

    public $user_id = '';
    public $attendance_date = '';
    public $check_in_time = '';
    public $check_out_time = '';
    public $status = Attendance::STATUS_PRESENT;
    public $notes = '';

    protected int $perPage = 15;
    protected string $paginationTheme = 'tailwind';
    public $isLoading = false;

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'attendance_date' => 'required|date',
        'check_in_time' => 'nullable|date_format:Y-m-d\TH:i',
        'check_out_time' => 'nullable|date_format:Y-m-d\TH:i',
        'status' => 'required|in:present,absent,late,half-day,on-leave',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'user_id.required' => 'Please select an employee',
        'attendance_date.required' => 'Please select an attendance date',
        'status.required' => 'Please select a status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Initialization
    |--------------------------------------------------------------------------
    */

    protected function init(): void
    {
        if ($this->attendanceId) {
            $this->loadAttendance();
        } else {
            $this->attendance_date = now()->format('Y-m-d');
            $this->status = Attendance::STATUS_PRESENT;
        }
    }

    public function mount($id = null)
    {
        $this->attendanceId = $id;
        $this->isEdit = (bool) $id;
        parent::mount();
    }

    protected function loadAttendance(): void
    {
        $attendance = Attendance::findOrFail($this->attendanceId);

        $this->user_id = $attendance->user_id;
        $this->attendance_date = $attendance->attendance_date->format('Y-m-d');
        $this->check_in_time = $attendance->check_in_time?->format('Y-m-d\TH:i') ?? '';
        $this->check_out_time = $attendance->check_out_time?->format('Y-m-d\TH:i') ?? '';
        $this->status = $attendance->status;
        $this->notes = $attendance->notes ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

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

    public function save(): void
    {
        $this->validate();

        try {
            $this->isLoading = true;

            $data = [
                'user_id' => $this->user_id,
                'attendance_date' => $this->attendance_date,
                'check_in_time' => $this->check_in_time ?: null,
                'check_out_time' => $this->check_out_time ?: null,
                'status' => $this->status,
                'notes' => $this->notes ?: null,
            ];

            if ($this->isEdit) {
                $attendance = Attendance::findOrFail($this->attendanceId);
                $attendance->update($data);
                $this->logAction('update_attendance', [
                    'attendance_id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'status' => $attendance->status,
                ]);
                $this->toastSuccess('Attendance record updated successfully');
            } else {
                $attendance = Attendance::create($data);
                $this->logAction('create_attendance', [
                    'attendance_id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'status' => $attendance->status,
                ]);
                $this->toastSuccess('Attendance record created successfully');
            }

            $this->redirect(route('admin.attendance.index'));
        } catch (\Exception $e) {
            $this->logError('Failed to save attendance', $e);
            $this->toastError('Failed to save: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.attendance.index'));
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('attendance::livewire.attendance-form')
            ->with([
                'users' => $this->users,
                'statuses' => $this->statuses,
                'pageTitle' => $this->isEdit ? 'Edit Attendance Record' : 'Create Attendance Record',
            ]);
    }
}
