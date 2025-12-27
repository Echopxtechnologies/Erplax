<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Noto Sans', Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
            line-height: 1.5; 
            background: #f5f5f5; 
            padding: 20px; 
        }
        .invoice-container { max-width: 800px; margin: 0 auto; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        /* Header */
        .invoice-header { 
            padding: 25px 30px; 
            border-bottom: 3px solid #3b82f6; 
        }
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; }
        .company-info { width: 60%; }
        .company-info h1 { font-size: 22px; color: #1f2937; margin-bottom: 5px; }
        .company-info p { color: #6b7280; font-size: 11px; margin: 2px 0; }
        .company-info .gstin { font-weight: 600; color: #374151; margin-top: 8px; font-size: 11px; }
        
        .invoice-title { width: 40%; text-align: right; }
        .invoice-title h2 { font-size: 26px; color: #3b82f6; margin-bottom: 5px; letter-spacing: 1px; }
        .invoice-title .invoice-no { font-size: 15px; color: #374151; font-weight: 600; }
        .invoice-title .invoice-date { color: #6b7280; font-size: 12px; margin-top: 5px; }
        
        /* Body */
        .invoice-body { padding: 25px 30px; }
        
        /* Info Sections */
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; width: 50%; padding: 0 15px 0 0; }
        .info-table td:last-child { padding: 0 0 0 15px; }
        .info-box h3 { 
            font-size: 10px; 
            text-transform: uppercase; 
            color: #6b7280; 
            margin-bottom: 10px; 
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .info-box p { color: #374151; font-size: 11px; margin: 3px 0; }
        .info-box .name { font-weight: 600; font-size: 13px; color: #1f2937; }
        
        /* Order Info Bar */
        .order-info { 
            background: #f3f4f6; 
            padding: 12px 15px; 
            margin-bottom: 20px; 
            border-radius: 6px;
        }
        .order-info table { width: 100%; }
        .order-info td { padding: 0 20px 0 0; }
        .order-info .label { font-size: 9px; text-transform: uppercase; color: #6b7280; display: block; }
        .order-info .value { font-weight: 600; color: #1f2937; font-size: 12px; }
        
        /* Items Table */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .items-table th { 
            background: #1f2937; 
            color: #fff;
            padding: 10px 8px; 
            text-align: left; 
            font-size: 10px; 
            text-transform: uppercase;
            font-weight: 600;
        }
        .items-table th.text-center { text-align: center; }
        .items-table th.text-right { text-align: right; }
        .items-table td { 
            padding: 12px 8px; 
            border-bottom: 1px solid #e5e7eb; 
            font-size: 11px;
        }
        .items-table tbody tr:nth-child(even) { background: #f9fafb; }
        .items-table .item-name { font-weight: 500; color: #1f2937; }
        .items-table .item-variant { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .items-table .item-hsn { font-size: 9px; color: #9ca3af; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .items-table .sr-no { width: 35px; text-align: center; color: #6b7280; }
        
        /* Totals */
        .totals-table { width: 100%; margin-bottom: 15px; }
        .totals-table td { vertical-align: top; }
        .totals-notes { width: 50%; padding-right: 30px; }
        .totals-section { width: 50%; }
        
        .totals-box { 
            background: #f9fafb; 
            padding: 15px; 
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .totals-box table { width: 100%; }
        .totals-box td { padding: 6px 0; font-size: 11px; }
        .totals-box .label { color: #6b7280; }
        .totals-box .value { text-align: right; font-weight: 500; color: #1f2937; }
        .totals-box .total-row td { 
            border-top: 2px solid #1f2937; 
            padding-top: 12px; 
            margin-top: 8px;
        }
        .totals-box .total-row .label { font-weight: 700; font-size: 13px; color: #1f2937; }
        .totals-box .total-row .value { font-size: 15px; font-weight: 700; color: #3b82f6; }
        .totals-box .sub-row td { padding-left: 15px; font-size: 10px; }
        
        /* Amount in Words */
        .amount-words {
            background: #fef3c7;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 11px;
            color: #92400e;
            margin-bottom: 15px;
            border: 1px solid #fcd34d;
        }
        .amount-words strong { color: #78350f; }
        
        /* Footer */
        .invoice-footer { 
            padding: 20px 30px; 
            border-top: 1px solid #e5e7eb; 
            background: #f9fafb;
        }
        .footer-note { 
            text-align: center; 
            color: #374151; 
            font-size: 13px; 
            margin-bottom: 8px;
            font-weight: 500;
        }
        .footer-terms { 
            font-size: 10px; 
            color: #9ca3af; 
            text-align: center; 
        }
        
        /* Status Badge */
        .status-badge { 
            display: inline-block; 
            padding: 3px 10px; 
            border-radius: 12px; 
            font-size: 10px; 
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e0e7ff; color: #3730a3; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        /* Print Button */
        .print-btn { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            padding: 12px 24px; 
            background: #3b82f6; 
            color: #fff; 
            border: none; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 600; 
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(59,130,246,0.3);
        }
        .print-btn:hover { background: #2563eb; }
        
        @media print {
            body { background: #fff; padding: 0; font-size: 11px; }
            .invoice-container { box-shadow: none; }
            .print-btn { display: none; }
            .items-table th { background: #374151 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        @if($settings->site_logo)
                            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" style="max-height: 50px; margin-bottom: 10px;">
                        @else
                            <h1>{{ $settings->site_name ?? 'Your Store' }}</h1>
                        @endif
                        @if($settings->store_address)
                        <p>{{ $settings->store_address }}</p>
                        <p>{{ $settings->store_city }}{{ $settings->store_state ? ', ' . $settings->store_state : '' }}{{ $settings->store_pincode ? ' - ' . $settings->store_pincode : '' }}</p>
                        @endif
                        @if($settings->contact_phone)
                        <p>Phone: {{ $settings->contact_phone }}</p>
                        @endif
                        @if($settings->contact_email)
                        <p>Email: {{ $settings->contact_email }}</p>
                        @endif
                        @if($settings->store_gstin)
                        <p class="gstin">GSTIN: {{ $settings->store_gstin }}</p>
                        @endif
                    </td>
                    <td class="invoice-title">
                        <h2>INVOICE</h2>
                        <div class="invoice-no">{{ $order->invoice_no ?? $order->order_no }}</div>
                        <div class="invoice-date">{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="invoice-body">
            <!-- Bill To / Ship To -->
            <table class="info-table">
                <tr>
                    <td>
                        <div class="info-box">
                            <h3>Bill To</h3>
                            <p class="name">{{ $order->customer_name }}</p>
                            @if($order->billing_address)
                            <p>{{ $order->billing_address }}</p>
                            <p>{{ $order->billing_city }}{{ $order->billing_state ? ', ' . $order->billing_state : '' }} {{ $order->billing_pincode ? '- ' . $order->billing_pincode : '' }}</p>
                            @else
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_pincode ? '- ' . $order->shipping_pincode : '' }}</p>
                            @endif
                            <p>Phone: {{ $order->customer_phone }}</p>
                            @if($order->customer_email)
                            <p>Email: {{ $order->customer_email }}</p>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="info-box">
                            <h3>Ship To</h3>
                            <p class="name">{{ $order->customer_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_pincode ? '- ' . $order->shipping_pincode : '' }}</p>
                            <p>Phone: {{ $order->customer_phone }}</p>
                        </div>
                    </td>
                </tr>
            </table>
            
            <!-- Order Info -->
            <div class="order-info">
                <table>
                    <tr>
                        <td>
                            <span class="label">Order Date</span>
                            <span class="value">{{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                        </td>
                        <td>
                            <span class="label">Order No</span>
                            <span class="value">{{ $order->order_no }}</span>
                        </td>
                        <td>
                            <span class="label">Payment Method</span>
                            <span class="value">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                        </td>
                        <td>
                            <span class="label">Status</span>
                            <span class="status-badge status-{{ strtolower($order->status) }}">{{ strtoupper($order->status) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="sr-no">#</th>
                        <th style="width: 40%;">Product</th>
                        <th>HSN</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Rate</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td class="sr-no">{{ $index + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->variation_name)
                            <div class="item-variant">{{ $item->variation_name }}</div>
                            @endif
                            @if($item->sku)
                            <div class="item-variant">SKU: {{ $item->sku }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="item-hsn">{{ $item->hsn_code ?? '-' }}</span>
                        </td>
                        <td class="text-center">{{ (int)$item->quantity }}</td>
                        <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">Rs. {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Totals -->
            <table class="totals-table">
                <tr>
                    <td class="totals-notes">
                        @if($order->notes)
                        <div style="font-size: 10px; color: #6b7280; background: #f3f4f6; padding: 10px; border-radius: 6px;">
                            <strong style="color: #374151;">Notes:</strong><br>
                            {{ $order->notes }}
                        </div>
                        @endif
                    </td>
                    <td class="totals-section">
                        <div class="totals-box">
                            <table>
                                <tr>
                                    <td class="label">Subtotal</td>
                                    <td class="value">Rs. {{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                
                                @if($order->tax_amount > 0)
                                <tr>
                                    <td class="label">Tax (GST)</td>
                                    <td class="value">Rs. {{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                @if($settings->show_tax_breakup ?? false)
                                @php $halfTax = $order->tax_amount / 2; @endphp
                                <tr class="sub-row">
                                    <td class="label">↳ CGST</td>
                                    <td class="value">Rs. {{ number_format($halfTax, 2) }}</td>
                                </tr>
                                <tr class="sub-row">
                                    <td class="label">↳ SGST</td>
                                    <td class="value">Rs. {{ number_format($halfTax, 2) }}</td>
                                </tr>
                                @endif
                                @endif
                                
                                <tr>
                                    <td class="label">Shipping</td>
                                    <td class="value">{{ $order->shipping_fee > 0 ? 'Rs. ' . number_format($order->shipping_fee, 2) : 'FREE' }}</td>
                                </tr>
                                
                                @if($order->cod_fee > 0)
                                <tr>
                                    <td class="label">COD Fee</td>
                                    <td class="value">Rs. {{ number_format($order->cod_fee, 2) }}</td>
                                </tr>
                                @endif
                                
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td class="label">Discount</td>
                                    <td class="value" style="color: #10b981;">-Rs. {{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                
                                <tr class="total-row">
                                    <td class="label">Grand Total</td>
                                    <td class="value">Rs. {{ number_format($order->total, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            
            <!-- Amount in Words -->
            @php
                $total = $order->total;
                $rupees = floor($total);
                $paise = round(($total - $rupees) * 100);
                
                $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                
                if (!function_exists('convertNumberToWords')) {
                    function convertNumberToWords($num) {
                        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                        
                        if ($num < 20) return $ones[$num] ?? '';
                        if ($num < 100) return ($tens[floor($num / 10)] ?? '') . (($num % 10) ? ' ' . ($ones[$num % 10] ?? '') : '');
                        if ($num < 1000) return ($ones[floor($num / 100)] ?? '') . ' Hundred' . (($num % 100) ? ' ' . convertNumberToWords($num % 100) : '');
                        if ($num < 100000) return convertNumberToWords(floor($num / 1000)) . ' Thousand' . (($num % 1000) ? ' ' . convertNumberToWords($num % 1000) : '');
                        if ($num < 10000000) return convertNumberToWords(floor($num / 100000)) . ' Lakh' . (($num % 100000) ? ' ' . convertNumberToWords($num % 100000) : '');
                        return convertNumberToWords(floor($num / 10000000)) . ' Crore' . (($num % 10000000) ? ' ' . convertNumberToWords($num % 10000000) : '');
                    }
                }
                
                $words = convertNumberToWords($rupees) . ' Rupees';
                if ($paise > 0) {
                    $words .= ' and ' . convertNumberToWords($paise) . ' Paise';
                }
                $words .= ' Only';
            @endphp
            <div class="amount-words">
                <strong>Amount in Words:</strong> {{ $words }}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-note">{{ $settings->invoice_footer ?? 'Thank you for your order!' }}</p>
            <p class="footer-terms">This is a computer generated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>
