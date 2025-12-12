<style>
/* =========================================
   DATATABLE - Clean Version
   Balanced colors, no zoom effects
   ========================================= */

/* Container */
.dt-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin: 15px 0;
    width: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* Table Wrapper for horizontal scroll */
.dt-container .dt-table-wrapper {
    overflow-x: auto;
    width: 100%;
    scrollbar-width: thin;
    scrollbar-color: #a0aec0 var(--body-bg);
}

/* Custom Scrollbar */
.dt-container .dt-table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.dt-container .dt-table-wrapper::-webkit-scrollbar-track {
    background: var(--body-bg);
    border-radius: 4px;
}

.dt-container .dt-table-wrapper::-webkit-scrollbar-thumb {
    background: #a0aec0;
    border-radius: 4px;
}

.dt-container .dt-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

/* Toolbar */
.dt-container .dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: var(--card-bg);
    border-bottom: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 12px;
}

.dt-container .dt-toolbar-left,
.dt-container .dt-toolbar-right {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

/* Search Input */
.dt-container .dt-search-input {
    padding: 8px 14px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    min-width: 220px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.dt-container .dt-search-input:focus {
    outline: none;
    border-color: #5a67d8;
    box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.15);
}

.dt-container .dt-search-input::placeholder {
    color: var(--text-muted);
}

/* Per Page Select */
.dt-container .dt-perpage-select {
    padding: 8px 12px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    cursor: pointer;
    transition: border-color 0.2s ease;
}

.dt-container .dt-perpage-select:focus {
    outline: none;
    border-color: #5a67d8;
}

/* Import Button */
.dt-container .dt-import-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: #5a67d8;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-import-btn:hover {
    background: #4c56c0;
}

/* Export Dropdown */
.dt-container .dt-export-dropdown {
    position: relative;
    display: inline-block;
}

.dt-container .dt-export-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: #38a169;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-export-btn:hover {
    background: #2f8a5a;
}

.dt-container .dt-export-btn .dt-caret {
    margin-left: 2px;
    font-size: 10px;
    transition: transform 0.2s ease;
}

.dt-container .dt-export-dropdown.open .dt-caret {
    transform: rotate(180deg);
}

.dt-container .dt-export-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 6px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-md);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    min-width: 180px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.dt-container .dt-export-dropdown.open .dt-export-menu {
    opacity: 1;
    visibility: visible;
}

.dt-container .dt-export-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    cursor: pointer;
    font-size: var(--font-sm);
    color: var(--text-primary);
    transition: background 0.15s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.dt-container .dt-export-menu-item:first-child {
    border-radius: var(--radius-md) var(--radius-md) 0 0;
}

.dt-container .dt-export-menu-item:last-child {
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}

.dt-container .dt-export-menu-item:hover {
    background: var(--body-bg);
}

.dt-container .dt-export-menu-item .dt-export-icon {
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}

.dt-container .dt-export-menu-item .dt-export-label {
    flex: 1;
    font-weight: 500;
}

.dt-container .dt-export-menu-item .dt-export-ext {
    font-size: 10px;
    color: var(--text-muted);
    background: var(--body-bg);
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 600;
    text-transform: uppercase;
}

.dt-container .dt-export-menu-divider {
    height: 1px;
    background: var(--card-border);
    margin: 6px 0;
}

/* Bulk Action Buttons */
.dt-container .dt-bulk-delete-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: #e53e3e;
    color: #fff;
    cursor: pointer;
    display: none;
    transition: background 0.2s ease;
}

.dt-container .dt-bulk-delete-btn:hover {
    background: #c53030;
}

.dt-container .dt-bulk-delete-btn.show {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: dtFadeIn 0.2s ease;
}

.dt-container .dt-bulk-export-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: #5a67d8;
    color: #fff;
    cursor: pointer;
    display: none;
    transition: background 0.2s ease;
}

.dt-container .dt-bulk-export-btn:hover {
    background: #4c56c0;
}

.dt-container .dt-bulk-export-btn.show {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: dtFadeIn 0.2s ease;
}

.dt-container .dt-selected-count {
    font-size: var(--font-sm);
    display: none;
    padding: 8px 14px;
    background: rgba(90, 103, 216, 0.1);
    color: #5a67d8;
    border-radius: var(--radius-md);
    font-weight: 600;
    border: 1px solid rgba(90, 103, 216, 0.2);
}

.dt-container .dt-selected-count.show {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: dtFadeIn 0.2s ease;
}

/* Animation */
@keyframes dtFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Table */
.dt-container .dt-table {
    width: 100%;
    min-width: 100%;
    border-collapse: collapse;
}

.dt-container .dt-table th,
.dt-container .dt-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--card-border);
    color: var(--text-primary);
    font-size: var(--font-sm);
    white-space: nowrap;
}

