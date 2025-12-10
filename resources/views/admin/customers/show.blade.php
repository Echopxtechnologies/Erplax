<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Customer Details</h1>
    </x-slot>

    <style>
        .cform { max-width:100%; }
        .ccard { background:var(--card-bg, #fff); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.08); margin-bottom:16px; border:1px solid var(--card-border, #e5e7eb); }
        .ccard-h { background:var(--body-bg, #f8f9fa); padding:14px 20px; border-bottom:1px solid var(--card-border, #e5e7eb); font-size:15px; font-weight:600; color:var(--text-primary, #374151); display:flex; justify-content:space-between; align-items:center; }
        .ccard-b { padding:20px; }
        .frow { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .frow3 { grid-template-columns:repeat(3,1fr); }
        .frow4 { grid-template-columns:repeat(4,1fr); }
        .frow:last-child { margin-bottom:0; }
        .fcol { min-width:0; }
        .fcol-full { grid-column:1/-1; }
        .flbl { display:block; font-size:12px; font-weight:500; color:var(--text-muted, #6b7280); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px; }
        .fval { font-size:15px; color:var(--text-primary, #1f2937); padding:8px 0; min-height:20px; }
        .fval.empty { color:var(--text-muted, #9ca3af); font-style:italic; }
        .fval a { color:var(--primary, #3b82f6); text-decoration:none; }
        .fval a:hover { text-decoration:underline; }
        .badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-blue { background:#dbeafe; color:#1d4ed8; }
        .badge-green { background:#dcfce7; color:#16a34a; }
        .badge-gray { background:var(--body-bg, #f3f4f6); color:var(--text-muted, #4b5563); }
        .tabs { display:flex; background:var(--body-bg, #f8f9fa); }
        .tab { padding:12px 24px; background:transparent; border:none; border-bottom:2px solid transparent; cursor:pointer; font-size:14px; color:var(--text-muted, #6b7280); }
        .tab.active { color:var(--primary, #3b82f6); background:var(--card-bg, #fff); border-bottom-color:var(--primary, #3b82f6); }
        .btn { padding:10px 20px; font-size:14px; font-weight:500; border-radius:6px; cursor:pointer; border:none; text-decoration:none; display:inline-flex; align-items:center; }
        .btn-p { background:var(--primary, #3b82f6); color:#fff; }
        .btn-p:hover { opacity:0.9; }
        .btn-l { background:var(--card-bg, #f3f4f6); color:var(--text-primary, #374151); border:1px solid var(--card-border, #d1d5db); }
        .btn-l:hover { background:var(--body-bg, #e5e7eb); }
        .btn-danger { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
        .btn-danger:hover { background:#fecaca; }
        .info-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:24px; }
        .customer-header { display:flex; align-items:center; gap:16px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid var(--card-border, #e5e7eb); }
        .customer-avatar { width:64px; height:64px; border-radius:50%; background:var(--primary, #3b82f6); color:#fff; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:600; }
        .customer-info h2 { margin:0 0 4px 0; font-size:22px; color:var(--text-primary, #1f2937); }
        .customer-info p { margin:0; color:var(--text-muted, #6b7280); font-size:14px; }
        .address-box { background:var(--body-bg, #f9fafb); border-radius:8px; padding:16px; border:1px solid var(--card-border, #e5e7eb); }
        .address-box h4 { margin:0 0 12px 0; font-size:14px; color:var(--text-primary, #374151); display:flex; align-items:center; gap:8px; }
        .address-line { color:var(--text-secondary, #4b5563); font-size:14px; line-height:1.6; }
        .notes-box { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:16px; }
        .notes-box p { margin:0; color:#92400e; font-size:14px; line-height:1.6; white-space:pre-wrap; }
        .page-title-text { margin:0; font-size:22px; color:var(--text-primary, #1f2937); }
        
        @media(max-width:1024px) { .frow4 { grid-template-columns:1fr 1fr; } .info-grid { grid-template-columns:1fr; } }
        @media(max-width:768px) { .frow,.frow3,.frow4 { grid-template-columns:1fr; } .members-grid { grid-template-columns:1fr; } }
        
        .members-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
        .member-card { display:flex; align-items:center; gap:12px; padding:12px 16px; background:var(--body-bg, #f9fafb); border:1px solid var(--card-border, #e5e7eb); border-radius:8px; text-decoration:none; transition:all 0.2s; }
        .member-card:hover { background:var(--primary-light, #eff6ff); border-color:var(--primary, #3b82f6); transform:translateY(-2px); box-shadow:0 4px 6px rgba(0,0,0,0.05); }
        .member-avatar { width:42px; height:42px; border-radius:50%; background:#6366f1; color:#fff; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:600; flex-shrink:0; }
        .member-info { min-width:0; }
        .member-name { font-weight:600; color:var(--text-primary, #1f2937); font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .member-detail { font-size:12px; color:var(--text-muted, #6b7280); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Dark mode overrides */
        .dark .ccard, [data-theme="dark"] .ccard { background:#1e293b; border-color:#334155; }
        .dark .ccard-h, [data-theme="dark"] .ccard-h { background:#0f172a; border-color:#334155; color:#f1f5f9; }
        .dark .ccard-b, [data-theme="dark"] .ccard-b { background:#1e293b; }
        .dark .flbl, [data-theme="dark"] .flbl { color:#94a3b8; }
        .dark .fval, [data-theme="dark"] .fval { color:#f1f5f9; }
        .dark .fval.empty, [data-theme="dark"] .fval.empty { color:#64748b; }
        .dark .customer-info h2, [data-theme="dark"] .customer-info h2 { color:#f1f5f9; }
        .dark .customer-info p, [data-theme="dark"] .customer-info p { color:#94a3b8; }
        .dark .customer-header, [data-theme="dark"] .customer-header { border-color:#334155; }
        .dark .badge-blue, [data-theme="dark"] .badge-blue { background:#1e3a5f; color:#93c5fd; }
        .dark .badge-green, [data-theme="dark"] .badge-green { background:#14532d; color:#86efac; }
        .dark .badge-gray, [data-theme="dark"] .badge-gray { background:#334155; color:#94a3b8; }
        .dark .address-box, [data-theme="dark"] .address-box { background:#0f172a; border-color:#334155; }
        .dark .address-box h4, [data-theme="dark"] .address-box h4 { color:#f1f5f9; }
        .dark .address-line, [data-theme="dark"] .address-line { color:#cbd5e1; }
        .dark .notes-box, [data-theme="dark"] .notes-box { background:#422006; border-color:#854d0e; }
        .dark .notes-box p, [data-theme="dark"] .notes-box p { color:#fbbf24; }
        .dark .btn-l, [data-theme="dark"] .btn-l { background:#334155; color:#f1f5f9; border-color:#475569; }
        .dark .btn-l:hover, [data-theme="dark"] .btn-l:hover { background:#475569; }
        .dark .btn-danger, [data-theme="dark"] .btn-danger { background:#450a0a; color:#fca5a5; border-color:#7f1d1d; }
        .dark .member-card, [data-theme="dark"] .member-card { background:#0f172a; border-color:#334155; }
        .dark .member-card:hover, [data-theme="dark"] .member-card:hover { background:#1e3a5f; border-color:#3b82f6; }
        .dark .member-name, [data-theme="dark"] .member-name { color:#f1f5f9; }
        .dark .member-detail, [data-theme="dark"] .member-detail { color:#94a3b8; }
        .dark .page-title-text, [data-theme="dark"] .page-title-text { color:#f1f5f9; }
    </style>

    <div class="cform">
        {{-- Page Header with Back Button --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-l">← Back to Customers</a>
                <h1 class="page-title-text">Customer Details</h1>
            </div>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-p">✏️ Edit Customer</a>
        </div>

        {{-- Customer Header --}}
        <div class="ccard">
            <div class="ccard-b">
                <div class="customer-header">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                    </div>
                    <div class="customer-info">
                        <h2>{{ $customer->name }}</h2>
                        <p>{{ $customer->email }}</p>
                    </div>
                    <div style="margin-left:auto;">
                        <span class="badge {{ $customer->customer_type === 'company' ? 'badge-blue' : 'badge-green' }}">
                            {{ ucfirst($customer->customer_type ?? 'Individual') }}
                        </span>
                        @if($customer->group_name)
                            <span class="badge badge-gray">{{ $customer->group_name }}</span>
                        @endif
                    </div>
                </div>

                <div class="frow frow4">
                    <div class="fcol">
                        <div class="flbl">Phone</div>
                        <div class="fval {{ !$customer->phone ? 'empty' : '' }}">
                            @if($customer->phone)
                                <a href="tel:{{ $customer->phone }}" style="color:#3b82f6;text-decoration:none;">{{ $customer->phone }}</a>
                            @else
                                Not provided
                            @endif
                        </div>
                    </div>
                    <div class="fcol">
                        <div class="flbl">Email</div>
                        <div class="fval">
                            <a href="mailto:{{ $customer->email }}" style="color:#3b82f6;text-decoration:none;">{{ $customer->email }}</a>
                        </div>
                    </div></br>
                    <div class="fcol">
                        <div class="flbl">Group</div>
                        <div class="fval {{ !$customer->group_name ? 'empty' : '' }}">{{ $customer->group_name ?: 'No group' }}</div>
                    </div>
                    <div class="fcol">
                        <div class="flbl">Created</div>
                        <div class="fval">{{ $customer->created_at?->format('M d, Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Company Details (only if company type) --}}
        @if($customer->customer_type === 'company')
        <div class="ccard">
            <div class="ccard-h">🏢 Company Details</div>
            <div class="ccard-b">
                <div class="frow frow4">
                    <div class="fcol">
                        <div class="flbl">Company Name</div>
                        <div class="fval {{ !$customer->company ? 'empty' : '' }}">{{ $customer->company ?: 'Not provided' }}</div>
                    </div>
                    <div class="fcol">
                        <div class="flbl">Designation</div>
                        <div class="fval {{ !$customer->designation ? 'empty' : '' }}">{{ $customer->designation ?: 'Not provided' }}</div>
                    </div>
                    <div class="fcol">
                        <div class="flbl">Website</div>
                        <div class="fval {{ !$customer->website ? 'empty' : '' }}">
                            @if($customer->website)
                                <a href="{{ $customer->website }}" target="_blank" style="color:#3b82f6;text-decoration:none;">{{ $customer->website }}</a>
                            @else
                                Not provided
                            @endif
                        </div>
                    </div>
                    <div class="fcol">
                        <div class="flbl">GST Number</div>
                        <div class="fval {{ !$customer->gst_number ? 'empty' : '' }}">{{ $customer->gst_number ?: 'Not provided' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Company Members --}}
        @if($customer->company && isset($companyMembers) && $companyMembers->count() > 0)
        <div class="ccard">
            <div class="ccard-h">
                👥 Company Members
                <span class="badge badge-blue">{{ $companyMembers->count() }}</span>
            </div>
            <div class="ccard-b">
                <div class="members-grid">
                    @foreach($companyMembers as $member)
                    <a href="{{ route('admin.customers.show', $member->id) }}" class="member-card">
                        <div class="member-avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                        <div class="member-info">
                            <div class="member-name">{{ $member->name }}</div>
                            <div class="member-detail">{{ $member->designation ?: 'No designation' }}</div>
                            <div class="member-detail">{{ $member->email }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @endif

        {{-- Addresses --}}
        <div class="ccard">
            <div class="ccard-h">📍 Addresses</div>
            <div class="ccard-b">
                <div class="info-grid">
                    {{-- Billing Address --}}
                    <div class="address-box">
                        <h4>🏠 Billing Address</h4>
                        @if($customer->address || $customer->city || $customer->state || $customer->country)
                            <div class="address-line">
                                @if($customer->address){{ $customer->address }}<br>@endif
                                @if($customer->city || $customer->state || $customer->zip_code)
                                    {{ implode(', ', array_filter([$customer->city, $customer->state, $customer->zip_code])) }}<br>
                                @endif
                                @if($customer->country){{ $customer->country }}@endif
                            </div>
                        @else
                            <div class="fval empty">No billing address provided</div>
                        @endif
                    </div>

                    {{-- Shipping Address --}}
                    <div class="address-box">
                        <h4>🚚 Shipping Address</h4>
                        @if($customer->shipping_address || $customer->shipping_city || $customer->shipping_state || $customer->shipping_country)
                            <div class="address-line">
                                @if($customer->shipping_address){{ $customer->shipping_address }}<br>@endif
                                @if($customer->shipping_city || $customer->shipping_state || $customer->shipping_zip_code)
                                    {{ implode(', ', array_filter([$customer->shipping_city, $customer->shipping_state, $customer->shipping_zip_code])) }}<br>
                                @endif
                                @if($customer->shipping_country){{ $customer->shipping_country }}@endif
                            </div>
                        @else
                            <div class="fval empty">No shipping address provided</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($customer->notes)
        <div class="ccard">
            <div class="ccard-h">📝 Notes</div>
            <div class="ccard-b">
                <div class="notes-box">
                    <p>{{ $customer->notes }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="ccard">
            <div class="ccard-h">⚡ Quick Actions</div>
            <div class="ccard-b">
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-p">✏️ Edit Customer</a>
                    {{-- <a href="mailto:{{ $customer->email }}" class="btn btn-l">📧 Send Email</a> --}}
                    @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="btn btn-l">📞 Call</a>
                    @endif
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>