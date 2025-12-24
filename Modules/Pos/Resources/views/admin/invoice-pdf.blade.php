<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice - {{ $sale->invoice_no }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #333;
    padding: 20px;
}
.header {
    width: 100%;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #2563eb;
}
.header-table { width: 100%; }
.company-name {
    font-size: 22px;
    color: #2563eb;
    font-weight: bold;
    margin-bottom: 5px;
}
.company-info { color: #666; line-height: 1.6; }
.invoice-title {
    font-size: 28px;
    color: #333;
    text-align: right;
}
.invoice-no {
    font-size: 13px;
    color: #2563eb;
    font-weight: bold;
    text-align: right;
}
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #dcfce7;
    color: #166534;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
}

.info-section {
    width: 100%;
    margin-bottom: 20px;
}
.info-table { width: 100%; }
.info-box { vertical-align: top; width: 50%; }
.info-label {
    font-size: 10px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    font-weight: bold;
}
.info-value { color: #333; line-height: 1.6; }

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.items-table th {
    background: #f1f5f9;
    text-align: left;
    padding: 10px;
    font-size: 10px;
    text-transform: uppercase;
    color: #666;
    border-bottom: 2px solid #e2e8f0;
}
.items-table th.right { text-align: right; }
.items-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
}
.items-table td.right { text-align: right; }
.item-name { font-weight: bold; color: #333; }
.item-variant { font-size: 10px; color: #666; margin-top: 2px; }

.totals-section { width: 100%; margin-bottom: 20px; }
.totals-table { width: 300px; margin-left: auto; }
.totals-table td { padding: 6px 0; }
.totals-table .label { color: #666; }
.totals-table .value { text-align: right; }
.totals-table .discount { color: #16a34a; }
.totals-table .grand-total td {
    border-top: 2px solid #333;
    padding-top: 10px;
    font-size: 16px;
    font-weight: bold;
}
.totals-table .grand-total .value { color: #2563eb; }

.payment-box {
    background: #f8fafc;
    padding: 15px;
    margin-bottom: 20px;
}
.payment-title {
    font-size: 11px;
    color: #666;
    margin-bottom: 8px;
    font-weight: bold;
}
.payment-info { color: #333; }

.footer {
    text-align: center;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
    color: #999;
    font-size: 10px;
}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <table class="header-table">
        <tr>
            <td style="width:60%">
                <div class="company-name">{{ $settings->store_name ?? 'EchoPx Store' }}</div>
                <div class="company-info">
                    @if($settings?->store_address){{ $settings->store_address }}<br>@endif
                    @if($settings?->store_phone)Tel: {{ $settings->store_phone }}<br>@endif
                    @if($settings?->store_gstin)GSTIN: {{ $settings->store_gstin }}@endif
                </div>
            </td>
            <td style="width:40%; vertical-align:top;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-no">{{ $sale->invoice_no }}</div>
                <div style="text-align:right; margin-top:8px;">
                    <span class="status-badge">PAID</span>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Info Section -->
<div class="info-section">
    <table class="info-table">
        <tr>
            <td class="info-box">
                <div class="info-label">Bill To</div>
                <div class="info-value">
                    @if($customer)
                        <strong>{{ $customer->name }}</strong><br>
                        @if($customer->email){{ $customer->email }}<br>@endif
                        @if($customer->phone){{ $customer->phone }}<br>@endif
                        @if($customer->address){{ $customer->address }}@endif
                    @else
                        <strong>Walk-in Customer</strong><br>
                        @if($sale->customer_name){{ $sale->customer_name }}<br>@endif
                        @if($sale->customer_phone){{ $sale->customer_phone }}@endif
                    @endif
                </div>
            </td>
            <td class="info-box" style="text-align:right;">
                <div class="info-label">Invoice Details</div>
                <div class="info-value">
                    <strong>Date:</strong> {{ $sale->created_at->format('d M Y') }}<br>
                    <strong>Time:</strong> {{ $sale->created_at->format('h:i A') }}<br>
                    <strong>Cashier:</strong> {{ $sale->admin->name ?? '-' }}
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Items Table -->
<table class="items-table">
    <thead>
        <tr>
            <th style="width:35%">Item Description</th>
            <th style="width:10%">Qty</th>
            <th style="width:15%" class="right">Rate</th>
            <th style="width:20%" class="right">Tax</th>
            <th style="width:20%" class="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td>
                <div class="item-name">{{ $item->product_name }}</div>
                @if($item->variant_name)
                <div class="item-variant">Variant: {{ $item->variant_name }}</div>
                @endif
            </td>
            <td>{{ $item->qty }}</td>
            <td class="right">₹{{ number_format($item->price, 2) }}</td>
            <td class="right">
                @if($item->tax_rate > 0)
                {{ $item->tax_rate }}% (₹{{ number_format($item->tax_amount ?? 0, 2) }})
                @else
                -
                @endif
            </td>
            <td class="right">₹{{ number_format($item->line_total + ($item->tax_amount ?? 0), 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Totals -->
<div class="totals-section">
    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">₹{{ number_format($sale->subtotal, 2) }}</td>
        </tr>
        @if($sale->discount_amount > 0)
        <tr class="discount">
            <td class="label">Discount</td>
            <td class="value">-₹{{ number_format($sale->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if($sale->tax_amount > 0)
        <tr>
            <td class="label">Tax</td>
            <td class="value">₹{{ number_format($sale->tax_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td class="label">Total</td>
            <td class="value">₹{{ number_format($sale->total, 2) }}</td>
        </tr>
    </table>
</div>

<!-- Payment Info -->
<div class="payment-box">
    <div class="payment-title">Payment Information</div>
    <div class="payment-info">
        <strong>Method:</strong> {{ strtoupper($sale->payment_method) }} &nbsp;&nbsp;|&nbsp;&nbsp;
        @if($sale->payment_method == 'cash')
        <strong>Received:</strong> ₹{{ number_format($sale->cash_received ?? $sale->total, 2) }} &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Change:</strong> ₹{{ number_format($sale->change_amount ?? 0, 2) }} &nbsp;&nbsp;|&nbsp;&nbsp;
        @endif
        <strong>Status:</strong> <span style="color:#16a34a;">PAID</span>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p><strong>{{ $settings->receipt_footer ?? 'Thank you for your business!' }}</strong></p>
    <p style="margin-top:5px;">This is a computer generated invoice. | Generated on {{ now()->format('d M Y h:i A') }}</p>
</div>

</body>
</html>
