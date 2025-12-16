@include('purchase::partials.styles')

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('admin.purchase.bills.show', $bill->id) }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1>✏️ Edit Vendor Bill - {{ $bill->bill_number }}</h1>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.purchase.bills.update', $bill->id) }}" method="POST" id="billForm">
        @csrf
        @method('PUT')
        
        <div class="form-card">
            <div class="form-card-header"><h5>📋 Bill Information</h5></div>
            <div class="form-card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bill Number</label>
                        <input type="text" class="form-control" value="{{ $bill->bill_number }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vendor</label>
                        <input type="text" class="form-control" value="{{ $bill->vendor->name }}" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bill Date <span class="required">*</span></label>
                        <input type="date" name="bill_date" class="form-control" value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $bill->due_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vendor Invoice No</label>
                        <input type="text" name="vendor_invoice_no" class="form-control" value="{{ old('vendor_invoice_no', $bill->vendor_invoice_no) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vendor Invoice Date</label>
                        <input type="date" name="vendor_invoice_date" class="form-control" value="{{ old('vendor_invoice_date', $bill->vendor_invoice_date?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="form-card">
            <div class="form-card-header"><h5>📦 Bill Items</h5></div>
            <div class="form-card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="data-table">
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
                        <tbody>
                            @foreach($bill->items as $idx => $item)
                            <tr class="item-row">
                                <td>
                                    <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                    <div class="product-sku">{{ $item->product->sku ?? '' }}</div>
                                    <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $item->id }}">
                                    <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                </td>
                                <td>{{ $item->unit->short_name ?? $item->unit->name ?? '-' }}</td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][qty]" class="form-control item-qty" 
                                        value="{{ $item->qty }}" min="0" step="0.001" onchange="calculateRow(this)">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][rate]" class="form-control item-rate" 
                                        value="{{ $item->rate }}" min="0" step="0.01" onchange="calculateRow(this)">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][tax_percent]" class="form-control item-tax" 
                                        value="{{ $item->tax_percent }}" min="0" max="100" step="0.01" onchange="calculateRow(this)">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $idx }}][discount_percent]" class="form-control item-discount" 
                                        value="{{ $item->discount_percent }}" min="0" max="100" step="0.01" onchange="calculateRow(this)">
                                </td>
                                <td class="text-end">
                                    <strong class="item-total">₹{{ number_format($item->total, 2) }}</strong>
                                    <input type="hidden" name="items[{{ $idx }}][total]" class="item-total-input" value="{{ $item->total }}">
                                    <input type="hidden" name="items[{{ $idx }}][tax_amount]" class="item-tax-amount" value="{{ $item->tax_amount }}">
                                    <input type="hidden" name="items[{{ $idx }}][discount_amount]" class="item-discount-amount" value="{{ $item->discount_amount }}">
                                </td>
                            </tr>
                            @endforeach
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
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $bill->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="summary-right">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="subtotal">₹{{ number_format($bill->subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax Amount</span>
                                <span id="taxAmount">₹{{ number_format($bill->tax_amount, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Discount</span>
                                <span id="discountAmount">-₹{{ number_format($bill->discount_amount, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <label style="display:flex;align-items:center;gap:8px;">
                                    Shipping
                                    <input type="number" name="shipping_charge" id="shippingCharge" class="form-control" style="width:100px;" value="{{ $bill->shipping_charge }}" min="0" step="0.01">
                                </label>
                                <span id="shippingDisplay">₹{{ number_format($bill->shipping_charge, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <label style="display:flex;align-items:center;gap:8px;">
                                    Adjustment
                                    <input type="number" name="adjustment" id="adjustment" class="form-control" style="width:100px;" value="{{ $bill->adjustment }}" step="0.01">
                                </label>
                                <span id="adjustmentDisplay">{{ $bill->adjustment >= 0 ? '+' : '' }}₹{{ number_format($bill->adjustment, 2) }}</span>
                            </div>
                            <div class="summary-row total">
                                <span>Grand Total</span>
                                <span id="grandTotal">₹{{ number_format($bill->grand_total, 2) }}</span>
                            </div>
                        </div>
                        <input type="hidden" name="subtotal" id="subtotalInput" value="{{ $bill->subtotal }}">
                        <input type="hidden" name="tax_amount" id="taxAmountInput" value="{{ $bill->tax_amount }}">
                        <input type="hidden" name="discount_amount" id="discountAmountInput" value="{{ $bill->discount_amount }}">
                        <input type="hidden" name="grand_total" id="grandTotalInput" value="{{ $bill->grand_total }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.purchase.bills.show', $bill->id) }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">💾 Update Bill</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('shippingCharge').addEventListener('input', calculateTotals);
    document.getElementById('adjustment').addEventListener('input', calculateTotals);
    
    // Calculate all rows on load
    document.querySelectorAll('.item-row').forEach(row => {
        calculateRow(row.querySelector('.item-qty'));
    });
});

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
