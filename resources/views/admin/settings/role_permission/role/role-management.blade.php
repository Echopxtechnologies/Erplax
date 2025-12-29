{{-- <x-layouts.app> --}}
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Role Management</h1>
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

        {{-- Stats Cards --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
            <div class="card">
                <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 10px; background-color: #dbeafe; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px; color: #3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $roles->total() }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Total Roles</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 10px; background-color: #d1fae5; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px; color: #10b981;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $roles->sum(fn($r) => $r->permissions->count()) }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Permissions Assigned</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 10px; background-color: #fef3c7; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px; color: #f59e0b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $roles->filter(fn($r) => $r->permissions->isEmpty())->count() }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Without Permissions</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Roles</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 50px;">
                                #
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Role Name
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Permissions
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 130px;">
                                Created At
                            </th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 100px;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $key => $role)
                            @php
                                $bgColor = $key % 2 == 0 ? '#ffffff' : '#f9fafb';
                                $roleColors = [
                                    'super-admin' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'admin' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                ];
                                $roleColor = $roleColors[$role->name] ?? ['bg' => '#e0e7ff', 'text' => '#3730a3'];
                            @endphp
                            <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $bgColor }};">
                                <td style="padding: 16px 20px; font-size: 14px; color: #6b7280;">
                                    {{ $roles->firstItem() + $key }}
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: {{ $roleColor['bg'] }}; display: flex; align-items: center; justify-content: center;">
                                            <svg style="width: 18px; height: 18px; color: {{ $roleColor['text'] }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </div>
                                        <span style="font-size: 14px; font-weight: 500; color: #111827;">
                                            {{ $role->name }}
                                        </span>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    @if($role->permissions->count() > 0)
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 400px;">
                                            @foreach($role->permissions->take(5) as $permission)
                                                @php
                                                    $parts = explode('.', $permission->name);
                                                    $action = end($parts);
                                                    $actionColors = [
                                                        'read' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                        'create' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                                        'edit' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                                        'delete' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                                    ];
                                                    $colors = $actionColors[$action] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                                @endphp
                                                <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 500; background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; border-radius: 4px;">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                            @if($role->permissions->count() > 5)
                                                <span style="display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: 500; background-color: #e5e7eb; color: #374151; border-radius: 4px;">
                                                    +{{ $role->permissions->count() - 5 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="font-size: 13px; color: #9ca3af; font-style: italic;">No permissions</span>
                                    @endif
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #6b7280;">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.settings.roles.edit', $role->id) }}" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #4f46e5; border-radius: 4px;"
                                           onmouseover="this.style.backgroundColor='#eef2ff'" 
                                           onmouseout="this.style.backgroundColor='transparent'"
                                           title="Edit">
                                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        
                                        {{-- Delete Button --}}
                                        @if($role->name !== 'super-admin')
                                            <button type="button" 
                                                    onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')"
                                                    style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #dc2626; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                                    onmouseover="this.style.backgroundColor='#fef2f2'" 
                                                    onmouseout="this.style.backgroundColor='transparent'"
                                                    title="Delete">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 48px 16px; text-align: center; color: #6b7280;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <svg style="width: 64px; height: 64px; color: #9ca3af; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                            </path>
                                        </svg>
                                        <h3 style="font-size: 18px; font-weight: 600; color: #374151; margin: 0 0 8px 0;">No roles found</h3>
                                        <p style="font-size: 14px; margin: 0 0 16px 0; color: #6b7280;">Get started by creating a new role.</p>
                                        <a href="{{ route('admin.settings.roles.create') }}" class="btn btn-primary">
                                            Create Role
                                        </a>
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
        function deleteRole(id, name) {
            if (confirm(`Delete role "${name}"? This action cannot be undone.`)) {
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
                        alert(data.message || 'Failed to delete role');
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