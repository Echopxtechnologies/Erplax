{{-- Website Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Website',
    'icon' => 'globe',
    'route' => 'admin.website.*',
    'children' => [
        ['title' => 'Dashboard', 'route' => 'admin.website.index', 'icon' => 'layout-dashboard'],
        ['title' => 'Settings', 'route' => 'admin.website.settings', 'icon' => 'settings'],
    ]
]) !!}
