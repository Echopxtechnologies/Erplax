{{-- <x-layouts.app> --}}
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

        @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

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
            <button type="button" id="tab-bulk" onclick="showTab('bulk')" 
                    style="padding: 12px 24px; font-size: 14px; font-weight: 500; border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; color: #6b7280;">
                Bulk Create
            </button>
        </div>

        {{-- TAB 1: Quick Builder --}}
        <div id="content-builder" class="tab-content">
            <div class="card" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                <div class="card-body">
                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Build permission using: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">module.menu.action</code>
                    </p>
                    
                    <form action="{{ route('admin.settings.permissions.store') }}" method="POST">
                        @csrf
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                            {{-- Module --}}
                            <div>
                                <label class="form-label">Module</label>
                                <select name="module_id" id="builder_module" class="form-control" required>
                                    <option value="">Select...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" data-alias="{{ $module->alias }}">
                                            {{ $module->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Menu --}}
                            <div>
                                <label class="form-label">Menu/Feature</label>
                                <input type="text" name="menu_name" id="builder_menu" class="form-control" 
                                       placeholder="e.g., list, reports" required>
                            </div>

                            {{-- Action --}}
                            <div>
                                <label class="form-label">Action</label>
                                <select name="action_type" id="builder_action" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="read">read</option>
                                    <option value="create">create</option>
                                    <option value="edit">edit</option>
                                    <option value="delete">delete</option>
                                    <option value="export">export</option>
                                    <option value="import">import</option>
                                </select>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div style="padding: 16px; background-color: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #6b7280; font-size: 14px;">Permission:</span>
                                <span id="builder_preview" style="font-family: monospace; font-size: 18px; color: #0369a1; font-weight: 600;">
                                    module.menu.action
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="name" id="builder_name">

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Create Permission</button>
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
                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Enter custom permission name in format: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">module.menu.action</code>
                    </p>
                    
                    <form action="{{ route('admin.settings.permissions.store') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 20px;">
                            <label class="form-label">Permission Name</label>
                            <input type="text" name="name" id="custom_name" class="form-control" 
                                   placeholder="e.g., inventory.products.approve" 
                                   pattern="^[a-z]+\.[a-z_]+\.[a-z_]+$"
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
                            <button type="submit" class="btn btn-primary">Create Permission</button>
                            <a href="{{ route('admin.settings.permissions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB 3: Bulk Create --}}
        <div id="content-bulk" class="tab-content" style="display: none;">
            <div class="card" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                <div class="card-body">
                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Create multiple permissions at once (read, create, edit, delete)
                    </p>
                    
                    <form action="{{ route('admin.settings.permissions.store-bulk') }}" method="POST">
                        @csrf
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                            <div>
                                <label class="form-label">Module</label>
                                <select name="bulk_module_id" id="bulk_module" class="form-control" required>
                                    <option value="">Select...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" data-alias="{{ $module->alias }}">
                                            {{ $module->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Menu/Feature</label>
                                <input type="text" name="bulk_menu_name" id="bulk_menu" class="form-control" 
                                       placeholder="e.g., list, reports" required>
                            </div>
                        </div>

                        {{-- Actions Checkboxes --}}
                        <div style="margin-bottom: 20px;">
                            <label class="form-label" style="margin-bottom: 12px;">Select Actions</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #dbeafe; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="bulk_actions[]" value="read" checked>
                                    <span style="font-weight: 500; color: #1e40af;">read</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #d1fae5; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="bulk_actions[]" value="create" checked>
                                    <span style="font-weight: 500; color: #065f46;">create</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #fef3c7; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="bulk_actions[]" value="edit" checked>
                                    <span style="font-weight: 500; color: #92400e;">edit</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #fee2e2; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="bulk_actions[]" value="delete" checked>
                                    <span style="font-weight: 500; color: #991b1b;">delete</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #e0e7ff; border-radius: 6px; cursor: pointer;">
                                    <input type="checkbox" name="bulk_actions[]" value="export">
                                    <span style="font-weight: 500; color: #3730a3;">export</span>
                                </label>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div style="padding: 16px; background-color: #f9fafb; border-radius: 8px; margin-bottom: 20px;">
                            <label style="font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Will create:</label>
                            <div id="bulk_preview" style="font-family: monospace; font-size: 14px; color: #6b7280;">
                                Select module and menu to preview
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Create All Permissions</button>
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
            const preview = document.getElementById('builder_preview');
            const hidden = document.getElementById('builder_name');

            const moduleAlias = moduleSelect.options[moduleSelect.selectedIndex]?.dataset?.alias || 'module';
            const menu = menuInput.value.toLowerCase().replace(/\s+/g, '_') || 'menu';
            const action = actionSelect.value || 'action';

            const name = `${moduleAlias}.${menu}.${action}`;
            preview.textContent = name;
            hidden.value = name;
        }

        document.getElementById('builder_module').addEventListener('change', updateBuilderPreview);
        document.getElementById('builder_menu').addEventListener('input', updateBuilderPreview);
        document.getElementById('builder_action').addEventListener('change', updateBuilderPreview);

        // Custom example click
        function setCustomValue(value) {
            document.getElementById('custom_name').value = value;
        }

        // Bulk preview
        function updateBulkPreview() {
            const moduleSelect = document.getElementById('bulk_module');
            const menuInput = document.getElementById('bulk_menu');
            const preview = document.getElementById('bulk_preview');
            const checkboxes = document.querySelectorAll('input[name="bulk_actions[]"]:checked');

            const moduleAlias = moduleSelect.options[moduleSelect.selectedIndex]?.dataset?.alias;
            const menu = menuInput.value.toLowerCase().replace(/\s+/g, '_');

            if (moduleAlias && menu) {
                const actions = Array.from(checkboxes).map(cb => cb.value);
                const permissions = actions.map(a => `${moduleAlias}.${menu}.${a}`);
                preview.innerHTML = permissions.map(p => `<div style="padding: 2px 0;">• ${p}</div>`).join('');
            } else {
                preview.textContent = 'Select module and menu to preview';
            }
        }

        document.getElementById('bulk_module').addEventListener('change', updateBulkPreview);
        document.getElementById('bulk_menu').addEventListener('input', updateBulkPreview);
        document.querySelectorAll('input[name="bulk_actions[]"]').forEach(cb => {
            cb.addEventListener('change', updateBulkPreview);
        });
    </script>
{{-- </x-layouts.app> --}}