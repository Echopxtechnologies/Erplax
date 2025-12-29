{{-- StudentSponsorship Module - Client Mobile Menu --}}
@php
    // Only show menu if user is a student
    $userId = auth()->id();
    $isStudent = \DB::table('school_students')->where('user_id', $userId)->exists()
              || \DB::table('university_students')->where('user_id', $userId)->exists();
@endphp

@if($isStudent)
<a href="{{ route('client.student-portal.my-profile') }}" 
   class="mobile-nav-item {{ request()->routeIs('client.student-portal.*') ? 'active' : '' }}">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span>My Form</span>
</a>
@endif
