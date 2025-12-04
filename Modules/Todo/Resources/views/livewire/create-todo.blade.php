<!-- Create Modal - Controlled by parent Alpine x-data -->
<div 
    x-show="showCreateModal"
    @click.self="showCreateModal = false"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999;"
    :style="showCreateModal ? 'display: flex' : 'display: none'"
    wire:ignore
>
    <div style="background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #DEE2E6; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 20px; color: #2C3E50;">Create New Todo</h2>
            <button 
                @click="showCreateModal = false; $wire.resetForm()"
                style="background: none; border: none; font-size: 24px; cursor: pointer; color: #95A5A6;"
            >
                ×
            </button>
        </div>

        <!-- Form -->
        <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
            <!-- Title -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Title <span style="color: #E74C3C;">*</span></label>
                <input 
                    wire:model="title"
                    type="text"
                    placeholder="Enter todo title"
                    style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;"
                >
                @error('title')
                    <span style="color: #E74C3C; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Description</label>
                <textarea 
                    wire:model="description"
                    placeholder="Enter todo description"
                    style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; min-height: 100px; box-sizing: border-box; font-family: inherit;"
                ></textarea>
                @error('description')
                    <span style="color: #E74C3C; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status and Priority Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <!-- Status -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Status <span style="color: #E74C3C;">*</span></label>
                    <select 
                        wire:model="status"
                        style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;"
                    >
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <span style="color: #E74C3C; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Priority <span style="color: #E74C3C;">*</span></label>
                    <select 
                        wire:model="priority"
                        style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;"
                    >
                        @foreach($priorities as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <span style="color: #E74C3C; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Due Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Due Date</label>
                <input 
                    wire:model="due_date"
                    type="date"
                    style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;"
                >
                @error('due_date')
                    <span style="color: #E74C3C; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div style="padding: 20px; border-top: 1px solid #DEE2E6; display: flex; gap: 10px; justify-content: flex-end;">
            <button 
                @click="showCreateModal = false; $wire.resetForm()"
                style="background: #95A5A6; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;"
            >
                Cancel
            </button>
            <button 
                wire:click="save"
                @click="showCreateModal = false"
                style="background: #27AE60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;"
            >
                Create Todo
            </button>
        </div>
    </div>
</div>
