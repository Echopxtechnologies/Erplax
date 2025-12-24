<style>
:root {
    --primary: #4f46e5;
    --primary-light: #eef2ff;
    --success: #059669;
    --success-light: #ecfdf5;
    --danger: #dc2626;
    --danger-light: #fef2f2;
    --warning: #d97706;
    --warning-light: #fffbeb;
    --text-dark: #111827;
    --text-secondary: #4b5563;
    --text-muted: #9ca3af;
    --bg-primary: #ffffff;
    --bg-secondary: #f9fafb;
    --bg-tertiary: #f3f4f6;
    --border-color: #e5e7eb;
    --border-light: #f3f4f6;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
}

.ws-container { max-width: 1100px; margin: 0 auto; padding: 28px; }

/* Header */
.ws-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
.ws-header-left { display: flex; align-items: center; gap: 16px; }
.ws-header h1 { font-size: 26px; font-weight: 700; color: var(--text-dark); margin: 0; }
.ws-header-badge { padding: 4px 12px; background: var(--primary-light); color: var(--primary); font-size: 12px; font-weight: 600; border-radius: 20px; }
.ws-back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-secondary); font-size: 14px; font-weight: 500; border-radius: var(--radius-md); text-decoration: none; transition: all 0.2s; }
.ws-back-btn:hover { border-color: var(--primary); color: var(--primary); }
.ws-back-btn svg { width: 18px; height: 18px; }

/* Tabs */
.ws-tabs { display: flex; gap: 6px; background: var(--bg-tertiary); padding: 6px; border-radius: var(--radius-lg); margin-bottom: 28px; overflow-x: auto; }
.ws-tab { padding: 12px 20px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; color: var(--text-muted); background: transparent; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; display: flex; align-items: center; gap: 8px; }
.ws-tab:hover { color: var(--text-secondary); }
.ws-tab.active { background: var(--bg-primary); color: var(--primary); box-shadow: var(--shadow-sm); }
.ws-tab svg { width: 18px; height: 18px; }

.ws-tab-content { display: none; animation: fadeIn 0.3s ease; }
.ws-tab-content.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Cards */
.ws-card { background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 24px; }
.ws-card-header { padding: 18px 24px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 12px; }
.ws-card-icon { width: 40px; height: 40px; background: var(--primary-light); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.ws-card-icon svg { width: 20px; height: 20px; color: var(--primary); }
.ws-card-icon.success { background: var(--success-light); }
.ws-card-icon.success svg { color: var(--success); }
.ws-card-icon.warning { background: var(--warning-light); }
.ws-card-icon.warning svg { color: var(--warning); }
.ws-card-title { font-size: 16px; font-weight: 600; color: var(--text-dark); margin: 0; }
.ws-card-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
.ws-card-body { padding: 24px; }

/* Form Elements */
.ws-form-group { margin-bottom: 22px; }
.ws-form-group:last-child { margin-bottom: 0; }
.ws-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
.ws-label .required { color: var(--danger); margin-left: 2px; }
.ws-hint { font-size: 13px; color: var(--text-muted); margin-top: 6px; line-height: 1.5; }
.ws-input, .ws-select, .ws-textarea { width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px; background: var(--bg-primary); color: var(--text-dark); transition: all 0.2s; }
.ws-input:focus, .ws-select:focus, .ws-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
.ws-input::placeholder { color: var(--text-muted); }
.ws-textarea { min-height: 100px; resize: vertical; }
.ws-input-sm { max-width: 200px; }
.ws-input-md { max-width: 320px; }

/* Grid */
.ws-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
.ws-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 768px) { .ws-row, .ws-row-3 { grid-template-columns: 1fr; } }

/* Input Group */
.ws-input-group { display: flex; }
.ws-input-group .ws-input { border-radius: var(--radius-md) 0 0 var(--radius-md); }
.ws-input-group-text { padding: 11px 16px; background: var(--bg-tertiary); border: 1px solid var(--border-color); border-left: none; border-radius: 0 var(--radius-md) var(--radius-md) 0; font-size: 14px; font-weight: 500; color: var(--text-secondary); }

