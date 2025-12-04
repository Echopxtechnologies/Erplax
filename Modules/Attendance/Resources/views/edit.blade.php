<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Edit Attendance Record</h1>
        <p style="color: var(--text-secondary); font-size: var(--font-sm); margin: 4px 0 0;">Update attendance information</p>
    </x-slot>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <!-- Back Button -->
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-light" style="width: fit-content;">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Records
        </a>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.attendance.update', $attendance->id) }}" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            @method('PUT')
            <div class="card">
                <div style="padding: 16px; border-bottom: 1px solid var(--card-border);">
                    <h3 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0;">Attendance Details</h3>
                </div>
                <div style="padding: 24px;">
                    <div style="display: grid; gap: 20px;">
                        <!-- Employee Selection -->
                        <div>
                            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">
                                Employee <span style="color: var(--danger);">*</span>
                            </label>
                            <select name="user_id" class="form-control" required style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px;">
                                <option value="">Select an employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id', $attendance->user_id) == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                        </div>

                        <!-- Attendance Date -->
                        <div>
                            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">
                                Attendance Date <span style="color: var(--danger);">*</span>
                            </label>
                            <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', $attendance->attendance_date->format('Y-m-d')) }}" required style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px;">
                            @error('attendance_date') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                        </div>

                        <!-- Time Row -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Check-In Time</label>
                                <input type="datetime-local" name="check_in_time" class="form-control" value="{{ old('check_in_time', $attendance->check_in_time?->format('Y-m-d\TH:i')) }}" style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px;">
                                @error('check_in_time') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Check-Out Time</label>
                                <input type="datetime-local" name="check_out_time" class="form-control" value="{{ old('check_out_time', $attendance->check_out_time?->format('Y-m-d\TH:i')) }}" style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px;">
                                @error('check_out_time') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">
                                Status <span style="color: var(--danger);">*</span>
                            </label>
                            <select name="status" class="form-control" required style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px;">
                                <option value="">Select status</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" @selected(old('status', $attendance->status) == $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Notes</label>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Add any notes or remarks..." style="padding: 10px; border: 1px solid var(--input-border); border-radius: 6px; resize: vertical;">{{ old('notes', $attendance->notes) }}</textarea>
                            @error('notes') <span style="color: var(--danger); font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div style="padding: 16px 24px; border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Attendance
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
