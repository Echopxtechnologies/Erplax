<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt - {{ $payment->payment_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
        
        .receipt-container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        
        .receipt-header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px solid #3b82f6; 
            padding-bottom: 20px; 
        }
        .receipt-header h1 { 
            color: #3b82f6; 
            font-size: 32px; 
            margin-bottom: 10px; 
        }
        .receipt-number { 
            background: #3b82f6; 
            color: white; 
            padding: 8px 20px; 
            display: inline-block; 
            border-radius: 6px; 
            font-weight: bold; 
            margin-top: 10px;
            font-size: 16px;
        }
        
       .company-section { 
    margin-bottom: 30px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}
.company-section h3 { 
    color: #1f2937; 
    margin-bottom: 10px; 
    font-size: 22px; 
}
.company-section p { 
    margin: 5px 0; 
    color: #6b7280;
    font-size: 14px;
}
        
        .payment-details { 
            background: #f9fafb; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0; 
            border: 2px solid #e5e7eb; 
        }
        .detail-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #e5e7eb; 
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { 
            color: #6b7280; 
            font-weight: 600;
            font-size: 14px;
        }
        .detail-value { 
            color: #1f2937; 
            font-weight: bold;
            font-size: 14px;
        }
        
        .amount-box { 
            background: linear-gradient(135deg, #10b981, #34d399); 
            color: white; 
            padding: 30px; 
            text-align: center; 
            border-radius: 8px; 
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .amount-box .label { 
            font-size: 14px; 
            margin-bottom: 8px;
            opacity: 0.9;
        }
        .amount-box .amount { 
            font-size: 42px; 
            font-weight: bold;
            letter-spacing: -1px;
        }
        
        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #1f2937;
            font-size: 18px;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td { 
            padding: 14px; 
            text-align: left; 
            border: 1px solid #e5e7eb; 
        }
        th { 
            background: #f3f4f6; 
            font-weight: 600; 
            color: #1f2937;
            font-size: 13px;
            text-transform: uppercase;
        }
        td { 
            color: #6b7280;
            font-size: 14px;
        }
        td strong {
            color: #10b981;
            font-size: 16px;
        }
        
        .footer { 
            text-align: center; 
            margin-top: 50px; 
            padding-top: 30px; 
            border-top: 2px solid #e5e7eb; 
            color: #9ca3af; 
            font-size: 13px; 
        }
        .footer p {
            margin: 8px 0;
        }
        .footer strong {
            color: #1f2937;
            font-size: 15px;
        }
        
        .print-btn { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: #3b82f6; 
            color: white; 
            padding: 12px 24px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.2s;
        }
        .print-btn:hover { 
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        
        @media print { 
            body { padding: 20px; background: white; }
            .print-btn { display: none; }
            .receipt-container { box-shadow: none; }
        }
        
        @media (max-width: 768px) {
            body { padding: 20px; }
            .receipt-container { padding: 20px; }
            .amount-box .amount { font-size: 32px; }
        }



        @media print {
    /* Two column layout for print */
    .company-section {
        background: white !important;
        border: 1px solid #000 !important;
        padding: 15px !important;
    }
    
    .company-section h3 {
        color: #000 !important;
        border-bottom: 1px solid #000 !important;
        padding-bottom: 5px !important;
        margin-bottom: 10px !important;
    }
    
    .company-section p {
        color: #333 !important;
        margin: 5px 0 !important;
        font-size: 12px !important;
    }
    .td strong[style*="color: #ef4444"] {
        color: #dc2626 !important;
    }
}
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">🖨️ Print / Save as PDF</button>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>PAYMENT RECEIPT</h1>
            <div class="receipt-number">{{ $payment->payment_number }}</div>
        </div>

        <!-- Company Info -->
        {{-- <div class="company-section">
            <h3>{{ $company['name'] }}</h3>
            <p>{{ $company['address'] }}</p>
            <p>📞 {{ $company['phone'] }} | ✉️ {{ $company['email'] }}</p>
            @if($company['gst'])
                <p><strong>GST:</strong> {{ $company['gst'] }}</p>
            @endif
        </div> --}}




        <!-- Company & Customer Info - Two Columns -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px;">
    <!-- Left: Company Info -->
    <div style="padding: 0;">
        <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">{{ $company['name'] }}</h3>
        <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">{{ $company['address'] }}</p>
        <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">📞 {{ $company['phone'] }}</p>
        <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">✉️ {{ $company['email'] }}</p>
    </div>

    <!-- Right: Customer Billing Address -->
    <div style="padding: 0; text-align: right;">
        @if($customer)
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">
                {{ $customer->name ?? 'N/A' }}
            </h3>
            
            @if($customer->billing_street)
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">{{ $customer->billing_street }}</p>
            @endif
            
            @php
                $billingCity = $customer->billing_city ?? '';
                $billingState = $customer->billing_state ?? '';
                $billingZip = $customer->billing_zip_code ?? '';
                $billingCountry = $customer->billing_country ?? '';
                
                $cityLine = trim("$billingCity, $billingState $billingZip");
            @endphp
            
            @if($cityLine != ', ')
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">{{ $cityLine }}</p>
            @endif
            
            @if($billingCountry)
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">{{ $billingCountry }}</p>
            @endif
            
            @if($customer->phone)
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">📞 {{ $customer->phone }}</p>
            @endif
            
            @if($customer->email)
                <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">✉️ {{ $customer->email }}</p>
            @endif
        @else
            <h3 style="font-size: 18px; font-weight: bold; color: #9ca3af; margin-bottom: 10px;">Customer Information</h3>
            <p style="margin: 5px 0; color: #9ca3af; font-size: 14px; font-style: italic;">
                No customer details available
            </p>
        @endif
    </div>
</div>

        <!-- Payment Details -->
        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Payment Date:</span>
                <span class="detail-value">{{ $payment->payment_date->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Mode:</span>
                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
            </div>
            @if($payment->transaction_id)
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span class="detail-value">{{ $payment->transaction_id }}</span>
            </div>
            @endif
            {{-- <div class="detail-row">
                <span class="detail-label">Received From:</span>
                <span class="detail-value">{{ $payment->invoice->customer->name ?? 'N/A' }}</span>
            </div> --}}
        </div>

        <!-- Amount -->
        <div class="amount-box">
            <div class="label">TOTAL AMOUNT PAID</div>
            <div class="amount">₹{{ number_format($payment->amount, 2) }}</div>
        </div>

        <!-- Payment For -->
        <h3 class="section-title">Payment For Invoice</h3>
        <table>
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Invoice Amount</th>
                    <th>Payment Amount</th>
                    <th>Amount Due</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                     <td><strong>{{ $payment->invoice->invoice_number }}</strong></td>
        <td>{{ $payment->invoice->date->format('d-m-Y') }}</td>
        <td>₹{{ number_format($payment->invoice->total, 2) }}</td>
        <td><strong style="color: #10b981;">₹{{ number_format($payment->amount, 2) }}</strong></td>
        <td><strong style="color: #ef4444;">₹{{ number_format($payment->invoice->amount_due, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        @if($payment->notes)
        <div class="payment-details" style="margin-top: 20px;">
            <h4 style="margin-bottom: 10px; color: #1f2937;">Payment Notes:</h4>
            <p style="color: #6b7280;">{{ $payment->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        {{-- <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated receipt and does not require a physical signature.</p>
            <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
        </div> --}}
    </div>
</body>
</html>