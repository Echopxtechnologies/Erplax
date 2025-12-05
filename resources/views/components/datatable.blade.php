

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

/* Table Wrapper for horizontal scroll if needed */
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

.dt-container .dt-search-input::placeholder {
    color: var(--text-muted);
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

.dt-container .dt-perpage-select:focus {
    outline: none;
    border-color: var(--primary);
}

.dt-container .dt-export-btn {
    padding: 7px 14px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: var(--success);
    color: #fff;
    cursor: pointer;
    transition: all 0.15s;
}

.dt-container .dt-export-btn:hover {
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
    display: inline-block;
}

.dt-container .dt-bulk-export-btn {
    padding: 7px 14px;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    font-weight: 500;
    background: var(--primary);
    color: #fff;
    cursor: pointer;
    display: none;
}

.dt-container .dt-bulk-export-btn.show {
    display: inline-block;
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
    color: var(--text-primary);
}

.dt-container .dt-table tbody tr:hover {
    background: var(--body-bg);
}

.dt-container .dt-table tbody tr.selected {
    background: var(--primary-light);
}

/* Checkbox Column - Fixed small width */
.dt-container .dt-table th.dt-checkbox-col,
.dt-container .dt-table td.dt-checkbox-col {
    width: 45px;
    min-width: 45px;
    max-width: 45px;
    text-align: center;
    padding: 11px 10px;
    overflow: visible;
}

.dt-container .dt-checkbox {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: var(--primary);
}

/* Actions Column - Fixed width for buttons */
.dt-container .dt-table th.dt-actions-col,
.dt-container .dt-table td.dt-actions-col {
    width: 150px;
    min-width: 150px;
    max-width: 150px;
    overflow: visible;
}

/* Sortable */
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
    color: var(--text-muted);
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
    text-transform: capitalize;
}

.dt-container .dt-badge-active,
.dt-container .dt-badge-success,
.dt-container .dt-badge-completed {
    background: var(--success-light);
    color: var(--success);
}

.dt-container .dt-badge-inactive,
.dt-container .dt-badge-secondary,
.dt-container .dt-badge-draft {
    background: var(--body-bg);
    color: var(--text-muted);
}

.dt-container .dt-badge-graduated,
.dt-container .dt-badge-info {
    background: var(--primary-light);
    color: var(--primary);
}

.dt-container .dt-badge-pending,
.dt-container .dt-badge-warning {
    background: var(--warning-light);
    color: var(--warning);
}

.dt-container .dt-badge-cancelled,
.dt-container .dt-badge-danger,
.dt-container .dt-badge-failed {
    background: var(--danger-light);
    color: var(--danger);
}

/* Action Buttons */
.dt-container .dt-actions {
    display: flex;
    gap: 4px;
    flex-wrap: nowrap;
}

