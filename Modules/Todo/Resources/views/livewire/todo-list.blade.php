<div x-data="{ showCreateModal: false, showEditModal: false }">
    <!-- Filters and Create Button -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; margin-bottom: 20px;">
        <input 
            wire:model.live="search" 
            type="text" 
            placeholder="Search todos..."
            style="padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px;"
        >
        
        <select 
            wire:model.live="filterStatus"
            style="padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px;"
        >
            <option value="">All Status</option>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        
        <select 
            wire:model.live="filterPriority"
            style="padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px;"
        >
            <option value="">All Priority</option>
            @foreach($priorities as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        
        <button 
            @click="showCreateModal = true"
            style="background: #3498DB; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;"
        >
            + New Todo
        </button>
    </div>

    <!-- Todos Table -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        @if($todos->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #F8F9FA; border-bottom: 2px solid #DEE2E6;">
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Title</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Priority</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600;">Due Date</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todos as $todo)
                        <tr style="border-bottom: 1px solid #DEE2E6; transition: background 0.2s;" onmouseover="this.style.background='#F8F9FA'" onmouseout="this.style.background='white'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2C3E50;">{{ $todo->title }}</div>
                                @if($todo->description)
                                    <div style="font-size: 12px; color: #7F8C8D; margin-top: 4px;">{{ Str::limit($todo->description, 50) }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                <span style="display: inline-block; background: {{ $todo->status_badge_color }}; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    {{ $todo->status_label }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <span style="display: inline-block; background: {{ $todo->priority_badge_color }}; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                    {{ $todo->priority_label }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                @if($todo->due_date)
                                    <span style="color: #555;">{{ $todo->due_date->format('M d, Y') }}</span>
                                @else
                                    <span style="color: #BDC3C7;">—</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <button 
                                    @click="showEditModal = true; $wire.openEditModal({{ $todo->id }})"
                                    style="background: #F39C12; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; margin-right: 5px;"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="deleteTodo({{ $todo->id }})"
                                    onclick="return confirm('Are you sure?')"
                                    style="background: #E74C3C; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="padding: 20px; border-top: 1px solid #DEE2E6; display: flex; justify-content: center;">
                {{ $todos->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center;">
                <p style="color: #95A5A6; font-size: 16px; margin-bottom: 20px;">No todos found</p>
                <button 
                    @click="showCreateModal = true"
                    style="background: #3498DB; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;"
                >
                    Create First Todo
                </button>
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @livewire('todo::create-todo', [], key('create-todo-' . time()))

    <!-- Edit Modal -->
    @livewire('todo::edit-todo', [], key('edit-todo-' . time()))
</div>
