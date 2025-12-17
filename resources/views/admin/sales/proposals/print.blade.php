<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal - {{ $proposal->proposal_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .company-info h1 {
            font-size: 24px;
            color: #3b82f6;
            margin-bottom: 5px;
        }
        
        .proposal-title {
            text-align: right;
        }
        
        .proposal-title h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .proposal-number {
            font-size: 14px;
            color: #666;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-block {
            width: 48%;
        }
        
        .info-block h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            width: 100px;
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            flex: 1;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
            font-size: 11px;
            text-transform: uppercase;
            color: #666;
        }
        
        .items-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .items-table tbody tr.section-row {
            background: #e3f2fd;
        }
        
        .items-table tbody tr.section-row td {
            font-weight: 600;
            color: #1976d2;
        }
        
        .items-table tbody tr.note-row {
            background: #fff8e1;
        }
        
        .items-table tbody tr.note-row td {
            font-style: italic;
            color: #666;
        }
        
        .long-desc {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .totals-row.total {
            font-weight: 700;
            font-size: 14px;
            border-top: 2px solid #333;
            border-bottom: none;
            margin-top: 5px;
            padding-top: 10px;
        }
        
        .content-section {
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .content-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        
        .signature-block {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 10px;
            font-size: 11px;
            color: #666;
        }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print" style="margin-bottom: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
                Print Proposal
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: #fff; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>

        <div class="header">
            <div class="company-info">
                <h1>{{ \App\Models\Option::companyName() ?? 'Company Name' }}</h1>
                <p>{{ \App\Models\Option::get('company_address') ?? '' }}</p>
            </div>
            <div class="proposal-title">
                <h2>PROPOSAL</h2>
                <div class="proposal-number">{{ $proposal->proposal_number }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-block">
                <h3>Proposal To</h3>
                @if($proposal->customer)
                    <p><strong>{{ $proposal->customer->company ?? $proposal->customer->name }}</strong></p>
                @endif
                @if($proposal->address)
                    <p>{{ $proposal->address }}</p>
                @endif
                @if($proposal->city || $proposal->state || $proposal->zip_code)
                    <p>{{ implode(', ', array_filter([$proposal->city, $proposal->state, $proposal->zip_code])) }}</p>
                @endif
                @if($proposal->country)
                    <p>{{ $proposal->country }}</p>
                @endif
                @if($proposal->email)
                    <p>Email: {{ $proposal->email }}</p>
                @endif
                @if($proposal->phone)
                    <p>Phone: {{ $proposal->phone }}</p>
                @endif
            </div>
            <div class="info-block">
                <h3>Proposal Details</h3>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $proposal->date ? $proposal->date->format('M d, Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valid Until:</span>
                    <span class="info-value">{{ $proposal->open_till ? $proposal->open_till->format('M d, Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Currency:</span>
                    <span class="info-value">{{ $proposal->currency }}</span>
                </div>
                @if($proposal->assignedUser)
                <div class="info-row">
                    <span class="info-label">Sales Rep:</span>
                    <span class="info-value">{{ $proposal->assignedUser->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <h3 style="margin-bottom: 10px;">{{ $proposal->subject }}</h3>

        @if($proposal->items->count() > 0)
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Description</th>
                    <th style="width: 60px;">Qty</th>
                    <th style="width: 80px;">Rate</th>
                    <th style="width: 80px;">Tax</th>
                    <th style="width: 100px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $itemNum = 0; @endphp
                @foreach($proposal->items as $item)
                    @if($item->item_type === 'section')
                        <tr class="section-row">
                            <td colspan="6">{{ $item->description }}</td>
                        </tr>
                    @elseif($item->item_type === 'note')
                        <tr class="note-row">
                            <td colspan="6">📝 {{ $item->description }}</td>
                        </tr>
                    @else
                        @php $itemNum++; @endphp
                        <tr>
                            <td>{{ $itemNum }}</td>
                            <td>
                                <strong>{{ $item->description }}</strong>
                                @if($item->long_description)
                                    <div class="long-desc">{{ $item->long_description }}</div>
                                @endif
                            </td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                            <td>{{ $proposal->currency }} {{ number_format($item->rate, 2) }}</td>
                            <td>{{ $item->tax_name ?: '-' }}</td>
                            <td style="text-align: right;">{{ $proposal->currency }} {{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="totals-section">
            <div class="totals-table">
                <div class="totals-row">
                    <span>Sub Total:</span>
                    <span>{{ $proposal->currency }} {{ number_format($proposal->subtotal, 2) }}</span>
                </div>
                @if($proposal->discount_amount > 0)
                <div class="totals-row">
                    <span>Discount ({{ $proposal->discount_percent }}%):</span>
                    <span>-{{ $proposal->currency }} {{ number_format($proposal->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="totals-row">
                    <span>Tax:</span>
                    <span>{{ $proposal->currency }} {{ number_format($proposal->total_tax, 2) }}</span>
                </div>
                @if($proposal->adjustment != 0)
                <div class="totals-row">
                    <span>Adjustment:</span>
                    <span>{{ $proposal->currency }} {{ number_format($proposal->adjustment, 2) }}</span>
                </div>
                @endif
                <div class="totals-row total">
                    <span>Total:</span>
                    <span>{{ $proposal->currency }} {{ number_format($proposal->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($proposal->content)
        <div class="content-section">
            <h3>Terms & Conditions</h3>
            <div>{!! nl2br(e($proposal->content)) !!}</div>
        </div>
        @endif

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line">Authorized Signature</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">Customer Acceptance</div>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Generated on {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
