
<div style="margin-bottom:24px;">
    <a href="{{ route('admin.customers.create') }}" class="btn-modern btn-light" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;text-decoration:none;font-size:15px;font-weight:500;">
        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to form
    </a>
</div>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:var(--text-primary);">Customer Groups</h1>
        </div>
    </x-slot>

    <style>
        .groups-grid { display:grid; gap:16px; }
        .group-card { background:var(--card-bg); border:1px solid var(--card-border); border-radius:var(--radius-lg); padding:20px; transition:all 0.2s; }
        .group-card:hover { box-shadow:0 4px 12px rgba(0,0,0,.08); }
        .group-header { display:flex; justify-content:space-between; align-items:start; margin-bottom:12px; }
        .group-name { font-size:var(--font-lg); font-weight:600; color:var(--text-primary); }
        .group-count { display:inline-block; padding:4px 10px; background:var(--primary-light); color:var(--primary); border-radius:12px; font-size:11px; font-weight:600; margin-left:8px; }
        .group-desc { font-size:var(--font-sm); color:var(--text-muted); margin-bottom:12px; line-height:1.5; }
        .group-actions { display:flex; gap:6px; }
        .empty-state { text-align:center; padding:60px 20px; color:var(--text-muted); }
        .empty-icon { font-size:48px; margin-bottom:16px; }
        
        /* Create Form Card */
        .create-card { background:linear-gradient(135deg, var(--primary-light), var(--card-bg)); border:2px dashed var(--primary); border-radius:var(--radius-lg); padding:20px; margin-bottom:20px; }
        .create-card-title { font-size:var(--font-lg); font-weight:600; color:var(--primary); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .create-card-title svg { width:20px; height:20px; }
        
        .flbl { display:block; font-size:var(--font-sm); font-weight:500; color:var(--text-primary); margin-bottom:6px; }
        .req { color:var(--danger); }
        .finput { width:100%; padding:9px 12px; font-size:var(--font-base); border:1px solid var(--input-border); border-radius:var(--radius-md); background:var(--input-bg); color:var(--input-text); box-sizing:border-box; }
        .finput:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-light); }
        .ferr { color:var(--danger); font-size:var(--font-xs); margin-top:4px; }
        .frow { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .fcol-full { grid-column:1/-1; }
        .factions { display:flex; justify-content:flex-end; gap:8px; margin-top:16px; }
        
        /* Modal */
        .modal { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; }
        .modal.active { display:flex; }
        .modal-content { background:var(--card-bg); border-radius:var(--radius-lg); max-width:500px; width:90%; max-height:90vh; overflow-y:auto; }
        .modal-header { padding:20px; border-bottom:1px solid var(--card-border); display:flex; justify-content:space-between; align-items:center; }
        .modal-title { font-size:18px; font-weight:600; color:var(--text-primary); }
        .modal-close { background:none; border:none; font-size:24px; color:var(--text-muted); cursor:pointer; padding:0; width:30px; height:30px; }
        .modal-body { padding:20px; }
        .modal-footer { padding:16px 20px; border-top:1px solid var(--card-border); display:flex; justify-content:flex-end; gap:8px; }
        
        @media(max-width:768px) { .frow { grid-template-columns:1fr; } }
    </style>

    <!-- Create New Group Form -->


<br>
<br>

    <div class="create-card">
        <div class="create-card-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Customer Group
        </div>
        
        <form action="{{ route('admin.customer-groups.store') }}" method="POST">
            @csrf
            <div class="frow">
                <div>
                    <label class="flbl">Group Name <span class="req">*</span></label>
                    <input type="text" name="name" class="finput" value="{{ old('name') }}" required autofocus placeholder="e.g., VIP Clients, Wholesale, Retail">
                    @error('name')<div class="ferr">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="flbl">Description</label>
                    <input type="text" name="description" class="finput" value="{{ old('description') }}" placeholder="Optional group description">
                    @error('description')<div class="ferr">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="factions">
                <button type="reset" class="btn btn-light btn-sm">Clear</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Group
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Groups -->
    @if($customerGroups->count() > 0)
        <h2 style="font-size:var(--font-lg);font-weight:600;color:var(--text-primary);margin-bottom:16px;">
            Existing Groups ({{ $customerGroups->count() }})
        </h2>
    @endif

    <div class="groups-grid">
        @forelse($customerGroups as $group)
            <div class="group-card">
                <div class="group-header">
                    <div>
                        <span class="group-name">{{ $group->name }}</span>
                        <span class="group-count">{{ $group->customers_count }} {{ Str::plural('customer', $group->customers_count) }}</span>
                    </div>
                    <div class="group-actions">
                        <a href="{{ route('admin.customer-groups.edit', $group->id) }}" onclick="return showEditModal(event, {{ $group->id }}, '{{ addslashes($group->name) }}', '{{ addslashes($group->description ?? '') }}')" class="btn btn-light btn-sm" style="font-size:11px;padding:4px 10px;">Edit</a>
                        @if($group->isDeletable())
                            <form action="{{ route('admin.customer-groups.destroy', $group->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this group? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm" style="font-size:11px;padding:4px 10px;color:var(--danger);">Delete</button>
                            </form>
                        @else
                            <button class="btn btn-light btn-sm" disabled style="font-size:11px;padding:4px 10px;opacity:0.5;" title="Cannot delete group with assigned customers">Delete</button>
                        @endif
                    </div>
                </div>
                @if($group->description)
                    <div class="group-desc">{{ $group->description }}</div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📁</div>
                <h3 style="font-size:var(--font-lg);color:var(--text-primary);margin-bottom:8px;">No Customer Groups Yet</h3>
                <p>Use the form above to create your first customer group.</p>
            </div>
        @endforelse
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Customer Group</h3>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <label class="flbl">Group Name <span class="req">*</span></label>
                    <input type="text" name="name" id="editName" class="finput" required style="margin-bottom:16px;">
                    <div class="ferr" id="editNameError"></div>
                    
                    <label class="flbl">Description</label>
                    <textarea name="description" id="editDescription" class="finput" rows="3"></textarea>
                    <div class="ferr" id="editDescError"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="closeModal('editModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">💾 Update Group</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showEditModal(e, id, name, description) {
            if (e) e.preventDefault();
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description || '';
            document.getElementById('editForm').action = "{{ route('admin.customer-groups.index') }}/" + id;
            document.getElementById('editModal').classList.add('active');
            return false;
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            // Clear errors
            document.querySelectorAll('.ferr').forEach(el => el.textContent = '');
        }

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // Handle edit form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const csrf = document.querySelector('meta[name="csrf-token"]');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf ? csrf.content : '',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof Toast !== 'undefined') {
                        Toast.success(data.message || 'Group updated successfully');
                    }
                    window.location.reload();
                } else {
                    if (data.errors) {
                        if (data.errors.name) {
                            document.getElementById('editNameError').textContent = data.errors.name[0];
                        }
                        if (data.errors.description) {
                            document.getElementById('editDescError').textContent = data.errors.description[0];
                        }
                    }
                    if (typeof Toast !== 'undefined') {
                        Toast.error(data.message || 'Validation failed');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Toast !== 'undefined') {
                    Toast.error('Failed to update group');
                }
            });
        });
    </script>
