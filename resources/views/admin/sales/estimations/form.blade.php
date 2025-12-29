<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($estimation) ? 'Edit Estimation' : 'New Estimation' }}</title>
    <style>
        /* Reset & Base */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f1f5f9;
            color: #1f2937;
            line-height: 1.5;
            min-height: 100vh;
        }

        /* Estimation Form Wrapper */
        .estimation-form-wrapper {
            padding: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .estimation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .estimation-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .header-buttons {
            display: flex;
            gap: 12px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-outline {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-outline:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4);
        }

        /* Card */
        .form-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        }

        .card-header h3 {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .card-body {
            padding: 24px;
        }

        /* Form Grid */
        .form-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
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
            gap: 8px;
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
            font-weight: 600;
            color: #374151;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: #fff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-control:read-only {
            background: #f3f4f6;
            color: #6b7280;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
            cursor: pointer;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Tabs */
        .tabs-container {
            border-bottom: 1px solid #e5e7eb;
            padding: 0 24px;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        }

        .tabs-list {
            display: flex;
            gap: 0;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .tab-btn {
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            background: none;
            border: none;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -1px;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: #374151;
            background: rgba(59, 130, 246, 0.05);
        }

        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-content {
            display: none;
            padding: 24px;
        }

        .tab-content.active {
            display: block;
        }

        /* Order Lines Table */
        .order-table-wrapper {
            overflow-x: auto;
            margin: 0 -24px;
            padding: 0 24px;
        }

        .order-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
        }

        .order-table th {
            text-align: left;
            padding: 12px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            background: #f9fafb;
        }

        .order-table td {
            padding: 12px 10px;
            vertical-align: top;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-table tbody tr:hover {
            background: #f9fafb;
        }

        .order-table .product-cell {
            min-width: 250px;
        }

        .order-table .qty-cell {
            width: 90px;
        }

        .order-table .price-cell {
            width: 110px;
        }

        .order-table .taxes-cell {
            min-width: 180px;
        }

        .order-table .amount-cell {
            width: 110px;
            text-align: right;
            font-weight: 700;
            color: #1f2937;
            white-space: nowrap;
        }

        .order-table .action-cell {
            width: 50px;
            text-align: center;
        }

        .order-table input,
        .order-table textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .order-table input:focus,
        .order-table textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .order-table .qty-input {
            text-align: center;
        }

        .order-table .price-input {
            text-align: right;
        }

        .order-table .desc-input {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
            resize: none;
            background: #f9fafb;
        }

        /* Section & Note Rows */
        .section-row {
            background: linear-gradient(to right, #eff6ff, #dbeafe) !important;
        }

        .section-row td {
            padding: 10px 12px;
        }

        .section-row input {
            font-weight: 700;
            background: transparent;
            border: none;
            padding: 6px 0;
            color: #1e40af;
        }

        .section-row input:focus {
            box-shadow: none;
        }

        .note-row {
            background: linear-gradient(to right, #fffbeb, #fef3c7) !important;
        }

        .note-row td {
            padding: 10px 12px;
        }

        .note-row textarea {
            background: transparent;
            border: none;
            font-style: italic;
            padding: 6px 0;
            resize: none;
            color: #92400e;
        }

        .note-row textarea:focus {
            box-shadow: none;
        }

        /* Row Controls */
        .row-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #3b82f6;
        }

        .drag-handle {
            cursor: grab;
            color: #9ca3af;
            font-size: 16px;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .drag-handle:hover {
            color: #6b7280;
            background: #f3f4f6;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 6px 10px;
            font-size: 20px;
            line-height: 1;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Tax Badges */
        .tax-badges-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            min-height: 36px;
        }

        .tax-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            background: linear-gradient(to bottom, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #991b1b;
            white-space: nowrap;
        }

        .tax-badge .remove-tax {
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            opacity: 0.7;
            margin-left: 2px;
            transition: opacity 0.2s ease;
        }

        .tax-badge .remove-tax:hover {
            opacity: 1;
        }

        .add-tax-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            color: #6b7280;
            font-size: 20px;
            font-weight: 400;
            transition: all 0.2s ease;
        }

        .add-tax-btn:hover {
            background: #e5e7eb;
            color: #374151;
            border-color: #9ca3af;
        }

        /* Tax Dropdown */
        .tax-dropdown {
            position: fixed;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            min-width: 220px;
            max-height: 280px;
            overflow-y: auto;
            display: none;
        }

        .tax-dropdown.show {
            display: block;
        }

        .tax-dropdown-item {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.15s ease;
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
            gap: 24px;
            padding: 20px 0;
            flex-wrap: wrap;
            border-top: 1px solid #f3f4f6;
            margin-top: 16px;
        }

        .add-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #3b82f6;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .add-link:hover {
            color: #2563eb;
            background: #eff6ff;
        }

        .add-link.danger {
            color: #dc2626;
        }

        .add-link.danger:hover {
            color: #b91c1c;
            background: #fef2f2;
        }

        /* Totals Section */
        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
            padding: 24px 0;
        }

        .totals-box {
            width: 360px;
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
            border-radius: 12px;
            padding: 20px;
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
            padding: 10px 0;
        }

        .totals-row + .totals-row {
            border-top: 1px solid #e5e7eb;
        }

        .totals-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .totals-value {
            font-size: 15px;
            font-weight: 700;
            color: #1f2937;
        }

        .totals-value.tax {
            color: #059669;
        }

        .totals-input {
            width: 90px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-align: right;
            font-size: 14px;
            font-weight: 600;
        }

        .totals-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .totals-row.grand-total {
            margin-top: 12px;
            padding-top: 16px;
            border-top: 2px solid #d1d5db;
        }

        .totals-row.grand-total .totals-label {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .totals-row.grand-total .totals-value {
            font-size: 22px;
            font-weight: 800;
            color: #3b82f6;
        }

        /* Tax Breakdown */
        .tax-breakdown {
            margin: 12px 0;
            padding: 14px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .tax-breakdown-title {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .tax-breakdown-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .tax-breakdown-item .name {
            color: #6b7280;
        }

        .tax-breakdown-item .amount {
            color: #059669;
            font-weight: 600;
        }

        /* Product Dropdown */
        .product-dropdown {
            position: fixed;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            width: 380px;
            max-height: 320px;
            overflow: hidden;
            display: none;
        }

        .product-dropdown.show {
            display: block;
        }

        .product-search {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            background: #fff;
        }

        .product-search input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .product-search input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .product-list {
            max-height: 250px;
            overflow-y: auto;
        }

        .product-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.15s ease;
        }

        .product-item:hover {
            background: #f9fafb;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .product-price {
            font-size: 13px;
            color: #059669;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Validation Errors */
        .validation-errors {
            background: linear-gradient(to right, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
            padding: 18px 20px;
            margin-bottom: 24px;
            border-radius: 10px;
        }

        .validation-errors strong {
            color: #dc2626;
            display: block;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .validation-errors ul {
            margin: 0;
            padding-left: 24px;
            color: #b91c1c;
        }

        .validation-errors li {
            margin: 6px 0;
        }

        /* Error Box */
        .validation-error-box {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>
<body>

<form action="{{ isset($estimation) ? route('admin.sales.estimations.update', $estimation->id) : route('admin.sales.estimations.store') }}" method="POST" id="estimationForm">
@csrf
@if(isset($estimation)) @method('PUT') @endif

<div class="estimation-form-wrapper">
    
    @if($errors->any())
    <div class="validation-errors">
        <strong>⚠️ Please fix the following errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header -->
    <div class="estimation-header">
        <h1 class="estimation-title">{{ isset($estimation) ? 'Edit Estimation' : 'New Estimation' }}</h1>
        <div class="header-buttons">
            <a href="{{ route('admin.sales.estimations.index') }}" class="btn btn-outline">
                <span>✕</span> Discard
            </a>
            <button type="submit" class="btn btn-primary">
                <span>💾</span> Save Estimation
            </button>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="form-card">
        <div class="card-header">
            <h3>📋 Basic Information</h3>
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
                    <input type="text" name="subject" class="form-control" value="{{ $estimation->subject ?? '' }}" placeholder="Enter estimation subject" required>
                </div>
              <div class="form-group">
    <label class="form-label">Estimation Number</label>
    <input type="text" class="form-control" value="{{ $estimation->estimation_number ?? $nextNumber ?? 'Auto-generated' }}" readonly style="background: #f3f4f6; cursor: not-allowed;">
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
                        <option value="INR" {{ (isset($estimation) && $estimation->currency == 'INR') ? 'selected' : '' }}>₹ INR</option>
                        <option value="USD" {{ (isset($estimation) && $estimation->currency == 'USD') ? 'selected' : '' }}>$ USD</option>
                        <option value="EUR" {{ (isset($estimation) && $estimation->currency == 'EUR') ? 'selected' : '' }}>€ EUR</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ (isset($estimation) && $estimation->status == 'draft') ? 'selected' : '' }}>📝 Draft</option>
                        <option value="sent" {{ (isset($estimation) && $estimation->status == 'sent') ? 'selected' : '' }}>📤 Sent</option>
                        <option value="accepted" {{ (isset($estimation) && $estimation->status == 'accepted') ? 'selected' : '' }}>✅ Accepted</option>
                        <option value="declined" {{ (isset($estimation) && $estimation->status == 'declined') ? 'selected' : '' }}>❌ Declined</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Lines & Other Info -->
    <div class="form-card">
        <div class="tabs-container">
            <div class="tabs-list">
                <button type="button" class="tab-btn active" data-tab="items">📦 Order Lines</button>
                <button type="button" class="tab-btn" data-tab="other">ℹ️ Other Info</button>
            </div>
        </div>

        <!-- Order Lines Tab -->
        <div id="tab-items" class="tab-content active">
            <div class="order-table-wrapper">
                <table class="order-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 35px;"><input type="checkbox" class="row-checkbox" id="selectAll"></th>
                            <th style="width: 35px;"></th>
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
                <span class="add-link" id="addProductBtn">➕ Add Product</span>
                <span class="add-link" id="addSectionBtn">≡ Add Section</span>
                <span class="add-link" id="addNoteBtn">📝 Add Note</span>
                <span class="add-link danger" id="deleteSelectedBtn">🗑️ Delete Selected</span>
            </div>

            <div class="totals-wrapper">
                <div class="totals-box">
                    <div class="totals-row">
                        <span class="totals-label">Subtotal</span>
                        <span class="totals-value" id="subtotal">₹ 0.00</span>
                    </div>
                    <div class="totals-row">
                        <span class="totals-label">Discount (%)</span>
                        <input type="number" name="discount_percent" id="discountPercent" class="totals-input" value="{{ $estimation->discount_percent ?? 0 }}" step="0.01" min="0" max="100">
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
                    <input type="email" name="email" id="email" class="form-control" value="{{ $estimation->email ?? '' }}" placeholder="customer@email.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $estimation->phone ?? '' }}" placeholder="+91 XXXXX XXXXX">
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="2" placeholder="Street address">{{ $estimation->address ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ $estimation->city ?? '' }}" placeholder="City">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ $estimation->state ?? '' }}" placeholder="State">
                </div>
                <div class="form-group">
                    <label class="form-label">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ $estimation->zip_code ?? '' }}" placeholder="000000">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="{{ $estimation->country ?? '' }}" placeholder="Country">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-span-2">
                    <label class="form-label">Customer Notes</label>
                    <textarea name="content" class="form-control" rows="3" placeholder="Notes visible to customer">{{ $estimation->content ?? '' }}</textarea>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="admin_note" class="form-control" rows="3" placeholder="Private notes (not visible to customer)">{{ $estimation->admin_note ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="grid-column: span 4;">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea name="terms_conditions" class="form-control" rows="4" placeholder="Terms and conditions for this estimation">{{ $estimation->terms_conditions ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Dropdown -->
<div class="product-dropdown" id="productDropdown">
    <div class="product-search">
        <input type="text" id="productSearch" placeholder="🔍 Search products...">
    </div>
    <div class="product-list" id="productList"></div>
</div>

<!-- Tax Dropdown -->
<div class="tax-dropdown" id="taxDropdown"></div>

</form>

<script>
// ========== INITIALIZATION ==========
let itemIndex = 0;
let currentProductInput = null;
let currentTaxCell = null;
let taxesList = [];

const existingItems = @json(isset($estimation) && $estimation->items ? $estimation->items->toArray() : []);

document.addEventListener('DOMContentLoaded', function() {
    console.log('Estimation form initialized');
    
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(`tab-${tab}`).classList.add('active');
        });
    });
    
    // Add buttons
    document.getElementById('addProductBtn').addEventListener('click', addProduct);
    document.getElementById('addSectionBtn').addEventListener('click', addSection);
    document.getElementById('addNoteBtn').addEventListener('click', addNote);
    document.getElementById('deleteSelectedBtn').addEventListener('click', deleteSelected);
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('#itemsBody .row-select').forEach(c => c.checked = this.checked);
    });
    
    // Discount calculation
    document.getElementById('discountPercent').addEventListener('input', calcTotals);
    
    // Customer change
    document.getElementById('customer_id').addEventListener('change', function() {
        const id = this.value;
        if (id) {
            fetch(`{{ url('admin/sales/estimations/customer') }}/${id}`)
                .then(res => res.ok ? res.json() : {})
                .then(data => {
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('phone').value = data.phone || '';
                    document.getElementById('address').value = data.address || '';
                    document.getElementById('city').value = data.city || '';
                    document.getElementById('state').value = data.state || '';
                    document.getElementById('zip_code').value = data.zip_code || '';
                    document.getElementById('country').value = data.country || '';
                })
                .catch(err => console.error('Customer fetch error:', err));
        }
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.product-dropdown') && !e.target.closest('[data-product-trigger]')) {
            document.getElementById('productDropdown').classList.remove('show');
        }
        if (!e.target.closest('.tax-dropdown') && !e.target.closest('.add-tax-btn')) {
            document.getElementById('taxDropdown').classList.remove('show');
        }
    });

    // Product search
    document.getElementById('productSearch').addEventListener('input', filterProducts);

    // Load data
    loadTaxes().then(() => {
        renderExistingItems();
        calcTotals();
    }).catch(err => {
        console.error('Error in initialization:', err);
        renderExistingItems();
        calcTotals();
    });

    // Form validation
    document.getElementById('estimationForm').addEventListener('submit', validateForm);
});

