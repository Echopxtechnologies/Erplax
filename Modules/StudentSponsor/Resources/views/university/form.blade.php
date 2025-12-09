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
    .input-group { display: flex; gap: 8px; }
    .input-group .form-input, .input-group .form-select { flex: 1; }
    .btn-add-new { padding: 10px 14px; background: var(--success-light); color: var(--success); border: 1px solid var(--success); border-radius: 8px; font-size: 14px; cursor: pointer; white-space: nowrap; }
    .btn-add-new:hover { background: var(--success); color: #fff; }
    .photo-preview { width: 120px; height: 120px; border-radius: 12px; background: var(--body-bg); border: 2px dashed var(--card-border); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 12px; }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
    .photo-preview svg { width: 40px; height: 40px; color: var(--text-muted); }
</style>

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.studentsponsor.university.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1>{{ $student ? 'Edit University Student' : 'Add University Student' }}</h1>
    </div>

    @if($errors->any())
        <div class="alert-errors">
            <strong>Please fix the following errors:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $student ? route('admin.studentsponsor.university.update', $student->id) : route('admin.studentsponsor.university.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($student) @method('PUT') @endif
        
        <div class="form-card">
            <div class="form-tabs">
                <a class="form-tab active" data-tab="tab-basic">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Basic Info
                </a>
                <a class="form-tab" data-tab="tab-university">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    University Info
                </a>
                <a class="form-tab" data-tab="tab-bank">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"></path></svg>
                    Bank Info
                </a>
                <a class="form-tab" data-tab="tab-family">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Family Info
                </a>
                <a class="form-tab" data-tab="tab-sponsor">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Sponsorship
                </a>
                <a class="form-tab" data-tab="tab-notes">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Notes
                </a>
            </div>

            <!-- Tab: Basic Info -->
            <div id="tab-basic" class="tab-content active">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Personal Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Profile Photo</label>
                        <div class="photo-preview" id="photoPreview">
                            @if($student && $student->profile_photo)
                                <img src="{{ route('admin.studentsponsor.university.photo', $student->id) }}" alt="Profile">
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @endif
                        </div>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-input" accept="image/*">
                        <div class="form-hint">Max 2MB. JPG, PNG, GIF or WebP</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $student->name ?? '') }}" required>
                        
                        <label class="form-label" style="margin-top: 16px;">Student ID</label>
                        <input type="text" name="university_internal_id" class="form-input" value="{{ old('university_internal_id', $student->university_internal_id ?? '') }}" placeholder="University student ID">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $student->email ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_no" class="form-input" value="{{ old('contact_no', $student->contact_no ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="university_student_dob" id="university_student_dob" class="form-input" value="{{ old('university_student_dob', $student?->university_student_dob?->format('Y-m-d') ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="university_age" id="university_age" class="form-input" value="{{ old('university_age', $student->university_age ?? '') }}" min="16" max="80" readonly>
                        <div class="form-hint">Auto-calculated from DOB</div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-textarea" rows="2">{{ old('address', $student->address ?? '') }}</textarea>
                </div>

                <div class="form-row three-col">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input" value="{{ old('city', $student->city ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Postcode</label>
                        <input type="text" name="zip" class="form-input" value="{{ old('zip', $student->zip ?? '') }}" placeholder="e.g., SW1A 1AA">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select name="country_id" class="form-select">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->country_id }}" {{ old('country_id', $student->country_id ?? '') == $country->country_id ? 'selected' : '' }}>{{ $country->short_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', $student->active ?? 1) ? 'checked' : '' }}>
                        <label for="active">Active Student</label>
                    </div>
                </div>
            </div>

            <!-- Tab: University Info (UK System) -->
            <div id="tab-university" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    University Information (UK Higher Education)
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">University</label>
                        <div class="input-group">
                            <select name="university_name_id" class="form-select">
                                <option value="">Select University</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->id }}" {{ old('university_name_id', $student->university_name_id ?? '') == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-add-new" onclick="addNewUniversity()">+ Add</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Programme/Course</label>
                        <div class="input-group">
                            <select name="university_program_id" class="form-select">
                                <option value="">Select Programme</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('university_program_id', $student->university_program_id ?? '') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-add-new" onclick="addNewProgram()">+ Add</button>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Year of Study</label>
                        <select name="university_year_of_study" class="form-select">
                            <option value="">Select Year</option>
                            @foreach($yearOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('university_year_of_study', $student->university_year_of_study ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Current Semester/Term</label>
                        <select name="university_semester" class="form-select">
                            <option value="">Select Semester</option>
                            @foreach($semesterOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('university_semester', $student->university_semester ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-hint" style="margin-top: -8px; margin-bottom: 16px;">
                    <strong>UK University Years:</strong> Foundation → Year 1 → Year 2 → (Placement) → Year 3 → Year 4 → Masters → PhD
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
                                <option value="{{ $bank->id }}" {{ old('bank_id', $student->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Code</label>
                        <input type="text" name="university_bank_branch_number" class="form-input" value="{{ old('university_bank_branch_number', $student->university_bank_branch_number ?? '') }}" placeholder="00-00-00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="university_bank_branch_info" class="form-input" value="{{ old('university_bank_branch_info', $student->university_bank_branch_info ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="university_bank_account_no" class="form-input" value="{{ old('university_bank_account_no', $student->university_bank_account_no ?? '') }}" placeholder="8 digits">
                    </div>
                </div>
            </div>

            <!-- Tab: Family Info -->
            <div id="tab-family" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Family Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Father's Name</label>
                        <input type="text" name="university_father_name" class="form-input" value="{{ old('university_father_name', $student->university_father_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Father's Annual Income (£)</label>
                        <input type="number" step="0.01" name="university_father_income" class="form-input" value="{{ old('university_father_income', $student->university_father_income ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mother's Name</label>
                        <input type="text" name="university_mother_name" class="form-input" value="{{ old('university_mother_name', $student->university_mother_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mother's Annual Income (£)</label>
                        <input type="number" step="0.01" name="university_mother_income" class="form-input" value="{{ old('university_mother_income', $student->university_mother_income ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Guardian's Name</label>
                        <input type="text" name="university_guardian_name" class="form-input" value="{{ old('university_guardian_name', $student->university_guardian_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guardian's Annual Income (£)</label>
                        <input type="number" step="0.01" name="university_guardian_income" class="form-input" value="{{ old('university_guardian_income', $student->university_guardian_income ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Introduced By</label>
                        <input type="text" name="university_introducedby" class="form-input" value="{{ old('university_introducedby', $student->university_introducedby ?? '') }}" placeholder="Referrer name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Introducer Phone</label>
                        <input type="text" name="university_introducedph" class="form-input" value="{{ old('university_introducedph', $student->university_introducedph ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- Tab: Sponsorship -->
            <div id="tab-sponsor" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Sponsorship Information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sponsor</label>
                        <select name="sponsor_id" class="form-select">
                            <option value="">No Sponsor Assigned</option>
                            @foreach($sponsors as $sponsor)
                                <option value="{{ $sponsor->id }}" {{ old('sponsor_id', $student->sponsor_id ?? '') == $sponsor->id ? 'selected' : '' }}>{{ $sponsor->name }} ({{ ucfirst($sponsor->sponsor_type ?? 'Individual') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sponsorship Start Date</label>
                        <input type="date" name="university_sponsorship_start_date" class="form-input" value="{{ old('university_sponsorship_start_date', $student?->university_sponsorship_start_date?->format('Y-m-d') ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sponsorship End Date</label>
                        <input type="date" name="university_sponsorship_end_date" class="form-input" value="{{ old('university_sponsorship_end_date', $student?->university_sponsorship_end_date?->format('Y-m-d') ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- Tab: Notes -->
            <div id="tab-notes" class="tab-content">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Additional Notes
                </div>

                <div class="form-group">
                    <label class="form-label">Background Information</label>
                    <textarea name="background_info" class="form-textarea" rows="3" placeholder="Student's background, circumstances, academic achievements, etc.">{{ old('background_info', $student->background_info ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Internal Comments</label>
                    <textarea name="internal_comment" class="form-textarea" rows="3" placeholder="Staff-only notes (not visible to sponsors)">{{ old('internal_comment', $student->internal_comment ?? '') }}</textarea>
                    <div class="form-hint">Only visible to staff members</div>
                </div>

                <div class="form-group">
                    <label class="form-label">External Comments</label>
                    <textarea name="external_comment" class="form-textarea" rows="3" placeholder="Comments visible to sponsors">{{ old('external_comment', $student->external_comment ?? '') }}</textarea>
                    <div class="form-hint">May be visible to sponsors</div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    {{ $student ? 'Update Student' : 'Create Student' }}
                </button>
                <a href="{{ route('admin.studentsponsor.university.index') }}" class="btn-cancel">Cancel</a>
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

    // Auto-calculate age from DOB
    const dobInput = document.getElementById('university_student_dob');
    const ageInput = document.getElementById('university_age');
    
    if (dobInput && ageInput) {
        dobInput.addEventListener('change', function() {
            if (this.value) {
                const dob = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                ageInput.value = age;
            }
        });
    }

    // Photo preview
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photoPreview');
    
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});

// Add new university (AJAX)
function addNewUniversity() {
    const name = prompt('Enter new university name:');
    if (name) {
        fetch('{{ route("admin.studentsponsor.university.addUniversity") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.querySelector('select[name="university_name_id"]');
                const option = new Option(data.name, data.id, true, true);
                select.add(option);
            } else {
                alert('Error adding university');
            }
        });
    }
}

// Add new program (AJAX)
function addNewProgram() {
    const name = prompt('Enter new programme name:');
    if (name) {
        fetch('{{ route("admin.studentsponsor.university.addProgram") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.querySelector('select[name="university_program_id"]');
                const option = new Option(data.name, data.id, true, true);
                select.add(option);
            } else {
                alert('Error adding programme');
            }
        });
    }
}
</script>
