<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Receipt</title>
<style>
@page { size: 80mm auto; margin: 0; }
@media print {
    html, body { width: 80mm; }
    .no-print { display: none !important; }
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    width: 80mm;
    margin: 0 auto;
    padding: 3mm;
    background: #fff;
    color: #000;
}
pre {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.4;
    white-space: pre;
    margin: 0;
}
.print-box {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    display: flex;
    gap: 10px;
}
.print-box button {
    padding: 12px 24px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    border-radius: 8px;
}
.btn-print { background: #2563eb; color: #fff; }
.btn-close { background: #f3f4f6; color: #333; }
</style>
</head>
<body>
@php
$w = 42;
$line = str_repeat('-', $w);
$dline = str_repeat('=', $w);

$center = function($text) use ($w) {
    $pad = floor(($w - mb_strlen($text)) / 2);
    return str_repeat(' ', max(0, $pad)) . $text;
};
$row = function($left, $right) use ($w) {
    $space = $w - strlen($left) - strlen($right);
    return $left . str_repeat(' ', max(1, $space)) . $right;
};

$storeName = $settings->store_name ?? 'EchoPx Store';
$storeAddr = $settings->store_address ?? '';
$storePhone = $settings->store_phone ?? '';
$storeGstin = $settings->store_gstin ?? '';
$storeFooter = $settings->receipt_footer ?? 'Thank you for shopping!';

// Build receipt
$receipt = [];
$receipt[] = $center($storeName);
if($storeAddr) $receipt[] = $center($storeAddr);
if($storePhone) $receipt[] = $center('Tel: ' . $storePhone);
if($storeGstin) $receipt[] = $center('GSTIN: ' . $storeGstin);
$receipt[] = $dline;
$receipt[] = $row('Invoice:', $sale->invoice_no);
$receipt[] = $row('Date:', $sale->created_at->format('d/m/Y h:i A'));
if($sale->customer_name) $receipt[] = $row('Customer:', $sale->customer_name);
$receipt[] = $row('Cashier:', $sale->admin->name ?? '-');
$receipt[] = $line;

foreach($sale->items as $item) {
    $name = $item->product_name;
    if($item->variant_name) $name .= ' ('.$item->variant_name.')';
    if(strlen($name) > $w) $name = substr($name, 0, $w-2).'..';
    $receipt[] = $name;
    $taxInfo = ($item->tax_rate > 0) ? ' +'.number_format($item->tax_rate,0).'%' : '';
    $receipt[] = $row($item->qty . ' x ' . number_format($item->price, 2) . $taxInfo, number_format($item->line_total, 2));
}

$receipt[] = $line;
$receipt[] = $row('Subtotal:', number_format($sale->subtotal, 2));
if($sale->discount_amount > 0) $receipt[] = $row('Discount:', '-' . number_format($sale->discount_amount, 2));
if($sale->tax_amount > 0) $receipt[] = $row('Tax (GST):', number_format($sale->tax_amount, 2));
$receipt[] = $dline;
$receipt[] = $row('TOTAL:', 'Rs.' . number_format($sale->total, 2));
$receipt[] = $line;
$receipt[] = $row('Payment:', strtoupper($sale->payment_method));
if($sale->payment_method == 'cash') {
    $receipt[] = $row('Received:', number_format($sale->cash_received ?? $sale->total, 2));
    $receipt[] = $row('Change:', number_format($sale->change_amount ?? 0, 2));
}
$receipt[] = $line;
$receipt[] = $center($sale->invoice_no);
$receipt[] = $center($storeFooter);
$receipt[] = $center(now()->format('d/m/Y h:i A'));
@endphp
<pre>{{ implode("\n", $receipt) }}</pre>

<div class="print-box no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Print</button>
    <button class="btn-close" onclick="window.close()">✕ Close</button>
</div>
<script>window.onload=function(){window.print();}</script>
</body>
</html>
