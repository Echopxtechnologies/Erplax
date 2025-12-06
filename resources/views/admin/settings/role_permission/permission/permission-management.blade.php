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

        {{-- Stats --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 20px;">
                    <div style="font-size: 32px; font-weight: 700; color: #3b82f6;">{{ $totalPermissions }}</div>
                    <div style="color: #6b7280; font-size: 14px;">Total Permissions</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 20px;">
                    <div style="font-size: 32px; font-weight: 700; color: #10b981;">{{ $modulesCount }}</div>
                    <div style="color: #6b7280; font-size: 14px;">Modules</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 20px;">
                    <div style="font-size: 32px; font-weight: 700; color: #f59e0b;">{{ $orphanPermissions }}</div>
                    <div style="color: #6b7280; font-size: 14px;">Unassigned</div>
                </div>
            </div>
        </div>

        {{-- Permissions Grouped by Module --}}
        @foreach($permissionsByModule as $moduleId => $data)
            @php
                $module = $data['module'];
                $menuGroups = $data['permissions'];
            @endphp
            
            <div class="card" style="margin-bottom: 20px;">
                {{-- Module Header --}}
                <div class="card-header" style="background-color: #f9fafb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background-color: #dbeafe; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 22px; height: 22px; color: #3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111827;">
                                    {{ $module->name ?? 'Other Permissions' }}
                                </h3>
                                <span style="font-size: 12px; color: #6b7280;">
                                    {{ $module->alias ?? 'unassigned' }}
                                </span>
                            </div>
                        </div>
                        <span style="padding: 4px 12px; font-size: 13px; font-weight: 500; background-color: #dbeafe; color: #1e40af; border-radius: 9999px;">
                            {{ collect($menuGroups)->flatten()->count() }} permissions
                        </span>
                    </div>
                </div>

                {{-- Module Content --}}
                <div class="card-body" style="padding: 0;">
                    @foreach($menuGroups as $menuSlug => $permissions)
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 16px 20px;">
                            {{-- Menu/Feature Header --}}
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                <span style="font-weight: 600; color: #374151; font-size: 15px; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $menuSlug) }}
                                </span>
                                <span style="padding: 2px 8px; font-size: 11px; background-color: #f3f4f6; color: #6b7280; border-radius: 4px;">
                                    {{ $menuSlug }}
                                </span>
                            </div>

                            {{-- Action Badges --}}
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach($permissions as $permission)
                                    @php
                                        $actionSlug = last(explode('.', $permission->name));
                                        $actionColors = [
                                            'read' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                                            'create' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'M12 4v16m8-8H4'],
                                            'edit' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                                            'delete' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                                            'export' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
                                            'import' => ['bg' => '#fce7f3', 'text' => '#9d174d', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
                                        ];
                                        $colors = $actionColors[$actionSlug] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'M9 5l7 7-7 7'];
                                    @endphp
                                    
                                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background-color: {{ $colors['bg'] }}; border-radius: 8px;">
                                        <svg style="width: 16px; height: 16px; color: {{ $colors['text'] }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $colors['icon'] }}"></path>
                                        </svg>
                                        <span style="font-size: 13px; font-weight: 500; color: {{ $colors['text'] }}; text-transform: capitalize;">
                                            {{ $actionSlug }}
                                        </span>
                                        
                                        {{-- Delete Button --}}
                                        <button onclick="deletePermission({{ $permission->id }}, '{{ $permission->name }}')" 
                                                style="margin-left: 4px; padding: 2px; background: none; border: none; cursor: pointer; opacity: 0.6;"
                                                onmouseover="this.style.opacity='1'" 
                                                onmouseout="this.style.opacity='0.6'"
                                                title="Delete permission">
                                            <svg style="width: 14px; height: 14px; color: {{ $colors['text'] }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Empty State --}}
        @if(count($permissionsByModule) === 0)
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 48px;">
                    <svg style="width: 64px; height: 64px; color: #9ca3af; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <h3 style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">No permissions found</h3>
                    <p style="color: #6b7280; margin-bottom: 16px;">Get started by creating your first permission.</p>
                    <a href="{{ route('admin.settings.permissions.create') }}" class="btn btn-primary">
                        Create Permission
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        function deletePermission(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?`)) {
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