.dt-container .dt-table th {
    background: var(--body-bg);
    font-weight: 600;
    color: var(--text-primary);
    position: sticky;
    top: 0;
    z-index: 10;
}

.dt-container .dt-table tbody tr {
    transition: background 0.15s ease;
}

.dt-container .dt-table tbody tr:hover {
    background: var(--body-bg);
}

.dt-container .dt-table tbody tr.selected {
    background: rgba(90, 103, 216, 0.08);
    border-left: 3px solid #5a67d8;
}

.dt-container .dt-table tbody tr:last-child td {
    border-bottom: none;
}

/* Checkbox Column */
.dt-container .dt-table th.dt-checkbox-col,
.dt-container .dt-table td.dt-checkbox-col {
    width: 50px;
    min-width: 50px;
    text-align: center;
    padding: 12px;
}

.dt-container .dt-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #5a67d8;
}

/* Actions Column */
.dt-container .dt-table th.dt-actions-col,
.dt-container .dt-table td.dt-actions-col {
    width: 160px;
    min-width: 160px;
}

/* Sortable Headers */
.dt-container .dt-table th.dt-sort {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 28px;
    transition: background 0.15s ease;
}

.dt-container .dt-table th.dt-sort:hover {
    background: var(--card-border);
}

.dt-container .dt-table th.dt-sort::after {
    content: '↕';
    position: absolute;
    right: 10px;
    opacity: 0.3;
    font-size: 12px;
    color: var(--text-muted);
    transition: opacity 0.2s ease;
}

.dt-container .dt-table th.dt-sort:hover::after {
    opacity: 0.5;
}

.dt-container .dt-table th.dt-sort.asc::after {
    content: '↑';
    opacity: 1;
    color: #5a67d8;
}

.dt-container .dt-table th.dt-sort.desc::after {
    content: '↓';
    opacity: 1;
    color: #5a67d8;
}

/* Pagination */
.dt-container .dt-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: var(--card-bg);
    border-top: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 12px;
}

.dt-container .dt-info {
    color: var(--text-muted);
    font-size: var(--font-sm);
    font-weight: 500;
}

.dt-container .dt-pages {
    display: flex;
    gap: 5px;
}

.dt-container .dt-pages button {
    padding: 6px 12px;
    border: 1px solid var(--input-border);
    background: var(--input-bg);
    color: var(--text-primary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    font-weight: 500;
    transition: border-color 0.15s ease, background 0.15s ease;
}

.dt-container .dt-pages button:hover:not(:disabled) {
    background: var(--body-bg);
    border-color: #5a67d8;
}

.dt-container .dt-pages button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.dt-container .dt-pages button.active {
    background: #5a67d8;
    border-color: #5a67d8;
    color: #fff;
}

/* Badges */
.dt-container .dt-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: var(--font-xs);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: capitalize;
    letter-spacing: 0.3px;
}

.dt-container .dt-badge-active,
.dt-container .dt-badge-success,
.dt-container .dt-badge-completed {
    background: rgba(56, 161, 105, 0.12);
    color: #2f855a;
    border: 1px solid rgba(56, 161, 105, 0.2);
}

.dt-container .dt-badge-inactive,
.dt-container .dt-badge-secondary,
.dt-container .dt-badge-draft {
    background: var(--body-bg);
    color: var(--text-muted);
    border: 1px solid var(--card-border);
}

.dt-container .dt-badge-graduated,
.dt-container .dt-badge-info {
    background: rgba(90, 103, 216, 0.12);
    color: #4c56c0;
    border: 1px solid rgba(90, 103, 216, 0.2);
}

.dt-container .dt-badge-pending,
.dt-container .dt-badge-warning {
    background: rgba(221, 156, 38, 0.12);
    color: #b7791f;
    border: 1px solid rgba(221, 156, 38, 0.2);
}

.dt-container .dt-badge-cancelled,
.dt-container .dt-badge-danger,
.dt-container .dt-badge-failed {
    background: rgba(229, 62, 62, 0.12);
    color: #c53030;
    border: 1px solid rgba(229, 62, 62, 0.2);
}

/* Action Buttons */
.dt-container .dt-actions {
    display: flex;
    gap: 6px;
    flex-wrap: nowrap;
}

.dt-container .dt-btn {
    padding: 5px 10px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    white-space: nowrap;
    transition: opacity 0.15s ease;
    min-width: 50px;
}

.dt-container .dt-btn:hover {
    opacity: 0.85;
}

.dt-container .dt-btn-view {
    background: #5a67d8;
    color: #fff;
}

.dt-container .dt-btn-edit {
    background: #dd9c26;
    color: #fff;
}

.dt-container .dt-btn-delete {
    background: #e53e3e;
    color: #fff;
}

