

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* ============================================
       PRODUCT EDIT FORM - Dark/Light Mode Ready
       Uses CSS variables throughout
       ============================================ */
    
    .page-wrapper { background: var(--body-bg); min-height: 100vh; padding: 0; }
    
    /* Header */
    .page-header { 
        background: var(--card-bg); 
        border-bottom: 1px solid var(--card-border); 
        padding: 12px 24px; 
        display: flex; 
        align-items: center; 
        gap: 16px; 
        position: sticky; 
        top: 0; 
        z-index: 100; 
    }
    
    /* Buttons */
    .btn { 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        padding: 10px 18px; 
        border-radius: 8px; 
        font-size: 14px; 
        font-weight: 600; 
        cursor: pointer; 
        border: 1px solid var(--card-border); 
        background: var(--card-bg); 
        color: var(--text-primary); 
        text-decoration: none; 
        transition: all 0.2s;
    }
    .btn:hover { background: var(--body-bg); }
    .btn svg { width: 16px; height: 16px; }
    .btn-primary { 
        background: linear-gradient(135deg, #3b82f6, #2563eb); 
        border-color: #2563eb; 
        color: #fff; 
    }
    .btn-primary:hover { 
        transform: translateY(-1px); 
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); 
    }
    
    .stock-badge { 
        background: linear-gradient(135deg, #10b981, #059669); 
        color: #fff; 
        padding: 8px 16px; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 13px; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
    }
    .stock-badge svg { width: 16px; height: 16px; }
    
    .form-container { background: var(--card-bg); }
    
    /* Product Header */
    .product-header { 
        padding: 20px 32px; 
        border-bottom: 1px solid var(--card-border); 
        display: flex; 
        gap: 20px; 
        background: var(--body-bg);
    }
    
    /* Image Upload */
    .image-upload-area { 
        width: 100px; 
        height: 100px; 
        border: 2px dashed var(--card-border); 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        background: var(--card-bg); 
        flex-shrink: 0; 
        overflow: hidden; 
        position: relative; 
        transition: all 0.2s; 
    }
    .image-upload-area:hover { 
        border-color: #3b82f6; 
        background: rgba(59, 130, 246, 0.05); 
    }
    .image-upload-area img { width: 100%; height: 100%; object-fit: cover; }
    .image-upload-area svg { width: 32px; height: 32px; color: var(--text-muted); }
    .image-count { 
        position: absolute; 
        top: 4px; 
        left: 4px; 
        background: #3b82f6; 
        color: #fff; 
        font-size: 10px; 
        padding: 2px 6px; 
        border-radius: 4px; 
        font-weight: 600; 
    }
    
    .product-main { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    
    .product-name-input { 
        font-size: 24px; 
        font-weight: 600; 
        border: none; 
        background: transparent; 
        color: var(--text-primary); 
        width: 100%; 
        padding: 0; 
        outline: none; 
        margin-bottom: 12px; 
    }
    .product-name-input:focus { border-bottom: 2px solid #3b82f6; }
    .product-name-input::placeholder { color: var(--text-muted); }
    
    /* Flag Chips */
    .product-flags { display: flex; gap: 6px; flex-wrap: wrap; }
    .flag-chip { 
        display: inline-flex; 
        align-items: center; 
        gap: 5px; 
        font-size: 12px; 
        cursor: pointer; 
        padding: 5px 10px; 
        border-radius: 20px; 
        background: var(--card-bg); 
        border: 1px solid var(--card-border); 
        color: var(--text-muted); 
        transition: all 0.2s; 
        user-select: none; 
    }
    .flag-chip:hover { border-color: #3b82f6; color: #3b82f6; }
    .flag-chip input[type="checkbox"] { position: absolute; opacity: 0; pointer-events: none; }
    .flag-chip.active { 
        background: rgba(59, 130, 246, 0.1); 
        border-color: #3b82f6; 
        color: #3b82f6; 
    }
    .flag-chip .check-icon { 
        width: 14px; 
        height: 14px; 
        border-radius: 50%; 
        border: 1.5px solid currentColor; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        transition: all 0.2s; 
    }
    .flag-chip.active .check-icon { background: #3b82f6; border-color: #3b82f6; }
    .flag-chip.active .check-icon svg { display: block; }
    .flag-chip .check-icon svg { display: none; width: 8px; height: 8px; color: #fff; }
    
    /* Tabs */
    .tabs-nav { 
        display: flex; 
        border-bottom: 1px solid var(--card-border); 
        padding: 0 32px; 
        background: var(--card-bg); 
        overflow-x: auto; 
    }
    .tab-btn { 
        padding: 14px 20px; 
        font-size: 14px; 
        font-weight: 500; 
        color: var(--text-muted); 
        cursor: pointer; 
        border: none; 
        background: none; 
        border-bottom: 3px solid transparent; 
        margin-bottom: -1px; 
        white-space: nowrap; 
    }
    .tab-btn:hover { color: var(--text-primary); }
    .tab-btn.active { color: #3b82f6; border-bottom-color: #3b82f6; }
    .tab-content { display: none; padding: 24px 32px; }
    .tab-content.active { display: block; }
    
    /* Sections */
    .section-title { 
        font-size: 13px; 
        font-weight: 600; 
        color: var(--text-muted); 
        text-transform: uppercase; 
        margin-bottom: 20px; 
        padding-bottom: 10px; 
        border-bottom: 1px solid var(--card-border); 
    }
    
    /* Form Grid */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 48px; }
    @media (max-width: 992px) { .form-grid { grid-template-columns: 1fr; } }
    
    .form-row { 
        display: flex; 
        align-items: flex-start; 
        padding: 12px 0; 
        border-bottom: 1px solid var(--body-bg); 
    }
    .form-label { 
        width: 140px; 
        flex-shrink: 0; 
        font-size: 13px; 
        font-weight: 500; 
        padding-top: 10px; 
        color: var(--text-secondary); 
    }
    .form-label .required { color: #ef4444; }
    .form-value { flex: 1; }
    
    /* Form Controls */
    .form-control { 
        width: 100%; 
        padding: 10px 14px; 
        border: 1px solid var(--card-border); 
        border-radius: 8px; 
        font-size: 14px; 
        background: var(--card-bg); 
        color: var(--text-primary); 
        box-sizing: border-box; 
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus { 
        outline: none; 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
    }
    .form-control::placeholder { color: var(--text-muted); }
    textarea.form-control { min-height: 80px; resize: vertical; }
    
    /* Checkbox */
    .checkbox-label { 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        font-size: 14px; 
        cursor: pointer; 
        padding: 10px 0; 
        color: var(--text-primary);
    }
    .checkbox-label input { width: 16px; height: 16px; accent-color: #3b82f6; }
    
    /* Input Group */
    .input-group { display: flex; }
    .input-prefix, .input-suffix { 
        padding: 10px 14px; 
        background: var(--body-bg); 
        border: 1px solid var(--card-border); 
        font-size: 14px; 
        color: var(--text-muted); 
    }
    .input-prefix { border-right: none; border-radius: 8px 0 0 8px; }
    .input-suffix { border-left: none; border-radius: 0 8px 8px 0; }
    .input-group .form-control.with-prefix { border-radius: 0 8px 8px 0; border-left: none; }
    .input-group .form-control.with-suffix { border-radius: 8px 0 0 8px; border-right: none; }
    
    /* Barcode Input Group */
    .barcode-input-group {
        display: flex;
        gap: 0;
    }
    .barcode-input-group .barcode-type-select select {
        border-radius: 8px 0 0 8px;
        border-right: none;
        background: var(--body-bg);
        font-size: 12px;
        padding: 10px 8px;
    }
    .barcode-input-group input {
        flex: 1;
        border-radius: 0;
        border-left: none;
        border-right: none;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        letter-spacing: 1px;
    }
    .btn-barcode-gen, .btn-barcode-scan {
        padding: 10px 12px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-left: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    .btn-barcode-gen:hover { background: #dcfce7; color: #059669; }
    .btn-barcode-scan:hover { background: #dbeafe; color: #2563eb; }
    .btn-barcode-scan { border-radius: 0 8px 8px 0; }
    .barcode-preview {
        margin-top: 12px;
        padding: 16px;
        background: #fff;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        text-align: center;
    }
    .barcode-preview svg {
        max-width: 100%;
        height: auto;
    }
    .barcode-status {
        margin-top: 8px;
        font-size: 12px;
    }
    .barcode-status.valid { color: #059669; }
    .barcode-status.invalid { color: #dc2626; }
    .barcode-status.exists { color: #f59e0b; }
    
    /* Variations Barcode Table */
    .variations-barcode-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .variations-barcode-table th,
    .variations-barcode-table td {
        padding: 10px 12px;
        border-bottom: 1px solid var(--card-border);
        text-align: left;
        vertical-align: middle;
    }
    .variations-barcode-table th {
        background: var(--body-bg);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .variations-barcode-table tbody tr:hover {
        background: var(--body-bg);
    }
    .var-attr-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        font-size: 11px;
    }
    .var-attr-chip .color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .var-barcode-input-group {
        display: flex;
        gap: 0;
    }
    .var-barcode-input-group .var-barcode-input {
        flex: 1;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        letter-spacing: 0.5px;
        border-radius: 6px 0 0 6px;
        padding: 6px 10px;
    }
    .btn-var-gen {
        padding: 6px 10px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-left: none;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        font-size: 12px;
    }
    .btn-var-gen:hover {
        background: #dcfce7;
    }
    .var-barcode-preview {
        margin-top: 4px;
        padding: 4px;
        background: #fff;
        border-radius: 4px;
        text-align: center;
    }
    .var-barcode-preview svg {
        max-height: 30px;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        text-decoration: none;
        font-size: 12px;
    }
    .btn-action:hover {
        background: var(--card-border);
    }
    .btn-sm {
        font-size: 12px;
        padding: 6px 12px;
    }
    .var-price-input {
        width: 100%;
        padding: 6px 8px;
        font-size: 12px;
        border: 1px solid var(--card-border);
        border-radius: 4px;
        background: var(--card-bg);
    }
    .var-price-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .form-error { color: #ef4444; font-size: 12px; margin-top: 6px; }
    .form-hint { color: var(--text-muted); font-size: 12px; margin-top: 6px; }
    .section-divider { height: 1px; background: var(--card-border); margin: 28px 0; }
    
    /* SKU Input Group */
    .sku-input-group {
        display: flex;
        gap: 0;
    }
    .sku-input-group input {
        flex: 1;
        border-radius: 8px;
    }
    .sku-status {
        margin-top: 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sku-status.checking { color: #6366f1; }
    .sku-status.valid { color: #059669; }
    .sku-status.invalid { color: #dc2626; }
    
    /* Tags Container */
    .tags-container { 
        display: flex; 
        flex-wrap: wrap; 
        gap: 6px; 
        padding: 8px 12px; 
        min-height: 44px; 
        border: 1px solid var(--card-border); 
        border-radius: 8px; 
        cursor: text; 
        background: var(--card-bg); 
    }
    .tags-container:focus-within { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
    }
    .tag-item { 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        background: #3b82f6; 
        color: #fff; 
        padding: 4px 10px; 
        border-radius: 6px; 
        font-size: 12px; 
        font-weight: 500; 
    }
    .tag-item button { 
        background: none; 
        border: none; 
        color: rgba(255,255,255,0.8); 
        cursor: pointer; 
        padding: 0; 
        font-size: 14px; 
    }
    .tags-input { 
        border: none; 
        outline: none; 
        background: transparent; 
        font-size: 14px; 
        flex: 1; 
        min-width: 120px; 
        padding: 4px; 
        color: var(--text-primary);
    }
    .tags-input::placeholder { color: var(--text-muted); }
    
    /* Image Drop Zone */
    .image-drop-zone { 
        border: 2px dashed var(--card-border); 
        border-radius: 12px; 
        padding: 40px; 
        text-align: center; 
        cursor: pointer; 
        background: var(--body-bg); 
        transition: all 0.2s;
        position: relative;
    }
    .image-drop-zone:hover, .image-drop-zone.drag-over { 
        border-color: #3b82f6; 
        background: rgba(59, 130, 246, 0.05); 
    }
    .image-drop-zone.drag-over {
        border-style: solid;
        transform: scale(1.01);
    }
    .image-drop-zone svg { width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 12px; }
    .image-drop-zone p { color: var(--text-muted); margin: 0; font-size: 14px; }
    
    .images-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); 
        gap: 12px; 
        margin-top: 16px; 
    }
    .image-item { 
        position: relative; 
        aspect-ratio: 1; 
        border-radius: 10px; 
        overflow: hidden; 
        border: 2px solid transparent; 
        box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
    }
    .image-item.primary { border-color: #3b82f6; }
    .image-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-actions { 
        position: absolute; 
        top: 5px; 
        right: 5px; 
        display: flex; 
        gap: 4px; 
    }
    .image-actions button { 
        width: 26px; 
        height: 26px; 
        border-radius: 6px; 
        border: none; 
        cursor: pointer; 
        background: var(--card-bg); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    .image-actions .btn-star { color: #f59e0b; }
    .image-actions .btn-delete { color: #ef4444; }
    .image-item .primary-badge { 
        position: absolute; 
        bottom: 5px; 
        left: 5px; 
        background: #3b82f6; 
        color: #fff; 
        font-size: 10px; 
        padding: 2px 8px; 
        border-radius: 4px; 
        font-weight: 600; 
    }
    .image-item .image-color-select {
        position: absolute;
        bottom: 6px;
        left: 6px;
        right: 6px;
        background: rgba(255,255,255,0.95);
        border: 1px solid var(--card-border);
        border-radius: 6px;
        padding: 4px 6px;
        font-size: 11px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .image-item .image-color-select .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        border: 1px solid rgba(0,0,0,0.2);
        flex-shrink: 0;
    }
    .image-item .image-color-select select {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 11px;
        cursor: pointer;
        padding: 0;
        outline: none;
    }
    .image-item.primary .primary-badge {
        bottom: 36px;
    }
    .upload-info { 
        margin-top: 12px; 
        padding: 12px 16px; 
        border-radius: 8px; 
        background: rgba(59, 130, 246, 0.1); 
        color: #3b82f6; 
        border: 1px solid rgba(59, 130, 246, 0.2); 
        font-size: 13px; 
    }
    
    /* Enhanced Image Cards with Color Assignment */
    .images-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }
    .image-card-enhanced {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s;
    }
    .image-card-enhanced.is-primary {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }
    .image-card-preview {
        position: relative;
        aspect-ratio: 1;
        background: var(--body-bg);
    }
    .image-card-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-card-preview .primary-ribbon {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #f59e0b;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .image-card-preview .new-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #10b981;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .btn-remove-new {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: rgba(220, 38, 38, 0.9);
        color: white;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .image-card-enhanced:hover .btn-remove-new {
        opacity: 1;
    }
    .btn-remove-new:hover {
        background: #dc2626;
    }
    .image-card-footer {
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .image-card-actions {
        display: flex;
        gap: 6px;
    }
    .action-btn {
        flex: 1;
        padding: 6px 8px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.15s;
    }
    .action-btn svg { width: 16px; height: 16px; }
    .action-btn.star-btn:hover, .action-btn.star-btn.active {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #f59e0b;
    }
    .action-btn.delete-btn:hover {
        background: #fef2f2;
        border-color: #ef4444;
        color: #ef4444;
    }
    .color-assign-select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        cursor: pointer;
    }
    .color-assign-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .color-select-wrapper {
        position: relative;
    }
    .color-swatch {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid var(--card-border);
        vertical-align: middle;
    }
    .color-swatch-preview {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .image-drop-zone {
        position: relative;
        border: 2px dashed var(--card-border);
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--card-bg);
    }
    .image-drop-zone:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }
    .image-drop-zone.dragover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
    }
    .image-drop-zone svg {
        width: 48px;
        height: 48px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }
    
    /* Stock Management Table */
    .stock-table-wrapper {
        overflow-x: auto;
        border: 1px solid var(--card-border);
        border-radius: 10px;
    }
    .stock-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .stock-table th {
        background: var(--body-bg);
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--card-border);
    }
    .stock-table td {
        padding: 10px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
    }
    .stock-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.03);
    }
    .stock-table tbody tr:last-child td {
        border-bottom: none;
    }
    .stock-table .total-row {
        background: var(--body-bg);
    }
    .stock-table .total-row td {
        padding: 14px 10px;
        border-top: 2px solid var(--card-border);
        border-bottom: none;
    }
    .variation-thumb {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--card-border);
    }
    .variation-thumb-empty {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .variation-thumb-empty svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    .variation-name {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .attr-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .attr-chip.color-chip .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .sku-code {
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 11px;
        padding: 4px 8px;
        background: var(--body-bg);
        border-radius: 4px;
        color: var(--text-muted);
    }
    .stock-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--card-border);
        border-radius: 6px;
        font-size: 14px;
        background: var(--card-bg);
        color: var(--text-primary);
        text-align: right;
    }
    .stock-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--card-border);
        transition: 0.3s;
        border-radius: 24px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    .toggle-switch input:checked + .toggle-slider {
        background-color: #10b981;
    }
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }
    
    /* Units Table */
    .units-table { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 14px; 
        border: 1px solid var(--card-border); 
        border-radius: 8px; 
        overflow: hidden; 
    }
    .units-table th { 
        text-align: left; 
        padding: 12px; 
        font-weight: 600; 
        color: var(--text-muted); 
        background: var(--body-bg); 
        font-size: 11px; 
        text-transform: uppercase; 
    }
    .units-table td { 
        padding: 10px 12px; 
        border-bottom: 1px solid var(--card-border); 
    }
    .units-table input, .units-table select { 
        width: 100%; 
        padding: 8px 10px; 
        border: 1px solid var(--card-border); 
        border-radius: 6px; 
        font-size: 13px; 
        background: var(--card-bg); 
        color: var(--text-primary);
    }
    .units-table .checkbox-cell { text-align: center; }
    .units-table .checkbox-cell input { width: 16px; height: 16px; accent-color: #3b82f6; }
    
    .btn-link { 
        background: none; 
        border: none; 
        color: #3b82f6; 
        cursor: pointer; 
        font-size: 14px; 
        padding: 12px 0; 
        font-weight: 600; 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
    }
    .btn-icon-danger { 
        background: none; 
        border: none; 
        color: #ef4444; 
        cursor: pointer; 
        padding: 6px; 
        border-radius: 6px; 
    }
    .btn-icon-danger:hover { background: rgba(239, 68, 68, 0.1); }
    
    /* ============================================
       TOM SELECT - Dark/Light Mode Ready
       ============================================ */
    .ts-wrapper { width: 100%; }
    .ts-wrapper.single .ts-control { padding: 10px 14px !important; cursor: pointer !important; }
    .ts-wrapper.single .ts-control::after { 
        content: ''; 
        border: solid var(--text-muted); 
        border-width: 0 2px 2px 0; 
        display: inline-block; 
        padding: 3px; 
        transform: rotate(45deg); 
        position: absolute; 
        right: 14px; 
        top: 50%; 
        margin-top: -4px; 
    }
    .ts-wrapper.multi .ts-control { 
        padding: 8px 12px !important; 
        min-height: 48px !important; 
        gap: 6px !important; 
        flex-wrap: wrap !important; 
    }
    .ts-control { 
        padding: 10px 14px !important; 
        border-radius: 10px !important; 
        border: 1px solid var(--card-border) !important; 
        min-height: 48px !important; 
        background: var(--card-bg) !important; 
        box-shadow: none !important; 
        color: var(--text-primary) !important;
    }
    .ts-control:hover { border-color: var(--text-muted) !important; }
    .ts-wrapper.focus .ts-control { 
        border-color: #3b82f6 !important; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important; 
    }
    .ts-dropdown { 
        border-radius: 12px !important; 
        border: 1px solid var(--card-border) !important; 
        box-shadow: 0 12px 40px rgba(0,0,0,0.15) !important; 
        margin-top: 6px !important; 
        overflow: hidden !important; 
        background: var(--card-bg) !important;
    }
    .ts-dropdown .ts-dropdown-content { 
        max-height: 280px !important; 
        padding: 6px !important; 
    }
    .ts-dropdown .option { 
        padding: 12px 14px !important; 
        border-radius: 8px !important; 
        margin: 2px 0 !important; 
        cursor: pointer !important; 
        color: var(--text-primary) !important;
    }
    .ts-dropdown .option:hover { background: var(--body-bg) !important; }
    .ts-dropdown .option.active, .ts-dropdown .option.selected { 
        background: rgba(59, 130, 246, 0.1) !important; 
        color: #3b82f6 !important; 
    }
    .ts-dropdown .optgroup-header { 
        padding: 10px 14px !important; 
        font-weight: 600 !important; 
        color: var(--text-muted) !important; 
        font-size: 11px !important; 
        text-transform: uppercase !important; 
    }
    
    /* Multi-select items (tags) */
    .ts-wrapper.multi .ts-control > .item { 
        background: linear-gradient(135deg, #3b82f6, #2563eb) !important; 
        color: #fff !important; 
        border: none !important; 
        border-radius: 8px !important; 
        padding: 6px 12px !important; 
        margin: 2px !important; 
        font-size: 13px !important; 
        font-weight: 500 !important; 
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }
    .ts-wrapper.multi .ts-control > .item .remove { 
        color: rgba(255,255,255,0.7) !important; 
        border: none !important;
        margin-left: 4px !important; 
        padding: 0 0 0 8px !important; 
        font-size: 18px !important; 
        line-height: 1 !important;
        border-left: 1px solid rgba(255,255,255,0.3) !important;
    }
    .ts-wrapper.multi .ts-control > .item .remove:hover { 
        color: #fff !important; 
        background: transparent !important; 
    }
    .ts-control > input { 
        font-size: 14px !important; 
        color: var(--text-primary) !important;
    }
    .ts-control > input::placeholder { color: var(--text-muted) !important; }
    
    /* Dropdown input plugin */
    .ts-dropdown .dropdown-input-wrap { 
        padding: 10px !important; 
        border-bottom: 1px solid var(--card-border) !important; 
        background: var(--card-bg) !important;
    }
    .ts-dropdown .dropdown-input { 
        width: 100% !important; 
        padding: 10px 14px !important; 
        border: 1px solid var(--card-border) !important; 
        border-radius: 8px !important; 
        font-size: 14px !important; 
        background: var(--card-bg) !important; 
        color: var(--text-primary) !important;
    }
    .ts-dropdown .dropdown-input:focus { 
        border-color: #3b82f6 !important; 
        outline: none !important; 
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important; 
    }
    .ts-dropdown .dropdown-input::placeholder { color: var(--text-muted) !important; }
    
    /* Checkbox options plugin */
    .ts-dropdown .option input[type="checkbox"] { 
        margin-right: 10px !important; 
        width: 16px !important; 
        height: 16px !important; 
        accent-color: #3b82f6 !important; 
    }
    
    /* Clear button */
    .ts-wrapper .clear-button { color: var(--text-muted) !important; }
    .ts-wrapper .clear-button:hover { color: #ef4444 !important; }
    
    /* Attribute Preview */
    .attr-preview-box { 
        margin-top: 20px; 
        padding: 20px; 
        background: var(--body-bg); 
        border-radius: 12px; 
        border: 1px solid var(--card-border); 
    }
    .attr-preview-box h4 { 
        font-size: 13px; 
        font-weight: 600; 
        color: var(--text-secondary); 
        margin: 0 0 16px 0; 
        display: flex; 
        align-items: center; 
        gap: 8px; 
    }
    .attr-preview-box h4::before { 
        content: ''; 
        width: 3px; 
        height: 14px; 
        background: #3b82f6; 
        border-radius: 2px; 
    }
    .attr-preview-item { margin-bottom: 14px; }
    .attr-preview-item:last-child { margin-bottom: 0; }
    .attr-preview-name { 
        font-size: 12px; 
        font-weight: 600; 
        color: var(--text-primary); 
        margin-bottom: 8px; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
    }
    .attr-preview-values { display: flex; flex-wrap: wrap; gap: 6px; }
    .attr-value-tag { 
        background: var(--card-bg); 
        border: 1px solid var(--card-border); 
        padding: 5px 10px; 
        border-radius: 6px; 
        font-size: 12px; 
        color: var(--text-secondary); 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
    }
    .attr-value-tag .color-swatch { 
        width: 12px; 
        height: 12px; 
        border-radius: 3px; 
        border: 1px solid rgba(128,128,128,0.3); 
    }
    
    /* Variation Builder */
    .variation-builder { margin-top: 10px; }
    
    .attr-add-section { margin-bottom: 24px; }
    
    .attr-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .attr-add-btn:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
        color: #3b82f6;
    }
    .attr-add-btn.new-attr-btn {
        border-style: dashed;
        color: var(--text-muted);
    }
    .attr-add-btn.added {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #1d4ed8;
    }
    
    .selected-attrs-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .selected-attr-card {
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .selected-attr-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: var(--card-bg);
        border-bottom: 1px solid var(--card-border);
    }
    
    .selected-attr-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .selected-attr-title .type-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        background: #e0e7ff;
        color: #4338ca;
        text-transform: uppercase;
        font-weight: 600;
    }
    
    .selected-attr-remove {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }
    .selected-attr-remove:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .selected-attr-values {
        padding: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    
    .value-checkbox {
        display: none;
    }
    
    .value-chip-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .value-chip-label:hover {
        border-color: #3b82f6;
    }
    .value-checkbox:checked + .value-chip-label {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #1d4ed8;
        font-weight: 500;
    }
    
    .value-chip-label .color-dot {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1px solid rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .add-value-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
        background: transparent;
        border: 2px dashed var(--card-border);
        border-radius: 8px;
        font-size: 12px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    .add-value-btn:hover {
        border-color: #10b981;
        color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }
    
    .variation-preview {
        margin-top: 16px;
        padding: 16px;
        background: var(--body-bg);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-secondary);
    }
    .variation-preview-count {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    .variation-preview-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .variation-preview-item {
        padding: 4px 10px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 4px;
        font-size: 12px;
    }
    .variation-preview-item .var-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 6px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 11px;
    }
    .variation-preview-item .var-chip .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    /* Color Image Upload */
    .color-value-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid var(--card-border);
    }
    .color-value-row:last-child { border-bottom: none; }
    
    .color-image-upload {
        width: 50px;
        height: 50px;
        border: 2px dashed var(--card-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: var(--card-bg);
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
        transition: all 0.2s;
    }
    .color-image-upload:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }
    .color-image-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .color-image-upload svg {
        width: 20px;
        height: 20px;
        color: var(--text-muted);
    }
    .color-image-upload .primary-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 16px;
        height: 16px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .color-image-upload .primary-badge svg {
        width: 10px;
        height: 10px;
        color: white;
    }
    
    .color-value-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }
    
    .set-primary-btn {
        padding: 4px 8px;
        font-size: 11px;
        background: transparent;
        border: 1px solid var(--card-border);
        border-radius: 4px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    .set-primary-btn:hover {
        border-color: #10b981;
        color: #10b981;
    }
    .set-primary-btn.is-primary {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal {
        background: var(--card-bg);
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--card-border);
    }
    .modal-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--text-muted);
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body { padding: 20px; }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid var(--card-border);
        background: var(--body-bg);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .product-header { flex-direction: column; align-items: center; text-align: center; padding: 20px; }
        .product-flags { justify-content: center; }
        .form-row { flex-direction: column; }
        .form-label { width: 100%; padding-bottom: 6px; }
        .tabs-nav { padding: 0 16px; }
        .tab-content { padding: 20px 16px; }
        .page-header { padding: 12px 16px; flex-wrap: wrap; }
    }
</style>

@php
    $hasVariants = old('has_variants', $product->has_variants);
    
    // Prepare data for JavaScript (avoid arrow functions in @json)
    $preSelectedAttrsData = [];
    foreach ($product->attributes as $attr) {
        $valuesData = [];
        foreach ($attr->values as $v) {
            $valuesData[] = [
                'id' => $v->id,
                'value' => $v->value,
                'color_code' => $v->color_code
            ];
        }
        $preSelectedAttrsData[] = [
            'id' => $attr->id,
            'name' => $attr->name,
            'type' => $attr->type,
            'values' => $valuesData
        ];
    }
    
    // Get selected value IDs from variations
    $selectedValueIdsData = [];
    foreach ($product->variations as $var) {
        foreach ($var->attributeValues as $av) {
            if (!in_array($av->id, $selectedValueIdsData)) {
                $selectedValueIdsData[] = $av->id;
            }
        }
    }
    
    // Get ALL color attribute values from this product's variations
    // These are the colors that can be assigned to images
    $productColorValues = [];
    $colorVariationMap = []; // Maps color_value_id => [variation_ids]
    
    foreach ($product->variations as $var) {
        foreach ($var->attributeValues as $av) {
            if ($av->attribute && $av->attribute->type === 'color') {
                // Add to color values list (unique)
                $found = false;
                foreach ($productColorValues as $cv) {
                    if ($cv['id'] == $av->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $productColorValues[] = [
                        'id' => $av->id,
                        'value' => $av->value,
                        'color_code' => $av->color_code ?? '#cccccc'
                    ];
                }
                
                // Map color to variations
                if (!isset($colorVariationMap[$av->id])) {
                    $colorVariationMap[$av->id] = [];
                }
                $colorVariationMap[$av->id][] = $var->id;
            }
        }
    }
    
    // Build map of image_id => assigned color_value_id
    // by checking which variations have this image_path
    $imageColorAssignments = [];
    foreach ($product->images as $img) {
        $assignedColor = null;
        foreach ($product->variations as $var) {
            if ($var->image_path && $var->image_path === $img->image_path) {
                // This variation uses this image - find its color
                foreach ($var->attributeValues as $av) {
                    if ($av->attribute && $av->attribute->type === 'color') {
                        $assignedColor = $av->id;
                        break 2;
                    }
                }
            }
        }
        $imageColorAssignments[$img->id] = $assignedColor;
    }
@endphp

<div class="page-wrapper">
    <div class="page-header">
        <a href="{{ route('inventory.products.index') }}" class="btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <span class="stock-badge">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            {{ number_format($product->current_stock, 0) }} {{ $product->unit?->short_name ?? 'PCS' }} On Hand
        </span>
        <div style="flex:1;"></div>
        <a href="{{ route('inventory.products.show', $product->id) }}" class="btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View
        </a>
        <button type="submit" form="productForm" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
    </div>

    <form action="{{ route('inventory.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="primary_image_id" id="primaryImageId" value="{{ $product->images->where('is_primary', true)->first()?->id }}">
        <div id="deleteInputsContainer"></div>

        <div class="form-container">
            <div class="product-header">
                <div class="image-upload-area" onclick="document.getElementById('imageInput').click();">
                    @php $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                    @if($primaryImg)
                        <img id="mainPreview" src="{{ asset('storage/' . $primaryImg->image_path) }}" alt="{{ $product->name }}">
                        @if($product->images->count() > 1)
                            <span class="image-count" id="imageCount">{{ $product->images->count() }}</span>
                        @endif
                        <svg id="cameraIcon" style="display:none;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg>
                    @else
                        <svg id="cameraIcon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg>
                        <img id="mainPreview" src="" alt="" style="display:none;">
                        <span class="image-count" id="imageCount" style="display:none;">0</span>
                    @endif
                </div>

                <div class="product-main">
                    <input type="text" name="name" class="product-name-input" value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="product-flags">
                        <label class="flag-chip {{ old('can_be_sold', $product->can_be_sold) ? 'active' : '' }}">
                            <input type="hidden" name="can_be_sold" value="0">
                            <input type="checkbox" name="can_be_sold" value="1" {{ old('can_be_sold', $product->can_be_sold) ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Sell
                        </label>
                        <label class="flag-chip {{ old('can_be_purchased', $product->can_be_purchased) ? 'active' : '' }}">
                            <input type="hidden" name="can_be_purchased" value="0">
                            <input type="checkbox" name="can_be_purchased" value="1" {{ old('can_be_purchased', $product->can_be_purchased) ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Purchase
                        </label>
                        <label class="flag-chip {{ old('track_inventory', $product->track_inventory) ? 'active' : '' }}">
                            <input type="hidden" name="track_inventory" value="0">
                            <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Track Stock
                        </label>
                        <label class="flag-chip {{ $hasVariants ? 'active' : '' }}" id="variantChip">
                            <input type="hidden" name="has_variants" value="0">
                            <input type="checkbox" name="has_variants" id="hasVariantsCheckbox" value="1" {{ $hasVariants ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Variants
                        </label>
                        <label class="flag-chip {{ old('is_active', $product->is_active) ? 'active' : '' }}">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="tabs-nav">
                <button type="button" class="tab-btn active" data-tab="general">General Information</button>
                <button type="button" class="tab-btn" data-tab="pricing">Pricing & Taxes</button>
                <button type="button" class="tab-btn" data-tab="inventory">Inventory</button>
                <button type="button" class="tab-btn" data-tab="images">Images</button>
                <button type="button" class="tab-btn" id="variationsTab" data-tab="variations" style="{{ $hasVariants ? '' : 'display:none;' }}">Variations</button>
            </div>

            <!-- General Information -->
            <div class="tab-content active" id="tab-general">
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">SKU <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="sku-input-group">
                                    <input type="text" name="sku" id="skuInput" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                                </div>
                                <div id="skuStatus" class="sku-status"></div>
                                @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Barcode</label>
                            <div class="form-value">
                                <div class="barcode-input-group">
                                    <div class="barcode-type-select">
                                        <select id="barcodeType" class="form-control" style="width: 110px;">
                                            <option value="EAN13">EAN-13</option>
                                            <option value="EAN8">EAN-8</option>
                                            <option value="CODE128">Code 128</option>
                                            <option value="INTERNAL">Internal</option>
                                        </select>
                                    </div>
                                    <input type="text" name="barcode" id="barcodeInput" class="form-control" placeholder="Enter or generate barcode" value="{{ old('barcode', $product->barcode) }}">
                                    <button type="button" class="btn-barcode-gen" onclick="generateBarcode()" title="Generate Barcode">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-barcode-scan" onclick="openBarcodeScanner()" title="Scan Barcode">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="barcode-preview" id="barcodePreview" style="display:none;">
                                    <svg id="barcodeCanvas"></svg>
                                    <div class="barcode-status" id="barcodeStatus"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Category</label>
                            <div class="form-value">
                                <select name="category_id" class="form-control searchable-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Brand</label>
                            <div class="form-value">
                                <select name="brand_id" class="form-control searchable-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)<option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">HSN Code</label>
                            <div class="form-value"><input type="text" name="hsn_code" class="form-control" value="{{ old('hsn_code', $product->hsn_code) }}"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Base Unit <span class="required">*</span></label>
                            <div class="form-value">
                                <select name="unit_id" class="form-control searchable-select" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)<option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Short Description</label>
                            <div class="form-value"><input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}" maxlength="500"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Full Description</label>
                            <div class="form-value"><textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Tags</label>
                            <div class="form-value">
                                <div class="tags-container" id="tagsWrapper">
                                    <input type="text" class="tags-input" id="tagsInput" placeholder="Type and press Enter">
                                </div>
                                <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $product->tags->pluck('name')->implode(', ')) }}">
                                <div class="form-hint">Press Enter to add tags</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Taxes -->
            <div class="tab-content" id="tab-pricing">
                <div class="section-title">Pricing</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Purchase Price <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="purchase_price" id="purchasePrice" class="form-control with-prefix" step="0.01" min="0" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Sale Price <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="sale_price" id="salePrice" class="form-control with-prefix" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">MRP</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="mrp" id="mrpPrice" class="form-control with-prefix" step="0.01" min="0" value="{{ old('mrp', $product->mrp) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Profit Rate</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <input type="number" name="default_profit_rate" id="profitRate" class="form-control with-suffix" step="0.01" min="0" max="100" placeholder="0" value="{{ old('default_profit_rate', $product->default_profit_rate ?? 0) }}">
                                    <span class="input-suffix">%</span>
                                </div>
                                <div class="form-hint">Enter % to calculate sale price</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Calculated Sale</label>
                            <div class="form-value">
                                <div class="input-group" style="cursor:pointer;" onclick="applyCalc()">
                                    <span class="input-prefix">₹</span>
                                    <input type="text" id="calculatedSale" class="form-control with-prefix" readonly placeholder="--" style="cursor:pointer;background:#f0fdf4;">
                                    <span class="input-suffix" style="background:#dcfce7;color:#166534;">Apply</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Profit Summary</label>
                            <div class="form-value">
                                <div class="profit-summary" id="profitSummary">
                                    <div class="profit-item">
                                        <span class="profit-label">Profit Amount:</span>
                                        <span class="profit-value" id="profitAmount">₹0.00</span>
                                    </div>
                                    <div class="profit-item">
                                        <span class="profit-label">Margin:</span>
                                        <span class="profit-value" id="profitMargin">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .profit-summary { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 12px; }
                    .profit-item { display: flex; justify-content: space-between; padding: 4px 0; }
                    .profit-label { color: var(--text-muted); font-size: 13px; }
                    .profit-value { font-weight: 600; font-size: 14px; }
                    .profit-value.positive { color: #059669; }
                    .profit-value.negative { color: #dc2626; }
                </style>

                <div class="section-divider"></div>
                <div class="section-title">Taxes</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Tax 1</label>
                            <div class="form-value">
                                <select name="tax_1_id" class="form-control searchable-select">
                                    <option value="">No Tax</option>
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_1_id', $product->tax_1_id) == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Tax 2</label>
                            <div class="form-value">
                                <select name="tax_2_id" class="form-control searchable-select">
                                    <option value="">No Tax</option>
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_2_id', $product->tax_2_id) == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>
                <div class="section-title">Alternate Units</div>
                <table class="units-table">
                    <thead>
                        <tr><th>Unit</th><th>Custom Name</th><th>Conversion</th><th>Purchase ₹</th><th>Sale ₹</th><th>Barcode</th><th class="checkbox-cell">Buy</th><th class="checkbox-cell">Sell</th><th></th></tr>
                    </thead>
                    <tbody id="productUnitsBody">
                        @foreach($product->productUnits as $idx => $pu)
                        <tr>
                            <td>
                                <input type="hidden" name="product_units[{{ $idx }}][id]" value="{{ $pu->id }}">
                                <select name="product_units[{{ $idx }}][unit_id]" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($units as $unit)<option value="{{ $unit->id }}" {{ $pu->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->short_name }}</option>@endforeach
                                </select>
                            </td>
                            <td><input type="text" name="product_units[{{ $idx }}][unit_name]" class="form-control" value="{{ $pu->unit_name }}"></td>
                            <td><input type="number" name="product_units[{{ $idx }}][conversion_factor]" class="form-control" step="0.0001" value="{{ $pu->conversion_factor }}" required></td>
                            <td><input type="number" name="product_units[{{ $idx }}][purchase_price]" class="form-control" step="0.01" value="{{ $pu->purchase_price }}"></td>
                            <td><input type="number" name="product_units[{{ $idx }}][sale_price]" class="form-control" step="0.01" value="{{ $pu->sale_price }}"></td>
                            <td><input type="text" name="product_units[{{ $idx }}][barcode]" class="form-control" value="{{ $pu->barcode }}"></td>
                            <td class="checkbox-cell"><input type="checkbox" name="product_units[{{ $idx }}][is_purchase_unit]" value="1" {{ $pu->is_purchase_unit ? 'checked' : '' }}></td>
                            <td class="checkbox-cell"><input type="checkbox" name="product_units[{{ $idx }}][is_sale_unit]" value="1" {{ $pu->is_sale_unit ? 'checked' : '' }}></td>
                            <td><button type="button" class="btn-icon-danger" onclick="this.closest('tr').remove()">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn-link" onclick="addUnitRow()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Unit
                </button>
            </div>

            <!-- Inventory -->
            <div class="tab-content" id="tab-inventory">
                <div class="section-title">Stock Settings</div>
                <div class="form-grid">
                    <div>
                        <div class="form-row">
                            <label class="form-label">Min Stock Level</label>
                            <div class="form-value">
                                <input type="number" name="min_stock_level" class="form-control" step="0.001" min="0" value="{{ old('min_stock_level', $product->min_stock_level) }}">
                                <div class="form-hint">Alert when stock falls below</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Max Stock Level</label>
                            <div class="form-value"><input type="number" name="max_stock_level" class="form-control" step="0.001" min="0" value="{{ old('max_stock_level', $product->max_stock_level) }}"></div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Batch Management</label>
                            <div class="form-value">
                                <label class="checkbox-label"><input type="checkbox" name="is_batch_managed" value="1" {{ old('is_batch_managed', $product->is_batch_managed) ? 'checked' : '' }}> Enable Batch/Lot Management</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Tab -->
            <div class="tab-content" id="tab-images">
                <div class="section-title">Product Images</div>
                
                @if(count($productColorValues) > 0)
                <p class="form-hint" style="margin-bottom:16px;">Upload product images and assign them to colors. When you assign a color, all variations with that color will use this image.</p>
                @else
                <div class="alert-info" style="margin-bottom:16px;padding:12px 16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;color:#1e40af;">
                    <strong>Tip:</strong> First create variations with colors in the Variations tab, then come back here to assign images to colors.
                </div>
                @endif
                
                @if($product->images->count() > 0)
                <div class="images-grid-enhanced" id="existingImages">
                    @foreach($product->images as $img)
                    <div class="image-card-enhanced {{ $img->is_primary ? 'is-primary' : '' }}" id="existing-{{ $img->id }}">
                        <div class="image-card-preview">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="">
                            @if($img->is_primary)<span class="primary-ribbon">★ Primary</span>@endif
                        </div>
                        <div class="image-card-footer">
                            <div class="image-card-actions">
                                <button type="button" class="action-btn star-btn {{ $img->is_primary ? 'active' : '' }}" onclick="setExistingPrimary({{ $img->id }})" title="Set as Primary">
                                    <svg fill="{{ $img->is_primary ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                                <button type="button" class="action-btn delete-btn" onclick="markForDelete({{ $img->id }})" title="Delete">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                            @if(count($productColorValues) > 0)
                            <div class="color-select-wrapper">
                                <label style="font-size:11px;color:var(--text-muted);margin-bottom:4px;display:block;">Assign to Color:</label>
                                <select name="image_color_assignments[{{ $img->id }}]" class="color-assign-select" onchange="updateColorPreview(this)">
                                    <option value="">-- Select Color --</option>
                                    @foreach($productColorValues as $color)
                                        <option value="{{ $color['id'] }}" 
                                            data-color="{{ $color['color_code'] }}"
                                            {{ (isset($imageColorAssignments[$img->id]) && $imageColorAssignments[$img->id] == $color['id']) ? 'selected' : '' }}>
                                            {{ $color['value'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="color-swatch-preview" style="margin-top:6px;">
                                    @if(isset($imageColorAssignments[$img->id]) && $imageColorAssignments[$img->id])
                                        @php
                                            $assignedColorCode = '#ccc';
                                            foreach($productColorValues as $c) {
                                                if($c['id'] == $imageColorAssignments[$img->id]) {
                                                    $assignedColorCode = $c['color_code'];
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <span class="color-swatch" style="background:{{ $assignedColorCode }};"></span>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div style="font-size:11px;color:var(--text-muted);text-align:center;padding:8px 0;">
                                Create variations first
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="section-divider"></div>
                @endif
                
                <div class="section-title" style="margin-top: 24px;">Add New Images</div>
                <div class="image-drop-zone" id="imageDropZone">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <p><strong style="color:#3b82f6;">Click to browse</strong> or drag and drop images here</p>
                    <p style="font-size:12px;margin-top:8px;color:var(--text-muted);">PNG, JPG, WEBP up to 5MB each • Select multiple files at once</p>
                </div>
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" style="display:none !important;">
                <div class="upload-info" id="uploadInfo" style="display:none;margin-top:16px;padding:12px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span style="display:flex;align-items:center;gap:8px;">
                            <svg fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <strong id="uploadCount">0</strong> new image(s) ready to upload. Save to keep changes.
                        </span>
                        <button type="button" class="btn btn-sm" onclick="clearNewImages()" style="font-size: 11px; padding: 4px 10px;">Clear</button>
                    </div>
                </div>
                <div class="images-grid-enhanced" id="newImagePreviewGrid" style="margin-top:16px;"></div>
            </div>

            <!-- Variations Tab -->
            <div class="tab-content" id="tab-variations">
                <div class="section-title">Product Variations</div>
                
                <!-- Existing Variations with Barcodes -->
                @if($product->variations->count() > 0)
                <div class="existing-variations-section" style="margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                        <h4 style="margin: 0; font-size: 14px; font-weight: 600;">Existing Variations ({{ $product->variations->count() }})</h4>
                        <button type="button" class="btn btn-sm" onclick="generateAllVariationBarcodes()" style="font-size: 12px; padding: 6px 12px;">
                            🔲 Generate All Barcodes
                        </button>
                    </div>
                    
                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #0369a1; font-size: 13px;">
                            <span>💡</span>
                            <span>Edit barcodes directly in the table. Click 🔄 to generate for one variation, or "Generate All Barcodes" for all empty barcodes.</span>
                        </div>
                    </div>
                    
                    <div class="variations-table-wrapper" style="overflow-x: auto;">
                        <table class="variations-barcode-table" id="variationsBarcodeTable">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">Active</th>
                                    <th>Variation</th>
                                    <th>SKU</th>
                                    <th style="min-width: 200px;">Barcode</th>
                                    <th style="width: 100px;">Purchase ₹</th>
                                    <th style="width: 100px;">Sale ₹</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variations as $var)
                                <tr data-variation-id="{{ $var->id }}">
                                    <td style="text-align: center;">
                                        <input type="checkbox" {{ $var->is_active ? 'checked' : '' }} onchange="toggleVariationActive({{ $var->id }}, this.checked)">
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            @foreach($var->attributeValues as $av)
                                                @if($av->attribute && $av->attribute->type === 'color')
                                                    <span class="var-attr-chip">
                                                        <span class="color-dot" style="background: {{ $av->color_code ?? '#ccc' }};"></span>
                                                        {{ $av->value }}
                                                    </span>
                                                @else
                                                    <span class="var-attr-chip">{{ $av->value }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td><code style="font-size: 11px;">{{ $var->sku }}</code></td>
                                    <td>
                                        <div class="var-barcode-input-group">
                                            <input type="text" 
                                                class="form-control var-barcode-input" 
                                                id="varBarcode{{ $var->id }}"
                                                value="{{ $var->barcode }}" 
                                                placeholder="No barcode"
                                                onchange="saveVariationField({{ $var->id }}, 'barcode', this.value)">
                                            <button type="button" class="btn-var-gen" onclick="generateSingleVariationBarcode({{ $var->id }})" title="Generate">
                                                🔄
                                            </button>
                                        </div>
                                        @if($var->barcode)
                                        <div class="var-barcode-preview" id="varPreview{{ $var->id }}"></div>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" 
                                            class="form-control var-price-input" 
                                            value="{{ $var->purchase_price ?? $product->purchase_price }}" 
                                            step="0.01" min="0"
                                            onchange="saveVariationField({{ $var->id }}, 'purchase_price', this.value)">
                                    </td>
                                    <td>
                                        <input type="number" 
                                            class="form-control var-price-input" 
                                            value="{{ $var->sale_price ?? $product->sale_price }}" 
                                            step="0.01" min="0"
                                            onchange="saveVariationField({{ $var->id }}, 'sale_price', this.value)">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="section-divider"></div>
                @endif

                <div class="section-title" style="font-size: 14px;">Add New Variations</div>
                <p class="form-hint" style="margin-bottom:20px;">Select which attributes and values to use for this product's variations. You can also add new values directly.</p>
                
                <!-- Attribute Selector -->
                <div class="variation-builder">
                    <div class="attr-add-section">
                        <label class="form-label">Add Attribute</label>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            @if(isset($attributes))
                                @foreach($attributes as $attribute)
                                    <button type="button" class="attr-add-btn" 
                                        data-attr-id="{{ $attribute->id }}"
                                        data-attr-name="{{ $attribute->name }}"
                                        data-attr-type="{{ $attribute->type }}"
                                        data-attr-values='@json($attribute->values)'
                                        onclick="addAttributeToProduct(this)">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14" height="14">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ $attribute->name }}
                                    </button>
                                @endforeach
                            @endif
                            <button type="button" class="attr-add-btn new-attr-btn" onclick="openQuickAttributeModal()">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14" height="14">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Attribute...
                            </button>
                        </div>
                    </div>
                    
                    <!-- Selected Attributes with Values -->
                    <div id="selectedAttributesContainer" class="selected-attrs-container">
                        <!-- Will be populated by JS -->
                    </div>
                    
                    <!-- Hidden inputs for form submission -->
                    <div id="variationHiddenInputs"></div>
                    
                    <!-- Generate button -->
                    <div id="generateSection" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--card-border);">
                        <label class="checkbox-label">
                            <input type="checkbox" name="generate_variations" value="1"> 
                            <strong>Generate variation combinations</strong>
                            <span style="color: var(--text-muted); font-weight: normal;"> - Creates all possible combinations (e.g., Red+S, Red+M, Blue+S, Blue+M)</span>
                        </label>
                        <div id="variationPreview" class="variation-preview"></div>
                    </div>
                </div>
            </div>

<!-- Quick Add Attribute Modal -->
<div id="quickAttrModal" class="modal-overlay">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Create New Attribute</h3>
            <button type="button" class="modal-close" onclick="closeQuickAttrModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Attribute Name <span style="color:#ef4444;">*</span></label>
                <input type="text" id="quickAttrName" class="form-input" placeholder="e.g., Color, Size, Material">
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <select id="quickAttrType" class="form-input">
                    <option value="select">Select (Dropdown)</option>
                    <option value="color">Color (with swatches)</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeQuickAttrModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveQuickAttribute()">Create & Add</button>
        </div>
    </div>
</div>

<!-- Quick Add Value Modal -->
<div id="quickValueModal" class="modal-overlay">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="quickValueModalTitle">Add Value</h3>
            <button type="button" class="modal-close" onclick="closeQuickValueModal()">×</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="quickValueAttrId">
            <input type="hidden" id="quickValueAttrType">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Value <span style="color:#ef4444;">*</span></label>
                <input type="text" id="quickValueName" class="form-input" placeholder="e.g., Red, Large, Cotton">
            </div>
            <div class="form-group" id="quickValueColorGroup" style="display: none;">
                <label class="form-label">Color</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="color" id="quickValueColorPicker" value="#3b82f6" style="width: 50px; height: 38px; border: 1px solid var(--card-border); border-radius: 6px; cursor: pointer;">
                    <input type="text" id="quickValueColorCode" class="form-input" placeholder="#3b82f6" style="flex: 1;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeQuickValueModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveQuickValue()">Add Value</button>
        </div>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div id="barcodeScannerModal" class="modal-overlay" style="display:none;">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>📷 Scan Barcode</h3>
            <button type="button" class="modal-close" onclick="closeBarcodeScanner()">×</button>
        </div>
        <div class="modal-body" style="padding: 0;">
            <div style="background: #000; position: relative;">
                <video id="scannerVideo" style="width: 100%; max-height: 300px; display: block;"></video>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 2px solid #22c55e; width: 80%; height: 60px; border-radius: 4px;"></div>
            </div>
            <div style="padding: 16px; text-align: center;">
                <p style="color: var(--text-muted); font-size: 13px; margin: 0 0 12px;">Point camera at barcode. Auto-detects EAN-13, EAN-8, Code 128.</p>
                <button type="button" class="btn" onclick="manualBarcodeEntry()">Enter Manually</button>
            </div>
        </div>
    </div>
</div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
// ==========================================
// BARCODE FUNCTIONS (Global)
// ==========================================
function generateBarcode() {
    var typeEl = document.getElementById('barcodeType');
    var inputEl = document.getElementById('barcodeInput');
    var previewEl = document.getElementById('barcodePreview');
    
    if (!typeEl || !inputEl) return;
    
    var type = typeEl.value;
    var skuEl = document.querySelector('input[name="sku"]');
    var sku = skuEl ? skuEl.value : '';
    
    fetch('{{ route("inventory.barcode.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type, sku: sku })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            inputEl.value = data.barcode;
            renderBarcodePreview(data.barcode, type);
        }
    })
    .catch(err => console.error('Barcode generation error:', err));
}

function renderBarcodePreview(code, type) {
    var previewEl = document.getElementById('barcodePreview');
    var canvasEl = document.getElementById('barcodeCanvas');
    var statusEl = document.getElementById('barcodeStatus');
    
    if (!previewEl || !canvasEl || !code) {
        if (previewEl) previewEl.style.display = 'none';
        return;
    }
    
    previewEl.style.display = 'block';
    
    var format = 'CODE128';
    if (type === 'EAN13') format = 'EAN13';
    else if (type === 'EAN8') format = 'EAN8';
    
    try {
        JsBarcode(canvasEl, code, {
            format: format,
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
            margin: 10,
            valid: function(valid) {
                if (statusEl) {
                    statusEl.textContent = valid ? '✓ Valid barcode' : '✗ Invalid format';
                    statusEl.className = 'barcode-status ' + (valid ? 'valid' : 'invalid');
                }
            }
        });
    } catch (e) {
        try {
            JsBarcode(canvasEl, code, { format: 'CODE128', width: 2, height: 60, displayValue: true });
            if (statusEl) {
                statusEl.textContent = '✓ Valid barcode (Code 128)';
                statusEl.className = 'barcode-status valid';
            }
        } catch (e2) {
            if (statusEl) {
                statusEl.textContent = '✗ Cannot render barcode';
                statusEl.className = 'barcode-status invalid';
            }
        }
    }
}

function openBarcodeScanner() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Camera scanning is not supported in this browser. Please enter barcode manually.');
        return;
    }
    var modal = document.getElementById('barcodeScannerModal');
    if (modal) {
        modal.style.display = 'flex';
        startBarcodeScanner();
    }
}

var scannerStream = null;
function startBarcodeScanner() {
    var video = document.getElementById('scannerVideo');
    if (!video) return;
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
    .then(function(stream) {
        scannerStream = stream;
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        alert('Could not access camera. Please enter barcode manually.');
        closeBarcodeScanner();
    });
}

function closeBarcodeScanner() {
    var modal = document.getElementById('barcodeScannerModal');
    if (modal) modal.style.display = 'none';
    if (scannerStream) {
        scannerStream.getTracks().forEach(track => track.stop());
        scannerStream = null;
    }
}

function manualBarcodeEntry() {
    var code = prompt('Enter barcode manually:');
    if (code) {
        var inputEl = document.getElementById('barcodeInput');
        if (inputEl) {
            inputEl.value = code;
            var typeEl = document.getElementById('barcodeType');
            renderBarcodePreview(code, typeEl ? typeEl.value : 'CODE128');
        }
    }
    closeBarcodeScanner();
}

// ==========================================
// VARIATION BARCODE FUNCTIONS
// ==========================================

function generateSingleVariationBarcode(variationId) {
    var inputEl = document.getElementById('varBarcode' + variationId);
    if (!inputEl) return;
    
    fetch('{{ url("admin/inventory/variations") }}/' + variationId + '/generate-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'EAN13' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            inputEl.value = data.barcode;
            renderVariationBarcodePreview(variationId, data.barcode);
        }
    })
    .catch(err => console.error('Error generating barcode:', err));
}

function generateAllVariationBarcodes() {
    if (!confirm('Generate barcodes for all variations without barcode?')) return;
    
    fetch('{{ route("inventory.products.generate-variation-barcodes", $product->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Update the inputs
            data.generated.forEach(function(item) {
                var inputEl = document.getElementById('varBarcode' + item.id);
                if (inputEl) {
                    inputEl.value = item.barcode;
                    renderVariationBarcodePreview(item.id, item.barcode);
                }
            });
        }
    })
    .catch(err => console.error('Error generating barcodes:', err));
}

function saveVariationBarcode(variationId, barcode) {
    saveVariationField(variationId, 'barcode', barcode);
}

// Generic function to save any variation field
function saveVariationField(variationId, field, value, inputEl) {
    var data = {};
    data[field] = value;
    
    fetch('{{ url("admin/inventory/variations") }}/' + variationId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // If barcode was updated, render preview
            if (field === 'barcode') {
                renderVariationBarcodePreview(variationId, value);
            }
            // Show success feedback
            if (inputEl) {
                inputEl.style.borderColor = '#10b981';
                setTimeout(() => { inputEl.style.borderColor = ''; }, 1500);
            }
        } else {
            // Show error
            alert('❌ ' + (data.message || 'Failed to save'));
            if (inputEl) {
                inputEl.style.borderColor = '#ef4444';
                inputEl.focus();
            }
        }
    })
    .catch(err => {
        console.error('Error saving variation:', err);
        alert('❌ Failed to save variation');
    });
}

// Toggle variation active status
function toggleVariationActive(variationId, isActive) {
    saveVariationField(variationId, 'is_active', isActive ? 1 : 0);
}

function renderVariationBarcodePreview(variationId, barcode) {
    var previewEl = document.getElementById('varPreview' + variationId);
    
    if (!barcode) {
        if (previewEl) previewEl.style.display = 'none';
        return;
    }
    
    // Create preview element if not exists
    if (!previewEl) {
        var inputGroup = document.querySelector('[data-variation-id="' + variationId + '"] .var-barcode-input-group');
        if (inputGroup) {
            previewEl = document.createElement('div');
            previewEl.id = 'varPreview' + variationId;
            previewEl.className = 'var-barcode-preview';
            previewEl.innerHTML = '<svg id="varCanvas' + variationId + '"></svg>';
            inputGroup.parentNode.appendChild(previewEl);
        }
    }
    
    if (previewEl) {
        previewEl.style.display = 'block';
        previewEl.innerHTML = '<svg id="varCanvas' + variationId + '"></svg>';
        
        var format = /^\d{13}$/.test(barcode) ? 'EAN13' : (/^\d{8}$/.test(barcode) ? 'EAN8' : 'CODE128');
        
        try {
            JsBarcode('#varCanvas' + variationId, barcode, {
                format: format,
                width: 1,
                height: 25,
                displayValue: true,
                fontSize: 10,
                margin: 2
            });
        } catch (e) {
            try {
                JsBarcode('#varCanvas' + variationId, barcode, {
                    format: 'CODE128',
                    width: 1,
                    height: 25,
                    displayValue: true,
                    fontSize: 10,
                    margin: 2
                });
            } catch (e2) {
                previewEl.innerHTML = '<small style="color:#dc2626;">Invalid barcode</small>';
            }
        }
    }
}

// Initialize variation barcode previews on page load
document.addEventListener('DOMContentLoaded', function() {
    @if($product->variations->count() > 0)
    @foreach($product->variations as $var)
    @if($var->barcode)
    renderVariationBarcodePreview({{ $var->id }}, '{{ $var->barcode }}');
    @endif
    @endforeach
    @endif
});

document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // VARIABLES
    // ==========================================
    var preSelectedAttrs = @json($preSelectedAttrsData);
    var selectedValueIds = @json($selectedValueIdsData);
    var productAttributes = {};
    var tags = [];
    var unitIdx = {{ $product->productUnits->count() }};
    
    // ==========================================
    // DEFINE ALL FUNCTIONS FIRST
    // ==========================================
    
    // --- Update Variation Preview ---
    function updateVariationPreview() {
        var generateSection = document.getElementById('generateSection');
        var preview = document.getElementById('variationPreview');
        if (!generateSection || !preview) return;
        
        var attrValues = {};
        document.querySelectorAll('.value-checkbox:checked').forEach(function(cb) {
            var attrId = cb.dataset.attrId;
            var valueId = cb.dataset.valueId;
            var colorCode = cb.dataset.colorCode || null;
            if (!attrValues[attrId]) attrValues[attrId] = [];
            var labelEl = cb.nextElementSibling;
            attrValues[attrId].push({
                id: valueId,
                label: labelEl ? labelEl.textContent.trim() : '',
                colorCode: colorCode
            });
        });
        
        var attrIds = Object.keys(attrValues);
        if (attrIds.length === 0) {
            generateSection.style.display = 'none';
            return;
        }
        
        generateSection.style.display = 'block';
        
        var combinations = [[]];
        attrIds.forEach(function(attrId) {
            var newCombos = [];
            combinations.forEach(function(combo) {
                attrValues[attrId].forEach(function(val) {
                    newCombos.push(combo.concat([{label: val.label, colorCode: val.colorCode}]));
                });
            });
            combinations = newCombos;
        });
        
        var html = '<div class="variation-preview-count">' + combinations.length + ' variations will be created:</div>';
        html += '<div class="variation-preview-list">';
        combinations.slice(0, 20).forEach(function(combo) {
            var itemHtml = combo.map(function(c) {
                if (c.colorCode) {
                    return '<span class="var-chip"><span class="dot" style="background:' + c.colorCode + '"></span>' + c.label + '</span>';
                }
                return c.label;
            }).join(' / ');
            html += '<span class="variation-preview-item">' + itemHtml + '</span>';
        });
        if (combinations.length > 20) {
            html += '<span class="variation-preview-item">... and ' + (combinations.length - 20) + ' more</span>';
        }
        html += '</div>';
        preview.innerHTML = html;
        updateHiddenInputs();
    }
    window.updateVariationPreview = updateVariationPreview;
    
    // --- Update Hidden Inputs ---
    function updateHiddenInputs() {
        var container = document.getElementById('variationHiddenInputs');
        if (!container) return;
        container.innerHTML = '';
        
        document.querySelectorAll('.value-checkbox:checked').forEach(function(cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'variation_values[]';
            input.value = cb.dataset.attrId + ':' + cb.dataset.valueId;
            container.appendChild(input);
        });
        
        Object.keys(productAttributes).forEach(function(attrId) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'attributes[]';
            input.value = attrId;
            container.appendChild(input);
        });
    }
    
    // --- Add Attribute Card ---
    function addAttributeCard(attrId, attrName, attrType, values, isPreSelected) {
        productAttributes[attrId] = { name: attrName, type: attrType, values: values, selectedValues: [] };
        
        var container = document.getElementById('selectedAttributesContainer');
        if (!container) return;
        
        var card = document.createElement('div');
        card.className = 'selected-attr-card';
        card.id = 'attr-card-' + attrId;
        
        var html = '<div class="selected-attr-header">';
        html += '<span class="selected-attr-title">' + attrName + ' <span class="type-badge">' + attrType + '</span></span>';
        html += '<button type="button" class="selected-attr-remove" onclick="removeAttributeFromProduct(' + attrId + ')" title="Remove">';
        html += '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
        html += '</button></div>';
        
        html += '<div class="selected-attr-values" id="attr-values-' + attrId + '">';
        
        if (values && values.length > 0) {
            // All attributes (including color) use same simple checkbox layout
            values.forEach(function(v) {
                var isChecked = !isPreSelected || selectedValueIds.indexOf(v.id) !== -1;
                var colorData = (attrType === 'color' && v.color_code) ? ' data-color-code="' + v.color_code + '"' : '';
                html += '<input type="checkbox" class="value-checkbox" id="val-' + v.id + '" data-attr-id="' + attrId + '" data-value-id="' + v.id + '"' + colorData + ' ' + (isChecked ? 'checked' : '') + ' onchange="updateVariationPreview()">';
                html += '<label class="value-chip-label" for="val-' + v.id + '">';
                if (attrType === 'color' && v.color_code) {
                    html += '<span class="color-dot" style="background:' + v.color_code + ';"></span>';
                }
                html += v.value + '</label>';
            });
        }
        
        html += '<button type="button" class="add-value-btn" onclick="openQuickValueModal(' + attrId + ', \'' + attrName.replace(/'/g, "\\'") + '\', \'' + attrType + '\')">';
        html += '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
        html += 'Add ' + attrName + '</button></div>';
        
        card.innerHTML = html;
        container.appendChild(card);
        
        updateVariationPreview();
        updateHiddenInputs();
    }
    
    // --- Add/Remove Attribute ---
    window.addAttributeToProduct = function(btn) {
        var attrId = btn.dataset.attrId;
        var attrName = btn.dataset.attrName;
        var attrType = btn.dataset.attrType;
        var attrValues = JSON.parse(btn.dataset.attrValues || '[]');
        
        if (productAttributes[attrId]) {
            alert(attrName + ' is already added!');
            return;
        }
        
        addAttributeCard(attrId, attrName, attrType, attrValues, false);
        btn.classList.add('added');
    };
    
    window.removeAttributeFromProduct = function(attrId) {
        delete productAttributes[attrId];
        var card = document.getElementById('attr-card-' + attrId);
        if (card) card.remove();
        var btn = document.querySelector('.attr-add-btn[data-attr-id="' + attrId + '"]');
        if (btn) btn.classList.remove('added');
        updateVariationPreview();
        updateHiddenInputs();
    };
    
    // --- Quick Add Modals ---
    window.openQuickAttributeModal = function() {
        document.getElementById('quickAttrName').value = '';
        document.getElementById('quickAttrType').value = 'select';
        document.getElementById('quickAttrModal').classList.add('show');
        document.getElementById('quickAttrName').focus();
    };
    
    window.closeQuickAttrModal = function() {
        document.getElementById('quickAttrModal').classList.remove('show');
    };
    
    window.saveQuickAttribute = function() {
        var name = document.getElementById('quickAttrName').value.trim();
        var type = document.getElementById('quickAttrType').value;
        if (!name) { alert('Please enter attribute name'); return; }
        
        fetch('{{ route("inventory.settings.attributes.quick-add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name, type: type })
        })
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var attr = result.attribute;
                addAttributeCard(attr.id, attr.name, attr.type, attr.values || [], false);
                closeQuickAttrModal();
            } else {
                alert(result.message || 'Error creating attribute');
            }
        })
        .catch(function(err) { alert('Error: ' + err); });
    };
    
    window.openQuickValueModal = function(attrId, attrName, attrType) {
        document.getElementById('quickValueModalTitle').textContent = 'Add ' + attrName + ' Value';
        document.getElementById('quickValueAttrId').value = attrId;
        document.getElementById('quickValueAttrType').value = attrType;
        document.getElementById('quickValueName').value = '';
        document.getElementById('quickValueColorCode').value = '';
        document.getElementById('quickValueColorPicker').value = '#3b82f6';
        document.getElementById('quickValueColorGroup').style.display = (attrType === 'color') ? 'block' : 'none';
        document.getElementById('quickValueModal').classList.add('show');
        document.getElementById('quickValueName').focus();
    };
    
    window.closeQuickValueModal = function() {
        document.getElementById('quickValueModal').classList.remove('show');
    };
    
    window.saveQuickValue = function() {
        var attrId = document.getElementById('quickValueAttrId').value;
        var attrType = document.getElementById('quickValueAttrType').value;
        var value = document.getElementById('quickValueName').value.trim();
        var colorCode = document.getElementById('quickValueColorCode').value.trim();
        if (!value) { alert('Please enter a value'); return; }
        
        fetch('{{ route("inventory.settings.attribute-values.quick-add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ attribute_id: attrId, value: value, color_code: attrType === 'color' ? colorCode : null })
        })
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var v = result.value;
                var container = document.getElementById('attr-values-' + attrId);
                var addBtn = container.querySelector('.add-value-btn');
                
                var checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'value-checkbox';
                checkbox.id = 'val-' + v.id;
                checkbox.dataset.attrId = attrId;
                checkbox.dataset.valueId = v.id;
                checkbox.checked = true;
                checkbox.onchange = updateVariationPreview;
                
                var label = document.createElement('label');
                label.className = 'value-chip-label';
                label.htmlFor = 'val-' + v.id;
                if (attrType === 'color' && v.color_code) {
                    label.innerHTML = '<span class="color-dot" style="background:' + v.color_code + ';"></span>' + v.value;
                } else {
                    label.textContent = v.value;
                }
                
                container.insertBefore(label, addBtn);
                container.insertBefore(checkbox, label);
                closeQuickValueModal();
                updateVariationPreview();
            } else {
                alert(result.message || 'Error adding value');
            }
        })
        .catch(function(err) { alert('Error: ' + err); });
    };
    
    // --- Tags ---
    function renderTags() {
        var wrapper = document.getElementById('tagsWrapper');
        var input = document.getElementById('tagsInput');
        if (!wrapper || !input) return;
        wrapper.querySelectorAll('.tag-item').forEach(function(t){ t.remove(); });
        tags.forEach(function(tag, i) {
            var span = document.createElement('span');
            span.className = 'tag-item';
            span.innerHTML = tag + '<button type="button" onclick="removeTag('+i+')">&times;</button>';
            wrapper.insertBefore(span, input);
        });
        var hidden = document.getElementById('tagsHidden');
        if (hidden) hidden.value = tags.join(', ');
    }
    window.renderTags = renderTags;
    window.removeTag = function(i) { tags.splice(i, 1); renderTags(); };
    
    // --- Images ---
    window.setPrimaryImage = function(imageId) {
        document.getElementById('primaryImageId').value = imageId;
        // Update enhanced image cards
        document.querySelectorAll('.image-card-enhanced').forEach(function(item) {
            item.classList.remove('is-primary');
            var ribbon = item.querySelector('.primary-ribbon');
            if (ribbon) ribbon.remove();
            var btn = item.querySelector('.star-btn');
            if (btn) {
                btn.classList.remove('active');
                var svg = btn.querySelector('svg');
                if (svg) svg.setAttribute('fill', 'none');
            }
        });
        var item = document.getElementById('existing-' + imageId);
        if (item) {
            item.classList.add('is-primary');
            // Add ribbon if not exists
            var preview = item.querySelector('.image-card-preview');
            if (preview && !preview.querySelector('.primary-ribbon')) {
                var ribbon = document.createElement('span');
                ribbon.className = 'primary-ribbon';
                ribbon.textContent = 'Primary';
                preview.appendChild(ribbon);
            }
            var btn = item.querySelector('.star-btn');
            if (btn) {
                btn.classList.add('active');
                var svg = btn.querySelector('svg');
                if (svg) svg.setAttribute('fill', 'currentColor');
            }
        }
    };
    window.setExistingPrimary = window.setPrimaryImage; // Alias
    
    window.markForDelete = function(imageId) {
        if (!confirm('Delete this image?')) return;
        var item = document.getElementById('existing-' + imageId);
        if (item) item.style.display = 'none';
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.value = imageId;
        document.getElementById('deleteInputsContainer').appendChild(input);
    };
    
    // --- Price Calc ---
    function calcPrice() {
        var ppEl = document.getElementById('purchasePrice');
        var prEl = document.getElementById('profitRate');
        var calcEl = document.getElementById('calculatedSale');
        var spEl = document.getElementById('salePrice');
        var profitAmtEl = document.getElementById('profitAmount');
        var profitMarginEl = document.getElementById('profitMargin');
        
        if (!ppEl || !prEl || !calcEl) return;
        
        var purchasePrice = parseFloat(ppEl.value) || 0;
        var profitRate = parseFloat(prEl.value) || 0;
        var salePrice = parseFloat(spEl ? spEl.value : 0) || 0;
        
        // Calculate sale price from profit rate
        if (purchasePrice > 0 && profitRate > 0) {
            var calculated = purchasePrice * (1 + profitRate / 100);
            calcEl.value = calculated.toFixed(2);
        } else {
            calcEl.value = '--';
        }
        
        // Update profit summary based on actual sale price
        if (purchasePrice > 0 && salePrice > 0) {
            var profitAmt = salePrice - purchasePrice;
            var margin = ((salePrice - purchasePrice) / purchasePrice) * 100;
            
            if (profitAmtEl) {
                profitAmtEl.textContent = '₹' + profitAmt.toFixed(2);
                profitAmtEl.className = 'profit-value ' + (profitAmt >= 0 ? 'positive' : 'negative');
            }
            if (profitMarginEl) {
                profitMarginEl.textContent = margin.toFixed(1) + '%';
                profitMarginEl.className = 'profit-value ' + (margin >= 0 ? 'positive' : 'negative');
            }
        } else {
            if (profitAmtEl) { profitAmtEl.textContent = '₹0.00'; profitAmtEl.className = 'profit-value'; }
            if (profitMarginEl) { profitMarginEl.textContent = '0%'; profitMarginEl.className = 'profit-value'; }
        }
    }
    window.calcPrice = calcPrice;
    
    // Calculate profit rate from sale price
    function calcProfitFromSale() {
        var ppEl = document.getElementById('purchasePrice');
        var spEl = document.getElementById('salePrice');
        var prEl = document.getElementById('profitRate');
        
        if (!ppEl || !spEl || !prEl) return;
        
        var purchasePrice = parseFloat(ppEl.value) || 0;
        var salePrice = parseFloat(spEl.value) || 0;
        
        // Only auto-calculate if profit rate is empty and we have both prices
        if (purchasePrice > 0 && salePrice > 0 && !prEl.value) {
            var rate = ((salePrice - purchasePrice) / purchasePrice) * 100;
            prEl.value = rate.toFixed(2);
        }
        
        calcPrice();
    }
    
    window.applyCalc = function() {
        var ppEl = document.getElementById('purchasePrice');
        var prEl = document.getElementById('profitRate');
        var spEl = document.getElementById('salePrice');
        
        if (!ppEl || !prEl || !spEl) return;
        
        var purchasePrice = parseFloat(ppEl.value) || 0;
        var profitRate = parseFloat(prEl.value) || 0;
        
        if (purchasePrice > 0 && profitRate > 0) {
            var calculated = purchasePrice * (1 + profitRate / 100);
            spEl.value = calculated.toFixed(2);
            calcPrice(); // Update summary
        }
    };
    
    // --- Units ---
    window.addUnitRow = function() {
        var tbody = document.getElementById('productUnitsBody');
        if (!tbody) return;
        var row = document.createElement('tr');
        row.innerHTML = '<td><select name="product_units['+unitIdx+'][unit_id]" class="form-control" required><option value="">Select</option>@foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->short_name }}</option>@endforeach</select></td><td><input type="text" name="product_units['+unitIdx+'][unit_name]" class="form-control" placeholder="Box of 12"></td><td><input type="number" name="product_units['+unitIdx+'][conversion_factor]" class="form-control" step="0.0001" min="0.0001" placeholder="1" required></td><td><input type="number" name="product_units['+unitIdx+'][purchase_price]" class="form-control" step="0.01" min="0" placeholder="0.00"></td><td><input type="number" name="product_units['+unitIdx+'][sale_price]" class="form-control" step="0.01" min="0" placeholder="0.00"></td><td><input type="text" name="product_units['+unitIdx+'][barcode]" class="form-control" placeholder="Barcode"></td><td class="checkbox-cell"><input type="checkbox" name="product_units['+unitIdx+'][is_purchase_unit]" value="1"></td><td class="checkbox-cell"><input type="checkbox" name="product_units['+unitIdx+'][is_sale_unit]" value="1"></td><td><button type="button" class="btn-icon-danger" onclick="this.closest(\'tr\').remove()">×</button></td>';
        tbody.appendChild(row);
        unitIdx++;
    };
    
    // ==========================================
    // INITIALIZATION (after all functions defined)
    // ==========================================
    
    // --- Flag Chips ---
    document.querySelectorAll('.flag-chip').forEach(function(chip) {
        var checkbox = chip.querySelector('input[type="checkbox"]');
        if (!checkbox) return;
        function updateChipUI() {
            chip.classList.toggle('active', checkbox.checked);
            if (checkbox.id === 'hasVariantsCheckbox') {
                var variationsTab = document.getElementById('variationsTab');
                if (variationsTab) variationsTab.style.display = checkbox.checked ? '' : 'none';
            }
        }
        updateChipUI();
        checkbox.addEventListener('change', updateChipUI);
    });
    
    // --- TomSelect ---
    document.querySelectorAll('.searchable-select').forEach(function(el) {
        new TomSelect(el, { plugins: ['dropdown_input'], create: false, allowEmptyOption: true, maxOptions: 100 });
    });
    
    // --- Initialize Pre-selected Attributes ---
    if (preSelectedAttrs && preSelectedAttrs.length > 0) {
        preSelectedAttrs.forEach(function(attr) {
            addAttributeCard(attr.id, attr.name, attr.type, attr.values, true);
        });
    }
    
    // --- Modal Close on Overlay ---
    document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
    });
    
    // --- Color Picker Sync ---
    var colorPicker = document.getElementById('quickValueColorPicker');
    var colorCodeInput = document.getElementById('quickValueColorCode');
    if (colorPicker && colorCodeInput) {
        colorPicker.addEventListener('input', function() { colorCodeInput.value = this.value; });
        colorCodeInput.addEventListener('input', function() { if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) colorPicker.value = this.value; });
    }
    
    // --- Tabs ---
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tab = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c){ c.classList.remove('active'); });
            this.classList.add('active');
            var content = document.getElementById('tab-' + tab);
            if (content) content.classList.add('active');
        });
    });
    
    // --- Tags Init ---
    var tagsHidden = document.getElementById('tagsHidden');
    if (tagsHidden && tagsHidden.value) {
        tags = tagsHidden.value.split(',').map(function(t){ return t.trim(); }).filter(Boolean);
        renderTags();
    }
    var tagsInput = document.getElementById('tagsInput');
    if (tagsInput) {
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                var val = this.value.trim().replace(',','');
                if (val && tags.indexOf(val) === -1) { tags.push(val); renderTags(); }
                this.value = '';
            }
            if (e.key === 'Backspace' && !this.value && tags.length) { tags.pop(); renderTags(); }
        });
    }
    
    // --- Image Input & Drag-Drop ---
    var newImageFiles = [];
    var newImageColors = []; // Track color assignment for each new image
    var imageInput = document.getElementById('imageInput');
    var dropZone = document.getElementById('imageDropZone');
    
    // Get selected colors from attribute checkboxes
    function getSelectedColors() {
        var colors = [];
        document.querySelectorAll('.value-checkbox:checked').forEach(function(cb) {
            var colorCode = cb.dataset.colorCode;
            if (colorCode) {
                var labelEl = cb.nextElementSibling;
                colors.push({
                    id: cb.dataset.valueId,
                    name: labelEl ? labelEl.textContent.trim() : '',
                    code: colorCode
                });
            }
        });
        return colors;
    }
    
    // Click to browse - SINGLE handler only
    if (dropZone) {
        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
            dropZone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });
        
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropZone.addEventListener(eventName, function() {
                dropZone.classList.add('drag-over');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(function(eventName) {
            dropZone.addEventListener(eventName, function() {
                dropZone.classList.remove('drag-over');
            }, false);
        });
        
        // Single click handler for file browser
        dropZone.addEventListener('click', function(e) {
            e.stopPropagation();
            if (imageInput) imageInput.click();
        });
        
        dropZone.addEventListener('drop', function(e) {
            var files = e.dataTransfer.files;
            if (files.length) addNewImageFiles(files);
        }, false);
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files.length) addNewImageFiles(this.files);
        });
    }
    
    function addNewImageFiles(files) {
        Array.from(files).forEach(function(file) {
            if (!file.type.startsWith('image/')) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('File "' + file.name + '" is too large. Max 5MB.');
                return;
            }
            newImageFiles.push(file);
            newImageColors.push('');
        });
        renderNewImagePreviews();
        updateNewImageInput();
    }
    
    window.setNewImageColor = function(idx, colorId) {
        newImageColors[idx] = colorId;
        renderNewImagePreviews();
        updateNewImageInput();
    };
    
    function renderNewImagePreviews() {
        var grid = document.getElementById('newImagePreviewGrid');
        if (!grid) return;
        grid.innerHTML = '';
        
        var info = document.getElementById('uploadInfo');
        var count = document.getElementById('uploadCount');
        var colorHint = document.getElementById('colorHint');
        
        if (newImageFiles.length === 0) {
            if (info) info.style.display = 'none';
            if (colorHint) colorHint.style.display = 'none';
            updateHeaderCount();
            return;
        }
        
        if (info) info.style.display = 'block';
        if (count) count.textContent = newImageFiles.length;
        
        // Hide color hint - colors assigned after save in Images tab
        if (colorHint) colorHint.style.display = 'none';
        
        newImageFiles.forEach(function(file, idx) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var sizeKB = Math.round(file.size / 1024);
                var sizeStr = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + 'MB' : sizeKB + 'KB';
                
                var card = document.createElement('div');
                card.className = 'image-card-enhanced';
                card.innerHTML = 
                    '<div class="image-card-preview">' +
                        '<img src="' + e.target.result + '" alt="' + file.name + '">' +
                        '<span class="primary-ribbon" style="background:#10b981;">NEW</span>' +
                        '<button type="button" class="btn-remove-new" onclick="removeNewImage(' + idx + ')" title="Remove">✕</button>' +
                    '</div>' +
                    '<div class="image-card-footer">' +
                        '<div style="font-size:11px;color:var(--text-muted);text-align:center;padding:6px 0;">' +
                            file.name.substring(0, 20) + (file.name.length > 20 ? '...' : '') + ' (' + sizeStr + ')' +
                        '</div>' +
                    '</div>';
                grid.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
        
        updateHeaderCount();
    }
    
    function updateHeaderCount() {
        var imageCount = document.getElementById('imageCount');
        if (imageCount) {
            var existingCount = {{ $product->images->count() }};
            imageCount.textContent = existingCount + newImageFiles.length;
            imageCount.style.display = 'flex';
        }
    }
    
    window.removeNewImage = function(idx) {
        newImageFiles.splice(idx, 1);
        newImageColors.splice(idx, 1);
        renderNewImagePreviews();
        updateNewImageInput();
    };
    
    window.clearNewImages = function() {
        newImageFiles = [];
        newImageColors = [];
        renderNewImagePreviews();
        updateNewImageInput();
    };
    
    function updateNewImageInput() {
        try {
            var dt = new DataTransfer();
            newImageFiles.forEach(function(file) {
                dt.items.add(file);
            });
            if (imageInput) imageInput.files = dt.files;
        } catch (e) {
            console.log('DataTransfer not supported');
        }
        
        var colorsInput = document.getElementById('imageColorsInput');
        if (!colorsInput) {
            colorsInput = document.createElement('input');
            colorsInput.type = 'hidden';
            colorsInput.name = 'image_colors';
            colorsInput.id = 'imageColorsInput';
            if (imageInput && imageInput.parentNode) {
                imageInput.parentNode.insertBefore(colorsInput, imageInput.nextSibling);
            }
        }
        colorsInput.value = JSON.stringify(newImageColors);
    }
    
    // --- Color Preview Update ---
    window.updateColorPreview = function(select) {
        var option = select.options[select.selectedIndex];
        var colorCode = option.dataset.color || '';
        var wrapper = select.closest('.color-select-wrapper');
        var preview = wrapper.querySelector('.color-swatch-preview');
        
        if (preview) {
            if (colorCode) {
                preview.innerHTML = '<span class="color-swatch" style="background:' + colorCode + ';"></span>';
            } else {
                preview.innerHTML = '';
            }
        }
    };
    
    // --- Price Calc Init ---
    var ppEl = document.getElementById('purchasePrice');
    var prEl = document.getElementById('profitRate');
    var spEl = document.getElementById('salePrice');
    
    if (ppEl) ppEl.addEventListener('input', calcPrice);
    if (prEl) prEl.addEventListener('input', calcPrice);
    if (spEl) spEl.addEventListener('input', calcPrice);
    
    // Initial calculation
    calcPrice();
    
    // Auto-calculate profit rate on load if not set
    if (ppEl && spEl && prEl && !prEl.value) {
        var pp = parseFloat(ppEl.value) || 0;
        var sp = parseFloat(spEl.value) || 0;
        if (pp > 0 && sp > 0) {
            var rate = ((sp - pp) / pp) * 100;
            prEl.value = rate.toFixed(2);
            calcPrice();
        }
    }
    
    // --- Barcode Init ---
    var barcodeInput = document.getElementById('barcodeInput');
    var barcodeType = document.getElementById('barcodeType');
    
    if (barcodeInput) {
        var debounceTimer;
        barcodeInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                renderBarcodePreview(barcodeInput.value, barcodeType ? barcodeType.value : 'CODE128');
            }, 500);
        });
        
        // Initial render if barcode exists
        if (barcodeInput.value) {
            // Auto-detect type based on format
            if (/^\d{13}$/.test(barcodeInput.value)) {
                if (barcodeType) barcodeType.value = 'EAN13';
                renderBarcodePreview(barcodeInput.value, 'EAN13');
            } else if (/^\d{8}$/.test(barcodeInput.value)) {
                if (barcodeType) barcodeType.value = 'EAN8';
                renderBarcodePreview(barcodeInput.value, 'EAN8');
            } else {
                renderBarcodePreview(barcodeInput.value, 'CODE128');
            }
        }
    }
    
    if (barcodeType) {
        barcodeType.addEventListener('change', function() {
            if (barcodeInput && barcodeInput.value) {
                renderBarcodePreview(barcodeInput.value, barcodeType.value);
            }
        });
    }
    
    // --- SKU Validation ---
    var skuInput = document.getElementById('skuInput');
    var skuStatus = document.getElementById('skuStatus');
    var skuTimeout = null;
    var originalSku = skuInput ? skuInput.value : '';
    
    if (skuInput) {
        skuInput.addEventListener('input', function() {
            clearTimeout(skuTimeout);
            var sku = this.value.trim();
            
            if (!sku) {
                skuStatus.innerHTML = '';
                skuStatus.className = 'sku-status';
                return;
            }
            
            // Don't check if it's the same as original
            if (sku === originalSku) {
                skuStatus.innerHTML = '✅ Current SKU';
                skuStatus.className = 'sku-status valid';
                return;
            }
            
            skuStatus.innerHTML = '⏳ Checking...';
            skuStatus.className = 'sku-status checking';
            
            skuTimeout = setTimeout(function() {
                checkSkuAvailability(sku);
            }, 500);
        });
    }
    
    function checkSkuAvailability(sku) {
        fetch('{{ route("inventory.sku.check") }}?sku=' + encodeURIComponent(sku) + '&product_id={{ $product->id }}')
            .then(r => r.json())
            .then(data => {
                if (data.valid) {
                    skuStatus.innerHTML = '✅ SKU available';
                    skuStatus.className = 'sku-status valid';
                } else {
                    skuStatus.innerHTML = '❌ ' + data.message;
                    skuStatus.className = 'sku-status invalid';
                }
            })
            .catch(err => {
                skuStatus.innerHTML = '';
                skuStatus.className = 'sku-status';
            });
    }
});
</script>
