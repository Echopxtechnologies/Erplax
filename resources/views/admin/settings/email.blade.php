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
        
        .form-input, .form-select {
            width: 100%; padding: 10px 14px; font-size: 14px;
            background: var(--input-bg); border: 1px solid var(--input-border);
            border-radius: 8px; color: var(--input-text); transition: all 0.2s;
        }
        .form-input:focus, .form-select:focus {
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
        .test-email-section h3 { font-size: 14px; font-weight: 600; color: var(--primary); margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px; }
        .test-email-row { display: flex; gap: 12px; }
        .test-email-row .form-input { flex: 1; }
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

        <form action="{{ route('admin.settings.email.save') }}" method="POST">
            @csrf

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
                            <input type="password" name="mail_password" class="form-input" value="{{ old('mail_password', $mail_password) }}">
                            <div class="form-hint">For Gmail, use App Password</div>
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

                    <div class="settings-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                            Save Email Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Test Email Section -->
        <div class="settings-card">
            <div class="settings-card-body">
                <form action="{{ route('admin.settings.email.test') }}" method="POST">
                    @csrf
                    <div class="test-email-section">
                        <h3>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
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
</x-layouts.app>