<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: space-between; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }

.form-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
.form-row:last-child { margin-bottom: 0; }
.form-group { display: flex; flex-direction: column; }
.form-group.col-2 { grid-column: span 2; }
.form-group.col-full { grid-column: 1 / -1; }

.form-label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-label .required { color: #ef4444; }
.form-control { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; width: 100%; box-sizing: border-box; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-control:read-only { background: #f3f4f6; color: #6b7280; }
.form-text { font-size: 12px; color: #6b7280; margin-top: 4px; }

select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 10px center; background-repeat: no-repeat; background-size: 16px; padding-right: 36px; }
textarea.form-control { min-height: 80px; resize: vertical; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert ul { margin: 0; padding-left: 20px; }

.form-actions { display: flex; gap: 12px; margin-top: 24px; }

/* Items Table */
.items-table { width: 100%; border-collapse: collapse; }
.items-table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #374151; border-bottom: 2px solid #e5e7eb; }
.items-table td { padding: 16px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
.items-table .form-control { padding: 8px 12px; font-size: 13px; }
.items-table input[type="number"] { width: 90px; text-align: right; }
.items-table input[type="date"] { width: 130px; }

.product-info { display: flex; flex-direction: column; gap: 4px; }
.product-name { font-weight: 600; color: #1f2937; }
.product-sku { font-size: 12px; color: #6b7280; }

.batch-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e5e7eb; }
.batch-fields .form-group { margin-bottom: 0; }
.batch-fields .form-label { font-size: 11px; margin-bottom: 4px; }

.badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-info { background: #dbeafe; color: #1d4ed8; }

.summary-card { position: sticky; top: 20px; }
.summary-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
.summary-item:last-child { border-bottom: none; }
.summary-label { color: #6b7280; font-size: 14px; }
.summary-value { font-weight: 600; color: #1f2937; }
.summary-value.success { color: #059669; }
.summary-value.danger { color: #dc2626; }

@media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr !important; } }
@media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .form-group.col-2 { grid-column: span 1; } }
</style>

<div class="page-header">
    <h1>📝 Edit GRN: {{ $grn->grn_number }}</h1>
    <a href="{{ route('admin.purchase.grn.show', $grn->id) }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Cancel
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.grn.update', $grn->id) }}" method="POST" id="grnForm">
    @csrf
    @method('PUT')
    
    <div class="main-grid" style="display: grid; grid-template-columns: 1fr 300px; gap: 24px;">
        <div>
            <!-- PO Info -->
            <div class="card">
                <div class="card-header"><h5>Purchase Order</h5></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-2">
                            <label class="form-label">PO Number</label>
                            <input type="text" class="form-control" value="{{ $grn->purchaseOrder->po_number ?? '-' }}" readonly>
                        </div>
                        <div class="form-group col-2">
                            <label class="form-label">Vendor</label>
                            <input type="text" class="form-control" value="{{ $grn->vendor->name ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRN Details -->
            <div class="card">
                <div class="card-header"><h5>Receipt Details</h5></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">GRN Date <span class="required">*</span></label>
                            <input type="date" name="grn_date" class="form-control" value="{{ old('grn_date', $grn->grn_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Warehouse <span class="required">*</span></label>
                            <select name="warehouse_id" id="warehouseSelect" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id', $grn->warehouse_id) == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rack/Location</label>
                            <select name="rack_id" id="rackSelect" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($racks as $rack)
                                <option value="{{ $rack->id }}" {{ old('rack_id', $grn->rack_id) == $rack->id ? 'selected' : '' }}>{{ $rack->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Received By</label>
                            <input type="text" name="received_by_name" class="form-control" value="{{ old('received_by_name', $grn->receiver->name ?? '') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Vendor Invoice No</label>
                            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $grn->invoice_number) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $grn->invoice_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">LR/Docket No</label>
                            <input type="text" name="lr_number" class="form-control" value="{{ old('lr_number', $grn->lr_number) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vehicle Number</label>
                            <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number', $grn->vehicle_number) }}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card">
                <div class="card-header">
                    <h5>Items</h5>
                    <span class="badge badge-info">{{ $grn->items->count() }} Items</span>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="min-width: 200px;">Product</th>
                                <th>Ordered</th>
                                <th>Received</th>
                                <th>Accepted</th>
                                <th>Rejected</th>
                                <th style="min-width: 280px;">Lot/Batch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grn->items as $idx => $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <span class="product-name">{{ $item->product->name ?? 'N/A' }}</span>
                                        <span class="product-sku">SKU: {{ $item->product->sku ?? '-' }}</span>
                                    </div>
                                    <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $item->id }}">
                                    <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                </td>
                                <td><strong>{{ number_format($item->ordered_qty, 2) }}</strong></td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][received_qty]" class="form-control received-qty" 
                                        value="{{ old("items.$idx.received_qty", $item->received_qty) }}" min="0" step="0.01" data-idx="{{ $idx }}">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][accepted_qty]" class="form-control accepted-qty" 
                                        value="{{ old("items.$idx.accepted_qty", $item->accepted_qty) }}" min="0" step="0.01" data-idx="{{ $idx }}">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][rejected_qty]" class="form-control rejected-qty" 
                                        value="{{ old("items.$idx.rejected_qty", $item->rejected_qty) }}" min="0" step="0.01" data-idx="{{ $idx }}" readonly>
                                </td>
                                <td>
                                    @if($item->product && $item->product->is_batch_managed)
                                    <div class="batch-fields">
                                        <div class="form-group">
                                            <label class="form-label">Lot No</label>
                                            <input type="text" name="items[{{ $idx }}][lot_no]" class="form-control" value="{{ old("items.$idx.lot_no", $item->lot_no) }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Batch No</label>
                                            <input type="text" name="items[{{ $idx }}][batch_no]" class="form-control" value="{{ old("items.$idx.batch_no", $item->batch_no) }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Mfg Date</label>
                                            <input type="date" name="items[{{ $idx }}][manufacturing_date]" class="form-control" value="{{ old("items.$idx.manufacturing_date", $item->manufacturing_date?->format('Y-m-d')) }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Expiry</label>
                                            <input type="date" name="items[{{ $idx }}][expiry_date]" class="form-control" value="{{ old("items.$idx.expiry_date", $item->expiry_date?->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    @else
                                    <span class="form-text">No batch tracking</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header"><h5>Notes</h5></div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $grn->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="card summary-card">
                <div class="card-header"><h5>Summary</h5></div>
                <div class="card-body">
                    <div class="summary-item">
                        <span class="summary-label">Total Items</span>
                        <span class="summary-value" id="summaryItems">{{ $grn->items->count() }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Received</span>
                        <span class="summary-value" id="summaryReceived">{{ number_format($grn->total_qty, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Accepted</span>
                        <span class="summary-value success" id="summaryAccepted">{{ number_format($grn->accepted_qty, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Rejected</span>
                        <span class="summary-value danger" id="summaryRejected">{{ number_format($grn->rejected_qty, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="flex-direction: column;">
                <button type="submit" class="btn btn-primary" style="justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update GRN
                </button>
                <a href="{{ route('admin.purchase.grn.show', $grn->id) }}" class="btn btn-outline" style="justify-content: center;">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.received-qty, .accepted-qty').forEach(input => {
        input.addEventListener('input', function() {
            const idx = this.dataset.idx;
            const rcv = parseFloat(document.querySelector(`.received-qty[data-idx="${idx}"]`).value) || 0;
            const acc = parseFloat(document.querySelector(`.accepted-qty[data-idx="${idx}"]`).value) || 0;
            const accInput = document.querySelector(`.accepted-qty[data-idx="${idx}"]`);
            if (acc > rcv) accInput.value = rcv;
            document.querySelector(`.rejected-qty[data-idx="${idx}"]`).value = Math.max(0, rcv - parseFloat(accInput.value)).toFixed(2);
            updateSummary();
        });
    });
    
    function updateSummary() {
        let rcv = 0, acc = 0, rej = 0;
        document.querySelectorAll('.received-qty').forEach(i => rcv += parseFloat(i.value) || 0);
        document.querySelectorAll('.accepted-qty').forEach(i => acc += parseFloat(i.value) || 0);
        document.querySelectorAll('.rejected-qty').forEach(i => rej += parseFloat(i.value) || 0);
        document.getElementById('summaryReceived').textContent = rcv.toFixed(2);
        document.getElementById('summaryAccepted').textContent = acc.toFixed(2);
        document.getElementById('summaryRejected').textContent = rej.toFixed(2);
    }
    
    document.getElementById('warehouseSelect').addEventListener('change', function() {
        if (this.value) {
            fetch(`{{ url('admin/purchase/grn/racks') }}/${this.value}`)
                .then(r => r.json())
                .then(data => {
                    const sel = document.getElementById('rackSelect');
                    sel.innerHTML = '<option value="">-- Optional --</option>';
                    data.racks.forEach(r => sel.innerHTML += `<option value="${r.id}">${r.name}</option>`);
                });
        }
    });
    
    document.querySelector('[name="vehicle_number"]')?.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
});
</script>
