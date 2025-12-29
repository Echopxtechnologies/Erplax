<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        color: white;
    }
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: white;
        border: 4px solid rgba(255,255,255,0.3);
        overflow: hidden;
        flex-shrink: 0;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-info h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }
    .profile-id {
        opacity: 0.9;
        font-size: 14px;
        margin-bottom: 12px;
    }
    .profile-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .profile-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .profile-badge.active {
        background: rgba(16, 185, 129, 0.3);
    }
    .profile-badge.inactive {
        background: rgba(239, 68, 68, 0.3);
    }
    
    .card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .card-header {
        padding: 16px 20px;
        background: var(--body-bg);
        border-bottom: 1px solid var(--card-border);
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-header i {
        color: var(--primary);
    }
    .card-body {
        padding: 20px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 15px;
        color: var(--text-primary);
    }
    .info-value a {
        color: var(--primary);
        text-decoration: none;
    }
    .info-value a:hover {
        text-decoration: underline;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
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
    
    .state-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
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
    
    .report-card-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }
    .report-card-item:hover {
        opacity: 0.9;
    }
    
    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }
        .profile-badges {
            justify-content: center;
        }
        .action-buttons {
            flex-wrap: wrap;
        }
    }
</style>

<div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('admin.studentsponsorship.university-students.edit', $student->hash_id) }}" class="btn btn-primary">
            Edit
        </a>
        <a href="{{ route('admin.studentsponsorship.university-students.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                @if($student->hasMedia('profile_photo'))
                    <img src="{{ $student->profile_photo_url }}" alt="Photo">
                @else
                    {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->name ?? '')[1] ?? '', 0, 1)) }}
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $student->name }}</h1>
                <div class="profile-id">Internal ID: {{ $student->university_id ?? 'N/A' }}</div>
                <div class="profile-badges">
                    <span class="profile-badge {{ $student->active ? 'active' : 'inactive' }}">
                        {{ $student->active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($student->university_year_of_study)
                    <span class="profile-badge">
                        {{ $student->year_of_study_display }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-address-book me-2"></i>Contact Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        @if($student->email)
                            <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                        @else
                            Not provided
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $student->contact_no ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address</span>
                    <span class="info-value">{{ $student->address ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">City</span>
                    <span class="info-value">{{ $student->city ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Zip Code</span>
                    <span class="info-value">{{ $student->zip ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Country</span>
                    <span class="info-value">{{ $student->country_name ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-user me-2"></i>Personal Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value">{{ $student->university_student_dob?->format('M d, Y') ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age</span>
                    <span class="info-value">{{ $student->university_age ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Current State</span>
                    <span class="info-value">
                        @if($student->current_state === 'complete')
                            <span class="state-badge complete">✓ Complete</span>
                        @else
                            <span class="state-badge inprogress">◐ In Progress</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- University Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-2"></i>University Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">University</span>
                    <span class="info-value">{{ $student->university?->name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Program</span>
                    <span class="info-value">{{ $student->program?->name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Year of Study</span>
                    <span class="info-value">{{ $student->year_of_study_display ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsorship Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-heart me-2"></i>Sponsorship Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Sponsorship Start</span>
                    <span class="info-value">{{ $student->university_sponsorship_start_date?->format('M d, Y') ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sponsorship End</span>
                    <span class="info-value">{{ $student->university_sponsorship_end_date?->format('M d, Y') ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Introduced By</span>
                    <span class="info-value">{{ $student->university_introducedby ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Introducer Phone</span>
                    <span class="info-value">{{ $student->university_introducedph ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Banking Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-university me-2"></i>Banking Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Bank</span>
                    <span class="info-value">{{ $student->bank_name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Number</span>
                    <span class="info-value">{{ $student->university_bank_account_no ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Branch Info</span>
                    <span class="info-value">{{ $student->university_bank_branch_info ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Branch Number</span>
                    <span class="info-value">{{ $student->university_bank_branch_number ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Information -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-users me-2"></i>Family Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Father's Name</span>
                    <span class="info-value">{{ $student->university_father_name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Father's Income</span>
                    <span class="info-value">{{ $student->university_father_income ? number_format($student->university_father_income, 2) : 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother's Name</span>
                    <span class="info-value">{{ $student->university_mother_name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother's Income</span>
                    <span class="info-value">{{ $student->university_mother_income ? number_format($student->university_mother_income, 2) : 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Guardian's Name</span>
                    <span class="info-value">{{ $student->university_guardian_name ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Guardian's Income</span>
                    <span class="info-value">{{ $student->university_guardian_income ? number_format($student->university_guardian_income, 2) : 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Background & Comments -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-comment-alt me-2"></i>Background & Comments
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item" style="grid-column: span 2;">
                    <span class="info-label">Background Information</span>
                    <span class="info-value">{{ $student->background_info ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Internal Comment</span>
                    <span class="info-value">{{ $student->internal_comment ?: 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">External Comment</span>
                    <span class="info-value">{{ $student->external_comment ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="card info-card">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;margin-right:8px;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Report Cards ({{ $student->reportCards->count() }})
        </div>
        <div class="card-body">
            @if($student->reportCards->count() > 0)
                <div class="report-cards-list" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                    @foreach($student->reportCards as $card)
                        <div class="report-card-row" id="report-card-{{ $card->id }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: #FEE2E2; color: #DC2626; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg fill="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zm-3 9h4v2h-4v-2zm0 3h4v2h-4v-2zm-2-3h1v2H8v-2zm0 3h1v2H8v-2z"/></svg>
                                </div>
                                <div>
                                    <div style="font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                        {{ $card->filename }}
                                        @php $status = $card->status ?? 'pending'; @endphp
                                        @if($status === 'pending')
                                            <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Pending</span>
                                        @elseif($status === 'approved')
                                            <span style="background: #D1FAE5; color: #065F46; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Approved</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $card->term_display }} • {{ $card->upload_date?->format('M d, Y') ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                @if($status === 'pending')
                                    <button type="button" onclick="approveReportCard({{ $card->id }})" 
                                            style="width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; background: #D1FAE5; color: #065F46; display: flex; align-items: center; justify-content: center;" title="Approve">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button type="button" onclick="rejectReportCard({{ $card->id }})" 
                                            style="width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center;" title="Reject & Delete">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                @endif
                                <a href="{{ route('admin.studentsponsorship.university-students.report-card.download', $card->id) }}" 
                                   style="width: 32px; height: 32px; border-radius: 6px; background: #DBEAFE; color: #1E40AF; display: flex; align-items: center; justify-content: center; text-decoration: none;" title="Download">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <button type="button" onclick="deleteReportCard({{ $card->id }})" 
                                        style="width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center;" title="Delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 20px 0;">No report cards uploaded yet.</p>
            @endif

            <!-- Upload Section -->
            <div style="padding-top: 16px; border-top: 1px solid var(--card-border);">
                <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Upload New Report Card</h4>
                <form id="report-card-upload-form" enctype="multipart/form-data">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; align-items: end;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted);">FILENAME <span style="color: red;">*</span></label>
                            <input type="text" id="rc-filename" placeholder="Enter filename" required style="padding: 10px; border: 1px solid var(--card-border); border-radius: 6px; width: 100%;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted);">TERM <span style="color: red;">*</span></label>
                            <select id="rc-term" class="form-control" required style="padding: 10px; border: 1px solid var(--card-border); border-radius: 6px; width: 100%;">
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
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted);">SEMESTER END YEAR <span style="color: red;">*</span></label>
                            <select id="rc-semester-year" class="form-control" required style="padding: 10px; border: 1px solid var(--card-border); border-radius: 6px; width: 100%;">
                                @for($y = date('Y'); $y >= 2015; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted);">FILE (PDF, Image) <span style="color: red;">*</span></label>
                            <input type="file" id="rc-file" accept=".pdf,.jpg,.jpeg,.png" required style="padding: 8px; border: 1px solid var(--card-border); border-radius: 6px; width: 100%;">
                        </div>
                    </div>
                    <div style="margin-top: 12px;">
                        <button type="submit" class="btn btn-primary" id="rc-upload-btn">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Student Portal Access -->
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-key me-2"></i>Student Portal Access
        </div>
        <div class="card-body" id="portal-access-section">
            <div id="portal-loading" style="text-align: center; padding: 20px;">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
            <div id="portal-content" style="display: none;"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPortalStatus();
});

function loadPortalStatus() {
    fetch('{{ route("admin.studentsponsorship.university-students.portal-status", $student->hash_id) }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('portal-loading').style.display = 'none';
            document.getElementById('portal-content').style.display = 'block';
            
            if (data.has_account) {
                renderExistingAccount(data.user);
            } else {
                renderCreateForm(data.suggested_email);
            }
        })
        .catch(error => {
            document.getElementById('portal-loading').innerHTML = '<span style="color: red;">Error loading portal status</span>';
        });
}

function renderExistingAccount(user) {
    const statusBadge = user.is_active 
        ? '<span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>'
        : '<span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>';
    
    const toggleBtn = user.is_active
        ? `<button onclick="togglePortalStatus(false)" class="btn" style="background: #FEE2E2; color: #991B1B;">Deactivate</button>`
        : `<button onclick="togglePortalStatus(true)" class="btn" style="background: #D1FAE5; color: #065F46;">Activate</button>`;

    document.getElementById('portal-content').innerHTML = `
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Portal Email</span>
                <span class="info-value">${user.email}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Portal Status</span>
                <span class="info-value">${statusBadge}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Created At</span>
                <span class="info-value">${new Date(user.created_at).toLocaleDateString()}</span>
            </div>
        </div>
        <hr style="margin: 20px 0; border-color: var(--card-border);">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            ${toggleBtn}
            <button onclick="showResetPasswordForm()" class="btn btn-secondary">Reset Password</button>
        </div>
        <div id="reset-password-form" style="display: none; margin-top: 20px; padding: 20px; background: var(--body-bg); border-radius: 8px;">
            <h4 style="margin: 0 0 16px 0; font-size: 16px;">Reset Password</h4>
            <div style="display: grid; gap: 12px; max-width: 400px;">
                <input type="password" id="new-password" placeholder="New Password" style="padding: 10px; border: 1px solid var(--card-border); border-radius: 6px;">
                <input type="password" id="confirm-password" placeholder="Confirm Password" style="padding: 10px; border: 1px solid var(--card-border); border-radius: 6px;">
                <div style="display: flex; gap: 12px;">
                    <button onclick="resetPassword()" class="btn btn-primary">Save Password</button>
                    <button onclick="hideResetPasswordForm()" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    `;
}

function renderCreateForm(suggestedEmail) {
    document.getElementById('portal-content').innerHTML = `
        <p style="color: var(--text-muted); margin-bottom: 20px;">No portal account exists for this student. Create one to allow student login.</p>
        <div style="max-width: 500px;">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Email Address</label>
                <div style="display: flex; gap: 8px;">
                    <input type="email" id="portal-email" value="${suggestedEmail || ''}" placeholder="Enter email address" 
                           style="flex: 1; padding: 10px; border: 1px solid var(--card-border); border-radius: 6px;">
                    <button onclick="checkEmail()" class="btn btn-secondary">Check</button>
                </div>
                <div id="email-status" style="margin-top: 8px; font-size: 13px;"></div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password</label>
                <input type="password" id="portal-password" placeholder="Enter password (min 6 characters)" 
                       style="width: 100%; padding: 10px; border: 1px solid var(--card-border); border-radius: 6px;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Confirm Password</label>
                <input type="password" id="portal-password-confirm" placeholder="Confirm password" 
                       style="width: 100%; padding: 10px; border: 1px solid var(--card-border); border-radius: 6px;">
            </div>
            <button onclick="createPortalAccount()" class="btn btn-primary" id="create-btn">
                <i class="fas fa-user-plus"></i> Create Portal Account
            </button>
        </div>
    `;
}

function checkEmail() {
    const email = document.getElementById('portal-email').value;
    const statusDiv = document.getElementById('email-status');
    
    if (!email) {
        statusDiv.innerHTML = '<span style="color: #B45309;">Please enter an email</span>';
        return;
    }
    
    statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
    
    fetch('{{ route("admin.studentsponsorship.university-students.check-email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email, student_hash: '{{ $student->hash_id }}' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.available) {
            statusDiv.innerHTML = '<span style="color: #065F46;"><i class="fas fa-check-circle"></i> ' + data.message + '</span>';
        } else {
            statusDiv.innerHTML = '<span style="color: #991B1B;"><i class="fas fa-times-circle"></i> ' + data.message + '</span>';
        }
    })
    .catch(error => {
        statusDiv.innerHTML = '<span style="color: #991B1B;">Error checking email</span>';
    });
}

function createPortalAccount() {
    const email = document.getElementById('portal-email').value;
    const password = document.getElementById('portal-password').value;
    const passwordConfirm = document.getElementById('portal-password-confirm').value;
    
    if (!email || !password) {
        alert('Please fill in all fields');
        return;
    }
    
    if (password.length < 6) {
        alert('Password must be at least 6 characters');
        return;
    }
    
    if (password !== passwordConfirm) {
        alert('Passwords do not match');
        return;
    }
    
    const btn = document.getElementById('create-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    
    fetch('{{ route("admin.studentsponsorship.university-students.create-portal", $student->hash_id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email, password: password, password_confirmation: passwordConfirm })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Portal account created successfully!');
            loadPortalStatus();
        } else {
            alert(data.message || 'Failed to create account');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-user-plus"></i> Create Portal Account';
        }
    })
    .catch(error => {
        alert('Error creating account');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-user-plus"></i> Create Portal Account';
    });
}

function togglePortalStatus(activate) {
    const url = activate 
        ? '{{ route("admin.studentsponsorship.university-students.activate-portal", $student->hash_id) }}'
        : '{{ route("admin.studentsponsorship.university-students.deactivate-portal", $student->hash_id) }}';
    
    if (!confirm(activate ? 'Activate this portal account?' : 'Deactivate this portal account?')) {
        return;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPortalStatus();
        } else {
            alert(data.message || 'Failed to update status');
        }
    });
}

function showResetPasswordForm() {
    document.getElementById('reset-password-form').style.display = 'block';
}

function hideResetPasswordForm() {
    document.getElementById('reset-password-form').style.display = 'none';
}

function resetPassword() {
    const password = document.getElementById('new-password').value;
    const confirm = document.getElementById('confirm-password').value;
    
    if (!password || password.length < 6) {
        alert('Password must be at least 6 characters');
        return;
    }
    
    if (password !== confirm) {
        alert('Passwords do not match');
        return;
    }
    
    fetch('{{ route("admin.studentsponsorship.university-students.reset-portal-password", $student->hash_id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ password: password, password_confirmation: confirm })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password reset successfully!');
            hideResetPasswordForm();
        } else {
            alert(data.message || 'Failed to reset password');
        }
    });
}

// Report Card Functions
document.getElementById('report-card-upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const filename = document.getElementById('rc-filename').value;
    const term = document.getElementById('rc-term').value;
    const semesterYear = document.getElementById('rc-semester-year').value;
    const fileInput = document.getElementById('rc-file');
    
    if (!filename) {
        alert('Please enter a filename');
        return;
    }
    
    if (!term) {
        alert('Please select a term');
        return;
    }
    
    if (!semesterYear) {
        alert('Please select semester end year');
        return;
    }
    
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Please select a file');
        return;
    }
    
    const file = fileInput.files[0];
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        alert('Please select a PDF or image file');
        return;
    }
    
    const formData = new FormData();
    formData.append('report_card_file', file);
    formData.append('filename', filename);
    formData.append('report_card_term', term);
    formData.append('semester_end_year', semesterYear);
    formData.append('student_hash', '{{ $student->hash_id }}');
    formData.append('_token', '{{ csrf_token() }}');
    
    const btn = document.getElementById('rc-upload-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    
    fetch('{{ route("admin.studentsponsorship.university-students.upload-report-card") }}', {
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
            btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
        }
    })
    .catch(err => {
        alert('Error uploading file');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
    });
});

function deleteReportCard(id) {
    if (!confirm('Are you sure you want to delete this report card?')) {
        return;
    }
    
    fetch('{{ url("admin/studentsponsorship/university-students/report-card") }}/' + id, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Report card deleted successfully!');
            location.reload();
        } else {
            alert(data.message || 'Delete failed');
        }
    })
    .catch(err => {
        alert('Error deleting report card');
    });
}

function approveReportCard(id) {
    if (!confirm('Approve this report card?')) {
        return;
    }
    
    fetch('{{ url("admin/studentsponsorship/university-students/report-card") }}/' + id + '/approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Report card approved successfully!');
            location.reload();
        } else {
            alert(data.message || 'Approval failed');
        }
    })
    .catch(err => {
        alert('Error approving report card');
    });
}

// Rejection Modal
let rejectingReportCardId = null;

function rejectReportCard(id) {
    rejectingReportCardId = id;
    document.getElementById('rejection-reason').value = '';
    document.getElementById('rejection-modal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejection-modal').style.display = 'none';
    rejectingReportCardId = null;
}

function submitRejection() {
    const reason = document.getElementById('rejection-reason').value.trim();
    if (!reason) {
        alert('Please enter a reason for rejection');
        return;
    }
    
    const btn = document.getElementById('submit-reject-btn');
    btn.disabled = true;
    btn.textContent = 'Rejecting...';
    
    fetch('{{ url("admin/studentsponsorship/university-students/report-card") }}/' + rejectingReportCardId + '/reject', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Report card rejected. Student has been notified.');
            location.reload();
        } else {
            alert(data.message || 'Rejection failed');
            btn.disabled = false;
            btn.textContent = 'Reject & Notify Student';
        }
    })
    .catch(err => {
        alert('Error rejecting report card');
        btn.disabled = false;
        btn.textContent = 'Reject & Notify Student';
    });
}

// Close modal on outside click
document.getElementById('rejection-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>

<!-- Rejection Modal -->
<div id="rejection-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--card-bg); border-radius:12px; padding:24px; width:100%; max-width:500px; margin:20px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:18px; font-weight:600; color:var(--text-primary); margin:0;">Reject Report Card</h3>
            <button onclick="closeRejectModal()" style="background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:20px;">&times;</button>
        </div>
        <p style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">
            Please provide a reason for rejection. This will be sent as a notification to the student.
        </p>
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:12px; font-weight:600; color:var(--text-muted); margin-bottom:6px;">REJECTION REASON <span style="color:red;">*</span></label>
            <textarea id="rejection-reason" rows="4" placeholder="e.g., Image is blurry, please upload a clearer copy of your report card." style="width:100%; padding:12px; border:1px solid var(--card-border); border-radius:8px; resize:vertical; font-size:14px;"></textarea>
        </div>
        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <button onclick="closeRejectModal()" style="padding:10px 20px; border:1px solid var(--card-border); border-radius:8px; background:var(--card-bg); color:var(--text-primary); cursor:pointer; font-weight:500;">Cancel</button>
            <button id="submit-reject-btn" onclick="submitRejection()" style="padding:10px 20px; border:none; border-radius:8px; background:#DC2626; color:#fff; cursor:pointer; font-weight:500;">Reject & Notify Student</button>
        </div>
    </div>
</div>
