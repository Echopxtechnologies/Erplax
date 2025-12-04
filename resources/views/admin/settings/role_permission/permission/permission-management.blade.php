<x-layouts.app>
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Permissions</h1>
            <a href="{{ route('admin.settings.permissions.create') }}" class="btn btn-primary">
                <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Permission
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
                <h3 class="card-title">All Permissions</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                #
                            </th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Permission Name
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
                        @forelse($permissions as $key => $permission)
                            <tr style="border-bottom: 1px solid #e5e7eb;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px; font-size: 14px; color: #111827;">
                                    {{ $permissions->firstItem() + $key }}
                                </td>
                                <td style="padding: 16px;">
                                    <span style="display: inline-block; padding: 4px 10px; font-size: 13px; font-weight: 500; background-color: #dbeafe; color: #1e40af; border-radius: 4px;">
                                        {{ $permission->name }}
                                    </span>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $permission->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.settings.permissions.edit', $permission->id) }}" 
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
                                                onclick="deletePermission({{ $permission->id }})"
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
                                <td colspan="4" style="padding: 48px 16px; text-align: center; color: #6b7280;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <svg style="width: 48px; height: 48px; color: #9ca3af; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p style="font-size: 16px; font-weight: 500; margin: 0 0 4px 0;">No permissions found</p>
                                        <p style="font-size: 14px; margin: 0;">Get started by creating a new permission.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($permissions->hasPages())
                    <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
                        {{ $permissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Script --}}
    <script>
        function deletePermission(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                fetch(`{{ url('admin/settings/permissions') }}/${id}`, {
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
                        alert('Failed to delete permission');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>
</x-layouts.app>