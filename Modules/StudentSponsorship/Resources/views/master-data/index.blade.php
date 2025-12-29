<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    
    .tabs-nav { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
    .tab-link { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); display: inline-flex; align-items: center; gap: 8px; }
    .tab-link:hover { border-color: var(--primary); color: var(--primary); }
    .tab-link.active { background: var(--primary); color: #fff; border-color: var(--primary); }
    .tab-link .badge { background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px; }
    
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .table-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .table-card-body { padding: 0; }
    
    .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; border: none; cursor: pointer; }
    .btn-add:hover { color: #fff; opacity: 0.9; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: var(--body-bg); padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--card-border); }
    .data-table td { padding: 12px 16px; border-bottom: 1px solid var(--card-border); color: var(--text-primary); }
    .data-table tr:hover { background: var(--body-bg); }
    
    .count-badge { background: var(--primary-light); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
    .date-text { color: var(--text-muted); font-size: 13px; }
    
    .btn-sm { padding: 6px 10px; border-radius: 6px; font-size: 12px; border: none; cursor: pointer; margin-right: 4px; }
    .btn-edit { background: #fef3c7; color: #92400e; }
    .btn-edit:hover { background: #fde68a; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #fecaca; }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
    .empty-msg { padding: 40px; text-align: center; color: var(--text-muted); }
    
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #dc2626; }
    
    /* Modal */
    .modal-bg { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
    .modal-bg.show { display: flex; }
    .modal-box { background: var(--card-bg); border-radius: 12px; width: 100%; max-width: 450px; margin: 20px; }
    .modal-head { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; }
    .modal-head h3 { margin: 0; font-size: 16px; font-weight: 600; }
    .modal-close { background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted); }
    .modal-body { padding: 20px; }
    .modal-body label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: var(--text-primary); }
    .modal-body input { width: 100%; padding: 10px 12px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 14px; box-sizing: border-box; }
    .modal-body input:focus { outline: none; border-color: var(--primary); }
    .modal-foot { padding: 12px 20px; border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 10px; }
    .btn-cancel { padding: 8px 16px; border: 1px solid var(--card-border); border-radius: 8px; background: var(--card-bg); cursor: pointer; }
    .btn-save { padding: 8px 16px; border: none; border-radius: 8px; background: var(--primary); color: #fff; cursor: pointer; font-weight: 600; }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            Master Data
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Tabs Navigation -->
    <div class="tabs-nav">
        <a href="{{ route('admin.studentsponsorship.master-data.index', ['tab' => 'schools']) }}" class="tab-link {{ $tab == 'schools' ? 'active' : '' }}">🏫 Schools <span class="badge">{{ $schools->count() }}</span></a>
        <a href="{{ route('admin.studentsponsorship.master-data.index', ['tab' => 'universities']) }}" class="tab-link {{ $tab == 'universities' ? 'active' : '' }}">🎓 Universities <span class="badge">{{ $universities->count() }}</span></a>
        <a href="{{ route('admin.studentsponsorship.master-data.index', ['tab' => 'programs']) }}" class="tab-link {{ $tab == 'programs' ? 'active' : '' }}">📚 Programs <span class="badge">{{ $programs->count() }}</span></a>
        <a href="{{ route('admin.studentsponsorship.master-data.index', ['tab' => 'banks']) }}" class="tab-link {{ $tab == 'banks' ? 'active' : '' }}">🏦 Banks <span class="badge">{{ $banks->count() }}</span></a>
    </div>

    <!-- Schools Tab -->
    <div class="tab-content {{ $tab == 'schools' ? 'active' : '' }}">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">🏫 School Names</div>
                <button class="btn-add" onclick="openModal('school')">+ Add School</button>
            </div>
            <div class="table-card-body">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Name</th><th>Students</th><th>Created</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($schools as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="count-badge">{{ $item->students_count ?? 0 }}</span></td>
                            <td class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <button class="btn-sm btn-edit" onclick="openModal('school', {{ $item->id }}, '{{ addslashes($item->name) }}')">✏️ Edit</button>
                                <form method="POST" action="{{ route('admin.studentsponsorship.master-data.schools.delete', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-msg">No schools found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Universities Tab -->
    <div class="tab-content {{ $tab == 'universities' ? 'active' : '' }}">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">🎓 University Names</div>
                <button class="btn-add" onclick="openModal('university')">+ Add University</button>
            </div>
            <div class="table-card-body">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Name</th><th>Students</th><th>Created</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($universities as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="count-badge">{{ $item->students_count ?? 0 }}</span></td>
                            <td class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <button class="btn-sm btn-edit" onclick="openModal('university', {{ $item->id }}, '{{ addslashes($item->name) }}')">✏️ Edit</button>
                                <form method="POST" action="{{ route('admin.studentsponsorship.master-data.universities.delete', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-msg">No universities found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Programs Tab -->
    <div class="tab-content {{ $tab == 'programs' ? 'active' : '' }}">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">📚 Programs / Courses</div>
                <button class="btn-add" onclick="openModal('program')">+ Add Program</button>
            </div>
            <div class="table-card-body">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Name</th><th>Students</th><th>Created</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($programs as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="count-badge">{{ $item->students_count ?? 0 }}</span></td>
                            <td class="date-text">{{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <button class="btn-sm btn-edit" onclick="openModal('program', {{ $item->id }}, '{{ addslashes($item->name) }}')">✏️ Edit</button>
                                <form method="POST" action="{{ route('admin.studentsponsorship.master-data.programs.delete', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-msg">No programs found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Banks Tab -->
    <div class="tab-content {{ $tab == 'banks' ? 'active' : '' }}">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">🏦 Bank Names</div>
                <button class="btn-add" onclick="openModal('bank')">+ Add Bank</button>
            </div>
            <div class="table-card-body">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Name</th><th>Students</th><th>Created</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($banks as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><span class="count-badge">{{ $item->students_count ?? 0 }}</span></td>
                            <td class="date-text">{{ $item->created_on ? \Carbon\Carbon::parse($item->created_on)->format('M d, Y') : '-' }}</td>
                            <td>
                                <button class="btn-sm btn-edit" onclick="openModal('bank', {{ $item->id }}, '{{ addslashes($item->name) }}')">✏️ Edit</button>
                                <form method="POST" action="{{ route('admin.studentsponsorship.master-data.banks.delete', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-msg">No banks found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal-bg" id="itemModal">
    <div class="modal-box">
        <div class="modal-head">
            <h3 id="modalTitle">Add Item</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="itemForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-body">
                <label id="nameLabel">Name *</label>
                <input type="text" name="name" id="itemName" required placeholder="Enter name">
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
var routes = {
    school: { store: '{{ route("admin.studentsponsorship.master-data.schools.store") }}', update: '{{ url("admin/studentsponsorship/master-data/schools") }}' },
    university: { store: '{{ route("admin.studentsponsorship.master-data.universities.store") }}', update: '{{ url("admin/studentsponsorship/master-data/universities") }}' },
    program: { store: '{{ route("admin.studentsponsorship.master-data.programs.store") }}', update: '{{ url("admin/studentsponsorship/master-data/programs") }}' },
    bank: { store: '{{ route("admin.studentsponsorship.master-data.banks.store") }}', update: '{{ url("admin/studentsponsorship/master-data/banks") }}' }
};

var titles = { school: 'School', university: 'University', program: 'Program', bank: 'Bank' };

function openModal(type, id, name) {
    document.getElementById('modalTitle').textContent = (id ? 'Edit ' : 'Add ') + titles[type];
    document.getElementById('nameLabel').textContent = titles[type] + ' Name *';
    document.getElementById('itemName').value = name || '';
    
    var form = document.getElementById('itemForm');
    if (id) {
        form.action = routes[type].update + '/' + id;
        document.getElementById('formMethod').value = 'PUT';
    } else {
        form.action = routes[type].store;
        document.getElementById('formMethod').value = 'POST';
    }
    
    document.getElementById('itemModal').classList.add('show');
    document.getElementById('itemName').focus();
}

function closeModal() {
    document.getElementById('itemModal').classList.remove('show');
}

document.getElementById('itemModal').onclick = function(e) {
    if (e.target === this) closeModal();
};
</script>
