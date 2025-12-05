(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dt-table').forEach(initTable);
    });

    function initTable(table) {
        const wrapper = table.closest('.dt-wrapper') || createWrapper(table);
        const route = table.dataset.route;
        const state = { page: 1, perPage: 10, search: '', sort: 'id', dir: 'desc', filters: {} };

        // Auto-add features based on classes
        if (table.classList.contains('dt-search')) addSearchBox(wrapper, state, loadData);
        if (table.classList.contains('dt-export')) addExportBtns(wrapper, route, state);
        if (table.classList.contains('dt-perpage')) addPerPage(wrapper, state, loadData);

        // Sortable headers
        table.querySelectorAll('th[data-col]').forEach(th => {
            th.classList.add('sortable');
            th.onclick = () => {
                state.dir = (state.sort === th.dataset.col && state.dir === 'asc') ? 'desc' : 'asc';
                state.sort = th.dataset.col;
                updateSortIcons(table, state);
                loadData();
            };
        });

        // Filters
        wrapper.querySelectorAll('[data-filter]').forEach(el => {
            el.onchange = () => { state.filters[el.dataset.filter] = el.value; state.page = 1; loadData(); };
        });

        async function loadData() {
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '<tr><td colspan="100" class="text-center">Loading...</td></tr>';

            const params = new URLSearchParams({
                page: state.page, per_page: state.perPage, search: state.search,
                sort: state.sort, dir: state.dir, filters: JSON.stringify(state.filters)
            });

            try {
                const res = await fetch(`${route}?${params}`);
                const json = await res.json();
                renderRows(table, json);
                renderPagination(wrapper, json, state, loadData);
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="100" class="text-center text-danger">Error loading</td></tr>';
            }
        }

        loadData();
        table.dtReload = loadData;
    }

    function createWrapper(table) {
        const w = document.createElement('div');
        w.className = 'dt-wrapper';
        table.parentNode.insertBefore(w, table);
        w.appendChild(table);
        return w;
    }

    function getToolbar(wrapper) {
        let t = wrapper.querySelector('.dt-toolbar');
        if (!t) { t = document.createElement('div'); t.className = 'dt-toolbar'; wrapper.prepend(t); }
        return t;
    }

    function addSearchBox(wrapper, state, reload) {
        const toolbar = getToolbar(wrapper);
        const div = document.createElement('div');
        div.className = 'dt-search-box';
        div.innerHTML = '<input type="text" placeholder="Search..." class="form-control form-control-sm">';
        let timer;
        div.querySelector('input').oninput = (e) => {
            clearTimeout(timer);
            timer = setTimeout(() => { state.search = e.target.value; state.page = 1; reload(); }, 300);
        };
        toolbar.prepend(div);
    }

    function addExportBtns(wrapper, route, state) {
        const toolbar = getToolbar(wrapper);
        const div = document.createElement('div');
        div.className = 'dt-export-btns';
        div.innerHTML = `<div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Export</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-type="csv">CSV</a></li>
                <li><a class="dropdown-item" href="#" data-type="excel">Excel</a></li>
            </ul>
        </div>`;
        div.querySelectorAll('[data-type]').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                const params = new URLSearchParams({ export: btn.dataset.type, search: state.search, filters: JSON.stringify(state.filters) });
                window.location.href = `${route}?${params}`;
            };
        });
        toolbar.appendChild(div);
    }

    function addPerPage(wrapper, state, reload) {
        const toolbar = getToolbar(wrapper);
        const div = document.createElement('div');
        div.className = 'dt-perpage';
        div.innerHTML = `<select class="form-select form-select-sm">
            <option value="10">10</option><option value="25">25</option>
            <option value="50">50</option><option value="100">100</option>
        </select>`;
        div.querySelector('select').onchange = (e) => { state.perPage = +e.target.value; state.page = 1; reload(); };
        toolbar.appendChild(div);
    }

    function updateSortIcons(table, state) {
        table.querySelectorAll('th[data-col]').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
            if (th.dataset.col === state.sort) th.classList.add(state.dir === 'asc' ? 'sort-asc' : 'sort-desc');
        });
    }

    function renderRows(table, json) {
        const tbody = table.querySelector('tbody');
        if (!json.data.length) { tbody.innerHTML = '<tr><td colspan="100" class="text-center">No data</td></tr>'; return; }

        const cols = table.querySelectorAll('th[data-col], th[data-render]');
        tbody.innerHTML = json.data.map(row => `<tr data-id="${row.id}">
            ${Array.from(cols).map(th => `<td>${renderCell(row, th)}</td>`).join('')}
        </tr>`).join('');

        // Delete buttons
        tbody.querySelectorAll('.dt-delete').forEach(btn => {
            btn.onclick = () => { if (confirm('Delete?')) deleteRow(table.dataset.route, btn.dataset.id, table); };
        });
    }

    function renderCell(row, th) {
        const col = th.dataset.col;
        const render = th.dataset.render;
        const val = col ? row[col] : null;

        switch (render) {
            case 'date': return val ? new Date(val).toLocaleDateString() : '';
            case 'datetime': return val ? new Date(val).toLocaleString() : '';
            case 'badge': return `<span class="badge bg-${val === 'active' ? 'success' : val === 'inactive' ? 'secondary' : 'info'}">${val || ''}</span>`;
            case 'actions': return `
                <a href="${row._edit_url || '#'}" class="btn btn-sm btn-outline-primary">Edit</a>
                <button class="btn btn-sm btn-outline-danger dt-delete" data-id="${row.id}">Delete</button>`;
            default: return val ?? '';
        }
    }

    function renderPagination(wrapper, json, state, reload) {
        let pag = wrapper.querySelector('.dt-pagination');
        if (!pag) { pag = document.createElement('div'); pag.className = 'dt-pagination'; wrapper.appendChild(pag); }

        pag.innerHTML = `
            <span class="dt-info">Page ${json.current_page} of ${json.last_page} (${json.total} records)</span>
            <div class="dt-pages">
                <button class="btn btn-sm btn-outline-secondary" ${json.current_page <= 1 ? 'disabled' : ''} data-page="${json.current_page - 1}">Prev</button>
                <button class="btn btn-sm btn-outline-secondary" ${json.current_page >= json.last_page ? 'disabled' : ''} data-page="${json.current_page + 1}">Next</button>
            </div>`;

        pag.querySelectorAll('[data-page]').forEach(btn => {
            btn.onclick = () => { state.page = +btn.dataset.page; reload(); };
        });
    }

    async function deleteRow(route, id, table) {
        await fetch(`${route.replace('/data', '')}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Accept': 'application/json' }
        });
        table.dtReload();
    }
})();
