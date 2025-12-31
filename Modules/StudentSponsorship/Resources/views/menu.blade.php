{{-- StudentSponsorship Module - Admin Sidebar Menu --}}
{!! admin_menu([
    'title' => 'Student Sponsorship',
    'icon' => 'academic-cap',
    'route' => 'admin.studentsponsorship.*',
    'children' => [
        ['title' => 'Dashboard', 'route' => 'admin.studentsponsorship.dashboard', 'icon' => 'home'],
        ['title' => 'Sponsors', 'route' => 'admin.studentsponsorship.sponsors.index', 'icon' => 'heart'],
        ['title' => 'Transactions', 'route' => 'admin.studentsponsorship.transactions.index', 'icon' => 'currency-dollar'],
        ['title' => 'Payments', 'route' => 'admin.studentsponsorship.payments.index', 'icon' => 'banknotes'],
        ['title' => 'Receipt Templates', 'route' => 'admin.studentsponsorship.receipts.index', 'icon' => 'document-text'],
        ['title' => 'School Students', 'route' => 'admin.studentsponsorship.school-students.index', 'icon' => 'users'],
        ['title' => 'Completed School', 'route' => 'admin.studentsponsorship.school-students.completed', 'icon' => 'check-circle'],
        ['title' => 'University Students', 'route' => 'admin.studentsponsorship.university-students.index', 'icon' => 'building-library'],
        ['title' => 'Completed University', 'route' => 'admin.studentsponsorship.university-students.completed', 'icon' => 'check-circle'],
        ['title' => 'Master Data', 'route' => 'admin.studentsponsorship.master-data.index', 'icon' => 'cog'],
    ]
]) !!}
