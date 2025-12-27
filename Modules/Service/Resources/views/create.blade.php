<style>
    .form-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .form-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .btn-back {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-back:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .btn-back svg {
        width: 20px;
        height: 20px;
    }
    
    .form-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        background: var(--body-bg);
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--primary);
    }
    
    .form-card-body {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .form-label .required {
        color: var(--danger);
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        border-radius: 10px;
        color: var(--input-text);
        transition: all 0.2s;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-light);
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .form-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }
    
    .form-error {
        font-size: 12px;
        color: var(--danger);
        margin-top: 6px;
    }
    
    /* Frequency Options */
    .frequency-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .frequency-option {
        flex: 1;
        min-width: 100px;
    }
    
    .frequency-option input {
        display: none;
    }
    
    .frequency-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 14px 10px;
        background: var(--body-bg);
        border: 2px solid var(--card-border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    
    .frequency-option label:hover {
        border-color: var(--text-muted);
    }
    
    .frequency-option input:checked + label {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    
    .frequency-option .freq-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary);
    }
    
    .frequency-option .freq-icon svg {
        width: 16px;
        height: 16px;
    }
    
    .frequency-option .freq-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        background: var(--body-bg);
        border-top: 1px solid var(--card-border);
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-submit svg {
        width: 18px;
        height: 18px;
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--card-bg);
        color: var(--text-secondary);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-cancel:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    /* Error Alert */
    .alert-errors {
        background: var(--danger-light);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .alert-errors-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--danger);
        margin-bottom: 8px;
    }
    
    .alert-errors ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-errors li {
        font-size: 13px;
        color: var(--danger);
        margin-bottom: 4px;
    }
    
    /* Section divider */
    .form-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--card-border);
    }
    
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }
    
    @media (max-width: 768px) {
        .form-row-3 {
            grid-template-columns: 1fr;
        }
        .frequency-options {
            flex-direction: column;
        }
    }
</style>

