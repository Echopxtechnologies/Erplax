<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Create Customer</h1>
    </x-slot>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-light">
            ← Back to Customers
        </a>

        <div class="card" style="padding: 16px;">
            <h2 style="font-size: 16px; font-weight: 600; margin-bottom: 14px;">New Customer</h2>

            <form wire:submit.prevent="save"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px 16px;">

                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" wire:model.defer="name">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" wire:model.defer="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" wire:model.defer="phone">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" wire:model.defer="company">
                    @error('company') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">GST Number</label>
                    <input type="text" class="form-control" wire:model.defer="gst_number">
                    @error('gst_number') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Pincode</label>
                    <input type="text" class="form-control" wire:model.defer="pincode">
                    @error('pincode') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" wire:model.defer="city">
                    @error('city') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" wire:model.defer="state">
                    @error('state') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" wire:model.defer="country">
                    @error('country') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" wire:model.defer="address_line1">
                    @error('address_line1') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" class="form-control" wire:model.defer="address_line2">
                    @error('address_line2') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="grid-column: 1 / -1; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="active" wire:model.defer="active" class="form-checkbox">
                    <label for="active" class="form-label" style="margin: 0;">Active</label>
                </div>

                <div style="grid-column: 1 / -1; display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
