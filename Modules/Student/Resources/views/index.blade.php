<x-layouts.app>
    <div style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Student Management</h1>
            <a href="{{ route('admin.student.create') }}" style="background: #3498DB; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                + Add New Student
            </a>
        </div>

        @if(session('success'))
            <div style="background: #D4EDDA; border: 1px solid #C3E6CB; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Just add classes - features appear automatically! --}}
        <table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
               data-route="{{ route('admin.student.data') }}">
            <thead>
                <tr>
                    <th class="dt-sort" data-col="id">ID</th>
                    <th class="dt-sort" data-col="name">Name</th>
                    <th class="dt-sort" data-col="email">Email</th>
                    <th class="dt-sort" data-col="phone">Phone</th>
                    <th class="dt-sort" data-col="course">Course</th>
                    <th class="dt-sort" data-col="status" data-render="badge">Status</th>
                    <th class="dt-sort" data-col="admission_date" data-render="date">Admission</th>
                    <th data-render="actions">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @include('core::datatable')
</x-layouts.app>
