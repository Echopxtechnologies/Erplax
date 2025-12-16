<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.page-header-right { display: flex; gap: 10px; flex-wrap: wrap; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; justify-content: center; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { color: #fff; }
.btn-success { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; }
.btn-success:hover { color: #fff; }
.btn-info { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: #fff; }
.btn-info:hover { color: #fff; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
.btn-warning:hover { color: #fff; }
.btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
.btn-danger:hover { color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; color: #374151; }
.btn-outline-danger { background: #fff; color: #dc2626; border: 1px solid #fca5a5; }
.btn-outline-danger:hover { background: #fef2f2; }
.btn-full { width: 100%; }
.btn-sm { padding: 8px 14px; font-size: 13px; }

.badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
.badge-success { background: #dcfce7; color: #166534; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-secondary { background: #f3f4f6; color: #374151; }
.badge-primary { background: #e0e7ff; color: #3730a3; }
.badge-info { background: #cffafe; color: #0e7490; }
.badge-dark { background: #1f2937; color: #fff; }

.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }
.card-body.p-0 { padding: 0; }

.info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.info-item { }
.info-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value { font-size: 15px; color: #1f2937; font-weight: 500; }
.info-value.highlight { color: #4f46e5; font-size: 18px; }
.info-value a { color: #4f46e5; text-decoration: none; }
.info-value a:hover { text-decoration: underline; }

.items-table { width: 100%; border-collapse: collapse; }
.items-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 2px solid #e5e7eb; }
.items-table td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; color: #4b5563; }
.items-table tfoot td { background: #f9fafb; font-weight: 600; }
.text-center { text-align: center; }
.text-end { text-align: right; }

.totals-section { background: #f9fafb; border-radius: 8px; padding: 16px; margin: 16px; }
.totals-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
.totals-row:last-child { border-bottom: none; font-weight: 700; font-size: 18px; color: #4f46e5; }
.totals-row span:first-child { color: #6b7280; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
.alert-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }

.action-card .card-body { display: flex; flex-direction: column; gap: 10px; }

.workflow-status { display: flex; gap: 4px; margin-bottom: 20px; }
.workflow-step { flex: 1; padding: 12px; background: #f3f4f6; border-radius: 8px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; position: relative; }
.workflow-step.active { background: #6366f1; color: #fff; }
.workflow-step.done { background: #dcfce7; color: #166534; }
.workflow-step.cancelled { background: #fee2e2; color: #991b1b; }

@media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } .info-grid { grid-template-columns: repeat(2, 1fr); } .workflow-status { flex-wrap: wrap; } }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $po->po_number }}</h1>
        @php
            $statusColors = ['DRAFT'=>'secondary','SENT'=>'info','CONFIRMED'=>'primary','PARTIALLY_RECEIVED'=>'warning','RECEIVED'=>'success','CANCELLED'=>'dark'];
            $statusLabels = ['DRAFT'=>'Draft','SENT'=>'Sent','CONFIRMED'=>'Confirmed','PARTIALLY_RECEIVED'=>'Partial','RECEIVED'=>'Received','CANCELLED'=>'Cancelled'];
        @endphp
        <span class="badge badge-{{ $statusColors[$po->status] ?? 'secondary' }}">{{ $statusLabels[$po->status] ?? $po->status }}</span>
    </div>
    <div class="page-header-right">
        <div style="position: relative; display: inline-block;">
            <button class="btn btn-outline" onclick="togglePdfMenu()" style="position: relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                PDF ▼
            </button>
            <div id="pdfMenu" style="display: none; position: absolute; top: 100%; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 100; min-width: 150px; margin-top: 4px;">
                <a href="{{ route('admin.purchase.orders.pdf', $po->id) }}" target="_blank" style="display: block; padding: 10px 16px; color: #374151; text-decoration: none; font-size: 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; vertical-align: middle;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    View PDF
                </a>
                <a href="{{ route('admin.purchase.orders.pdf', $po->id) }}?download=1" style="display: block; padding: 10px 16px; color: #374151; text-decoration: none; font-size: 14px; border-top: 1px solid #e5e7eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; vertical-align: middle;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download
                </a>
            </div>
        </div>
        @if($po->canEdit())
        <a href="{{ route('admin.purchase.orders.edit', $po->id) }}" class="btn btn-primary">Edit</a>
        @endif
        <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-outline">Back</a>
    </div>
</div>

<script>
function togglePdfMenu() {
    const menu = document.getElementById('pdfMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    const menu = document.getElementById('pdfMenu');
    if (!e.target.closest('#pdfMenu') && !e.target.closest('[onclick="togglePdfMenu()"]')) {
        menu.style.display = 'none';
    }
});
</script>

<!-- Workflow Status -->
<div class="workflow-status">
    <div class="workflow-step {{ in_array($po->status, ['DRAFT']) ? 'active' : (in_array($po->status, ['SENT','CONFIRMED','PARTIALLY_RECEIVED','RECEIVED']) ? 'done' : '') }} {{ $po->status == 'CANCELLED' ? 'cancelled' : '' }}">Draft</div>
    <div class="workflow-step {{ $po->status == 'SENT' ? 'active' : (in_array($po->status, ['CONFIRMED','PARTIALLY_RECEIVED','RECEIVED']) ? 'done' : '') }}">Sent</div>
    <div class="workflow-step {{ $po->status == 'CONFIRMED' ? 'active' : (in_array($po->status, ['PARTIALLY_RECEIVED','RECEIVED']) ? 'done' : '') }}">Confirmed</div>
    <div class="workflow-step {{ $po->status == 'PARTIALLY_RECEIVED' ? 'active' : ($po->status == 'RECEIVED' ? 'done' : '') }}">Receiving</div>
    <div class="workflow-step {{ $po->status == 'RECEIVED' ? 'done' : '' }}">Complete</div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="detail-grid">
    <div>
        <div class="card">
            <div class="card-header"><h5>Order Details</h5></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">PO Number</div>
                        <div class="info-value highlight">{{ $po->po_number }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date</div>
                        <div class="info-value">{{ $po->po_date->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Expected Delivery</div>
                        <div class="info-value">{{ $po->expected_date ? $po->expected_date->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Vendor</div>
                        <div class="info-value"><a href="{{ route('admin.purchase.vendors.show', $po->vendor_id) }}">{{ $po->vendor->name }}</a></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Payment Terms</div>
                        <div class="info-value">{{ $po->payment_terms ?: '-' }}</div>
                    </div>
                    @if($po->purchaseRequest)
                    <div class="info-item">
                        <div class="info-label">PR Reference</div>
                        <div class="info-value"><a href="{{ route('admin.purchase.requests.show', $po->purchase_request_id) }}">{{ $po->purchaseRequest->pr_number }}</a></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($po->shipping_address)
        <div class="card">
            <div class="card-header"><h5>Shipping Address</h5></div>
            <div class="card-body">
                <p style="margin: 0;">{{ $po->shipping_address }}</p>
                <p style="margin: 8px 0 0; color: #6b7280;">
                    {{ $po->shipping_city }}{{ $po->shipping_state ? ', ' . $po->shipping_state : '' }}
                    {{ $po->shipping_pincode ? ' - ' . $po->shipping_pincode : '' }}
                </p>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><h5>Items ({{ $po->items->count() }})</h5></div>
            <div class="card-body p-0">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Product</th>
                            <th width="80">Unit</th>
                            <th width="80" class="text-end">Qty</th>
                            <th width="100" class="text-end">Rate</th>
                            <th width="80" class="text-end">Tax</th>
                            <th width="120" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->items as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td><strong>{{ $item->product->name ?? $item->description ?? 'N/A' }}</strong></td>
                            <td>{{ $item->unit->short_name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                            <td class="text-end">{{ $item->tax_percent }}%</td>
                            <td class="text-end"><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: flex-end;">
                    <div class="totals-section" style="width: 300px; margin: 16px;">
                        <div class="totals-row">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($po->subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Tax:</span>
                            <span>₹{{ number_format($po->tax_amount, 2) }}</span>
                        </div>
                        @if($po->shipping_charge > 0)
                        <div class="totals-row">
                            <span>Shipping:</span>
                            <span>₹{{ number_format($po->shipping_charge, 2) }}</span>
                        </div>
                        @endif
                        @if($po->discount_amount > 0)
                        <div class="totals-row">
                            <span>Discount:</span>
                            <span>-₹{{ number_format($po->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="totals-row">
                            <span>Grand Total:</span>
                            <span>₹{{ number_format($po->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($po->terms_conditions)
        <div class="card">
            <div class="card-header"><h5>Terms & Conditions</h5></div>
            <div class="card-body">{!! nl2br(e($po->terms_conditions)) !!}</div>
        </div>
        @endif

        @if($po->notes)
        <div class="card">
            <div class="card-header"><h5>Notes</h5></div>
            <div class="card-body">{{ $po->notes }}</div>
        </div>
        @endif
    </div>

    <div>
        <div class="card action-card">
            <div class="card-header"><h5>Actions</h5></div>
            <div class="card-body">
                @if($po->canSend())
                <form action="{{ route('admin.purchase.orders.send', $po->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info btn-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Send to Vendor
                    </button>
                </form>
                @endif

                @if($po->canConfirm())
                <form action="{{ route('admin.purchase.orders.confirm', $po->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Mark as Confirmed
                    </button>
                </form>
                @endif

                @if($po->canReceive())
                <a href="{{ route('admin.purchase.grn.create', ['po_id' => $po->id]) }}" class="btn btn-success btn-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Create GRN
                </a>
                <div class="alert alert-info" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <span>Ready for GRN. Create Goods Receipt to receive items.</span>
                </div>
                @endif

                @if($po->canCancel())
                <form action="{{ route('admin.purchase.orders.cancel', $po->id) }}" method="POST" onsubmit="return confirm('Cancel this PO?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-full">Cancel PO</button>
                </form>
                @endif

                <form action="{{ route('admin.purchase.orders.duplicate', $po->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Duplicate PO
                    </button>
                </form>

                @if($po->status == 'RECEIVED')
                <div class="alert alert-success" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>All items received. PO completed.</span>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Vendor Info</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Name</div>
                    <div class="info-value"><a href="{{ route('admin.purchase.vendors.show', $po->vendor_id) }}">{{ $po->vendor->name }}</a></div>
                </div>
                @if($po->vendor->contact_person)
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Contact</div>
                    <div class="info-value">{{ $po->vendor->contact_person }}</div>
                </div>
                @endif
                @if($po->vendor->phone)
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $po->vendor->phone }}</div>
                </div>
                @endif
                @if($po->vendor->email)
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $po->vendor->email }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Timeline</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $po->created_at->format('d M Y, h:i A') }}</div>
                </div>
                @if($po->sent_at)
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Sent to Vendor</div>
                    <div class="info-value">{{ $po->sent_at->format('d M Y, h:i A') }}</div>
                </div>
                @endif
                @if($po->confirmed_at)
                <div class="info-item">
                    <div class="info-label">Confirmed</div>
                    <div class="info-value">{{ $po->confirmed_at->format('d M Y, h:i A') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
