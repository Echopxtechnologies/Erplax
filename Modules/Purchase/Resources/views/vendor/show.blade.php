<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header-left { display: flex; align-items: center; gap: 12px; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.page-header-right { display: flex; gap: 10px; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; color: #374151; }

.badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.badge-success { background: #dcfce7; color: #166534; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }

.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }

.info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.info-item { }
.info-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value { font-size: 15px; color: #1f2937; font-weight: 500; }
.info-value.highlight { font-size: 18px; color: #4f46e5; }

@media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } .info-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $vendor->name }}</h1>
        <span class="badge badge-{{ $vendor->status == 'ACTIVE' ? 'success' : ($vendor->status == 'INACTIVE' ? 'warning' : 'danger') }}">{{ $vendor->status }}</span>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.purchase.vendors.edit', $vendor->id) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit
        </a>
        <a href="{{ route('admin.purchase.vendors.index') }}" class="btn btn-outline">Back</a>
    </div>
</div>

<div class="detail-grid">
    <div>
        <div class="card">
            <div class="card-header"><h5>Basic Information</h5></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Vendor Code</div>
                        <div class="info-value highlight">{{ $vendor->vendor_code }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $vendor->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact Person</div>
                        <div class="info-value">{{ $vendor->contact_person ?: '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $vendor->email ?: '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $vendor->phone ?: '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Mobile</div>
                        <div class="info-value">{{ $vendor->mobile ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Tax Information</h5></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">GST Type</div>
                        <div class="info-value">{{ $vendor->gst_type }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">GST Number</div>
                        <div class="info-value">{{ $vendor->gst_number ?: '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">PAN Number</div>
                        <div class="info-value">{{ $vendor->pan_number ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Address</h5></div>
            <div class="card-body">
                <div class="info-value" style="margin-bottom: 8px;">{{ $vendor->billing_address ?: '-' }}</div>
                <div style="color: #6b7280;">
                    {{ $vendor->billing_city }}{{ $vendor->billing_state ? ', ' . $vendor->billing_state : '' }}
                    {{ $vendor->billing_pincode ? ' - ' . $vendor->billing_pincode : '' }}
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header"><h5>Financial Info</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 20px;">
                    <div class="info-label">Payment Terms</div>
                    <div class="info-value">{{ $vendor->payment_terms ?: 'Net 30' }}</div>
                </div>
                <div class="info-item" style="margin-bottom: 20px;">
                    <div class="info-label">Credit Days</div>
                    <div class="info-value">{{ $vendor->credit_days }} days</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Credit Limit</div>
                    <div class="info-value highlight">₹{{ number_format($vendor->credit_limit, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Timeline</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 20px;">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $vendor->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $vendor->updated_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
