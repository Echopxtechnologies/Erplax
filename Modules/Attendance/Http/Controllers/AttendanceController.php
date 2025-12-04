<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Attendance\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends AdminController
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();
        
        $query = Attendance::with('user');
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->byUser($request->user_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date && $request->has('to_date') && $request->to_date) {
            $query->byDateRange($request->from_date, $request->to_date);
        } else {
            if ($request->has('from_date') && $request->from_date) {
                $query->whereDate('attendance_date', '>=', $request->from_date);
            }
            if ($request->has('to_date') && $request->to_date) {
                $query->whereDate('attendance_date', '<=', $request->to_date);
            }
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(15);
        $users = User::orderBy('name')->get();
        $statuses = Attendance::getStatuses();
        
        return view('attendance::index', compact('attendances', 'users', 'statuses'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $users = User::orderBy('name')->get();
        $statuses = Attendance::getStatuses();
        
        return view('attendance::create', compact('users', 'statuses'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        try {
            $validated = $this->validateRequest($request, [
                'user_id' => 'required|exists:users,id',
                'attendance_date' => 'required|date',
                'check_in_time' => 'nullable|datetime',
                'check_out_time' => 'nullable|datetime',
                'status' => 'required|in:' . implode(',', array_keys(Attendance::getStatuses())),
                'notes' => 'nullable|string|max:1000',
            ]);

            $attendance = Attendance::create($validated);
            
            $this->logAction('create', [
                'entity' => 'Attendance',
                'attendance_id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'status' => $attendance->status,
            ]);

            return $this->redirectWithSuccess('admin.attendance.index', 'Attendance record created successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to create Attendance', $e);
            return $this->redirectWithError('admin.attendance.create', 'Failed to create attendance record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified attendance record.
     */
    public function show($id)
    {
        $this->authorizeAdmin();
        $attendance = Attendance::with('user')->findOrFail($id);
        
        return view('attendance::show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit($id)
    {
        $this->authorizeAdmin();
        $attendance = Attendance::findOrFail($id);
        $users = User::orderBy('name')->get();
        $statuses = Attendance::getStatuses();
        
        return view('attendance::edit', compact('attendance', 'users', 'statuses'));
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        try {
            $attendance = Attendance::findOrFail($id);

            $validated = $this->validateRequest($request, [
                'user_id' => 'required|exists:users,id',
                'attendance_date' => 'required|date',
                'check_in_time' => 'nullable|datetime',
                'check_out_time' => 'nullable|datetime',
                'status' => 'required|in:' . implode(',', array_keys(Attendance::getStatuses())),
                'notes' => 'nullable|string|max:1000',
            ]);

            $attendance->update($validated);
            
            $this->logAction('update', [
                'entity' => 'Attendance',
                'attendance_id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'status' => $attendance->status,
            ]);

            return $this->redirectWithSuccess('admin.attendance.index', 'Attendance record updated successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to update Attendance', $e);
            return $this->redirectWithError('admin.attendance.edit', 'Failed to update attendance record: ' . $e->getMessage(), ['id' => $id]);
        }
    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        try {
            $attendance = Attendance::findOrFail($id);
            $userId = $attendance->user_id;
            $status = $attendance->status;
            
            $attendance->delete();
            
            $this->logAction('delete', [
                'entity' => 'Attendance',
                'user_id' => $userId,
                'status' => $status,
            ]);

            return $this->redirectWithSuccess('admin.attendance.index', 'Attendance record deleted successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to delete Attendance', $e);
            return $this->redirectWithError('admin.attendance.index', 'Failed to delete attendance record: ' . $e->getMessage());
        }
    }
}
