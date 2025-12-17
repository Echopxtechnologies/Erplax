<x-layouts.app>

@push('styles')
<style>
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

    .page-wrapper { padding: 24px; max-width: 1400px; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--gray-900); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-title svg { width: 28px; height: 28px; color: var(--primary); }
    .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: all 0.2s; text-decoration: none; border: none;
    }
    .btn svg { width: 18px; height: 18px; }
    .btn-secondary { background: white; color: var(--gray-700); border: 1px solid var(--gray-300); }
    .btn-secondary:hover { background: var(--gray-50); }
    .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }
    .btn-success { background: linear-gradient(135deg, var(--success), #059669); color: white; }
    .btn-success:hover { transform: translateY(-1px); }
    .btn-danger { background: linear-gradient(135deg, var(--danger), #dc2626); color: white; }
    .btn-danger:hover { transform: translateY(-1px); }
    .btn-warning { background: linear-gradient(135deg, var(--warning), #d97706); color: white; }
    .btn-warning:hover { transform: translateY(-1px); }

    .two-column { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
    @media (max-width: 1024px) { .two-column { grid-template-columns: 1fr; } }

    .card { background: white; border-radius: 12px; border: 1px solid var(--gray-200); margin-bottom: 20px; overflow: hidden; }
    .card-header { padding: 16px 20px; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); display: flex; justify-content: space-between; align-items: center; }
    .card-header h3 { font-size: 15px; font-weight: 700; color: var(--gray-800); margin: 0; display: flex; align-items: center; gap: 8px; }
    .card-header h3 svg { width: 18px; height: 18px; color: var(--primary); }
    .card-body { padding: 20px; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
        border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;
    }
    .status-draft { background: #f3f4f6; color: #374151; }
    .status-sent { background: #dbeafe; color: #1d4ed8; }
    .status-open { background: #e0e7ff; color: #4338ca; }
    .status-revised { background: #fef3c7; color: #92400e; }
    .status-declined { background: #fee2e2; color: #991b1b; }
    .status-accepted { background: #d1fae5; color: #065f46; }

    .amount-box {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px;
    }
    .amount-box.accepted { background: linear-gradient(135deg, #10b981, #059669); }
    .amount-box.declined { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .amount-box .label { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.8); text-transform: uppercase; margin-bottom: 4px; }
    .amount-box .amount { font-size: 32px; font-weight: 800; color: white; }
    .amount-box .valid-date { font-size: 13px; color: rgba(255,255,255,0.8); margin-top: 4px; }

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
        text-align: left; padding: 12px; font-size: 11px; font-weight: 700;
        color: var(--gray-500); text-transform: uppercase; background: var(--gray-50);
        border-bottom: 2px solid var(--gray-200);
    }
    .items-table td { padding: 14px 12px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
    .items-table th:last-child, .items-table td:last-child { text-align: right; }
    .items-table .item-name { font-weight: 600; color: var(--gray-800); }
    .items-table .item-desc { font-size: 13px; color: var(--gray-500); margin-top: 2px; }

    /* Tax Badges in Show Page */
    .tax-badges { display: flex; flex-wrap: wrap; gap: 4px; }
    .tax-badge { 
        display: inline-flex; align-items: center; padding: 2px 6px; 
        background: #fef3f2; border: 1px solid #fecaca; border-radius: 4px; 
        font-size: 10px; font-weight: 500; color: #991b1b; white-space: nowrap; 
    }

    /* Section Row Styling */
    .section-row {
        background: linear-gradient(135deg, #eff6ff, #dbeafe) !important;
    }
    .section-row td {
        padding: 10px 12px !important;
        font-weight: 700 !important;
        color: var(--gray-700) !important;
        font-size: 14px;
        border-bottom: 2px solid #bfdbfe !important;
    }

    /* Note Row Styling */
    .note-row {
        background: linear-gradient(135deg, #fffbeb, #fef3c7) !important;
    }
    .note-row td {
        padding: 10px 12px !important;
        font-style: italic !important;
        color: var(--gray-600) !important;
        font-size: 13px;
        border-bottom: 1px solid #fde68a !important;
    }

    .totals-section { padding: 16px 20px; background: var(--gray-50); border-top: 2px solid var(--gray-200); }
    .totals-row { display: flex; justify-content: space-between; padding: 8px 0; }
    .totals-row.grand { padding-top: 12px; margin-top: 8px; border-top: 2px solid var(--gray-300); }
    .totals-row.grand .label { font-size: 16px; font-weight: 700; }
    .totals-row.grand .value { font-size: 20px; font-weight: 800; color: var(--primary); }
    .totals-row .label { color: var(--gray-600); }
    .totals-row .value { font-weight: 600; color: var(--gray-800); }
    .totals-row .value.success { color: var(--success); }
    .totals-row .value.danger { color: var(--danger); }

    /* Tax Breakdown Styling */
    .tax-breakdown-section { 
        margin: 8px 0; 
        padding: 12px; 
        background: white; 
        border-radius: 8px; 
        border: 1px solid var(--gray-200);
    }
    .tax-breakdown-title { 
        font-size: 12px; 
        font-weight: 700; 
        color: var(--gray-600); 
        text-transform: uppercase; 
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .tax-breakdown-title svg { width: 14px; height: 14px; }
    .tax-breakdown-item { 
        display: flex; 
        justify-content: space-between; 
        padding: 6px 0; 
        font-size: 13px;
        border-bottom: 1px dashed var(--gray-200);
    }
    .tax-breakdown-item:last-child { border-bottom: none; }
    .tax-breakdown-item .tax-name { color: var(--gray-600); }
    .tax-breakdown-item .tax-amount { color: var(--success); font-weight: 600; }
    .tax-total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        margin-top: 8px;
        border-top: 1px solid var(--gray-300);
        font-weight: 600;
    }
    .tax-total-row .label { color: var(--gray-700); }
    .tax-total-row .value { color: var(--success); }

    .timeline { position: relative; padding-left: 24px; }
    .timeline::before { content: ''; position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: var(--gray-200); }
    .timeline-item { position: relative; padding-bottom: 16px; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-dot { position: absolute; left: -20px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary); border: 2px solid white; box-shadow: 0 0 0 2px var(--primary); }
    .timeline-dot.success { background: var(--success); box-shadow: 0 0 0 2px var(--success); }
    .timeline-dot.warning { background: var(--warning); box-shadow: 0 0 0 2px var(--warning); }
    .timeline-dot.danger { background: var(--danger); box-shadow: 0 0 0 2px var(--danger); }
    .timeline-content .title { font-weight: 600; color: var(--gray-800); font-size: 14px; }
    .timeline-content .meta { font-size: 12px; color: var(--gray-500); }

    .empty-state { text-align: center; padding: 30px; color: var(--gray-500); }

    .content-section { margin-top: 16px; padding: 16px; background: var(--gray-50); border-radius: 8px; }
    .content-section h4 { font-size: 13px; font-weight: 700; color: var(--gray-600); text-transform: uppercase; margin: 0 0 8px 0; }
    .content-section p { margin: 0; color: var(--gray-700); font-size: 14px; line-height: 1.6; }

    .dropdown { position: relative; display: inline-block; }
    .dropdown-toggle { cursor: pointer; }
    .dropdown-menu {
        position: absolute; top: 100%; right: 0; background: white; border: 1px solid var(--gray-200);
        border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); min-width: 180px;
        display: none; z-index: 100; overflow: hidden;
    }
    .dropdown.active .dropdown-menu { display: block; }
    .dropdown-item {
        display: block; padding: 10px 16px; color: var(--gray-700); text-decoration: none;
        font-size: 14px; transition: background 0.2s;
    }
    .dropdown-item:hover { background: var(--gray-50); }
    .dropdown-item.danger { color: var(--danger); }
    .dropdown-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

    @media print {
        .header-actions, .btn, .dropdown { display: none !important; }
        .two-column { grid-template-columns: 1fr; }
        .card { break-inside: avoid; }
    }
</style>
@endpush

@php
    // Helper function to parse tax_ids
    function parseItemTaxIds($taxIds) {
        if (empty($taxIds)) return [];
        if (is_array($taxIds)) return array_map('intval', $taxIds);
        $decoded = json_decode($taxIds, true);
        if (is_array($decoded)) return array_map('intval', $decoded);
        if (strpos($taxIds, ',') !== false) return array_map('intval', array_filter(explode(',', $taxIds)));
        return $taxIds ? [intval($taxIds)] : [];
    }
    
    // Get taxes map
    $taxesMap = \App\Models\Tax::where('active', 1)->pluck('name', 'id')->toArray();
    $taxRatesMap = \App\Models\Tax::where('active', 1)->pluck('rate', 'id')->toArray();
    
    // Calculate tax breakdown from items
    $itemTaxBreakdown = [];
    foreach ($proposal->items as $item) {
        if (($item->item_type ?? 'product') === 'product') {
            $itemTaxIds = parseItemTaxIds($item->tax_ids);
            foreach ($itemTaxIds as $taxId) {
                $taxName = $taxesMap[$taxId] ?? 'Tax';
                $taxRate = $taxRatesMap[$taxId] ?? 0;
                $taxAmount = ($item->amount * $taxRate) / 100;
                $key = $taxId;
                if (!isset($itemTaxBreakdown[$key])) {
                    $itemTaxBreakdown[$key] = [
                        'name' => $taxName,
                        'rate' => $taxRate,
                        'amount' => 0
                    ];
                }
                $itemTaxBreakdown[$key]['amount'] += $taxAmount;
            }
        }
    }
@endphp

<div class="page-wrapper">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Proposal {{ $proposal->proposal_number }}
        </h1>
        <div class="header-actions">
            <a href="{{ route('admin.sales.proposals.index') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <form action="{{ route('admin.sales.estimations.fromProposal', $proposal->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Create estimation from this proposal?')">
                @csrf
                <button type="submit" class="btn btn-success">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Create Estimation
                </button>
            </form>
            <a href="{{ route('admin.sales.proposals.edit', $proposal->id) }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <div class="dropdown" id="moreDropdown">
                <button type="button" class="btn btn-secondary dropdown-toggle" onclick="toggleDropdown()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                    More
                </button>
                <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item" onclick="duplicateProposal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Duplicate
                    </a>
                    <a href="javascript:window.print()" class="dropdown-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" class="dropdown-item danger" onclick="deleteProposal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="two-column">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Customer Info -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer
                    </h3>
                    <span class="status-badge status-{{ $proposal->status }}">{{ ucfirst($proposal->status) }}</span>
                </div>
                <div class="card-body customer-info">
                    <h4>{{ $proposal->customer->name ?? 'No Customer Selected' }}</h4>
                    @if($proposal->email)<p><a href="mailto:{{ $proposal->email }}">{{ $proposal->email }}</a></p>@endif
                    @if($proposal->phone)<p>{{ $proposal->phone }}</p>@endif
                    @if($proposal->address)<p>{{ $proposal->address }}</p>@endif
                    @if($proposal->city || $proposal->state || $proposal->zip_code)
                        <p>{{ $proposal->city }}{{ $proposal->city && $proposal->state ? ', ' : '' }}{{ $proposal->state }}{{ $proposal->zip_code ? ', ' . $proposal->zip_code : '' }}</p>
                    @endif
                    @if($proposal->country)<p>{{ $proposal->country }}</p>@endif
                </div>
            </div>

            <!-- Proposal Items -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Order Lines
                    </h3>
                </div>
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
                        @forelse($proposal->items as $item)
                            @if(($item->item_type ?? 'product') === 'section')
                                {{-- Section Row --}}
                                <tr class="section-row">
                                    <td colspan="5">
                                        <strong>{{ $item->description }}</strong>
                                    </td>
                                </tr>
                            @elseif(($item->item_type ?? 'product') === 'note')
                                {{-- Note Row --}}
                                <tr class="note-row">
                                    <td colspan="5">
                                        <em>{{ $item->long_description ?: $item->description }}</em>
                                    </td>
                                </tr>
                            @else
                                {{-- Product Row --}}
                                @php
                                    $itemTaxIds = parseItemTaxIds($item->tax_ids);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="item-name">{{ $item->description }}</div>
                                        @if($item->long_description)
                                            <div class="item-desc">{{ $item->long_description }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                    <td>{{ $proposal->currency }} {{ number_format($item->rate, 2) }}</td>
                                    <td>
                                        @if(!empty($itemTaxIds))
                                            <div class="tax-badges">
                                                @foreach($itemTaxIds as $taxId)
                                                    @if(isset($taxesMap[$taxId]))
                                                        <span class="tax-badge">{{ $taxRatesMap[$taxId] ?? 0 }}% {{ $taxesMap[$taxId] }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="color: #9ca3af; font-size: 12px;">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $proposal->currency }} {{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="totals-section">
                    <div class="totals-row">
                        <span class="label">Subtotal</span>
                        <span class="value">{{ $proposal->currency }} {{ number_format($proposal->subtotal, 2) }}</span>
                    </div>
                    
                    @if($proposal->discount_amount > 0)
                    <div class="totals-row">
                        <span class="label">Discount @if($proposal->discount_percent > 0)({{ $proposal->discount_percent }}%)@endif</span>
                        <span class="value danger">- {{ $proposal->currency }} {{ number_format($proposal->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    {{-- Tax Breakdown from Items --}}
                    @if(!empty($itemTaxBreakdown))
                    <div class="tax-breakdown-section">
                        <div class="tax-breakdown-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                            Tax Breakdown
                        </div>
                        @php $calculatedTaxTotal = 0; @endphp
                        @foreach($itemTaxBreakdown as $taxId => $taxInfo)
                            @php $calculatedTaxTotal += $taxInfo['amount']; @endphp
                            <div class="tax-breakdown-item">
                                <span class="tax-name">{{ $taxInfo['name'] }} ({{ number_format($taxInfo['rate'], 2) }}%)</span>
                                <span class="tax-amount">{{ $proposal->currency }} {{ number_format($taxInfo['amount'], 2) }}</span>
                            </div>
                        @endforeach
                        <div class="tax-total-row">
                            <span class="label">Total Tax</span>
                            <span class="value">{{ $proposal->currency }} {{ number_format($calculatedTaxTotal, 2) }}</span>
                        </div>
                    </div>
                    @elseif(($proposal->tax_amount ?? $proposal->total_tax ?? 0) > 0)
                    <div class="totals-row">
                        <span class="label">Tax</span>
                        <span class="value">{{ $proposal->currency }} {{ number_format($proposal->tax_amount ?? $proposal->total_tax ?? 0, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($proposal->adjustment != 0)
                    <div class="totals-row">
                        <span class="label">Adjustment</span>
                        <span class="value">{{ $proposal->currency }} {{ number_format($proposal->adjustment, 2) }}</span>
                    </div>
                    @endif
                    
                    @php
                        $taxTotal = !empty($itemTaxBreakdown) 
                            ? array_sum(array_column($itemTaxBreakdown, 'amount'))
                            : ($proposal->tax_amount ?? $proposal->total_tax ?? 0);
                        $grandTotal = $proposal->subtotal + $taxTotal - ($proposal->discount_amount ?? 0) + ($proposal->adjustment ?? 0);
                    @endphp
                    <div class="totals-row grand">
                        <span class="label">Total</span>
                        <span class="value">{{ $proposal->currency }} {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Content -->
            @if($proposal->content || $proposal->admin_note)
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Notes
                    </h3>
                </div>
                <div class="card-body">
                    @if($proposal->content)
                        <div class="content-section">
                            <h4>Proposal Content</h4>
                            <p>{!! nl2br(e($proposal->content)) !!}</p>
                        </div>
                    @endif
                    @if($proposal->admin_note)
                        <div class="content-section" style="background: #fef3c7; border-left: 3px solid var(--warning);">
                            <h4 style="color: var(--warning);">Admin Note</h4>
                            <p>{{ $proposal->admin_note }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Amount Box -->
            @php
                $grandTotalForBox = $proposal->subtotal + $taxTotal - ($proposal->discount_amount ?? 0) + ($proposal->adjustment ?? 0);
            @endphp
            <div class="amount-box {{ $proposal->status === 'accepted' ? 'accepted' : ($proposal->status === 'declined' ? 'declined' : '') }}">
                <div class="label">Proposal Total</div>
                <div class="amount">{{ $proposal->currency }} {{ number_format($grandTotalForBox, 2) }}</div>
                @if($proposal->open_till)
                    <div class="valid-date">VALID UNTIL: {{ $proposal->open_till->format('d M Y') }}</div>
                @endif
            </div>

            <!-- Proposal Details -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Proposal Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Proposal Number</span>
                            <span class="info-value">{{ $proposal->proposal_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Subject</span>
                            <span class="info-value">{{ $proposal->subject }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date</span>
                            <span class="info-value">{{ $proposal->date?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Valid Until</span>
                            <span class="info-value">{{ $proposal->open_till?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge status-{{ $proposal->status }}">{{ ucfirst($proposal->status) }}</span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Currency</span>
                            <span class="info-value">{{ $proposal->currency }}</span>
                        </div>
                        @if($proposal->assigned_to)
                        <div class="info-row">
                            <span class="info-label">Assigned To</span>
                            <span class="info-value">{{ $proposal->assigned_to }}</span>
                        </div>
                        @endif
                        @if($proposal->created_by)
                        <div class="info-row">
                            <span class="info-label">Created By</span>
                            <span class="info-value">{{ $proposal->created_by }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Actions -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Actions
                    </h3>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                    @if($proposal->status === 'draft')
                        <button type="button" class="btn btn-primary" onclick="updateStatus('sent')" style="width: 100%;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Mark as Sent
                        </button>
                    @endif
                    @if(in_array($proposal->status, ['sent', 'open']))
                        <button type="button" class="btn btn-success" onclick="updateStatus('accepted')" style="width: 100%;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Mark as Accepted
                        </button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus('declined')" style="width: 100%;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Mark as Declined
                        </button>
                    @endif
                    @if($proposal->status === 'declined')
                        <button type="button" class="btn btn-warning" onclick="updateStatus('revised')" style="width: 100%;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Mark as Revised
                        </button>
                    @endif
                </div>
            </div>

            <!-- Activity -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activity
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <div class="title">Proposal Created</div>
                                <div class="meta">{{ $proposal->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @if($proposal->sent_at)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="title">Proposal Sent</div>
                                <div class="meta">{{ $proposal->sent_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($proposal->accepted_at)
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <div class="title">Proposal Accepted</div>
                                <div class="meta">{{ $proposal->accepted_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($proposal->declined_at)
                        <div class="timeline-item">
                            <div class="timeline-dot danger"></div>
                            <div class="timeline-content">
                                <div class="title">Proposal Declined</div>
                                <div class="meta">{{ $proposal->declined_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleDropdown() {
    document.getElementById('moreDropdown').classList.toggle('active');
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('moreDropdown');
    if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});

function updateStatus(status) {
    if (!confirm(`Are you sure you want to change the status to "${status}"?`)) return;

    fetch('{{ route("admin.sales.proposals.updateStatus", $proposal->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Status updated successfully');
            location.reload();
        } else {
            alert(data.message || 'Error updating status');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error updating status');
    });
}

function duplicateProposal() {
    if (!confirm('Are you sure you want to duplicate this proposal?')) return;

    fetch('{{ route("admin.sales.proposals.duplicate", $proposal->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Proposal duplicated successfully');
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            alert(data.message || 'Error duplicating proposal');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error duplicating proposal');
    });
}

function deleteProposal() {
    if (!confirm('Are you sure you want to delete this proposal? This action cannot be undone.')) return;

    fetch('{{ route("admin.sales.proposals.destroy", $proposal->id) }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Proposal deleted successfully');
            window.location.href = '{{ route("admin.sales.proposals.index") }}';
        } else {
            alert(data.message || 'Error deleting proposal');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error deleting proposal');
    });
}
</script>
@endpush

</x-layouts.app>