{{-- StudentSponsorship Module - Student Portal - My Form (Read-Only) --}}

<style>
    .student-form-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        color: white;
    }
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        color: white;
        border: 3px solid rgba(255,255,255,0.3);
        overflow: hidden;
        flex-shrink: 0;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-info h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 6px 0;
    }
    .profile-id {
        opacity: 0.9;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .profile-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .profile-badge {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .card-header {
        padding: 14px 20px;
        background: var(--body-bg);
        border-bottom: 1px solid var(--card-border);
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-header svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }
    .card-body {
        padding: 20px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        color: var(--text-primary);
        background: var(--body-bg);
    }
    .form-control:disabled {
        background: var(--body-bg);
        color: var(--text-secondary);
        cursor: not-allowed;
        opacity: 0.8;
    }
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }
    
    .state-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .state-badge.complete {
        background: #D1FAE5;
        color: #065F46;
    }
    .state-badge.inprogress {
        background: #FEF3C7;
        color: #92400E;
    }
    
    /* Report Cards Section */
    .rc-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 12px;
    }
    .rc-tab {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: transparent;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .rc-tab:hover { color: var(--text-primary); }
    .rc-tab.active {
        background: var(--primary);
        color: white;
    }
    .rc-tab .count {
        background: rgba(0,0,0,0.1);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
    }
    .rc-tab.active .count {
        background: rgba(255,255,255,0.2);
    }
    .rc-content { display: none; }
    .rc-content.active { display: block; }
    
    .report-cards-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .report-card-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 8px;
        transition: all 0.2s;
    }
    .report-card-item:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .report-card-item.pending {
        border-left: 3px solid #F59E0B;
    }
    .report-card-item.approved {
        border-left: 3px solid #10B981;
        background: linear-gradient(to right, rgba(16, 185, 129, 0.05), transparent);
    }
    .report-card-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .report-card-icon {
        width: 40px;
        height: 40px;
        background: #FEE2E2;
        color: #DC2626;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .report-card-icon.pending {
        background: #FEF3C7;
        color: #D97706;
    }
    .report-card-icon.approved {
        background: #D1FAE5;
        color: #059669;
    }
    .report-card-icon svg {
        width: 20px;
        height: 20px;
    }
    .report-card-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .report-card-meta {
        font-size: 12px;
        color: var(--text-muted);
    }
    .status-badge {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-badge.pending {
        background: #FEF3C7;
        color: #92400E;
    }
    .status-badge.approved {
        background: #D1FAE5;
        color: #065F46;
    }
    .report-card-actions {
        display: flex;
        gap: 8px;
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        background: var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .btn-icon svg {
        width: 16px;
        height: 16px;
    }
    
    /* Upload Section */
    .upload-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--card-border);
    }
    .upload-area {
        border: 2px dashed var(--card-border);
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background: var(--body-bg);
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-area:hover {
        border-color: var(--primary);
        background: var(--primary-light, #EEF2FF);
    }
    .upload-area svg {
        width: 40px;
        height: 40px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }
    .upload-area p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }
    .upload-area .upload-hint {
        font-size: 12px;
        margin-top: 6px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-secondary {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        color: var(--text-secondary);
    }
    
    .empty-state {
        text-align: center;
        padding: 30px;
        color: var(--text-muted);
    }
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }
        .profile-badges {
            justify-content: center;
        }
        .report-card-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .report-card-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="student-form-container">
    <div class="page-header mb-4">
        <h1 class="page-title">My Student Form</h1>
        <p style="color: var(--text-muted); margin-top: 4px; font-size: 14px;">
            View your information and manage report cards
        </p>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                @if($type === 'school')
                    @if($student->hasProfilePhoto())
                        <img src="{{ $student->profile_photo_url }}" alt="Photo">
                    @else
                        {{ strtoupper(substr($student->full_name ?? 'S', 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->full_name ?? '')[1] ?? '', 0, 1)) }}
                    @endif
                @else
                    @if($student->hasMedia('profile_photo'))
                        <img src="{{ $student->profile_photo_url }}" alt="Photo">
                    @else
                        {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->name ?? '')[1] ?? '', 0, 1)) }}
                    @endif
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $type === 'school' ? $student->full_name : $student->name }}</h1>
                <div class="profile-id">
                    @if($type === 'school')
                        {{ $student->school_internal_id }} • ID: {{ $student->school_student_id }}
                    @else
                        {{ $student->university_internal_id }} • REG: {{ $student->university_id }}
                    @endif
                </div>
                <div class="profile-badges">
                    <span class="profile-badge">
                        {{ $type === 'school' ? 'School Student' : 'University Student' }}
                    </span>
                    @if($student->current_state === 'complete')
                        <span class="profile-badge" style="background: rgba(16, 185, 129, 0.3);">✓ Complete</span>
                    @else
                        <span class="profile-badge" style="background: rgba(251, 191, 36, 0.3);">◐ In Progress</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Basic Information
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="{{ $type === 'school' ? $student->full_name : $student->name }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $student->email ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->phone : $student->contact_no) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->dob?->format('Y-m-d') : $student->university_student_dob?->format('Y-m-d')) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->age : $student->university_age) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" value="{{ $student->country_name ?: 'Not provided' }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Address
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" value="{{ $student->address ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" value="{{ $student->city ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Postal Code / ZIP</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->postal_code : $student->zip) ?: 'Not provided' }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- Education Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            {{ $type === 'school' ? 'School Information' : 'University Information' }}
        </div>
        <div class="card-body">
            <div class="form-grid">
                @if($type === 'school')
                    <div class="form-group">
                        <label class="form-label">School Name</label>
                        <input type="text" class="form-control" value="{{ $student->school_name_display ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">School Type</label>
                        <input type="text" class="form-control" value="{{ $student->school_type ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade</label>
                        <input type="text" class="form-control" value="{{ $student->grade ?: 'Not provided' }}" disabled>
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-label">University</label>
                        <input type="text" class="form-control" value="{{ $student->university?->name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <input type="text" class="form-control" value="{{ $student->program?->name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year of Study</label>
                        <input type="text" class="form-control" value="{{ $student->year_of_study_display ?: 'Not provided' }}" disabled>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sponsorship Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Sponsorship
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->sponsorship_start_date?->format('Y-m-d') : $student->university_sponsorship_start_date?->format('Y-m-d')) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->sponsorship_end_date?->format('Y-m-d') : $student->university_sponsorship_end_date?->format('Y-m-d')) ?: 'Not provided' }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Family Information
        </div>
        <div class="card-body">
            <div class="form-grid">
                @if($type === 'school')
                    <div class="form-group">
                        <label class="form-label">Father's Name</label>
                        <input type="text" class="form-control" value="{{ $student->father_name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" value="{{ $student->mother_name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guardian's Name</label>
                        <input type="text" class="form-control" value="{{ $student->guardian_name ?: 'Not provided' }}" disabled>
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-label">Father's Name</label>
                        <input type="text" class="form-control" value="{{ $student->university_father_name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" value="{{ $student->university_mother_name ?: 'Not provided' }}" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guardian's Name</label>
                        <input type="text" class="form-control" value="{{ $student->university_guardian_name ?: 'Not provided' }}" disabled>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bank Information -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Bank Information
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Bank Name</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->bank_name_display : $student->bank_name) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Account Number</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->bank_account_number : $student->university_bank_account_no) ?: 'Not provided' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Branch Info</label>
                    <input type="text" class="form-control" value="{{ ($type === 'school' ? $student->bank_branch_info : $student->university_bank_branch_info) ?: 'Not provided' }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards Section -->
    <div class="card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            My Report Cards
        </div>
        <div class="card-body">
            @php
                $pendingCards = $reportCards->where('status', 'pending');
                $approvedCards = $reportCards->where('status', 'approved');
            @endphp
            
            @if($reportCards->count() > 0)
                <!-- Tabs -->
                <div class="rc-tabs">
                    <button class="rc-tab active" onclick="showRcTab('all')">
                        📋 All <span class="count">{{ $reportCards->count() }}</span>
                    </button>
                    <button class="rc-tab" onclick="showRcTab('pending')">
                        ⏳ Pending <span class="count">{{ $pendingCards->count() }}</span>
                    </button>
                    <button class="rc-tab" onclick="showRcTab('approved')">
                        ✅ Approved <span class="count">{{ $approvedCards->count() }}</span>
                    </button>
                </div>
                
                <!-- All Report Cards -->
                <div class="rc-content active" id="rc-all">
                    <div class="report-cards-list">
                        @foreach($reportCards as $rc)
                            <div class="report-card-item {{ $rc->status ?? 'pending' }}">
                                <div class="report-card-info">
                                    <div class="report-card-icon {{ $rc->status ?? 'pending' }}">
                                        @if(($rc->status ?? 'pending') === 'approved')
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="report-card-name">
                                            {{ $rc->filename }}
                                            <span class="status-badge {{ $rc->status ?? 'pending' }}">
                                                {{ ($rc->status ?? 'pending') === 'approved' ? '✓ Approved' : '⏳ Pending Review' }}
                                            </span>
                                        </div>
                                        <div class="report-card-meta">
                                            @if($type === 'school')
                                                {{ str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $rc->term) }} • 
                                                Uploaded: {{ \Carbon\Carbon::parse($rc->upload_date)->format('M d, Y') }}
                                            @else
                                                {{ $rc->term_display ?? $rc->term }} • 
                                                Uploaded: {{ $rc->upload_date?->format('M d, Y') ?? 'N/A' }}
                                            @endif
                                            @if(($rc->status ?? 'pending') === 'approved' && $rc->approved_at)
                                                • Approved: {{ \Carbon\Carbon::parse($rc->approved_at)->format('M d, Y') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="report-card-actions">
                                    <a href="{{ route('client.student-portal.report-card.download', $rc->id) }}" class="btn-icon" title="Download">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Pending Report Cards -->
                <div class="rc-content" id="rc-pending">
                    @if($pendingCards->count() > 0)
                        <div class="report-cards-list">
                            @foreach($pendingCards as $rc)
                                <div class="report-card-item pending">
                                    <div class="report-card-info">
                                        <div class="report-card-icon pending">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="report-card-name">
                                                {{ $rc->filename }}
                                                <span class="status-badge pending">⏳ Pending Review</span>
                                            </div>
                                            <div class="report-card-meta">
                                                @if($type === 'school')
                                                    {{ str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $rc->term) }} • 
                                                    Uploaded: {{ \Carbon\Carbon::parse($rc->upload_date)->format('M d, Y') }}
                                                @else
                                                    {{ $rc->term_display ?? $rc->term }} • 
                                                    Uploaded: {{ $rc->upload_date?->format('M d, Y') ?? 'N/A' }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-card-actions">
                                        <a href="{{ route('client.student-portal.report-card.download', $rc->id) }}" class="btn-icon" title="Download">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding: 30px;">
                            <p style="color: var(--text-muted);">No pending report cards</p>
                        </div>
                    @endif
                </div>
                
                <!-- Approved Report Cards -->
                <div class="rc-content" id="rc-approved">
                    @if($approvedCards->count() > 0)
                        <div class="report-cards-list">
                            @foreach($approvedCards as $rc)
                                <div class="report-card-item approved">
                                    <div class="report-card-info">
                                        <div class="report-card-icon approved">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="report-card-name">
                                                {{ $rc->filename }}
                                                <span class="status-badge approved">✓ Approved</span>
                                            </div>
                                            <div class="report-card-meta">
                                                @if($type === 'school')
                                                    {{ str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $rc->term) }} • 
                                                    Uploaded: {{ \Carbon\Carbon::parse($rc->upload_date)->format('M d, Y') }}
                                                @else
                                                    {{ $rc->term_display ?? $rc->term }} • 
                                                    Uploaded: {{ $rc->upload_date?->format('M d, Y') ?? 'N/A' }}
                                                @endif
                                                @if($rc->approved_at)
                                                    • Approved: {{ \Carbon\Carbon::parse($rc->approved_at)->format('M d, Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-card-actions">
                                        <a href="{{ route('client.student-portal.report-card.download', $rc->id) }}" class="btn-icon" title="Download">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="padding: 30px;">
                            <p style="color: var(--text-muted);">No approved report cards yet</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>No report cards uploaded yet</p>
                </div>
            @endif

            <!-- Upload Section -->
            <div class="upload-section">
                <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: var(--text-primary);">Upload New Report Card</h4>
                <form id="upload-form" enctype="multipart/form-data">
                    @csrf
                    @if($type === 'school')
                        {{-- School Upload Form --}}
                        <div class="form-grid" style="margin-bottom: 16px;">
                            <div class="form-group">
                                <label class="form-label">Filename <span style="color: red;">*</span></label>
                                <input type="text" id="upload-filename" class="form-control" placeholder="Enter filename" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Term <span style="color: red;">*</span></label>
                                <select id="upload-term" class="form-control" required>
                                    <option value="">Select Term</option>
                                    <option value="Term1">Term 1</option>
                                    <option value="Term2">Term 2</option>
                                    <option value="Term3">Term 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Upload Date <span style="color: red;">*</span></label>
                                <input type="date" id="upload-date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    @else
                        {{-- University Upload Form --}}
                        <div class="form-grid" style="margin-bottom: 16px;">
                            <div class="form-group">
                                <label class="form-label">Filename <span style="color: red;">*</span></label>
                                <input type="text" id="upload-filename" class="form-control" placeholder="Enter filename" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Term <span style="color: red;">*</span></label>
                                <select id="upload-term" class="form-control" required>
                                    <option value="">Select Term</option>
                                    <option value="1Y1S">Year 1 - Semester 1</option>
                                    <option value="1Y2S">Year 1 - Semester 2</option>
                                    <option value="2Y1S">Year 2 - Semester 1</option>
                                    <option value="2Y2S">Year 2 - Semester 2</option>
                                    <option value="3Y1S">Year 3 - Semester 1</option>
                                    <option value="3Y2S">Year 3 - Semester 2</option>
                                    <option value="4Y1S">Year 4 - Semester 1</option>
                                    <option value="4Y2S">Year 4 - Semester 2</option>
                                    <option value="5Y1S">Year 5 - Semester 1</option>
                                    <option value="5Y2S">Year 5 - Semester 2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Semester End Year <span style="color: red;">*</span></label>
                                <select id="upload-semester-year" class="form-control" required>
                                    @for($y = date('Y'); $y >= 2015; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label">Report Card File (PDF, Image) <span style="color: red;">*</span></label>
                        <input type="file" id="file-input" accept=".pdf,.jpg,.jpeg,.png" class="form-control" required onchange="handleFileSelect(this)">
                    </div>
                    <div id="selected-file" style="display: none; margin-bottom: 12px; padding: 12px; background: var(--body-bg); border-radius: 8px;">
                        <span id="file-name" style="font-weight: 600;"></span>
                        <button type="button" onclick="clearFile()" style="margin-left: 12px; color: var(--danger); background: none; border: none; cursor: pointer;">Remove</button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" id="upload-btn" disabled>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Report Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedFile = null;
const studentType = '{{ $type }}';

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a PDF or image file');
            input.value = '';
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            input.value = '';
            return;
        }
        
        selectedFile = file;
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('selected-file').style.display = 'block';
        document.getElementById('upload-btn').disabled = false;
    }
}

function clearFile() {
    selectedFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('selected-file').style.display = 'none';
    document.getElementById('upload-btn').disabled = true;
}

document.getElementById('upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const filename = document.getElementById('upload-filename').value;
    const term = document.getElementById('upload-term').value;
    
    if (!filename) {
        alert('Please enter a filename');
        return;
    }
    
    if (!term) {
        alert('Please select a term');
        return;
    }
    
    const fileInput = document.getElementById('file-input');
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Please select a file');
        return;
    }
    
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('filename', filename);
    formData.append('term', term);
    formData.append('_token', '{{ csrf_token() }}');
    
    if (studentType === 'school') {
        const uploadDate = document.getElementById('upload-date').value;
        if (!uploadDate) {
            alert('Please select upload date');
            return;
        }
        formData.append('upload_date', uploadDate);
    } else {
        const semesterYear = document.getElementById('upload-semester-year').value;
        if (!semesterYear) {
            alert('Please select semester end year');
            return;
        }
        formData.append('semester_year', semesterYear);
    }
    
    const btn = document.getElementById('upload-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Uploading...';
    
    fetch('{{ route("client.student-portal.report-card.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Report card uploaded successfully!');
            location.reload();
        } else {
            alert(data.message || 'Upload failed');
            btn.disabled = false;
            btn.innerHTML = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> Upload Report Card';
        }
    })
    .catch(err => {
        alert('Error uploading file');
        btn.disabled = false;
        btn.innerHTML = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> Upload Report Card';
    });
});

// Report Cards Tab Switching
function showRcTab(tab) {
    // Remove active from all tabs and content
    document.querySelectorAll('.rc-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.rc-content').forEach(c => c.classList.remove('active'));
    
    // Add active to clicked tab
    event.target.closest('.rc-tab').classList.add('active');
    
    // Show corresponding content
    document.getElementById('rc-' + tab).classList.add('active');
}
</script>
