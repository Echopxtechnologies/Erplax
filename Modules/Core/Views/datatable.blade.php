{{-- 
    DataTable Assets - Modules/Core/Views/datatable.blade.php
    Uses app layout CSS variables for automatic dark/light mode support
--}}

<style>
/* DataTable Container */
.dt-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin: 15px 0;
}

/* Toolbar */
.dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--card-bg);
    border-bottom: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 10px;
}
.dt-toolbar-left,
.dt-toolbar-right {
    display: flex;
    gap: 10px;
    align-items: center;
}

/* Search Input */
.dt-search-input {
    padding: 7px 12px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    min-width: 200px;
}
.dt-search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-light);
}
.dt-search-input::placeholder {
    color: var(--text-muted);
}

/* Per Page Select */
.dt-perpage-select {
    padding: 7px 10px;
    border: 1px solid var(--input-border);
    border-radius: var(--radius-md);
    font-size: var(--font-sm);
    background: var(--input-bg);
    color: var(--input-text);
    cursor: pointer;
}
.dt-perpage-select:focus {
    outline: none;
    border-color: var(--primary);
}

/* Export Button */
.dt-export-btn {
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
.dt-export-btn:hover {
    opacity: 0.9;
}

/* Table */
.dt-table {
    width: 100%;
    border-collapse: collapse;
}
.dt-table th,
.dt-table td {
    padding: 11px 14px;
    text-align: left;
    border-bottom: 1px solid var(--card-border);
    color: var(--text-primary);
    font-size: var(--font-sm);
}
.dt-table th {
    background: var(--body-bg);
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
}
.dt-table tbody tr:hover {
    background: var(--body-bg);
}

/* Sortable Headers */
.dt-table th.dt-sort {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 22px;
}
.dt-table th.dt-sort:hover {
    background: var(--card-border);
}
.dt-table th.dt-sort::after {
    content: '↕';
    position: absolute;
    right: 8px;
    opacity: 0.3;
    font-size: 11px;
    color: var(--text-muted);
}
.dt-table th.dt-sort.asc::after {
    content: '↑';
    opacity: 1;
    color: var(--primary);
}
.dt-table th.dt-sort.desc::after {
    content: '↓';
    opacity: 1;
    color: var(--primary);
}

/* Pagination */
.dt-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: var(--card-bg);
    border-top: 1px solid var(--card-border);
    flex-wrap: wrap;
    gap: 10px;
}
.dt-info {
    color: var(--text-muted);
    font-size: var(--font-sm);
}
.dt-pages {
    display: flex;
    gap: 4px;
}
.dt-pages button {
    padding: 5px 10px;
    border: 1px solid var(--input-border);
    background: var(--input-bg);
    color: var(--text-primary);
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    transition: all 0.15s;
}
.dt-pages button:hover:not(:disabled) {
    background: var(--body-bg);
    border-color: var(--primary);
}
.dt-pages button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
.dt-pages button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

/* Badges */
.dt-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: var(--font-xs);
    font-weight: 600;
    display: inline-block;
    text-transform: capitalize;
}
.dt-badge-active,
.dt-badge-success,
.dt-badge-completed {
    background: var(--success-light);
    color: var(--success);
}
.dt-badge-inactive,
.dt-badge-secondary,
.dt-badge-draft {
    background: var(--body-bg);
    color: var(--text-muted);
}
.dt-badge-graduated,
.dt-badge-info {
    background: var(--primary-light);
    color: var(--primary);
}
.dt-badge-pending,
.dt-badge-warning {
    background: var(--warning-light);
    color: var(--warning);
}
.dt-badge-cancelled,
.dt-badge-danger,
.dt-badge-failed {
    background: var(--danger-light);
    color: var(--danger);
}

/* Action Buttons */
.dt-btn {
    padding: 4px 10px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: var(--font-xs);
    margin-right: 4px;
    text-decoration: none;
    display: inline-block;
    font-weight: 500;
    transition: all 0.15s;
}
.dt-btn-view {
    background: var(--primary);
    color: #fff;
}
.dt-btn-edit {
    background: var(--warning);
    color: #fff;
}
.dt-btn-delete {
    background: var(--danger);
    color: #fff;
}
.dt-btn:hover {
    opacity: 0.85;
}

