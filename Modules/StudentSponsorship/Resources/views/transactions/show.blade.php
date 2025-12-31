<style>
    .page-container { padding: 24px; max-width: 1200px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 12px; }
    .page-title svg { width: 28px; height: 28px; color: var(--primary); }
    .header-actions { display: flex; gap: 12px; }
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; }
    .btn-back { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-secondary); }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn svg { width: 18px; height: 18px; }

    .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }

    .detail-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .card-header { padding: 16px 20px; background: var(--body-bg); border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }
    .card-header h3 { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .card-header h3 svg { width: 20px; height: 20px; color: var(--primary); }
    .card-body { padding: 20px; }

    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .detail-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .detail-item { }
    .detail-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
    .detail-value { font-size: 15px; color: var(--text-primary); }
    .detail-value.large { font-size: 28px; font-weight: 700; }
    .detail-value.mono { font-family: monospace; }
    .detail-value.success { color: var(--success); }
    .detail-value.primary { color: var(--primary); }
    .detail-value.warning { color: #d97706; }

    .badge { padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    .badge-success { background: #dcfce7; color: #16a34a; }
    .badge-warning { background: #fef3c7; color: #d97706; }
    .badge-danger { background: #fee2e2; color: #dc2626; }
    .badge-secondary { background: #e5e7eb; color: #6b7280; }
    .badge-info { background: #dbeafe; color: #2563eb; }

    /* Progress bar */
    .progress-container { margin-top: 20px; }
    .progress-bar { height: 12px; background: #e5e7eb; border-radius: 6px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--success)); transition: width 0.3s; }
    .progress-labels { display: flex; justify-content: space-between; margin-top: 8px; font-size: 13px; }

    /* Summary Cards */
    .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
    .summary-card { background: var(--body-bg); border-radius: 10px; padding: 16px; text-align: center; }
    .summary-card .amount { font-size: 22px; font-weight: 700; }
    .summary-card .label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

    /* Payments Table */
    .payments-table { width: 100%; border-collapse: collapse; }
    .payments-table th { text-align: left; padding: 12px; background: var(--body-bg); font-weight: 600; font-size: 12px; text-transform: uppercase; color: var(--text-muted); }
    .payments-table td { padding: 12px; border-bottom: 1px solid var(--card-border); font-size: 14px; }
    .payments-table tbody tr:hover { background: rgba(59,130,246,0.05); }

    .action-btns { display: flex; gap: 6px; }
    .btn-action { width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; }
    .btn-action svg { width: 14px; height: 14px; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: #fff; }

    .empty-payments { padding: 40px; text-align: center; color: var(--text-muted); }
    .empty-payments svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5; }

    /* Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 100000; }
    .modal-overlay.show { display: flex; }
    .modal-content { background: var(--card-bg); border-radius: 12px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
    .modal-header { padding: 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { font-size: 18px; font-weight: 600; }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); }
    .modal-body { padding: 20px; }
    .modal-footer { padding: 20px; border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 12px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); box-sizing: border-box; }
    .form-input:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }

    /* Searchable Select */
    .searchable-select { position: relative; }
    .searchable-select .ss-display { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); cursor: pointer; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; min-height: 42px; }
    .searchable-select .ss-display:hover { border-color: var(--primary); }
    .searchable-select .ss-display.open { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); border-radius: 8px 8px 0 0; }
    .searchable-select .ss-arrow { transition: transform 0.2s; }
    .searchable-select .ss-display.open .ss-arrow { transform: rotate(180deg); }
    .searchable-select .ss-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg); border: 1px solid var(--primary); border-top: none; border-radius: 0 0 8px 8px; max-height: 250px; overflow: hidden; display: none; z-index: 100001; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .searchable-select .ss-dropdown.show { display: block; }
    .searchable-select .ss-search { padding: 10px; border-bottom: 1px solid var(--card-border); }
    .searchable-select .ss-search input { width: 100%; padding: 8px 12px; border: 1px solid var(--input-border); border-radius: 6px; font-size: 14px; background: var(--input-bg); color: var(--input-text); box-sizing: border-box; }
    .searchable-select .ss-options { max-height: 180px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 14px; cursor: pointer; font-size: 14px; }
    .searchable-select .ss-option:hover { background: var(--primary-light); color: var(--primary); }
    .searchable-select .ss-option.selected { background: var(--primary); color: #fff; }
    .searchable-select .ss-no-results { padding: 12px 14px; color: var(--text-muted); font-size: 14px; text-align: center; }

    .alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #16a34a; }

    .sponsor-link { color: var(--primary); text-decoration: none; font-weight: 600; }
    .sponsor-link:hover { text-decoration: underline; }

    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; }
        .detail-grid, .detail-grid-3, .summary-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
            Transaction: {{ $transaction->transaction_number }}
        </h1>
        <div class="header-actions">
            <a href="{{ route('admin.studentsponsorship.transactions.index') }}" class="btn btn-back">← Back</a>
            @if($transaction->sponsor && $transaction->sponsor->email)
            <button onclick="openEmailModal()" class="btn" style="background:#dbeafe;color:#2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                Send Email
            </button>
            @endif
            <a href="{{ route('admin.studentsponsorship.transactions.edit', $transaction->id) }}" class="btn btn-edit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid-2">
        <div>
            <!-- Transaction Summary -->
            <div class="detail-card">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Payment Summary
                    </h3>
                    <span class="badge badge-{{ $transaction->status_badge }}">{{ $transaction->status_display }}</span>
                </div>
                <div class="card-body">
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="amount">{{ $transaction->formatted_total }}</div>
                            <div class="label">Total Amount</div>
                        </div>
                        <div class="summary-card">
                            <div class="amount" style="color:var(--success);">{{ $transaction->formatted_paid }}</div>
                            <div class="label">Amount Paid</div>
                        </div>
                        <div class="summary-card">
                            <div class="amount" style="color:{{ $transaction->balance > 0 ? '#d97706' : ($transaction->balance < 0 ? 'var(--primary)' : 'var(--success)') }};">
                                @if($transaction->balance < 0)
                                    {{ $transaction->currency_symbol }}{{ number_format(abs($transaction->balance), 2) }}
                                    <span style="font-size:12px;">(Extra)</span>
                                @else
                                    {{ $transaction->formatted_balance }}
                                @endif
                            </div>
                            <div class="label">{{ $transaction->balance < 0 ? 'Extra Paid' : 'Balance' }}</div>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min(100, $transaction->payment_progress) }}%;"></div>
                        </div>
                        <div class="progress-labels">
                            <span>
                                @if($transaction->payment_progress > 100)
                                    {{ number_format($transaction->payment_progress, 1) }}% ({{ number_format($transaction->payment_progress - 100, 1) }}% Extra)
                                @else
                                    {{ number_format($transaction->payment_progress, 1) }}% Paid
                                @endif
                            </span>
                            <span>{{ $transaction->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="detail-card">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                        Payments
                    </h3>
                    @if($transaction->status !== 'cancelled')
                    <button class="btn btn-primary" onclick="openPaymentModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        @if($transaction->status === 'completed')
                            Add Extra
                        @else
                            Add Payment
                        @endif
                    </button>
                    @endif
                </div>
                <div class="card-body" style="padding:0;">
                    @if($transaction->payments->count() > 0)
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date?->format('d M Y') }}</td>
                                <td style="font-weight:600;color:var(--success);">{{ $payment->formatted_amount }}</td>
                                <td>{{ $payment->payment_method_display ?? '-' }}</td>
                                <td style="font-family:monospace;">{{ $payment->reference_number ?? '-' }}</td>
                                <td>
                                    <div style="font-size:13px;">{{ $payment->created_by_name ?? 'System' }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $payment->created_at?->format('d M Y, h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button onclick="deletePayment({{ $payment->id }})" class="btn-action btn-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-payments">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                        <p>No payments recorded yet</p>
                        @if($transaction->status !== 'cancelled')
                        <button class="btn btn-primary" onclick="openPaymentModal()" style="margin-top:12px;">Add First Payment</button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <!-- Transaction Details -->
            <div class="detail-card">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                        Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Sponsor</div>
                        <div class="detail-value">
                            @if($transaction->sponsor)
                            <a href="{{ route('admin.studentsponsorship.sponsors.show', $transaction->sponsor_id) }}" class="sponsor-link">
                                {{ $transaction->sponsor->name }}
                            </a>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $transaction->sponsor->sponsor_internal_id }}</div>
                            @else
                            -
                            @endif
                        </div>
                    </div>

                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Student</div>
                        <div class="detail-value">
                            @if($transaction->student_name)
                            {{ $transaction->student_name }}
                            <div style="font-size:12px;color:var(--text-muted);">{{ ucfirst($transaction->student_type) }} - {{ $transaction->student_id_display }}</div>
                            @else
                            <span style="color:var(--text-muted);">None</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Payment Type</div>
                        <div class="detail-value">{{ $transaction->payment_type_display }}</div>
                    </div>

                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Currency</div>
                        <div class="detail-value">{{ $transaction->currency_display }}</div>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="detail-card">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        Schedule
                    </h3>
                </div>
                <div class="card-body">
                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Last Payment</div>
                        <div class="detail-value">{{ $transaction->last_payment_date?->format('d M Y') ?? 'No payments yet' }}</div>
                    </div>
                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Next Due</div>
                        <div class="detail-value {{ $transaction->isDue() ? 'warning' : '' }}">
                            {{ $transaction->next_payment_due?->format('d M Y') ?? '-' }}
                            @if($transaction->isDue())
                            <span class="badge badge-warning" style="margin-left:8px;">Overdue</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Reminder</div>
                        <div class="detail-value">
                            @if($transaction->due_reminder_active)
                            <span class="badge badge-success">Active</span>
                            <span style="margin-left:8px;">{{ $transaction->days_before_due }} days before</span>
                            @else
                            <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($transaction->due_reminder_active)
                    <div class="detail-item">
                        <div class="detail-label">Email Status</div>
                        <div class="detail-value" style="font-size:13px;">
                            X-days: {{ $transaction->x_days_email_sent ? '✓ Sent' : '✗ Not Sent' }}<br>
                            Due-day: {{ $transaction->due_day_email_sent ? '✓ Sent' : '✗ Not Sent' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($transaction->description || $transaction->internal_note)
            <div class="detail-card">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                        Notes
                    </h3>
                </div>
                <div class="card-body">
                    @if($transaction->description)
                    <div class="detail-item" style="margin-bottom:16px;">
                        <div class="detail-label">Description</div>
                        <div class="detail-value">{{ $transaction->description }}</div>
                    </div>
                    @endif
                    @if($transaction->internal_note)
                    <div class="detail-item">
                        <div class="detail-label">Internal Note</div>
                        <div class="detail-value">{{ $transaction->internal_note }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal-overlay" id="paymentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Payment</h3>
            <button class="modal-close" onclick="closePaymentModal()">&times;</button>
        </div>
        <form id="paymentForm" onsubmit="submitPayment(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Amount <span class="required">*</span></label>
                    <input type="number" name="amount" id="paymentAmount" class="form-input" step="0.01" min="0.01" required placeholder="0.00">
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                        @if($transaction->balance > 0)
                            Balance: {{ $transaction->formatted_balance }}
                        @else
                            <span style="color:var(--success);">✓ Fully Paid</span> (Extra contributions welcome!)
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Date <span class="required">*</span></label>
                    <input type="date" name="payment_date" id="paymentDate" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <div class="searchable-select" id="paymentMethodSelect">
                        <div class="ss-display" onclick="toggleDropdown('paymentMethodSelect')">
                            <span class="ss-text">Select Method</span>
                            <span class="ss-arrow">▼</span>
                        </div>
                        <div class="ss-dropdown">
                            <div class="ss-search">
                                <input type="text" placeholder="Search method..." oninput="filterOptions('paymentMethodSelect', this.value)">
                            </div>
                            <div class="ss-options">
                                <div class="ss-option" data-value="" data-search="none select" onclick="selectOption('paymentMethodSelect', '', 'Select Method')">Select Method</div>
                                <div class="ss-option" data-value="cash" data-search="cash" onclick="selectOption('paymentMethodSelect', 'cash', 'Cash')">Cash</div>
                                <div class="ss-option" data-value="bank_transfer" data-search="bank transfer wire" onclick="selectOption('paymentMethodSelect', 'bank_transfer', 'Bank Transfer')">Bank Transfer</div>
                                <div class="ss-option" data-value="cheque" data-search="cheque check" onclick="selectOption('paymentMethodSelect', 'cheque', 'Cheque')">Cheque</div>
                                <div class="ss-option" data-value="upi" data-search="upi mobile" onclick="selectOption('paymentMethodSelect', 'upi', 'UPI')">UPI</div>
                                <div class="ss-option" data-value="online" data-search="online internet" onclick="selectOption('paymentMethodSelect', 'online', 'Online')">Online</div>
                                <div class="ss-option" data-value="card" data-search="card credit debit" onclick="selectOption('paymentMethodSelect', 'card', 'Card')">Card</div>
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" id="paymentMethod" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Reference Number</label>
                    <input type="text" name="reference_number" id="paymentReference" class="form-input" placeholder="UTR / Cheque No / Ref">
                </div>
                <div class="form-group">
                    <label class="form-label">Receipt Number</label>
                    <input type="text" name="receipt_number" id="paymentReceipt" class="form-input" placeholder="Receipt #">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" id="paymentNotes" class="form-input" rows="2" placeholder="Payment notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-back" onclick="closePaymentModal()" id="paymentCancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary" id="paymentSubmitBtn">
                    <svg class="btn-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;display:none;animation:spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    <span class="btn-text">Save Payment</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Email Preview Modal -->
@if($transaction->sponsor && $transaction->sponsor->email)
<div class="modal-overlay" id="emailModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff;">
            <h3 style="color:#fff; display:flex; align-items:center; gap:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                Send Email to Sponsor
            </h3>
            <button class="modal-close" onclick="closeEmailModal()" style="color:#fff;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 0;">
            <!-- Email Type Selector -->
            <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e5e7eb;">
                <label style="font-weight: 600; font-size: 13px; color: #374151; display: block; margin-bottom: 8px;">Email Type</label>
                <div style="display: flex; gap: 12px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 8px 16px; border: 2px solid #3b82f6; border-radius: 8px; background: #eff6ff;">
                        <input type="radio" name="email_type" value="reminder" checked onchange="updateEmailPreview()"> 
                        <span style="font-weight: 500;">Payment Reminder</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                        <input type="radio" name="email_type" value="thank_you" onchange="updateEmailPreview()"> 
                        <span style="font-weight: 500;">Thank You</span>
                    </label>
                </div>
            </div>

            <!-- Email Info -->
            <div style="padding: 16px 20px; background: #fff; border-bottom: 1px solid #e5e7eb;">
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 600; color: #6b7280; width: 60px; font-size: 13px;">To:</span>
                        <span style="color: #111827;">{{ $transaction->sponsor->email }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 600; color: #6b7280; width: 60px; font-size: 13px;">Subject:</span>
                        <span id="emailSubject" style="color: #111827; font-weight: 500;">Reminder: Payment Due on {{ $transaction->next_payment_due?->format('d M Y') ?? 'Not Set' }}</span>
                    </div>
                </div>
            </div>

            <!-- Email Preview -->
            <div style="padding: 20px; background: #f3f4f6;">
                <div style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Reminder Email Preview -->
                    <div id="reminderPreview">
                        <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); padding: 24px; text-align: center;">
                            <h2 style="color: #fff; margin: 0; font-size: 20px;">Payment Reminder</h2>
                        </div>
                        <div style="padding: 24px;">
                            <p style="font-size: 15px; color: #374151; margin: 0 0 16px 0;">Hello <strong>{{ $transaction->sponsor->name }}</strong>,</p>
                            
                            <p style="font-size: 14px; color: #4b5563; line-height: 1.6; margin: 0 0 20px 0;">
                                This is a reminder that your next payment for <strong>{{ $transaction->student_name ?? 'General Donation' }}</strong> is due on <strong>{{ $transaction->next_payment_due?->format('d M Y') ?? 'Not Set' }}</strong>.
                            </p>
                            
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin: 20px 0;">
                                <thead>
                                    <tr style="background: #f3f4f6;">
                                        <th style="padding: 10px; text-align: left; border: 1px solid #e5e7eb;">Sponsor</th>
                                        <th style="padding: 10px; text-align: left; border: 1px solid #e5e7eb;">Student</th>
                                        <th style="padding: 10px; text-align: right; border: 1px solid #e5e7eb;">Total</th>
                                        <th style="padding: 10px; text-align: right; border: 1px solid #e5e7eb;">Paid</th>
                                        <th style="padding: 10px; text-align: right; border: 1px solid #e5e7eb;">Balance</th>
                                        <th style="padding: 10px; text-align: left; border: 1px solid #e5e7eb;">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $transaction->sponsor->name }}</td>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $transaction->student_name ?? 'General Donation' }}</td>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: right; font-weight: 600;">{{ $transaction->currency }} {{ number_format($transaction->total_amount, 2) }}</td>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: right; color: #16a34a;">{{ $transaction->currency }} {{ number_format($transaction->amount_paid, 2) }}</td>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: right; font-weight: 600; color: #d97706;">{{ $transaction->currency }} {{ number_format(max(0, $transaction->total_amount - $transaction->amount_paid), 2) }}</td>
                                        <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $transaction->next_payment_due?->format('d M Y') ?? 'Not Set' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <p style="font-size: 14px; color: #4b5563; margin: 20px 0 0 0;">
                                Thank you for supporting <strong>87 Initiative</strong> in helping underprivileged children in Sri Lanka.
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center;">
                                <p style="margin: 0; color: #6b7280; font-size: 13px;"><strong>87 Initiative</strong></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thank You Email Preview -->
                    <div id="thankYouPreview" style="display: none;">
                        <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 24px; text-align: center;">
                            <h2 style="color: #fff; margin: 0; font-size: 20px;">✓ Thank You!</h2>
                        </div>
                        <div style="padding: 24px;">
                            <p style="font-size: 15px; color: #374151; margin: 0 0 16px 0;">Dear <strong>{{ $transaction->sponsor->name }}</strong>,</p>
                            
                            <p style="font-size: 14px; color: #4b5563; line-height: 1.6; margin: 0 0 20px 0;">
                                Thank you for your generous payment for <strong>{{ $transaction->student_name ?? 'General Donation' }}</strong>. Your support makes a significant difference!
                            </p>
                            
                            <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 16px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                                <table style="width: 100%; font-size: 14px;">
                                    <tr>
                                        <td style="padding: 4px 0; color: #6b7280;">Transaction:</td>
                                        <td style="padding: 4px 0; text-align: right; font-family: monospace; font-weight: 600;">{{ $transaction->transaction_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 4px 0; color: #6b7280;">Amount Paid:</td>
                                        <td style="padding: 4px 0; text-align: right; font-weight: 600; color: #059669;">{{ $transaction->currency }} {{ number_format($transaction->amount_paid, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 4px 0; color: #6b7280;">Status:</td>
                                        <td style="padding: 4px 0; text-align: right;"><span style="background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ ucfirst($transaction->status) }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <p style="font-size: 14px; color: #4b5563; margin: 20px 0 0 0;">
                                We are grateful for your continued support in helping underprivileged children in Sri Lanka achieve their educational dreams.
                            </p>
                            
                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center;">
                                <p style="margin: 0; color: #6b7280; font-size: 13px;">Warm regards,<br><strong>87 Initiative</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; color: #6b7280;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                This will send the email immediately
            </span>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-back" onclick="closeEmailModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendEmail()" id="sendEmailBtn" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                    Send Email
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Searchable Select Functions
function toggleDropdown(selectId) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display');
    var dropdown = container.querySelector('.ss-dropdown');
    var isOpen = dropdown.classList.contains('show');
    
    document.querySelectorAll('.ss-dropdown.show').forEach(d => {
        d.classList.remove('show');
        d.parentElement.querySelector('.ss-display').classList.remove('open');
    });
    
    if (!isOpen) {
        dropdown.classList.add('show');
        display.classList.add('open');
        var searchInput = dropdown.querySelector('.ss-search input');
        if (searchInput) setTimeout(() => searchInput.focus(), 50);
    }
}

function filterOptions(selectId, searchText) {
    var container = document.getElementById(selectId);
    var options = container.querySelectorAll('.ss-option');
    var hasResults = false;
    
    searchText = searchText.toLowerCase();
    options.forEach(opt => {
        var text = opt.getAttribute('data-search') || opt.textContent.toLowerCase();
        if (text.includes(searchText)) {
            opt.style.display = 'block';
            hasResults = true;
        } else {
            opt.style.display = 'none';
        }
    });

    var noResults = container.querySelector('.ss-no-results');
    if (!hasResults) {
        if (!noResults) {
            noResults = document.createElement('div');
            noResults.className = 'ss-no-results';
            noResults.textContent = 'No results found';
            container.querySelector('.ss-options').appendChild(noResults);
        }
        noResults.style.display = 'block';
    } else if (noResults) {
        noResults.style.display = 'none';
    }
}

function selectOption(selectId, value, text) {
    var container = document.getElementById(selectId);
    var display = container.querySelector('.ss-display .ss-text');
    var dropdown = container.querySelector('.ss-dropdown');
    var hiddenInput = container.querySelector('input[type="hidden"]');
    
    display.textContent = text;
    if (hiddenInput) hiddenInput.value = value;
    dropdown.classList.remove('show');
    container.querySelector('.ss-display').classList.remove('open');
    
    container.querySelectorAll('.ss-option').forEach(opt => opt.classList.remove('selected'));
    container.querySelector('.ss-option[data-value="' + value + '"]')?.classList.add('selected');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.searchable-select')) {
        document.querySelectorAll('.ss-dropdown.show').forEach(d => {
            d.classList.remove('show');
            d.parentElement.querySelector('.ss-display').classList.remove('open');
        });
    }
});

function openPaymentModal() {
    document.getElementById('paymentModal').classList.add('show');
    document.getElementById('paymentForm').reset();
    document.getElementById('paymentDate').value = '{{ date('Y-m-d') }}';
    // Reset payment method dropdown
    document.querySelector('#paymentMethodSelect .ss-text').textContent = 'Select Method';
    document.getElementById('paymentMethod').value = '';
    document.querySelectorAll('#paymentMethodSelect .ss-option').forEach(opt => opt.classList.remove('selected'));
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
    resetPaymentForm();
}

// Flag to prevent double submission
var isPaymentSubmitting = false;

function setPaymentLoading(loading) {
    var btn = document.getElementById('paymentSubmitBtn');
    var spinner = btn.querySelector('.btn-spinner');
    var text = btn.querySelector('.btn-text');
    var cancelBtn = document.getElementById('paymentCancelBtn');
    
    if (loading) {
        btn.disabled = true;
        cancelBtn.disabled = true;
        spinner.style.display = 'inline-block';
        text.textContent = 'Processing...';
        btn.style.opacity = '0.7';
        btn.style.cursor = 'not-allowed';
    } else {
        btn.disabled = false;
        cancelBtn.disabled = false;
        spinner.style.display = 'none';
        text.textContent = 'Save Payment';
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        isPaymentSubmitting = false;
    }
}

function resetPaymentForm() {
    document.getElementById('paymentForm').reset();
    document.getElementById('paymentDate').value = '{{ date("Y-m-d") }}';
    document.getElementById('paymentMethod').value = '';
    document.querySelector('#paymentMethodSelect .ss-text').textContent = 'Select Method';
    setPaymentLoading(false);
}

function submitPayment(e) {
    e.preventDefault();
    
    // Prevent double submission
    if (isPaymentSubmitting) {
        console.log('Payment already submitting, please wait...');
        return false;
    }
    
    // Set flag and show loading
    isPaymentSubmitting = true;
    setPaymentLoading(true);
    
    var formData = {
        transaction_id: {{ $transaction->id }},
        amount: document.getElementById('paymentAmount').value,
        payment_date: document.getElementById('paymentDate').value,
        payment_method: document.getElementById('paymentMethod').value,
        reference_number: document.getElementById('paymentReference').value,
        receipt_number: document.getElementById('paymentReceipt').value,
        notes: document.getElementById('paymentNotes').value,
    };
    
    fetch('{{ route("admin.studentsponsorship.payments.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Show success briefly then reload
            var btn = document.getElementById('paymentSubmitBtn');
            btn.querySelector('.btn-spinner').style.display = 'none';
            btn.querySelector('.btn-text').textContent = '✓ Saved!';
            btn.style.background = '#10b981';
            setTimeout(function() {
                location.reload();
            }, 500);
        } else {
            alert(d.message || 'Error saving payment');
            setPaymentLoading(false);
        }
    })
    .catch(err => {
        alert('Error saving payment');
        setPaymentLoading(false);
    });
}

function deletePayment(id) {
    if (!confirm('Delete this payment?')) return;
    
    fetch('{{ url("admin/studentsponsorship/payments") }}/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            location.reload();
        } else {
            alert(d.message || 'Error deleting payment');
        }
    });
}

// Email Modal Functions
function openEmailModal() {
    document.getElementById('emailModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    updateEmailPreview();
}

function closeEmailModal() {
    document.getElementById('emailModal').classList.remove('show');
    document.body.style.overflow = '';
}

function updateEmailPreview() {
    var emailType = document.querySelector('input[name="email_type"]:checked').value;
    var reminderPreview = document.getElementById('reminderPreview');
    var thankYouPreview = document.getElementById('thankYouPreview');
    var subjectEl = document.getElementById('emailSubject');
    
    // Update radio button styles
    document.querySelectorAll('input[name="email_type"]').forEach(function(radio) {
        var label = radio.closest('label');
        if (radio.checked) {
            label.style.border = '2px solid #3b82f6';
            label.style.background = '#eff6ff';
        } else {
            label.style.border = '1px solid #e5e7eb';
            label.style.background = '#fff';
        }
    });
    
    if (emailType === 'reminder') {
        reminderPreview.style.display = 'block';
        thankYouPreview.style.display = 'none';
        subjectEl.textContent = 'Reminder: Payment Due on {{ $transaction->next_payment_due?->format("d M Y") ?? "Not Set" }}';
    } else {
        reminderPreview.style.display = 'none';
        thankYouPreview.style.display = 'block';
        subjectEl.textContent = 'Thank You for Your Payment - {{ $transaction->transaction_number }}';
    }
}

function sendEmail() {
    var emailType = document.querySelector('input[name="email_type"]:checked').value;
    var btn = document.getElementById('sendEmailBtn');
    
    btn.disabled = true;
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;animation:spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg> Sending...';
    
    fetch('{{ route("admin.studentsponsorship.transactions.send-email", $transaction->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email_type: emailType })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg> Sent!';
            btn.style.background = '#10b981';
            setTimeout(function() {
                closeEmailModal();
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg> Send Email';
                btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            }, 1500);
        } else {
            alert(d.message || 'Error sending email');
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg> Send Email';
        }
    })
    .catch(err => {
        alert('Error sending email');
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg> Send Email';
    });
}

// Close modal on backdrop click
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});

@if($transaction->sponsor && $transaction->sponsor->email)
document.getElementById('emailModal').addEventListener('click', function(e) {
    if (e.target === this) closeEmailModal();
});
@endif
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
