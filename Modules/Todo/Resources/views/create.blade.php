<style>
    .form-page {
        max-width: 700px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .form-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .btn-back {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-back:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    .btn-back svg {
        width: 20px;
        height: 20px;
    }
    
    .form-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        background: var(--body-bg);
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-card-title svg {
        width: 20px;
        height: 20px;
        color: var(--primary);
    }
    
    .form-card-body {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .form-label .required {
        color: var(--danger);
    }
    
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        border-radius: 10px;
        color: var(--input-text);
        transition: all 0.2s;
    }
    
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-light);
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .form-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }
    
    .form-error {
        font-size: 12px;
        color: var(--danger);
        margin-top: 6px;
    }
    
    /* Priority Options */
    .priority-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .priority-option {
        flex: 1;
        min-width: 100px;
    }
    
    .priority-option input {
        display: none;
    }
    
    .priority-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px;
        background: var(--body-bg);
        border: 2px solid var(--card-border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .priority-option label:hover {
        border-color: var(--text-muted);
    }
    
    .priority-option input:checked + label {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    
    .priority-option .priority-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .priority-option .priority-icon svg {
        width: 18px;
        height: 18px;
    }
    
    .priority-option.low .priority-icon { background: var(--success-light); color: var(--success); }
    .priority-option.medium .priority-icon { background: var(--warning-light); color: var(--warning); }
    .priority-option.high .priority-icon { background: var(--danger-light); color: var(--danger); }
    
    .priority-option .priority-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        background: var(--body-bg);
        border-top: 1px solid var(--card-border);
    }
    
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-submit svg {
        width: 18px;
        height: 18px;
    }
    
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--card-bg);
        color: var(--text-secondary);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-cancel:hover {
        background: var(--body-bg);
        color: var(--text-primary);
    }
    
    /* Error Alert */
    .alert-errors {
        background: var(--danger-light);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .alert-errors-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--danger);
        margin-bottom: 8px;
    }
    
    .alert-errors ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .alert-errors li {
        font-size: 13px;
        color: var(--danger);
        margin-bottom: 4px;
    }
    
    /* Assign Section */
    .assign-section {
        background: var(--primary-light);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 10px;
        padding: 16px;
        margin-top: 20px;
    }
    
    .assign-section .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .assign-section .form-label svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }
</style>

<div class="form-page">
    <!-- Header -->
    <div class="form-header">
        <a href="{{ route('admin.todo.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1>Create New Task</h1>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert-errors">
            <div class="alert-errors-title">Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('admin.todo.store') }}" method="POST">
        @csrf
        
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Task Details
                </h2>
            </div>
            
            <div class="form-card-body">
                <!-- Title -->
                <div class="form-group">
                    <label class="form-label">
                        Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" class="form-input" 
                           value="{{ old('title') }}" 
                           placeholder="Enter task title..."
                           required>
                    @error('title')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" 
                              placeholder="Enter task description...">{{ old('description') }}</textarea>
                    <div class="form-hint">Optional: Add more details about this task</div>
                </div>

                <!-- Priority -->
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <div class="priority-options">
                        <div class="priority-option low">
                            <input type="radio" name="priority" value="low" id="priority_low" 
                                   {{ old('priority', 'medium') == 'low' ? 'checked' : '' }}>
                            <label for="priority_low">
                                <div class="priority-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                                <span class="priority-text">Low</span>
                            </label>
                        </div>
                        <div class="priority-option medium">
                            <input type="radio" name="priority" value="medium" id="priority_medium"
                                   {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
                            <label for="priority_medium">
                                <div class="priority-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 12H4"></path>
                                    </svg>
                                </div>
                                <span class="priority-text">Medium</span>
                            </label>
                        </div>
                        <div class="priority-option high">
                            <input type="radio" name="priority" value="high" id="priority_high"
                                   {{ old('priority', 'medium') == 'high' ? 'checked' : '' }}>
                            <label for="priority_high">
                                <div class="priority-icon">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                </div>
                                <span class="priority-text">High</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Status & Due Date -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-input" 
                               value="{{ old('due_date') }}">
                        <div class="form-hint">Optional: Set a deadline</div>
                    </div>
                </div>

                <!-- Assign To (Admin Only) -->
                @if($isAdmin && $users->count() > 0)
                    <div class="assign-section">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Assign To
                            </label>
                            <select name="assigned_to" class="form-select">
                                <option value="">-- Not Assigned --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">Assign this task to a team member. They will receive a notification.</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Task
                </button>
                <a href="{{ route('admin.todo.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>
