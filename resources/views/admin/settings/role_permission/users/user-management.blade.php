<x-layouts.app>
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Users</h1>
            <a href="{{ route('admin.settings.users.create') }}" class="btn btn-primary">
                <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add User
            </a>
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
            <div class="card-header">
                <h3 class="card-title">All Users</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">#</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Name</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Email</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Roles</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Created</th>
                            <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                            <tr style="border-bottom: 1px solid #e5e7eb;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px; font-size: 14px; color: #111827;">
                                    {{ $users->firstItem() + $key }}
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #4f46e5; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span style="font-size: 14px; font-weight: 500; color: #111827;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $user->email }}
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        @forelse($user->roles as $role)
                                            <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 500; background-color: {{ $role->name === 'admin' ? '#fef3c7' : '#dbeafe' }}; color: {{ $role->name === 'admin' ? '#92400e' : '#1e40af' }}; border-radius: 4px;">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span style="font-size: 13px; color: #9ca3af; font-style: italic;">No roles</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.settings.users.edit', $user->id) }}" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #4f46e5; border-radius: 4px;"
                                           onmouseover="this.style.backgroundColor='#eef2ff'" 
                                           onmouseout="this.style.backgroundColor='transparent'"
                                           title="Edit">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        
                                        {{-- Delete Button (hidden for current user) --}}
                                        @if($user->id !== auth()->id())
                                            <button type="button" onclick="deleteUser({{ $user->id }})"
                                                    style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #dc2626; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                                    onmouseover="this.style.backgroundColor='#fef2f2'" 
                                                    onmouseout="this.style.backgroundColor='transparent'"
                                                    title="Delete">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 48px 16px; text-align: center; color: #6b7280;">
                                    <p style="font-size: 16px; font-weight: 500; margin: 0;">No users found</p>
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
                fetch(`{{ url('admin/settings/users') }}/${id}`, {
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
    </script>
</x-layouts.app>