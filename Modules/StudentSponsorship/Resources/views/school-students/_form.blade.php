@php 
    $student = $student ?? null;
@endphp
<style>
    .form-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-back { padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
    .btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
    
    /* Tabs */
    .tabs-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .tabs-nav { display: flex; border-bottom: 1px solid var(--card-border); overflow-x: auto; background: var(--body-bg); }
    .tab-btn { padding: 16px 24px; font-size: 14px; font-weight: 600; color: var(--text-muted); border: none; background: none; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -1px; transition: all 0.2s; }
    .tab-btn:hover { color: var(--text-primary); background: rgba(59, 130, 246, 0.05); }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); background: var(--card-bg); }
    .tab-btn svg { width: 18px; height: 18px; }
    .tab-content { display: none; padding: 24px; }
    .tab-content.active { display: block; }
    
    /* Form */
    .form-section { margin-bottom: 32px; }
    .form-section:last-child { margin-bottom: 0; }
    .section-title { font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px; }
    .section-title svg { width: 20px; height: 20px; }
    
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-row-3 { grid-template-columns: repeat(3, 1fr); }
    .form-row-4 { grid-template-columns: repeat(4, 1fr); }
    .form-group { margin-bottom: 0; }
    .form-group.full-width { grid-column: span 2; }
    
    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    .form-label .hint { font-weight: 400; color: var(--text-muted); font-size: 12px; }
    
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); transition: all 0.2s; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
    
    .input-group { display: flex; }
    .input-group-text { padding: 10px 14px; background: var(--body-bg); border: 1px solid var(--input-border); border-right: none; border-radius: 8px 0 0 8px; color: var(--text-muted); font-size: 14px; }
    .input-group .form-input { border-radius: 0 8px 8px 0; }
    .input-group .form-select { border-radius: 0 8px 8px 0; flex: 1; }
    
    .input-with-btn { display: flex; gap: 8px; }
    .input-with-btn .form-select { flex: 1; }
    .btn-add-inline { padding: 10px 16px; background: var(--success); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; white-space: nowrap; }
    .btn-add-inline:hover { background: var(--success-hover); }
    
    /* Photo Upload */
    .photo-upload { display: flex; align-items: flex-start; gap: 20px; }
    .photo-preview { width: 120px; height: 120px; border-radius: 12px; background: var(--body-bg); border: 2px dashed var(--card-border); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
    .photo-preview svg { width: 40px; height: 40px; color: var(--text-muted); }
    .photo-input { flex: 1; }
    
    /* Report Cards Table */
    .report-cards-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .report-cards-table th { text-align: left; padding: 12px; background: var(--body-bg); font-weight: 600; font-size: 13px; border-bottom: 2px solid var(--card-border); }
    .report-cards-table td { padding: 12px; border-bottom: 1px solid var(--card-border); font-size: 14px; }
    .report-cards-table tr:hover { background: var(--body-bg); }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
    .btn-view { background: var(--primary-light); color: var(--primary); }
    .btn-view:hover { background: var(--primary); color: #fff; }
    .btn-delete { background: var(--danger-light); color: var(--danger); }
    .btn-delete:hover { background: var(--danger); color: #fff; }
    .btn-upload { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-upload:hover { background: var(--primary-hover); }
    
    /* Internal ID */
    .internal-id-box { display: flex; align-items: center; gap: 12px; }
    .internal-id-value { background: var(--primary-light); color: var(--primary); padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 16px; }
    .btn-edit-id { padding: 8px 12px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 6px; color: var(--text-muted); cursor: pointer; font-size: 12px; }
    .btn-edit-id:hover { background: var(--body-bg); }
    
    /* Form Actions */
    .form-actions { display: flex; gap: 12px; padding: 20px 24px; background: var(--body-bg); border-top: 1px solid var(--card-border); }
    .btn-submit { padding: 12px 28px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-cancel { padding: 12px 28px; background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); border-radius: 8px; text-decoration: none; font-weight: 600; }
    .btn-cancel:hover { background: var(--body-bg); }
    
    /* Report Cards */
    .report-cards-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .report-card-item { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 8px; }
    .report-card-item .name { font-weight: 600; color: var(--text-primary); }
    .report-card-item .actions { display: flex; gap: 8px; }
    .report-card-item .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; }
    .btn-view { background: var(--primary-light); color: var(--primary); }
    .btn-delete { background: var(--danger-light); color: var(--danger); border: none; cursor: pointer; }
    
    .upload-box { border: 2px dashed var(--card-border); border-radius: 8px; padding: 24px; text-align: center; background: var(--body-bg); }
    .upload-box input[type="file"] { display: none; }
    .upload-box label { cursor: pointer; color: var(--primary); font-weight: 600; }
    
    .alert-info { background: var(--primary-light); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 16px; color: var(--primary); margin-bottom: 20px; }
    
    /* Age-Grade Warning */
    .age-grade-warning { display: none; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 6px; padding: 10px 12px; margin-top: 8px; font-size: 13px; color: #92400E; }
    
    @media (max-width: 768px) {
        .form-row, .form-row-3, .form-row-4 { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
        .tabs-nav { flex-wrap: nowrap; }
        .tab-btn { padding: 12px 16px; font-size: 13px; }
    }
</style>

<div class="form-page">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            {{ $isEdit ? 'Edit School Student' : 'Register School Student' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn-back">
            ← Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert-errors" style="background:var(--danger-light);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:16px;margin-bottom:20px;">
            <strong style="color:var(--danger);">Please fix the following errors:</strong>
            <ul style="margin:8px 0 0 20px;color:var(--danger);">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="background:var(--success-light);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:16px;margin-bottom:20px;color:var(--success);">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.studentsponsorship.school-students.update', $student?->id) : route('admin.studentsponsorship.school-students.store') }}" 
          method="POST" enctype="multipart/form-data" id="studentForm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="tabs-container">
            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <button type="button" class="tab-btn active" data-tab="student-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Student Info
                </button>
                <button type="button" class="tab-btn" data-tab="sponsorship">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Sponsorship
                </button>
                <button type="button" class="tab-btn" data-tab="bank-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Bank Info
                </button>
                <button type="button" class="tab-btn" data-tab="family-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Family Info
                </button>
                <button type="button" class="tab-btn" data-tab="additional-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Additional Info
                </button>
                <button type="button" class="tab-btn" data-tab="report-cards">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Report Cards
                </button>
            </div>

            <!-- Tab: Student Info -->
            <div class="tab-content active" id="tab-student-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Basic Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="full_name" class="form-input" value="{{ old('full_name', $student?->full_name ?? '') }}" placeholder="Enter student's full name" required>
                            @error('full_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <div class="input-with-btn">
                                <select name="country_id" id="countrySelect" class="form-select" onchange="updatePhonePrefix()">
                                    <option value="" data-calling-code="94">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_id }}" 
                                                data-calling-code="{{ $country->calling_code }}"
                                                {{ old('country_id', $student?->country_id ?? '') == $country->country_id ? 'selected' : '' }}>
                                            {{ $country->short_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $student?->email ?? '') }}" placeholder="student@example.com">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-textarea" rows="2" placeholder="Complete address">{{ old('address', $student?->address ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text" id="phonePrefix">+94</span>
                                <input type="text" name="phone" class="form-input" value="{{ old('phone', $student?->phone ?? '') }}" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City / District</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city', $student?->city ?? '') }}" placeholder="City / District">
                        </div>
                    </div>

                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" id="dobField" class="form-input" value="{{ old('dob', $student?->dob?->format('Y-m-d') ?? '') }}" onchange="calculateAge()">
                            @error('dob')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age <span class="required">*</span></label>
                            <input type="number" name="age" id="ageField" class="form-input" value="{{ old('age', $student?->age ?? '') }}" min="1" max="100" required>
                            @error('age')<div class="form-error">{{ $message }}</div>@enderror
                            <small style="color:var(--text-muted);margin-top:4px;display:block;">Auto-calculated from DOB or enter manually</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code', $student?->postal_code ?? '') }}" placeholder="Postal code">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Profile Photo</label>
                            <div class="photo-upload">
                                <div class="photo-preview" id="photoPreview">
                                    @if($isEdit && $student->hasProfilePhoto())
                                        <img src="{{ $student->profile_photo_url }}" alt="Photo">
                                    @else
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                    @endif
                                </div>
                                <div class="photo-input">
                                    <input type="file" name="profile_photo" class="form-input" accept="image/*" onchange="previewPhoto(this)">
                                    <small style="color:var(--text-muted);display:block;margin-top:4px;">Max 2MB. JPG, PNG supported.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Student ID <span class="required">*</span> <span class="hint">(Internal tracking ID)</span></label>
                            <input type="text" name="school_internal_id" class="form-input" value="{{ old('school_internal_id', $student?->school_internal_id ?? '') }}" placeholder="e.g. 344fdfe" required>
                            @error('school_internal_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        School Information
                    </h3>
                    
                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Grade / Class <span class="required">*</span></label>
                            <select name="grade" class="form-select" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $key => $label)
                                    <option value="{{ $key }}" {{ old('grade', $student?->grade ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('grade')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Current State <span class="required">*</span></label>
                            <select name="current_state" class="form-select" required>
                                <option value="inprogress" {{ old('current_state', $student?->current_state ?? 'inprogress') == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                                <option value="complete" {{ old('current_state', $student?->current_state ?? '') == 'complete' ? 'selected' : '' }}>Complete</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">School Type</label>
                            <select name="school_type" class="form-select">
                                <option value="">Select School Type</option>
                                @foreach($schoolTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('school_type', $student?->school_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">School Name</label>
                            <div class="input-with-btn">
                                <select name="school_id" id="schoolSelect" class="form-select">
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $student?->school_id ?? '') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewSchool()">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grade Mismatch Reason (shown when age is 1 year off) -->
                    <div class="form-row" id="gradeMismatchRow" style="display: none;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Grade Mismatch Reason <span class="required">*</span></label>
                            <input type="text" name="grade_mismatch_reason" id="gradeMismatchReason" class="form-input" 
                                   value="{{ old('grade_mismatch_reason', $student?->grade_mismatch_reason ?? '') }}"
                                   placeholder="Please explain why student's age doesn't match the grade">
                            <small class="form-hint" id="mismatchHint">Required when student age is outside expected range for the grade</small>
                            @error('grade_mismatch_reason')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Sponsorship -->
            <div class="tab-content" id="tab-sponsorship">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Sponsorship Details
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sponsorship Start Date</label>
                            <input type="date" name="sponsorship_start_date" class="form-input" value="{{ old('sponsorship_start_date', $student?->sponsorship_start_date?->format('Y-m-d') ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sponsorship End Date</label>
                            <input type="date" name="sponsorship_end_date" class="form-input" value="{{ old('sponsorship_end_date', $student?->sponsorship_end_date?->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Introduced By</label>
                            <input type="text" name="introduced_by" class="form-input" value="{{ old('introduced_by', $student?->introduced_by ?? '') }}" placeholder="Name of introducer">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Introducer's Phone</label>
                            <input type="text" name="introducer_phone" class="form-input" value="{{ old('introducer_phone', $student?->introducer_phone ?? '') }}" placeholder="Phone number">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Bank Info -->
            <div class="tab-content" id="tab-bank-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Bank Details
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <select name="bank_id" class="form-select">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id', $student?->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Account Number</label>
                            <input type="text" name="bank_account_number" class="form-input" value="{{ old('bank_account_number', $student?->bank_account_number ?? '') }}" placeholder="Account number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Branch Number</label>
                            <input type="text" name="bank_branch_number" class="form-input" value="{{ old('bank_branch_number', $student?->bank_branch_number ?? '') }}" placeholder="Branch number/code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Branch Information</label>
                            <textarea name="bank_branch_info" class="form-textarea" rows="2" placeholder="Additional branch details">{{ old('bank_branch_info', $student?->bank_branch_info ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Family Info -->
            <div class="tab-content" id="tab-family-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Family Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-input" value="{{ old('father_name', $student?->father_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Father's Income</label>
                            <input type="number" name="father_income" class="form-input" value="{{ old('father_income', $student?->father_income ?? '') }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-input" value="{{ old('mother_name', $student?->mother_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mother's Income</label>
                            <input type="number" name="mother_income" class="form-input" value="{{ old('mother_income', $student?->mother_income ?? '') }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Guardian's Name</label>
                            <input type="text" name="guardian_name" class="form-input" value="{{ old('guardian_name', $student?->guardian_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Guardian's Income</label>
                            <input type="number" name="guardian_income" class="form-input" value="{{ old('guardian_income', $student?->guardian_income ?? '') }}" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Background Information</label>
                        <textarea name="background_info" class="form-textarea" rows="4" placeholder="Family background, special circumstances, etc.">{{ old('background_info', $student?->background_info ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Additional Info -->
            <div class="tab-content" id="tab-additional-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Comments & Notes
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Internal Comment <span class="hint">These comments are only visible to staff/administrators.</span></label>
                        <textarea name="internal_comment" class="form-textarea" rows="4" placeholder="Staff-only notes...">{{ old('internal_comment', $student?->internal_comment ?? '') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label class="form-label">External Comment <span class="hint">These comments are visible to students and sponsors.</span></label>
                        <textarea name="external_comment" class="form-textarea" rows="4" placeholder="Public notes...">{{ old('external_comment', $student?->external_comment ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Report Cards -->
            <div class="tab-content" id="tab-report-cards">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Report Cards
                    </h3>
                    
                    @if(!$isEdit)
                        <div class="alert-info">
                            <strong>Note:</strong> Please save the student first before uploading report cards.
                        </div>
                    @else
                        <!-- Existing Report Cards List -->
                        <div class="report-cards-list" id="reportCardsList">
                            @if($student && $student->report_cards && $student->report_cards->count() > 0)
                                <table class="report-cards-table">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Term</th>
                                            <th>Upload Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->report_cards as $media)
                                            <tr class="report-card-row" data-id="{{ $media->id }}">
                                                <td>{{ $media->name }}</td>
                                                <td>{{ $media->getCustomProperty('term', '-') }}</td>
                                                <td>{{ $media->getCustomProperty('upload_date', $media->created_at->format('Y-m-d')) }}</td>
                                                <td>
                                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn-sm btn-view">View</a>
                                                    <button type="button" class="btn-sm btn-delete" onclick="deleteReportCard({{ $media->id }})">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="color:var(--text-muted);text-align:center;padding:20px;">No report cards uploaded yet.</p>
                            @endif
                        </div>
                        
                        <!-- Upload New Report Card -->
                        <div class="upload-form-box" style="margin-top:24px;padding:20px;background:var(--body-bg);border-radius:8px;border:1px solid var(--card-border);">
                            <h4 style="margin-bottom:16px;font-size:14px;font-weight:600;">Upload New Report Card</h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Filename <span class="required">*</span></label>
                                    <input type="text" id="rcFilename" class="form-input" placeholder="e.g. Term 1 Report 2024">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Term <span class="required">*</span></label>
                                    <select id="rcTerm" class="form-select">
                                        <option value="">Select Term</option>
                                        <option value="Term 1">Term 1</option>
                                        <option value="Term 2">Term 2</option>
                                        <option value="Term 3">Term 3</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Upload Date <span class="required">*</span></label>
                                    <input type="date" id="rcUploadDate" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Report Card File (PDF, Image) <span class="required">*</span></label>
                                    <input type="file" id="reportCardInput" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                                    <small style="color:var(--text-muted);margin-top:4px;display:block;">Max 5MB. PDF, JPG, PNG supported.</small>
                                </div>
                            </div>
                            
                            <button type="button" class="btn-upload" onclick="uploadReportCard()" style="margin-top:12px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Report Card
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13l4 4L19 7"></path></svg>
                    {{ $isEdit ? 'Update Student' : 'Save Student' }}
                </button>
                <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// Photo preview
function previewPhoto(input) {
    var preview = document.getElementById('photoPreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Age to Grade mapping (Sri Lankan education system)
var gradeAgeMapping = {
    '1': {min: 5, max: 6, label: 'Grade 1'},
    '2': {min: 6, max: 7, label: 'Grade 2'},
    '3': {min: 7, max: 8, label: 'Grade 3'},
    '4': {min: 8, max: 9, label: 'Grade 4'},
    '5': {min: 9, max: 10, label: 'Grade 5'},
    '6': {min: 10, max: 11, label: 'Grade 6'},
    '7': {min: 11, max: 12, label: 'Grade 7'},
    '8': {min: 12, max: 13, label: 'Grade 8'},
    '9': {min: 13, max: 14, label: 'Grade 9'},
    '10': {min: 14, max: 15, label: 'Grade 10'},
    '11': {min: 15, max: 16, label: 'O/L (Grade 11)'},
    '12': {min: 16, max: 17, label: 'A/L1 (Grade 12)'},
    '13': {min: 17, max: 18, label: 'A/L2 (Grade 13)'},
    '14': {min: 18, max: 19, label: 'A/L Final (Grade 14)'}
};

// Get suggested grade for age
function getSuggestedGrade(age) {
    for (var grade in gradeAgeMapping) {
        if (age >= gradeAgeMapping[grade].min && age <= gradeAgeMapping[grade].max) {
            return grade;
        }
    }
    return null;
}

// Check if age matches grade
function isAgeGradeMatch(age, grade) {
    if (!age || !grade || !gradeAgeMapping[grade]) return true;
    var mapping = gradeAgeMapping[grade];
    return age >= mapping.min && age <= mapping.max;
}

// Calculate age from DOB and suggest grade
function calculateAge() {
    var dob = document.querySelector('input[name="dob"]').value;
    if (dob) {
        var today = new Date();
        var birth = new Date(dob);
        var age = today.getFullYear() - birth.getFullYear();
        var m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        document.getElementById('ageField').value = age;
        
        // Suggest grade based on age
        var suggestedGrade = getSuggestedGrade(age);
        if (suggestedGrade) {
            var gradeSelect = document.querySelector('select[name="grade"]');
            if (gradeSelect && !gradeSelect.value) {
                gradeSelect.value = suggestedGrade;
            }
        }
        
        // Validate current selection
        validateAgeGrade();
    }
}

// Validate age matches grade
function validateAgeGrade() {
    var age = parseInt(document.getElementById('ageField').value);
    var gradeSelect = document.querySelector('select[name="grade"]');
    var grade = gradeSelect ? gradeSelect.value : '';
    var warningEl = document.getElementById('ageGradeWarning');
    var mismatchRow = document.getElementById('gradeMismatchRow');
    var mismatchInput = document.getElementById('gradeMismatchReason');
    
    if (!warningEl) {
        warningEl = document.createElement('div');
        warningEl.id = 'ageGradeWarning';
        warningEl.className = 'age-grade-warning';
        gradeSelect.parentNode.appendChild(warningEl);
    }
    
    if (age && grade && gradeAgeMapping[grade]) {
        var mapping = gradeAgeMapping[grade];
        var diff = 0;
        
        if (age < mapping.min) {
            diff = mapping.min - age;
        } else if (age > mapping.max) {
            diff = age - mapping.max;
        }
        
        if (diff > 0) {
            var suggestedGrade = getSuggestedGrade(age);
            var suggestedLabel = suggestedGrade ? gradeAgeMapping[suggestedGrade].label : 'Unknown';
            warningEl.innerHTML = '<strong>⚠️ Age Mismatch:</strong> Age ' + age + ' typically should be in ' + suggestedLabel + ' (Age ' + (suggestedGrade ? gradeAgeMapping[suggestedGrade].min + '-' + gradeAgeMapping[suggestedGrade].max : '?') + ')';
            warningEl.style.display = 'block';
            
            // If exactly 1 year off, require mismatch reason
            if (diff === 1) {
                mismatchRow.style.display = 'block';
                mismatchInput.required = true;
                document.getElementById('mismatchHint').textContent = 'Age is 1 year outside expected range. Please provide a reason.';
            } else if (diff > 1) {
                // More than 1 year off - still show but with different message
                mismatchRow.style.display = 'block';
                mismatchInput.required = true;
                document.getElementById('mismatchHint').textContent = 'Age is ' + diff + ' years outside expected range. Please provide a reason.';
            }
        } else {
            warningEl.style.display = 'none';
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
            mismatchInput.value = '';
        }
    } else {
        warningEl.style.display = 'none';
        if (mismatchRow) {
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
        }
    }
}

// Listen for grade change
document.addEventListener('DOMContentLoaded', function() {
    var gradeSelect = document.querySelector('select[name="grade"]');
    if (gradeSelect) {
        gradeSelect.addEventListener('change', validateAgeGrade);
    }
    var ageField = document.getElementById('ageField');
    if (ageField) {
        ageField.addEventListener('change', validateAgeGrade);
        ageField.addEventListener('input', validateAgeGrade);
    }
    // Initial validation - check if reason should be shown on edit
    validateAgeGrade();
    
    // If editing and has mismatch reason, keep it visible
    @if($isEdit && $student?->grade_mismatch_reason)
    document.getElementById('gradeMismatchRow').style.display = 'block';
    @endif
});

// Add new school
function addNewSchool() {
    var name = prompt('Enter new school name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.school-students.add-school") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('schoolSelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
            }
        });
    }
}

// Update phone prefix based on selected country
function updatePhonePrefix() {
    var select = document.getElementById('countrySelect');
    var prefix = document.getElementById('phonePrefix');
    if (select && prefix) {
        var selected = select.options[select.selectedIndex];
        var code = selected.getAttribute('data-calling-code') || '94';
        prefix.textContent = '+' + code;
    }
}

// Initialize phone prefix on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePhonePrefix();
});

@if($isEdit && $student)
// Upload report card
function uploadReportCard() {
    var filename = document.getElementById('rcFilename').value.trim();
    var term = document.getElementById('rcTerm').value;
    var uploadDate = document.getElementById('rcUploadDate').value;
    var fileInput = document.getElementById('reportCardInput');
    
    // Validation
    if (!filename) {
        alert('Please enter a filename');
        document.getElementById('rcFilename').focus();
        return;
    }
    if (!term) {
        alert('Please select a term');
        document.getElementById('rcTerm').focus();
        return;
    }
    if (!uploadDate) {
        alert('Please select upload date');
        document.getElementById('rcUploadDate').focus();
        return;
    }
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Please select a file to upload');
        fileInput.focus();
        return;
    }
    
    var formData = new FormData();
    formData.append('report_card', fileInput.files[0]);
    formData.append('title', filename);
    formData.append('term', term);
    formData.append('upload_date', uploadDate);
    
    fetch('{{ route("admin.studentsponsorship.school-students.upload-report-card", $student?->id ?? 0) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Upload failed');
        }
    })
    .catch(err => {
        alert('Upload failed: ' + err.message);
    });
}

// Delete report card
function deleteReportCard(mediaId) {
    if (!confirm('Delete this report card?')) return;
    
    fetch('{{ url("admin/studentsponsorship/school-students/".($student?->id ?? 0)."/report-cards") }}/' + mediaId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.report-card-item[data-id="' + mediaId + '"]').remove();
        }
    });
}
@endif
</script>
