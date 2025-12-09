<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* Main Container */
.school-student-form-page {
    padding: 24px;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.form-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-header h1 svg {
    width: 28px;
    height: 28px;
    color: var(--primary);
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-back:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Form Card */
.form-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    overflow: hidden;
}

/* Tab Navigation */
.form-tabs {
    display: flex;
    background: linear-gradient(to bottom, #f8f9fa 0%, #f1f3f4 100%);
    border-bottom: 2px solid var(--card-border);
    overflow-x: auto;
    padding: 0 16px;
}

.form-tab {
    padding: 16px 20px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    border: none;
    background: none;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    position: relative;
    transition: all 0.2s;
}

.form-tab:hover {
    color: var(--primary);
}

.form-tab.active {
    color: var(--primary);
    font-weight: 600;
}

.form-tab.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary);
    border-radius: 3px 3px 0 0;
}

.form-tab svg {
    width: 18px;
    height: 18px;
}

/* Tab Content */
.form-content {
    padding: 32px;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Section Header */
.section-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--primary);
    margin: 0 0 16px 0;
    padding-left: 12px;
    border-left: 4px solid var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title:not(:first-child) {
    margin-top: 32px;
}

.section-title svg {
    width: 18px;
    height: 18px;
}

.section-divider {
    border: none;
    border-top: 1px solid var(--card-border);
    margin: 0 0 24px 0;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.form-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

@media (max-width: 768px) {
    .form-grid, .form-grid-3, .form-grid-4 {
        grid-template-columns: 1fr;
    }
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.form-label .required {
    color: #dc2626;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    font-size: 14px;
    border: 1px solid var(--card-border);
    border-radius: 10px;
    background: var(--card-bg);
    color: var(--text-primary);
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-control:disabled, .form-control[readonly] {
    background: #f3f4f6;
    cursor: not-allowed;
}

.form-control.is-invalid {
    border-color: #dc2626;
}

.invalid-feedback {
    font-size: 12px;
    color: #dc2626;
    margin-top: 4px;
}

.form-hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Select with Add Button */
.select-with-add {
    display: flex;
    gap: 8px;
}

.select-with-add .form-control {
    flex: 1;
}

.select-with-add .select2-container {
    flex: 1;
}

.btn-add-new {
    padding: 12px 14px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.btn-add-new:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Select2 Custom Styling */
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 46px;
    padding: 8px 12px;
    border: 1px solid var(--card-border);
    border-radius: 10px;
    background: var(--card-bg);
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    color: var(--text-primary);
    padding-left: 0;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.select2-dropdown {
    border: 1px solid var(--card-border);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.select2-search--dropdown .select2-search__field {
    padding: 10px 12px;
    border: 1px solid var(--card-border);
    border-radius: 8px;
}

.select2-results__option--highlighted[aria-selected] {
    background-color: var(--primary);
}

/* Input Group */
.input-group {
    display: flex;
}

.input-group-prepend {
    padding: 12px 14px;
    background: #f3f4f6;
    border: 1px solid var(--card-border);
    border-right: none;
    border-radius: 10px 0 0 10px;
    font-size: 14px;
    color: var(--text-muted);
}

.input-group .form-control {
    border-radius: 0 10px 10px 0;
}

/* Current Photo */
.current-photo {
    margin-top: 12px;
}

.current-photo img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid var(--card-border);
}

.current-photo-label {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 6px;
}

/* Static Value Display */
.form-value-static {
    padding: 12px 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
}

/* Sponsor Card */
.sponsor-card {
    background: #f8fafc;
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
}

.sponsor-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.sponsor-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.sponsor-name svg {
    width: 18px;
    height: 18px;
    color: #dc2626;
}

.sponsor-badge {
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 20px;
    background: var(--primary-light);
    color: var(--primary);
}

.sponsor-email {
    font-size: 14px;
    color: var(--primary);
    margin-bottom: 8px;
}

.sponsor-meta {
    font-size: 13px;
    color: var(--text-muted);
}

.no-sponsors-alert {
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 32px;
    text-align: center;
}

.no-sponsors-alert svg {
    width: 48px;
    height: 48px;
    color: #d97706;
    margin-bottom: 12px;
}

.no-sponsors-alert h4 {
    font-size: 16px;
    font-weight: 600;
    color: #92400e;
    margin: 0 0 8px 0;
}

.no-sponsors-alert p {
    font-size: 14px;
    color: #a16207;
    margin: 0;
}

/* Report Cards Table */
.report-cards-table {
    width: 100%;
    border-collapse: collapse;
}

.report-cards-table th,
.report-cards-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--card-border);
}

.report-cards-table th {
    background: #f8fafc;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
}

.report-cards-table td {
    font-size: 14px;
    color: var(--text-primary);
}

.report-cards-table tbody tr:hover {
    background: #f8fafc;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.btn-secondary {
    background: var(--card-bg);
    color: var(--text-primary);
    border: 1px solid var(--card-border);
}

.btn-secondary:hover {
    background: #f3f4f6;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 13px;
}

.btn-icon {
    padding: 8px;
    border-radius: 8px;
}

.btn svg {
    width: 18px;
    height: 18px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    padding: 24px 32px;
    background: #f8fafc;
    border-top: 1px solid var(--card-border);
}

/* Alert */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.alert-info {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--card-bg);
    border-radius: 16px;
    width: 100%;
    max-width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    transform: scale(0.9);
    transition: transform 0.3s;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--card-border);
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    color: var(--text-muted);
    border-radius: 8px;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f3f4f6;
    color: var(--text-primary);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--card-border);
    background: #f8fafc;
}

