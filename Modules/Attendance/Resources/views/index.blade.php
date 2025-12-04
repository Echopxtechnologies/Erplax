<div>
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h1 class="page-title">Attendance Management</h1>
            <p style="color: var(--text-secondary); font-size: var(--font-sm);">Manage employee attendance records</p>
        </div>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            Add Attendance
        </a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: var(--space-lg);">
        <div class="card-body" style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" style="display: block; margin-bottom: 4px;">Employee</label>
                <select name="user_id" onchange="location.href='?user_id=' + this.value" class="form-control">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width: 150px;">
                <label class="form-label" style="display: block; margin-bottom: 4px;">Status</label>
                <select name="status" onchange="location.href='?status=' + this.value" class="form-control">
                    <option value="">All Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Employee</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Date</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Check-In</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Check-Out</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Status</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 12px 16px;">
                                <div>
                                    <div style="font-weight: 500; color: var(--text-primary);">{{ $attendance->user->name }}</div>
                                    <div style="font-size: var(--font-xs); color: var(--text-muted);">{{ $attendance->user->email }}</div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; font-size: var(--font-sm); color: var(--text-secondary);">
                                {{ $attendance->attendance_date->format('M d, Y') }}
                            </td>
                            <td style="padding: 12px 16px; font-size: var(--font-sm); color: var(--text-secondary);">
                                @if($attendance->check_in_time)
                                    <span>{{ $attendance->check_in_time->format('h:i A') }}</span>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px; font-size: var(--font-sm); color: var(--text-secondary);">
                                @if($attendance->check_out_time)
                                    <span>{{ $attendance->check_out_time->format('h:i A') }}</span>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 16px;">
                                <span class="badge badge-{{ $attendance->status_badge_class }}" 
                                      style="background-color: {{ $attendance->status_badge_color }};">
                                    <span class="badge-dot"></span>
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn btn-xs btn-light">Edit</a>
                                    <form method="POST" action="{{ route('admin.attendance.destroy', $attendance->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-muted);">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 32px; height: 32px; margin: 0 auto 8px;">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>No attendance records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div style="padding: var(--space-lg); border-top: 1px solid var(--card-border);">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