<div class="form-page">
    <!-- Header -->
    <div class="form-header">
        <a href="{{ route('admin.service.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1>Add New Service</h1>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert-errors">
            <div class="alert-errors-title">Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('admin.service.store') }}" method="POST">
        @csrf
        
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Service Details
                </h2>
            </div>
            
            <div class="form-card-body">
                <!-- Client Selection -->
                <div class="form-group">
                    <label class="form-label">
                        Client <span class="required">*</span>
                    </label>
                    <select name="client_id" class="form-select" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->company ?? $client->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Machine Name -->
                <div class="form-group">
                    <label class="form-label">
                        Machine Name <span class="required">*</span>
                    </label>
                    <input type="text" name="machine_name" class="form-input" 
                           value="{{ old('machine_name') }}" 
                           placeholder="Enter machine/equipment name..."
                           required>
                    @error('machine_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Equipment Details -->
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Equipment No</label>
                        <input type="text" name="equipment_no" class="form-input" 
                               value="{{ old('equipment_no') }}" 
                               placeholder="EQ-001">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Model No</label>
                        <input type="text" name="model_no" class="form-input" 
                               value="{{ old('model_no') }}" 
                               placeholder="Model number">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-input" 
                               value="{{ old('serial_number') }}" 
                               placeholder="Serial number">
                    </div>
                </div>

                <!-- Service Frequency -->
                <div class="form-section">
                    <div class="form-section-title">Service Schedule</div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Service Frequency <span class="required">*</span>
                        </label>
                        <div class="frequency-options">
                            <div class="frequency-option">
                                <input type="radio" name="service_frequency" value="monthly" id="freq_monthly" 
                                       {{ old('service_frequency', 'monthly') == 'monthly' ? 'checked' : '' }}>
                                <label for="freq_monthly">
                                    <div class="freq-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="freq-text">Monthly</span>
                                </label>
                            </div>
                            <div class="frequency-option">
                                <input type="radio" name="service_frequency" value="quarterly" id="freq_quarterly"
                                       {{ old('service_frequency') == 'quarterly' ? 'checked' : '' }}>
                                <label for="freq_quarterly">
                                    <div class="freq-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="freq-text">Quarterly</span>
                                </label>
                            </div>
                            <div class="frequency-option">
                                <input type="radio" name="service_frequency" value="half_yearly" id="freq_half_yearly"
                                       {{ old('service_frequency') == 'half_yearly' ? 'checked' : '' }}>
                                <label for="freq_half_yearly">
                                    <div class="freq-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="freq-text">Half Yearly</span>
                                </label>
                            </div>
                            <div class="frequency-option">
                                <input type="radio" name="service_frequency" value="yearly" id="freq_yearly"
                                       {{ old('service_frequency') == 'yearly' ? 'checked' : '' }}>
                                <label for="freq_yearly">
                                    <div class="freq-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="freq-text">Yearly</span>
                                </label>
                            </div>
                            <div class="frequency-option">
                                <input type="radio" name="service_frequency" value="custom" id="freq_custom"
                                       {{ old('service_frequency') == 'custom' ? 'checked' : '' }}>
                                <label for="freq_custom">
                                    <div class="freq-icon">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <span class="freq-text">Custom</span>
                                </label>
                            </div>
                        </div>
                        @error('service_frequency')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                First Service Date <span class="required">*</span>
                            </label>
                            <input type="date" name="first_service_date" id="first_service_date" class="form-input" 
                                   value="{{ old('first_service_date') }}" required onchange="calculateNextDate()">
                            <div class="form-hint">When should the first service be scheduled?</div>
                            @error('first_service_date')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Next Service Date</label>
                            <input type="date" name="next_service_date" id="next_service_date" class="form-input" 
                                   value="{{ old('next_service_date') }}">
                            <div class="form-hint" id="next_date_hint">Auto-calculated when service is marked completed</div>
                        </div>
                    </div>
                </div>

                <!-- Reminder Settings -->
                <div class="form-section">
                    <div class="form-section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;display:inline;vertical-align:middle;margin-right:6px;">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Reminder Settings
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Reminder Days Before</label>
                            <input type="number" name="reminder_days" class="form-input" 
                                   value="{{ old('reminder_days', 15) }}" min="1" max="90">
                            <div class="form-hint">How many days before due date to send reminder</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Auto Send Reminders</label>
                            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:8px;transition:all 0.2s;">
                                    <input type="checkbox" name="auto_reminder" value="1" 
                                           {{ old('auto_reminder', true) ? 'checked' : '' }}
                                           style="width:18px;height:18px;cursor:pointer;">
                                    <span style="font-weight:500;color:#1e293b;">Enable automatic email reminders</span>
                                </label>
                            </div>
                            <div class="form-hint">When enabled, system will automatically send reminder emails before service due date</div>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="form-section">
                    <div class="form-section-title">Status</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Service Status</label>
                            <select name="service_status" class="form-select">
                                <option value="draft" {{ old('service_status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('service_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('service_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="canceled" {{ old('service_status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-textarea" 
                                  placeholder="Add any additional notes or special instructions...">{{ old('notes') }}</textarea>
                        <div class="form-hint">Optional: Include any relevant details about this service</div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Service
                </button>
                <a href="{{ route('admin.service.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-calculate next service date based on frequency
    function calculateNextDate() {
        const frequency = document.querySelector('input[name="service_frequency"]:checked')?.value;
        const firstDateInput = document.getElementById('first_service_date');
        const nextDateInput = document.getElementById('next_service_date');
        const hintEl = document.getElementById('next_date_hint');
        const firstDate = firstDateInput.value;
        
        // Handle custom frequency - enable manual input
        if (frequency === 'custom') {
            nextDateInput.disabled = false;
            nextDateInput.style.backgroundColor = '';
            nextDateInput.style.cursor = '';
            hintEl.textContent = 'Enter custom next service date';
            return;
        }
        
        // Disable for other frequencies
        nextDateInput.disabled = true;
        nextDateInput.style.backgroundColor = '#f1f5f9';
        nextDateInput.style.cursor = 'not-allowed';
        hintEl.textContent = 'Auto-calculated when service is marked completed';
        
        if (!firstDate) {
            nextDateInput.value = '';
            return;
        }
        
        // Calculate next date based on frequency
        const date = new Date(firstDate);
        
        switch(frequency) {
            case 'monthly':
                date.setMonth(date.getMonth() + 1);
                break;
            case 'quarterly':
                date.setMonth(date.getMonth() + 3);
                break;
            case 'half_yearly':
                date.setMonth(date.getMonth() + 6);
                break;
            case 'yearly':
                date.setFullYear(date.getFullYear() + 1);
                break;
            default:
                return;
        }
        
        // Format as YYYY-MM-DD
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        nextDateInput.value = `${year}-${month}-${day}`;
    }
    
    // Listen to frequency changes
    document.querySelectorAll('input[name="service_frequency"]').forEach(input => {
        input.addEventListener('change', calculateNextDate);
    });
    
    // Calculate on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateNextDate();
    });
</script>