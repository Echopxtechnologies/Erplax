@include('purchase::partials.styles')

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.purchase.requests.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1>
                {{ $pr->pr_number }}
                <span class="badge badge-{{ strtolower($pr->status) }} badge-lg">{{ $pr->status }}</span>
            </h1>
        </div>
        <div class="header-actions">
            @if($pr->canEdit())
            <a href="{{ route('admin.purchase.requests.edit', $pr->id) }}" class="btn btn-outline">✏️ Edit</a>
            @endif
            @if($pr->canSubmit())
            <form action="{{ route('admin.purchase.requests.submit', $pr->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary">📤 Submit for Approval</button>
            </form>
            @endif
            @if($pr->canApprove())
            <form action="{{ route('admin.purchase.requests.approve', $pr->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">✅ Approve</button>
            </form>
            <button type="button" class="btn btn-danger" onclick="showRejectModal()">❌ Reject</button>
            @endif
            @if($pr->status === 'APPROVED')
            <a href="{{ route('admin.purchase.orders.create') }}?pr_id={{ $pr->id }}" class="btn btn-primary">🛒 Create PO</a>
            @endif
            @if($pr->canCancel())
            <form action="{{ route('admin.purchase.requests.cancel', $pr->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this PR?');">
                @csrf
                <button type="submit" class="btn btn-outline" style="color:#ef4444;">🚫 Cancel</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($pr->rejection_reason)
    <div class="alert alert-danger"><strong>Rejection Reason:</strong> {{ $pr->rejection_reason }}</div>
    @endif

    <div class="grid-2">
        <!-- PR Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📋 Request Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">PR Number</div><div class="detail-value"><strong>{{ $pr->pr_number }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">PR Date</div><div class="detail-value">{{ $pr->pr_date->format('d M Y') }}</div></div>
                <div class="detail-row"><div class="detail-label">Required By</div><div class="detail-value">{{ $pr->required_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Priority</div><div class="detail-value"><span class="badge badge-{{ strtolower($pr->priority) }}">{{ $pr->priority }}</span></div></div>
                <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="badge badge-{{ strtolower($pr->status) }}">{{ $pr->status }}</span></div></div>
            </div>
        </div>

        <!-- Request Info -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📝 Request Info</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Department</div><div class="detail-value">{{ $pr->department ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Purpose</div><div class="detail-value">{{ $pr->purpose ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Requested By</div><div class="detail-value">{{ $pr->requester->name ?? '-' }}</div></div>
                @if($pr->approver)
                <div class="detail-row"><div class="detail-label">{{ $pr->status === 'REJECTED' ? 'Rejected' : 'Approved' }} By</div><div class="detail-value">{{ $pr->approver->name }}</div></div>
                <div class="detail-row"><div class="detail-label">{{ $pr->status === 'REJECTED' ? 'Rejected' : 'Approved' }} At</div><div class="detail-value">{{ $pr->approved_at?->format('d M Y, h:i A') ?? '-' }}</div></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Request Items</h5>
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
                            <th class="text-end">Ordered</th>
                            <th class="text-end">Pending</th>
                            <th class="text-end">Est. Price</th>
                            <th class="text-end">Est. Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @forelse($pr->items as $idx => $item)
                        @php 
                            $lineTotal = $item->qty * ($item->estimated_price ?? 0); 
                            $grandTotal += $lineTotal;
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
                            <td class="text-end"><strong>{{ number_format($item->qty, 3) }}</strong></td>
                            <td class="text-end">{{ number_format($item->ordered_qty, 3) }}</td>
                            <td class="text-end">{{ number_format($item->pending_qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->estimated_price ?? 0, 2) }}</td>
                            <td class="text-end"><strong>₹{{ number_format($lineTotal, 2) }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted">No items found</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-end"><strong>Estimated Total:</strong></td>
                            <td class="text-end"><strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($pr->notes)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📝 Notes</h5>
        </div>
        <div class="detail-card-body">
            <p style="margin:0;white-space:pre-wrap;">{{ $pr->notes }}</p>
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
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $pr->created_at->format('d M Y, h:i A') }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Updated At</div><div class="detail-value">{{ $pr->updated_at->format('d M Y, h:i A') }}</div></div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;justify-content:center;align-items:center;">
    <div style="background:var(--card-bg);border-radius:12px;padding:24px;width:90%;max-width:500px;">
        <h5 style="margin:0 0 16px;">Reject Purchase Request</h5>
        <form action="{{ route('admin.purchase.requests.reject', $pr->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Rejection Reason <span class="required">*</span></label>
                <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}
function hideRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
