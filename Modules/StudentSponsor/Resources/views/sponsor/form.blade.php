<style>
    .form-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .btn-back { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-secondary); text-decoration: none; transition: all 0.2s; }
    .btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
    .btn-back svg { width: 20px; height: 20px; }
    .form-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .form-tabs { display: flex; border-bottom: 1px solid var(--card-border); background: var(--body-bg); overflow-x: auto; }
    .form-tab { padding: 14px 20px; font-size: 14px; font-weight: 500; color: var(--text-muted); text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; white-space: nowrap; display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .form-tab:hover { color: var(--text-primary); background: rgba(59, 130, 246, 0.05); }
    .form-tab.active { color: var(--primary); border-bottom-color: var(--primary); background: var(--card-bg); }
    .form-tab svg { width: 16px; height: 16px; }
    .tab-content { display: none; padding: 24px; }
    .tab-content.active { display: block; }
    .section-title { font-size: 16px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid var(--card-border); display: flex; align-items: center; gap: 8px; }
    .section-title svg { width: 18px; height: 18px; color: var(--primary); }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 16px; }
    .form-row.three-col { grid-template-columns: repeat(3, 1fr); }
    .form-group { margin-bottom: 16px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-label { display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 6px; }
    .form-label .required { color: var(--danger); }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); transition: all 0.2s; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-actions { display: flex; gap: 12px; padding: 20px 24px; background: var(--body-bg); border-top: 1px solid var(--card-border); }
    .btn-submit { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-submit svg { width: 18px; height: 18px; }
    .btn-cancel { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-cancel:hover { background: var(--body-bg); color: var(--text-primary); }
    .alert-errors { background: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; padding: 16px; margin-bottom: 20px; color: var(--danger); }
    .alert-errors ul { margin: 8px 0 0 0; padding-left: 20px; }
    .form-check { display: flex; align-items: center; gap: 8px; }
    .form-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); }
    .form-check label { margin: 0; font-weight: 500; }
    .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    
    /* Summary Cards */
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .summary-card { background: var(--body-bg); border-radius: 12px; padding: 16px; text-align: center; }
    .summary-card .value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
    .summary-card .label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    .summary-card.success .value { color: var(--success); }
    .summary-card.warning .value { color: var(--warning); }
    .summary-card.danger .value { color: var(--danger); }
    
    /* Students List */
    .students-section { margin-top: 24px; }
    .students-list { background: var(--body-bg); border-radius: 12px; max-height: 300px; overflow-y: auto; }
    .student-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--card-border); }
    .student-item:last-child { border-bottom: none; }
    .student-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 600; }
    .student-info { flex: 1; }
    .student-name { font-weight: 500; color: var(--text-primary); }
    .student-meta { font-size: 13px; color: var(--text-muted); }
    .student-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .student-badge.school { background: var(--primary-light); color: var(--primary); }
    .student-badge.university { background: #f3e8ff; color: #9333ea; }
    .empty-state { padding: 32px; text-align: center; color: var(--text-muted); }
</style>

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.studentsponsor.sponsor.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1>{{ $sponsor ? 'Edit Sponsor' : 'Add Sponsor' }}</h1>
    </div>

    @if($errors->any())
        <div class="alert-errors">
            <strong>Please fix the following errors:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $sponsor ? route('admin.studentsponsor.sponsor.update', $sponsor->id) : route('admin.studentsponsor.sponsor.store') }}" method="POST">
        @csrf
        @if($sponsor) @method('PUT') @endif
        
        <div class="form-card">
            <div class="form-tabs">
                <a class="form-tab active" data-tab="tab-basic">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Basic Info
                </a>
                <a class="form-tab" data-tab="tab-contact">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Contact Info
                </a>
                <a class="form-tab" data-tab="tab-bank">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg>
                    Bank Info
                </a>
                <a class="form-tab" data-tab="tab-membership">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Membership
                </a>
                @if($sponsor)
                <a class="form-tab" data-tab="tab-students">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    Students ({{ ($schoolStudents->count() ?? 0) + ($universityStudents->count() ?? 0) }})
                </a>
                @endif
            </div>

            <!-- Tab: Basic Info -->
            <div id="tab-basic" class="tab-content active">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Sponsor Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sponsor Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $sponsor->name ?? '') }}" required placeholder="Individual name or company name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sponsor Type</label>
                        <select name="sponsor_type" class="form-select">
                            <option value="">Select Type</option>
                            @foreach($sponsorTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('sponsor_type', $sponsor->sponsor_type ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Occupation / Business Type</label>
                    <input type="text" name="sponsor_occupation" class="form-input" value="{{ old('sponsor_occupation', $sponsor->sponsor_occupation ?? '') }}" placeholder="e.g., Software Engineer, Retail Company">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', $sponsor->active ?? 1) ? 'checked' : '' }}>
                        <label for="active">Active Sponsor</label>
                    </div>
                    <div class="form-hint">Inactive sponsors won't appear in sponsor selection dropdowns</div>
                </div>
            </div>

            <!-- Tab: Contact Info -->
            <div id="tab-contact" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Contact Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $sponsor->email ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="contact_no" class="form-input" value="{{ old('contact_no', $sponsor->contact_no ?? '') }}" placeholder="+44 ...">
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" rows="2">{{ old('address', $sponsor->address ?? '') }}</textarea>
                </div>

                <div class="form-row three-col">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input" value="{{ old('city', $sponsor->city ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">County/State</label>
                        <input type="text" name="state" class="form-input" value="{{ old('state', $sponsor->state ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Postcode</label>
                        <input type="text" name="zip" class="form-input" value="{{ old('zip', $sponsor->zip ?? '') }}" placeholder="e.g., SW1A 1AA">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_id" class="form-select">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->country_id }}" {{ old('country_id', $sponsor->country_id ?? '') == $country->country_id ? 'selected' : '' }}>{{ $country->short_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tab: Bank Info -->
            <div id="tab-bank" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg>
                    Bank Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bank</label>
                        <select name="bank_id" class="form-select">
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{ old('bank_id', $sponsor->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Code</label>
                        <input type="text" name="sponsor_bank_branch_number" class="form-input" value="{{ old('sponsor_bank_branch_number', $sponsor->sponsor_bank_branch_number ?? '') }}" placeholder="00-00-00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="sponsor_bank_branch_info" class="form-input" value="{{ old('sponsor_bank_branch_info', $sponsor->sponsor_bank_branch_info ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="sponsor_bank_account_no" class="form-input" value="{{ old('sponsor_bank_account_no', $sponsor->sponsor_bank_account_no ?? '') }}" placeholder="8 digits">
                    </div>
                </div>
            </div>

            <!-- Tab: Membership -->
            <div id="tab-membership" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Membership Details
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sponsorship Frequency</label>
                        <select name="sponsor_frequency" class="form-select">
                            <option value="">Select Frequency</option>
                            @foreach($frequencyOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Membership Start Date</label>
                        <input type="date" name="membership_start_date" class="form-input" value="{{ old('membership_start_date', $sponsor?->membership_start_date?->format('Y-m-d') ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Membership End Date</label>
                        <input type="date" name="membership_end_date" class="form-input" value="{{ old('membership_end_date', $sponsor?->membership_end_date?->format('Y-m-d') ?? '') }}">
                        <div class="form-hint">Leave empty for ongoing membership</div>
                    </div>
                </div>

                @if($sponsor && isset($financialSummary))
                <div class="section-title" style="margin-top: 32px;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Financial Summary
                </div>

                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="value">£{{ number_format($financialSummary['total_committed'], 2) }}</div>
                        <div class="label">Total Committed</div>
                    </div>
                    <div class="summary-card success">
                        <div class="value">£{{ number_format($financialSummary['total_paid'], 2) }}</div>
                        <div class="label">Total Paid</div>
                    </div>
                    <div class="summary-card {{ $financialSummary['total_outstanding'] > 0 ? 'warning' : '' }}">
                        <div class="value">£{{ number_format($financialSummary['total_outstanding'], 2) }}</div>
                        <div class="label">Outstanding</div>
                    </div>
                    <div class="summary-card">
                        <div class="value">{{ $financialSummary['transaction_count'] }}</div>
                        <div class="label">Transactions</div>
                    </div>
                </div>
                @endif
            </div>

            @if($sponsor)
            <!-- Tab: Students -->
            <div id="tab-students" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    Sponsored Students
                </div>

                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="value">{{ ($schoolStudents->count() ?? 0) + ($universityStudents->count() ?? 0) }}</div>
                        <div class="label">Total Students</div>
                    </div>
                    <div class="summary-card">
                        <div class="value">{{ $schoolStudents->count() ?? 0 }}</div>
                        <div class="label">School Students</div>
                    </div>
                    <div class="summary-card">
                        <div class="value">{{ $universityStudents->count() ?? 0 }}</div>
                        <div class="label">University Students</div>
                    </div>
                </div>

                @if($schoolStudents->count() > 0)
                <div class="students-section">
                    <h4 style="margin-bottom: 12px; color: var(--text-primary);">School Students</h4>
                    <div class="students-list">
                        @foreach($schoolStudents as $student)
                        <div class="student-item">
                            <div class="student-avatar">{{ substr($student->name, 0, 1) }}</div>
                            <div class="student-info">
                                <div class="student-name">{{ $student->name }}</div>
                                <div class="student-meta">{{ $student->school_grade ?? 'N/A' }} • {{ $student->schoolName?->name ?? 'N/A' }}</div>
                            </div>
                            <span class="student-badge school">School</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($universityStudents->count() > 0)
                <div class="students-section">
                    <h4 style="margin-bottom: 12px; color: var(--text-primary);">University Students</h4>
                    <div class="students-list">
                        @foreach($universityStudents as $student)
                        <div class="student-item">
                            <div class="student-avatar">{{ substr($student->name, 0, 1) }}</div>
                            <div class="student-info">
                                <div class="student-name">{{ $student->name }}</div>
                                <div class="student-meta">{{ $student->university_year_of_study ?? 'N/A' }} • {{ $student->universityName?->name ?? 'N/A' }}</div>
                            </div>
                            <span class="student-badge university">University</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($schoolStudents->count() == 0 && $universityStudents->count() == 0)
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 16px; display: block; opacity: 0.5;">
                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"></path>
                    </svg>
                    <p>No students currently sponsored</p>
                    <p style="font-size: 13px;">Assign students from their individual forms</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    {{ $sponsor ? 'Update Sponsor' : 'Create Sponsor' }}
                </button>
                <a href="{{ route('admin.studentsponsor.sponsor.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.form-tab');
    const contents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-tab');
            
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });
});
</script>
