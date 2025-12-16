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
                <span class="badge badge-{{ strtolower($pr->priority) }}">{{ $pr->priority }}</span>
            </h1>
        </div>
        <div class="header-actions">
            @if($pr->status === 'DRAFT')
            <a href="{{ route('admin.purchase.requests.edit', $pr->id) }}" class="btn btn-outline">✏️ Edit</a>
            <form action="{{ route('admin.purchase.requests.submit', $pr->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary">📤 Submit for Approval</button>
            </form>
            @endif
            @if($pr->status === 'PENDING')
            <form action="{{ route('admin.purchase.requests.approve', $pr->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">✅ Approve</button>
            </form>
            <button type="button" class="btn btn-danger" onclick="showRejectModal()">❌ Reject</button>
            @endif
            @if($pr->status === 'APPROVED')
            <a href="{{ route('admin.purchase.orders.create') }}?pr_id={{ $pr->id }}" class="btn btn-primary">📦 Convert to PO</a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($pr->status === 'REJECTED' && $pr->rejection_reason)
    <div class="rejection-box">
        <h6>❌ Rejection Reason</h6>
        <p>{{ $pr->rejection_reason }}</p>
    </div>
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
                <div class="detail-row"><div class="detail-label">Required Date</div><div class="detail-value">{{ $pr->required_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Department</div><div class="detail-value">{{ $pr->department ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Priority</div><div class="detail-value"><span class="badge badge-{{ strtolower($pr->priority) }}">{{ $pr->priority }}</span></div></div>
                <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="badge badge-{{ strtolower($pr->status) }}">{{ $pr->status }}</span></div></div>
            </div>
        </div>

        <!-- Purpose & Notes -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📝 Purpose & Notes</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Purpose</div><div class="detail-value">{{ $pr->purpose ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Notes</div><div class="detail-value">{{ $pr->notes ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Requested Items</h5>
        </div>
        <div class="detail-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th class="text-end">Qty Requested</th>
                            <th class="text-end">Qty Ordered</th>
                            <th class="text-end">Est. Price</th>
                            <th>Specifications</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pr->items as $idx => $item)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                @if($item->product?->sku)
                                <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                                @endif
                            </td>
                            <td>{{ $item->unit->short_name ?? $item->unit->name ?? '-' }}</td>
                            <td class="text-end"><strong>{{ number_format($item->qty, 3) }}</strong></td>
                            <td class="text-end">{{ number_format($item->ordered_qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->estimated_price ?? 0, 2) }}</td>
                            <td>{{ $item->specifications ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
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
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Requested By</div><div class="detail-value">{{ $pr->requester->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $pr->created_at->format('d M Y, h:i A') }}</div></div>
                @if($pr->approved_at)
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved By</div><div class="detail-value">{{ $pr->approver->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved At</div><div class="detail-value">{{ $pr->approved_at->format('d M Y, h:i A') }}</div></div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>❌ Reject Purchase Request</h5>
            <button type="button" class="modal-close" onclick="hideRejectModal()">&times;</button>
        </div>
        <form action="{{ route('admin.purchase.requests.reject', $pr->id) }}" method="POST">
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
