@include('purchase::partials.styles')

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.purchase.orders.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1>
                {{ $po->po_number }}
                <span class="badge badge-{{ strtolower($po->status) }} badge-lg">{{ $po->status }}</span>
            </h1>
        </div>
        <div class="header-actions">
            @if($po->status === 'DRAFT')
            <a href="{{ route('admin.purchase.orders.edit', $po->id) }}" class="btn btn-outline">✏️ Edit</a>
            <form action="{{ route('admin.purchase.orders.send', $po->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary">📤 Send to Vendor</button>
            </form>
            @endif
            @if($po->status === 'SENT')
            <form action="{{ route('admin.purchase.orders.confirm', $po->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">✅ Mark Confirmed</button>
            </form>
            @endif
            @if(in_array($po->status, ['CONFIRMED', 'PARTIALLY_RECEIVED']))
            <a href="{{ route('admin.purchase.grn.create') }}?po_id={{ $po->id }}" class="btn btn-primary">📥 Create GRN</a>
            @endif
            <div style="position: relative; display: inline-block;">
                <button type="button" class="btn btn-outline" onclick="togglePdfMenu()">📄 PDF ▾</button>
                <div id="pdfMenu" style="display: none; position: absolute; top: 100%; right: 0; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 100; min-width: 150px; margin-top: 4px;">
                    <a href="{{ route('admin.purchase.orders.pdf', $po->id) }}" target="_blank" style="display: block; padding: 10px 16px; color: var(--text-secondary); text-decoration: none; font-size: 14px;">
                        👁️ View PDF
                    </a>
                    <a href="{{ route('admin.purchase.orders.pdf', $po->id) }}?download=1" style="display: block; padding: 10px 16px; color: var(--text-secondary); text-decoration: none; font-size: 14px; border-top: 1px solid var(--card-border);">
                        📥 Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid-2">
        <!-- PO Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📋 Order Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">PO Number</div><div class="detail-value"><strong>{{ $po->po_number }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">PO Date</div><div class="detail-value">{{ $po->po_date->format('d M Y') }}</div></div>
                <div class="detail-row"><div class="detail-label">Expected Date</div><div class="detail-value">{{ $po->expected_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Delivery Date</div><div class="detail-value">{{ $po->delivery_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="badge badge-{{ strtolower($po->status) }}">{{ $po->status }}</span></div></div>
                <div class="detail-row"><div class="detail-label">Payment Terms</div><div class="detail-value">{{ $po->payment_terms ?? '-' }}</div></div>
                @if($po->purchaseRequest)
                <div class="detail-row"><div class="detail-label">PR Reference</div><div class="detail-value"><a href="{{ route('admin.purchase.requests.show', $po->purchase_request_id) }}">{{ $po->purchaseRequest->pr_number }}</a></div></div>
                @endif
            </div>
        </div>

        <!-- Vendor Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">🏢 Vendor Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Vendor</div><div class="detail-value"><strong><a href="{{ route('admin.purchase.vendors.show', $po->vendor_id) }}">{{ $po->vendor->name }}</a></strong></div></div>
                <div class="detail-row"><div class="detail-label">Code</div><div class="detail-value">{{ $po->vendor->vendor_code }}</div></div>
                @if($po->vendor->billing_address)
                <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">{{ $po->vendor->billing_address }}</div></div>
                @endif
                @if($po->vendor->billing_city)
                <div class="detail-row"><div class="detail-label">City / State</div><div class="detail-value">{{ $po->vendor->billing_city }}{{ $po->vendor->billing_state ? ', ' . $po->vendor->billing_state : '' }} {{ $po->vendor->billing_pincode }}</div></div>
                @endif
                @if($po->vendor->gst_number)
                <div class="detail-row"><div class="detail-label">GSTIN</div><div class="detail-value">{{ $po->vendor->gst_number }}</div></div>
                @endif
                @if($po->vendor->phone)
                <div class="detail-row"><div class="detail-label">Phone</div><div class="detail-value">{{ $po->vendor->phone }}</div></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Shipping Address -->
    @if($po->shipping_address)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Ship To</h5>
        </div>
        <div class="detail-card-body">
            <div class="grid-4">
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Address</div><div class="detail-value">{{ $po->shipping_address }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">City</div><div class="detail-value">{{ $po->shipping_city ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">State</div><div class="detail-value">{{ $po->shipping_state ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">PIN Code</div><div class="detail-value">{{ $po->shipping_pincode ?? '-' }}</div></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Items Table -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Order Items</h5>
        </div>
        <div class="detail-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Received</th>
                            <th class="text-end">Rate</th>
                            <th class="text-end">Disc%</th>
                            <th class="text-end">Tax%</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($po->items as $idx => $item)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                <div class="product-meta">
                                    @if($item->product?->sku)<span>SKU: {{ $item->product->sku }}</span>@endif
                                    @if($item->product?->hsn_code)<span style="margin-left:10px;">HSN: {{ $item->product->hsn_code }}</span>@endif
                                </div>
                            </td>
                            <td>{{ $item->unit->short_name ?? $item->unit->name ?? '-' }}</td>
                            <td class="text-end"><strong>{{ number_format($item->qty, 3) }}</strong></td>
                            <td class="text-end">{{ number_format($item->received_qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                            <td class="text-end">{{ number_format($item->discount_percent, 2) }}%</td>
                            <td class="text-end">{{ number_format($item->tax_percent, 2) }}%</td>
                            <td class="text-end"><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Summary -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">🧮 Summary</h5>
            </div>
            <div class="detail-card-body">
                <div class="summary-box">
                    <div class="summary-row"><span>Subtotal</span><span>₹{{ number_format($po->subtotal, 2) }}</span></div>
                    <div class="summary-row"><span>Tax</span><span>₹{{ number_format($po->tax_amount, 2) }}</span></div>
                    <div class="summary-row"><span>Discount</span><span>-₹{{ number_format($po->discount_amount, 2) }}</span></div>
                    @if($po->shipping_charge > 0)
                    <div class="summary-row"><span>Shipping</span><span>₹{{ number_format($po->shipping_charge, 2) }}</span></div>
                    @endif
                    <div class="summary-row total"><span>Total Amount</span><span>₹{{ number_format($po->total_amount, 2) }}</span></div>
                </div>
            </div>
        </div>

        <!-- Terms & Notes -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📝 Terms & Notes</h5>
            </div>
            <div class="detail-card-body">
                @if($po->terms_conditions)
                <div style="margin-bottom:12px;">
                    <div class="detail-label" style="margin-bottom:6px;">Terms & Conditions</div>
                    <p style="margin:0;white-space:pre-wrap;color:var(--text-primary);font-size:13px;">{{ $po->terms_conditions }}</p>
                </div>
                @endif
                @if($po->notes)
                <div>
                    <div class="detail-label" style="margin-bottom:6px;">Notes</div>
                    <p style="margin:0;white-space:pre-wrap;color:var(--text-primary);font-size:13px;">{{ $po->notes }}</p>
                </div>
                @endif
                @if(!$po->terms_conditions && !$po->notes)
                <p style="margin:0;color:var(--text-muted);">No terms or notes</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Audit Info -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">🕐 Audit Information</h5>
        </div>
        <div class="detail-card-body">
            <div class="grid-4">
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created By</div><div class="detail-value">{{ $po->creator->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $po->created_at->format('d M Y, h:i A') }}</div></div>
                @if($po->sent_at)
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Sent At</div><div class="detail-value">{{ $po->sent_at->format('d M Y, h:i A') }}</div></div>
                @endif
                @if($po->confirmed_at)
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Confirmed At</div><div class="detail-value">{{ $po->confirmed_at->format('d M Y, h:i A') }}</div></div>
                @endif
            </div>
        </div>
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
