<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Attendance Management</h1>
            <p class="text-gray-400 text-sm mt-1">Manage employee attendance records</p>
        </div>
        <a href="{{ route('admin.attendance.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Attendance
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Name or email..." class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Employee Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Employee</label>
                <select wire:model.live="user_id" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select wire:model.live="status" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- From Date -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">From Date</label>
                <input type="date" wire:model.live="from_date" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- To Date -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">To Date</label>
                <input type="date" wire:model.live="to_date" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 transition">
            </div>
        </div>

        <!-- Reset Button -->
        <div class="mt-4 flex justify-end">
            <button wire:click="resetFilters" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-900 border-b border-gray-700">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            <button wire:click="changeSort('user_id')" class="inline-flex items-center hover:text-white transition">
                                Employee
                                @if($sortBy === 'user_id')
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path d="M3 8l7-7 7 7M3 8l7 7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                        @else
                                            <path d="M3 12l7 7 7-7M3 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            <button wire:click="changeSort('attendance_date')" class="inline-flex items-center hover:text-white transition">
                                Date
                                @if($sortBy === 'attendance_date')
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDirection === 'asc')
                                            <path d="M3 8l7-7 7 7M3 8l7 7 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                        @else
                                            <path d="M3 12l7 7 7-7M3 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" />
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Check-In</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Check-Out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-750 transition">
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-white">{{ $attendance->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $attendance->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                {{ $attendance->attendance_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                @if($attendance->check_in_time)
                                    <span class="text-green-400">{{ $attendance->check_in_time->format('h:i A') }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                @if($attendance->check_out_time)
                                    <span class="text-red-400">{{ $attendance->check_out_time->format('h:i A') }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusColors = [
                                        'present' => 'bg-green-900 text-green-200',
                                        'absent' => 'bg-red-900 text-red-200',
                                        'late' => 'bg-yellow-900 text-yellow-200',
                                        'half-day' => 'bg-blue-900 text-blue-200',
                                        'on-leave' => 'bg-gray-600 text-gray-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$attendance->status] ?? 'bg-gray-700 text-gray-300' }}">
                                    <span class="w-2 h-2 rounded-full mr-1" style="background-color: currentColor;"></span>
                                    {{ ucfirst(str_replace('-', ' ', $attendance->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <a href="{{ route('admin.attendance.edit', $attendance->id) }}" wire:navigate class="inline-block text-blue-400 hover:text-blue-300 transition">Edit</a>
                                <button wire:click="confirmDelete({{ $attendance->id }})" class="inline-block text-red-400 hover:text-red-300 transition">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-400">No attendance records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
            <div class="bg-gray-900 border-t border-gray-700 px-6 py-4">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold text-white mb-4">Confirm Delete</h3>
                <p class="text-gray-400 mb-6">Are you sure you want to delete this attendance record? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition font-medium">Cancel</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