/* Loading State */
.dt-container .dt-loading {
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
    font-size: var(--font-sm);
}

.dt-container .dt-loading::before {
    content: '';
    display: block;
    width: 36px;
    height: 36px;
    margin: 0 auto 15px;
    border: 3px solid var(--card-border);
    border-top-color: #5a67d8;
    border-radius: 50%;
    animation: dtSpin 0.8s linear infinite;
}

@keyframes dtSpin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.dt-container .dt-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.dt-container .dt-empty-icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.dt-container .dt-empty-text {
    font-size: var(--font-sm);
    font-weight: 500;
}

/* =========================================
   IMPORT MODAL
   ========================================= */

.dt-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}

.dt-modal-overlay.show {
    display: flex;
    animation: dtFadeIn 0.2s ease;
}

.dt-modal {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    width: 100%;
    max-width: 520px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: dtSlideUp 0.25s ease;
}

@keyframes dtSlideUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dt-modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--card-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dt-modal-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.dt-modal-close {
    background: none;
    border: none;
    font-size: 26px;
    cursor: pointer;
    color: var(--text-muted);
    line-height: 1;
    padding: 4px;
    border-radius: var(--radius-sm);
    transition: color 0.15s ease;
}

.dt-modal-close:hover {
    color: #e53e3e;
}

.dt-modal-body {
    padding: 24px;
}

.dt-modal-footer {
    padding: 18px 24px;
    border-top: 1px solid var(--card-border);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* File Drop Zone */
.dt-file-drop {
    border: 2px dashed var(--card-border);
    border-radius: var(--radius-lg);
    padding: 50px 24px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease;
    background: var(--body-bg);
}

.dt-file-drop:hover {
    border-color: #5a67d8;
    background: rgba(90, 103, 216, 0.04);
}

.dt-file-drop.dragover {
    border-color: #5a67d8;
    background: rgba(90, 103, 216, 0.06);
}

.dt-file-drop-icon {
    font-size: 50px;
    margin-bottom: 12px;
    opacity: 0.7;
}

.dt-file-drop-text {
    color: var(--text-muted);
    font-size: var(--font-sm);
    line-height: 1.6;
}

.dt-file-drop-text strong {
    color: #5a67d8;
    font-weight: 600;
}

.dt-file-input {
    display: none;
}

.dt-file-name {
    margin-top: 15px;
    padding: 12px 16px;
    background: rgba(56, 161, 105, 0.1);
    border: 1px solid rgba(56, 161, 105, 0.2);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    display: none;
    color: #2f855a;
    font-weight: 500;
}

.dt-file-name.show {
    display: flex;
    align-items: center;
    gap: 8px;
    animation: dtFadeIn 0.2s ease;
}

.dt-template-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #5a67d8;
    font-size: var(--font-sm);
    text-decoration: none;
    margin-top: 18px;
    padding: 8px 14px;
    background: rgba(90, 103, 216, 0.08);
    border-radius: var(--radius-md);
    font-weight: 500;
    transition: background 0.15s ease;
}

.dt-template-link:hover {
    background: rgba(90, 103, 216, 0.15);
}

/* Import Results */
.dt-import-results {
    margin-top: 18px;
    padding: 14px 16px;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    display: none;
    font-weight: 500;
}

.dt-import-results.show {
    display: block;
    animation: dtFadeIn 0.2s ease;
}

.dt-import-results.success {
    background: rgba(56, 161, 105, 0.1);
    color: #2f855a;
    border: 1px solid rgba(56, 161, 105, 0.2);
}

.dt-import-results.error {
    background: rgba(229, 62, 62, 0.1);
    color: #c53030;
    border: 1px solid rgba(229, 62, 62, 0.2);
}

.dt-import-errors {
    margin-top: 12px;
    max-height: 160px;
    overflow-y: auto;
    font-size: 12px;
    font-weight: 400;
}

