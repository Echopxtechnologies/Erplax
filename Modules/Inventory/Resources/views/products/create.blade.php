

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* ============================================
       PRODUCT CREATE FORM - Dark/Light Mode Ready
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
        border-radius: 8px 0 0 8px;
    }
    .btn-sku-gen {
        padding: 10px 14px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-left: none;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    .btn-sku-gen:hover { background: #dbeafe; color: #2563eb; }
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
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); 
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
        background: var(--body-bg);
    }
    .image-item.primary { border-color: #3b82f6; }
    .image-item img { width: 100%; height: 100%; object-fit: cover; }
    .image-item .image-controls {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 6px;
        display: flex;
        justify-content: space-between;
        background: linear-gradient(to bottom, rgba(0,0,0,0.5), transparent);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .image-item:hover .image-controls { opacity: 1; }
    .image-item .btn-img { 
        width: 26px; 
        height: 26px; 
        border-radius: 6px; 
        border: none; 
        cursor: pointer; 
        background: rgba(255,255,255,0.9); 
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 14px;
    }
    .image-item .btn-img:hover { background: #fff; }
    .image-item .btn-star { color: #f59e0b; }
    .image-item .btn-star.active { background: #fef3c7; }
    .image-item .btn-remove { color: #dc2626; }
    .image-item .btn-remove:hover { background: #fee2e2; }
    .image-item .primary-badge { 
        position: absolute; 
        bottom: 6px; 
        left: 6px; 
        background: #3b82f6; 
        color: #fff; 
        font-size: 10px; 
        padding: 2px 8px; 
        border-radius: 4px; 
        font-weight: 600; 
    }
    .image-item .image-size {
        position: absolute;
        bottom: 6px;
        right: 6px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 4px;
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
    
    .value-checkbox { display: none; }
    
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
    .value-chip-label:hover { border-color: #3b82f6; }
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
    
    /* Variation Preview Table */
    .variation-preview-table-wrapper {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        background: var(--card-bg);
    }
    .variation-preview-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .variation-preview-table th {
        position: sticky;
        top: 0;
        background: var(--body-bg);
        padding: 10px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--card-border);
        z-index: 1;
    }
    .variation-preview-table td {
        padding: 8px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
    }
    .variation-preview-table tbody tr:hover {
        background: var(--body-bg);
    }
    .variation-preview-table tbody tr.disabled {
        opacity: 0.4;
    }
    .variation-preview-table input[type="text"],
    .variation-preview-table input[type="number"] {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid var(--card-border);
        border-radius: 4px;
        font-size: 12px;
        background: var(--card-bg);
        color: var(--text-primary);
    }
    .variation-preview-table input[type="text"]:focus,
    .variation-preview-table input[type="number"]:focus {
        border-color: #3b82f6;
        outline: none;
    }
    .variation-preview-table .var-name-cell {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .variation-preview-table .var-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 11px;
    }
    .variation-preview-table .var-chip .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .variation-preview-table .barcode-cell {
        display: flex;
        gap: 4px;
    }
    .variation-preview-table .barcode-cell input {
        flex: 1;
        font-family: 'Courier New', monospace;
    }
    .variation-preview-table .btn-gen-barcode {
        padding: 6px 8px;
        background: var(--body-bg);
        border: 1px solid var(--card-border);
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
    }
    .variation-preview-table .btn-gen-barcode:hover {
        background: #dcfce7;
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
        top: 0; left: 0; right: 0; bottom: 0;
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
    .modal-header h3 { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .modal-close {
        background: none; border: none; font-size: 24px;
        color: var(--text-muted); cursor: pointer; line-height: 1;
    }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body { padding: 20px; }
    .modal-footer {
        display: flex; justify-content: flex-end; gap: 10px;
        padding: 16px 20px; border-top: 1px solid var(--card-border); background: var(--body-bg);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .product-header { flex-direction: column; align-items: center; text-align: center; padding: 20px; }
        .product-flags { justify-content: center; }
        .form-row { flex-direction: column; }
        .form-label { width: 100%; padding-bottom: 6px; }
        .tabs-nav { padding: 0 16px; }
        .tab-content { padding: 20px 16px; }
    }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <a href="{{ route('inventory.products.index') }}" class="btn">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <div style="flex:1;"></div>
        <button type="submit" form="productForm" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Product
        </button>
    </div>

    <form action="{{ route('inventory.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
        @csrf
        <input type="file" name="images[]" id="imageInput" multiple accept="image/*" style="display:none !important;">
        <input type="hidden" name="primary_image" id="primaryImageInput" value="0">

        <div class="form-container">
            <div class="product-header">
                <div class="image-upload-area" onclick="document.getElementById('imageInput').click();">
                    <svg id="cameraIcon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                    </svg>
                    <img id="mainPreview" src="" alt="" style="display:none;">
                    <span class="image-count" id="imageCount" style="display:none;">0</span>
                </div>

                <div class="product-main">
                    <input type="text" name="name" class="product-name-input" placeholder="Enter Product Name" value="{{ old('name') }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="product-flags">
                        @php
                            $canBeSold = old('can_be_sold', true);
                            $canBePurchased = old('can_be_purchased', true);
                            $trackInventory = old('track_inventory', true);
                            $hasVariants = old('has_variants', false);
                        @endphp
                        <label class="flag-chip {{ $canBeSold ? 'active' : '' }}">
                            <input type="hidden" name="can_be_sold" value="0">
                            <input type="checkbox" name="can_be_sold" value="1" {{ $canBeSold ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Sell
                        </label>
                        <label class="flag-chip {{ $canBePurchased ? 'active' : '' }}">
                            <input type="hidden" name="can_be_purchased" value="0">
                            <input type="checkbox" name="can_be_purchased" value="1" {{ $canBePurchased ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Purchase
                        </label>
                        <label class="flag-chip {{ $trackInventory ? 'active' : '' }}">
                            <input type="hidden" name="track_inventory" value="0">
                            <input type="checkbox" name="track_inventory" value="1" {{ $trackInventory ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Track Stock
                        </label>
                        <label class="flag-chip {{ $hasVariants ? 'active' : '' }}" id="variantChip">
                            <input type="hidden" name="has_variants" value="0">
                            <input type="checkbox" name="has_variants" id="hasVariantsCheckbox" value="1" {{ $hasVariants ? 'checked' : '' }}>
                            <span class="check-icon"><svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            Variants
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
                                    <input type="text" name="sku" id="skuInput" class="form-control" placeholder="e.g., PRD-001" value="{{ old('sku') }}" required>
                                    <button type="button" class="btn-sku-gen" onclick="generateUniqueSku()" title="Generate SKU">🔄</button>
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
                                    <input type="text" name="barcode" id="barcodeInput" class="form-control" placeholder="Enter or generate barcode" value="{{ old('barcode') }}">
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
                                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Brand</label>
                            <div class="form-value">
                                <select name="brand_id" class="form-control searchable-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)<option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">HSN Code</label>
                            <div class="form-value"><input type="text" name="hsn_code" class="form-control" placeholder="e.g., 8471" value="{{ old('hsn_code') }}"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Base Unit <span class="required">*</span></label>
                            <div class="form-value">
                                <select name="unit_id" class="form-control searchable-select" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)<option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }} {{ $unit->short_name == 'PCS' && !old('unit_id') ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Short Description</label>
                            <div class="form-value"><input type="text" name="short_description" class="form-control" placeholder="Brief description" value="{{ old('short_description') }}" maxlength="500"></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Full Description</label>
                            <div class="form-value"><textarea name="description" class="form-control" placeholder="Detailed description">{{ old('description') }}</textarea></div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Tags</label>
                            <div class="form-value">
                                <div class="tags-container" id="tagsWrapper">
                                    <input type="text" class="tags-input" id="tagsInput" placeholder="Type and press Enter">
                                </div>
                                <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags') }}">
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
                                    <input type="number" name="purchase_price" id="purchasePrice" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('purchase_price') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Sale Price <span class="required">*</span></label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="sale_price" id="salePrice" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('sale_price') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">MRP</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <span class="input-prefix">₹</span>
                                    <input type="number" name="mrp" id="mrpPrice" class="form-control with-prefix" step="0.01" min="0" placeholder="0.00" value="{{ old('mrp') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Profit Rate</label>
                            <div class="form-value">
                                <div class="input-group">
                                    <input type="number" name="default_profit_rate" id="profitRate" class="form-control with-suffix" step="0.01" min="0" max="100" placeholder="0" value="{{ old('default_profit_rate', '0') }}" formnovalidate>
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
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_1_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
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
                                    @foreach($taxes as $tax)<option value="{{ $tax->id }}" {{ old('tax_2_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>@endforeach
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
                    <tbody id="productUnitsBody"></tbody>
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
                                <input type="number" name="min_stock_level" class="form-control" step="0.001" min="0" placeholder="0" value="{{ old('min_stock_level', 0) }}">
                                <div class="form-hint">Alert when stock falls below</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="form-label">Max Stock Level</label>
                            <div class="form-value"><input type="number" name="max_stock_level" class="form-control" step="0.001" min="0" placeholder="0" value="{{ old('max_stock_level', 0) }}"></div>
                        </div>
                    </div>
                    <div>
                        <div class="form-row">
                            <label class="form-label">Batch Management</label>
                            <div class="form-value">
                                <label class="checkbox-label"><input type="checkbox" name="is_batch_managed" value="1" {{ old('is_batch_managed') ? 'checked' : '' }}> Enable Batch/Lot Management</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Tab -->
            <div class="tab-content" id="tab-images">
                <div class="section-title">Product Images</div>
                <p class="form-hint" style="margin-bottom: 16px;">Upload multiple images at once. Drag & drop or click to browse. First image is set as primary.</p>
                
                <div id="colorHint" style="display:none; margin-bottom: 16px; padding: 12px; background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; font-size: 13px; color: #92400e;">
                    💡 <strong>Tip:</strong> You have color variations selected. You can assign each image to a specific color below.
                </div>
                
                <div class="image-drop-zone" id="imageDropZone">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p><strong style="color:#3b82f6;">Click to browse</strong> or drag and drop images here</p>
                    <p style="font-size:12px;margin-top:8px;">PNG, JPG, WEBP up to 5MB each • Select multiple files at once</p>
                </div>
                <!-- File input is at top of form with id="imageInput" -->
                
                <div class="upload-info" id="uploadInfo" style="display:none; margin-top: 16px; padding: 12px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span><strong id="uploadCount">0</strong> image(s) selected. Click ⭐ to set primary, ✕ to remove.</span>
                        <button type="button" class="btn btn-sm" onclick="clearAllImages()" style="font-size: 11px; padding: 4px 10px;">Clear All</button>
                    </div>
                </div>
                <div class="images-grid" id="imagePreviewGrid"></div>
            </div>

            <!-- Variations Tab -->
            <div class="tab-content" id="tab-variations">
                <div class="section-title">Product Variations</div>
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
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <label class="checkbox-label" style="margin-bottom: 8px;">
                                    <input type="checkbox" name="generate_variations" id="generateVariationsCheckbox" value="1" checked> 
                                    <strong>Generate variation combinations</strong>
                                </label>
                                <p style="color: var(--text-muted); font-size: 12px; margin: 0;">All variations below will be created with their SKU, barcode, and prices</p>
                            </div>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-sm" onclick="regenerateAllBarcodes()" title="Generate new barcodes for all">
                                    🔲 Generate All Barcodes
                                </button>
                                <button type="button" class="btn btn-sm" onclick="copyBasePrices()" title="Copy base product prices to all variations">
                                    💰 Apply Base Prices
                                </button>
                            </div>
                        </div>
                        
                        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #0369a1; font-size: 13px;">
                                <span>💡</span>
                                <span>Edit SKU, barcode, or prices directly in the table below. All changes are saved when you submit the form.</span>
                            </div>
                        </div>
                        
                        <!-- Variation Preview Table -->
                        <div id="variationPreviewTable" class="variation-preview-table-wrapper">
                            <table class="variation-preview-table" id="varPreviewTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="selectAllVariations" checked onchange="toggleAllVariations(this)">
                                        </th>
                                        <th>Variation</th>
                                        <th style="width: 150px;">SKU</th>
                                        <th style="width: 180px;">Barcode</th>
                                        <th style="width: 100px;">Purchase ₹</th>
                                        <th style="width: 100px;">Sale ₹</th>
                                    </tr>
                                </thead>
                                <tbody id="variationPreviewBody">
                                    <!-- Will be populated by JS -->
                                </tbody>
                            </table>
                        </div>
                        <div id="variationCount" style="margin-top: 10px; font-size: 12px; color: var(--text-muted);"></div>
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
        // Try CODE128 as fallback
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
    
    // Check if exists
    checkBarcodeExists(code);
}

function checkBarcodeExists(code) {
    if (!code || code.length < 3) return;
    
    var statusEl = document.getElementById('barcodeStatus');
    
    fetch('{{ url("admin/inventory/barcode/check") }}/' + encodeURIComponent(code))
    .then(r => r.json())
    .then(data => {
        if (data.exists && statusEl) {
            statusEl.textContent = '⚠ Barcode already exists!';
            statusEl.className = 'barcode-status exists';
        }
    });
}

function openBarcodeScanner() {
    // Check if browser supports camera
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Camera scanning is not supported in this browser. Please enter barcode manually.');
        return;
    }
    
    // Open scanner modal
    var modal = document.getElementById('barcodeScannerModal');
    if (modal) {
        modal.style.display = 'flex';
        startBarcodeScanner();
    } else {
        alert('Scanner not available. Please enter barcode manually.');
    }
}

var scannerStream = null;
function startBarcodeScanner() {
    var video = document.getElementById('scannerVideo');
    if (!video) return;
    
    navigator.mediaDevices.getUserMedia({ 
        video: { facingMode: 'environment' } 
    })
    .then(function(stream) {
        scannerStream = stream;
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        console.error('Camera error:', err);
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

// Auto-render preview on input change
document.addEventListener('DOMContentLoaded', function() {
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
        
        // Initial render if value exists
        if (barcodeInput.value) {
            renderBarcodePreview(barcodeInput.value, barcodeType ? barcodeType.value : 'CODE128');
        }
    }
    
    if (barcodeType) {
        barcodeType.addEventListener('change', function() {
            if (barcodeInput && barcodeInput.value) {
                renderBarcodePreview(barcodeInput.value, barcodeType.value);
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // VARIABLES
    // ==========================================
    var productAttributes = {};
    var tags = [];
    var unitIdx = 0;
    
    // ==========================================
    // DEFINE ALL FUNCTIONS FIRST
    // ==========================================
    
    // --- Update Variation Preview ---
    // Store variation data for form submission
    var generatedVariations = [];
    
    function updateVariationPreview() {
        var generateSection = document.getElementById('generateSection');
        var tbody = document.getElementById('variationPreviewBody');
        var countEl = document.getElementById('variationCount');
        if (!generateSection) return;
        
        // Get selected attribute values with full info
        var attrValues = {};
        var attrInfo = {};
        document.querySelectorAll('.value-checkbox:checked').forEach(function(cb) {
            var attrId = cb.dataset.attrId;
            var valueId = cb.dataset.valueId;
            var labelEl = cb.nextElementSibling;
            var colorCode = cb.dataset.colorCode || null;
            
            if (!attrValues[attrId]) {
                attrValues[attrId] = [];
                attrInfo[attrId] = productAttributes[attrId] || {};
            }
            attrValues[attrId].push({
                id: valueId,
                label: labelEl ? labelEl.textContent.trim() : '',
                colorCode: colorCode
            });
        });
        
        var attrIds = Object.keys(attrValues);
        if (attrIds.length === 0) {
            generateSection.style.display = 'none';
            generatedVariations = [];
            return;
        }
        
        generateSection.style.display = 'block';
        
        // Generate combinations
        var combinations = [[]];
        attrIds.forEach(function(attrId) {
            var newCombos = [];
            combinations.forEach(function(combo) {
                attrValues[attrId].forEach(function(val) {
                    newCombos.push(combo.concat([{
                        attrId: attrId,
                        attrName: attrInfo[attrId].name || '',
                        attrType: attrInfo[attrId].type || 'select',
                        valueId: val.id,
                        value: val.label,
                        colorCode: val.colorCode
                    }]));
                });
            });
            combinations = newCombos;
        });
        
        // Get base SKU and prices from form
        var baseSku = document.querySelector('input[name="sku"]')?.value || 'PRD';
        var basePrice = parseFloat(document.getElementById('purchasePrice')?.value) || 0;
        var baseSalePrice = parseFloat(document.getElementById('salePrice')?.value) || 0;
        var baseTime = Date.now(); // Use same base time for all barcodes in this batch
        
        // Generate table rows
        generatedVariations = [];
        var html = '';
        
        combinations.forEach(function(combo, idx) {
            // Generate variation SKU suffix
            var skuSuffix = combo.map(function(c) {
                return c.value.substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, '');
            }).join('-');
            var varSku = baseSku + '-' + skuSuffix;
            
            // Always generate barcode (EAN-13)
            var barcode = '200' + String(baseTime + idx).slice(-7) + String(idx).padStart(2, '0') + '0';
            // Calculate check digit
            var sum = 0;
            for (var i = 0; i < 12; i++) {
                sum += parseInt(barcode[i]) * (i % 2 === 0 ? 1 : 3);
            }
            barcode = barcode.substring(0, 12) + ((10 - (sum % 10)) % 10);
            
            // Build attribute chips HTML
            var chipsHtml = combo.map(function(c) {
                if (c.colorCode) {
                    return '<span class="var-chip"><span class="dot" style="background:' + c.colorCode + '"></span>' + c.value + '</span>';
                }
                return '<span class="var-chip">' + c.value + '</span>';
            }).join('');
            
            // Store variation data
            var varData = {
                index: idx,
                enabled: true,
                attributes: combo.map(function(c) {
                    return { attr_id: c.attrId, value_id: c.valueId };
                }),
                sku: varSku,
                barcode: barcode,
                purchase_price: basePrice,
                sale_price: baseSalePrice
            };
            generatedVariations.push(varData);
            
            html += '<tr data-var-idx="' + idx + '">';
            html += '<td><input type="checkbox" class="var-enable-cb" data-idx="' + idx + '" checked onchange="toggleVariationRow(this)"></td>';
            html += '<td><div class="var-name-cell">' + chipsHtml + '</div></td>';
            html += '<td><input type="text" class="var-sku-input" data-idx="' + idx + '" value="' + varSku + '" onchange="updateVariationData(this, \'sku\')"></td>';
            html += '<td><div class="barcode-cell">';
            html += '<input type="text" class="var-barcode-input" data-idx="' + idx + '" value="' + barcode + '" onchange="updateVariationData(this, \'barcode\')" placeholder="Auto or enter">';
            html += '<button type="button" class="btn-gen-barcode" onclick="regenerateBarcode(' + idx + ')" title="Regenerate">🔄</button>';
            html += '</div></td>';
            html += '<td><input type="number" class="var-price-input" data-idx="' + idx + '" value="' + basePrice + '" step="0.01" min="0" onchange="updateVariationData(this, \'purchase_price\')"></td>';
            html += '<td><input type="number" class="var-sale-input" data-idx="' + idx + '" value="' + baseSalePrice + '" step="0.01" min="0" onchange="updateVariationData(this, \'sale_price\')"></td>';
            html += '</tr>';
        });
        
        if (tbody) tbody.innerHTML = html;
        if (countEl) countEl.textContent = combinations.length + ' variations will be created';
        
        updateHiddenInputs();
        
        // Re-render image previews to update color dropdowns
        if (typeof renderImagePreviews === 'function') {
            renderImagePreviews();
        }
    }
    window.updateVariationPreview = updateVariationPreview;
    
    // Toggle variation row
    window.toggleVariationRow = function(cb) {
        var idx = parseInt(cb.dataset.idx);
        var row = cb.closest('tr');
        if (cb.checked) {
            row.classList.remove('disabled');
            generatedVariations[idx].enabled = true;
        } else {
            row.classList.add('disabled');
            generatedVariations[idx].enabled = false;
        }
        updateHiddenInputs();
    };
    
    // Toggle all variations
    window.toggleAllVariations = function(cb) {
        document.querySelectorAll('.var-enable-cb').forEach(function(varCb) {
            varCb.checked = cb.checked;
            toggleVariationRow(varCb);
        });
    };
    
    // Update variation data
    window.updateVariationData = function(input, field) {
        var idx = parseInt(input.dataset.idx);
        if (generatedVariations[idx]) {
            generatedVariations[idx][field] = input.value;
            updateHiddenInputs();
        }
    };
    
    // Regenerate single barcode
    window.regenerateBarcode = function(idx) {
        var barcode = '200' + String(Date.now()).slice(-7) + String(idx).padStart(2, '0') + '0';
        var sum = 0;
        for (var i = 0; i < 12; i++) {
            sum += parseInt(barcode[i]) * (i % 2 === 0 ? 1 : 3);
        }
        barcode = barcode.substring(0, 12) + ((10 - (sum % 10)) % 10);
        
        var input = document.querySelector('.var-barcode-input[data-idx="' + idx + '"]');
        if (input) {
            input.value = barcode;
            generatedVariations[idx].barcode = barcode;
            updateHiddenInputs();
        }
    };
    
    // Regenerate ALL barcodes
    window.regenerateAllBarcodes = function() {
        var baseTime = Date.now();
        generatedVariations.forEach(function(varData, idx) {
            var barcode = '200' + String(baseTime + idx).slice(-7) + String(idx).padStart(2, '0') + '0';
            var sum = 0;
            for (var i = 0; i < 12; i++) {
                sum += parseInt(barcode[i]) * (i % 2 === 0 ? 1 : 3);
            }
            barcode = barcode.substring(0, 12) + ((10 - (sum % 10)) % 10);
            
            var input = document.querySelector('.var-barcode-input[data-idx="' + idx + '"]');
            if (input) {
                input.value = barcode;
                generatedVariations[idx].barcode = barcode;
            }
        });
        updateHiddenInputs();
    };
    
    // Copy base prices to all variations
    window.copyBasePrices = function() {
        var basePrice = parseFloat(document.getElementById('purchasePrice')?.value) || 0;
        var baseSalePrice = parseFloat(document.getElementById('salePrice')?.value) || 0;
        
        generatedVariations.forEach(function(varData, idx) {
            var purchaseInput = document.querySelector('.var-price-input[data-idx="' + idx + '"]');
            var saleInput = document.querySelector('.var-sale-input[data-idx="' + idx + '"]');
            
            if (purchaseInput) {
                purchaseInput.value = basePrice;
                generatedVariations[idx].purchase_price = basePrice;
            }
            if (saleInput) {
                saleInput.value = baseSalePrice;
                generatedVariations[idx].sale_price = baseSalePrice;
            }
        });
        updateHiddenInputs();
    };
    
    // --- Update Hidden Inputs ---
    function updateHiddenInputs() {
        var container = document.getElementById('variationHiddenInputs');
        if (!container) return;
        container.innerHTML = '';
        
        // Add attribute IDs
        Object.keys(productAttributes).forEach(function(attrId) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'attributes[]';
            input.value = attrId;
            container.appendChild(input);
        });
        
        // Add variation data as JSON
        var enabledVariations = generatedVariations.filter(function(v) { return v.enabled; });
        if (enabledVariations.length > 0) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'variations_data';
            input.value = JSON.stringify(enabledVariations);
            container.appendChild(input);
        }
    }
    
    // --- Add Attribute Card ---
    function addAttributeCard(attrId, attrName, attrType, values) {
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
                var colorData = (attrType === 'color' && v.color_code) ? ' data-color-code="' + v.color_code + '"' : '';
                html += '<input type="checkbox" class="value-checkbox" id="val-' + v.id + '" data-attr-id="' + attrId + '" data-value-id="' + v.id + '"' + colorData + ' checked onchange="updateVariationPreview()">';
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
        
        addAttributeCard(attrId, attrName, attrType, attrValues);
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
                addAttributeCard(attr.id, attr.name, attr.type, attr.values || []);
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
                if (attrType === 'color' && v.color_code) {
                    checkbox.dataset.colorCode = v.color_code;
                }
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
    
    // --- Price Calc ---
    function calcPrice() {
        var ppEl = document.getElementById('purchasePrice');
        var prEl = document.getElementById('profitRate');
        var calcEl = document.getElementById('calculatedSale');
        var spEl = document.getElementById('salePrice');
        var profitAmtEl = document.getElementById('profitAmount');
        var profitMarginEl = document.getElementById('profitMargin');
        
        if (!ppEl || !calcEl) return;
        
        var purchasePrice = parseFloat(ppEl.value) || 0;
        var profitRate = parseFloat(prEl ? prEl.value : 0) || 0;
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
                var tab = document.getElementById('variationsTab');
                if (tab) tab.style.display = checkbox.checked ? '' : 'none';
            }
        }
        updateChipUI();
        checkbox.addEventListener('change', updateChipUI);
    });
    
    // --- TomSelect ---
    document.querySelectorAll('.searchable-select').forEach(function(el) {
        new TomSelect(el, { plugins: ['dropdown_input'], create: false, allowEmptyOption: true, maxOptions: 100 });
    });
    
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
    
    // --- Image Upload (Simplified) ---
    var imageFiles = [];
    var imageColors = [];
    var primaryImageIndex = 0;
    var imageInput = document.getElementById('imageInput');
    var imageDropZone = document.getElementById('imageDropZone');
    var imagePreviewGrid = document.getElementById('imagePreviewGrid');
    
    // Click to browse
    if (imageDropZone) {
        imageDropZone.addEventListener('click', function() {
            if (imageInput) imageInput.click();
        });
        
        // Drag & Drop
        imageDropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        imageDropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
        });
        imageDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            var files = e.dataTransfer.files;
            if (files.length) addImageFiles(files);
        });
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files.length) addImageFiles(this.files);
        });
    }
    
    function addImageFiles(files) {
        Array.from(files).forEach(function(file) {
            if (!file.type.startsWith('image/')) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('File "' + file.name + '" is too large. Max 5MB.');
                return;
            }
            imageFiles.push(file);
            imageColors.push('');
        });
        renderImagePreviews();
    }
    
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
    
    function renderImagePreviews() {
        if (!imagePreviewGrid) return;
        imagePreviewGrid.innerHTML = '';
        
        var info = document.getElementById('uploadInfo');
        var count = document.getElementById('uploadCount');
        var colorHint = document.getElementById('colorHint');
        
        if (imageFiles.length === 0) {
            if (info) info.style.display = 'none';
            if (colorHint) colorHint.style.display = 'none';
            updateHeaderPreview();
            return;
        }
        
        if (info) info.style.display = 'block';
        if (count) count.textContent = imageFiles.length;
        
        var colors = getSelectedColors();
        if (colorHint) colorHint.style.display = colors.length > 0 ? 'block' : 'none';
        
        imageFiles.forEach(function(file, idx) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'image-item' + (idx === primaryImageIndex ? ' primary' : '');
                div.dataset.idx = idx;
                
                var sizeKB = Math.round(file.size / 1024);
                var sizeStr = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + 'MB' : sizeKB + 'KB';
                
                var colorSelectHtml = '';
                if (colors.length > 0) {
                    var currentColor = imageColors[idx] || '';
                    var currentColorObj = colors.find(function(c) { return c.id === currentColor; });
                    var dotColor = currentColorObj ? currentColorObj.code : '#ccc';
                    
                    colorSelectHtml = '<div class="image-color-select">' +
                        '<span class="color-dot" style="background:' + dotColor + '"></span>' +
                        '<select onchange="setImageColor(' + idx + ', this.value)">' +
                        '<option value="">No color</option>';
                    colors.forEach(function(c) {
                        colorSelectHtml += '<option value="' + c.id + '"' + (currentColor === c.id ? ' selected' : '') + '>' + c.name + '</option>';
                    });
                    colorSelectHtml += '</select></div>';
                }
                
                div.innerHTML = 
                    '<img src="' + e.target.result + '" alt="' + file.name + '">' +
                    '<div class="image-controls">' +
                        '<button type="button" class="btn-img btn-star' + (idx === primaryImageIndex ? ' active' : '') + '" onclick="setImagePrimary(' + idx + ')" title="Set as primary">⭐</button>' +
                        '<button type="button" class="btn-img btn-remove" onclick="removeImage(' + idx + ')" title="Remove">✕</button>' +
                    '</div>' +
                    (idx === primaryImageIndex ? '<span class="primary-badge">PRIMARY</span>' : '') +
                    colorSelectHtml +
                    (colors.length === 0 ? '<span class="image-size">' + sizeStr + '</span>' : '');
                
                imagePreviewGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        updateHeaderPreview();
    }
    
    function updateHeaderPreview() {
        var mainPreview = document.getElementById('mainPreview');
        var cameraIcon = document.getElementById('cameraIcon');
        var imageCount = document.getElementById('imageCount');
        
        if (imageFiles.length > 0) {
            var headerReader = new FileReader();
            headerReader.onload = function(e) {
                if (mainPreview) { mainPreview.src = e.target.result; mainPreview.style.display = 'block'; }
                if (cameraIcon) cameraIcon.style.display = 'none';
                if (imageCount) { imageCount.textContent = imageFiles.length; imageCount.style.display = 'flex'; }
            };
            headerReader.readAsDataURL(imageFiles[primaryImageIndex] || imageFiles[0]);
        } else {
            if (mainPreview) mainPreview.style.display = 'none';
            if (cameraIcon) cameraIcon.style.display = 'block';
            if (imageCount) imageCount.style.display = 'none';
        }
    }
    
    window.setImagePrimary = function(idx) {
        primaryImageIndex = idx;
        document.getElementById('primaryImageInput').value = idx;
        renderImagePreviews();
    };
    
    window.setImageColor = function(idx, colorId) {
        imageColors[idx] = colorId;
        updateImageColorsInput();
        renderImagePreviews();
    };
    
    window.removeImage = function(idx) {
        imageFiles.splice(idx, 1);
        imageColors.splice(idx, 1);
        if (primaryImageIndex >= imageFiles.length) {
            primaryImageIndex = Math.max(0, imageFiles.length - 1);
        } else if (primaryImageIndex > idx) {
            primaryImageIndex--;
        }
        document.getElementById('primaryImageInput').value = primaryImageIndex;
        rebuildFileInput();
        renderImagePreviews();
    };
    
    window.clearAllImages = function() {
        if (!confirm('Remove all images?')) return;
        imageFiles = [];
        imageColors = [];
        primaryImageIndex = 0;
        document.getElementById('primaryImageInput').value = 0;
        rebuildFileInput();
        renderImagePreviews();
    };
    
    function rebuildFileInput() {
        // Create new DataTransfer to update file input
        try {
            var dt = new DataTransfer();
            imageFiles.forEach(function(file) {
                dt.items.add(file);
            });
            if (imageInput) imageInput.files = dt.files;
        } catch (e) {
            // Fallback for browsers that don't support DataTransfer
            console.log('DataTransfer not supported, files will be submitted correctly anyway');
        }
        updateImageColorsInput();
    }
    
    function updateImageColorsInput() {
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
        colorsInput.value = JSON.stringify(imageColors);
    }
    
    // Form submission handler - ensure files are properly attached
    document.getElementById('productForm').addEventListener('submit', function(e) {
        // Files are already in the input from rebuildFileInput()
        updateImageColorsInput();
    });
    
    // --- Price Calc Init ---
    var ppEl = document.getElementById('purchasePrice');
    var prEl = document.getElementById('profitRate');
    var spEl = document.getElementById('salePrice');
    
    if (ppEl) ppEl.addEventListener('input', calcPrice);
    if (prEl) prEl.addEventListener('input', calcPrice);
    if (spEl) spEl.addEventListener('input', calcPrice);
    
    // Update variation preview when base SKU or prices change
    var skuEl = document.querySelector('input[name="sku"]');
    if (skuEl) skuEl.addEventListener('input', function() {
        if (typeof updateVariationPreview === 'function') updateVariationPreview();
    });
    if (ppEl) ppEl.addEventListener('change', function() {
        if (typeof updateVariationPreview === 'function') updateVariationPreview();
    });
    if (spEl) spEl.addEventListener('change', function() {
        if (typeof updateVariationPreview === 'function') updateVariationPreview();
    });
    
    // Initial calculation
    calcPrice();
    
    // --- SKU Validation ---
    var skuInput = document.getElementById('skuInput');
    var skuStatus = document.getElementById('skuStatus');
    var skuTimeout = null;
    
    if (skuInput) {
        skuInput.addEventListener('input', function() {
            clearTimeout(skuTimeout);
            var sku = this.value.trim();
            
            if (!sku) {
                skuStatus.innerHTML = '';
                skuStatus.className = 'sku-status';
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
        fetch('{{ route("inventory.sku.check") }}?sku=' + encodeURIComponent(sku))
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

// Generate unique SKU
function generateUniqueSku() {
    var nameInput = document.querySelector('input[name="name"]');
    var name = nameInput ? nameInput.value : '';
    
    fetch('{{ route("inventory.sku.generate") }}?name=' + encodeURIComponent(name))
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var skuInput = document.getElementById('skuInput');
                if (skuInput) {
                    skuInput.value = data.sku;
                    skuInput.dispatchEvent(new Event('input'));
                }
            }
        });
}
</script>
