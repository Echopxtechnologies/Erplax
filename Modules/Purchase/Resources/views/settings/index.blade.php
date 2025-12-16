@include('purchase::partials.styles')

<div class="detail-page">
    <div class="page-header">
        <h1>⚙️ Purchase Settings</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.purchase.settings.update') }}" method="POST">
        @csrf
        
        <div class="settings-grid">
            <!-- General Settings -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">🔧 General Settings</h5>
                </div>
                <div class="detail-card-body">
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
                    
                    <div class="form-row" style="grid-template-columns: repeat(2, 1fr);">
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
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">🎨 PDF Style</h5>
                </div>
                <div class="detail-card-body">
                    <div class="form-group">
                        <label class="form-label">Primary Color (Headers, Totals)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="pdf_primary_color" name="pdf_primary_color" value="{{ $settings['pdf_primary_color'] }}" onchange="updatePreview()">
                            <input type="text" class="form-control" value="{{ $settings['pdf_primary_color'] }}" onchange="document.getElementById('pdf_primary_color').value = this.value; updatePreview();">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Secondary Color (Table Headers)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="pdf_secondary_color" name="pdf_secondary_color" value="{{ $settings['pdf_secondary_color'] }}" onchange="updatePreview()">
                            <input type="text" class="form-control" value="{{ $settings['pdf_secondary_color'] }}" onchange="document.getElementById('pdf_secondary_color').value = this.value; updatePreview();">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Font Size</label>
                        <select name="pdf_font_size" class="form-control">
                            <option value="8" {{ $settings['pdf_font_size'] == '8' ? 'selected' : '' }}>8pt (Compact)</option>
                            <option value="9" {{ $settings['pdf_font_size'] == '9' ? 'selected' : '' }}>9pt (Default)</option>
                            <option value="10" {{ $settings['pdf_font_size'] == '10' ? 'selected' : '' }}>10pt (Large)</option>
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
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">📄 PDF Display Options</h5>
                </div>
                <div class="detail-card-body">
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
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">📜 Default Terms & Conditions</h5>
                </div>
                <div class="detail-card-body">
                    <div class="form-group">
                        <label class="form-label">Terms Text</label>
                        <textarea name="po_terms" class="form-control" rows="5">{{ $settings['po_terms'] }}</textarea>
                        <div class="form-text">These terms will be pre-filled when creating new Purchase Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Save Settings</button>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    const primaryColor = document.getElementById('pdf_primary_color').value;
    const secondaryColor = document.getElementById('pdf_secondary_color').value;
    
    document.getElementById('previewHeader').style.backgroundColor = primaryColor;
    document.getElementById('previewTable').style.backgroundColor = secondaryColor;
    document.getElementById('previewTotal').style.backgroundColor = primaryColor;
}
</script>
