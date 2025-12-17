<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="margin:0 0 4px 0;font-size:24px;font-weight:700;color:var(--text-primary);">
                    Add New Contact
                </h1>
                <p style="margin:0;font-size:14px;color:var(--text-muted);">Add a new contact to {{ $companyName }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $parentCustomer->id) }}" class="btn-modern btn-light">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Company
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
        .btn-primary { 
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white;
        }
        .btn-primary:hover { 
            background:linear-gradient(135deg,#5568d3 0%,#63408a 100%);
        }
        .btn-light { 
            background:white;
            color:#64748b;
            border:2px solid #e2e8f0;
        }
        .btn-light:hover { 
            background:#f8fafc;
            color:#475569;
            border-color:#cbd5e1;
        }
        
        /* Form Container */
        .cform { 
            max-width:900px;
            margin:0 auto;
        }
        
        /* Company Badge */
        .company-badge {
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white;
            padding:16px 24px;
            border-radius:12px;
            font-weight:700;
            margin-bottom:24px;
            display:flex;
            align-items:center;
            gap:12px;
            box-shadow:0 4px 12px rgba(102,126,234,0.3);
        }
        .company-badge-icon {
            width:40px;
            height:40px;
            background:rgba(255,255,255,0.2);
            border-radius:10px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
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
        
        .card-body { 
            padding:24px;
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
        
        /* Form Actions */
        .factions { 
            display:flex;
            justify-content:flex-end;
            gap:12px;
            padding:24px 0;
        }
        
        /* Responsive */
        @media(max-width:768px) { 
            .frow,.frow3 { 
                grid-template-columns:1fr;
            }
        }
    </style>

    <div class="cform">
        <!-- Company Badge -->
        <div class="company-badge">
            <div class="company-badge-icon">🏢</div>
            <div>
                <div style="font-size:12px;opacity:0.9;text-transform:uppercase;letter-spacing:0.5px;">Adding Contact To</div>
                <div style="font-size:18px;">{{ $companyName }}</div>
            </div>
        </div>

        <form action="{{ route('admin.customers.contacts.store', $parentCustomer->id) }}" method="POST">
            @csrf

            <!-- Contact Information -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;">
                        👤
                    </div>
                    <h2 class="card-title">Contact Information</h2>
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
                        </div>
                        <div class="fcol">
                            <label class="flbl">Designation</label>
                            <input name="designation" class="finput" value="{{ old('designation') }}" placeholder="Job title">
                        </div>
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

            <!-- Email Notifications -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon" style="background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%);color:#1e293b;">
                        🔔
                    </div>
                    <h2 class="card-title">Email Notifications</h2>
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

            <!-- Form Actions -->
            <div class="factions">
                <a href="{{ route('admin.customers.show', $parentCustomer->id) }}" class="btn-modern btn-light">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Add Contact
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>