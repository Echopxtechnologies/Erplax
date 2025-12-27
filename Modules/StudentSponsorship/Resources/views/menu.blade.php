{{-- StudentSponsorship Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Student Sponsorship',
    'icon' => 'academic-cap',
    'route' => 'admin.studentsponsorship.*',
    'children' => [
        ['title' => 'School Students', 'route' => 'admin.studentsponsorship.school-students.index', 'icon' => 'users'],
        ['title' => 'Add School Student', 'route' => 'admin.studentsponsorship.school-students.create', 'icon' => 'plus'],
    ]
]) !!}
