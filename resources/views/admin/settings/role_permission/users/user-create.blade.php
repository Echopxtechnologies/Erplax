{{-- <x-layouts.app> --}}
    <style>
        .staff-page { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .page-header .title h1 { font-size: 24px; font-weight: 600; color: #111827; margin: 0 0 4px 0; }
        .page-header .title p { font-size: 14px; color: #6b7280; margin: 0; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; color: #6366f1; font-size: 14px; font-weight: 500; text-decoration: none; }
        .back-btn:hover { color: #4f46e5; }
        
        .section-card {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .section-header .left { display: flex; align-items: center; gap: 12px; }
        .section-icon {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
        }
        .section-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6366f1); }
        .section-icon.orange { background: linear-gradient(135deg, #f97316, #ef4444); }
        .section-icon.red { background: linear-gradient(135deg, #ef4444, #ec4899); }
        .section-icon.blue { background: linear-gradient(135deg, #3b82f6, #6366f1); }
        .section-icon.green { background: linear-gradient(135deg, #10b981, #14b8a6); }
        .section-title { font-size: 16px; font-weight: 600; color: #111827; }
        .step-badge {
            padding: 6px 14px; background: #eef2ff; border-radius: 20px;
            font-size: 12px; font-weight: 600; color: #6366f1;
        }
        
        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .form-grid.cols-4 { grid-template-columns: repeat(4, 1fr); }
        .form-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        
        .form-group { }
        .form-group.span-2 { grid-column: span 2; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px; }
        .form-group label .req { color: #ef4444; margin-left: 2px; }
        .form-group input, .form-group select {
            width: 100%; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 10px;
            font-size: 14px; background: white; box-sizing: border-box; transition: all 0.2s;
        }
        .form-group input::placeholder { color: #9ca3af; }
        .form-group input:focus, .form-group select:focus {
            outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        
        .pwd-wrapper { position: relative; }
        .pwd-wrapper input { padding-right: 44px; }
        .pwd-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #9ca3af; cursor: pointer;
        }
        .pwd-toggle:hover { color: #6b7280; }
        
        .status-box {
            display: flex; align-items: center; gap: 14px; padding: 16px 20px;
            background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;
        }
        .toggle { position: relative; width: 48px; height: 26px; flex-shrink: 0; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute; cursor: pointer; inset: 0;
            background: #d1d5db; border-radius: 26px; transition: 0.25s;
        }
        .toggle-track:before {
            content: ""; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 3px;
            background: white; border-radius: 50%; transition: 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .toggle input:checked + .toggle-track { background: #10b981; }
        .toggle input:checked + .toggle-track:before { transform: translateX(22px); }
        .status-text strong { display: block; font-size: 14px; color: #111827; margin-bottom: 2px; }
        .status-text span { font-size: 13px; color: #6b7280; }
        
        .roles-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .role-chip {
            display: flex; align-items: center; gap: 10px; padding: 10px 16px;
            background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 10px;
            cursor: pointer; transition: all 0.15s;
        }
        .role-chip:hover { border-color: #d1d5db; background: #f3f4f6; }
        .role-chip.selected { background: #eef2ff; border-color: #6366f1; }
        .role-chip input { display: none; }
        .role-chip .checkbox {
            width: 18px; height: 18px; border: 2px solid #d1d5db; border-radius: 5px;
            display: flex; align-items: center; justify-content: center; transition: all 0.15s;
        }
        .role-chip.selected .checkbox { background: #6366f1; border-color: #6366f1; color: white; }
        .role-chip .checkbox i { font-size: 10px; }
        .role-chip .name { font-size: 14px; font-weight: 500; color: #374151; }
        
        .gen-btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; margin-top: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: white; cursor: pointer; transition: 0.2s;
        }
        .gen-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
        
        .form-actions {
            display: flex; justify-content: flex-end; gap: 12px;
            padding: 20px 0; margin-top: 10px;
        }
        .form-actions .btn { padding: 12px 28px; font-size: 14px; font-weight: 500; border-radius: 10px; }
        
        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert ul { margin: 0; padding-left: 18px; font-size: 14px; }
        
        @media (max-width: 900px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .form-grid.cols-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .form-grid, .form-grid.cols-4, .form-grid.cols-2 { grid-template-columns: 1fr; }
            .form-group.span-2 { grid-column: span 1; }
        }
    </style>

    <div class="staff-page">
        <div class="page-header">
            <div class="title">
                <h1>Create New Staff</h1>
                <p>Add a new staff member to your organization</p>
            </div>
            <a href="{{ route('admin.settings.users.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            </div>
        @endif

        <form action="{{ route('admin.settings.users.store') }}" method="POST" id="staffForm">
            @csrf

            {{-- Section 1: Personal Information --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="left">
                        <div class="section-icon orange"><i class="fas fa-user"></i></div>
                        <span class="section-title">Personal Information</span>
                    </div>
                    <span class="step-badge">Step 1 of 5</span>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 2: Employment Details --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="left">
                        <div class="section-icon purple"><i class="fas fa-briefcase"></i></div>
                        <span class="section-title">Employment Details</span>
                    </div>
                    <span class="step-badge">Step 2 of 5</span>
                </div>
                <div class="form-grid cols-4">
                    <div class="form-group">
                        <label>Employee Code</label>
                        <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="Auto-generated">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Engineering">
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Manager">
                    </div>
                    <div class="form-group">
                        <label>Join Date</label>
                        <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label>Confirmation Date</label>
                        <input type="date" name="confirmation_date" value="{{ old('confirmation_date') }}">
                    </div>
                </div>
            </div>

            {{-- Section 3: Address --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="left">
                        <div class="section-icon red"><i class="fas fa-map-marker-alt"></i></div>
                        <span class="section-title">Address</span>
                    </div>
                    <span class="step-badge">Step 3 of 5</span>
                </div>
                <div class="form-grid">
                    <div class="form-group span-2">
                        <label>Address Line 1</label>
                        <input type="text" name="address_line1" value="{{ old('address_line1') }}" placeholder="Street address">
                    </div>
                    <div class="form-group">
                        <label>Address Line 2</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Apt, suite, building">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State">
                    </div>
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}" placeholder="Pincode">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country" value="{{ old('country', 'India') }}" placeholder="Country">
                    </div>
                </div>
            </div>

            {{-- Section 4: Emergency Contact --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="left">
                        <div class="section-icon blue"><i class="fas fa-phone-alt"></i></div>
                        <span class="section-title">Emergency Contact</span>
                    </div>
                    <span class="step-badge">Step 4 of 5</span>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <label>Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Phone number">
                    </div>
                    <div class="form-group">
                        <label>Relationship</label>
                        <select name="emergency_contact_relation">
                            <option value="">-- Select Relation --</option>
                            @foreach(['Father', 'Mother', 'Spouse', 'Sibling', 'Friend', 'Other'] as $rel)
                                <option value="{{ $rel }}" {{ old('emergency_contact_relation') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 5: Access & Credentials --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="left">
                        <div class="section-icon green"><i class="fas fa-shield-alt"></i></div>
                        <span class="section-title">Access & Credentials</span>
                    </div>
                    <span class="step-badge">Step 5 of 5</span>
                </div>
                
                <label class="status-box" style="margin-bottom: 24px; cursor: pointer;">
                    <div class="toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </div>
                    <div class="status-text">
                        <strong>Account Active</strong>
                        <span>Inactive staff cannot login to the system</span>
                    </div>
                </label>

                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label>Password <span class="req">*</span></label>
                        <div class="pwd-wrapper">
                            <input type="password" name="password" id="password" placeholder="Enter password" required>
                            <button type="button" class="pwd-toggle" onclick="togglePwd('password')"><i class="fas fa-eye" id="password-icon"></i></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="req">*</span></label>
                        <div class="pwd-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required>
                            <button type="button" class="pwd-toggle" onclick="togglePwd('password_confirmation')"><i class="fas fa-eye" id="password_confirmation-icon"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="gen-btn" onclick="genPwd()"><i class="fas fa-magic"></i> Generate Strong Password</button>

                <div style="margin-top: 28px;">
                    <label style="font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 12px; display: block;">
                        Assign Roles <span class="req">*</span>
                    </label>
                    @if($roles->count() > 0)
                        <div class="roles-grid">
                            @foreach($roles as $role)
                                <label class="role-chip {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="checkbox"><i class="fas fa-check"></i></span>
                                    <span class="name">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #6b7280; font-size: 14px;">No roles available</p>
                    @endif
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.settings.users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Staff</button>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.role-chip').forEach(chip => {
            chip.querySelector('input').addEventListener('change', function() {
                chip.classList.toggle('selected', this.checked);
            });
        });

        function togglePwd(id) {
            const field = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function genPwd() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%&*';
            let pwd = '';
            for (let i = 0; i < 14; i++) pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('password').value = pwd;
            document.getElementById('password_confirmation').value = pwd;
            document.getElementById('password').type = 'text';
            document.getElementById('password_confirmation').type = 'text';
            document.getElementById('password-icon').classList.replace('fa-eye', 'fa-eye-slash');
            document.getElementById('password_confirmation-icon').classList.replace('fa-eye', 'fa-eye-slash');
        }

        document.getElementById('staffForm').addEventListener('submit', function(e) {
            const pw = document.querySelector('[name="password"]').value;
            const pc = document.querySelector('[name="password_confirmation"]').value;
            const roles = document.querySelectorAll('[name="roles[]"]:checked');
            
            if (!pw || pw.length < 6) { alert('Password must be at least 6 characters'); e.preventDefault(); return; }
            if (pw !== pc) { alert('Passwords do not match'); e.preventDefault(); return; }
            if (!roles.length) { alert('Please select at least one role'); e.preventDefault(); return; }
        });
    </script>
{{-- </x-layouts.app> --}}