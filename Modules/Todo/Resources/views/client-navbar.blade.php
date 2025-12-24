{{-- Todo Module - Client Navbar Menu --}}
{!! client_menu([
    'title' => 'My Tasks',
    'icon' => 'clipboard-check',
    'route' => 'client.todo.*',
    'children' => [
        ['title' => 'All Tasks', 'route' => 'client.todo.index', 'icon' => 'list'],
    ]
]) !!}
