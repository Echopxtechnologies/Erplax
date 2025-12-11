<style>
/* Container */
.dt-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin: 15px 0;
    width: 100%;
}

.dt-container .dt-table-wrapper {
    overflow-x: auto;
    width: 100%;
}

/* Toolbar */
.dt-container .dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--card-bg);
    border-bottom: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 10px;
}

.dt-container .dt-toolbar-left,
.dt-container .dt-toolbar-right {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.dt-container .dt-search-input {
    padding: 7px 12px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    min-width: 200px;
}

.dt-container .dt-search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-light);
}

.dt-container .dt-perpage-select {
    padding: 7px 10px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    cursor: pointer;
}

.dt-container .dt-export-btn,
.dt-container .dt-import-btn {
    padding: 7px 14px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.dt-container .dt-export-btn {
    background: var(--success);
    color: #fff;
}

.dt-container .dt-import-btn {
    background: var(--primary);
    color: #fff;
}

.dt-container .dt-export-btn:hover,
.dt-container .dt-import-btn:hover {
    opacity: 0.9;
}

.dt-container .dt-bulk-delete-btn {
    padding: 7px 14px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: var(--danger);
    color: #fff;
    cursor: pointer;
    display: none;
}

.dt-container .dt-bulk-delete-btn.show {
    display: inline-flex;
}

.dt-container .dt-selected-count {
    font-size: var(--font-sm);
    display: none;
    padding: 7px 12px;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: var(--radius-md);
    font-weight: 500;
}

.dt-container .dt-selected-count.show {
    display: inline-block;
}

/* Table */
.dt-container .dt-table {
    width: 100%;
    min-width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.dt-container .dt-table th,
.dt-container .dt-table td {
    padding: 11px 14px;
    text-align: left;
    border-bottom: 1px solid var(--card-border);
    color: var(--text-primary);
    font-size: var(--font-sm);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 0;
}

.dt-container .dt-table th {
    background: var(--body-bg);
    font-weight: 600;
}

.dt-container .dt-table tbody tr:hover {
    background: var(--body-bg);
}

.dt-container .dt-table tbody tr.selected {
    background: var(--primary-light);
}

.dt-container .dt-table th.dt-checkbox-col,
.dt-container .dt-table td.dt-checkbox-col {
    width: 45px;
    min-width: 45px;
    max-width: 45px;
    text-align: center;
    padding: 11px 10px;
}

.dt-container .dt-checkbox {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--primary);
}

.dt-container .dt-table th.dt-actions-col,
.dt-container .dt-table td.dt-actions-col {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
}

.dt-container .dt-table th.dt-sort {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 22px;
}

.dt-container .dt-table th.dt-sort:hover {
    background: var(--card-border);
}

.dt-container .dt-table th.dt-sort::after {
    content: '↕';
    position: absolute;
    right: 8px;
    opacity: 0.3;
    font-size: 11px;
}

.dt-container .dt-table th.dt-sort.asc::after {
    content: '↑';
    opacity: 1;
    color: var(--primary);
}

.dt-container .dt-table th.dt-sort.desc::after {
    content: '↓';
    opacity: 1;
    color: var(--primary);
}

/* Pagination */
.dt-container .dt-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--card-bg);
    border-top: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 10px;
}

.dt-container .dt-info {
    color: var(--text-muted);
    font-size: var(--font-sm);
}

.dt-container .dt-pages {
    display: flex;
    gap: 4px;
}

.dt-container .dt-pages button {
    padding: 5px 10px;
    border: 1px solid var(--input-border);
    background: var(--input-bg);
    color: var(--text-primary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
}

.dt-container .dt-pages button:hover:not(:disabled) {
    background: var(--body-bg);
    border-color: var(--primary);
}

.dt-container .dt-pages button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.dt-container .dt-pages button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

/* Badges */
.dt-container .dt-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: var(--font-xs);
    font-weight: 600;
    display: inline-block;
}