/* Toggle Switch */
.ws-toggle { display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: var(--bg-secondary); border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; }
.ws-toggle:hover { background: var(--bg-tertiary); }
.ws-toggle-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.ws-toggle-switch input { opacity: 0; width: 0; height: 0; }
.ws-toggle-slider { position: absolute; cursor: pointer; inset: 0; background-color: var(--border-color); transition: 0.3s; border-radius: 24px; }
.ws-toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: 0.3s; border-radius: 50%; box-shadow: var(--shadow-sm); }
.ws-toggle-switch input:checked + .ws-toggle-slider { background-color: var(--success); }
.ws-toggle-switch input:checked + .ws-toggle-slider:before { transform: translateX(20px); }
.ws-toggle-content { flex: 1; }
.ws-toggle-label { font-size: 14px; font-weight: 600; color: var(--text-dark); display: block; }
.ws-toggle-hint { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

/* Checkbox Grid */
.ws-checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
.ws-checkbox-item { display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: var(--bg-secondary); border: 2px solid var(--border-light); border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s; }
.ws-checkbox-item:hover { border-color: var(--primary); background: var(--primary-light); }
.ws-checkbox-item.checked { border-color: var(--primary); background: var(--primary-light); }
.ws-checkbox-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
.ws-checkbox-item span { font-size: 14px; font-weight: 500; color: var(--text-dark); }
.ws-checkbox-item .ws-method-icon { width: 28px; height: 28px; background: var(--bg-primary); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; }

/* File Upload */
.ws-file-upload { display: flex; align-items: flex-start; gap: 20px; }
.ws-file-preview { width: 100px; height: 100px; border: 2px dashed var(--border-color); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--bg-secondary); }
.ws-file-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
.ws-file-preview svg { width: 32px; height: 32px; color: var(--text-muted); }
.ws-file-actions { flex: 1; }
.ws-file-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px; font-weight: 500; color: var(--text-secondary); cursor: pointer; transition: all 0.2s; }
.ws-file-btn:hover { border-color: var(--primary); color: var(--primary); }
.ws-file-btn svg { width: 18px; height: 18px; }
.ws-file-input { position: absolute; opacity: 0; width: 0; height: 0; }
.ws-file-remove { margin-top: 10px; padding: 6px 12px; background: var(--danger-light); border: 1px solid #fecaca; border-radius: var(--radius-sm); color: var(--danger); font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }

/* Info Box */
.ws-info { padding: 14px 18px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-md); font-size: 13px; color: #1e40af; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; line-height: 1.6; }
.ws-info svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px; }

/* Alerts */
.ws-alert { padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500; }
.ws-alert svg { width: 20px; height: 20px; flex-shrink: 0; }
.ws-alert-success { background: var(--success-light); border: 1px solid #a7f3d0; color: #065f46; }
.ws-alert-error { background: var(--danger-light); border: 1px solid #fecaca; color: #991b1b; }

/* Submit Button */
.ws-submit-card { background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; }
.ws-submit-hint { font-size: 14px; color: var(--text-muted); }
.ws-submit-btn { display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%); color: white; border: none; border-radius: var(--radius-md); font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4); }
.ws-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5); }
.ws-submit-btn svg { width: 20px; height: 20px; }

/* Section Divider */
.ws-divider { height: 1px; background: var(--border-light); margin: 24px 0; }
</style>

