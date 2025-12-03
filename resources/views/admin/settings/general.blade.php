<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">General Settings</h1>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General Settings</h3>
            </div>
            <div class="card-body">
                <form>
                    <div style="display: grid; gap: 16px;">
                        {{-- App Name --}}
                        <div>
                            <label class="form-label">Application Name</label>
                            <input type="text" name="app_name" class="form-control" 
                                   value="{{ config('app.name') }}">
                        </div>
                        
                        {{-- App URL --}}
                        <div>
                            <label class="form-label">Application URL</label>
                            <input type="url" name="app_url" class="form-control" 
                                   value="{{ config('app.url') }}" placeholder="https://example.com">
                        </div>
                        
                        {{-- Timezone --}}
                        <div>
                            <label class="form-label">Timezone</label>
                            <select name="timezone" class="form-control">
                                <option value="UTC" {{ config('app.timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Asia/Kolkata" {{ config('app.timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                <option value="America/New_York" {{ config('app.timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                <option value="Europe/London" {{ config('app.timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                            </select>
                        </div>
                        
                        {{-- Date Format --}}
                        <div>
                            <label class="form-label">Date Format</label>
                            <select name="date_format" class="form-control">
                                <option value="d/m/Y">DD/MM/YYYY (31/12/2024)</option>
                                <option value="m/d/Y">MM/DD/YYYY (12/31/2024)</option>
                                <option value="Y-m-d">YYYY-MM-DD (2024-12-31)</option>
                            </select>
                        </div>
                        
                        {{-- Currency --}}
                        <div>
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-control">
                                <option value="INR">₹ INR (Indian Rupee)</option>
                                <option value="USD">$ USD (US Dollar)</option>
                                <option value="EUR">€ EUR (Euro)</option>
                                <option value="GBP">£ GBP (British Pound)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border);">
                        <button type="button" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>