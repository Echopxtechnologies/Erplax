
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:var(--text-primary);">
                Edit Contact: {{ $contact->name }}
            </h1>
            <a href="{{ route('admin.customers.show', $contact->id) }}" class="btn btn-light btn-sm">← Back</a>
        </div>
    </x-slot>

    <style>
        .cform { max-width:800px; margin:0 auto; }
        .ccard { background:var(--card-bg); border-radius:var(--radius-lg); box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:16px; border:1px solid var(--card-border); }
        .ccard-h { background:var(--body-bg); padding:14px 20px; border-bottom:1px solid var(--card-border); font-size:15px; font-weight:600; color:var(--text-primary); }
        .ccard-b { padding:20px; }
        .frow { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .frow3 { grid-template-columns:repeat(3,1fr); }
        .fcol { min-width:0; }
        .fcol-full { grid-column:1/-1; }
        .flbl { display:block; font-size:var(--font-sm); font-weight:500; color:var(--text-primary); margin-bottom:6px; }
        .req { color:var(--danger); }
        .finput { width:100%; padding:9px 12px; font-size:var(--font-base); border:1px solid var(--input-border); border-radius:var(--radius-md); background:var(--input-bg); color:var(--input-text); box-sizing:border-box; }
        .finput:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-light); }
        .ferr { color:var(--danger); font-size:var(--font-xs); margin-top:4px; }
        .info-badge { display:inline-block; padding:8px 16px; background:var(--primary-light); color:var(--primary); border-radius:8px; font-weight:600; margin-bottom:16px; }
        .notifications-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:12px; }
        .notification-item { display:flex; align-items:center; gap:8px; }
        .notification-item input[type="checkbox"] { width:18px; height:18px; cursor:pointer; }
        .notification-item label { cursor:pointer; font-size:var(--font-sm); color:var(--text-primary); }
        .factions { display:flex; justify-content:flex-end; gap:12px; padding:16px 0; }
        @media(max-width:768px) { .frow,.frow3 { grid-template-columns:1fr; } }
    </style>

    <div class="cform">
        <div class="info-badge">🏢 Company: {{ $contact->company }}</div>

        <form action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="ccard">
                <div class="ccard-h">Contact Information</div>
                <div class="ccard-b">
                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">First Name <span class="req">*</span></label>
                            <input name="firstname" class="finput" value="{{ old('firstname', $firstname) }}" required>
                            @error('firstname')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">Last Name <span class="req">*</span></label>
                            <input name="lastname" class="finput" value="{{ old('lastname', $lastname) }}" required>
                            @error('lastname')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">Email <span class="req">*</span></label>
                            <input type="email" name="email" class="finput" value="{{ old('email', $contact->email) }}" required>
                            @error('email')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">Phone</label>
                            <input name="phone" class="finput" value="{{ old('phone', $contact->phone) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Designation</label>
                            <input name="designation" class="finput" value="{{ old('designation', $contact->designation) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Status</label>
                            <select name="active" class="finput">
                                <option value="1" {{ old('active', $contact->active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('active', $contact->active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Notifications -->
            <div class="ccard">
                <div class="ccard-h">Email Notifications</div>
                <div class="ccard-b">
                    <div class="notifications-grid">
                        <div class="notification-item">
                            <input type="checkbox" name="invoice_emails" value="1" id="invoice" {{ old('invoice_emails', $contact->invoice_emails) ? 'checked' : '' }}>
                            <label for="invoice">Invoice Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="estimate_emails" value="1" id="estimate" {{ old('estimate_emails', $contact->estimate_emails) ? 'checked' : '' }}>
                            <label for="estimate">Estimate Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="credit_note_emails" value="1" id="credit_note" {{ old('credit_note_emails', $contact->credit_note_emails) ? 'checked' : '' }}>
                            <label for="credit_note">Credit Note Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="contract_emails" value="1" id="contract" {{ old('contract_emails', $contact->contract_emails) ? 'checked' : '' }}>
                            <label for="contract">Contract Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="task_emails" value="1" id="task" {{ old('task_emails', $contact->task_emails) ? 'checked' : '' }}>
                            <label for="task">Task Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="project_emails" value="1" id="project" {{ old('project_emails', $contact->project_emails) ? 'checked' : '' }}>
                            <label for="project">Project Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="ticket_emails" value="1" id="ticket" {{ old('ticket_emails', $contact->ticket_emails) ? 'checked' : '' }}>
                            <label for="ticket">Ticket Emails</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="factions">
                <button type="submit" class="btn btn-primary">💾 Update Contact</button>
                <a href="{{ route('admin.customers.show', $contact->id) }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