.dt-import-errors div {
    padding: 6px 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.dt-import-errors div:last-child {
    border-bottom: none;
}

/* Modal Buttons */
.dt-btn-cancel {
    padding: 10px 20px;
    border: 1px solid var(--card-border);
    background: var(--card-bg);
    color: var(--text-primary);
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: var(--font-sm);
    font-weight: 500;
    transition: background 0.15s ease;
}

.dt-btn-cancel:hover {
    background: var(--body-bg);
}

.dt-btn-submit {
    padding: 10px 24px;
    border: none;
    background: #5a67d8;
    color: #fff;
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: var(--font-sm);
    font-weight: 600;
    transition: background 0.15s ease;
}

.dt-btn-submit:hover:not(:disabled) {
    background: #4c56c0;
}

.dt-btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* =========================================
   RESPONSIVE
   ========================================= */

@media (max-width: 768px) {
    .dt-container .dt-toolbar {
        padding: 12px;
    }
    
    .dt-container .dt-toolbar-left,
    .dt-container .dt-toolbar-right {
        width: 100%;
        justify-content: center;
    }
    
    .dt-container .dt-search-input {
        min-width: 100%;
    }
    
    .dt-container .dt-pagination {
        flex-direction: column;
        text-align: center;
    }
    
    .dt-container .dt-table th,
    .dt-container .dt-table td {
        padding: 10px 12px;
    }
    
    .dt-modal {
        max-width: 100%;
        margin: 10px;
    }
}
</style>

<script>
/**
 * DataTable - Clean JavaScript
 * Handles: List, Search, Filter, Sort, Pagination, Export, Import
 */
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('table.dt-table').forEach(initDT);
    });

    function initDT(table){
        var route = table.dataset.route;
        if(!route){
            console.error('dt-table: data-route required');
            return;
        }

        var tableId = table.id || 'dt-' + Math.random().toString(36).substr(2, 9);
        table.id = tableId;

        // State
        var state = {
            page: 1,
            perPage: 10,
            search: '',
            sort: 'id',
            dir: 'desc',
            selected: [],
            filters: {}
        };

        // Feature flags from classes
        var hasCheckbox = table.classList.contains('dt-checkbox');
        var hasSearch = table.classList.contains('dt-search');
        var hasExport = table.classList.contains('dt-export');
        var hasImport = table.classList.contains('dt-import');
        var hasPerPage = table.classList.contains('dt-perpage');

        // Create wrapper
        var wrapper = document.createElement('div');
        wrapper.className = 'dt-container';
        table.parentNode.insertBefore(wrapper, table);

        var selectedCountEl, bulkDeleteBtn, bulkExportBtn;
        var exportDropdown = null;

        // Build toolbar
        if(hasSearch || hasExport || hasImport || hasPerPage || hasCheckbox){
            var toolbar = document.createElement('div');
            toolbar.className = 'dt-toolbar';

            var left = document.createElement('div');
            left.className = 'dt-toolbar-left';

            // Search
            if(hasSearch){
                var inp = document.createElement('input');
                inp.type = 'text';
                inp.placeholder = '🔍 Search...';
                inp.className = 'dt-search-input';
                var t;
                inp.oninput = function(){
                    clearTimeout(t);
                    t = setTimeout(function(){
                        state.search = inp.value;
                        state.page = 1;
                        load();
                    }, 300);
                };
                left.appendChild(inp);
            }

            // Selected count
            if(hasCheckbox){
                selectedCountEl = document.createElement('span');
                selectedCountEl.className = 'dt-selected-count';
                left.appendChild(selectedCountEl);
            }

            var right = document.createElement('div');
            right.className = 'dt-toolbar-right';

            // Bulk delete button
            if(hasCheckbox){
                bulkDeleteBtn = document.createElement('button');
                bulkDeleteBtn.type = 'button';
                bulkDeleteBtn.className = 'dt-bulk-delete-btn';
                bulkDeleteBtn.innerHTML = '🗑️ Delete Selected';
                bulkDeleteBtn.onclick = function(){ bulkDelete(); };
                right.appendChild(bulkDeleteBtn);

                bulkExportBtn = document.createElement('button');
                bulkExportBtn.type = 'button';
                bulkExportBtn.className = 'dt-bulk-export-btn';
                bulkExportBtn.innerHTML = '📤 Export Selected';
                bulkExportBtn.onclick = function(){ bulkExport(); };
                right.appendChild(bulkExportBtn);
            }

            // Per page select
            if(hasPerPage){
                var sel = document.createElement('select');
                sel.className = 'dt-perpage-select';
                [10, 25, 50, 100].forEach(function(n){
                    var o = document.createElement('option');
                    o.value = n;
                    o.textContent = n + ' rows';
                    sel.appendChild(o);
                });
                sel.onchange = function(){
                    state.perPage = parseInt(this.value);
                    state.page = 1;
                    load();
                };
                right.appendChild(sel);
            }

            // Import button
            if(hasImport){
                var importBtn = document.createElement('button');
                importBtn.type = 'button';
                importBtn.className = 'dt-import-btn';
                importBtn.innerHTML = '📥 Import';
                importBtn.onclick = showImportModal;
                right.appendChild(importBtn);
            }

            // Export dropdown
            if(hasExport){
                exportDropdown = document.createElement('div');
                exportDropdown.className = 'dt-export-dropdown';
                
                var exportBtn = document.createElement('button');
                exportBtn.type = 'button';
                exportBtn.className = 'dt-export-btn';
                exportBtn.innerHTML = '📤 Export <span class="dt-caret">▼</span>';
                exportBtn.onclick = function(e){
                    e.stopPropagation();
                    exportDropdown.classList.toggle('open');
                };
                
                var exportMenu = document.createElement('div');
                exportMenu.className = 'dt-export-menu';
                exportMenu.innerHTML = 
                    '<button class="dt-export-menu-item" data-format="csv">' +
                        '<span class="dt-export-icon">📊</span>' +
                        '<span class="dt-export-label">CSV File</span>' +
                        '<span class="dt-export-ext">.csv</span>' +
                    '</button>' +
                    '<button class="dt-export-menu-item" data-format="xlsx">' +
                        '<span class="dt-export-icon">📗</span>' +
                        '<span class="dt-export-label">Excel File</span>' +
                        '<span class="dt-export-ext">.xlsx</span>' +
                    '</button>' +
                    '<div class="dt-export-menu-divider"></div>' +
                    '<button class="dt-export-menu-item" data-format="pdf">' +
                        '<span class="dt-export-icon">📕</span>' +
                        '<span class="dt-export-label">PDF Document</span>' +
                        '<span class="dt-export-ext">.pdf</span>' +
                    '</button>';
                
                exportMenu.querySelectorAll('.dt-export-menu-item').forEach(function(item){
                    item.onclick = function(e){
                        e.stopPropagation();
                        var format = this.dataset.format;
                        doExport(format);
                        exportDropdown.classList.remove('open');
                    };
                });
                
                exportDropdown.appendChild(exportBtn);
                exportDropdown.appendChild(exportMenu);
                right.appendChild(exportDropdown);
                
                // Close dropdown on outside click
                document.addEventListener('click', function(){
                    if(exportDropdown) exportDropdown.classList.remove('open');
                });
            }

            toolbar.appendChild(left);
            toolbar.appendChild(right);
            wrapper.appendChild(toolbar);
        }

        // Table wrapper for horizontal scroll
        var tableWrapper = document.createElement('div');
        tableWrapper.className = 'dt-table-wrapper';
        tableWrapper.appendChild(table);
        wrapper.appendChild(tableWrapper);

        // Add checkbox header
        if(hasCheckbox){
            var thead = table.querySelector('thead tr');
            var checkTh = document.createElement('th');
            checkTh.className = 'dt-checkbox-col';
            checkTh.innerHTML = '<input type="checkbox" class="dt-checkbox dt-check-all" title="Select All">';
            thead.insertBefore(checkTh, thead.firstChild);

            checkTh.querySelector('.dt-check-all').onchange = function(){
                var checked = this.checked;
                table.querySelectorAll('tbody .dt-row-check').forEach(function(cb){
                    cb.checked = checked;
                    var id = parseInt(cb.dataset.id);
                    var tr = cb.closest('tr');
                    if(checked){
                        if(state.selected.indexOf(id) === -1) state.selected.push(id);
                        tr.classList.add('selected');
                    } else {
                        state.selected = state.selected.filter(function(x){ return x !== id; });
                        tr.classList.remove('selected');
                    }
                });
                updateBulkUI();
            };
        }

        // Mark actions column
        table.querySelectorAll('thead th').forEach(function(th){
            if(th.dataset.render === 'actions'){
                th.classList.add('dt-actions-col');
            }
        });

        // Pagination
        var pag = document.createElement('div');
        pag.className = 'dt-pagination';
        pag.innerHTML = '<span class="dt-info"></span><div class="dt-pages"></div>';
        wrapper.appendChild(pag);

        var tbody = table.querySelector('tbody') || table.createTBody();
        var infoEl = pag.querySelector('.dt-info');
        var pagesEl = pag.querySelector('.dt-pages');

        // Parse columns
        var cols = [];
        table.querySelectorAll('thead th:not(.dt-checkbox-col)').forEach(function(th){
            cols.push({
                col: th.dataset.col || null,
                render: th.dataset.render || null
            });
            
            // Sortable
            if(th.classList.contains('dt-sort') && th.dataset.col){
                th.onclick = function(){
                    var c = this.dataset.col;
                    if(state.sort === c){
                        state.dir = state.dir === 'asc' ? 'desc' : 'asc';
                    } else {
                        state.sort = c;
                        state.dir = 'asc';
                    }
                    table.querySelectorAll('th.dt-sort').forEach(function(h){
                        h.classList.remove('asc', 'desc');
                    });
                    this.classList.add(state.dir);
                    load();
                };
            }
        });

        // External filters
        document.querySelectorAll('[data-dt-filter]').forEach(function(el){
            var targetTable = el.dataset.dtTable;
            if(!targetTable || targetTable === tableId){
                var column = el.dataset.dtFilter;
                var tagName = el.tagName.toUpperCase();
                var inputType = el.type || '';
                
                if(tagName === 'SELECT' || inputType === 'date'){
                    el.addEventListener('change', function(){
                        state.filters[column] = this.value;
                        state.page = 1;
                        load();
                    });
                } else {
                    var filterTimer;
                    el.addEventListener('input', function(){
                        var val = this.value;
                        clearTimeout(filterTimer);
                        filterTimer = setTimeout(function(){
                            state.filters[column] = val;
                            state.page = 1;
                            load();
                        }, 300);
                    });
                }
            }
        });

        function updateBulkUI(){
            if(!hasCheckbox) return;
            var count = state.selected.length;
            if(count > 0){
                selectedCountEl.innerHTML = '✓ ' + count + ' selected';
                selectedCountEl.classList.add('show');
                bulkDeleteBtn.classList.add('show');
                bulkExportBtn.classList.add('show');
            } else {
                selectedCountEl.classList.remove('show');
                bulkDeleteBtn.classList.remove('show');
                bulkExportBtn.classList.remove('show');
            }
        }

        function load(){
            var colSpan = cols.length + (hasCheckbox ? 1 : 0);
            tbody.innerHTML = '<tr><td colspan="' + colSpan + '" class="dt-loading">Loading data...</td></tr>';
            
            var params = new URLSearchParams();
            params.set('page', state.page);
            params.set('per_page', state.perPage);
            if(state.search) params.set('search', state.search);
            params.set('sort', state.sort);
            params.set('dir', state.dir);
            
            for(var key in state.filters){
                if(state.filters[key]) params.set(key, state.filters[key]);
            }

            fetch(route + '?' + params.toString())
                .then(function(r){ return r.json(); })
                .then(function(json){
                    render(json);
                    renderPag(json);
                })
                .catch(function(e){
                    tbody.innerHTML = '<tr><td colspan="' + colSpan + '" class="dt-loading" style="color:#c53030">❌ Error loading data</td></tr>';
                });
        }

        function render(json){
            var colSpan = cols.length + (hasCheckbox ? 1 : 0);
            
            if(!json.data || !json.data.length){
                tbody.innerHTML = '<tr><td colspan="' + colSpan + '"><div class="dt-empty"><div class="dt-empty-icon">📭</div><div class="dt-empty-text">No data found</div></div></td></tr>';
                return;
            }
            
            tbody.innerHTML = json.data.map(function(row){
                var isSelected = state.selected.indexOf(row.id) !== -1;
                var checkboxCell = hasCheckbox ? 
                    '<td class="dt-checkbox-col"><input type="checkbox" class="dt-checkbox dt-row-check" data-id="' + row.id + '" ' + (isSelected ? 'checked' : '') + '></td>' : '';
                
                return '<tr data-id="' + row.id + '" class="' + (isSelected ? 'selected' : '') + '">' + 
                    checkboxCell + 
                    cols.map(function(c){
                        var cls = c.render === 'actions' ? ' class="dt-actions-col"' : '';
                        return '<td' + cls + '>' + cell(row, c) + '</td>';
                    }).join('') + 
                '</tr>';
            }).join('');

            // Checkbox handlers
            tbody.querySelectorAll('.dt-row-check').forEach(function(cb){
                cb.onchange = function(){
                    var id = parseInt(this.dataset.id);
                    var tr = this.closest('tr');
                    if(this.checked){
                        if(state.selected.indexOf(id) === -1) state.selected.push(id);
                        tr.classList.add('selected');
                    } else {
                        state.selected = state.selected.filter(function(x){ return x !== id; });
                        tr.classList.remove('selected');
                    }
                    updateBulkUI();
                    
                    var allChecked = tbody.querySelectorAll('.dt-row-check').length === tbody.querySelectorAll('.dt-row-check:checked').length;
                    var selectAll = table.querySelector('.dt-check-all');
                    if(selectAll) selectAll.checked = allChecked;
                };
            });

            // Delete handlers
            tbody.querySelectorAll('.dt-btn-delete').forEach(function(b){
                b.onclick = function(e){
                    e.preventDefault();
                    del(this.dataset.id);
                };
            });

            updateBulkUI();
        }

        function cell(row, c){
            var v = c.col ? row[c.col] : null;
            
            // Custom render support
            if(c.render && window.dtRenders && window.dtRenders[c.render]){
                return window.dtRenders[c.render](v, row);
            }
            
            switch(c.render){
                case 'date':
                    return v ? new Date(v).toLocaleDateString() : '-';
                case 'datetime':
                    return v ? new Date(v).toLocaleString() : '-';
                case 'badge':
                    var cls = badge(v);
                    return '<span class="dt-badge dt-badge-' + cls + '">' + (v || '-') + '</span>';
                case 'actions':
                    var h = '<div class="dt-actions">';
                    if(row._show_url && row._show_url !== '#'){
                        h += '<a href="' + row._show_url + '" class="dt-btn dt-btn-view">View</a>';
                    }
                    if(row._edit_url && row._edit_url !== '#'){
                        h += '<a href="' + row._edit_url + '" class="dt-btn dt-btn-edit">Edit</a>';
                    }
                    h += '<button class="dt-btn dt-btn-delete" data-id="' + row.id + '">Delete</button>';
                    h += '</div>';
                    return h;
                default:
                    return v !== null && v !== undefined ? v : '-';
            }
        }

        function badge(s){
            var m = {
                active: 'active',
                inactive: 'inactive',
                graduated: 'graduated',
                pending: 'pending',
                cancelled: 'cancelled',
                completed: 'success',
                draft: 'secondary',
                success: 'success',
                failed: 'danger'
            };
            return m[s] || 'secondary';
        }

        function renderPag(json){
            infoEl.textContent = 'Page ' + json.current_page + ' of ' + json.last_page + ' (' + json.total + ' total)';
            
            var h = '<button ' + (json.current_page <= 1 ? 'disabled' : '') + ' data-p="' + (json.current_page - 1) + '">← Prev</button>';
            
            for(var i = 1; i <= json.last_page; i++){
                if(i === 1 || i === json.last_page || (i >= json.current_page - 1 && i <= json.current_page + 1)){
                    h += '<button class="' + (i === json.current_page ? 'active' : '') + '" data-p="' + i + '">' + i + '</button>';
                } else if(i === json.current_page - 2 || i === json.current_page + 2){
                    h += '<span style="color:var(--text-muted);padding:0 6px">...</span>';
                }
            }
            
            h += '<button ' + (json.current_page >= json.last_page ? 'disabled' : '') + ' data-p="' + (json.current_page + 1) + '">Next →</button>';
            
            pagesEl.innerHTML = h;
            pagesEl.querySelectorAll('button').forEach(function(b){
                b.onclick = function(){
                    var p = parseInt(this.dataset.p);
                    if(p && !this.disabled){
                        state.page = p;
                        load();
                    }
                };
            });
        }

        function doExport(format){
            var url = route + '?export=' + format + '&search=' + encodeURIComponent(state.search) + '&sort=' + state.sort + '&dir=' + state.dir;
            
            for(var key in state.filters){
                if(state.filters[key]) url += '&' + key + '=' + encodeURIComponent(state.filters[key]);
            }
            
            if(state.selected.length > 0){
                url += '&ids=' + state.selected.join(',');
            }
            
            window.location.href = url;
        }

        function del(id){
            if(!confirm('Are you sure you want to delete this item?')) return;
            
            var dr = route.replace('/data', '') + '/' + id;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            
            fetch(dr, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf ? csrf.content : '',
                    'Accept': 'application/json'
                }
            })
            .then(function(){
                state.selected = state.selected.filter(function(x){ return x !== parseInt(id); });
                load();
            })
            .catch(function(){
                alert('Delete failed');
            });
        }

        function bulkDelete(){
            if(state.selected.length === 0) return;
            if(!confirm('Delete ' + state.selected.length + ' selected items?')) return;
            
            var dr = route.replace('/data', '') + '/bulk-delete';
            var csrf = document.querySelector('meta[name="csrf-token"]');
            
            fetch(dr, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf ? csrf.content : '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: state.selected })
            })
            .then(function(){
                state.selected = [];
                load();
            })
            .catch(function(){
                alert('Bulk delete failed');
            });
        }

        function bulkExport(){
            if(state.selected.length === 0) return;
            window.location.href = route + '?export=xlsx&ids=' + state.selected.join(',');
        }

        // Import Modal
        var importModal = null;
        var selectedFile = null;

        function showImportModal(){
            if(!importModal){
                importModal = document.createElement('div');
                importModal.className = 'dt-modal-overlay';
                importModal.innerHTML = 
                    '<div class="dt-modal">' +
                        '<div class="dt-modal-header">' +
                            '<span class="dt-modal-title">📥 Import Data</span>' +
                            '<button class="dt-modal-close">&times;</button>' +
                        '</div>' +
                        '<div class="dt-modal-body">' +
                            '<div class="dt-file-drop">' +
                                '<div class="dt-file-drop-icon">📁</div>' +
                                '<div class="dt-file-drop-text">Drop Excel/CSV file here<br>or <strong>click to browse</strong></div>' +
                                '<input type="file" class="dt-file-input" accept=".xlsx,.xls,.csv">' +
                            '</div>' +
                            '<div class="dt-file-name"></div>' +
                            '<a href="' + route + '?template=1" class="dt-template-link">📄 Download Import Template</a>' +
                            '<div class="dt-import-results"></div>' +
                        '</div>' +
                        '<div class="dt-modal-footer">' +
                            '<button class="dt-btn-cancel">Cancel</button>' +
                            '<button class="dt-btn-submit" disabled>Import Data</button>' +
                        '</div>' +
                    '</div>';
                    
                document.body.appendChild(importModal);

                var dropZone = importModal.querySelector('.dt-file-drop');
                var fileInput = importModal.querySelector('.dt-file-input');
                var fileName = importModal.querySelector('.dt-file-name');
                var submitBtn = importModal.querySelector('.dt-btn-submit');
                var results = importModal.querySelector('.dt-import-results');
                var closeBtn = importModal.querySelector('.dt-modal-close');
                var cancelBtn = importModal.querySelector('.dt-btn-cancel');

                dropZone.onclick = function(){ fileInput.click(); };
                
                dropZone.ondragover = function(e){
                    e.preventDefault();
                    this.classList.add('dragover');
                };
                
                dropZone.ondragleave = function(){
                    this.classList.remove('dragover');
                };
                
                dropZone.ondrop = function(e){
                    e.preventDefault();
                    this.classList.remove('dragover');
                    if(e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
                };

                fileInput.onchange = function(){
                    if(this.files.length) handleFile(this.files[0]);
                };

                function handleFile(file){
                    var ext = file.name.split('.').pop().toLowerCase();
                    if(['xlsx', 'xls', 'csv'].indexOf(ext) === -1){
                        alert('Please select an Excel or CSV file');
                        return;
                    }
                    selectedFile = file;
                    fileName.innerHTML = '✅ ' + file.name + ' (' + formatSize(file.size) + ')';
                    fileName.classList.add('show');
                    submitBtn.disabled = false;
                    results.classList.remove('show');
                }

                function formatSize(bytes){
                    if(bytes < 1024) return bytes + ' B';
                    if(bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                    return (bytes / 1048576).toFixed(1) + ' MB';
                }

                submitBtn.onclick = function(){
                    if(!selectedFile) return;
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Importing...';
                    results.classList.remove('show');

                    var formData = new FormData();
                    formData.append('file', selectedFile);

                    var csrf = document.querySelector('meta[name="csrf-token"]');
                    
                    fetch(route, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
                        body: formData
                    })
                    .then(function(r){ return r.json(); })
                    .then(function(json){
                        submitBtn.textContent = 'Import Data';
                        
                        if(json.success){
                            results.className = 'dt-import-results show success';
                            results.innerHTML = '✅ ' + json.message;
                            
                            if(json.results && json.results.errors && json.results.errors.length){
                                results.innerHTML += '<div class="dt-import-errors">' + 
                                    json.results.errors.map(function(e){ return '<div>⚠️ ' + e + '</div>'; }).join('') + 
                                '</div>';
                            }
                            
                            load();
                            
                            setTimeout(function(){
                                importModal.classList.remove('show');
                                resetModal();
                            }, 2000);
                        } else {
                            results.className = 'dt-import-results show error';
                            results.innerHTML = '❌ ' + json.message;
                            
                            if(json.results && json.results.errors){
                                results.innerHTML += '<div class="dt-import-errors">' + 
                                    json.results.errors.map(function(e){ return '<div>' + e + '</div>'; }).join('') + 
                                '</div>';
                            }
                            
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(function(e){
                        submitBtn.textContent = 'Import Data';
                        submitBtn.disabled = false;
                        results.className = 'dt-import-results show error';
                        results.innerHTML = '❌ Error: ' + e.message;
                    });
                };

                function resetModal(){
                    selectedFile = null;
                    fileInput.value = '';
                    fileName.classList.remove('show');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Import Data';
                    results.classList.remove('show');
                }

                closeBtn.onclick = function(){
                    importModal.classList.remove('show');
                    resetModal();
                };
                
                cancelBtn.onclick = function(){
                    importModal.classList.remove('show');
                    resetModal();
                };
                
                // Close on backdrop click
                importModal.onclick = function(e){
                    if(e.target === importModal){
                        importModal.classList.remove('show');
                        resetModal();
                    }
                };
            }

            importModal.classList.add('show');
        }

        // Initial load
        load();
        
        // Expose API
        window.dtInstance = window.dtInstance || {};
        window.dtInstance[tableId] = {
            reload: load,
            setFilter: function(col, val){
                state.filters[col] = val;
                state.page = 1;
                load();
            },
            exportTo: doExport,
            getSelected: function(){ return state.selected; },
            clearSelection: function(){
                state.selected = [];
                updateBulkUI();
            }
        };
        
        // Legacy API
        table.dtReload = load;
        table.dtSetFilter = function(col, val){
            state.filters[col] = val;
            state.page = 1;
            load();
        };
        table.dtClearSelection = function(){
            state.selected = [];
            updateBulkUI();
        };
        table.dtExport = doExport;
    }
})();
</script>