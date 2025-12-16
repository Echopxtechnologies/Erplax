<x-layouts.app>
    <style>
        .settings-page { max-width: 900px; margin: 0 auto; }
        .settings-header { margin-bottom: 24px; }
        .settings-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
        .settings-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
        
        .settings-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 24px; overflow: hidden; }
        .settings-card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); display: flex; align-items: center; gap: 10px; }
        .settings-card-header h2 { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; }
        .settings-card-header svg { width: 20px; height: 20px; color: var(--primary); }
        .settings-card-body { padding: 20px; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
        .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
        
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 10px 14px; font-size: 14px;
            background: var(--input-bg); border: 1px solid var(--input-border);
            border-radius: 8px; color: var(--input-text); transition: all 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light);
        }
        .form-textarea { min-height: 100px; resize: vertical; }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .form-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        @media (max-width: 768px) { .form-row-3 { grid-template-columns: 1fr; } }
        
        .file-upload { display: flex; align-items: center; gap: 16px; }
        .file-preview { width: 80px; height: 80px; border: 2px dashed var(--card-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--body-bg); }
        .file-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .file-preview svg { width: 32px; height: 32px; color: var(--text-muted); }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        
        .settings-footer { display: flex; justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--card-border); margin-top: 20px; }
    </style>

    <div class="settings-page">
        <div class="settings-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                General Settings
            </h1>
        </div>

        <form action="{{ route('admin.settings.general.save') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Company Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2>Company Information</h2>
                </div>
                <div class="settings-card-body">
                    <div class="form-row" style="margin-bottom: 24px;">
                        <div class="form-group">
                            <label class="form-label">Company Logo</label>
                            <div class="file-upload">
                                <div class="file-preview">
                                    @if($company_logo)
                                        <img src="{{ $company_logo }}" alt="Logo">
                                    @else
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <input type="file" name="company_logo" accept="image/*">
                            </div>
                            @error('company_logo') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Favicon</label>
                            <div class="file-upload">
                                <div class="file-preview" style="width:50px;height:50px;">
                                    @if($company_favicon)
                                        <img src="{{ $company_favicon }}" alt="Favicon">
                                    @else
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:20px;height:20px;">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <input type="file" name="company_favicon" accept="image/*">
                            </div>
                            @error('company_favicon') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company Name *</label>
                            <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $company_name) }}" required>
                            @error('company_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="company_email" class="form-input" value="{{ old('company_email', $company_email) }}">
                            @error('company_email') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="company_phone" class="form-input" value="{{ old('company_phone', $company_phone) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Website</label>
                            <input type="url" name="company_website" class="form-input" value="{{ old('company_website', $company_website) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea name="company_address" class="form-textarea" rows="2">{{ old('company_address', $company_address) }}</textarea>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="company_city" class="form-input" value="{{ old('company_city', $company_city ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" name="company_state" class="form-input" value="{{ old('company_state', $company_state ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="company_zip" class="form-input" value="{{ old('company_zip', $company_zip ?? '') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country Code</label>
                        <input type="text" name="company_country_code" class="form-input" value="{{ old('company_country_code', $company_country_code ?? '') }}" placeholder="e.g., IN, US, UK" style="max-width: 200px;">
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="company_gst" class="form-input" value="{{ old('company_gst', $company_gst) }}" placeholder="e.g., 29ABCDE1234F1Z5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">PAN Number</label>
                            <input type="text" name="company_pan" class="form-input" value="{{ old('company_pan', $company_pan ?? '') }}" placeholder="e.g., ABCDE1234F">
                        </div>
                        <div class="form-group">
                            <label class="form-label">CIN Number</label>
                            <input type="text" name="company_cin" class="form-input" value="{{ old('company_cin', $company_cin ?? '') }}" placeholder="Corporate Identity Number">
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <h2>System Settings</h2>
                </div>
                <div class="settings-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Timezone</label>
                            <select name="site_timezone" class="form-select">
                                <option value="Asia/Kolkata" {{ $site_timezone == 'Asia/Kolkata' ? 'selected' : '' }}>India (Asia/Kolkata)</option>
                                <option value="UTC" {{ $site_timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ $site_timezone == 'America/New_York' ? 'selected' : '' }}>US Eastern</option>
                                <option value="Europe/London" {{ $site_timezone == 'Europe/London' ? 'selected' : '' }}>UK (London)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pagination Limit</label>
                            <input type="number" name="pagination_limit" class="form-input" value="{{ old('pagination_limit', $pagination_limit) }}" min="5" max="100">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date Format</label>
                            <select name="date_format" class="form-select">
                                <option value="d/m/Y" {{ $date_format == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ $date_format == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ $date_format == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Time Format</label>
                            <select name="time_format" class="form-select">
                                <option value="h:i A" {{ $time_format == 'h:i A' ? 'selected' : '' }}>12-hour (02:30 PM)</option>
                                <option value="H:i" {{ $time_format == 'H:i' ? 'selected' : '' }}>24-hour (14:30)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-input" value="{{ old('currency_symbol', $currency_symbol) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency Code</label>
                            <input type="text" name="currency_code" class="form-input" value="{{ old('currency_code', $currency_code) }}">
                        </div>
                    </div>

                    <div class="settings-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>