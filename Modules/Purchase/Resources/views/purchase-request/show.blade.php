<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.page-header-right { display: flex; gap: 10px; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; justify-content: center; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { color: #fff; }
.btn-success { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; }
.btn-success:hover { color: #fff; }
.btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
.btn-danger:hover { color: #fff; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
.btn-warning:hover { color: #fff; }
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
.badge-dark { background: #1f2937; color: #fff; }
.badge-info { background: #cffafe; color: #0e7490; }

.detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }
.card-body.p-0 { padding: 0; }
.card.border-danger { border-color: #fca5a5; }
.card.border-danger .card-header { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }

.info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.info-item { }
.info-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value { font-size: 15px; color: #1f2937; font-weight: 500; }
.info-value.highlight { color: #4f46e5; }

.items-table { width: 100%; border-collapse: collapse; }
.items-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 2px solid #e5e7eb; }
.items-table td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; color: #4b5563; }
.items-table tfoot td { background: #f9fafb; font-weight: 600; }
.text-center { text-align: center; }
.text-end { text-align: right; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

.action-card .card-body { display: flex; flex-direction: column; gap: 10px; }

.modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal-overlay.show { display: flex; }
.modal-content { background: #fff; border-radius: 12px; width: 100%; max-width: 480px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
.modal-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
.modal-header h5 { margin: 0; font-size: 18px; font-weight: 600; }
.modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280; }
.modal-body { padding: 24px; }
.modal-footer { padding: 16px 24px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 10px; }

@media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } .info-grid { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $pr->pr_number }}</h1>
        @php
            $statusColors = ['DRAFT'=>'secondary','PENDING'=>'warning','APPROVED'=>'success','REJECTED'=>'danger','CANCELLED'=>'dark','CONVERTED'=>'info'];
            $priorityColors = ['LOW'=>'secondary','NORMAL'=>'primary','HIGH'=>'warning','URGENT'=>'danger'];
        @endphp
        <span class="badge badge-{{ $statusColors[$pr->status] ?? 'secondary' }}">{{ $pr->status }}</span>
        <span class="badge badge-{{ $priorityColors[$pr->priority] ?? 'secondary' }}">{{ $pr->priority }}</span>
    </div>
    <div class="page-header-right">
        @if($pr->canEdit())
        <a href="{{ route('admin.purchase.requests.edit', $pr->id) }}" class="btn btn-primary">Edit</a>
        @endif
        <a href="{{ route('admin.purchase.requests.index') }}" class="btn btn-outline">Back</a>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="detail-grid">
    <div>
        <div class="card">
            <div class="card-header"><h5>Request Details</h5></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">PR Number</div>
                        <div class="info-value highlight">{{ $pr->pr_number }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date</div>
                        <div class="info-value">{{ $pr->pr_date->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Required By</div>
                        <div class="info-value">{{ $pr->required_date ? $pr->required_date->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Department</div>
                        <div class="info-value">{{ $pr->department ?: '-' }}</div>
                    </div>
                </div>
                @if($pr->purpose)
                <div style="margin-top: 20px;">
                    <div class="info-label">Purpose</div>
                    <div class="info-value">{{ $pr->purpose }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Items ({{ $pr->items->count() }})</h5></div>
            <div class="card-body p-0">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Product</th>
                            <th width="100">Unit</th>
                            <th width="100" class="text-end">Qty</th>
                            <th width="120" class="text-end">Est. Price</th>
                            <th width="130" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pr->items as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td><strong>{{ $item->product->name ?? 'N/A' }}</strong></td>
                            <td>{{ $item->unit->short_name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->estimated_price ?? 0, 2) }}</td>
                            <td class="text-end"><strong>₹{{ number_format($item->estimated_total, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end">Grand Total:</td>
                            <td class="text-end" style="font-size: 18px; color: #4f46e5;">₹{{ number_format($pr->total_estimated, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($pr->rejection_reason)
        <div class="card border-danger">
            <div class="card-header"><h5>Rejection Reason</h5></div>
            <div class="card-body">{{ $pr->rejection_reason }}</div>
        </div>
        @endif

        @if($pr->notes)
        <div class="card">
            <div class="card-header"><h5>Notes</h5></div>
            <div class="card-body">{{ $pr->notes }}</div>
        </div>
        @endif
    </div>

    <div>
        <div class="card action-card">
            <div class="card-header"><h5>Actions</h5></div>
            <div class="card-body">
                @if($pr->canSubmit())
                <form action="{{ route('admin.purchase.requests.submit', $pr->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Submit for Approval
                    </button>
                </form>
                @endif

                @if($pr->canApprove())
                <form action="{{ route('admin.purchase.requests.approve', $pr->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Approve
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-full" onclick="document.getElementById('rejectModal').classList.add('show')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reject
                </button>
                @endif

                @if($pr->status == 'APPROVED')
                <a href="{{ route('admin.purchase.orders.create', ['pr_id' => $pr->id]) }}" class="btn btn-warning btn-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                    Convert to Purchase Order
                </a>
                <div class="alert alert-success" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>PR approved. Ready for PO.</span>
                </div>
                @endif

                @if($pr->status == 'CONVERTED')
                <div class="alert alert-info" style="margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                    <span>Converted to Purchase Order</span>
                </div>
                @endif

                @if($pr->canCancel())
                <form action="{{ route('admin.purchase.requests.cancel', $pr->id) }}" method="POST" onsubmit="return confirm('Cancel this PR?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-full">Cancel PR</button>
                </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Info</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">Requested By</div>
                    <div class="info-value">{{ $pr->requester->name ?? '-' }}</div>
                </div>
                @if($pr->approved_by)
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">{{ $pr->status == 'APPROVED' ? 'Approved' : 'Rejected' }} By</div>
                    <div class="info-value">{{ $pr->approver->name ?? '-' }}</div>
                </div>
                <div class="info-item" style="margin-bottom: 16px;">
                    <div class="info-label">At</div>
                    <div class="info-value">{{ $pr->approved_at->format('d M Y, h:i A') }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $pr->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal-content">
        <form action="{{ route('admin.purchase.requests.reject', $pr->id) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5>Reject Purchase Request</h5>
                <button type="button" class="modal-close" onclick="document.getElementById('rejectModal').classList.remove('show')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" style="display:block;margin-bottom:8px;">Reason <span style="color:#ef4444;">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Enter rejection reason..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('rejectModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>