/* Row utility */
.row-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* Upload area */
.upload-section {
    background: #f8fafc;
    border: 2px dashed var(--card-border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}
</style>

<div class="school-student-form-page">
    {{-- Header --}}
    <div class="form-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            {{ $student ? 'Edit School Student' : 'Register School Student' }}
        </h1>
        <a href="{{ route('admin.studentsponsor.school.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form id="school-student-form" method="POST" enctype="multipart/form-data"
        action="{{ $student ? route('admin.studentsponsor.school.update', $student->id) : route('admin.studentsponsor.school.store') }}">
        @csrf
        @if($student)
            @method('PUT')
        @endif

        <input type="hidden" name="active_tab" id="active_tab" value="{{ old('active_tab', session('active_tab', 'student-info')) }}">
        <input type="hidden" name="calculated_age" id="calculated_age_hidden" value="{{ $student?->school_age }}">

        <div class="form-card">
            {{-- Tab Navigation --}}
            <div class="form-tabs">
                <button type="button" class="form-tab active" data-tab="student-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Student Info
                </button>
                <button type="button" class="form-tab" data-tab="sponsorship">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Sponsorship
                </button>
                <button type="button" class="form-tab" data-tab="bank-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Bank Info
                </button>
                <button type="button" class="form-tab" data-tab="family-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Family Info
                </button>
                <button type="button" class="form-tab" data-tab="additional-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Additional Info
                </button>
                <button type="button" class="form-tab" data-tab="report-cards">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Report Cards
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="form-content">
                {{-- Student Info Tab --}}
                <div class="tab-pane active" id="tab-student-info">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Basic Information
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid">
                        {{-- Left Column --}}
                        <div>
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $student?->name) }}" placeholder="Enter student's full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $student?->email) }}" placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-prepend" id="phone-code-display">
                                        @php
                                            $phoneCode = '+94';
                                            if ($student && $student->country) {
                                                $phoneCode = $student->country->calling_code ?? $student->country->phone_code ?? '+94';
                                            }
                                        @endphp
                                        {{ $phoneCode }}
                                    </span>
                                    <input type="text" name="contact_no" class="form-control"
                                        value="{{ old('contact_no', $student?->contact_no) }}" placeholder="Enter phone number">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Date of Birth <span class="required">*</span></label>
                                <input type="date" name="school_student_dob" id="school_student_dob" class="form-control"
                                    value="{{ old('school_student_dob', $student?->school_student_dob?->format('Y-m-d')) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Age</label>
                                <input type="text" id="calculated-age" class="form-control" readonly
                                    value="{{ $student && $student->school_age ? $student->school_age . ' years' : '' }}"
                                    placeholder="Calculated from DOB">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">
                                @if($student && $student->profile_photo)
                                    <div class="current-photo">
                                        <img src="{{ route('admin.studentsponsor.school.photo', $student->id) }}"
                                            alt="Profile Photo" onerror="this.style.display='none'">
                                        <span class="current-photo-label">Current photo</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <div class="select-with-add">
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            @php
                                                $phoneCode = $country->calling_code ?? $country->phone_code ?? '';
                                            @endphp
                                            <option value="{{ $country->id }}"
                                                data-phone-code="{{ $phoneCode }}"
                                                {{ old('country_id', $student?->country_id) == $country->id ? 'selected' : '' }}>
                                                {{ $country->short_name ?? $country->name }}{{ $phoneCode ? ' ('.$phoneCode.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-add-new" onclick="openModal('countryModal')" title="Add Country">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                                            <path d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Complete address">{{ old('address', $student?->address) }}</textarea>
                            </div>

                            <div class="row-2">
                                <div class="form-group">
                                    <label class="form-label">City / District</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city', $student?->city) }}" placeholder="City / District">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" name="zip" class="form-control"
                                        value="{{ old('zip', $student?->zip) }}" placeholder="Postal code">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">School Student ID</label>
                                <input type="text" name="school_id" class="form-control"
                                    value="{{ old('school_id', $student?->school_id) }}" placeholder="School's student ID">
                            </div>

                            @if($student && $student->school_internal_id)
                                <div class="form-group">
                                    <label class="form-label">Internal Student ID</label>
                                    <div class="form-value-static">{{ $student->school_internal_id }}</div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="form-label">Edit Internal ID</label>
                                <input type="text" name="school_internal_id" class="form-control"
                                    value="{{ old('school_internal_id', $student?->school_internal_id) }}" placeholder="Internal tracking ID">
                                <div class="form-hint">Admin only: Modify internal tracking ID</div>
                            </div>
                        </div>
                    </div>

                    {{-- School Information --}}
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        </svg>
                        School Information
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Grade / Class</label>
                                <select name="school_grade" id="school_grade" class="form-control">
                                    <option value="">Select Grade</option>
                                    @for($g = 1; $g <= 10; $g++)
                                        <option value="{{ $g }}" {{ old('school_grade', $student?->school_grade) == $g ? 'selected' : '' }}>
                                            Grade {{ $g }}
                                        </option>
                                    @endfor
                                    <option value="O/L" {{ old('school_grade', $student?->school_grade) == 'O/L' ? 'selected' : '' }}>O/L (Grade 11)</option>
                                    <option value="A/L1" {{ old('school_grade', $student?->school_grade) == 'A/L1' ? 'selected' : '' }}>A/L1 (Grade 12)</option>
                                    <option value="A/L2" {{ old('school_grade', $student?->school_grade) == 'A/L2' ? 'selected' : '' }}>A/L2 (Grade 13)</option>
                                    <option value="A/L Final" {{ old('school_grade', $student?->school_grade) == 'A/L Final' ? 'selected' : '' }}>A/L Final (Grade 14)</option>
                                </select>
                            </div>

                            <div class="form-group" id="grade-mismatch-group" style="display: none;">
                                <label class="form-label">Grade Mismatch Reason <span class="required">*</span></label>
                                <textarea name="grade_mismatch_reason" id="grade_mismatch_reason" class="form-control" rows="3"
                                    placeholder="Explain why age doesn't match the grade">{{ old('grade_mismatch_reason', $student?->grade_mismatch_reason) }}</textarea>
                                <div class="form-hint">Required when age doesn't match the typical age for the grade.</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">School Type</label>
                                <select name="school_type" id="school_type" class="form-control">
                                    <option value="">Select School Type</option>
                                    @foreach(['Type 1AB', 'Type 1C', 'Type 2', 'Type 3'] as $type)
                                        <option value="{{ $type }}" {{ old('school_type', $student?->school_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">School Name</label>
                                <div class="select-with-add">
                                    <select name="school_name_id" id="school_name_id" class="form-control">
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}"
                                                data-name="{{ $school->name }}"
                                                {{ old('school_name_id', $student?->school_name_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-add-new" onclick="openModal('schoolModal')" title="Add School">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                                            <path d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Grade Year</label>
                                <input type="number" name="school_grade_year" class="form-control"
                                    value="{{ old('school_grade_year', $student?->school_grade_year) }}" placeholder="e.g. 2025">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sponsorship Tab --}}
                <div class="tab-pane" id="tab-sponsorship">
                    @if($student && count($sponsorHistory ?? []) > 0)
                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Current Sponsors
                        </h3>
                        <hr class="section-divider">

                        @foreach($sponsorHistory as $sponsor)
                            <div class="sponsor-card">
                                <div class="sponsor-card-header">
                                    <div class="sponsor-name">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        {{ $sponsor['sponsor_name'] }}
                                        @if(!empty($sponsor['sponsor_type']))
                                            <span class="sponsor-badge">{{ $sponsor['sponsor_type'] }}</span>
                                        @endif
                                    </div>
                                    <span class="sponsor-badge">Direct Assignment</span>
                                </div>
                                @if(!empty($sponsor['sponsor_email']))
                                    <div class="sponsor-email">
                                        <a href="mailto:{{ $sponsor['sponsor_email'] }}">{{ $sponsor['sponsor_email'] }}</a>
                                    </div>
                                @endif
                                <div class="sponsor-meta">
                                    <strong>Relationship:</strong> {{ ucfirst($sponsor['relationship_type'] ?? 'Direct') }} Sponsorship
                                    @if(!empty($sponsor['sponsorship_start']))
                                        | <strong>Start:</strong> {{ \Carbon\Carbon::parse($sponsor['sponsorship_start'])->format('M d, Y') }}
                                    @endif
                                    @if(!empty($sponsor['sponsorship_end']))
                                        | <strong>End:</strong> {{ \Carbon\Carbon::parse($sponsor['sponsorship_end'])->format('M d, Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-sponsors-alert">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h4>No Sponsors Assigned</h4>
                            <p>This student does not have any sponsors yet.</p>
                        </div>
                    @endif

                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"></path>
                        </svg>
                        Assign Sponsor
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Select Sponsor</label>
                                <select name="sponsor_id" id="sponsor_id" class="form-control">
                                    <option value="">Select Sponsor</option>
                                    @foreach($sponsors as $sponsor)
                                        <option value="{{ $sponsor->id }}" {{ old('sponsor_id', $student?->sponsor_id) == $sponsor->id ? 'selected' : '' }}>
                                            {{ $sponsor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Sponsorship Start Date</label>
                                <input type="date" name="school_sponsorship_start_date" class="form-control"
                                    value="{{ old('school_sponsorship_start_date', $student?->school_sponsorship_start_date?->format('Y-m-d')) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Sponsorship End Date</label>
                                <input type="date" name="school_sponsorship_end_date" class="form-control"
                                    value="{{ old('school_sponsorship_end_date', $student?->school_sponsorship_end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Introduced By</label>
                                <input type="text" name="school_introducedby" class="form-control"
                                    value="{{ old('school_introducedby', $student?->school_introducedby) }}" placeholder="Person who introduced the student">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Introducer's Phone</label>
                                <input type="text" name="school_introducedph" class="form-control"
                                    value="{{ old('school_introducedph', $student?->school_introducedph) }}" placeholder="Contact number">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank Info Tab --}}
                <div class="tab-pane" id="tab-bank-info">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Bank Information
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Bank Name</label>
                                <div class="select-with-add">
                                    <select name="bank_id" id="bank_id" class="form-control">
                                        <option value="">Select Bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank_id', $student?->bank_id) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-add-new" onclick="openModal('bankModal')" title="Add Bank">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                                            <path d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bank Account Number</label>
                                <input type="text" name="school_bank_account_no" class="form-control @error('school_bank_account_no') is-invalid @enderror"
                                    value="{{ old('school_bank_account_no', $student?->school_bank_account_no) }}" placeholder="Account number">
                                @error('school_bank_account_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Bank Branch Number</label>
                                <input type="text" name="school_bank_branch_number" class="form-control"
                                    value="{{ old('school_bank_branch_number', $student?->school_bank_branch_number) }}" placeholder="Branch code/number">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bank Branch Information</label>
                                <textarea name="school_bank_branch_info" class="form-control" rows="3" placeholder="Additional branch details">{{ old('school_bank_branch_info', $student?->school_bank_branch_info) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Family Info Tab --}}
                <div class="tab-pane" id="tab-family-info">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Family Information
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid-3">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="school_father_name" class="form-control"
                                    value="{{ old('school_father_name', $student?->school_father_name) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Father's Income</label>
                                <input type="number" step="0.01" name="school_father_income" class="form-control"
                                    value="{{ old('school_father_income', $student?->school_father_income) }}" placeholder="Monthly income">
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" name="school_mother_name" class="form-control"
                                    value="{{ old('school_mother_name', $student?->school_mother_name) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mother's Income</label>
                                <input type="number" step="0.01" name="school_mother_income" class="form-control"
                                    value="{{ old('school_mother_income', $student?->school_mother_income) }}" placeholder="Monthly income">
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label class="form-label">Guardian's Name</label>
                                <input type="text" name="school_guardian_name" class="form-control"
                                    value="{{ old('school_guardian_name', $student?->school_guardian_name) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Guardian's Income</label>
                                <input type="number" step="0.01" name="school_guardian_income" class="form-control"
                                    value="{{ old('school_guardian_income', $student?->school_guardian_income) }}" placeholder="Monthly income">
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Background Information</label>
                        <textarea name="background_info" class="form-control" rows="4"
                            placeholder="Student background, family situation, etc.">{{ old('background_info', $student?->background_info) }}</textarea>
                    </div>
                </div>

                {{-- Additional Info Tab --}}
                <div class="tab-pane" id="tab-additional-info">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Comments
                    </h3>
                    <hr class="section-divider">

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Internal Comment</label>
                            <textarea name="internal_comment" class="form-control" rows="5"
                                placeholder="Internal notes (not visible to sponsors)">{{ old('internal_comment', $student?->internal_comment) }}</textarea>
                            <div class="form-hint">These comments are only visible to staff/administrators.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">External Comment</label>
                            <textarea name="external_comment" class="form-control" rows="5"
                                placeholder="Comments visible to sponsors">{{ old('external_comment', $student?->external_comment) }}</textarea>
                            <div class="form-hint">These comments may be visible to sponsors and other stakeholders.</div>
                        </div>
                    </div>
                </div>

                {{-- Report Cards Tab --}}
                <div class="tab-pane" id="tab-report-cards">
                    @if($student)
                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Report Card
                        </h3>
                        <hr class="section-divider">

                        <input type="hidden" id="rc_student_school_id" value="{{ $student->id }}">

                        <div class="upload-section">
                            <div class="form-grid-3">
                                <div class="form-group">
                                    <label class="form-label">Filename</label>
                                    <input type="text" id="rc_filename" class="form-control" placeholder="e.g. Term 1 Report - 2025">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Term</label>
                                    <select id="rc_term" class="form-control">
                                        <option value="">Select Term</option>
                                        <option value="Term1">Term 1</option>
                                        <option value="Term2">Term 2</option>
                                        <option value="Term3">Term 3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Upload Date</label>
                                    <input type="date" id="rc_upload_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Report Card File (PDF, Image)</label>
                                <input type="file" id="report_card_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif">
                            </div>

                            <button type="button" class="btn btn-primary" id="uploadReportCardBtn">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Upload Report Card
                            </button>
                        </div>

                        <h3 class="section-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Report Cards
                        </h3>
                        <hr class="section-divider">

                        <table class="report-cards-table" id="reportCardsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Filename</th>
                                    <th>Term</th>
                                    <th>Upload Date</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="6" style="text-align: center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Please save the student first before uploading report cards.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btn-save-student">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                        <path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    {{ $student ? 'Update Student' : 'Register Student' }}
                </button>
                <a href="{{ route('admin.studentsponsor.school.index') }}" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Add Country Modal --}}
<div class="modal-overlay" id="countryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Add New Country</h3>
            <button type="button" class="modal-close" onclick="closeModal('countryModal')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Country Name <span class="required">*</span></label>
                <input type="text" id="modal_country_name" class="form-control" placeholder="e.g., Sri Lanka">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Code <span class="required">*</span></label>
                <input type="text" id="modal_country_phone" class="form-control" placeholder="+94">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('countryModal')">Cancel</button>
            <button type="button" class="btn btn-primary" id="btnAddCountry">Add Country</button>
        </div>
    </div>
</div>

{{-- Add School Modal --}}
<div class="modal-overlay" id="schoolModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Add New School</h3>
            <button type="button" class="modal-close" onclick="closeModal('schoolModal')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">School Name <span class="required">*</span></label>
                <input type="text" id="modal_school_name" class="form-control" placeholder="Enter school name">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('schoolModal')">Cancel</button>
            <button type="button" class="btn btn-primary" id="btnAddSchool">Add School</button>
        </div>
    </div>
</div>

{{-- Add Bank Modal --}}
<div class="modal-overlay" id="bankModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Add New Bank</h3>
            <button type="button" class="modal-close" onclick="closeModal('bankModal')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Bank Name <span class="required">*</span></label>
                <input type="text" id="modal_bank_name" class="form-control" placeholder="Enter bank name">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('bankModal')">Cancel</button>
            <button type="button" class="btn btn-primary" id="btnAddBank">Add Bank</button>
        </div>
    </div>
</div>

{{-- Age Validation Modal --}}
<div class="modal-overlay" id="ageValidationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24" height="24" style="color: #d97706;">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Age Validation Required
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('ageValidationModal')">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <p><strong>Warning:</strong> <span id="age-validation-message"></span></p>
            <p>Please provide a reason for this grade/age mismatch to continue.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="closeModal('ageValidationModal')">I'll Provide Reason</button>
        </div>
    </div>
</div>

<!-- jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
(function() {
    // Initialize Select2 on dropdowns
    $(document).ready(function() {
        $('#country_id, #school_name_id, #bank_id, #school_grade, #school_type, #sponsor_id').select2({
            allowClear: true,
            width: '100%'
        });
    });

    // Grade to age mapping
    const gradeAgeMapping = {
        '1': {min: 5, max: 6, name: 'Grade 1'},
        '2': {min: 6, max: 7, name: 'Grade 2'},
        '3': {min: 7, max: 8, name: 'Grade 3'},
        '4': {min: 8, max: 9, name: 'Grade 4'},
        '5': {min: 9, max: 10, name: 'Grade 5'},
        '6': {min: 10, max: 11, name: 'Grade 6'},
        '7': {min: 11, max: 12, name: 'Grade 7'},
        '8': {min: 12, max: 13, name: 'Grade 8'},
        '9': {min: 13, max: 14, name: 'Grade 9'},
        '10': {min: 14, max: 15, name: 'Grade 10'},
        'O/L': {min: 15, max: 16, name: 'O/L (Grade 11)'},
        'A/L1': {min: 16, max: 17, name: 'A/L1 (Grade 12)'},
        'A/L2': {min: 17, max: 18, name: 'A/L2 (Grade 13)'},
        'A/L Final': {min: 18, max: 19, name: 'A/L Final (Grade 14)'}
    };

    // Tab Navigation
    document.querySelectorAll('.form-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            
            document.getElementById('active_tab').value = tabId;

            if (tabId === 'report-cards') {
                loadReportCards();
            }
        });
    });

    // Modal functions
    window.openModal = function(id) {
        document.getElementById(id).classList.add('show');
    };

    window.closeModal = function(id) {
        document.getElementById(id).classList.remove('show');
    };

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });

    // Calculate age from DOB
    function calculateAge() {
        const dob = document.getElementById('school_student_dob').value;
        if (!dob) {
            document.getElementById('calculated-age').value = '';
            document.getElementById('calculated_age_hidden').value = '';
            return null;
        }
        
        const today = new Date();
        const birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        document.getElementById('calculated-age').value = age + ' years';
        document.getElementById('calculated_age_hidden').value = age;
        
        validateAgeGrade();
        return age;
    }

    // Validate age-grade combination
    function validateAgeGrade() {
        const grade = document.getElementById('school_grade').value;
        const age = parseInt(document.getElementById('calculated_age_hidden').value);
        const gradeReason = document.getElementById('grade_mismatch_reason').value.trim();
        const mismatchGroup = document.getElementById('grade-mismatch-group');
        
        document.getElementById('school_grade').classList.remove('is-invalid');
        mismatchGroup.style.display = 'none';
        document.getElementById('grade_mismatch_reason').required = false;

        if (!grade || !age || age <= 0 || !gradeAgeMapping[grade]) {
            return true;
        }

        const expected = gradeAgeMapping[grade];
        
        if (age >= expected.min && age <= expected.max) {
            return true;
        }

        if (age < expected.min) {
            const message = `Student is too young for ${expected.name}. Age ${age} is below minimum required age of ${expected.min} years.`;
            document.getElementById('age-validation-message').textContent = message;
            openModal('ageValidationModal');
            document.getElementById('school_grade').classList.add('is-invalid');
            return false;
        }

        if (age === (expected.max + 1)) {
            mismatchGroup.style.display = 'block';
            document.getElementById('grade_mismatch_reason').required = true;
            
            if (!gradeReason) {
                const message = `Age ${age} is one year older than typical for ${expected.name} (expected ${expected.min}-${expected.max} years). Please provide a grade mismatch reason.`;
                document.getElementById('age-validation-message').textContent = message;
                openModal('ageValidationModal');
                return false;
            }
            return true;
        }

        if (age > (expected.max + 1)) {
            const message = `Student is too old for ${expected.name}. Age ${age} exceeds maximum allowed age of ${expected.max + 1} years.`;
            document.getElementById('age-validation-message').textContent = message;
            openModal('ageValidationModal');
            document.getElementById('school_grade').classList.add('is-invalid');
            return false;
        }

        return true;
    }

    document.getElementById('school_student_dob').addEventListener('change', calculateAge);
    $('#school_grade').on('change', validateAgeGrade);
    document.getElementById('grade_mismatch_reason').addEventListener('input', validateAgeGrade);

    // Country selection - update phone code (Select2 compatible)
    $('#country_id').on('change', function() {
        var selected = $(this).find(':selected');
        var phoneCode = selected.data('phone-code') || '+94';
        document.getElementById('phone-code-display').textContent = phoneCode;
    });

    // Add School (Fixed AJAX)
    document.getElementById('btnAddSchool').addEventListener('click', function() {
        const name = document.getElementById('modal_school_name').value.trim();
        if (!name) {
            alert('Enter school name');
            return;
        }

        this.disabled = true;
        this.textContent = 'Adding...';

        fetch('{{ route("admin.studentsponsor.school.add-school-name") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var newOption = new Option(data.name, data.id, true, true);
                $('#school_name_id').append(newOption).trigger('change');
                closeModal('schoolModal');
                document.getElementById('modal_school_name').value = '';
                alert('School added successfully');
            } else {
                alert(data.message || 'Failed to add school');
            }
        })
        .catch(() => alert('Error adding school'))
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Add School';
        });
    });

    // Add Bank (Fixed AJAX)
    document.getElementById('btnAddBank').addEventListener('click', function() {
        const name = document.getElementById('modal_bank_name').value.trim();
        if (!name) {
            alert('Enter bank name');
            return;
        }

        this.disabled = true;
        this.textContent = 'Adding...';

        fetch('{{ route("admin.studentsponsor.school.add-bank") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var newOption = new Option(data.name, data.id, true, true);
                $('#bank_id').append(newOption).trigger('change');
                closeModal('bankModal');
                document.getElementById('modal_bank_name').value = '';
                alert('Bank added successfully');
            } else {
                alert(data.message || 'Failed to add bank');
            }
        })
        .catch(() => alert('Error adding bank'))
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Add Bank';
        });
    });

    // Add Country (Fixed AJAX)
    document.getElementById('btnAddCountry').addEventListener('click', function() {
        const name = document.getElementById('modal_country_name').value.trim();
        const phone = document.getElementById('modal_country_phone').value.trim();
        if (!name || !phone) {
            alert('Country name and phone code are required');
            return;
        }

        this.disabled = true;
        this.textContent = 'Adding...';

        fetch('{{ route("admin.studentsponsor.school.add-country") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name, phone_code: phone })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var newOption = new Option(data.name + ' (' + data.phone_code + ')', data.id, true, true);
                $(newOption).attr('data-phone-code', data.phone_code);
                $('#country_id').append(newOption).trigger('change');
                document.getElementById('phone-code-display').textContent = data.phone_code;
                closeModal('countryModal');
                document.getElementById('modal_country_name').value = '';
                document.getElementById('modal_country_phone').value = '';
                alert('Country added successfully');
            } else {
                alert(data.message || 'Failed to add country');
            }
        })
        .catch(() => alert('Error adding country'))
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Add Country';
        });
    });

    // Load Report Cards
    function loadReportCards() {
        const studentId = document.getElementById('rc_student_school_id')?.value;
        if (!studentId) return;

        fetch('{{ url("admin/studentsponsor/school") }}/' + studentId + '/report-cards')
            .then(r => r.json())
            .then(data => {
                const tbody = document.querySelector('#reportCardsTable tbody');
                tbody.innerHTML = '';

                if (data.success && data.report_cards && data.report_cards.length) {
                    data.report_cards.forEach((card, i) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${i + 1}</td>
                            <td><a href="${card.download_url}" target="_blank">${card.filename}</a></td>
                            <td>${card.term || 'N/A'}</td>
                            <td>${card.upload_date || ''}</td>
                            <td>${card.file_size ? card.file_size + ' bytes' : ''}</td>
                            <td>
                                <a href="${card.download_url}" class="btn btn-sm btn-secondary btn-icon" title="Download">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16">
                                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="deleteReportCard(${card.id})" title="Delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No report cards uploaded yet.</td></tr>';
                }
            })
            .catch(() => {
                document.querySelector('#reportCardsTable tbody').innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Error loading report cards.</td></tr>';
            });
    }

    // Upload Report Card
    document.getElementById('uploadReportCardBtn')?.addEventListener('click', function() {
        const filename = document.getElementById('rc_filename').value;
        const term = document.getElementById('rc_term').value;
        const uploadDate = document.getElementById('rc_upload_date').value;
        const fileInput = document.getElementById('report_card_file');
        const studentId = document.getElementById('rc_student_school_id').value;

        if (!filename) { alert('Please enter a filename'); return; }
        if (!term) { alert('Please select a term'); return; }
        if (!uploadDate) { alert('Please select upload date'); return; }
        if (!fileInput.files[0]) { alert('Please select a file'); return; }

        const formData = new FormData();
        formData.append('student_school_id', studentId);
        formData.append('filename', filename);
        formData.append('term', term);
        formData.append('upload_date', uploadDate);
        formData.append('report_card_file', fileInput.files[0]);

        this.disabled = true;
        this.textContent = 'Uploading...';

        fetch('{{ route("admin.studentsponsor.school.upload-report-card") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Report card uploaded successfully');
                document.getElementById('rc_filename').value = '';
                document.getElementById('rc_term').value = '';
                document.getElementById('report_card_file').value = '';
                loadReportCards();
            } else {
                alert(data.message || 'Upload failed');
            }
        })
        .catch(() => alert('Error uploading file'))
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> Upload Report Card';
        });
    });

    // Delete Report Card
    window.deleteReportCard = function(id) {
        if (!confirm('Are you sure you want to delete this report card?')) return;

        fetch('{{ url("admin/studentsponsor/school/report-card") }}/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Report card deleted successfully');
                loadReportCards();
            } else {
                alert(data.message || 'Delete failed');
            }
        })
        .catch(() => alert('Error deleting report card'));
    };

    // Form submission
    document.getElementById('school-student-form').addEventListener('submit', function(e) {
        const activeTab = document.querySelector('.form-tab.active').dataset.tab;
        document.getElementById('active_tab').value = activeTab;

        if (!validateAgeGrade()) {
            e.preventDefault();
            document.querySelector('.form-tab[data-tab="student-info"]').click();
            return false;
        }

        document.getElementById('btn-save-student').disabled = true;
        document.getElementById('btn-save-student').textContent = 'Saving...';
    });

    // Restore active tab on page load
    const initialTab = '{{ old("active_tab", session("active_tab", "student-info")) }}';
    if (initialTab) {
        const tabBtn = document.querySelector(`.form-tab[data-tab="${initialTab}"]`);
        if (tabBtn) tabBtn.click();
    }

    // Calculate age on load
    calculateAge();

    // Profile photo preview
    document.getElementById('profile_photo')?.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            let photoDiv = document.querySelector('.current-photo');
            if (!photoDiv) {
                photoDiv = document.createElement('div');
                photoDiv.className = 'current-photo';
                photoDiv.innerHTML = '<img src="" alt="Preview"><span class="current-photo-label">New photo preview</span>';
                document.getElementById('profile_photo').parentNode.appendChild(photoDiv);
            }
            photoDiv.querySelector('img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
})();
</script>