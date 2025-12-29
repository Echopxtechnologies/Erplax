
    <div style="max-width: 1000px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 class="page-title" style="margin: 0;">Edit Role</h1>
                <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 14px;">Edit Role: <strong style="color: #111827;">{{ $role->name }}</strong></p>
            </div>
            <a href="{{ route('admin.settings.roles.index') }}" class="btn btn-secondary">
                <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Roles
            </a>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.roles.update', $role->id) }}" method="POST">
            @csrf
            
            {{-- Role Name Card --}}
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3 class="card-title">Role Name</h3>
                </div>
                <div class="card-body">
                    <input type="text" name="name" class="form-control" 
                           value="{{ old('name', $role->name) }}" placeholder="Enter role name" required
                           style="max-width: 400px;"
                           {{ $role->name === 'super-admin' ? 'readonly' : '' }}>
                    @if($role->name === 'super-admin')
                        <p style="font-size: 12px; color: #f59e0b; margin-top: 6px;">Super-admin role name cannot be changed</p>
                    @endif
                </div>
            </div>

            {{-- Permissions Card --}}
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="card-title">Assign Permissions</h3>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="selectAll()" style="padding: 6px 12px; font-size: 12px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Select All
                        </button>
                        <button type="button" onclick="deselectAll()" style="padding: 6px 12px; font-size: 12px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Deselect All
                        </button>
                    </div>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if(count($permissionsByModule) > 0)
                        @foreach($permissionsByModule as $moduleId => $data)
                            @php
                                $module = $data['module'];
                                $menuGroups = $data['permissions'];
                                $moduleColor = $moduleId === 'orphan' ? '#f59e0b' : '#3b82f6';
                                
                                // Count assigned permissions in this module
                                $modulePermissionNames = collect($menuGroups)->flatten()->pluck('name')->toArray();
                                $assignedInModule = count(array_intersect($modulePermissionNames, $rolePermissions));
                                $totalInModule = count($modulePermissionNames);
                            @endphp
                            
                            {{-- Module Section --}}
                            <div style="border-bottom: 1px solid #e5e7eb;">
                                {{-- Module Header --}}
                                <div style="background: linear-gradient(135deg, {{ $moduleColor }}10, transparent); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer;"
                                     onclick="toggleModule('module_{{ $moduleId }}')"
                                     onmouseover="this.style.backgroundColor='{{ $moduleColor }}15'" 
                                     onmouseout="this.style.backgroundColor='{{ $moduleColor }}08'">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 8px; background: {{ $moduleColor }}; display: flex; align-items: center; justify-content: center;">
                                            <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0; font-size: 15px; font-weight: 600; color: #111827;">{{ $module->name ?? 'Other Permissions' }}</h4>
                                            <p style="margin: 2px 0 0 0; font-size: 12px; color: #6b7280;">
                                                <span style="color: {{ $assignedInModule > 0 ? '#10b981' : '#6b7280' }}; font-weight: 600;">{{ $assignedInModule }}</span> / {{ $totalInModule }} selected
                                            </p>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($assignedInModule === $totalInModule && $totalInModule > 0)
                                            <span style="padding: 3px 10px; font-size: 11px; background: #d1fae5; color: #065f46; border-radius: 4px; font-weight: 500;">All</span>
                                        @elseif($assignedInModule > 0)
                                            <span style="padding: 3px 10px; font-size: 11px; background: #fef3c7; color: #92400e; border-radius: 4px; font-weight: 500;">Partial</span>
                                        @endif
                                        <button type="button" onclick="event.stopPropagation(); selectModule('module_{{ $moduleId }}')" 
                                                style="padding: 4px 10px; font-size: 11px; background: #dbeafe; color: #1e40af; border: none; border-radius: 4px; cursor: pointer;">
                                            All
                                        </button>
                                        <button type="button" onclick="event.stopPropagation(); deselectModule('module_{{ $moduleId }}')" 
                                                style="padding: 4px 10px; font-size: 11px; background: #fee2e2; color: #991b1b; border: none; border-radius: 4px; cursor: pointer;">
                                            None
                                        </button>
                                        <svg id="icon_module_{{ $moduleId }}" style="width: 20px; height: 20px; color: #6b7280; transition: transform 0.2s; transform: rotate({{ $assignedInModule > 0 ? '0' : '-90' }}deg);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                {{-- Module Permissions --}}
                                <div id="module_{{ $moduleId }}" class="module-content" style="padding: 16px 20px; background: #fafafa; display: {{ $assignedInModule > 0 ? 'block' : 'none' }};">
                                    @foreach($menuGroups as $menuSlug => $permissions)
                                        <div style="margin-bottom: 16px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                                            {{-- Menu Header --}}
                                            <div style="background: #f9fafb; padding: 10px 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-size: 13px; font-weight: 600; color: #374151; text-transform: capitalize;">
                                                    {{ str_replace(['_', '-'], ' ', $menuSlug) }}
                                                </span>
                                                <span style="font-size: 11px; color: #9ca3af; background: #e5e7eb; padding: 2px 8px; border-radius: 4px;">
                                                    {{ count($permissions) }} permissions
                                                </span>
                                            </div>
                                            
                                            {{-- Permission Checkboxes --}}
                                            <div style="padding: 12px 16px; display: flex; flex-wrap: wrap; gap: 8px;">
                                                @foreach($permissions as $permission)
                                                    @php
                                                        $parts = explode('.', $permission->name);
                                                        $action = end($parts);
                                                        $isChecked = in_array($permission->name, $rolePermissions);
                                                        
                                                        $actionColors = [
                                                            'read' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'border' => '#93c5fd'],
                                                            'create' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#6ee7b7'],
                                                            'edit' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                                            'update' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                                            'delete' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
                                                            'export' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'border' => '#a5b4fc'],
                                                            'import' => ['bg' => '#fce7f3', 'text' => '#9d174d', 'border' => '#f9a8d4'],
                                                        ];
                                                        $colors = $actionColors[$action] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#d1d5db'];
                                                    @endphp
                                                    
                                                    <label style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: {{ $colors['bg'] }}; border: 2px solid {{ $isChecked ? $colors['text'] : $colors['border'] }}; border-radius: 6px; cursor: pointer; transition: all 0.15s;"
                                                           onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)';" 
                                                           onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                               class="permission-checkbox module_{{ $moduleId }}_checkbox"
                                                               style="width: 16px; height: 16px; cursor: pointer; accent-color: {{ $colors['text'] }};"
                                                               {{ $isChecked ? 'checked' : '' }}>
                                                        <span style="font-size: 13px; font-weight: 500; color: {{ $colors['text'] }}; text-transform: capitalize;">
                                                            {{ $action }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 48px; text-align: center;">
                            <svg style="width: 48px; height: 48px; color: #9ca3af; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <p style="margin: 0; color: #6b7280;">No permissions available. <a href="{{ route('admin.settings.permissions.create') }}" style="color: #4f46e5;">Create permissions first</a></p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Role
                </button>
                <a href="{{ route('admin.settings.roles.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleModule(moduleId) {
            const content = document.getElementById(moduleId);
            const icon = document.getElementById('icon_' + moduleId);
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(-90deg)';
            }
        }

        function selectModule(moduleId) {
            document.getElementById(moduleId).style.display = 'block';
            document.getElementById('icon_' + moduleId).style.transform = 'rotate(0deg)';
            
            const checkboxes = document.querySelectorAll('.' + moduleId + '_checkbox');
            checkboxes.forEach(cb => cb.checked = true);
        }

        function deselectModule(moduleId) {
            const checkboxes = document.querySelectorAll('.' + moduleId + '_checkbox');
            checkboxes.forEach(cb => cb.checked = false);
        }

        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
            document.querySelectorAll('.module-content').forEach(el => el.style.display = 'block');
            document.querySelectorAll('[id^="icon_module_"]').forEach(icon => icon.style.transform = 'rotate(0deg)');
        }

        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        }
    </script>