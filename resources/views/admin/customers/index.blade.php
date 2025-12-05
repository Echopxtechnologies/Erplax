-<x-layouts.app>
    <x-slot name="header">
        <h1 class="page-title">Customers</h1>
    </x-slot>

    <div style="display: flex; flex-direction: column; gap: 16px;">

        {{-- Header row --}}
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Customer
                </a>

                <div style="position: relative;">
                    <input
                        type="text"
                        wire:model.debounce.400ms="search"
                        placeholder="Search customers..."
                        class="form-control"
                        style="padding-right: 32px; min-width: 220px;"
                    >
                    <svg style="width: 16px; height: 16px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                </div>
            </div>

            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                Manage your CRM customers
            </p>
        </div>

        {{-- Stats cards (similar style to modules) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
            <div class="card" style="padding: 14px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-5-4M9 11a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Total Customers</p>
                    <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $total }}</p>
                </div>
            </div>

            <div class="card" style="padding: 14px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--success-light); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: var(--success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">Active</p>
                    <p style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $activeCount }}</p>
                </div>
            </div>
        </div>

        {{-- Customers table --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between;">
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                    Showing {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
                </p>
            </div>

            <div style="overflow-x: auto;">
                <table class="table" style="width: 100%; font-size: 13px;">
                    <thead>
                        <tr style="background: var(--table-header-bg);">
                            <th style="padding: 8px 12px;">#</th>
                            <th style="padding: 8px 12px;">Name</th>
                            <th style="padding: 8px 12px;">Email</th>
                            <th style="padding: 8px 12px;">Phone</th>
                            <th style="padding: 8px 12px;">Company</th>
                            <th style="padding: 8px 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.customers.show', $customer) }}'">
                                <td style="padding: 8px 12px;">{{ $customer->id }}</td>
                                <td style="padding: 8px 12px;">
                                    <a href="{{ route('admin.customers.show', $customer) }}" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                        {{ $customer->name }}
                                    </a>
                                </td>
                                <td style="padding: 8px 12px;">{{ $customer->email }}</td>
                                <td style="padding: 8px 12px;">{{ $customer->phone }}</td>
                                <td style="padding: 8px 12px;">{{ $customer->company }}</td>
                                <td style="padding: 8px 12px;">
                                    @if($customer->active)
                                        <span class="badge badge-success"><span class="badge-dot"></span> Active</span>
                                    @else
                                        <span class="badge badge-danger"><span class="badge-dot"></span> Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 20px 12px; text-align: center; color: var(--text-secondary);">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 10px 16px; border-top: 1px solid var(--card-border);">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
