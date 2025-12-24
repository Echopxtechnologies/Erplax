@extends('website::public.auth.auth-layout')

@section('title', 'My Account - ' . ($settings->site_name ?? 'Store'))

@section('content')
<div class="account-page">
    <div class="account-container">
        {{-- Header --}}
        <div class="account-header">
            <a href="{{ route('website.shop') }}" class="back-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20"><path d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="header-info">
                <div class="avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                <div>
                    <h1>{{ $user->name ?? 'User' }}</h1>
                    <p>{{ $user->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('website.logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="20" height="20"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
        
        {{-- Quick Links --}}
        <div class="quick-links">
            <a href="{{ route('website.orders') }}" class="quick-link orders-link">
                <div class="link-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </div>
                <div class="link-info">
                    <span class="link-title">My Orders</span>
                    <span class="link-desc">View order history & track shipments</span>
                </div>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" class="link-arrow"><path d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('website.wishlist') }}" class="quick-link wishlist-link">
                <div class="link-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </div>
                <div class="link-info">
                    <span class="link-title">Wishlist</span>
                    <span class="link-desc">Your saved items</span>
                </div>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="16" height="16" class="link-arrow"><path d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert success">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error') || $errors->any())
        <div class="alert error">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif
        
        {{-- Tabs --}}
        <div class="tabs">
            <button class="tab active" data-tab="profile">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profile</span>
            </button>
            <button class="tab" data-tab="shipping">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <span>Shipping</span>
            </button>
            <button class="tab" data-tab="billing">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Billing</span>
            </button>
            <button class="tab" data-tab="security">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Security</span>
            </button>
        </div>
        
        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- Profile Tab --}}
            <div class="tab-pane active" id="profile">
                <form method="POST" action="{{ route('website.account.profile') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ $user->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" value="{{ $user->email ?? '' }}" disabled>
                            <span class="input-hint">Email cannot be changed</span>
                        </div>
                        <div class="form-group full">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="{{ $customer->phone ?? '' }}" placeholder="+91 XXXXX XXXXX">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            
            {{-- Shipping Tab --}}
            <div class="tab-pane" id="shipping">
                <form method="POST" action="{{ route('website.account.shipping') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Street Address</label>
                            <textarea name="shipping_address" rows="2" placeholder="House no, Street name, Area">{{ $customer->shipping_address ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="shipping_city" value="{{ $customer->shipping_city ?? '' }}" placeholder="Enter city">
                        </div>
                        <div class="form-group">
                            <label>State / Province</label>
                            <input type="text" name="shipping_state" value="{{ $customer->shipping_state ?? '' }}" placeholder="Enter state">
                        </div>
                        <div class="form-group">
                            <label>PIN / ZIP Code</label>
                            <input type="text" name="shipping_zip_code" value="{{ $customer->shipping_zip_code ?? '' }}" placeholder="560001">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="shipping_country" value="{{ $customer->shipping_country ?? 'India' }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Shipping Address</button>
                    </div>
                </form>
            </div>
            
            {{-- Billing Tab --}}
            <div class="tab-pane" id="billing">
                <form method="POST" action="{{ route('website.account.billing') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Billing Address</label>
                            <textarea name="billing_street" rows="2" placeholder="House no, Street name, Area">{{ $customer->billing_street ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="billing_city" value="{{ $customer->billing_city ?? '' }}" placeholder="Enter city">
                        </div>
                        <div class="form-group">
                            <label>State / Province</label>
                            <input type="text" name="billing_state" value="{{ $customer->billing_state ?? '' }}" placeholder="Enter state">
                        </div>
                        <div class="form-group">
                            <label>PIN / ZIP Code</label>
                            <input type="text" name="billing_zip_code" value="{{ $customer->billing_zip_code ?? '' }}" placeholder="560001">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="billing_country" value="{{ $customer->billing_country ?? 'India' }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Billing Address</button>
                    </div>
                </form>
            </div>
            
            {{-- Security Tab --}}
            <div class="tab-pane" id="security">
                <form method="POST" action="{{ route('website.account.password') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required placeholder="Enter current password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" required minlength="6" placeholder="Min 6 characters">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" required placeholder="Confirm password">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const panes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));
            
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });
});
</script>
@endsection

