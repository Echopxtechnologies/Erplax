<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $pageTitle }}</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $isEdit ? 'Update attendance information' : 'Add a new attendance entry' }}</p>
        </div>
        <button wire:click="cancel" class="inline-flex items-center px-4 py-2 text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </button>
    </div>

    <!-- Form Card -->
    <form wire:submit="save" class="bg-gray-800 border border-gray-700 rounded-lg">
        <!-- Form Body -->
        <div class="p-6 space-y-6">
            <!-- Employee Selection -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Employee <span class="text-red-500">*</span>
                </label>
                <select 
                    wire:model="user_id"
                    id="user_id"
                    class="w-full bg-gray-700 border @error('user_id') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition"
                >
                    <option value="">Select an employee</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attendance Date -->
            <div>
                <label for="attendance_date" class="block text-sm font-medium text-gray-300 mb-2">
                    Attendance Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date"
                    wire:model="attendance_date"
                    id="attendance_date"
                    class="w-full bg-gray-700 border @error('attendance_date') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition"
                >
                @error('attendance_date')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Time Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="check_in_time" class="block text-sm font-medium text-gray-300 mb-2">
                        Check-In Time
                    </label>
                    <input 
                        type="datetime-local"
                        wire:model="check_in_time"
                        id="check_in_time"
                        class="w-full bg-gray-700 border @error('check_in_time') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition"
                    >
                    @error('check_in_time')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_out_time" class="block text-sm font-medium text-gray-300 mb-2">
                        Check-Out Time
                    </label>
                    <input 
                        type="datetime-local"
                        wire:model="check_out_time"
                        id="check_out_time"
                        class="w-full bg-gray-700 border @error('check_out_time') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition"
                    >
                    @error('check_out_time')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Selection -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select 
                    wire:model="status"
                    id="status"
                    class="w-full bg-gray-700 border @error('status') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition"
                >
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                    Notes
                </label>
                <textarea 
                    wire:model="notes"
                    id="notes"
                    rows="4"
                    placeholder="Add any notes or remarks..."
                    class="w-full bg-gray-700 border @error('notes') border-red-500 @else border-gray-600 @enderror text-white rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition resize-none"
                ></textarea>
                @error('notes')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Footer -->
        <div class="bg-gray-900 border-t border-gray-700 px-6 py-4 flex justify-end space-x-3">
            <button 
                type="button"
                wire:click="cancel"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
            >
                Cancel
            </button>
            <button 
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span wire:loading.remove>{{ $isEdit ? 'Update Attendance' : 'Create Attendance' }}</span>
                <span wire:loading>
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>
</div>
