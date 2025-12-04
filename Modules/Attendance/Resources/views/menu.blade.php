{!! \App\Livewire\Admin\AdminComponent::renderMenu([
    'title' => 'Attendance',
    'icon' => 'calendar-check',
    'route' => 'admin.attendance.*',
    'children' => [
        [
            'title' => 'All Records',
            'route' => 'admin.attendance.index',
            'icon' => 'list'
        ],
        [
            'title' => 'Add Record',
            'route' => 'admin.attendance.create',
            'icon' => 'plus'
        ],
    ]
]) !!}
