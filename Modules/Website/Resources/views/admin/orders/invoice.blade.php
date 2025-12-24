<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; background: #f5f5f5; padding: 20px; }
        .invoice-container { max-width: 800px; margin: 0 auto; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .invoice-header { padding: 30px; border-bottom: 3px solid #3b82f6; display: flex; justify-content: space-between; align-items: flex-start; }
        .company-info h1 { font-size: 24px; color: #1f2937; margin-bottom: 8px; }
        .company-info p { color: #6b7280; font-size: 13px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 28px; color: #3b82f6; margin-bottom: 8px; }
        .invoice-title .invoice-no { font-size: 16px; color: #374151; }
        .invoice-title .invoice-date { color: #6b7280; font-size: 13px; margin-top: 4px; }
        
        .invoice-body { padding: 30px; }
        
        .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; gap: 30px; }
        .info-box { flex: 1; }
        .info-box h3 { font-size: 12px; text-transform: uppercase; color: #6b7280; margin-bottom: 10px; letter-spacing: 0.5px; }
        .info-box p { color: #374151; }
        .info-box .name { font-weight: 600; font-size: 15px; color: #1f2937; }
        
        .order-info { background: #f9fafb; padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 40px; }
        .order-info-item { display: flex; flex-direction: column; }
        .order-info-item label { font-size: 11px; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; }
        .order-info-item span { font-weight: 600; color: #1f2937; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f3f4f6; padding: 12px 15px; text-align: left; font-size: 12px; text-transform: uppercase; color: #6b7280; border-bottom: 2px solid #e5e7eb; }
        .items-table td { padding: 15px; border-bottom: 1px solid #f3f4f6; }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table .item-name { font-weight: 500; color: #1f2937; }
        .items-table .item-variant { font-size: 12px; color: #6b7280; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        
        .totals-section { display: flex; justify-content: flex-end; }
        .totals-box { width: 280px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .totals-row:last-child { border-bottom: none; }
        .totals-row.total { border-top: 2px solid #1f2937; margin-top: 10px; padding-top: 15px; font-size: 18px; font-weight: 700; }
        .totals-row .label { color: #6b7280; }
        .totals-row .value { font-weight: 500; color: #1f2937; }
        .totals-row.total .value { color: #3b82f6; }
        
        .invoice-footer { padding: 30px; border-top: 1px solid #e5e7eb; background: #f9fafb; }
        .footer-note { text-align: center; color: #6b7280; font-size: 13px; margin-bottom: 15px; }
        .footer-terms { font-size: 11px; color: #9ca3af; text-align: center; }
        
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 12px 24px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(59,130,246,0.3); }
        .print-btn:hover { background: #2563eb; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-container { box-shadow: none; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
    
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ $settings->site_name ?? 'Your Store' }}</h1>
                @if($settings->store_address)
                <p>{{ $settings->store_address }}</p>
                <p>{{ $settings->store_city }}, {{ $settings->store_state }} - {{ $settings->store_pincode }}</p>
                @endif
                @if($settings->store_gstin)
                <p style="margin-top: 8px;"><strong>GSTIN:</strong> {{ $settings->store_gstin }}</p>
                @endif
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-no">{{ $order->order_no }}</div>
                <div class="invoice-date">{{ $order->created_at->format('d M Y') }}</div>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="info-section">
                <div class="info-box">
                    <h3>Bill To</h3>
                    <p class="name">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                    <p>{{ $order->shipping_pincode }}</p>
                    <p style="margin-top: 8px;">Phone: {{ $order->customer_phone }}</p>
                    @if($order->customer_email)
                    <p>Email: {{ $order->customer_email }}</p>
                    @endif
                </div>
                <div class="info-box">
                    <h3>Ship To</h3>
                    <p class="name">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                    <p>{{ $order->shipping_pincode }}</p>
                </div>
            </div>
            
            <div class="order-info">
                <div class="order-info-item">
                    <label>Order Date</label>
                    <span>{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="order-info-item">
                    <label>Order No</label>
                    <span>{{ $order->order_no }}</span>
                </div>
                <div class="order-info-item">
                    <label>Payment Method</label>
                    <span>{{ $order->payment_method_label }}</span>
                </div>
                <div class="order-info-item">
                    <label>Payment Status</label>
                    <span class="status-badge status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->variation_name)
                            <div class="item-variant">{{ $item->variation_name }}</div>
                            @endif
                            @if($item->sku)
                            <div class="item-variant">SKU: {{ $item->sku }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="totals-section">
                <div class="totals-box">
                    <div class="totals-row">
                        <span class="label">Subtotal</span>
                        <span class="value">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="totals-row">
                        <span class="label">Tax</span>
                        <span class="value">₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="totals-row">
                        <span class="label">Shipping</span>
                        <span class="value">{{ $order->shipping_fee > 0 ? '₹' . number_format($order->shipping_fee, 2) : 'Free' }}</span>
                    </div>
                    @if($order->cod_fee > 0)
                    <div class="totals-row">
                        <span class="label">COD Charges</span>
                        <span class="value">₹{{ number_format($order->cod_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount_amount > 0)
                    <div class="totals-row">
                        <span class="label">Discount</span>
                        <span class="value" style="color: #10b981;">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="totals-row total">
                        <span class="label">Total</span>
                        <span class="value">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="invoice-footer">
            <p class="footer-note">Thank you for your order!</p>
            @if($settings->invoice_footer)
            <p class="footer-terms">{{ $settings->invoice_footer }}</p>
            @endif
        </div>
    </div>
</body>
</html>
