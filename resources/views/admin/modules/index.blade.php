<x-slot name="header">
    <h1 class="page-title">Modules</h1>
</x-slot>

    

<div style="display: flex; flex-direction: column; gap: 16px;">

    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px;">Modules</h2>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Manage your application modules</p>
        </div>
        
        @can('modules.modules.upload')
            <button onclick="document.getElementById('uploadPanel').classList.toggle('hidden')" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Upload Module
            </button>
        @endcan
    </div>

    {{-- Upload Panel (Hidden by default) --}}
    @can('modules.modules.upload')
        <div id="uploadPanel" class="card hidden" style="padding: 16px;">
            <form action="{{ route('admin.modules.upload') }}" method="POST" enctype="multipart/form-data" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                @csrf
                <div style="flex: 1; min-width: 200px;">
                    <input type="file" name="module_zip" accept=".zip" required class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Upload ZIP</button>
                <button type="button" onclick="document.getElementById('uploadPanel').classList.add('hidden')" class="btn btn-light">Cancel</button>
            </form>
        </div>
    @endcan
    {{-- Stats Cards --}}
    @php
        $total = count($modules);
        $installed = collect($modules)->where('is_installed', true)->count();
        $active = collect($modules)->where('is_active', true)->count();
        
        // Create lookup for installed modules
        $installedModules = collect($modules)->where('is_active', true)->pluck('alias')->map(fn($a) => strtolower($a))->toArray();
    @endphp
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
        <div class="card" style="padding: 14px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Total Modules</p>
                <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $total }}</p>
            </div>
        </div>
        
        <div class="card" style="padding: 14px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--success-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Active</p>
                <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $active }}</p>
            </div>
        </div>
        
        <div class="card" style="padding: 14px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--warning-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px; color: var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Installed</p>
                <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $installed }}</p>
            </div>
        </div>
    </div>

    {{-- Modules Grid --}}
    @if($total > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            @foreach($modules as $module)
                <div class="card" style="overflow: hidden;">
                    {{-- Card Header --}}
                    <div style="padding: 14px 16px; border-bottom: 1px solid var(--card-border);">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 600; flex-shrink: 0;">
                                    {{ strtoupper(substr($module['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0;">{{ $module['name'] }}</h3>
                                    <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0;">{{ $module['alias'] }}</p>
                                </div>
                            </div>
                            <span style="font-size: 10px; font-weight: 500; color: var(--text-muted); background: var(--body-bg); padding: 3px 6px; border-radius: 4px;">
                                v{{ $module['version'] }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Card Body --}}
                    <div style="padding: 14px 16px;">
                        <p style="font-size: 12px; color: var(--text-secondary); margin: 0 0 10px; line-height: 1.5;">
                            {{ Str::limit($module['description'] ?? 'No description available', 80) }}
                        </p>

                        {{-- Show Dependencies with Checkmarks --}}
                        @if(!empty($module['requires']))
                            <p style="font-size: 11px; color: var(--text-muted); margin: 0 0 12px; padding: 6px 10px; background: var(--body-bg); border-radius: 4px;">
                                <strong>Depends on:</strong> 
                                @foreach($module['requires'] as $req)
                                    @php
                                        $isInstalled = in_array(strtolower($req), $installedModules);
                                    @endphp
                                    @if($isInstalled)
                                        <span style="color: var(--success);">✓</span>
                                    @else
                                        <span style="color: var(--danger);">✗</span>
                                    @endif
                                    {{ $req }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </p>
                        @endif
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            {{-- Status Badge --}}
                            @if(!$module['is_installed'])
                                <span class="badge badge-warning">
                                    <span class="badge-dot"></span>
                                    Not Installed
                                </span>
                            @elseif($module['is_core'])
                                <span class="badge badge-info">
                                    <span class="badge-dot"></span>
                                    Core
                                </span>
                            @elseif($module['is_active'])
                                <span class="badge badge-success">
                                    <span class="badge-dot"></span>
                                    Active
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <span class="badge-dot"></span>
                                    Inactive
                                </span>
                            @endif

                            {{-- Action Buttons --}}
                            <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                
                                {{-- NOT INSTALLED: Show Install + Delete --}}
                                @if(!$module['is_installed'])
                                @can('modules.modules.install')
                                    <form action="{{ route('admin.modules.install', $module['alias']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-xs">Install</button>
                                    </form>
                                @endcan
                                    
                                @can('modules.modules.delete')
                                    <form action="{{ route('admin.modules.delete', $module['alias']) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to DELETE this module? This will remove all files and cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                    </form>
                                @endcan
                                    
                                {{-- INSTALLED & ACTIVE --}}
                                @elseif($module['is_active'])
                                    {{-- Migrate Button --}}
                                    <form action="{{ route('admin.modules.migrate', $module['alias']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-light btn-xs" title="Run Migrations">
                                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>

                                    @if(!$module['is_core'])
                                        @can('modules.modules.deactivate')
                                            <form action="{{ route('admin.modules.deactivate', $module['alias']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-xs">Deactivate</button>
                                        </form>
                                        @endcan
                                        
                                        @can('modules.modules.uninstall')
                                            <form action="{{ route('admin.modules.uninstall', $module['alias']) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to UNINSTALL this module? Tables will be dropped but files will be kept.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-xs">Uninstall</button>
                                        </form>
                                        @endcan
                                        
                                    @endif
                                
                                {{-- INSTALLED & INACTIVE --}}
                                @else
                                    {{-- Migrate Button --}}
                                    @can('modules.modules.migrate')
                                        <form action="{{ route('admin.modules.migrate', $module['alias']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-light btn-xs" title="Run Migrations">
                                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    @can('modules.modules.activate')
                                    <form action="{{ route('admin.modules.activate', $module['alias']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-xs">Activate</button>
                                    </form>
                                    @endcan
                                    @if(!$module['is_core'])
                                    @can('modules.modules.uninstall')
                                        <form action="{{ route('admin.modules.uninstall', $module['alias']) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to UNINSTALL this module? Tables will be dropped but files will be kept.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-xs">Uninstall</button>
                                        </form>
                                    @endcan
                                        
                                    @endif
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="card" style="padding: 40px 20px; text-align: center; border-style: dashed;">
            <div style="width: 56px; height: 56px; margin: 0 auto 16px; border-radius: 50%; background: var(--body-bg); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 28px; height: 28px; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin: 0 0 8px;">No modules found</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0 0 16px;">Create your first module using the command below</p>
            <code style="display: inline-block; padding: 8px 16px; background: var(--body-bg); border-radius: 6px; font-size: 12px; color: var(--text-secondary);">
                php artisan module:make ModuleName
            </code>
        </div>
    @endif

</div>