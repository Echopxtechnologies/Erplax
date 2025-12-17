<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h1 style="margin:0;font-size:20px;font-weight:600;color:var(--text-primary);">Edit Customer Group</h1>
            <a href="{{ route('admin.customer-groups.index') }}" class="btn btn-light btn-sm">← Back</a>
        </div>
    </x-slot>

    <style>
        .cform { max-width:600px; margin:0 auto; }
        .ccard { background:var(--card-bg); border-radius:var(--radius-lg); box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:16px; border:1px solid var(--card-border); }
        .ccard-b { padding:20px; }
        .flbl { display:block; font-size:var(--font-sm); font-weight:500; color:var(--text-primary); margin-bottom:6px; }
        .req { color:var(--danger); }
        .finput { width:100%; padding:9px 12px; font-size:var(--font-base); border:1px solid var(--input-border); border-radius:var(--radius-md); background:var(--input-bg); color:var(--input-text); box-sizing:border-box; }
        .finput:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px var(--primary-light); }
        .ferr { color:var(--danger); font-size:var(--font-xs); margin-top:4px; }
        .factions { display:flex; justify-content:flex-end; gap:12px; padding:16px 0; }
    </style>

    <div class="cform">
        <form action="{{ route('admin.customer-groups.update', $customerGroup->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="ccard">
                <div class="ccard-b">
                    <div style="margin-bottom:16px;">
                        <label class="flbl">Group Name <span class="req">*</span></label>
                        <input type="text" name="name" class="finput" value="{{ old('name', $customerGroup->name) }}" required autofocus>
                        @error('name')<div class="ferr">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="flbl">Description</label>
                        <textarea name="description" class="finput" rows="4">{{ old('description', $customerGroup->description) }}</textarea>
                        @error('description')<div class="ferr">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="factions">
                <button type="submit" class="btn btn-primary">💾 Update Group</button>
                <a href="{{ route('admin.customer-groups.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>