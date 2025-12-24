<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($proposal->id) ? 'Edit Proposal #' . $proposal->proposal_number : 'New Proposal' }}</title>
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

        .page-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;  border-bottom: 1px solid var(--gray-200); position: sticky; top: 0; z-index: 100; }
        .page-title { font-size: 20px; font-weight: 700; margin: 0; color: var(--gray-900); }
        .header-actions { display: flex; gap: 10px; }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; text-decoration: none; border: none; }
        .btn-light { background: white; color: var(--gray-700); border: 1px solid var(--gray-300); }
        .btn-light:hover { background: var(--gray-50); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }

        .card { background: white; border-radius: 12px; border: 1px solid var(--gray-200); margin: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-body { padding: 24px; }

        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-row-3 { grid-template-columns: repeat(3, 1fr); }
        .form-row-4 { grid-template-columns: repeat(4, 1fr); }
        @media (max-width: 768px) { .form-row, .form-row-3, .form-row-4 { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 6px; }
        .form-group label.required::after { content: ' *'; color: var(--danger); }

        .form-control { 
            width: 100%; padding: 10px 14px; border: 1px solid var(--gray-300); border-radius: 8px; 
            font-size: 14px; transition: all 0.2s; background: white; 
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
            cursor: pointer;
        }

        textarea.form-control { resize: vertical; min-height: 80px; }

        /* Tabs */
        .form-tabs { display: flex; border-bottom: 2px solid var(--gray-200); margin-top: 24px; }
        .form-tab { 
            padding: 14px 24px; font-size: 14px; font-weight: 600; color: var(--gray-500); 
            cursor: pointer; border: none; background: none; border-bottom: 3px solid transparent; 
            margin-bottom: -2px; transition: all 0.2s; 
        }
        .form-tab:hover { color: var(--gray-700); background: var(--gray-50); }
        .form-tab.active { color: var(--primary); border-bottom-color: var(--primary); }
        .tab-content { display: none; padding-top: 20px; }
        .tab-content.active { display: block; }

        /* Order Lines Table */
        .odoo-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .odoo-table thead th { 
            padding: 12px 10px; font-weight: 700; color: var(--gray-500); text-align: left; 
            border-bottom: 2px solid var(--gray-200); font-size: 11px; text-transform: uppercase; 
            background: var(--gray-50); 
        }
        .odoo-table thead th:last-child { text-align: right; }
        .odoo-table tbody tr { border-bottom: 1px solid var(--gray-100); }
        .odoo-table tbody tr:hover { background: var(--gray-50); }
        .odoo-table tbody td { padding: 10px; vertical-align: middle; }

        .col-drag { width: 30px; }
        .drag-handle { cursor: grab; color: var(--gray-400); font-size: 14px; padding: 4px; }
        .drag-handle:hover { color: var(--gray-600); }
        .col-product { min-width: 220px; }
        .col-taxes { min-width: 160px; }
        .col-amount { text-align: right !important; font-weight: 600; white-space: nowrap; }
        .col-actions { width: 40px; text-align: center; }

        /* Section & Note Rows */
        .section-row { background: linear-gradient(135deg, #eff6ff, #dbeafe) !important; }
        .section-row td { font-weight: 700 !important; color: var(--gray-700) !important; }
        .note-row { background: linear-gradient(135deg, #fffbeb, #fef3c7) !important; }
        .note-row td { font-style: italic !important; color: var(--gray-600) !important; }

        /* Quick Add Links */
        .quick-add-links { display: flex; gap: 24px; padding: 16px 0; border-top: 1px solid var(--gray-100); margin-top: 16px; flex-wrap: wrap; }
        .quick-add-link { color: var(--primary); font-size: 14px; cursor: pointer; font-weight: 600; padding: 8px 12px; border-radius: 6px; transition: all 0.2s; }
        .quick-add-link:hover { background: #eff6ff; }

        /* Inline Inputs */
        .odoo-input { 
            width: 100%; border: 1px solid transparent; background: transparent; 
            padding: 8px 10px; font-size: 14px; border-radius: 6px; transition: all 0.2s; 
        }
        .odoo-input:focus { outline: none; background: white; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .odoo-input.text-right { text-align: right; }

        .product-desc-input { 
            width: 100%; border: none; background: transparent; font-size: 12px; 
            color: var(--gray-500); resize: none; padding: 4px 10px; margin-top: 4px; 
        }
        .product-desc-input:focus { outline: none; background: white; border: 1px solid var(--gray-300); border-radius: 4px; }

        .section-input { width: 100%; border: none; background: transparent; font-weight: 700; padding: 8px 10px; }
        .section-input:focus { outline: none; background: white; border: 1px solid var(--gray-300); border-radius: 4px; }

        .note-input { width: 100%; border: none; background: transparent; font-style: italic; color: var(--gray-600); resize: none; padding: 8px 10px; }
        .note-input:focus { outline: none; background: white; border: 1px solid var(--gray-300); border-radius: 4px; }

        /* Delete Button */
        .delete-btn { 
            background: none; border: none; color: var(--gray-300); cursor: pointer; 
            padding: 6px; opacity: 0; transition: all 0.2s; font-size: 16px; border-radius: 4px; 
        }
        .odoo-table tbody tr:hover .delete-btn { opacity: 1; }
        .delete-btn:hover { color: var(--danger); background: #fee2e2; }

        /* Tax Badges */
        .tax-badges-wrapper { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; min-height: 32px; }
        .tax-badge { 
            display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; 
            background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fecaca; 
            border-radius: 6px; font-size: 11px; font-weight: 600; color: #991b1b; white-space: nowrap; 
        }
        .tax-badge .remove-tax { cursor: pointer; font-size: 14px; opacity: 0.7; margin-left: 2px; }
        .tax-badge .remove-tax:hover { opacity: 1; }
        .add-tax-btn { 
            display: inline-flex; align-items: center; justify-content: center; 
            width: 28px; height: 28px; background: var(--gray-100); border: 2px dashed var(--gray-300); 
            border-radius: 6px; cursor: pointer; color: var(--gray-500); font-size: 18px; transition: all 0.2s; 
        }
        .add-tax-btn:hover { background: var(--gray-200); color: var(--gray-700); border-color: var(--gray-400); }

        /* Tax Dropdown */
        .tax-dropdown { 
            position: fixed; background: white; border: 1px solid var(--gray-200); border-radius: 10px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); z-index: 1001; min-width: 220px; max-height: 280px; 
            overflow-y: auto; display: none; 
        }
        .tax-dropdown.show { display: block; }
        .tax-dropdown-item { 
            padding: 12px 16px; cursor: pointer; font-size: 13px; display: flex; 
            justify-content: space-between; align-items: center; border-bottom: 1px solid var(--gray-100); 
        }
        .tax-dropdown-item:last-child { border-bottom: none; }
        .tax-dropdown-item:hover { background: var(--gray-50); }
        .tax-dropdown-item.selected { background: #eff6ff; }
        .tax-dropdown-item .tax-rate { color: var(--gray-500); font-size: 12px; }
        .tax-dropdown-item .check-mark { color: var(--primary); font-weight: bold; }

        /* Product Dropdown */
        .product-dropdown { 
            position: fixed; min-width: 350px; background: white; border: 1px solid var(--gray-200); 
            border-radius: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); z-index: 1000; display: none; 
        }
        .product-dropdown.show { display: block; }
        .product-dropdown-search { padding: 12px; border-bottom: 1px solid var(--gray-200); }
        .product-dropdown-search input { 
            width: 100%; border: 1px solid var(--gray-300); border-radius: 8px; 
            padding: 10px 14px; font-size: 14px; 
        }
        .product-dropdown-search input:focus { outline: none; border-color: var(--primary); }
        .product-dropdown-list { max-height: 280px; overflow-y: auto; }
        .product-option { padding: 12px 16px; cursor: pointer; border-bottom: 1px solid var(--gray-100); }
        .product-option:hover { background: var(--gray-50); }
        .product-option:last-child { border-bottom: none; }
        .product-option-name { font-weight: 600; color: var(--gray-800); }
        .product-option-sku { font-size: 12px; color: var(--gray-500); margin-left: 8px; }
        .product-option-price { font-size: 13px; color: var(--success); margin-top: 4px; font-weight: 600; }
        .product-option-more { color: var(--primary); font-weight: 600; }

        /* Totals */
        .totals-section { display: flex; justify-content: flex-end; padding: 20px 0; border-top: 2px solid var(--gray-200); margin-top: 20px; }
        .totals-table { width: 380px; background: linear-gradient(135deg, var(--gray-50), var(--gray-100)); padding: 20px; border-radius: 12px; border: 1px solid var(--gray-200); }
        @media (max-width: 576px) { .totals-table { width: 100%; } }
        .totals-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 14px; }
        .totals-row + .totals-row { border-top: 1px solid var(--gray-200); }
        .totals-row.total { font-weight: 700; font-size: 18px; padding-top: 14px; margin-top: 8px; border-top: 2px solid var(--gray-300); }
        .totals-row.total .totals-value { color: var(--primary); font-size: 22px; }
        .totals-label { color: var(--gray-600); }
        .totals-value { font-weight: 600; }

        .totals-input { 
            width: 70px; border: 1px solid var(--gray-300); text-align: center; 
            border-radius: 6px; padding: 6px; font-size: 14px; font-weight: 600; 
        }
        .totals-input:focus { outline: none; border-color: var(--primary); }

        /* Tax Breakdown */
        .tax-breakdown-box { margin: 12px 0; padding: 14px; background: white; border-radius: 8px; border: 1px solid var(--gray-200); }
        .tax-breakdown-title { font-size: 11px; font-weight: 700; color: var(--gray-600); text-transform: uppercase; margin-bottom: 10px; }
        .tax-breakdown-item { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .tax-breakdown-item .tax-name { color: var(--gray-600); }
        .tax-breakdown-item .tax-amount { color: var(--success); font-weight: 600; }

        /* Catalog Modal */
        .catalog-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1100; }
        .catalog-modal.active { display: flex; }
        .catalog-content { background: white; border-radius: 16px; width: 90%; max-width: 900px; max-height: 85vh; overflow: hidden; }
        .catalog-header { padding: 20px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
        .catalog-header h3 { margin: 0; font-size: 20px; font-weight: 700; }
        .catalog-close { background: none; border: none; font-size: 28px; cursor: pointer; color: var(--gray-400); padding: 0 8px; }
        .catalog-close:hover { color: var(--gray-600); }
        .catalog-body { padding: 20px; overflow-y: auto; max-height: calc(85vh - 80px); }
        .catalog-search { margin-bottom: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .product-card { border: 1px solid var(--gray-200); border-radius: 10px; padding: 16px; cursor: pointer; transition: all 0.2s; }
        .product-card:hover { border-color: var(--primary); background: #eff6ff; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59,130,246,0.15); }
        .product-card .product-name { font-weight: 700; margin-bottom: 4px; color: var(--gray-800); }
        .product-card .product-sku { font-size: 12px; color: var(--gray-500); }
        .product-card .product-price { font-weight: 700; color: var(--primary); margin-top: 10px; font-size: 16px; }

        .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--gray-200); }

        /* Validation Error */
        .validation-error-box { 
            background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 2px solid var(--danger); 
            color: #991b1b; padding: 16px 20px; border-radius: 10px; margin: 20px 24px; 
            display: flex; align-items: center; gap: 12px; font-weight: 600; 
            box-shadow: 0 4px 12px rgba(239,68,68,0.2); animation: slideDown 0.3s ease; 
        }
        .validation-error-box button { 
            margin-left: auto; background: var(--danger); color: white; border: none; 
            padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; 
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
    </style>
</head>
<body>

<form id="proposalForm" method="POST" action="{{ isset($proposal->id) ? route('admin.sales.proposals.update', $proposal->id) : route('admin.sales.proposals.store') }}">
    @csrf
    @if(isset($proposal->id)) @method('PUT') @endif

    <div class="page-header">
        <h1 class="page-title">{{ isset($proposal->id) ? '✏️ Edit Proposal #' . $proposal->proposal_number : '📋 New Proposal' }}</h1>
        <div class="header-actions">
            <a href="{{ route('admin.sales.proposals.index') }}" class="btn btn-light">✕ Discard</a>
            <button type="submit" class="btn btn-primary">💾 Save</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Basic Info -->
            <div class="form-row form-row-4">
                <div class="form-group">
                    <label class="required">Customer</label>
                    <select name="customer_id" id="customerSelect" class="form-control" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $proposal->customer_id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="required">Subject</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $proposal->subject ?? '') }}" placeholder="Proposal subject" required>
                </div>
                <div class="form-group">
                    <label>Proposal Number</label>
                    <input type="text" class="form-control" value="{{ $proposal->proposal_number ?? $nextNumber ?? 'Auto-generated' }}" readonly style="background: var(--gray-100);">
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
                <button type="button" class="form-tab active" data-tab="order-lines">📦 Order Lines</button>
                <button type="button" class="form-tab" data-tab="other-info">ℹ️ Other Info</button>
            </div>

            <!-- Order Lines Tab -->
            <div class="tab-content active" id="tab-order-lines">
                <div style="overflow-x: auto;">
                    <table class="odoo-table" id="orderLinesTable">
                        <thead>
                            <tr>
                                <th class="col-drag"></th>
                                <th class="col-product">Product</th>
                                <th style="width: 90px;">Qty</th>
                                <th style="width: 110px;">Unit Price</th>
                                <th class="col-taxes">Taxes</th>
                                <th style="width: 120px;" class="col-amount">Amount</th>
                                <th class="col-actions"></th>
                            </tr>
                        </thead>
                        <tbody id="orderLinesBody"></tbody>
                    </table>
                </div>

                <div class="quick-add-links">
                    <span class="quick-add-link" id="addProductBtn">➕ Add Product</span>
                    <span class="quick-add-link" id="addSectionBtn">≡ Add Section</span>
                    <span class="quick-add-link" id="addNoteBtn">📝 Add Note</span>
                    <span class="quick-add-link" id="openCatalogBtn">📦 Catalog</span>
                </div>

                <!-- Totals -->
                <div class="totals-section">
                    <div class="totals-table">
                        <div class="totals-row">
                            <span class="totals-label">Subtotal</span>
                            <span class="totals-value" id="subtotal">₹ 0.00</span>
                        </div>
                        <div class="totals-row" id="discountRow" style="display:none;">
                            <span class="totals-label">Discount (<input type="number" name="discount_percent" id="discountPercent" class="totals-input" value="{{ $proposal->discount_percent ?? 0 }}" min="0" max="100">%)</span>
                            <span class="totals-value" id="discountAmt" style="color: var(--danger);">-₹ 0.00</span>
                        </div>
                        
                        <div class="tax-breakdown-box" id="taxBreakdown" style="display:none;">
                            <div class="tax-breakdown-title">Tax Breakdown</div>
                            <div id="taxBreakdownList"></div>
                        </div>

                        <div class="totals-row">
                            <span class="totals-label">Total Tax</span>
                            <span class="totals-value" id="totalTax" style="color: var(--success);">₹ 0.00</span>
                        </div>
                        <div class="totals-row total">
                            <span class="totals-label">Total</span>
                            <span class="totals-value" id="grandTotal">₹ 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Info Tab -->
            <div class="tab-content" id="tab-other-info">
                <div class="form-row">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Street address">{{ $proposal->address ?? '' }}</textarea>
                    </div>
                </div>
                <div class="form-row form-row-4">
                    <div class="form-group"><label>City</label><input type="text" name="city" id="city" class="form-control" value="{{ $proposal->city ?? '' }}" placeholder="City"></div>
                    <div class="form-group"><label>State</label><input type="text" name="state" id="state" class="form-control" value="{{ $proposal->state ?? '' }}" placeholder="State"></div>
                    <div class="form-group"><label>Country</label><input type="text" name="country" id="country" class="form-control" value="{{ $proposal->country ?? '' }}" placeholder="Country"></div>
                    <div class="form-group"><label>Zip</label><input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ $proposal->zip_code ?? '' }}" placeholder="Zip Code"></div>
                </div>
                <div class="form-row form-row-3">
                    <div class="form-group"><label>Email</label><input type="email" name="email" id="email" class="form-control" value="{{ $proposal->email ?? '' }}" placeholder="customer@email.com"></div>
                    <div class="form-group"><label>Phone</label><input type="text" name="phone" id="phone" class="form-control" value="{{ $proposal->phone ?? '' }}" placeholder="+91 XXXXX XXXXX"></div>
                    <div class="form-group">
                        <label>Assigned To</label>
                        <select name="assigned_to" class="form-control">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" {{ old('assigned_to', $proposal->assigned_to ?? '') == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Customer Notes</label>
                        <textarea name="content" class="form-control" rows="3" placeholder="Notes visible to customer">{{ $proposal->content ?? '' }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Internal Notes</label>
                        <textarea name="admin_note" class="form-control" rows="2" placeholder="Private notes (not visible to customer)">{{ $proposal->admin_note ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.sales.proposals.index') }}" class="btn btn-light">✕ Discard</a>
                <button type="submit" class="btn btn-primary">💾 Save</button>
            </div>
        </div>
    </div>
</form>

<!-- Product Search Dropdown -->
<div class="product-dropdown" id="productDropdown">
    <div class="product-dropdown-search">
        <input type="text" id="productSearchInput" placeholder="🔍 Search products...">
    </div>
    <div class="product-dropdown-list" id="productList"></div>
</div>

<!-- Tax Dropdown -->
<div class="tax-dropdown" id="taxDropdown"></div>

<!-- Catalog Modal -->
<div class="catalog-modal" id="catalogModal">
    <div class="catalog-content">
        <div class="catalog-header">
            <h3>📦 Product Catalog</h3>
            <button type="button" class="catalog-close" id="closeCatalogBtn">×</button>
        </div>
        <div class="catalog-body">
            <div class="catalog-search">
                <input type="text" class="form-control" placeholder="🔍 Search products..." id="catalogSearch">
            </div>
            <div class="product-grid" id="productGrid">
                @foreach($products as $p)
                    <div class="product-card" data-name="{{ strtolower($p->name) }}" data-id="{{ $p->id }}" data-price="{{ $p->sale_price ?? 0 }}" data-unit="{{ $p->unit ? $p->unit->short_name : 'PCS' }}" data-desc="{{ $p->short_description ?? '' }}">
                        <div class="product-name">{{ $p->name }}</div>
                        <div class="product-sku">{{ $p->sku ?? '' }}</div>
                        <div class="product-price">₹ {{ number_format($p->sale_price ?? 0, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
// ========== INITIALIZATION ==========
let itemIndex = 0;
let currentProductInput = null;
let currentTaxCell = null;
const cur = '₹';
let taxesList = [];

const existingItems = @json(isset($proposal->id) && $proposal->items ? $proposal->items->toArray() : []);

document.addEventListener('DOMContentLoaded', function() {
    console.log('Proposal form initialized');
    
    // Tab switching
    document.querySelectorAll('.form-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });
    
    // Quick add buttons
    document.getElementById('addProductBtn').addEventListener('click', () => addProduct());
    document.getElementById('addSectionBtn').addEventListener('click', addSection);
    document.getElementById('addNoteBtn').addEventListener('click', addNote);
    document.getElementById('openCatalogBtn').addEventListener('click', openCatalog);
    document.getElementById('closeCatalogBtn').addEventListener('click', closeCatalog);
    
    // Discount handling
    document.getElementById('discountType').addEventListener('change', function() {
        document.getElementById('discountRow').style.display = this.value !== 'no_discount' ? 'flex' : 'none';
        if (this.value === 'no_discount') document.getElementById('discountPercent').value = 0;
        calcTotals();
    });
    document.getElementById('discountPercent').addEventListener('input', calcTotals);
    if (document.getElementById('discountType').value !== 'no_discount') {
        document.getElementById('discountRow').style.display = 'flex';
    }
    
    // Customer autofill
    document.getElementById('customerSelect').addEventListener('change', function() {
        const id = this.value;
        if (id) {
            fetch(`/admin/sales/proposals/customer/${id}`)
                .then(r => r.json())
                .then(d => {
                    document.getElementById('email').value = d.email || '';
                    document.getElementById('phone').value = d.phone || '';
                    document.getElementById('address').value = d.address || '';
                    document.getElementById('city').value = d.city || '';
                    document.getElementById('state').value = d.state || '';
                    document.getElementById('country').value = d.country || '';
                    document.getElementById('zip_code').value = d.zip_code || '';
                });
        }
    });
    
    // Product search
    document.getElementById('productSearchInput').addEventListener('input', filterProducts);
    
    // Catalog search
    document.getElementById('catalogSearch').addEventListener('input', filterCatalog);
    
    // Product cards in catalog
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            pickProductFromCatalog(
                this.dataset.id,
                this.querySelector('.product-name').textContent,
                this.dataset.desc,
                this.dataset.price,
                this.dataset.unit
            );
        });
    });
    
    // Outside click handlers
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#productDropdown') && !e.target.closest('[data-product-trigger]')) {
            document.getElementById('productDropdown').classList.remove('show');
        }
        if (!e.target.closest('.tax-dropdown') && !e.target.closest('.add-tax-btn')) {
            document.getElementById('taxDropdown').classList.remove('show');
        }
    });
    
    // Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeCatalog();
            document.getElementById('productDropdown').classList.remove('show');
            document.getElementById('taxDropdown').classList.remove('show');
        }
    });
    
    // Modal backdrop click
    document.getElementById('catalogModal').addEventListener('click', function(e) {
        if (e.target === this) closeCatalog();
    });
    
    // Form validation
    document.getElementById('proposalForm').addEventListener('submit', validateForm);
    
    // Load taxes and render items
    loadTaxes().then(() => {
        renderExistingItems();
        calcTotals();
    });
});

// ========== TAX FUNCTIONS ==========
function loadTaxes() {
    return fetch('/admin/sales/proposals/taxes')
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
    
    calcTotals();
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
    
    dropdown.innerHTML = html || '<div style="padding: 14px; color: var(--gray-500);">No taxes available</div>';
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
    row.className = 'item-row product-row';
    row.dataset.type = 'product';
    row.innerHTML = `
        <td class="col-drag">
            <span class="drag-handle">⋮⋮</span>
            <input type="hidden" name="items[${itemIndex}][item_type]" value="product">
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${item.product_id || ''}">
        </td>
        <td class="col-product">
            <input type="text" name="items[${itemIndex}][description]" class="odoo-input" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Product name" data-product-trigger>
            <textarea name="items[${itemIndex}][long_description]" class="product-desc-input" rows="1" placeholder="Description">${item.long_description || ''}</textarea>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="odoo-input text-right qty-input" value="${qty.toFixed(2)}" min="0.01" step="0.01">
            <input type="hidden" name="items[${itemIndex}][unit]" value="${item.unit || 'PCS'}">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][rate]" class="odoo-input text-right rate-input" value="${rate.toFixed(2)}" step="0.01">
        </td>
        <td class="col-taxes">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="">
            <div class="tax-badges-wrapper"></div>
        </td>
        <td class="col-amount">
            <span class="amt-display">${cur} ${amount.toFixed(2)}</span>
            <input type="hidden" name="items[${itemIndex}][amount]" class="amt-hidden" value="${amount.toFixed(2)}">
        </td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
    itemIndex++;
    
    attachRowEvents(row);
    renderTaxBadges(row, item.tax_ids || '');
}

function addSectionWithData(item) {
    const row = document.createElement('tr');
    row.className = 'item-row section-row';
    row.dataset.type = 'section';
    row.innerHTML = `
        <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="section"></td>
        <td colspan="5"><input type="text" name="items[${itemIndex}][description]" class="section-input" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Section Title"></td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
}

function addNoteWithData(item) {
    const row = document.createElement('tr');
    row.className = 'item-row note-row';
    row.dataset.type = 'note';
    row.innerHTML = `
        <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="note"></td>
        <td colspan="5"><textarea name="items[${itemIndex}][description]" class="note-input" rows="1" placeholder="Note text...">${item.description || item.long_description || ''}</textarea></td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
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
            <input type="text" name="items[${itemIndex}][description]" class="odoo-input" value="${name}" placeholder="Product name" data-product-trigger>
            <textarea name="items[${itemIndex}][long_description]" class="product-desc-input" rows="1" placeholder="Description">${desc}</textarea>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="odoo-input text-right qty-input" value="1.00" min="0.01" step="0.01">
            <input type="hidden" name="items[${itemIndex}][unit]" value="${unit}">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][rate]" class="odoo-input text-right rate-input" value="${parseFloat(rate).toFixed(2)}" step="0.01">
        </td>
        <td class="col-taxes">
            <input type="hidden" name="items[${itemIndex}][tax_ids]" class="tax-ids-hidden" value="[]">
            <div class="tax-badges-wrapper"><span class="add-tax-btn">+</span></div>
        </td>
        <td class="col-amount">
            <span class="amt-display">${cur} ${parseFloat(rate).toFixed(2)}</span>
            <input type="hidden" name="items[${itemIndex}][amount]" class="amt-hidden" value="${parseFloat(rate).toFixed(2)}">
        </td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
    itemIndex++;
    
    attachRowEvents(row);
    
    // Attach tax button event
    row.querySelector('.add-tax-btn').addEventListener('click', function() {
        showTaxDropdown(this);
    });
    
    calcTotals();
}

function addSection() {
    const row = document.createElement('tr');
    row.className = 'item-row section-row';
    row.dataset.type = 'section';
    row.innerHTML = `
        <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="section"></td>
        <td colspan="5"><input type="text" name="items[${itemIndex}][description]" class="section-input" placeholder="Section name"></td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
    itemIndex++;
    attachRowEvents(row);
}

function addNote() {
    const row = document.createElement('tr');
    row.className = 'item-row note-row';
    row.dataset.type = 'note';
    row.innerHTML = `
        <td class="col-drag"><span class="drag-handle">⋮⋮</span><input type="hidden" name="items[${itemIndex}][item_type]" value="note"></td>
        <td colspan="5"><textarea name="items[${itemIndex}][description]" class="note-input" rows="1" placeholder="Add a note..."></textarea></td>
        <td class="col-actions"><button type="button" class="delete-btn">🗑</button></td>
    `;
    document.getElementById('orderLinesBody').appendChild(row);
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
    
    // Quantity and rate inputs
    const qtyInput = row.querySelector('.qty-input');
    const rateInput = row.querySelector('.rate-input');
    if (qtyInput) qtyInput.addEventListener('input', function() { calcRow(this); });
    if (rateInput) rateInput.addEventListener('input', function() { calcRow(this); });
}

function reindex() {
    document.querySelectorAll('#orderLinesBody .item-row').forEach((r, i) => {
        r.querySelectorAll('input,textarea,select').forEach(el => {
            if (el.name) el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
        });
    });
    itemIndex = document.querySelectorAll('#orderLinesBody .item-row').length;
}

// ========== CALCULATION FUNCTIONS ==========
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
    
    const dt = document.getElementById('discountType').value;
    const dp = parseFloat(document.getElementById('discountPercent').value) || 0;
    let discountAmount = 0;
    
    if (dt === 'before_tax') {
        discountAmount = (subtotal * dp) / 100;
    } else if (dt === 'after_tax') {
        discountAmount = ((subtotal + totalTax) * dp) / 100;
    }
    
    document.getElementById('discountRow').style.display = (dp > 0 && dt !== 'no_discount') ? 'flex' : 'none';
    document.getElementById('discountAmt').textContent = '-' + cur + ' ' + discountAmount.toFixed(2);
    
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
    
    document.getElementById('subtotal').textContent = cur + ' ' + subtotal.toFixed(2);
    document.getElementById('totalTax').textContent = cur + ' ' + totalTax.toFixed(2);
    document.getElementById('grandTotal').textContent = cur + ' ' + grandTotal.toFixed(2);
}