/* Loading State */
.dt-loading {
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

        var state={page:1,perPage:10,search:'',sort:'id',dir:'desc'};

        var wrapper=document.createElement('div');
        wrapper.className='dt-container';
        table.parentNode.insertBefore(wrapper,table);

        var hasSearch=table.classList.contains('dt-search');
        var hasExport=table.classList.contains('dt-export');
        var hasPerPage=table.classList.contains('dt-perpage');

        if(hasSearch||hasExport||hasPerPage){
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

            var right=document.createElement('div');
            right.className='dt-toolbar-right';

            if(hasPerPage){
                var sel=document.createElement('select');
                sel.className='dt-perpage-select';
                [10,25,50,100].forEach(function(n){var o=document.createElement('option');o.value=n;o.textContent=n+' rows';sel.appendChild(o);});
                sel.onchange=function(){state.perPage=parseInt(this.value);state.page=1;load();};
                right.appendChild(sel);
            }

            if(hasExport){
                var btn=document.createElement('button');
                btn.type='button';btn.className='dt-export-btn';btn.textContent='Export CSV';
                btn.onclick=function(){window.location.href=route+'?export=csv&search='+encodeURIComponent(state.search)+'&sort='+state.sort+'&dir='+state.dir;};
                right.appendChild(btn);
            }

            toolbar.appendChild(left);
            toolbar.appendChild(right);
            wrapper.appendChild(toolbar);
        }

        wrapper.appendChild(table);

        var pag=document.createElement('div');
        pag.className='dt-pagination';
        pag.innerHTML='<span class="dt-info"></span><div class="dt-pages"></div>';
        wrapper.appendChild(pag);

        var tbody=table.querySelector('tbody')||table.createTBody();
        var infoEl=pag.querySelector('.dt-info');
        var pagesEl=pag.querySelector('.dt-pages');

        var cols=[];
        table.querySelectorAll('thead th').forEach(function(th){
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

        function load(){
            tbody.innerHTML='<tr><td colspan="'+cols.length+'" class="dt-loading">Loading...</td></tr>';
            fetch(route+'?page='+state.page+'&per_page='+state.perPage+'&search='+encodeURIComponent(state.search)+'&sort='+state.sort+'&dir='+state.dir)
            .then(function(r){return r.json();})
            .then(function(json){render(json);renderPag(json);})
            .catch(function(e){tbody.innerHTML='<tr><td colspan="'+cols.length+'" class="dt-loading" style="color:var(--danger)">Error loading</td></tr>';});
        }

        function render(json){
            if(!json.data||!json.data.length){tbody.innerHTML='<tr><td colspan="'+cols.length+'" class="dt-loading">No data found</td></tr>';return;}
            tbody.innerHTML=json.data.map(function(row){
                return '<tr data-id="'+row.id+'">'+cols.map(function(c){return '<td>'+cell(row,c)+'</td>';}).join('')+'</tr>';
            }).join('');
            tbody.querySelectorAll('.dt-btn-delete').forEach(function(b){
                b.onclick=function(e){e.preventDefault();del(this.dataset.id);};
            });
        }

        function cell(row,c){
            var v=c.col?row[c.col]:null;
            switch(c.render){
                case'date':return v?new Date(v).toLocaleDateString():'';
                case'datetime':return v?new Date(v).toLocaleString():'';
                case'badge':var cls=badge(v);return'<span class="dt-badge dt-badge-'+cls+'">'+(v||'')+'</span>';
                case'actions':
                    var h='';
                    if(row._show_url&&row._show_url!=='#')h+='<a href="'+row._show_url+'" class="dt-btn dt-btn-view">View</a>';
                    if(row._edit_url&&row._edit_url!=='#')h+='<a href="'+row._edit_url+'" class="dt-btn dt-btn-edit">Edit</a>';
                    h+='<button class="dt-btn dt-btn-delete" data-id="'+row.id+'">Delete</button>';
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
            .then(function(){load();}).catch(function(){alert('Delete failed');});
        }

        load();
        table.dtReload=load;
    }
})();
</script>
