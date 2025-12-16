<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt - {{ $payment->payment_number }}</title>
    <style>
        @page { margin: 30px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 10pt; color: #333; line-height: 1.5; background: #fff; }
        
        .receipt { max-width: 550px; margin: 0 auto; border: 2px solid {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; }
        
        /* Header */
        .header { background: {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; color: #fff; padding: 20px; text-align: center; }
        .company-name { font-size: 16pt; font-weight: bold; margin-bottom: 3px; }
        .company-details { font-size: 8pt; opacity: 0.9; line-height: 1.4; }
        
        /* Title Bar */
        .title-bar { background: {{ $settings['pdf_secondary_color'] ?? '#f1f5f9' }}; padding: 12px 20px; border-bottom: 2px solid {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; display: table; width: 100%; }
        .title-left { display: table-cell; vertical-align: middle; }
        .title-right { display: table-cell; vertical-align: middle; text-align: right; }
        .receipt-title { font-size: 14pt; font-weight: bold; color: {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; letter-spacing: 2px; }
        .receipt-number { font-size: 9pt; color: #666; margin-top: 2px; }
        .receipt-date { font-size: 9pt; color: #333; }
        .receipt-date strong { color: {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; }
        
        /* Content */
        .content { padding: 20px; }
        
        /* Amount Box */
        .amount-box { background: linear-gradient(135deg, {{ $settings['pdf_primary_color'] ?? '#1e40af' }} 0%, #3b82f6 100%); color: #fff; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
        .amount-label { font-size: 9pt; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9; }
        .amount-value { font-size: 32pt; font-weight: bold; margin: 8px 0; }
        .amount-words { font-size: 9pt; opacity: 0.85; font-style: italic; padding: 8px 15px; background: rgba(255,255,255,0.1); border-radius: 4px; display: inline-block; margin-top: 5px; }
        
        /* Info Grid */
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-col:first-child { padding-right: 10px; }
        .info-col:last-child { padding-left: 10px; }
        
        .info-box { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 12px; }
        .info-box-header { background: {{ $settings['pdf_secondary_color'] ?? '#f1f5f9' }}; padding: 6px 10px; font-size: 7pt; font-weight: bold; color: {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e7eb; }
        .info-box-body { padding: 10px; }
        .info-row { margin-bottom: 6px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { font-size: 7pt; color: #888; text-transform: uppercase; letter-spacing: 0.3px; }
        .info-value { font-size: 9pt; color: #111; font-weight: 500; }
        
        .vendor-name { font-size: 11pt; font-weight: bold; color: #111; margin-bottom: 4px; }
        .vendor-address { font-size: 8pt; color: #666; line-height: 1.4; }
        .vendor-gst { font-size: 8pt; color: #111; font-weight: 600; margin-top: 4px; padding-top: 4px; border-top: 1px dashed #ddd; }
        
        /* Bill Reference */
        .bill-ref { background: #fffbeb; border: 1px solid #fde047; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px; display: table; width: 100%; }
        .bill-ref-left { display: table-cell; width: 50%; vertical-align: middle; }
        .bill-ref-right { display: table-cell; width: 50%; vertical-align: middle; text-align: right; }
        .bill-ref-label { font-size: 7pt; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; }
        .bill-ref-value { font-size: 11pt; font-weight: bold; color: #78350f; }
        
        /* Details Table */
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
        .details-table th { background: {{ $settings['pdf_secondary_color'] ?? '#f1f5f9' }}; padding: 8px 12px; text-align: left; font-size: 8pt; color: {{ $settings['pdf_primary_color'] ?? '#1e40af' }}; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        .details-table td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 9pt; }
        .details-table td.label { color: #666; width: 45%; }
        .details-table td.value { font-weight: 500; color: #111; }
        .details-table tr:last-child td { border-bottom: none; }
        .details-table tr.highlight td { background: #f0fdf4; }
        .details-table tr.highlight td.value { color: #059669; font-weight: bold; font-size: 10pt; }
        .details-table tr.balance td { background: #fef2f2; }
        .details-table tr.balance td.value { color: #dc2626; font-weight: bold; }
        
        /* Status Badge */
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        
        /* Paid Stamp */
        .paid-stamp { position: fixed; top: 200px; right: 80px; border: 4px solid #10b981; border-radius: 8px; padding: 8px 20px; color: #10b981; font-size: 24pt; font-weight: bold; text-transform: uppercase; transform: rotate(-15deg); opacity: 0.3; }
        
        /* Signatures */
        .signatures { display: table; width: 100%; margin-top: 30px; }
        .sig-box { display: table-cell; width: 45%; text-align: center; vertical-align: bottom; }
        .sig-box.gap { width: 10%; }
        .sig-line { border-top: 1px solid #333; width: 150px; margin: 0 auto; padding-top: 6px; }
        .sig-label { font-size: 8pt; color: #666; }
        
        /* Footer */
        .footer { background: {{ $settings['pdf_secondary_color'] ?? '#f1f5f9' }}; padding: 12px 20px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer-text { font-size: 7pt; color: #888; }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Paid Stamp -->
        @if($payment->status === 'COMPLETED')
        <div class="paid-stamp">PAID</div>
        @endif
        
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] ?? 'Company Name' }}</div>
            <div class="company-details">
                @if(!empty($company['address'])){{ $company['address'] }}@endif
                @if(!empty($company['phone'])) | {{ $company['phone'] }}@endif
                @if(!empty($company['email'])) | {{ $company['email'] }}@endif
            </div>
        </div>
        
        <!-- Title Bar -->
        <div class="title-bar">
            <div class="title-left">
                <div class="receipt-title">PAYMENT RECEIPT</div>
                <div class="receipt-number"># {{ $payment->payment_number }}</div>
            </div>
            <div class="title-right">
                <div class="receipt-date"><strong>Date:</strong> {{ $payment->payment_date->format('d M Y') }}</div>
                <div style="margin-top:4px;">
                    <span class="status-badge status-{{ strtolower($payment->status) }}">{{ $payment->status }}</span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Amount Box -->
            <div class="amount-box">
                <div class="amount-label">Amount Paid</div>
                <div class="amount-value">₹{{ number_format($payment->amount, 2) }}</div>
                <div class="amount-words">{{ $amountInWords }}</div>
            </div>
            
            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-col">
                    <!-- Paid To -->
                    <div class="info-box">
                        <div class="info-box-header">Paid To (Vendor)</div>
                        <div class="info-box-body">
                            <div class="vendor-name">{{ $payment->vendor->name }}</div>
                            <div class="vendor-address">
                                @if($payment->vendor->billing_address){{ $payment->vendor->billing_address }}<br>@endif
                                @if($payment->vendor->billing_city){{ $payment->vendor->billing_city }}{{ $payment->vendor->billing_state ? ', ' . $payment->vendor->billing_state : '' }}@endif
                            </div>
                            @if($payment->vendor->gst_number)
                            <div class="vendor-gst">GSTIN: {{ $payment->vendor->gst_number }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="info-col">
                    <!-- Payment Details -->
                    <div class="info-box">
                        <div class="info-box-header">Payment Details</div>
                        <div class="info-box-body">
                            <div class="info-row">
                                <div class="info-label">Payment Method</div>
                                <div class="info-value">{{ $payment->paymentMethod->name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Reference No</div>
                                <div class="info-value">{{ $payment->reference_no ?? '-' }}</div>
                            </div>
                            @if($payment->cheque_no)
                            <div class="info-row">
                                <div class="info-label">Cheque No</div>
                                <div class="info-value">{{ $payment->cheque_no }}</div>
                            </div>
                            @endif
                            @if($payment->bank_name)
                            <div class="info-row">
                                <div class="info-label">Bank</div>
                                <div class="info-value">{{ $payment->bank_name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bill Reference -->
            <div class="bill-ref">
                <div class="bill-ref-left">
                    <div class="bill-ref-label">Against Vendor Bill</div>
                    <div class="bill-ref-value">{{ $payment->bill->bill_number }}</div>
                </div>
                <div class="bill-ref-right">
                    <div class="bill-ref-label">Bill Date</div>
                    <div class="bill-ref-value">{{ $payment->bill->bill_date->format('d M Y') }}</div>
                </div>
            </div>
            
            <!-- Payment Breakdown Table -->
            <table class="details-table">
                <thead>
                    <tr>
                        <th colspan="2">Payment Breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Bill Total Amount</td>
                        <td class="value">₹{{ number_format($payment->bill->grand_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Previously Paid</td>
                        <td class="value">₹{{ number_format($payment->bill->paid_amount - $payment->amount, 2) }}</td>
                    </tr>
                    <tr class="highlight">
                        <td class="label">This Payment</td>
                        <td class="value">₹{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr class="balance">
                        <td class="label">Balance After Payment</td>
                        <td class="value">₹{{ number_format($payment->bill->balance_due, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            @if($payment->notes)
            <div class="info-box">
                <div class="info-box-header">Notes</div>
                <div class="info-box-body">{{ $payment->notes }}</div>
            </div>
            @endif
            
            <!-- Signatures -->
            <div class="signatures">
                <div class="sig-box">
                    <div class="sig-line">Received By</div>
                </div>
                <div class="sig-box gap"></div>
                <div class="sig-box">
                    <div class="sig-line">Authorized Signatory</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">This is a computer generated receipt | Generated on {{ now()->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</body>
</html>
