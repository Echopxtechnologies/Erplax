<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Edit User</h1>

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
                <h3 class="card-title">Edit User: {{ $user->name }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.users.update', $user->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        {{-- Name --}}
                        <div>
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Enter name">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Enter email">
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

                        {{-- Roles --}}
                        <div>
                            <label class="form-label" style="margin-bottom: 12px;">Assign Roles</label>
                            @if($roles->count() > 0)
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; padding: 16px; background-color: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                                    @foreach($roles as $role)
                                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px; border-radius: 4px;"
                                               onmouseover="this.style.backgroundColor='#e5e7eb'" 
                                               onmouseout="this.style.backgroundColor='transparent'">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                   style="width: 16px; height: 16px; cursor: pointer;"
                                                   {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                            <span style="font-size: 14px; color: #374151;">{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div style="padding: 24px; text-align: center; background-color: #f9fafb; border-radius: 6px;">
                                    <p style="margin: 0; color: #6b7280;">No roles available.</p>
                                </div>
                            @endif
                        </div>

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="{{ route('admin.settings.users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>