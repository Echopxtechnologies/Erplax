<style>
    :root {
        --primary: #3b82f6;
        --primary-hover: #2563eb;
        --primary-light: rgba(59, 130, 246, 0.1);
        --success: #10b981;
        --success-light: rgba(16, 185, 129, 0.1);
        --warning: #f59e0b;
        --warning-light: rgba(245, 158, 11, 0.1);
        --danger: #ef4444;
        --danger-light: rgba(239, 68, 68, 0.1);
        --info: #06b6d4;
        --info-light: rgba(6, 182, 212, 0.1);
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        --card-bg: #ffffff;
        --card-border: #e5e7eb;
        --body-bg: #f3f4f6;
        --input-bg: #ffffff;
        --input-border: #d1d5db;
        --input-text: #374151;
    }

    .form-page {
        max-width: 1000px;
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

    .form-tabs {
        display: flex;
        border-bottom: 1px solid var(--card-border);
        background: var(--body-bg);
        overflow-x: auto;
    }

    .form-tab {
        padding: 14px 24px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .form-tab:hover {
        color: var(--text-primary);
        background: rgba(59, 130, 246, 0.05);
    }

    .form-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: var(--card-bg);
    }

    .form-tab svg {
        width: 18px;
        height: 18px;
    }

    .form-tab.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .tab-content {
        display: none;
        padding: 24px;
    }

    .tab-content.active {
        display: block;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-label .required {
        color: var(--danger);
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        border-radius: 8px;
        color: var(--input-text);
        transition: all 0.2s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .form-input:read-only {
        background: var(--body-bg);
        color: var(--text-muted);
    }

    .help-text {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px;
        background: var(--body-bg);
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        font-size: 14px;
        color: var(--text-primary);
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        background: var(--body-bg);
        border-top: 1px solid var(--card-border);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn svg {
        width: 18px;
        height: 18px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #059669);
        color: #fff;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        background: var(--card-bg);
        color: var(--text-secondary);
        border: 1px solid var(--card-border);
    }

    .btn-secondary:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
    }

    .btn-danger {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .btn-danger:hover {
        background: var(--danger);
        color: #fff;
    }

    /* Payments Table */
    .payments-section {
        margin-top: 24px;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .payments-table th {
        background: var(--body-bg);
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    .payments-table th.text-right {
        text-align: right;
    }

    .payments-table td {
        padding: 12px;
        border-top: 1px solid var(--card-border);
        font-size: 14px;
    }

    .payments-table td.text-right {
        text-align: right;
    }

    .payments-table tbody tr:hover {
        background: var(--body-bg);
    }

    /* Add Payment Form */
    .add-payment-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .add-payment-card h4 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Email Preview */
    .email-preview-section {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid var(--card-border);
    }

    .email-preview-card {
        background: #f9fafb;
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .email-preview-header {
        background: var(--info-light);
        padding: 12px 16px;
        font-weight: 600;
        color: var(--info);
    }

    .email-preview-body {
        padding: 20px;
    }

    .email-send-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
    }

    .email-send-card h5 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 8px 0;
    }

    .email-send-card p {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0 0 16px 0;
    }

    /* Status indicator */
    .status-row {
        display: flex;
        gap: 24px;
        margin-top: 16px;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--text-secondary);
    }

    .status-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .alert {
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .alert-danger {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .amount-paid { color: var(--success); font-weight: 600; }
</style>

<div class="form-page">
    <!-- Header -->
    <div class="form-header">
        <a href="{{ route('admin.studentsponsor.transaction.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1>{{ isset($transaction) ? 'Edit Transaction #' . $transaction->id : 'New Transaction' }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="form-card">
        <!-- Tabs -->
        <div class="form-tabs">
            <a href="#" class="form-tab active" data-tab="tab-details">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Details
            </a>
            <a href="#" class="form-tab {{ !isset($transaction) ? 'disabled' : '' }}" data-tab="tab-payments" {{ !isset($transaction) ? 'onclick="return false;"' : '' }}>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Payments
            </a>
        </div>

        <!-- Tab: Details -->
        <div id="tab-details" class="tab-content active">
            <form action="{{ isset($transaction) ? route('admin.studentsponsor.transaction.update', $transaction->id) : route('admin.studentsponsor.transaction.store') }}" method="POST">
                @csrf
                @if(isset($transaction))
                    @method('PUT')
                @endif

                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Sponsor & Student
                </div>

                <div class="form-group">
                    <label class="form-label">Sponsor <span class="required">*</span></label>
                    <select name="sponsor_id" class="form-select" required>
                        <option value="">-- Select Sponsor --</option>
                        @foreach($sponsors as $sponsor)
                            <option value="{{ $sponsor->id }}" {{ old('sponsor_id', $transaction->sponsor_id ?? '') == $sponsor->id ? 'selected' : '' }}>
                                {{ $sponsor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">School Student</label>
                        <select name="school_student_id" id="school_student_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($schoolStudents as $student)
                                <option value="{{ $student->id }}" {{ old('school_student_id', $transaction->school_student_id ?? '') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->school_internal_id ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">University Student</label>
                        <select name="university_student_id" id="university_student_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($universityStudents as $student)
                                <option value="{{ $student->id }}" {{ old('university_student_id', $transaction->university_student_id ?? '') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->university_internal_id ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="help-text">Pick <strong>either</strong> School student <strong>or</strong> University student (not both).</p>

                <div class="section-title" style="margin-top: 24px;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Payment Details
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Total Amount <span class="required">*</span></label>
                        <input type="number" step="0.01" name="total_amount" class="form-input" required value="{{ old('total_amount', $transaction->total_amount ?? '0.00') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount Paid</label>
                        <input type="number" step="0.01" name="amount_paid_display" class="form-input" readonly value="{{ old('amount_paid', $transaction->amount_paid ?? '0.00') }}">
                        <p class="help-text">Auto-updates from Payments.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency <span class="required">*</span></label>
                        <select name="currency" class="form-select" required>
                            @foreach($currencies as $code => $label)
                                <option value="{{ $code }}" {{ old('currency', $transaction->currency ?? 'LKR') == $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" class="form-select">
                        @foreach($paymentTypes as $type => $label)
                            <option value="{{ $type }}" {{ old('payment_type', $transaction->payment_type ?? 'one_time') == $type ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Last Payment Date</label>
                        <input type="date" name="last_payment_date_display" class="form-input" readonly value="{{ old('last_payment_date', isset($transaction) && $transaction->last_payment_date ? $transaction->last_payment_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Payment Due</label>
                        <input type="date" name="next_payment_due" class="form-input" value="{{ old('next_payment_due', isset($transaction) && $transaction->next_payment_due ? $transaction->next_payment_due->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="section-title" style="margin-top: 24px;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Reminders
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="due_reminder_active" id="due_reminder_active" value="1" {{ old('due_reminder_active', $transaction->due_reminder_active ?? 0) ? 'checked' : '' }}>
                    <label for="due_reminder_active">Due Reminder Active</label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Days Before Due</label>
                        <input type="number" name="due_reminder_days_before" class="form-input" min="0" value="{{ old('due_reminder_days_before', $transaction->due_reminder_days_before ?? 15) }}">
                    </div>
                </div>

                @if(isset($transaction))
                <div class="status-row">
                    <div class="status-item">
                        <input type="checkbox" {{ $transaction->due_reminder_sent ? 'checked' : '' }} disabled>
                        <label>X-days-before Email Sent</label>
                    </div>
                    <div class="status-item">
                        <input type="checkbox" {{ $transaction->due_day_email_sent ?? 0 ? 'checked' : '' }} disabled>
                        <label>Due-Day Email Sent</label>
                    </div>
                </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Save
                    </button>
                    <a href="{{ route('admin.studentsponsor.transaction.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Tab: Payments -->
        @if(isset($transaction))
        <div id="tab-payments" class="tab-content {{ request('tab') == 'payments' ? 'active' : '' }}">
            <!-- Add Payment Form -->
            <div class="add-payment-card">
                <h4>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                        <path d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ isset($editingPayment) ? 'Edit Payment' : 'Add New Payment' }}
                </h4>

                <form action="{{ isset($editingPayment) ? route('admin.studentsponsor.transaction.payment.update', [$transaction->id, $editingPayment->id]) : route('admin.studentsponsor.transaction.payment.store', $transaction->id) }}" method="POST">
                    @csrf
                    @if(isset($editingPayment))
                        @method('PUT')
                    @endif

                    <input type="hidden" name="sponsor_id" value="{{ $transaction->sponsor_id }}">
                    <input type="hidden" name="student_id" value="{{ $transaction->school_student_id ?? $transaction->university_student_id }}">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Payment Date <span class="required">*</span></label>
                            <input type="date" name="payment_date" class="form-input" required value="{{ old('payment_date', isset($editingPayment) ? $editingPayment->payment_date->format('Y-m-d') : date('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Amount <span class="required">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-input" required value="{{ old('amount', $editingPayment->amount ?? '') }}" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select">
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" {{ old('currency', $editingPayment->currency ?? $transaction->currency) == $code ? 'selected' : '' }}>
                                        {{ $code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Note</label>
                        <input type="text" name="note" class="form-input" value="{{ old('note', $editingPayment->note ?? '') }}" placeholder="Optional note...">
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-success btn-sm">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ isset($editingPayment) ? 'Update Payment' : 'Add Payment' }}
                        </button>
                        @if(isset($editingPayment))
                            <a href="{{ route('admin.studentsponsor.transaction.edit', ['id' => $transaction->id, 'tab' => 'payments']) }}" class="btn btn-secondary btn-sm">Cancel</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Payments List -->
            <div class="payments-section">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Payment History
                </div>

                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-right">Amount</th>
                            <th>Currency</th>
                            <th>Note</th>
                            <th>Created By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td class="text-right amount-paid">{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->currency }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                            <td>{{ $payment->created_by ?? '-' }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.studentsponsor.transaction.edit', ['id' => $transaction->id, 'tab' => 'payments', 'edit_payment' => $payment->id]) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.studentsponsor.transaction.payment.destroy', [$transaction->id, $payment->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this payment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                No payments recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Email Preview Section -->
            @if(isset($emailTemplate) && $emailTemplate)
            <div class="email-preview-section">
                <div class="section-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Due Payment Email Preview
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <div class="email-preview-card">
                            <div class="email-preview-header">
                                <strong>Subject:</strong> {{ $emailTemplate['subject'] }}
                            </div>
                            <div class="email-preview-body">
                                {!! $emailTemplate['body'] !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <div class="email-send-card">
                            <h5>Send Email</h5>
                            <p>Send due payment reminder to sponsor.</p>
                            <a href="{{ route('admin.studentsponsor.transaction.sendEmail', $transaction->id) }}" class="btn btn-success" onclick="return confirm('Send due payment reminder email now?');">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.form-tab:not(.disabled)');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.tab;

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // XOR for School/University student
    const schoolSelect = document.getElementById('school_student_id');
    const universitySelect = document.getElementById('university_student_id');

    if (schoolSelect && universitySelect) {
        schoolSelect.addEventListener('change', function() {
            if (this.value) {
                universitySelect.value = '';
            }
        });

        universitySelect.addEventListener('change', function() {
            if (this.value) {
                schoolSelect.value = '';
            }
        });
    }

    // Activate payments tab if URL has tab=payments
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'payments') {
        document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector('[data-tab="tab-payments"]')?.classList.add('active');
        document.getElementById('tab-payments')?.classList.add('active');
    }
});
</script>
