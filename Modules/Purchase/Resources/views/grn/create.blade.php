@include('purchase::partials.styles')


<div class="page-header">
    <h1>Create Goods Receipt Note</h1>
    <a href="{{ route('admin.purchase.grn.index') }}" class="btn btn-outline">← Back</a>
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
            const variationName = item.variation ? (item.variation.variation_name || item.variation.sku || '') : '';
            
            itemsBody.innerHTML += `
                <tr>
                    <td>
                        <div class="product-info">
                            <span class="product-name">${item.product?.name || 'N/A'}</span>
                            ${variationName ? `<span style="background:#8b5cf6;color:#fff;padding:2px 8px;border-radius:4px;font-size:11px;display:inline-block;margin:4px 0;">${variationName}</span>` : ''}
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
