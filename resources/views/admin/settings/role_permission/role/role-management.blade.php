{{-- <x-layouts.app> --}}
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Roles</h1>
            <a href="{{ route('admin.settings.roles.create') }}" class="btn btn-primary">
                <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Role
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
                <h3 class="card-title">All Roles</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                #
                            </th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Role Name
                            </th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Permissions
                            </th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Created At
                            </th>
                            <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $key => $role)
                            <tr style="border-bottom: 1px solid #e5e7eb;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px; font-size: 14px; color: #111827;">
                                    {{ $roles->firstItem() + $key }}
                                </td>
                                <td style="padding: 16px;">
                                    <span style="display: inline-block; padding: 4px 10px; font-size: 13px; font-weight: 500; background-color: #fef3c7; color: #92400e; border-radius: 4px;">
                                        {{ $role->name }}
                                    </span>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 300px;">
                                        @forelse($role->permissions as $permission)
                                            <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 500; background-color: #dbeafe; color: #1e40af; border-radius: 4px;">
                                                {{ $permission->name }}
                                            </span>
                                        @empty
                                            <span style="font-size: 13px; color: #9ca3af; font-style: italic;">No permissions</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.settings.roles.edit', $role->id) }}" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #4f46e5; border-radius: 4px; transition: background-color 0.2s;"
                                           onmouseover="this.style.backgroundColor='#eef2ff'" 
                                           onmouseout="this.style.backgroundColor='transparent'"
                                           title="Edit">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        
                                        {{-- Delete Button --}}
                                        <button type="button" 
                                                onclick="deleteRole({{ $role->id }})"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #dc2626; border: none; background: none; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                                onmouseover="this.style.backgroundColor='#fef2f2'" 
                                                onmouseout="this.style.backgroundColor='transparent'"
                                                title="Delete">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 48px 16px; text-align: center; color: #6b7280;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <svg style="width: 48px; height: 48px; color: #9ca3af; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <p style="font-size: 16px; font-weight: 500; margin: 0 0 4px 0;">No roles found</p>
                                        <p style="font-size: 14px; margin: 0;">Get started by creating a new role.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($roles->hasPages())
                    <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Script --}}
    <script>
        function deleteRole(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                fetch(`{{ url('admin/settings/roles') }}/${id}`, {
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
                        alert('Failed to delete role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>
{{-- </x-layouts.app> --}}