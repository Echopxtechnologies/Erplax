<x-layouts.app>
    <style>
        .form-page { max-width: 800px; margin: 0 auto; }
        .form-header { margin-bottom: 24px; }
        .form-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px; display: flex; align-items: center; gap: 10px; }
        .form-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
        .form-header p { color: var(--text-muted); margin: 0; }
        
        .form-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
        .form-card-body { padding: 24px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
        .form-label span { color: #ef4444; }
        .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
        
        .form-control { width: 100%; padding: 12px 16px; font-size: 14px; border: 1px solid var(--card-border); border-radius: 8px; background: var(--card-bg); color: var(--text-primary); transition: border-color 0.2s, box-shadow 0.2s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .form-control::placeholder { color: var(--text-muted); }
        
        select.form-control { cursor: pointer; }
        textarea.form-control { min-height: 100px; resize: vertical; }
        
        .form-check { display: flex; align-items: center; gap: 10px; }
        .form-check input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--primary); cursor: pointer; }
        .form-check label { font-size: 14px; color: var(--text-primary); cursor: pointer; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 12px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: var(--card-border); color: var(--text-primary); }
        .btn-secondary:hover { background: var(--text-muted); color: #fff; }
        
        .form-actions { display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--card-border); }
        
        .available-methods { background: var(--body-bg); border-radius: 8px; padding: 16px; margin-top: 16px; }
        .available-methods h4 { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin: 0 0 12px; text-transform: uppercase; }
        .method-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .method-item { background: var(--card-bg); border: 1px solid var(--card-border); padding: 6px 12px; border-radius: 6px; font-size: 13px; font-family: monospace; cursor: pointer; transition: all 0.2s; }
        .method-item:hover { border-color: var(--primary); background: rgba(59,130,246,0.1); }
        
        .error-message { color: #ef4444; font-size: 13px; margin-top: 6px; }
    </style>

    <div class="form-page">
        <div class="form-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                Add Cron Job
            </h1>
            <p>Create a new scheduled task that runs automatically.</p>
        </div>

        <div class="form-card">
            <div class="form-card-body">
                <form action="{{ route('admin.cronjob.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Name <span>*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Send Invoice Reminders" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">A descriptive name for this cron job.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Method <span>*</span></label>
                        <input type="text" name="method" id="methodInput" class="form-control" value="{{ old('method') }}" placeholder="e.g., TestCron/sendRemainder" required>
                        @error('method')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Format: <code>ClassName/methodName</code> — The class should be in <code>app/Crons/</code> directory.</div>
                        
                        @if(!empty($availableCrons))
                            <div class="available-methods">
                                <h4>Available Methods (Click to use)</h4>
                                <div class="method-list">
                                    @foreach($availableCrons as $class => $methods)
                                        @foreach($methods as $method)
                                            <span class="method-item" onclick="document.getElementById('methodInput').value='{{ $class }}/{{ $method }}'">
                                                {{ $class }}/{{ $method }}
                                            </span>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Schedule <span>*</span></label>
                        <select name="schedule" class="form-control" required>
                            @foreach($schedules as $value => $label)
                                <option value="{{ $value }}" {{ old('schedule', 'daily') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('schedule')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">How often should this cron job run?</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="What does this cron job do?">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                            <label for="status">Active — Run this cron job according to schedule</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Cron Job
                        </button>
                        <a href="{{ route('admin.cronjob.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>