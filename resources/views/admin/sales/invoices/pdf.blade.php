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
            line-height: 1.5;
        }
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
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
        .company-logo {
            max-width: 150px;
            max-height: 60px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 11px;
            color: #6b7280;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 14px;
            color: #374151;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 11px;
            color: #6b7280;
        }

        /* Billing Section */
        .billing-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .billing-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .billing-from {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .billing-title {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .billing-name {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .billing-details {
            font-size: 11px;
            color: #4b5563;
        }

        /* Invoice Details Box */
        .invoice-details-box {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .details-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .details-label {
            display: table-cell;
            width: 50%;
            font-size: 11px;
            color: #6b7280;
        }
        .details-value {
            display: table-cell;
            width: 50%;
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
            text-align: right;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background: #3b82f6;
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 10px;
            text-align: left;
            border: none;
        }
        .items-table th:last-child {
            text-align: right;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
            vertical-align: top;
        }
        .items-table td:last-child {
            text-align: right;
        }
        .items-table .item-name {
            font-weight: 600;
            color: #1f2937;
        }
        .items-table .item-desc {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        .items-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        /* Section Row */
        .section-row {
            background: #dbeafe !important;
        }
        .section-row td {
            font-weight: bold;
            color: #1e40af;
            padding: 8px 10px;
            border-bottom: 2px solid #93c5fd;
        }

        /* Note Row */
        .note-row {
            background: #fef3c7 !important;
        }
        .note-row td {
            font-style: italic;
            color: #92400e;
            padding: 8px 10px;
            font-size: 10px;
        }

        /* Totals */
        .totals-section {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .totals-label {
            display: table-cell;
            font-size: 11px;
            color: #6b7280;
        }
        .totals-value {
            display: table-cell;
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }
        .totals-row.grand {
            background: #3b82f6;
            margin-top: 10px;
            padding: 12px 10px;
            border-radius: 6px;
        }
        .totals-row.grand .totals-label {
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        .totals-row.grand .totals-value {
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        /* Amount Due Box */
        .amount-due-box {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
            width: 300px;
            margin-left: auto;
        }
        .amount-due-box.paid {
            background: #dcfce7;
            border-color: #22c55e;
        }
        .amount-due-label {
            font-size: 10px;
            font-weight: bold;
            color: #991b1b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .amount-due-box.paid .amount-due-label {
            color: #166534;
        }
        .amount-due-value {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
        }
        .amount-due-box.paid .amount-due-value {
            color: #16a34a;
        }

        /* Payment History */
        .payment-history {
            margin-bottom: 25px;
        }
        .payment-history-title {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .payment-item {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .payment-info {
            display: table-cell;
            font-size: 10px;
            color: #4b5563;
        }
        .payment-amount {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            font-weight: bold;
            color: #059669;
        }

        /* Notes Section */
        .notes-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .notes-title {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .notes-content {
            font-size: 10px;
            color: #6b7280;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        .footer-text {
            font-size: 10px;
            color: #9ca3af;
        }
        .footer-thanks {
            font-size: 14px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 5px;
        }

        /* Bank Details */
        .bank-details {
            background: #eff6ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .bank-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        .bank-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .bank-label {
            display: table-cell;
            width: 40%;
            font-size: 10px;
            color: #6b7280;
        }
        .bank-value {
            display: table-cell;
            font-size: 10px;
            font-weight: 600;
            color: #1f2937;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1d4ed8; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-partial { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                @if($company['logo'])
                    <img src="{{ $company['logo'] }}" alt="Logo" class="company-logo">
                @endif
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="company-details">
                    @if($company['address']){{ $company['address'] }}<br>@endif
                    @if($company['phone'])Phone: {{ $company['phone'] }}<br>@endif
                    @if($company['email'])Email: {{ $company['email'] }}<br>@endif
                    @if($company['website'])Website: {{ $company['website'] }}<br>@endif
                    @if($company['gst'])GSTIN: {{ $company['gst'] }}@endif
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                <div class="invoice-date">
                    Date: {{ $invoice->date->format($settings['date_format']) }}<br>
                    @if($invoice->due_date)
                        Due: {{ $invoice->due_date->format($settings['date_format']) }}
                    @endif
                </div>
                <div style="margin-top: 10px;">
                    <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Billing Section -->
        <div class="billing-section">
            <div class="billing-to">
                <div class="billing-title">Bill To</div>
                <div class="billing-name">{{ $invoice->customer->name ?? 'N/A' }}</div>
                <div class="billing-details">
                    @if($invoice->email){{ $invoice->email }}<br>@endif
                    @if($invoice->phone){{ $invoice->phone }}<br>@endif
                    @if($invoice->address){{ $invoice->address }}<br>@endif
                    @if($invoice->city || $invoice->state || $invoice->zip_code)
                        {{ $invoice->city }}{{ $invoice->city && $invoice->state ? ', ' : '' }}{{ $invoice->state }} {{ $invoice->zip_code }}<br>
                    @endif
                    @if($invoice->country){{ $invoice->country }}@endif
                </div>
            </div>
            <div class="billing-from">
                <div class="billing-title">Invoice Details</div>
                <div class="billing-details">
                    <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Date:</strong> {{ $invoice->date->format($settings['date_format']) }}<br>
                    @if($invoice->due_date)
                        <strong>Due Date:</strong> {{ $invoice->due_date->format($settings['date_format']) }}<br>
                    @endif
                    <strong>Currency:</strong> {{ $invoice->currency ?? $settings['currency_code'] }}<br>
                    @if($invoice->subject)
                        <strong>Subject:</strong> {{ $invoice->subject }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Rate</th>
                    <th style="width: 10%;">Tax</th>
                    <th style="width: 20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $sno = 1; @endphp
                @foreach($invoice->items as $item)
                    @if(($item->item_type ?? 'product') === 'section')
                        <tr class="section-row">
                            <td colspan="6">{{ $item->description }}</td>
                        </tr>
                    @elseif(($item->item_type ?? 'product') === 'note')
                        <tr class="note-row">
                            <td colspan="6">{{ $item->long_description ?: $item->description }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>
                                <div class="item-name">{{ $item->description }}</div>
                                @if($item->long_description)
                                    <div class="item-desc">{{ $item->long_description }}</div>
                                @endif
                            </td>
                            <td>{{ intval($item->quantity) }}</td>
                            <td>{{ $settings['currency_symbol'] }}{{ number_format($item->rate, 2) }}</td>
                            <td>{{ number_format($item->tax_rate, 2) }}%</td>
                            <td>{{ $settings['currency_symbol'] }}{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-row">
                <span class="totals-label">Subtotal</span>
                <span class="totals-value">{{ $settings['currency_symbol'] }}{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
            <div class="totals-row">
                <span class="totals-label">Discount</span>
                <span class="totals-value">-{{ $settings['currency_symbol'] }}{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            @if($invoice->tax > 0)
            <div class="totals-row">
                <span class="totals-label">Tax</span>
                <span class="totals-value">{{ $settings['currency_symbol'] }}{{ number_format($invoice->tax, 2) }}</span>
            </div>
            @endif
            <div class="totals-row grand">
                <span class="totals-label">Total</span>
                <span class="totals-value">{{ $settings['currency_symbol'] }}{{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>

        <!-- Amount Due Box -->
        <div class="amount-due-box {{ $invoice->payment_status === 'paid' ? 'paid' : '' }}">
            <div class="amount-due-label">
                {{ $invoice->payment_status === 'paid' ? 'Paid in Full' : 'Amount Due' }}
            </div>
            <div class="amount-due-value">
                {{ $settings['currency_symbol'] }}{{ number_format($invoice->amount_due, 2) }}
            </div>
        </div>

        <!-- Payment History -->
        @if($invoice->payments && $invoice->payments->count() > 0)
        <div class="payment-history">
            <div class="payment-history-title">Payment History</div>
            @foreach($invoice->payments as $payment)
            <div class="payment-item">
                <span class="payment-info">
                    {{ $payment->payment_number }} • {{ $payment->payment_date->format($settings['date_format']) }} • {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                </span>
                <span class="payment-amount">{{ $settings['currency_symbol'] }}{{ number_format($payment->amount, 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Notes -->
        @if($invoice->content || $invoice->terms_conditions)
        <div class="notes-section">
            @if($invoice->content)
                <div class="notes-title">Notes:</div>
                <div class="notes-content">{{ $invoice->content }}</div>
            @endif
            @if($invoice->terms_conditions)
                <div class="notes-title" style="margin-top: 10px;">Terms & Conditions:</div>
                <div class="notes-content">{{ $invoice->terms_conditions }}</div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-thanks">Thank you for your business!</div>
            <div class="footer-text">
                {{ $company['name'] }}
                @if($company['phone']) • {{ $company['phone'] }}@endif
                @if($company['email']) • {{ $company['email'] }}@endif
            </div>
        </div>
    </div>
</body>
</html>
