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
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        
        .form-actions { display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--card-border); }
        .form-actions-right { margin-left: auto; }
        
        .available-methods { background: var(--body-bg); border-radius: 8px; padding: 16px; margin-top: 16px; }
        .available-methods h4 { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin: 0 0 12px; text-transform: uppercase; }
        .method-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .method-item { background: var(--card-bg); border: 1px solid var(--card-border); padding: 6px 12px; border-radius: 6px; font-size: 13px; font-family: monospace; cursor: pointer; transition: all 0.2s; }
        .method-item:hover { border-color: var(--primary); background: rgba(59,130,246,0.1); }
        
        .error-message { color: #ef4444; font-size: 13px; margin-top: 6px; }
        
        .last-run-info { background: var(--body-bg); border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        .last-run-info h4 { font-size: 13px; font-weight: 600; color: var(--text-secondary); margin: 0 0 8px; text-transform: uppercase; }
        .last-run-info p { margin: 0; font-size: 14px; color: var(--text-primary); }
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; font-size: 11px; font-weight: 600; border-radius: 20px; margin-left: 8px; }
        .badge-success { background: rgba(34,197,94,0.1); color: #22c55e; }
        .badge-danger { background: rgba(239,68,68,0.1); color: #ef4444; }
    </style>

    <div class="form-page">
        <div class="form-header">
            <h1>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Cron Job
            </h1>
            <p>Update the scheduled task configuration.</p>
        </div>

        <div class="form-card">
            <div class="form-card-body">
                @if($cronjob->last_run)
                    <div class="last-run-info">
                        <h4>Last Execution</h4>
                        <p>
                            {{ $cronjob->last_run->format('M d, Y H:i:s') }} ({{ $cronjob->last_run->diffForHumans() }})
                            @if($cronjob->last_status === 'success')
                                <span class="badge badge-success">Success</span>
                            @elseif($cronjob->last_status === 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @endif
                            @if($cronjob->last_duration)
                                — Duration: {{ $cronjob->last_duration_formatted }}
                            @endif
                        </p>
                        @if($cronjob->last_message)
                            <p style="margin-top: 8px; color: var(--text-muted); font-size: 13px;">
                                {{ $cronjob->last_message }}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- UPDATE FORM --}}
                <form action="{{ route('admin.cronjob.update', $cronjob) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Name <span>*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $cronjob->name) }}" placeholder="e.g., Send Invoice Reminders" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">A descriptive name for this cron job.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Method <span>*</span></label>
                        <input type="text" name="method" id="methodInput" class="form-control" value="{{ old('method', $cronjob->method) }}" placeholder="e.g., TestCron/sendRemainder" required>
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
                                <option value="{{ $value }}" {{ old('schedule', $cronjob->schedule) === $value ? 'selected' : '' }}>
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
                        <textarea name="description" class="form-control" placeholder="What does this cron job do?">{{ old('description', $cronjob->description) }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="status" id="status" value="1" {{ old('status', $cronjob->status) ? 'checked' : '' }}>
                            <label for="status">Active — Run this cron job according to schedule</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Cron Job
                        </button>
                        <a href="{{ route('admin.cronjob.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
                {{-- END UPDATE FORM --}}

                {{-- DELETE FORM (Separate, outside update form) --}}
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--card-border);">
                    <form action="{{ route('admin.cronjob.destroy', $cronjob) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this cron job?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Cron Job
                        </button>
                    </form>
                </div>
                {{-- END DELETE FORM --}}

            </div>
        </div>
    </div>
</x-layouts.app>