<x-layouts.app>
    <div style="max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="page-title">Client User Details</h1>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.settings.client.edit', $user->id) }}" class="btn btn-primary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.settings.client.index') }}" class="btn btn-secondary">
                    <svg style="width: 18px; height: 18px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 20px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="card-title" style="margin: 0;">{{ $user->name }}</h3>
                        <p style="margin: 4px 0 0; font-size: 14px; color: #6b7280;">ID: #{{ $user->id }}</p>
                    </div>
                </div>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 13px; font-weight: 500; border-radius: 20px;
                             background-color: {{ $user->is_active ? '#d1fae5' : '#fee2e2' }}; 
                             color: {{ $user->is_active ? '#065f46' : '#991b1b' }};">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $user->is_active ? '#10b981' : '#ef4444' }};"></span>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 24px;">
                    {{-- Contact Information --}}
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">
                            Contact Information
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 500;">Email</label>
                                <p style="margin: 4px 0 0; font-size: 15px; color: #111827;">
                                    <a href="mailto:{{ $user->email }}" style="color: #4f46e5; text-decoration: none;">{{ $user->email }}</a>
                                </p>
                            </div>
                        </div>
                    </div>


                    {{-- Account Information --}}
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 16px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">
                            Account Information
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 500;">Created At</label>
                                <p style="margin: 4px 0 0; font-size: 15px; color: #111827;">
                                    {{ $user->created_at->format('M d, Y') }}
                                    <span style="color: #6b7280; font-size: 13px;">at {{ $user->created_at->format('h:i A') }}</span>
                                </p>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 500;">Last Updated</label>
                                <p style="margin: 4px 0 0; font-size: 15px; color: #111827;">
                                    {{ $user->updated_at->format('M d, Y') }}
                                    <span style="color: #6b7280; font-size: 13px;">at {{ $user->updated_at->format('h:i A') }}</span>
                                </p>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 500;">Email Verified</label>
                                <p style="margin: 4px 0 0; font-size: 15px;">
                                    @if($user->email_verified_at)
                                        <span style="color: #10b981;">
                                            <svg style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 4px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span style="color: #f59e0b;">
                                            <svg style="width: 16px; height: 16px; display: inline; vertical-align: middle; margin-right: 4px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Not verified
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 500;">Account Status</label>
                                <p style="margin: 4px 0 0; font-size: 15px; color: #111827;">
                                    {{ $user->status ?? ($user->is_active ? 'Active' : 'Inactive') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between;">
                    <div style="display: flex; gap: 12px;">
                        <a href="{{ route('admin.settings.client.edit', $user->id) }}" class="btn btn-primary">Edit User</a>
                        <button type="button" onclick="toggleStatus()" class="btn btn-secondary">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                    <button type="button" onclick="deleteUser()" class="btn btn-danger">Delete User</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleStatus() {
            fetch(`{{ url('admin/settings/client-users') }}/{{ $user->id }}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to update status');
                }
            });
        }

        function deleteUser() {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch(`{{ url('admin/settings/client-users') }}/{{ $user->id }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        window.location.href = '{{ route('admin.settings.client.index') }}';
                    } else {
                        alert(data.message || 'Failed to delete user');
                    }
                });
            }
        }
    </script>
</x-layouts.app>