// ========== TAX FUNCTIONS ==========
function loadTaxes() {
    return fetch('/admin/sales/taxes')
        .then(res => res.ok ? res.json() : [])
        .then(data => { taxesList = data || []; })
        .catch(err => { console.error('Failed to load taxes:', err); taxesList = []; });
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
            html += `<span class="tax-badge" data-id="${tax.id}">${tax.rate}% ${tax.name}<span class="remove-tax" data-tax-id="${tax.id}">×</span></span>`;
        }
    });
    html += `<span class="add-tax-btn">+</span>`;
    
    container.innerHTML = html;
    hidden.value = JSON.stringify(ids);
    
    // Attach events
    container.querySelectorAll('.remove-tax').forEach(btn => {
        btn.addEventListener('click', function() {
            removeTax(this, this.dataset.taxId);
        });
    });
    container.querySelector('.add-tax-btn').addEventListener('click', function() {
        showTaxDropdown(this);
    });
    
    calcRow(row.querySelector('.qty-input'));
}

function showTaxDropdown(btn) {
    currentTaxCell = btn.closest('td');
    const row = btn.closest('tr');
    const hidden = row.querySelector('.tax-ids-hidden');
    const selectedIds = parseTaxIds(hidden.value);
    
    const dropdown = document.getElementById('taxDropdown');
    const rect = btn.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + 5) + 'px';
    dropdown.style.left = Math.min(rect.left, window.innerWidth - 240) + 'px';
    
    let html = '';
    taxesList.forEach(tax => {
        const isSelected = selectedIds.includes(String(tax.id));
        html += `<div class="tax-dropdown-item ${isSelected ? 'selected' : ''}" data-tax-id="${tax.id}">
            <span>${tax.name}</span>
            <span style="display: flex; align-items: center; gap: 8px;">
                <span class="tax-rate">${tax.rate}%</span>
                ${isSelected ? '<span class="check-mark">✓</span>' : ''}
            </span>
        </div>`;
    });
    
    dropdown.innerHTML = html || '<div style="padding: 14px; color: #666;">No taxes available</div>';
    dropdown.classList.add('show');
    
    // Attach click events
    dropdown.querySelectorAll('.tax-dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            toggleTax(this.dataset.taxId);
        });
    });
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
    
    // Update dropdown
    const dropdown = document.getElementById('taxDropdown');
    dropdown.querySelectorAll('.tax-dropdown-item').forEach(item => {
        const isSelected = selectedIds.includes(item.dataset.taxId);
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

// ========== ITEM FUNCTIONS ==========
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
            <input type="text" name="items[${itemIndex}][description]" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Product name" data-product-trigger>
            <textarea name="items[${itemIndex}][long_description]" class="desc-input" rows="1" placeholder="Description">${item.long_description || ''}</textarea>
        </td>
        <td class="qty-cell">
            <input type="number" name="items[${itemIndex}][quantity]" class="qty-input" value="${qty}" min="0.01" step="0.01">
        </td>
        <td class="price-cell">
            <input type="number" name="items[${itemIndex}][rate]" class="price-input" value="${rate.toFixed(2)}" step="0.01">
        </td>
        <td class="taxes-cell">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="">
            <div class="tax-badges-wrapper"></div>
        </td>
        <td class="amount-cell">₹ ${amount.toFixed(2)}</td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    
    attachRowEvents(row);
    renderTaxBadges(row, item.tax_ids || '');
}

function addSectionWithData(item) {
    const row = document.createElement('tr');
    row.className = 'section-row';
    row.dataset.type = 'section';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="section">
            <input type="text" name="items[${itemIndex}][description]" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Section Title">
        </td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
}

function addNoteWithData(item) {
    const row = document.createElement('tr');
    row.className = 'note-row';
    row.dataset.type = 'note';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="note">
            <textarea name="items[${itemIndex}][long_description]" placeholder="Note text..." rows="2">${item.long_description || ''}</textarea>
        </td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
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
            <input type="text" name="items[${itemIndex}][description]" placeholder="Product name" data-product-trigger>
            <textarea name="items[${itemIndex}][long_description]" class="desc-input" rows="1" placeholder="Description"></textarea>
        </td>
        <td class="qty-cell">
            <input type="number" name="items[${itemIndex}][quantity]" class="qty-input" value="1" min="0.01" step="0.01">
        </td>
        <td class="price-cell">
            <input type="number" name="items[${itemIndex}][rate]" class="price-input" value="0" step="0.01">
        </td>
        <td class="taxes-cell">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="[]">
            <div class="tax-badges-wrapper"><span class="add-tax-btn">+</span></div>
        </td>
        <td class="amount-cell">₹ 0.00</td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    
    attachRowEvents(row);
    
    // Attach tax button event
    row.querySelector('.add-tax-btn').addEventListener('click', function() {
        showTaxDropdown(this);
    });
}

function addSection() {
    const row = document.createElement('tr');
    row.className = 'section-row';
    row.dataset.type = 'section';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="section">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Section Title">
        </td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
}

function addNote() {
    const row = document.createElement('tr');
    row.className = 'note-row';
    row.dataset.type = 'note';
    row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox row-select"></td>
        <td><span class="drag-handle">⋮⋮</span></td>
        <td colspan="5">
            <input type="hidden" name="items[${itemIndex}][item_type]" value="note">
            <textarea name="items[${itemIndex}][long_description]" placeholder="Note text..." rows="2"></textarea>
        </td>
        <td class="action-cell"><button type="button" class="delete-btn">×</button></td>
    `;
    document.getElementById('itemsBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
}

function attachRowEvents(row) {
    // Delete button
    row.querySelector('.delete-btn').addEventListener('click', function() {
        row.remove();
        reindex();
        calcTotals();
    });
    
    // Product trigger
    const productTrigger = row.querySelector('[data-product-trigger]');
    if (productTrigger) {
        productTrigger.addEventListener('click', function() {
            showProducts(this);
        });
    }
    
    // Quantity and price inputs
    const qtyInput = row.querySelector('.qty-input');
    const priceInput = row.querySelector('.price-input');
    if (qtyInput) {
        qtyInput.addEventListener('input', function() { calcRow(this); });
    }
    if (priceInput) {
        priceInput.addEventListener('input', function() { calcRow(this); });
    }
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

function deleteSelected() {
    document.querySelectorAll('#itemsBody .row-select:checked').forEach(c => c.closest('tr').remove());
    document.getElementById('selectAll').checked = false;
    reindex();
    calcTotals();
}

// ========== PRODUCT FUNCTIONS ==========
function showProducts(el) {
    currentProductInput = el;
    const dropdown = document.getElementById('productDropdown');
    const rect = el.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + 5) + 'px';
    dropdown.style.left = Math.min(rect.left, window.innerWidth - 400) + 'px';
    
    fetch('{{ route("admin.sales.estimations.searchProducts") }}?q=')
        .then(res => res.json())
        .then(renderProducts);
    dropdown.classList.add('show');
    document.getElementById('productSearch').value = '';
    document.getElementById('productSearch').focus();
}

function renderProducts(list) {
    const container = document.getElementById('productList');
    container.innerHTML = list.map(p => {
        const name = (p.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        const taxIds = JSON.stringify(p.tax_ids || []);
        return `<div class="product-item" data-id="${p.id}" data-name="${name}" data-price="${p.price || 0}" data-taxes='${taxIds}'>
            <div class="product-name">${p.name}</div>
            <div class="product-price">₹ ${parseFloat(p.price || 0).toFixed(2)}</div>
        </div>`;
    }).join('') || '<div style="padding: 16px; color: #666; text-align: center;">No products found</div>';
    
    // Attach click events
    container.querySelectorAll('.product-item').forEach(item => {
        item.addEventListener('click', function() {
            pickProduct(
                this.dataset.id,
                this.dataset.name,
                this.dataset.price,
                this.dataset.taxes
            );
        });
    });
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

// ========== CALCULATION FUNCTIONS ==========
function calcRow(el) {
    const row = el?.closest('tr');
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

// ========== VALIDATION ==========
function validateForm(e) {
    const productRows = document.querySelectorAll('#itemsBody tr[data-type="product"]');
    
    if (productRows.length === 0) {
        e.preventDefault();
        showError('Please add at least one product!');
        document.querySelector('[data-tab="items"]').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
    
    let hasValidProduct = false;
    productRows.forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
        const rate = parseFloat(row.querySelector('.price-input')?.value) || 0;
        if (qty > 0 && rate >= 0) {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        showError('Please add valid quantity and price for at least one product!');
        document.querySelector('[data-tab="items"]').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
}

function showError(message) {
    hideError();
    
    const errorBox = document.createElement('div');
    errorBox.className = 'validation-error-box';
    errorBox.innerHTML = `
        <div style="background: linear-gradient(to right, #fef2f2, #fee2e2); border: 2px solid #ef4444; color: #991b1b; padding: 18px 24px; border-radius: 10px; margin: 20px 0; display: flex; align-items: center; gap: 14px; font-size: 15px; font-weight: 600; box-shadow: 0 4px 16px rgba(239, 68, 68, 0.25);">
            <span style="font-size: 28px;">⚠️</span>
            <span>${message}</span>
            <button type="button" style="margin-left: auto; background: #dc2626; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background 0.2s;">Close</button>
        </div>
    `;
    
    errorBox.querySelector('button').addEventListener('click', hideError);
    
    const estimationHeader = document.querySelector('.estimation-header');
    estimationHeader.insertAdjacentElement('afterend', errorBox);
}

function hideError() {
    const existingError = document.querySelector('.validation-error-box');
    if (existingError) existingError.remove();
}
</script>

</body>
</html>