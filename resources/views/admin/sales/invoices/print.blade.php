<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }
        .content-wrapper {
            padding-bottom: 80px;
        }
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }
        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 5px;
        }
        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .invoice-meta {
            font-size: 11px;
            color: #666;
        }
        .invoice-meta table {
            margin-left: auto;
        }
        .invoice-meta td {
            padding: 3px 0;
        }
        .invoice-meta td:first-child {
            text-align: right;
            padding-right: 10px;
            color: #888;
        }
        .invoice-meta td:last-child {
            font-weight: 600;
            color: #333;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .status-draft { background: #f3f4f6; color: #6b7280; }
        .status-sent { background: #dbeafe; color: #1d4ed8; }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-overdue { background: #fee2e2; color: #dc2626; }
        .status-cancelled { background: #fef3c7; color: #d97706; }
        
        /* Billing Section */
        .billing-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .billing-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .billing-box:last-child {
            padding-right: 0;
            padding-left: 20px;
        }
        .billing-title {
            font-size: 10px;
            font-weight: bold;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .billing-name {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .billing-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #3b82f6;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table th:nth-child(1) { width: 5%; text-align: center; }
        .items-table th:nth-child(2) { width: 40%; }
        .items-table th:nth-child(3) { width: 10%; text-align: center; }
        .items-table th:nth-child(4) { width: 15%; text-align: right; }
        .items-table th:nth-child(5) { width: 15%; text-align: center; }
        .items-table th:nth-child(6) { width: 15%; text-align: right; }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .items-table td:nth-child(1) { text-align: center; color: #888; }
        .items-table td:nth-child(3) { text-align: center; }
        .items-table td:nth-child(4) { text-align: right; }
        .items-table td:nth-child(5) { text-align: center; }
        .items-table td:nth-child(6) { text-align: right; font-weight: 600; }
        .items-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .item-name {
            font-weight: 600;
            color: #1f2937;
        }
        .item-desc {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        
        /* Section Row */
        .section-row {
            background: #eff6ff !important;
        }
        .section-row td {
            font-weight: bold;
            color: #1e40af;
            font-size: 11px;
            padding: 8px 12px;
        }
        
        /* Note Row */
        .note-row {
            background: #fffbeb !important;
        }
        .note-row td {
            font-style: italic;
            color: #92400e;
            font-size: 10px;
            padding: 8px 12px;
        }
        
        /* Tax Badge */
        .tax-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #fef3f2;
            border: 1px solid #fecaca;
            border-radius: 3px;
            font-size: 8px;
            color: #991b1b;
            margin: 1px;
        }
        
        /* Totals Section */
        .totals-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .totals-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .totals-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .totals-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }
        .totals-row {
            display: table;
            width: 100%;
            padding: 6px 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .totals-row.grand {
            border-top: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 12px;
        }
        .totals-label {
            display: table-cell;
            width: 60%;
            color: #666;
            font-size: 11px;
        }
        .totals-value {
            display: table-cell;
            width: 40%;
            text-align: right;
            font-weight: 600;
            color: #333;
            font-size: 11px;
        }
        .totals-row.grand .totals-label {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        .totals-row.grand .totals-value {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
        }
        
        /* Tax Breakdown */
        .tax-breakdown {
            margin-top: 10px;
            padding: 10px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .tax-breakdown-title {
            font-size: 9px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .tax-breakdown-item {
            display: table;
            width: 100%;
            padding: 4px 0;
            font-size: 10px;
        }
        .tax-breakdown-name {
            display: table-cell;
            color: #666;
        }
        .tax-breakdown-amount {
            display: table-cell;
            text-align: right;
            color: #10b981;
            font-weight: 600;
        }
        
        /* Notes Section */
        .notes-section {
            margin-bottom: 30px;
        }
        .notes-title {
            font-size: 10px;
            font-weight: bold;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .notes-content {
            font-size: 11px;
            color: #666;
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            background: white;
        }
        .footer-text {
            font-size: 10px;
            color: #888;
            margin-bottom: 5px;
        }
        .footer-company {
            font-size: 11px;
            font-weight: bold;
            color: #3b82f6;
        }
        
        /* Amount Due Box */
        .amount-due-box {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }
        .amount-due-label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .amount-due-value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        /* Utilities */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if(!empty($company['logo']))
                @if(str_starts_with($company['logo'], 'data:image'))
                    {{-- Base64 image --}}
                    <img src="{{ $company['logo'] }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
                @else
                    {{-- File path --}}
                    <img src="{{ public_path('storage/' . $company['logo']) }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
                @endif
            @endif
            <div class="company-name">{{ $company['name'] }}</div>
            <div class="company-details">
                @if($company['address']){{ $company['address'] }}<br>@endif
                @if($company['phone'])Phone: {{ $company['phone'] }}<br>@endif
                @if($company['email'])Email: {{ $company['email'] }}<br>@endif
                @if($company['gst'])GST: {{ $company['gst'] }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            <div class="invoice-meta">
                <table>
                    <tr>
                        <td>Invoice Date:</td>
                        <td>{{ $invoice->date ? $invoice->date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
        </div>
    </div>
    
    <!-- Billing Section -->
    <div class="billing-section">
        <div class="billing-box">
            <div class="billing-title">Bill To</div>
            @if($invoice->customer)
                <div class="billing-name">
                    {{ $invoice->customer->customer_type === 'company' ? $invoice->customer->company : $invoice->customer->name }}
                </div>
                <div class="billing-details">
                    @if($invoice->customer->customer_type === 'company' && $invoice->customer->name)
                        Attn: {{ $invoice->customer->name }}<br>
                    @endif
                    @if($invoice->address){{ $invoice->address }}<br>@endif
                    @if($invoice->city || $invoice->state){{ $invoice->city }}{{ $invoice->city && $invoice->state ? ', ' : '' }}{{ $invoice->state }}<br>@endif
                    @if($invoice->country){{ $invoice->country }} {{ $invoice->zip_code }}<br>@endif
                    @if($invoice->email)Email: {{ $invoice->email }}<br>@endif
                    @if($invoice->phone)Phone: {{ $invoice->phone }}@endif
                </div>
            @else
                <div class="billing-details">No customer assigned</div>
            @endif
        </div>
        <div class="billing-box">
            <div class="billing-title">Payment Details</div>
            <div class="billing-details">
                <strong>Currency:</strong> {{ $invoice->currency ?? 'INR' }}<br>
                <strong>Payment Status:</strong> {{ ucfirst($invoice->payment_status ?? 'unpaid') }}<br>
                @if($invoice->amount_paid > 0)
                    <strong>Amount Paid:</strong> {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_paid, 2) }}<br>
                @endif
                <strong>Amount Due:</strong> <span class="{{ $invoice->amount_due > 0 ? 'text-danger' : 'text-success' }} font-bold">
                    {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_due ?? $invoice->total, 2) }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Subject -->
    @if($invoice->subject)
    <div style="margin-bottom: 20px; padding: 10px 15px; background: #f0f9ff; border-left: 3px solid #3b82f6; border-radius: 4px;">
        <strong style="color: #1e40af;">Subject:</strong> 
        <span style="color: #334155;">{{ $invoice->subject }}</span>
    </div>
    @endif
    
    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Tax</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $itemIndex = 0; @endphp
            @forelse($invoice->items as $item)
                @if(($item->item_type ?? 'product') === 'section')
                    <tr class="section-row">
                        <td colspan="6">{{ $item->description }}</td>
                    </tr>
                @elseif(($item->item_type ?? 'product') === 'note')
                    <tr class="note-row">
                        <td colspan="6">{{ $item->long_description ?: $item->description }}</td>
                    </tr>
                @else
                    @php 
                        $itemIndex++;
                        $itemTaxIds = [];
                        if (!empty($item->tax_ids)) {
                            if (is_array($item->tax_ids)) {
                                $itemTaxIds = array_map('intval', $item->tax_ids);
                            } else {
                                $decoded = json_decode($item->tax_ids, true);
                                if (is_array($decoded)) {
                                    $itemTaxIds = array_map('intval', $decoded);
                                } elseif (strpos($item->tax_ids, ',') !== false) {
                                    $itemTaxIds = array_map('intval', array_filter(explode(',', $item->tax_ids)));
                                } elseif ($item->tax_ids) {
                                    $itemTaxIds = [intval($item->tax_ids)];
                                }
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $itemIndex }}</td>
                        <td>
                            <div class="item-name">{{ $item->description }}</div>
                            @if($item->long_description)
                                <div class="item-desc">{{ $item->long_description }}</div>
                            @endif
                        </td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td>{{ number_format($item->rate, 2) }}</td>
                        <td>
                            @foreach($itemTaxIds as $taxId)
                                <span class="tax-badge">{{ $taxRatesMap[$taxId] ?? 0 }}%</span>
                            @endforeach
                            @if(empty($itemTaxIds))
                                <span style="color: #9ca3af; font-size: 9px;">-</span>
                            @endif
                        </td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #888;">No items</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Totals Section -->
    <div class="totals-section">
        <div class="totals-left">
            @if($invoice->content)
                <div class="notes-section">
                    <div class="notes-title">Notes</div>
                    <div class="notes-content">{{ $invoice->content }}</div>
                </div>
            @endif
            
            @if($invoice->terms_conditions)
                <div class="notes-section">
                    <div class="notes-title">Terms & Conditions</div>
                    <div class="notes-content">{{ $invoice->terms_conditions }}</div>
                </div>
            @endif
        </div>
        <div class="totals-right">
            @if($invoice->amount_due > 0)
            <div class="amount-due-box">
                <div class="amount-due-label">Amount Due</div>
                <div class="amount-due-value">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_due ?? $invoice->total, 2) }}</div>
            </div>
            @endif
            
            <div class="totals-box">
                <div class="totals-row">
                    <span class="totals-label">Subtotal</span>
                    <span class="totals-value">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                
                @if(($invoice->discount_amount ?? 0) > 0)
                <div class="totals-row">
                    <span class="totals-label">
                        Discount 
                        @if($invoice->discount_percent > 0)({{ $invoice->discount_percent }}%)@endif
                    </span>
                    <span class="totals-value text-danger">- {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
                @endif
                
                @if(count($taxBreakdown) > 0)
                <div class="tax-breakdown">
                    <div class="tax-breakdown-title">Tax Breakdown</div>
                    @foreach($taxBreakdown as $tax)
                    <div class="tax-breakdown-item">
                        <span class="tax-breakdown-name">{{ $tax['name'] }} ({{ $tax['rate'] }}%)</span>
                        <span class="tax-breakdown-amount">{{ $invoice->currency ?? 'INR' }} {{ number_format($tax['amount'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="totals-row">
                    <span class="totals-label">Total Tax</span>
                    <span class="totals-value text-success">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->tax_amount ?? 0, 2) }}</span>
                </div>
                
                @if(($invoice->adjustment ?? 0) != 0)
                <div class="totals-row">
                    <span class="totals-label">Adjustment</span>
                    <span class="totals-value">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->adjustment, 2) }}</span>
                </div>
                @endif
                
                <div class="totals-row grand">
                    <span class="totals-label">Grand Total</span>
                    <span class="totals-value">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->total, 2) }}</span>
                </div>
                
                @if($invoice->amount_paid > 0)
                <div class="totals-row">
                    <span class="totals-label">Amount Paid</span>
                    <span class="totals-value text-success">- {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_paid, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span class="totals-label font-bold">Balance Due</span>
                    <span class="totals-value font-bold {{ $invoice->amount_due > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_due, 2) }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">Thank you for your business!</div>
        <div class="footer-company">{{ $company['name'] }}</div>
        <div class="footer-text" style="margin-top: 10px;">
            This is a computer-generated invoice. No signature required.
        </div>
    </div>
</body>
</html>