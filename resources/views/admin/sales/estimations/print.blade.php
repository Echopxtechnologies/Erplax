<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estimation {{ $estimation->estimation_number }}</title>
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
            border-bottom: 2px solid #0ea5e9;
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
            color: #0369a1;
            margin-bottom: 8px;
        }
        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        .estimation-title {
            font-size: 32px;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 5px;
        }
        .estimation-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .estimation-meta {
            font-size: 11px;
            color: #666;
        }
        .estimation-meta table {
            margin-left: auto;
        }
        .estimation-meta td {
            padding: 3px 0;
        }
        .estimation-meta td:first-child {
            text-align: right;
            padding-right: 10px;
            color: #888;
        }
        .estimation-meta td:last-child {
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
        .status-accepted { background: #dcfce7; color: #15803d; }
        .status-declined { background: #fee2e2; color: #dc2626; }
        .status-expired { background: #fef3c7; color: #d97706; }
        .status-invoiced { background: #d1fae5; color: #059669; }
        
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
            color: #0ea5e9;
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
            background: #0ea5e9;
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
            background: #f0f9ff !important;
        }
        .section-row td {
            font-weight: bold;
            color: #0369a1;
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
            border-top: 2px solid #0ea5e9;
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
            color: #0ea5e9;
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
            color: #0ea5e9;
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
            border-left: 3px solid #0ea5e9;
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
            color: #0ea5e9;
        }
        
        /* Amount Due Box */
        .amount-due-box {
            background: linear-gradient(135deg, #0ea5e9, #0369a1);
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
            <div class="estimation-title">ESTIMATE</div>
            <div class="estimation-number">#{{ $estimation->estimation_number }}</div>
            <div class="estimation-meta">
                <table>
                    <tr>
                        <td>Estimate Date:</td>
                        <td>{{ $estimation->date ? $estimation->date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Valid Until:</td>
                        <td>{{ $estimation->valid_until ? $estimation->valid_until->format('d M Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <span class="status-badge status-{{ $estimation->status }}">{{ ucfirst($estimation->status) }}</span>
        </div>
    </div>
    
    <!-- Billing Section -->
    <div class="billing-section">
        <div class="billing-box">
            <div class="billing-title">Prepared For</div>
            @if($estimation->customer)
                <div class="billing-name">
                    {{ $estimation->customer->customer_type === 'company' ? $estimation->customer->company : $estimation->customer->name }}
                </div>
                <div class="billing-details">
                    @if($estimation->customer->customer_type === 'company' && $estimation->customer->name)
                        Attn: {{ $estimation->customer->name }}<br>
                    @endif
                    @if($estimation->address){{ $estimation->address }}<br>@endif
                    @if($estimation->city || $estimation->state){{ $estimation->city }}{{ $estimation->city && $estimation->state ? ', ' : '' }}{{ $estimation->state }}<br>@endif
                    @if($estimation->country){{ $estimation->country }} {{ $estimation->zip_code }}<br>@endif
                    @if($estimation->email)Email: {{ $estimation->email }}<br>@endif
                    @if($estimation->phone)Phone: {{ $estimation->phone }}@endif
                </div>
            @else
                <div class="billing-details">No customer assigned</div>
            @endif
        </div>
        <div class="billing-box">
            <div class="billing-title">Estimate Details</div>
            <div class="billing-details">
                <strong>Currency:</strong> {{ $estimation->currency ?? 'INR' }}<br>
                <strong>Status:</strong> {{ ucfirst($estimation->status ?? 'draft') }}<br>
                <strong>Total Value:</strong> <span class="text-success font-bold">
                    {{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->total, 2) }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Subject -->
    @if($estimation->subject)
    <div style="margin-bottom: 20px; padding: 10px 15px; background: #f0f9ff; border-left: 3px solid #0ea5e9; border-radius: 4px;">
        <strong style="color: #0369a1;">Subject:</strong> 
        <span style="color: #334155;">{{ $estimation->subject }}</span>
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
            @forelse($estimation->items as $item)
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
            @if($estimation->content)
                <div class="notes-section">
                    <div class="notes-title">Notes</div>
                    <div class="notes-content">{{ $estimation->content }}</div>
                </div>
            @endif
            
            @if($estimation->terms_conditions)
                <div class="notes-section">
                    <div class="notes-title">Terms & Conditions</div>
                    <div class="notes-content">{{ $estimation->terms_conditions }}</div>
                </div>
            @endif
        </div>
        <div class="totals-right">
            <div class="amount-due-box">
                <div class="amount-due-label">Total Estimate</div>
                <div class="amount-due-value">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->total, 2) }}</div>
            </div>
            
            <div class="totals-box">
                <div class="totals-row">
                    <span class="totals-label">Subtotal</span>
                    <span class="totals-value">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->subtotal, 2) }}</span>
                </div>
                
                @if(($estimation->discount_amount ?? 0) > 0)
                <div class="totals-row">
                    <span class="totals-label">
                        Discount 
                        @if($estimation->discount_percent > 0)({{ $estimation->discount_percent }}%)@endif
                    </span>
                    <span class="totals-value text-danger">- {{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->discount_amount, 2) }}</span>
                </div>
                @endif
                
                @if(count($taxBreakdown) > 0)
                <div class="tax-breakdown">
                    <div class="tax-breakdown-title">Tax Breakdown</div>
                    @foreach($taxBreakdown as $tax)
                    <div class="tax-breakdown-item">
                        <span class="tax-breakdown-name">{{ $tax['name'] }} ({{ $tax['rate'] }}%)</span>
                        <span class="tax-breakdown-amount">{{ $estimation->currency ?? 'INR' }} {{ number_format($tax['amount'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="totals-row">
                    <span class="totals-label">Total Tax</span>
                    <span class="totals-value text-success">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->tax_amount ?? 0, 2) }}</span>
                </div>
                
                @if(($estimation->adjustment ?? 0) != 0)
                <div class="totals-row">
                    <span class="totals-label">Adjustment</span>
                    <span class="totals-value">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->adjustment, 2) }}</span>
                </div>
                @endif
                
                <div class="totals-row grand">
                    <span class="totals-label">Grand Total</span>
                    <span class="totals-value">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">Thank you for considering our services!</div>
        <div class="footer-company">{{ $company['name'] }}</div>
        <div class="footer-text" style="margin-top: 10px;">
            This is a computer-generated estimate. No signature required.
        </div>
    </div>
</body>
</html>