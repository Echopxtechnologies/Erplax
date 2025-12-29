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
    .tabs-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 20px; }
    .tabs-nav { display: flex; border-bottom: 1px solid var(--card-border); overflow-x: auto; background: var(--body-bg); border-radius: 12px 12px 0 0; }
    .tabs-nav::-webkit-scrollbar { display: none; }
    .tabs-nav { -ms-overflow-style: none; scrollbar-width: none; }
    .tab-btn { padding: 16px 24px; font-size: 14px; font-weight: 600; color: var(--text-muted); border: none; background: none; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -1px; transition: all 0.2s; }
    .tab-btn:hover { color: var(--text-primary); background: rgba(59, 130, 246, 0.05); }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); background: var(--card-bg); }
    .tab-btn svg { width: 18px; height: 18px; }
    .tab-content { display: none; padding: 24px; position: relative; }
    .tab-content.active { display: block; overflow: visible; }
    
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
    .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    
    /* Hide number input spinners */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none !important; margin: 0 !important; }
    input[type="number"] { -moz-appearance: textfield !important; appearance: textfield !important; }
    
    /* Searchable Select */
    .searchable-select { position: relative; }
    .searchable-select .ss-display { width: 100%; padding: 10px 14px; padding-right: 36px; font-size: 14px; background: var(--input-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; color: var(--input-text, #333); cursor: pointer; min-height: 42px; display: flex; align-items: center; }
    .searchable-select .ss-display.placeholder { color: var(--text-muted, #999); }
    .searchable-select .ss-arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 10px; cursor: pointer; }
    .searchable-select .ss-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; margin-top: 4px; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .searchable-select.open .ss-dropdown { display: block; }
    .searchable-select .ss-search { width: 100%; padding: 10px 14px; font-size: 14px; border: none; border-bottom: 1px solid var(--input-border, #ccc); border-radius: 8px 8px 0 0; background: var(--body-bg, #f9fafb); color: var(--input-text, #333); outline: none; }
    .searchable-select .ss-options { max-height: 200px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 14px; cursor: pointer; font-size: 14px; color: var(--text-primary, #333); }
    .searchable-select .ss-option:hover, .searchable-select .ss-option.highlighted { background: var(--primary-light, #e0e7ff); color: var(--primary, #4F46E5); }
    .searchable-select .ss-option.selected { background: var(--primary, #4F46E5); color: #fff; }
    .searchable-select .ss-no-results { padding: 10px 14px; color: var(--text-muted, #999); font-size: 13px; }
    
    .input-with-btn { display: flex; gap: 8px; }
    .input-with-btn .form-select { flex: 1; }
    .btn-add-inline { padding: 10px 16px; background: var(--success); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; white-space: nowrap; }
    .btn-add-inline:hover { background: var(--success-hover); }
    
    /* Photo Upload */
    .photo-upload { display: flex; align-items: flex-start; gap: 20px; }
    .photo-preview { width: 120px; height: 120px; border-radius: 12px; background: var(--body-bg); border: 2px dashed var(--card-border); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
    
    /* Report Cards DataTable */
    .rc-dt-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .rc-dt-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--card-border); background: var(--body-bg); }
    .rc-dt-title { font-size: 15px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .rc-dt-body { overflow-x: auto; }
    .rc-datatable { width: 100%; border-collapse: collapse; }
    .rc-datatable thead th { text-align: left; padding: 14px 16px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }
    .rc-datatable tbody td { padding: 14px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; vertical-align: middle; }
    .rc-datatable tbody tr:hover { background: rgba(59, 130, 246, 0.05); }
    .rc-dt-footer { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; border-top: 1px solid var(--card-border); background: var(--body-bg); font-size: 13px; color: var(--text-muted); }
    
    /* Term Badges */
    .term-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #DBEAFE; color: #1E40AF; }
    
    /* Action Buttons */
    .action-btns { display: flex; gap: 6px; }
    .btn-action { width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-action svg { width: 16px; height: 16px; }
    .btn-action.btn-view { background: var(--primary-light); color: var(--primary); }
    .btn-action.btn-view:hover { background: var(--primary); color: #fff; }
    .btn-action.btn-delete { background: var(--danger-light); color: var(--danger); }
    .btn-action.btn-delete:hover { background: var(--danger); color: #fff; }
    
    /* Form Actions */
    .form-actions { display: flex; gap: 12px; padding: 20px 24px; background: var(--body-bg); border-top: 1px solid var(--card-border); }
    .btn-submit { padding: 12px 28px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-cancel { padding: 12px 28px; background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); border-radius: 8px; text-decoration: none; font-weight: 600; }
    .btn-cancel:hover { background: var(--body-bg); }
    
    .btn-upload { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-upload:hover { background: var(--primary-hover); }
    
    /* Mobile Responsive */
    @media (max-width: 992px) {
        .form-row-3, .form-row-4 { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 768px) {
        .form-page { padding: 12px; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .tab-btn { padding: 12px 14px; font-size: 12px; }
        .tab-content { padding: 16px; }
        .form-row, .form-row-3, .form-row-4 { grid-template-columns: 1fr; gap: 16px; }
        .form-group.full-width { grid-column: span 1; }
        .photo-upload { flex-direction: column; align-items: center; }
        .photo-preview { width: 100px; height: 100px; }
        .input-with-btn { flex-direction: column; gap: 8px; }
        .form-actions { flex-direction: column; padding: 16px; }
        .btn-submit, .btn-cancel { width: 100%; justify-content: center; text-align: center; }
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
            {{ $isEdit ? 'Edit University Student' : 'Register University Student' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.university-students.index') }}" class="btn-back">
            ← Back to List
        </a>
    </div>

    @if($errors->any())
        <div style="background:var(--danger-light);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:16px;margin-bottom:20px;">
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

    <form action="{{ $isEdit ? route('admin.studentsponsorship.university-students.update', $student?->hash_id) : route('admin.studentsponsorship.university-students.store') }}" 
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
                @if($isEdit)
                <button type="button" class="tab-btn" data-tab="report-cards">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Report Cards
                </button>
                @endif
            </div>

            <!-- Tab: Student Info (Combined with University Info) -->
            <div class="tab-content active" id="tab-student-info">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Basic Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $student?->name ?? '') }}" placeholder="Enter student's full name" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $student?->email ?? '') }}" placeholder="student@example.com">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" class="form-input" value="{{ old('contact_no', $student?->contact_no ?? '') }}" placeholder="Phone number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select name="country_id" id="countrySelect" class="form-select">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $student?->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-textarea" rows="2" placeholder="Complete address">{{ old('address', $student?->address ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city', $student?->city ?? '') }}" placeholder="City">
                            <div style="margin-top:12px;">
                                <label class="form-label">Zip Code</label>
                                <input type="text" name="zip" class="form-input" value="{{ old('zip', $student?->zip ?? '') }}" placeholder="Postal code">
                            </div>
                        </div>
                    </div>

                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="university_student_dob" id="dobField" class="form-input" 
                                   value="{{ old('university_student_dob', $student?->university_student_dob?->format('Y-m-d') ?? '') }}" onchange="calculateAge()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="text" id="ageField" class="form-input" readonly 
                                   value="{{ $student?->age ?? '' }}" style="background: var(--body-bg); cursor: not-allowed;">
                            <small class="form-hint">Auto-calculated from DOB</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="active" class="form-select" id="statusSelect">
                                <option value="1" {{ old('active', $student?->active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('active', $student?->active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- University Information Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        University Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Internal ID <span class="required">*</span></label>
                            <input type="text" name="university_internal_id" class="form-input" required
                                   value="{{ old('university_internal_id', $student?->university_internal_id ?? '') }}" placeholder="Enter Internal ID">
                        </div>
                        <div class="form-group">
                            <label class="form-label">University</label>
                            <div class="input-with-btn">
                                <select name="university_name_id" id="universitySelect" class="form-select">
                                    <option value="">Select University</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->id }}" {{ old('university_name_id', $student?->university_name_id ?? '') == $university->id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewUniversity()">+</button>
                            </div>
                            <input type="hidden" name="new_university_name" id="new_university_name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Program / Course</label>
                            <div class="input-with-btn">
                                <select name="university_program_id" id="programSelect" class="form-select">
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('university_program_id', $student?->university_program_id ?? '') == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewProgram()">+</button>
                            </div>
                            <input type="hidden" name="new_program_name" id="new_program_name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Year of Study</label>
                            <select name="university_year_of_study" id="yearOfStudySelect" class="form-select">
                                <option value="">Select Year</option>
                                @foreach($yearsOfStudy as $key => $label)
                                    <option value="{{ $key }}" {{ old('university_year_of_study', $student?->university_year_of_study ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Profile Photo</label>
                            <div class="photo-upload">
                                <div class="photo-preview" id="photoPreview">
                                    @if($isEdit && $student->hasMedia('profile_photo'))
                                        <img src="{{ $student->profile_photo_url }}" alt="Photo" id="currentPhoto">
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;color:var(--text-muted);">
                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="photo-input">
                                    <input type="file" name="profile_photo" id="profilePhotoInput" class="form-input" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(this)">
                                    <small class="form-hint">Max 2MB. JPG, PNG supported.</small>
                                    @if($isEdit && $student->hasMedia('profile_photo'))
                                        <button type="button" onclick="removeProfilePhoto()" class="btn-remove-photo" style="margin-top:8px;padding:6px 12px;background:#EF4444;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;">
                                            Remove Photo
                                        </button>
                                    @endif
                                </div>
                            </div>
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
                            <input type="date" name="university_sponsorship_start_date" class="form-input" 
                                   value="{{ old('university_sponsorship_start_date', $student?->university_sponsorship_start_date?->format('Y-m-d') ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sponsorship End Date</label>
                            <input type="date" name="university_sponsorship_end_date" class="form-input" 
                                   value="{{ old('university_sponsorship_end_date', $student?->university_sponsorship_end_date?->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Introduced By</label>
                            <input type="text" name="university_introducedby" class="form-input" 
                                   value="{{ old('university_introducedby', $student?->university_introducedby ?? '') }}" placeholder="Name of introducer">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Introducer Phone</label>
                            <input type="text" name="university_introducedph" class="form-input" 
                                   value="{{ old('university_introducedph', $student?->university_introducedph ?? '') }}" placeholder="Phone number">
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
                            <div class="input-with-btn">
                                <select name="bank_id" id="bankSelect" class="form-select">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id', $student?->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewBank()">+</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Account Number</label>
                            <input type="text" name="university_bank_account_no" class="form-input numeric-only" 
                                   value="{{ old('university_bank_account_no', $student?->university_bank_account_no ?? '') }}" placeholder="Account number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Branch Number</label>
                            <input type="text" name="university_bank_branch_number" class="form-input numeric-only" 
                                   value="{{ old('university_bank_branch_number', $student?->university_bank_branch_number ?? '') }}" placeholder="Branch number/code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Branch Information</label>
                            <textarea name="university_bank_branch_info" class="form-textarea" rows="2" placeholder="Additional branch details">{{ old('university_bank_branch_info', $student?->university_bank_branch_info ?? '') }}</textarea>
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
                            <input type="text" name="university_father_name" class="form-input" value="{{ old('university_father_name', $student?->university_father_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Father's Income</label>
                            <input type="text" name="university_father_income" class="form-input decimal-only" value="{{ old('university_father_income', $student?->university_father_income ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="university_mother_name" class="form-input" value="{{ old('university_mother_name', $student?->university_mother_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mother's Income</label>
                            <input type="text" name="university_mother_income" class="form-input decimal-only" value="{{ old('university_mother_income', $student?->university_mother_income ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Guardian's Name</label>
                            <input type="text" name="university_guardian_name" class="form-input" value="{{ old('university_guardian_name', $student?->university_guardian_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Guardian's Income</label>
                            <input type="text" name="university_guardian_income" class="form-input decimal-only" value="{{ old('university_guardian_income', $student?->university_guardian_income ?? '') }}" placeholder="0.00">
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
                        <label class="form-label">Internal Comment <span class="hint">- Staff only, not visible to student/sponsor</span></label>
                        <textarea name="internal_comment" class="form-textarea" rows="4" placeholder="Staff-only notes...">{{ old('internal_comment', $student?->internal_comment ?? '') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label class="form-label">External Comment <span class="hint">- Visible to students and sponsors</span></label>
                        <textarea name="external_comment" class="form-textarea" rows="4" placeholder="Public notes...">{{ old('external_comment', $student?->external_comment ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Report Cards -->
            @if($isEdit)
            <div class="tab-content" id="tab-report-cards">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Report Cards
                    </h3>
                    
                    <div id="rcUploadMessage" style="display:none;padding:12px 16px;border-radius:8px;margin-bottom:16px;"></div>
                    
                    <!-- Upload Form -->
                    <div style="margin-bottom:24px;padding:20px;background:var(--body-bg);border-radius:8px;border:1px solid var(--card-border);">
                        <h4 style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:16px;">Upload Report Card</h4>
                        <div class="form-row form-row-3">
                            <div class="form-group">
                                <label class="form-label">Filename <span class="required">*</span></label>
                                <input type="text" id="rcFilename" class="form-input" placeholder="Report card name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Term <span class="required">*</span></label>
                                <select id="rcTerm" class="form-select">
                                    <option value="">Select Term</option>
                                    @foreach($terms as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Semester End Year</label>
                                <select id="rcYear" class="form-select">
                                    @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Report Card File (PDF, Image) <span class="required">*</span></label>
                                <input type="file" id="rcFileInput" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-hint">PDF, JPG, PNG (Max 10MB)</small>
                            </div>
                            <div class="form-group" style="display:flex;align-items:flex-end;">
                                <button type="button" id="rcUploadBtn" class="btn-upload" onclick="uploadReportCard()">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <span id="rcUploadBtnText">Upload</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Report Cards Table -->
                    <div class="rc-dt-container">
                        <div class="rc-dt-header">
                            <div class="rc-dt-title">Uploaded Report Cards</div>
                        </div>
                        <div class="rc-dt-body">
                            <table class="rc-datatable" id="reportCardsTable">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Term</th>
                                        <th>Semester End</th>
                                        <th>Upload Date</th>
                                        <th>Size</th>
                                        <th style="width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reportCardsBody">
                                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:24px;">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="rc-dt-footer">
                            <span id="rcCountInfo">0 report cards</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13l4 4L19 7"></path></svg>
                    {{ $isEdit ? 'Update Student' : 'Save Student' }}
                </button>
                <a href="{{ route('admin.studentsponsorship.university-students.index') }}" class="btn-cancel">Cancel</a>
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

// Calculate age from DOB
function calculateAge() {
    var dob = document.getElementById('dobField').value;
    if (dob) {
        var today = new Date();
        var birth = new Date(dob);
        var age = today.getFullYear() - birth.getFullYear();
        var m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        document.getElementById('ageField').value = age;
    }
}

@if($isEdit && $student)
// Remove profile photo
function removeProfilePhoto() {
    if (!confirm('Are you sure you want to remove the profile photo?')) return;
    
    fetch('{{ route("admin.studentsponsorship.university-students.remove-photo", $student->hash_id) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('photoPreview').innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;color:var(--text-muted);"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
            var removeBtn = document.querySelector('.btn-remove-photo');
            if (removeBtn) removeBtn.style.display = 'none';
            alert('Photo removed successfully');
        }
    });
}
@endif

// Add new university
function addNewUniversity() {
    var name = prompt('Enter new university name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.university-students.add-university") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('universitySelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
                if (select.searchableSelect) {
                    select.searchableSelect.options = Array.from(select.options);
                    select.searchableSelect.updateDisplay();
                }
                alert('University "' + data.name + '" added successfully!');
            } else {
                alert(data.message || 'Failed to add university');
            }
        });
    }
}

// Add new program
function addNewProgram() {
    var name = prompt('Enter new program name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.university-students.add-program") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('programSelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
                if (select.searchableSelect) {
                    select.searchableSelect.options = Array.from(select.options);
                    select.searchableSelect.updateDisplay();
                }
                alert('Program "' + data.name + '" added successfully!');
            } else {
                alert(data.message || 'Failed to add program');
            }
        });
    }
}

// Add new bank
function addNewBank() {
    var name = prompt('Enter new bank name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.university-students.add-bank") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('bankSelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
                if (select.searchableSelect) {
                    select.searchableSelect.options = Array.from(select.options);
                    select.searchableSelect.updateDisplay();
                }
                alert('Bank "' + data.name + '" added successfully!');
            } else {
                alert(data.message || 'Failed to add bank');
            }
        });
    }
}

// Numeric-only inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.numeric-only').forEach(function(input) {
        input.addEventListener('input', function() { this.value = this.value.replace(/[^0-9]/g, ''); });
    });
    document.querySelectorAll('.decimal-only').forEach(function(input) {
        input.addEventListener('input', function() {
            var value = this.value.replace(/[^0-9.]/g, '');
            var parts = value.split('.');
            if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
            this.value = value;
        });
    });
});

// ============ SEARCHABLE SELECT ============
class SearchableSelect {
    constructor(selectEl) {
        this.select = selectEl;
        this.options = Array.from(selectEl.options);
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'searchable-select';
        this.hasAddButton = selectEl.parentNode.classList.contains('input-with-btn');
        
        this.display = document.createElement('div');
        this.display.className = 'ss-display';
        
        this.arrow = document.createElement('span');
        this.arrow.className = 'ss-arrow';
        this.arrow.innerHTML = '▼';
        
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'ss-dropdown';
        
        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.className = 'ss-search';
        this.searchInput.placeholder = 'Type to search...';
        this.searchInput.autocomplete = 'off';
        
        this.optionsContainer = document.createElement('div');
        this.optionsContainer.className = 'ss-options';
        
        this.dropdown.appendChild(this.searchInput);
        this.dropdown.appendChild(this.optionsContainer);
        
        this.select.style.display = 'none';
        
        if (this.hasAddButton) {
            var parent = this.select.parentNode;
            parent.insertBefore(this.wrapper, this.select);
            this.wrapper.appendChild(this.display);
            this.wrapper.appendChild(this.arrow);
            this.wrapper.appendChild(this.dropdown);
            this.wrapper.appendChild(this.select);
            this.wrapper.style.flex = '1';
        } else {
            this.select.parentNode.insertBefore(this.wrapper, this.select);
            this.wrapper.appendChild(this.display);
            this.wrapper.appendChild(this.arrow);
            this.wrapper.appendChild(this.dropdown);
            this.wrapper.appendChild(this.select);
        }
        
        this.updateDisplay();
        this.renderOptions('');
        this.bindEvents();
    }
    
    updateDisplay() {
        var currentValue = String(this.select.value);
        if (currentValue && currentValue !== '') {
            var selectedOpt = this.options.find(o => String(o.value) === currentValue);
            if (selectedOpt && selectedOpt.value) {
                this.display.textContent = selectedOpt.text;
                this.display.classList.remove('placeholder');
            } else {
                this.display.textContent = this.options[0]?.text || 'Select...';
                this.display.classList.add('placeholder');
            }
        } else {
            this.display.textContent = this.options[0]?.text || 'Select...';
            this.display.classList.add('placeholder');
        }
    }
    
    renderOptions(filter) {
        this.optionsContainer.innerHTML = '';
        var filtered = this.options.filter(o => {
            if (!o.value) return false;
            return o.text.toLowerCase().includes(filter.toLowerCase());
        });
        
        if (filtered.length === 0) {
            this.optionsContainer.innerHTML = '<div class="ss-no-results">No results found</div>';
            return;
        }
        
        filtered.forEach(opt => {
            var div = document.createElement('div');
            div.className = 'ss-option' + (opt.value === this.select.value ? ' selected' : '');
            div.textContent = opt.text;
            div.dataset.value = opt.value;
            div.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectOption(opt);
            });
            this.optionsContainer.appendChild(div);
        });
    }
    
    selectOption(opt) {
        this.select.value = opt.value;
        this.updateDisplay();
        this.close();
        this.select.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    open() {
        if (this.wrapper.classList.contains('open')) return;
        this.wrapper.classList.add('open');
        this.searchInput.value = '';
        this.renderOptions('');
        setTimeout(() => this.searchInput.focus(), 10);
    }
    
    close() {
        this.wrapper.classList.remove('open');
        this.searchInput.value = '';
    }
    
    bindEvents() {
        this.display.addEventListener('click', (e) => { e.stopPropagation(); this.wrapper.classList.contains('open') ? this.close() : this.open(); });
        this.arrow.addEventListener('click', (e) => { e.stopPropagation(); this.wrapper.classList.contains('open') ? this.close() : this.open(); });
        this.searchInput.addEventListener('input', () => this.renderOptions(this.searchInput.value));
        this.dropdown.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', (e) => { if (!this.wrapper.contains(e.target)) this.close(); });
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
            else if (e.key === 'Enter') {
                e.preventDefault();
                var highlighted = this.optionsContainer.querySelector('.ss-option.highlighted') || this.optionsContainer.querySelector('.ss-option');
                if (highlighted) {
                    var opt = this.options.find(o => o.value === highlighted.dataset.value);
                    if (opt) this.selectOption(opt);
                }
            }
        });
    }
}

// Initialize searchable selects
document.addEventListener('DOMContentLoaded', function() {
    var allSelects = document.querySelectorAll('select.form-select');
    allSelects.forEach(function(el) {
        // Skip status select and small selects
        if (el.id !== 'statusSelect' && el.id !== 'rcTerm' && el.id !== 'rcYear') {
            var ss = new SearchableSelect(el);
            el.searchableSelect = ss;
        }
    });
});

// ============ REPORT CARD FUNCTIONS ============
@if($isEdit && $student)
document.addEventListener('DOMContentLoaded', function() {
    loadReportCards();
});

function loadReportCards() {
    fetch('{{ route("admin.studentsponsorship.university-students.report-cards", $student->hash_id) }}')
    .then(r => r.json())
    .then(data => {
        var tbody = document.getElementById('reportCardsBody');
        if (data.success && data.data && data.data.length > 0) {
            var html = '';
            data.data.forEach(function(card) {
                html += '<tr>' +
                    '<td>' + escapeHtml(card.filename) + '</td>' +
                    '<td><span class="term-badge">' + escapeHtml(card.term) + '</span></td>' +
                    '<td>' + escapeHtml(card.semester_end || '-') + '</td>' +
                    '<td>' + escapeHtml(card.upload_date) + '</td>' +
                    '<td>' + escapeHtml(card.file_size) + '</td>' +
                    '<td><div class="action-btns">' +
                        '<a href="{{ url("admin/studentsponsorship/university-students/report-card") }}/' + card.id + '/view" target="_blank" class="btn-action btn-view" title="View"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>' +
                        '<a href="{{ url("admin/studentsponsorship/university-students/report-card") }}/' + card.id + '/download" class="btn-action" style="background:#D1FAE5;color:#065F46;" title="Download"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></a>' +
                        '<button type="button" class="btn-action btn-delete" onclick="deleteReportCard(' + card.id + ')" title="Delete"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>' +
                    '</div></td></tr>';
            });
            tbody.innerHTML = html;
            document.getElementById('rcCountInfo').textContent = data.data.length + ' report card' + (data.data.length !== 1 ? 's' : '');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:24px;">No report cards uploaded yet</td></tr>';
            document.getElementById('rcCountInfo').textContent = '0 report cards';
        }
    })
    .catch(err => {
        document.getElementById('reportCardsBody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:24px;">Failed to load</td></tr>';
    });
}

function uploadReportCard() {
    var btn = document.getElementById('rcUploadBtn');
    var btnText = document.getElementById('rcUploadBtnText');
    var filename = document.getElementById('rcFilename').value.trim();
    var term = document.getElementById('rcTerm').value;
    var year = document.getElementById('rcYear').value;
    var fileInput = document.getElementById('rcFileInput');
    
    if (!filename) { showRcMessage('Please enter a filename', 'error'); return; }
    if (!term) { showRcMessage('Please select a term', 'error'); return; }
    if (!fileInput.files || !fileInput.files[0]) { showRcMessage('Please select a file', 'error'); return; }
    if (fileInput.files[0].size > 10 * 1024 * 1024) { showRcMessage('File size must be less than 10MB', 'error'); return; }
    
    btn.disabled = true;
    btnText.textContent = 'Uploading...';
    
    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('student_hash', '{{ $student->hash_id }}');
    formData.append('filename', filename);
    formData.append('report_card_term', term);
    formData.append('semester_end_year', year);
    formData.append('report_card_file', fileInput.files[0]);
    
    fetch('{{ route("admin.studentsponsorship.university-students.upload-report-card") }}', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showRcMessage('Report card uploaded successfully!', 'success');
            document.getElementById('rcFilename').value = '';
            document.getElementById('rcTerm').value = '';
            document.getElementById('rcFileInput').value = '';
            loadReportCards();
        } else {
            showRcMessage(data.message || 'Upload failed', 'error');
        }
    })
    .catch(err => showRcMessage('Upload failed', 'error'))
    .finally(() => { btn.disabled = false; btnText.textContent = 'Upload'; });
}

function deleteReportCard(id) {
    if (!confirm('Delete this report card?')) return;
    fetch('{{ url("admin/studentsponsorship/university-students/report-card") }}/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showRcMessage('Report card deleted!', 'success'); loadReportCards(); }
        else { showRcMessage(data.message || 'Delete failed', 'error'); }
    })
    .catch(err => showRcMessage('Delete failed', 'error'));
}

function showRcMessage(message, type) {
    var msgDiv = document.getElementById('rcUploadMessage');
    msgDiv.style.display = 'block';
    msgDiv.style.background = type === 'success' ? '#D1FAE5' : '#FEE2E2';
    msgDiv.style.border = '1px solid ' + (type === 'success' ? '#10B981' : '#EF4444');
    msgDiv.style.color = type === 'success' ? '#065F46' : '#991B1B';
    msgDiv.textContent = message;
    setTimeout(() => { msgDiv.style.display = 'none'; }, 5000);
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
@endif
</script>
