<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Create Permission</h1>

        @if($errors->any())
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Permission</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.permissions.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        
                        {{-- Module Selection --}}
                        <div>
                            <label class="form-label">Select Module <span style="color: #ef4444;">*</span></label>
                            <select name="module_id" id="module_id" class="form-control" required>
                                <option value="">-- Select Module --</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" data-alias="{{ $module->alias }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                        {{ $module->name }} ({{ $module->alias }})
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: #6b7280;">The module this permission belongs to</small>
                        </div>

                        {{-- Menu/Feature Name --}}
                        <div>
                            <label class="form-label">Menu/Feature Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="menu_name" id="menu_name" class="form-control" 
                                   value="{{ old('menu_name') }}" 
                                   placeholder="e.g., list, create, reports, categories"
                                   required>
                            <small style="color: #6b7280;">The feature or menu within the module (lowercase, no spaces)</small>
                        </div>

                        {{-- Action Type --}}
                        <div>
                            <label class="form-label">Action Type <span style="color: #ef4444;">*</span></label>
                            <select name="action_type" id="action_type" class="form-control" required>
                                <option value="">-- Select Action --</option>
                                <option value="read" {{ old('action_type') == 'read' ? 'selected' : '' }}>Read (View)</option>
                                <option value="create" {{ old('action_type') == 'create' ? 'selected' : '' }}>Create (Add New)</option>
                                <option value="edit" {{ old('action_type') == 'edit' ? 'selected' : '' }}>Edit (Update)</option>
                                <option value="delete" {{ old('action_type') == 'delete' ? 'selected' : '' }}>Delete (Remove)</option>
                                <option value="export" {{ old('action_type') == 'export' ? 'selected' : '' }}>Export</option>
                                <option value="import" {{ old('action_type') == 'import' ? 'selected' : '' }}>Import</option>
                                <option value="custom" {{ old('action_type') == 'custom' ? 'selected' : '' }}>Custom Action</option>
                            </select>
                        </div>

                        {{-- Custom Action Name (shown only when custom is selected) --}}
                        <div id="custom_action_wrapper" style="display: none;">
                            <label class="form-label">Custom Action Name</label>
                            <input type="text" name="custom_action" id="custom_action" class="form-control" 
                                   value="{{ old('custom_action') }}" 
                                   placeholder="e.g., approve, publish, archive">
                            <small style="color: #6b7280;">Enter a custom action name (lowercase, no spaces)</small>
                        </div>

                        {{-- Permission Preview --}}
                        <div style="padding: 16px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <label class="form-label" style="margin-bottom: 8px;">Permission Name Preview</label>
                            <div id="permission_preview" style="font-family: monospace; font-size: 16px; color: #3b82f6; font-weight: 600;">
                                module.menu.action
                            </div>
                            <small style="color: #6b7280;">This will be the final permission name</small>
                        </div>

                        {{-- Hidden field for final permission name --}}
                        <input type="hidden" name="name" id="permission_name">

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">
                                Add Permission
                            </button>
                            <a href="{{ route('admin.settings.permissions.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quick Create Multiple --}}
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3 class="card-title">Quick Create: All CRUD Permissions</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.permissions.store-bulk') }}" method="POST">
                    @csrf
                    <p style="color: #6b7280; margin-bottom: 16px;">
                        Quickly create all standard permissions (read, create, edit, delete) for a module menu.
                    </p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Module</label>
                            <select name="bulk_module_id" class="form-control" required>
                                <option value="">-- Select Module --</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" data-alias="{{ $module->alias }}">
                                        {{ $module->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Menu/Feature Name</label>
                            <input type="text" name="bulk_menu_name" class="form-control" 
                                   placeholder="e.g., list, reports" required>
                        </div>
                    </div>

                    <div style="margin-top: 16px;">
                        <label class="form-label">Actions to Create</label>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bulk_actions[]" value="read" checked>
                                <span>Read</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bulk_actions[]" value="create" checked>
                                <span>Create</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bulk_actions[]" value="edit" checked>
                                <span>Edit</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bulk_actions[]" value="delete" checked>
                                <span>Delete</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="bulk_actions[]" value="export">
                                <span>Export</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top: 16px;">
                        Create All Selected Permissions
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moduleSelect = document.getElementById('module_id');
            const menuInput = document.getElementById('menu_name');
            const actionSelect = document.getElementById('action_type');
            const customInput = document.getElementById('custom_action');
            const customWrapper = document.getElementById('custom_action_wrapper');
            const preview = document.getElementById('permission_preview');
            const hiddenName = document.getElementById('permission_name');

            function updatePreview() {
                const moduleOption = moduleSelect.options[moduleSelect.selectedIndex];
                const moduleAlias = moduleOption?.dataset?.alias || 'module';
                const menuName = menuInput.value.toLowerCase().replace(/\s+/g, '_') || 'menu';
                let action = actionSelect.value || 'action';
                
                if (action === 'custom') {
                    action = customInput.value.toLowerCase().replace(/\s+/g, '_') || 'custom';
                }

                const permissionName = `${moduleAlias}.${menuName}.${action}`;
                preview.textContent = permissionName;
                hiddenName.value = permissionName;
            }

            // Show/hide custom action input
            actionSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customWrapper.style.display = 'block';
                } else {
                    customWrapper.style.display = 'none';
                }
                updatePreview();
            });

            moduleSelect.addEventListener('change', updatePreview);
            menuInput.addEventListener('input', updatePreview);
            customInput.addEventListener('input', updatePreview);

            // Initial preview
            updatePreview();
        });
    </script>
</x-layouts.app>