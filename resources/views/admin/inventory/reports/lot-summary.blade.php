<x-layouts.app>
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-header h1 svg {
        width: 28px;
        height: 28px;
        color: var(--primary);
    }

    .filter-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .filter-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .filter-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .filter-group select,
    .filter-group input {
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
    }
    
    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn svg {
        width: 16px;
        height: 16px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: #fff;
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
    }
    
    .btn-secondary {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
    }
    
    .btn-secondary:hover {
        background: var(--card-border);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .summary-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    
    .summary-card-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }
    
    .summary-card-value.blue { color: #2563eb; }
    .summary-card-value.green { color: #059669; }
    .summary-card-value.orange { color: #ea580c; }
    .summary-card-value.red { color: #dc2626; }
    
    .summary-card-label {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 8px;
    }

    .report-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .report-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .report-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .report-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    
    .report-card-body {
        padding: 0;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .report-table th,
    .report-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
    }
    
    .report-table th {
        background: var(--body-bg);
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    
    .report-table tbody tr:hover {
        background: var(--body-bg);
    }
    
    .report-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .report-table .text-right {
        text-align: right;
    }
    
    .report-table .text-center {
        text-align: center;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-info { background: #dbeafe; color: #1e40af; }

    .expiry-ok {
        color: #059669;
    }
    
    .expiry-soon {
        color: #ea580c;
        font-weight: 600;
    }
    
    .expiry-expired {
        color: #dc2626;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 15px;
    }

    .btn-export {
        padding: 8px 14px;
        font-size: 13px;
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--card-border);
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-export:hover {
        background: var(--card-border);
    }
    
    .btn-export svg {
        width: 16px;
        height: 16px;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Lot Summary Report
        </h1>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">Filters</div>
        <form method="GET" action="{{ route('admin.inventory.reports.lot-summary') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Product</label>
                    <select name="product_id">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="AVAILABLE" {{ request('status') == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                        <option value="RESERVED" {{ request('status') == 'RESERVED' ? 'selected' : '' }}>Reserved</option>
                        <option value="EXPIRED" {{ request('status') == 'EXPIRED' ? 'selected' : '' }}>Expired</option>
                        <option value="CONSUMED" {{ request('status') == 'CONSUMED' ? 'selected' : '' }}>Consumed</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filter
                    </button>
                    <a href="{{ route('admin.inventory.reports.lot-summary') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    @php
        $totalLots = $lots->count();
        $availableLots = $lots->where('status', 'AVAILABLE')->count();
        $expiringSoon = $lots->filter(function($lot) {
            if (!$lot->expiry_date) return false;
            $days = now()->diffInDays($lot->expiry_date, false);
            return $days >= 0 && $days <= 30;
        })->count();
        $expiredLots = $lots->where('status', 'EXPIRED')->count();
    @endphp
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-value blue">{{ $totalLots }}</div>
            <div class="summary-card-label">Total Lots</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-value green">{{ $availableLots }}</div>
            <div class="summary-card-label">Available</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-value orange">{{ $expiringSoon }}</div>
            <div class="summary-card-label">Expiring Soon (30 days)</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-value red">{{ $expiredLots }}</div>
            <div class="summary-card-label">Expired</div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Lot Details
            </div>
            <button class="btn-export" onclick="window.print()">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
        </div>
        <div class="report-card-body">
            @if($lots->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Lot No</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th class="text-right">Initial Qty</th>
                            <th class="text-center">Mfg Date</th>
                            <th class="text-center">Expiry Date</th>
                            <th class="text-center">Days Left</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lots as $lot)
                        @php
                            $daysLeft = null;
                            $expiryClass = 'expiry-ok';
                            if ($lot->expiry_date) {
                                $daysLeft = now()->diffInDays($lot->expiry_date, false);
                                if ($daysLeft < 0) {
                                    $expiryClass = 'expiry-expired';
                                } elseif ($daysLeft <= 30) {
                                    $expiryClass = 'expiry-soon';
                                }
                            }
                            
                            $statusClass = match($lot->status) {
                                'AVAILABLE' => 'badge-success',
                                'RESERVED' => 'badge-info',
                                'EXPIRED' => 'badge-danger',
                                'CONSUMED' => 'badge-warning',
                                default => 'badge-info'
                            };
                        @endphp
                        <tr>
                            <td><strong>{{ $lot->lot_no }}</strong></td>
                            <td>{{ $lot->product->name ?? '-' }}</td>
                            <td>{{ $lot->product->sku ?? '-' }}</td>
                            <td class="text-right">{{ number_format($lot->initial_qty, 2) }}</td>
                            <td class="text-center">{{ $lot->manufacturing_date ?? '-' }}</td>
                            <td class="text-center">
                                @if($lot->expiry_date)
                                    <span class="{{ $expiryClass }}">{{ $lot->expiry_date }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($daysLeft !== null)
                                    <span class="{{ $expiryClass }}">
                                        @if($daysLeft < 0)
                                            Expired
                                        @else
                                            {{ $daysLeft }} days
                                        @endif
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $statusClass }}">{{ $lot->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p>No lots found for the selected filters</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.app>