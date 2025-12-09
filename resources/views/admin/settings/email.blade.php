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
        .form-textarea { min-height: 150px; resize: vertical; font-family: monospace; }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #16a34a; }
        .btn-secondary { background: var(--card-border); color: var(--text-primary); }
        .btn-secondary:hover { background: var(--text-muted); color: #fff; }
        
        .settings-footer { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--card-border); margin-top: 20px; }
        
        .test-email-section { background: var(--primary-light); border: 1px solid rgba(59,130,246,0.2); border-radius: 10px; padding: 16px; margin-top: 20px; }
        .test-email-section h3 { font-size: 14px; font-weight: 600; color: var(--primary); margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px; }
        .test-email-row { display: flex; gap: 12px; }
        .test-email-row .form-input { flex: 1; }

        .variables-box { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 12px; margin-top: 10px; }
        .variables-box h4 { font-size: 12px; font-weight: 600; color: var(--text-secondary); margin: 0 0 8px 0; text-transform: uppercase; }
        .variables-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .variable-tag { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 4px; padding: 4px 8px; font-size: 12px; font-family: monospace; color: var(--primary); cursor: pointer; transition: all 0.2s; }
        .variable-tag:hover { background: var(--primary); color: #fff; }

        .tabs { display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 1px solid var(--card-border); padding-bottom: 0; }
        .tab { padding: 10px 20px; font-size: 14px; font-weight: 500; color: var(--text-secondary); cursor: pointer; border: none; background: none; border-bottom: 2px solid transparent; margin-bottom: -1px; transition: all 0.2s; }
        .tab:hover { color: var(--text-primary); }
        .tab.active { color: var(--primary); border-bottom-color: var(--primary); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>

    <div class="settings-page">
        <div class="settings-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Email Settings
            </h1>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button type="button" class="tab active" data-tab="smtp">SMTP Configuration</button>
            <button type="button" class="tab" data-tab="templates">Email Templates</button>
        </div>

        <form action="{{ route('admin.settings.email.save') }}" method="POST">
            @csrf

            <!-- SMTP Configuration Tab -->
            <div class="tab-content active" id="tab-smtp">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                        </svg>
                        <h2>SMTP Configuration</h2>
                    </div>
                    <div class="settings-card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Mail Driver</label>
                                <select name="mail_mailer" class="form-select">
                                    <option value="smtp" {{ $mail_mailer == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $mail_mailer == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Encryption</label>
                                <select name="mail_encryption" class="form-select">
                                    <option value="tls" {{ $mail_encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $mail_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="null" {{ $mail_encryption == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">SMTP Host *</label>
                                <input type="text" name="mail_host" class="form-input" value="{{ old('mail_host', $mail_host) }}" required>
                                <div class="form-hint">Gmail: smtp.gmail.com, Outlook: smtp.office365.com</div>
                                @error('mail_host') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">SMTP Port *</label>
                                <input type="number" name="mail_port" class="form-input" value="{{ old('mail_port', $mail_port) }}" required>
                                <div class="form-hint">TLS: 587, SSL: 465</div>
                                @error('mail_port') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="mail_username" class="form-input" value="{{ old('mail_username', $mail_username) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="mail_password" class="form-input" placeholder="{{ $mail_password ? '••••••••' : 'Enter password' }}">
                                <div class="form-hint">Leave blank to keep current password. For Gmail, use App Password.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">From Address *</label>
                                <input type="email" name="mail_from_address" class="form-input" value="{{ old('mail_from_address', $mail_from_address) }}" required>
                                @error('mail_from_address') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">From Name *</label>
                                <input type="text" name="mail_from_name" class="form-input" value="{{ old('mail_from_name', $mail_from_name) }}" required>
                                @error('mail_from_name') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Templates Tab -->
            <div class="tab-content" id="tab-templates">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h2>Test Email Template</h2>
                    </div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label class="form-label">Test Email Subject</label>
                            <input type="text" name="mail_test_subject" class="form-input" value="{{ old('mail_test_subject', $mail_test_subject ?? 'Test Email - {company_name}') }}">
                            @error('mail_test_subject') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Test Email Body (HTML)</label>
                            <textarea name="mail_test_body" class="form-textarea" rows="8">{{ old('mail_test_body', $mail_test_body ?? '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #333;">Test Email</h2>
    <p>Hello,</p>
    <p>This is a test email from <strong>{company_name}</strong>.</p>
    <p>If you received this email, your mail settings are configured correctly!</p>
    <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="color: #666; font-size: 12px;">Sent at: {date_time}</p>
</div>') }}</textarea>
                            @error('mail_test_body') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="variables-box">
                            <h4>Available Variables (click to copy)</h4>
                            <div class="variables-list">
                                <span class="variable-tag" onclick="copyVariable('{company_name}')">{company_name}</span>
                                <span class="variable-tag" onclick="copyVariable('{company_email}')">{company_email}</span>
                                <span class="variable-tag" onclick="copyVariable('{company_phone}')">{company_phone}</span>
                                <span class="variable-tag" onclick="copyVariable('{company_address}')">{company_address}</span>
                                <span class="variable-tag" onclick="copyVariable('{date}')">{date}</span>
                                <span class="variable-tag" onclick="copyVariable('{time}')">{time}</span>
                                <span class="variable-tag" onclick="copyVariable('{date_time}')">{date_time}</span>
                                <span class="variable-tag" onclick="copyVariable('{year}')">{year}</span>
                                <span class="variable-tag" onclick="copyVariable('{recipient_email}')">{recipient_email}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Email Footer -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h2>Email Footer (All Emails)</h2>
                    </div>
                    <div class="settings-card-body">
                        <div class="form-group">
                            <label class="form-label">Email Footer HTML</label>
                            <textarea name="mail_footer" class="form-textarea" rows="6">{{ old('mail_footer', $mail_footer ?? '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 12px;">
    <p>{company_name}</p>
    <p>{company_address}</p>
    <p>© {year} All rights reserved.</p>
</div>') }}</textarea>
                            <div class="form-hint">This footer will be appended to all outgoing emails.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    Save All Settings
                </button>
            </div>
        </form>

        <!-- Test Email Section -->
        <div class="settings-card">
            <div class="settings-card-body">
                <form action="{{ route('admin.settings.email.test') }}" method="POST">
                    @csrf
                    <div class="test-email-section">
                        <h3>
                            {{-- <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> --}}
                            Send Test Email
                        </h3>
                        <div class="test-email-row">
                            <input type="email" name="test_email" class="form-input" placeholder="test@example.com" required>
                            <button type="submit" class="btn btn-success">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Test
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });

        // Copy variable to clipboard
        function copyVariable(variable) {
            navigator.clipboard.writeText(variable).then(() => {
                // Show brief feedback
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                btn.style.background = 'var(--success)';
                btn.style.color = '#fff';
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 1000);
            });
        }
    </script>
</x-layouts.app>