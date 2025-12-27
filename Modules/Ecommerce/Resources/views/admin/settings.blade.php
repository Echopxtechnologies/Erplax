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
.ws-header-badge { padding: 4px 12px; background: var(--success-light); color: var(--success); font-size: 12px; font-weight: 600; border-radius: 20px; }
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

/* File Upload */
.ws-file-upload { display: flex; align-items: flex-start; gap: 20px; }
.ws-file-preview { width: 100px; height: 100px; border: 2px dashed var(--border-color); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--bg-secondary); }
.ws-file-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
.ws-file-preview svg { width: 32px; height: 32px; color: var(--text-muted); }
.ws-file-actions { flex: 1; }
.ws-file-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px; font-weight: 500; color: var(--text-secondary); cursor: pointer; transition: all 0.2s; }
.ws-file-btn:hover { border-color: var(--primary); color: var(--primary); }
</style>

<div class="ws-container">
    <div class="ws-header">
        <div class="ws-header-left">
            <h1>Ecommerce Settings</h1>
            <span class="ws-header-badge">Shop</span>
        </div>
        <a href="{{ route('admin.ecommerce.index') }}" class="ws-back-btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="ws-alert ws-alert-success">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
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
        <button type="button" class="ws-tab active" data-tab="store">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Store Info
        </button>
        <button type="button" class="ws-tab" data-tab="shipping">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            Shipping
        </button>
        <button type="button" class="ws-tab" data-tab="payment">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Payment
        </button>
        <button type="button" class="ws-tab" data-tab="orders">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            Orders & Invoice
        </button>
        <button type="button" class="ws-tab" data-tab="notifications">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Notifications
        </button>
    </div>

    <form action="{{ route('admin.ecommerce.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Store Info Tab -->
        <div class="ws-tab-content active" id="tab-store">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    <div><h2 class="ws-card-title">Store Information</h2><p class="ws-card-subtitle">Your business details for invoices</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Store Name<span class="required">*</span></label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="ws-input" required>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Store URL</label>
                            <input type="url" name="site_url" value="{{ old('site_url', $settings->site_url) }}" class="ws-input" placeholder="https://yourstore.com">
                        </div>
                    </div>
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="ws-input" placeholder="orders@yourstore.com">
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}" class="ws-input" placeholder="+91 98765 43210">
                        </div>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Store Address</label>
                        <textarea name="store_address" class="ws-textarea" rows="2" placeholder="Full address for invoices">{{ old('store_address', $settings->store_address) }}</textarea>
                    </div>
                    <div class="ws-row-3">
                        <div class="ws-form-group">
                            <label class="ws-label">City</label>
                            <input type="text" name="store_city" value="{{ old('store_city', $settings->store_city) }}" class="ws-input">
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">State</label>
                            <input type="text" name="store_state" value="{{ old('store_state', $settings->store_state) }}" class="ws-input">
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Pincode</label>
                            <input type="text" name="store_pincode" value="{{ old('store_pincode', $settings->store_pincode) }}" class="ws-input">
                        </div>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">GSTIN</label>
                        <input type="text" name="store_gstin" value="{{ old('store_gstin', $settings->store_gstin) }}" class="ws-input ws-input-md" placeholder="22AAAAA0000A1Z5">
                        <p class="ws-hint">GST Identification Number (displayed on invoices)</p>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></div>
                    <div><h2 class="ws-card-title">Shop URL</h2><p class="ws-card-subtitle">Configure your shop URL prefix</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group">
                        <label class="ws-label">Shop Prefix</label>
                        <div class="ws-input-group" style="max-width: 400px;">
                            <input type="text" name="shop_prefix" value="{{ old('shop_prefix', $settings->shop_prefix ?? 'shop') }}" class="ws-input" placeholder="shop">
                            <span class="ws-input-group-text">→ /shop/</span>
                        </div>
                        <p class="ws-hint">Your shop will be accessible at: {{ url('/') }}/<strong>{{ $settings->shop_prefix ?? 'shop' }}</strong>/</p>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-toggle">
                            <div class="ws-toggle-switch">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                                <span class="ws-toggle-slider"></span>
                            </div>
                            <div class="ws-toggle-content">
                                <span class="ws-toggle-label">Shop Active</span>
                                <span class="ws-toggle-hint">When disabled, visitors see a coming soon page</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Branding</h2><p class="ws-card-subtitle">Logo for shop and invoices</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group">
                        <label class="ws-label">Store Logo</label>
                        <div class="ws-file-upload">
                            <div class="ws-file-preview">
                                @if($settings->site_logo)
                                    <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo">
                                @else
                                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                @endif
                            </div>
                            <div class="ws-file-actions">
                                <label class="ws-file-btn">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload Logo
                                    <input type="file" name="site_logo" class="ws-file-input" accept="image/*">
                                </label>
                                <p class="ws-hint" style="margin-top: 10px;">Recommended: 200x60 pixels, PNG or JPG</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Tab -->
        <div class="ws-tab-content" id="tab-shipping">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg></div>
                    <div><h2 class="ws-card-title">Shipping Settings</h2><p class="ws-card-subtitle">Configure delivery options</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Shipping Fee (Rs.)</label>
                            <input type="number" name="shipping_fee" value="{{ old('shipping_fee', $settings->shipping_fee ?? 50) }}" class="ws-input ws-input-sm" step="0.01" min="0">
                            <p class="ws-hint">Default shipping charge per order</p>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Free Shipping Above (Rs.)</label>
                            <input type="number" name="free_shipping_min" value="{{ old('free_shipping_min', $settings->free_shipping_min ?? 500) }}" class="ws-input ws-input-sm" step="0.01" min="0">
                            <p class="ws-hint">0 = Free shipping disabled</p>
                        </div>
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Estimated Delivery Time</label>
                        <input type="text" name="delivery_days" value="{{ old('delivery_days', $settings->delivery_days ?? '3-5 business days') }}" class="ws-input ws-input-md" placeholder="3-5 business days">
                    </div>
                    <div class="ws-form-group">
                        <label class="ws-label">Minimum Order Amount (Rs.)</label>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $settings->min_order_amount ?? 0) }}" class="ws-input ws-input-sm" step="0.01" min="0">
                        <p class="ws-hint">0 = No minimum order value required</p>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon warning"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg></div>
                    <div><h2 class="ws-card-title">Tax Settings</h2><p class="ws-card-subtitle">GST and tax display options</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="tax_included_in_price" value="1" {{ old('tax_included_in_price', $settings->tax_included_in_price ?? true) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Tax Included in Price</span>
                            <span class="ws-toggle-hint">Prices shown include GST (MRP inclusive)</span>
                        </div>
                    </label>
                    <div style="height: 16px;"></div>
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="show_tax_breakup" value="1" {{ old('show_tax_breakup', $settings->show_tax_breakup ?? true) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Show Tax Breakup</span>
                            <span class="ws-toggle-hint">Display CGST/SGST split on invoices</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Payment Tab -->
        <div class="ws-tab-content" id="tab-payment">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon success"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <div><h2 class="ws-card-title">Cash on Delivery</h2><p class="ws-card-subtitle">COD payment settings</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="cod_enabled" value="1" {{ old('cod_enabled', $settings->cod_enabled ?? true) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Enable COD</span>
                            <span class="ws-toggle-hint">Allow customers to pay on delivery</span>
                        </div>
                    </label>
                    <div style="height: 20px;"></div>
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">COD Fee (Rs.)</label>
                            <input type="number" name="cod_fee" value="{{ old('cod_fee', $settings->cod_fee ?? 0) }}" class="ws-input ws-input-sm" step="0.01" min="0">
                            <p class="ws-hint">Additional charge for COD orders</p>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Max COD Amount (Rs.)</label>
                            <input type="number" name="cod_max_amount" value="{{ old('cod_max_amount', $settings->cod_max_amount ?? 0) }}" class="ws-input ws-input-sm" step="0.01" min="0">
                            <p class="ws-hint">0 = No limit</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <div><h2 class="ws-card-title">Online Payment</h2><p class="ws-card-subtitle">UPI, Card, NetBanking</p></div>
                </div>
                <div class="ws-card-body">
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="online_payment_enabled" value="1" {{ old('online_payment_enabled', $settings->online_payment_enabled ?? false) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Enable Online Payment</span>
                            <span class="ws-toggle-hint">Accept UPI, Cards, NetBanking via payment gateway</span>
                        </div>
                    </label>
                    <div style="height: 20px;"></div>
                    <div class="ws-form-group">
                        <label class="ws-label">Payment Button Label</label>
                        <input type="text" name="online_payment_label" value="{{ old('online_payment_label', $settings->online_payment_label ?? 'Pay Online (UPI/Card/NetBanking)') }}" class="ws-input ws-input-md">
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders & Invoice Tab -->
        <div class="ws-tab-content" id="tab-orders">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div><h2 class="ws-card-title">Order Settings</h2><p class="ws-card-subtitle">Order numbering and checkout</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-row">
                        <div class="ws-form-group">
                            <label class="ws-label">Order Number Prefix</label>
                            <input type="text" name="order_prefix" value="{{ old('order_prefix', $settings->order_prefix ?? 'ORD-') }}" class="ws-input ws-input-sm">
                            <p class="ws-hint">Example: ORD-00001</p>
                        </div>
                        <div class="ws-form-group">
                            <label class="ws-label">Invoice Number Prefix</label>
                            <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $settings->invoice_prefix ?? 'INV-') }}" class="ws-input ws-input-sm">
                            <p class="ws-hint">Example: INV-00001</p>
                        </div>
                    </div>
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="guest_checkout" value="1" {{ old('guest_checkout', $settings->guest_checkout ?? false) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Allow Guest Checkout</span>
                            <span class="ws-toggle-hint">Let customers checkout without creating an account</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon warning"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Invoice Settings</h2><p class="ws-card-subtitle">Invoice customization</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group">
                        <label class="ws-label">Invoice Footer Text</label>
                        <textarea name="invoice_footer" class="ws-textarea" rows="2" placeholder="Thank you for shopping with us!">{{ old('invoice_footer', $settings->invoice_footer) }}</textarea>
                        <p class="ws-hint">Displayed at the bottom of invoices</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div class="ws-tab-content" id="tab-notifications">
            <div class="ws-card">
                <div class="ws-card-header">
                    <div class="ws-card-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <div><h2 class="ws-card-title">Email Notifications</h2><p class="ws-card-subtitle">Order email settings</p></div>
                </div>
                <div class="ws-card-body">
                    <div class="ws-form-group">
                        <label class="ws-label">Admin Notification Email</label>
                        <input type="email" name="order_notification_email" value="{{ old('order_notification_email', $settings->order_notification_email) }}" class="ws-input ws-input-md" placeholder="admin@yourstore.com">
                        <p class="ws-hint">Where to send new order alerts</p>
                    </div>
                    <div style="height: 10px;"></div>
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="send_customer_order_email" value="1" {{ old('send_customer_order_email', $settings->send_customer_order_email ?? true) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Send Order Confirmation to Customer</span>
                            <span class="ws-toggle-hint">Email customers when they place an order</span>
                        </div>
                    </label>
                    <div style="height: 16px;"></div>
                    <label class="ws-toggle">
                        <div class="ws-toggle-switch">
                            <input type="checkbox" name="send_admin_order_alert" value="1" {{ old('send_admin_order_alert', $settings->send_admin_order_alert ?? true) ? 'checked' : '' }}>
                            <span class="ws-toggle-slider"></span>
                        </div>
                        <div class="ws-toggle-content">
                            <span class="ws-toggle-label">Send New Order Alert to Admin</span>
                            <span class="ws-toggle-hint">Notify admin when new orders are placed</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="ws-submit-card">
            <span class="ws-submit-hint">Remember to save your changes</span>
            <button type="submit" class="ws-submit-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.ws-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ws-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.ws-tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});
</script>
