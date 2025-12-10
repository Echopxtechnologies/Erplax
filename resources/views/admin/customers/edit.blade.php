<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 class="page-title" style="margin:0;">Edit Customer</h1>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">← Back</a>
        </div>
    </x-slot>

    <style>
        .cform { max-width:100%; }
        .ccard { background:var(--card-bg, #fff); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:16px; border:1px solid var(--card-border, #e5e7eb); }
        .ccard-h { background:var(--body-bg, #f8f9fa); padding:14px 20px; border-bottom:1px solid var(--card-border, #e5e7eb); font-size:15px; font-weight:600; color:var(--text-primary, #374151); }
        .ccard-b { padding:20px; }
        .frow { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .frow3 { grid-template-columns:repeat(3,1fr); }
        .frow4 { grid-template-columns:repeat(4,1fr); }
        .fcol { min-width:0; }
        .fcol-full { grid-column:1/-1; }
        .flbl { display:block; font-size:13px; font-weight:500; color:var(--text-primary, #374151); margin-bottom:6px; }
        .req { color:#ef4444; margin-left:2px; }
        .finput { width:100%; padding:9px 12px; font-size:14px; border:1px solid var(--card-border, #d1d5db); border-radius:6px; background:var(--card-bg, #fff); color:var(--text-primary, #1f2937); box-sizing:border-box; transition:border-color 0.2s, box-shadow 0.2s; }
        .finput:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .finput.is-invalid { border-color:#ef4444; background:#fef2f2; }
        .finput.is-invalid:focus { box-shadow:0 0 0 3px rgba(239,68,68,0.1); }
        .finput.is-valid { border-color:#10b981; }
        .ferr { color:#ef4444; font-size:12px; margin-top:4px; display:flex; align-items:center; gap:4px; }
        .ferr:before { content:'⚠'; font-size:11px; }
        .fhelp { color:var(--text-muted, #6b7280); font-size:11px; margin-top:4px; }
        .char-count { font-size:11px; color:var(--text-muted, #6b7280); text-align:right; margin-top:2px; }
        .char-count.warning { color:#f59e0b; }
        .char-count.danger { color:#ef4444; font-weight:600; }
        .rgroup { display:flex; gap:24px; margin-top:6px; }
        .rlbl { display:flex; align-items:center; cursor:pointer; color:var(--text-primary, #374151); }
        .rlbl input { width:18px; height:18px; margin-right:8px; }
        .iwbtn { display:flex; gap:8px; }
        .iwbtn select { flex:1; }
        .badd { width:40px; height:40px; border:1px solid var(--card-border, #d1d5db); border-radius:6px; background:var(--body-bg, #f9fafb); color:var(--text-primary, #374151); cursor:pointer; font-size:18px; }
        .tabs { display:flex; background:var(--body-bg, #f8f9fa); }
        .tab { padding:12px 24px; background:transparent; border:none; border-bottom:2px solid transparent; cursor:pointer; font-size:14px; color:var(--text-muted, #6b7280); }
        .tab.active { color:#3b82f6; background:var(--card-bg, #fff); border-bottom-color:#3b82f6; }
        .cpychk { margin-bottom:16px; padding:10px 14px; background:#f0f9ff; border-radius:6px; border:1px solid #bae6fd; }
        .cpychk label { color:var(--text-primary, #374151); display:flex; align-items:center; cursor:pointer; }
        .cpychk input { margin-right:8px; }
        .factions { display:flex; justify-content:flex-end; gap:12px; padding:16px 0; }
        .btn { padding:10px 20px; font-size:14px; font-weight:500; border-radius:6px; cursor:pointer; border:none; text-decoration:none; display:inline-flex; align-items:center; }
        .btn-p { background:#3b82f6; color:#fff; }
        .btn-p:hover { background:#2563eb; }
        .btn-l { background:var(--card-bg, #f3f4f6); color:var(--text-primary, #374151); border:1px solid var(--card-border, #d1d5db); }
        .modal-o { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:9999; display:flex; align-items:center; justify-content:center; }
        .modal-c { background:var(--card-bg, #fff); border-radius:12px; width:100%; max-width:400px; }
        .modal-h { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid var(--card-border, #e5e7eb); }
        .modal-h h4 { margin:0; color:var(--text-primary, #1f2937); }
        .modal-b { padding:20px; }
        .modal-f { display:flex; justify-content:flex-end; gap:12px; padding:16px 20px; border-top:1px solid var(--card-border, #e5e7eb); background:var(--body-bg, #f9fafb); }
        .validation-summary { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:6px; margin-bottom:16px; }
        .validation-summary strong { display:flex; align-items:center; gap:6px; }
        .validation-summary ul { margin:8px 0 0 0; padding-left:20px; }
        .validation-summary li { margin:4px 0; font-size:14px; }
        @media(max-width:1024px) { .frow4 { grid-template-columns:1fr 1fr; } }
        @media(max-width:768px) { .frow,.frow3,.frow4 { grid-template-columns:1fr; } }

        /* Dark mode */
        .dark .ccard, [data-theme="dark"] .ccard { background:#1e293b; border-color:#334155; }
        .dark .ccard-h, [data-theme="dark"] .ccard-h { background:#0f172a; border-color:#334155; color:#f1f5f9; }
        .dark .ccard-b, [data-theme="dark"] .ccard-b { background:#1e293b; }
        .dark .flbl, [data-theme="dark"] .flbl { color:#f1f5f9; }
        .dark .finput, [data-theme="dark"] .finput { background:#0f172a; border-color:#334155; color:#f1f5f9; }
        .dark .finput:focus, [data-theme="dark"] .finput:focus { border-color:#3b82f6; }
        .dark .finput::placeholder, [data-theme="dark"] .finput::placeholder { color:#64748b; }
        .dark .finput.is-invalid, [data-theme="dark"] .finput.is-invalid { background:#450a0a; border-color:#dc2626; }
        .dark .rlbl, [data-theme="dark"] .rlbl { color:#f1f5f9; }
        .dark .badd, [data-theme="dark"] .badd { background:#334155; border-color:#475569; color:#f1f5f9; }
        .dark .tabs, [data-theme="dark"] .tabs { background:#0f172a; }
        .dark .tab, [data-theme="dark"] .tab { color:#94a3b8; }
        .dark .tab.active, [data-theme="dark"] .tab.active { background:#1e293b; color:#3b82f6; }
        .dark .cpychk, [data-theme="dark"] .cpychk { background:#1e3a5f; border-color:#2563eb; }
        .dark .cpychk label, [data-theme="dark"] .cpychk label { color:#bfdbfe; }
        .dark .btn-l, [data-theme="dark"] .btn-l { background:#334155; color:#f1f5f9; border-color:#475569; }
        .dark .modal-c, [data-theme="dark"] .modal-c { background:#1e293b; }
        .dark .modal-h, [data-theme="dark"] .modal-h { border-color:#334155; }
        .dark .modal-h h4, [data-theme="dark"] .modal-h h4 { color:#f1f5f9; }
        .dark .modal-b, [data-theme="dark"] .modal-b { background:#1e293b; }
        .dark .modal-f, [data-theme="dark"] .modal-f { background:#0f172a; border-color:#334155; }
        .dark select.finput, [data-theme="dark"] select.finput { background:#0f172a; }
        .dark textarea.finput, [data-theme="dark"] textarea.finput { background:#0f172a; }
        .dark .validation-summary, [data-theme="dark"] .validation-summary { background:#450a0a; border-color:#7f1d1d; color:#fca5a5; }
        .dark .fhelp, [data-theme="dark"] .fhelp { color:#94a3b8; }
        .dark .char-count, [data-theme="dark"] .char-count { color:#94a3b8; }
    </style>

    <div class="cform">
        @if($errors->any())
            <div class="validation-summary">
                <strong>⚠️ Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.customers.update', $customer) }}" method="POST" id="customerForm" novalidate>
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="ccard">
                <div class="ccard-h">Basic Information</div>
                <div class="ccard-b">
                    @php $ctype = old('customer_type', $customer->customer_type ?? 'individual'); @endphp
                    <div class="frow"><div class="fcol-full">
                        <label class="flbl">Customer Type <span class="req">*</span></label>
                        <div class="rgroup">
                            <label class="rlbl"><input type="radio" name="customer_type" value="individual" class="ctype-radio" {{ $ctype==='individual'?'checked':'' }} required> Individual</label>
                            <label class="rlbl"><input type="radio" name="customer_type" value="company" class="ctype-radio" {{ $ctype==='company'?'checked':'' }}> Company</label>
                        </div>
                    </div></div>

                    <div class="frow frow3">
                        <div class="fcol">
                            <label class="flbl" for="name">Name <span class="req">*</span></label>
                            <input type="text" name="name" id="name" class="finput @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $customer->name) }}" required minlength="2" maxlength="100" 
                                   placeholder="Enter full name">
                            @error('name')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="char-count"><span class="current">{{ strlen(old('name', $customer->name ?? '')) }}</span>/100</div>
                        </div>
                        <div class="fcol">
                            <label class="flbl" for="email">Email <span class="req">*</span></label>
                            <input type="email" name="email" id="email" class="finput @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $customer->email) }}" required maxlength="150" 
                                   placeholder="email@example.com">
                            @error('email')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="char-count"><span class="current">{{ strlen(old('email', $customer->email ?? '')) }}</span>/150</div>
                        </div>
                        <div class="fcol">
                            <label class="flbl" for="phone">Phone</label>
                            <input type="tel" name="phone" id="phone" class="finput @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $customer->phone) }}" minlength="10" maxlength="20" 
                                   placeholder="e.g., 9876543210">
                            @error('phone')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="fhelp">10-20 digits only</div>
                        </div>
                    </div>

                    <div class="frow"><div class="fcol">
                        <label class="flbl" for="group_name">Group</label>
                        <div class="iwbtn">
                            <select name="group_name" id="group_name" class="finput">
                                <option value="">Select group</option>
                                @foreach($groups ?? [] as $g)
                                    <option value="{{ $g }}" {{ old('group_name', $customer->group_name)===$g?'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="badd" onclick="openModal()" title="Add new group">+</button>
                        </div>
                        <div class="fhelp">Optional - categorize your customer</div>
                    </div><div class="fcol"></div></div>
                </div>
            </div>

            {{-- Company Details --}}
            <div class="ccard" id="compCard" style="display:{{ $ctype==='company'?'block':'none' }};">
                <div class="ccard-h">Company Details</div>
                <div class="ccard-b" id="compFields">
                    <div class="frow frow4">
                        <div class="fcol">
                            <label class="flbl">Company Name <span class="req" id="compReq">*</span></label>
                            @livewire('admin.customers.company-search', ['value' => old('company', $customer->company ?? '')])
                            @error('company')<div class="ferr">{{ $message }}</div>@enderror
                            {{-- <div class="fhelp">Must be unique</div> --}}
                        </div>
                        <div class="fcol">
                            <label class="flbl" for="designation">Designation</label>
                            <input type="text" name="designation" id="designation" class="finput @error('designation') is-invalid @enderror" 
                                   value="{{ old('designation', $customer->designation) }}" maxlength="100" 
                                   placeholder="e.g., Manager, CEO">
                            @error('designation')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="char-count"><span class="current">{{ strlen(old('designation', $customer->designation ?? '')) }}</span>/100</div>
                        </div>
                        <div class="fcol">
                            <label class="flbl" for="website">Website</label>
                            <input type="url" name="website" id="website" class="finput @error('website') is-invalid @enderror" 
                                   value="{{ old('website', $customer->website) }}" maxlength="200" 
                                   placeholder="https://example.com">
                            @error('website')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="fhelp">Include https://</div>
                        </div>
                        <div class="fcol">
                            <label class="flbl" for="gst_number">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="finput @error('gst_number') is-invalid @enderror" 
                                   value="{{ old('gst_number', $customer->gst_number) }}" maxlength="20" 
                                   placeholder="e.g., 29ABCDE1234F1Z5" style="text-transform:uppercase;">
                            @error('gst_number')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="char-count"><span class="current">{{ strlen(old('gst_number', $customer->gst_number ?? '')) }}</span>/20</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <div class="ccard">
                <div class="tabs">
                    <button type="button" class="tab active" data-t="bill">📍 Billing Address</button>
                    <button type="button" class="tab" data-t="ship">🚚 Shipping Address</button>
                </div>
                <div class="ccard-b">
                    <div id="bill-tab">
                        <div class="frow"><div class="fcol-full">
                            <label class="flbl" for="address">Street Address</label>
                            <textarea name="address" id="address" class="finput @error('address') is-invalid @enderror" 
                                      rows="2" maxlength="500" placeholder="Enter street address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="char-count"><span class="current">{{ strlen(old('address', $customer->address ?? '')) }}</span>/500</div>
                        </div></div>
                        <div class="frow frow4">
                            <div class="fcol">
                                <label class="flbl" for="city">City</label>
                                <input type="text" name="city" id="city" class="finput" value="{{ old('city', $customer->city) }}" maxlength="100" placeholder="City">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="state">State</label>
                                <input type="text" name="state" id="state" class="finput" value="{{ old('state', $customer->state) }}" maxlength="100" placeholder="State">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="zip_code">ZIP Code</label>
                                <input type="text" name="zip_code" id="zip_code" class="finput" value="{{ old('zip_code', $customer->zip_code) }}" maxlength="20" placeholder="ZIP Code">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="country">Country</label>
                                <input type="text" name="country" id="country" class="finput" value="{{ old('country', $customer->country) }}" maxlength="100" placeholder="Country">
                            </div>
                        </div>
                    </div>
                    <div id="ship-tab" style="display:none;">
                        <div class="cpychk">
                            <label><input type="checkbox" id="cpybill"> Same as billing address</label>
                        </div>
                        <div class="frow"><div class="fcol-full">
                            <label class="flbl" for="shipping_address">Street Address</label>
                            <textarea name="shipping_address" id="shipping_address" class="finput" rows="2" maxlength="500" placeholder="Enter shipping address">{{ old('shipping_address', $customer->shipping_address) }}</textarea>
                            <div class="char-count"><span class="current">{{ strlen(old('shipping_address', $customer->shipping_address ?? '')) }}</span>/500</div>
                        </div></div>
                        <div class="frow frow4">
                            <div class="fcol">
                                <label class="flbl" for="shipping_city">City</label>
                                <input type="text" name="shipping_city" id="shipping_city" class="finput" value="{{ old('shipping_city', $customer->shipping_city) }}" maxlength="100" placeholder="City">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="shipping_state">State</label>
                                <input type="text" name="shipping_state" id="shipping_state" class="finput" value="{{ old('shipping_state', $customer->shipping_state) }}" maxlength="100" placeholder="State">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="shipping_zip_code">ZIP Code</label>
                                <input type="text" name="shipping_zip_code" id="shipping_zip_code" class="finput" value="{{ old('shipping_zip_code', $customer->shipping_zip_code) }}" maxlength="20" placeholder="ZIP Code">
                            </div>
                            <div class="fcol">
                                <label class="flbl" for="shipping_country">Country</label>
                                <input type="text" name="shipping_country" id="shipping_country" class="finput" value="{{ old('shipping_country', $customer->shipping_country) }}" maxlength="100" placeholder="Country">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="ccard">
                <div class="ccard-h">Additional Notes</div>
                <div class="ccard-b">
                    <textarea name="notes" id="notes" class="finput @error('notes') is-invalid @enderror" 
                              rows="3" maxlength="2000" placeholder="Any additional notes about this customer...">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes')<div class="ferr">{{ $message }}</div>@enderror
                    <div class="char-count"><span class="current">{{ strlen(old('notes', $customer->notes ?? '')) }}</span>/2000</div>
                </div>
            </div>

            <div class="factions">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-l">Cancel</a>
                <button type="submit" class="btn btn-p" id="submitBtn">💾 Update Customer</button>
            </div>
        </form>
    </div>

    {{-- Add Group Modal --}}
    <div id="grpModal" class="modal-o" style="display:none;">
        <div class="modal-c">
            <div class="modal-h">
                <h4>Add New Group</h4>
                <button onclick="closeModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-muted);">&times;</button>
            </div>
            <div class="modal-b">
                <label class="flbl">Group Name <span class="req">*</span></label>
                <input id="grp_name" class="finput" maxlength="50" placeholder="Enter group name">
                <div class="fhelp">Max 50 characters</div>
                <div id="grp_err" class="ferr" style="display:none;"></div>
            </div>
            <div class="modal-f">
                <button type="button" class="btn btn-l" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-p" onclick="saveGrp()">Add Group</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Customer type toggle
        const compCard = document.getElementById('compCard');
        
        function toggleComp() {
            const isCompany = document.querySelector('.ctype-radio:checked')?.value === 'company';
            compCard.style.display = isCompany ? 'block' : 'none';
            const compInput = document.querySelector('input[name="company"]');
            if(compInput) compInput.required = isCompany;
        }
        document.querySelectorAll('.ctype-radio').forEach(r => r.addEventListener('change', toggleComp));
        toggleComp();

        // Tab switching
        document.querySelectorAll('.tab').forEach(t => {
            t.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('bill-tab').style.display = this.dataset.t === 'bill' ? 'block' : 'none';
                document.getElementById('ship-tab').style.display = this.dataset.t === 'ship' ? 'block' : 'none';
            });
        });

        // Copy billing to shipping
        document.getElementById('cpybill')?.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('shipping_address').value = document.getElementById('address').value;
                document.getElementById('shipping_city').value = document.getElementById('city').value;
                document.getElementById('shipping_state').value = document.getElementById('state').value;
                document.getElementById('shipping_zip_code').value = document.getElementById('zip_code').value;
                document.getElementById('shipping_country').value = document.getElementById('country').value;
                updateAllCharCounts();
            }
        });

        // Livewire company selection
        window.addEventListener('companySelected', function(e) {
            const d = e.detail.data || e.detail;
            document.getElementById('website').value = d.website || '';
            document.getElementById('gst_number').value = d.gst_number || '';
            updateAllCharCounts();
        });

        // Character count
        function updateCharCount(input) {
            const counter = input.closest('.fcol, .fcol-full')?.querySelector('.char-count .current');
            if (counter) {
                const len = input.value.length;
                const max = parseInt(input.getAttribute('maxlength') || 0);
                counter.textContent = len;
                const parent = counter.parentElement;
                parent.classList.remove('warning', 'danger');
                if (max > 0) {
                    if (len >= max) parent.classList.add('danger');
                    else if (len >= max * 0.8) parent.classList.add('warning');
                }
            }
        }

        function updateAllCharCounts() {
            document.querySelectorAll('.finput[maxlength]').forEach(updateCharCount);
        }

        document.querySelectorAll('.finput[maxlength]').forEach(input => {
            input.addEventListener('input', () => updateCharCount(input));
            updateCharCount(input);
        });

        // Validation
        function validateField(input) {
            const value = input.value.trim();
            let isValid = true;
            let errorMsg = '';

            input.classList.remove('is-invalid', 'is-valid');
            const existingErr = input.closest('.fcol, .fcol-full')?.querySelector('.ferr.client-error');
            if (existingErr) existingErr.remove();

            if (input.hasAttribute('required') && !value) {
                isValid = false;
                errorMsg = 'This field is required';
            }

            if (isValid && input.hasAttribute('minlength') && value) {
                const min = parseInt(input.getAttribute('minlength'));
                if (value.length < min) {
                    isValid = false;
                    errorMsg = `Minimum ${min} characters required`;
                }
            }

            if (isValid && input.type === 'email' && value) {
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    errorMsg = 'Please enter a valid email';
                }
            }

            if (isValid && input.type === 'tel' && value) {
                if (value.replace(/\D/g, '').length < 10) {
                    isValid = false;
                    errorMsg = 'Phone must have at least 10 digits';
                }
            }

            if (isValid && input.type === 'url' && value) {
                try { new URL(value); } catch(e) {
                    isValid = false;
                    errorMsg = 'Please enter a valid URL';
                }
            }

            if (value) input.classList.add(isValid ? 'is-valid' : 'is-invalid');
            if (!isValid && errorMsg) {
                const errDiv = document.createElement('div');
                errDiv.className = 'ferr client-error';
                errDiv.textContent = errorMsg;
                input.closest('.fcol, .fcol-full')?.appendChild(errDiv);
            }
            return isValid;
        }

        document.querySelectorAll('.finput:not([type="hidden"])').forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const err = this.closest('.fcol, .fcol-full')?.querySelector('.ferr.client-error');
                if (err) err.remove();
            });
        });

        document.getElementById('customerForm').addEventListener('submit', function(e) {
            let isValid = true;
            let firstError = null;

            this.querySelectorAll('.finput[required]').forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                    if (!firstError) firstError = input;
                }
            });

            if (document.querySelector('.ctype-radio[value="company"]').checked) {
                const compInput = document.querySelector('input[name="company"]');
                if (compInput && !compInput.value.trim()) {
                    isValid = false;
                    compInput.classList.add('is-invalid');
                    if (!firstError) firstError = compInput;
                }
            }

            if (!isValid) {
                e.preventDefault();
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        document.getElementById('phone')?.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });

        document.getElementById('gst_number')?.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    function openModal() {
        document.getElementById('grpModal').style.display = 'flex';
        document.getElementById('grp_name').value = '';
        document.getElementById('grp_err').style.display = 'none';
        document.getElementById('grp_name').focus();
    }
    function closeModal() { document.getElementById('grpModal').style.display = 'none'; }
    document.getElementById('grpModal')?.addEventListener('click', e => { if (e.target.id === 'grpModal') closeModal(); });

    function saveGrp() {
        const name = document.getElementById('grp_name').value.trim();
        const err = document.getElementById('grp_err');
        if (!name) { err.textContent = 'Please enter a group name'; err.style.display = 'block'; return; }
        if (name.length > 50) { err.textContent = 'Max 50 characters'; err.style.display = 'block'; return; }

        fetch('{{ route("admin.customers.addGroup") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ group_name: name })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const select = document.getElementById('group_name');
                const option = document.createElement('option');
                option.value = option.textContent = d.group_name;
                select.appendChild(option);
                select.value = d.group_name;
                closeModal();
            } else { err.textContent = d.message || 'Error'; err.style.display = 'block'; }
        });
    }
    </script>
</x-layouts.app>