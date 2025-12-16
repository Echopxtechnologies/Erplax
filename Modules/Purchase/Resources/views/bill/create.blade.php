@include('purchase::partials.styles')

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.purchase.bills.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1>📄 Create Vendor Bill</h1>
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
            <div class="form-card-header"><h5>📋 Bill Information</h5></div>
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
            <div class="form-card-header"><h5>📦 Bill Items</h5></div>
            <div class="form-card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="data-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th style="width:100px;">Qty</th>
                                <th style="width:120px;">Rate</th>
                                <th style="width:80px;">Tax %</th>
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
            <div class="form-card-header"><h5>🧮 Summary</h5></div>
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
                                <span>Tax Amount</span>
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
            <a href="{{ route('admin.purchase.bills.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" name="action" value="draft" class="btn btn-outline">💾 Save as Draft</button>
            <button type="submit" name="action" value="submit" class="btn btn-primary">📤 Save & Submit</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grnSelect = document.getElementById('grn_id');
    
    // Load GRN items on change
    grnSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (!this.value) {
            document.getElementById('vendor_display').value = '';
            document.getElementById('vendor_id').value = '';
            document.getElementById('itemsBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Select a GRN to load items</td></tr>';
            return;
        }
        
        document.getElementById('vendor_display').value = option.dataset.vendorName;
        document.getElementById('vendor_id').value = option.dataset.vendor;
        document.getElementById('purchase_order_id').value = option.dataset.po;
        document.getElementById('warehouse_id').value = option.dataset.warehouse;
        document.getElementById('vendor_invoice_no').value = option.dataset.invoice || '';
        document.getElementById('vendor_invoice_date').value = option.dataset.invoiceDate || '';
        
        // Load items via AJAX
        loadGRNItems(this.value);
    });
    
    // Trigger change if pre-selected
    if (grnSelect.value) {
        grnSelect.dispatchEvent(new Event('change'));
    }
    
    // Shipping & Adjustment change
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
                    const taxPercent = parseFloat(item.product?.tax_percent) || 18;
                    
                    html += `
                    <tr class="item-row">
                        <td>
                            <div class="product-name">${item.product?.name || 'N/A'}</div>
                            <div class="product-sku">${item.product?.sku || ''}</div>
                            <input type="hidden" name="items[${idx}][grn_item_id]" value="${item.id}">
                            <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${idx}][variation_id]" value="${item.variation_id || ''}">
                            <input type="hidden" name="items[${idx}][unit_id]" value="${item.unit_id || ''}">
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
                        <td>
                            <input type="number" name="items[${idx}][tax_percent]" class="form-control item-tax" 
                                value="${taxPercent}" min="0" max="100" step="0.01" onchange="calculateRow(this)">
                        </td>
                        <td>
                            <input type="number" name="items[${idx}][discount_percent]" class="form-control item-discount" 
                                value="0" min="0" max="100" step="0.01" onchange="calculateRow(this)">
                        </td>
                        <td class="text-end">
                            <strong class="item-total">₹0.00</strong>
                            <input type="hidden" name="items[${idx}][total]" class="item-total-input">
                            <input type="hidden" name="items[${idx}][tax_amount]" class="item-tax-amount">
                            <input type="hidden" name="items[${idx}][discount_amount]" class="item-discount-amount">
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="7" class="text-center text-muted">No items found</td></tr>';
            }
            document.getElementById('itemsBody').innerHTML = html;
            
            // Calculate all rows
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
    const taxPercent = parseFloat(row.querySelector('.item-tax').value) || 0;
    const discountPercent = parseFloat(row.querySelector('.item-discount').value) || 0;
    
    const subtotal = qty * rate;
    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = taxableAmount * (taxPercent / 100);
    const total = taxableAmount + taxAmount;
    
    row.querySelector('.item-total').textContent = '₹' + total.toFixed(2);
    row.querySelector('.item-total-input').value = total.toFixed(2);
    row.querySelector('.item-tax-amount').value = taxAmount.toFixed(2);
    row.querySelector('.item-discount-amount').value = discountAmount.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0, taxAmount = 0, discountAmount = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
        subtotal += qty * rate;
        taxAmount += parseFloat(row.querySelector('.item-tax-amount').value) || 0;
        discountAmount += parseFloat(row.querySelector('.item-discount-amount').value) || 0;
    });
    
    const shipping = parseFloat(document.getElementById('shippingCharge').value) || 0;
    const adjustment = parseFloat(document.getElementById('adjustment').value) || 0;
    const grandTotal = subtotal - discountAmount + taxAmount + shipping + adjustment;
    
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('taxAmount').textContent = '₹' + taxAmount.toFixed(2);
    document.getElementById('discountAmount').textContent = '-₹' + discountAmount.toFixed(2);
    document.getElementById('shippingDisplay').textContent = '₹' + shipping.toFixed(2);
    document.getElementById('adjustmentDisplay').textContent = (adjustment >= 0 ? '+' : '') + '₹' + adjustment.toFixed(2);
    document.getElementById('grandTotal').textContent = '₹' + grandTotal.toFixed(2);
    
    document.getElementById('subtotalInput').value = subtotal.toFixed(2);
    document.getElementById('taxAmountInput').value = taxAmount.toFixed(2);
    document.getElementById('discountAmountInput').value = discountAmount.toFixed(2);
    document.getElementById('grandTotalInput').value = grandTotal.toFixed(2);
}
</script>
