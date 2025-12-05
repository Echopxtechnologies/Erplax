<x-layouts.app>
    <div style="max-width: 1200px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 class="page-title">Edit Permissions</h1>
                <p style="color: #6b7280; margin-top: 4px;">
                    Role: <strong style="color: #111827;">{{ $role->name }}</strong>
                </p>
            </div>
            <a href="{{ route('admin.settings.role-permissions.index') }}" class="btn btn-secondary">
                <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Roles
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

        <form action="{{ route('admin.settings.role-permissions.update', $role->id) }}" method="POST">
            @csrf

            {{-- Quick Actions --}}
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-body" style="display: flex; gap: 12px; align-items: center; padding: 16px;">
                    <button type="button" onclick="selectAll()" class="btn btn-sm btn-primary">
                        <svg style="width: 16px; height: 16px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Select All
                    </button>
                    <button type="button" onclick="deselectAll()" class="btn btn-sm btn-secondary">
                        <svg style="width: 16px; height: 16px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Deselect All
                    </button>
                    <button type="button" onclick="selectReadOnly()" class="btn btn-sm btn-secondary">
                        Read Only
                    </button>
                    <span style="margin-left: auto; color: #6b7280; font-size: 14px;">
                        Selected: <strong id="selectedCount" style="color: #3b82f6;">{{ count($rolePermissions) }}</strong> permissions
                    </span>
                </div>
            </div>

            {{-- Modules Loop --}}
            @foreach($modules as $module)
                @if($module->menus->count() > 0)
                    <div class="card" style="margin-bottom: 16px;">
                        {{-- Module Header --}}
                        <div class="card-header" 
                             style="background-color: #f9fafb; cursor: pointer; user-select: none;" 
                             onclick="toggleModule('module-{{ $module->id }}')">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="checkbox" 
                                           id="module-checkbox-{{ $module->id }}"
                                           onchange="toggleModulePermissions({{ $module->id }}); event.stopPropagation();"
                                           onclick="event.stopPropagation();"
                                           style="width: 18px; height: 18px; cursor: pointer;">
                                    <h3 class="card-title" style="margin: 0; font-size: 16px; font-weight: 600;">
                                        {{ $module->name }}
                                    </h3>
                                    <span style="padding: 2px 8px; font-size: 11px; background-color: #e5e7eb; color: #374151; border-radius: 9999px;">
                                        {{ $module->alias }}
                                    </span>
                                    <span style="padding: 2px 8px; font-size: 11px; background-color: #dbeafe; color: #1e40af; border-radius: 9999px;">
                                        v{{ $module->version }}
                                    </span>
                                </div>
                                <svg id="chevron-module-{{ $module->id }}" 
                                     style="width: 20px; height: 20px; transition: transform 0.2s; transform: rotate(0deg);" 
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Module Content (Menus) --}}
                        <div id="module-{{ $module->id }}" class="card-body" style="padding: 0; display: block;">
                            @foreach($module->menus as $menu)
                                <div style="border-bottom: 1px solid #e5e7eb; padding: 16px;">
                                    {{-- Menu Header --}}
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                        <input type="checkbox" 
                                               id="menu-checkbox-{{ $menu->id }}"
                                               onchange="toggleMenuPermissions({{ $menu->id }}, {{ $module->id }})"
                                               style="width: 16px; height: 16px; cursor: pointer;">
                                        <span style="font-weight: 600; color: #374151;">{{ $menu->menu_name }}</span>
                                        <span style="padding: 2px 6px; font-size: 10px; background-color: #dbeafe; color: #1e40af; border-radius: 4px;">
                                            {{ $menu->slug }}
                                        </span>
                                        @if($menu->route)
                                            <span style="padding: 2px 6px; font-size: 10px; background-color: #fef3c7; color: #92400e; border-radius: 4px;">
                                                {{ $menu->route }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-left: 28px;">
                                        @foreach($menu->actions as $action)
                                            @php
                                                $permissionName = "{$module->alias}.{$menu->slug}.{$action->action_slug}";
                                                $isChecked = in_array($permissionName, $rolePermissions);
                                            @endphp
                                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; background-color: {{ $isChecked ? '#dbeafe' : '#f9fafb' }}; border-radius: 6px; border: 1px solid {{ $isChecked ? '#3b82f6' : '#e5e7eb' }}; transition: all 0.15s;"
                                                   class="permission-label"
                                                   data-checked="{{ $isChecked ? 'true' : 'false' }}">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permissionName }}"
                                                       class="permission-checkbox module-{{ $module->id }} menu-{{ $menu->id }}"
                                                       data-action="{{ $action->action_slug }}"
                                                       style="width: 16px; height: 16px; cursor: pointer;"
                                                       onchange="updateCounts(); updateParentCheckboxes({{ $module->id }}, {{ $menu->id }}); updateLabelStyle(this);"
                                                       {{ $isChecked ? 'checked' : '' }}>
                                                <span style="font-size: 13px; color: #374151; font-weight: 500;">{{ $action->action_name }}</span>
                                                <span style="font-size: 10px; color: #9ca3af;">({{ $action->action_slug }})</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    {{-- Child Menus (if any) --}}
                                    @if($menu->children && $menu->children->count() > 0)
                                        <div style="margin-left: 28px; margin-top: 16px; padding-left: 16px; border-left: 2px solid #e5e7eb;">
                                            @foreach($menu->children as $childMenu)
                                                <div style="margin-bottom: 16px;">
                                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                                        <input type="checkbox" 
                                                               id="menu-checkbox-{{ $childMenu->id }}"
                                                               onchange="toggleMenuPermissions({{ $childMenu->id }}, {{ $module->id }})"
                                                               style="width: 14px; height: 14px; cursor: pointer;">
                                                        <span style="font-weight: 500; color: #6b7280; font-size: 14px;">{{ $childMenu->menu_name }}</span>
                                                        <span style="padding: 1px 4px; font-size: 9px; background-color: #f3f4f6; color: #6b7280; border-radius: 3px;">
                                                            {{ $childMenu->slug }}
                                                        </span>
                                                    </div>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-left: 26px;">
                                                        @foreach($childMenu->actions as $action)
                                                            @php
                                                                $permissionName = "{$module->alias}.{$childMenu->slug}.{$action->action_slug}";
                                                                $isChecked = in_array($permissionName, $rolePermissions);
                                                            @endphp
                                                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 10px; background-color: {{ $isChecked ? '#dbeafe' : '#f9fafb' }}; border-radius: 4px; border: 1px solid {{ $isChecked ? '#3b82f6' : '#e5e7eb' }}; font-size: 12px;"
                                                                   class="permission-label">
                                                                <input type="checkbox" 
                                                                       name="permissions[]" 
                                                                       value="{{ $permissionName }}"
                                                                       class="permission-checkbox module-{{ $module->id }} menu-{{ $childMenu->id }}"
                                                                       data-action="{{ $action->action_slug }}"
                                                                       style="width: 14px; height: 14px; cursor: pointer;"
                                                                       onchange="updateCounts(); updateLabelStyle(this);"
                                                                       {{ $isChecked ? 'checked' : '' }}>
                                                                <span style="color: #374151;">{{ $action->action_name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Submit Button --}}
            <div style="display: flex; gap: 12px; margin-top: 24px; padding: 20px; background-color: #f9fafb; border-radius: 8px;">
                <button type="submit" class="btn btn-primary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Permissions
                </button>
                <a href="{{ route('admin.settings.role-permissions.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Toggle module visibility
        function toggleModule(moduleId) {
            const content = document.getElementById(moduleId);
            const chevron = document.getElementById('chevron-' + moduleId);
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                chevron.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        // Select all permissions
        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                cb.checked = true;
                updateLabelStyle(cb);
            });
            document.querySelectorAll('[id^="module-checkbox-"]').forEach(cb => cb.checked = true);
            document.querySelectorAll('[id^="menu-checkbox-"]').forEach(cb => cb.checked = true);
            updateCounts();
        }

        // Deselect all permissions
        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                cb.checked = false;
                updateLabelStyle(cb);
            });
            document.querySelectorAll('[id^="module-checkbox-"]').forEach(cb => cb.checked = false);
            document.querySelectorAll('[id^="menu-checkbox-"]').forEach(cb => cb.checked = false);
            updateCounts();
        }

        // Select read-only permissions
        function selectReadOnly() {
            deselectAll();
            document.querySelectorAll('.permission-checkbox[data-action="read"]').forEach(cb => {
                cb.checked = true;
                updateLabelStyle(cb);
            });
            updateCounts();
            // Update parent checkboxes
            document.querySelectorAll('[id^="module-checkbox-"]').forEach(cb => {
                const moduleId = cb.id.replace('module-checkbox-', '');
                updateModuleCheckbox(moduleId);
            });
        }

        // Toggle all permissions for a module
        function toggleModulePermissions(moduleId) {
            const moduleCheckbox = document.getElementById('module-checkbox-' + moduleId);
            const isChecked = moduleCheckbox.checked;
            
            document.querySelectorAll('.module-' + moduleId).forEach(cb => {
                cb.checked = isChecked;
                updateLabelStyle(cb);
            });
            
            // Also update menu checkboxes
            document.querySelectorAll('.module-' + moduleId).forEach(cb => {
                const menuId = Array.from(cb.classList).find(c => c.startsWith('menu-'))?.replace('menu-', '');
                if (menuId) {
                    const menuCheckbox = document.getElementById('menu-checkbox-' + menuId);
                    if (menuCheckbox) menuCheckbox.checked = isChecked;
                }
            });
            
            updateCounts();
        }

        // Toggle all permissions for a menu
        function toggleMenuPermissions(menuId, moduleId) {
            const menuCheckbox = document.getElementById('menu-checkbox-' + menuId);
            const isChecked = menuCheckbox.checked;
            
            document.querySelectorAll('.menu-' + menuId).forEach(cb => {
                cb.checked = isChecked;
                updateLabelStyle(cb);
            });
            
            updateModuleCheckbox(moduleId);
            updateCounts();
        }

        // Update parent checkboxes based on children
        function updateParentCheckboxes(moduleId, menuId) {
            // Update menu checkbox
            const menuCheckboxes = document.querySelectorAll('.menu-' + menuId);
            const menuCheckbox = document.getElementById('menu-checkbox-' + menuId);
            if (menuCheckbox && menuCheckboxes.length > 0) {
                const allChecked = Array.from(menuCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(menuCheckboxes).some(cb => cb.checked);
                menuCheckbox.checked = allChecked;
                menuCheckbox.indeterminate = someChecked && !allChecked;
            }

            updateModuleCheckbox(moduleId);
        }

        // Update module checkbox based on its permissions
        function updateModuleCheckbox(moduleId) {
            const moduleCheckboxes = document.querySelectorAll('.module-' + moduleId);
            const moduleCheckbox = document.getElementById('module-checkbox-' + moduleId);
            if (moduleCheckbox && moduleCheckboxes.length > 0) {
                const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(moduleCheckboxes).some(cb => cb.checked);
                moduleCheckbox.checked = allChecked;
                moduleCheckbox.indeterminate = someChecked && !allChecked;
            }
        }

        // Update permission count
        function updateCounts() {
            const count = document.querySelectorAll('.permission-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = count;
        }

        // Update label style based on checkbox state
        function updateLabelStyle(checkbox) {
            const label = checkbox.closest('.permission-label');
            if (label) {
                if (checkbox.checked) {
                    label.style.backgroundColor = '#dbeafe';
                    label.style.borderColor = '#3b82f6';
                } else {
                    label.style.backgroundColor = '#f9fafb';
                    label.style.borderColor = '#e5e7eb';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
            
            // Initialize parent checkboxes
            document.querySelectorAll('[id^="module-checkbox-"]').forEach(cb => {
                const moduleId = cb.id.replace('module-checkbox-', '');
                updateModuleCheckbox(moduleId);
            });
            
            document.querySelectorAll('[id^="menu-checkbox-"]').forEach(cb => {
                const menuId = cb.id.replace('menu-checkbox-', '');
                const menuCheckboxes = document.querySelectorAll('.menu-' + menuId);
                if (menuCheckboxes.length > 0) {
                    const allChecked = Array.from(menuCheckboxes).every(c => c.checked);
                    const someChecked = Array.from(menuCheckboxes).some(c => c.checked);
                    cb.checked = allChecked;
                    cb.indeterminate = someChecked && !allChecked;
                }
            });
        });
    </script>
</x-layouts.app>