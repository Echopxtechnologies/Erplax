<x-layouts.app>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); }
        .page-title { font-size: var(--font-xl); font-weight: 600; margin: 0; }
        .header-actions { display: flex; gap: var(--space-sm); }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-lg); }
        .form-row-3 { grid-template-columns: repeat(3, 1fr); }
        .form-row-4 { grid-template-columns: repeat(4, 1fr); }
        .form-group { margin-bottom: var(--space-md); }
        .form-group label { display: block; font-size: var(--font-sm); font-weight: 500; color: var(--text-primary); margin-bottom: var(--space-xs); }
        .form-group label.required::after { content: ' *'; color: var(--danger); }
        
        /* Tabs */
        .form-tabs { display: flex; border-bottom: 1px solid var(--card-border); margin-top: var(--space-xl); }
        .form-tab { padding: 12px 20px; font-size: var(--font-sm); font-weight: 500; color: var(--text-secondary); cursor: pointer; border: none; background: none; border-bottom: 2px solid transparent; margin-bottom: -1px; }
        .form-tab:hover { color: var(--text-primary); }
        .form-tab.active { color: #0046FF; border-bottom-color: #0046FF; }
        
        /* Odoo Table */
        .odoo-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .odoo-table thead th { padding: 10px 8px; font-weight: 600; color: #666; text-align: left; border-bottom: 1px solid #ddd; }
        .odoo-table thead th:last-child { text-align: right; }
        .odoo-table tbody tr { border-bottom: 1px solid #eee; }
        .odoo-table tbody tr:hover { background: #f9f9f9; }
        .odoo-table tbody tr.section-row { background: #f0f0f0; }
        .odoo-table tbody tr.section-row td { font-weight: 600; font-style: italic; }
        .odoo-table tbody tr.note-row { background: #fffbeb; }
        .odoo-table tbody tr.note-row td { font-style: italic; color: #666; }
        .odoo-table tbody td { padding: 8px; vertical-align: middle; }
        
        .col-drag { width: 24px; }
        .drag-handle { cursor: grab; color: #999; }
        .drag-handle:hover { color: #333; }
        .col-product { min-width: 200px; }
        .col-taxes { min-width: 160px; }
        .col-amount { text-align: right; font-weight: 500; white-space: nowrap; }
        .col-actions { width: 40px; text-align: center; }
        
        /* Quick Add Links */
        .quick-add-row td { padding: 12px 8px !important; }
        .quick-add-links { display: flex; gap: 24px; }
        .quick-add-link { color: #0046FF; font-size: 14px; cursor: pointer; font-weight: 500; text-decoration: none; }
        .quick-add-link:hover { text-decoration: underline; }
        
        /* Inline Inputs */
        .odoo-input { width: 100%; border: none; background: transparent; padding: 4px 0; font-size: 14px; }
        .odoo-input:focus { outline: none; background: #fff; border: 1px solid #017e84; border-radius: 3px; padding: 4px 6px; }
        .odoo-input.text-right { text-align: right; }
        
        .product-desc { font-size: 12px; color: #888; margin-top: 2px; }
        .product-desc-input { width: 100%; border: none; background: transparent; font-size: 12px; color: #888; resize: none; padding: 2px 0; }
        .product-desc-input:focus { outline: none; background: #fff; border: 1px solid #ddd; border-radius: 3px; padding: 4px 6px; }
        
        .section-input { width: 100%; border: none; background: transparent; font-weight: 600; font-style: italic; padding: 4px 0; }
        .section-input:focus { outline: none; background: #fff; border: 1px solid #ddd; border-radius: 3px; padding: 4px 6px; }
        
        .note-input { width: 100%; border: none; background: transparent; font-style: italic; color: #666; resize: none; padding: 4px 0; }
        .note-input:focus { outline: none; background: #fff; border: 1px solid #ddd; border-radius: 3px; padding: 4px 6px; }
        
        /* Delete Button */
        .delete-btn { background: none; border: none; color: #ccc; cursor: pointer; padding: 4px; opacity: 0; transition: opacity 0.2s; font-size: 16px; }
        .odoo-table tbody tr:hover .delete-btn { opacity: 1; }
        .delete-btn:hover { color: #e74c3c; }
        
        /* Tax Badges */
        .tax-badges-wrapper { display: flex; flex-wrap: wrap; gap: 4px; align-items: center; min-height: 28px; }
        .tax-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background: #fef3f2; border: 1px solid #fecaca; border-radius: 4px; font-size: 11px; font-weight: 500; color: #991b1b; white-space: nowrap; }
        .tax-badge .remove-tax { cursor: pointer; font-size: 14px; line-height: 1; opacity: 0.7; margin-left: 2px; }
        .tax-badge .remove-tax:hover { opacity: 1; }
        .add-tax-btn { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 4px; cursor: pointer; color: #6b7280; font-size: 16px; font-weight: 300; transition: all 0.2s; }
        .add-tax-btn:hover { background: #e5e7eb; color: #374151; }

        /* Tax Dropdown */
        .tax-dropdown { position: absolute; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); z-index: 1001; min-width: 200px; max-height: 250px; overflow-y: auto; display: none; }
        .tax-dropdown.show { display: block; }
        .tax-dropdown-item { padding: 10px 14px; cursor: pointer; font-size: 13px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f3f4f6; }
        .tax-dropdown-item:last-child { border-bottom: none; }
        .tax-dropdown-item:hover { background: #f9fafb; }
        .tax-dropdown-item.selected { background: #eff6ff; }
        .tax-dropdown-item .tax-rate { color: #6b7280; font-size: 12px; }
        .tax-dropdown-item .check-mark { color: #3b82f6; font-weight: bold; }

        /* Product Dropdown */
        #productSearchContainer { position: absolute; z-index: 1000; }
        .product-dropdown { min-width: 320px; background: #fff; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .product-dropdown-search { padding: 8px; border-bottom: 1px solid #eee; }
        .product-dropdown-search input { width: 100%; border: none; font-size: 14px; outline: none; }
        .product-dropdown-list { max-height: 250px; overflow-y: auto; }
        .product-option { padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; }
        .product-option:hover { background: #f5f5f5; }
        .product-option:last-child { border-bottom: none; }
        .product-option-name { font-weight: 500; }
        .product-option-sku { font-size: 12px; color: #888; margin-left: 8px; }
        .product-option-more { color: #0046FF; font-weight: 500; }
        
        /* Totals */
        .totals-section { display: flex; justify-content: flex-end; padding: 16px 0; border-top: 1px solid #eee; }
        .totals-table { width: 350px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
        .totals-row.total { font-weight: 700; font-size: 16px; padding-top: 12px; margin-top: 6px; border-top: 2px solid #ddd; }
        .totals-label { color: #666; }
        .totals-value { font-weight: 500; }
        
        /* Tax Breakdown */
        .tax-breakdown-box { margin: 10px 0; padding: 12px; background: #f8fafc; border-radius: 8px; border: 1px solid #e5e7eb; }
        .tax-breakdown-title { font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 8px; }
        .tax-breakdown-item { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
        .tax-breakdown-item .tax-name { color: #6b7280; }
        .tax-breakdown-item .tax-amount { color: #10b981; font-weight: 600; }
        
        /* Catalog Modal */
        .catalog-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1100; }
        .catalog-modal.active { display: flex; }
        .catalog-content { background: #fff; border-radius: 8px; width: 90%; max-width: 800px; max-height: 80vh; overflow: hidden; }
        .catalog-header { padding: 16px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .catalog-header h3 { margin: 0; }
        .catalog-body { padding: 16px; overflow-y: auto; max-height: calc(80vh - 60px); }
        .catalog-search { margin-bottom: 16px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
        .product-card { border: 1px solid #ddd; border-radius: 6px; padding: 12px; cursor: pointer; }
        .product-card:hover { border-color: #0046FF; background: #f0fafa; }
        .product-card .product-name { font-weight: 600; margin-bottom: 4px; }
        .product-card .product-sku { font-size: 12px; color: #888; }
        .product-card .product-price { font-weight: 600; color: #0046FF; margin-top: 8px; }
        
        .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee; }
        
        .select2-container--default .select2-selection--single { height: 38px; border: 1px solid #ddd; border-radius: 6px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }

        @media (max-width: 768px) {
            .form-row, .form-row-3, .form-row-4 { grid-template-columns: 1fr; }
            .totals-table { width: 100%; }
            .quick-add-links { flex-wrap: wrap; gap: 12px; }
        }
    </style>
    @endpush

    <form id="proposalForm" method="POST" action="{{ isset($proposal->id) ? route('admin.sales.proposals.update', $proposal->id) : route('admin.sales.proposals.store') }}">
        @csrf
        @if(isset($proposal->id))
            @method('PUT')
        @endif

        <div class="page-header">
            <h1 class="page-title">{{ isset($proposal->id) ? 'Edit Proposal #' . $proposal->proposal_number : 'New Proposal' }}</h1>
            <div class="header-actions">
                <a href="{{ route('admin.sales.proposals.index') }}" class="btn btn-light">Discard</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Basic Info -->
                <div class="form-row form-row-4">
                    <div class="form-group">
                        <label class="required">Customer</label>
                        <select name="customer_id" id="customerSelect" class="form-control select2">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $proposal->customer_id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Subject</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject', $proposal->subject ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proposal Number</label>
                        <input type="text" class="form-control" value="{{ $proposal->proposal_number ?? $nextNumber ?? 'Auto-generated' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $proposal->status ?? 'draft') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row form-row-4">
                    <div class="form-group">
                        <label class="required">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', isset($proposal->date) ? $proposal->date->format('Y-m-d') : date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Valid Until</label>
                        <input type="date" name="open_till" class="form-control" value="{{ old('open_till', isset($proposal->open_till) ? $proposal->open_till->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}">
                    </div>
                    <div class="form-group">
                        <label>Currency</label>
                        <select name="currency" class="form-control">
                            @foreach($currencies as $code => $label)
                                <option value="{{ $code }}" {{ old('currency', $proposal->currency ?? 'INR') == $code ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Discount</label>
                        <select name="discount_type" id="discountType" class="form-control">
                            <option value="no_discount" {{ old('discount_type', $proposal->discount_type ?? 'no_discount') == 'no_discount' ? 'selected' : '' }}>No Discount</option>
                            <option value="before_tax" {{ old('discount_type', $proposal->discount_type ?? '') == 'before_tax' ? 'selected' : '' }}>Before Tax</option>
                            <option value="after_tax" {{ old('discount_type', $proposal->discount_type ?? '') == 'after_tax' ? 'selected' : '' }}>After Tax</option>
                        </select>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="form-tabs">
                    <button type="button" class="form-tab active" data-tab="order-lines">Order Lines</button>
                    <button type="button" class="form-tab" data-tab="other-info">Other Info</button>
                </div>

                <!-- Order Lines Tab -->
                <div class="tab-content" id="tab-order-lines">
                    <div style="overflow-x: auto; margin-top: 16px;">
                        <table class="odoo-table" id="orderLinesTable">
                            <thead>
                                <tr>
                                    <th class="col-drag"></th>
                                    <th class="col-product">Product</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 100px;">Unit Price</th>
                                    <th class="col-taxes">Taxes</th>
                                    <th style="width: 100px;" class="col-amount">Amount</th>
                                    <th class="col-actions"></th>
                                </tr>
                            </thead>
                            <tbody id="orderLinesBody"></tbody>
                        </table>
                    </div>

                    <div class="quick-add-links" style="padding: 12px 0;">
                        <a class="quick-add-link" onclick="addProduct()">+ Add Product</a>
                        <a class="quick-add-link" onclick="addSection()">≡ Add Section</a>
                        <a class="quick-add-link" onclick="addNote()">✎ Add Note</a>
                        <a class="quick-add-link" onclick="openCatalog()">📦 Catalog</a>
                    </div>

                    <!-- Totals -->
                    <div class="totals-section">
                        <div class="totals-table">
                            <div class="totals-row">
                                <span class="totals-label">Subtotal:</span>
                                <span class="totals-value" id="subtotal">₹ 0.00</span>
                            </div>
                            <div class="totals-row" id="discountRow" style="display:none;">
                                <span class="totals-label">Discount (<input type="number" name="discount_percent" id="discountPercent" style="width:50px;border:1px solid #ddd;text-align:center;border-radius:4px;padding:2px;" value="{{ $proposal->discount_percent ?? 0 }}" onchange="calcTotals()" min="0" max="100">%):</span>
                                <span class="totals-value" id="discountAmt" style="color:#ef4444;">-₹ 0.00</span>
                            </div>
                            
                            <!-- Tax Breakdown -->
                            <div class="tax-breakdown-box" id="taxBreakdown" style="display:none;">
                                <div class="tax-breakdown-title">Tax Breakdown</div>
                                <div id="taxBreakdownList"></div>
                            </div>

                            <div class="totals-row">
                                <span class="totals-label">Total Tax:</span>
                                <span class="totals-value" id="totalTax" style="color:#10b981;">₹ 0.00</span>
                            </div>
                            <div class="totals-row total">
                                <span class="totals-label">Total:</span>
                                <span class="totals-value" id="grandTotal">₹ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Info Tab -->
                <div class="tab-content" id="tab-other-info" style="display:none; padding-top:16px;">
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Address</label>
                            <textarea name="address" id="address" class="form-control" rows="2">{{ $proposal->address ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-row form-row-4">
                        <div class="form-group"><label>City</label><input type="text" name="city" id="city" class="form-control" value="{{ $proposal->city ?? '' }}"></div>
                        <div class="form-group"><label>State</label><input type="text" name="state" id="state" class="form-control" value="{{ $proposal->state ?? '' }}"></div>
                        <div class="form-group"><label>Country</label><input type="text" name="country" id="country" class="form-control" value="{{ $proposal->country ?? '' }}"></div>
                        <div class="form-group"><label>Zip</label><input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ $proposal->zip_code ?? '' }}"></div>
                    </div>
                    <div class="form-row form-row-3">
                        <div class="form-group"><label>Email</label><input type="email" name="email" id="email" class="form-control" value="{{ $proposal->email ?? '' }}"></div>
                        <div class="form-group"><label>Phone</label><input type="text" name="phone" id="phone" class="form-control" value="{{ $proposal->phone ?? '' }}"></div>
                        <div class="form-group"><label>Assigned To</label>
                            <select name="assigned_to" class="form-control select2">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}" {{ old('assigned_to', $proposal->assigned_to ?? '') == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Notes</label>
                            <textarea name="content" class="form-control" rows="3">{{ $proposal->content ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Internal Notes</label>
                            <textarea name="admin_note" class="form-control" rows="2">{{ $proposal->admin_note ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.sales.proposals.index') }}" class="btn btn-light">Discard</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Product Search Dropdown -->
    <div id="productSearchContainer" style="display:none;">
        <div class="product-dropdown">
            <div class="product-dropdown-search">
                <input type="text" id="productSearchInput" placeholder="Search a product..." onkeyup="filterProducts()">
            </div>
            <div class="product-dropdown-list" id="productList"></div>
        </div>
    </div>

    <!-- Tax Dropdown -->
    <div class="tax-dropdown" id="taxDropdown"></div>

    <!-- Catalog Modal -->
    <div class="catalog-modal" id="catalogModal">
        <div class="catalog-content">
            <div class="catalog-header">
                <h3>Product Catalog</h3>
                <button type="button" class="btn btn-light" onclick="closeCatalog()">×</button>
            </div>
            <div class="catalog-body">
                <div class="catalog-search">
                    <input type="text" class="form-control" placeholder="Search..." id="catalogSearch" onkeyup="filterCatalog()">
                </div>
                <div class="product-grid" id="productGrid">
                    @foreach($products as $p)
                        <div class="product-card" data-name="{{ strtolower($p->name) }}" onclick="pickProductFromCatalog({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->short_description ?? '') }}', {{ $p->sale_price ?? 0 }}, '{{ $p->unit ? $p->unit->short_name : 'PCS' }}')">
                            <div class="product-name">{{ $p->name }}</div>
                            <div class="product-sku">{{ $p->sku ?? '' }}</div>
                            <div class="product-price">₹ {{ number_format($p->sale_price ?? 0, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let itemIndex = 0;
        let currentProductInput = null;
        let currentTaxCell = null;
        const cur = '₹';
        let taxesList = [];
        
        const existingItems = @json(isset($proposal->id) && $proposal->items ? $proposal->items->toArray() : []);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Proposal form initialized');
            
            $('.select2').select2({ width: '100%' });
            
            loadTaxes().then(() => {
                renderExistingItems();
                calcTotals();
            });
            
            // Tabs
            $('.form-tab').click(function(){
                $('.form-tab').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').hide();
                $('#tab-'+$(this).data('tab')).show();
            });
            
            // Customer autofill
            $('#customerSelect').change(function(){
                const id = $(this).val();
                if(id){
                    fetch(`/admin/sales/proposals/customer/${id}`)
                        .then(r=>r.json())
                        .then(d=>{
                            $('#email').val(d.email||'');
                            $('#phone').val(d.phone||'');
                            $('#address').val(d.address||'');
                            $('#city').val(d.city||'');
                            $('#state').val(d.state||'');
                            $('#country').val(d.country||'');
                            $('#zip_code').val(d.zip_code||'');
                        });
                }
            });
            
            // Discount toggle
            $('#discountType').change(function(){
                $('#discountRow').toggle($(this).val()!=='no_discount');
                if($(this).val()==='no_discount') $('#discountPercent').val(0);
                calcTotals();
            });
            if($('#discountType').val()!=='no_discount') $('#discountRow').show();
            
            // Sortable
            new Sortable(document.getElementById('orderLinesBody'), {
                handle: '.drag-handle',
                animation: 150,
                onEnd: reindex
            });
            
            // Outside click handlers
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#productSearchContainer') && !e.target.closest('[onclick*="showProducts"]')) {
                    hideProducts();
                }
                if (!e.target.closest('.tax-dropdown') && !e.target.closest('.add-tax-btn')) {
                    document.getElementById('taxDropdown').classList.remove('show');
                }
            });
        });

        function loadTaxes() {
            return fetch('/admin/sales/proposals/taxes')
                .then(res => res.ok ? res.json() : [])
                .then(data => { 
                    taxesList = data || []; 
                    console.log('Taxes loaded:', taxesList);
                })
                .catch(err => { 
                    console.error('Failed to load taxes:', err);
                    taxesList = []; 
                });
        }

        function parseTaxIds(taxIds) {
            if (!taxIds) return [];
            if (Array.isArray(taxIds)) return taxIds.map(String);
            if (typeof taxIds === 'string') {
                try {
                    const p = JSON.parse(taxIds);
                    return Array.isArray(p) ? p.map(String) : [String(p)];
                } catch(e) {
                    return taxIds.split(',').map(id => id.trim()).filter(Boolean);
                }
            }
            return [String(taxIds)];
        }

        function getTaxById(id) {
            return taxesList.find(t => String(t.id) === String(id));
        }

        function renderTaxBadges(row, taxIds) {
            const container = row.querySelector('.tax-badges-wrapper');
            const hidden = row.querySelector('.tax-ids-hidden');
            const ids = parseTaxIds(taxIds);
            
            let html = '';
            ids.forEach(id => {
                const tax = getTaxById(id);
                if (tax) {
                    html += `<span class="tax-badge" data-id="${tax.id}">${tax.rate}% ${tax.name}<span class="remove-tax" onclick="removeTax(this, ${tax.id})">×</span></span>`;
                }
            });
            html += `<span class="add-tax-btn" onclick="showTaxDropdown(this)">+</span>`;
            
            container.innerHTML = html;
            hidden.value = JSON.stringify(ids);
            calcTotals();
        }

        function showTaxDropdown(btn) {
            currentTaxCell = btn.closest('td');
            const row = btn.closest('tr');
            const hidden = row.querySelector('.tax-ids-hidden');
            const selectedIds = parseTaxIds(hidden.value);
            
            const dropdown = document.getElementById('taxDropdown');
            const rect = btn.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + window.scrollY + 5) + 'px';
            dropdown.style.left = Math.min(rect.left, window.innerWidth - 220) + 'px';
            
            let html = '';
            taxesList.forEach(tax => {
                const isSelected = selectedIds.includes(String(tax.id));
                html += `<div class="tax-dropdown-item ${isSelected ? 'selected' : ''}" onclick="toggleTax(${tax.id})" data-id="${tax.id}">
                    <span>${tax.name}</span>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <span class="tax-rate">${tax.rate}%</span>
                        ${isSelected ? '<span class="check-mark">✓</span>' : ''}
                    </span>
                </div>`;
            });
            
            dropdown.innerHTML = html || '<div style="padding: 12px; color: #666;">No taxes available</div>';
            dropdown.classList.add('show');
        }

        function toggleTax(taxId) {
            const row = currentTaxCell.closest('tr');
            const hidden = row.querySelector('.tax-ids-hidden');
            let selectedIds = parseTaxIds(hidden.value);
            
            const idx = selectedIds.indexOf(String(taxId));
            if (idx > -1) {
                selectedIds.splice(idx, 1);
            } else {
                selectedIds.push(String(taxId));
            }
            
            renderTaxBadges(row, selectedIds);
            
            // Update dropdown checkmarks
            const dropdown = document.getElementById('taxDropdown');
            dropdown.querySelectorAll('.tax-dropdown-item').forEach(item => {
                const isSelected = selectedIds.includes(item.dataset.id);
                item.classList.toggle('selected', isSelected);
                const checkMark = item.querySelector('.check-mark');
                if (isSelected && !checkMark) {
                    item.querySelector('span:last-child').innerHTML += '<span class="check-mark">✓</span>';
                } else if (!isSelected && checkMark) {
                    checkMark.remove();
                }
            });
        }

        function removeTax(btn, taxId) {
            const row = btn.closest('tr');
            const hidden = row.querySelector('.tax-ids-hidden');
            let selectedIds = parseTaxIds(hidden.value);
            const idx = selectedIds.indexOf(String(taxId));
            if (idx > -1) selectedIds.splice(idx, 1);
            renderTaxBadges(row, selectedIds);
        }

        function renderExistingItems() {
            if (!existingItems.length) return;
            existingItems.forEach(item => {
                if (item.item_type === 'section') addSectionWithData(item);
                else if (item.item_type === 'note') addNoteWithData(item);
                else addProductWithData(item);
            });
        }

        function addProductWithData(item) {
            const qty = parseFloat(item.quantity || 1);
            const rate = parseFloat(item.rate || 0);
            const amount = qty * rate;
            
            const row = document.createElement('tr');
            row.className = 'item-row product-row';
            row.dataset.type = 'product';
            row.innerHTML = `
                <td class="col-drag">
                    <span class="drag-handle">⋮⋮</span>
                    <input type="hidden" name="items[${itemIndex}][item_type]" value="product">
                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${item.product_id || ''}">
                </td>
                <td class="col-product">
                    <input type="text" name="items[${itemIndex}][description]" class="odoo-input" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Product name" onclick="showProducts(this)">
                    <textarea name="items[${itemIndex}][long_description]" class="product-desc-input" rows="1" placeholder="Description">${item.long_description || ''}</textarea>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="odoo-input text-right qty-input" value="${qty.toFixed(2)}" min="0.01" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
                    <input type="hidden" name="items[${itemIndex}][unit]" value="${item.unit || 'PCS'}">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][rate]" class="odoo-input text-right rate-input" value="${rate.toFixed(2)}" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
                </td>
                <td class="col-taxes">
                    <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="">
                    <div class="tax-badges-wrapper"></div>
                </td>
                <td class="col-amount">
                    <span class="amt-display">${cur} ${amount.toFixed(2)}</span>
                    <input type="hidden" name="items[${itemIndex}][amount]" class="amt-hidden" value="${amount.toFixed(2)}">
                </td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            `;
            document.getElementById('orderLinesBody').appendChild(row);
            itemIndex++;
            renderTaxBadges(row, item.tax_ids || '');
        }

        function addSectionWithData(item) {
            const row = `<tr class="item-row section-row" data-type="section">
                <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="section"></td>
                <td colspan="5"><input type="text" name="items[${itemIndex}][description]" class="section-input" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Section Title"></td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            </tr>`;
            document.getElementById('orderLinesBody').insertAdjacentHTML('beforeend', row);
            itemIndex++;
        }

        function addNoteWithData(item) {
            const row = `<tr class="item-row note-row" data-type="note">
                <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="note"></td>
                <td colspan="5"><textarea name="items[${itemIndex}][description]" class="note-input" rows="1" placeholder="Note text...">${item.description || item.long_description || ''}</textarea></td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            </tr>`;
            document.getElementById('orderLinesBody').insertAdjacentHTML('beforeend', row);
            itemIndex++;
        }

        function addProduct(id='', name='', desc='', rate=0, unit='PCS') {
            const row = document.createElement('tr');
            row.className = 'item-row product-row';
            row.dataset.type = 'product';
            row.innerHTML = `
                <td class="col-drag">
                    <span class="drag-handle">⋮⋮</span>
                    <input type="hidden" name="items[${itemIndex}][item_type]" value="product">
                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${id}">
                </td>
                <td class="col-product">
                    <input type="text" name="items[${itemIndex}][description]" class="odoo-input" value="${name}" placeholder="Product name" onclick="showProducts(this)">
                    <textarea name="items[${itemIndex}][long_description]" class="product-desc-input" rows="1" placeholder="Description">${desc}</textarea>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="odoo-input text-right qty-input" value="1.00" min="0.01" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
                    <input type="hidden" name="items[${itemIndex}][unit]" value="${unit}">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][rate]" class="odoo-input text-right rate-input" value="${parseFloat(rate).toFixed(2)}" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
                </td>
                <td class="col-taxes">
                    <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="[]">
                    <div class="tax-badges-wrapper"><span class="add-tax-btn" onclick="showTaxDropdown(this)">+</span></div>
                </td>
                <td class="col-amount">
                    <span class="amt-display">${cur} ${parseFloat(rate).toFixed(2)}</span>
                    <input type="hidden" name="items[${itemIndex}][amount]" class="amt-hidden" value="${parseFloat(rate).toFixed(2)}">
                </td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            `;
            document.getElementById('orderLinesBody').appendChild(row);
            itemIndex++;
            calcTotals();
        }

        function addSection() {
            const row = `<tr class="item-row section-row" data-type="section">
                <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="section"></td>
                <td colspan="5"><input type="text" name="items[${itemIndex}][description]" class="section-input" placeholder="Section name"></td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            </tr>`;
            document.getElementById('orderLinesBody').insertAdjacentHTML('beforeend', row);
            itemIndex++;
        }

        function addNote() {
            const row = `<tr class="item-row note-row" data-type="note">
                <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="note"></td>
                <td colspan="5"><textarea name="items[${itemIndex}][description]" class="note-input" rows="1" placeholder="Add a note..."></textarea></td>
                <td class="col-actions"><button type="button" class="delete-btn" onclick="delRow(this)">🗑</button></td>
            </tr>`;
            document.getElementById('orderLinesBody').insertAdjacentHTML('beforeend', row);
            itemIndex++;
        }

        function delRow(btn) { 
            btn.closest('tr').remove(); 
            reindex(); 
            calcTotals(); 
        }

        function calcRow(el) {
            const tr = el.closest('tr');
            if (!tr || !tr.classList.contains('product-row')) return;
            
            const q = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const r = parseFloat(tr.querySelector('.rate-input').value) || 0;
            const amt = q * r;
            
            tr.querySelector('.amt-display').textContent = cur + ' ' + amt.toFixed(2);
            tr.querySelector('.amt-hidden').value = amt.toFixed(2);
            
            calcTotals();
        }

        function calcTotals() {
            let subtotal = 0, totalTax = 0;
            const taxBreakdown = {};
            
            document.querySelectorAll('#orderLinesBody .product-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
                const rate = parseFloat(row.querySelector('.rate-input')?.value) || 0;
                const amount = qty * rate;
                subtotal += amount;
                
                parseTaxIds(row.querySelector('.tax-ids-hidden')?.value).forEach(taxId => {
                    const tax = getTaxById(taxId);
                    if (tax) {
                        const taxAmt = (amount * tax.rate) / 100;
                        totalTax += taxAmt;
                        const key = `${tax.name} (${tax.rate}%)`;
                        taxBreakdown[key] = (taxBreakdown[key] || 0) + taxAmt;
                    }
                });
            });
            
            const dt = $('#discountType').val();
            const dp = parseFloat($('#discountPercent').val()) || 0;
            let discountAmount = 0;
            
            if (dt === 'before_tax') {
                discountAmount = (subtotal * dp) / 100;
            } else if (dt === 'after_tax') {
                discountAmount = ((subtotal + totalTax) * dp) / 100;
            }
            
            $('#discountRow').toggle(dp > 0 && dt !== 'no_discount');
            $('#discountAmt').text('-' + cur + ' ' + discountAmount.toFixed(2));
            
            // Tax breakdown
            const taxSection = document.getElementById('taxBreakdown');
            const taxList = document.getElementById('taxBreakdownList');
            taxList.innerHTML = '';
            
            if (Object.keys(taxBreakdown).length > 0) {
                taxSection.style.display = 'block';
                for (const [name, amt] of Object.entries(taxBreakdown)) {
                    taxList.innerHTML += `<div class="tax-breakdown-item"><span class="tax-name">${name}</span><span class="tax-amount">${cur} ${amt.toFixed(2)}</span></div>`;
                }
            } else {
                taxSection.style.display = 'none';
            }
            
            const grandTotal = subtotal + totalTax - discountAmount;
            
            $('#subtotal').text(cur + ' ' + subtotal.toFixed(2));
            $('#totalTax').text(cur + ' ' + totalTax.toFixed(2));
            $('#grandTotal').text(cur + ' ' + grandTotal.toFixed(2));
        }

        function reindex() {
            document.querySelectorAll('#orderLinesBody .item-row').forEach((r, i) => {
                r.querySelectorAll('input,textarea,select').forEach(el => {
                    if (el.name) el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
                });
            });
            itemIndex = document.querySelectorAll('#orderLinesBody .item-row').length;
        }

        function showProducts(el) {
            currentProductInput = el;
            const dropdown = document.getElementById('productSearchContainer');
            const rect = el.getBoundingClientRect();
            dropdown.style.display = 'block';
            dropdown.style.top = (rect.bottom + window.scrollY + 5) + 'px';
            dropdown.style.left = rect.left + 'px';
            document.getElementById('productSearchInput').value = '';
            document.getElementById('productSearchInput').focus();
            
            fetch('{{ route("admin.sales.proposals.products.search") }}?q=')
                .then(res => res.json())
                .then(renderProductList);
        }

        function hideProducts() { 
            document.getElementById('productSearchContainer').style.display = 'none'; 
        }

        function renderProductList(products) {
            let html = '';
            products.forEach(p => {
                const name = (p.name || '').replace(/'/g, "\\'");
                html += `<div class="product-option" onclick="pickProduct(${p.id}, '${name}', '', ${p.price || 0}, 'PCS')">
                    <span class="product-option-name">${p.name}</span>
                    ${p.sku ? '<span class="product-option-sku">[' + p.sku + ']</span>' : ''}
                </div>`;
            });
            html += '<div class="product-option product-option-more" onclick="openCatalog()">Search more...</div>';
            document.getElementById('productList').innerHTML = html;
        }

        function filterProducts() {
            const search = document.getElementById('productSearchInput').value;
            fetch(`{{ route("admin.sales.proposals.products.search") }}?q=${encodeURIComponent(search)}`)
                .then(res => res.json())
                .then(renderProductList);
        }

        function pickProduct(id, name, desc, price, unit) {
            hideProducts();
            if (currentProductInput) {
                const row = currentProductInput.closest('tr');
                row.querySelector('[name$="[product_id]"]').value = id;
                row.querySelector('[name$="[description]"]').value = name;
                row.querySelector('[name$="[rate]"]').value = parseFloat(price).toFixed(2);
                calcRow(row.querySelector('.qty-input'));
            } else {
                addProduct(id, name, desc, price, unit);
            }
            currentProductInput = null;
        }

        function pickProductFromCatalog(id, name, desc, price, unit) {
            closeCatalog();
            addProduct(id, name, desc, price, unit);
        }

        function openCatalog() { 
            hideProducts(); 
            document.getElementById('catalogModal').classList.add('active'); 
            document.getElementById('catalogSearch').focus(); 
        }

        function closeCatalog() { 
            document.getElementById('catalogModal').classList.remove('active'); 
        }

        function filterCatalog() {
            const s = document.getElementById('catalogSearch').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(c => {
                c.style.display = c.dataset.name.includes(s) ? '' : 'none';
            });
        }

        document.addEventListener('keydown', e => { 
            if (e.key === 'Escape') { closeCatalog(); hideProducts(); }
        });

        document.getElementById('catalogModal').addEventListener('click', e => { 
            if (e.target === document.getElementById('catalogModal')) closeCatalog(); 
        });
    </script>
    @endpush
</x-layouts.app>