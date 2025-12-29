{{-- StudentSponsorship Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Student Sponsorship',
    'icon' => 'academic-cap',
    'route' => 'admin.studentsponsorship.*',
    'children' => [
        ['title' => 'School Students', 'route' => 'admin.studentsponsorship.school-students.index', 'icon' => 'users'],
        ['title' => 'Completed School', 'route' => 'admin.studentsponsorship.school-students.completed', 'icon' => 'check-circle'],
        ['title' => 'University Students', 'route' => 'admin.studentsponsorship.university-students.index', 'icon' => 'building-library'],
        ['title' => 'Completed University', 'route' => 'admin.studentsponsorship.university-students.completed', 'icon' => 'check-circle'],
        ['title' => 'Master Data', 'route' => 'admin.studentsponsorship.master-data.index', 'icon' => 'cog'],
    ]
]) !!}
