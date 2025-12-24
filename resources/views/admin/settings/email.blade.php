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
        
        .settings-footer { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--card-border); margin-top: 20px; }
        
        .test-email-section { background: var(--primary-light); border: 1px solid rgba(59,130,246,0.2); border-radius: 10px; padding: 16px; margin-top: 20px; }
        .test-email-section h3 { font-size: 14px; font-weight: 600; color: var(--primary); margin: 0 0 12px 0; }
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

        .input-with-icon { position: relative; }
        .input-with-icon .form-input { padding-right: 45px; }
        .input-icon-btn { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 6px; border-radius: 4px; }
        .input-icon-btn:hover { color: var(--primary); background: var(--primary-light); }
        .input-icon-btn svg { width: 20px; height: 20px; display: block; }
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

        <div class="tabs">
            <button type="button" class="tab active" data-tab="smtp">SMTP Configuration</button>
            <button type="button" class="tab" data-tab="templates">Email Templates</button>
        </div>

        <form action="{{ route('admin.settings.email.save') }}" method="POST">
            @csrf

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
                            </div>
                            <div class="form-group">
                                <label class="form-label">SMTP Port *</label>
                                <input type="number" name="mail_port" class="form-input" value="{{ old('mail_port', $mail_port) }}" required>
                                <div class="form-hint">TLS: 587, SSL: 465</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="mail_username" class="form-input" value="{{ old('mail_username', $mail_username) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <div class="input-with-icon">
                                    <input type="password" name="mail_password" id="mailPassword" class="form-input" value="{{ old('mail_password', $mail_password) }}">
                                    <button type="button" class="input-icon-btn" onclick="togglePassword()" title="Show/Hide">
                                        <svg id="eyeShow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg id="eyeHide" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                                            <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="form-hint">For Gmail use <strong>App Password</strong> (16 chars). <a href="https://myaccount.google.com/apppasswords" target="_blank">Get App Password →</a></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">From Address *</label>
                                <input type="email" name="mail_from_address" class="form-input" value="{{ old('mail_from_address', $mail_from_address) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">From Name *</label>
                                <input type="text" name="mail_from_name" class="form-input" value="{{ old('mail_from_name', $mail_from_name) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                        </div>

                        <div class="form-group">
                            <label class="form-label">Test Email Body (HTML)</label>
                            <textarea name="mail_test_body" class="form-textarea" rows="8">{{ old('mail_test_body', $mail_test_body ?? '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;"><h2 style="color: #333;">Test Email</h2><p>Hello,</p><p>This is a test email from <strong>{company_name}</strong>.</p><p>If you received this email, your mail settings are configured correctly!</p><hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;"><p style="color: #666; font-size: 12px;">Sent at: {date_time}</p></div>') }}</textarea>
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
                            <textarea name="mail_footer" class="form-textarea" rows="6">{{ old('mail_footer', $mail_footer ?? '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 12px;"><p>{company_name}</p><p>{company_address}</p><p>© {year} All rights reserved.</p></div>') }}</textarea>
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

        <div class="settings-card">
            <div class="settings-card-body">
                <form action="{{ route('admin.settings.email.test') }}" method="POST">
                    @csrf
                    <div class="test-email-section">
                        <h3>Send Test Email</h3>
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
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });

        function togglePassword() {
            const input = document.getElementById('mailPassword');
            const eyeShow = document.getElementById('eyeShow');
            const eyeHide = document.getElementById('eyeHide');
            if (input.type === 'password') {
                input.type = 'text';
                eyeShow.style.display = 'none';
                eyeHide.style.display = 'block';
            } else {
                input.type = 'password';
                eyeShow.style.display = 'block';
                eyeHide.style.display = 'none';
            }
        }

        function copyVariable(variable) {
            navigator.clipboard.writeText(variable).then(() => {
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