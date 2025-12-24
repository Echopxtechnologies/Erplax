<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Estimation {{ $estimation->estimation_number }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
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
        
        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 24px; 
            flex-wrap: wrap; 
            gap: 16px; 
        }
        
        .page-title { 
            font-size: 24px; 
            font-weight: 700; 
            color: var(--gray-900); 
            margin: 0; 
        }
        
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .btn { 
            display: inline-flex; 
            align-items: center; 
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
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #059669; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #dc2626; }

        .two-column { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
        @media (max-width: 1024px) { .two-column { grid-template-columns: 1fr; } }

        .card { 
            background: white; 
            border-radius: 12px; 
            border: 1px solid var(--gray-200); 
            margin-bottom: 20px; 
            overflow: hidden; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .card-header { 
            padding: 16px 20px; 
            border-bottom: 1px solid var(--gray-200); 
            background: var(--gray-50); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .card-header h3 { 
            font-size: 15px; 
            font-weight: 700; 
            color: var(--gray-800); 
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-body { padding: 20px; }

        .status-badge { 
            display: inline-flex; 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 600; 
            text-transform: uppercase; 
        }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1d4ed8; }
        .status-accepted { background: #d1fae5; color: #065f46; }
        .status-declined { background: #fee2e2; color: #991b1b; }

        .amount-box { 
            background: linear-gradient(135deg, #a78bfa, #c4b5fd); 
            border-radius: 12px; 
            padding: 20px; 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .amount-box.accepted { background: linear-gradient(135deg, #10b981, #059669); }
        .amount-box.declined { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .amount-box .label { font-size: 12px; font-weight: 600; color: white; text-transform: uppercase; margin-bottom: 4px; opacity: 0.9; }
        .amount-box .amount { font-size: 32px; font-weight: 800; color: white; }
        .amount-box .due-date { font-size: 13px; color: white; margin-top: 4px; opacity: 0.9; }

        .info-grid { display: grid; gap: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--gray-100); }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--gray-500); font-size: 13px; }
        .info-value { color: var(--gray-800); font-weight: 600; font-size: 14px; text-align: right; }

        .customer-info h4 { font-size: 18px; font-weight: 700; color: var(--gray-900); margin: 0 0 8px 0; }
        .customer-info p { margin: 4px 0; color: var(--gray-600); font-size: 14px; }
        .customer-info a { color: var(--primary); text-decoration: none; }
        .customer-info a:hover { text-decoration: underline; }

        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { 
            text-align: left; 
            padding: 12px; 
            font-size: 11px; 
            font-weight: 700; 
            color: var(--gray-500); 
            text-transform: uppercase; 
            background: var(--gray-50); 
            border-bottom: 2px solid var(--gray-200); 
        }
        .items-table td { padding: 14px 12px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
        .items-table th:last-child, .items-table td:last-child { text-align: right; }
        .items-table .item-name { font-weight: 600; color: var(--gray-800); }
        .items-table .item-desc { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

        .section-row { background: linear-gradient(135deg, #eff6ff, #dbeafe) !important; }
        .section-row td { padding: 10px 12px !important; font-weight: 700 !important; color: var(--gray-700) !important; }
        .note-row { background: linear-gradient(135deg, #fffbeb, #fef3c7) !important; }
        .note-row td { padding: 10px 12px !important; font-style: italic !important; color: var(--gray-600) !important; }

        .tax-badges { display: flex; flex-wrap: wrap; gap: 4px; }
        .tax-badge { 
            display: inline-flex; 
            padding: 3px 8px; 
            background: #fef3f2; 
            border: 1px solid #fecaca; 
            border-radius: 4px; 
            font-size: 10px; 
            font-weight: 500; 
            color: #991b1b; 
            white-space: nowrap; 
        }

        .totals-section { padding: 16px 20px; background: var(--gray-50); border-top: 2px solid var(--gray-200); }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .totals-row.grand { padding-top: 12px; margin-top: 8px; border-top: 2px solid var(--gray-300); }
        .totals-row.grand .label { font-size: 16px; font-weight: 700; }
        .totals-row.grand .value { font-size: 20px; font-weight: 800; color: var(--primary); }
        .totals-row .label { color: var(--gray-600); }
        .totals-row .value { font-weight: 600; color: var(--gray-800); }

        .tax-breakdown-section { 
            margin: 8px 0; 
            padding: 12px; 
            background: white; 
            border-radius: 8px; 
            border: 1px solid var(--gray-200); 
        }
        .tax-breakdown-title { 
            font-size: 11px; 
            font-weight: 600; 
            color: var(--gray-600); 
            text-transform: uppercase; 
            margin-bottom: 8px; 
        }
        .tax-breakdown-item { 
            display: flex; 
            justify-content: space-between; 
            padding: 4px 0; 
            font-size: 12px; 
        }
        .tax-breakdown-item:last-child { border-bottom: none; }
        .tax-breakdown-item .name { color: var(--gray-600); }
        .tax-breakdown-item .amount { color: var(--success); font-weight: 500; }

        .alert-success { background: #d1fae5; border: 1px solid #10b981; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #065f46; }
        .alert-error { background: #fee2e2; border: 1px solid #dc2626; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #991b1b; }

        /* Activity Timeline */
        .timeline { position: relative; padding-left: 24px; }
        .timeline::before { content: ''; position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background: var(--gray-200); }
        .timeline-item { position: relative; padding-bottom: 20px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot { 
            position: absolute; 
            left: -24px; 
            top: 4px; 
            width: 14px; 
            height: 14px; 
            border-radius: 50%; 
            background: var(--gray-300); 
            border: 3px solid white; 
            box-shadow: 0 0 0 2px var(--gray-200); 
        }
        .timeline-dot.success { background: var(--success); box-shadow: 0 0 0 2px #d1fae5; }
        .timeline-dot.primary { background: var(--primary); box-shadow: 0 0 0 2px #dbeafe; }
        .timeline-dot.warning { background: var(--warning); box-shadow: 0 0 0 2px #fef3c7; }
        .timeline-dot.danger { background: var(--danger); box-shadow: 0 0 0 2px #fee2e2; }
        .timeline-content .title { font-weight: 600; color: var(--gray-800); font-size: 14px; }
        .timeline-content .meta { font-size: 12px; color: var(--gray-500); margin-top: 2px; }

        @media print { 
            .header-actions, .btn { display: none !important; } 
            .two-column { grid-template-columns: 1fr; } 
            body { background: white; }
        }
    </style>
</head>
<body>

@php
function parseItemTaxIds($taxIds) {
    if (empty($taxIds)) return [];
    if (is_array($taxIds)) return array_map('intval', $taxIds);
    $decoded = json_decode($taxIds, true);
    if (is_array($decoded)) return array_map('intval', $decoded);
    if (strpos($taxIds, ',') !== false) return array_map('intval', array_filter(explode(',', $taxIds)));
    return $taxIds ? [intval($taxIds)] : [];
}

$taxesMap = \App\Models\Tax::where('active', 1)->pluck('name', 'id')->toArray();
$taxRatesMap = \App\Models\Tax::where('active', 1)->pluck('rate', 'id')->toArray();

$taxBreakdown = [];
foreach ($estimation->items as $item) {
    if (($item->item_type ?? 'product') !== 'product') continue;
    $itemTaxIds = parseItemTaxIds($item->tax_ids);
    foreach ($itemTaxIds as $taxId) {
        $taxName = $taxesMap[$taxId] ?? 'Tax';
        $taxRate = $taxRatesMap[$taxId] ?? 0;
        $taxAmount = ($item->amount * $taxRate) / 100;
        $key = $taxName . ' (' . $taxRate . '%)';
        if (!isset($taxBreakdown[$key])) $taxBreakdown[$key] = ['name' => $taxName, 'rate' => $taxRate, 'amount' => 0];
        $taxBreakdown[$key]['amount'] += $taxAmount;
    }
}

$calculatedTaxTotal = array_sum(array_column($taxBreakdown, 'amount'));
$grandTotal = $estimation->subtotal + $calculatedTaxTotal - ($estimation->discount_amount ?? 0);
@endphp

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">📋 Estimation {{ $estimation->estimation_number }}</h1>
        <div class="header-actions">
            <a href="{{ route('admin.sales.estimations.index') }}" class="btn btn-secondary">← Back</a>
            <a href="{{ route('admin.sales.estimations.edit', $estimation->id) }}" class="btn btn-warning">✏️ Edit</a>
<a href="{{ route('admin.sales.estimations.print', $estimation->id) }}" target="_blank" class="btn btn-secondary">
  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
    </svg> Print PDF
</a>            @if(!in_array($estimation->status, ['declined']))
            <form action="{{ route('admin.sales.invoices.fromEstimation', $estimation->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Create invoice from this estimation?')">
                @csrf
                <button type="submit" class="btn btn-success">📄 Convert to Invoice</button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))<div class="alert-success">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error">❌ {{ session('error') }}</div>@endif

    <div class="two-column">
        <div class="left-column">
            <!-- Customer Card -->
            <div class="card">
                <div class="card-header">
                    <h3>👤 Prepared For</h3>
                    <span class="status-badge status-{{ $estimation->status }}">{{ ucfirst($estimation->status) }}</span>
                </div>
                <div class="card-body customer-info">
                    <h4>{{ $estimation->customer->name ?? 'N/A' }}</h4>
                    @if($estimation->email)<p><a href="mailto:{{ $estimation->email }}">{{ $estimation->email }}</a></p>@endif
                    @if($estimation->phone)<p>📞 {{ $estimation->phone }}</p>@endif
                    @if($estimation->address)<p>{{ $estimation->address }}</p>@endif
                    @if($estimation->city || $estimation->state)<p>{{ $estimation->city }}{{ $estimation->city && $estimation->state ? ', ' : '' }}{{ $estimation->state }}{{ $estimation->zip_code ? ', ' . $estimation->zip_code : '' }}</p>@endif
                    @if($estimation->country)<p>{{ $estimation->country }}</p>@endif
                </div>
            </div>

            <!-- Line Items -->
            <div class="card">
                <div class="card-header"><h3>📦 Line Items</h3></div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>DESCRIPTION</th>
                            <th>QTY</th>
                            <th>RATE</th>
                            <th>TAXES</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estimation->items as $item)
                            @if(($item->item_type ?? 'product') === 'section')
                                <tr class="section-row"><td colspan="5"><strong>{{ $item->description }}</strong></td></tr>
                            @elseif(($item->item_type ?? 'product') === 'note')
                                <tr class="note-row"><td colspan="5"><em>{{ $item->long_description ?: $item->description }}</em></td></tr>
                            @else
                                @php $itemTaxIds = parseItemTaxIds($item->tax_ids); @endphp
                                <tr>
                                    <td>
                                        <div class="item-name">{{ $item->description }}</div>
                                        @if($item->long_description)<div class="item-desc">{{ $item->long_description }}</div>@endif
                                    </td>
                                    <td>{{ number_format($item->quantity, 2) }}</td>
                                    <td>{{ number_format($item->rate, 2) }}</td>
                                    <td>
                                        <div class="tax-badges">
                                            @foreach($itemTaxIds as $taxId)
                                                <span class="tax-badge">{{ $taxRatesMap[$taxId] ?? 0 }}% {{ $taxesMap[$taxId] ?? 'Tax' }}</span>
                                            @endforeach
                                            @if(empty($itemTaxIds))<span style="color: #9ca3af; font-size: 12px;">No tax</span>@endif
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr><td colspan="5" style="text-align: center; padding: 30px; color: #6b7280;">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="totals-section">
                    <div class="totals-row">
                        <span class="label">Subtotal</span>
                        <span class="value">{{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->subtotal, 2) }}</span>
                    </div>
                    @if(($estimation->discount_amount ?? 0) > 0)
                    <div class="totals-row">
                        <span class="label">Discount @if($estimation->discount_percent > 0)({{ $estimation->discount_percent }}%)@endif</span>
                        <span class="value" style="color: #dc2626;">- {{ $estimation->currency ?? 'INR' }} {{ number_format($estimation->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if(count($taxBreakdown) > 0)
                    <div class="tax-breakdown-section">
                        <div class="tax-breakdown-title">Tax Breakdown</div>
                        @foreach($taxBreakdown as $key => $tax)
                        <div class="tax-breakdown-item">
                            <span class="name">{{ $key }}</span>
                            <span class="amount">{{ $estimation->currency ?? 'INR' }} {{ number_format($tax['amount'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="totals-row">
                        <span class="label">Total Tax</span>
                        <span class="value" style="color: #10b981;">{{ $estimation->currency ?? 'INR' }} {{ number_format($calculatedTaxTotal, 2) }}</span>
                    </div>
                    <div class="totals-row grand">
                        <span class="label">Total</span>
                        <span class="value">{{ $estimation->currency ?? 'INR' }} {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($estimation->content || $estimation->terms_conditions)
            <div class="card">
                <div class="card-header"><h3>📝 Notes & Terms</h3></div>
                <div class="card-body">
                    @if($estimation->content)<div style="margin-bottom: 16px;"><strong>Customer Notes:</strong><p style="margin-top: 4px; color: var(--gray-600);">{{ $estimation->content }}</p></div>@endif
                    @if($estimation->terms_conditions)<div><strong>Terms & Conditions:</strong><p style="margin-top: 4px; color: var(--gray-600);">{{ $estimation->terms_conditions }}</p></div>@endif
                </div>
            </div>
            @endif
        </div>

        <div class="right-column">
            <!-- Amount Box -->
            <div class="amount-box {{ $estimation->status === 'accepted' ? 'accepted' : ($estimation->status === 'declined' ? 'declined' : '') }}">
                <div class="label">Estimated Total</div>
                <div class="amount">{{ $estimation->currency ?? 'INR' }} {{ number_format($grandTotal, 2) }}</div>
                @if($estimation->valid_until)<div class="due-date">VALID UNTIL: {{ $estimation->valid_until->format('d M Y') }}</div>@endif
            </div>

            <!-- Details Card -->
            <div class="card">
                <div class="card-header"><h3>ℹ️ Estimation Details</h3></div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row"><span class="info-label">Estimation Number</span><span class="info-value">{{ $estimation->estimation_number }}</span></div>
                        <div class="info-row"><span class="info-label">Subject</span><span class="info-value">{{ $estimation->subject ?? '-' }}</span></div>
                        <div class="info-row"><span class="info-label">Date</span><span class="info-value">{{ $estimation->date ? $estimation->date->format('d M Y') : '-' }}</span></div>
                        <div class="info-row"><span class="info-label">Valid Until</span><span class="info-value">{{ $estimation->valid_until ? $estimation->valid_until->format('d M Y') : '-' }}</span></div>
                        <div class="info-row"><span class="info-label">Status</span><span class="info-value"><span class="status-badge status-{{ $estimation->status }}">{{ ucfirst($estimation->status) }}</span></span></div>
                        @if($estimation->created_by)<div class="info-row"><span class="info-label">Created By</span><span class="info-value">{{ $estimation->created_by }}</span></div>@endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header"><h3>⚡ Actions</h3></div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                    @if($estimation->status === 'draft')<button type="button" class="btn btn-primary" onclick="updateStatus('sent')" style="width: 100%;">📤 Mark as Sent</button>@endif
                    @if(in_array($estimation->status, ['draft', 'sent']))
                        <button type="button" class="btn btn-success" onclick="updateStatus('accepted')" style="width: 100%;">✅ Mark as Accepted</button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus('declined')" style="width: 100%;">❌ Mark as Declined</button>
                    @endif
                    @if($estimation->status === 'declined')<button type="button" class="btn btn-warning" onclick="updateStatus('draft')" style="width: 100%;">↩️ Revert to Draft</button>@endif
                    @if($estimation->status === 'accepted')<p style="text-align: center; color: #10b981; font-weight: 600;">✓ Estimation Accepted</p>@endif
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card">
                <div class="card-header"><h3>🕐 Activity</h3></div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <div class="title">Estimation Created</div>
                                <div class="meta">{{ $estimation->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @if($estimation->proposal_id)
                        <div class="timeline-item">
                            <div class="timeline-dot primary"></div>
                            <div class="timeline-content">
                                <div class="title">Created from Proposal</div>
                                <div class="meta">Proposal #{{ $estimation->proposal->proposal_number ?? $estimation->proposal_id }}</div>
                            </div>
                        </div>
                        @endif
                        @if($estimation->status === 'sent')
                        <div class="timeline-item">
                            <div class="timeline-dot primary"></div>
                            <div class="timeline-content">
                                <div class="title">Estimation Sent</div>
                                <div class="meta">{{ $estimation->updated_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($estimation->status === 'accepted')
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <div class="title">Estimation Accepted</div>
                                <div class="meta">{{ $estimation->updated_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($estimation->status === 'declined')
                        <div class="timeline-item">
                            <div class="timeline-dot danger"></div>
                            <div class="timeline-content">
                                <div class="title">Estimation Declined</div>
                                <div class="meta">{{ $estimation->updated_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (!confirm(`Change status to "${status}"?`)) return;
    fetch('{{ route("admin.sales.estimations.updateStatus", $estimation->id) }}', {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
            'Content-Type': 'application/json', 
            'Accept': 'application/json' 
        },
        body: JSON.stringify({ status: status })
    })
    .then(r => r.json())
    .then(d => { 
        if (d.success) location.reload(); 
        else alert(d.message || 'Error'); 
    })
    .catch(() => alert('Error updating status'));
}
</script>

</body>
</html>