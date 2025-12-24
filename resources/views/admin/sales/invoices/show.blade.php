<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
           background: #f1f5f9;
            color: var(--gray-800);
            line-height: 1.5;
            min-height: 100vh;
        }

        .page-wrapper { padding: 24px; max-width: 1400px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--gray-900); margin: 0; display: flex; align-items: center; gap: 10px; }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .btn { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            padding: 10px 18px; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 14px; 
            cursor: pointer; 
            transition: all 0.2s; 
            text-decoration: none; 
            border: none; 
        }
        .btn-secondary { background: white; color: var(--gray-700); border: 1px solid var(--gray-300); }
        .btn-secondary:hover { background: var(--gray-50); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }
        .btn-success { background: linear-gradient(135deg, var(--success), #059669); color: white; }
        .btn-danger { background: linear-gradient(135deg, var(--danger), #dc2626); color: white; }
        .btn-warning { background: linear-gradient(135deg, var(--warning), #d97706); color: white; }

        .two-column { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
        @media (max-width: 1024px) { .two-column { grid-template-columns: 1fr; } }

        .card { background: white; border-radius: 12px; border: 1px solid var(--gray-200); margin-bottom: 20px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); display: flex; justify-content: space-between; align-items: center; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: var(--gray-800); margin: 0; display: flex; align-items: center; gap: 8px; }
        .card-body { padding: 20px; }

        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1d4ed8; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-partial, .status-partially_paid { background: #fef3c7; color: #92400e; }
        .status-overdue, .status-cancelled, .status-unpaid { background: #fee2e2; color: #991b1b; }

        .amount-box { background: linear-gradient(135deg, #41c0ff, #baf5ff); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px; }
        .amount-box.paid { background: linear-gradient(135deg, #10b981, #34d399); }
        .amount-box.overdue { background: linear-gradient(135deg, #ef4444, #f87171); }
        .amount-box .label { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.9); text-transform: uppercase; margin-bottom: 4px; }
        .amount-box .amount { font-size: 32px; font-weight: 800; color: white; }
        .amount-box .due-date { font-size: 13px; color: rgba(255,255,255,0.9); margin-top: 4px; }

        .info-grid { display: grid; gap: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--gray-100); }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--gray-500); font-size: 13px; }
        .info-value { color: var(--gray-800); font-weight: 600; font-size: 14px; text-align: right; }

        .customer-info h4 { font-size: 18px; font-weight: 700; color: var(--gray-900); margin: 0 0 8px 0; }
        .customer-info p { margin: 4px 0; color: var(--gray-600); font-size: 14px; }
        .customer-info a { color: var(--primary); text-decoration: none; }

        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { text-align: left; padding: 12px; font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; background: var(--gray-50); border-bottom: 2px solid var(--gray-200); }
        .items-table td { padding: 14px 12px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
        .items-table th:last-child, .items-table td:last-child { text-align: right; }
        .items-table th.text-right, .items-table td.text-right { text-align: right; }
        .items-table .item-name { font-weight: 600; color: var(--gray-800); }
        .items-table .item-desc { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

        .section-row { background: linear-gradient(135deg, #eff6ff, #dbeafe) !important; }
        .section-row td { padding: 10px 12px !important; font-weight: 700 !important; color: var(--gray-700) !important; border-bottom: 2px solid #bfdbfe !important; }
        .note-row { background: linear-gradient(135deg, #fffbeb, #fef3c7) !important; }
        .note-row td { padding: 10px 12px !important; font-style: italic !important; color: var(--gray-600) !important; border-bottom: 1px solid #fde68a !important; }

        .totals-section { padding: 16px 20px; background: var(--gray-50); border-top: 2px solid var(--gray-200); }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .totals-row.grand { padding-top: 12px; margin-top: 8px; border-top: 2px solid var(--gray-300); }
        .totals-row.grand .label { font-size: 16px; font-weight: 700; }
        .totals-row.grand .value { font-size: 20px; font-weight: 800; color: var(--primary); }
        .totals-row .label { color: var(--gray-600); }
        .totals-row .value { font-weight: 600; color: var(--gray-800); }
        .totals-row .value.success { color: var(--success); }
        .totals-row .value.danger { color: var(--danger); }

        .tax-breakdown-section { margin: 8px 0; padding: 12px; background: white; border-radius: 8px; border: 1px solid var(--gray-200); }
        .tax-breakdown-title { font-size: 12px; font-weight: 700; color: var(--gray-600); text-transform: uppercase; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .tax-breakdown-item { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px dashed var(--gray-200); }
        .tax-breakdown-item:last-child { border-bottom: none; }
        .tax-breakdown-item .tax-name { color: var(--gray-600); }
        .tax-breakdown-item .tax-amount { color: var(--success); font-weight: 600; }
        .tax-total-row { display: flex; justify-content: space-between; padding: 8px 0; margin-top: 8px; border-top: 1px solid var(--gray-300); font-weight: 600; }

        .tax-badges { display: flex; flex-wrap: wrap; gap: 4px; }
        .tax-badge { display: inline-flex; align-items: center; padding: 3px 8px; background: #fef3f2; border: 1px solid #fecaca; border-radius: 4px; font-size: 11px; font-weight: 600; color: #991b1b; white-space: nowrap; }

        .payment-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--gray-100); }
        .payment-item:last-child { border-bottom: none; }
        .payment-info .payment-number { font-weight: 600; color: var(--gray-800); }
        .payment-info .payment-meta { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
        .payment-amount { font-weight: 700; color: var(--success); font-size: 16px; }

        .timeline { position: relative; padding-left: 24px; }
        .timeline::before { content: ''; position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: var(--gray-200); }
        .timeline-item { position: relative; padding-bottom: 16px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot { position: absolute; left: -20px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary); border: 2px solid white; box-shadow: 0 0 0 2px var(--primary); }
        .timeline-dot.success { background: var(--success); box-shadow: 0 0 0 2px var(--success); }
        .timeline-dot.danger { background: var(--danger); box-shadow: 0 0 0 2px var(--danger); }

        .empty-state { text-align: center; padding: 30px; color: var(--gray-500); }

        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; }
        .modal { background: white; border-radius: 16px; width: 100%; max-width: 480px; max-height: 90vh; overflow-y: auto; }
        .modal-header { padding: 20px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { font-size: 18px; font-weight: 700; margin: 0; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--gray-400); }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 16px 20px; border-top: 1px solid var(--gray-200); display: flex; justify-content: flex-end; gap: 10px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 14px; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }

        .status-message { text-align: center; padding: 16px; border-radius: 8px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .status-message.success { background: #d1fae5; color: #065f46; }

        .btn-receipt { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; background: #3b82f6; color: white; border-radius: 4px; font-size: 11px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-receipt:hover { background: #2563eb; }

        @media print { .header-actions, .btn, .modal-backdrop { display: none !important; } .two-column { grid-template-columns: 1fr; } body { background: white; } }
    </style>
</head>
<body>

@php
    $taxesMap = \App\Models\Tax::pluck('rate', 'id')->toArray();
    $taxNamesMap = \App\Models\Tax::pluck('name', 'id')->toArray();
    
    function parseItemTaxIds($taxIds) {
        if (empty($taxIds)) return [];
        if (is_array($taxIds)) return array_map('intval', $taxIds);
        if (is_string($taxIds)) {
            $decoded = json_decode($taxIds, true);
            if (is_array($decoded)) return array_map('intval', $decoded);
            if (strpos($taxIds, ',') !== false) return array_map('intval', explode(',', $taxIds));
            return $taxIds ? [intval($taxIds)] : [];
        }
        return is_numeric($taxIds) ? [intval($taxIds)] : [];
    }
    
    $taxBreakdown = [];
    $totalTax = 0;
    
    foreach ($invoice->items as $item) {
        if ($item->item_type === 'product' && $item->tax_ids) {
            $itemTaxIds = parseItemTaxIds($item->tax_ids);
            foreach ($itemTaxIds as $taxId) {
                $taxRate = $taxesMap[$taxId] ?? 0;
                $taxName = $taxNamesMap[$taxId] ?? 'Tax';
                $taxAmount = ($item->amount * $taxRate) / 100;
                $totalTax += $taxAmount;
                
                $key = $taxName . ' (' . $taxRate . '%)';
                if (!isset($taxBreakdown[$key])) {
                    $taxBreakdown[$key] = ['rate' => $taxRate, 'amount' => 0];
                }
                $taxBreakdown[$key]['amount'] += $taxAmount;
            }
        }
    }
    
    if ($totalTax == 0 && $invoice->tax > 0) {
        $totalTax = $invoice->tax;
    }
@endphp

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">📄 Invoice {{ $invoice->invoice_number }}</h1>
        <div class="header-actions">
            <a href="{{ route('admin.sales.invoices.index') }}" class="btn btn-secondary">← Back</a>
            <a href="{{ route('admin.sales.invoices.edit', $invoice->id) }}" class="btn btn-secondary">✏️ Edit</a>
<a href="{{ route('admin.sales.invoices.print', $invoice->id) }}" 
   target="_blank" 
   class="btn btn-secondary">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
    </svg>
    Print PDF
</a>            @if($invoice->amount_due > 0)<button onclick="openPaymentModal()" class="btn btn-success">💰 Record Payment</button>@endif
        </div>
    </div>

    <div class="two-column">
        <div class="left-column">
            <!-- Customer Card -->
            <div class="card">
                <div class="card-header">
                    <h3>👤 Bill To</h3>
                    <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                </div>
                <div class="card-body customer-info">
                    <h4>{{ $invoice->customer->name ?? 'N/A' }}</h4>
                    @if($invoice->email)<p><a href="mailto:{{ $invoice->email }}">{{ $invoice->email }}</a></p>@endif
                    @if($invoice->phone)<p>📞 {{ $invoice->phone }}</p>@endif
                    @if($invoice->address)<p>{{ $invoice->address }}</p>@endif
                    @if($invoice->city || $invoice->state || $invoice->zip_code)<p>{{ $invoice->city }}{{ $invoice->city && $invoice->state ? ', ' : '' }}{{ $invoice->state }}{{ $invoice->zip_code ? ', ' . $invoice->zip_code : '' }}</p>@endif
                    @if($invoice->country)<p>{{ $invoice->country }}</p>@endif
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="card">
                <div class="card-header"><h3>📦 Invoice Items</h3></div>
                <div style="overflow-x: auto;">
                    <table class="items-table">
                        <thead><tr><th>Product</th><th class="text-right">Quantity</th><th class="text-right">Unit Price</th><th>Taxes</th><th class="text-right">Amount</th></tr></thead>
                        <tbody>
                            @forelse($invoice->items as $item)
                                @if(($item->item_type ?? 'product') === 'section')
                                    <tr class="section-row"><td colspan="5"><strong>{{ $item->description }}</strong></td></tr>
                                @elseif(($item->item_type ?? 'product') === 'note')
                                    <tr class="note-row"><td colspan="5"><em>{{ $item->long_description ?: $item->description }}</em></td></tr>
                                @else
                                    @php $itemTaxIds = parseItemTaxIds($item->tax_ids); @endphp
                                    <tr>
                                        <td><div class="item-name">{{ $item->description }}</div>@if($item->long_description)<div class="item-desc">{{ $item->long_description }}</div>@endif</td>
                                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="text-right">{{ $invoice->currency ?? 'INR' }} {{ number_format($item->rate, 2) }}</td>
                                        <td>
                                            @if(count($itemTaxIds) > 0)
                                                <div class="tax-badges">
                                                    @foreach($itemTaxIds as $taxId)
                                                        <span class="tax-badge">{{ $taxesMap[$taxId] ?? 0 }}% {{ $taxNamesMap[$taxId] ?? 'Tax' }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span style="color: var(--gray-400);">-</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $invoice->currency ?? 'INR' }} {{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr><td colspan="5" class="empty-state">No items found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="totals-section">
                    <div class="totals-row"><span class="label">Subtotal</span><span class="value">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->subtotal, 2) }}</span></div>
                    @if(($invoice->discount ?? 0) > 0)<div class="totals-row"><span class="label">Discount @if($invoice->discount_percent > 0)({{ $invoice->discount_percent }}%)@endif</span><span class="value" style="color: var(--danger);">- {{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->discount, 2) }}</span></div>@endif
                    @if(count($taxBreakdown) > 0)
                    <div class="tax-breakdown-section">
                        <div class="tax-breakdown-title">🧾 Tax Summary</div>
                        @foreach($taxBreakdown as $taxName => $taxData)<div class="tax-breakdown-item"><span class="tax-name">{{ $taxName }}</span><span class="tax-amount">{{ $invoice->currency ?? 'INR' }} {{ number_format($taxData['amount'], 2) }}</span></div>@endforeach
                        <div class="tax-total-row"><span class="label">Total Tax</span><span class="value" style="color: var(--success);">{{ $invoice->currency ?? 'INR' }} {{ number_format($totalTax, 2) }}</span></div>
                    </div>
                    @elseif($totalTax > 0)<div class="totals-row"><span class="label">Tax</span><span class="value success">{{ $invoice->currency ?? 'INR' }} {{ number_format($totalTax, 2) }}</span></div>@endif
                    @php $grandTotal = $invoice->subtotal - ($invoice->discount ?? 0) + $totalTax; @endphp
                    <div class="totals-row grand"><span class="label">Total</span><span class="value">{{ $invoice->currency ?? 'INR' }} {{ number_format($grandTotal, 2) }}</span></div>
                    <div class="totals-row"><span class="label">Amount Paid</span><span class="value success">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_paid, 2) }}</span></div>
                    <div class="totals-row"><span class="label">Amount Due</span><span class="value danger">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_due, 2) }}</span></div>
                </div>
            </div>

            @if($invoice->content || $invoice->terms_conditions)
            <div class="card">
                <div class="card-header"><h3>📝 Notes & Terms</h3></div>
                <div class="card-body">
                    @if($invoice->content)<div style="margin-bottom: 16px;"><strong style="color: var(--gray-700);">Customer Notes:</strong><p style="margin-top: 4px; color: var(--gray-600);">{{ $invoice->content }}</p></div>@endif
                    @if($invoice->terms_conditions)<div><strong style="color: var(--gray-700);">Terms & Conditions:</strong><p style="margin-top: 4px; color: var(--gray-600);">{{ $invoice->terms_conditions }}</p></div>@endif
                </div>
            </div>
            @endif
        </div>

        <div class="right-column">
            @php $isOverdue = $invoice->due_date && $invoice->due_date->isPast() && $invoice->amount_due > 0; $isPaid = $invoice->payment_status === 'paid' || $invoice->amount_due <= 0; @endphp
            <div class="amount-box {{ $isPaid ? 'paid' : ($isOverdue ? 'overdue' : '') }}">
                <div class="label">{{ $isPaid ? 'Paid in Full' : 'Amount Due' }}</div>
                <div class="amount">{{ $invoice->currency ?? 'INR' }} {{ number_format($invoice->amount_due, 2) }}</div>
                @if($invoice->due_date && !$isPaid)<div class="due-date">{{ $isOverdue ? 'OVERDUE SINCE' : 'DUE' }}: {{ $invoice->due_date->format('d M Y') }}</div>@endif
            </div>

            <!-- Invoice Details -->
            <div class="card">
                <div class="card-header"><h3>ℹ️ Invoice Details</h3></div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row"><span class="info-label">Invoice Number</span><span class="info-value">{{ $invoice->invoice_number }}</span></div>
                        <div class="info-row"><span class="info-label">Invoice Date</span><span class="info-value">{{ $invoice->date->format('d M Y') }}</span></div>
                        <div class="info-row"><span class="info-label">Due Date</span><span class="info-value">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</span></div>
                        <div class="info-row"><span class="info-label">Status</span><span class="info-value"><span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></span></div>
                        <div class="info-row"><span class="info-label">Payment Status</span><span class="info-value"><span class="status-badge status-{{ $invoice->payment_status }}">{{ ucfirst(str_replace('_', ' ', $invoice->payment_status)) }}</span></span></div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header"><h3>⚡ Actions</h3></div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                    @if($invoice->status === 'draft')<button type="button" class="btn btn-primary" onclick="updateStatus('sent')" style="width: 100%;">📤 Mark as Sent</button>@endif
                    @if(in_array($invoice->status, ['draft', 'sent']) && $invoice->amount_due > 0)
                        <button type="button" class="btn btn-success" onclick="openPaymentModal()" style="width: 100%;">💰 Record Payment</button>
                        <button type="button" class="btn btn-success" onclick="updateStatus('paid')" style="width: 100%;">✅ Mark as Paid</button>
                    @endif
                    @if($invoice->status !== 'cancelled' && $invoice->payment_status !== 'paid')<button type="button" class="btn btn-danger" onclick="updateStatus('cancelled')" style="width: 100%;">❌ Cancel Invoice</button>@endif
                    @if($invoice->status === 'cancelled')<button type="button" class="btn btn-warning" onclick="updateStatus('draft')" style="width: 100%;">↩️ Revert to Draft</button>@endif
                    @if($invoice->payment_status === 'paid')<div class="status-message success">✅ Invoice Paid in Full</div>@endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="card">
                <div class="card-header"><h3>💳 Payment History</h3></div>
                <div class="card-body">
                    @if($invoice->payments && $invoice->payments->count() > 0)
                        @foreach($invoice->payments as $payment)
                        <div class="payment-item">
                            <div class="payment-info">
                                <div class="payment-number">{{ $payment->payment_number }}</div>
                                <div class="payment-meta">{{ $payment->payment_date->format('d M Y') }} • {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="payment-amount">{{ $invoice->currency ?? 'INR' }} {{ number_format($payment->amount, 2) }}</div>
                                <a href="{{ route('admin.sales.payments.receipt.show', $payment->id) }}" class="btn-receipt">👁️ View</a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="empty-state">No payments recorded yet</p>
                    @endif
                </div>
            </div>

            <!-- Activity -->
            <div class="card">
                <div class="card-header"><h3>🕐 Activity</h3></div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item"><div class="timeline-dot success"></div><div class="timeline-content"><div class="title">Invoice Created</div><div class="meta">{{ $invoice->created_at->format('d M Y, h:i A') }}</div></div></div>
                        @if($invoice->payments && $invoice->payments->count() > 0)
                            @foreach($invoice->payments as $payment)
                            <div class="timeline-item"><div class="timeline-dot success"></div><div class="timeline-content"><div class="title">Payment - {{ $invoice->currency ?? 'INR' }} {{ number_format($payment->amount, 2) }}</div><div class="meta">{{ $payment->created_at->format('d M Y, h:i A') }}</div></div></div>
                            @endforeach
                        @endif
                        @if($invoice->status === 'cancelled')<div class="timeline-item"><div class="timeline-dot danger"></div><div class="timeline-content"><div class="title">Invoice Cancelled</div><div class="meta">{{ $invoice->updated_at->format('d M Y, h:i A') }}</div></div></div>@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal-backdrop" id="paymentModal">
    <div class="modal">
        <div class="modal-header"><h3>💰 Record Payment</h3><button class="modal-close" onclick="closePaymentModal()">&times;</button></div>
        <form id="paymentForm" onsubmit="submitPayment(event)">
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Amount *</label><input type="number" name="amount" class="form-control" step="0.01" max="{{ $invoice->amount_due }}" value="{{ $invoice->amount_due }}" required><small style="color: var(--gray-500);">Maximum: {{ number_format($invoice->amount_due, 2) }}</small></div>
                <div class="form-group"><label class="form-label">Payment Date *</label><input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                <div class="form-group"><label class="form-label">Payment Method *</label><select name="payment_method" class="form-control" required><option value="cash">Cash</option><option value="bank_transfer">Bank Transfer</option><option value="upi">UPI</option><option value="card">Card</option><option value="cheque">Cheque</option><option value="other">Other</option></select></div>
                <div class="form-group"><label class="form-label">Transaction ID</label><input type="text" name="transaction_id" class="form-control" placeholder="Optional"></div>
                <div class="form-group"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2" placeholder="Optional"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Cancel</button><button type="submit" class="btn btn-success">💰 Record Payment</button></div>
        </form>
    </div>
</div>

<script>
function openPaymentModal() { document.getElementById('paymentModal').classList.add('show'); }
function closePaymentModal() { document.getElementById('paymentModal').classList.remove('show'); }

function submitPayment(e) {
    e.preventDefault();
    const form = document.getElementById('paymentForm');
    const formData = new FormData(form);
    fetch('{{ route("admin.sales.invoices.recordPayment", $invoice->id) }}', { 
        method: 'POST', 
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, 
        body: formData 
    })
    .then(res => res.json())
    .then(data => { 
        if (data.success) { alert(data.message); window.location.reload(); } 
        else { alert(data.message || 'Error'); } 
    })
    .catch(err => alert('Error recording payment'));
}

function updateStatus(status) {
    if (!confirm(`Change status to "${status}"?`)) return;
    fetch('{{ route("admin.sales.invoices.updateStatus", $invoice->id) }}', { 
        method: 'POST', 
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' }, 
        body: JSON.stringify({ status: status }) 
    })
    .then(r => r.json())
    .then(data => { 
        if (data.success) { alert(data.message || 'Updated'); location.reload(); } 
        else { alert(data.message || 'Error'); } 
    })
    .catch(() => alert('Error'));
}

document.getElementById('paymentModal').addEventListener('click', function(e) { if (e.target === this) closePaymentModal(); });
</script>

</body>
</html> 