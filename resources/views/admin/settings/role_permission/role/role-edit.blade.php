{{-- <x-layouts.app> --}}
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Edit Role</h1>

        {{-- Validation Errors --}}
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
                <h3 class="card-title">Edit Role: {{ $role->name }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.roles.update', $role->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        {{-- Role Name --}}
                        <div>
                            <label class="form-label">Role Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $role->name) }}" placeholder="Enter role name">
                        </div>

                        {{-- Permissions --}}
                        <div>
                            <label class="form-label" style="margin-bottom: 12px;">Assign Permissions</label>
                            
                            @if($permissions->count() > 0)
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; padding: 16px; background-color: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                                    @foreach($permissions as $permission)
                                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px; border-radius: 4px; transition: background-color 0.2s;"
                                               onmouseover="this.style.backgroundColor='#e5e7eb'" 
                                               onmouseout="this.style.backgroundColor='transparent'">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   style="width: 16px; height: 16px; cursor: pointer;"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <span style="font-size: 14px; color: #374151;">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div style="padding: 24px; text-align: center; background-color: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                                    <p style="margin: 0; color: #6b7280;">No permissions available. <a href="{{ route('admin.settings.permissions.create') }}" style="color: #4f46e5;">Create one first</a></p>
                                </div>
                            @endif
                        </div>

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">
                                Update Role
                            </button>
                            <a href="{{ route('admin.settings.roles.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{-- </x-layouts.app> --}}