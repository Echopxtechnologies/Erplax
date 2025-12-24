<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $po->po_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        .container { padding: 20px; }
        .header { display: table; width: 100%; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header-left { display: table-cell; width: 60%; vertical-align: top; }
        .header-right { display: table-cell; width: 40%; vertical-align: top; text-align: right; }
        .company-name { font-size: 18px; font-weight: bold; color: #1e40af; margin-bottom: 5px; }
        .company-details { font-size: 9px; color: #666; line-height: 1.5; }
        .doc-title { font-size: 22px; font-weight: bold; color: #1e40af; margin-bottom: 8px; }
        .doc-number { font-size: 12px; color: #666; }
        .info-section { display: table; width: 100%; margin-bottom: 15px; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .info-box:first-child { border-right: none; }
        .info-title { font-size: 10px; font-weight: bold; color: #1e40af; margin-bottom: 8px; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; }
        .info-row { margin-bottom: 3px; }
        .info-label { color: #666; display: inline-block; width: 80px; }
        .info-value { font-weight: 500; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #1e40af; color: white; padding: 8px 5px; font-size: 9px; text-align: left; font-weight: 600; }
        table.items th.right { text-align: right; }
        table.items td { padding: 8px 5px; border-bottom: 1px solid #e2e8f0; font-size: 9px; vertical-align: top; }
        table.items td.right { text-align: right; }
        table.items tr:nth-child(even) { background: #f8fafc; }
        .summary-section { display: table; width: 100%; margin-top: 15px; }
        .terms-box { display: table-cell; width: 55%; vertical-align: top; padding-right: 20px; }
        .totals-box { display: table-cell; width: 45%; vertical-align: top; }
        .terms-title { font-weight: bold; color: #1e40af; margin-bottom: 5px; font-size: 10px; }
        .terms-content { font-size: 8px; color: #666; line-height: 1.5; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 6px 10px; font-size: 10px; }
        table.totals td:first-child { text-align: left; color: #666; }
        table.totals td:last-child { text-align: right; font-weight: 500; }
        table.totals tr.grand { background: #1e40af; color: white; }
        table.totals tr.grand td { font-size: 12px; font-weight: bold; padding: 10px; }
        .signature-section { margin-top: 40px; display: table; width: 100%; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 150px; margin: 30px auto 5px; }
        .signature-text { font-size: 9px; color: #666; }
        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 9px; font-weight: bold; }
        .status-DRAFT { background: #fef3c7; color: #92400e; }
        .status-SENT { background: #dbeafe; color: #1e40af; }
        .status-CONFIRMED { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <div class="company-name">{{ $company['name'] ?? config('app.name') }}</div>
            <div class="company-details">
                @if(!empty($company['address'])){{ $company['address'] }}<br>@endif
                @if(!empty($company['city']) || !empty($company['state'])){{ $company['city'] }}{{ !empty($company['city']) && !empty($company['state']) ? ', ' : '' }}{{ $company['state'] }} {{ $company['zip'] ?? '' }}<br>@endif
                @if(!empty($company['phone']))Phone: {{ $company['phone'] }}<br>@endif
                @if(!empty($company['email']))Email: {{ $company['email'] }}<br>@endif
                @if(!empty($company['gst']))GSTIN: {{ $company['gst'] }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">PURCHASE ORDER</div>
            <div class="doc-number">
                <strong>{{ $po->po_number }}</strong><br>
                Date: {{ $po->po_date->format('d M Y') }}<br>
                <span class="status-badge status-{{ $po->status }}">{{ $po->status }}</span>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <div class="info-title">Vendor Details</div>
            <div style="font-weight: bold; margin-bottom: 5px;">{{ $po->vendor->name ?? '-' }}</div>
            @if($po->vendor)
                @if($po->vendor->address)<div>{{ $po->vendor->address }}</div>@endif
                @if($po->vendor->city || $po->vendor->state)<div>{{ $po->vendor->city }}{{ $po->vendor->city && $po->vendor->state ? ', ' : '' }}{{ $po->vendor->state }} {{ $po->vendor->pincode }}</div>@endif
                @if($po->vendor->phone)<div>Phone: {{ $po->vendor->phone }}</div>@endif
                @if($po->vendor->email)<div>Email: {{ $po->vendor->email }}</div>@endif
                @if($po->vendor->gst_number)<div>GSTIN: {{ $po->vendor->gst_number }}</div>@endif
            @endif
        </div>
        <div class="info-box">
            <div class="info-title">Shipping Details</div>
            @if($po->shipping_address)<div>{{ $po->shipping_address }}</div>@endif
            @if($po->shipping_city || $po->shipping_state)<div>{{ $po->shipping_city }}{{ $po->shipping_city && $po->shipping_state ? ', ' : '' }}{{ $po->shipping_state }} {{ $po->shipping_pincode }}</div>@endif
            <div class="info-row"><span class="info-label">Payment:</span> <span class="info-value">{{ $po->payment_terms ?? 'Net 30' }}</span></div>
            @if($po->expected_date)<div class="info-row"><span class="info-label">Expected:</span> <span class="info-value">{{ $po->expected_date->format('d M Y') }}</span></div>@endif
        </div>
    </div>

    @php
        // Collect unique tax names
        $hasTax1 = $po->items->whereNotNull('tax_1_name')->count() > 0;
        $hasTax2 = $po->items->whereNotNull('tax_2_name')->count() > 0;
        $tax1Name = $po->items->whereNotNull('tax_1_name')->first()->tax_1_name ?? 'Tax 1';
        $tax2Name = $po->items->whereNotNull('tax_2_name')->first()->tax_2_name ?? 'Tax 2';
    @endphp

    <table class="items">
        <thead>
            <tr>
                <th style="width:25px">#</th>
                <th>Description</th>
                <th style="width:40px">Unit</th>
                <th style="width:45px" class="right">Qty</th>
                <th style="width:60px" class="right">Rate</th>
                <th style="width:65px" class="right">Amount</th>
                @if($hasTax1)<th style="width:55px" class="right">{{ $tax1Name }}</th>@endif
                @if($hasTax2)<th style="width:55px" class="right">{{ $tax2Name }}</th>@endif
                <th style="width:70px" class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $i => $item)
            @php
                $lineTotal = $item->qty * $item->rate;
                $discAmount = $lineTotal * (($item->discount_percent ?? 0) / 100);
                $afterDisc = $lineTotal - $discAmount;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                    @if($item->variation)<br><span style="color:#8b5cf6;font-size:8px">{{ $item->variation->variation_name ?: $item->variation->sku }}</span>@endif
                    @if($item->product && $item->product->sku)<br><span style="color:#666;font-size:8px">SKU: {{ $item->product->sku }}</span>@endif
                    @if($item->discount_percent > 0)<br><span style="color:#059669;font-size:8px">Discount: {{ $item->discount_percent }}%</span>@endif
                </td>
                <td>{{ $item->unit->short_name ?? '-' }}</td>
                <td class="right">{{ number_format($item->qty, 3) }}</td>
                <td class="right">{{ number_format($item->rate, 2) }}</td>
                <td class="right">{{ number_format($afterDisc, 2) }}</td>
                @if($hasTax1)<td class="right">{{ $item->tax_1_amount ? number_format($item->tax_1_amount, 2) : '-' }}</td>@endif
                @if($hasTax2)<td class="right">{{ $item->tax_2_amount ? number_format($item->tax_2_amount, 2) : '-' }}</td>@endif
                <td class="right"><strong>{{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <div class="terms-box">
            @if($po->terms_conditions)
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-content">{!! nl2br(e($po->terms_conditions)) !!}</div>
            @endif
        </div>
        <div class="totals-box">
            <table class="totals">
                <tr><td>Subtotal</td><td>₹{{ number_format($po->subtotal, 2) }}</td></tr>
                @if($hasTax1)
                @php $tax1Total = $po->items->sum('tax_1_amount'); @endphp
                <tr><td>{{ $tax1Name }}</td><td>₹{{ number_format($tax1Total, 2) }}</td></tr>
                @endif
                @if($hasTax2)
                @php $tax2Total = $po->items->sum('tax_2_amount'); @endphp
                <tr><td>{{ $tax2Name }}</td><td>₹{{ number_format($tax2Total, 2) }}</td></tr>
                @endif
                @if($po->shipping_charge > 0)<tr><td>Shipping</td><td>₹{{ number_format($po->shipping_charge, 2) }}</td></tr>@endif
                @if($po->discount_amount > 0)<tr><td>Discount</td><td>-₹{{ number_format($po->discount_amount, 2) }}</td></tr>@endif
                <tr class="grand"><td>Grand Total</td><td>₹{{ number_format($po->total_amount, 2) }}</td></tr>
            </table>
        </div>
    </div>

    @if($settings['show_signature'] ?? true)
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-text">Vendor Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-text">{{ $settings['signature_text'] ?? 'Authorized Signatory' }}</div>
        </div>
    </div>
    @endif

    @if(!empty($settings['footer_text']))
    <div class="footer">{!! $settings['footer_text'] !!}</div>
    @endif
</div>
</body>
</html>
