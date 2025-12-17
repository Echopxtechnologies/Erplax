<x-layouts.app>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Estimation Form Styles */
    .estimation-form-wrapper {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header */
    .estimation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .estimation-title {
        font-size: 22px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .header-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
    }

    .btn-outline {
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f9fafb;
    }

    .btn-primary {
        background: #3b82f6;
        color: #fff;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    /* Card */
    .form-card {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        border-radius: 8px 8px 0 0;
    }

    .card-header h3 {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }

    .card-body {
        padding: 20px;
    }

    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-row:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group.col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 576px) {
        .form-group.col-span-2 {
            grid-column: span 1;
        }
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control:read-only {
        background: #f3f4f6;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 70px;
    }

    /* Select2 Override */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    /* Tabs */
    .tabs-container {
        border-bottom: 1px solid #e5e7eb;
        padding: 0 20px;
        background: #f9fafb;
    }

    .tabs-list {
        display: flex;
        gap: 0;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .tab-btn {
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        background: none;
        border: none;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: #374151;
    }

    .tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    .tab-content {
        display: none;
        padding: 20px;
    }

    .tab-content.active {
        display: block;
    }

    /* Order Lines Table */
    .order-table-wrapper {
        overflow-x: auto;
        margin: 0 -20px;
        padding: 0 20px;
    }

    .order-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .order-table th {
        text-align: left;
        padding: 10px 8px;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .order-table td {
        padding: 10px 8px;
        vertical-align: top;
        border-bottom: 1px solid #f3f4f6;
    }

    .order-table .product-cell {
        min-width: 250px;
    }

    .order-table .qty-cell {
        width: 80px;
    }

    .order-table .price-cell {
        width: 100px;
    }

    .order-table .taxes-cell {
        min-width: 180px;
    }

    .order-table .amount-cell {
        width: 100px;
        text-align: right;
        font-weight: 600;
        color: #1f2937;
        white-space: nowrap;
    }

    .order-table .action-cell {
        width: 40px;
        text-align: center;
    }

    .order-table input,
    .order-table textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        font-size: 13px;
    }

    .order-table input:focus,
    .order-table textarea:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .order-table .qty-input {
        text-align: center;
    }

    .order-table .price-input {
        text-align: right;
    }

    .order-table .desc-input {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
        resize: none;
    }

    /* Section & Note Rows */
    .section-row {
        background: #eff6ff;
    }

    .section-row td {
        padding: 8px 10px;
    }

    .section-row input {
        font-weight: 600;
        background: transparent;
        border: none;
        padding: 4px 0;
    }

    .note-row {
        background: #fffbeb;
    }

    .note-row td {
        padding: 8px 10px;
    }

    .note-row textarea {
        background: transparent;
        border: none;
        font-style: italic;
        padding: 4px 0;
        resize: none;
    }

    /* Row Controls */
    .row-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .drag-handle {
        cursor: grab;
        color: #9ca3af;
        font-size: 14px;
        padding: 4px;
    }

    .drag-handle:hover {
        color: #6b7280;
    }

    .delete-btn {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 4px 8px;
        font-size: 18px;
        line-height: 1;
        border-radius: 4px;
    }

    .delete-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Tax Badges */
    .tax-badges-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        align-items: center;
        min-height: 32px;
    }

    .tax-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: #fef3f2;
        border: 1px solid #fecaca;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        color: #991b1b;
        white-space: nowrap;
    }

    .tax-badge .remove-tax {
        cursor: pointer;
        font-size: 14px;
        line-height: 1;
        opacity: 0.7;
        margin-left: 2px;
    }

    .tax-badge .remove-tax:hover {
        opacity: 1;
    }

    .add-tax-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #f3f4f6;
        border: 1px dashed #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        color: #6b7280;
        font-size: 18px;
        font-weight: 300;
        transition: all 0.2s;
    }

    .add-tax-btn:hover {
        background: #e5e7eb;
        color: #374151;
    }

    /* Tax Dropdown */
    .tax-dropdown {
        position: absolute;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        z-index: 1001;
        min-width: 200px;
        max-height: 250px;
        overflow-y: auto;
        display: none;
    }

    .tax-dropdown.show {
        display: block;
    }

    .tax-dropdown-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f3f4f6;
    }

    .tax-dropdown-item:last-child {
        border-bottom: none;
    }

    .tax-dropdown-item:hover {
        background: #f9fafb;
    }

    .tax-dropdown-item.selected {
        background: #eff6ff;
    }

    .tax-dropdown-item .tax-rate {
        color: #6b7280;
        font-size: 12px;
    }

    .tax-dropdown-item .check-mark {
        color: #3b82f6;
        font-weight: bold;
    }

    /* Add Links */
    .add-links {
        display: flex;
        gap: 20px;
        padding: 15px 0;
        flex-wrap: wrap;
    }

    .add-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #3b82f6;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: color 0.2s;
    }

    .add-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .add-link.danger {
        color: #dc2626;
    }

    .add-link.danger:hover {
        color: #b91c1c;
    }

    /* Totals Section */
    .totals-wrapper {
        display: flex;
        justify-content: flex-end;
        padding: 20px 0;
    }

    .totals-box {
        width: 320px;
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
        border: 1px solid #e5e7eb;
    }

    @media (max-width: 576px) {
        .totals-box {
            width: 100%;
        }
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }

    .totals-row + .totals-row {
        border-top: 1px solid #e5e7eb;
    }

    .totals-label {
        font-size: 13px;
        color: #6b7280;
    }

    .totals-value {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .totals-value.tax {
        color: #10b981;
    }

    .totals-input {
        width: 80px;
        padding: 6px 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        text-align: right;
        font-size: 13px;
    }

    .totals-row.grand-total {
        margin-top: 8px;
        padding-top: 12px;
        border-top: 2px solid #d1d5db;
    }

    .totals-row.grand-total .totals-label {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }

    .totals-row.grand-total .totals-value {
        font-size: 18px;
        font-weight: 700;
        color: #3b82f6;
    }

    /* Tax Breakdown */
    .tax-breakdown {
        margin: 10px 0;
        padding: 10px;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .tax-breakdown-title {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .tax-breakdown-item {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 12px;
    }

    .tax-breakdown-item .name {
        color: #6b7280;
    }

    .tax-breakdown-item .amount {
        color: #10b981;
        font-weight: 500;
    }

    /* Product Dropdown */
    .product-dropdown {
        position: absolute;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        width: 350px;
        max-height: 280px;
        overflow: hidden;
        display: none;
    }

    .product-dropdown.show {
        display: block;
    }

    .product-search {
        padding: 10px;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        background: #fff;
    }

    .product-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
    }

    .product-item {
        padding: 10px 14px;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
    }

    .product-item:hover {
        background: #f9fafb;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-name {
        font-weight: 500;
        color: #1f2937;
        font-size: 13px;
    }

    .product-price {
        font-size: 12px;
        color: #10b981;
        margin-top: 2px;
    }

    /* Validation Errors */
    .validation-errors {
        background: #fef2f2;
        border: 1px solid #fecaca;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .validation-errors strong {
        color: #dc2626;
        display: block;
        margin-bottom: 8px;
    }

    .validation-errors ul {
        margin: 0;
        padding-left: 20px;
        color: #dc2626;
    }

    .validation-errors li {
        margin: 4px 0;
    }
</style>
@endpush

<form action="{{ isset($estimation) ? route('admin.sales.estimations.update', $estimation->id) : route('admin.sales.estimations.store') }}" method="POST" id="estimationForm">
@csrf
@if(isset($estimation)) @method('PUT') @endif

@if($errors->any())
<div class="validation-errors">
    <strong>Please fix the following errors:</strong>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="estimation-form-wrapper">
    <!-- Header -->
    <div class="estimation-header">
        <h1 class="estimation-title">{{ isset($estimation) ? 'Edit Estimation' : 'New Estimation' }}</h1>
        <div class="header-buttons">
            <a href="{{ route('admin.sales.estimations.index') }}" class="btn btn-outline">Discard</a>
            <button type="submit" class="btn btn-primary">Save Estimation</button>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="form-card">
        <div class="card-header">
            <h3>Basic Information</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Customer <span class="required">*</span></label>
                    <select name="customer_id" id="customer_id" class="form-control" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (isset($estimation) && $estimation->customer_id == $customer->id) ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Subject <span class="required">*</span></label>
                    <input type="text" name="subject" class="form-control" value="{{ $estimation->subject ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimation Number</label>
                    <input type="text" name="estimation_number" class="form-control" value="{{ $estimation->estimation_number ?? $nextNumber ?? 'Auto-generated' }}" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Date <span class="required">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ isset($estimation) ? $estimation->date->format('Y-m-d') : date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Valid Until</label>
                    <input type="date" name="valid_until" class="form-control" value="{{ isset($estimation) && $estimation->valid_until ? $estimation->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+30 days')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-control">
                        <option value="INR" {{ (isset($estimation) && $estimation->currency == 'INR') ? 'selected' : '' }}>INR</option>
                        <option value="USD" {{ (isset($estimation) && $estimation->currency == 'USD') ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ (isset($estimation) && $estimation->currency == 'EUR') ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ (isset($estimation) && $estimation->status == 'draft') ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ (isset($estimation) && $estimation->status == 'sent') ? 'selected' : '' }}>Sent</option>
                        <option value="accepted" {{ (isset($estimation) && $estimation->status == 'accepted') ? 'selected' : '' }}>Accepted</option>
                        <option value="declined" {{ (isset($estimation) && $estimation->status == 'declined') ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Lines & Other Info -->
    <div class="form-card">
        <div class="tabs-container">
            <div class="tabs-list">
                <button type="button" class="tab-btn active" onclick="switchTab('items')">Order Lines</button>
                <button type="button" class="tab-btn" onclick="switchTab('other')">Other Info</button>
            </div>
        </div>

        <!-- Order Lines Tab -->
        <div id="tab-items" class="tab-content active">
            <div class="order-table-wrapper">
                <table class="order-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 30px;"><input type="checkbox" class="row-checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                            <th style="width: 30px;"></th>
                            <th class="product-cell">PRODUCT</th>
                            <th class="qty-cell">QUANTITY</th>
                            <th class="price-cell">UNIT PRICE</th>
                            <th class="taxes-cell">TAXES</th>
                            <th class="amount-cell">AMOUNT</th>
                            <th class="action-cell"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>

            <div class="add-links">
                <span class="add-link" onclick="addProduct()">+ Add Product</span>
                <span class="add-link" onclick="addSection()">≡ Add Section</span>
                <span class="add-link" onclick="addNote()">✎ Add Note</span>
                <span class="add-link danger" onclick="deleteSelected()">🗑 Delete Selected</span>
            </div>

            <div class="totals-wrapper">
                <div class="totals-box">
                    <div class="totals-row">
                        <span class="totals-label">Subtotal</span>
                        <span class="totals-value" id="subtotal">₹ 0.00</span>
                    </div>
                    <div class="totals-row">
                        <span class="totals-label">Discount (%)</span>
                        <input type="number" name="discount_percent" id="discountPercent" class="totals-input" value="{{ $estimation->discount_percent ?? 0 }}" step="0.01" min="0" max="100" onchange="calcTotals()" onkeyup="calcTotals()">
                    </div>
                    <div class="totals-row" id="discountRow" style="display: none;">
                        <span class="totals-label">Discount Amount</span>
                        <span class="totals-value" id="discountAmount" style="color: #dc2626;">- ₹ 0.00</span>
                    </div>
                    <div class="tax-breakdown" id="taxBreakdown" style="display: none;">
                        <div class="tax-breakdown-title">Tax Breakdown</div>
                        <div id="taxBreakdownList"></div>
                    </div>
                    <div class="totals-row">
                        <span class="totals-label">Total Tax</span>
                        <span class="totals-value tax" id="totalTax">₹ 0.00</span>
                    </div>
                    <div class="totals-row grand-total">
                        <span class="totals-label">Total</span>
                        <span class="totals-value" id="grandTotal">₹ 0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Info Tab -->
        <div id="tab-other" class="tab-content">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $estimation->email ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $estimation->phone ?? '' }}">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="2">{{ $estimation->address ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ $estimation->city ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ $estimation->state ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ $estimation->zip_code ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="{{ $estimation->country ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-span-2">
                    <label class="form-label">Customer Notes</label>
                    <textarea name="content" class="form-control" rows="3">{{ $estimation->content ?? '' }}</textarea>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="admin_note" class="form-control" rows="3">{{ $estimation->admin_note ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="grid-column: span 4;">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea name="terms_conditions" class="form-control" rows="4">{{ $estimation->terms_conditions ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Dropdown -->
<div class="product-dropdown" id="productDropdown">
    <div class="product-search">
        <input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()">
    </div>
    <div id="productList"></div>
</div>

<!-- Tax Dropdown -->
<div class="tax-dropdown" id="taxDropdown"></div>

</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let itemIndex = 0;
let currentProductInput = null;
let currentTaxCell = null;
let taxesList = [];

const existingItems = @json(isset($estimation) && $estimation->items ? $estimation->items->toArray() : []);

document.addEventListener('DOMContentLoaded', function() {
    console.log('Estimation form initialized');
    
    if (typeof $.fn.select2 !== 'undefined') {
        $('#customer_id').select2({ placeholder: 'Select Customer', allowClear: true });
    }
    
    loadTaxes().then(() => {
        console.log('Taxes loaded, rendering items...');
        renderExistingItems();
        calcTotals();
    }).catch(err => {
        console.error('Error in initialization:', err);
        renderExistingItems();
        calcTotals();
    });
    
    $('#customer_id').on('change', function() {
        const id = $(this).val();
        if (id) {
            fetch(`{{ url('admin/sales/estimations/customer') }}/${id}`)
                .then(res => {
                    if (!res.ok) throw new Error('Customer fetch failed');
                    return res.json();
                })
                .then(data => {
                    $('#email').val(data.email || '');
                    $('#phone').val(data.phone || '');
                    $('#address').val(data.address || '');
                    $('#city').val(data.city || '');
                    $('#state').val(data.state || '');
                    $('#zip_code').val(data.zip_code || '');
                    $('#country').val(data.country || '');
                })
                .catch(err => console.error('Customer fetch error:', err));
        }
    });

    new Sortable(document.getElementById('itemsBody'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: reindex
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.product-dropdown') && !e.target.closest('[onclick="showProducts(this)"]')) {
            document.getElementById('productDropdown').classList.remove('show');
        }
        if (!e.target.closest('.tax-dropdown') && !e.target.closest('.add-tax-btn')) {
            document.getElementById('taxDropdown').classList.remove('show');
        }
    });
});

function loadTaxes() {
    return fetch('/admin/sales/taxes')
        .then(res => {
            if (!res.ok) {
                console.error('Taxes API error:', res.status, res.statusText);
                return [];
            }
            return res.json();
        })
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
    calcRow(row.querySelector('.qty-input'));
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
    row.dataset.type = 'product';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td class="product-cell">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="product">
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${item.product_id || ''}">
            <input type="text" name="items[${itemIndex}][description]" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Product name" onclick="showProducts(this)">
            <textarea name="items[${itemIndex}][long_description]" class="desc-input" rows="1" placeholder="Description">${item.long_description || ''}</textarea>
        </td>
        <td class="qty-cell">
            <input type="number" name="items[${itemIndex}][quantity]" class="qty-input" value="${qty}" min="0.01" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
        </td>
        <td class="price-cell">
            <input type="number" name="items[${itemIndex}][rate]" class="price-input" value="${rate.toFixed(2)}" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
        </td>
        <td class="taxes-cell">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="">
            <div class="tax-badges-wrapper"></div>
        </td>
        <td class="amount-cell">₹ ${amount.toFixed(2)}</td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    renderTaxBadges(row, item.tax_ids || '');
}

function addSectionWithData(item) {
    const row = `<tr class="section-row" data-type="section">
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="section">
            <input type="text" name="items[${itemIndex}][description]" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Section Title">
        </td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
}

function addNoteWithData(item) {
    const row = `<tr class="note-row" data-type="note">
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="note">
            <textarea name="items[${itemIndex}][long_description]" placeholder="Note text..." rows="2">${item.long_description || ''}</textarea>
        </td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
}

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');
    document.getElementById(`tab-${tab}`).classList.add('active');
}

function addProduct() {
    const row = document.createElement('tr');
    row.dataset.type = 'product';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td class="product-cell">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="product">
            <input type="hidden" name="items[${itemIndex}][product_id]" value="">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Product name" onclick="showProducts(this)">
            <textarea name="items[${itemIndex}][long_description]" class="desc-input" rows="1" placeholder="Description"></textarea>
        </td>
        <td class="qty-cell">
            <input type="number" name="items[${itemIndex}][quantity]" class="qty-input" value="1" min="0.01" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
        </td>
        <td class="price-cell">
            <input type="number" name="items[${itemIndex}][rate]" class="price-input" value="0" step="0.01" onchange="calcRow(this)" onkeyup="calcRow(this)">
        </td>
        <td class="taxes-cell">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="[]">
            <div class="tax-badges-wrapper"><span class="add-tax-btn" onclick="showTaxDropdown(this)">+</span></div>
        </td>
        <td class="amount-cell">₹ 0.00</td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
}

function addSection() {
    const row = `<tr class="section-row" data-type="section">
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="section">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Section Title">
        </td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
}

function addNote() {
    const row = `<tr class="note-row" data-type="note">
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="note">
            <textarea name="items[${itemIndex}][long_description]" placeholder="Note text..." rows="2"></textarea>
        </td>
        <td class="action-cell"><button type="button" class="delete-btn" onclick="delRow(this)">×</button></td>
    </tr>`;
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
    itemIndex++;
}

function delRow(btn) {
    btn.closest('tr').remove();
    reindex();
    calcTotals();
}

function reindex() {
    document.querySelectorAll('#itemsBody tr').forEach((row, i) => {
        row.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace(/items\[\d+\]/, `items[${i}]`);
        });
    });
    itemIndex = document.querySelectorAll('#itemsBody tr').length;
}

function toggleSelectAll(cb) {
    document.querySelectorAll('#itemsBody .row-select').forEach(c => c.checked = cb.checked);
}

function deleteSelected() {
    document.querySelectorAll('#itemsBody .row-select:checked').forEach(c => c.closest('tr').remove());
    document.getElementById('selectAll').checked = false;
    reindex();
    calcTotals();
}

function showProducts(el) {
    currentProductInput = el;
    const dropdown = document.getElementById('productDropdown');
    const rect = el.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + window.scrollY + 5) + 'px';
    dropdown.style.left = Math.min(rect.left, window.innerWidth - 370) + 'px';
    fetch('{{ route("admin.sales.estimations.searchProducts") }}?q=')
        .then(res => res.json())
        .then(renderProducts);
    dropdown.classList.add('show');
    document.getElementById('productSearch').focus();
}

function renderProducts(list) {
    document.getElementById('productList').innerHTML = list.map(p => {
        const name = (p.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        const taxIds = JSON.stringify(p.tax_ids || []).replace(/'/g, "\\'");
        return `<div class="product-item" onclick="pickProduct(${p.id}, '${name}', ${p.price || 0}, '${taxIds}')">
            <div class="product-name">${p.name}</div>
            <div class="product-price">₹ ${parseFloat(p.price || 0).toFixed(2)}</div>
        </div>`;
    }).join('');
}

function filterProducts() {
    const search = document.getElementById('productSearch').value;
    fetch(`{{ route("admin.sales.estimations.searchProducts") }}?q=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(renderProducts);
}

function pickProduct(id, name, price, taxIdsJson) {
    const row = currentProductInput.closest('tr');
    row.querySelector('[name$="[product_id]"]').value = id;
    row.querySelector('[name$="[description]"]').value = name;
    row.querySelector('[name$="[rate]"]').value = parseFloat(price || 0).toFixed(2);
    
    let taxIds = [];
    try { taxIds = JSON.parse(taxIdsJson); } catch(e) {}
    renderTaxBadges(row, taxIds);
    
    document.getElementById('productDropdown').classList.remove('show');
}

function calcRow(el) {
    const row = el.closest('tr');
    if (!row || row.dataset.type !== 'product') return;
    
    const qty = parseFloat(row.querySelector('[name$="[quantity]"]')?.value) || 0;
    const rate = parseFloat(row.querySelector('[name$="[rate]"]')?.value) || 0;
    row.querySelector('.amount-cell').textContent = '₹ ' + (qty * rate).toFixed(2);
    calcTotals();
}

function calcTotals() {
    let subtotal = 0, totalTax = 0;
    const taxBreakdown = {};
    
    document.querySelectorAll('#itemsBody tr[data-type="product"]').forEach(row => {
        const qty = parseFloat(row.querySelector('[name$="[quantity]"]')?.value) || 0;
        const rate = parseFloat(row.querySelector('[name$="[rate]"]')?.value) || 0;
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
    
    const discPct = parseFloat(document.getElementById('discountPercent').value) || 0;
    const discAmt = subtotal * (discPct / 100);
    
    document.getElementById('discountRow').style.display = discPct > 0 ? 'flex' : 'none';
    document.getElementById('discountAmount').textContent = '- ₹ ' + discAmt.toFixed(2);
    
    const taxSection = document.getElementById('taxBreakdown');
    const taxList = document.getElementById('taxBreakdownList');
    taxList.innerHTML = '';
    
    if (Object.keys(taxBreakdown).length > 0) {
        taxSection.style.display = 'block';
        for (const [name, amt] of Object.entries(taxBreakdown)) {
            taxList.innerHTML += `<div class="tax-breakdown-item"><span class="name">${name}</span><span class="amount">₹ ${amt.toFixed(2)}</span></div>`;
        }
    } else {
        taxSection.style.display = 'none';
    }
    
    document.getElementById('subtotal').textContent = '₹ ' + subtotal.toFixed(2);
    document.getElementById('totalTax').textContent = '₹ ' + totalTax.toFixed(2);
    document.getElementById('grandTotal').textContent = '₹ ' + ((subtotal - discAmt) + totalTax).toFixed(2);
}
</script>
@endpush

</x-layouts.app>