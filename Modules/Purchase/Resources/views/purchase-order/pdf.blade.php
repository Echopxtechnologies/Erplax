<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PO - {{ $po->po_number }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', sans-serif; }
        
        body { 
            font-size: {{ $pdfSettings['font_size'] }}pt; 
            color: #333; 
            line-height: 1.3; 
        }
        
        /* Dynamic Colors */
        .primary-bg { background-color: {{ $pdfSettings['primary_color'] }}; }
        .primary-text { color: {{ $pdfSettings['primary_color'] }}; }
        .secondary-bg { background-color: {{ $pdfSettings['secondary_color'] }}; }
        
        /* Header */
        .header {
            background-color: {{ $pdfSettings['primary_color'] }};
            color: #fff;
            padding: {{ $pdfSettings['compact_mode'] ? '15px 25px' : '20px 30px' }};
        }
        .header table { width: 100%; }
        .header-left { width: 55%; vertical-align: middle; }
        .header-right { width: 45%; text-align: right; vertical-align: middle; }
        .logo { max-height: 35px; margin-bottom: 5px; }
        .company-name { font-size: 14pt; font-weight: bold; margin-bottom: 4px; }
        .company-info { font-size: 7pt; opacity: 0.9; line-height: 1.5; }
        .doc-title { font-size: 18pt; font-weight: bold; letter-spacing: 1px; }
        .po-box { background: rgba(255,255,255,0.15); display: inline-block; padding: 5px 12px; margin-top: 6px; border-radius: 3px; }
        .po-number { font-size: 10pt; font-weight: bold; }
        .po-date { font-size: 7pt; opacity: 0.9; margin-top: 2px; }
        
        /* Content */
        .content { padding: {{ $pdfSettings['compact_mode'] ? '15px 25px' : '20px 30px' }}; }
        
        /* Info Grid */
        .info-grid { width: 100%; margin-bottom: {{ $pdfSettings['compact_mode'] ? '12px' : '18px' }}; border-bottom: 1px solid #e5e7eb; padding-bottom: {{ $pdfSettings['compact_mode'] ? '10px' : '14px' }}; }
        .info-grid td { width: 25%; vertical-align: top; padding-right: 8px; }
        .info-label { font-size: 6pt; color: #888; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
        .info-value { font-size: {{ $pdfSettings['font_size'] }}pt; color: #111; font-weight: 600; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 6pt; font-weight: bold; text-transform: uppercase; }
        .status-draft { background: #e5e7eb; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        /* Address Section */
        .address-section { width: 100%; margin-bottom: {{ $pdfSettings['compact_mode'] ? '12px' : '18px' }}; }
        .address-section td { width: 48%; vertical-align: top; }
        .address-section td.gap { width: 4%; }
        .address-box { border: 1px solid #e5e7eb; border-radius: 4px; overflow: hidden; }
        .address-header { background: {{ $pdfSettings['secondary_color'] }}; padding: {{ $pdfSettings['compact_mode'] ? '6px 10px' : '8px 12px' }}; font-size: 7pt; font-weight: bold; color: {{ $pdfSettings['primary_color'] }}; text-transform: uppercase; border-bottom: 2px solid {{ $pdfSettings['primary_color'] }}; }
        .address-body { padding: {{ $pdfSettings['compact_mode'] ? '8px 10px' : '10px 12px' }}; }
        .address-name { font-size: {{ $pdfSettings['font_size'] }}pt; font-weight: bold; color: #111; margin-bottom: 4px; }
        .address-text { font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: #555; line-height: 1.5; }
        .address-text p { margin: 0; }
        .address-gst { margin-top: 6px; padding-top: 5px; border-top: 1px dashed #ddd; font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: #111; font-weight: 600; }
        
        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: {{ $pdfSettings['compact_mode'] ? '12px' : '18px' }}; }
        .items-table th { background: {{ $pdfSettings['secondary_color'] }}; border-bottom: 2px solid #d1d5db; padding: {{ $pdfSettings['compact_mode'] ? '6px 5px' : '8px 6px' }}; font-size: 6pt; font-weight: bold; color: #374151; text-transform: uppercase; text-align: left; }
        .items-table th.c { text-align: center; }
        .items-table th.r { text-align: right; }
        .items-table td { border-bottom: 1px solid #f3f4f6; padding: {{ $pdfSettings['compact_mode'] ? '6px 5px' : '8px 6px' }}; font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: #374151; vertical-align: top; }
        .items-table td.c { text-align: center; }
        .items-table td.r { text-align: right; }
        .items-table tr:nth-child(even) td { background: #fafafa; }
        .item-name { font-weight: 600; color: #111; }
        .item-sku { font-size: 6pt; color: #999; }
        
        /* Totals */
        .totals-section { width: 100%; margin-bottom: {{ $pdfSettings['compact_mode'] ? '10px' : '15px' }}; }
        .totals-section > table { width: 100%; }
        .totals-left { width: 50%; vertical-align: top; padding-right: 15px; }
        .totals-right { width: 50%; vertical-align: top; }
        .notes-box { background: #fefce8; border: 1px solid #fde047; border-radius: 4px; padding: {{ $pdfSettings['compact_mode'] ? '8px' : '10px' }}; }
        .notes-title { font-size: 7pt; font-weight: bold; color: #854d0e; text-transform: uppercase; margin-bottom: 4px; }
        .notes-text { font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: #713f12; line-height: 1.4; }
        .totals-table { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 4px; overflow: hidden; }
        .totals-table td { padding: {{ $pdfSettings['compact_mode'] ? '6px 10px' : '8px 12px' }}; font-size: {{ $pdfSettings['font_size'] - 1 }}pt; border-bottom: 1px solid #e5e7eb; }
        .totals-table td.lbl { background: {{ $pdfSettings['secondary_color'] }}; color: #555; width: 55%; }
        .totals-table td.val { text-align: right; color: #111; font-weight: 500; }
        .totals-table tr.total td { background: {{ $pdfSettings['primary_color'] }}; color: #fff; font-weight: bold; font-size: {{ $pdfSettings['font_size'] }}pt; border: none; }
        .amount-words { margin-top: 8px; padding: 6px 10px; background: {{ $pdfSettings['secondary_color'] }}; border-left: 3px solid {{ $pdfSettings['primary_color'] }}; font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: {{ $pdfSettings['primary_color'] }}; }
        
        /* Terms */
        .terms-box { background: {{ $pdfSettings['secondary_color'] }}; border: 1px solid #e5e7eb; border-radius: 4px; padding: {{ $pdfSettings['compact_mode'] ? '8px 10px' : '10px 12px' }}; margin-bottom: {{ $pdfSettings['compact_mode'] ? '10px' : '15px' }}; }
        .terms-title { font-size: 7pt; font-weight: bold; color: #374151; text-transform: uppercase; margin-bottom: 4px; }
        .terms-text { font-size: {{ $pdfSettings['font_size'] - 1 }}pt; color: #6b7280; line-height: 1.5; }
        
        /* Signature */
        .signature-section { margin-top: {{ $pdfSettings['compact_mode'] ? '20px' : '30px' }}; width: 100%; }
        .signature-section td { width: 40%; vertical-align: bottom; padding-top: {{ $pdfSettings['compact_mode'] ? '25px' : '35px' }}; }
        .signature-section td.gap { width: 20%; }
        .sig-line { border-top: 1px solid #333; padding-top: 5px; }
        .sig-label { font-size: {{ $pdfSettings['font_size'] - 1 }}pt; font-weight: bold; color: #111; }
        .sig-name { font-size: {{ $pdfSettings['font_size'] - 2 }}pt; color: #888; margin-top: 2px; }
        
        /* Footer */
        .footer { margin-top: {{ $pdfSettings['compact_mode'] ? '10px' : '15px' }}; padding: 10px 25px; border-top: 1px solid #e5e7eb; text-align: center; }
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
                        @if($companyPhone) | Tel: {{ $companyPhone }}@endif
                        @if($companyEmail) | {{ $companyEmail }}@endif
                        @if($pdfSettings['show_gst'] && $companyGst)<br>GSTIN: {{ $companyGst }}@endif
                    </div>
                </td>
                <td class="header-right">
                    <div class="doc-title">PURCHASE ORDER</div>
                    <div class="po-box">
                        <div class="po-number">{{ $po->po_number }}</div>
                        <div class="po-date">Date: {{ $po->po_date->format('d M Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Info Grid -->
        <table class="info-grid">
            <tr>
                <td>
                    <div class="info-label">Order Date</div>
                    <div class="info-value">{{ $po->po_date->format('d M Y') }}</div>
                </td>
                <td>
                    <div class="info-label">Expected Delivery</div>
                    <div class="info-value">{{ $po->expected_date ? $po->expected_date->format('d M Y') : 'TBD' }}</div>
                </td>
                <td>
                    <div class="info-label">Payment Terms</div>
                    <div class="info-value">{{ $po->payment_terms ?: 'Net 30' }}</div>
                </td>
                <td>
                    <div class="info-label">Status</div>
                    <div class="info-value"><span class="status status-{{ strtolower($po->status) }}">{{ $po->status }}</span></div>
                </td>
            </tr>
            @if($po->purchaseRequest)
            <tr>
                <td colspan="4" style="padding-top: 8px;">
                    <div class="info-label">Reference PR</div>
                    <div class="info-value">{{ $po->purchaseRequest->pr_number }}</div>
                </td>
            </tr>
            @endif
        </table>

        <!-- Address Section -->
        <table class="address-section">
            <tr>
                <td>
                    <div class="address-box">
                        <div class="address-header">Vendor / Supplier</div>
                        <div class="address-body">
                            <div class="address-name">{{ $po->vendor->name }}</div>
                            <div class="address-text">
                                @if($po->vendor->contact_person)<p>Attn: {{ $po->vendor->contact_person }}</p>@endif
                                @if($po->vendor->billing_address)<p>{{ $po->vendor->billing_address }}</p>@endif
                                @if($po->vendor->billing_city || $po->vendor->billing_state)
                                <p>{{ $po->vendor->billing_city }}{{ $po->vendor->billing_state ? ', ' . $po->vendor->billing_state : '' }}{{ $po->vendor->billing_pincode ? ' - ' . $po->vendor->billing_pincode : '' }}</p>
                                @endif
                                @if($po->vendor->phone)<p>Tel: {{ $po->vendor->phone }}</p>@endif
                                @if($po->vendor->email)<p>{{ $po->vendor->email }}</p>@endif
                            </div>
                            @if($pdfSettings['show_gst'] && $po->vendor->gst_number)
                            <div class="address-gst">GSTIN: {{ $po->vendor->gst_number }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="gap"></td>
                <td>
                    <div class="address-box">
                        <div class="address-header">Ship To</div>
                        <div class="address-body">
                            <div class="address-name">{{ $companyName }}</div>
                            <div class="address-text">
                                @if($po->shipping_address)
                                <p>{{ $po->shipping_address }}</p>
                                @if($po->shipping_city || $po->shipping_state)
                                <p>{{ $po->shipping_city }}{{ $po->shipping_state ? ', ' . $po->shipping_state : '' }}{{ $po->shipping_pincode ? ' - ' . $po->shipping_pincode : '' }}</p>
                                @endif
                                @elseif($companyAddress)
                                <p>{{ $companyAddress }}</p>
                                @endif
                                @if($companyPhone)<p>Tel: {{ $companyPhone }}</p>@endif
                            </div>
                            @if($pdfSettings['show_gst'] && $companyGst)
                            <div class="address-gst">GSTIN: {{ $companyGst }}</div>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:20px;" class="c">#</th>
                    <th>Description</th>
                    <th style="width:40px;" class="c">Unit</th>
                    <th style="width:45px;" class="r">Qty</th>
                    <th style="width:60px;" class="r">Rate</th>
                    <th style="width:40px;" class="r">Disc%</th>
                    <th style="width:40px;" class="r">Tax%</th>
                    <th style="width:65px;" class="r">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->items as $i => $item)
                <tr>
                    <td class="c">{{ $i + 1 }}</td>
                    <td>
                        <span class="item-name">{{ $item->product->name ?? $item->description ?? '-' }}</span>
                        @if($item->product && $item->product->sku)
                        <span class="item-sku"> ({{ $item->product->sku }})</span>
                        @endif
                    </td>
                    <td class="c">{{ $item->unit->short_name ?? '-' }}</td>
                    <td class="r">{{ number_format($item->qty, 2) }}</td>
                    <td class="r">{{ number_format($item->rate, 2) }}</td>
                    <td class="r">{{ $item->discount_percent > 0 ? number_format($item->discount_percent, 1) : '-' }}</td>
                    <td class="r">{{ $item->tax_percent > 0 ? number_format($item->tax_percent, 1) : '-' }}</td>
                    <td class="r"><strong>{{ number_format($item->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <table>
                <tr>
                    <td class="totals-left">
                        @if($pdfSettings['show_notes'] && $po->notes)
                        <div class="notes-box">
                            <div class="notes-title">Notes</div>
                            <div class="notes-text">{{ $po->notes }}</div>
                        </div>
                        @endif
                    </td>
                    <td class="totals-right">
                        <table class="totals-table">
                            <tr>
                                <td class="lbl">Subtotal</td>
                                <td class="val">Rs. {{ number_format($po->subtotal, 2) }}</td>
                            </tr>
                            @if($po->discount_amount > 0)
                            <tr>
                                <td class="lbl">Discount</td>
                                <td class="val">- Rs. {{ number_format($po->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="lbl">Tax</td>
                                <td class="val">Rs. {{ number_format($po->tax_amount, 2) }}</td>
                            </tr>
                            @if($po->shipping_charge > 0)
                            <tr>
                                <td class="lbl">Shipping</td>
                                <td class="val">Rs. {{ number_format($po->shipping_charge, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="total">
                                <td class="lbl">TOTAL</td>
                                <td class="val">Rs. {{ number_format($po->total_amount, 2) }}</td>
                            </tr>
                        </table>
                        <div class="amount-words"><strong>In Words:</strong> {{ $amountInWords }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Terms -->
        @if($pdfSettings['show_terms'] && $po->terms_conditions)
        <div class="terms-box">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-text">{!! nl2br(e($po->terms_conditions)) !!}</div>
        </div>
        @endif

        <!-- Signature -->
        @if($pdfSettings['show_signature'])
        <table class="signature-section">
            <tr>
                <td>
                    <div class="sig-line">
                        <div class="sig-label">Authorized By</div>
                        <div class="sig-name">{{ $companyName }}</div>
                    </div>
                </td>
                <td class="gap"></td>
                <td>
                    <div class="sig-line">
                        <div class="sig-label">Vendor Acknowledgement</div>
                        <div class="sig-name">Signature & Date</div>
                    </div>
                </td>
            </tr>
        </table>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">Computer generated document | {{ now()->format('d-M-Y h:i A') }}</div>
    </div>
</body>
</html>