// ========== PRODUCT FUNCTIONS ==========
function showProducts(el) {
    currentProductInput = el;
    const dropdown = document.getElementById('productDropdown');
    const rect = el.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + 5) + 'px';
    dropdown.style.left = Math.min(rect.left, window.innerWidth - 370) + 'px';
    dropdown.classList.add('show');
    
    document.getElementById('productSearchInput').value = '';
    document.getElementById('productSearchInput').focus();
    
    fetch('{{ route("admin.sales.proposals.products.search") }}?q=')
        .then(res => res.json())
        .then(renderProductList);
}

function renderProductList(products) {
    const container = document.getElementById('productList');
    let html = '';
    products.forEach(p => {
        const name = (p.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        html += `<div class="product-option" data-id="${p.id}" data-name="${name}" data-price="${p.price || 0}">
            <span class="product-option-name">${p.name}</span>
            ${p.sku ? '<span class="product-option-sku">[' + p.sku + ']</span>' : ''}
            <div class="product-option-price">${cur} ${parseFloat(p.price || 0).toFixed(2)}</div>
        </div>`;
    });
    html += '<div class="product-option product-option-more" id="searchMoreBtn">🔍 Search more...</div>';
    container.innerHTML = html;
    
    // Attach click events
    container.querySelectorAll('.product-option:not(.product-option-more)').forEach(item => {
        item.addEventListener('click', function() {
            pickProduct(this.dataset.id, this.dataset.name, '', this.dataset.price, 'PCS');
        });
    });
    document.getElementById('searchMoreBtn')?.addEventListener('click', openCatalog);
}

function filterProducts() {
    const search = document.getElementById('productSearchInput').value;
    fetch(`{{ route("admin.sales.proposals.products.search") }}?q=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(renderProductList);
}

function pickProduct(id, name, desc, price, unit) {
    document.getElementById('productDropdown').classList.remove('show');
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
    document.getElementById('productDropdown').classList.remove('show');
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

// ========== VALIDATION ==========
function validateForm(e) {
    const productRows = document.querySelectorAll('#orderLinesBody tr.product-row');
    
    if (productRows.length === 0) {
        e.preventDefault();
        showError('Please add at least one product!');
        document.querySelector('[data-tab="order-lines"]').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
    
    let hasValidProduct = false;
    productRows.forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
        const rate = parseFloat(row.querySelector('.rate-input')?.value) || 0;
        if (qty > 0 && rate >= 0) {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        showError('Please add valid quantity and price for at least one product!');
        document.querySelector('[data-tab="order-lines"]').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
}

function showError(message) {
    hideError();
    
    const errorBox = document.createElement('div');
    errorBox.className = 'validation-error-box';
    errorBox.innerHTML = `<span style="font-size: 24px;">⚠️</span><span>${message}</span><button type="button">Close</button>`;
    
    errorBox.querySelector('button').addEventListener('click', hideError);
    
    const pageHeader = document.querySelector('.page-header');
    pageHeader.insertAdjacentElement('afterend', errorBox);
}

function hideError() {
    const existingError = document.querySelector('.validation-error-box');
    if (existingError) existingError.remove();
}
</script>

</body>
</html>