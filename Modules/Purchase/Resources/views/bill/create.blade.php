@include('purchase::partials.styles')

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.purchase.bills.index') }}" class="btn-back">←</a>
        <h1>Create Vendor Bill</h1>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.purchase.bills.store') }}" method="POST" id="billForm">
        @csrf
        
        <div class="form-card">
            <div class="form-card-header"><h5>Bill Information</h5></div>
            <div class="form-card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">GRN <span class="required">*</span></label>
                        <select name="grn_id" id="grn_id" class="form-control" required>
                            <option value="">-- Select Approved GRN --</option>
                            @foreach($grns as $grn)
                            <option value="{{ $grn->id }}" 
                                data-vendor="{{ $grn->vendor_id }}"
                                data-vendor-name="{{ $grn->vendor->name }}"
                                data-po="{{ $grn->purchase_order_id }}"
                                data-invoice="{{ $grn->invoice_number }}"
                                data-invoice-date="{{ $grn->invoice_date?->format('Y-m-d') }}"
                                data-warehouse="{{ $grn->warehouse_id }}"
                                {{ old('grn_id', request('grn_id')) == $grn->id ? 'selected' : '' }}>
                                {{ $grn->grn_number }} - {{ $grn->vendor->name }} ({{ $grn->grn_date->format('d M Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vendor</label>
                        <input type="text" id="vendor_display" class="form-control" readonly>
                        <input type="hidden" name="vendor_id" id="vendor_id">
                        <input type="hidden" name="purchase_order_id" id="purchase_order_id">
                        <input type="hidden" name="warehouse_id" id="warehouse_id">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bill Date <span class="required">*</span></label>
                        <input type="date" name="bill_date" class="form-control" value="{{ old('bill_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vendor Invoice No</label>
                        <input type="text" name="vendor_invoice_no" id="vendor_invoice_no" class="form-control" value="{{ old('vendor_invoice_no') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vendor Invoice Date</label>
                        <input type="date" name="vendor_invoice_date" id="vendor_invoice_date" class="form-control" value="{{ old('vendor_invoice_date') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="form-card">
            <div class="form-card-header"><h5>Bill Items</h5></div>
            <div class="form-card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="data-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th style="width:90px;">Qty</th>
                                <th style="width:110px;">Rate</th>
                                <th style="width:140px;">Tax</th>
                                <th style="width:80px;">Disc %</th>
                                <th style="width:120px;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr><td colspan="7" class="text-center text-muted">Select a GRN to load items</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="form-card">
            <div class="form-card-header"><h5>Summary</h5></div>
            <div class="form-card-body">
                <div class="summary-grid">
                    <div class="summary-left">
                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="summary-right">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="subtotal">₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax 1 (CGST/IGST)</span>
                                <span id="tax1Amount">₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax 2 (SGST)</span>
                                <span id="tax2Amount">₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Total Tax</span>
                                <span id="taxAmount">₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Discount</span>
                                <span id="discountAmount">-₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <label style="display:flex;align-items:center;gap:8px;">
                                    Shipping
                                    <input type="number" name="shipping_charge" id="shippingCharge" class="form-control" style="width:100px;" value="0" min="0" step="0.01">
                                </label>
                                <span id="shippingDisplay">₹0.00</span>
                            </div>
                            <div class="summary-row">
                                <label style="display:flex;align-items:center;gap:8px;">
                                    Adjustment
                                    <input type="number" name="adjustment" id="adjustment" class="form-control" style="width:100px;" value="0" step="0.01">
                                </label>
                                <span id="adjustmentDisplay">₹0.00</span>
                            </div>
                            <div class="summary-row total">
                                <span>Grand Total</span>
                                <span id="grandTotal">₹0.00</span>
                            </div>
                        </div>
                        <input type="hidden" name="subtotal" id="subtotalInput">
                        <input type="hidden" name="tax_amount" id="taxAmountInput">
                        <input type="hidden" name="discount_amount" id="discountAmountInput">
                        <input type="hidden" name="grand_total" id="grandTotalInput">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.purchase.bills.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Bill</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grnSelect = document.getElementById('grn_id');
    
    grnSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (!this.value) {
            document.getElementById('vendor_display').value = '';
            document.getElementById('vendor_id').value = '';
            document.getElementById('itemsBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Select a GRN to load items</td></tr>';
            resetTotals();
            return;
        }
        
        document.getElementById('vendor_display').value = option.dataset.vendorName;
        document.getElementById('vendor_id').value = option.dataset.vendor;
        document.getElementById('purchase_order_id').value = option.dataset.po;
        document.getElementById('warehouse_id').value = option.dataset.warehouse;
        document.getElementById('vendor_invoice_no').value = option.dataset.invoice || '';
        document.getElementById('vendor_invoice_date').value = option.dataset.invoiceDate || '';
        
        loadGRNItems(this.value);
    });
    
    if (grnSelect.value) {
        grnSelect.dispatchEvent(new Event('change'));
    }
    
    document.getElementById('shippingCharge').addEventListener('input', calculateTotals);
    document.getElementById('adjustment').addEventListener('input', calculateTotals);
});

function loadGRNItems(grnId) {
    fetch(`/admin/purchase/grn/${grnId}?format=json`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            if (data.items && data.items.length > 0) {
                data.items.forEach((item, idx) => {
                    const qty = parseFloat(item.accepted_qty) || 0;
                    const rate = parseFloat(item.rate) || 0;
                    
                    // Get tax from GRN item (inherited from PO)
                    const tax1Id = item.tax_1_id || '';
                    const tax1Name = item.tax_1_name || '';
                    const tax1Rate = parseFloat(item.tax_1_rate) || 0;
                    const tax2Id = item.tax_2_id || '';
                    const tax2Name = item.tax_2_name || '';
                    const tax2Rate = parseFloat(item.tax_2_rate) || 0;
                    const totalTaxRate = tax1Rate + tax2Rate;
                    
                    // Tax display
                    let taxDisplay = '';
                    if (tax1Name) taxDisplay += `<span class="tax-badge">${tax1Name} (${tax1Rate}%)</span><br>`;
                    if (tax2Name) taxDisplay += `<span class="tax-badge">${tax2Name} (${tax2Rate}%)</span>`;
                    if (!taxDisplay) taxDisplay = '<span class="text-muted">No Tax</span>';
                    
                    html += `
                    <tr class="item-row">
                        <td>
                            <div class="product-name">${item.product?.name || 'N/A'}</div>
                            <div class="product-sku">${item.product?.sku || ''}</div>
                            <input type="hidden" name="items[${idx}][grn_item_id]" value="${item.id}">
                            <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${idx}][variation_id]" value="${item.variation_id || ''}">
                            <input type="hidden" name="items[${idx}][unit_id]" value="${item.unit_id || ''}">
                            <input type="hidden" name="items[${idx}][description]" value="${item.product?.name || ''}">
                            <!-- Tax hidden fields -->
                            <input type="hidden" name="items[${idx}][tax_1_id]" value="${tax1Id}">
                            <input type="hidden" name="items[${idx}][tax_1_name]" value="${tax1Name}">
                            <input type="hidden" name="items[${idx}][tax_1_rate]" class="item-tax1-rate" value="${tax1Rate}">
                            <input type="hidden" name="items[${idx}][tax_2_id]" value="${tax2Id}">
                            <input type="hidden" name="items[${idx}][tax_2_name]" value="${tax2Name}">
                            <input type="hidden" name="items[${idx}][tax_2_rate]" class="item-tax2-rate" value="${tax2Rate}">
                        </td>
                        <td>${item.unit?.short_name || item.unit?.name || '-'}</td>
                        <td>
                            <input type="number" name="items[${idx}][qty]" class="form-control item-qty" 
                                value="${qty}" min="0" max="${qty}" step="0.001" onchange="calculateRow(this)">
                        </td>
                        <td>
                            <input type="number" name="items[${idx}][rate]" class="form-control item-rate" 
                                value="${rate}" min="0" step="0.01" onchange="calculateRow(this)">
                        </td>
                        <td class="tax-info">
                            ${taxDisplay}
                            <input type="hidden" class="item-tax-total" value="${totalTaxRate}">
                        </td>
                        <td>
                            <input type="number" name="items[${idx}][discount_percent]" class="form-control item-discount" 
                                value="0" min="0" max="100" step="0.01" onchange="calculateRow(this)">
                        </td>
                        <td class="text-end">
                            <strong class="item-total">₹0.00</strong>
                            <input type="hidden" name="items[${idx}][total]" class="item-total-input">
                            <input type="hidden" class="item-tax1-amount">
                            <input type="hidden" class="item-tax2-amount">
                            <input type="hidden" class="item-discount-amount">
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="7" class="text-center text-muted">No items found</td></tr>';
            }
            document.getElementById('itemsBody').innerHTML = html;
            
            document.querySelectorAll('.item-row').forEach(row => {
                calculateRow(row.querySelector('.item-qty'));
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('itemsBody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading items</td></tr>';
        });
}

function calculateRow(input) {
    const row = input.closest('tr');
    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
    const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
    const tax1Rate = parseFloat(row.querySelector('.item-tax1-rate').value) || 0;
    const tax2Rate = parseFloat(row.querySelector('.item-tax2-rate').value) || 0;
    const discountPercent = parseFloat(row.querySelector('.item-discount').value) || 0;
    
    const lineTotal = qty * rate;
    const discountAmount = lineTotal * (discountPercent / 100);
    const taxableAmount = lineTotal - discountAmount;
    const tax1Amount = taxableAmount * (tax1Rate / 100);
    const tax2Amount = taxableAmount * (tax2Rate / 100);
    const total = taxableAmount + tax1Amount + tax2Amount;
    
    row.querySelector('.item-total').textContent = '₹' + total.toFixed(2);
    row.querySelector('.item-total-input').value = total.toFixed(2);
    row.querySelector('.item-tax1-amount').value = tax1Amount.toFixed(2);
    row.querySelector('.item-tax2-amount').value = tax2Amount.toFixed(2);
    row.querySelector('.item-discount-amount').value = discountAmount.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0, tax1Total = 0, tax2Total = 0, discountTotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
        subtotal += qty * rate;
        tax1Total += parseFloat(row.querySelector('.item-tax1-amount').value) || 0;
        tax2Total += parseFloat(row.querySelector('.item-tax2-amount').value) || 0;
        discountTotal += parseFloat(row.querySelector('.item-discount-amount').value) || 0;
    });
    
    const taxTotal = tax1Total + tax2Total;
    const shipping = parseFloat(document.getElementById('shippingCharge').value) || 0;
    const adjustment = parseFloat(document.getElementById('adjustment').value) || 0;
    const grandTotal = subtotal - discountTotal + taxTotal + shipping + adjustment;
    
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('tax1Amount').textContent = '₹' + tax1Total.toFixed(2);
    document.getElementById('tax2Amount').textContent = '₹' + tax2Total.toFixed(2);
    document.getElementById('taxAmount').textContent = '₹' + taxTotal.toFixed(2);
    document.getElementById('discountAmount').textContent = '-₹' + discountTotal.toFixed(2);
    document.getElementById('shippingDisplay').textContent = '₹' + shipping.toFixed(2);
    document.getElementById('adjustmentDisplay').textContent = (adjustment >= 0 ? '+' : '') + '₹' + adjustment.toFixed(2);
    document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
    
    document.getElementById('subtotalInput').value = subtotal.toFixed(2);
    document.getElementById('taxAmountInput').value = taxTotal.toFixed(2);
    document.getElementById('discountAmountInput').value = discountTotal.toFixed(2);
    document.getElementById('grandTotalInput').value = grandTotal.toFixed(2);
}

function resetTotals() {
    document.getElementById('subtotal').textContent = '₹0.00';
    document.getElementById('tax1Amount').textContent = '₹0.00';
    document.getElementById('tax2Amount').textContent = '₹0.00';
    document.getElementById('taxAmount').textContent = '₹0.00';
    document.getElementById('discountAmount').textContent = '-₹0.00';
    document.getElementById('grandTotal').textContent = '₹0.00';
}
</script>
