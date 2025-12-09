<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Edit Permission</h1>

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

        @php
            // Parse existing permission name
            $parts = explode('.', $permission->name);
            $currentModule = $parts[0] ?? '';
            $currentMenu = $parts[1] ?? '';
            $currentAction = $parts[2] ?? '';
            $standardActions = ['read', 'create', 'edit', 'delete', 'export', 'import'];
            $isCustomAction = !in_array($currentAction, $standardActions);
        @endphp

        {{-- Tab Navigation --}}
        <div style="display: flex; gap: 0; margin-bottom: 0; border-bottom: 2px solid #e5e7eb;">
            <button type="button" id="tab-builder" onclick="showTab('builder')" 
                    style="padding: 12px 24px; font-size: 14px; font-weight: 500; border: none; background: none; cursor: pointer; border-bottom: 2px solid #3b82f6; margin-bottom: -2px; color: #3b82f6;">
                Quick Builder
            </button>
            <button type="button" id="tab-custom" onclick="showTab('custom')" 
                    style="padding: 12px 24px; font-size: 14px; font-weight: 500; border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; color: #6b7280;">
                Custom Permission
            </button>
        </div>

        {{-- TAB 1: Quick Builder --}}
        <div id="content-builder" class="tab-content">
            <div class="card" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                <div class="card-body">
                    {{-- Current Permission Info --}}
                    <div style="padding: 12px 16px; background-color: #fef3c7; border-radius: 8px; border: 1px solid #fcd34d; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                        <svg style="width: 20px; height: 20px; color: #92400e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span style="color: #92400e; font-size: 13px;">Editing:</span>
                            <code style="font-size: 14px; color: #92400e; font-weight: 600; margin-left: 6px;">{{ $permission->name }}</code>
                        </div>
                    </div>

                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Build permission using: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">module.menu.action</code>
                    </p>
                    
                    <form action="{{ route('admin.settings.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                            {{-- Module --}}
                            <div>
                                <label class="form-label">Module</label>
                                <select name="module_id" id="builder_module" class="form-control" required>
                                    <option value="">Select...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" 
                                                data-alias="{{ $module->alias }}"
                                                {{ (old('module_id', $permission->module_id) == $module->id) ? 'selected' : '' }}>
                                            {{ $module->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Menu --}}
                            <div>
                                <label class="form-label">Menu/Feature</label>
                                <input type="text" name="menu_name" id="builder_menu" class="form-control" 
                                       value="{{ old('menu_name', $currentMenu) }}"
                                       placeholder="e.g., list, reports" required>
                            </div>

                            {{-- Action --}}
                            <div>
                                <label class="form-label">Action</label>
                                <select name="action_type" id="builder_action" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="read" {{ $currentAction == 'read' ? 'selected' : '' }}>read</option>
                                    <option value="create" {{ $currentAction == 'create' ? 'selected' : '' }}>create</option>
                                    <option value="edit" {{ $currentAction == 'edit' ? 'selected' : '' }}>edit</option>
                                    <option value="delete" {{ $currentAction == 'delete' ? 'selected' : '' }}>delete</option>
                                    <option value="export" {{ $currentAction == 'export' ? 'selected' : '' }}>export</option>
                                    <option value="import" {{ $currentAction == 'import' ? 'selected' : '' }}>import</option>
                                    <option value="custom" {{ $isCustomAction ? 'selected' : '' }}>custom...</option>
                                </select>
                            </div>
                        </div>

                        {{-- Custom Action Input --}}
                        <div id="custom_action_wrapper" style="display: {{ $isCustomAction ? 'block' : 'none' }}; margin-bottom: 20px;">
                            <label class="form-label">Custom Action Name</label>
                            <input type="text" name="custom_action" id="builder_custom" class="form-control" 
                                   value="{{ $isCustomAction ? $currentAction : '' }}"
                                   placeholder="e.g., approve, publish, archive">
                            <small style="color: #6b7280;">Lowercase, no spaces</small>
                        </div>

                        {{-- Preview --}}
                        <div style="padding: 16px; background-color: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #6b7280; font-size: 14px;">Permission:</span>
                                <span id="builder_preview" style="font-family: monospace; font-size: 18px; color: #0369a1; font-weight: 600;">
                                    {{ $permission->name }}
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="name" id="builder_name" value="{{ $permission->name }}">

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Update Permission</button>
                            <a href="{{ route('admin.settings.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB 2: Custom Permission --}}
        <div id="content-custom" class="tab-content" style="display: none;">
            <div class="card" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                <div class="card-body">
                    {{-- Current Permission Info --}}
                    <div style="padding: 12px 16px; background-color: #fef3c7; border-radius: 8px; border: 1px solid #fcd34d; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                        <svg style="width: 20px; height: 20px; color: #92400e;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span style="color: #92400e; font-size: 13px;">Editing:</span>
                            <code style="font-size: 14px; color: #92400e; font-weight: 600; margin-left: 6px;">{{ $permission->name }}</code>
                        </div>
                    </div>

                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Enter custom permission name in format: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">module.menu.action</code>
                    </p>
                    
                    <form action="{{ route('admin.settings.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        
                        {{-- Hidden module_id field --}}
                        <input type="hidden" name="module_id" value="{{ $permission->module_id }}">
                        
                        <div style="margin-bottom: 20px;">
                            <label class="form-label">Permission Name</label>
                            <input type="text" name="name" id="custom_name" class="form-control" 
                                   value="{{ old('name', $permission->name) }}"
                                   placeholder="e.g., inventory.products.approve" 
                                   style="font-family: monospace; font-size: 16px;"
                                   required>
                            <small style="color: #6b7280;">Use lowercase, dots to separate, underscores for spaces</small>
                        </div>

                        {{-- Examples --}}
                        <div style="padding: 16px; background-color: #f9fafb; border-radius: 8px; margin-bottom: 20px;">
                            <label style="font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Examples:</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                <span onclick="setCustomValue('inventory.products.read')" style="padding: 4px 10px; background: #dbeafe; color: #1e40af; border-radius: 4px; font-family: monospace; font-size: 13px; cursor: pointer;">inventory.products.read</span>
                                <span onclick="setCustomValue('sales.invoices.create')" style="padding: 4px 10px; background: #d1fae5; color: #065f46; border-radius: 4px; font-family: monospace; font-size: 13px; cursor: pointer;">sales.invoices.create</span>
                                <span onclick="setCustomValue('hr.employees.approve')" style="padding: 4px 10px; background: #fef3c7; color: #92400e; border-radius: 4px; font-family: monospace; font-size: 13px; cursor: pointer;">hr.employees.approve</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Update Permission</button>
                            <a href="{{ route('admin.settings.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tab) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            // Reset all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.style.borderBottomColor = 'transparent';
                el.style.color = '#6b7280';
            });
            // Show selected
            document.getElementById('content-' + tab).style.display = 'block';
            document.getElementById('tab-' + tab).style.borderBottomColor = '#3b82f6';
            document.getElementById('tab-' + tab).style.color = '#3b82f6';
        }

        // Builder preview
        function updateBuilderPreview() {
            const moduleSelect = document.getElementById('builder_module');
            const menuInput = document.getElementById('builder_menu');
            const actionSelect = document.getElementById('builder_action');
            const customInput = document.getElementById('builder_custom');
            const preview = document.getElementById('builder_preview');
            const hidden = document.getElementById('builder_name');
            const customWrapper = document.getElementById('custom_action_wrapper');

            const moduleAlias = moduleSelect.options[moduleSelect.selectedIndex]?.dataset?.alias || 'module';
            const menu = menuInput.value.toLowerCase().replace(/\s+/g, '_') || 'menu';
            let action = actionSelect.value || 'action';

            // Show/hide custom input
            if (action === 'custom') {
                customWrapper.style.display = 'block';
                action = customInput.value.toLowerCase().replace(/\s+/g, '_') || 'custom';
            } else {
                customWrapper.style.display = 'none';
            }

            const name = `${moduleAlias}.${menu}.${action}`;
            preview.textContent = name;
            hidden.value = name;
        }

        document.getElementById('builder_module').addEventListener('change', updateBuilderPreview);
        document.getElementById('builder_menu').addEventListener('input', updateBuilderPreview);
        document.getElementById('builder_action').addEventListener('change', updateBuilderPreview);
        document.getElementById('builder_custom').addEventListener('input', updateBuilderPreview);

        // Custom example click
        function setCustomValue(value) {
            document.getElementById('custom_name').value = value;
        }

        // Initialize preview on load
        document.addEventListener('DOMContentLoaded', function() {
            updateBuilderPreview();
        });
    </script>
</x-layouts.app>