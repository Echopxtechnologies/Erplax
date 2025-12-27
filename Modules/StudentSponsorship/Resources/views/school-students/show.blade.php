<style>
    .show-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .header-actions { display: flex; gap: 12px; }
    .btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
    .btn-back { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-secondary); }
    .btn-edit { background: var(--primary); color: #fff; }
    .btn-delete { background: var(--danger); color: #fff; border: none; cursor: pointer; }
    
    .student-header { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; margin-bottom: 24px; display: flex; gap: 24px; align-items: center; }
    .student-photo { width: 120px; height: 120px; border-radius: 12px; overflow: hidden; background: var(--body-bg); flex-shrink: 0; }
    .student-photo img { width: 100%; height: 100%; object-fit: cover; }
    .student-photo svg { width: 100%; height: 100%; padding: 30px; color: var(--text-muted); }
    .student-info h2 { font-size: 24px; font-weight: 700; margin: 0 0 8px 0; }
    .student-id { display: inline-block; background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 12px; }
    .status-badge.active { background: var(--success-light); color: var(--success); }
    .status-badge.inactive { background: var(--danger-light); color: var(--danger); }
    
    .info-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .info-card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
    .info-card-header svg { width: 20px; height: 20px; color: var(--primary); }
    .info-card-body { padding: 20px; }
    
    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .info-item { }
    .info-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-value { font-size: 15px; color: var(--text-primary); }
    
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: repeat(2, 1fr); }
        .student-header { flex-direction: column; text-align: center; }
    }
</style>

<div class="show-page">
    <div class="page-header">
        <h1>Student Details</h1>
        <div class="header-actions">
            <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn btn-back">← Back</a>
            <a href="{{ route('admin.studentsponsorship.school-students.edit', $student->id) }}" class="btn btn-edit">Edit</a>
            <form action="{{ route('admin.studentsponsorship.school-students.destroy', $student->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this student?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-delete">Delete</button>
            </form>
        </div>
    </div>

    <!-- Student Header -->
    <div class="student-header">
        <div class="student-photo">
            @if($student->hasProfilePhoto())
                <img src="{{ $student->profile_photo_url }}" alt="Photo">
            @else
                <svg fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            @endif
        </div>
        <div class="student-info">
            <h2>{{ $student->full_name }}</h2>
            <span class="student-id">{{ $student->school_internal_id }}</span>
            <span class="status-badge {{ $student->status ? 'active' : 'inactive' }}">{{ $student->status ? 'Active' : 'Inactive' }}</span>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Basic Information
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Email</div><div class="info-value">{{ $student->email ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Phone</div><div class="info-value">{{ $student->phone ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Date of Birth</div><div class="info-value">{{ $student->dob?->format('M d, Y') ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Age</div><div class="info-value">{{ $student->age ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Country</div><div class="info-value">{{ $student->country_name }}</div></div>
                <div class="info-item"><div class="info-label">City</div><div class="info-value">{{ $student->city ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Postal Code</div><div class="info-value">{{ $student->postal_code ?? '-' }}</div></div>
                <div class="info-item" style="grid-column: span 2;"><div class="info-label">Address</div><div class="info-value">{{ $student->address ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- School Information -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
            School Information
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Grade</div><div class="info-value">{{ $student->grade ?? '-' }}</div></div>
                <div class="info-item">
                    <div class="info-label">Current State</div>
                    <div class="info-value">
                        @if($student->current_state === 'complete')
                            <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Complete</span>
                        @else
                            <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">In Progress</span>
                        @endif
                    </div>
                </div>
                <div class="info-item"><div class="info-label">School Type</div><div class="info-value">{{ $student->school_type ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">School Name</div><div class="info-value">{{ $student->school_name_display }}</div></div>
                @if($student->grade_mismatch_reason)
                <div class="info-item" style="grid-column: span 2;">
                    <div class="info-label">Grade Mismatch Reason</div>
                    <div class="info-value" style="color: #B45309;">{{ $student->grade_mismatch_reason }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sponsorship -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            Sponsorship
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Start Date</div><div class="info-value">{{ $student->sponsorship_start_date?->format('M d, Y') ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">End Date</div><div class="info-value">{{ $student->sponsorship_end_date?->format('M d, Y') ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Introduced By</div><div class="info-value">{{ $student->introduced_by ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Introducer Phone</div><div class="info-value">{{ $student->introducer_phone ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- Bank Information -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Bank Information
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Bank Name</div><div class="info-value">{{ $student->bank_name_display }}</div></div>
                <div class="info-item"><div class="info-label">Account Number</div><div class="info-value">{{ $student->bank_account_number ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Branch Number</div><div class="info-value">{{ $student->bank_branch_number ?? '-' }}</div></div>
                <div class="info-item" style="grid-column: span 3;"><div class="info-label">Branch Info</div><div class="info-value">{{ $student->bank_branch_info ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- Family Information -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Family Information
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Father's Name</div><div class="info-value">{{ $student->father_name ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Father's Income</div><div class="info-value">{{ $student->father_income ? number_format($student->father_income, 2) : '-' }}</div></div>
                <div class="info-item"><div class="info-label">Mother's Name</div><div class="info-value">{{ $student->mother_name ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Mother's Income</div><div class="info-value">{{ $student->mother_income ? number_format($student->mother_income, 2) : '-' }}</div></div>
                <div class="info-item"><div class="info-label">Guardian's Name</div><div class="info-value">{{ $student->guardian_name ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">Guardian's Income</div><div class="info-value">{{ $student->guardian_income ? number_format($student->guardian_income, 2) : '-' }}</div></div>
                <div class="info-item" style="grid-column: span 3;"><div class="info-label">Background</div><div class="info-value">{{ $student->background_info ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- Comments -->
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Comments
        </div>
        <div class="info-card-body">
            <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="info-item"><div class="info-label">Internal Comment (Staff Only)</div><div class="info-value">{{ $student->internal_comment ?? '-' }}</div></div>
                <div class="info-item"><div class="info-label">External Comment</div><div class="info-value">{{ $student->external_comment ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    @if($student->report_cards->count() > 0)
    <div class="info-card">
        <div class="info-card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Report Cards ({{ $student->report_cards->count() }})
        </div>
        <div class="info-card-body">
            <div style="display:flex;flex-wrap:wrap;gap:12px;">
                @foreach($student->report_cards as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" style="padding:12px 16px;background:var(--primary-light);color:var(--primary);border-radius:8px;text-decoration:none;font-weight:600;">
                        {{ $media->name }} ({{ $media->created_at->format('M Y') }})
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    <div class="info-card">
        <div class="info-card-body">
            <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="info-item"><div class="info-label">Created At</div><div class="info-value">{{ $student->created_at->format('M d, Y H:i') }}</div></div>
                <div class="info-item"><div class="info-label">Last Updated</div><div class="info-value">{{ $student->updated_at->format('M d, Y H:i') }}</div></div>
            </div>
        </div>
    </div>
</div>
