<div>
    {{-- Quick Actions Bar --}}
    <div class="permission-actions-bar">
        <div class="actions-left">
            <button wire:click="selectAll" class="btn btn-sm btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Select All
            </button>
            <button wire:click="deselectAll" class="btn btn-sm btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Deselect All
            </button>
            <button wire:click="selectReadOnly" class="btn btn-sm btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Read Only
            </button>
        </div>
        
        <div class="actions-right">
            <span class="permission-count">
                Selected: <strong>{{ $this->selectedCount }}</strong> / {{ $this->totalCount }}
            </span>
            
            <div wire:loading class="saving-indicator">
                <svg class="spin" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                Saving...
            </div>
        </div>
    </div>

    {{-- Modules Grid --}}
    <div class="modules-container">
        @forelse($modulePermissions as $moduleId => $data)
            @php
                $module = $data['module'];
                $permissions = $data['permissions'];
                $isFullySelected = $this->isModuleFullySelected($moduleId);
                
                // Count permissions
                $totalPerms = 0;
                $selectedPerms = 0;
                foreach ($permissions as $menuPerms) {
                    foreach ($menuPerms as $perm) {
                        $totalPerms++;
                        if (in_array($perm->name, $selectedPermissions)) {
                            $selectedPerms++;
                        }
                    }
                }
            @endphp
            
            <div class="module-card" wire:key="module-{{ $moduleId }}">
                {{-- Module Header --}}
                <div class="module-header">
                    <div class="module-info">
                        <label class="module-checkbox">
                            <input type="checkbox" 
                                   wire:click="toggleModule({{ is_numeric($moduleId) ? $moduleId : \"'$moduleId'\" }}, $event.target.checked)"
                                   {{ $isFullySelected ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                        </label>
                        <div class="module-icon">
                            @switch($module->alias ?? 'default')
                                @case('book')
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    @break
                                @case('todo')
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    @break
                                @case('settings')
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    @break
                                @case('student')
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                    @break
                                @case('attendance')
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @break
                                @default
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                            @endswitch
                        </div>
                        <div class="module-text">
                            <h3>{{ $module->name }}</h3>
                            <span class="module-alias">{{ $module->alias ?? 'system' }}</span>
                        </div>
                    </div>
                    <div class="module-badge {{ $selectedPerms > 0 ? 'active' : '' }}">
                        {{ $selectedPerms }}/{{ $totalPerms }}
                    </div>
                </div>

                {{-- Module Permissions --}}
                <div class="module-body">
                    @foreach($permissions as $menuSlug => $menuPermissions)
                        @php
                            $isMenuFullySelected = $this->isMenuFullySelected($moduleId, $menuSlug);
                        @endphp
                        
                        <div class="menu-section" wire:key="menu-{{ $moduleId }}-{{ $menuSlug }}">
                            {{-- Menu Title --}}
                            <div class="menu-header">
                                <label class="menu-checkbox">
                                    <input type="checkbox" 
                                           wire:click="toggleMenu({{ is_numeric($moduleId) ? $moduleId : \"'$moduleId'\" }}, '{{ $menuSlug }}', $event.target.checked)"
                                           {{ $isMenuFullySelected ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                                <span class="menu-name">{{ ucfirst(str_replace('_', ' ', $menuSlug)) }}</span>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="action-buttons">
                                @foreach($menuPermissions as $permission)
                                    @php
                                        $isChecked = in_array($permission->name, $selectedPermissions);
                                        $parts = explode('.', $permission->name);
                                        $actionSlug = end($parts);
                                    @endphp
                                    
                                    <button type="button"
                                            wire:click="togglePermission('{{ $permission->name }}')"
                                            wire:key="perm-{{ $permission->id }}"
                                            class="action-btn action-{{ $actionSlug }} {{ $isChecked ? 'active' : '' }}">
                                        @switch($actionSlug)
                                            @case('read')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                @break
                                            @case('create')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                @break
                                            @case('edit')
                                            @case('update')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                @break
                                            @case('delete')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                @break
                                            @case('export')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                @break
                                            @case('import')
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                        @endswitch
                                        <span>{{ ucfirst($actionSlug) }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="empty-state">
                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3>No Permissions Found</h3>
                <p>Create permissions and link them to modules to see them here.</p>
            </div>
        @endforelse
    </div>

    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         x-cloak
         class="toast-notification">
        <div :class="type === 'success' ? 'toast-success' : 'toast-error'">
            <svg x-show="type === 'success'" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-text="message"></span>
        </div>
    </div>

    <style>
        /* Actions Bar */
        .permission-actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        
        .actions-left {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .actions-left .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .actions-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .permission-count {
            color: #6b7280;
            font-size: 14px;
        }
        
        .permission-count strong {
            color: #3b82f6;
            font-size: 16px;
        }
        
        .saving-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #3b82f6;
            font-size: 13px;
            font-weight: 500;
        }
        
        /* Modules Container */
        .modules-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        /* Module Card */
        .module-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        /* Module Header */
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: #fff;
        }
        
        .module-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        
        .module-icon {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .module-icon svg {
            color: #fff;
        }
        
        .module-text h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .module-alias {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .module-badge {
            padding: 6px 14px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .module-badge.active {
            background: rgba(255,255,255,0.25);
        }
        
        /* Module Checkbox */
        .module-checkbox,
        .menu-checkbox {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .module-checkbox input,
        .menu-checkbox input {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #fff;
        }
        
        .menu-checkbox input {
            accent-color: #3b82f6;
        }
        
        /* Module Body */
        .module-body {
            padding: 0;
        }
        
        /* Menu Section */
        .menu-section {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .menu-section:last-child {
            border-bottom: none;
        }
        
        .menu-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        
        .menu-name {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-left: 32px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            border-color: #d1d5db;
            background: #f3f4f6;
        }
        
        /* Action Colors */
        .action-btn.action-read.active {
            background: #dbeafe;
            border-color: #3b82f6;
            color: #1e40af;
        }
        
        .action-btn.action-create.active {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        
        .action-btn.action-edit.active,
        .action-btn.action-update.active {
            background: #fef3c7;
            border-color: #f59e0b;
            color: #92400e;
        }
        
        .action-btn.action-delete.active {
            background: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
        
        .action-btn.action-export.active {
            background: #e0e7ff;
            border-color: #6366f1;
            color: #3730a3;
        }
        
        .action-btn.action-import.active {
            background: #fce7f3;
            border-color: #ec4899;
            color: #9d174d;
        }
        
        /* Default active for unknown actions */
        .action-btn.active:not(.action-read):not(.action-create):not(.action-edit):not(.action-update):not(.action-delete):not(.action-export):not(.action-import) {
            background: #f3f4f6;
            border-color: #6b7280;
            color: #374151;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .empty-state svg {
            color: #9ca3af;
            margin-bottom: 16px;
        }
        
        .empty-state h3 {
            color: #374151;
            font-size: 18px;
            margin: 0 0 8px;
        }
        
        .empty-state p {
            color: #6b7280;
            margin: 0;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }
        
        .toast-success,
        .toast-error {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .toast-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .toast-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        /* Spin Animation */
        .spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        [x-cloak] { 
            display: none !important; 
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .permission-actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .actions-right {
                justify-content: space-between;
            }
            
            .action-buttons {
                margin-left: 0;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</div>