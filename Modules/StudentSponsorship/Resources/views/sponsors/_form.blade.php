@php 
    $sponsor = $sponsor ?? null;
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
    .tab-btn { padding: 16px 24px; font-size: 14px; font-weight: 600; color: var(--text-muted); border: none; background: none; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -1px; transition: all 0.2s; }
    .tab-btn:hover { color: var(--text-primary); background: rgba(59, 130, 246, 0.05); }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); background: var(--card-bg); }
    .tab-btn svg { width: 18px; height: 18px; }
    .tab-content { display: none; padding: 24px; position: relative; overflow: visible !important; }
    .tab-content.active { display: block; overflow: visible !important; }
    
    /* Form */
    .form-section { margin-bottom: 32px; position: relative; }
    .form-section:last-child { margin-bottom: 0; }
    .section-title { font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px; }
    .section-title svg { width: 20px; height: 20px; }
    
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; position: relative; }
    .form-row-3 { grid-template-columns: repeat(3, 1fr); }
    .form-row-4 { grid-template-columns: repeat(4, 1fr); }
    .form-group { margin-bottom: 0; position: relative; }
    .form-group.full-width { grid-column: span 2; }
    
    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); transition: all 0.2s; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
    
    /* Searchable Select */
    .searchable-select { position: relative; }
    .searchable-select .ss-display { width: 100%; padding: 10px 14px; padding-right: 36px; font-size: 14px; background: var(--input-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; color: var(--input-text, #333); cursor: pointer; min-height: 42px; display: flex; align-items: center; }
    .searchable-select .ss-display.placeholder { color: var(--text-muted, #999); }
    .searchable-select .ss-arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 10px; }
    .searchable-select .ss-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; margin-top: 4px; z-index: 99999 !important; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
    .searchable-select.open .ss-dropdown { display: block; }
    .searchable-select .ss-search { width: 100%; padding: 10px 14px; font-size: 14px; border: none; border-bottom: 1px solid var(--input-border, #ccc); border-radius: 8px 8px 0 0; background: var(--body-bg, #f9fafb); color: var(--input-text, #333); outline: none; }
    .searchable-select .ss-options { max-height: 200px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 14px; cursor: pointer; font-size: 14px; color: var(--text-primary, #333); }
    .searchable-select .ss-option:hover { background: var(--primary-light, #e0e7ff); color: var(--primary, #4F46E5); }
    .searchable-select .ss-option.selected { background: var(--primary, #4F46E5); color: #fff; }
    .searchable-select .ss-no-results { padding: 10px 14px; color: var(--text-muted, #999); font-size: 13px; }
    
    /* Input with button */
    .input-with-btn { display: flex; gap: 8px; }
    .input-with-btn .searchable-select { flex: 1; }
    .btn-add-inline { padding: 10px 16px; background: var(--success, #22c55e); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px; white-space: nowrap; min-width: 44px; }
    .btn-add-inline:hover { background: var(--success-hover, #16a34a); }
    
    /* Submit */
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid var(--card-border); }
    .btn-submit { padding: 12px 32px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.2s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); }
    .btn-cancel { padding: 12px 24px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; font-size: 15px; font-weight: 600; color: var(--text-secondary); cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: var(--body-bg); color: var(--text-primary); }
    
    /* Checkbox */
    .checkbox-wrapper { display: flex; align-items: center; gap: 10px; margin-top: 8px; }
    .checkbox-wrapper input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    .checkbox-label { font-size: 14px; color: var(--text-primary); cursor: pointer; }
    
    /* Alert */
    .alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
    .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
    .alert-danger ul { margin: 8px 0 0 20px; }
    
    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100000; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: var(--card-bg, #fff); border-radius: 12px; padding: 24px; max-width: 400px; width: 90%; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; font-size: 18px; font-weight: 600; color: var(--text-primary); }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); }
    .modal-body { margin-bottom: 20px; }
    .modal-footer { display: flex; gap: 12px; justify-content: flex-end; }
    
    @media (max-width: 768px) {
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
        .page-header { flex-direction: column; gap: 12px; align-items: flex-start; }
        .form-actions { flex-direction: column; }
        .btn-submit, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>

<div class="form-page">
    <div class="page-header">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            {{ $isEdit ? 'Edit Sponsor' : 'Add New Sponsor' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.sponsors.index') }}" class="btn-back">← Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.studentsponsorship.sponsors.update', $sponsor->id) : route('admin.studentsponsorship.sponsors.store') }}" method="POST" id="sponsor-form">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="tabs-container">
            <div class="tabs-nav">
                <button type="button" class="tab-btn active" data-tab="basic">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Basic Info
                </button>
                <button type="button" class="tab-btn" data-tab="banking">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                    Banking
                </button>
                <button type="button" class="tab-btn" data-tab="sponsorship">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    Sponsorship
                </button>
            </div>

            <!-- Tab: Basic Info -->
            <div id="tab-basic" class="tab-content active">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        Sponsor Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sponsor Internal ID <span class="required">*</span></label>
                            <input type="text" name="sponsor_internal_id" class="form-input" value="{{ old('sponsor_internal_id', $sponsor->sponsor_internal_id ?? '') }}" placeholder="Enter unique ID" required>
                            @error('sponsor_internal_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sponsor Type <span class="required">*</span></label>
                            <select name="sponsor_type" class="form-select" required>
                                <option value="individual" {{ old('sponsor_type', $sponsor->sponsor_type ?? 'individual') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="company" {{ old('sponsor_type', $sponsor->sponsor_type ?? '') == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sponsor Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $sponsor->name ?? '') }}" placeholder="Full name or company name" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="sponsor_occupation" class="form-input" value="{{ old('sponsor_occupation', $sponsor->sponsor_occupation ?? '') }}" placeholder="Occupation or business type">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $sponsor->email ?? '') }}" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="contact_no" class="form-input" value="{{ old('contact_no', $sponsor->contact_no ?? '') }}" placeholder="Phone number">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        Address Information
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select name="country_id" id="countrySelect" class="form-select searchable">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ old('country_id', $sponsor->country_id ?? '') == $country->country_id ? 'selected' : '' }}>{{ $country->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city', $sponsor->city ?? '') }}" placeholder="City">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-textarea" rows="2" placeholder="Full address">{{ old('address', $sponsor->address ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="zip" class="form-input" value="{{ old('zip', $sponsor->zip ?? '') }}" placeholder="ZIP/Postal code">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Banking -->
            <div id="tab-banking" class="tab-content">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                        Bank Information
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <div class="input-with-btn">
                                <select name="bank_id" id="bankSelect" class="form-select searchable">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id', $sponsor->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="openAddBankModal()" title="Add New Bank">+</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Branch Info</label>
                            <input type="text" name="sponsor_bank_branch_info" class="form-input" value="{{ old('sponsor_bank_branch_info', $sponsor->sponsor_bank_branch_info ?? '') }}" placeholder="Branch name or location">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Branch Number / Code</label>
                            <input type="text" name="sponsor_bank_branch_number" class="form-input" value="{{ old('sponsor_bank_branch_number', $sponsor->sponsor_bank_branch_number ?? '') }}" placeholder="Branch code (e.g., IFSC)">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="sponsor_bank_account_no" class="form-input" value="{{ old('sponsor_bank_account_no', $sponsor->sponsor_bank_account_no ?? '') }}" placeholder="Bank account number">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Sponsorship -->
            <div id="tab-sponsorship" class="tab-content">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        Membership Period
                    </h3>

                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Sponsorship Start Date</label>
                            <input type="date" name="membership_start_date" class="form-input" value="{{ old('membership_start_date', isset($sponsor->membership_start_date) ? $sponsor->membership_start_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sponsorship End Date</label>
                            <input type="date" name="membership_end_date" class="form-input" value="{{ old('membership_end_date', isset($sponsor->membership_end_date) ? $sponsor->membership_end_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Frequency</label>
                            <select name="sponsor_frequency" class="form-select">
                                <option value="">Select Frequency</option>
                                <option value="one_time" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == 'one_time' ? 'selected' : '' }}>One-time</option>
                                <option value="monthly" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="half_yearly" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == 'half_yearly' ? 'selected' : '' }}>Half-yearly</option>
                                <option value="yearly" {{ old('sponsor_frequency', $sponsor->sponsor_frequency ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" /></svg>
                        Status
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="active" id="active" value="1" {{ old('active', $sponsor->active ?? 1) ? 'checked' : '' }}>
                                <label for="active" class="checkbox-label">Active Sponsor</label>
                            </div>
                            <div style="margin-top: 8px; font-size: 12px; color: var(--text-muted);">Inactive sponsors won't appear in transaction forms</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                        Comments & Notes
                    </h3>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label class="form-label">Background Information</label>
                            <textarea name="background_info" class="form-textarea" rows="2" placeholder="General background about the sponsor">{{ old('background_info', $sponsor->background_info ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Internal Comment</label>
                            <textarea name="internal_comment" class="form-textarea" rows="2" placeholder="Internal notes (not visible to sponsor)">{{ old('internal_comment', $sponsor->internal_comment ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">External Comment</label>
                            <textarea name="external_comment" class="form-textarea" rows="2" placeholder="Notes that can be shared">{{ old('external_comment', $sponsor->external_comment ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.studentsponsorship.sponsors.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    {{ $isEdit ? 'Update Sponsor' : 'Create Sponsor' }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Add Bank Modal -->
<div id="addBankModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add New Bank</h3>
            <button type="button" class="modal-close" onclick="closeAddBankModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Bank Name <span class="required">*</span></label>
                <input type="text" id="newBankName" class="form-input" placeholder="Enter bank name">
                <div id="bankError" class="form-error" style="display:none;"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeAddBankModal()">Cancel</button>
            <button type="button" class="btn-submit" onclick="saveNewBank()">Add Bank</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            this.classList.add('active');
            localStorage.setItem('sponsor_form_active_tab', tabId);
        });
    });
    
    var savedTab = localStorage.getItem('sponsor_form_active_tab');
    if (savedTab) {
        var btn = document.querySelector('.tab-btn[data-tab="' + savedTab + '"]');
        if (btn) btn.click();
    }
    
    // Init searchable selects
    document.querySelectorAll('.form-select.searchable').forEach(function(select) {
        new SearchableSelect(select);
    });
});

// Searchable Select Class
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
        var val = String(this.select.value);
        if (val) {
            var opt = this.options.find(o => String(o.value) === val);
            if (opt && opt.value) {
                this.display.textContent = opt.text;
                this.display.classList.remove('placeholder');
                return;
            }
        }
        this.display.textContent = this.options[0]?.text || 'Select...';
        this.display.classList.add('placeholder');
    }
    
    renderOptions(filter) {
        this.optionsContainer.innerHTML = '';
        var filtered = this.options.filter(o => o.value && o.text.toLowerCase().includes(filter.toLowerCase()));
        
        if (!filtered.length) {
            this.optionsContainer.innerHTML = '<div class="ss-no-results">No results found</div>';
            return;
        }
        
        var self = this;
        filtered.forEach(function(opt) {
            var div = document.createElement('div');
            div.className = 'ss-option' + (opt.value === self.select.value ? ' selected' : '');
            div.textContent = opt.text;
            div.addEventListener('click', function() {
                self.select.value = opt.value;
                self.updateDisplay();
                self.close();
                self.select.dispatchEvent(new Event('change'));
            });
            self.optionsContainer.appendChild(div);
        });
    }
    
    open() { this.wrapper.classList.add('open'); this.searchInput.value = ''; this.renderOptions(''); this.searchInput.focus(); }
    close() { this.wrapper.classList.remove('open'); }
    
    bindEvents() {
        var self = this;
        this.display.addEventListener('click', () => self.wrapper.classList.contains('open') ? self.close() : self.open());
        this.searchInput.addEventListener('input', function() { self.renderOptions(this.value); });
        this.searchInput.addEventListener('keydown', e => { if (e.key === 'Escape') self.close(); });
        document.addEventListener('click', e => { if (!self.wrapper.contains(e.target)) self.close(); });
    }
}

// Add Bank Modal
function openAddBankModal() {
    document.getElementById('addBankModal').classList.add('active');
    document.getElementById('newBankName').value = '';
    document.getElementById('bankError').style.display = 'none';
    document.getElementById('newBankName').focus();
}

function closeAddBankModal() {
    document.getElementById('addBankModal').classList.remove('active');
}

function saveNewBank() {
    var name = document.getElementById('newBankName').value.trim();
    var errorEl = document.getElementById('bankError');
    
    if (!name) {
        errorEl.textContent = 'Please enter a bank name';
        errorEl.style.display = 'block';
        return;
    }
    
    fetch('{{ route("admin.studentsponsorship.sponsors.add-bank") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ name: name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeAddBankModal();
            alert('Bank "' + data.bank.name + '" added successfully!');
            location.reload();
        } else {
            errorEl.textContent = data.message || 'Failed to add bank';
            errorEl.style.display = 'block';
        }
    })
    .catch(err => {
        errorEl.textContent = 'An error occurred';
        errorEl.style.display = 'block';
    });
}

document.getElementById('addBankModal').addEventListener('click', function(e) { if (e.target === this) closeAddBankModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAddBankModal(); });
</script>
