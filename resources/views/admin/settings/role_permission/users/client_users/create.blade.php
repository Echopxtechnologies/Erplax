<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Create Client User</h1>

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
            <div class="card-header">
                <h3 class="card-title">New Client User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.client.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        {{-- Name --}}
                        <div>
                            <label class="form-label">Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter full name" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="form-label">Email <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email address" required>
                        </div>


                        {{-- Password --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label">Password <span style="color: #ef4444;">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div>
                                <label class="form-label">Confirm Password <span style="color: #ef4444;">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" 
                                       style="width: 18px; height: 18px; cursor: pointer;"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="form-label" style="margin: 0;">Active</span>
                            </label>
                            <p style="font-size: 13px; color: #6b7280; margin: 4px 0 0 28px;">Allow this user to log in</p>
                        </div>

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Create User</button>
                            <a href="{{ route('admin.settings.client.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>