@can('customers.customers.edit')
    

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:var(--text-primary);">
                Edit {{ $customer->isCompany() ? 'Company' : 'Customer' }}: {{ $customer->display_name }}
            </h1>
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-light btn-sm">← Back</a>
        </div>
    </x-slot>

    <style>
        .cform { max-width:900px; margin:0 auto; }
        .ccard { background:var(--card-bg); border-radius:var(--radius-lg); box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:16px; border:1px solid var(--card-border); }
        .ccard-h { background:var(--body-bg); padding:14px 20px; border-bottom:1px solid var(--card-border); font-size:15px; font-weight:600; color:var(--text-primary); }
        .ccard-b { padding:20px; }
        .frow { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .frow3 { grid-template-columns:repeat(3,1fr); }
        .frow4 { grid-template-columns:repeat(4,1fr); }
        .fcol { min-width:0; }
        .fcol-full { grid-column:1/-1; }
        .flbl { display:block; font-size:var(--font-sm); font-weight:500; color:var(--text-primary); margin-bottom:6px; }
        .req { color:var(--danger); }
        .finput { width:100%; padding:9px 12px; font-size:var(--font-base); border:1px solid var(--input-border); border-radius:var(--radius-md); background:var(--input-bg); color:var(--input-text); box-sizing:border-box; }
        .finput:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-light); }
        .ferr { color:var(--danger); font-size:var(--font-xs); margin-top:4px; }
        .info-badge { display:inline-block; padding:8px 16px; background:var(--primary-light); color:var(--primary); border-radius:8px; font-weight:600; margin-bottom:16px; }
        .warning-badge { display:inline-block; padding:8px 16px; background:#fef3c7; color:#92400e; border-radius:8px; font-size:var(--font-sm); margin-bottom:16px; }
        .notifications-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:12px; }
        .notification-item { display:flex; align-items:center; gap:8px; }
        .notification-item input[type="checkbox"] { width:18px; height:18px; cursor:pointer; }
        .notification-item label { cursor:pointer; font-size:var(--font-sm); color:var(--text-primary); }
        .factions { display:flex; justify-content:flex-end; gap:12px; padding:16px 0; }
        @media(max-width:768px) { .frow,.frow3,.frow4 { grid-template-columns:1fr; } }
    </style>

    <div class="cform">
        @if($customer->isCompany() && $contactCount > 1)
            <div class="warning-badge">
                ⚠️ This company has {{ $contactCount }} contacts. Changes to company details will update ALL contacts.
            </div>
        @endif

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="customer_type" value="{{ $customer->customer_type }}">

            <div class="info-badge">
                {{ $customer->isCompany() ? '🏢 Company' : '👤 Individual' }}
            </div>

            <!-- Contact Information -->
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
                            <input type="email" name="email" class="finput" value="{{ old('email', $customer->email) }}" required>
                            @error('email')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">Phone</label>
                            <input name="phone" class="finput" value="{{ old('phone', $customer->phone) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Designation</label>
                            <input name="designation" class="finput" value="{{ old('designation', $customer->designation) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Customer Group</label>
                            <select name="group_name" class="finput">
                                <option value="">-- Select Group --</option>
                                @foreach($customerGroups as $group)
                                    <option value="{{ $group }}" {{ old('group_name', $customer->group_name) === $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            @if($customer->isCompany())
            <div class="ccard">
                <div class="ccard-h">Company Information</div>
                <div class="ccard-b">
                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl">Company Name <span class="req">*</span></label>
                            <input name="company" class="finput" value="{{ old('company', $customer->company) }}" required>
                            @error('company')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="fcol">
                            <label class="flbl">VAT Number</label>
                            <input name="vat" class="finput" value="{{ old('vat', $customer->vat) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Website</label>
                            <input type="url" name="website" class="finput" value="{{ old('website', $customer->website) }}">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Billing Address -->
            <div class="ccard">
                <div class="ccard-h">Billing Address</div>
                <div class="ccard-b">
                    <div class="frow"><div class="fcol-full">
                        <label class="flbl">Street Address</label>
                        <textarea name="billing_street" rows="2" class="finput">{{ old('billing_street', $customer->billing_street) }}</textarea>
                    </div></div>

                    <div class="frow frow4">
                        <div class="fcol">
                            <label class="flbl">City</label>
                            <input name="billing_city" class="finput" value="{{ old('billing_city', $customer->billing_city) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">State</label>
                            <input name="billing_state" class="finput" value="{{ old('billing_state', $customer->billing_state) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Zip Code</label>
                            <input name="billing_zip_code" class="finput" value="{{ old('billing_zip_code', $customer->billing_zip_code) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Country</label>
                            <select name="billing_country" class="finput">
                                <option value="">-- Select --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('billing_country', $customer->billing_country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="ccard">
                <div class="ccard-h">
                    Shipping Address
                    <button type="button" class="btn btn-sm btn-light" onclick="copyBillingToShipping()">📋 Same as Billing</button>
                </div>
                <div class="ccard-b">
                    <div class="frow"><div class="fcol-full">
                        <label class="flbl">Street Address</label>
                        <textarea name="shipping_address" rows="2" class="finput" id="shipping_address">{{ old('shipping_address', $customer->shipping_address) }}</textarea>
                    </div></div>

                    <div class="frow frow4">
                        <div class="fcol">
                            <label class="flbl">City</label>
                            <input name="shipping_city" class="finput" id="shipping_city" value="{{ old('shipping_city', $customer->shipping_city) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">State</label>
                            <input name="shipping_state" class="finput" id="shipping_state" value="{{ old('shipping_state', $customer->shipping_state) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Zip Code</label>
                            <input name="shipping_zip_code" class="finput" id="shipping_zip_code" value="{{ old('shipping_zip_code', $customer->shipping_zip_code) }}">
                        </div>
                        <div class="fcol">
                            <label class="flbl">Country</label>
                            <select name="shipping_country" class="finput" id="shipping_country">
                                <option value="">-- Select --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('shipping_country', $customer->shipping_country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Notifications (Only for individuals) -->
            @if($customer->isIndividual())
            <div class="ccard">
                <div class="ccard-h">Email Notifications</div>
                <div class="ccard-b">
                    <div class="notifications-grid">
                        <div class="notification-item">
                            <input type="checkbox" name="invoice_emails" value="1" id="invoice" {{ old('invoice_emails', $customer->invoice_emails) ? 'checked' : '' }}>
                            <label for="invoice">Invoice Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="estimate_emails" value="1" id="estimate" {{ old('estimate_emails', $customer->estimate_emails) ? 'checked' : '' }}>
                            <label for="estimate">Estimate Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="credit_note_emails" value="1" id="credit_note" {{ old('credit_note_emails', $customer->credit_note_emails) ? 'checked' : '' }}>
                            <label for="credit_note">Credit Note Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="contract_emails" value="1" id="contract" {{ old('contract_emails', $customer->contract_emails) ? 'checked' : '' }}>
                            <label for="contract">Contract Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="task_emails" value="1" id="task" {{ old('task_emails', $customer->task_emails) ? 'checked' : '' }}>
                            <label for="task">Task Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="project_emails" value="1" id="project" {{ old('project_emails', $customer->project_emails) ? 'checked' : '' }}>
                            <label for="project">Project Emails</label>
                        </div>
                        <div class="notification-item">
                            <input type="checkbox" name="ticket_emails" value="1" id="ticket" {{ old('ticket_emails', $customer->ticket_emails) ? 'checked' : '' }}>
                            <label for="ticket">Ticket Emails</label>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Settings -->
            <div class="ccard">
                <div class="ccard-h">Settings</div>
                <div class="ccard-b">
                    <div class="frow">
                        <div class="fcol">
                            <label class="flbl">Status</label>
                            <select name="active" class="finput">
                                <option value="1" {{ old('active', $customer->active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('active', $customer->active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="factions">
                <button type="submit" class="btn btn-primary">💾 Update Customer</button>
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function copyBillingToShipping() {
            document.getElementById('shipping_address').value = document.querySelector('[name="billing_street"]').value;
            document.getElementById('shipping_city').value = document.querySelector('[name="billing_city"]').value;
            document.getElementById('shipping_state').value = document.querySelector('[name="billing_state"]').value;
            document.getElementById('shipping_zip_code').value = document.querySelector('[name="billing_zip_code"]').value;
            document.getElementById('shipping_country').value = document.querySelector('[name="billing_country"]').value;
        }
    </script>
@endcan