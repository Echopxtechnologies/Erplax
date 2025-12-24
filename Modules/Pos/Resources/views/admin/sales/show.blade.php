<style>
.show-page{max-width:800px;margin:0 auto;padding:20px}
.show-header{display:flex;align-items:center;gap:16px;margin-bottom:24px}
.btn-back{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:var(--card-bg);border:1px solid var(--card-border);border-radius:10px;color:var(--text-secondary);text-decoration:none}
.btn-back:hover{background:var(--body-bg);color:var(--text-primary)}
.btn-back svg{width:20px;height:20px}
.show-header h1{font-size:24px;font-weight:700;color:var(--text-primary);margin:0;flex:1}
.btn-print{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:var(--primary);color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px}
.btn-print:hover{background:var(--primary-hover);color:#fff}
.btn-print svg{width:18px;height:18px}
.show-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.05)}
.show-card-header{padding:24px;border-bottom:1px solid var(--card-border)}
.invoice-title{font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 16px 0;font-family:monospace}
.badges{display:flex;gap:10px;flex-wrap:wrap}
.badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:20px;font-size:13px;font-weight:600}
.badge-completed{background:var(--success-light);color:var(--success)}
.badge-voided{background:var(--danger-light);color:var(--danger)}
.badge-cash{background:#dcfce7;color:#16a34a}
.badge-card{background:#dbeafe;color:#2563eb}
.badge-upi{background:#f3e8ff;color:#9333ea}
.show-card-body{padding:24px}
.detail-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:24px;margin-bottom:24px}
.detail-label{font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px}
.detail-value{font-size:15px;color:var(--text-primary);font-weight:500}
.items-table{width:100%;border-collapse:collapse;margin:24px 0}
.items-table th{padding:12px 16px;text-align:left;font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;background:var(--body-bg);border-bottom:2px solid var(--card-border)}
.items-table td{padding:14px 16px;border-bottom:1px solid var(--card-border);font-size:14px}
.item-name{font-weight:600;color:var(--text-primary)}
.item-variant{font-size:12px;color:var(--primary);font-weight:500}
.totals-box{background:var(--body-bg);border-radius:12px;padding:20px}
.total-row{display:flex;justify-content:space-between;padding:8px 0;font-size:15px}
.total-row.discount{color:var(--success)}
.total-row.final{font-size:22px;font-weight:800;border-top:2px solid var(--card-border);margin-top:10px;padding-top:14px}
.show-card-footer{padding:16px 24px;background:var(--body-bg);border-top:1px solid var(--card-border);display:flex;gap:24px;flex-wrap:wrap}
.meta-item{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted)}
.meta-item svg{width:16px;height:16px}
</style>

<div class="show-page">
<div class="show-header">
<a href="{{ route('admin.pos.sales') }}" class="btn-back"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></a>
<h1>Sale Details</h1>
<a href="{{ route('admin.pos.receipt', $sale->id) }}" target="_blank" class="btn-print"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>Print</a>
</div>

<div class="show-card">
<div class="show-card-header">
<h2 class="invoice-title">{{ $sale->invoice_no }}</h2>
<div class="badges">
<span class="badge badge-{{ $sale->status }}">{{ ucfirst($sale->status) }}</span>
<span class="badge badge-{{ $sale->payment_method }}">{{ strtoupper($sale->payment_method) }}</span>
</div>
</div>

<div class="show-card-body">
<div class="detail-grid">
<div><div class="detail-label">Customer</div><div class="detail-value">{{ $sale->customer_name ?: 'Walk-in' }}</div></div>
<div><div class="detail-label">Phone</div><div class="detail-value">{{ $sale->customer_phone ?: '-' }}</div></div>
<div><div class="detail-label">Cashier</div><div class="detail-value">{{ $sale->admin->name ?? '-' }}</div></div>
<div><div class="detail-label">Session</div><div class="detail-value" style="font-family:monospace">{{ $sale->session->session_code ?? '-' }}</div></div>
</div>

<table class="items-table">
<thead><tr><th>Item</th><th>Qty</th><th>Price</th><th style="text-align:right">Total</th></tr></thead>
<tbody>
@foreach($sale->items as $item)
<tr>
<td><div class="item-name">{{ $item->product_name }}</div>@if($item->variant_name)<div class="item-variant">{{ $item->variant_name }}</div>@endif</td>
<td>{{ $item->qty }}</td>
<td>₹{{ number_format($item->price, 2) }}</td>
<td style="text-align:right;font-weight:600">₹{{ number_format($item->line_total, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="totals-box">
<div class="total-row"><span>Subtotal</span><span>₹{{ number_format($sale->subtotal, 2) }}</span></div>
@if($sale->discount_amount > 0)<div class="total-row discount"><span>Discount</span><span>-₹{{ number_format($sale->discount_amount, 2) }}</span></div>@endif
<div class="total-row"><span>Tax</span><span>₹{{ number_format($sale->tax_amount, 2) }}</span></div>
<div class="total-row final"><span>Total</span><span>₹{{ number_format($sale->total, 2) }}</span></div>
@if($sale->payment_method == 'cash' && $sale->cash_received)
<div class="total-row" style="margin-top:12px;padding-top:12px;border-top:1px dashed var(--card-border)"><span>Cash Received</span><span>₹{{ number_format($sale->cash_received, 2) }}</span></div>
<div class="total-row"><span>Change</span><span>₹{{ number_format($sale->change_amount, 2) }}</span></div>
@endif
</div>
</div>

<div class="show-card-footer">
<div class="meta-item"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>{{ $sale->created_at->format('M d, Y \a\t h:i A') }}</div>
</div>
</div>
</div>
