<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }

.settings-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
@media (max-width: 1024px) { .settings-grid { grid-template-columns: 1fr; } }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; display: flex; align-items: center; gap: 8px; }
.card-body { padding: 24px; }

.form-group { margin-bottom: 20px; }
.form-group:last-child { margin-bottom: 0; }
.form-label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
.form-control { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; width: 100%; box-sizing: border-box; transition: all 0.2s; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 10px center; background-repeat: no-repeat; background-size: 16px; padding-right: 36px; }
textarea.form-control { resize: vertical; min-height: 80px; }

.form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.form-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

.color-input-wrapper { display: flex; align-items: center; gap: 10px; }
.color-input-wrapper input[type="color"] { width: 50px; height: 38px; border: 1px solid #d1d5db; border-radius: 6px; padding: 2px; cursor: pointer; }
.color-input-wrapper input[type="text"] { flex: 1; }

.toggle-switch { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
.toggle-switch:last-child { border-bottom: none; }
.toggle-label { font-size: 14px; color: #374151; }
.toggle-desc { font-size: 12px; color: #6b7280; margin-top: 2px; }
.switch { position: relative; width: 44px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #d1d5db; transition: .3s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
input:checked + .slider { background-color: #6366f1; }
input:checked + .slider:before { transform: translateX(20px); }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 12px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }

.preview-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 16px; }
.preview-header { padding: 15px; border-radius: 6px; color: #fff; margin-bottom: 15px; }
.preview-header h4 { margin: 0; font-size: 14px; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 11px; }
.preview-table th { padding: 8px; text-align: left; border-bottom: 2px solid #ddd; }
.preview-table td { padding: 8px; border-bottom: 1px solid #eee; }
.preview-total { padding: 8px; text-align: right; font-weight: bold; border-radius: 4px; }

.form-actions { margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb; }
</style>

<div class="page-header">
    <h1>Purchase Settings</h1>
</div>

@if(session('success'))
<div class="alert alert-success">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.purchase.settings.update') }}" method="POST">
    @csrf
    
    <div class="settings-grid">
        <!-- General Settings -->
        <div class="card">
            <div class="card-header">
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    General Settings
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Vendor Prefix</label>
                        <input type="text" name="vendor_prefix" class="form-control" value="{{ $settings['vendor_prefix'] }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PR Prefix</label>
                        <input type="text" name="pr_prefix" class="form-control" value="{{ $settings['pr_prefix'] }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PO Prefix</label>
                        <input type="text" name="po_prefix" class="form-control" value="{{ $settings['po_prefix'] }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Default Payment Terms</label>
                        <select name="default_payment_terms" class="form-control">
                            <option value="Immediate" {{ $settings['default_payment_terms'] == 'Immediate' ? 'selected' : '' }}>Immediate</option>
                            <option value="Net 15" {{ $settings['default_payment_terms'] == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                            <option value="Net 30" {{ $settings['default_payment_terms'] == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                            <option value="Net 45" {{ $settings['default_payment_terms'] == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                            <option value="Net 60" {{ $settings['default_payment_terms'] == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default Tax %</label>
                        <input type="number" name="default_tax_percent" class="form-control" value="{{ $settings['default_tax_percent'] }}" min="0" max="100" step="0.01">
                    </div>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">PR Approval Required</div>
                        <div class="toggle-desc">Require approval before converting PR to PO</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pr_approval_required" {{ $settings['pr_approval_required'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- PDF Style Settings -->
        <div class="card">
            <div class="card-header">
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    PDF Style
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Primary Color (Header & Highlights)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="primaryColorPicker" value="{{ $settings['pdf_primary_color'] }}" onchange="document.getElementById('pdf_primary_color').value = this.value; updatePreview();">
                            <input type="text" name="pdf_primary_color" id="pdf_primary_color" class="form-control" value="{{ $settings['pdf_primary_color'] }}" onchange="document.getElementById('primaryColorPicker').value = this.value; updatePreview();">
                        </div>
                        <div class="form-hint">Used for header background and total row</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Secondary Color (Backgrounds)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="secondaryColorPicker" value="{{ $settings['pdf_secondary_color'] }}" onchange="document.getElementById('pdf_secondary_color').value = this.value; updatePreview();">
                            <input type="text" name="pdf_secondary_color" id="pdf_secondary_color" class="form-control" value="{{ $settings['pdf_secondary_color'] }}" onchange="document.getElementById('secondaryColorPicker').value = this.value; updatePreview();">
                        </div>
                        <div class="form-hint">Used for section backgrounds</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Font Size</label>
                    <select name="pdf_font_size" class="form-control" onchange="updatePreview()">
                        <option value="8" {{ $settings['pdf_font_size'] == '8' ? 'selected' : '' }}>Small (8pt) - Fits more content</option>
                        <option value="9" {{ $settings['pdf_font_size'] == '9' ? 'selected' : '' }}>Normal (9pt) - Recommended</option>
                        <option value="10" {{ $settings['pdf_font_size'] == '10' ? 'selected' : '' }}>Large (10pt) - Easier to read</option>
                    </select>
                </div>
                
                <!-- Preview -->
                <div class="preview-box">
                    <div class="preview-header" id="previewHeader" style="background-color: {{ $settings['pdf_primary_color'] }}">
                        <h4>PURCHASE ORDER Preview</h4>
                    </div>
                    <table class="preview-table" id="previewTable" style="background-color: {{ $settings['pdf_secondary_color'] }}">
                        <tr><th>Item</th><th>Qty</th><th>Rate</th><th>Amount</th></tr>
                        <tr><td>Sample Product</td><td>10</td><td>Rs. 100</td><td>Rs. 1,000</td></tr>
                    </table>
                    <div class="preview-total" id="previewTotal" style="background-color: {{ $settings['pdf_primary_color'] }}; color: #fff; margin-top: 10px;">
                        Total: Rs. 1,000
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Options -->
        <div class="card">
            <div class="card-header">
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                    PDF Display Options
                </h5>
            </div>
            <div class="card-body">
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Compact Mode</div>
                        <div class="toggle-desc">Reduce spacing to fit more on one page</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_compact_mode" {{ $settings['pdf_compact_mode'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Show Company Logo</div>
                        <div class="toggle-desc">Display logo in PDF header</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_show_logo" {{ $settings['pdf_show_logo'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Show GST Numbers</div>
                        <div class="toggle-desc">Display GSTIN in address sections</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_show_gst" {{ $settings['pdf_show_gst'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Show Terms & Conditions</div>
                        <div class="toggle-desc">Display terms section in PDF</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_show_terms" {{ $settings['pdf_show_terms'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Show Signature Section</div>
                        <div class="toggle-desc">Display signature lines at bottom</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_show_signature" {{ $settings['pdf_show_signature'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="toggle-switch">
                    <div>
                        <div class="toggle-label">Show Notes</div>
                        <div class="toggle-desc">Display notes/remarks section</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="pdf_show_notes" {{ $settings['pdf_show_notes'] ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Default Terms -->
        <div class="card">
            <div class="card-header">
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Default Terms & Conditions
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Terms Text</label>
                    <textarea name="po_terms" class="form-control" rows="5">{{ $settings['po_terms'] }}</textarea>
                    <div class="form-hint">These terms will be pre-filled when creating new Purchase Orders</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Settings
        </button>
    </div>
</form>

<script>
function updatePreview() {
    const primaryColor = document.getElementById('pdf_primary_color').value;
    const secondaryColor = document.getElementById('pdf_secondary_color').value;
    
    document.getElementById('previewHeader').style.backgroundColor = primaryColor;
    document.getElementById('previewTable').style.backgroundColor = secondaryColor;
    document.getElementById('previewTotal').style.backgroundColor = primaryColor;
}
</script>
