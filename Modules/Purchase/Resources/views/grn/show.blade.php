<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 12px; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #fff; }
.btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; }
.btn-success:hover { background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; }
.btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
.btn-warning:hover { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: #fff; }
.btn-danger { background: #ef4444; color: #fff; }
.btn-danger:hover { background: #dc2626; color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; }
.btn-sm { padding: 6px 12px; font-size: 13px; }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }

.info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.info-item { display: flex; flex-direction: column; gap: 4px; }
.info-item .label { font-size: 13px; color: #6b7280; }
.info-item .value { font-size: 15px; font-weight: 500; color: #1f2937; }

.badge { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
.badge-draft { background: #f3f4f6; color: #374151; }
.badge-inspecting { background: #fef3c7; color: #92400e; }
.badge-approved { background: #d1fae5; color: #065f46; }
.badge-rejected { background: #fee2e2; color: #991b1b; }
.badge-cancelled { background: #e5e7eb; color: #6b7280; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-lg { padding: 10px 20px; font-size: 15px; }

/* Table */
.items-table { width: 100%; border-collapse: collapse; }
.items-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #374151; border-bottom: 2px solid #e5e7eb; }
.items-table td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
.items-table tr:last-child td { border-bottom: none; }

.product-info { display: flex; flex-direction: column; gap: 2px; }
.product-name { font-weight: 600; color: #1f2937; }
.product-sku { font-size: 12px; color: #6b7280; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
.alert-danger { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }
.alert-warning { background: #fef3c7; border: 1px solid #fde68a; color: #92400e; }
.alert-info { background: #dbeafe; border: 1px solid #bfdbfe; color: #1e40af; }

.actions-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }

.summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
.summary-item { text-align: center; }
.summary-item .label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
.summary-item .value { font-size: 20px; font-weight: 700; }
.summary-item .value.success { color: #059669; }
.summary-item .value.danger { color: #dc2626; }

.status-box { padding: 20px; border-radius: 12px; text-align: center; }
.status-box.draft { background: #f9fafb; border: 2px solid #e5e7eb; }
.status-box.inspecting { background: #fffbeb; border: 2px solid #fde68a; }
.status-box.approved { background: #ecfdf5; border: 2px solid #a7f3d0; }
.status-box.rejected { background: #fef2f2; border: 2px solid #fecaca; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; }
.modal-overlay.active { display: flex; }
.modal { background: #fff; border-radius: 12px; width: 100%; max-width: 500px; padding: 24px; }
.modal h3 { margin: 0 0 16px; font-size: 18px; }
.modal textarea { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; min-height: 100px; margin-bottom: 16px; }
.modal-actions { display: flex; gap: 12px; justify-content: flex-end; }

@media (max-width: 768px) { 
    .info-grid { grid-template-columns: 1fr; }
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="page-header">
    <h1>
        📦 {{ $grn->grn_number }}
        <span class="badge badge-{{ strtolower($grn->status) }}">{{ $grn->status }}</span>
    </h1>
    <a href="{{ route('admin.purchase.grn.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Action Buttons -->
<div class="actions-bar">
    @if($grn->status === 'DRAFT')
        <form action="{{ route('admin.purchase.grn.submit', $grn->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Submit for Inspection
            </button>
        </form>
        <a href="{{ route('admin.purchase.grn.edit', $grn->id) }}" class="btn btn-outline">Edit</a>
    @endif
    
    @if($grn->status === 'INSPECTING')
        <form action="{{ route('admin.purchase.grn.approve', $grn->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Approve GRN and update stock? This action cannot be undone.')">
            @csrf
            <button type="submit" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                Approve & Update Stock
            </button>
        </form>
        <button type="button" class="btn btn-danger" onclick="showRejectModal()">Reject</button>
    @endif
    
    @if(!$grn->stock_updated && $grn->status !== 'CANCELLED')
        <form action="{{ route('admin.purchase.grn.cancel', $grn->id) }}" method="POST" style="display: inline; margin-left: auto;" onsubmit="return confirm('Cancel this GRN?')">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">Cancel GRN</button>
        </form>
    @endif
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px;">
    <div>
        <!-- GRN Details -->
        <div class="card">
            <div class="card-header"><h5>GRN Details</h5></div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">GRN Number</span>
                        <span class="value">{{ $grn->grn_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">GRN Date</span>
                        <span class="value">{{ $grn->grn_date->format('d M Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Purchase Order</span>
                        <span class="value">
                            <a href="{{ route('admin.purchase.orders.show', $grn->purchase_order_id) }}" style="color: #4f46e5;">
                                {{ $grn->purchaseOrder->po_number ?? '-' }}
                            </a>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="label">Vendor</span>
                        <span class="value">{{ $grn->vendor->name ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Warehouse</span>
                        <span class="value">{{ $grn->warehouse->name ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Rack/Location</span>
                        <span class="value">{{ $grn->rack->name ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Vendor Invoice</span>
                        <span class="value">{{ $grn->invoice_number ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Invoice Date</span>
                        <span class="value">{{ $grn->invoice_date ? $grn->invoice_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">LR/Docket No</span>
                        <span class="value">{{ $grn->lr_number ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Vehicle Number</span>
                        <span class="value">{{ $grn->vehicle_number ?? '-' }}</span>
                    </div>
                </div>
                
                @if($grn->notes)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <div class="info-item">
                        <span class="label">Notes</span>
                        <span class="value">{{ $grn->notes }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div class="card">
            <div class="card-header"><h5>Received Items</h5></div>
            <div class="card-body" style="padding: 0; overflow-x: auto;">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Ordered</th>
                            <th>Received</th>
                            <th>Accepted</th>
                            <th>Rejected</th>
                            <th>Lot/Batch</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grn->items as $item)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <span class="product-name">{{ $item->product->name ?? 'N/A' }}</span>
                                    <span class="product-sku">SKU: {{ $item->product->sku ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item->ordered_qty, 2) }} {{ $item->unit->short_name ?? '' }}</td>
                            <td>{{ number_format($item->received_qty, 2) }}</td>
                            <td style="color: #059669; font-weight: 600;">{{ number_format($item->accepted_qty, 2) }}</td>
                            <td style="color: #dc2626;">{{ number_format($item->rejected_qty, 2) }}</td>
                            <td>
                                @if($item->lot_no || $item->batch_no)
                                    <small>
                                        @if($item->lot_no)<strong>Lot:</strong> {{ $item->lot_no }}<br>@endif
                                        @if($item->batch_no)<strong>Batch:</strong> {{ $item->batch_no }}<br>@endif
                                        @if($item->expiry_date)<strong>Exp:</strong> {{ $item->expiry_date->format('d M Y') }}@endif
                                    </small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>₹{{ number_format($item->rate, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">No items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Status Box -->
        <div class="status-box {{ strtolower($grn->status) }}" style="margin-bottom: 20px;">
            <div class="badge badge-{{ strtolower($grn->status) }} badge-lg" style="margin-bottom: 12px;">{{ $grn->status }}</div>
            <p style="margin: 0; font-size: 14px; color: #6b7280;">
                @switch($grn->status)
                    @case('DRAFT') Draft - Submit for inspection when ready @break
                    @case('INSPECTING') Awaiting quality inspection approval @break
                    @case('APPROVED') Approved - Stock has been updated @break
                    @case('REJECTED') Rejected - {{ $grn->rejection_reason ?? 'See notes' }} @break
                    @case('CANCELLED') Cancelled @break
                @endswitch
            </p>
        </div>

        <!-- Stock Status -->
        <div class="card">
            <div class="card-header"><h5>Stock Status</h5></div>
            <div class="card-body" style="text-align: center;">
                @if($grn->stock_updated)
                    <span class="badge badge-success badge-lg">✓ Stock Updated</span>
                    <p style="margin: 12px 0 0; font-size: 13px; color: #6b7280;">
                        Updated on {{ $grn->approved_at ? $grn->approved_at->format('d M Y H:i') : '-' }}
                    </p>
                @else
                    <span class="badge badge-warning badge-lg">⏳ Pending</span>
                    <p style="margin: 12px 0 0; font-size: 13px; color: #6b7280;">
                        Stock will be updated when GRN is approved
                    </p>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="card">
            <div class="card-header"><h5>Quantity Summary</h5></div>
            <div class="card-body">
                <div class="summary-grid" style="border-top: none; padding-top: 0; margin-top: 0;">
                    <div class="summary-item">
                        <div class="label">Items</div>
                        <div class="value">{{ $grn->items->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Received</div>
                        <div class="value">{{ number_format($grn->total_qty, 2) }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Accepted</div>
                        <div class="value success">{{ number_format($grn->accepted_qty, 2) }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Rejected</div>
                        <div class="value danger">{{ number_format($grn->rejected_qty, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="card">
            <div class="card-header"><h5>Record Info</h5></div>
            <div class="card-body">
                <div class="info-item" style="margin-bottom: 12px;">
                    <span class="label">Created By</span>
                    <span class="value">{{ $grn->creator->name ?? '-' }}</span>
                </div>
                <div class="info-item" style="margin-bottom: 12px;">
                    <span class="label">Created At</span>
                    <span class="value">{{ $grn->created_at->format('d M Y H:i') }}</span>
                </div>
                @if($grn->approver)
                <div class="info-item">
                    <span class="label">Approved By</span>
                    <span class="value">{{ $grn->approver->name ?? '-' }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <h3>Reject GRN</h3>
        <form action="{{ route('admin.purchase.grn.reject', $grn->id) }}" method="POST">
            @csrf
            <textarea name="rejection_reason" placeholder="Enter rejection reason..." required></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject GRN</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() { document.getElementById('rejectModal').classList.add('active'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('active'); }
</script>