.dt-container .dt-btn {
    padding: 4px 8px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    text-decoration: none;
    display: inline-block;
    font-weight: 500;
    white-space: nowrap;
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

/* Loading */
.dt-container .dt-loading {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}
</style>

<script>
(function(){
    document.addEventListener('DOMContentLoaded',function(){
        document.querySelectorAll('table.dt-table').forEach(initDT);
    });

    function initDT(table){
        var route=table.dataset.route;
        if(!route){console.error('dt-table: data-route required');return;}

        var state={page:1,perPage:10,search:'',sort:'id',dir:'desc',selected:[]};
        var hasCheckbox=table.classList.contains('dt-checkbox');

        var wrapper=document.createElement('div');
        wrapper.className='dt-container';
        table.parentNode.insertBefore(wrapper,table);

        var hasSearch=table.classList.contains('dt-search');
        var hasExport=table.classList.contains('dt-export');
        var hasPerPage=table.classList.contains('dt-perpage');

        var selectedCountEl, bulkDeleteBtn, bulkExportBtn;

        if(hasSearch||hasExport||hasPerPage||hasCheckbox){
            var toolbar=document.createElement('div');
            toolbar.className='dt-toolbar';

            var left=document.createElement('div');
            left.className='dt-toolbar-left';

            if(hasSearch){
                var inp=document.createElement('input');
                inp.type='text';inp.placeholder='Search...';inp.className='dt-search-input';
                var t;inp.oninput=function(){clearTimeout(t);t=setTimeout(function(){state.search=inp.value;state.page=1;load();},300);};
                left.appendChild(inp);
            }

            if(hasCheckbox){
                selectedCountEl=document.createElement('span');
                selectedCountEl.className='dt-selected-count';
                left.appendChild(selectedCountEl);
            }

            var right=document.createElement('div');
            right.className='dt-toolbar-right';

            if(hasCheckbox){
                bulkDeleteBtn=document.createElement('button');
                bulkDeleteBtn.type='button';
                bulkDeleteBtn.className='dt-bulk-delete-btn';
                bulkDeleteBtn.textContent='Delete Selected';
                bulkDeleteBtn.onclick=function(){bulkDelete();};
                right.appendChild(bulkDeleteBtn);

                bulkExportBtn=document.createElement('button');
                bulkExportBtn.type='button';
                bulkExportBtn.className='dt-bulk-export-btn';
                bulkExportBtn.textContent='Export Selected';
                bulkExportBtn.onclick=function(){bulkExport();};
                right.appendChild(bulkExportBtn);
            }

            if(hasPerPage){
                var sel=document.createElement('select');
                sel.className='dt-perpage-select';
                [10,25,50,100].forEach(function(n){var o=document.createElement('option');o.value=n;o.textContent=n+' rows';sel.appendChild(o);});
                sel.onchange=function(){state.perPage=parseInt(this.value);state.page=1;load();};
                right.appendChild(sel);
            }

            if(hasExport){
                var btn=document.createElement('button');
                btn.type='button';btn.className='dt-export-btn';btn.textContent='Export All';
                btn.onclick=function(){window.location.href=route+'?export=csv&search='+encodeURIComponent(state.search)+'&sort='+state.sort+'&dir='+state.dir;};
                right.appendChild(btn);
            }

            toolbar.appendChild(left);
            toolbar.appendChild(right);
            wrapper.appendChild(toolbar);
        }

        // Add table wrapper for horizontal scroll
        var tableWrapper=document.createElement('div');
        tableWrapper.className='dt-table-wrapper';
        tableWrapper.appendChild(table);
        wrapper.appendChild(tableWrapper);

        if(hasCheckbox){
            var thead=table.querySelector('thead tr');
            var checkTh=document.createElement('th');
            checkTh.className='dt-checkbox-col';
            checkTh.innerHTML='<input type="checkbox" class="dt-checkbox dt-check-all">';
            thead.insertBefore(checkTh,thead.firstChild);

            checkTh.querySelector('.dt-check-all').onchange=function(){
                var checked=this.checked;
                table.querySelectorAll('tbody .dt-row-check').forEach(function(cb){
                    cb.checked=checked;
                    var id=parseInt(cb.dataset.id);
                    var tr=cb.closest('tr');
                    if(checked){
                        if(state.selected.indexOf(id)===-1)state.selected.push(id);
                        tr.classList.add('selected');
                    }else{
                        state.selected=state.selected.filter(function(x){return x!==id;});
                        tr.classList.remove('selected');
                    }
                });
                updateBulkUI();
            };
        }

        // Mark actions column
        table.querySelectorAll('thead th').forEach(function(th){
            if(th.dataset.render==='actions'){
                th.classList.add('dt-actions-col');
            }
        });

        var pag=document.createElement('div');
        pag.className='dt-pagination';
        pag.innerHTML='<span class="dt-info"></span><div class="dt-pages"></div>';
        wrapper.appendChild(pag);

        var tbody=table.querySelector('tbody')||table.createTBody();
        var infoEl=pag.querySelector('.dt-info');
        var pagesEl=pag.querySelector('.dt-pages');

        var cols=[];
        table.querySelectorAll('thead th:not(.dt-checkbox-col)').forEach(function(th){
            cols.push({col:th.dataset.col||null,render:th.dataset.render||null});
            if(th.classList.contains('dt-sort')&&th.dataset.col){
                th.onclick=function(){
                    var c=this.dataset.col;
                    if(state.sort===c){state.dir=state.dir==='asc'?'desc':'asc';}else{state.sort=c;state.dir='asc';}
                    table.querySelectorAll('th.dt-sort').forEach(function(h){h.classList.remove('asc','desc');});
                    this.classList.add(state.dir);
                    load();
                };
            }
        });

        function updateBulkUI(){
            if(!hasCheckbox)return;
            var count=state.selected.length;
            if(count>0){
                selectedCountEl.textContent=count+' selected';
                selectedCountEl.classList.add('show');
                bulkDeleteBtn.classList.add('show');
                bulkExportBtn.classList.add('show');
            }else{
                selectedCountEl.classList.remove('show');
                bulkDeleteBtn.classList.remove('show');
                bulkExportBtn.classList.remove('show');
            }
        }

        function load(){
            tbody.innerHTML='<tr><td colspan="'+(cols.length+(hasCheckbox?1:0))+'" class="dt-loading">Loading...</td></tr>';
            fetch(route+'?page='+state.page+'&per_page='+state.perPage+'&search='+encodeURIComponent(state.search)+'&sort='+state.sort+'&dir='+state.dir)
            .then(function(r){return r.json();})
            .then(function(json){render(json);renderPag(json);})
            .catch(function(e){tbody.innerHTML='<tr><td colspan="'+(cols.length+(hasCheckbox?1:0))+'" class="dt-loading" style="color:var(--danger)">Error loading</td></tr>';});
        }

        function render(json){
            if(!json.data||!json.data.length){
                tbody.innerHTML='<tr><td colspan="'+(cols.length+(hasCheckbox?1:0))+'" class="dt-loading">No data found</td></tr>';
                return;
            }
            tbody.innerHTML=json.data.map(function(row){
                var isSelected=state.selected.indexOf(row.id)!==-1;
                var checkboxCell=hasCheckbox?'<td class="dt-checkbox-col"><input type="checkbox" class="dt-checkbox dt-row-check" data-id="'+row.id+'" '+(isSelected?'checked':'')+'></td>':'';
                return '<tr data-id="'+row.id+'" class="'+(isSelected?'selected':'')+'">'+checkboxCell+cols.map(function(c,i){
                    var cls=c.render==='actions'?' class="dt-actions-col"':'';
                    return '<td'+cls+'>'+cell(row,c)+'</td>';
                }).join('')+'</tr>';
            }).join('');

            tbody.querySelectorAll('.dt-row-check').forEach(function(cb){
                cb.onchange=function(){
                    var id=parseInt(this.dataset.id);
                    var tr=this.closest('tr');
                    if(this.checked){
                        if(state.selected.indexOf(id)===-1)state.selected.push(id);
                        tr.classList.add('selected');
                    }else{
                        state.selected=state.selected.filter(function(x){return x!==id;});
                        tr.classList.remove('selected');
                    }
                    updateBulkUI();
                    var allChecked=tbody.querySelectorAll('.dt-row-check').length===tbody.querySelectorAll('.dt-row-check:checked').length;
                    var selectAll=table.querySelector('.dt-check-all');
                    if(selectAll)selectAll.checked=allChecked;
                };
            });

            tbody.querySelectorAll('.dt-btn-delete').forEach(function(b){
                b.onclick=function(e){e.preventDefault();del(this.dataset.id);};
            });

            updateBulkUI();
        }

        function cell(row,c){
            var v=c.col?row[c.col]:null;
            switch(c.render){
                case'date':return v?new Date(v).toLocaleDateString():'';
                case'datetime':return v?new Date(v).toLocaleString():'';
                case'badge':var cls=badge(v);return'<span class="dt-badge dt-badge-'+cls+'">'+(v||'')+'</span>';
                case'actions':
                    var h='<div class="dt-actions">';
                    if(row._show_url&&row._show_url!=='#')h+='<a href="'+row._show_url+'" class="dt-btn dt-btn-view">View</a>';
                    if(row._edit_url&&row._edit_url!=='#')h+='<a href="'+row._edit_url+'" class="dt-btn dt-btn-edit">Edit</a>';
                    h+='<button class="dt-btn dt-btn-delete" data-id="'+row.id+'">Delete</button>';
                    h+='</div>';
                    return h;
                default:return v!==null&&v!==undefined?v:'';
            }
        }

        function badge(s){var m={active:'active',inactive:'inactive',graduated:'graduated',pending:'pending',cancelled:'cancelled',completed:'success',draft:'secondary',success:'success',failed:'danger'};return m[s]||'secondary';}

        function renderPag(json){
            infoEl.textContent='Page '+json.current_page+' of '+json.last_page+' ('+json.total+' total)';
            var h='<button '+(json.current_page<=1?'disabled':'')+' data-p="'+(json.current_page-1)+'">Prev</button>';
            for(var i=1;i<=json.last_page;i++){
                if(i===1||i===json.last_page||(i>=json.current_page-1&&i<=json.current_page+1)){
                    h+='<button class="'+(i===json.current_page?'active':'')+'" data-p="'+i+'">'+i+'</button>';
                }else if(i===json.current_page-2||i===json.current_page+2){
                    h+='<span style="color:var(--text-muted);padding:0 4px">...</span>';
                }
            }
            h+='<button '+(json.current_page>=json.last_page?'disabled':'')+' data-p="'+(json.current_page+1)+'">Next</button>';
            pagesEl.innerHTML=h;
            pagesEl.querySelectorAll('button').forEach(function(b){
                b.onclick=function(){var p=parseInt(this.dataset.p);if(p&&!this.disabled){state.page=p;load();}};
            });
        }

        function del(id){
            if(!confirm('Delete this item?'))return;
            var dr=route.replace('/data','')+'/'+id;
            var csrf=document.querySelector('meta[name="csrf-token"]');
            fetch(dr,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf?csrf.content:'','Accept':'application/json'}})
            .then(function(){state.selected=state.selected.filter(function(x){return x!==parseInt(id);});load();})
            .catch(function(){alert('Delete failed');});
        }

        function bulkDelete(){
            if(state.selected.length===0)return;
            if(!confirm('Delete '+state.selected.length+' selected items?'))return;
            var dr=route.replace('/data','')+'/bulk-delete';
            var csrf=document.querySelector('meta[name="csrf-token"]');
            fetch(dr,{
                method:'POST',
                headers:{'X-CSRF-TOKEN':csrf?csrf.content:'','Accept':'application/json','Content-Type':'application/json'},
                body:JSON.stringify({ids:state.selected})
            })
            .then(function(){state.selected=[];load();})
            .catch(function(){alert('Bulk delete failed');});
        }

        function bulkExport(){
            if(state.selected.length===0)return;
            window.location.href=route+'?export=csv&ids='+state.selected.join(',');
        }

        load();
        table.dtReload=load;
        table.dtClearSelection=function(){state.selected=[];updateBulkUI();};
    }
})();
</script>
