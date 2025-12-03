<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Email Settings</h1>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Email Configuration</h3>
            </div>
            <div class="card-body">
                <form>
                    <div style="display: grid; gap: 16px;">
                        {{-- Mail Driver --}}
                        <div>
                            <label class="form-label">Mail Driver</label>
                            <select name="mail_mailer" class="form-control">
                                <option value="smtp">SMTP</option>
                                <option value="sendmail">Sendmail</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="log">Log (for testing)</option>
                            </select>
                        </div>
                        
                        {{-- SMTP Host --}}
                        <div>
                            <label class="form-label">SMTP Host</label>
                            <input type="text" name="mail_host" class="form-control" 
                                   value="{{ config('mail.mailers.smtp.host') }}" 
                                   placeholder="smtp.gmail.com">
                        </div>
                        
                        {{-- SMTP Port --}}
                        <div>
                            <label class="form-label">SMTP Port</label>
                            <input type="number" name="mail_port" class="form-control" 
                                   value="{{ config('mail.mailers.smtp.port') }}" 
                                   placeholder="587">
                        </div>
                        
                        {{-- Username --}}
                        <div>
                            <label class="form-label">SMTP Username</label>
                            <input type="text" name="mail_username" class="form-control" 
                                   placeholder="your-email@gmail.com">
                        </div>
                        
                        {{-- Password --}}
                        <div>
                            <label class="form-label">SMTP Password</label>
                            <input type="password" name="mail_password" class="form-control" 
                                   placeholder="Enter SMTP password">
                            <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">
                                For Gmail, use App Password (not your regular password)
                            </p>
                        </div>
                        
                        {{-- Encryption --}}
                        <div>
                            <label class="form-label">Encryption</label>
                            <select name="mail_encryption" class="form-control">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        
                        <div style="border-top: 1px solid var(--card-border); padding-top: 16px; margin-top: 8px;">
                            <p style="font-size: 12px; font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">From Address</p>
                            
                            {{-- From Address --}}
                            <div style="margin-bottom: 16px;">
                                <label class="form-label">From Email Address</label>
                                <input type="email" name="mail_from_address" class="form-control" 
                                       value="{{ config('mail.from.address') }}" 
                                       placeholder="noreply@example.com">
                            </div>
                            
                            {{-- From Name --}}
                            <div>
                                <label class="form-label">From Name</label>
                                <input type="text" name="mail_from_name" class="form-control" 
                                       value="{{ config('mail.from.name') }}" 
                                       placeholder="Your Company Name">
                            </div>
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
        
        {{-- Test Email --}}
        <div class="card" style="margin-top: 16px;">
            <div class="card-header">
                <h3 class="card-title">Test Email</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 12px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label class="form-label">Send Test Email To</label>
                        <input type="email" name="test_email" class="form-control" 
                               placeholder="test@example.com">
                    </div>
                    <button type="button" class="btn btn-light">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Test
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>