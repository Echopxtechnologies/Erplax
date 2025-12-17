<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;color:white;font-size:18px;font-weight:700;">
                    {{ substr($customer->display_name, 0, 1) }}
                </div>
                <div>
                    <h1 style="margin:0;font-size:22px;font-weight:700;color:var(--text-primary);">
                        {{ $customer->display_name }}
                    </h1>
                    <p style="margin:0;font-size:13px;color:var(--text-muted);">
                        {{ $customer->isCompany() ? '🏢 Company Account' : '👤 Individual Account' }}
                    </p>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @if($customer->isCompany())
                    <a href="{{ route('admin.customers.contacts.create', $customer->id) }}" class="btn-modern btn-success">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add Contact
                    </a>
                @endif
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-modern btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this customer?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern btn-danger">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.customers.index') }}" class="btn-modern btn-light">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        /* Modern Button Styles */
        .btn-modern {
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:10px 18px;
            font-size:14px;
            font-weight:600;
            border-radius:10px;
            border:none;
            cursor:pointer;
            transition:all 0.2s ease;
            text-decoration:none;
            box-shadow:0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-modern:hover {
            transform:translateY(-2px);
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-success { background:#10b981; color:white; }
        .btn-success:hover { background:#059669; }
        .btn-primary { background:#3b82f6; color:white; }
        .btn-primary:hover { background:#2563eb; }
        .btn-danger { background:#ef4444; color:white; }
        .btn-danger:hover { background:#dc2626; }
        .btn-light { background:white; color:#64748b; border:1px solid #e2e8f0; }
        .btn-light:hover { background:#f8fafc; }

        /* Modern Card Grid */
        .detail-grid { 
            display:grid; 
            grid-template-columns:repeat(auto-fit,minmax(380px,1fr)); 
            gap:24px; 
            margin-bottom:24px; 
        }
        
        /* Modern Card Styles */
        .modern-card { 
            background:white;
            border:1px solid #e5e7eb;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
            transition:all 0.3s ease;
        }
        .modern-card:hover {
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            transform:translateY(-2px);
        }
        
        .card-header { 
            background:linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding:18px 24px;
            border-bottom:2px solid #e5e7eb;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .card-header-icon {
            width:32px;
            height:32px;
            border-radius:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:16px;
        }
        .card-header-title { 
            font-size:16px;
            font-weight:700;
            color:#1e293b;
            margin:0;
        }
        
        .card-body { 
            padding:24px; 
        }
        
        /* Info Rows */
        .info-row { 
            display:grid; 
            grid-template-columns:150px 1fr; 
            gap:16px; 
            padding:14px 0; 
            border-bottom:1px solid #f1f5f9;
            align-items:start;
        }
        .info-row:last-child { 
            border-bottom:none; 
        }
        .info-row:hover {
            background:#fafbfc;
            margin:0 -12px;
            padding:14px 12px;
            border-radius:8px;
        }
        
        .info-label { 
            font-size:13px;
            font-weight:600;
            color:#64748b;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }
        
        .info-value { 
            font-size:14px;
            color:#1e293b;
            font-weight:500;
            word-break:break-word;
        }
        .info-value a {
            color:#3b82f6;
            text-decoration:none;
            transition:color 0.2s;
        }
        .info-value a:hover {
            color:#2563eb;
            text-decoration:underline;
        }
        
        /* Status Badges */
        .status-badge { 
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            border-radius:20px;
            font-size:12px;
            font-weight:600;
            letter-spacing:0.3px;
        }
        .status-badge::before {
            content:'';
            width:6px;
            height:6px;
            border-radius:50%;
        }
        .status-badge-active { 
            background:#dcfce7;
            color:#15803d;
        }
        .status-badge-active::before {
            background:#15803d;
        }
        .status-badge-inactive { 
            background:#f1f5f9;
            color:#64748b;
        }
        .status-badge-inactive::before {
            background:#64748b;
        }
        
        /* Contacts Table */
        .contacts-section {
            background:white;
            border:1px solid #e5e7eb;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        
        .contacts-header {
            background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding:20px 24px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            color:white;
        }
        .contacts-title {
            font-size:18px;
            font-weight:700;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .contacts-count {
            background:rgba(255,255,255,0.2);
            padding:4px 12px;
            border-radius:12px;
            font-size:13px;
            font-weight:600;
        }
        
        .contacts-table { 
            width:100%;
            border-collapse:collapse;
        }
        .contacts-table thead {
            background:#f8fafc;
        }
        .contacts-table th { 
            padding:16px 20px;
            text-align:left;
            font-size:12px;
            font-weight:700;
            color:#64748b;
            text-transform:uppercase;
            letter-spacing:0.5px;
            border-bottom:2px solid #e5e7eb;
        }
        .contacts-table td { 
            padding:16px 20px;
            font-size:14px;
            color:#1e293b;
            border-bottom:1px solid #f1f5f9;
        }
        .contacts-table tbody tr {
            transition:all 0.2s ease;
        }
        .contacts-table tbody tr:hover { 
            background:#fafbfc;
        }
        .contacts-table tbody tr:last-child td {
            border-bottom:none;
        }
        
        /* Action Buttons in Table */
        .btn-actions { 
            display:flex;
            gap:6px;
            flex-wrap:wrap;
        }
        .btn-sm {
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
            border-radius:6px;
            border:none;
            cursor:pointer;
            transition:all 0.2s;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:4px;
        }
        .btn-sm:hover {
            transform:translateY(-1px);
            box-shadow:0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-sm.btn-light {
            background:#f1f5f9;
            color:#64748b;
        }
        .btn-sm.btn-light:hover {
            background:#e2e8f0;
            color:#475569;
        }
        .btn-sm.btn-primary {
            background:#3b82f6;
            color:white;
        }
        .btn-sm.btn-primary:hover {
            background:#2563eb;
        }
        .btn-sm.btn-danger {
            background:#ef4444;
            color:white;
        }
        .btn-sm.btn-danger:hover {
            background:#dc2626;
        }
        
        .btn-add-contact {
            background:white;
            color:#3b82f6;
            padding:8px 16px;
            border-radius:8px;
            font-weight:600;
            font-size:13px;
            display:inline-flex;
            align-items:center;
            gap:6px;
            text-decoration:none;
            transition:all 0.2s;
            border:2px solid white;
        }
        .btn-add-contact:hover {
            background:rgba(255,255,255,0.95);
            transform:translateY(-2px);
        }
        
        /* Empty State */
        .empty-state {
            padding:40px;
            text-align:center;
            color:#94a3b8;
            font-size:14px;
        }
        
        /* Responsive */
        @media(max-width:768px) { 
            .detail-grid { 
                grid-template-columns:1fr; 
            } 
            .info-row { 
                grid-template-columns:120px 1fr;
                gap:12px;
            }
            .btn-modern {
                padding:8px 14px;
                font-size:13px;
            }
        }
    </style>

    <!-- Information Cards Grid -->
    <div class="detail-grid">
        <!-- Customer/Company Information Card -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-header-icon" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                    {{ $customer->isCompany() ? '🏢' : '👤' }}
                </div>
                <h2 class="card-header-title">{{ $customer->isCompany() ? 'Company' : 'Customer' }} Information</h2>
            </div>
            <div class="card-body">
                @if($customer->isCompany())
                <div class="info-row">
                    <div class="info-label">Company</div>
                    <div class="info-value">{{ $customer->company }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">VAT</div>
                    <div class="info-value">{{ $customer->vat ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Website</div>
                    <div class="info-value">
                        @if($customer->website)
                            <a href="{{ $customer->website }}" target="_blank">{{ $customer->website }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $customer->name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value"><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></div>
                </div>

                @if($customer->phone)
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $customer->phone }}</div>
                </div>
                @endif

                @if($customer->designation)
                <div class="info-row">
                    <div class="info-label">Designation</div>
                    <div class="info-value">{{ $customer->designation }}</div>
                </div>
                @endif

                @if($customer->group_name)
                <div class="info-row">
                    <div class="info-label">Group</div>
                    <div class="info-value">{{ $customer->group_name }}</div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Type</div>
                    <div class="info-value">{!! $customer->type_badge !!}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($customer->isActive())
                            <span class="status-badge status-badge-active">Active</span>
                        @else
                            <span class="status-badge status-badge-inactive">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Address Card -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-header-icon" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);">
                    📍
                </div>
                <h2 class="card-header-title">Billing Address</h2>
            </div>
            <div class="card-body">
                @if($customer->billing_street)
                <div class="info-row">
                    <div class="info-label">Street</div>
                    <div class="info-value">{{ $customer->billing_street }}</div>
                </div>
                @endif

                @if($customer->billing_city)
                <div class="info-row">
                    <div class="info-label">City</div>
                    <div class="info-value">{{ $customer->billing_city }}</div>
                </div>
                @endif

                @if($customer->billing_state)
                <div class="info-row">
                    <div class="info-label">State</div>
                    <div class="info-value">{{ $customer->billing_state }}</div>
                </div>
                @endif

                @if($customer->billing_zip_code)
                <div class="info-row">
                    <div class="info-label">Zip Code</div>
                    <div class="info-value">{{ $customer->billing_zip_code }}</div>
                </div>
                @endif

                @if($customer->billing_country)
                <div class="info-row">
                    <div class="info-label">Country</div>
                    <div class="info-value">{{ $customer->billing_country }}</div>
                </div>
                @endif

                @if(!$customer->billing_street && !$customer->billing_city && !$customer->billing_state && !$customer->billing_zip_code && !$customer->billing_country)
                <div class="empty-state">
                    <svg style="width:48px;height:48px;margin:0 auto 12px;opacity:0.3;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p style="margin:0;">No billing address on file</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contacts Section (Only for Companies) -->
    @if($customer->isCompany() && $contacts)
    <div class="contacts-section">
        <div class="contacts-header">
            <div class="contacts-title">
                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Contacts</span>
                <span class="contacts-count">{{ $contacts->count() }}</span>
            </div>
            <a href="{{ route('admin.customers.contacts.create', $customer->id) }}" class="btn-add-contact">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                Add Contact
            </a>
        </div>
        <table class="contacts-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <td><strong>{{ $contact->name }}</strong></td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td>{{ $contact->designation ?? '-' }}</td>
                    <td>
                        @if($contact->isActive())
                            <span class="status-badge status-badge-active">Active</span>
                        @else
                            <span class="status-badge status-badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-actions">
                            <a href="{{ route('admin.customers.show', $contact->id) }}" class="btn-sm btn-light">View</a>
                            <a href="{{ route('admin.contacts.edit', $contact->id) }}" class="btn-sm btn-primary">Edit</a>
                            @if($contacts->count() > 1)
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this contact?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</x-layouts.app>