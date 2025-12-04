<x-layouts.app>
    <div style="max-width: 800px;">
        <h1 class="page-title" style="margin-bottom: 20px;">Edit Permission</h1>

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
                <h3 class="card-title">Edit Permission</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.permissions.update', $permission->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; gap: 16px;">
                        <div>
                            <label class="form-label">Permission Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $permission->name) }}" placeholder="Enter permission name">
                        </div>

                        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; gap: 12px;">
                            <button type="submit" class="btn btn-primary">
                                Update Permission
                            </button>
                            <a href="{{ route('admin.settings.permissions.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>