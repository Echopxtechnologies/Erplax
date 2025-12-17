<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="margin:0 0 4px 0;font-size:24px;font-weight:700;color:var(--text-primary);">Create New Customer</h1>
                <p style="margin:0;font-size:14px;color:var(--text-muted);">Add a new individual or company to your customer database</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="btn-modern btn-light">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <style>
        /* Modern Button Styles */
        .btn-modern {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 20px;
            font-size:14px;
            font-weight:600;
            border-radius:10px;
            border:none;
            cursor:pointer;
            transition:all 0.2s ease;
            text-decoration:none;
            box-shadow:0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-modern:hover {
            transform:translateY(-2px);
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; }
        .btn-primary:hover { background:linear-gradient(135deg,#5568d3 0%,#63408a 100%); }
        .btn-light { background:white; color:#64748b; border:1px solid #e2e8f0; }
        .btn-light:hover { background:#f8fafc; color:#475569; }
        .btn-sm { padding:8px 14px; font-size:13px; }

        /* Form Container */
        .cform { 
            max-width:1000px;
            margin:0 auto;
        }
        
        /* Progress Indicator */
        .progress-steps {
            display:flex;
            justify-content:space-between;
            margin-bottom:32px;
            padding:0 20px;
            position:relative;
        }
        .progress-line {
            position:absolute;
            top:20px;
            left:0;
            right:0;
            height:2px;
            background:#e5e7eb;
            z-index:0;
        }
        .step {
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:8px;
            position:relative;
            z-index:1;
        }
        .step-circle {
            width:40px;
            height:40px;
            border-radius:50%;
            background:white;
            border:3px solid #e5e7eb;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            color:#94a3b8;
            transition:all 0.3s;
        }
        .step.active .step-circle {
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            border-color:#667eea;
            color:white;
        }
        .step-label {
            font-size:12px;
            font-weight:600;
            color:#94a3b8;
        }
        .step.active .step-label {
            color:#667eea;
        }
        
        /* Modern Cards */
        .modern-card { 
            background:white;
            border:1px solid #e5e7eb;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
            margin-bottom:24px;
            transition:all 0.3s ease;
        }
        .modern-card:hover {
            box-shadow:0 8px 20px rgba(0,0,0,0.08);
        }
        
        .card-header { 
            background:linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding:18px 24px;
            border-bottom:2px solid #e5e7eb;
            display:flex;
            align-items:center;
            gap:12px;
        }
        .card-icon {
            width:36px;
            height:36px;
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
            background:white;
            box-shadow:0 2px 4px rgba(0,0,0,0.1);
        }
        .card-title { 
            font-size:16px;
            font-weight:700;
            color:#1e293b;
            margin:0;
        }
        .card-badge {
            margin-left:auto;
            padding:4px 12px;
            background:#dbeafe;
            color:#1e40af;
            border-radius:12px;
            font-size:11px;
            font-weight:600;
        }
        
        .card-body { 
            padding:24px;
        }
        
        /* Customer Type Selector */
        .type-selector { 
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:16px;
        }
        .type-btn { 
            padding:24px;
            border:3px solid #e5e7eb;
            border-radius:16px;
            background:white;
            cursor:pointer;
            text-align:center;
            transition:all 0.3s ease;
            position:relative;
            overflow:hidden;
        }
        .type-btn::before {
            content:'';
            position:absolute;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background:linear-gradient(135deg,#667eea15 0%,#764ba215 100%);
            opacity:0;
            transition:opacity 0.3s;
        }
        .type-btn:hover {
            border-color:#667eea;
            transform:translateY(-4px);
            box-shadow:0 8px 20px rgba(102,126,234,0.2);
        }
        .type-btn:hover::before {
            opacity:1;
        }
        .type-btn.active { 
            border-color:#667eea;
            background:linear-gradient(135deg,#667eea15 0%,#764ba215 100%);
            box-shadow:0 0 0 4px rgba(102,126,234,0.1);
        }
        .type-btn input[type="radio"] { 
            display:none;
        }
        .type-icon { 
            font-size:48px;
            margin-bottom:12px;
            display:block;
        }
        .type-label { 
            font-weight:700;
            font-size:16px;
            color:#1e293b;
            display:block;
        }
        .type-desc {
            font-size:13px;
            color:#64748b;
            margin-top:4px;
        }
        
        /* Form Rows */
        .frow { 
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-bottom:20px;
        }
        .frow3 { 
            grid-template-columns:repeat(3,1fr);
        }
        .frow4 { 
            grid-template-columns:repeat(4,1fr);
        }
        .fcol { 
            min-width:0;
        }
        .fcol-full { 
            grid-column:1/-1;
        }
        
        /* Form Labels */
        .flbl { 
            display:block;
            font-size:13px;
            font-weight:600;
            color:#1e293b;
            margin-bottom:8px;
            letter-spacing:0.3px;
        }
        .req { 
            color:#ef4444;
            margin-left:2px;
        }
        
        /* Form Inputs */
        .finput { 
            width:100%;
            padding:12px 16px;
            font-size:14px;
            border:2px solid #e5e7eb;
            border-radius:10px;
            background:white;
            color:#1e293b;
            box-sizing:border-box;
            transition:all 0.2s;
            font-family:inherit;
        }
        .finput:hover {
            border-color:#cbd5e1;
        }
        .finput:focus { 
            outline:none;
            border-color:#667eea;
            box-shadow:0 0 0 4px rgba(102,126,234,0.1);
        }
        textarea.finput {
            resize:vertical;
            min-height:80px;
        }
        select.finput {
            cursor:pointer;
            appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 12px center;
            background-size:20px;
            padding-right:40px;
        }
        
        /* Error Messages */
        .ferr { 
            color:#ef4444;
            font-size:12px;
            margin-top:6px;
            display:flex;
            align-items:center;
            gap:4px;
        }
        .ferr::before {
            content:'⚠';
        }
        
        /* Notifications Grid */
        .notifications-grid { 
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
            gap:16px;
        }
        .notification-item { 
            display:flex;
            align-items:center;
            gap:10px;
            padding:12px 16px;
            background:#f8fafc;
            border:2px solid #e5e7eb;
            border-radius:10px;
            transition:all 0.2s;
            cursor:pointer;
        }
        .notification-item:hover {
            background:#f1f5f9;
            border-color:#cbd5e1;
        }
        .notification-item input[type="checkbox"] { 
            width:20px;
            height:20px;
            cursor:pointer;
            accent-color:#667eea;
        }
        .notification-item label { 
            cursor:pointer;
            font-size:13px;
            font-weight:500;
            color:#1e293b;
            flex:1;
        }
        
        /* Company Section */
        .company-section { 
            display:none;
        }
        
        /* Form Actions */
        .factions { 
            display:flex;
            justify-content:flex-end;
            gap:12px;
            padding:24px 0;
        }
        
        /* Copy Button */
        .copy-btn {
            padding:8px 14px;
            background:white;
            border:2px solid #e5e7eb;
            border-radius:8px;
            font-size:13px;
            font-weight:600;
            color:#64748b;
            cursor:pointer;
            transition:all 0.2s;
            display:inline-flex;
            align-items:center;
            gap:6px;
        }
        .copy-btn:hover {
            background:#f8fafc;
            border-color:#667eea;
            color:#667eea;
        }
        
        /* Responsive */
        @media(max-width:768px) { 
            .frow,.frow3,.frow4 { 
                grid-template-columns:1fr;
            }
            .type-selector {
                grid-template-columns:1fr;
            }
            .progress-steps {
                display:none;
            }
        }
    </style>

    <div class="cform">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="step active">
                <div class="step-circle">1</div>
                <div class="step-label">Type</div>
            </div>
            <div class="step">
                <div class="step-circle">2</div>
                <div class="step-label">Contact</div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-label">Address</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-label">Settings</div>
            </div>
        </div>

        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf

            <!-- Customer Type Selector -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;">
                        🎯
                    </div>
                    <h2 class="card-title">Customer Type</h2>
                    <span class="card-badge">Step 1 of 4</span>
                </div>
                <div class="card-body">
                    <div class="type-selector">
                        <label class="type-btn {{ old('customer_type', 'individual') === 'individual' ? 'active' : '' }}" onclick="setCustomerType('individual')">
                            <input type="radio" name="customer_type" value="individual" {{ old('customer_type', 'individual') === 'individual' ? 'checked' : '' }}>
                            <span class="type-icon">👤</span>
                            <span class="type-label">Individual</span>
                            <span class="type-desc">Single person or freelancer</span>
                        </label>
                        <label class="type-btn {{ old('customer_type') === 'company' ? 'active' : '' }}" onclick="setCustomerType('company')">
                            <input type="radio" name="customer_type" value="company" {{ old('customer_type') === 'company' ? 'checked' : '' }}>
                            <span class="type-icon">🏢</span>
                            <span class="type-label">Company</span>
                            <span class="type-desc">Business or organization</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;">
                        📧
                    </div>
                    <h2 class="card-title">Contact Information</h2>
                    <span class="card-badge">Step 2 of 4</span>
                </div>
                <div class="card-body">
                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">First Name <span class="req">*</span></label>
                            <input name="firstname" class="finput" value="{{ old('firstname') }}" required placeholder="Enter first name">
                            @error('firstname')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">Last Name <span class="req">*</span></label>
                            <input name="lastname" class="finput" value="{{ old('lastname') }}" required placeholder="Enter last name">
                            @error('lastname')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">Email <span class="req">*</span></label>
                            <input type="email" name="email" class="finput" value="{{ old('email') }}" required placeholder="email@example.com">
                            @error('email')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">Phone</label>
                            <input name="phone" class="finput" value="{{ old('phone') }}" placeholder="+1 234 567 8900">
                            @error('phone')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">Designation</label>
                            <input name="designation" class="finput" value="{{ old('designation') }}" placeholder="Job title">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Customer Group</label>
                            <select name="group_name" id="group_name_select" class="finput">
                                <option value="">-- Select Group --</option>
                                @foreach($customerGroups as $group)
                                    <option value="{{ $group }}" {{ old('group_name') === $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information (Hidden by default) -->
            <div class="modern-card company-section" id="companySection">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);color:white;">
                        🏢
                    </div>
                    <h2 class="card-title">Company Information</h2>
                    <span class="card-badge">Required for Company</span>
                </div>
                <div class="card-body">
                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">Company Name <span class="req">*</span></label>
                            <input 
                                name="company" 
                                id="company_input"
                                class="finput company-required" 
                                value="{{ old('company') }}" 
                                placeholder="Type or select company"
                                list="companies_list"
                                autocomplete="off">
                            <datalist id="companies_list">
                                @foreach($existingCompanies as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </datalist>
                            <small style="color:#64748b;font-size:11px;margin-top:4px;display:block;">💡 Select existing or type new company name</small>
                            @error('company')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">VAT Number</label>
                            <input name="vat" id="vat_input" class="finput" value="{{ old('vat') }}" placeholder="VAT123456">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Website</label>
                            <input type="url" name="website" id="website_input" class="finput" value="{{ old('website') }}" placeholder="https://example.com">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#fa709a 0%,#fee140 100%);color:white;">
                        📍
                    </div>
                    <h2 class="card-title">Billing Address</h2>
                    <span class="card-badge">Step 3 of 4</span>
                </div>
                <div class="card-body">
                    <div class="frow"><div class="fcol-full">
                        <label class="flbl">Street Address</label>
                        <textarea name="billing_street" rows="2" class="finput" placeholder="Enter street address">{{ old('billing_street') }}</textarea>
                    </div></div>

                    <div class="frow frow4">
                        <div class="fcol">
                            <label class="flbl">City</label>
                            <input name="billing_city" class="finput" value="{{ old('billing_city') }}" placeholder="City">
                        </div>
                        <div class="fcol">
                            <label class="flbl">State</label>
                            <input name="billing_state" class="finput" value="{{ old('billing_state') }}" placeholder="State">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Zip Code</label>
                            <input name="billing_zip_code" class="finput" value="{{ old('billing_zip_code') }}" placeholder="12345">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Country</label>
                            <select name="billing_country" class="finput">
                                <option value="">-- Select --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('billing_country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#30cfd0 0%,#330867 100%);color:white;">
                        🚚
                    </div>
                    <h2 class="card-title">Shipping Address</h2>
                    <button type="button" class="copy-btn" onclick="copyBillingToShipping()">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy from Billing
                    </button>
                </div>
                <div class="card-body">
                    <div class="frow"><div class="fcol-full">
                        <label class="flbl">Street Address</label>
                        <textarea name="shipping_address" rows="2" class="finput" id="shipping_address" placeholder="Enter street address">{{ old('shipping_address') }}</textarea>
                    </div></div>

                    <div class="frow frow4">
                        <div class="fcol">
                            <label class="flbl">City</label>
                            <input name="shipping_city" class="finput" id="shipping_city" value="{{ old('shipping_city') }}" placeholder="City">
                        </div>
                        <div class="fcol">
                            <label class="flbl">State</label>
                            <input name="shipping_state" class="finput" id="shipping_state" value="{{ old('shipping_state') }}" placeholder="State">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Zip Code</label>
                            <input name="shipping_zip_code" class="finput" id="shipping_zip_code" value="{{ old('shipping_zip_code') }}" placeholder="12345">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Country</label>
                            <select name="shipping_country" class="finput" id="shipping_country">
                                <option value="">-- Select --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('shipping_country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Notifications -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%);color:#1e293b;">
                        🔔
                    </div>
                    <h2 class="card-title">Email Notifications</h2>
                    <span class="card-badge">Step 4 of 4</span>
                </div>
                <div class="card-body">
                    <div class="notifications-grid">
                        <div class="notification-item">
                            <input type="checkbox" name="invoice_emails" value="1" id="invoice" {{ old('invoice_emails', 1) ? 'checked' : '' }}>
                            <label for="invoice">Invoice Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="estimate_emails" value="1" id="estimate" {{ old('estimate_emails', 1) ? 'checked' : '' }}>
                            <label for="estimate">Estimate Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="credit_note_emails" value="1" id="credit_note" {{ old('credit_note_emails', 1) ? 'checked' : '' }}>
                            <label for="credit_note">Credit Note Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="contract_emails" value="1" id="contract" {{ old('contract_emails', 1) ? 'checked' : '' }}>
                            <label for="contract">Contract Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="task_emails" value="1" id="task" {{ old('task_emails', 1) ? 'checked' : '' }}>
                            <label for="task">Task Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="project_emails" value="1" id="project" {{ old('project_emails', 1) ? 'checked' : '' }}>
                            <label for="project">Project Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="ticket_emails" value="1" id="ticket" {{ old('ticket_emails', 1) ? 'checked' : '' }}>
                            <label for="ticket">Ticket Emails</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%);color:#1e293b;">
                        ⚙️
                    </div>
                    <h2 class="card-title">Account Settings</h2>
                </div>
                <div class="card-body">
                    <div class="frow">
                        <div class="fcol">
                            <label class="flbl">Status</label>
                            <select name="active" class="finput">
                                <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="factions">
                <a href="{{ route('admin.customers.index') }}" class="btn-modern btn-light">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Customer
                </button>
            </div>
        </form>
    </div>

<script>
    let isCompanySelected = false;
    let companySelectTimeout = null;

    // Customer type toggle
    function setCustomerType(type) {
        const companySection = document.getElementById('companySection');
        const companyRequired = document.querySelectorAll('.company-required');
        const typeBtns = document.querySelectorAll('.type-btn');
        
        typeBtns.forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        
        if (type === 'company') {
            companySection.style.display = 'block';
            companyRequired.forEach(input => input.required = true);
        } else {
            companySection.style.display = 'none';
            companyRequired.forEach(input => input.required = false);
            resetCompanyFields();
        }
    }

    // Copy billing to shipping
    function copyBillingToShipping() {
        document.getElementById('shipping_address').value = document.querySelector('[name="billing_street"]').value;
        document.getElementById('shipping_city').value = document.querySelector('[name="billing_city"]').value;
        document.getElementById('shipping_state').value = document.querySelector('[name="billing_state"]').value;
        document.getElementById('shipping_zip_code').value = document.querySelector('[name="billing_zip_code"]').value;
        document.getElementById('shipping_country').value = document.querySelector('[name="billing_country"]').value;
    }

    // Reset company fields to editable
    function resetCompanyFields() {
        isCompanySelected = false;
        
        const fields = [
            'company_input', 'vat_input', 'website_input', 'group_name_select',
            'billing_street', 'billing_city', 'billing_state', 'billing_zip_code', 'billing_country',
            'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip_code', 'shipping_country'
        ];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId) || document.querySelector(`[name="${fieldId}"]`);
            if (field) {
                field.removeAttribute('readonly');
                field.removeAttribute('disabled');
                field.style.backgroundColor = 'white';
                field.style.cursor = 'text';
                if (field.tagName === 'SELECT') {
                    field.style.cursor = 'pointer';
                }
            }
        });
    }

    // Make fields readonly
    function makeFieldsReadonly() {
        const fields = [
            'vat_input', 'website_input', 'group_name_select',
            'billing_street', 'billing_city', 'billing_state', 'billing_zip_code', 'billing_country',
            'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip_code', 'shipping_country'
        ];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId) || document.querySelector(`[name="${fieldId}"]`);
            if (field) {
                if (field.tagName === 'SELECT') {
                    field.setAttribute('disabled', 'disabled');
                } else {
                    field.setAttribute('readonly', 'readonly');
                }
                field.style.backgroundColor = '#f8fafc';
                field.style.cursor = 'not-allowed';
            }
        });
    }

    // Fetch company details
    async function fetchCompanyDetails(companyName) {
        try {
            const response = await fetch('{{ route("admin.customers.getCompanyDetails") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ company: companyName })
            });

            const result = await response.json();

            if (result.success) {
                const data = result.data;
                
                // Fill company fields
                document.getElementById('company_input').value = data.company || '';
                document.getElementById('vat_input').value = data.vat || '';
                document.getElementById('website_input').value = data.website || '';
                
                // Fill customer group
                const groupSelect = document.getElementById('group_name_select');
                if (groupSelect && data.group_name) {
                    groupSelect.value = data.group_name;
                }
                
                // Fill billing address
                const billingStreet = document.querySelector('[name="billing_street"]');
                const billingCity = document.querySelector('[name="billing_city"]');
                const billingState = document.querySelector('[name="billing_state"]');
                const billingZip = document.querySelector('[name="billing_zip_code"]');
                const billingCountry = document.querySelector('[name="billing_country"]');
                
                if (billingStreet) billingStreet.value = data.billing_street || '';
                if (billingCity) billingCity.value = data.billing_city || '';
                if (billingState) billingState.value = data.billing_state || '';
                if (billingZip) billingZip.value = data.billing_zip_code || '';
                if (billingCountry) billingCountry.value = data.billing_country || '';
                
                // Fill shipping address
                document.getElementById('shipping_address').value = data.shipping_address || '';
                document.getElementById('shipping_city').value = data.shipping_city || '';
                document.getElementById('shipping_state').value = data.shipping_state || '';
                document.getElementById('shipping_zip_code').value = data.shipping_zip_code || '';
                document.getElementById('shipping_country').value = data.shipping_country || '';
                
                // Make fields readonly
                isCompanySelected = true;
                makeFieldsReadonly();
                
                // Show success message
                console.log('✅ Company details loaded successfully');
                
            } else {
                console.log('⚠️ Company not found - manual entry allowed');
                resetCompanyFields();
            }
            
        } catch (error) {
            console.error('Error fetching company details:', error);
            resetCompanyFields();
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedType = document.querySelector('input[name="customer_type"]:checked').value;
        if (selectedType === 'company') {
            document.getElementById('companySection').style.display = 'block';
        }

        // Company input listener
        const companyInput = document.getElementById('company_input');
        if (companyInput) {
            // Listen for selection from datalist
            companyInput.addEventListener('input', function(e) {
                clearTimeout(companySelectTimeout);
                
                const value = e.target.value.trim();
                
                // Check if value matches an option in datalist
                const datalist = document.getElementById('companies_list');
                const options = Array.from(datalist.options).map(opt => opt.value);
                
                if (options.includes(value)) {
                    // User selected from dropdown
                    companySelectTimeout = setTimeout(() => {
                        fetchCompanyDetails(value);
                    }, 300);
                } else {
                    // User is typing manually
                    resetCompanyFields();
                }
            });

            // Listen for manual typing
            companyInput.addEventListener('keyup', function(e) {
                if (isCompanySelected) {
                    // If user starts typing after selection, reset
                    const datalist = document.getElementById('companies_list');
                    const options = Array.from(datalist.options).map(opt => opt.value);
                    
                    if (!options.includes(e.target.value.trim())) {
                        resetCompanyFields();
                    }
                }
            });
        }
    });
</script>
</x-layouts.app>