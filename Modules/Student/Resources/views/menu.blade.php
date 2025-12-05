{!! \App\Livewire\Admin\AdminComponent::renderMenu([
    'title' => 'Students',
    'icon' => 'user-graduate',
    'route' => 'admin.student.*',
    'children' => [
        ['title' => 'All Students', 'route' => 'admin.student.index', 'icon' => 'list'],
        ['title' => 'Add New Student', 'route' => 'admin.student.create', 'icon' => 'plus'],
    ]
]) !!}
