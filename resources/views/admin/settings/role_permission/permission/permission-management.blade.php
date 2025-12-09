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

        {{-- Messages --}}
        @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $totalPermissions }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Total Permissions</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 10px; background-color: #d1fae5; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px; color: #10b981;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $modulesCount }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Modules</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 10px; background-color: #fef3c7; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px; color: #f59e0b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 28px; font-weight: 700; color: #111827;">{{ $orphanPermissions }}</div>
                        <div style="color: #6b7280; font-size: 13px;">Unassigned</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions Table --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Permissions</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 50px;">
                                #
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Permission Name
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 150px;">
                                Module
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 100px;">
                                Menu
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 100px;">
                                Action
                            </th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 100px;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 0; @endphp
                        @foreach($permissionsByModule as $moduleId => $data)
                            @php
                                $module = $data['module'];
                                $menuGroups = $data['permissions'];
                            @endphp
                            
                            @foreach($menuGroups as $menuSlug => $permissions)
                                @foreach($permissions as $permission)
                                    @php
                                        $index++;
                                        $parts = explode('.', $permission->name);
                                        $action = end($parts);
                                        $bgColor = $index % 2 == 0 ? '#ffffff' : '#f9fafb';
                                        
                                        $actionColors = [
                                            'read' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                            'create' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                            'edit' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                            'delete' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                            'export' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                                            'import' => ['bg' => '#fce7f3', 'text' => '#9d174d'],
                                        ];
                                        $colors = $actionColors[$action] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                    @endphp
                                    
                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $bgColor }};">
                                        <td style="padding: 12px 20px; font-size: 14px; color: #6b7280;">
                                            {{ $index }}
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <code style="font-size: 13px; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; color: #111827;">
                                                {{ $permission->name }}
                                            </code>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <span style="font-size: 13px; font-weight: 500; color: #374151;">
                                                {{ $module->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <span style="font-size: 13px; color: #6b7280; text-transform: capitalize;">
                                                {{ str_replace('_', ' ', $menuSlug) }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <span style="display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 500; background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; border-radius: 4px; text-transform: capitalize;">
                                                {{ $action }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px 16px; text-align: right;">
                                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                                {{-- Edit --}}
                                                <a href="{{ route('admin.settings.permissions.edit', $permission->id) }}" 
                                                   style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #4f46e5; border-radius: 4px;"
                                                   onmouseover="this.style.backgroundColor='#eef2ff'" 
                                                   onmouseout="this.style.backgroundColor='transparent'"
                                                   title="Edit">
                                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                
                                                {{-- Delete --}}
                                                <button onclick="deletePermission({{ $permission->id }}, '{{ $permission->name }}')" 
                                                        style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #dc2626; border: none; background: none; border-radius: 4px; cursor: pointer;"
                                                        onmouseover="this.style.backgroundColor='#fef2f2'" 
                                                        onmouseout="this.style.backgroundColor='transparent'"
                                                        title="Delete">
                                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                {{-- Empty State --}}
                @if(count($permissionsByModule) === 0)
                    <div style="text-align: center; padding: 48px;">
                        <svg style="width: 64px; height: 64px; color: #9ca3af; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <h3 style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">No permissions found</h3>
                        <p style="color: #6b7280; margin-bottom: 16px;">Get started by creating your first permission.</p>
                        <a href="{{ route('admin.settings.permissions.create') }}" class="btn btn-primary">
                            Create Permission
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function deletePermission(id, name) {
            if (confirm(`Delete permission "${name}"?`)) {
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
                });
            }
        }
    </script>
</x-layouts.app>