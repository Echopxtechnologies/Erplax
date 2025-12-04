<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div style="margin-bottom: 16px; padding: 12px 16px; background: var(--success-light); color: var(--success); border-radius: var(--radius-md); display: flex; align-items: center; gap: 8px;" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
        <div>
            <h1 class="page-title">Permissions</h1>
            <p style="color: var(--text-muted); font-size: var(--font-sm); margin-top: 4px;">Manage system permissions</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            Add Permission
        </button>
    </div>

    {{-- Search --}}
    <div style="margin-bottom: var(--space-lg);">
        <div style="position: relative; max-width: 320px;">
            <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--text-muted);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search permissions..." 
                   class="form-control"
                   style="padding-left: 40px;">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--body-bg); border-bottom: 1px solid var(--card-border);">
                    <th style="padding: 12px 16px; text-align: left; font-size: var(--font-xs); font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        #
                    </th>
                    <th style="padding: 12px 16px; text-align: left; font-size: var(--font-xs); font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        Permission Name
                    </th>
                    <th style="padding: 12px 16px; text-align: left; font-size: var(--font-xs); font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        Created At
                    </th>
                    <th style="padding: 12px 16px; text-align: right; font-size: var(--font-xs); font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $index => $permission)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td style="padding: 14px 16px; color: var(--text-muted); font-size: var(--font-sm);">
                            {{ $permissions->firstItem() + $index }}
                        </td>
                        <td style="padding: 14px 16px;">
                            <span class="badge badge-info">
                                {{ $permission->name }}
                            </span>
                        </td>
                        <td style="padding: 14px 16px; color: var(--text-secondary); font-size: var(--font-sm);">
                            {{ $permission->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 14px 16px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                <button wire:click="openEditModal({{ $permission->id }})" class="btn btn-sm btn-warning">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                
                                @if ($confirmingDelete === $permission->id)
                                    <button wire:click="delete({{ $permission->id }})" class="btn btn-sm btn-danger">
                                        Confirm
                                    </button>
                                    <button wire:click="cancelDelete" class="btn btn-sm btn-light">
                                        Cancel
                                    </button>
                                @else
                                    <button wire:click="confirmDelete({{ $permission->id }})" class="btn btn-sm btn-danger">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 48px 16px; text-align: center;">
                            <svg style="width: 48px; height: 48px; color: var(--text-muted); margin: 0 auto 12px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 style="font-size: var(--font-md); font-weight: 600; color: var(--text-primary); margin: 0 0 4px;">No permissions found</h3>
                            <p style="font-size: var(--font-sm); color: var(--text-muted); margin: 0 0 16px;">Get started by creating a new permission.</p>
                            <button wire:click="openCreateModal" class="btn btn-primary">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Permission
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($permissions->hasPages())
            <div style="padding: 12px 16px; border-top: 1px solid var(--card-border);">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div style="position: fixed; inset: 0; z-index: 1200; display: flex; align-items: center; justify-content: center; padding: 16px;">
            {{-- Backdrop --}}
            <div wire:click="closeModal" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5);"></div>
            
            {{-- Modal Content --}}
            <div class="card" style="position: relative; width: 100%; max-width: 480px; z-index: 1;">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="card-title">
                        {{ $isEditing ? 'Edit Permission' : 'Create New Permission' }}
                    </h3>
                    <button wire:click="closeModal" style="width: 32px; height: 32px; border: none; background: var(--body-bg); border-radius: var(--radius-md); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 18px; height: 18px; color: var(--text-muted);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="save">
                    <div class="card-body">
                        <div style="display: flex; align-items: flex-start; gap: 16px;">
                            <div style="width: 44px; height: 44px; background: var(--primary-light); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 22px; height: 22px; color: var(--primary);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <label class="form-label">Permission Name</label>
                                <input wire:model="name" 
                                       type="text" 
                                       class="form-control @error('name') error @enderror" 
                                       placeholder="e.g., users.create, posts.delete"
                                       style="@error('name') border-color: var(--danger); @enderror">
                                @error('name')
                                    <p style="margin-top: 6px; font-size: var(--font-sm); color: var(--danger);">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div style="padding: 12px var(--space-lg); background: var(--body-bg); border-top: 1px solid var(--card-border); display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" wire:click="closeModal" class="btn btn-light">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                {{ $isEditing ? 'Update' : 'Create' }}
                            </span>
                            <span wire:loading wire:target="save">
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>