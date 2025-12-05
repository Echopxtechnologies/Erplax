<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Customer Info</h1>
    </x-slot>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-light">← Back to Customers</a>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Create New Customer</a>
        </div>

        <div class="card" style="padding: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 600; margin: 0;">{{ $customer->name }}</h2>
                    <p style="font-size: 13px; color: var(--text-secondary); margin: 2px 0 0;">
                        {{ $customer->company ?: 'No company' }}
                    </p>
                </div>

                <div>
                    @if($customer->active)
                        <span class="badge badge-success"><span class="badge-dot"></span> Active</span>
                    @else
                        <span class="badge badge-danger"><span class="badge-dot"></span> Inactive</span>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px 16px; font-size: 13px;">
                <div>
                    <strong>Email</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->email }}</p>
                </div>
                <div>
                    <strong>Phone</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->phone ?: '—' }}</p>
                </div>
                <div>
                    <strong>GST Number</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->gst_number ?: '—' }}</p>
                </div>
                <div>
                    <strong>Pincode</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->pincode ?: '—' }}</p>
                </div>
                <div>
                    <strong>City</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->city ?: '—' }}</p>
                </div>
                <div>
                    <strong>State</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->state ?: '—' }}</p>
                </div>
                <div>
                    <strong>Country</strong>
                    <p style="margin: 2px 0 0;">{{ $customer->country ?: '—' }}</p>
                </div>
                <div style="grid-column: 1 / -1;">
                    <strong>Address</strong>
                    <p style="margin: 2px 0 0;">
                        {{ $customer->address_line1 }}<br>
                        {{ $customer->address_line2 }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
