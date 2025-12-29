{{-- StudentSponsorship Module - Client Navbar Menu --}}
@php
    // Only show menu if user is a student
    $userId = auth()->id();
    $isStudent = \DB::table('school_students')->where('user_id', $userId)->exists()
              || \DB::table('university_students')->where('user_id', $userId)->exists();
@endphp

@if($isStudent)
{!! client_menu([
    'title' => 'My Form',
    'icon' => 'document-text',
    'route' => 'client.student-portal.*',
    'children' => [
        ['title' => 'View My Form', 'route' => 'client.student-portal.my-profile', 'icon' => 'clipboard-list'],
    ]
]) !!}
@endif
