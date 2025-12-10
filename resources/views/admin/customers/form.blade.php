{{-- resources/views/admin/customers/form.blade.php --}}
<x-layouts.app>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 class="page-title" style="margin:0;">
                    {{ $isEdit ? 'Edit Customer' : 'Add Customer' }}
                </h1>
                <p style="margin:4px 0 0;font-size:12px;color:var(--text-secondary);">
                    {{ $isEdit ? 'Update customer details.' : 'Create a new customer record.' }}
                </p>
            </div>

            <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">
                ← Back to list
            </a>
        </div>
    </x-slot>

    <div style="max-width:760px;">
        <div class="card" style="padding:20px 18px;">

            <form
                action="{{ $isEdit ? route('admin.customers.update', $customer) : route('admin.customers.store') }}"
                method="POST"
            >
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">

                    <div>
                        <label for="name" class="form-label">Name *</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $customer->name) }}"
                            class="form-control"
                        >
                        @error('name')
                            <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $customer->email) }}"
                            class="form-control"
                        >
                        @error('email')
                            <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $customer->phone) }}"
                            class="form-control"
                        >
                        @error('phone')
                            <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="company" class="form-label">Company</label>
                        <input
                            id="company"
                            type="text"
                            name="company"
                            value="{{ old('company', $customer->company) }}"
                            class="form-control"
                        >
                        @error('company')
                            <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <label for="address" class="form-label">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="form-control"
                    >{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top:16px;">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="form-control"
                    >{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes')
                        <div style="color:var(--danger);font-size:11px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:8px;">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ $isEdit ? 'Update customer' : 'Save customer' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-layouts.app>
