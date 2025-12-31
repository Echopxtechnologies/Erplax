@php 
    $transaction = $transaction ?? null;
@endphp
<style>
    .form-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-back { padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 600; }

    .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; margin-bottom: 20px; }
    .section-title { font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px; }
    .section-title svg { width: 20px; height: 20px; }

    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-row-3 { grid-template-columns: repeat(3, 1fr); }
    .form-group { margin-bottom: 0; position: relative; }
    .form-group.full-width { grid-column: span 2; }

    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); box-sizing: border-box; }
    .form-input:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
    .form-textarea { min-height: 80px; resize: vertical; }
    .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
    .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--card-border); }
    .btn-submit { padding: 12px 32px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { transform: translateY(-2px); }
    .btn-cancel { padding: 12px 24px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; font-size: 15px; font-weight: 600; color: var(--text-secondary); text-decoration: none; }

    .alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
    .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
    .alert-danger ul { margin: 8px 0 0 20px; }

    /* Searchable Select */
    .searchable-select { position: relative; }
    .searchable-select .ss-display { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); cursor: pointer; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; min-height: 42px; }
    .searchable-select .ss-display:hover { border-color: var(--primary); }
    .searchable-select .ss-display.open { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); border-radius: 8px 8px 0 0; }
    .searchable-select .ss-arrow { transition: transform 0.2s; }
    .searchable-select .ss-display.open .ss-arrow { transform: rotate(180deg); }
    .searchable-select .ss-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg); border: 1px solid var(--primary); border-top: none; border-radius: 0 0 8px 8px; max-height: 250px; overflow: hidden; display: none; z-index: 99999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .searchable-select .ss-dropdown.show { display: block; }
    .searchable-select .ss-search { padding: 10px; border-bottom: 1px solid var(--card-border); }
    .searchable-select .ss-search input { width: 100%; padding: 8px 12px; border: 1px solid var(--input-border); border-radius: 6px; font-size: 14px; background: var(--input-bg); color: var(--input-text); box-sizing: border-box; }
    .searchable-select .ss-options { max-height: 180px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 14px; cursor: pointer; font-size: 14px; }
    .searchable-select .ss-option:hover, .searchable-select .ss-option.highlighted { background: var(--primary-light); color: var(--primary); }
    .searchable-select .ss-option.selected { background: var(--primary); color: #fff; }
    .searchable-select .ss-no-results { padding: 12px 14px; color: var(--text-muted); font-size: 14px; text-align: center; }

    /* Radio Group */
    .radio-group { display: flex; flex-wrap: wrap; gap: 10px; }
    .radio-option { display: flex; align-items: center; gap: 8px; padding: 10px 16px; border: 1px solid var(--input-border); border-radius: 8px; cursor: pointer; transition: all 0.2s; }
    .radio-option:hover { border-color: var(--primary); background: var(--primary-light); }
    .radio-option.selected { border-color: var(--primary); background: var(--primary); color: #fff; }
    .radio-option input { display: none; }

    /* Toggle */
    .toggle-wrapper { display: flex; align-items: center; gap: 12px; }
    .toggle { position: relative; width: 50px; height: 26px; background: #ccc; border-radius: 13px; cursor: pointer; transition: 0.3s; }
    .toggle.active { background: var(--primary); }
    .toggle::after { content: ''; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; background: #fff; border-radius: 50%; transition: 0.3s; }
    .toggle.active::after { left: 27px; }

    /* Amount Display */
    .amount-display { font-size: 18px; font-weight: 700; color: var(--success); padding: 10px 14px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; }

    @media (max-width: 768px) {
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
        .radio-group { flex-direction: column; }
    }
</style>

<div class="form-page">
    <div class="page-header">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
            {{ $isEdit ? 'Edit Transaction' : 'New Transaction' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.transactions.index') }}" class="btn-back">← Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.studentsponsorship.transactions.update', $transaction->id) : route('admin.studentsponsorship.transactions.store') }}" method="POST" id="transactionForm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <!-- Sponsor & Student -->
        <div class="form-card">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                Sponsor & Student
            </h3>

            <div class="form-row">
                <!-- Sponsor Searchable -->
                <div class="form-group">
                    <label class="form-label">Sponsor <span class="required">*</span></label>
                    <div class="searchable-select" id="sponsorSelect">
                        <div class="ss-display" onclick="toggleDropdown('sponsorSelect')">
                            <span class="ss-text">Select Sponsor</span>
                            <span class="ss-arrow">▼</span>
                        </div>
                        <div class="ss-dropdown">
                            <div class="ss-search">
                                <input type="text" placeholder="Search sponsor..." oninput="filterOptions('sponsorSelect', this.value)">
                            </div>
                            <div class="ss-options">
                                @foreach($sponsors as $sponsor)
                                    <div class="ss-option" data-value="{{ $sponsor->id }}" data-search="{{ strtolower($sponsor->name . ' ' . $sponsor->sponsor_internal_id) }}" onclick="selectOption('sponsorSelect', '{{ $sponsor->id }}', '{{ addslashes($sponsor->name) }} ({{ $sponsor->sponsor_internal_id }})')">
                                        {{ $sponsor->name }} ({{ $sponsor->sponsor_internal_id }})
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="sponsor_id" id="sponsor_id" value="{{ old('sponsor_id', $transaction?->sponsor_id ?? $selectedSponsor?->id) }}" required>
                    </div>
                    @error('sponsor_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="form-row">
                <!-- School Student Searchable -->
                <div class="form-group" id="schoolStudentGroup">
                    <label class="form-label">School Student</label>
                    <div class="searchable-select" id="schoolStudentSelect">
                        <div class="ss-display" onclick="toggleDropdown('schoolStudentSelect')">
                            <span class="ss-text">Select School Student</span>
                            <span class="ss-arrow">▼</span>
                        </div>
                        <div class="ss-dropdown">
                            <div class="ss-search">
                                <input type="text" placeholder="Search student..." oninput="filterOptions('schoolStudentSelect', this.value)">
                            </div>
                            <div class="ss-options">
                                @foreach($schoolStudents as $student)
                                    <div class="ss-option" data-value="{{ $student->id }}" data-search="{{ strtolower($student->full_name . ' ' . $student->school_student_id) }}" onclick="selectOption('schoolStudentSelect', '{{ $student->id }}', '{{ addslashes($student->full_name) }} ({{ $student->school_student_id }})')">
                                        {{ $student->full_name }} ({{ $student->school_student_id }})
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="school_student_id" id="school_student_id" value="{{ old('school_student_id', $transaction?->school_student_id) }}">
                    </div>
                </div>

                <!-- University Student Searchable -->
                <div class="form-group" id="universityStudentGroup">
                    <label class="form-label">University Student</label>
                    <div class="searchable-select" id="universityStudentSelect">
                        <div class="ss-display" onclick="toggleDropdown('universityStudentSelect')">
                            <span class="ss-text">Select University Student</span>
                            <span class="ss-arrow">▼</span>
                        </div>
                        <div class="ss-dropdown">
                            <div class="ss-search">
                                <input type="text" placeholder="Search student..." oninput="filterOptions('universityStudentSelect', this.value)">
                            </div>
                            <div class="ss-options">
                                @foreach($universityStudents as $student)
                                    <div class="ss-option" data-value="{{ $student->id }}" data-search="{{ strtolower($student->name . ' ' . $student->university_internal_id) }}" onclick="selectOption('universityStudentSelect', '{{ $student->id }}', '{{ addslashes($student->name) }} ({{ $student->university_internal_id }})')">
                                        {{ $student->name }} ({{ $student->university_internal_id }})
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="university_student_id" id="university_student_id" value="{{ old('university_student_id', $transaction?->university_student_id) }}">
                    </div>
                </div>
            </div>
            <div class="form-hint" style="margin-top:-10px;margin-bottom:10px;padding:0 4px;">
                You can select one student, both students, or leave empty for general donation. Sponsorship is confirmed when payment is completed.
            </div>
        </div>

        <!-- Amount & Currency -->
        <div class="form-card">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Amount & Payment
            </h3>

            <div class="form-row form-row-3">
                <div class="form-group">
                    <label class="form-label">Total Amount <span class="required">*</span></label>
                    <input type="number" name="total_amount" class="form-input" step="0.01" min="0.01" value="{{ old('total_amount', $transaction?->total_amount) }}" placeholder="0.00" required>
                    @error('total_amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                @if($isEdit)
                <div class="form-group">
                    <label class="form-label">Amount Paid</label>
                    <div class="amount-display">{{ $transaction->formatted_paid ?? 'Rs.0.00' }}</div>
                    <div class="form-hint">Auto-updated from Payments</div>
                </div>
                @endif

                <!-- Currency Searchable -->
                <div class="form-group">
                    <label class="form-label">Currency <span class="required">*</span></label>
                    <div class="searchable-select" id="currencySelect">
                        <div class="ss-display" onclick="toggleDropdown('currencySelect')">
                            <span class="ss-text">Select Currency</span>
                            <span class="ss-arrow">▼</span>
                        </div>
                        <div class="ss-dropdown">
                            <div class="ss-search">
                                <input type="text" placeholder="Search currency..." oninput="filterOptions('currencySelect', this.value)">
                            </div>
                            <div class="ss-options">
                                <div class="ss-option" data-value="LKR" data-search="lkr sri lankan rupees" onclick="selectOption('currencySelect', 'LKR', 'Sri Lankan Rupees (LKR)')">Sri Lankan Rupees (LKR)</div>
                                <div class="ss-option" data-value="USD" data-search="usd us dollars american" onclick="selectOption('currencySelect', 'USD', 'US Dollars (USD)')">US Dollars (USD)</div>
                                <div class="ss-option" data-value="CAD" data-search="cad canadian dollars" onclick="selectOption('currencySelect', 'CAD', 'Canadian Dollars (CAD)')">Canadian Dollars (CAD)</div>
                                <div class="ss-option" data-value="GBP" data-search="gbp uk pounds british sterling" onclick="selectOption('currencySelect', 'GBP', 'UK Pounds (GBP)')">UK Pounds (GBP)</div>
                                <div class="ss-option" data-value="AUD" data-search="aud australian dollars" onclick="selectOption('currencySelect', 'AUD', 'Australian Dollars (AUD)')">Australian Dollars (AUD)</div>
                            </div>
                        </div>
                        <input type="hidden" name="currency" id="currency" value="{{ old('currency', $transaction?->currency ?? 'LKR') }}" required>
                    </div>
                    <div class="form-hint">Currency stored as 3-letter code (e.g., LKR, USD)</div>
                </div>
            </div>

            <div class="form-row">
                <!-- Payment Type -->
                <div class="form-group full-width">
                    <label class="form-label">Payment Type <span class="required">*</span></label>
                    <div class="radio-group" id="paymentTypeGroup">
                        <label class="radio-option" data-value="one_time" onclick="selectPaymentType('one_time')">
                            <input type="radio" name="payment_type" value="one_time">
                            One time
                        </label>
                        <label class="radio-option" data-value="monthly" onclick="selectPaymentType('monthly')">
                            <input type="radio" name="payment_type" value="monthly">
                            Monthly
                        </label>
                        <label class="radio-option" data-value="quarterly" onclick="selectPaymentType('quarterly')">
                            <input type="radio" name="payment_type" value="quarterly">
                            Quarterly
                        </label>
                        <label class="radio-option" data-value="yearly" onclick="selectPaymentType('yearly')">
                            <input type="radio" name="payment_type" value="yearly">
                            Yearly
                        </label>
                        <label class="radio-option" data-value="custom" onclick="selectPaymentType('custom')">
                            <input type="radio" name="payment_type" value="custom">
                            Custom
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Schedule -->
        <div class="form-card">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                Payment Schedule
            </h3>

            <div class="form-row form-row-3">
                <div class="form-group">
                    <label class="form-label">Last Payment Date</label>
                    <input type="date" name="last_payment_date" class="form-input" value="{{ old('last_payment_date', $transaction?->last_payment_date?->format('Y-m-d')) }}" {{ $isEdit ? 'readonly' : '' }}>
                    @if($isEdit)<div class="form-hint">Auto-updated from payments</div>@endif
                </div>
                <div class="form-group">
                    <label class="form-label">Next Payment Due</label>
                    <input type="date" name="next_payment_due" class="form-input" value="{{ old('next_payment_due', $transaction?->next_payment_due?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Days Before Due</label>
                    <input type="number" name="days_before_due" class="form-input" min="1" max="90" value="{{ old('days_before_due', $transaction?->days_before_due ?? 7) }}">
                    <div class="form-hint">For reminder emails</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Due Reminder Active</label>
                    <div class="toggle-wrapper">
                        <div class="toggle {{ old('due_reminder_active', $transaction?->due_reminder_active) ? 'active' : '' }}" onclick="toggleSwitch(this, 'due_reminder_active')"></div>
                        <span id="due_reminder_active_label">{{ old('due_reminder_active', $transaction?->due_reminder_active) ? 'Active' : 'Inactive' }}</span>
                        <input type="hidden" name="due_reminder_active" id="due_reminder_active" value="{{ old('due_reminder_active', $transaction?->due_reminder_active) ? '1' : '0' }}">
                    </div>
                </div>
                @if($isEdit)
                <div class="form-group">
                    <label class="form-label">Email Status</label>
                    <div style="display:flex;gap:20px;flex-wrap:wrap;">
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <input type="checkbox" {{ $transaction?->x_days_email_sent ? 'checked' : '' }} disabled style="width:16px;height:16px;">
                            X-days-before Email Sent
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                            <input type="checkbox" {{ $transaction?->due_day_email_sent ? 'checked' : '' }} disabled style="width:16px;height:16px;">
                            Due-Day Email Sent
                        </label>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        <div class="form-card">
            <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                Notes
            </h3>

            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2" placeholder="Transaction description">{{ old('description', $transaction?->description) }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label">Internal Note</label>
                    <textarea name="internal_note" class="form-textarea" rows="2" placeholder="Internal notes (not visible to sponsor)">{{ old('internal_note', $transaction?->internal_note) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.studentsponsorship.transactions.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">{{ $isEdit ? 'Update Transaction' : 'Create Transaction' }}</button>
            </div>
        </div>
    </form>
</div>

<script>
// Searchable Select Functions
function toggleDropdown(selectId) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display');
    var dropdown = container.querySelector('.ss-dropdown');
    var isOpen = dropdown.classList.contains('show');
    
    document.querySelectorAll('.ss-dropdown.show').forEach(d => {
        d.classList.remove('show');
        d.parentElement.querySelector('.ss-display').classList.remove('open');
    });
    
    if (!isOpen) {
        dropdown.classList.add('show');
        display.classList.add('open');
        var searchInput = dropdown.querySelector('.ss-search input');
        if (searchInput) setTimeout(() => searchInput.focus(), 50);
    }
}

function filterOptions(selectId, searchText) {
    var container = document.getElementById(selectId);
    var options = container.querySelectorAll('.ss-option');
    var hasResults = false;
    
    searchText = searchText.toLowerCase();
    options.forEach(opt => {
        var text = opt.getAttribute('data-search') || opt.textContent.toLowerCase();
        if (text.includes(searchText)) {
            opt.style.display = 'block';
            hasResults = true;
        } else {
            opt.style.display = 'none';
        }
    });

    var noResults = container.querySelector('.ss-no-results');
    if (!hasResults) {
        if (!noResults) {
            noResults = document.createElement('div');
            noResults.className = 'ss-no-results';
            noResults.textContent = 'No results found';
            container.querySelector('.ss-options').appendChild(noResults);
        }
        noResults.style.display = 'block';
    } else if (noResults) {
        noResults.style.display = 'none';
    }
}

function selectOption(selectId, value, text) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display .ss-text');
    var dropdown = container.querySelector('.ss-dropdown');
    var hiddenInput = container.querySelector('input[type="hidden"]');
    
    display.textContent = text;
    hiddenInput.value = value;
    dropdown.classList.remove('show');
    container.querySelector('.ss-display').classList.remove('open');
    
    container.querySelectorAll('.ss-option').forEach(opt => opt.classList.remove('selected'));
    container.querySelector('.ss-option[data-value="' + value + '"]')?.classList.add('selected');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.searchable-select')) {
        document.querySelectorAll('.ss-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.parentElement.querySelector('.ss-display').classList.remove('open');
        });
    }
});

// Payment Type Selection
function selectPaymentType(type) {
    document.querySelectorAll('input[name="payment_type"]').forEach(r => {
        r.closest('.radio-option').classList.remove('selected');
        if (r.value === type) {
            r.checked = true;
            r.closest('.radio-option').classList.add('selected');
        }
    });
}

// Toggle Switch
function toggleSwitch(el, inputName) {
    el.classList.toggle('active');
    var isActive = el.classList.contains('active');
    document.getElementById(inputName).value = isActive ? '1' : '0';
    var label = document.getElementById(inputName + '_label');
    if (label) label.textContent = isActive ? 'Active' : 'Inactive';
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Sponsor
    var sponsorVal = '{{ old('sponsor_id', $transaction?->sponsor_id ?? $selectedSponsor?->id ?? '') }}';
    if (sponsorVal) {
        var sponsorOpt = document.querySelector('#sponsorSelect .ss-option[data-value="' + sponsorVal + '"]');
        if (sponsorOpt) selectOption('sponsorSelect', sponsorVal, sponsorOpt.textContent.trim());
    }

    // Currency
    var currVal = '{{ old('currency', $transaction?->currency ?? 'LKR') }}';
    var currOpt = document.querySelector('#currencySelect .ss-option[data-value="' + currVal + '"]');
    if (currOpt) selectOption('currencySelect', currVal, currOpt.textContent.trim());

    // Payment Type
    var payType = '{{ old('payment_type', $transaction?->payment_type ?? 'one_time') }}';
    selectPaymentType(payType);

    // School Student (if selected)
    var schoolId = '{{ old('school_student_id', $transaction?->school_student_id ?? '') }}';
    if (schoolId) {
        var schOpt = document.querySelector('#schoolStudentSelect .ss-option[data-value="' + schoolId + '"]');
        if (schOpt) selectOption('schoolStudentSelect', schoolId, schOpt.textContent.trim());
    }

    // University Student (if selected)
    var uniId = '{{ old('university_student_id', $transaction?->university_student_id ?? '') }}';
    if (uniId) {
        var uniOpt = document.querySelector('#universityStudentSelect .ss-option[data-value="' + uniId + '"]');
        if (uniOpt) selectOption('universityStudentSelect', uniId, uniOpt.textContent.trim());
    }
});
</script>
