@include('purchase::partials.styles')

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.purchase.vendors.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1>
                {{ $vendor->name }}
                <span class="badge badge-{{ strtolower($vendor->status) }} badge-lg">{{ $vendor->status }}</span>
            </h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.purchase.vendors.edit', $vendor->id) }}" class="btn btn-outline">✏️ Edit</a>
            <a href="{{ route('admin.purchase.vendors.index') }}" class="btn btn-outline">📋 All Vendors</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid-2">
        <!-- Basic Info -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">🏢 Basic Information</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Vendor Code</div><div class="detail-value"><strong>{{ $vendor->vendor_code }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">Vendor Name</div><div class="detail-value">{{ $vendor->name }}</div></div>
                <div class="detail-row"><div class="detail-label">Display Name</div><div class="detail-value">{{ $vendor->display_name ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Contact Person</div><div class="detail-value">{{ $vendor->contact_person ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Email</div><div class="detail-value">{{ $vendor->email ? '<a href="mailto:'.$vendor->email.'">'.$vendor->email.'</a>' : '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">{{ $vendor->phone ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Mobile</div><div class="detail-value">{{ $vendor->mobile ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Website</div><div class="detail-value">{!! $vendor->website ? '<a href="'.$vendor->website.'" target="_blank">'.$vendor->website.'</a>' : '-' !!}</div></div>
            </div>
        </div>

        <!-- Tax Info -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📑 Tax Information</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">GST Type</div><div class="detail-value"><span class="badge badge-info">{{ $vendor->gst_type }}</span></div></div>
                <div class="detail-row"><div class="detail-label">GST Number</div><div class="detail-value"><strong>{{ $vendor->gst_number ?? '-' }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">PAN Number</div><div class="detail-value">{{ $vendor->pan_number ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Billing Address -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📍 Billing Address</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">{{ $vendor->billing_address ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">City</div><div class="detail-value">{{ $vendor->billing_city ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">State</div><div class="detail-value">{{ $vendor->billing_state ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">PIN Code</div><div class="detail-value">{{ $vendor->billing_pincode ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Country</div><div class="detail-value">{{ $vendor->billing_country ?? 'India' }}</div></div>
            </div>
        </div>

        <!-- Payment Terms -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">💰 Payment Terms</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Payment Terms</div><div class="detail-value">{{ $vendor->payment_terms ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Credit Days</div><div class="detail-value">{{ $vendor->credit_days }} days</div></div>
                <div class="detail-row"><div class="detail-label">Credit Limit</div><div class="detail-value">₹{{ number_format($vendor->credit_limit, 2) }}</div></div>
                <div class="detail-row"><div class="detail-label">Opening Balance</div><div class="detail-value">₹{{ number_format($vendor->opening_balance, 2) }}</div></div>
            </div>
        </div>
    </div>

    @if($vendor->bankDetail)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">🏦 Bank Details</h5>
        </div>
        <div class="detail-card-body">
            <div class="grid-4">
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Account Holder</div><div class="detail-value"><strong>{{ $vendor->bankDetail->account_holder_name ?? '-' }}</strong></div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Bank Name</div><div class="detail-value">{{ $vendor->bankDetail->bank_name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Account Number</div><div class="detail-value"><strong>{{ $vendor->bankDetail->account_number ?? '-' }}</strong></div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">IFSC Code</div><div class="detail-value">{{ $vendor->bankDetail->ifsc_code ?? '-' }}</div></div>
            </div>
            <div class="grid-4" style="margin-top:12px;">
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Branch</div><div class="detail-value">{{ $vendor->bankDetail->branch_name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Account Type</div><div class="detail-value">{{ $vendor->bankDetail->account_type ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">UPI ID</div><div class="detail-value">{{ $vendor->bankDetail->upi_id ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">SWIFT Code</div><div class="detail-value">{{ $vendor->bankDetail->swift_code ?? '-' }}</div></div>
            </div>
        </div>
    </div>
    @endif

    @if($vendor->notes)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📝 Notes</h5>
        </div>
        <div class="detail-card-body">
            <p style="margin:0;white-space:pre-wrap;color:var(--text-primary);">{{ $vendor->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Audit Info -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">🕐 Audit Information</h5>
        </div>
        <div class="detail-card-body">
            <div class="grid-4">
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created By</div><div class="detail-value">{{ $vendor->creator->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $vendor->created_at->format('d M Y, h:i A') }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Updated At</div><div class="detail-value">{{ $vendor->updated_at->format('d M Y, h:i A') }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Status</div><div class="detail-value"><span class="badge badge-{{ strtolower($vendor->status) }}">{{ $vendor->status }}</span></div></div>
            </div>
        </div>
    </div>
</div>
