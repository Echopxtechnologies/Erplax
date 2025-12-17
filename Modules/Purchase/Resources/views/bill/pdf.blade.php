<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $bill->bill_number }}</title>
    <style>
        @page { margin: 20px 25px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: {{ $pdfSettings['font_size'] }}pt; color: #333; line-height: 1.4; }
        
        /* Header */
        .header { background: {{ $pdfSettings['primary_color'] }}; color: #fff; padding: 15px 20px; margin: -20px -25px 15px -25px; }
        .header table { width: 100%; }
        .header-left { width: 60%; vertical-align: middle; }
        .header-right { width: 40%; text-align: right; vertical-align: middle; }
        .logo { max-height: 30px; margin-bottom: 3px; }
        .company-name { font-size: 13pt; font-weight: bold; }
        .company-info { font-size: 7pt; opacity: 0.9; line-height: 1.4; }
        .doc-title { font-size: 16pt; font-weight: bold; letter-spacing: 1px; }
        .doc-box { background: rgba(255,255,255,0.15); display: inline-block; padding: 4px 10px; margin-top: 4px; border-radius: 3px; }
        .doc-number { font-size: 9pt; font-weight: bold; }
        .doc-date { font-size: 7pt; opacity: 0.9; }
        
        /* Info Row */
        .info-row { margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        .info-row table { width: 100%; }
        .info-row td { width: 25%; vertical-align: top; }
        .info-label { font-size: 6pt; color: #888; text-transform: uppercase; letter-spacing: 0.3px; }
        .info-value { font-size: 8pt; color: #111; font-weight: 600; }
        .status { display: inline-block; padding: 2px 6px; border-radius: 8px; font-size: 6pt; font-weight: bold; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
        .status-partially_paid { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        
        /* Address */
        .address-row { margin-bottom: 12px; }
        .address-row table { width: 100%; }
        .address-row td { width: 48%; vertical-align: top; }
        .address-row td.gap { width: 4%; }
        .address-box { border: 1px solid #e5e7eb; border-radius: 3px; }
        .address-header { background: {{ $pdfSettings['secondary_color'] }}; padding: 5px 8px; font-size: 6pt; font-weight: bold; color: {{ $pdfSettings['primary_color'] }}; text-transform: uppercase; border-bottom: 2px solid {{ $pdfSettings['primary_color'] }}; }
        .address-body { padding: 8px; }
        .address-name { font-size: 9pt; font-weight: bold; color: #111; margin-bottom: 2px; }
        .address-text { font-size: 7pt; color: #555; line-height: 1.4; }
        .address-gst { margin-top: 4px; padding-top: 4px; border-top: 1px dashed #ddd; font-size: 7pt; color: #111; font-weight: 600; }
        
        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; border: 1px solid #d1d5db; }
        .items-table th { background: {{ $pdfSettings['secondary_color'] }}; border: 1px solid #d1d5db; padding: 5px 3px; font-size: 6pt; font-weight: bold; color: #374151; text-transform: uppercase; text-align: center; }
        .items-table td { border: 1px solid #e5e7eb; padding: 5px 3px; font-size: 7pt; color: #374151; vertical-align: middle; text-align: center; }
        .items-table td.l { text-align: left; padding-left: 5px; }
        .items-table td.r { text-align: right; padding-right: 5px; }
        .items-table tr:nth-child(even) td { background: #fafafa; }
        .items-table tfoot td { background: {{ $pdfSettings['secondary_color'] }}; font-weight: bold; border-top: 2px solid #d1d5db; }
        .item-name { font-weight: 600; color: #111; }
        .item-sku { font-size: 5pt; color: #999; display: block; }
        
        /* Summary Section */
        .summary-row { margin-bottom: 10px; }
        .summary-row > table { width: 100%; }
        .summary-left { width: 45%; vertical-align: top; }
        .summary-right { width: 55%; vertical-align: top; }
        
        .info-box { border: 1px solid #e5e7eb; border-radius: 3px; margin-bottom: 8px; }
        .info-box-header { background: {{ $pdfSettings['secondary_color'] }}; padding: 4px 8px; font-size: 6pt; font-weight: bold; color: {{ $pdfSettings['primary_color'] }}; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        .info-box-body { padding: 6px 8px; font-size: 7pt; color: #374151; line-height: 1.4; }
        
        .totals-table { width: 100%; border-collapse: collapse; border: 1px solid #d1d5db; }
        .totals-table td { padding: 5px 8px; font-size: 8pt; border-bottom: 1px solid #e5e7eb; }
        .totals-table td.lbl { background: {{ $pdfSettings['secondary_color'] }}; color: #555; width: 60%; border-right: 1px solid #e5e7eb; }
        .totals-table td.val { text-align: right; color: #111; font-weight: 500; }
        .totals-table tr.grand td { background: {{ $pdfSettings['primary_color'] }}; color: #fff; font-weight: bold; font-size: 9pt; }
        .totals-table tr.paid td { background: #d1fae5; color: #065f46; }
        .totals-table tr.balance td { background: #fee2e2; color: #991b1b; font-weight: bold; }
        
        .amount-words { margin-top: 8px; padding: 6px 8px; background: #fffbeb; border: 1px solid #fde047; border-radius: 3px; font-size: 7pt; color: #92400e; }
        .amount-words strong { color: #78350f; }
        
        /* Signature */
        .signature-row { margin-top: 20px; }
        .signature-row table { width: 100%; }
        .signature-row td { width: 40%; vertical-align: bottom; padding-top: 25px; }
        .signature-row td.gap { width: 20%; }
        .sig-line { border-top: 1px solid #333; padding-top: 4px; }
        .sig-label { font-size: 7pt; font-weight: bold; color: #111; }
        .sig-name { font-size: 6pt; color: #888; }
        
        /* Footer */
        .footer { margin-top: 12px; padding-top: 8px; border-top: 1px solid #e5e7eb; text-align: center; }
        .footer-text { font-size: 6pt; color: #999; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td class="header-left">
                    @if($pdfSettings['show_logo'] && $companyLogo)
                    <img src="{{ $companyLogo }}" class="logo" alt="Logo"><br>
                    @endif
                    <div class="company-name">{{ $companyName }}</div>
                    <div class="company-info">
                        @if($companyAddress){{ $companyAddress }}@endif
                        @if($companyPhone) | {{ $companyPhone }}@endif
                        @if($companyEmail) | {{ $companyEmail }}@endif
                        @if($pdfSettings['show_gst'] && $companyGst)<br>GSTIN: {{ $companyGst }}@endif
                    </div>
                </td>
                <td class="header-right">
                    <div class="doc-title">VENDOR INVOICE</div>
                    <div class="doc-box">
                        <div class="doc-number">{{ $bill->bill_number }}</div>
                        <div class="doc-date">Date: {{ $bill->bill_date->format('d M Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Info Row -->
    <div class="info-row">
        <table>
            <tr>
                <td>
                    <div class="info-label">Due Date</div>
                    <div class="info-value">{{ $bill->due_date?->format('d M Y') ?? '-' }}</div>
                </td>
                <td>
                    <div class="info-label">Vendor Invoice #</div>
                    <div class="info-value">{{ $bill->vendor_invoice_no ?? '-' }}</div>
                </td>
                <td>
                    <div class="info-label">Invoice Date</div>
                    <div class="info-value">{{ $bill->vendor_invoice_date?->format('d M Y') ?? '-' }}</div>
                </td>
                <td>
                    <div class="info-label">Payment Status</div>
                    <div class="info-value">
                        <span class="status status-{{ strtolower($bill->payment_status) }}">{{ str_replace('_', ' ', $bill->payment_status) }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Address Row -->
    <div class="address-row">
        <table>
            <tr>
                <td>
                    <div class="address-box">
                        <div class="address-header">Vendor (Bill From)</div>
                        <div class="address-body">
                            <div class="address-name">{{ $bill->vendor->name }}</div>
                            <div class="address-text">
                                @if($bill->vendor->billing_address){{ $bill->vendor->billing_address }}<br>@endif
                                @if($bill->vendor->billing_city){{ $bill->vendor->billing_city }}@endif
                                @if($bill->vendor->billing_state), {{ $bill->vendor->billing_state }}@endif
                                @if($bill->vendor->billing_pincode) - {{ $bill->vendor->billing_pincode }}@endif
                                @if($bill->vendor->phone)<br>Tel: {{ $bill->vendor->phone }}@endif
                            </div>
                            @if($bill->vendor->gst_number)
                            <div class="address-gst">GSTIN: {{ $bill->vendor->gst_number }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="gap"></td>
                <td>
                    <div class="address-box">
                        <div class="address-header">Ship To</div>
                        <div class="address-body">
                            @if($bill->warehouse)
                            <div class="address-name">{{ $bill->warehouse->name ?? 'Main Warehouse' }}</div>
                            <div class="address-text">
                                @if($bill->warehouse->address){{ $bill->warehouse->address }}<br>@endif
                                @if($bill->warehouse->city){{ $bill->warehouse->city }}@endif
                                @if($bill->warehouse->state), {{ $bill->warehouse->state }}@endif
                                @if($bill->warehouse->phone)<br>Tel: {{ $bill->warehouse->phone }}@endif
                            </div>
                            @else
                            <div class="address-name">{{ $companyName }}</div>
                            <div class="address-text">{{ $companyAddress }}</div>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:20px;">SL.</th>
                <th style="width:auto;">Product Description</th>
                <th style="width:40px;">HSN</th>
                <th style="width:30px;">QTY</th>
                <th style="width:50px;">Rate</th>
                <th style="width:55px;">Taxable</th>
                @if($hasTax1)<th style="width:50px;">{{ $tax1Name }}</th>@endif
                @if($hasTax2)<th style="width:50px;">{{ $tax2Name }}</th>@endif
                <th style="width:55px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $i => $item)
            @php
                $taxableValue = ($item->qty * $item->rate) - ($item->discount_amount ?? 0);
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="l">
                    <span class="item-name">{{ $item->product->name ?? $item->description ?? 'N/A' }}</span>
                    @if($item->product && $item->product->sku)
                    <span class="item-sku">{{ $item->product->sku }}</span>
                    @endif
                </td>
                <td>{{ $item->product->hsn_code ?? '-' }}</td>
                <td>{{ number_format($item->qty, 0) }}</td>
                <td class="r">{{ number_format($item->rate, 2) }}</td>
                <td class="r">{{ number_format($taxableValue, 2) }}</td>
                @if($hasTax1)<td class="r">{{ $item->tax_1_amount ? number_format($item->tax_1_amount, 2) : '-' }}</td>@endif
                @if($hasTax2)<td class="r">{{ $item->tax_2_amount ? number_format($item->tax_2_amount, 2) : '-' }}</td>@endif
                <td class="r"><strong>{{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:left;padding-left:8px;"><strong>TOTAL</strong></td>
                <td>{{ number_format($bill->items->sum('qty'), 0) }}</td>
                <td></td>
                <td class="r">{{ number_format($totalTaxableValue, 2) }}</td>
                @if($hasTax1)<td class="r">{{ number_format($tax1Total, 2) }}</td>@endif
                @if($hasTax2)<td class="r">{{ number_format($tax2Total, 2) }}</td>@endif
                <td class="r">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary Section -->
    <div class="summary-row">
        <table>
            <tr>
                <td class="summary-left">
                    <!-- Amount in Words -->
                    <div class="amount-words">
                        <strong>Total Invoice Amount in Words:</strong><br>
                        {{ $amountInWords }}
                    </div>
                    
                    <!-- Bank Details -->
                    @if($vendorBank)
                    <div class="info-box" style="margin-top:8px;">
                        <div class="info-box-header">Vendor Bank Details</div>
                        <div class="info-box-body">
                            <strong>{{ $vendorBank->account_holder_name ?? $bill->vendor->name }}</strong><br>
                            Bank: {{ $vendorBank->bank_name }}<br>
                            A/C: {{ $vendorBank->account_number }}<br>
                            @if($vendorBank->ifsc_code)IFSC: {{ $vendorBank->ifsc_code }}<br>@endif
                            @if($vendorBank->upi_id)UPI: {{ $vendorBank->upi_id }}@endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Notes -->
                    @if($pdfSettings['show_notes'] && $bill->notes)
                    <div class="info-box" style="margin-top:8px;">
                        <div class="info-box-header">Notes</div>
                        <div class="info-box-body">{{ $bill->notes }}</div>
                    </div>
                    @endif
                </td>
                <td class="summary-right">
                    <table class="totals-table">
                        <tr>
                            <td class="lbl">Total Amount before Tax</td>
                            <td class="val">{{ number_format($totalTaxableValue, 2) }}</td>
                        </tr>
                        @if($hasTax1 && $tax1Total > 0)
                        <tr>
                            <td class="lbl">Add: {{ $tax1Name }}</td>
                            <td class="val">{{ number_format($tax1Total, 2) }}</td>
                        </tr>
                        @endif
                        @if($hasTax2 && $tax2Total > 0)
                        <tr>
                            <td class="lbl">Add: {{ $tax2Name }}</td>
                            <td class="val">{{ number_format($tax2Total, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="lbl">Total Tax Amount</td>
                            <td class="val">{{ number_format($totalTaxAmount, 2) }}</td>
                        </tr>
                        @if($bill->shipping_charge > 0)
                        <tr>
                            <td class="lbl">Shipping Charges</td>
                            <td class="val">{{ number_format($bill->shipping_charge, 2) }}</td>
                        </tr>
                        @endif
                        @if($bill->adjustment != 0)
                        <tr>
                            <td class="lbl">Round Off (+/-)</td>
                            <td class="val">{{ number_format($bill->adjustment, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="grand">
                            <td class="lbl">Total Amount after Tax</td>
                            <td class="val">{{ number_format($bill->grand_total, 2) }}</td>
                        </tr>
                        <tr class="paid">
                            <td class="lbl">Paid Amount</td>
                            <td class="val">{{ number_format($bill->paid_amount, 2) }}</td>
                        </tr>
                        <tr class="balance">
                            <td class="lbl">Balance Due</td>
                            <td class="val">{{ number_format($bill->balance_due, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Signature -->
    @if($pdfSettings['show_signature'])
    <div class="signature-row">
        <table>
            <tr>
                <td>
                    <div class="sig-line">
                        <div class="sig-label">Vendor Signature</div>
                        <div class="sig-name">{{ $bill->vendor->name }}</div>
                    </div>
                </td>
                <td class="gap"></td>
                <td>
                    <div class="sig-line">
                        <div class="sig-label">Authorized Signatory</div>
                        <div class="sig-name">{{ $companyName }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">This is a computer generated invoice | Generated on {{ now()->format('d-M-Y h:i A') }}</div>
    </div>
</body>
</html>