@section('styles')
<style>
.account-page{min-height:100vh;background:#fff}
.account-container{max-width:680px;margin:0 auto;padding:24px 20px 60px}

.account-header{display:flex;align-items:center;gap:16px;padding:20px 0 24px;border-bottom:1px solid #e5e7eb}
.back-btn{width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;color:#64748b;text-decoration:none;transition:all .15s}
.back-btn:hover{background:#f1f5f9;color:#1e293b}
.header-info{display:flex;align-items:center;gap:14px;flex:1}
.avatar{width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#8b5cf6);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:600}
.header-info h1{font-size:18px;font-weight:600;color:#1e293b;margin:0 0 2px}
.header-info p{font-size:13px;color:#64748b;margin:0}
.logout-form{margin:0}
.logout-btn{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:#fef2f2;border:none;border-radius:10px;color:#ef4444;cursor:pointer;transition:all .15s}
.logout-btn:hover{background:#fee2e2}

.quick-links{display:flex;flex-direction:column;gap:10px;margin:20px 0;padding-bottom:20px;border-bottom:1px solid #e5e7eb}
.quick-link{display:flex;align-items:center;gap:14px;padding:16px;background:#fafbfc;border-radius:12px;text-decoration:none;transition:all .2s}
.quick-link:hover{background:#f1f5f9;transform:translateX(4px)}
.quick-link .link-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:12px;flex-shrink:0}
.orders-link .link-icon{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
.wishlist-link .link-icon{background:linear-gradient(135deg,#f472b6,#ec4899);color:#fff}
.quick-link .link-info{flex:1}
.quick-link .link-title{display:block;font-size:15px;font-weight:600;color:#1e293b}
.quick-link .link-desc{display:block;font-size:13px;color:#64748b;margin-top:2px}
.quick-link .link-arrow{color:#94a3b8;transition:transform .2s}
.quick-link:hover .link-arrow{transform:translateX(4px);color:#3b82f6}

.alert{display:flex;align-items:center;gap:10px;padding:14px 16px;border-radius:10px;margin:20px 0;font-size:14px}
.alert.success{background:#f0fdf4;color:#166534}
.alert.error{background:#fef2f2;color:#dc2626}

.tabs{display:flex;gap:4px;padding:20px 0;overflow-x:auto;-webkit-overflow-scrolling:touch}
.tabs::-webkit-scrollbar{display:none}
.tab{display:flex;align-items:center;gap:8px;padding:10px 16px;background:none;border:none;border-radius:8px;font-size:14px;font-weight:500;color:#64748b;cursor:pointer;white-space:nowrap;transition:all .15s}
.tab:hover{background:#f1f5f9;color:#1e293b}
.tab.active{background:#eff6ff;color:#3b82f6}
.tab svg{flex-shrink:0}

.tab-pane{display:none;padding:8px 0}
.tab-pane.active{display:block}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:0}
.form-group.full{grid-column:1/-1}
.form-group label{display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px}
.form-group input,.form-group textarea{width:100%;padding:12px 14px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;font-size:14px;color:#1e293b;transition:all .15s}
.form-group input:focus,.form-group textarea:focus{outline:none;background:#fff;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.form-group input:disabled{background:#f1f5f9;color:#64748b;cursor:not-allowed}
.form-group textarea{resize:vertical;min-height:70px}
.input-hint{display:block;font-size:11px;color:#94a3b8;margin-top:4px}

.form-actions{margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9}
.btn-primary{padding:12px 24px;background:#3b82f6;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;transition:all .15s}
.btn-primary:hover{background:#2563eb}

@media(max-width:640px){
    .form-grid{grid-template-columns:1fr}
    .form-group.full{grid-column:1}
    .tabs{gap:2px}
    .tab{padding:10px 12px;font-size:13px}
    .tab span{display:none}
    .quick-links{gap:8px}
    .quick-link{padding:14px}
}
</style>
@endsection