<div class="ws-container">
    <div class="ws-header">
        <div class="ws-header-left">
            <h1>Website Settings</h1>
            <span class="ws-header-badge">v2.0</span>
        </div>
        <a href="{{ route('admin.website.index') }}" class="ws-back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="ws-alert ws-alert-success">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="ws-alert ws-alert-error">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="ws-tabs">
        <button type="button" class="ws-tab active" data-tab="general">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            General
        </button>
        <button type="button" class="ws-tab" data-tab="branding">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Branding
        </button>
        <button type="button" class="ws-tab" data-tab="ecommerce">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            E-Commerce
        </button>
        <button type="button" class="ws-tab" data-tab="payment">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Payment
        </button>
        <button type="button" class="ws-tab" data-tab="store">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Store Info
        </button>
    </div>

    <form action="{{ route('admin.website.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- TAB: General --}}
        <div class="ws-tab-content active" id="tab-general">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div><h2 class="ws-card-title">Website Status</h2><p class="ws-card-subtitle">Control website visibility and mode</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Website Active</span><span class="ws-toggle-hint">When disabled, visitors see maintenance page</span></div>
                    </label>
                    <div class="ws-divider"></div>
                    <div class="ws-form-group">
                        <label class="ws-label">Website Mode</label>
                        <select name="site_mode" class="ws-select ws-input-md" id="siteMode">
                            <option value="website_only" {{ old('site_mode', $settings->site_mode) == 'website_only' ? 'selected' : '' }}>Website Only</option>
                            <option value="ecommerce_only" {{ old('site_mode', $settings->site_mode) == 'ecommerce_only' ? 'selected' : '' }}>E-Commerce Only</option>
                            <option value="both" {{ old('site_mode', $settings->site_mode) == 'both' ? 'selected' : '' }}>Website + E-Commerce</option>
                        </select>
                        <p class="ws-hint" id="modeDescription">Full website with pages + ecommerce.</p>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg></div>
                    <div><h2 class="ws-card-title">SEO & Meta</h2><p class="ws-card-subtitle">Search engine optimization settings</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group">
                        <label class="ws-label">Site Name <span class="required">*</span></label>
                        <input type="text" name="site_name" class="ws-input ws-input-md" value="{{ old('site_name', $settings->site_name) }}" required>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Meta Description</label>
                        <textarea name="meta_description" class="ws-textarea" rows="3" placeholder="Brief description for search engines...">{{ old('meta_description', $settings->meta_description) }}</textarea>
                        <p class="ws-hint">Recommended: 150-160 characters</p>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="ws-input" value="{{ old('meta_keywords', $settings->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon warning"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></div>
                    <div><h2 class="ws-card-title">URL Configuration</h2><p class="ws-card-subtitle">Customize website and shop URLs</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Website Prefix</label>
                            <div class="ws-input-group">
                                <input type="text" name="site_prefix" class="ws-input" value="{{ old('site_prefix', $settings->site_prefix ?? 'site') }}" placeholder="site">
                                <span class="ws-input-group-text">/page/about</span>
                            </div>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Shop Prefix</label>
                            <div class="ws-input-group">
                                <input type="text" name="shop_prefix" class="ws-input" value="{{ old('shop_prefix', $settings->shop_prefix ?? 'shop') }}" placeholder="shop">
                                <span class="ws-input-group-text">/product/1</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: Branding --}}
        <div class="ws-tab-content" id="tab-branding">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Logo & Favicon</h2><p class="ws-card-subtitle">Upload your brand assets</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Site Logo</label>
                            <div class="ws-file-upload">
                                <div class="ws-file-preview" id="logoPreview">
                                    @if($settings->site_logo)<img src="{{ Storage::url($settings->site_logo) }}" alt="Logo">@else<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>@endif
                                </div>
                                <div class="ws-file-actions">
                                    <label class="ws-file-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>Upload Logo<input type="file" name="site_logo" class="ws-file-input" accept="image/*" onchange="previewImage(this, 'logoPreview')"></label>
                                    <p class="ws-hint" style="margin-top:10px;">200x60px, PNG or SVG</p>
                                    @if($settings->site_logo)<button type="button" class="ws-file-remove" onclick="document.getElementById('removeLogo').submit()">Remove</button>@endif
                                </div>
                            </div>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Favicon</label>
                            <div class="ws-file-upload">
                                <div class="ws-file-preview" id="faviconPreview" style="width:60px;height:60px;">
                                    @if($settings->site_favicon)<img src="{{ Storage::url($settings->site_favicon) }}" alt="Favicon">@else<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>@endif
                                </div>
                                <div class="ws-file-actions">
                                    <label class="ws-file-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>Upload<input type="file" name="site_favicon" class="ws-file-input" accept="image/*" onchange="previewImage(this, 'faviconPreview')"></label>
                                    <p class="ws-hint" style="margin-top:10px;">32x32px ICO/PNG</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg></div>
                    <div><h2 class="ws-card-title">Brand Colors</h2><p class="ws-card-subtitle">Customize your website colors</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row-3">
                        <div class="ws-form-group">
                            <label class="ws-label">Primary Color</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#3b82f6') }}" style="width:50px;height:40px;border:1px solid var(--border-color);border-radius:var(--radius-sm);cursor:pointer;">
                                <input type="text" class="ws-input ws-input-sm" value="{{ old('primary_color', $settings->primary_color ?? '#3b82f6') }}" readonly style="max-width:100px;">
                            </div>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Secondary Color</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color ?? '#1f2937') }}" style="width:50px;height:40px;border:1px solid var(--border-color);border-radius:var(--radius-sm);cursor:pointer;">
                                <input type="text" class="ws-input ws-input-sm" value="{{ old('secondary_color', $settings->secondary_color ?? '#1f2937') }}" readonly style="max-width:100px;">
                            </div>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Accent Color</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" name="accent_color" value="{{ old('accent_color', $settings->accent_color ?? '#10b981') }}" style="width:50px;height:40px;border:1px solid var(--border-color);border-radius:var(--radius-sm);cursor:pointer;">
                                <input type="text" class="ws-input ws-input-sm" value="{{ old('accent_color', $settings->accent_color ?? '#10b981') }}" readonly style="max-width:100px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon warning"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                    <div><h2 class="ws-card-title">Contact Information</h2><p class="ws-card-subtitle">How customers can reach you</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row-3">
                        <div class="ws-form-group"><label class="ws-label">Email</label><input type="email" name="contact_email" class="ws-input" value="{{ old('contact_email', $settings->contact_email) }}" placeholder="contact@example.com"></div>
                        <div class="ws-form-group"><label class="ws-label">Phone</label><input type="text" name="contact_phone" class="ws-input" value="{{ old('contact_phone', $settings->contact_phone) }}" placeholder="+91 98765 43210"></div>
                        <div class="ws-form-group"><label class="ws-label">WhatsApp</label><input type="text" name="whatsapp_number" class="ws-input" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="919876543210"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: E-Commerce --}}
        <div class="ws-tab-content" id="tab-ecommerce">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><h2 class="ws-card-title">Currency & Display</h2><p class="ws-card-subtitle">Price display settings</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group"><label class="ws-label">Currency Symbol</label><input type="text" name="currency_symbol" class="ws-input ws-input-sm" value="{{ old('currency_symbol', $settings->currency_symbol ?? '₹') }}" style="max-width:80px;"></div>
                        <div class="ws-form-group"><label class="ws-label">Currency Code</label><input type="text" name="currency_code" class="ws-input ws-input-sm" value="{{ old('currency_code', $settings->currency_code ?? 'INR') }}" style="max-width:100px;"></div>
                    </div>
                    <div class="ws-row">
                        <div class="ws-form-group"><label class="ws-label">Products Per Page</label><input type="number" name="products_per_page" class="ws-input ws-input-sm" value="{{ old('products_per_page', $settings->products_per_page ?? 12) }}" min="4" max="48" style="max-width:100px;"></div>
                        <div class="ws-form-group"><label class="ws-label">Low Stock Threshold</label><input type="number" name="low_stock_threshold" class="ws-input ws-input-sm" value="{{ old('low_stock_threshold', $settings->low_stock_threshold ?? 5) }}" min="1" style="max-width:100px;"><p class="ws-hint">Show "Only X left" warning</p></div>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                    <div><h2 class="ws-card-title">Checkout Options</h2><p class="ws-card-subtitle">Customize checkout experience</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="guest_checkout" value="1" {{ old('guest_checkout', $settings->guest_checkout ?? false) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Guest Checkout</span><span class="ws-toggle-hint">Allow orders without account registration</span></div>
                    </label>
                    <div class="ws-divider"></div>
                    <div class="ws-row">
                        <div class="ws-form-group"><label class="ws-label">Minimum Order Amount</label><div class="ws-input-group"><input type="number" name="min_order_amount" class="ws-input" value="{{ old('min_order_amount', $settings->min_order_amount ?? 0) }}" min="0" step="0.01"><span class="ws-input-group-text">₹</span></div><p class="ws-hint">0 = no minimum</p></div>
                        <div class="ws-form-group"><label class="ws-label">Admin Notification Email</label><input type="email" name="order_notification_email" class="ws-input" value="{{ old('order_notification_email', $settings->order_notification_email) }}" placeholder="orders@example.com"><p class="ws-hint">Email to receive new order alerts</p></div>
                    </div>
                </div>
            </div>

            {{-- Email Notifications Card --}}
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon" style="background: #fef3c7;"><svg fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Email Notifications</h2><p class="ws-card-subtitle">Configure order email alerts</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="send_customer_order_email" value="1" {{ old('send_customer_order_email', $settings->send_customer_order_email ?? true) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Send Order Confirmation to Customer</span><span class="ws-toggle-hint">Email customer when order is placed with order details</span></div>
                    </label>
                    <div class="ws-divider"></div>
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="send_admin_order_alert" value="1" {{ old('send_admin_order_alert', $settings->send_admin_order_alert ?? true) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Send New Order Alert to Admin</span><span class="ws-toggle-hint">Notify admin via email when new order is received</span></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- TAB: Payment --}}
        <div class="ws-tab-content" id="tab-payment">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon warning"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <div><h2 class="ws-card-title">Cash on Delivery (COD)</h2><p class="ws-card-subtitle">Allow customers to pay when order is delivered</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="cod_enabled" value="1" {{ old('cod_enabled', $settings->cod_enabled ?? true) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Enable Cash on Delivery</span><span class="ws-toggle-hint">Show COD option at checkout</span></div>
                    </label>
                    <div class="ws-divider"></div>
                    <div class="ws-row">
                        <div class="ws-form-group"><label class="ws-label">COD Extra Fee</label><div class="ws-input-group"><input type="number" name="cod_fee" class="ws-input" value="{{ old('cod_fee', $settings->cod_fee ?? 0) }}" min="0" step="0.01"><span class="ws-input-group-text">₹</span></div><p class="ws-hint">Additional charge for COD (0 = free)</p></div>
                        <div class="ws-form-group"><label class="ws-label">COD Maximum Limit</label><div class="ws-input-group"><input type="number" name="cod_max_amount" class="ws-input" value="{{ old('cod_max_amount', $settings->cod_max_amount ?? 0) }}" min="0" step="0.01"><span class="ws-input-group-text">₹</span></div><p class="ws-hint">COD unavailable above this amount (0 = no limit)</p></div>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <div><h2 class="ws-card-title">Online Payment</h2><p class="ws-card-subtitle">Accept payments via payment gateway</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <label class="ws-toggle-switch"><input type="checkbox" name="online_payment_enabled" value="1" {{ old('online_payment_enabled', $settings->online_payment_enabled ?? false) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                        <div class="ws-toggle-content"><span class="ws-toggle-label">Enable Online Payment</span><span class="ws-toggle-hint">Show online payment option at checkout</span></div>
                    </label>
                    <div class="ws-divider"></div>
                    <div class="ws-info">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Payment gateway integration (Razorpay/Stripe) coming soon. When enabled, customers can pay online using UPI, Cards, Net Banking, etc.</span>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Online Payment Label</label>
                        <input type="text" name="online_payment_label" class="ws-input ws-input-md" value="{{ old('online_payment_label', $settings->online_payment_label ?? 'Pay Online (UPI/Card/NetBanking)') }}" placeholder="Pay Online">
                        <p class="ws-hint">Text shown for online payment option at checkout</p>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Tax Settings</h2><p class="ws-card-subtitle">Configure tax display options</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-info">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Product taxes are configured in individual product settings (tax_1, tax_2).</span>
                    </div>
                    <div class="ws-row">
                        <label class="ws-toggle">
                            <label class="ws-toggle-switch"><input type="checkbox" name="tax_included_in_price" value="1" {{ old('tax_included_in_price', $settings->tax_included_in_price ?? true) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                            <div class="ws-toggle-content"><span class="ws-toggle-label">Prices Include Tax</span><span class="ws-toggle-hint">Product prices are MRP (tax included)</span></div>
                        </label>
                        <label class="ws-toggle">
                            <label class="ws-toggle-switch"><input type="checkbox" name="show_tax_breakup" value="1" {{ old('show_tax_breakup', $settings->show_tax_breakup ?? true) ? 'checked' : '' }}><span class="ws-toggle-slider"></span></label>
                            <div class="ws-toggle-content"><span class="ws-toggle-label">Show Tax Breakup</span><span class="ws-toggle-hint">Display tax details in order summary</span></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB: Store Info --}}
        <div class="ws-tab-content" id="tab-store">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    <div><h2 class="ws-card-title">Store Information</h2><p class="ws-card-subtitle">This appears on invoices and order confirmations</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group"><label class="ws-label">Store Address</label><textarea name="store_address" class="ws-textarea" rows="2" placeholder="123 Main Street, Building Name">{{ old('store_address', $settings->store_address) }}</textarea></div>
                    <div class="ws-row-3">
                        <div class="ws-form-group"><label class="ws-label">City</label><input type="text" name="store_city" class="ws-input" value="{{ old('store_city', $settings->store_city) }}" placeholder="Bengaluru"></div>
                        <div class="ws-form-group"><label class="ws-label">State</label><input type="text" name="store_state" class="ws-input" value="{{ old('store_state', $settings->store_state) }}" placeholder="Karnataka"></div>
                        <div class="ws-form-group"><label class="ws-label">Pincode</label><input type="text" name="store_pincode" class="ws-input" value="{{ old('store_pincode', $settings->store_pincode) }}" placeholder="560001"></div>
                    </div>
                    <div class="ws-form-group"><label class="ws-label">GSTIN</label><input type="text" name="store_gstin" class="ws-input ws-input-md" value="{{ old('store_gstin', $settings->store_gstin) }}" placeholder="29ABCDE1234F1Z5"><p class="ws-hint">GST Identification Number (displayed on invoices)</p></div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Invoice Settings</h2><p class="ws-card-subtitle">Customize invoice appearance</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group"><label class="ws-label">Invoice Footer Text</label><textarea name="invoice_footer" class="ws-textarea" rows="2" placeholder="Thank you for shopping with us!">{{ old('invoice_footer', $settings->invoice_footer) }}</textarea><p class="ws-hint">This text appears at the bottom of all invoices</p></div>
                </div>
            </div>
        </div>

        <div class="ws-submit-card">
            <span class="ws-submit-hint">💡 Changes will be applied immediately after saving</span>
            <button type="submit" class="ws-submit-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Save All Settings</button>
        </div>
    </form>
</div>

@if($settings->site_logo)<form id="removeLogo" action="{{ route('admin.website.remove-logo') }}" method="POST" style="display:none;">@csrf</form>@endif
@if($settings->site_favicon)<form id="removeFavicon" action="{{ route('admin.website.remove-favicon') }}" method="POST" style="display:none;">@csrf</form>@endif

<script>
document.querySelectorAll('.ws-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.ws-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.ws-tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
});
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">'; };
        reader.readAsDataURL(input.files[0]);
    }
}
document.getElementById('siteMode')?.addEventListener('change', function() {
    var d = {'website_only':'Pages and content only. Shop is hidden.','ecommerce_only':'Shop only. Homepage redirects to /shop.','both':'Full website with pages + ecommerce.'};
    document.getElementById('modeDescription').textContent = d[this.value] || '';
});
document.querySelectorAll('input[type="color"]').forEach(p => { p.addEventListener('input', function() { this.nextElementSibling.value = this.value; }); });
</script>
