<style>
    .product-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .product-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .product-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: #fff; }
    .btn-add svg { width: 18px; height: 18px; }
    
    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .stat-card.active { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.total { background: var(--primary-light); color: var(--primary); }
    .stat-icon.active-icon { background: var(--success-light); color: var(--success); }
    .stat-icon.inactive { background: var(--danger-light); color: var(--danger); }
    .stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); }
    .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    
    /* Table Card */
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .table-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .table-card-title svg { width: 20px; height: 20px; color: var(--text-muted); }
    
    /* Filters */
    .filter-section { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .filter-select { padding: 8px 12px; border: 1px solid var(--input-border); border-radius: var(--radius-md); font-size: 13px; background: var(--input-bg); color: var(--input-text); cursor: pointer; min-width: 130px; }
    .filter-select:focus { outline: none; border-color: var(--primary); }
</style>

<div style="padding: 20px;">
    <div class="product-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Products
        </h1>
        @can('product.products-list.create')
            <a href="{{ route('admin.product.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Add Product
        </a>
        @endcan
        
    </div>

    <!-- Stats Cards (Clickable Filters) -->
    <div class="stats-grid">
        <div class="stat-card active" data-filter="all" onclick="filterByStatus('')">
            <div class="stat-icon total">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        <div class="stat-card" data-filter="1" onclick="filterByStatus('1')">
            <div class="stat-icon active-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card" data-filter="0" onclick="filterByStatus('0')">
            <div class="stat-icon inactive">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
            <div>
                <div class="stat-value">{{ $stats['inactive'] }}</div>
                <div class="stat-label">Inactive</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Product List
            </div>
            
            <!-- Filters -->
            <div class="filter-section">
                <select class="filter-select" id="statusFilter" data-dt-filter="is_active" data-dt-table="productTable">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <!-- DataTable with all features: search, export (CSV, XLSX, PDF), import, per page, checkbox -->
            <table id="productTable" 
                   class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.product.data') }}"
                   data-delete-route="{{ route('admin.product.bulk-delete') }}">
                <thead>
                    <tr>
                        <th class="dt-sort" data-col="id">ID</th>
                        <th class="dt-sort dt-clickable" data-col="name">Name</th>
                        <th class="dt-sort" data-col="sku">SKU</th>
                        <th class="dt-sort" data-col="purchase_price" data-render="currency">Purchase</th>
                        <th class="dt-sort" data-col="sale_price" data-render="currency">Sale</th>
                        <th class="dt-sort" data-col="mrp" data-render="currency">MRP</th>
                        <th class="dt-sort" data-col="is_active" data-render="status">Status</th>
                        <th data-render="actions">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@include('core::datatable')

<script>
    // Custom renders
    window.dtRenders = window.dtRenders || {};
    
    // Currency render
    window.dtRenders.currency = function(value, row) {
        if (value === null || value === undefined || value === '') return '-';
        return '₹' + parseFloat(value).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };
    
    // Status render
    window.dtRenders.status = function(value, row) {
        if (value === true || value === 1 || value === '1') {
            return '<span class="dt-badge dt-badge-success">Active</span>';
        }
        return '<span class="dt-badge dt-badge-danger">Inactive</span>';
    };
    
    // Filter by status (from stat cards)
    function filterByStatus(status) {
        document.getElementById('statusFilter').value = status;
        
        // Trigger change event
        var event = new Event('change', { bubbles: true });
        document.getElementById('statusFilter').dispatchEvent(event);
        
        // Update active card
        document.querySelectorAll('.stat-card').forEach(function(card) {
            card.classList.remove('active');
            if ((status === '' && card.dataset.filter === 'all') || card.dataset.filter === status) {
                card.classList.add('active');
            }
        });
    }
</script>
