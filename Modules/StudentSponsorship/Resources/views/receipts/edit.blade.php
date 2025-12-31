<style>
.page-container { padding: 24px; background: var(--body-bg, #f3f4f6); min-height: calc(100vh - 60px); }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.page-title { font-size: 24px; font-weight: 700; color: var(--text-primary, #1f2937); display: flex; align-items: center; gap: 12px; }
.page-title svg { width: 28px; height: 28px; color: #8b5cf6; }
.currency-badge { background: linear-gradient(135deg, {{ $template->primary_color }}, {{ $template->secondary_color }}); color: #fff; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 16px; }

.header-actions { display: flex; gap: 12px; }
.btn { padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s; }
.btn svg { width: 18px; height: 18px; }
.btn-back { background: var(--card-bg, #fff); color: var(--text-primary, #374151); border: 1px solid var(--border-color, #d1d5db); }
.btn-back:hover { background: var(--body-bg, #f3f4f6); }
.btn-preview { background: #f3e8ff; color: #7c3aed; }
.btn-preview:hover { background: #e9d5ff; }
.btn-save { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
.btn-save:hover { box-shadow: 0 4px 12px rgba(139,92,246,0.4); }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 1024px) { .form-grid { grid-template-columns: 1fr; } }

.form-section { background: var(--card-bg, #fff); border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.form-section-title { font-size: 16px; font-weight: 700; color: var(--text-primary, #1f2937); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.form-section-title svg { width: 20px; height: 20px; color: #8b5cf6; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary, #374151); margin-bottom: 6px; }
.form-label span { color: #dc2626; }
.form-input, .form-textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color, #d1d5db); border-radius: 8px; font-size: 14px; background: var(--input-bg, #fff); color: var(--input-text, #374151); }
.form-input:focus, .form-textarea:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1); }
.form-textarea { resize: vertical; min-height: 80px; }
.form-hint { font-size: 12px; color: var(--text-muted, #6b7280); margin-top: 4px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }

.color-input-wrapper { display: flex; gap: 10px; align-items: center; }
.color-input { width: 50px; height: 40px; padding: 2px; border: 1px solid var(--border-color, #d1d5db); border-radius: 8px; cursor: pointer; }
.color-hex { flex: 1; }

.toggle-wrapper { display: flex; align-items: center; gap: 12px; }
.toggle { position: relative; width: 48px; height: 26px; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; inset: 0; background: #cbd5e1; border-radius: 26px; transition: 0.3s; }
.toggle-slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.3s; }
.toggle input:checked + .toggle-slider { background: #8b5cf6; }
.toggle input:checked + .toggle-slider:before { transform: translateX(22px); }
.toggle-label { font-size: 14px; font-weight: 500; color: var(--text-primary, #374151); }

.preview-frame { border: 1px solid var(--border-color, #d1d5db); border-radius: 8px; height: 400px; width: 100%; }
</style>

<div class="page-container">
    <form action="{{ route('admin.studentsponsorship.receipts.update', $template->currency) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="page-header">
            <h1 class="page-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Edit Receipt Template
                <span class="currency-badge">{{ $template->currency }}</span>
            </h1>
            <div class="header-actions">
                <a href="{{ route('admin.studentsponsorship.receipts.index') }}" class="btn btn-back">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
                <a href="{{ route('admin.studentsponsorship.receipts.preview', $template->currency) }}" target="_blank" class="btn btn-preview">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </a>
                <button type="submit" class="btn btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
            </div>
        </div>

        @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <div class="form-grid">
            <!-- Left Column -->
            <div>
                <!-- Currency Info -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Currency Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Currency Name</label>
                            <input type="text" name="currency_name" class="form-input" value="{{ old('currency_name', $template->currency_name) }}" placeholder="e.g., Sri Lankan Rupees">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-input" value="{{ old('currency_symbol', $template->currency_symbol) }}" placeholder="e.g., Rs.">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="toggle-wrapper">
                            <label class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Template Active</span>
                        </div>
                    </div>
                </div>

                <!-- Organization Details -->
                <div class="form-section" style="margin-top: 24px;">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Organization Details
                    </div>
                    <div class="form-group">
                        <label class="form-label">Organization Name <span>*</span></label>
                        <input type="text" name="organization_name" class="form-input" value="{{ old('organization_name', $template->organization_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea name="organization_address" class="form-textarea" rows="2">{{ old('organization_address', $template->organization_address) }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="organization_phone" class="form-input" value="{{ old('organization_phone', $template->organization_phone) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="organization_email" class="form-input" value="{{ old('organization_email', $template->organization_email) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="text" name="organization_website" class="form-input" value="{{ old('organization_website', $template->organization_website) }}">
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="form-section" style="margin-top: 24px;">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Bank Details (Optional)
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-input" value="{{ old('bank_name', $template->bank_name) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Name</label>
                            <input type="text" name="bank_account_name" class="form-input" value="{{ old('bank_account_name', $template->bank_account_name) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_account_number" class="form-input" value="{{ old('bank_account_number', $template->bank_account_number) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">SWIFT Code</label>
                            <input type="text" name="bank_swift_code" class="form-input" value="{{ old('bank_swift_code', $template->bank_swift_code) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Branch</label>
                        <input type="text" name="bank_branch" class="form-input" value="{{ old('bank_branch', $template->bank_branch) }}">
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Receipt Content -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Receipt Content
                    </div>
                    <div class="form-group">
                        <label class="form-label">Receipt Title <span>*</span></label>
                        <input type="text" name="receipt_title" class="form-input" value="{{ old('receipt_title', $template->receipt_title) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Header Text</label>
                        <textarea name="header_text" class="form-textarea" rows="2" placeholder="Text displayed below title">{{ old('header_text', $template->header_text) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thank You Message</label>
                        <textarea name="thank_you_message" class="form-textarea" rows="2">{{ old('thank_you_message', $template->thank_you_message) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Footer Text</label>
                        <textarea name="footer_text" class="form-textarea" rows="2">{{ old('footer_text', $template->footer_text) }}</textarea>
                    </div>
                </div>

                <!-- Styling -->
                <div class="form-section" style="margin-top: 24px;">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Styling
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Primary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="primary_color" class="color-input" value="{{ old('primary_color', $template->primary_color) }}" id="primaryColor">
                                <input type="text" class="form-input color-hex" value="{{ old('primary_color', $template->primary_color) }}" id="primaryColorHex" maxlength="7">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Secondary Color</label>
                            <div class="color-input-wrapper">
                                <input type="color" name="secondary_color" class="color-input" value="{{ old('secondary_color', $template->secondary_color) }}" id="secondaryColor">
                                <input type="text" class="form-input color-hex" value="{{ old('secondary_color', $template->secondary_color) }}" id="secondaryColorHex" maxlength="7">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="form-section" style="margin-top: 24px;">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Live Preview
                        <a href="{{ route('admin.studentsponsorship.receipts.preview', $template->currency) }}" target="_blank" style="margin-left:auto;font-size:12px;color:#7c3aed;font-weight:500;">Open in new tab →</a>
                    </div>
                    <iframe src="{{ route('admin.studentsponsorship.receipts.preview', $template->currency) }}" class="preview-frame"></iframe>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Sync color inputs
document.getElementById('primaryColor').addEventListener('input', function() {
    document.getElementById('primaryColorHex').value = this.value;
});
document.getElementById('primaryColorHex').addEventListener('input', function() {
    document.getElementById('primaryColor').value = this.value;
});
document.getElementById('secondaryColor').addEventListener('input', function() {
    document.getElementById('secondaryColorHex').value = this.value;
});
document.getElementById('secondaryColorHex').addEventListener('input', function() {
    document.getElementById('secondaryColor').value = this.value;
});
</script>
