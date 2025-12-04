<!-- Todo Module Menu Item -->
<div class="sidebar-menu-item" style="margin: 8px 0;">
    <a href="{{ route('admin.todo.index') }}" 
       class="menu-link {{ request()->routeIs('admin.todo.*') ? 'active' : '' }}"
       style="display: flex; align-items: center; padding: 12px 16px; border-radius: 6px; text-decoration: none; color: #333; transition: all 0.2s; {{ request()->routeIs('admin.todo.*') ? 'background: #E3F2FD; color: #0066cc;' : '' }}">
        <!-- Todo Icon (SVG) -->
        <svg style="width: 20px; height: 20px; margin-right: 12px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <div style="flex: 1;">
            <div style="font-weight: 600; font-size: 14px;">Todo</div>
            <div style="font-size: 12px; color: {{ request()->routeIs('admin.todo.*') ? '#0066cc' : '#666' }}; margin-top: 2px;">Task Management</div>
        </div>
        <!-- Badge (optional - count) -->
        <span style="display: inline-block; background: #E74C3C; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-left: 8px;">
            {{ $todo_count ?? 0 }}
        </span>
    </a>
    
    <!-- Submenu (shows when on todo routes) -->
    @if(request()->routeIs('admin.todo.*'))
        <div style="margin-top: 8px; margin-left: 40px; display: flex; flex-direction: column; gap: 4px;">
            <a href="{{ route('admin.todo.index') }}" 
               class="{{ request()->routeIs('admin.todo.index') ? 'active' : '' }}"
               style="display: flex; align-items: center; padding: 8px 12px; border-radius: 4px; text-decoration: none; color: {{ request()->routeIs('admin.todo.index') ? '#0066cc' : '#666' }}; font-size: 13px; transition: all 0.2s;">
                <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                All Todos
            </a>
        </div>
    @endif
</div>
