{{-- <x-layouts.app> --}}
<div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <!-- Modern Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #1e293b;">Create New Lead</h1>
                <p style="margin: 4px 0 0 0; font-size: 14px; color: #64748b;">Add a new lead to your pipeline</p>
            </div>
        </div>
        <a href="{{ route('admin.leads.index') }}" style="background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;">
            ← Back to Leads
        </a>
    </div>

    <form method="POST" action="{{ route('admin.leads.store') }}">
        @csrf
        
        <!-- Card Container -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            
            <!-- Section 1: Lead Details -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 16px 24px;">
                <h2 style="margin: 0; color: white; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Lead Information
                </h2>
            </div>
            
            <div style="padding: 24px;">
                <!-- Status, Source, Assigned -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">
                            <span style="color: #ef4444;">*</span> Status
                        </label>
                        <div style="display: flex; gap: 8px;">
                            <select name="status" style="flex: 1; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; background: white;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status', $status->isdefault ? $status->id : '') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openStatusModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">+</button>
                        </div>
                        @error('status')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">
                            <span style="color: #ef4444;">*</span> Source
                        </label>
                        <div style="display: flex; gap: 8px;">
                            <select name="source" style="flex: 1; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; background: white;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                                <option value="">Select Source</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}" {{ old('source') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openSourceModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">+</button>
                        </div>
                        @error('source')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Assigned To</label>
                        <select name="assigned" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; background: white;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ old('assigned', auth()->guard('admin')->id()) == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tags -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">
                        <svg style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tags
                    </label>
                    <input type="text" name="tags" placeholder="e.g., Hot Lead, Priority, Follow-up" value="{{ old('tags') }}" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
            </div>

            <!-- Section 2: Contact Information -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 16px 24px; margin-top: 8px;">
                <h2 style="margin: 0; color: white; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Contact Information
                </h2>
            </div>

            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">
                            <span style="color: #ef4444;">*</span> Full Name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter full name" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('name')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Position/Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g., CEO, Manager" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        @error('email')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Phone Number</label>
                        <input type="text" name="phonenumber" value="{{ old('phonenumber') }}" placeholder="+91 1234567890" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Company Name</label>
                        <input type="text" name="company" value="{{ old('company') }}" placeholder="Company name" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Website</label>
                        <input type="url" name="website" value="{{ old('website') }}" placeholder="https://company.com" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Section 3: Address & Location -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 16px 24px; margin-top: 8px;">
                <h2 style="margin: 0; color: white; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Address & Location
                </h2>
            </div>

            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Street Address</label>
                        <textarea name="address" rows="3" placeholder="Enter full address" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box; resize: vertical;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="City" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="State" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Country</label>
                        <select name="country" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; background: white;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Select Country</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Zip/Postal Code</label>
                        <input type="text" name="zip" value="{{ old('zip') }}" placeholder="560001" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Section 4: Additional Details -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 16px 24px; margin-top: 8px;">
                <h2 style="margin: 0; color: white; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Additional Details
                </h2>
            </div>

            <div style="padding: 24px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Lead Value (₹)</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748b; font-weight: 600;">₹</span>
                        <input type="number" step="0.01" name="lead_value" value="{{ old('lead_value', '0.00') }}" style="width: 100%; padding: 10px 12px 10px 32px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Description / Notes</label>
                    <textarea name="description" rows="4" placeholder="Add any additional notes or comments about this lead..." style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.2s; box-sizing: border-box; resize: vertical;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description') }}</textarea>
                </div>

                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} style="margin-right: 12px; width: 20px; height: 20px; cursor: pointer; accent-color: #667eea;">
                        <div>
                            <span style="font-weight: 600; color: #1e293b; font-size: 14px;">Visible to All Staff</span>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">When enabled, all team members can view and edit this lead</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="background: #f8fafc; padding: 20px 24px; border-top: 1px solid #e2e8f0; display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.leads.index') }}" style="background: white; color: #475569; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 2px solid #e2e8f0; transition: all 0.2s; display: inline-block;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    Cancel
                </a>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'">
                    <svg style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Lead
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modern Status Modal -->
<div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; padding: 0; border-radius: 16px; width: 450px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 24px; border-radius: 16px 16px 0 0;">
            <h3 style="margin: 0; color: white; font-size: 20px; font-weight: 700;">Add New Status</h3>
        </div>
        <form id="statusForm" style="padding: 24px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Status Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="statusName" required placeholder="e.g., Hot Lead, Cold Lead" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Color</label>
                <input type="color" id="statusColor" value="#3498db" style="width: 100%; height: 48px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeStatusModal()" style="flex: 1; background: #f1f5f9; color: #475569; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancel</button>
                <button type="submit" style="flex: 1; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Save Status</button>
            </div>
        </form>
    </div>
</div>

<!-- Modern Source Modal -->
<div id="sourceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; padding: 0; border-radius: 16px; width: 450px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px 24px; border-radius: 16px 16px 0 0;">
            <h3 style="margin: 0; color: white; font-size: 20px; font-weight: 700;">Add New Source</h3>
        </div>
        <form id="sourceForm" style="padding: 24px;">
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Source Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="sourceName" required placeholder="e.g., Website, Referral, Social Media" style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeSourceModal()" style="flex: 1; background: #f1f5f9; color: #475569; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancel</button>
                <button type="submit" style="flex: 1; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Save Source</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function openStatusModal() {
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    document.getElementById('statusForm').reset();
}

function openSourceModal() {
    document.getElementById('sourceModal').style.display = 'flex';
}

function closeSourceModal() {
    document.getElementById('sourceModal').style.display = 'none';
    document.getElementById('sourceForm').reset();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('statusName').value;
    const color = document.getElementById('statusColor').value;
    
    fetch('{{ route('admin.leads.status.quick-create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name: name, color: color })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusSelect = document.querySelector('select[name="status"]');
            const option = document.createElement('option');
            option.value = data.status.id;
            option.text = data.status.name;
            option.selected = true;
            statusSelect.add(option);
            
            alert(data.message || 'Status created successfully!');
            closeStatusModal();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create status');
    });
});

document.getElementById('sourceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('sourceName').value;
    
    fetch('{{ route('admin.leads.source.quick-create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name: name })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const sourceSelect = document.querySelector('select[name="source"]');
            const option = document.createElement('option');
            option.value = data.source.id;
            option.text = data.source.name;
            option.selected = true;
            sourceSelect.add(option);
            
            alert(data.message || 'Source created successfully!');
            closeSourceModal();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create source');
    });
});

document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

document.getElementById('sourceModal').addEventListener('click', function(e) {
    if (e.target === this) closeSourceModal();
});
</script>

{{-- </x-layouts.app> --}}