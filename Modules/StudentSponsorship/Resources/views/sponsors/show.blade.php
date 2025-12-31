<style>
    .detail-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .page-actions { display: flex; gap: 12px; }
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; border: none; cursor: pointer; }
    .btn svg { width: 18px; height: 18px; }
    .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); color: #fff; }
    .btn-secondary { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text-secondary); }
    .btn-secondary:hover { background: var(--body-bg); color: var(--text-primary); }
    
    .sponsor-header { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 20px; }
    .sponsor-avatar { width: 80px; height: 80px; background: linear-gradient(135deg, #ec4899, #f472b6); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 32px; font-weight: 700; flex-shrink: 0; }
    .sponsor-info h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0; }
    .sponsor-meta { display: flex; gap: 16px; flex-wrap: wrap; }
    .sponsor-meta span { font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; }
    .sponsor-meta svg { width: 16px; height: 16px; }
    .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-active { background: #dcfce7; color: #16a34a; }
    .badge-inactive { background: #f3f4f6; color: #6b7280; }
    .badge-individual { background: #f3e8ff; color: #9333ea; }
    .badge-company { background: #fef3c7; color: #d97706; }
    
    .detail-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 20px; }
    .detail-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; align-items: center; gap: 10px; }
    .detail-card-header svg { width: 20px; height: 20px; color: var(--primary); }
    .detail-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); }
    .detail-card-body { padding: 20px; }
    
    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .detail-item { }
    .detail-label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .detail-value { font-size: 15px; color: var(--text-primary); }
    .detail-value.empty { color: var(--text-muted); font-style: italic; }
    
    .alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
    .alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #16a34a; }
    .alert svg { width: 20px; height: 20px; flex-shrink: 0; }
    
    .meta-footer { padding: 16px 20px; background: var(--body-bg); border-top: 1px solid var(--card-border); border-radius: 0 0 12px 12px; font-size: 12px; color: var(--text-muted); display: flex; gap: 20px; }
</style>

<div class="detail-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            Sponsor Details
        </h1>
        <div class="page-actions">
            <a href="{{ route('admin.studentsponsorship.sponsors.edit', $sponsor->id) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.studentsponsorship.sponsors.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Sponsor Header -->
    <div class="sponsor-header">
        <div class="sponsor-avatar">
            {{ strtoupper(substr($sponsor->name, 0, 1)) }}
        </div>
        <div class="sponsor-info">
            <h2>{{ $sponsor->name }}</h2>
            <div class="sponsor-meta">
                @if($sponsor->sponsor_internal_id)
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        {{ $sponsor->sponsor_internal_id }}
                    </span>
                @endif
                <span class="badge {{ $sponsor->sponsor_type == 'company' ? 'badge-company' : 'badge-individual' }}">
                    {{ ucfirst($sponsor->sponsor_type) }}
                </span>
                <span class="badge {{ $sponsor->active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $sponsor->active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Basic Info -->
    <div class="detail-card">
        <div class="detail-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span class="detail-card-title">Basic Information</span>
        </div>
        <div class="detail-card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Occupation</div>
                    <div class="detail-value {{ !$sponsor->sponsor_occupation ? 'empty' : '' }}">{{ $sponsor->sponsor_occupation ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value {{ !$sponsor->email ? 'empty' : '' }}">
                        @if($sponsor->email)
                            <a href="mailto:{{ $sponsor->email }}" style="color: var(--primary);">{{ $sponsor->email }}</a>
                        @else
                            Not specified
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value {{ !$sponsor->contact_no ? 'empty' : '' }}">
                        @if($sponsor->contact_no)
                            <a href="tel:{{ $sponsor->contact_no }}" style="color: var(--primary);">{{ $sponsor->contact_no }}</a>
                        @else
                            Not specified
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Country</div>
                    <div class="detail-value {{ !$sponsor->country ? 'empty' : '' }}">{{ $sponsor->country?->short_name ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">City</div>
                    <div class="detail-value {{ !$sponsor->city ? 'empty' : '' }}">{{ $sponsor->city ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Postal Code</div>
                    <div class="detail-value {{ !$sponsor->zip ? 'empty' : '' }}">{{ $sponsor->zip ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Address</div>
                    <div class="detail-value {{ !$sponsor->address ? 'empty' : '' }}">{{ $sponsor->address ?? 'Not specified' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banking -->
    <div class="detail-card">
        <div class="detail-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
            </svg>
            <span class="detail-card-title">Banking Details</span>
        </div>
        <div class="detail-card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Bank</div>
                    <div class="detail-value {{ !$sponsor->bank_name ? 'empty' : '' }}">{{ $sponsor->bank_name ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Branch Name</div>
                    <div class="detail-value {{ !$sponsor->sponsor_bank_branch_info ? 'empty' : '' }}">{{ $sponsor->sponsor_bank_branch_info ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Branch/IFSC Code</div>
                    <div class="detail-value {{ !$sponsor->sponsor_bank_branch_number ? 'empty' : '' }}">{{ $sponsor->sponsor_bank_branch_number ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Account Number</div>
                    <div class="detail-value {{ !$sponsor->sponsor_bank_account_no ? 'empty' : '' }}">{{ $sponsor->sponsor_bank_account_no ?? 'Not specified' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsorship -->
    <div class="detail-card">
        <div class="detail-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            <span class="detail-card-title">Sponsorship Details</span>
        </div>
        <div class="detail-card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Start Date</div>
                    <div class="detail-value {{ !$sponsor->membership_start_date ? 'empty' : '' }}">{{ $sponsor->membership_start_date?->format('d M Y') ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">End Date</div>
                    <div class="detail-value {{ !$sponsor->membership_end_date ? 'empty' : '' }}">{{ $sponsor->membership_end_date?->format('d M Y') ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Frequency</div>
                    <div class="detail-value {{ !$sponsor->sponsor_frequency ? 'empty' : '' }}">{{ $sponsor->frequency_display ?? 'Not specified' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $sponsor->active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $sponsor->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @if($sponsor->background_info || $sponsor->internal_comment || $sponsor->external_comment)
        <div class="detail-card-body" style="border-top: 1px solid var(--card-border);">
            <div class="detail-grid">
                @if($sponsor->background_info)
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Background Information</div>
                    <div class="detail-value">{{ $sponsor->background_info }}</div>
                </div>
                @endif
                @if($sponsor->internal_comment)
                <div class="detail-item">
                    <div class="detail-label">Internal Comment</div>
                    <div class="detail-value">{{ $sponsor->internal_comment }}</div>
                </div>
                @endif
                @if($sponsor->external_comment)
                <div class="detail-item">
                    <div class="detail-label">External Comment</div>
                    <div class="detail-value">{{ $sponsor->external_comment }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        <div class="meta-footer">
            <span>Created: {{ $sponsor->created_at?->format('d M Y, h:i A') ?? '-' }}</span>
            @if($sponsor->updated_at && $sponsor->updated_at != $sponsor->created_at)
                <span>Updated: {{ $sponsor->updated_at->format('d M Y, h:i A') }}</span>
            @endif
        </div>
    </div>

    <!-- Sponsored Students Section -->
    @php
        $sponsoredStudents = $sponsor->sponsored_students_list;
    @endphp
    
    <div class="detail-card">
        <div class="detail-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
            </svg>
            <span class="detail-card-title">Sponsored Students ({{ count($sponsoredStudents) }})</span>
        </div>
        <div class="detail-card-body">
            @if(count($sponsoredStudents) > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <thead>
                            <tr style="background: var(--body-bg); border-bottom: 1px solid var(--card-border);">
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Student</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Type</th>
                                <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Paid</th>
                                <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Balance</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Status</th>
                                <th style="padding: 12px; text-align: center; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sponsoredStudents as $student)
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 12px;">
                                    @if($student['type'] == 'School' && isset($student['hash_id']))
                                        <a href="{{ route('admin.studentsponsorship.school-students.show', $student['hash_id']) }}" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                            {{ $student['name'] }}
                                        </a>
                                    @elseif($student['type'] == 'University' && isset($student['hash_id']))
                                        <a href="{{ route('admin.studentsponsorship.university-students.show', $student['hash_id']) }}" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                            {{ $student['name'] }}
                                        </a>
                                    @else
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $student['name'] }}</div>
                                    @endif
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $student['id'] }}</div>
                                </td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; background: {{ $student['type'] == 'School' ? '#dbeafe' : '#f3e8ff' }}; color: {{ $student['type'] == 'School' ? '#2563eb' : '#9333ea' }};">
                                        {{ $student['type'] }}
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: 600; color: var(--success);">
                                    {{ $student['currency_symbol'] }}{{ number_format($student['amount_paid'], 2) }}
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: 600; color: {{ $student['balance'] > 0 ? '#d97706' : 'var(--success)' }};">
                                    {{ $student['currency_symbol'] }}{{ number_format($student['balance'], 2) }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($student['status'] == 'completed')
                                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #16a34a;">Completed</span>
                                    @else
                                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dbeafe; color: #2563eb;">Partial</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <a href="{{ route('admin.studentsponsorship.transactions.show', $student['transaction_id']) }}" style="color: var(--primary); text-decoration: none; font-size: 13px; font-weight: 500;">
                                        View →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <p style="margin: 0;">No students sponsored yet</p>
                    <a href="{{ route('admin.studentsponsorship.transactions.create', ['sponsor_id' => $sponsor->id]) }}" style="display: inline-block; margin-top: 12px; padding: 8px 16px; background: var(--primary); color: #fff; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        + Create Transaction
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Portal Access -->
    <div class="detail-card">
        <div class="detail-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
            <span class="detail-card-title">Portal Access</span>
        </div>
        <div class="detail-card-body">
            @if($sponsor->staff_id)
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1;">
                        <div class="detail-item">
                            <div class="detail-label">Portal Status</div>
                            <div class="detail-value">
                                <span class="badge badge-active">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Portal Access Enabled
                                </span>
                            </div>
                        </div>
                        <div class="detail-item" style="margin-top: 12px;">
                            <div class="detail-label">Login Email</div>
                            <div class="detail-value">{{ $sponsor->portal_email ?? $sponsor->staff?->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="showResetPasswordModal()" class="btn btn-secondary" style="font-size: 13px; padding: 8px 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Reset Password
                        </button>
                        <button type="button" onclick="disablePortalAccess()" class="btn" style="background: #fee2e2; color: #dc2626; font-size: 13px; padding: 8px 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Disable Access
                        </button>
                    </div>
                </div>
            @else
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1;">
                        <div class="detail-item">
                            <div class="detail-label">Portal Status</div>
                            <div class="detail-value">
                                <span class="badge badge-inactive">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    No Portal Access
                                </span>
                            </div>
                        </div>
                        <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                            Enable portal access to allow this sponsor to login to the staff portal.
                        </p>
                    </div>
                    <button type="button" onclick="showEnablePortalModal()" class="btn btn-primary" style="font-size: 13px; padding: 8px 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Enable Portal Access
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Enable Portal Modal -->
<div id="enablePortalModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--card-bg); border-radius: 12px; padding: 24px; max-width: 400px; width: 90%; margin: 20px;">
        <h3 style="margin: 0 0 16px; font-size: 18px; font-weight: 600; color: var(--text-primary);">Enable Portal Access</h3>
        <form id="enablePortalForm">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">Login Email *</label>
                <input type="email" name="login_email" value="{{ $sponsor->email }}" required 
                       style="width: 100%; padding: 10px 12px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 14px; background: var(--input-bg); color: var(--input-text);">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">Password *</label>
                <input type="password" name="password" required minlength="6"
                       style="width: 100%; padding: 10px 12px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 14px; background: var(--input-bg); color: var(--input-text);">
                <small style="color: var(--text-muted); font-size: 11px;">Minimum 6 characters</small>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal('enablePortalModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Enable Access</button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--card-bg); border-radius: 12px; padding: 24px; max-width: 400px; width: 90%; margin: 20px;">
        <h3 style="margin: 0 0 16px; font-size: 18px; font-weight: 600; color: var(--text-primary);">Reset Portal Password</h3>
        <form id="resetPasswordForm">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;">New Password *</label>
                <input type="password" name="password" required minlength="6"
                       style="width: 100%; padding: 10px 12px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 14px; background: var(--input-bg); color: var(--input-text);">
                <small style="color: var(--text-muted); font-size: 11px;">Minimum 6 characters</small>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal('resetPasswordModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<script>
function showEnablePortalModal() {
    document.getElementById('enablePortalModal').style.display = 'flex';
}

function showResetPasswordModal() {
    document.getElementById('resetPasswordModal').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Enable Portal Form Submit
document.getElementById('enablePortalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    fetch('{{ route("admin.studentsponsorship.sponsors.enable-portal", $sponsor->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            login_email: formData.get('login_email'),
            password: formData.get('password')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to enable portal access');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
});

// Reset Password Form Submit
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    
    fetch('{{ route("admin.studentsponsorship.sponsors.reset-password", $sponsor->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            password: formData.get('password')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal('resetPasswordModal');
            this.reset();
        } else {
            alert(data.message || 'Failed to reset password');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
});

// Disable Portal Access
function disablePortalAccess() {
    if (!confirm('Are you sure you want to disable portal access for this sponsor? They will no longer be able to login.')) {
        return;
    }
    
    fetch('{{ route("admin.studentsponsorship.sponsors.disable-portal", $sponsor->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to disable portal access');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

// Close modal on backdrop click
document.getElementById('enablePortalModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('enablePortalModal');
});
document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('resetPasswordModal');
});
</script>