.dt-container .dt-badge-active,
.dt-container .dt-badge-success {
    background: var(--success-light);
    color: var(--success);
}

.dt-container .dt-badge-inactive,
.dt-container .dt-badge-secondary {
    background: var(--body-bg);
    color: var(--text-muted);
}

.dt-container .dt-badge-pending,
.dt-container .dt-badge-warning {
    background: var(--warning-light);
    color: var(--warning);
}

.dt-container .dt-badge-danger,
.dt-container .dt-badge-failed {
    background: var(--danger-light);
    color: var(--danger);
}

/* Actions */
.dt-container .dt-actions {
    display: flex;
    gap: 4px;
}

.dt-container .dt-btn {
    padding: 4px 8px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    text-decoration: none;
    font-weight: 500;
}

.dt-container .dt-btn-view {
    background: var(--primary);
    color: #fff;
}

.dt-container .dt-btn-edit {
    background: var(--warning);
    color: #fff;
}

.dt-container .dt-btn-delete {
    background: var(--danger);
    color: #fff;
}

.dt-container .dt-btn:hover {
    opacity: 0.85;
}

.dt-container .dt-loading {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

/* Import Modal */
.dt-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.dt-modal-overlay.show {
    display: flex;
}

.dt-modal {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
}

.dt-modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--card-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dt-modal-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.dt-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-muted);
    line-height: 1;
}

.dt-modal-body {
    padding: 20px;
}

.dt-modal-footer {
    padding: 16px 20px;
    border-top: 1px solid var(--card-border);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.dt-file-drop {
    border: 2px dashed var(--card-border);
    border-radius: var(--radius-md);
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
}

.dt-file-drop:hover,
.dt-file-drop.dragover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.dt-file-drop-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.dt-file-drop-text {
    color: var(--text-muted);
    font-size: var(--font-sm);
}

.dt-file-drop-text strong {
    color: var(--primary);
}

.dt-file-input {
    display: none;
}

.dt-file-name {
    margin-top: 10px;
    padding: 10px;
    background: var(--body-bg);
    border-radius: var(--radius-sm);
    font-size: var(--font-sm);
    display: none;
}

.dt-file-name.show {
    display: block;
}

.dt-template-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--primary);
    font-size: var(--font-sm);
    text-decoration: none;
    margin-top: 15px;
}

.dt-template-link:hover {
    text-decoration: underline;
}

.dt-import-results {
    margin-top: 15px;
    padding: 12px;
    border-radius: var(--radius-sm);
    font-size: var(--font-sm);
    display: none;
}

.dt-import-results.show {
    display: block;
}

.dt-import-results.success {
    background: var(--success-light);
    color: var(--success);
}

.dt-import-results.error {
    background: var(--danger-light);
    color: var(--danger);
}

.dt-import-errors {
    margin-top: 10px;
    max-height: 150px;
    overflow-y: auto;
    font-size: 12px;
}

.dt-import-errors div {
    padding: 4px 0;
    border-bottom: 1px solid rgba(0,0,0,.1);
}

.dt-btn-cancel {
    padding: 8px 16px;
    border: 1px solid var(--card-border);
    background: var(--card-bg);
    color: var(--text-primary);
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: var(--font-sm);
}

.dt-btn-submit {
    padding: 8px 16px;
    border: none;
    background: var(--primary);
    color: #fff;
    border-radius: var(--radius-md);
    cursor: pointer;
    font-size: var(--font-sm);
    font-weight: 500;
}

.dt-btn-submit:disabled {
    opacity: .5;
    cursor: not-allowed;
}
</style>

