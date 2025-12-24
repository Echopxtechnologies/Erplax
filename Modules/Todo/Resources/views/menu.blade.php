{{-- Todo Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Todo',
    'icon' => 'clipboard-check',
    'route' => 'admin.todo.*',
    'children' => [
        ['title' => 'All Tasks', 'route' => 'admin.todo.index', 'icon' => 'list'],
        ['title' => 'Add New Task', 'route' => 'admin.todo.create', 'icon' => 'plus'],
    ]
]) !!}
