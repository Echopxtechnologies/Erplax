<style>
    .profile-page { max-width: 900px; margin: 0 auto; }
    
    .profile-header-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: var(--space-xl);
    }
    
    .profile-header-bg {
        height: 100px;
        background: linear-gradient(135deg, var(--primary) 0%, #06b6d4 50%, #8b5cf6 100%);
    }
    
    .profile-header-content {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding: 0 var(--space-xl) var(--space-xl);
        margin-top: -40px;
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .profile-avatar-section {
        display: flex;
        align-items: flex-end;
        gap: var(--space-lg);
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: var(--primary);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        border: 3px solid var(--card-bg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    
    .profile-info { padding-bottom: 6px; }
    .profile-name { font-size: var(--font-lg); font-weight: 700; color: var(--text-primary); margin: 0; }
    .profile-email { font-size: var(--font-sm); color: var(--text-secondary); margin: 2px 0 0; }
    .profile-phone {
        font-size: var(--font-xs);
        color: var(--text-muted);
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .profile-phone svg { width: 12px; height: 12px; }
    
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-xl);
    }
    
    .card-title {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-title svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-lg);
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: var(--space-sm);
        margin-top: var(--space-lg);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--card-border);
    }
    
    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
        .profile-header-content { flex-direction: column; align-items: flex-start; }
        .profile-avatar-section { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="profile-page">
    <!-- Profile Header -->
    <div class="profile-header-card">
        <div class="profile-header-bg"></div>
        <div class="profile-header-content">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-email">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="profile-phone">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $user->phone }}
                        </p>
                    @endif
                </div>
            </div>
            <form action="{{ route('client.profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                @csrf
                @method('PUT')
                <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none" onchange="this.form.submit()">
                <button type="button" class="btn btn-light btn-sm" onclick="document.getElementById('avatarInput').click()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Change Photo
                </button>
            </form>
        </div>
    </div>

    <div class="profile-grid">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Personal Information
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('client.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="info-grid">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Enter address">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Change Password
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('client.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>