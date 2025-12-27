{{-- <x-layouts.app> --}}
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 600; color: #111827; margin: 0; }
        
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        @media (max-width: 768px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
        .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; }
        .stat-card .label { font-size: 13px; color: #6b7280; margin-bottom: 8px; }
        .stat-card .value { font-size: 28px; font-weight: 600; color: #111827; }
        .stat-card.success .value { color: #10b981; }
        .stat-card.danger .value { color: #ef4444; }
        .stat-card.primary .value { color: #6366f1; }
        
        .filters-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
        .search-box { flex: 1; min-width: 200px; max-width: 320px; position: relative; }
        .search-box input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
        }
        .search-box input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .filter-select {
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            min-width: 140px;
            background: white;
        }
        
        .table-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table td { padding: 16px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover { background: #fafafa; }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
        }
        .avatar-1 { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .avatar-2 { background: linear-gradient(135deg, #10b981, #14b8a6); }
        .avatar-3 { background: linear-gradient(135deg, #f59e0b, #f97316); }
        .avatar-4 { background: linear-gradient(135deg, #ef4444, #ec4899); }
        .avatar-5 { background: linear-gradient(135deg, #3b82f6, #6366f1); }
        
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-details .name { font-weight: 500; color: #111827; margin-bottom: 2px; }
        .user-details .email { font-size: 13px; color: #6b7280; }
        
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .status-dot.active { background: #10b981; }
        .status-dot.inactive { background: #ef4444; }
        
        .role-pills { display: flex; flex-wrap: wrap; gap: 4px; }
        .role-pill { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
        .role-pill.super-admin { background: #fef3c7; color: #92400e; }
        .role-pill.admin { background: #dbeafe; color: #1e40af; }
        .role-pill.default { background: #f3f4f6; color: #374151; }
        
        .actions { display: flex; gap: 8px; }
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .action-btn:hover { border-color: #6366f1; color: #6366f1; background: #f5f3ff; }
        .action-btn.danger:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
        
        .empty-state { padding: 60px 20px; text-align: center; }
        .empty-state i { font-size: 48px; color: #d1d5db; margin-bottom: 16px; }
        .empty-state p { color: #6b7280; margin: 0; }
        
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        .table-footer .info { font-size: 13px; color: #6b7280; }
        
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal { background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; }
        .modal h3 { margin: 0 0 12px 0; font-size: 18px; color: #111827; }
        .modal p { margin: 0 0 24px 0; color: #6b7280; }
        .modal-actions { display: flex; gap: 12px; justify-content: flex-end; }
        
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; border: 1px solid #10b981; color: #065f46; }
        .alert-error { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; }
    </style>

    @php
        $totalStaff = $staffs->total();
        $activeStaff = \App\Models\Admin\Staff::where('status', true)->count();
        $inactiveStaff = \App\Models\Admin\Staff::where('status', false)->count();
        $totalRoles = $roles->count();
    @endphp

    <div class="page-header">
        <h1>Staff Management</h1>
        <a href="{{ route('admin.settings.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Staff</a>
    </div>

    <div class="stats-row">
        <div class="stat-card"><div class="label">Total Staff</div><div class="value">{{ $totalStaff }}</div></div>
        <div class="stat-card success"><div class="label">Active</div><div class="value">{{ $activeStaff }}</div></div>
        <div class="stat-card danger"><div class="label">Inactive</div><div class="value">{{ $inactiveStaff }}</div></div>
        <div class="stat-card primary"><div class="label">Roles</div><div class="value">{{ $totalRoles }}</div></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.settings.users.index') }}">
        <div class="filters-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search staff..." value="{{ request('search') }}" onkeydown="if(event.key==='Enter') this.form.submit()">
            </div>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                @endforeach
            </select>
            @if(request()->hasAny(['search', 'status', 'role']))
                <a href="{{ route('admin.settings.users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Clear</a>
            @endif
        </div>
    </form>

    <div class="table-card">
        @if($staffs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Employee Code</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $index => $staff)
                        @php
                            $admin = $staff->admin;
                            $avatarClass = 'avatar-' . (($index % 5) + 1);
                            $initials = strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1));
                            $staffRoles = $admin ? $admin->roles->pluck('name')->toArray() : [];
                        @endphp
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="avatar {{ $avatarClass }}">{{ $initials }}</div>
                                    <div class="user-details">
                                        <div class="name">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                        <div class="email">{{ $staff->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span style="color: #6b7280;">{{ $staff->employee_code ?: '-' }}</span></td>
                            <td><span style="color: #6b7280;">{{ $staff->department ?: '-' }}</span></td>
                            <td>
                                <div class="role-pills">
                                    @forelse($staffRoles as $role)
                                        @php
                                            $pillClass = 'default';
                                            if ($role === 'super-admin') $pillClass = 'super-admin';
                                            elseif ($role === 'admin') $pillClass = 'admin';
                                        @endphp
                                        <span class="role-pill {{ $pillClass }}">{{ ucfirst(str_replace('-', ' ', $role)) }}</span>
                                    @empty
                                        <span style="color: #9ca3af;">No roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if($staff->status)
                                    <span class="badge badge-success"><span class="status-dot active"></span> Active</span>
                                @else
                                    <span class="badge badge-danger"><span class="status-dot inactive"></span> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.settings.users.edit', $staff->admin_id) }}" class="action-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                    @if(!$admin || $admin->id !== auth('admin')->id())
                                        <button type="button" class="action-btn danger" onclick="confirmDelete({{ $staff->admin_id }}, '{{ $staff->first_name }} {{ $staff->last_name }}')" title="Delete"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-footer">
                <div class="info">Showing {{ $staffs->firstItem() }} to {{ $staffs->lastItem() }} of {{ $staffs->total() }} staff</div>
                {{ $staffs->links() }}
            </div>
        @else
            <div class="empty-state"><i class="fas fa-users"></i><p>No staff members found</p></div>
        @endif
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h3>Delete Staff</h3>
            <p>Are you sure you want to delete <strong id="deleteStaffName"></strong>? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;

        function confirmDelete(id, name) {
            deleteId = id;
            document.getElementById('deleteStaffName').textContent = name;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
            deleteId = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            fetch(`{{ url('admin/settings/users') }}/${deleteId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) window.location.reload();
                else { alert(data.message || 'Failed to delete'); this.disabled = false; this.innerHTML = 'Delete'; }
            })
            .catch(() => { alert('An error occurred'); this.disabled = false; this.innerHTML = 'Delete'; });
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
    </script>
{{-- </x-layouts.app> --}}