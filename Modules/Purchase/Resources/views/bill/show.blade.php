@include('purchase::partials.styles')

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.purchase.bills.index') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1>
                {{ $bill->bill_number }}
                <span class="badge badge-{{ strtolower($bill->status) }} badge-lg">{{ $bill->status }}</span>
                <span class="badge badge-{{ $bill->payment_status == 'PAID' ? 'success' : ($bill->payment_status == 'PARTIALLY_PAID' ? 'warning' : 'danger') }} badge-lg">
                    {{ str_replace('_', ' ', $bill->payment_status) }}
                </span>
                @if($bill->is_overdue)
                <span class="badge badge-danger badge-lg">⚠️ {{ $bill->days_overdue }} Days Overdue</span>
                @endif
            </h1>
        </div>
        <div class="header-actions">
            @if($bill->canEdit())
            <a href="{{ route('admin.purchase.bills.edit', $bill->id) }}" class="btn btn-outline">✏️ Edit</a>
            @endif
            @if($bill->canSubmit())
            <form action="{{ route('admin.purchase.bills.submit', $bill->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">📤 Submit</button>
            </form>
            @endif
            @if($bill->canApprove())
            <form action="{{ route('admin.purchase.bills.approve', $bill->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">✅ Approve</button>
            </form>
            <button type="button" class="btn btn-danger" onclick="showRejectModal()">❌ Reject</button>
            @endif
            @if($bill->canPay())
            <button type="button" class="btn btn-primary" onclick="showPaymentModal()">💰 Record Payment</button>
            @endif
            <a href="{{ route('admin.purchase.bills.pdf', $bill->id) }}" class="btn btn-outline" target="_blank">📄 Invoice PDF</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($bill->status === 'REJECTED' && $bill->rejection_reason)
    <div class="rejection-box">
        <h6>❌ Rejection Reason</h6>
        <p>{{ $bill->rejection_reason }}</p>
    </div>
    @endif

    <!-- Payment Summary Bar -->
    @if($bill->status === 'APPROVED')
    <div class="payment-summary-bar">
        <div class="payment-stat">
            <span class="payment-stat-label">Total Amount</span>
            <span class="payment-stat-value">₹{{ number_format($bill->grand_total, 2) }}</span>
        </div>
        <div class="payment-progress-container">
            @php $paidPercent = $bill->grand_total > 0 ? min(100, ($bill->paid_amount / $bill->grand_total) * 100) : 0; @endphp
            <div class="payment-progress">
                <div class="payment-progress-bar" style="width: {{ $paidPercent }}%;"></div>
            </div>
            <span class="payment-progress-text">{{ number_format($paidPercent, 1) }}% Paid</span>
        </div>
        <div class="payment-stat">
            <span class="payment-stat-label">Paid</span>
            <span class="payment-stat-value text-success">₹{{ number_format($bill->paid_amount, 2) }}</span>
        </div>
        <div class="payment-stat">
            <span class="payment-stat-label">Balance Due</span>
            <span class="payment-stat-value text-danger">₹{{ number_format($bill->balance_due, 2) }}</span>
        </div>
    </div>
    @endif

    <div class="grid-2">
        <!-- Bill Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">📋 Bill Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Bill Number</div><div class="detail-value"><strong>{{ $bill->bill_number }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">Bill Date</div><div class="detail-value">{{ $bill->bill_date->format('d M Y') }}</div></div>
                <div class="detail-row"><div class="detail-label">Due Date</div><div class="detail-value">{{ $bill->due_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Vendor Invoice No</div><div class="detail-value">{{ $bill->vendor_invoice_no ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Invoice Date</div><div class="detail-value">{{ $bill->vendor_invoice_date?->format('d M Y') ?? '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Purchase Order</div><div class="detail-value">@if($bill->purchaseOrder)<a href="{{ route('admin.purchase.orders.show', $bill->purchase_order_id) }}">{{ $bill->purchaseOrder->po_number }}</a>@else - @endif</div></div>
                <div class="detail-row"><div class="detail-label">GRN</div><div class="detail-value">@if($bill->grn)<a href="{{ route('admin.purchase.grn.show', $bill->grn_id) }}">{{ $bill->grn->grn_number }}</a>@else - @endif</div></div>
            </div>
        </div>

        <!-- Vendor Details -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h5 class="detail-card-title">🏢 Vendor Details</h5>
            </div>
            <div class="detail-card-body">
                <div class="detail-row"><div class="detail-label">Vendor Name</div><div class="detail-value"><strong>{{ $bill->vendor->name ?? '-' }}</strong></div></div>
                @if($bill->vendor?->billing_address)
                <div class="detail-row"><div class="detail-label">Address</div><div class="detail-value">{{ $bill->vendor->billing_address }}</div></div>
                @endif
                @if($bill->vendor?->billing_city)
                <div class="detail-row"><div class="detail-label">City / State</div><div class="detail-value">{{ $bill->vendor->billing_city }}{{ $bill->vendor->billing_state ? ', ' . $bill->vendor->billing_state : '' }} {{ $bill->vendor->billing_pincode }}</div></div>
                @endif
                @if($bill->vendor?->gst_number)
                <div class="detail-row"><div class="detail-label">GSTIN</div><div class="detail-value">{{ $bill->vendor->gst_number }}</div></div>
                @endif
                
                @if($vendorBank)
                <div style="margin-top: 16px;">
                    <div class="bank-card">
                        <div class="bank-title">🏦 Bank Details</div>
                        <div class="bank-row"><span class="bank-label">Account Holder</span><span class="bank-value">{{ $vendorBank->account_holder_name }}</span></div>
                        <div class="bank-row"><span class="bank-label">Bank Name</span><span class="bank-value">{{ $vendorBank->bank_name }}</span></div>
                        <div class="bank-row"><span class="bank-label">Account No</span><span class="bank-value">{{ $vendorBank->account_number }}</span></div>
                        @if($vendorBank->ifsc_code)<div class="bank-row"><span class="bank-label">IFSC Code</span><span class="bank-value">{{ $vendorBank->ifsc_code }}</span></div>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📦 Bill Items</h5>
        </div>
        <div class="detail-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>HSN</th>
                            <th>Unit</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Rate</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->items as $idx => $item)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <div class="product-name">{{ $item->product->name ?? 'N/A' }}</div>
                                <div class="product-sku">{{ $item->product->sku ?? '' }}</div>
                            </td>
                            <td>{{ $item->product->hsn_code ?? '-' }}</td>
                            <td>{{ $item->unit->short_name ?? $item->unit->name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->qty, 3) }}</td>
                            <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                            <td class="text-end">₹{{ number_format($item->tax_amount, 2) }} <small>({{ $item->tax_percent }}%)</small></td>
                            <td class="text-end">₹{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="text-end"><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-end"><strong>Subtotal</strong></td>
                            <td class="text-end">₹{{ number_format($bill->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end">Tax</td>
                            <td class="text-end">₹{{ number_format($bill->tax_amount, 2) }}</td>
                        </tr>
                        @if($bill->discount_amount > 0)
                        <tr>
                            <td colspan="8" class="text-end">Discount</td>
                            <td class="text-end">-₹{{ number_format($bill->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($bill->shipping_charge > 0)
                        <tr>
                            <td colspan="8" class="text-end">Shipping</td>
                            <td class="text-end">₹{{ number_format($bill->shipping_charge, 2) }}</td>
                        </tr>
                        @endif
                        @if($bill->adjustment != 0)
                        <tr>
                            <td colspan="8" class="text-end">Adjustment</td>
                            <td class="text-end">{{ $bill->adjustment >= 0 ? '+' : '' }}₹{{ number_format($bill->adjustment, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td colspan="8" class="text-end"><strong>Grand Total</strong></td>
                            <td class="text-end"><strong>₹{{ number_format($bill->grand_total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">💳 Payment History</h5>
        </div>
        <div class="detail-card-body">
            @if($bill->payments->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->payment_number }}</strong></td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>{{ $payment->paymentMethod->name ?? 'N/A' }}</td>
                            <td>
                                {{ $payment->reference_no ?? '-' }}
                                @if($payment->cheque_no)
                                <br><small>Cheque: {{ $payment->cheque_no }}</small>
                                @endif
                            </td>
                            <td class="text-end"><strong class="text-success">₹{{ number_format($payment->amount, 2) }}</strong></td>
                            <td><span class="badge badge-{{ strtolower($payment->status) }}">{{ $payment->status }}</span></td>
                            <td>
                                <a href="{{ route('admin.purchase.bills.payment.receipt', $payment->id) }}" class="btn btn-sm btn-outline" target="_blank">🧾 Receipt</a>
                            </td>
                        </tr>
                        @if($payment->notes)
                        <tr>
                            <td></td>
                            <td colspan="6" style="padding-top:0;"><small class="text-muted">Note: {{ $payment->notes }}</small></td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Paid</strong></td>
                            <td class="text-end"><strong class="text-success">₹{{ number_format($bill->paid_amount, 2) }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Balance Due</strong></td>
                            <td class="text-end"><strong class="text-danger">₹{{ number_format($bill->balance_due, 2) }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p>💸 No payments recorded yet</p>
                @if($bill->canPay())
                <button type="button" class="btn btn-primary" onclick="showPaymentModal()">💰 Record First Payment</button>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if($bill->notes)
    <div class="detail-card">
        <div class="detail-card-header">
            <h5 class="detail-card-title">📝 Notes</h5>
        </div>
        <div class="detail-card-body">
            <p style="margin:0;white-space:pre-wrap;color:var(--text-primary);">{{ $bill->notes }}</p>
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
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created By</div><div class="detail-value">{{ $bill->creator->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Created At</div><div class="detail-value">{{ $bill->created_at->format('d M Y, h:i A') }}</div></div>
                @if($bill->approved_at)
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved By</div><div class="detail-value">{{ $bill->approver->name ?? '-' }}</div></div>
                <div class="detail-row" style="display:block;border:none;"><div class="detail-label">Approved At</div><div class="detail-value">{{ $bill->approved_at->format('d M Y, h:i A') }}</div></div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal" id="paymentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>💰 Record Payment</h5>
            <button type="button" class="modal-close" onclick="hidePaymentModal()">&times;</button>
        </div>
        <form action="{{ route('admin.purchase.bills.payment', $bill->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="payment-info-box">
                    <div><span>Grand Total:</span> <strong>₹{{ number_format($bill->grand_total, 2) }}</strong></div>
                    <div><span>Paid:</span> <strong class="text-success">₹{{ number_format($bill->paid_amount, 2) }}</strong></div>
                    <div><span>Balance:</span> <strong class="text-danger">₹{{ number_format($bill->balance_due, 2) }}</strong></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Payment Date <span class="required">*</span></label>
                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount <span class="required">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" max="{{ $bill->balance_due }}" value="{{ $bill->balance_due }}" required>
                    <small class="form-text">Max: ₹{{ number_format($bill->balance_due, 2) }}</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Method <span class="required">*</span></label>
                    <select name="payment_method_id" class="form-control" required onchange="toggleChequeFields(this)">
                        <option value="">-- Select Method --</option>
                        @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}" data-slug="{{ $method->slug }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Reference No / UTR</label>
                    <input type="text" name="reference_no" class="form-control" placeholder="Transaction ID, UTR, etc.">
                </div>
                <div class="cheque-fields" id="chequeFields">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Cheque No</label>
                            <input type="text" name="cheque_no" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cheque Date</label>
                            <input type="date" name="cheque_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Payment remarks..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hidePaymentModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">💾 Record Payment</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>❌ Reject Bill</h5>
            <button type="button" class="modal-close" onclick="hideRejectModal()">&times;</button>
        </div>
        <form action="{{ route('admin.purchase.bills.reject', $bill->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Rejection Reason <span class="required">*</span></label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject Bill</button>
            </div>
        </form>
    </div>
</div>

<style>
.payment-summary-bar {
    display: flex;
    align-items: center;
    gap: 24px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 20px;
}
.payment-stat { text-align: center; }
.payment-stat-label { font-size: 12px; color: var(--text-muted); display: block; }
.payment-stat-value { font-size: 18px; font-weight: 700; color: var(--text-primary); }
.payment-progress-container { flex: 1; }
.payment-progress { height: 12px; background: var(--body-bg); border-radius: 6px; overflow: hidden; }
.payment-progress-bar { height: 100%; background: linear-gradient(90deg, #10b981, #34d399); transition: width 0.3s; }
.payment-progress-text { font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block; text-align: center; }
.text-success { color: #10b981 !important; }
.text-danger { color: #ef4444 !important; }
.empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); }
.empty-state p { font-size: 16px; margin-bottom: 16px; }
.total-row { background: var(--body-bg); }
.payment-info-box { display: flex; gap: 20px; background: var(--body-bg); padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
.payment-info-box > div { flex: 1; text-align: center; }
.payment-info-box span { font-size: 12px; color: var(--text-muted); display: block; }
.btn-sm { padding: 4px 10px; font-size: 12px; }
</style>

<script>
function showPaymentModal() { document.getElementById('paymentModal').classList.add('show'); }
function hidePaymentModal() { document.getElementById('paymentModal').classList.remove('show'); }
function showRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }

function toggleChequeFields(select) {
    const slug = select.options[select.selectedIndex]?.dataset?.slug || '';
    const chequeFields = document.getElementById('chequeFields');
    chequeFields.classList.toggle('show', slug.toLowerCase() === 'cheque');
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
