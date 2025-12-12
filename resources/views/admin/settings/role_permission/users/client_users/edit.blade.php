<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Edit Client User</h1>

        @if($errors->any())
            <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">Edit User: {{ $user->name }}</h3>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500; border-radius: 20px;
                             background-color: {{ $user->is_active ? '#d1fae5' : '#fee2e2' }}; 
                             color: {{ $user->is_active ? '#065f46' : '#991b1b' }};">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $user->is_active ? '#10b981' : '#ef4444' }};"></span>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.client.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="display: grid; gap: 20px;">
                        {{-- Name --}}
                        <div>
                            <label class="form-label">Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="form-label">Email <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                        </div>


                        {{-- Password --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label">Password <span style="color: #9ca3af; font-weight: normal;">(leave blank to keep current)</span></label>
                                <input type="password" name="password" class="form-control" placeholder="New password">
                            </div>
                            <div>
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" 
                                       style="width: 18px; height: 18px; cursor: pointer;"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <span class="form-label" style="margin: 0;">Active</span>
                            </label>
                            <p style="font-size: 13px; color: #6b7280; margin: 4px 0 0 28px;">Allow this user to log in</p>
                        </div>

                        {{-- Meta Info --}}
                        <div style="background-color: #f9fafb; padding: 16px; border-radius: 6px; border: 1px solid #e5e7eb;">
                            <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 12px;">User Information</h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                                <div>
                                    <span style="color: #6b7280;">User ID:</span>
                                    <span style="color: #111827; font-weight: 500; margin-left: 8px;">#{{ $user->id }}</span>
                                </div>
                                <div>
                                    <span style="color: #6b7280;">Created:</span>
                                    <span style="color: #111827; margin-left: 8px;">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div>
                                    <span style="color: #6b7280;">Last Updated:</span>
                                    <span style="color: #111827; margin-left: 8px;">{{ $user->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($user->email_verified_at)
                                <div>
                                    <span style="color: #6b7280;">Email Verified:</span>
                                    <span style="color: #10b981; margin-left: 8px;">✓ {{ $user->email_verified_at->format('M d, Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; justify-content: space-between;">
                            <div style="display: flex; gap: 12px;">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="{{ route('admin.settings.client.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                            <button type="button" onclick="deleteUser({{ $user->id }})" class="btn btn-danger">Delete User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch(`{{ url('admin/settings/client-users') }}/${id}`, {
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