<script>
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('table.dt-table').forEach(initDT);
    });

    function initDT(table){
        var route = table.dataset.route;
        if(!route) return;

        var tableId = table.id || 'dt-' + Math.random().toString(36).substr(2,9);
        table.id = tableId;

        var state = {page:1, perPage:10, search:'', sort:'id', dir:'desc', selected:[], filters:{}};

        var hasCheckbox = table.classList.contains('dt-checkbox');
        var hasSearch = table.classList.contains('dt-search');
        var hasExport = table.classList.contains('dt-export');
        var hasImport = table.classList.contains('dt-import');
        var hasPerPage = table.classList.contains('dt-perpage');

        var wrapper = document.createElement('div');
        wrapper.className = 'dt-container';
        table.parentNode.insertBefore(wrapper, table);

        var selectedCountEl, bulkDeleteBtn;

        // Toolbar
        if(hasSearch || hasExport || hasImport || hasPerPage || hasCheckbox){
            var toolbar = document.createElement('div');
            toolbar.className = 'dt-toolbar';

            var left = document.createElement('div');
            left.className = 'dt-toolbar-left';

            if(hasSearch){
                var inp = document.createElement('input');
                inp.type = 'text';
                inp.placeholder = 'Search...';
                inp.className = 'dt-search-input';
                var searchTimer;
                inp.oninput = function(){
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function(){
                        state.search = inp.value;
                        state.page = 1;
                        load();
                    }, 300);
                };
                left.appendChild(inp);
            }

            if(hasCheckbox){
                selectedCountEl = document.createElement('span');
                selectedCountEl.className = 'dt-selected-count';
                left.appendChild(selectedCountEl);
            }

            var right = document.createElement('div');
            right.className = 'dt-toolbar-right';

            if(hasCheckbox){
                bulkDeleteBtn = document.createElement('button');
                bulkDeleteBtn.type = 'button';
                bulkDeleteBtn.className = 'dt-bulk-delete-btn';
                bulkDeleteBtn.textContent = 'Delete Selected';
                bulkDeleteBtn.onclick = bulkDelete;
                right.appendChild(bulkDeleteBtn);
            }

            if(hasPerPage){
                var sel = document.createElement('select');
                sel.className = 'dt-perpage-select';
                [10,25,50,100].forEach(function(n){
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

            if(hasImport){
                var importBtn = document.createElement('button');
                importBtn.type = 'button';
                importBtn.className = 'dt-import-btn';
                importBtn.innerHTML = '📥 Import';
                importBtn.onclick = showImportModal;
                right.appendChild(importBtn);
            }

            if(hasExport){
                var exportBtn = document.createElement('button');
                exportBtn.type = 'button';
                exportBtn.className = 'dt-export-btn';
                exportBtn.innerHTML = '📤 Export';
                exportBtn.onclick = function(){
                    var url = route + '?export=csv&search=' + encodeURIComponent(state.search) + '&sort=' + state.sort + '&dir=' + state.dir;
                    for(var key in state.filters){
                        if(state.filters[key]) url += '&' + key + '=' + encodeURIComponent(state.filters[key]);
                    }
                    window.location.href = url;
                };
                right.appendChild(exportBtn);
            }

            toolbar.appendChild(left);
            toolbar.appendChild(right);
            wrapper.appendChild(toolbar);
        }

        var tableWrapper = document.createElement('div');
        tableWrapper.className = 'dt-table-wrapper';
        tableWrapper.appendChild(table);
        wrapper.appendChild(tableWrapper);

        if(hasCheckbox){
            var thead = table.querySelector('thead tr');
            var checkTh = document.createElement('th');
            checkTh.className = 'dt-checkbox-col';
            checkTh.innerHTML = '<input type="checkbox" class="dt-checkbox dt-check-all">';
            thead.insertBefore(checkTh, thead.firstChild);

            checkTh.querySelector('.dt-check-all').onchange = function(){
                var checked = this.checked;
                table.querySelectorAll('tbody .dt-row-check').forEach(function(cb){
                    cb.checked = checked;
                    var id = parseInt(cb.dataset.id);
                    var tr = cb.closest('tr');
                    if(checked){
                        if(state.selected.indexOf(id)===-1) state.selected.push(id);
                        tr.classList.add('selected');
                    } else {
                        state.selected = state.selected.filter(function(x){return x!==id;});
                        tr.classList.remove('selected');
                    }
                });
                updateBulkUI();
            };
        }

        table.querySelectorAll('thead th').forEach(function(th){
            if(th.dataset.render==='actions') th.classList.add('dt-actions-col');
        });

        var pag = document.createElement('div');
        pag.className = 'dt-pagination';
        pag.innerHTML = '<span class="dt-info"></span><div class="dt-pages"></div>';
        wrapper.appendChild(pag);

        var tbody = table.querySelector('tbody') || table.createTBody();
        var infoEl = pag.querySelector('.dt-info');
        var pagesEl = pag.querySelector('.dt-pages');

        var cols = [];
        table.querySelectorAll('thead th:not(.dt-checkbox-col)').forEach(function(th){
            cols.push({col:th.dataset.col||null, render:th.dataset.render||null});
            if(th.classList.contains('dt-sort') && th.dataset.col){
                th.onclick = function(){
                    var c = this.dataset.col;
                    if(state.sort===c) state.dir = state.dir==='asc'?'desc':'asc';
                    else {state.sort=c; state.dir='asc';}
                    table.querySelectorAll('th.dt-sort').forEach(function(h){h.classList.remove('asc','desc');});
                    this.classList.add(state.dir);
                    load();
                };
            }
        });

        // External filters - data-dt-filter
        document.querySelectorAll('[data-dt-filter]').forEach(function(el){
            var targetTable = el.dataset.dtTable;
            if(!targetTable || targetTable===tableId){
                var column = el.dataset.dtFilter;
                var tagName = el.tagName.toUpperCase();
                var inputType = el.type || '';
                
                if(tagName==='SELECT' || inputType==='date'){
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
            if(count>0){
                selectedCountEl.textContent = count + ' selected';
                selectedCountEl.classList.add('show');
                bulkDeleteBtn.classList.add('show');
            } else {
                selectedCountEl.classList.remove('show');
                bulkDeleteBtn.classList.remove('show');
            }
        }

        function load(){
            var colCount = cols.length + (hasCheckbox?1:0);
            tbody.innerHTML = '<tr><td colspan="'+colCount+'" class="dt-loading">Loading...</td></tr>';
            
            var url = route + '?page=' + state.page + '&per_page=' + state.perPage + '&search=' + encodeURIComponent(state.search) + '&sort=' + state.sort + '&dir=' + state.dir;
            
            // Add filters as direct params
            for(var key in state.filters){
                if(state.filters[key]!=='' && state.filters[key]!==null){
                    url += '&' + key + '=' + encodeURIComponent(state.filters[key]);
                }
            }
            
            fetch(url)
                .then(function(r){return r.json();})
                .then(function(json){render(json);renderPag(json);})
                .catch(function(e){
                    tbody.innerHTML = '<tr><td colspan="'+colCount+'" class="dt-loading" style="color:var(--danger)">Error loading</td></tr>';
                });
        }

        function render(json){
            var colCount = cols.length + (hasCheckbox?1:0);
            if(!json.data || !json.data.length){
                tbody.innerHTML = '<tr><td colspan="'+colCount+'" class="dt-loading">No data found</td></tr>';
                return;
            }
            
            tbody.innerHTML = json.data.map(function(row){
                var isSelected = state.selected.indexOf(row.id)!==-1;
                var checkboxCell = hasCheckbox ? '<td class="dt-checkbox-col"><input type="checkbox" class="dt-checkbox dt-row-check" data-id="'+row.id+'" '+(isSelected?'checked':'')+'></td>' : '';
                return '<tr data-id="'+row.id+'" class="'+(isSelected?'selected':'')+'">' + checkboxCell + cols.map(function(c){
                    var cls = c.render==='actions'?' class="dt-actions-col"':'';
                    return '<td'+cls+'>'+cell(row,c)+'</td>';
                }).join('') + '</tr>';
            }).join('');

            tbody.querySelectorAll('.dt-row-check').forEach(function(cb){
                cb.onchange = function(){
                    var id = parseInt(this.dataset.id);
                    var tr = this.closest('tr');
                    if(this.checked){
                        if(state.selected.indexOf(id)===-1) state.selected.push(id);
                        tr.classList.add('selected');
                    } else {
                        state.selected = state.selected.filter(function(x){return x!==id;});
                        tr.classList.remove('selected');
                    }
                    updateBulkUI();
                };
            });

            tbody.querySelectorAll('.dt-btn-delete').forEach(function(b){
                b.onclick = function(e){e.preventDefault();del(this.dataset.id);};
            });

            updateBulkUI();
        }

        function cell(row,c){
            var v = c.col ? row[c.col] : null;
            
            // Check for custom renderer
            if(c.render && window.dtRenders && window.dtRenders[c.render]){
                return window.dtRenders[c.render](v, row);
            }
            
            switch(c.render){
                case 'date': return v ? new Date(v).toLocaleDateString() : '';
                case 'datetime': return v ? new Date(v).toLocaleString() : '';
                case 'badge':
                    var cls = {active:'active',inactive:'inactive',pending:'pending',success:'success',failed:'danger'}[v] || 'secondary';
                    return '<span class="dt-badge dt-badge-'+cls+'">'+(v||'')+'</span>';
                case 'actions':
                    var h = '<div class="dt-actions">';
                    if(row._show_url && row._show_url!=='#') h += '<a href="'+row._show_url+'" class="dt-btn dt-btn-view">View</a>';
                    if(row._edit_url && row._edit_url!=='#') h += '<a href="'+row._edit_url+'" class="dt-btn dt-btn-edit">Edit</a>';
                    h += '<button class="dt-btn dt-btn-delete" data-id="'+row.id+'">Delete</button></div>';
                    return h;
                default: return v!==null && v!==undefined ? v : '';
            }
        }

        function renderPag(json){
            infoEl.textContent = 'Page '+json.current_page+' of '+json.last_page+' ('+json.total+' total)';
            var h = '<button '+(json.current_page<=1?'disabled':'')+' data-p="'+(json.current_page-1)+'">Prev</button>';
            for(var i=1;i<=json.last_page;i++){
                if(i===1||i===json.last_page||(i>=json.current_page-1&&i<=json.current_page+1)){
                    h += '<button class="'+(i===json.current_page?'active':'')+'" data-p="'+i+'">'+i+'</button>';
                } else if(i===json.current_page-2||i===json.current_page+2){
                    h += '<span style="padding:0 4px">...</span>';
                }
            }
            h += '<button '+(json.current_page>=json.last_page?'disabled':'')+' data-p="'+(json.current_page+1)+'">Next</button>';
            pagesEl.innerHTML = h;
            pagesEl.querySelectorAll('button').forEach(function(b){
                b.onclick = function(){
                    var p = parseInt(this.dataset.p);
                    if(p && !this.disabled){state.page=p;load();}
                };
            });
        }

        function del(id){
            if(!confirm('Delete this item?')) return;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            fetch(route.replace('/data','') + '/' + id, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN':csrf?csrf.content:'','Accept':'application/json'}
            }).then(function(){state.selected=state.selected.filter(function(x){return x!==parseInt(id);});load();});
        }

        function bulkDelete(){
            if(!state.selected.length || !confirm('Delete '+state.selected.length+' items?')) return;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            fetch(route.replace('/data','') + '/bulk-delete', {
                method:'POST',
                headers:{'X-CSRF-TOKEN':csrf?csrf.content:'','Accept':'application/json','Content-Type':'application/json'},
                body:JSON.stringify({ids:state.selected})
            }).then(function(){state.selected=[];load();});
        }

        // ==========================================
        // IMPORT MODAL
        // ==========================================
        var importModal = null;
        var selectedFile = null;

        function showImportModal(){
            if(!importModal){
                importModal = document.createElement('div');
                importModal.className = 'dt-modal-overlay';
                importModal.innerHTML = 
                    '<div class="dt-modal">'+
                        '<div class="dt-modal-header">'+
                            '<span class="dt-modal-title">📥 Import Data</span>'+
                            '<button class="dt-modal-close">&times;</button>'+
                        '</div>'+
                        '<div class="dt-modal-body">'+
                            '<div class="dt-file-drop">'+
                                '<div class="dt-file-drop-icon">📁</div>'+
                                '<div class="dt-file-drop-text">Drop Excel/CSV file here or <strong>click to browse</strong></div>'+
                                '<input type="file" class="dt-file-input" accept=".xlsx,.xls,.csv">'+
                            '</div>'+
                            '<div class="dt-file-name"></div>'+
                            '<a href="'+route+'?template=1" class="dt-template-link">📄 Download Template</a>'+
                            '<div class="dt-import-results"></div>'+
                        '</div>'+
                        '<div class="dt-modal-footer">'+
                            '<button class="dt-btn-cancel">Cancel</button>'+
                            '<button class="dt-btn-submit" disabled>Import</button>'+
                        '</div>'+
                    '</div>';
                document.body.appendChild(importModal);

                var dropZone = importModal.querySelector('.dt-file-drop');
                var fileInput = importModal.querySelector('.dt-file-input');
                var fileName = importModal.querySelector('.dt-file-name');
                var submitBtn = importModal.querySelector('.dt-btn-submit');
                var results = importModal.querySelector('.dt-import-results');
                var closeBtn = importModal.querySelector('.dt-modal-close');
                var cancelBtn = importModal.querySelector('.dt-btn-cancel');

                dropZone.onclick = function(){fileInput.click();};
                dropZone.ondragover = function(e){e.preventDefault();this.classList.add('dragover');};
                dropZone.ondragleave = function(){this.classList.remove('dragover');};
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
                    if(['xlsx','xls','csv'].indexOf(ext)===-1){
                        alert('Please select Excel or CSV file');
                        return;
                    }
                    selectedFile = file;
                    fileName.textContent = '📄 ' + file.name + ' (' + formatSize(file.size) + ')';
                    fileName.classList.add('show');
                    submitBtn.disabled = false;
                    results.classList.remove('show');
                }

                function formatSize(bytes){
                    if(bytes<1024) return bytes+' B';
                    if(bytes<1048576) return (bytes/1024).toFixed(1)+' KB';
                    return (bytes/1048576).toFixed(1)+' MB';
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
                        method:'POST',
                        headers:{'X-CSRF-TOKEN':csrf?csrf.content:''},
                        body:formData
                    })
                    .then(function(r){return r.json();})
                    .then(function(json){
                        submitBtn.textContent = 'Import';
                        
                        if(json.success){
                            results.className = 'dt-import-results show success';
                            results.innerHTML = '✅ ' + json.message;
                            if(json.results && json.results.errors && json.results.errors.length){
                                results.innerHTML += '<div class="dt-import-errors">' + 
                                    json.results.errors.map(function(e){return '<div>⚠️ '+e+'</div>';}).join('') + 
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
                                    json.results.errors.map(function(e){return '<div>'+e+'</div>';}).join('') + 
                                '</div>';
                            }
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(function(e){
                        submitBtn.textContent = 'Import';
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
                    submitBtn.textContent = 'Import';
                    results.classList.remove('show');
                }

                closeBtn.onclick = function(){importModal.classList.remove('show');resetModal();};
                cancelBtn.onclick = function(){importModal.classList.remove('show');resetModal();};
            }

            importModal.classList.add('show');
        }

        load();
        
        // Expose methods
        window.dtInstance = window.dtInstance || {};
        window.dtInstance[tableId] = {
            reload: load,
            setFilter: function(col,val){state.filters[col]=val;state.page=1;load();}
        };
        table.dtReload = load;
        table.dtSetFilter = function(col,val){state.filters[col]=val;state.page=1;load();};
    }
})();
</script>