<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
.page-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; }
.btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; }
.btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #fff; }
.btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
.btn-outline:hover { background: #f9fafb; color: #374151; }

.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; margin-bottom: 20px; }
.card-header { padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: space-between; }
.card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #1f2937; }
.card-body { padding: 24px; }

.form-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
.form-row:last-child { margin-bottom: 0; }
.form-group { display: flex; flex-direction: column; }
.form-group.col-2 { grid-column: span 2; }
.form-group.col-3 { grid-column: span 3; }
.form-group.col-full { grid-column: 1 / -1; }

.form-label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-label .required { color: #ef4444; }
.form-control { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; width: 100%; box-sizing: border-box; }
.form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
.form-control:read-only, .form-control:disabled { background: #f3f4f6; color: #6b7280; cursor: not-allowed; }
.form-text { font-size: 12px; color: #6b7280; margin-top: 4px; }

select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 10px center; background-repeat: no-repeat; background-size: 16px; padding-right: 36px; }
textarea.form-control { min-height: 80px; resize: vertical; }

.alert { padding: 16px; border-radius: 8px; margin-bottom: 20px; }
.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
.alert ul { margin: 0; padding-left: 20px; }

.form-actions { display: flex; gap: 12px; margin-top: 24px; }

/* Items Table */
.table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.items-table { width: 100%; border-collapse: collapse; min-width: 900px; }
.items-table th { background: #f9fafb; padding: 12px 10px; text-align: left; font-weight: 600; font-size: 13px; color: #374151; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
.items-table td { padding: 12px 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
.items-table tr:last-child td { border-bottom: none; }
.items-table .form-control { padding: 8px 10px; font-size: 13px; }
.items-table input[type="number"] { width: 80px; text-align: right; }
.items-table input[type="date"] { width: 120px; }

.product-info { display: flex; flex-direction: column; gap: 4px; }
.product-name { font-weight: 600; color: #1f2937; }
.product-sku { font-size: 12px; color: #6b7280; }
.product-unit { font-size: 12px; color: #9ca3af; }

.qty-group { display: flex; flex-direction: column; gap: 4px; }
.qty-label { font-size: 11px; color: #6b7280; text-transform: uppercase; }

.batch-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e5e7eb; }
.batch-fields .form-group { margin-bottom: 0; }
.batch-fields .form-label { font-size: 11px; margin-bottom: 4px; }

/* Empty State */
.empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
.empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
.empty-state h4 { margin: 0 0 8px; color: #374151; font-size: 18px; }
.empty-state p { margin: 0; font-size: 14px; }

/* Summary Card */
.summary-card { position: sticky; top: 20px; }
.summary-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
.summary-item:last-child { border-bottom: none; }
.summary-label { color: #6b7280; font-size: 14px; }
.summary-value { font-weight: 600; color: #1f2937; }
.summary-value.success { color: #059669; }
.summary-value.danger { color: #dc2626; }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-info { background: #dbeafe; color: #1d4ed8; }

.loading { display: flex; align-items: center; justify-content: center; padding: 40px; color: #6b7280; }
.spinner { width: 24px; height: 24px; border: 3px solid #e5e7eb; border-top-color: #6366f1; border-radius: 50%; animation: spin 0.8s linear infinite; margin-right: 12px; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 1024px) { 
    .main-grid { grid-template-columns: 1fr !important; }
    .form-row { grid-template-columns: repeat(2, 1fr); } 
}
@media (max-width: 768px) { 
    .form-row { grid-template-columns: 1fr; } 
    .form-group.col-2, .form-group.col-3 { grid-column: span 1; } 
}
</style>

<div class="page-header">
    <h1>📦 Create Goods Receipt Note</h1>
    <a href="{{ route('admin.purchase.grn.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div class="alert alert-info">
    <strong>💡 Workflow:</strong> Select PO → Enter received quantities → Save Draft → Submit for Inspection → Approve to update stock
</div>

<form action="{{ route('admin.purchase.grn.store') }}" method="POST" id="grnForm">
    @csrf
    
    <div class="main-grid" style="display: grid; grid-template-columns: 1fr 300px; gap: 24px;">
        <div>
            <!-- PO Selection -->
            <div class="card">
                <div class="card-header"><h5>Select Purchase Order</h5></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-3">
                            <label class="form-label">Purchase Order <span class="required">*</span></label>
                            <select name="purchase_order_id" id="poSelect" class="form-control" required>
                                <option value="">-- Select Confirmed PO --</option>
                                @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}" data-vendor="{{ $po->vendor_id }}" data-vendor-name="{{ $po->vendor->name ?? 'N/A' }}"
                                    {{ (request('po_id') == $po->id || old('purchase_order_id') == $po->id) ? 'selected' : '' }}>
                                    {{ $po->po_number }} - {{ $po->vendor->name ?? 'N/A' }} ({{ $po->po_date->format('d M Y') }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vendor</label>
                            <input type="text" id="vendorDisplay" class="form-control" readonly>
                            <input type="hidden" name="vendor_id" id="vendorId">
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
                            <input type="date" name="grn_date" class="form-control" value="{{ old('grn_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Warehouse <span class="required">*</span></label>
                            <select name="warehouse_id" id="warehouseSelect" class="form-control" required>
                                <option value="">-- Select Warehouse --</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rack/Location</label>
                            <select name="rack_id" id="rackSelect" class="form-control">
                                <option value="">-- Optional --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Received By</label>
                            <input type="text" name="received_by_name" class="form-control" value="{{ old('received_by_name', auth()->user()->name ?? '') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Vendor Invoice No</label>
                            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">LR/Docket No</label>
                            <input type="text" name="lr_number" class="form-control" value="{{ old('lr_number') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vehicle Number</label>
                            <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number') }}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card">
                <div class="card-header">
                    <h5>Received Items</h5>
                    <span class="badge badge-info" id="itemCount">0 Items</span>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div id="itemsContainer">
                        <div class="empty-state" id="emptyState">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                            <h4>No Items to Receive</h4>
                            <p>Select a Purchase Order above to load pending items</p>
                        </div>
                        <div class="loading" id="loadingState" style="display: none;">
                            <div class="spinner"></div> Loading items...
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="items-table" id="itemsTable" style="display: none;">
                                <thead>
                                    <tr>
                                        <th style="min-width: 200px;">Product</th>
                                        <th>Ordered</th>
                                        <th>Prev Rcvd</th>
                                        <th>Received</th>
                                        <th>Accepted</th>
                                        <th>Rejected</th>
                                        <th style="min-width: 280px;">Lot/Batch Info</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header"><h5>Notes</h5></div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any remarks...">{{ old('notes') }}</textarea>
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
                        <span class="summary-value" id="summaryItems">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Received</span>
                        <span class="summary-value" id="summaryReceived">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Accepted</span>
                        <span class="summary-value success" id="summaryAccepted">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Rejected</span>
                        <span class="summary-value danger" id="summaryRejected">0</span>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="flex-direction: column;">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled style="justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save as Draft
                </button>
                <a href="{{ route('admin.purchase.grn.index') }}" class="btn btn-outline" style="justify-content: center;">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const poSelect = document.getElementById('poSelect');
    const warehouseSelect = document.getElementById('warehouseSelect');
    const rackSelect = document.getElementById('rackSelect');
    const vendorDisplay = document.getElementById('vendorDisplay');
    const vendorId = document.getElementById('vendorId');
    const itemsBody = document.getElementById('itemsBody');
    const itemsTable = document.getElementById('itemsTable');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    const submitBtn = document.getElementById('submitBtn');
    
    poSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        vendorDisplay.value = selected.dataset.vendorName || '';
        vendorId.value = selected.dataset.vendor || '';
        if (this.value) loadPOItems(this.value);
        else showEmpty();
    });
    
    warehouseSelect.addEventListener('change', function() {
        if (this.value) loadRacks(this.value);
        else rackSelect.innerHTML = '<option value="">-- Optional --</option>';
    });
    
    function loadPOItems(poId) {
        emptyState.style.display = 'none';
        itemsTable.style.display = 'none';
        loadingState.style.display = 'flex';
        
        fetch(`{{ url('admin/purchase/grn/po-items') }}/${poId}`)
            .then(r => r.json())
            .then(data => {
                loadingState.style.display = 'none';
                if (data.items && data.items.length > 0) {
                    renderItems(data.items);
                    itemsTable.style.display = 'table';
                    submitBtn.disabled = false;
                } else {
                    emptyState.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:64px;height:64px;margin-bottom:16px;opacity:0.5;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><h4>All Items Received</h4><p>This PO has no pending items</p>';
                    emptyState.style.display = 'block';
                    submitBtn.disabled = true;
                }
                updateSummary();
            })
            .catch(err => {
                loadingState.style.display = 'none';
                emptyState.innerHTML = '<h4>Error</h4><p>' + err.message + '</p>';
                emptyState.style.display = 'block';
            });
    }
    
    function loadRacks(warehouseId) {
        fetch(`{{ url('admin/purchase/grn/racks') }}/${warehouseId}`)
            .then(r => r.json())
            .then(data => {
                rackSelect.innerHTML = '<option value="">-- Optional --</option>';
                data.racks.forEach(rack => {
                    rackSelect.innerHTML += `<option value="${rack.id}">${rack.name}</option>`;
                });
            });
    }
    
    function renderItems(items) {
        itemsBody.innerHTML = '';
        items.forEach((item, idx) => {
            const pending = item.ordered_qty - item.received_qty;
            const hasBatch = item.product && item.product.is_batch_managed;
            
            itemsBody.innerHTML += `
                <tr>
                    <td>
                        <div class="product-info">
                            <span class="product-name">${item.product?.name || 'N/A'}</span>
                            <span class="product-sku">SKU: ${item.product?.sku || '-'}</span>
                            <span class="product-unit">Unit: ${item.unit?.short_name || item.unit?.name || '-'}</span>
                        </div>
                        <input type="hidden" name="items[${idx}][po_item_id]" value="${item.id}">
                        <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                        <input type="hidden" name="items[${idx}][variation_id]" value="${item.variation_id || ''}">
                        <input type="hidden" name="items[${idx}][unit_id]" value="${item.unit_id}">
                        <input type="hidden" name="items[${idx}][rate]" value="${item.rate}">
                    </td>
                    <td><div class="qty-group"><span class="qty-label">Ordered</span><strong>${parseFloat(item.ordered_qty).toFixed(2)}</strong></div></td>
                    <td><div class="qty-group"><span class="qty-label">Previous</span><strong>${parseFloat(item.received_qty).toFixed(2)}</strong></div></td>
                    <td><input type="number" name="items[${idx}][received_qty]" class="form-control received-qty" value="${pending}" min="0" max="${pending}" step="0.01" data-idx="${idx}"></td>
                    <td><input type="number" name="items[${idx}][accepted_qty]" class="form-control accepted-qty" value="${pending}" min="0" step="0.01" data-idx="${idx}"></td>
                    <td><input type="number" name="items[${idx}][rejected_qty]" class="form-control rejected-qty" value="0" min="0" step="0.01" data-idx="${idx}" readonly></td>
                    <td>${hasBatch ? `
                        <div class="batch-fields">
                            <div class="form-group"><label class="form-label">Lot No</label><input type="text" name="items[${idx}][lot_no]" class="form-control" placeholder="LOT-001"></div>
                            <div class="form-group"><label class="form-label">Batch No</label><input type="text" name="items[${idx}][batch_no]" class="form-control" placeholder="BATCH-001"></div>
                            <div class="form-group"><label class="form-label">Mfg Date</label><input type="date" name="items[${idx}][manufacturing_date]" class="form-control"></div>
                            <div class="form-group"><label class="form-label">Expiry</label><input type="date" name="items[${idx}][expiry_date]" class="form-control"></div>
                        </div>` : '<span class="form-text">No batch tracking</span>'}</td>
                </tr>`;
        });
        
        document.getElementById('itemCount').textContent = items.length + ' Items';
        
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
        updateSummary();
    }
    
    function showEmpty() {
        itemsTable.style.display = 'none';
        loadingState.style.display = 'none';
        emptyState.style.display = 'block';
        submitBtn.disabled = true;
        document.getElementById('itemCount').textContent = '0 Items';
        updateSummary();
    }
    
    function updateSummary() {
        let items = document.querySelectorAll('#itemsBody tr').length;
        let rcv = 0, acc = 0, rej = 0;
        document.querySelectorAll('.received-qty').forEach(i => rcv += parseFloat(i.value) || 0);
        document.querySelectorAll('.accepted-qty').forEach(i => acc += parseFloat(i.value) || 0);
        document.querySelectorAll('.rejected-qty').forEach(i => rej += parseFloat(i.value) || 0);
        document.getElementById('summaryItems').textContent = items;
        document.getElementById('summaryReceived').textContent = rcv.toFixed(2);
        document.getElementById('summaryAccepted').textContent = acc.toFixed(2);
        document.getElementById('summaryRejected').textContent = rej.toFixed(2);
    }
    
    if (poSelect.value) poSelect.dispatchEvent(new Event('change'));
    if (warehouseSelect.value) loadRacks(warehouseSelect.value);
    document.querySelector('[name="vehicle_number"]')?.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
});
</script>
