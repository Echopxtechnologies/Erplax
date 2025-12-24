@include('purchase::partials.styles')

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.purchase.grn.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1>
                {{ $grn->grn_number }}
                <span class="badge badge-{{ strtolower($grn->status) }} badge-lg">{{ $grn->status }}</span>
                @if($grn->stock_updated)
                <span class="badge badge-success badge-lg">Stock Updated</span>
                @else
                <span class="badge badge-warning badge-lg">Stock Pending</span>
                @endif
            </h1>
        </div>
        <div class="header-actions">
            @if($grn->status === 'DRAFT')
            <a href="{{ route('admin.purchase.grn.edit', $grn->id) }}" class="btn btn-outline">✏️ Edit</a>
            <form action="{{ route('admin.purchase.grn.inspect', $grn->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">🔍 Start Inspection</button>
            </form>
            @endif
            @if($grn->status === 'INSPECTING')
            <form action="{{ route('admin.purchase.grn.approve', $grn->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">✅ Approve & Update Stock</button>
            </form>
            <button type="button" class="btn btn-danger" onclick="showRejectModal()">❌ Reject</button>
            @endif
            @if($grn->status === 'APPROVED' && !$grn->bill)
            <a href="{{ route('admin.purchase.bills.create') }}?grn_id={{ $grn->id }}" class="btn btn-primary">📄 Create Bill</a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($grn->status === 'REJECTED' && $grn->rejection_reason)
    <div class="rejection-box">
        <h6>❌ Rejection Reason</h6>
        <p>{{ $grn->rejection_reason }}</p>
    </div>
    @endif

    <div class="grid-2">
        <!-- GRN Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📥 GRN Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">GRN Number</div><div class="detail-value"><strong>{{ $grn->grn_number }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">GRN Date</div><div class="detail-value">{{ $grn->grn_date->format('d M Y') }}</div></div>
                <div class="detail-row"><div class="detail-label">Purchase Order</div><div class="detail-value"><a href="{{ route('admin.purchase.orders.show', $grn->purchase_order_id) }}">{{ $grn->purchaseOrder->po_number }}</a></div></div>
                <div class="detail-row"><div class="detail-label">Invoice Number</div><div class="detail-value">{{ $grn->invoice_number ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Invoice Date</div><div class="detail-value">{{ $grn->invoice_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">LR Number</div><div class="detail-value">{{ $grn->lr_number ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Vehicle Number</div><div class="detail-value">{{ $grn->vehicle_number ?? '-' }}</div></div>
            </div>
        </div>

        <!-- Vendor Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">🏢 Vendor Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Vendor</div><div class="detail-value"><strong><a href="{{ route('admin.purchase.vendors.show', $grn->vendor_id) }}">{{ $grn->vendor->name }}</a></strong></div></div>
                <div class="detail-row"><div class="detail-label">Code</div><div class="detail-value">{{ $grn->vendor->vendor_code }}</div></div>
                @if($grn->vendor->billing_address)
                <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">{{ $grn->vendor->billing_address }}</div></div>
                @endif
                @if($grn->vendor->gst_number)
                <div class="detail-row"><div class="detail-label">GSTIN</div><div class="detail-value">{{ $grn->vendor->gst_number }}</div></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Warehouse & Stock Info -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Warehouse & Stock</h5>
        </div>
        <div class="detail-card-body">
            <div class="grid-4">
                <div class="detail-row" style="display:block;border:none;">
                    <div class="detail-label">Warehouse</div>
                    <div class="detail-value"><strong>{{ $grn->warehouse->name ?? '-' }}</strong></div>
                </div>
                <div class="detail-row" style="display:block;border:none;">
                    <div class="detail-label">Rack</div>
                    <div class="detail-value">{{ $grn->rack->name ?? '-' }}</div>
                </div>
                <div class="detail-row" style="display:block;border:none;">
                    <div class="detail-label">Stock Status</div>
                    <div class="detail-value">
                        @if($grn->stock_updated)
                        <span class="badge badge-success">✓ Updated</span>
                        @else
                        <span class="badge badge-warning">Pending</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row" style="display:block;border:none;">
                    <div class="detail-label">Total / Accepted / Rejected</div>
                    <div class="detail-value">
                        <strong>{{ number_format($grn->total_qty, 3) }}</strong> / 
                        <span style="color:var(--success);">{{ number_format($grn->accepted_qty, 3) }}</span> / 
                        <span style="color:var(--danger);">{{ number_format($grn->rejected_qty, 3) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Received Items</h5>
        </div>
        <div class="detail-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th class="text-end">Ordered</th>
                            <th class="text-end">Received</th>
                            <th class="text-end">Accepted</th>
                            <th class="text-end">Rejected</th>
                            <th class="text-end">Rate</th>
                            <th>Lot / Batch</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grn->items as $idx => $item)
                        @php 
                            $imageUrl = null;
                            if ($item->variation && $item->variation->image_path) {
                                $imageUrl = asset('storage/' . $item->variation->image_path);
                            } elseif ($item->product && $item->product->primaryImage) {
                                $imageUrl = asset('storage/' . $item->product->primaryImage->image_path);
                            }
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="product-cell">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="" class="product-thumb">
                                    @else
                                        <div class="product-thumb no-img">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <path d="M21 15l-5-5L5 21"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="product-info">
                                        <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                        <div class="product-meta">
                                            @if($item->variation)
                                            <span class="var-badge">{{ $item->variation->variation_name ?: $item->variation->sku }}</span>
                                            @endif
                                            @if($item->product?->sku)<span class="sku">SKU: {{ $item->product->sku }}</span>@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->unit->short_name ?? $item->unit->name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->ordered_qty, 3) }}</td>
                            <td class="text-end">{{ number_format($item->received_qty, 3) }}</td>
                            <td class="text-end" style="color:var(--success);font-weight:600;">{{ number_format($item->accepted_qty, 3) }}</td>
                            <td class="text-end" style="color:var(--danger);">{{ number_format($item->rejected_qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                            <td>
                                @if($item->lot_no || $item->batch_no)
                                <span style="font-size:12px;">{{ $item->lot_no }} {{ $item->batch_no }}</span>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $item->expiry_date?->format('d M Y') ?? '-' }}</td>
                        </tr>
                        @if($item->rejection_reason)
                        <tr>
                            <td></td>
                            <td colspan="9" style="color:var(--danger);font-size:12px;padding-top:0;">
                                ⚠️ Rejection: {{ $item->rejection_reason }}
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr><td colspan="10" class="text-center text-muted">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($grn->notes)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📝 Notes</h5>
        </div>
        <div class="detail-card-body">
            <p style="margin:0;white-space:pre-wrap;color:var(--text-primary);">{{ $grn->notes }}</p>
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
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Received By</div><div class="detail-value">{{ $grn->receiver->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $grn->created_at->format('d M Y, h:i A') }}</div></div>
                @if($grn->approved_at)
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved By</div><div class="detail-value">{{ $grn->approver->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved At</div><div class="detail-value">{{ $grn->approved_at->format('d M Y, h:i A') }}</div></div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>❌ Reject GRN</h5>
            <button type="button" class="modal-close" onclick="hideRejectModal()">&times;</button>
        </div>
        <form action="{{ route('admin.purchase.grn.reject', $grn->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Rejection Reason <span class="required">*</span></label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
