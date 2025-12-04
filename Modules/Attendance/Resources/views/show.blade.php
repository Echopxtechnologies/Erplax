<div>
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h1 class="page-title">Attendance Record</h1>
            <p style="color: var(--text-secondary); font-size: var(--font-sm);">View attendance details</p>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-light">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Details Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $attendance->user->name }}</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl);">
                <!-- Left Column -->
                <div>
                    <div style="margin-bottom: var(--space-lg);">
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Employee</span>
                        <div style="font-size: var(--font-sm); color: var(--text-primary);">{{ $attendance->user->name }}</div>
                        <div style="font-size: var(--font-xs); color: var(--text-muted);">{{ $attendance->user->email }}</div>
                    </div>

                    <div style="margin-bottom: var(--space-lg);">
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Date</span>
                        <div style="font-size: var(--font-sm); color: var(--text-primary);">{{ $attendance->attendance_date->format('F d, Y') }}</div>
                    </div>

                    <div>
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Status</span>
                        <div>
                            <span class="badge badge-{{ $attendance->status_color }}">
                                <span class="badge-dot"></span>
                                {{ ucfirst(str_replace('-', ' ', $attendance->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div style="margin-bottom: var(--space-lg);">
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Check-In Time</span>
                        <div style="font-size: var(--font-sm); color: var(--text-primary);">
                            @if($attendance->check_in_time)
                                {{ $attendance->check_in_time->format('h:i A') }}
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-bottom: var(--space-lg);">
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Check-Out Time</span>
                        <div style="font-size: var(--font-sm); color: var(--text-primary);">
                            @if($attendance->check_out_time)
                                {{ $attendance->check_out_time->format('h:i A') }}
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Created</span>
                        <div style="font-size: var(--font-sm); color: var(--text-primary);">{{ $attendance->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            @if($attendance->notes)
                <div style="margin-top: var(--space-xl); padding-top: var(--space-xl); border-top: 1px solid var(--card-border);">
                    <span style="display: block; font-size: var(--font-xs); font-weight: 600; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">Notes</span>
                    <div style="font-size: var(--font-sm); color: var(--text-primary); line-height: 1.6;">{{ $attendance->notes }}</div>
                </div>
            @endif
        </div>
        <div style="padding: var(--space-lg); border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: var(--space-sm);">
            <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form method="POST" action="{{ route('admin.attendance.destroy', $attendance->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
