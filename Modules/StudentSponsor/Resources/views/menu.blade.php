{{-- StudentSponsor Module - Sidebar Menu --}}
{!! \App\Livewire\Admin\AdminComponent::renderMenu([
    'title' => 'Student Sponsor',
    'icon' => 'graduation-cap',
    'route' => 'admin.studentsponsor.*',
    'children' => [
        ['title' => 'Dashboard', 'route' => 'admin.studentsponsor.dashboard', 'icon' => 'dashboard'],
        ['title' => 'School Students', 'route' => 'admin.studentsponsor.school.index', 'icon' => 'child'],
        ['title' => 'University Students', 'route' => 'admin.studentsponsor.university.index', 'icon' => 'university'],
        ['title' => 'Sponsors', 'route' => 'admin.studentsponsor.sponsor.index', 'icon' => 'handshake'],
        ['title' => 'Transactions', 'route' => 'admin.studentsponsor.transaction.index', 'icon' => 'exchange-alt'],
        ['title' => 'Payments', 'route' => 'admin.studentsponsor.payment.index', 'icon' => 'money-bill'],
        ['title' => 'Banks', 'route' => 'admin.studentsponsor.bank.index', 'icon' => 'building'],
        ['title' => 'School Names', 'route' => 'admin.studentsponsor.schoolname.index', 'icon' => 'school'],
        ['title' => 'University Names', 'route' => 'admin.studentsponsor.universityname.index', 'icon' => 'university'],
        ['title' => 'Programs', 'route' => 'admin.studentsponsor.program.index', 'icon' => 'book'],
    ]
]) !!}
