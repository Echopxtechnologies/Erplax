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
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">All Permissions</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 180px;">
                                Module
                            </th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 150px;">
                                Menu
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 80px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 8px; height: 8px; background: #3b82f6; border-radius: 50%;"></span>
                                    Read
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 80px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></span>
                                    Create
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 80px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%;"></span>
                                    Edit
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 80px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                                    Delete
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; width: 80px;">
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 8px; height: 8px; background: #6366f1; border-radius: 50%;"></span>
                                    Export
                                </span>
                            </th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
                                Other
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowIndex = 0; @endphp
                        @foreach($permissionsByModule as $moduleId => $data)
                            @php
                                $module = $data['module'];
                                $menuGroups = $data['permissions'];
                                $menuCount = count($menuGroups);
                                $firstMenu = true;
                            @endphp
                            
                            @foreach($menuGroups as $menuSlug => $permissions)
                                @php
                                    $rowIndex++;
                                    $bgColor = $rowIndex % 2 == 0 ? '#ffffff' : '#f9fafb';
                                    
                                    // Get permissions by action
                                    $permissionsByAction = [];
                                    $otherPermissions = [];
                                    $standardActions = ['read', 'create', 'edit', 'delete', 'export'];
                                    
                                    foreach($permissions as $perm) {
                                        $action = last(explode('.', $perm->name));
                                        if (in_array($action, $standardActions)) {
                                            $permissionsByAction[$action] = $perm;
                                        } else {
                                            $otherPermissions[] = $perm;
                                        }
                                    }
                                @endphp
                                
                                <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $bgColor }};">
                                    {{-- Module Name (only show for first menu row) --}}
                                    @if($firstMenu)
                                        <td rowspan="{{ $menuCount }}" style="padding: 16px 20px; vertical-align: top; border-right: 1px solid #e5e7eb;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #dbeafe; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg style="width: 18px; height: 18px; color: #3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: #111827; font-size: 14px;">
                                                        {{ $module->name ?? 'Other' }}
                                                    </div>
                                                    <div style="font-size: 11px; color: #9ca3af; font-family: monospace;">
                                                        {{ $module->alias ?? 'unassigned' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @php $firstMenu = false; @endphp
                                    @endif
                                    
                                    {{-- Menu Name --}}
                                    <td style="padding: 12px 16px; border-right: 1px solid #f3f4f6;">
                                        <div style="font-weight: 500; color: #374151; font-size: 14px; text-transform: capitalize;">
                                            {{ str_replace('_', ' ', $menuSlug) }}
                                        </div>
                                        <div style="font-size: 11px; color: #9ca3af; font-family: monospace;">
                                            {{ $menuSlug }}
                                        </div>
                                    </td>
                                    
                                    {{-- Read --}}
                                    <td style="padding: 12px 16px; text-align: center;">
                                        @if(isset($permissionsByAction['read']))
                                            <button onclick="deletePermission({{ $permissionsByAction['read']->id }}, '{{ $permissionsByAction['read']->name }}')" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; background-color: #dbeafe; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                                    title="{{ $permissionsByAction['read']->name }}">
                                                <svg style="width: 16px; height: 16px; color: #1e40af;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Create --}}
                                    <td style="padding: 12px 16px; text-align: center;">
                                        @if(isset($permissionsByAction['create']))
                                            <button onclick="deletePermission({{ $permissionsByAction['create']->id }}, '{{ $permissionsByAction['create']->name }}')" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; background-color: #d1fae5; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                                    title="{{ $permissionsByAction['create']->name }}">
                                                <svg style="width: 16px; height: 16px; color: #065f46;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Edit --}}
                                    <td style="padding: 12px 16px; text-align: center;">
                                        @if(isset($permissionsByAction['edit']))
                                            <button onclick="deletePermission({{ $permissionsByAction['edit']->id }}, '{{ $permissionsByAction['edit']->name }}')" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; background-color: #fef3c7; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                                    title="{{ $permissionsByAction['edit']->name }}">
                                                <svg style="width: 16px; height: 16px; color: #92400e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Delete --}}
                                    <td style="padding: 12px 16px; text-align: center;">
                                        @if(isset($permissionsByAction['delete']))
                                            <button onclick="deletePermission({{ $permissionsByAction['delete']->id }}, '{{ $permissionsByAction['delete']->name }}')" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; background-color: #fee2e2; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                                    title="{{ $permissionsByAction['delete']->name }}">
                                                <svg style="width: 16px; height: 16px; color: #991b1b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Export --}}
                                    <td style="padding: 12px 16px; text-align: center;">
                                        @if(isset($permissionsByAction['export']))
                                            <button onclick="deletePermission({{ $permissionsByAction['export']->id }}, '{{ $permissionsByAction['export']->name }}')" 
                                                    style="width: 32px; height: 32px; border-radius: 6px; background-color: #e0e7ff; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                                    title="{{ $permissionsByAction['export']->name }}">
                                                <svg style="width: 16px; height: 16px; color: #3730a3;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Other Actions --}}
                                    <td style="padding: 12px 16px;">
                                        @if(count($otherPermissions) > 0)
                                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                                @foreach($otherPermissions as $perm)
                                                    @php $action = last(explode('.', $perm->name)); @endphp
                                                    <button onclick="deletePermission({{ $perm->id }}, '{{ $perm->name }}')" 
                                                            style="padding: 4px 10px; font-size: 12px; background-color: #f3f4f6; color: #374151; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                                            title="{{ $perm->name }}">
                                                        {{ $action }}
                                                        <svg style="width: 12px; height: 12px; opacity: 0.5;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @else
                                            <span style="color: #d1d5db;">—</span>
                                        @endif
                                    </td>
                                </tr>
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

        {{-- Legend --}}
        <div style="margin-top: 16px; padding: 12px 16px; background-color: #f9fafb; border-radius: 8px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Legend:</span>
            <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280;">
                <span style="width: 24px; height: 24px; background: #dbeafe; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 14px; height: 14px; color: #1e40af;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </span>
                Permission exists (click to delete)
            </span>
            <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280;">
                <span style="color: #d1d5db; font-size: 16px;">—</span>
                Not created
            </span>
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