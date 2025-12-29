<style>
/* =========================================
   DATATABLE v2.0 - Production Ready
   Features: Multi-table, Bulk Actions Dropdown
   ========================================= */

/* Container */
.dt-container {
    background: var(--card-bg, #fff);
    border: 1px solid var(--card-border, #e5e7eb);
    border-radius: var(--radius-lg, 12px);
    overflow: hidden;
    margin: 15px 0;
    width: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* Table Wrapper */
.dt-container .dt-table-wrapper {
    overflow-x: auto;
    width: 100%;
    scrollbar-width: thin;
    scrollbar-color: #a0aec0 var(--body-bg, #f7fafc);
}

.dt-container .dt-table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.dt-container .dt-table-wrapper::-webkit-scrollbar-track {
    background: var(--body-bg, #f7fafc);
    border-radius: 4px;
}

.dt-container .dt-table-wrapper::-webkit-scrollbar-thumb {
    background: #a0aec0;
    border-radius: 4px;
}

/* Toolbar */
.dt-container .dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: var(--card-bg, #fff);
    border-bottom: 1px solid var(--card-border, #e5e7eb);
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
    border: 1px solid var(--input-border, #d1d5db);
    border-radius: var(--radius-md, 8px);
    font-size: var(--font-sm, 14px);
    background: var(--input-bg, #fff);
    color: var(--input-text, #1f2937);
    min-width: 220px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.dt-container .dt-search-input:focus {
    outline: none;
    border-color: #5a67d8;
    box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.15);
}

/* Per Page Select */
.dt-container .dt-perpage-select {
    padding: 8px 12px;
    border: 1px solid var(--input-border, #d1d5db);
    border-radius: var(--radius-md, 8px);
    font-size: var(--font-sm, 14px);
    background: var(--input-bg, #fff);
    color: var(--input-text, #1f2937);
    cursor: pointer;
}

/* Buttons Base */
.dt-container .dt-btn-base {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md, 8px);
    font-size: var(--font-sm, 14px);
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, opacity 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-btn-base:hover {
    opacity: 0.9;
}

/* Import Button */
.dt-container .dt-import-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md, 8px);
    font-size: var(--font-sm, 14px);
    font-weight: 500;
    background: #5a67d8;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-import-btn:hover {
    background: #4c56c0;
}

/* Dropdown Base */
.dt-container .dt-dropdown {
    position: relative;
    display: inline-block;
}

.dt-container .dt-dropdown-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--radius-md, 8px);
    font-size: var(--font-sm, 14px);
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-dropdown .dt-caret {
    margin-left: 2px;
    font-size: 10px;
    transition: transform 0.2s;
}

.dt-container .dt-dropdown.open .dt-caret {
    transform: rotate(180deg);
}

.dt-container .dt-dropdown-menu {
    position: absolute;
    top: 100%;
    margin-top: 6px;
    background: var(--card-bg, #fff);
    border: 1px solid var(--card-border, #e5e7eb);
    border-radius: var(--radius-md, 8px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    min-width: 180px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s, visibility 0.2s;
}

.dt-container .dt-dropdown.open .dt-dropdown-menu {
    opacity: 1;
    visibility: visible;
}

.dt-container .dt-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    cursor: pointer;
    font-size: var(--font-sm, 14px);
    color: var(--text-primary, #1f2937);
    transition: background 0.15s;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.dt-container .dt-dropdown-item:first-child {
    border-radius: var(--radius-md, 8px) var(--radius-md, 8px) 0 0;
}

.dt-container .dt-dropdown-item:last-child {
    border-radius: 0 0 var(--radius-md, 8px) var(--radius-md, 8px);
}

.dt-container .dt-dropdown-item:hover {
    background: var(--body-bg, #f7fafc);
}

.dt-container .dt-dropdown-divider {
    height: 1px;
    background: var(--card-border, #e5e7eb);
    margin: 6px 0;
}

/* Export Dropdown */
.dt-container .dt-export-dropdown .dt-dropdown-btn {
    background: #38a169;
    color: #fff;
}

.dt-container .dt-export-dropdown .dt-dropdown-btn:hover {
    background: #2f8a5a;
}

.dt-container .dt-export-dropdown .dt-dropdown-menu {
    right: 0;
}

.dt-container .dt-dropdown-item .dt-icon {
    width: 22px;
    text-align: center;
    font-size: 15px;
}

.dt-container .dt-dropdown-item .dt-ext {
    font-size: 10px;
    color: var(--text-muted, #6b7280);
    background: var(--body-bg, #f7fafc);
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 600;
    text-transform: uppercase;
    margin-left: auto;
}

/* Bulk Actions Dropdown */
.dt-container .dt-bulk-dropdown {
    display: none;
}

.dt-container .dt-bulk-dropdown.show {
    display: inline-block;
    animation: dtFadeIn 0.2s;
}

.dt-container .dt-bulk-dropdown .dt-dropdown-btn {
    background: #5a67d8;
    color: #fff;
}

.dt-container .dt-bulk-dropdown .dt-dropdown-btn:hover {
    background: #4c56c0;
}

.dt-container .dt-bulk-dropdown .dt-dropdown-menu {
    left: 0;
}

/* Bulk action colors */
.dt-container .dt-dropdown-item[data-color="red"] { color: #e53e3e; }
.dt-container .dt-dropdown-item[data-color="green"] { color: #38a169; }
.dt-container .dt-dropdown-item[data-color="yellow"] { color: #d69e2e; }
.dt-container .dt-dropdown-item[data-color="blue"] { color: #3182ce; }

/* Selected Count */
.dt-container .dt-selected-count {
    font-size: var(--font-sm, 14px);
    display: none;
    padding: 8px 14px;
    background: rgba(90, 103, 216, 0.1);
    color: #5a67d8;
    border-radius: var(--radius-md, 8px);
    font-weight: 600;
    border: 1px solid rgba(90, 103, 216, 0.2);
}

.dt-container .dt-selected-count.show {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: dtFadeIn 0.2s;
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
    border-bottom: 1px solid var(--card-border, #e5e7eb);
    color: var(--text-primary, #1f2937);
    font-size: var(--font-sm, 14px);
    white-space: nowrap;
}

.dt-container .dt-table th {
    background: var(--body-bg, #f7fafc);
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
}

.dt-container .dt-table tbody tr {
    transition: background 0.15s;
}

.dt-container .dt-table tbody tr:hover {
    background: var(--body-bg, #f7fafc);
}

.dt-container .dt-table tbody tr.selected {
    background: rgba(90, 103, 216, 0.08);
    border-left: 3px solid #5a67d8;
}

.dt-container .dt-table tbody tr:last-child td {
    border-bottom: none;
}

/* Clickable cells */
.dt-container .dt-table td.dt-clickable-cell {
    cursor: pointer;
    transition: color 0.15s;
}

.dt-container .dt-table td.dt-clickable-cell:hover {
    color: #5a67d8;
    text-decoration: underline;
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
    transition: background 0.15s;
}

.dt-container .dt-table th.dt-sort:hover {
    background: var(--card-border, #e5e7eb);
}

.dt-container .dt-table th.dt-sort::after {
    content: '↕';
    position: absolute;
    right: 10px;
    opacity: 0.3;
    font-size: 12px;
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
    background: var(--card-bg, #fff);
    border-top: 1px solid var(--card-border, #e5e7eb);
    flex-wrap: wrap;
    gap: 12px;
}

.dt-container .dt-info {
    color: var(--text-muted, #6b7280);
    font-size: var(--font-sm, 14px);
    font-weight: 500;
}

.dt-container .dt-pages {
    display: flex;
    gap: 5px;
}

.dt-container .dt-pages button {
    padding: 6px 12px;
    border: 1px solid var(--input-border, #d1d5db);
    background: var(--input-bg, #fff);
    color: var(--text-primary, #1f2937);
    border-radius: var(--radius-sm, 6px);
    cursor: pointer;
    font-size: var(--font-xs, 12px);
    font-weight: 500;
    transition: border-color 0.15s, background 0.15s;
}

.dt-container .dt-pages button:hover:not(:disabled) {
    background: var(--body-bg, #f7fafc);
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
    font-size: var(--font-xs, 12px);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: capitalize;
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
    background: var(--body-bg, #f7fafc);
    color: var(--text-muted, #6b7280);
    border: 1px solid var(--card-border, #e5e7eb);
}

.dt-container .dt-badge-pending,
.dt-container .dt-badge-warning,
.dt-container .dt-badge-in_progress {
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

.dt-container .dt-badge-info {
    background: rgba(90, 103, 216, 0.12);
    color: #4c56c0;
    border: 1px solid rgba(90, 103, 216, 0.2);
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
    border-radius: var(--radius-sm, 6px);
    cursor: pointer;
    font-size: var(--font-xs, 12px);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    white-space: nowrap;
    transition: opacity 0.15s;
    min-width: 50px;
}

.dt-container .dt-btn:hover {
    opacity: 0.85;
}

.dt-container .dt-btn-view { background: #5a67d8; color: #fff; }
.dt-container .dt-btn-edit { background: #dd9c26; color: #fff; }
.dt-container .dt-btn-delete { background: #e53e3e; color: #fff; }

/* Empty State */
.dt-container .dt-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted, #6b7280);
}

.dt-container .dt-empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.dt-container .dt-empty-text {
    font-size: 16px;
    font-weight: 500;
}

/* Loading */
.dt-container .dt-loading {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted, #6b7280);
}

/* Modal */
.dt-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s, visibility 0.2s;
}

.dt-modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.dt-modal {
    background: var(--card-bg, #fff);
    border-radius: var(--radius-lg, 12px);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.dt-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--card-border, #e5e7eb);
}

.dt-modal-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary, #1f2937);
}

.dt-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-muted, #6b7280);
    padding: 0;
    line-height: 1;
}

.dt-modal-body {
    padding: 20px;
    overflow-y: auto;
    max-height: calc(90vh - 140px);
}

.dt-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 20px;
    border-top: 1px solid var(--card-border, #e5e7eb);
}

.dt-btn-cancel {
    padding: 10px 20px;
    border: 1px solid var(--input-border, #d1d5db);
    background: var(--input-bg, #fff);
    color: var(--text-primary, #1f2937);
    border-radius: var(--radius-md, 8px);
    cursor: pointer;
    font-weight: 500;
}

.dt-btn-submit {
    padding: 10px 20px;
    border: none;
    background: #5a67d8;
    color: #fff;
    border-radius: var(--radius-md, 8px);
    cursor: pointer;
    font-weight: 500;
}

.dt-btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.dt-btn-danger {
    background: #e53e3e;
}

/* File Drop */
.dt-file-drop {
    border: 2px dashed var(--input-border, #d1d5db);
    border-radius: var(--radius-md, 8px);
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}

.dt-file-drop:hover,
.dt-file-drop.dragover {
    border-color: #5a67d8;
    background: rgba(90, 103, 216, 0.05);
}

.dt-file-drop-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.dt-file-drop-text {
    color: var(--text-muted, #6b7280);
    font-size: 14px;
}

.dt-file-input {
    display: none;
}

.dt-file-name {
    margin-top: 12px;
    padding: 10px;
    background: rgba(56, 161, 105, 0.1);
    border-radius: var(--radius-md, 8px);
    color: #2f855a;
    font-size: 14px;
    display: none;
}

.dt-file-name.show {
    display: block;
}

.dt-template-link {
    display: inline-block;
    margin-top: 16px;
    color: #5a67d8;
    font-size: 14px;
    text-decoration: none;
}

.dt-template-link:hover {
    text-decoration: underline;
}

.dt-import-results {
    margin-top: 16px;
    padding: 12px;
    border-radius: var(--radius-md, 8px);
    font-size: 14px;
    display: none;
}

.dt-import-results.show {
    display: block;
}

.dt-import-results.success {
    background: rgba(56, 161, 105, 0.1);
    color: #2f855a;
}

.dt-import-results.error {
    background: rgba(229, 62, 62, 0.1);
    color: #c53030;
}

.dt-import-errors {
    margin-top: 10px;
    font-size: 12px;
    max-height: 150px;
    overflow-y: auto;
}
</style>

<script>
/**
 * DataTable v2.0
 * Multi-table support, Bulk Actions Dropdown
 */
(function(){
    'use strict';

    // Store all instances
    window.dtInstance = window.dtInstance || {};

    // Find all tables
    var tables = document.querySelectorAll('.dt-table[data-route]');
    
    tables.forEach(function(table){
        initTable(table);
    });

    function initTable(table){
        var tableId = table.id || 'dt_' + Math.random().toString(36).substr(2, 9);
        table.id = tableId;
        
        var route = table.dataset.route;
        var bulkRoute = table.dataset.bulkRoute || route.replace('/data', '/bulk-action');
        var hasSearch = table.classList.contains('dt-search');
        var hasExport = table.classList.contains('dt-export');
        var hasImport = table.classList.contains('dt-import');
        var hasPerpage = table.classList.contains('dt-perpage');
        var hasCheckbox = table.classList.contains('dt-checkbox');
        
        // Default filters from data attribute
        var defaultFilters = {};
        try {
            defaultFilters = JSON.parse(table.dataset.filters || '{}');
        } catch(e) {}

        // Bulk actions config
        var bulkActionsConfig = {};
        try {
            bulkActionsConfig = JSON.parse(table.dataset.bulkActions || '{}');
        } catch(e) {}

        // State
        var state = {
            page: 1,
            perPage: 10,
            search: '',
            sort: 'id',
            dir: 'desc',
            filters: Object.assign({}, defaultFilters),
            selected: [],
            data: [],
            total: 0,
            lastPage: 1
        };

        // Build container
        var container = document.createElement('div');
        container.className = 'dt-container';
        table.parentNode.insertBefore(container, table);

        // Build toolbar
        var toolbar = document.createElement('div');
        toolbar.className = 'dt-toolbar';
        
        var toolbarLeft = document.createElement('div');
        toolbarLeft.className = 'dt-toolbar-left';
        
        var toolbarRight = document.createElement('div');
        toolbarRight.className = 'dt-toolbar-right';

        // Search
        if(hasSearch){
            var searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'dt-search-input';
            searchInput.placeholder = 'Search...';
            var searchTimer;
            searchInput.oninput = function(){
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function(){
                    state.search = searchInput.value;
                    state.page = 1;
                    load();
                }, 300);
            };
            toolbarLeft.appendChild(searchInput);
        }

        // Per page
        if(hasPerpage){
            var perPageSelect = document.createElement('select');
            perPageSelect.className = 'dt-perpage-select';
            [10, 25, 50, 100].forEach(function(n){
                var opt = document.createElement('option');
                opt.value = n;
                opt.textContent = n + ' per page';
                if(n === state.perPage) opt.selected = true;
                perPageSelect.appendChild(opt);
            });
            perPageSelect.onchange = function(){
                state.perPage = parseInt(this.value);
                state.page = 1;
                load();
            };
            toolbarLeft.appendChild(perPageSelect);
        }

        // Selected count
        var selectedCount = document.createElement('span');
        selectedCount.className = 'dt-selected-count';
        toolbarLeft.appendChild(selectedCount);

        // Bulk Actions Dropdown
        var bulkDropdown = document.createElement('div');
        bulkDropdown.className = 'dt-dropdown dt-bulk-dropdown';
        
        var bulkBtn = document.createElement('button');
        bulkBtn.className = 'dt-dropdown-btn';
        bulkBtn.innerHTML = '⚡ Actions <span class="dt-caret">▼</span>';
        
        var bulkMenu = document.createElement('div');
        bulkMenu.className = 'dt-dropdown-menu';
        
        bulkDropdown.appendChild(bulkBtn);
        bulkDropdown.appendChild(bulkMenu);
        toolbarLeft.appendChild(bulkDropdown);

        // Load bulk actions config
        function loadBulkActions(){
            if(Object.keys(bulkActionsConfig).length > 0){
                renderBulkActions(bulkActionsConfig);
            } else {
                // Fetch from server
                fetch(route + '?bulk_actions=1')
                    .then(function(r){ return r.json(); })
                    .then(function(json){
                        if(json.actions){
                            bulkActionsConfig = json.actions;
                            renderBulkActions(json.actions);
                        }
                    })
                    .catch(function(){
                        // Default actions
                        renderBulkActions({ delete: { label: 'Delete', confirm: true, color: 'red' }});
                    });
            }
        }

        function renderBulkActions(actions){
            bulkMenu.innerHTML = '';
            Object.keys(actions).forEach(function(key){
                var action = actions[key];
                var item = document.createElement('button');
                item.className = 'dt-dropdown-item';
                item.dataset.action = key;
                item.dataset.confirm = action.confirm ? '1' : '0';
                if(action.color) item.dataset.color = action.color;
                item.innerHTML = '<span class="dt-icon">' + getActionIcon(key) + '</span><span>' + action.label + '</span>';
                item.onclick = function(e){
                    e.stopPropagation();
                    bulkDropdown.classList.remove('open');
                    handleBulkAction(key, action);
                };
                bulkMenu.appendChild(item);
            });
        }

        function getActionIcon(action){
            var icons = {
                'delete': '🗑️',
                'activate': '✅',
                'deactivate': '⛔',
                'restore': '♻️',
                'export': '📤'
            };
            return icons[action] || '⚡';
        }

        bulkBtn.onclick = function(e){
            e.stopPropagation();
            closeAllDropdowns();
            bulkDropdown.classList.toggle('open');
        };

        // Import button
        if(hasImport){
            var importBtn = document.createElement('button');
            importBtn.className = 'dt-import-btn';
            importBtn.innerHTML = '📥 Import';
            importBtn.onclick = showImportModal;
            toolbarRight.appendChild(importBtn);
        }

        // Export dropdown
        if(hasExport){
            var exportDropdown = document.createElement('div');
            exportDropdown.className = 'dt-dropdown dt-export-dropdown';
            
            var exportBtn = document.createElement('button');
            exportBtn.className = 'dt-dropdown-btn';
            exportBtn.innerHTML = '📤 Export <span class="dt-caret">▼</span>';
            
            var exportMenu = document.createElement('div');
            exportMenu.className = 'dt-dropdown-menu';
            
            var exports = [
                { format: 'csv', icon: '📄', label: 'CSV', ext: 'csv' },
                { format: 'xlsx', icon: '📊', label: 'Excel', ext: 'xlsx' },
                { format: 'pdf', icon: '📕', label: 'PDF', ext: 'pdf' }
            ];

            exports.forEach(function(exp){
                var item = document.createElement('button');
                item.className = 'dt-dropdown-item';
                item.innerHTML = '<span class="dt-icon">' + exp.icon + '</span><span>' + exp.label + '</span><span class="dt-ext">' + exp.ext + '</span>';
                item.onclick = function(e){
                    e.stopPropagation();
                    exportDropdown.classList.remove('open');
                    doExport(exp.format, false);
                };
                exportMenu.appendChild(item);
            });

            // Divider
            var divider = document.createElement('div');
            divider.className = 'dt-dropdown-divider';
            exportMenu.appendChild(divider);

            // Export selected
            exports.forEach(function(exp){
                var item = document.createElement('button');
                item.className = 'dt-dropdown-item';
                item.innerHTML = '<span class="dt-icon">☑️</span><span>Selected ' + exp.label + '</span><span class="dt-ext">' + exp.ext + '</span>';
                item.onclick = function(e){
                    e.stopPropagation();
                    exportDropdown.classList.remove('open');
                    doExport(exp.format, true);
                };
                exportMenu.appendChild(item);
            });

            exportBtn.onclick = function(e){
                e.stopPropagation();
                closeAllDropdowns();
                exportDropdown.classList.toggle('open');
            };

            exportDropdown.appendChild(exportBtn);
            exportDropdown.appendChild(exportMenu);
            toolbarRight.appendChild(exportDropdown);
        }

        toolbar.appendChild(toolbarLeft);
        toolbar.appendChild(toolbarRight);
        container.appendChild(toolbar);

        // Table wrapper
        var wrapper = document.createElement('div');
        wrapper.className = 'dt-table-wrapper';
        wrapper.appendChild(table);
        container.appendChild(wrapper);

        // Get headers
        var headers = [];
        var ths = table.querySelectorAll('thead th');
        ths.forEach(function(th){
            headers.push({
                col: th.dataset.col || '',
                render: th.dataset.render || '',
                sortable: th.classList.contains('dt-sort'),
                clickable: th.classList.contains('dt-clickable')
            });
        });

        // Add checkbox column
        if(hasCheckbox){
            var checkTh = document.createElement('th');
            checkTh.className = 'dt-checkbox-col';
            var checkAll = document.createElement('input');
            checkAll.type = 'checkbox';
            checkAll.className = 'dt-checkbox';
            checkAll.onchange = function(){
                var checked = this.checked;
                state.selected = checked ? state.data.map(function(r){ return r.id; }) : [];
                updateCheckboxes();
                updateBulkUI();
            };
            checkTh.appendChild(checkAll);
            table.querySelector('thead tr').insertBefore(checkTh, table.querySelector('thead tr').firstChild);
            headers.unshift({ col: '_checkbox', render: 'checkbox' });
        }

        // Setup sort
        ths.forEach(function(th){
            if(th.classList.contains('dt-sort')){
                th.onclick = function(){
                    var col = th.dataset.col;
                    if(state.sort === col){
                        state.dir = state.dir === 'asc' ? 'desc' : 'asc';
                    } else {
                        state.sort = col;
                        state.dir = 'asc';
                    }
                    state.page = 1;
                    load();
                };
            }
        });

        // Pagination
        var pagination = document.createElement('div');
        pagination.className = 'dt-pagination';
        container.appendChild(pagination);

        // External filter support
        document.querySelectorAll('[data-dt-filter][data-dt-table="' + tableId + '"]').forEach(function(el){
            el.addEventListener('change', function(){
                var filterCol = this.dataset.dtFilter;
                state.filters[filterCol] = this.value;
                state.page = 1;
                load();
            });
        });

        // Close dropdowns on outside click
        document.addEventListener('click', closeAllDropdowns);

        function closeAllDropdowns(){
            container.querySelectorAll('.dt-dropdown.open').forEach(function(d){
                d.classList.remove('open');
            });
        }

        // Load data
        function load(){
            var tbody = table.querySelector('tbody');
            tbody.innerHTML = '<tr><td colspan="' + headers.length + '" class="dt-loading">Loading...</td></tr>';

            var params = new URLSearchParams();
            params.set('page', state.page);
            params.set('per_page', state.perPage);
            params.set('sort', state.sort);
            params.set('dir', state.dir);
            if(state.search) params.set('search', state.search);
            
            Object.keys(state.filters).forEach(function(k){
                if(state.filters[k]) params.set(k, state.filters[k]);
            });

            fetch(route + '?' + params.toString())
                .then(function(r){ return r.json(); })
                .then(function(json){
                    state.data = json.data || [];
                    state.total = json.total || 0;
                    state.lastPage = json.last_page || 1;
                    render();
                    updateSortUI();
                    updateBulkUI();
                })
                .catch(function(e){
                    tbody.innerHTML = '<tr><td colspan="' + headers.length + '" class="dt-empty"><div class="dt-empty-icon">⚠️</div><div class="dt-empty-text">Error loading data</div></td></tr>';
                });
        }

        function render(){
            var tbody = table.querySelector('tbody');
            tbody.innerHTML = '';

            if(state.data.length === 0){
                tbody.innerHTML = '<tr><td colspan="' + headers.length + '" class="dt-empty"><div class="dt-empty-icon">📭</div><div class="dt-empty-text">No data found</div></td></tr>';
                renderPagination();
                return;
            }
            state.data.forEach(function(row, index){
                var tr = document.createElement('tr');
                tr.dataset.id = row.id;
                var rowNum = (state.page - 1) * state.perPage + index + 1;


                if(state.selected.indexOf(row.id) !== -1){
                    tr.classList.add('selected');
                }

                    headers.forEach(function(h){
                        var td = document.createElement('td');
                        var val = row[h.col] || '';

                        if(h.col === '_row_num'){
                            td.textContent = rowNum;
                            td.style.textAlign = 'center';
                            td.style.fontWeight = '500';
                            td.style.color = '#6b7280';
                        }
                        else if(h.render === 'checkbox'){
                        td.className = 'dt-checkbox-col';
                        var cb = document.createElement('input');
                        cb.type = 'checkbox';
                        cb.className = 'dt-checkbox';
                        cb.checked = state.selected.indexOf(row.id) !== -1;
                        cb.onchange = function(){
                            if(this.checked){
                                if(state.selected.indexOf(row.id) === -1) state.selected.push(row.id);
                            } else {
                                state.selected = state.selected.filter(function(id){ return id !== row.id; });
                            }
                            tr.classList.toggle('selected', this.checked);
                            updateBulkUI();
                        };
                        td.appendChild(cb);
                    } else if(h.render === 'actions'){
                        td.className = 'dt-actions-col';
                        td.innerHTML = renderActions(row);
                    } else if(h.render === 'badge'){
                        td.innerHTML = '<span class="dt-badge dt-badge-' + String(val).toLowerCase().replace(/\s+/g, '_') + '">' + formatValue(val) + '</span>';
                    } else if(h.render === 'date'){
                        td.textContent = val ? new Date(val).toLocaleDateString() : '-';
                    } else if(h.render === 'datetime'){
                        td.textContent = val ? new Date(val).toLocaleString() : '-';
                    } else if(h.render === 'currency'){
                        td.textContent = val ? parseFloat(val).toLocaleString('en-US', { style: 'currency', currency: 'USD' }) : '-';
                    } else if(window.dtRenders && window.dtRenders[h.render]){
                        td.innerHTML = window.dtRenders[h.render](val, row);
                    } else {
                        if(h.clickable && row._show_url){
                            td.className = 'dt-clickable-cell';
                            td.textContent = val || '-';
                            td.onclick = function(){ window.location.href = row._show_url; };
                        } else {
                            td.textContent = val || '-';
                        }
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });

            renderPagination();
        }

        function renderActions(row){
            var html = '<div class="dt-actions">';
            if(row._show_url) html += '<a href="' + row._show_url + '" class="dt-btn dt-btn-view">View</a>';
            if(row._edit_url) html += '<a href="' + row._edit_url + '" class="dt-btn dt-btn-edit">Edit</a>';
            if(row._delete_url) html += '<button class="dt-btn dt-btn-delete" onclick="dtDelete(\'' + row._delete_url + '\', \'' + tableId + '\')">Delete</button>';
            html += '</div>';
            return html;
        }

        function formatValue(val){
            if(val === null || val === undefined) return '-';
            return String(val).replace(/_/g, ' ');
        }

        function renderPagination(){
            var start = (state.page - 1) * state.perPage + 1;
            var end = Math.min(state.page * state.perPage, state.total);
            
            var html = '<div class="dt-info">Showing ' + (state.total ? start : 0) + ' to ' + end + ' of ' + state.total + '</div>';
            html += '<div class="dt-pages">';
            
            html += '<button ' + (state.page <= 1 ? 'disabled' : '') + ' onclick="dtPage(\'' + tableId + '\', 1)">First</button>';
            html += '<button ' + (state.page <= 1 ? 'disabled' : '') + ' onclick="dtPage(\'' + tableId + '\', ' + (state.page - 1) + ')">Prev</button>';
            
            // Page numbers
            var startPage = Math.max(1, state.page - 2);
            var endPage = Math.min(state.lastPage, state.page + 2);
            
            for(var p = startPage; p <= endPage; p++){
                html += '<button class="' + (p === state.page ? 'active' : '') + '" onclick="dtPage(\'' + tableId + '\', ' + p + ')">' + p + '</button>';
            }
            
            html += '<button ' + (state.page >= state.lastPage ? 'disabled' : '') + ' onclick="dtPage(\'' + tableId + '\', ' + (state.page + 1) + ')">Next</button>';
            html += '<button ' + (state.page >= state.lastPage ? 'disabled' : '') + ' onclick="dtPage(\'' + tableId + '\', ' + state.lastPage + ')">Last</button>';
            html += '</div>';
            
            pagination.innerHTML = html;
        }

        function updateSortUI(){
            ths.forEach(function(th){
                th.classList.remove('asc', 'desc');
                if(th.dataset.col === state.sort){
                    th.classList.add(state.dir);
                }
            });
        }

        function updateCheckboxes(){
            table.querySelectorAll('tbody .dt-checkbox').forEach(function(cb){
                var id = parseInt(cb.closest('tr').dataset.id);
                cb.checked = state.selected.indexOf(id) !== -1;
                cb.closest('tr').classList.toggle('selected', cb.checked);
            });
            
            var checkAll = table.querySelector('thead .dt-checkbox');
            if(checkAll){
                checkAll.checked = state.data.length > 0 && state.selected.length === state.data.length;
            }
        }

        function updateBulkUI(){
            var count = state.selected.length;
            
            if(count > 0){
                selectedCount.textContent = count + ' selected';
                selectedCount.classList.add('show');
                bulkDropdown.classList.add('show');
            } else {
                selectedCount.classList.remove('show');
                bulkDropdown.classList.remove('show');
            }
        }

        function doExport(format, selectedOnly){
            var params = new URLSearchParams();
            params.set('export', format);
            params.set('sort', state.sort);
            params.set('dir', state.dir);
            if(state.search) params.set('search', state.search);
            
            Object.keys(state.filters).forEach(function(k){
                if(state.filters[k]) params.set(k, state.filters[k]);
            });

            if(selectedOnly && state.selected.length > 0){
                params.set('ids', state.selected.join(','));
            }

            window.location.href = route + '?' + params.toString();
        }

        // Bulk action handler
        function handleBulkAction(action, config){
            if(state.selected.length === 0){
                showToast('No items selected', 'error');
                return;
            }

            if(config.confirm){
                showConfirmModal(
                    'Confirm ' + config.label,
                    'Are you sure you want to ' + config.label.toLowerCase() + ' ' + state.selected.length + ' items?',
                    function(){
                        executeBulkAction(action);
                    }
                );
            } else {
                executeBulkAction(action);
            }
        }

        function executeBulkAction(action){
            var csrf = document.querySelector('meta[name="csrf-token"]');
            
            fetch(bulkRoute, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf ? csrf.content : '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: action, ids: state.selected })
            })
            .then(function(r){ return r.json(); })
            .then(function(json){
                if(json.success !== false){
                    showToast(json.message || 'Action completed', 'success');
                    state.selected = [];
                    load();
                } else {
                    showToast(json.message || 'Action failed', 'error');
                }
            })
            .catch(function(){
                showToast('Action failed', 'error');
            });
        }

        // Confirm Modal
        var confirmModal = null;
        
        function showConfirmModal(title, message, onConfirm){
            if(!confirmModal){
                confirmModal = document.createElement('div');
                confirmModal.className = 'dt-modal-overlay';
                confirmModal.innerHTML = 
                    '<div class="dt-modal">' +
                        '<div class="dt-modal-header">' +
                            '<span class="dt-modal-title"></span>' +
                            '<button class="dt-modal-close">&times;</button>' +
                        '</div>' +
                        '<div class="dt-modal-body"><p class="dt-confirm-message"></p></div>' +
                        '<div class="dt-modal-footer">' +
                            '<button class="dt-btn-cancel">Cancel</button>' +
                            '<button class="dt-btn-submit dt-btn-danger">Confirm</button>' +
                        '</div>' +
                    '</div>';
                document.body.appendChild(confirmModal);

                confirmModal.querySelector('.dt-modal-close').onclick = function(){ confirmModal.classList.remove('show'); };
                confirmModal.querySelector('.dt-btn-cancel').onclick = function(){ confirmModal.classList.remove('show'); };
                confirmModal.onclick = function(e){ if(e.target === confirmModal) confirmModal.classList.remove('show'); };
            }

            confirmModal.querySelector('.dt-modal-title').textContent = title;
            confirmModal.querySelector('.dt-confirm-message').textContent = message;
            
            var confirmBtn = confirmModal.querySelector('.dt-btn-submit');
            confirmBtn.onclick = function(){
                confirmModal.classList.remove('show');
                onConfirm();
            };

            confirmModal.classList.add('show');
        }

        // Toast
        function showToast(message, type){
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:14px 20px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;z-index:10000;animation:dtFadeIn 0.2s;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
            toast.style.background = type === 'error' ? '#e53e3e' : '#38a169';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(function(){ toast.remove(); }, 3000);
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

                dropZone.onclick = function(){ fileInput.click(); };
                dropZone.ondragover = function(e){ e.preventDefault(); this.classList.add('dragover'); };
                dropZone.ondragleave = function(){ this.classList.remove('dragover'); };
                dropZone.ondrop = function(e){ e.preventDefault(); this.classList.remove('dragover'); if(e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]); };
                fileInput.onchange = function(){ if(this.files.length) handleFile(this.files[0]); };

                function handleFile(file){
                    var ext = file.name.split('.').pop().toLowerCase();
                    if(['xlsx', 'xls', 'csv'].indexOf(ext) === -1){
                        showToast('Please select an Excel or CSV file', 'error');
                        return;
                    }
                    selectedFile = file;
                    fileName.innerHTML = '✅ ' + file.name;
                    fileName.classList.add('show');
                    submitBtn.disabled = false;
                    results.classList.remove('show');
                }

                submitBtn.onclick = function(){
                    if(!selectedFile) return;
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Importing...';

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
                            setTimeout(function(){ importModal.classList.remove('show'); resetModal(); }, 2000);
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

                importModal.querySelector('.dt-modal-close').onclick = function(){ importModal.classList.remove('show'); resetModal(); };
                importModal.querySelector('.dt-btn-cancel').onclick = function(){ importModal.classList.remove('show'); resetModal(); };
                importModal.onclick = function(e){ if(e.target === importModal){ importModal.classList.remove('show'); resetModal(); }};
            }

            importModal.classList.add('show');
        }

        // Load bulk actions and initial data
        if(hasCheckbox){
            loadBulkActions();
        }
        load();

        // Expose API
        window.dtInstance[tableId] = {
            reload: load,
            setFilter: function(col, val){
                state.filters[col] = val;
                state.page = 1;
                load();
            },
            getSelected: function(){ return state.selected; },
            clearSelection: function(){
                state.selected = [];
                updateCheckboxes();
                updateBulkUI();
            },
            exportTo: function(format){ doExport(format, false); },
            exportSelected: function(format){ doExport(format, true); }
        };

        // Legacy API
        table.dtReload = load;
        table.dtSetFilter = function(col, val){
            state.filters[col] = val;
            state.page = 1;
            load();
        };
    }

    // Global functions
    window.dtPage = function(tableId, page){
        var instance = window.dtInstance[tableId];
        if(instance){
            instance.setFilter('page', page);
        }
    };

    window.dtDelete = function(url, tableId){
        if(!confirm('Are you sure you want to delete this item?')) return;
        
        var csrf = document.querySelector('meta[name="csrf-token"]');
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf ? csrf.content : '',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(json){
            if(json.success !== false){
                window.dtInstance[tableId].reload();
            } else {
                alert(json.message || 'Delete failed');
            }
        })
        .catch(function(){
            alert('Delete failed');
        });
    };
})();
</script>