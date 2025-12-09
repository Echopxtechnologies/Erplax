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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }
    
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
    
    .stock-value {
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .stock-low {
        color: #dc2626;
    }
    
    .stock-ok {
        color: #059669;
    }
    
    .currency {
        font-weight: 600;
        color: var(--primary);
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

    .export-btns {
        display: flex;
        gap: 8px;
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

    .table-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--card-border);
        background: var(--body-bg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-footer-total {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .table-footer-total span {
        color: var(--primary);
        font-size: 18px;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Stock Summary Report
        </h1>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">Filters</div>
        <form method="GET" action="{{ route('admin.inventory.reports.stock-summary') }}">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Warehouse</label>
                    <select name="warehouse_id">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Brand</label>
                    <select name="brand_id">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filter
                    </button>
                    <a href="{{ route('admin.inventory.reports.stock-summary') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-value">{{ $stockReport->count() }}</div>
            <div class="summary-card-label">Total Items</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-value">{{ number_format($stockReport->sum('total_stock'), 0) }}</div>
            <div class="summary-card-label">Total Quantity</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-value">₹{{ number_format($totalValue, 2) }}</div>
            <div class="summary-card-label">Total Stock Value</div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Stock Details
            </div>
            <div class="export-btns">
                <button class="btn-export" onclick="window.print()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>
        </div>
        <div class="report-card-body">
            @if($stockReport->count() > 0)
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Warehouse</th>
                            <th class="text-center">Unit</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Unit Cost</th>
                            <th class="text-right">Stock Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockReport as $item)
                        <tr>
                            <td><strong>{{ $item->product_name }}</strong></td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->category_name ?? '-' }}</td>
                            <td>{{ $item->brand_name ?? '-' }}</td>
                            <td>{{ $item->warehouse_name }}</td>
                            <td class="text-center">{{ $item->unit_name ?? 'PCS' }}</td>
                            <td class="text-right">
                                <span class="stock-value {{ $item->total_stock < 10 ? 'stock-low' : 'stock-ok' }}">
                                    {{ number_format($item->total_stock, 2) }}
                                </span>
                            </td>
                            <td class="text-right">₹{{ number_format($item->purchase_price, 2) }}</td>
                            <td class="text-right">
                                <span class="currency">₹{{ number_format($item->stock_value, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="table-footer-total">
                        Total Stock Value: <span>₹{{ number_format($totalValue, 2) }}</span>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p>No stock data found for the selected filters</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.app>