<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Bill - {{ $bill->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        .container { padding: 20px; }
        
        /* Header */
        .header { display: table; width: 100%; margin-bottom: 20px; border-bottom: 2px solid {{ $pdfSettings['primary_color'] ?? '#2563eb' }}; padding-bottom: 15px; }
        .header-left { display: table-cell; width: 60%; vertical-align: top; }
        .header-right { display: table-cell; width: 40%; vertical-align: top; text-align: right; }
        .company-logo { max-height: 40px; margin-bottom: 5px; }
        .company-name { font-size: 18px; font-weight: bold; color: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; margin-bottom: 5px; }
        .company-details { font-size: 9px; color: #666; line-height: 1.5; }
        .doc-title { font-size: 22px; font-weight: bold; color: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; margin-bottom: 8px; }
        .doc-number { font-size: 12px; color: #666; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 9px; font-weight: bold; margin-top: 5px; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-draft { background: #fef3c7; color: #92400e; }
        .status-pending { background: #dbeafe; color: #1e40af; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .payment-paid { background: #d1fae5; color: #065f46; }
        .payment-unpaid { background: #fee2e2; color: #991b1b; }
        .payment-partially_paid { background: #fef3c7; color: #92400e; }
        
        /* Info Sections */
        .info-section { display: table; width: 100%; margin-bottom: 15px; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .info-box:first-child { border-right: none; }
        .info-title { font-size: 10px; font-weight: bold; color: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; margin-bottom: 8px; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; }
        .info-row { margin-bottom: 3px; }
        .info-label { color: #666; display: inline-block; width: 100px; }
        .info-value { font-weight: 500; }
        
        /* Quick Info Row */
        .quick-info { display: table; width: 100%; margin-bottom: 15px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .quick-info-cell { display: table-cell; width: 25%; padding: 10px; border-right: 1px solid #e2e8f0; text-align: center; }
        .quick-info-cell:last-child { border-right: none; }
        .quick-info-label { font-size: 8px; color: #888; text-transform: uppercase; margin-bottom: 3px; }
        .quick-info-value { font-size: 11px; font-weight: bold; color: #333; }
        
        /* Items Table */
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; color: white; padding: 8px 5px; font-size: 9px; text-align: left; font-weight: 600; }
        table.items th.center { text-align: center; }
        table.items th.right { text-align: right; }
        table.items td { padding: 8px 5px; border-bottom: 1px solid #e2e8f0; font-size: 9px; vertical-align: top; }
        table.items td.center { text-align: center; }
        table.items td.right { text-align: right; }
        table.items tr:nth-child(even) { background: #f8fafc; }
        table.items tfoot td { background: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1; }
        
        /* Summary Section */
        .summary-section { display: table; width: 100%; margin-top: 15px; }
        .summary-left { display: table-cell; width: 50%; vertical-align: top; padding-right: 20px; }
        .summary-right { display: table-cell; width: 50%; vertical-align: top; }
        
        /* Amount in Words */
        .amount-words { background: #fffbeb; border: 1px solid #fde047; border-radius: 4px; padding: 8px 10px; font-size: 9px; color: #92400e; margin-bottom: 10px; }
        .amount-words strong { color: #78350f; }
        
        /* Notes/Terms Box */
        .notes-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 10px; margin-bottom: 10px; }
        .notes-title { font-weight: bold; color: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; margin-bottom: 5px; font-size: 9px; text-transform: uppercase; }
        .notes-content { font-size: 8px; color: #666; line-height: 1.5; }
        
        /* Totals Table */
        table.totals { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; }
        table.totals td { padding: 6px 10px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        table.totals td:first-child { text-align: left; color: #666; background: #f8fafc; width: 60%; }
        table.totals td:last-child { text-align: right; font-weight: 500; }
        table.totals tr.grand { background: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; color: white; }
        table.totals tr.grand td { font-size: 11px; font-weight: bold; padding: 8px 10px; background: {{ $pdfSettings['primary_color'] ?? '#1e40af' }}; }
        table.totals tr.paid td { background: #d1fae5; color: #065f46; }
        table.totals tr.paid td:first-child { background: #d1fae5; }
        table.totals tr.balance td { background: #fee2e2; color: #991b1b; font-weight: bold; }
        table.totals tr.balance td:first-child { background: #fee2e2; }
        
        /* Signature Section */
        .signature-section { margin-top: 40px; display: table; width: 100%; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 150px; margin: 30px auto 5px; }
        .signature-text { font-size: 9px; color: #666; }
        .signature-name { font-size: 8px; color: #999; margin-top: 2px; }
        
        /* Footer */
        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if(($pdfSettings['show_logo'] ?? true) && !empty($companyLogo))
            <img src="{{ $companyLogo }}" class="company-logo" alt="Logo"><br>
            @endif
            <div class="company-name">{{ $companyName ?: config('app.name') }}</div>
            <div class="company-details">
                @if($companyAddress){{ $companyAddress }}<br>@endif
                @if($companyPhone)Phone: {{ $companyPhone }}<br>@endif
                @if($companyEmail)Email: {{ $companyEmail }}<br>@endif
                @if(($pdfSettings['show_gst'] ?? true) && $companyGst)GSTIN: {{ $companyGst }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">PURCHASE BILL</div>
            <div class="doc-number">
                <strong>{{ $bill->bill_number }}</strong><br>
                Date: {{ $bill->bill_date->format('d M Y') }}<br>
                <span class="status-badge status-{{ strtolower($bill->status) }}">{{ $bill->status }}</span>
                <span class="status-badge payment-{{ strtolower($bill->payment_status) }}">{{ str_replace('_', ' ', $bill->payment_status) }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Info Row -->
    <div class="quick-info">
        <div class="quick-info-cell">
            <div class="quick-info-label">Due Date</div>
            <div class="quick-info-value">{{ $bill->due_date?->format('d M Y') ?? '-' }}</div>
        </div>
        <div class="quick-info-cell">
            <div class="quick-info-label">Vendor Invoice #</div>
            <div class="quick-info-value">{{ $bill->vendor_invoice_no ?? '-' }}</div>
        </div>
        <div class="quick-info-cell">
            <div class="quick-info-label">Invoice Date</div>
            <div class="quick-info-value">{{ $bill->vendor_invoice_date?->format('d M Y') ?? '-' }}</div>
        </div>
        <div class="quick-info-cell">
            <div class="quick-info-label">Reference</div>
            <div class="quick-info-value">
                @if($bill->purchaseOrder)PO: {{ $bill->purchaseOrder->po_number }}@elseif($bill->grn)GRN: {{ $bill->grn->grn_number }}@else - @endif
            </div>
        </div>
    </div>

    <!-- Vendor & Company Info -->
    <div class="info-section">
        <div class="info-box">
            <div class="info-title">Vendor Details</div>
            <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">{{ $bill->vendor->name ?? '-' }}</div>
            @if($bill->vendor)
                @if($bill->vendor->billing_address)<div>{{ $bill->vendor->billing_address }}</div>@endif
                @if($bill->vendor->billing_city || $bill->vendor->billing_state)
                <div>{{ $bill->vendor->billing_city }}{{ $bill->vendor->billing_city && $bill->vendor->billing_state ? ', ' : '' }}{{ $bill->vendor->billing_state }} {{ $bill->vendor->billing_pincode }}</div>
                @endif
                @if($bill->vendor->phone)<div>Phone: {{ $bill->vendor->phone }}</div>@endif
                @if($bill->vendor->email)<div>Email: {{ $bill->vendor->email }}</div>@endif
                @if($bill->vendor->gst_number)<div style="margin-top: 5px; font-weight: bold;">GSTIN: {{ $bill->vendor->gst_number }}</div>@endif
            @endif
        </div>
        <div class="info-box">
            <div class="info-title">Bill To (Our Company)</div>
            <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">{{ $companyName }}</div>
            @if($companyAddress)<div>{{ $companyAddress }}</div>@endif
            @if($companyPhone)<div>Phone: {{ $companyPhone }}</div>@endif
            @if($companyEmail)<div>Email: {{ $companyEmail }}</div>@endif
            @if($companyGst)<div style="margin-top: 5px; font-weight: bold;">GSTIN: {{ $companyGst }}</div>@endif
        </div>
    </div>

    <!-- Items Table -->
    <table class="items">
        <thead>
            <tr>
                <th style="width:25px" class="center">#</th>
                <th>Item Description</th>
                <th style="width:50px" class="center">HSN</th>
                <th style="width:40px" class="center">Qty</th>
                <th style="width:60px" class="right">Rate</th>
                <th style="width:70px" class="right">Amount</th>
                @if($hasTax1)<th style="width:55px" class="right">{{ $tax1Name }}</th>@endif
                @if($hasTax2)<th style="width:55px" class="right">{{ $tax2Name }}</th>@endif
                <th style="width:70px" class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $i => $item)
            @php
                $taxableValue = ($item->qty * $item->rate) - ($item->discount_amount ?? 0);
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? $item->description ?? 'N/A' }}</strong>
                    @if($item->variation)<br><span style="color:#8b5cf6;font-size:8px">{{ $item->variation->variation_name ?: $item->variation->sku }}</span>@endif
                    @if($item->product && $item->product->sku)<br><span style="color:#666;font-size:8px">SKU: {{ $item->product->sku }}</span>@endif
                    @if(($item->discount_percent ?? 0) > 0)<br><span style="color:#059669;font-size:8px">Discount: {{ $item->discount_percent }}%</span>@endif
                </td>
                <td class="center">{{ $item->product->hsn_code ?? '-' }}</td>
                <td class="center">{{ number_format($item->qty, 2) }}</td>
                <td class="right">₹{{ number_format($item->rate, 2) }}</td>
                <td class="right">₹{{ number_format($taxableValue, 2) }}</td>
                @if($hasTax1)<td class="right">{{ $item->tax_1_amount ? '₹' . number_format($item->tax_1_amount, 2) : '-' }}</td>@endif
                @if($hasTax2)<td class="right">{{ $item->tax_2_amount ? '₹' . number_format($item->tax_2_amount, 2) : '-' }}</td>@endif
                <td class="right"><strong>₹{{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="center"></td>
                <td><strong>TOTAL</strong></td>
                <td class="center"></td>
                <td class="center"><strong>{{ number_format($bill->items->sum('qty'), 2) }}</strong></td>
                <td class="right"></td>
                <td class="right"><strong>₹{{ number_format($totalTaxableValue, 2) }}</strong></td>
                @if($hasTax1)<td class="right"><strong>₹{{ number_format($tax1Total, 2) }}</strong></td>@endif
                @if($hasTax2)<td class="right"><strong>₹{{ number_format($tax2Total, 2) }}</strong></td>@endif
                <td class="right"><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-left">
            <!-- Amount in Words -->
            <div class="amount-words">
                <strong>Amount in Words:</strong><br>
                {{ $amountInWords }}
            </div>
            
            <!-- Bank Details -->
            @if($vendorBank)
            <div class="notes-box">
                <div class="notes-title">Vendor Bank Details</div>
                <div class="notes-content">
                    <strong>{{ $vendorBank->account_holder_name ?? $bill->vendor->name }}</strong><br>
                    Bank: {{ $vendorBank->bank_name }}<br>
                    A/C No: {{ $vendorBank->account_number }}<br>
                    @if($vendorBank->ifsc_code)IFSC: {{ $vendorBank->ifsc_code }}<br>@endif
                    @if($vendorBank->upi_id)UPI: {{ $vendorBank->upi_id }}@endif
                </div>
            </div>
            @endif
            
            <!-- Notes -->
            @if(($pdfSettings['show_notes'] ?? true) && $bill->notes)
            <div class="notes-box">
                <div class="notes-title">Notes</div>
                <div class="notes-content">{!! nl2br(e($bill->notes)) !!}</div>
            </div>
            @endif
        </div>
        <div class="summary-right">
            <table class="totals">
                <tr>
                    <td>Subtotal</td>
                    <td>₹{{ number_format($totalTaxableValue, 2) }}</td>
                </tr>
                @if($hasTax1 && $tax1Total > 0)
                <tr>
                    <td>{{ $tax1Name }}</td>
                    <td>₹{{ number_format($tax1Total, 2) }}</td>
                </tr>
                @endif
                @if($hasTax2 && $tax2Total > 0)
                <tr>
                    <td>{{ $tax2Name }}</td>
                    <td>₹{{ number_format($tax2Total, 2) }}</td>
                </tr>
                @endif
                @if($bill->shipping_charge > 0)
                <tr>
                    <td>Shipping Charges</td>
                    <td>₹{{ number_format($bill->shipping_charge, 2) }}</td>
                </tr>
                @endif
                @if($bill->adjustment != 0)
                <tr>
                    <td>Round Off (+/-)</td>
                    <td>₹{{ number_format($bill->adjustment, 2) }}</td>
                </tr>
                @endif
                <tr class="grand">
                    <td>Grand Total</td>
                    <td>₹{{ number_format($bill->grand_total, 2) }}</td>
                </tr>
                <tr class="paid">
                    <td>Paid Amount</td>
                    <td>₹{{ number_format($bill->paid_amount, 2) }}</td>
                </tr>
                @if($bill->balance_due > 0)
                <tr class="balance">
                    <td>Balance Due</td>
                    <td>₹{{ number_format($bill->balance_due, 2) }}</td>
                </tr>
                @else
                <tr class="paid">
                    <td>Balance Due</td>
                    <td>₹0.00</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Signature Section -->
    @if($pdfSettings['show_signature'] ?? true)
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-text">Vendor Signature</div>
            <div class="signature-name">{{ $bill->vendor->name ?? '' }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-text">Authorized Signatory</div>
            <div class="signature-name">{{ $companyName }}</div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        This is a computer generated document | Generated on {{ now()->format('d M Y, h:i A') }}
    </div>
</div>
</body>
</html>
