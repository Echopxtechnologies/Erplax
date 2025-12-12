<x-layouts.app>
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Client Users</h1>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.settings.client.export') }}" class="btn btn-secondary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </a>
                <a href="{{ route('admin.settings.client.create') }}" class="btn btn-primary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Client User
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">All Client Users</h3>
                <div id="bulkActions" style="display: none; gap: 10px;">
                    <span id="selectedCount" style="color: #6b7280; font-size: 14px;"></span>
                    <button type="button" onclick="bulkDelete()" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;">
                        Delete Selected
                    </button>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; width: 40px;">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" style="width: 16px; height: 16px; cursor: pointer;">
                            </th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">#</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Name</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Email</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Status</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Created</th>
                            <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                            <tr style="border-bottom: 1px solid #e5e7eb;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px;">
                                    <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onclick="updateBulkActions()" style="width: 16px; height: 16px; cursor: pointer;">
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #111827;">
                                    {{ $users->firstItem() + $key }}
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span style="font-size: 14px; font-weight: 500; color: #111827;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $user->email }}
                                </td>
                                <td style="padding: 16px;">
                                    <button type="button" onclick="toggleStatus({{ $user->id }}, this)"
                                            style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border: none; border-radius: 20px; cursor: pointer;
                                                   background-color: {{ $user->is_active ? '#d1fae5' : '#fee2e2' }}; 
                                                   color: {{ $user->is_active ? '#065f46' : '#991b1b' }};">
                                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $user->is_active ? '#10b981' : '#ef4444' }};"></span>
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        {{-- View Button --}}
                                        <a href="{{ route('admin.settings.client.show', $user->id) }}" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #059669; border-radius: 4px;"
                                           onmouseover="this.style.backgroundColor='#d1fae5'" 
                                           onmouseout="this.style.backgroundColor='transparent'"
                                           title="View">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.settings.client.edit', $user->id) }}" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #4f46e5; border-radius: 4px;"
                                           onmouseover="this.style.backgroundColor='#eef2ff'" 
                                           onmouseout="this.style.backgroundColor='transparent'"
                                           title="Edit">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        
                                        {{-- Delete Button --}}
                                        <button type="button" onclick="deleteUser({{ $user->id }})"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #dc2626; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                                onmouseover="this.style.backgroundColor='#fef2f2'" 
                                                onmouseout="this.style.backgroundColor='transparent'"
                                                title="Delete">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 48px 16px; text-align: center; color: #6b7280;">
                                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px; color: #d1d5db;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                    </svg>
                                    <p style="font-size: 16px; font-weight: 500; margin: 0;">No client users found</p>
                                    <p style="font-size: 14px; margin: 8px 0 0;">
                                        <a href="{{ route('admin.settings.client.create') }}" style="color: #4f46e5;">Create your first client user</a>
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($users->hasPages())
                    <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`{{ url('admin/settings/client-users') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to delete user');
                    }
                });
            }
        }

        function toggleStatus(id, btn) {
            fetch(`{{ url('admin/settings/client') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const isActive = data.is_active;
                    btn.style.backgroundColor = isActive ? '#d1fae5' : '#fee2e2';
                    btn.style.color = isActive ? '#065f46' : '#991b1b';
                    btn.innerHTML = `
                        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: ${isActive ? '#10b981' : '#ef4444'};"></span>
                        ${isActive ? 'Active' : 'Inactive'}
                    `;
                } else {
                    alert(data.message || 'Failed to update status');
                }
            });
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
                selectedCount.textContent = `${checkboxes.length} selected`;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            
            if (ids.length === 0) {
                alert('Please select users to delete');
                return;
            }

            if (confirm(`Are you sure you want to delete ${ids.length} user(s)?`)) {
                fetch(`{{ route('admin.settings.client.bulk-delete') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to delete users');
                    }
                });
            }
        }
    </script>
</x-layouts.app>